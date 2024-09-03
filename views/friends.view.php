<?php
session_start();
include '../config.php';

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
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 4px;
            color: #fff;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 8px; /* Rounded corners for rectangle effect */
            object-fit: cover;
            border: 2px solid #ddd; /* Border around profile image */
            margin-right: 15px;
        }
        .friend-card {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .friend-card:last-child {
            border-bottom: none;
        }
        .friend-info {
            display: flex;
            flex-direction: column;
        }
        .friend-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .friend-card:hover {
            background-color: #f9f9f9;
        }
        .send-request-btn {
            margin-top: 10px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include "../Partials/navbar.php"; ?>

    <div class="container">
        <div class="card">
            <h1 class="text-2xl font-bold mb-4">Welcome</h1>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>

        <div class="card">
            <h2 class="text-xl font-semibold mb-2"> suggestions Friend Request:</h2>
            <ul class="list-none p-0">
                <?php
                // Fetch users who are not yet friends and have no pending friend requests
                $stmt = $pdo->prepare("
                    SELECT u.id, u.firstname, u.lastname, u.profile_picture
                    FROM users u
                    WHERE u.id != :user_id 
                    AND u.id NOT IN (
                        SELECT IF(f.user1_id = :user_id, f.user2_id, f.user1_id) 
                        FROM friendships f 
                        WHERE f.user1_id = :user_id OR f.user2_id = :user_id
                    )
                    AND u.id NOT IN (
                        SELECT fr.receiver_id 
                        FROM friend_requests fr 
                        WHERE fr.sender_id = :user_id
                    )
                ");
                $stmt->execute(['user_id' => $user_id]);
                $potential_friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($potential_friends)) {
                    foreach ($potential_friends as $user) {
                        // Default profile picture if none provided
                        $profilePic = $user['profile_picture'] ? $user['profile_picture'] : 'default-avatar.png';
                        $fullName = "{$user['firstname']} {$user['lastname']}";
                        echo "<li class='friend-card'>
                            <img src='{$profilePic}' alt='Profile Picture' class='profile-img'>
                            <div class='friend-info'>
                                <span class='friend-name'>{$fullName}</span>
                                <form method='post' action='send_request.php' class='send-request-btn'>
                                    <input type='hidden' name='receiver_id' value='{$user['id']}'>
                                    <button type='submit' class='btn btn-primary'>Send Request</button>
                                </form>
                            </div>
                        </li>";
                    }
                } else {
                    echo "<li class='p-2 border-b border-gray-300'>No users available to send a friend request.</li>";
                }
                ?>
            </ul>
        </div>

        <div class="card">
            <h2 class="text-xl font-semibold mb-2">Friend Requests:</h2>
            <ul class="list-none p-0">
                <?php
                // Fetch and display friend requests
                $stmt = $pdo->prepare("
                    SELECT fr.id, u.firstname, u.lastname, u.profile_picture
                    FROM friend_requests fr
                    JOIN users u ON fr.sender_id = u.id
                    WHERE fr.receiver_id = :user_id AND fr.status = 'pending'
                ");
                $stmt->execute(['user_id' => $user_id]);
                $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($requests)) {
                    foreach ($requests as $request) {
                        $profilePic = $request['profile_picture'] ? $request['profile_picture'] : 'default-avatar.png';
                        $fullName = "{$request['firstname']} {$request['lastname']}";
                        echo "<li class='friend-card'>
                            <img src='{$profilePic}' alt='Profile Picture' class='profile-img'>
                            <div class='friend-info'>
                                <span class='friend-name'>{$fullName}</span>
                                <form method='post' action='respond_request.php' class='send-request-btn'>
                                    <input type='hidden' name='request_id' value='{$request['id']}'>
                                    <button type='submit' name='action' value='accept' class='btn btn-success'>Accept</button>
                                    <button type='submit' name='action' value='reject' class='btn btn-danger'>Reject</button>
                                </form>
                            </div>
                        </li>";
                    }
                } else {
                    echo "<li class='p-2 border-b border-gray-300'>No friend requests.</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
