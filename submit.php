<?php
// handle form submit
require 'db.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

// build redirect params to preserve form data
$params = "&name=" . urlencode($name) . "&email=" . urlencode($email) . "&phone=" . urlencode($phone) . "&address=" . urlencode($address);

// validate and redirect back with error + data if invalid
if (empty($name) || strlen($name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
    header("Location: index.php?error=invalid_name" . $params);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?error=invalid_email" . $params);
    exit;
}

if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
    header("Location: index.php?error=invalid_phone" . $params);
    exit;
}

if (empty($address)) {
    header("Location: index.php?error=empty_address" . $params);
    exit;
}

// check if a record with the same name and phone already exists
$check = $conn->prepare("SELECT id FROM users WHERE name = ? AND phone = ?");
$check->bind_param("ss", $name, $phone);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    header("Location: index.php?error=duplicate_record" . $params);
    exit;
}
$check->close();

// insert into db
$stmt = $conn->prepare("INSERT INTO users (name, email, phone, address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $address);

if ($stmt->execute()) {
    header("Location: view.php");
    exit;
} else {
    // check for duplicate email (MySQL error code 1062)
    if ($conn->errno === 1062) {
        header("Location: index.php?error=duplicate_email" . $params);
    } else {
        header("Location: index.php?error=db_error" . $params);
    }
    exit;
}

$stmt->close();
$conn->close();
?>