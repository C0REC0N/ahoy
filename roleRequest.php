 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$requestID = $_GET['requestID'];
$response = $_GET['response'];

$requestQuery = "SELECT * FROM roleRequests WHERE requestID = $requestID";
$requestResult = mysqli_query($link, $requestQuery);

if ($requestResult){
	
		if($response) {
			
			$requestRow = mysqli_fetch_assoc($requestResult);
		
			$updateQuery = "UPDATE user SET roleChoice = '".$requestRow['role']."' WHERE userID = ".$requestRow['userID']."";
			$updateResult = mysqli_query($link, $updateQuery);
			
			$removeQuery = "DELETE FROM roleRequests WHERE requestID = $requestID";
			$remove = mysqli_query($link, $removeQuery);

			
			if ($updateResult){
				$_SESSION['msg'] = "Request Approved";
				echo "<script>window.location.href = 'privileges.php';</script>";
			} else {
				
				$_SESSION['msg'] = "Request Failed";
				echo "<script>window.location.href = 'privileges.php';</script>";
			}
			
		} else {
			
			$removeQuery = "DELETE FROM roleRequests WHERE requestID = $requestID";
			$_SESSION['msg'] = "Request Denied";
			echo "<script>window.location.href = 'privileges.php';</script>";
		}
		
		

} else {
	
	$_SESSION['msg'] = "Request Already Dealt With";
	echo "<script>window.location.href = 'privileges.php';</script>";
}


		
				?>
				
