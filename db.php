<?php
$host = 'sql108.infinityfree.com';
$user = 'if0_41437522';
$pass = 'D0vHybihaU';
$dbName = 'if0_41437522_unbundl_db';

$conn = new mysqli($host, $user, $pass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>