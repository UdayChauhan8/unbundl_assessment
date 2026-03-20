<?php
require_once 'db.php';

function isValidEmail(string $email): bool
{
    if (strlen($email) > 254 || preg_match('/\s/', $email) || strpos($email, '@') === false) {
        return false;
    }

    [$local, $domain] = explode('@', $email, 2);
    if ($local === '' || $domain === '') {
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
    if (count($labels) < 2 || strpos($domain, '..') !== false) {
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
        if ($label === $tld) continue;
        if (!preg_match('/^[A-Za-z](?:[A-Za-z0-9-]{0,61}[A-Za-z0-9])?$/', $label)) {
            return false;
        }
    }

    return true;
}

// Validate fields and redirect on failure. Exits if invalid.
function validateUserInput(string $redirectBase, string $params, array $data, mysqli $conn, ?int $excludeId = null): void
{
    $sep = (strpos($redirectBase, '?') !== false) ? '&' : '?';

    if (empty($data['name']) || strlen($data['name']) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $data['name'])) {
        header("Location: {$redirectBase}{$sep}error=invalid_name" . $params);
        exit;
    }

    if (empty($data['email']) || !isValidEmail($data['email'])) {
        header("Location: {$redirectBase}{$sep}error=invalid_email" . $params);
        exit;
    }

    if (empty($data['phone']) || !preg_match('/^[6-9]\d{9}$/', $data['phone'])) {
        header("Location: {$redirectBase}{$sep}error=invalid_phone" . $params);
        exit;
    }

    if (empty($data['address'])) {
        header("Location: {$redirectBase}{$sep}error=empty_address" . $params);
        exit;
    }
    if (strlen($data['address']) > 120 || str_word_count($data['address']) > 20) {
        header("Location: {$redirectBase}{$sep}error=address_too_long" . $params);
        exit;
    }
    $words = preg_split('/\s+/', trim($data['address']), -1, PREG_SPLIT_NO_EMPTY);
    if (is_array($words)) {
        foreach ($words as $w) {
            if (strlen($w) > 50) {
                header("Location: {$redirectBase}{$sep}error=address_too_long" . $params);
                exit;
            }
        }
    }

    // Duplicate name+phone check
    if ($excludeId !== null) {
        $check = $conn->prepare("SELECT id FROM users WHERE name = ? AND phone = ? AND id != ?");
        $check->bind_param("ssi", $data['name'], $data['phone'], $excludeId);
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE name = ? AND phone = ?");
        $check->bind_param("ss", $data['name'], $data['phone']);
    }
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        header("Location: {$redirectBase}{$sep}error=duplicate_record" . $params);
        exit;
    }
    $check->free_result();
    $check->close();
}
?>
