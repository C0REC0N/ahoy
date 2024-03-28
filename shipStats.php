 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$shipID = $_GET['shipID'];
	
	$nameQuery = "SELECT shipName FROM Ship WHERE shipID = $shipID";
	$nameResult = mysqli_query($link, $nameQuery);
	$nameRow = mysqli_fetch_assoc($nameResult);
	
	$descQuery = "SELECT shipDescription FROM Ship WHERE shipID = $shipID";
	$descResult = mysqli_query($link, $descQuery);
	$descRow = mysqli_fetch_assoc($descResult);
	
	$crewQuery = "SELECT COUNT(userID) AS crew FROM team WHERE shipID = $shipID";
	$crewResult = mysqli_query($link, $crewQuery);
	$crewRow = mysqli_fetch_assoc($crewResult);
	
	$capQuery = "SELECT username FROM user JOIN team ON user.userID = team.userID WHERE shipID = $shipID AND isOwner = 1";
	$capResult = mysqli_query($link, $capQuery);
	$capRow = mysqli_fetch_assoc($capResult);
	
	$qQuery = "SELECT username FROM user JOIN team ON user.userID = team.userID WHERE shipID = $shipID AND isAdmin = 1 AND isOwner = 0";
	$qResult = mysqli_query($link, $qQuery);
	if (mysqli_num_rows($qResult) > 0) {
		while ($qRow = mysqli_fetch_assoc($qResult)) {
			$q[] = $qRow['username'];
		}
	} else {
		
		$q[] = 0;
	}
	
	$mQuery = "SELECT username FROM user JOIN team ON user.userID = team.userID WHERE shipID = $shipID AND isOwner = 0 AND isAdmin = 0";
	$mResult = mysqli_query($link, $mQuery);
	if (mysqli_num_rows($mResult) > 0) {
		while ($mRow = mysqli_fetch_assoc($mResult)) {
			$m[] = $mRow['username'];
		}
	} else {
		
		$m[] = 0;
	}
	
	$questQuery = "SELECT COUNT(taskID) AS quests FROM task WHERE shipID = $shipID AND completed = 0";
	$questResult = mysqli_query($link, $questQuery);
	$questRow = mysqli_fetch_assoc($questResult);
	
	$compQuery = "SELECT COUNT(taskID) AS quests FROM task WHERE shipID = $shipID AND completed = 1";
	$compResult = mysqli_query($link, $compQuery);
	$compRow = mysqli_fetch_assoc($compResult);
	
	$decksQuery = "SELECT COUNT(fileID) AS decks FROM test_files WHERE shipID = $shipID";
	$decksResult = mysqli_query($link, $decksQuery);
	$decksRow = mysqli_fetch_assoc($decksResult);
	
	$iQuery = "SELECT username FROM user JOIN memberInvites ON user.userID = memberInvites.userID JOIN team ON user.userID = team.userID WHERE memberInvites.shipID = $shipID AND isOwner = 0 AND isAdmin = 0";
	$iResult = mysqli_query($link, $iQuery);
	if (mysqli_num_rows($iResult) > 0) {
		while ($iRow = mysqli_fetch_assoc($iResult)) {
			$i[] = $iRow['username'];
		}
	} else {
		
		$i[] = 0;
	}
	
	$_SESSION['shipStats'] = [$nameRow['shipName'], $descRow['shipDescription'], $crewRow['crew'], $capRow['username'], $q, $m, $questRow['quests'], $compRow['quests'], $decksRow['decks'], $i];
	
	$_SESSION['msg'] = "Ship Statistics Found";
	echo "<script>window.location.href = 'privileges.php?shipID=$shipID';</script>";
				
		
				?>
