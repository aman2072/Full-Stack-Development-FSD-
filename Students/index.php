<?php
require "db.php";

$sql = "SELECT * FROM students";
$stmt = $conn->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
</head>
<body>

<h2>Students List</h2>
<a href="add.php">Add New Student</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($students as $student): ?>
    <tr>
        <td><?= $student['id']; ?></td>
        <td><?= $student['name']; ?></td>
        <td><?= $student['email']; ?></td>
        <td><?= $student['course']; ?></td>
        <td>
            <a href="edit.php?id=<?= $student['id']; ?>">Edit</a> |
            <a href="delete.php?id=<?= $student['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
