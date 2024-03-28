<?php
# Access session.
session_start();

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
            
            if ($_SESSION['userID'] == $userID) {
                echo "<script>alert('You cannot remove yourself from the team!');
                window.location.href = 'ship_page.php?shipID=$shipID';</script>";
                exit();}

            // Delete the member from the team based on their userID and shipID
            $deleteMemberQuery = "DELETE FROM team WHERE userID = $userID AND shipID = $shipID";
            $deleteMemberResult = mysqli_query($link, $deleteMemberQuery);

            if($deleteMemberResult) {
                 $_SESSION['msg'] = "$username has been removed from the team.";
                echo "<script>window.location.href = 'ship_page.php?shipID=$shipID';</script>";
            } else {
                echo "Error: " . mysqli_error($link);
            }
        } else {
            echo "Username not found.";
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
