<?php
session_start();
include 'db.php'; // Include database connection

// Define maximum file size (e.g., 5 MB)
define('MAX_FILE_SIZE', 5 * 1024 * 1024); 
// Check if a user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);

$stmt->bindParam(':id', $userId, PDO::PARAM_INT);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('User not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Handle file uploads
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['profile_picture']['size'] > MAX_FILE_SIZE) {
            $errors[] = 'Profile picture file is too large. Maximum file size is 5 MB.';
        } else {
            $profilePicture = 'uploads/' . basename($_FILES['profile_picture']['name']);
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePicture)) {
                $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->execute([$profilePicture, $userId]);
            } else {
                $errors[] = 'Failed to upload profile picture.';
            }
        }
    }

    if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['background_image']['size'] > MAX_FILE_SIZE) {
            $errors[] = 'Background image file is too large. Maximum file size is 5 MB.';
        } else {
            $backgroundImage = 'uploads/' . basename($_FILES['background_image']['name']);
            if (move_uploaded_file($_FILES['background_image']['tmp_name'], $backgroundImage)) {
                $stmt = $pdo->prepare("UPDATE users SET background_image = ? WHERE id = ?");
                $stmt->execute([$backgroundImage, $userId]);
            } else {
                $errors[] = 'Failed to upload background image.';
            }
        }
    }

    // Handle bio update
    $bio = $_POST['bio'];
    $stmt = $pdo->prepare("UPDATE users SET bio = ? WHERE id = ?");
    $stmt->execute([$bio, $userId]);

    // Refresh user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['name']); ?>'s Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="profile-container">
        <header class="profile-header" style="background-image: url('<?php echo htmlspecialchars($user['background_image']); ?>');">
            <div class="profile-picture-container">
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="<?php echo htmlspecialchars($user['name']); ?>'s Profile Picture" class="profile-picture">
            </div>
            <h1><?php echo htmlspecialchars($user['name']); ?></h1>
        </header>
        <section class="profile-bio">
            <p><?php echo htmlspecialchars($user['bio']); ?></p>
        </section>
        <section class="profile-update">
            <h2>Update Profile</h2>
            <form action="profile.view.php" method="post" enctype="multipart/form-data">
                <label for="profile_picture">Change Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture">
                <br>
                <label for="background_image">Change Background Image:</label>
                <input type="file" name="background_image" id="background_image">
                <br>
                <label for="bio">Update Bio:</label>
                <textarea name="bio" id="bio" rows="4" cols="50"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                <br>
                <input type="submit" value="Update Profile">
            </form>
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
