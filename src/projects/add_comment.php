<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in to comment.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$project_id = $_POST['project_id'] ?? null;
$comment = $_POST['comment'] ?? null;

// Validate data
if (!$project_id || !$comment || trim($comment) === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    exit;
}

// Check if project exists
$stmt = $pdo->prepare("SELECT id FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Project not found.']);
    exit;
}

try {
    // Insert comment
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, project_id, comment) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $project_id, trim($comment)]);
    
    // Get the newly created comment with user information including profile_photo
    $comment_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("
        SELECT c.*, u.username, u.profile_photo
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.id = ?
    ");
    $stmt->execute([$comment_id]);
    $newComment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return success response with comment data
    echo json_encode([
        'success' => true,
        'id' => $newComment['id'],
        'username' => $newComment['username'],
        'profile_photo' => $newComment['profile_photo'],
        'comment' => htmlspecialchars($newComment['comment']),
        'created_at' => $newComment['created_at']
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
    exit;
}
