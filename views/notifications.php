<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$notifications = $stmt->fetchAll();

// Mark all notifications as read
$stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
$stmt->execute([$userId]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include "../Partials/navbar.php"; ?>

    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Notifications</h1>
        <?php foreach ($notifications as $notification): ?>
            <div class="bg-white p-4 mb-4 rounded shadow">
         
                <p class="text-blue-800"><?php ECHO ($notification['message']); ?></p>
                <p class="text-sm text-gray-500 mt-2"><?php echo date('F j, Y, g:i a', strtotime($notification['created_at'])); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>