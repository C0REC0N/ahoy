<?php
session_start();

require('connect_db.php'); // Include database connection

// check to see if the shipID, shipname, and shipdesc are posted
if (isset($_POST['shipID'], $_POST['shipname'], $_POST['shipdesc'])) {
    $shipID = mysqli_real_escape_string($link, $_POST['shipID']);
    $shipname = mysqli_real_escape_string($link, $_POST['shipname']);
    $shipdesc = mysqli_real_escape_string($link, $_POST['shipdesc']);

    //Update the name and description of the ship to the new posted details
    $updateShipDetails = "UPDATE Ship SET shipName = '$shipname', shipDescription = '$shipdesc' WHERE shipID = $shipID";
    $updateShipDetailsResult = mysqli_query($link, $updateShipDetails);

    if ($updateShipDetailsResult) {
        echo "<script>alert('Ship details updated successfully.');       
        window.location.href = 'ship_page.php?shipID=$shipID';</script>";
        exit();
    } else {
        echo "Error updating ship details: " . mysqli_error($link);
    }
} else {
    echo "Ship ID, ship name, or ship description not provided.";
}

// Close the database connection
mysqli_close($link);
?>
