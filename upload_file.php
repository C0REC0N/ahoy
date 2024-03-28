<?php

// Access session.
session_start();
require('connect_db.php');

// Check if request method is POST and that there has been a file and shipID posted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"]) && isset($_POST["shipID"])) {
    $file = $_FILES["file"];   //store file and ship id in relevant variables
    $shipID = $_POST["shipID"];

    //Extract all the data from the uploaded file
    $fileName = $file["name"];
    $fileSize = $file["size"];
    $fileType = $file["type"];
    $tempFilePath = $file["tmp_name"];

    //read the content of the uploaded file
    $fileContent = file_get_contents($tempFilePath);

    // Prepare SQL statement to insert file into 'test_files' database
    $insertFileQuery = "INSERT INTO test_files (shipID, filename, filesize, filetype, filedata) VALUES (?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($link, $insertFileQuery);

    if ($statement) {
        mysqli_stmt_bind_param($statement, "isiss", $shipID, $fileName, $fileSize, $fileType, $fileContent);
        $ifSuccess = mysqli_stmt_execute($statement);

        if ($ifSuccess) {
            echo "<script>alert('File uploaded successfully!');
             window.location.href = 'ship_page.php?shipID=$shipID';</script>";
        } else {
            echo "<script>alert('Error uploading file: " . mysqli_error($link) . "');
             window.location.href = 'ship_page.php?shipID=$shipID';</script>";
        }
    } else {
        echo "<script>alert('Error preparing SQL statement: " . mysqli_error($link) . "');
         window.location.href = 'ship_page.php?shipID=$shipID';</script>";
    }

    // Close statement and database connection
    mysqli_stmt_close($statement);
    mysqli_close($link);
} else {
    echo "<script>alert('No file uploaded or shipID provided!');
     window.location.href = 'ship_page.php';</script>";
}
?>
