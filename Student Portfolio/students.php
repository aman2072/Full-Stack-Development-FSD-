<?php
include "header.php";

$file = "students.txt";

echo "<h2>Student List</h2>";

if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $line) {
        list($name, $email, $skills) = explode(",", $line);
        $skillsArray = explode("|", $skills);

        echo "<p>";
        echo "<strong>Name:</strong> $name<br>";
        echo "<strong>Email:</strong> $email<br>";
        echo "<strong>Skills:</strong> ";
        print_r($skillsArray);
        echo "</p><hr>";
    }
} else {
    echo "No student data found.";
}

include "footer.php";
