<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['post_id']) || !isset($_POST['content'])) {
    die(json_encode(['success' => false, 'message' => 'Missing parameters']));
}

$userId = $_SESSION['user_id'];
$postId = intval($_POST['post_id']);
$content = trim($_POST['content']);

if (empty($content)) {
    die(json_encode(['success' => false, 'message' => 'Content cannot be empty']));
}

// Check if post_id exists
$checkPost = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE id = :post_id");
$checkPost->bindParam(':post_id', $postId, PDO::PARAM_INT);
$checkPost->execute();

if ($checkPost->fetchColumn() == 0) {
    die(json_encode(['success' => false, 'message' => 'Post does not exist']));
}

// Prepare and execute SQL statement
$sql = "INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);

    $success = $stmt->execute();
    
    echo json_encode(['success' => $success]);
} catch (PDOException $e) {
    error_log('SQL Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
