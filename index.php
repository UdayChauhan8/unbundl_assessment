<!DOCTYPE html>
<html>

<head>
    <title>User Data Form</title>
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

    <h2>User Data Form</h2>

    <form action="submit.php" method="POST" onsubmit="return validateForm();">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter your full name" required>
        <span class="error" id="nameError"></span>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        <span class="error" id="emailError"></span>

        <label for="phone">Phone Number:</label>
        <input type="number" name="phone" id="phone" placeholder="Enter 10-digit phone number" required>
        <span class="error" id="phoneError"></span>

        <label for="address">Address:</label>
        <textarea name="address" id="address" placeholder="Enter your address"></textarea>

        <button type="submit">Submit</button>

    </form>

    <script>
        // basic form validation before sending to PHP
        function validateForm() {
            let isValid = true;

            // clear old errors
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
            // simple email regex
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField)) {
                document.getElementById('emailError').textContent = 'Please enter a valid email address.';
                isValid = false;
            }

            let phoneField = document.getElementById('phone').value.trim();
            // check for exactly 10 digits
            if (!/^\d{10}$/.test(phoneField)) {
                document.getElementById('phoneError').textContent = 'Phone number must be exactly 10 digits.';
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>

</html>