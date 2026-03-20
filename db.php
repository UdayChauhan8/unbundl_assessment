<?php
//connecting to the database
$conn = new mysqli("sql108.byetcluster.com", "if0_41437522", "D0vHybihaU", "if0_41437522_unbundl_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>