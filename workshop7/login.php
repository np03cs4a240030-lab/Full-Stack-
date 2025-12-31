<?php
require'db.php';

try{ if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $student_id = $_POST['student_id'];
        $password   = $_POST['password'];

        $sql = "SELECT * FROM students WHERE student_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id]);

        $student = $stmt->fetch();

        if (!$student){
        	echo "Register First";
        	header("Location: register.php");
        } else{
        	$hashedPassword = $student['password_hash'];
        	$isPasswordValid = password_verify($password, $hashedPassword);
        	if(!$isPasswordValid){
        		echo "Invalid password!";
        		exit;

        	}else{
        		echo " Sucessfull login ...." ;
        		session_start();
        		$_SESSION['logged_in'] = true;
        		$_SESSION['username'] = $student['full_name'];
        	header("Location: dashboard.php");

        }
        }
    }
    }catch(PDOException $e){
	die("Database Error: ".$e->getMessage());

}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login </title>
</head>
<body>

	<form method="POST">
		<label>Student ID:</label>
    <input type="text" name="student_id" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

	</form>

</body>
</html>