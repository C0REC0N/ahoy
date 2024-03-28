 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$shipID = $_GET['shipID'];

$userID = $_SESSION['userID'];

$PQuery = "SELECT user.userID FROM team JOIN user ON team.userID = user.userID WHERE roleChoice = 'platform_manager' AND shipID = $shipID";
$PResult = mysqli_query($link, $PQuery);

if (mysqli_num_rows($PResult) > 0) {
	
	$_SESSION['msg'] = "A platform manager is already on the ship";
	echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
} else {
	
	$invQuery = "SELECT inviteID FROM memberInvites WHERE shipID = $shipID AND isRequest = 1";
	$invResult = mysqli_query($link, $invQuery);

		if (mysqli_num_rows($invResult) > 0) {
		
		$_SESSION['msg'] = "A platform manager has already been requested";
		echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
	} else {
		
	$PMQuery = "SELECT userID, username FROM user WHERE roleChoice = 'platform_manager'";
	$PMResult = mysqli_query($link, $PMQuery);

	$index = random_int(0, mysqli_num_rows($PMResult)-1);

	while ($row = mysqli_fetch_assoc($PMResult)) {
		
		$PMs[] = $row['userID'];
	}

	$PID = $PMs[$index];

	$query = "INSERT INTO memberInvites (userID, shipID, isRequest) VALUE ($PID, $shipID, 1)";
	$request = mysqli_query($link, $query);

	if ($request){
		
		$_SESSION['msg'] = "A Sea Captain has been requested";
		echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";

	} else {
		
		$_SESSION['msg'] = "No Sea Captains available";
		echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
	}
	}
}


		
				?>
