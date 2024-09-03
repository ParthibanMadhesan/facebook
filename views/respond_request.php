<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_POST['action'])) {
    $request_id = (int)$_POST['request_id'];
    $action = $_POST['action'];
    $user_id = $_SESSION['user_id'];

    try {
        // Check if request belongs to the current user
        $stmt = $pdo->prepare("
            SELECT * FROM friend_requests 
            WHERE id = :request_id AND receiver_id = :user_id
        ");
        $stmt->execute(['request_id' => $request_id, 'user_id' => $user_id]);
        if ($stmt->rowCount() === 0) {
            die('Invalid request.');
        }

        if ($action === 'accept') {
            // Update request status
            $stmt = $pdo->prepare("
                UPDATE friend_requests 
                SET status = 'accepted' 
                WHERE id = :request_id
            ");
            $stmt->execute(['request_id' => $request_id]);

            // Add friendship
            $stmt = $pdo->prepare("
                SELECT sender_id FROM friend_requests 
                WHERE id = :request_id
            ");
            $stmt->execute(['request_id' => $request_id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            $sender_id = $request['sender_id'];

            $stmt = $pdo->prepare("
                INSERT INTO friendships (user1_id, user2_id) 
                VALUES (:user1_id, :user2_id), (:user2_id, :user1_id)
            ");
            $stmt->execute(['user1_id' => $sender_id, 'user2_id' => $user_id]);
        } elseif ($action === 'reject') {
            // Update request status
            $stmt = $pdo->prepare("
                UPDATE friend_requests 
                SET status = 'rejected' 
                WHERE id = :request_id
            ");
            $stmt->execute(['request_id' => $request_id]);
        }

      header("location:/friendslist.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>




