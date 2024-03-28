<?php
# Open database connection.
require('connect_db.php');

if(isset($_POST['username']) && isset($_POST['shipID'])) {
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $shipID = mysqli_real_escape_string($link, $_POST['shipID']);

    $retrieveUserID = "SELECT userID FROM user WHERE username = '$username'";
    $getUserIDResult = mysqli_query($link, $retrieveUserID);

    if($getUserIDResult) {
        if(mysqli_num_rows($getUserIDResult) > 0) {
            $row = mysqli_fetch_assoc($getUserIDResult);
            $userID = $row['userID'];

            // Delete the member from the team based on their userID and shipID
            $updateAdminQuery = "UPDATE team SET isAdmin = 1 WHERE userID = $userID AND shipID = $shipID";
            $updateAdminResult = mysqli_query($link, $updateAdminQuery);

            if($updateAdminResult) {
                echo "<script>alert('$username has been made an admin.');
                window.location.href = 'ship_page.php?shipID=$shipID';</script>";
            } else {
                echo "Error: " . mysqli_error($link);
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "Error: " . mysqli_error($link);
    }
} else {
    echo "Username or shipID not provided.";
}

// Close the database connection
mysqli_close($link);
?>
