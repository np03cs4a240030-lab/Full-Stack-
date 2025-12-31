<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $theme = $_POST['theme'];
    setcookie('theme', $theme, time() + (86400 * 30));
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Theme Preference</title>
</head>
<body>

<h2>Select Theme</h2>

<form method="POST">
    <select name="theme">
        <option value="light">Light Mode</option>
        <option value="dark">Dark Mode</option>
    </select>

    <button type="submit">Save</button>
</form>

</body>
</html>
