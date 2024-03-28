<?php

// Access session.
session_start();
require('connect_db.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["fileID"])) {
    $fileID = $_GET["fileID"];

    $selectFileQuery = "SELECT filename, filedata FROM test_files WHERE fileID = ?";
    $statement = mysqli_prepare($link, $selectFileQuery);

    if ($statement) {
        mysqli_stmt_bind_param($statement, "i", $fileID);
        $success = mysqli_stmt_execute($statement);

        if ($success) {
            mysqli_stmt_bind_result($statement, $filename, $filedata);
            mysqli_stmt_fetch($statement);

            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo $filedata;
        } else {
            echo "Error executing SQL statement: " . mysqli_error($link);
        }
    } else {
        echo "Error preparing SQL statement: " . mysqli_error($link);
    }

    mysqli_stmt_close($statement);
    mysqli_close($link);
} else {
    echo "File ID not provided or invalid request method.";
}
?>
