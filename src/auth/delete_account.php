<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'You must be logged in to delete your account.';
    header('Location: ../../public/login.php');
    exit;
}

// Check if password was submitted
if (!isset($_POST['password']) || empty($_POST['password'])) {
    $_SESSION['error'] = 'Password is required to confirm account deletion.';
    header('Location: ../../public/profile.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$password = $_POST['password'];

try {
    // Get user's hashed password from database
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $_SESSION['error'] = 'User not found.';
        header('Location: ../../public/profile.php');
        exit;
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Incorrect password. Account deletion cancelled.';
        header('Location: ../../public/profile.php');
        exit;
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Delete user's data
    // Note: The foreign key constraints with ON DELETE CASCADE should handle related records
    // But we'll explicitly delete relationships anyway to be thorough
    
    // Delete bookmarks
    $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    // Delete likes
    $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    // Delete comments
    $stmt = $pdo->prepare("DELETE FROM comments WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    // Get list of user's projects to delete files
    $stmt = $pdo->prepare("SELECT thumbnail, project_file FROM projects WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $projects = $stmt->fetchAll();
    
    // Delete projects
    $stmt = $pdo->prepare("DELETE FROM projects WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    // Delete user
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    // Commit transaction
    $pdo->commit();
    
    // Delete project files
    foreach ($projects as $project) {
        if (!empty($project['thumbnail'])) {
            @unlink(__DIR__ . "/../../uploads/" . $project['thumbnail']);
        }
        if (!empty($project['project_file'])) {
            @unlink(__DIR__ . "/../../uploads/" . $project['project_file']);
        }
    }
    
    // Delete profile photo if exists
    $stmt = $pdo->prepare("SELECT profile_photo FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
    
    if ($profile && !empty($profile['profile_photo'])) {
        @unlink(__DIR__ . "/../../uploads/profile_photos/" . $profile['profile_photo']);
    }
    
    // Clear session and redirect to login
    session_destroy();
    session_start();
    $_SESSION['success'] = 'Your account has been permanently deleted.';
    header('Location: ../../public/login.php');
    exit;
    
} catch (PDOException $e) {
    // Roll back transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    $_SESSION['error'] = 'Error deleting account: ' . $e->getMessage();
    header('Location: ../../public/profile.php');
    exit;
}
?>
