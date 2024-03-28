<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect the user to the login page or any other appropriate action
    header("Location: login.php");
    exit();
}

require('connect_db.php'); // Include database connection

// Check if the ship ID is posted
if (isset($_POST['shipID'])) {
    $shipID = mysqli_real_escape_string($link, $_POST['shipID']);

    // Delete all tasks associated with shipID
    $deleteTasksQuery = "DELETE FROM task WHERE shipID = $shipID";
    $deleteTasksResult = mysqli_query($link, $deleteTasksQuery);

    if ($deleteTasksResult) {
        // Delete all messages from the Chat table where shipID matches
        $deleteChatQuery = "DELETE FROM Chat WHERE shipID = $shipID";
        $deleteChatResult = mysqli_query($link, $deleteChatQuery);

        if ($deleteChatResult) {
            // Delete all messages from the PrivateChat table where shipID matches
            $deletePrivateChatQuery = "DELETE FROM PrivateChat WHERE shipID = $shipID";
            $deletePrivateChatResult = mysqli_query($link, $deletePrivateChatQuery);

            if ($deletePrivateChatResult) {
                // Delete all the files that are part of the shipID
                $deleteFilesQuery = "DELETE FROM test_files WHERE shipID = $shipID";
                $deleteFilesResult = mysqli_query($link, $deleteFilesQuery);

                if ($deleteFilesResult) {
                    // Delete all crew members part of the shipID
                    $deleteTeamQuery = "DELETE FROM team WHERE shipID = $shipID";
                    $deleteTeamResult = mysqli_query($link, $deleteTeamQuery);

                    if ($deleteTeamResult) {
                        // Delete all pending invites to the team with the matching shipID
                        $deleteInvitesQuery = "DELETE FROM memberInvites WHERE shipID = $shipID";
                        $deleteInvitesResult = mysqli_query($link, $deleteInvitesQuery);

                        if ($deleteInvitesResult) {
                            // Delete the ship itself with the shipID requested to delete
                            $deleteShipQuery = "DELETE FROM Ship WHERE shipID = $shipID";
                            $deleteShipResult = mysqli_query($link, $deleteShipQuery);

                            if ($deleteShipResult) {
                                echo "<script>alert('The ship and associated data have been successfully deleted.');
                                window.location.href = 'ship_select.php';</script>";
                                exit();
                            } else {
                                echo "Error deleting the ship: " . mysqli_error($link);
                            }
                        } else {
                            echo "Error deleting member invites: " . mysqli_error($link);
                        }
                    } else {
                        echo "Error deleting crew members of the ship: " . mysqli_error($link);
                    }
                } else {
                    echo "Error deleting files associated with the ship: " . mysqli_error($link);
                }
            } else {
                echo "Error deleting private chat messages: " . mysqli_error($link);
            }
        } else {
            echo "Error deleting chat messages: " . mysqli_error($link);
        }
    } else {
        echo "Error deleting tasks: " . mysqli_error($link);
    }
} else {
    echo "Ship ID not provided.";
}

// Close the database connection
mysqli_close($link);
?>
