<?php
session_start();
require('connect_db.php');

// Debugging: Check if script is being executed
echo "Script is executed!<br>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['shipID'])) {
        $userID = $_SESSION['userID'];
        $shipID = $_POST['shipID'];

        // Debugging: Print received shipID
        echo "Received shipID: $shipID<br>";

        $acceptInvite = "INSERT INTO team (userID, shipID) VALUES ($userID, $shipID)";
        if (mysqli_query($link, $acceptInvite)) {
            //delete invite if accepted by system
            $deleteInviteQuery = "DELETE FROM memberInvites WHERE userID = $userID AND shipID = $shipID";
            if (mysqli_query($link, $deleteInviteQuery)) {
                header("Location: ship_select.php");
                exit();
            } else {
                echo "Error deleting invitation: " . mysqli_error($link);
            }
        }
            else {
            echo "Error accepting invite: " . mysqli_error($link);
        }
    } else {
        echo "Invalid request.";
    }
}
?>
