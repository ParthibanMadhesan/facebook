<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}


$_SESSION['notification'] = 'Post creation canceled.';

header('Location: profiles.view.php');
exit();
