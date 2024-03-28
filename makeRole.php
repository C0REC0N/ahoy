 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$username = $_POST['username'];

$userID = $_SESSION['userID'];

$role = $_POST['role'];

if (isset ($_POST['shipID'])){
	
	$shipID = $_POST['shipID'];
	$query = "SELECT user.userID FROM user JOIN team ON team.userID = user.userID WHERE username = '$username' AND shipID = $shipID AND user.userID <> $userID AND roleChoice <> 'platform_manager'";
	
} else {

	$query = "SELECT userID FROM user WHERE username = '$username' AND userID <> $userID AND roleChoice <> 'platform_manager'";
}

$result = mysqli_query($link, $query);

    if($result) {
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
			 $query = "UPDATE user SET roleChoice = '$role' WHERE userID = ".$row['userID']."";
			 $result = mysqli_query($link, $query);
			 
			 if ($result){
				$_SESSION['msg'] = "$username is now a $role";
				if (isset ($shipID)){
					echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
				} else {
					echo "<script>window.location.href = 'privileges.php';</script>";
				}
			 } else {
				$_SESSION['msg'] = "$username is not $role";
				if (isset ($shipID)){
					echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
				} else {
					echo "<script>window.location.href = 'privileges.php';</script>";
				}
			}
		} else {
				$_SESSION['msg'] = "$username does not exist";
				if (isset ($shipID)){
					echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
				} else {
					echo "<script>window.location.href = 'privileges.php';</script>";
				}
			}
	} else {
				$_SESSION['msg'] = "$username does not exist";
				if (isset ($shipID)){
					echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
				} else {
					echo "<script>window.location.href = 'privileges.php';</script>";
				}
			}
				
		
				?>
