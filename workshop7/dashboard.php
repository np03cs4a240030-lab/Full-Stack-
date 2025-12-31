<?php
session_start();

$message = "";

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$theme = "light";   

if (isset($_COOKIE['theme'])) {
    $theme = $_COOKIE['theme'];
}

$message = "Welcome " . $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
</head>

<body style="
<?php
if ($theme == 'dark') {
    echo 'background-color:black; color:white;';
} else {
    echo 'background-color:white; color:black;';
}
?>
">

<h1><?= $message ?></h1>

<p>Current Theme: <strong><?= $theme ?></strong></p>

<a href="preference.php">Change Theme</a><br><br>


</body>
</html>
