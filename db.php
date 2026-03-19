<?php
// TODO: maybe move credentials to a .env file later?
$conn = new mysqli("127.0.0.1", "root", "root", "unbundl_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // bad connection
}
?>