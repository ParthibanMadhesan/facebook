<?php

session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['post_id'])) {
    die(json_encode(['success' => false, 'error' => 'Invalid request']));
}

$userId = $_SESSION['user_id'];
$postId = intval($_POST['post_id']);

// Check if the post exists
$stmt = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
$stmt->execute([$postId]);
if (!$stmt->fetch()) {
    die(json_encode(['success' => false, 'error' => 'Post not found']));
}

try {
    $pdo->beginTransaction();

    // Check if the user has already liked the post
    $stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
    $stmt->execute([$userId, $postId]);
    $existingLike = $stmt->fetch();

    if ($existingLike) {
        // Unlike the post
        $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
    } else {
        // Like the post
        $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
        $stmt->execute([$userId, $postId]);
    }

    // Get the new like count
    $stmt = $pdo->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ?");
    $stmt->execute([$postId]);
    $result = $stmt->fetch();

    $pdo->commit();

    echo json_encode(['success' => true, 'likeCount' => $result['like_count']]);
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Like error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
}