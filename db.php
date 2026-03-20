<?php
// Database connection.
// Uses environment variables when available so the project works with the provided `schema.sql`.
//
// Optional env vars:
// - DB_HOST (default: localhost)
// - DB_USER (default: root)
// - DB_PASS (default: empty)
// - DB_NAME (default: unbundl_db)

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'unbundl_db';

$conn = new mysqli($host, $user, $pass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>
