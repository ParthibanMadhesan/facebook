<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.view.php');
    exit();
}

$user_id = $_SESSION['user_id'];



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .friend-requests {
            list-style-type: none;
            padding: 0;
        }
        .friend-requests li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        button {
            padding: 5px 10px;$userid= $_SESSION['id'];
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .accept {
            background-color: #28a745;
            color: white;
        }
        .accept:hover {
            background-color: #218838;
        }
        .reject {
            background-color: #dc3545;
            color: white;
        }
        .reject:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Friend Requests</h1>
        <a href="home.php">Back to Home</a>
        
        <h2>Your Friend Requests:</h2>
        <ul class="friend-requests">
            <?php
            // Fetch and display friend requests
            $stmt = $pdo->prepare("
                SELECT fr.id, u.email
                FROM friend_requests fr
                JOIN users u ON fr.sender_id = u.id
                WHERE fr.receiver_id = :user_id AND fr.status = 'pending'
            ");
            $stmt->execute(['user_id' => $user_id]);
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($requests)) {
                foreach ($requests as $request) {
                    echo "<li>{$request['email']}
                    <form method='post' action='respond_request.php' style='display:inline;'>
                        <input type='hidden' name='request_id' value='{$request['id']}'>
                        <button type='submit' name='action' value='accept' class='accept'>Accept</button>
                        <button type='submit' name='action' value='reject' class='reject'>Reject</button>
                    </form>
                    </li>";
                }
            } else {
                echo "<li>No friend requests.</li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>
