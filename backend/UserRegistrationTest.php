<?php

use PHPUnit\Framework\TestCase;

class UserRegistrationTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Mock database connection
        $this->conn = new mysqli("localhost", "root", "", "test_database");

        // Ensure the database connection is successful
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Create table for testing
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            phone VARCHAR(255) NOT NULL,
            pswd VARCHAR(255) NOT NULL,
            date_registered TIMESTAMP NOT NULL
        )";

        $this->conn->query($sql);
    }

    protected function tearDown(): void
    {
        // Drop the users table after the test
        $this->conn->query("DROP TABLE IF EXISTS users");
        $this->conn->close();
    }

    public function testTableCreation(): void
    {
        $result = $this->conn->query("SHOW TABLES LIKE 'users'");
        $this->assertEquals(1, $result->num_rows, "Table 'users' should exist.");
    }

    public function testUserRegistration(): void
    {
        $first_name = "John";
        $last_name = "Doe";
        $email = "john.doe@example.com";
        $phone = "1234567890";
        $password = "Password123";

        // Prepare insert query
        $query = "INSERT INTO users (firstname, lastname, email, phone, pswd, date_registered) VALUES (?, ?, ?, ?, SHA1(?), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $password);

        $this->assertTrue($stmt->execute(), "User should be registered successfully.");

        // Verify the user was inserted
        $result = $this->conn->query("SELECT * FROM users WHERE email = '$email'");
        $this->assertEquals(1, $result->num_rows, "User should exist in the database.");

        $stmt->close();
    }

    public function testDuplicateEmailRegistration(): void
    {
        $first_name = "Jane";
        $last_name = "Doe";
        $email = "jane.doe@example.com";
        $phone = "0987654321";
        $password = "Password123";

        // Register the user once
        $query = "INSERT INTO users (firstname, lastname, email, phone, pswd, date_registered) VALUES (?, ?, ?, ?, SHA1(?), NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $password);
        $stmt->execute();

        // Try to register the user again with the same email
        $this->expectException(mysqli_sql_exception::class);
        $stmt->execute();

        $stmt->close();
    }

    public function testEmptyFields(): void
    {
        $first_name = "";
        $last_name = "";
        $email = "";
        $phone = "";
        $password = "";

        // Check if any field is empty
        $this->assertTrue(empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($password), "Fields cannot be blank.");
    }

    public function testInvalidEmail(): void
    {
        $email = "invalid-email";
        $this->assertFalse(filter_var($email, FILTER_VALIDATE_EMAIL), "Invalid email address.");
    }
}
