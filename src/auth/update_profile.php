<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to update your profile.";
    header("Location: ../../public/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
    $original_username = isset($_POST['original_username']) ? trim($_POST['original_username']) : '';
    
    // Validate username
    if (empty($original_username)) {
        $_SESSION['error'] = "Username cannot be empty.";
        header("Location: ../../public/profile.php");
        exit;
    }
    
    // Only update the bio, don't change the username
    $sql = "UPDATE users SET bio = ? WHERE id = ?";
    $params = [$bio, $user_id];
    
    // Handle profile photo upload if provided
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['profile_photo']['type'], $allowed_types)) {
            $_SESSION['error'] = "Only JPG and PNG files are allowed.";
            header("Location: ../../public/profile.php");
            exit;
        }
        
        if ($_FILES['profile_photo']['size'] > $max_size) {
            $_SESSION['error'] = "File size must be less than 5MB.";
            header("Location: ../../public/profile.php");
            exit;
        }
        
        // Create uploads directory if it doesn't exist
        $upload_dir = __DIR__ . "/../../uploads/profile_photos/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Generate a unique filename
        $filename = $user_id . '_' . time() . '_' . $_FILES['profile_photo']['name'];
        $target_file = $upload_dir . $filename;
        
        // Move the uploaded file
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
            $sql = "UPDATE users SET bio = ?, profile_photo = ? WHERE id = ?";
            $params = [$bio, $filename, $user_id];
        } else {
            $_SESSION['error'] = "Failed to upload file.";
            header("Location: ../../public/profile.php");
            exit;
        }
    }
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Profile updated successfully!";
        } else {
            $_SESSION['info'] = "No changes were made to your profile.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
    
    // Redirect back to profile page
    header("Location: ../../public/profile.php");
    exit;
}
?>