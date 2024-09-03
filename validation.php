<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = "First name is required";
    } else {
        $data['firstname'] = sanitizeinput($_POST['firstname']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $data['firstname'])) {
            $errors['firstname'] = "Only letters and whitespace are allowed";
        }
    }

    if (empty($_POST['lastname'])) {
        $errors['lastname'] = "Last name is required";
    } else {
        $data['lastname'] = sanitizeinput($_POST['lastname']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $data['lastname'])) {
            $errors['lastname'] = "Only letters and whitespace are allowed";
        }
    }

    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required";  
    } else {
        $data['email'] = sanitizeinput($_POST['email']);
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        } else {
            // Check if the email already exists in the database
            $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();
            $emailCount = $stmt->fetchColumn();

            if ($emailCount > 0) {
                $errors['email'] = "Email is already registered";
            }
        }
    }

    if (empty($_POST["password"])) {
        $errors['password'] = "Password is required";
    } else {
        $data['password'] = sanitizeinput($_POST["password"]);
        if (strlen($data['password']) < 6) {
            $errors['password'] = "Password must be at least 6 characters long";
        }
    }

    if (empty($_POST["dob-day"]) || empty($_POST["dob-month"]) || empty($_POST["dob-year"])) {
        $errors['dob'] = "Date of birth is required";
    } else {
        $data['dob_day'] = (int)sanitizeinput($_POST["dob-day"]);
        $data['dob_month'] = (int)sanitizeinput($_POST["dob-month"]);
        $data['dob_year'] = (int)sanitizeinput($_POST["dob-year"]);

        if (!checkdate($data['dob_month'], $data['dob_day'], $data['dob_year'])) {
            $errors['dob'] = "Invalid date of birth";
        } else {
            // Concatenate day, month, and year into a DATE format
            $data['dob'] = sprintf('%04d-%02d-%02d', $data['dob_year'], $data['dob_month'], $data['dob_day']);
        }
    }

    if (empty($_POST["gender"])) {
        $errors['gender'] = "Gender is required";
    } else {
        $data['gender'] = sanitizeinput($_POST["gender"]);
        if ($data['gender'] === 'other') {
            $data['other_gender_detail'] = sanitizeinput($_POST["other-gender-detail"]);
        }
    }

    if (empty($_POST["nickname"])) {
        $errors['nickname'] = "Nickname is required";
    } else {
        $data['nickname'] = sanitizeinput($_POST["nickname"]);
        // Validate that the nickname matches one of the ENUM values
        if (!in_array($data['nickname'], ['peg', 'rat'])) {
            $errors['nickname'] = "Invalid nickname";
        }
    }
    
    if (empty($errors)) {
        try {
            // Hash the password before storing it
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            // Prepare an SQL statement for insertion
            $sql = "INSERT INTO users (firstname, lastname, email, password, dob, gender, other_gender_detail, nickname) 
                    VALUES (:firstname, :lastname, :email, :password, :dob, :gender, :other_gender_detail, :nickname)";
            
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':firstname', $data['firstname']);
            $stmt->bindParam(':lastname', $data['lastname']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':dob', $data['dob']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':other_gender_detail', $data['other_gender_detail']);
            $stmt->bindParam(':nickname', $data['nickname']);

            // Execute the statement
            $stmt->execute();
            
            // Redirect to login page
            header("Location: login.view.php");
            exit(); 

        } catch (PDOException $e) {
            echo "<h2>Database Error</h2>";
            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        // Display validation errors
        echo "<h2>Validation Errors</h2>";
        foreach ($errors as $error) {
            echo "<p>" . htmlspecialchars($error) . "</p>";
        }
  
    }}