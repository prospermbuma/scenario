<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\UserRegistration;

class UserRegistrationTest extends TestCase
{
    private $db;
    private $userRegistration;

    protected function setUp(): void
    {
        // Set up a test database connection
        $this->db = new \mysqli('localhost', 'root', '', 'test_scenario');

        // Check connection
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        // Initialize the UserRegistration class
        $this->userRegistration = new UserRegistration($this->db);
    }

    protected function tearDown(): void
    {
        // Clean up the database after each test
        // TRUNCATE - Removes all rows and resets the auto-increment counter to 1.
        $this->db->query("TRUNCATE TABLE users");
        $this->db->close();
    }

    // TEST CASE 01: User registration
    public function testUserRegistration()
    {
        $result = $this->userRegistration->registerUser('test', 'one', 'test1@example.com', '07454546723', 'password123', 'password123');
        $this->assertTrue($result);
    }

    // TEST CASE 02: Duplicate email
    public function testDuplicateEmailRegistration()
    {
        $this->userRegistration->registerUser('test', 'two', 'test@example.com', '07454546723', 'password123', 'password123');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Sorry, user already taken.");
        $this->userRegistration->registerUser('test', 'two', 'test@example.com', '07454546723', 'password123', 'password123');
    }

    // TEST CASE 03: Empty fields
    public function testEmptyFields()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Fields cannot be blank.");
        $this->userRegistration->registerUser('', '', '', '', '', '');
    }

    // TEST CASE 04: Invalid Email
    public function testInvalidEmail()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid email address.");
        $this->userRegistration->registerUser('test', 'three', 'invalid-email', '07454546723', 'password123', 'password123');
    }

    // TEST CASE 05: Password Mismatch
    public function testPasswordMismatch()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Password mismatch.");
        $this->userRegistration->registerUser('test', 'four', 'test@example.com', '07454546723', 'password123', 'password12345');
    }
}
