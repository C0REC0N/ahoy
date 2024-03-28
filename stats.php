 <?php
 session_start();

#Open a database connection
require('connect_db.php');
	
	$userQuery = "SELECT userID FROM user";
	$userResult = mysqli_query($link, $userQuery);
	
	$PMQuery = "SELECT userID FROM user WHERE roleChoice = 'platform_manager'";
	$PMResult = mysqli_query($link, $PMQuery);
	
	$adQuery = "SELECT DISTINCT(userID) FROM team WHERE isAdmin = 1";
	$adResult = mysqli_query($link, $adQuery);
	
	$mQuery = "SELECT userID FROM user WHERE roleChoice = 'manager'";
	$mResult = mysqli_query($link, $mQuery);
	
	$eQuery = "SELECT userID FROM user WHERE roleChoice = 'employee'";
	$eResult = mysqli_query($link, $eQuery);
	
	$tQuery = "SELECT userID FROM user WHERE roleChoice = 'teacher'";
	$tResult = mysqli_query($link, $tQuery);
	
	$sQuery = "SELECT userID FROM user WHERE roleChoice = 'student'";
	$sResult = mysqli_query($link, $sQuery);
	
	$faQuery = "SELECT userID FROM user WHERE is_2fa_enabled = 1";
	$faResult = mysqli_query($link, $faQuery);
	
	$reportQuery = "SELECT userID FROM user WHERE reports > 0";
	$reportResult = mysqli_query($link, $reportQuery);
	
	$reportCountQuery = "SELECT SUM(reports) AS reports FROM user";
	$reportCountResult = mysqli_query($link, $reportCountQuery);
	$row = mysqli_fetch_assoc($reportCountResult);
	
	$banQuery = "SELECT userID FROM user WHERE reports > 9";
	$banResult = mysqli_query($link, $banQuery);
	
	$shipQuery = "SELECT shipID FROM Ship";
	$shipResult = mysqli_query($link, $shipQuery);
	
	$_SESSION['stats'] = [mysqli_num_rows($userResult), mysqli_num_rows($PMResult), mysqli_num_rows($adResult), mysqli_num_rows($mResult), mysqli_num_rows($eResult), mysqli_num_rows($tResult), mysqli_num_rows($sResult), mysqli_num_rows($faResult), mysqli_num_rows($reportResult), $row['reports'], mysqli_num_rows($banResult), mysqli_num_rows($shipResult)];
	
	$_SESSION['msg'] = "Statistics Found";
	echo "<script>window.location.href = 'privileges.php';</script>";
				
		
				?>
