<?php

$host = 'localhost';  
$dbname = 'facebook';   
$dbuser = 'root';    
$dbpass = 'Parthi@123';         

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
