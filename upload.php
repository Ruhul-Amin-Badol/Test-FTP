<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the file was uploaded without errors
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $target_dir = "../upload/"; // specify your upload directory
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        // Check if the file already exists
        if (file_exists($target_file)) {
            echo "Sorry, the file already exists.";
        } else {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Error: " . $_FILES["fileToUpload"]["error"];
    }
}
?>