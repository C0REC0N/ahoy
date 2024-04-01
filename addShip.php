<?php
# Access session.
session_start();
# Check form submitted.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # Connect to the database.
    require('connect_db.php');
    
    # Initialize an error array.
    $errors = array();

    # Check for a ship name:
    if (empty($_POST['shipName'])) {
        $errors[] = 'Please enter a ship name.';
    } else {
        $sn = mysqli_real_escape_string($link, trim($_POST['shipName']));
    }

    # Check for a ship description
    if (!empty($_POST['shipDescription'])) {
        $sd = mysqli_real_escape_string($link, trim($_POST['shipDescription']));
    } else {
        $errors[] = 'Please enter a description of your ship.';
    }

    $existQuery = "SELECT * FROM team JOIN Ship ON team.shipID = Ship.shipID WHERE shipName = '$sn' AND userID = ".$_SESSION['userID']."";
        
    $exist = @mysqli_query($link, $existQuery);
	
	if ( mysqli_num_rows($exist) > 0 ){
		
		$_SESSION['msg'] = "You already have a ship called ".$sn."!";
		header("Location: ship_select.php");
		exit();
	}

    # On success update database for new ship name and description
    if (empty($errors)) {
        $insertShipQuery = "INSERT INTO Ship (shipName, shipDescription) VALUES ('$sn', '$sd')";
        
        
        $r = @mysqli_query($link, $insertShipQuery);
        
        if ($r) {

            $shipID = mysqli_insert_id($link);

                    $insertTeamQuery = "INSERT INTO team (userID, shipID, isOwner, isAdmin) VALUES ('{$_SESSION['userID']}', '$shipID',1, 1)";
                    $r2 = @mysqli_query($link, $insertTeamQuery);


                    if ($r2){
                        $_SESSION['msg'] = "$sn has been built!";
                        header("Location: ship_select.php");
                        exit();
                    } else {
                        echo "Error updating record: " . mysqli_error($link);
                    } }
        
        # Close database connection.
        mysqli_close($link);
    } 
    #else echo error messages
    else {
        echo '<body style="color:white"> <div style="margin-top:10px;"class="container"><div class="alert alert-dark alert-dismissible fade show">
        <h1><strong>Error!</strong></h1><p>The following error(s) occurred:<br>';
        foreach ($errors as $msg) {
            echo " - $msg<br>";
        }
        echo 'Please try again.</div></div></body>';
        
        # Close database connection.
        mysqli_close($link);
    }
}
?>
