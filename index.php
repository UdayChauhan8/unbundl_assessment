<!DOCTYPE html>
<html>

<head>
    <title>User Data Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header style="margin-bottom: 40px;">
        <img src="logo.png" alt="Unbundl Logo" style="height: 60px; margin-left: -32px;">
    </header>

    <h2>User Data Form</h2>

    <?php
    // show server-side error if redirected back
    $error = isset($_GET['error']) ? $_GET['error'] : '';
    ?>

    <form action="submit.php" method="POST">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter your full name"
            pattern="[a-zA-Z\s]+" title="Only letters and spaces allowed." minlength="2"
            value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>" required>
        <span class="error"><?php if ($error === 'invalid_name') echo 'Name must be at least 2 characters (letters and spaces only).';
            elseif ($error === 'duplicate_record') echo 'A user with this name and phone number already exists.'; ?></span>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email"
            value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>" required>
        <span class="error"><?php if ($error === 'invalid_email') echo 'Please enter a valid email address.';
            elseif ($error === 'duplicate_email') echo 'This email is already registered. Please use a different one.'; ?></span>

        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone" id="phone" placeholder="Enter 10-digit phone number"
            pattern="\d{10}" title="Must be exactly 10 digits." maxlength="10"
            value="<?= isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : '' ?>" required>
        <span class="error"><?php if ($error === 'invalid_phone') echo 'Phone number must be exactly 10 digits.'; ?></span>

        <label for="address">Address:</label>
        <textarea name="address" id="address" placeholder="Enter your address" required><?= isset($_GET['address']) ? htmlspecialchars($_GET['address']) : '' ?></textarea>
        <span class="error"><?php if ($error === 'empty_address') echo 'Address is required.'; ?></span>

        <button type="submit">Submit</button>

    </form>

</body>

</html>