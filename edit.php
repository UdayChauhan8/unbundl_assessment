<?php
require 'db.php';

if (!isset($_GET['id'])) {
    die("No ID provided!");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($idOut, $nameOut, $emailOut, $phoneOut, $addressOut);
if (!$stmt->fetch()) {
    die("User not found.");
}
$user = [
    'id' => $idOut,
    'name' => $nameOut,
    'email' => $emailOut,
    'phone' => $phoneOut,
    'address' => $addressOut,
];
$stmt->close();

$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
    <img src="logo.png" alt="Logo" class="logo">
    <h2>Edit User</h2>

    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name"
            pattern="[a-zA-Z\s]+" title="Only letters and spaces allowed." minlength="2"
            value="<?= htmlspecialchars(isset($_GET['name']) ? $_GET['name'] : $user['name']) ?>" required>
        <span class="error"><?php if ($error === 'invalid_name') echo 'Name must be at least 2 characters (letters and spaces only).';
            elseif ($error === 'duplicate_record') echo 'A user with this name and phone number already exists.'; ?></span>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email"
            pattern="^[A-Za-z0-9._%+\-]+@[A-Za-z][A-Za-z0-9.\-]*\.[A-Za-z]{2,}$"
            title="Enter a valid email like name@domain.tld (domain must start with a letter)."
            value="<?= htmlspecialchars(isset($_GET['email']) ? $_GET['email'] : $user['email']) ?>" required>
        <span class="error"><?php if ($error === 'invalid_email') echo 'Please enter a valid email address.';
            elseif ($error === 'duplicate_email') echo 'This email is already registered. Please use a different one.'; ?></span>

        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone" id="phone"
            pattern="^[6-9]\d{9}$" title="Must be exactly 10 digits and start with 6/7/8/9." maxlength="10"
            value="<?= htmlspecialchars(isset($_GET['phone']) ? $_GET['phone'] : $user['phone']) ?>" required>
        <span class="error"><?php if ($error === 'invalid_phone') echo 'Phone number must be exactly 10 digits and start with 6/7/8/9.'; ?></span>

        <label for="address">Address:</label>
        <textarea name="address" id="address" maxlength="120" required><?= htmlspecialchars(isset($_GET['address']) ? $_GET['address'] : $user['address']) ?></textarea>
        <span class="error"><?php if ($error === 'empty_address') echo 'Address is required.';
            elseif ($error === 'address_too_long') echo 'Address is too long (max 20 words, max 50 chars/word, max 120 chars total).'; ?></span>

        <button type="submit">Update</button>
        <a href="view.php" class="btn btn-cancel">Cancel</a>
    </form>

</body>

</html>