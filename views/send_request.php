<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'])) {
    $sender_id = (int) $_SESSION['user_id'];
    $receiver_id = (int)$_POST['receiver_id'];

    if ($sender_id === $receiver_id) {
        die('You cannot send a friend request to yourself.');
    }
    
    try {
        // Check if request already exists
        $stmt = $pdo->prepare("
            SELECT * FROM friend_requests 
            WHERE sender_id = :sender_id AND receiver_id = :receiver_id
        ");
        $stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id]);
        if ($stmt->rowCount() > 0) {
            die('Friend request already sent.');
        }

        // Insert new request
        $stmt = $pdo->prepare("
            INSERT INTO friend_requests (sender_id, receiver_id) 
            VALUES (:sender_id, :receiver_id)
        ");
        $stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id]);

      header("location:dashboard.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
