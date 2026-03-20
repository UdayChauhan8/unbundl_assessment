<?php
// update user record
require 'db.php';

$id = $_POST['id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

// basic backend validation
if (empty($name) || strlen($name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
    die("Invalid name. Use only letters and spaces (min 2 characters)."); // redirect back with error instead of dying
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

// 10 digits exactly
if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
    die("Invalid phone number. Must be exactly 10 digits.");
}

if (empty($address)) {
    die("Address is required.");
}

// execute update
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
$stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

if ($stmt->execute()) {
    header("Location: view.php"); // head back to list
    exit;
} else {
    echo "Update Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>