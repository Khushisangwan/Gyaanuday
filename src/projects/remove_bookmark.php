<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit;
}

// Check if bookmark_id is provided
if (!isset($_POST['bookmark_id']) || empty($_POST['bookmark_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No bookmark specified'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$bookmark_id = intval($_POST['bookmark_id']);

try {
    // Verify the bookmark belongs to the user
    $stmt = $pdo->prepare("SELECT id FROM bookmarks WHERE id = ? AND user_id = ?");
    $stmt->execute([$bookmark_id, $user_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'Bookmark not found or not owned by you'
        ]);
        exit;
    }
    
    // Delete the bookmark
    $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE id = ?");
    $stmt->execute([$bookmark_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Bookmark removed successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
