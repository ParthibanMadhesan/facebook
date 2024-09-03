<?php
session_start();
include '../config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

// Function to create a notification
function createNotification($pdo, $userId, $message) {
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$userId, $message]);
}

// Function to get user's friends
function getUserFriends($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT CASE 
    WHEN sender_id = ? THEN receiver_id
    ELSE sender_id
END AS friend_id
FROM friend_requests
WHERE (sender_id = ? OR receiver_id = ?) AND status = 'accepted'");
$stmt->execute([$userId, $userId, $userId]);
return $stmt->fetchAll(PDO::FETCH_COLUMN);

}

// Function to notify friends about new post
function notifyFriendsAboutNewPost($pdo, $userId, $friendIds) {
    $user = $pdo->query("SELECT firstname FROM users WHERE id = $userId")->fetch(PDO::FETCH_ASSOC);
    $firstName = htmlspecialchars($user['firstname']);
    
    // Create a clickable link to the user's profile page
    $profileLink = "<a href='dashboard.php?id=$userId' class='text-red-800>$firstName</a>";
    
    // Construct the notification message with the link
    $message = "$profileLink has created a new post!";
    
    
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
    foreach ($friendIds as $friendId) {
            $stmt->execute([$friendId, $message]);
        }
}

// Handle post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    $photoPath = null;
    $error = null;

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = basename($_FILES['photo']['name']);
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
            $uniqueFileName = uniqid('post_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $uploadDir = 'uploads/';
            $photoPath = $uploadDir . $uniqueFileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($fileTmpPath, $photoPath)) {
                // File upload succeeded
            } else {
                $error = 'Failed to move uploaded photo.';
            }
        } else {
            $error = 'Invalid file type or size for photo.';
        }
    }

    if (!empty($content) || $photoPath) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, photo, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$userId, $content, $photoPath])) {
            // Create notification for the user
            createNotification($pdo, $userId, "You created a new post!");
            
            // Get user's friends and notify them
            $friendIds = getUserFriends($pdo, $userId);
            notifyFriendsAboutNewPost($pdo, $userId, $friendIds);
            
            // Set success notification in session
            $_SESSION['notification'] = 'Post added successfully!';
            $_SESSION['post_created'] = true; // Set flag for new post creation
        } else {
            // Set error notification in session
            $_SESSION['notification'] = 'Failed to add post. Please try again.';
        }
        header('Location: dashboard.php'); // Redirect to the dashboard or profile page
        exit();
    } else {
        $error = 'Post content and/or photo cannot be empty.';
    }
}
?>

<!DOCTYPE html>
<!-- ... (rest of the HTML remains the same) ... -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Post</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .cancel-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <?php include "../Partials/navbar.php"; ?>

    <div class="flex justify-center items-center min-h-screen">
        <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <!-- Cancel Icon -->
            <i class="fas fa-times cancel-icon text-red-600 hover:text-gray-900" onclick="window.location.href='cancel_post.php';"></i>

            <h1 class="text-xl font-semibold mb-4">Create a Post</h1>
            <?php if (isset($error)): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="create_post.php" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <textarea name="content" rows="4" class="w-full border border-gray-300 rounded-md p-2" placeholder="What's on your mind?" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="photo" class="block text-gray-700">Add a Photo:</label>
                    <input type="file" name="photo" id="photo" class="mt-2 block w-full border border-gray-300 rounded-md">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Post</button>
            </form>
        </div>
    </div>

</body>
</html>
