<?php
require_once "db.php";

$id = $_GET['id'];

$sql = "DELETE FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

header("Location: index.php");
?>
