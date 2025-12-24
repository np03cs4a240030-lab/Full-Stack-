<?php
require 'db.php';

/* ================= CREATE ================= */
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add'])) {
    $stmt = $pdo->prepare(
        "INSERT INTO students (name, email, course) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $_POST['Name'],
        $_POST['Email'],
        $_POST['course']
    ]);
    header("Location: index.php");
    exit;
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
    exit;
}

/* ================= FETCH FOR EDIT ================= */
$editStudent = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editStudent = $stmt->fetch();
}

/* ================= UPDATE ================= */
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update'])) {
    $stmt = $pdo->prepare(
        "UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['course'],
        $_POST['id']
    ]);
    header("Location: index.php");
    exit;
}

/* ================= READ ================= */
$students = $pdo->query("SELECT * FROM students")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Database</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*{margin:0;padding:0;box-sizing:border-box}

body{
    font-family:Inter,sans-serif;
    min-height:100vh;
    background:url('lib.jpg') center/cover no-repeat;
    display:flex;
    flex-direction:column;
    align-items:center;
    padding:40px 20px;
    position:relative
}

body::before{
    content:"";
    position:fixed;
    inset:0;
    background:inherit;
    filter:blur(14px);
    transform:scale(1.1);
    z-index:-1
}

h2{
    color:#fff;
    font-size:28px;
    font-weight:700;
    margin-bottom:20px;
    text-shadow:0 6px 20px rgba(0,0,0,.35)
}

form{
    width:100%;
    max-width:460px;
    background:rgba(255,255,255,.9);
    backdrop-filter:blur(18px);
    padding:28px;
    border-radius:20px;
    box-shadow:0 30px 60px rgba(0,0,0,.25);
    margin-bottom:40px
}

input{
    width:100%;
    padding:14px 16px;
    margin-bottom:14px;
    border-radius:12px;
    border:1px solid #d1d5db;
    background:#f9fafb;
    font-size:14px
}

input:focus{
    outline:none;
    border-color:#6366f1;
    box-shadow:0 0 0 3px rgba(99,102,241,.2)
}

button{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:none;
    background:linear-gradient(135deg,#6366f1,#3b82f6);
    color:#fff;
    font-size:15px;
    font-weight:600;
    cursor:pointer
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 30px rgba(99,102,241,.35)
}

form a{
    display:block;
    text-align:center;
    margin-top:12px;
    color:#6b7280;
    text-decoration:none
}

table{
    width:100%;
    max-width:1100px;
    background:rgba(255,255,255,.9);
    backdrop-filter:blur(18px);
    border-radius:20px;
    border-collapse:separate;
    border-spacing:0;
    overflow:hidden;
    box-shadow:0 30px 60px rgba(0,0,0,.25)
}

th{
    background:linear-gradient(135deg,#6366f1,#3b82f6);
    color:#fff;
    padding:16px;
    font-size:14px;
    font-weight:600;
    text-transform:uppercase
}

td{
    padding:14px;
    font-size:14px;
    text-align:center;
    border-bottom:1px solid #e5e7eb
}

tr:last-child td{border-bottom:none}
tr:hover{background:rgba(99,102,241,.08)}

td a{
    padding:6px 12px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    margin:0 4px
}

td a.edit{background:rgba(99,102,241,.15);color:#4f46e5}
td a.delete{background:rgba(239,68,68,.15);color:#dc2626}
</style>
</head>

<body>

<h2><?= $editStudent ? "Edit Student" : "Add Student" ?></h2>

<form method="POST">
<input type="hidden" name="id" value="<?= $editStudent['Id'] ?? '' ?>">

<input type="text" name="name" placeholder="Name"
value="<?= $editStudent['Name'] ?? '' ?>" required>

<input type="email" name="email" placeholder="Email"
value="<?= $editStudent['Email'] ?? '' ?>" required>

<input type="text" name="course" placeholder="Course"
value="<?= $editStudent['course'] ?? '' ?>" required>

<?php if ($editStudent): ?>
<button type="submit" name="update">Update Student</button>
<a href="index.php">Cancel</a>
<?php else: ?>
<button type="submit" name="add">Add Student</button>
<?php endif; ?>
</form>

<table>
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Actions</th>
</tr>

<?php foreach ($students as $s): ?>
<tr>
<td><?= $s['Id'] ?></td>
<td><?= $s['Name'] ?></td>
<td><?= $s['Email'] ?></td>
<td><?= $s['course'] ?></td>
<td>
<a class="edit" href="?edit=<?= $s['Id'] ?>">Edit</a>
<a class="delete" href="?delete=<?= $s['Id'] ?>" onclick="return confirm('Delete student?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
