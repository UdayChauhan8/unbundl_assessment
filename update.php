<?php
// update user record
require 'db.php';

$id = $_POST['id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

// build redirect params to preserve form data
$params = "&name=" . urlencode($name) . "&email=" . urlencode($email) . "&phone=" . urlencode($phone) . "&address=" . urlencode($address);

// validate and redirect back with error + data if invalid
if (empty($name) || strlen($name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
    header("Location: edit.php?id=$id&error=invalid_name" . $params);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: edit.php?id=$id&error=invalid_email" . $params);
    exit;
}

if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
    header("Location: edit.php?id=$id&error=invalid_phone" . $params);
    exit;
}

if (empty($address)) {
    header("Location: edit.php?id=$id&error=empty_address" . $params);
    exit;
}

// check if another record with the same name and phone exists
$check = $conn->prepare("SELECT id FROM users WHERE name = ? AND phone = ? AND id != ?");
$check->bind_param("ssi", $name, $phone, $id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    header("Location: edit.php?id=$id&error=duplicate_record" . $params);
    exit;
}
$check->close();

// execute update
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
$stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

if ($stmt->execute()) {
    header("Location: view.php");
    exit;
} else {
    // check for duplicate email
    if ($conn->errno === 1062) {
        header("Location: edit.php?id=$id&error=duplicate_email" . $params);
    } else {
        header("Location: edit.php?id=$id&error=db_error" . $params);
    }
    exit;
}

$stmt->close();
$conn->close();
?>