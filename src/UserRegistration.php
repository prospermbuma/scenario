<?php

namespace App;

class UserRegistration
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function registerUser($firstname, $lastname, $email, $phone, $password, $confirmPassword)
    {
        // Validate input
        if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword)) {
            throw new \Exception("Fields cannot be blank.");
        }

        // Validate email format
        $this->validateEmail($email);

        // Check if passwords match
        $this->validatePasswordMatch($password, $confirmPassword);

        // Check if email already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            throw new \Exception("Sorry, user already taken.");
        }

        // Insert the new user
        $query = "INSERT INTO users (firstname, lastname, email, phone, pswd, date_registered) VALUES (?, ?, ?, ?, SHA1(?), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $password);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new \Exception("Failed to register user.");
        }
    }

    // Validate Password
    public function validatePasswordMatch($password, $confirmPassword)
    {
        if ($password !== $confirmPassword) {
            throw new \Exception("Password mismatch.");
        }
    }

    // Validate Email
    public function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email address.");
        }
    }
}
