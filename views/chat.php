<?php 
session_start();
include '../config.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: ../login.view.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['send'])) {
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $profile_picture = $_POST['profile_picture'];
    $message = $_POST['message'];

    // Prepare SQL query using PDO
    $sql = "INSERT INTO message (firstname, email,profile_picture, message) VALUES (:firstname, :email, :profile_picture, :message)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':profile_picture', $profile_picture);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }

    header("Location: chat.php");
    exit();
}

$sql1 = "SELECT firstname, email, message, DATE_FORMAT(time, '%M %e at %l:%i %p') AS time2, profile_picture FROM message";
$stmt1 = $pdo->query($sql1);
$messages = $stmt1->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            background-color: #F0F2F5;
        }
        .message-box img {
            width: 20px; 
            height: 20px; 
            border-radius: 50%;
        }
        .img-fluid {
            max-width: 100%;
            height: 35px;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col h-screen">
    <!-- Navbar -->
    <?php include "../Partials/navbar.php"; ?>

    <!-- Main Content -->
    <div class="flex flex-1">
        <!-- Sidebar -->
        <?php include "../Partials/sidebarc.php"; ?>

        <div class="container mx-auto my-8 p-4 bg-white rounded-lg shadow-lg">
            <div class="flex flex-col h-full">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <img src="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="User Image" class="w-10 h-10 rounded-full">
                        <span class="font-bold"><?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                    </div>
                    <a href="logout.php" class="btn btn-danger text-white bg-red-500 hover:bg-red-600 py-2 px-4 rounded">Logout</a>
                </div>
                

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4">
                    <?php 
                    foreach ($messages as $row) {
                        $isUserMessage = $row['email'] == $_SESSION['email'];
                    ?>
                        <div class="flex <?php echo $isUserMessage ? 'justify-end' : 'justify-start'; ?> mb-4">
                            <?php if (!$isUserMessage) { ?>
                                <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="User Image" class="w-8 h-8 rounded-full mr-2 mt-3">
                            <?php } ?>
                            <div class="<?php echo $isUserMessage ? 'bg-blue-500 text-white' : 'bg-gray-200'; ?> p-3 rounded-lg max-w-xl">
                                <?php echo htmlspecialchars($row['message']); ?>
                                <div class="text-xs text-gray-600 mt-1"><?php echo htmlspecialchars($row['time2']); ?></div>
                                
                            </div>
                        </div>
                    <?php 
                    }
                    ?>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-200">
                    <form action="chat.php" method="POST" class="flex items-center space-x-3">
                        <input type="text" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg" name="message" placeholder="Write message" required>
                        <input type="hidden" name="firstname" value="<?php echo htmlspecialchars($_SESSION['firstname']); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
                        <input type="hidden" name="profile_picture" value="<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>">
                        <button type="submit" name="send" class="bg-blue-500 text-white hover:bg-blue-600 py-2 px-4 rounded-lg flex items-center">
                            <img src="" alt="Send" class="w-5 h-5">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
