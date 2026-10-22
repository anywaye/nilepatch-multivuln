<?php
// Database configuration - VULNERABLE: hardcoded credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password123');
define('DB_NAME', 'app_db');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
