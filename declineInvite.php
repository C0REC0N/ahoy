<?php
session_start();
require('connect_db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['shipID'])) {
        $userID = $_SESSION['userID'];
        $shipID = $_POST['shipID'];


            $deleteInviteQuery = "DELETE FROM memberInvites WHERE userID = $userID AND shipID = $shipID";
            if (mysqli_query($link, $deleteInviteQuery)) {
                header("Location: ship_select.php");
                exit();
            } else {
                echo "Error deleting invitation: " . mysqli_error($link);
            }
        }
    } else {
        echo "Invalid request.";
    }

?>
