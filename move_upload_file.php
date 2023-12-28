<?php

// Include the AWS SDK for PHP autoloader
//require 'vendor/autoload.php'; // Replace with the actual path to aws-autoloader.php

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

//Local upload details
$localUploadDirectory = "../upload/"; // Replace with the actual path to your local upload directory

// AWS S3 details
$AWS_S3_BUCKET = "nbrs3bucket";
$AWS_S3_DIRECTORY = "testfolder";
$AWS_REGION = "ap-south-1";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the file was uploaded without errors
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        // Local file upload
        $target_dir = $localUploadDirectory;
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        // Check if the file already exists
        if (file_exists($target_file)) {
            echo "Sorry, the file already exists locally.";
        } else {
            // Move the uploaded file to the specified local directory
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded locally.";

                // Upload the file to AWS S3
                $s3 = new S3Client([
                    'version' => 'latest',
                    'region' => $AWS_REGION,
                ]);

                try {
                    $result = $s3->putObject([
                        'Bucket' => $AWS_S3_BUCKET,
                        'Key' => $AWS_S3_DIRECTORY . '/' . basename($_FILES["fileToUpload"]["name"]),
                        'SourceFile' => $target_file,
                        'ACL' => 'public-read', // Adjust ACL as needed
                    ]);

                    // Print the URL of the uploaded file in S3
                    echo 'Uploaded file to S3: ' . $result['ObjectURL'] . PHP_EOL;
                } catch (AwsException $e) {
                    // Handle errors
                    echo 'Error uploading file to S3: ' . $e->getMessage() . PHP_EOL;
                }

                // Optionally, you can remove the local file after uploading it to S3
                unlink($target_file);
            } else {
                echo "Sorry, there was an error uploading your file locally.";
            }
        }
    } else {
        echo "Error: " . $_FILES["fileToUpload"]["error"];
    }
}
?>