<?php
session_start();
include '../config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

// Handle profile picture and cover photo update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateProfilePic = false;
    $updateCoverPhoto = false;

    // Handle profile picture update
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = basename($_FILES['profile_picture']['name']);
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
            $uniqueFileName = uniqid('profile_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $uploadDir = 'uploads/';
            $profilePicture = $uploadDir . $uniqueFileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($fileTmpPath, $profilePicture)) {
                $updateProfilePic = true;
                $profilePicturePath = $profilePicture;
            } else {
                $error = 'Failed to move uploaded profile picture.';
            }
        } else {
            $error = 'Invalid file type or size for profile picture.';
        }
    }

    // Handle cover photo update
    if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['cover_photo']['tmp_name'];
        $fileName = basename($_FILES['cover_photo']['name']);
        $fileSize = $_FILES['cover_photo']['size'];
        $fileType = $_FILES['cover_photo']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
            $uniqueFileName = uniqid('cover_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $uploadDir = 'uploads/';
            $coverPhoto = $uploadDir . $uniqueFileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($fileTmpPath, $coverPhoto)) {
                $updateCoverPhoto = true;
                $coverPhotoPath = $coverPhoto;
            } else {
                $error = 'Failed to move uploaded cover photo.';
            }
        } else {
            $error = 'Invalid file type or size for cover photo.';
        }
    }

    if ($updateProfilePic || $updateCoverPhoto) {
        $sql = "UPDATE users SET ";
        $params = [];

        if ($updateProfilePic) {
            $sql .= "profile_picture = ?, ";
            $params[] = $profilePicturePath;
        }

        if ($updateCoverPhoto) {
            $sql .= "cover_photo = ? ";
            $params[] = $coverPhotoPath;
        }

        $sql .= "WHERE id = ?";
        $params[] = $userId;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header('Location: profiles.view.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile Picture and Cover Photo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-xl font-semibold mb-4">Update Profile Picture and Cover Photo</h1>
            <?php if (isset($error)): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="edit_profile_picture.php" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="profile_picture" class="block text-gray-700">Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="mt-2 block w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="cover_photo" class="block text-gray-700">Cover Photo:</label>
                    <input type="file" name="cover_photo" id="cover_photo" class="mt-2 block w-full border border-gray-300 rounded-md">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
