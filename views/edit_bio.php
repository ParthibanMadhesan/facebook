<?php
session_start();
include '../config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

// Handle bio update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bio'])) {
        $bio = trim($_POST['bio']);

        // Update bio in the database
        $stmt = $pdo->prepare("UPDATE users SET bio = ? WHERE id = ?");
        $stmt->execute([$bio, $userId]);

        header('Location: profiles.view.php');
        exit();
    }
}

// Fetch current bio
$stmt = $pdo->prepare("SELECT bio FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
$currentBio = $user['bio'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bio</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-xl font-semibold mb-4">Edit Bio</h1>
            <form action="edit_bio.php" method="post">
                <div class="mb-4">
                    <label for="bio" class="block text-gray-700">Bio:</label>
                    <textarea name="bio" id="bio" rows="5" class="mt-2 block w-full border border-gray-300 rounded-md"><?php echo htmlspecialchars($currentBio); ?></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Bio</button>
            </form>
        </div>
    </div>
</body>
</html>
