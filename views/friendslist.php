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
        .friend-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px;
        }
        .friend-card:last-child {
            border-bottom: none;
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        .friend-info {
            display: flex;
            flex-direction: column;
        }
        .friend-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .delete-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 1.5rem;
        }
    </style>
        <script>
        function confirmDelete(event) {
            if (!confirm('Are you sure you want to remove this friend?')) {
                event.preventDefault();
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <?php include "../Partials/navbar.php"; ?>

    <div class="container">

        <div class="card">
            <h2 class="text-xl font-semibold mb-2">Your Friends:</h2>
            <div>
                <?php
                // Fetch and display friends
                $stmt = $pdo->prepare("
                    SELECT DISTINCT u.id, u.firstname, u.lastname, u.profile_picture
                    FROM friendships f
                    JOIN users u ON (f.user1_id = u.id OR f.user2_id = u.id)
                    WHERE (f.user1_id = :user_id OR f.user2_id = :user_id) AND u.id != :user_id
                ");
                $stmt->execute(['user_id' => $user_id]);
                $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($friends)) {
                    foreach ($friends as $friend) {
                        
                        // Default profile picture if none provided

                        $profilePic = $friend['profile_picture'] ? $friend['profile_picture'] : 'default-avatar.png';
                        $fullName = "{$friend['firstname']} {$friend['lastname']}";
                        $profileUrl = "friend_profile.php?friend_id={$friend['id']}";   
                               
                        echo "<div class='friend-card'>
                            <div style='display: flex; align-items: center;'>
                            <a href='{$profileUrl}'><img src='{$profilePic}' alt='Profile Picture' class='profile-img'></a>
                                <div class='friend-info'>
                                    <span class='friend-name'>{$fullName}</span>
                                </div>
                            </div>
                            <form action='delete_friend.php' method='POST' onsubmit='confirmDelete(event)'>
                                <input type='hidden' name='friend_id' value='{$friend['id']}'>
                                <button type='submit' class='bg-red-500 rounded-md p-2'>
                                   remove
                                </button>
                            </form>
                        </div>";
                    }
                } else {
                    echo "<p class='p-2'>You have no friends yet.</p>";
                }
                ?>
            </div>
        </div>

    </div>
</body>
</html>
