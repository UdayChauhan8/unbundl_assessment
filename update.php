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

// Stricter email validation:
// - no spaces
// - local part allowed characters
// - domain must contain at least one dot and each label must start with a letter
// - TLD must be letters-only (2-63 chars)
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
    // Disallow consecutive dots and leading/trailing dot in local part
    if ($local[0] === '.' || substr($local, -1) === '.' || strpos($local, '..') !== false) {
        return false;
    }
    // Basic allowed chars for the local part
    if (!preg_match('/^[A-Za-z0-9._%+\-]+$/', $local)) {
        return false;
    }

    // Domain must be dot-separated labels, no empty labels (no consecutive dots)
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
        // Domain labels must start with a letter (reject things like "12.com")
        if (!preg_match('/^[A-Za-z](?:[A-Za-z0-9-]{0,61}[A-Za-z0-9])?$/', $label)) {
            return false;
        }
    }

    // Disallow consecutive dots in domain
    if (strpos($domain, '..') !== false) {
        return false;
    }

    return true;
}

// validate and redirect back with error + data if invalid
if (empty($name) || strlen($name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $name)) {
    header("Location: edit.php?id=$id&error=invalid_name" . $params);
    exit;
}

if (empty($email) || !isValidEmail($email)) {
    header("Location: edit.php?id=$id&error=invalid_email" . $params);
    exit;
}

if (empty($phone) || !preg_match('/^[6-9]\d{9}$/', $phone)) {
    header("Location: edit.php?id=$id&error=invalid_phone" . $params);
    exit;
}

if (empty($address)) {
    header("Location: edit.php?id=$id&error=empty_address" . $params);
    exit;
}

// check address word limit (max 20 words)
$word_count = str_word_count($address);
if ($word_count > 20) {
    header("Location: edit.php?id=$id&error=address_too_long" . $params);
    exit;
}

// check address length:
// - max 50 characters per word
// - max 120 characters total (matches textarea maxlength)
$address_max_word_len = 50;
$address_max_len = 120;
if (strlen($address) > $address_max_len) {
    header("Location: edit.php?id=$id&error=address_too_long" . $params);
    exit;
}
$words = preg_split('/\s+/', trim($address), -1, PREG_SPLIT_NO_EMPTY);
if (is_array($words)) {
    foreach ($words as $w) {
        if (strlen($w) > $address_max_word_len) {
            header("Location: edit.php?id=$id&error=address_too_long" . $params);
            exit;
        }
    }
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