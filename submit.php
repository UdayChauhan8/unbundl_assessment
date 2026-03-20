<?php
require 'db.php';

if (!$conn || $conn->connect_error) {
    header("Location: index.php");
    exit;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);

$params = "&name=" . urlencode($name) . "&email=" . urlencode($email) . "&phone=" . urlencode($phone) . "&address=" . urlencode($address);

// Validate email with a stricter pattern to reject malformed inputs.
function isValidEmail(string $email): bool
{
    if (strlen($email) > 254) {
        return false;
    }
    if (preg_match('/\s/', $email)) {
        return false;
    }
    if (strpos($email, '@') === false) {
        return false;
    }

    [$local, $domain] = explode('@', $email, 2);
    if ($local === '' || $domain === '' || $local === false || $domain === false) {
        return false;
    }

    if (strlen($local) > 64) {
        return false;
    }
    if ($local[0] === '.' || substr($local, -1) === '.' || strpos($local, '..') !== false) {
        return false;
    }
    if (!preg_match('/^[A-Za-z0-9._%+\-]+$/', $local)) {
        return false;
    }

    $labels = explode('.', $domain);
    if (count($labels) < 2) {
        return false;
    }
    foreach ($labels as $label) {
        if ($label === '') {
            return false;
        }
    }

    $tld = end($labels);
    if (!preg_match('/^[A-Za-z]{2,63}$/', $tld)) {
        return false;
    }

    foreach ($labels as $label) {
        if ($label === $tld) {
            continue;
        }
        if (!preg_match('/^[A-Za-z](?:[A-Za-z0-9-]{0,61}[A-Za-z0-9])?$/', $label)) {
            return false;
        }
    }

    if (strpos($domain, '..') !== false) {
        return false;
    }

    return true;
}

if (empty($name) || strlen($name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
    header("Location: index.php?error=invalid_name" . $params);
    exit;
}

if (empty($email) || !isValidEmail($email)) {
    header("Location: index.php?error=invalid_email" . $params);
    exit;
}

if (empty($phone) || !preg_match('/^[6-9]\d{9}$/', $phone)) {
    header("Location: index.php?error=invalid_phone" . $params);
    exit;
}

if (empty($address)) {
    header("Location: index.php?error=empty_address" . $params);
    exit;
}

$word_count = str_word_count($address);
if ($word_count > 20) {
    header("Location: index.php?error=address_too_long" . $params);
    exit;
}

$address_max_word_len = 50;
$address_max_len = 120;
if (strlen($address) > $address_max_len) {
    header("Location: index.php?error=address_too_long" . $params);
    exit;
}
$words = preg_split('/\s+/', trim($address), -1, PREG_SPLIT_NO_EMPTY);
if (is_array($words)) {
    foreach ($words as $w) {
        if (strlen($w) > $address_max_word_len) {
            header("Location: index.php?error=address_too_long" . $params);
            exit;
        }
    }
}

$check = $conn->prepare("SELECT id FROM users WHERE name = ? AND phone = ?");
$check->bind_param("ss", $name, $phone);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    header("Location: index.php?error=duplicate_record" . $params);
    exit;
}
$check->close();

$stmt = $conn->prepare("INSERT INTO users (name, email, phone, address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $phone, $address);

if ($stmt->execute()) {
    header("Location: view.php");
} else {
    if ($conn->errno === 1062) {
        header("Location: index.php?error=duplicate_email" . $params);
    } else {
        die("Database error: " . $conn->error);
    }
    exit;
}

$stmt->close();
$conn->close();
?>