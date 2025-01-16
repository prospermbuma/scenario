<?php

// Database Connection
require_once('connection.php');

/*===============================================================
# Table Creation
===============================================================*/
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(255) NOT NULL,
    pswd VARCHAR(255) NOT NULL,
    date_registered TIMESTAMP NOT NULL
)";

if (!$conn->query($sql)) {
    die("Table creation failed: " . $conn->error);
}
/*===============================================================
# User registration
===============================================================*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // INPUTS
    $first_name = ucfirst(filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS));
    $last_name = ucfirst(filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = strtolower(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS));
    $phone = strtolower(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
    $pswd_1 = filter_input(INPUT_POST, 'pswd1', FILTER_SANITIZE_SPECIAL_CHARS);
    $pswd_2 = filter_input(INPUT_POST, 'pswd2', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($phone) && !empty($pswd_1) && !empty($pswd_2)) {
        if ($pswd_2 != $pswd_1) {
            echo "Password mismatch";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address.";
        } else {
            // Check if the email exists
            $sql = "SELECT email FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $select_result = $stmt->get_result();

            // If email does not exist then insert new data
            if ($select_result->num_rows === 0) {
                $query = "INSERT INTO users (firstname, lastname, email, phone, pswd, date_registered) VALUES (?, ?, ?, ?, SHA1(?), NOW())";
                $insert_stmt = $conn->prepare($query);
                $insert_stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $pswd_2);

                if ($insert_stmt->execute()) {
                    echo "Registered successfully";
                } else {
                    die("Not saved: " . $conn->error);
                }

                $insert_stmt->close();
            } else {
                echo "Sorry, user already taken";
            }

            // Free result
            $select_result->free();
            $stmt->close();
        }
    } else {
        echo "Fields cannot be blank";
    }
}
$conn->close();
