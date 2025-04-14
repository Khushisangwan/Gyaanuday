<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You need to be logged in to perform this action.";
    header("Location: /gyaanuday/public/login.php");
    exit;
}

// Check if project ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid project ID.";
    header("Location: /gyaanuday/public/profile.php");
    exit;
}

$project_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// First verify that the project belongs to the current user
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->execute([$project_id, $user_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    $_SESSION['error'] = "You don't have permission to delete this project or the project doesn't exist.";
    header("Location: /gyaanuday/public/profile.php");
    exit;
}

try {
    // Begin a transaction as we might need to delete related data in the future
    $pdo->beginTransaction();
    
    // Delete any project images if they exist
    if (!empty($project['image_url']) && strpos($project['image_url'], '../uploads/') === 0) {
        $image_path = __DIR__ . '/../../' . substr($project['image_url'], 3);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // Delete the project from the database
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    
    // Commit the transaction
    $pdo->commit();
    
    $_SESSION['success'] = "Project successfully deleted.";
} catch (PDOException $e) {
    // Roll back the transaction if something failed
    $pdo->rollBack();
    $_SESSION['error'] = "Error deleting project: " . $e->getMessage();
}

header("Location: /gyaanuday/public/profile.php");
exit;
?>
