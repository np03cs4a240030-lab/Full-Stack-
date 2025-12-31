<?php
require 'db.php';
try{if ($_SERVER['REQUEST_METHOD']=="POST"){
	$student_id = $_POST['student_id'];
	$full_name =  $_POST['name'];
	$password =  $_POST['password'];

	$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

	$sql = "INSERT INTO students (student_id,full_name,password_hash )
	        VALUES (?,?,?)";

	        $stmt = $pdo -> prepare($sql);

	        $stmt -> execute ([$student_id,$full_name, $hashedPassword]);
	        echo "Student Registered...!!";

	         header("Location: login.php");
}}
catch(PDOException $e){
	die("Database Error: ".$e->getMessage());

}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Student Registration</title>
</head>
<body>
	<form method="POST">
		<label>Student_ID:</label><input type="text" name="student_id" required>
		<label>Name:</label><input type="text" name="name" required>
		<label>Password:</label><input type="password" name="password" required>
		<button type="submit">Register</button>
		</form>
</body>
</html>