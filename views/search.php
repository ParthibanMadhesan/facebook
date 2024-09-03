<?php
// search.php
header('Content-Type: application/json');
include '../config.php'; // Replace with your actual DB connection file

$query = isset($_GET['q']) ? $_GET['q'] : '';
$results = [];

if ($query) {
    $stmt = $pdo->prepare("SELECT  firstname,email FROM users WHERE firstname LIKE ? LIMIT 10");
    $stmt->execute(['%' . $query . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($results);
?>
