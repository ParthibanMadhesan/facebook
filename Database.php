<?php

include "config.php";

 
include "functions.php";

$errors = [];

$data = [
    'firstname' => '',
    'lastname' => '',
    'email' => '',
    'password' => '',
    'dob_day' => '',
    'dob_month' => '',
    'dob_year' => '',
    'dob' => '',
    'gender' => '',
    'other_gender_detail' => '',
    'nickname' => ''
];

try {
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            dob DATE NOT NULL,
            gender ENUM('male', 'female', 'other') NULL,
            other_gender_detail VARCHAR(100),
            nickname ENUM('peg', 'rat')  NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ";
    $pdo->exec($createTableSQL);


    
    
} catch (PDOException $e) {
    die("Failed to create table: " . $e->getMessage());
}
require "validation.php";


