<?php
require 'db.php';
require 'session.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email) {
        $message = "Invalid email format";
    } elseif (empty($password) || strlen($password) < 6) {
        $message = "Password must be at least 6 characters";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO users (email, password) VALUES (?, ?)"
        );

        try {
            $stmt->execute([$email, $hashedPassword]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $message = "User already exists";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>
<h2>Signup</h2>

<p style="color:red;"><?php echo htmlspecialchars($message); ?></p>

<form method="post">
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Signup</button>
</form>

<a href="login.php">Login</a>
</body>
</html>
