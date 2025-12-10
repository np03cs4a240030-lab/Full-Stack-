<?php

define('USERS_FILE', __DIR__ . '/users.json');

$errors = [
    'name' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => '',
    'general' => ''
];

$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    $valid = true;

    // --- Validation ---
    if ($name === "" || strlen($name) < 2) {
        $errors["name"] = "Name is required (min 2 characters).";
        $valid = false;
    }

    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Enter a valid email address.";
        $valid = false;
    }

    if ($password === "") {
        $errors["password"] = "Password is required.";
        $valid = false;
    } else {
        if (strlen($password) < 8) {
            $errors["password"] .= "Password must be at least 8 characters. ";
            $valid = false;
        }
        if (!preg_match('/\d/', $password)) {
            $errors["password"] .= "Include at least one number. ";
            $valid = false;
        }
        if (!preg_match('/[\W_]/', $password)) {
            $errors["password"] .= "Include at least one special character.";
            $valid = false;
        }
    }

    if ($confirm !== $password) {
        $errors["confirm_password"] = "Passwords do not match.";
        $valid = false;
    }

    if ($valid) {
        // Ensure users.json exists
        if (!file_exists(USERS_FILE)) {
            file_put_contents(USERS_FILE, json_encode([]));
        }

        $json = file_get_contents(USERS_FILE);
        if ($json === false) {
            $errors["general"] = "Error reading users.json";
        } else {
            $users = json_decode($json, true);
            if (!is_array($users)) $users = [];

            // Check duplicate email
            foreach ($users as $u) {
                if (strtolower($u["email"]) === strtolower($email)) {
                    $errors["email"] = "Email already exists.";
                    $valid = false;
                }
            }
        }

        if ($valid) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $users[] = [
                "name" => $name,
                "email" => $email,
                "password_hash" => $hashed,
                "registered_at" => date("c")
            ];

            $newJson = json_encode($users, JSON_PRETTY_PRINT);
            if (file_put_contents(USERS_FILE, $newJson, LOCK_EX) === false) {
                $errors["general"] = "Error writing to users.json";
            } else {
                $success = "Registration successful!";
                $_POST = [];
            }
        }
    }
}

function old($key) {
    return htmlspecialchars($_POST[$key] ?? "");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>User Registration</h2>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($errors["general"]): ?>
        <div class="error"><?= htmlspecialchars($errors["general"]) ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Name:</label>
        <input type="text" name="name" value="<?= old('name') ?>">
        <div class="field-error"><?= $errors["name"] ?></div>

        <label>Email:</label>
        <input type="email" name="email" value="<?= old('email') ?>">
        <div class="field-error"><?= $errors["email"] ?></div>

        <label>Password:</label>
        <input type="password" name="password">
        <div class="field-error"><?= $errors["password"] ?></div>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password">
        <div class="field-error"><?= $errors["confirm_password"] ?></div>

        <button type="submit">Register</button>

    </form>

</div>

</body>
</html>
