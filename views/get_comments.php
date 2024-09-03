<?php
include '../config.php'; // Include your database connection file

session_start(); // Start session to access logged-in user data

// Check if the user is logged in and get their user ID
if (!isset($_SESSION['user_id'])) {
    echo 'User not logged in';
    exit;
}

$loggedInUserId = intval($_SESSION['user_id']);

if (!isset($_GET['post_id'])) {
    echo 'No post ID specified';
    exit;
}

$postId = intval($_GET['post_id']);

// Fetch comments
$stmt = $pdo->prepare("SELECT c.*, u.firstname, u.lastname FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at desc");
$stmt->execute([$postId]);
$comments = $stmt->fetchAll();

foreach ($comments as $comment) {
    echo '<div class="p-2 bg-gray-100 rounded mb-2">';
    echo '<p class="font-bold text-gray-800">' . htmlspecialchars($comment['firstname'] . ' ' . $comment['lastname']) . '</p>';
    echo '<p class="text-gray-600">' . htmlspecialchars($comment['content']) . '</p>';
    echo '<p class="text-gray-400 text-sm">' . date('F j, Y, g:i a', strtotime($comment['created_at'])) . '</p>';
    
    // Check if the logged-in user is the owner of the comment
    if ($loggedInUserId == $comment['user_id']) {
        echo '<a href="delete_comment.php?comment_id=' . intval($comment['id']) . '&post_id=' . $postId . '" class="text-red-500 hover:text-red-700">Delete</a>';
    }
    
    echo '</div>';
}
?>


