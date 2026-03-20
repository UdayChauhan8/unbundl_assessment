<?php
// handle form submit
require 'db.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

// quick validation checks
if (empty($name) || strlen($name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
    die("Invalid name. Use only letters and spaces (min 2 characters)."); // TODO: show this nicely on the form instead of dying
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

// Ensure phone is exactly 10 digits
if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
    die("Invalid phone number. Must be exactly 10 digits.");
}

if (empty($address)) {
    die("Address is required.");
}

// insert into db
$stmt = $conn->prepare("INSERT INTO users (name, email, phone, address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $address);

if ($stmt->execute()) {
    header("Location: view.php"); // redirect to list
    exit;
} else {
    echo "DB Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>