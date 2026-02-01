<?php
include "header.php";
require "functions.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = formatName($_POST['name']);
        $email = $_POST['email'];
        $skillsInput = $_POST['skills'];

        if (empty($name) || empty($email) || empty($skillsInput)) {
            throw new Exception("All fields are required.");
        }

        if (!validateEmail($email)) {
            throw new Exception("Invalid email format.");
        }

        $skillsArray = cleanSkills($skillsInput);
        saveStudent($name, $email, $skillsArray);

        $message = "Student information saved successfully.";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<h2>Add Student Info</h2>

<p style="color:green;"><?php echo $message; ?></p>

<form method="post">
    Name: <input type="text" name="name"><br><br>
    Email: <input type="email" name="email"><br><br>
    Skills (comma-separated): <input type="text" name="skills"><br><br>
    <button type="submit">Save Student</button>
</form>

<?php include "footer.php"; ?>
