<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit;
}

if (!isset($_POST['project_id']) || empty($_POST['project_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No project specified'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$project_id = intval($_POST['project_id']);

try {
    // Check if the project exists
    $stmt = $pdo->prepare("SELECT id FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Project does not exist'
        ]);
        exit;
    }
    
    // Check if already bookmarked
    $stmt = $pdo->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND project_id = ?");
    $stmt->execute([$user_id, $project_id]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Remove bookmark
        $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE user_id = ? AND project_id = ?");
        $stmt->execute([$user_id, $project_id]);
        
        echo json_encode([
            'status' => 'unbookmarked',
            'message' => 'Project removed from bookmarks'
        ]);
    } else {
        // Add bookmark
        $stmt = $pdo->prepare("INSERT INTO bookmarks (user_id, project_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $project_id]);
        
        echo json_encode([
            'status' => 'bookmarked',
            'message' => 'Project bookmarked successfully'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
