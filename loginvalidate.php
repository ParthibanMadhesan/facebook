<?php


include "config.php";
include "functions.php";

$errors = [];
$data = [
    'email' => '',
    'password' => ''
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required";
    } else {
        $data['email'] = sanitizeinput($_POST['email']);
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $errors['password'] = "Password is required";
    } else {
        $data['password'] = sanitizeinput($_POST["password"]);
    }

    if (empty($errors)) {
        try {
            $sql = "SELECT password ,id,profile_picture,firstname FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $data['email']);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && password_verify($data['password'], $result['password']) && $result['id']) {
                session_start();
                 $_SESSION['user_id'] = $result['id']; 
                 $_SESSION['firstname'] = $result['firstname'];  
                $_SESSION['email'] = $data['email'];
                $_SESSION['profile_picture'] = $result['profile_picture'];  
                

    
                header("Location: views/dashboard.php");
                exit();
            } else {
                // Redirect with login error
                header("Location: login.view.php?error=login_failed");
                exit();
            }

        } catch (PDOException $e) {
            echo "<h2>Database Error</h2>";
            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        // Redirect with validation errors
        $errorType = '';
        if (isset($errors['email'])) {
            $errorType = $errors['email'] === "Email is required" ? 'email_required' : 'email_invalid';
        } elseif (isset($errors['password'])) {
            $errorType = 'password_required';
        }
        header("Location: login.view.php?error=$errorType");
        exit();
    }
}
?>
