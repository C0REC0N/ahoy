<?php
# Access session.
session_start();

# Open database connection.
require('connect_db.php');

if(isset($_POST['userID']) && isset($_POST['shipID'])) {
    $userID = mysqli_real_escape_string($link, $_POST['userID']);
    $shipID = mysqli_real_escape_string($link, $_POST['shipID']);

    // Delete the member from the team based on their userID and shipID
    $deleteMemberQuery = "DELETE FROM team WHERE userID = $userID AND shipID = $shipID";
    $deleteMemberResult = mysqli_query($link, $deleteMemberQuery);
    if($deleteMemberResult) {
        $_SESSION['msg'] = "You have left the ship!";
        echo "<script>window.location.href = 'ship_select.php';</script>";
    } else {
        echo "Error: " . mysqli_error($link);
    }
} else {
    echo "Username or shipID not provided.";
}

// Close the database connection
mysqli_close($link);
?>
