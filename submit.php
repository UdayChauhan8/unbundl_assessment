<?php

require 'db.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

if (empty($name) || strlen($name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
    die("Invalid name. Use only letters and spaces (min 2 characters).");
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
    die("Invalid phone number. Must be exactly 10 digits.");
}

$stmt = $conn->prepare("INSERT INTO users (name, email, phone, address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $address);

if ($stmt->execute()) {
    header("Location: view.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>