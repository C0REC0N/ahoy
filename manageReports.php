 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$username = $_POST['username'];

$userID = $_SESSION['userID'];

$sign = $_POST['option'];

if (isset ($_POST['shipID'])){
	
	$shipID = $_POST['shipID'];
	$query = "SELECT user.userID FROM user INNER JOIN team ON user.userID = team.userID WHERE team.shipID = $shipID AND username = '$username' AND user.userID <> $userID AND roleChoice <> 'platform_manager'";
	
} else {

	$query = "SELECT userID FROM user WHERE username = '$username' AND userID <> $userID AND roleChoice <> 'platform_manager'";
}

$result = mysqli_query($link, $query);

    if($result) {
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
			
			if($sign != 0){
				$query = "UPDATE user SET reports = reports $sign 1 WHERE userID = ".$row['userID']."";
			} else {
				
				$query = "UPDATE user SET reports = 0 WHERE userID = ".$row['userID']."";
			}
			 $result = mysqli_query($link, $query);
			 
			 if ($result){
				$_SESSION['msg'] = "$username reports updated";
				if (isset ($shipID)){
					echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
				} else {
					echo "<script>window.location.href = 'privileges.php';</script>";
				}
			 } else {
				$_SESSION['msg'] = "$username not updated";
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
