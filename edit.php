<?php
require 'db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE id = :id");
$stmt->execute([':id' => $id]);
$student = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE students SET name=:name, email=:email, course=:course WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':course' => $_POST['course'],
        ':id' => $id
    ]);

    header("Location: index.php");
}
?>

<form method="POST">
    <h2>Edit Student</h2>
    Name: <input type="text" name="name" value="<?= $student['name'] ?>"><br><br>
    Email: <input type="email" name="email" value="<?= $student['email'] ?>"><br><br>
    Course: <input type="text" name="course" value="<?= $student['course'] ?>"><br><br>
    <button type="submit">Update</button>
</form>
