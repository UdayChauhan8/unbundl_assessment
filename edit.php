<?php
require 'db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h2>Edit User</h2>

    <form action="update.php" method="POST" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <label>Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <span class="error" id="nameError"></span>

        <label>Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <span class="error" id="emailError"></span>

        <label>Phone Number:</label>
        <input type="number" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
        <span class="error" id="phoneError"></span>

        <label>Address:</label>
        <textarea name="address"><?= htmlspecialchars($user['address']) ?></textarea>

        <button type="submit">Update</button>
        <a href="view.php" class="btn btn-cancel">Cancel</a>
    </form>

    <script>
        function validateForm() {
            let valid = true;

            document.querySelectorAll('.error').forEach(e => e.textContent = '');

            const name = document.getElementById('name').value.trim();
            if (name.length < 2) {
                document.getElementById('nameError').textContent = 'Name must be at least 2 characters.';
                valid = false;
            } else if (!/^[a-zA-Z\s]+$/.test(name)) {
                document.getElementById('nameError').textContent = 'Name can only contain letters and spaces.';
                valid = false;
            }

            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').textContent = 'Please enter a valid email address.';
                valid = false;
            }

            const phone = document.getElementById('phone').value.trim();
            if (!/^\d{10}$/.test(phone)) {
                document.getElementById('phoneError').textContent = 'Phone must be exactly 10 digits.';
                valid = false;
            }

            return valid;
        }
    </script>

</body>

</html>
