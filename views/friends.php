
<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id'])) {
    die('User not logged in.');
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT u.id, u.email
        FROM friendships f
        JOIN users u ON (f.user1_id = u.id OR f.user2_id = u.id)
        WHERE (f.user1_id = :user_id OR f.user2_id = :user_id) AND u.id != :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($friends)) {
        echo "<h2>Your Friends:</h2>";
        foreach ($friends as $friend) {
            echo "<p>{$friend['email']}</p>";
           
        }
    } else {
        echo "<p>You have no friends yet.</p>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>