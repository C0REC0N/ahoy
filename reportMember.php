 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$username = $_POST['username'];

$userID = $_POST['userID'];

$shipID = $_POST['shipID'];

$role = $_POST['role'];

$reason = $_POST['reason'];

if ($role == 'teacher' || $role == 'manager'){

	$query = "SELECT user.userID AS reported, roleChoice FROM user JOIN team ON user.userID = team.userID WHERE username = '$username' AND user.userID <> $userID AND roleChoice <> 'platform_manager' AND roleChoice = '$role' AND shipID = $shipID";
} else {
	
	$query = "SELECT user.userID AS reported, roleChoice FROM user JOIN team ON user.userID = team.userID WHERE username = '$username' AND user.userID <> $userID AND roleChoice <> 'platform_manager' AND shipID = $shipID";
}


$result = mysqli_query($link, $query);
   
if(mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$reported = $row['reported'];
	
	if ($row['roleChoice'] == 'teacher' || $row['roleChoice'] == 'manager'){
		$adminQuery = "INSERT INTO reports (reported, reason, userID, shipID, forPM) VALUES ($reported, '$reason', $userID, $shipID, 1)";
	} else {
		
		$authQuery = "SELECT user.userID FROM team JOIN user ON user.userID = team.userID WHERE shipID = $shipID AND (isAdmin = 1 OR isOwner = 1) AND (roleChoice = 'teacher' OR roleChoice = 'manager')";
		$authResult = mysqli_query($link, $authQuery);
		
		if(mysqli_num_rows($authResult) > 0) {
		
			$adminQuery = "INSERT INTO reports (reported, reason, userID, shipID) VALUES ($reported, '$reason', $userID, $shipID)";
		} else {
			
			$adminQuery = "INSERT INTO reports (reported, reason, userID, shipID, forPM) VALUES ($reported, '$reason', $userID, $shipID, 1)";
		}
		
	}
	$adminResult = mysqli_query($link, $adminQuery);
	
	if($adminResult) {
		
		$_SESSION['msg'] = "$username reported";
		echo "<script>window.location.href = 'ship_page.php?shipID=$shipID';</script>";
	} else {
		
		$_SESSION['msg'] = "Failed to report $username";
	    echo "<script>window.location.href = 'ship_page.php?shipID=$shipID';</script>";
	}
	
} else {
	
	$_SESSION['msg'] = "$username invalid";
	echo "<script>window.location.href = 'ship_page.php?shipID=$shipID';</script>";
}
	
				
		
				?>
