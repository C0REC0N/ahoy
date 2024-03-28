 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$shipID = $_GET['shipID'];

$userID = $_SESSION['userID'];

$removeQuery = "DELETE FROM memberInvites WHERE shipID = $shipID AND userID = $userID";
$removeResult = mysqli_query($link, $removeQuery);

if ($removeResult){
	
	$joinQuery = "INSERT INTO team (userID, shipID) VALUES ($userID, $shipID)";
	$join = mysqli_query($link, $joinQuery);
	
	if ($join){
	
		echo "<script>window.location.href = 'ship_page.php?shipID=$shipID';</script>";
	}

} else {
	
	$_SESSION['msg'] = "Cannot access ship";
	echo "<script>window.location.href = 'privileges.php';</script>";
}


		
				?>
				
