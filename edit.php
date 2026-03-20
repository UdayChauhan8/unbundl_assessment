<?php
require 'db.php';

if (!isset($_GET['id'])) {
    die("No ID provided!");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

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
    <header style="text-align: left; margin-bottom: 20px;">
        <div
            style="display: inline-flex; align-items: center; gap: 8px; background: white; padding: 6px 12px; border-radius: 6px;">
            <img src="https://unbundl.com/cdn/shop/files/Logo_83b3f08f-7fa6-460e-b0f6-3a7bdd540472.webp?v=1756893840&width=40"
                alt="Unbundl Symbol" style="height: 30px;">
            <img src="https://unbundl.com/cdn/shop/files/unbundl_logo_blue.png" alt="Unbundl Text"
                style="height: 22px;">
        </div>
    </header>

    <h2>Edit User</h2>

    <form action="update.php" method="POST" onsubmit="return validateEditForm()">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <span class="error" id="nameError"></span>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <span class="error" id="emailError"></span>

        <label for="phone">Phone Number:</label>
        <input type="number" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
        <span class="error" id="phoneError"></span>

        <label for="address">Address:</label>
        <textarea name="address" id="address"><?= htmlspecialchars($user['address']) ?></textarea>

        <button type="submit">Update</button>
        <a href="view.php" class="btn btn-cancel">Cancel</a>
    </form>

    <script>
        // duplicate validation logic from index.php (should probably extract this to a separate js file later)
        function validateEditForm() {
            let isValid = true;

            document.querySelectorAll('.error').forEach(e => e.textContent = '');

            let nameField = document.getElementById('name').value.trim();
            if (nameField.length < 2) {
                document.getElementById('nameError').textContent = 'Name must be at least 2 characters.';
                isValid = false;
            } else if (!/^[a-zA-Z\s]+$/.test(nameField)) {
                document.getElementById('nameError').textContent = 'Name can only contain letters and spaces.';
                isValid = false;
            }

            let emailField = document.getElementById('email').value.trim();
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField)) {
                document.getElementById('emailError').textContent = 'Invalid email address.';
                isValid = false;
            }

            let phoneField = document.getElementById('phone').value.trim();
            if (!/^\d{10}$/.test(phoneField)) {
                document.getElementById('phoneError').textContent = 'Phone must be exactly 10 digits.';
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>

</html>