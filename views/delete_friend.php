<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $friend_id = $_POST['friend_id'];

    // Delete the friendship
    $stmt = $pdo->prepare("
        DELETE FROM friendships 
        WHERE (user1_id = :user_id AND user2_id = :friend_id) 
           OR (user1_id = :friend_id AND user2_id = :user_id)
    ");
    $stmt->execute(['user_id' => $user_id, 'friend_id' => $friend_id]);

    // Redirect back to the friends list
    header('Location: friendslist.php');
    exit();
}
?>
