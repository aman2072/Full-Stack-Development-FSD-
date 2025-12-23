<?php
include "header.php";
require "functions.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_FILES['portfolio'])) {
            throw new Exception("No file selected.");
        }

        $fileName = uploadPortfolioFile($_FILES['portfolio']);
        $message = "File uploaded successfully as $fileName";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<h2>Upload Portfolio File</h2>

<p style="color:blue;"><?php echo $message; ?></p>

<form method="post" enctype="multipart/form-data">
    Select File (PDF/JPG/PNG, max 2MB):
    <input type="file" name="portfolio"><br><br>
    <button type="submit">Upload</button>
</form>

<?php include "footer.php"; ?>
