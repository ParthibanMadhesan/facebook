<?php
session_start(); 

session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script type="text/javascript">
        // Display an alert to the user
        function showLogoutAlert() {
            alert('You have been logged out successfully.');
            // Redirect to the login page or home page after the alert
            window.location.href = 'index.php';
        }
        
        // Call the function when the page loads
        window.onload = showLogoutAlert;
    </script>
</head>
<body>
    <!-- Body content can be left empty or include a loading message -->
    <p>Logging out...</p>
</body>
</html>
