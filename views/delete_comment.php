<?php
include '../config.php'; 

session_start(); 

// Check if the user is logged in and get their user ID
if (!isset($_SESSION['user_id'])) {
    echo 'User not logged in';
    exit;
}

$loggedInUserId = intval($_SESSION['user_id']);

if (!isset($_GET['comment_id']) || !isset($_GET['post_id'])) {
    echo 'Missing comment ID or post ID';
    exit;
}

$commentId = intval($_GET['comment_id']);
$postId = intval($_GET['post_id']);

// Fetch the comment to check ownership
$stmt = $pdo->prepare("SELECT user_id FROM comments WHERE id = ?");
$stmt->execute([$commentId]);
$comment = $stmt->fetch();

if (!$comment) {
    echo 'Comment not found';
    exit;
}

// Ensure the logged-in user is the owner of the comment
if ($loggedInUserId != $comment['user_id']) {
    echo 'Unauthorized';
    exit;
}

// Proceed with deletion
$stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
$result = $stmt->execute([$commentId]);

if ($result) {
    echo 'Comment deleted successfully';
} else {
    echo 'Failed to delete comment';
}

// Redirect back to the post page
header("Location: dashboard.php?post_id=$postId");
exit;
?>
