 <?php
 session_start();

#Open a database connection
require('connect_db.php');

$user = $_POST['username'];

	$userQuery = "SELECT *
FROM user
WHERE username = '$user'";

$result = mysqli_query($link, $userQuery);

        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
			
			$userID = $row['userID'];
			
			$ships = "SELECT COUNT(shipID) AS ships FROM team WHERE userID = '$userID'";
			
			$shipResult = mysqli_query($link, $ships);
			
			$shipRow = mysqli_fetch_assoc($shipResult);
			
			$_SESSION['info'] = [$row['username'], $userID, $row['email'], $row['roleChoice'], $row['reports'], $row['is_2fa_enabled'], $shipRow['ships']];
			
			$_SESSION['msg'] = "$user found";
			echo "<script>window.location.href = 'privileges.php';</script>";
			
			
		} else {
				$_SESSION['msg'] = "$user does not exist";
				echo "<script>window.location.href = 'privileges.php';</script>";
			}
		
				
		
				?>
