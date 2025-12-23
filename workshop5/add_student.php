<?php include 'header.php'; ?>

<?php
$message = "";

function formatName($name) {
    return ucwords(trim($name));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function cleanSkills($string) {
    return array_map('trim', explode(',', $string));
}

function saveStudent($name, $email, $skills) {
    $data = $name . "|" . $email . "|" . implode(',', $skills) . PHP_EOL;
    file_put_contents("students.txt", $data, FILE_APPEND);
}

if (isset($_POST['submit'])) {
    try {
        $name = formatName($_POST['name']);
        $email = $_POST['email'];
        $skills = cleanSkills($_POST['skills']);

        if (empty($name) || empty($email) || empty($_POST['skills'])) {
            throw new Exception("All fields are required.");
        }

        if (!validateEmail($email)) {
            throw new Exception("Invalid email address.");
        }

        saveStudent($name, $email, $skills);
        $message = "✅ Student added successfully!";
    } catch (Exception $e) {
        $message = "❌ " . $e->getMessage();
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Name"><br><br>
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="text" name="skills" placeholder="Skills (comma separated)"><br><br>
    <button type="submit" name="submit">Save</button>
</form>

<p><?php echo $message; ?></p>

<?php include 'footer.php'; ?>
