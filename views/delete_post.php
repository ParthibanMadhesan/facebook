<?php
session_start();
include '../config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid post ID.');
}

$postId = intval($_GET['id']);

// Fetch the post to verify ownership
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$postId, $userId]);
$post = $stmt->fetch();

if (!$post) {
    die('Post not found or you do not have permission to delete this post.');
}

// Begin a transaction
$pdo->beginTransaction();

try {
    // Delete related likes first
    $stmt = $pdo->prepare("DELETE FROM likes WHERE post_id = ?");
    $stmt->execute([$postId]);

    // Delete the post
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$postId]);

    // Optionally, delete the photo from the server if it exists
    if ($post['photo'] && file_exists($post['photo'])) {
        unlink($post['photo']);
    }

    // Commit the transaction
    $pdo->commit();

    $_SESSION['notification'] = 'Post deleted successfully!';
} catch (Exception $e) {
    // Roll back the transaction if something failed
    $pdo->rollBack();
    $_SESSION['notification'] = 'Failed to delete post. Please try again.';
}

header('Location: profiles.view.php'); // Redirect to the profile or posts page
exit();
