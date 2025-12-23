<?php include 'header.php'; ?>

<h3>Students List</h3>

<?php
if (file_exists("students.txt")) {
    $lines = file("students.txt");

    foreach ($lines as $line) {
        list($name, $email, $skills) = explode("|", trim($line));
        $skillsArray = explode(",", $skills);

        echo "<p>";
        echo "<strong>Name:</strong> $name <br>";
        echo "<strong>Email:</strong> $email <br>";
        echo "<strong>Skills:</strong> " . implode(", ", $skillsArray);
        echo "</p><hr>";
    }
} else {
    echo "No students found.";
}
?>

<?php include 'footer.php'; ?>
