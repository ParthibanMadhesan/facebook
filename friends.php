<?php

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
}




if($_SERVER['REQUEST_METHOD']=='GET'){
   
    header("location:views/friends.view.php");
    exit();

}