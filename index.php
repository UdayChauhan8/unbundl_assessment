
<!DOCTYPE html>
<html>

<head>
    <title>User Data Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h2>User Data Form</h2>

    <form action="submit.php" method="POST" onsubmit="return validateForm()">

        <label>Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter your full name" required>
        <span class="error" id="nameError"></span>

        <label>Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        <span class="error" id="emailError"></span>

        <label>Phone Number:</label>
        <input type="number" name="phone" id="phone" placeholder="Enter 10-digit phone number" required>
        <span class="error" id="phoneError"></span>

        <label>Address:</label>
        <textarea name="address" placeholder="Enter your address"></textarea>

        <button type="submit">Submit</button>

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
                document.getElementById('phoneError').textContent = 'Phone number must be exactly 10 digits.';
                valid = false;
            }

            return valid;
        }
    </script>

</body>

</html>