<?php

// CONSTANTS
require('constants.php');

// Database connection (MySQL)
$conn = new mysqli(HOST, USER, PASSWORD, DB);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
