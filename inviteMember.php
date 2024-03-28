<?php
session_start();

#Open a database connection
require('connect_db.php');

#Check to see if a member has been inputted into form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['shipID']) && !empty($_POST['shipID'])) {
        $shipID = $_POST['shipID'];
    } else {
        echo "Ship ID is not provided.";
    }

    $username = $_POST['username'];

    #Check to see if the username matches existing username
    $checkUserQuery = "SELECT userID, roleChoice FROM user WHERE username = '$username'";
    $result = mysqli_query($link, $checkUserQuery);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userID = $row['userID'];
        $userRole = $row['roleChoice'];

        if(isset($_SESSION['userID'])) {
            
            $sessionUserID = $_SESSION['userID'];

            // Prepare and execute the query to retrieve role based on userID
            $query = "SELECT roleChoice FROM user WHERE userID = '$sessionUserID'";
            $result = mysqli_query($link, $query);
            
            if ($result) {
                // Check if any rows are returned
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $sessionRole = $row['roleChoice'];
                } else {
                    echo "No role found for this user.";
                    exit;
                }
            } else {
                echo "Error executing query: " . mysqli_error($link);
                exit;
            }
        } else {
            echo "UserID not set in session.";
            exit;
        }

        if ($sessionRole == 'teacher' || $sessionRole == 'student') {
            // If session role is teacher or student, only allow inviting teacher or student
            if ($userRole != 'teacher' && $userRole != 'student') {
                $_SESSION['msg'] = "You can only invite teachers or students to your ship.";
                header("Location: ship_page.php?shipID=$shipID");
                exit;
            }
        } elseif ($sessionRole == 'employee' || $sessionRole == 'manager') {
            // If session role is employee or manager, only allow inviting employee or manager
            if ($userRole != 'employee' && $userRole != 'manager') {
                $_SESSION['msg'] = "You can only invite employees or managers to your ship.";
                header("Location: ship_page.php?shipID=$shipID");
                exit;
            }
        }

        #Check to see if the username is already a member of the ship
        $checkIfMember = "SELECT * FROM team WHERE userID = '$userID' AND shipID = '$shipID'";
        $result = mysqli_query($link, $checkIfMember);

            
        if (mysqli_num_rows($result) == 0) {
        
            # Query to store the invitation in the memberInvites table
            $insertInviteQuery = "INSERT INTO memberInvites (userID, shipID) VALUES ('$userID', '$shipID')";
            $result = mysqli_query($link, $insertInviteQuery);

            if ($result) {
                $_SESSION['msg'] = "Invitation sent to $username!";    
                header("Location: ship_page.php?shipID=$shipID");
                exit;
            } else {
                $_SESSION['msg'] = 'Error sending invitation';
                header("Location: ship_page.php?shipID=$shipID");
                exit;
            }
            
        } else {
            $_SESSION['msg'] = "$username already a member";
            header("Location: ship_page.php?shipID=$shipID");
            exit;
        }
    } else {
        
        $_SESSION['msg'] = 'Username was not found';
        header("Location: ship_page.php?shipID=$shipID");
        exit;
    }

    mysqli_close($link);
}
?>
