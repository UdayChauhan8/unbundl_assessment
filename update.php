<?php
require_once 'validate.php';

$id = $_POST['id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

$params = "&name=" . urlencode($name) . "&email=" . urlencode($email) . "&phone=" . urlencode($phone) . "&address=" . urlencode($address);
$data = compact('name', 'email', 'phone', 'address');

// Validate all fields — redirects and exits on failure
validateUserInput("edit.php?id=$id", $params, $data, $conn, (int)$id);

$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
$stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

if ($stmt->execute()) {
    header("Location: view.php");
} else {
    if ($conn->errno === 1062) {
        header("Location: edit.php?id=$id&error=duplicate_email" . $params);
    } else {
        header("Location: edit.php?id=$id&error=db_error" . $params);
    }
}
$stmt->close();
$conn->close();
?>