<?php

define('USERS_FILE', __DIR__ . '/users.json');

$errors = [
    'name' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => '',
    'general' => ''
];

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $valid = true;

    // Validate name
    if ($name === '' || strlen($name) < 2) {
        $errors['name'] = 'Name is required (min 2 characters).';
        $valid = false;
    }

    // Validate email
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
        $valid = false;
    }

    // Validate password
    if ($password === '') {
        $errors['password'] = 'Password is required.';
        $valid = false;
    } else {
        if (strlen($password) < 8) {
            $errors['password'] .= 'At least 8 characters. ';
            $valid = false;
        }
        if (!preg_match('/\d/', $password)) {
            $errors['password'] .= 'Must include a number. ';
            $valid = false;
        }
        if (!preg_match('/[\W_]/', $password)) {
            $errors['password'] .= 'Must include a special character.';
            $valid = false;
        }
    }

    if ($confirm !== $password) {
        $errors['confirm_password'] = 'Passwords do not match.';
        $valid = false;
    }

    // Read JSON and append user
    if ($valid) {

        if (!file_exists(USERS_FILE)) {
            file_put_contents(USERS_FILE, json_encode([]));
        }

        $json = file_get_contents(USERS_FILE);
        $users = json_decode($json, true);

        if (!is_array($users)) {
            $users = [];
        }

        // Duplicate email check
        foreach ($users as $u) {
            if (strtolower($u['email']) === strtolower($email)) {
                $errors['email'] = 'This email is already registered.';
                $valid = false;
                break;
            }
        }

        if ($valid) {
            $users[] = [
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'registered_at' => date('c')
            ];

            $newJson = json_encode($users, JSON_PRETTY_PRINT);

            file_put_contents(USERS_FILE, $newJson, LOCK_EX);

            // PRG redirect
            header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
            exit;
        }
    }
}

// Show success message after redirect
if (isset($_GET['success']) && $_GET['success'] === '1') {
    $success = 'Registration successful!';
}

// Helper for old values
function old($key) {
    return htmlspecialchars($_POST[$key] ?? '');
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">

    <?php if ($success): ?>
        <!-- auto-refresh to clean URL -->
        <meta http-equiv="refresh" content="3.5;url=<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
    <?php endif; ?>
</head>

<body>

<div class="container">

    <h2>User Registration</h2>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($errors['general']): ?>
        <div class="error"><?= htmlspecialchars($errors['general']) ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Name:</label>
        <input type="text" name="name" value="<?= old('name') ?>">
        <div class="field-error"><?= $errors['name'] ?></div>

        <label>Email:</label>
        <input type="email" name="email" value="<?= old('email') ?>">
        <div class="field-error"><?= $errors['email'] ?></div>

        <label>Password:</label>
        <input type="password" name="password">
        <div class="field-error"><?= $errors['password'] ?></div>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password">
        <div class="field-error"><?= $errors['confirm_password'] ?></div>

        <button type="submit" class="btn">Register</button>

    </form>
</div>

</body>
</html>
