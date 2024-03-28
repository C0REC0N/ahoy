<!DOCTYPE html>
<?php
# Access session.
session_start();

$_SESSION['msg'] = null;

$msg = $_SESSION['msg'];

$page = 'task';

# Open database connection.
require ( 'connect_db.php' ) ;

//storing if the current user is an admin or an owner for attribute based access controls
if(isset($_GET['shipID'])) {            
    $shipID = $_GET['shipID'];

    $isOwnerQuery = "SELECT user.userID AS ownerID, team.isOwner
    FROM user 
    INNER JOIN team ON user.userID = team.userID 
    WHERE team.shipID = $shipID AND team.isOwner = 1";
    $result = mysqli_query($link, $isOwnerQuery);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $isOwnerID = $row['ownerID'];
    } 
    else {
        echo "Error fetching owner details: " . mysqli_error($link);
    }

    $isAdminQuery = "SELECT user.userID AS adminID, team.isAdmin
    FROM user 
    INNER JOIN team ON user.userID = team.userID 
    WHERE team.shipID = $shipID AND team.isAdmin = 1";
    $result = mysqli_query($link, $isAdminQuery);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $isAdminID = $row['adminID'];
    } 
    else {
        echo "Error fetching owner details: " . mysqli_error($link);
    }
}
?>


<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> AHOY </title>
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="parrot.css">
    <script src="main.js"></script> 
	<script src="parrot.js"></script> 

    <?php
	$userID = $_SESSION['userID'];
	//Checking to see if the user is new
	$checkFirst = "SELECT first_login FROM user WHERE userID = $userID";
	$result = mysqli_query($link, $checkFirst);

	// Assigning result to variable
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$first = $row['first_login'];
		}
	}
	else{
	
	}
	?>
</head>

<body style="background-color: lightcyan;" onload="welcome(<?php echo "$first"; ?>, false, null)">
<header>
	<?php include 'parrot.php';

    $changeFirst = "UPDATE user SET first_login = 0 WHERE userID = $userID";
	$result = mysqli_query($link, $changeFirst);
	
	?>
</header>
    <div class="page-wrapper">
        <div class="row">
            <div class="flex-left">
                <div class="side-nav">
                    <div class="side">
                        <a href="ship_select.php" class="logo">
                            <img src="AHOYYYYY.png" class="logo-img">
                        </a>
                        <a>
                            <img src="starfish.png" class="profile-image">
                            <p style="text-align: center; font-family: papyrus; color: white"><?php echo "{$_SESSION['username']}"; ?></p>
                        </a>
                        <ul class="prof-links">
                            <li><a href="ship_page.php?shipID=<?php echo $shipID; ?>"><button class="login-button"> <p style="text-align: center;"> Return To Ship </p> </button></a></li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="flex-right">
                <h1 style="text-align: center; padding: 20px;">
                   Select Your Quest
                </h1>
                <?php
                # Retrieve all current ships from 'ship' database table.
                $taskQuery = "SELECT * 
                FROM task 
                WHERE shipID = $shipID";
                
                $r = mysqli_query( $link, $taskQuery ) ;
                echo '<div class="row">';
                if ( mysqli_num_rows( $r ) > 0 )
                {
                    while ($row = mysqli_fetch_assoc($r)) :
                        $taskID = $row['taskID'];
                        $taskName = $row['taskName'];
                        $staskDesc = $row['taskDesc'];

                        
						if ($row['completed'] == 1) : ?>
							
							<button class="ship-button" style="text-align: center; padding: 60px; font-size: 30px; font-family: papyrus; text-decoration: none; opacity: 0.2;" onclick="openTaskPopup('<?php echo $taskID; ?>');">
							<?php echo $taskName; ?>
							</button>
							<?php
						else :
							?>
							<button class="ship-button" style="text-align: center; padding: 60px; font-size: 30px; font-family: papyrus; text-decoration: none;" onclick="openTaskPopup('<?php echo $taskID; ?>');">
							<?php echo $taskName; ?>
							</button>
							<?php
						endif;

 
                    endwhile;  
                }
                
                echo'<button class="ship-button" onclick="openForm()">
                <img src="PLUS-2.png" style="height: 50px; width: 50px">
                <p>ADD NEW QUEST</p>
                </button></row>' 

                ?>
               <div class="row form-popup" id="myForm">
                    <form style="border: lightcyan;" method="post" action="addTask.php">
                      <h1 style="text-align: center;">Create A Quest!</h1>
                  
                      <label for="taskName"><p style="text-align: center;">Name Yer Quest</p></label>
                      <input for="taskName" type="text" placeholder="Enter Name Here" name="taskName" required>
                  
                      <label for="taskDesc"><p style="text-align: center;">Describe Yer Quest</p></label>
                      <input for="taskDesc" type="text" placeholder="Enter Description Here" name="taskDesc" required>
                  
					<input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                      <button type="submit" class="btn">Create Quest</button>
                      <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                    </form>
                </div>  
                <div class="row form-popup" id="imgSelect">

                </div>
            </div>
            <!--Div for opening the task  popup-->
            
                    
                        <?php
                        //Displaying a list of all files in the ship
                        $taskQuery = "SELECT taskID, taskName, taskDesc, completed FROM task  WHERE shipID = $shipID";
                        $taskResult = mysqli_query($link, $taskQuery);

                        if ($taskResult->num_rows > 0) {
                            while($row = $taskResult->fetch_assoc()) {
								
								echo " <div id='".$row['taskID']."' class='file-popup'><div class='file-content-popup'>";
								
								if ($row['completed'] == 1){
									echo "<h2 style='text-align: center'><b style='color: green;'>Completed</b> Quest</h2><p>";
								} else {
									
									echo "<h2 style='text-align: center'>Current Quest</h2><p>";
								}
							
                                echo "Task Name: " . $row["taskName"]."<br><br>";
                                echo "Task Description: " . $row["taskDesc"]."<br><br>";
								
	
								
								echo " </p><br>";
								if ($row['completed'] == 0) {
									echo "<a href='completeTask.php?shipID=$shipID&taskID=".$row['taskID']."'><button type='button' class='btn'>Complete Quest</button></a>";
								} 
								echo "
								
						<a href='deleteTask.php?shipID=$shipID&taskID=".$row['taskID']."'><button type='button' class='btn cancel'>Delete Quest</button></a>
                        <button type='button' class='btn cancel' onclick='closeTaskPopup(".$row['taskID'].")'>Close Quest</button>
                        </div></div>";
                            }
                        }
                        ?>
                    
                    
        </div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">



</script>

</html>
