<?php 

	session_start();
	
	require ( 'connect_db.php' ) ;
	
	$shipID = $_GET['shipID'];
	
	$userID = $_SESSION['userID'];
	
	$info = "SELECT roleChoice, isAdmin, isOwner FROM user JOIN team ON user.userID = team.userID WHERE user.userID = $userID and shipID = $shipID";
	$result = mysqli_query($link, $info); 
	$row = mysqli_fetch_assoc($result);

	if ($row['isOwner'] == 1 || $row['roleChoice'] == 'platform_manager'){
		
			$_SESSION['msg'] = 'Welcome Aboard, Captain!';
		} else if ($row['isAdmin'] == 1){
			
			$_SESSION['msg'] = 'Welcome Aboard, Quartermaster!';
		} else {
			
			$_SESSION['msg'] = 'Welcome Aboard, Matey!';
		}
	
	echo "<script>window.location.href = 'ship_page.php?shipID=$shipID';</script>";

?>
