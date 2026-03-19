<?php

require 'db.php';

$id = $_POST['id'];
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

$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
$stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

if ($stmt->execute()) {
    header("Location: view.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
