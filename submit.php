<?php
require_once 'validate.php';

if (!$conn || $conn->connect_error) {
    header("Location: index.php");
    exit;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

$params = "&name=" . urlencode($name) . "&email=" . urlencode($email) . "&phone=" . urlencode($phone) . "&address=" . urlencode($address);
$data = compact('name', 'email', 'phone', 'address');

validateUserInput('index.php', $params, $data, $conn);

// Sequential ID to avoid gaps after deletions
$result = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM users");
$nextId = $result->fetch_assoc()['next_id'];

$stmt = $conn->prepare("INSERT INTO users (id, name, email, phone, address) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $nextId, $name, $email, $phone, $address);

if ($stmt->execute()) {
    header("Location: view.php");
} else {
    if ($conn->errno === 1062) {
        header("Location: index.php?error=duplicate_email" . $params);
    } else {
        die("Database error: " . $conn->error);
    }
}
$stmt->close();
$conn->close();
?>