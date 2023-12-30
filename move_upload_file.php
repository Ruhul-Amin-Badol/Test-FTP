<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$localUploadDirectory = "../upload/";
$AWS_S3_BUCKET = "nbrs3bucket";
$AWS_S3_DIRECTORY = "testfolder";
$AWS_REGION = "ap-south-1";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $target_dir = $localUploadDirectory;
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        if (file_exists($target_file)) {
            echo "Sorry, the file already exists locally.";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded locally.";

                // $s3 = new S3Client([
                //     'version' => 'latest',
                //     'region' => $AWS_REGION,
                // ]);

                try {
                    $result = $s3->putObject([
                        'Bucket' => $AWS_S3_BUCKET,
                        'Key' => $AWS_S3_DIRECTORY . '/' . basename($_FILES["fileToUpload"]["name"]),
                        'SourceFile' => $target_file,
                        'ACL' => 'public-read',
                    ]);

                    echo 'Uploaded file to S3: ' . $result['ObjectURL'] . PHP_EOL;
                } catch (AwsException $e) {
                    echo 'Error uploading file to S3: ' . $e->getMessage() . PHP_EOL;
                }

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
