<?php
require 'db.php';
require 'session.php';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT email FROM users WHERE id = ?"
);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Dashboard</h2>

<p>
    Welcome,
    <?php echo htmlspecialchars($user['email']); ?>
</p>

<form method="post">
    <button type="submit" name="logout">Logout</button>
</form>

</body>
</html>
