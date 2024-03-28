 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$shipID = $_GET['shipID'];

$userID = $_SESSION['userID'];

$reportID = $_GET['reportID'];

$removeQuery = "DELETE FROM reports WHERE reportID = $reportID";
$removeResult = mysqli_query($link, $removeQuery);

if ($removeResult){
	
	$currentQuery = "SELECT userID FROM team WHERE userID=$userID";
	$current = mysqli_query($link, $currentQuery);
	
	if(mysqli_num_rows($current) > 0) {
		
		echo "<script>window.location.href = 'ship_page.php?shipID=$shipID';</script>";
	} else {
	
		$joinQuery = "INSERT INTO team (userID, shipID, isAdmin) VALUES ($userID, $shipID, 1)";
		$join = mysqli_query($link, $joinQuery);
		
		echo "<script>window.location.href = 'ship_page.php?shipID=$shipID';</script>";
			
		}

} else {
	
	$_SESSION['msg'] = "Cannot access ship";
	echo "<script>window.location.href = 'privileges.php';</script>";
}


		
				?>
				
