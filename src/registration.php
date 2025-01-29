<?php
// registration.php

require_once('connection.php');
require_once('../vendor/autoload.php'); // Include Composer's autoloader

use App\UserRegistration;

// Create an instance of UserRegistration
$userRegistration = new UserRegistration($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $firstname = ucfirst(filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS));
    $lastname = ucfirst(filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = strtolower(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS));
    $phone = strtolower(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
    $password = filter_input(INPUT_POST, 'pswd1', FILTER_SANITIZE_SPECIAL_CHARS);
    $confirmPassword = filter_input(INPUT_POST, 'pswd2', FILTER_SANITIZE_SPECIAL_CHARS);

    try {
        // Register the user
        if ($userRegistration->registerUser($firstname, $lastname, $email, $phone, $password, $confirmPassword)) {
            echo "User Registered Successfully";
        }
    } catch (\Exception $e) {
        echo $e->getMessage(); // Display error message
    }
}

$conn->close();
