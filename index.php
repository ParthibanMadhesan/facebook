<?php

session_start();


if (isset($_SESSION['email'])) {
    header('Location:views/dashboard.php');
    exit();
}

require "login.view.php";

require "Database.php";






