<?php
require_once "db.php";

if (isset($_POST['add_student'])) {
    $stmt = $conn->prepare(
        "INSERT INTO students (name, email, course) VALUES (?, ?, ?)"
    );
    $stmt->bind_param(
        "sss",
        $_POST['name'],
        $_POST['email'],
        $_POST['course']
    );
    $stmt->execute();
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

$editStudent = null;

if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $result = $stmt->get_result();
    $editStudent = $result->fetch_assoc();
}

if (isset($_POST['update_student'])) {
    $stmt = $conn->prepare(
        "UPDATE students SET name=?, email=?, course=? WHERE id=?"
    );
    $stmt->bind_param(
        "sssi",
        $_POST['name'],
        $_POST['email'],
        $_POST['course'],
        $_POST['id']
    );
    $stmt->execute();
    header("Location: index.php");
    exit;
}

$result = $conn->query("SELECT * FROM students");
$students = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student CRUD</title>
</head>
<body>

<h2><?= $editStudent ? "Edit Student" : "Add New Student" ?></h2>

<form method="POST">
    <?php if ($editStudent): ?>
        <input type="hidden" name="id" value="<?= $editStudent['id'] ?>">
    <?php endif; ?>

    <input type="text" name="name"
           value="<?= $editStudent['name'] ?? '' ?>" required><br><br>

    <input type="email" name="email"
           value="<?= $editStudent['email'] ?? '' ?>" required><br><br>

    <input type="text" name="course"
           value="<?= $editStudent['course'] ?? '' ?>" required><br><br>

    <button type="submit"
            name="<?= $editStudent ? 'update_student' : 'add_student' ?>">
        <?= $editStudent ? 'Update Student' : 'Add Student' ?>
    </button>
</form>

<hr>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Action</th>
    </tr>

    <?php foreach ($students as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><?= $s['name'] ?></td>
            <td><?= $s['email'] ?></td>
            <td><?= $s['course'] ?></td>
            <td>
                <a href="?edit=<?= $s['id'] ?>">Edit</a> |
                <a href="?delete=<?= $s['id'] ?>"
                   onclick="return confirm('Delete this student?')">
                   Delete
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
