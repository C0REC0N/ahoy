<!DOCTYPE html>
<?php
# Access session.
session_start();

$page = 'admin';

# Open database connection.
require ( 'connect_db.php' ) ;

$msg = $_SESSION['msg'];

$userID = $_SESSION['userID'];

if(isset($_GET['shipID'])) {            
    $shipID = $_GET['shipID'];
	
$isAdmin = false;

    $isAdminQuery = "SELECT userID
    FROM team
    WHERE shipID = $shipID AND userID = $userID and isAdmin = 1";
    $result = mysqli_query($link, $isAdminQuery);
    
    if (mysqli_num_rows($result) > 0) {
        $isAdmin = true;
    } 
    else {
        echo "Error fetching owner details: " . mysqli_error($link);
    }
	
	$isOwner = false;

    $isOwnerQuery = "SELECT userID
    FROM team
    WHERE shipID = $shipID AND userID = $userID and isOwner = 1";
    $result = mysqli_query($link, $isOwnerQuery);
    
    if (mysqli_num_rows($result) > 0) {
        $isOwner = true;
    } 
    else {
        echo "Error fetching owner details: " . mysqli_error($link);
    }
}

$roleQuery = "SELECT roleChoice AS role FROM user WHERE userID = $userID";
    $result = mysqli_query($link, $roleQuery);
    
    if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
        $role = $row['role'];
    } 


$checkBlocked = "SELECT reports FROM user WHERE userID = $userID";
    $result = mysqli_query($link, $checkBlocked);


    // Check if reports are 10 or more
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if ($row['reports'] > 9) {
					
					echo "<script>window.location.href = 'blocked.html';</script>";
					exit;
				};
			}
		}
?>


<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> AHOY </title>
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="parrot.css">
    <script src="main.js"></script> 
	<script src="parrot.js"></script> 

</head>

<body style="background-color: lightcyan;" onload="welcome(false, false, '<?php echo $msg; ?>')">

<?php $_SESSION['msg'] = null; ?>
<header>
	<?php include 'parrot.php';	?>
</header>
    <div class="page-wrapper">
        <div class="row">
            <div class="flex-left">
                <div class="side-nav">
                    <div class="side">
                        <a href="ship_select.php" class="logo">
                            <img src="AHOYYYYY.png" class="logo-img">
                        </a>
                        <a>
                            <img src="starfish.png" class="profile-image">
                            <p style="text-align: center; font-family: papyrus; color: white"><?php echo "{$_SESSION['username']}"; ?></p>
                        </a>
							<ul class="prof-links">
								<?php if(isset ($_GET['shipID'])){
									echo "<li> <a href='ship_page.php?shipID=$shipID'> <button class='login-button'>Return to Ship</button></a></li>";
								} else {
									echo "<li> <a href='ship_select.php'> <button class='login-button'>Return to Sea</button></a></li>";
								}
								?>
							</ul>
                    </div>
                </div>
            </div>
            <div class="flex-right">
                <h1 style="text-align: center; padding: 20px;">
					<?php if(isset ($shipID)){
						echo "The Bridge";
					} else {
						
						echo "Sea Captain's Quarters";
					}
					?>
                </h1>
                <div class="row">
				
				<?php
				if (isset ($_GET['shipID'])){
					echo '
					<a href="shipStats.php?shipID='.$shipID.'"><button class="ship-button">
					<p>SHIP STATISTICS</p>
					</button></a>';
					
					if ($role <> 'platform_manager'){
						
						echo "<a href='request.php?shipID=$shipID'><button class='ship-button'>
						<p>REQUEST SEA CAPTAIN</p>
						</button></a>";
					}
					
					if($isOwner){
						echo '
						<button class="ship-button" onclick="openMan()">
					MANAGE SHIP
					</button>					
					<button class="ship-button" onclick="openInviteForm()">
					<p>INVITE MEMBER</p>
					</button>';
					}
					
					
				}
				
				if ($role == 'manager' || $role == 'teacher' || $role == 'platform_manager'){
					echo '
					<button class="ship-button" onclick="reportsOpen()">
					<p>USER REPORTS</p>
					</button>
					<button class="ship-button" onclick="MReportOpen()">
					<p>MANAGE USER REPORT</p>
					</button>
					<button class="ship-button" onclick="roleRequestsOpen()">
					<p>ROLE REQUESTS</p>
					</button>
					<button class="ship-button" onclick="MRoleOpen()">
					<p>MANAGE USER ROLE</p>
					</button>';
				}
	
				if ($role == 'platform_manager'){
					echo '<button class="ship-button" onclick="requestsOpen()">
					<p>PRESENCE REQUESTS</p>
					</button>
					<button class="ship-button" onclick="userSearchOpen()">
					<p>SEARCH FOR USER</p>
					</button>';
					if (isset ($shipID)){
						echo "
						<a href='stats.php?shipID=$shipID'><button class='ship-button'>
						<p>SITE STATISTICS</p>
						</button></a>";
					} else {
						echo '
						<a href="stats.php"><button class="ship-button">
						<p>SITE STATISTICS</p>
						</button></a>';
					}
				}
				 
				 
				 ?>
				 
				  
				  
				  <div class="row form-popup" id="inviteForm" style="text-align: center">
				  <h1>Invite Member</h1>
                    <form method="post" action="inviteMember.php">
                        <label for="username">Enter username to invite:</label>
                        <input type="hidden" name="shipID" value="<?php echo $_GET['shipID']; ?>">
                        <input type="text" id="username" name="username" required>
                        <button type="submit" class="btn" onClick="closeInviteForm()">Send Invitation</button>
                        <button type="button" class="btn cancel" onclick="closeInviteForm()">Exit</button>
                    </form>
                  </div>
				  
				  <div class="row form-popup" id="accountForm" style="text-align: center">
                            <h1>Edit Ship</h1>
                        
                            <form action="update_ship_details.php" method="post">
                                <label for="shipname"><b>Ship Name</b></label>
                                <input type="text" placeholder="Enter New Ship Name" name="shipname" required>
    
                                <label for="shipdesc"><b>Ship Description</b></label>
                                <input type="text" placeholder="Enter New Ship Description" name="shipdesc" required>
    
                                <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                                <button type="submit" class="btn" onclick="closeMan()" onsubmit="speech('Youve changed your ship settings!',true)">Save & Exit</button>
                                <button type="button" class="btn cancel" onclick="closeMan()">Exit</button>
                            </form>


                            <form action="ship_delete.php" method="post" >
                                <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                                <button type="submit"  style="background-color:red;"class="btn cancel" onclick="return confirm('Are you sure you want to delete this ship, all associated team members and all files stored in it?')">Delete Ship</button>
                                </form>


                    </div>
				
				

              <div class="row form-popup" id="MReport">
                    <form style="border: lightcyan;" method="post" action="manageReports.php">
                      <h1 style="text-align: center;">Manage Reports</h1>
                      <label for="username"><p style="text-align: center;">Username</p></label>
                      <input for="username" type="text" placeholder="Enter Name Here" name="username" required>
				
					  <select name="option">
						<option value="+">Increase</option>
						<option value="-">Decrease</option>
						<option value="0">Reset</option>
					  </select>
					  <?php if ($role != 'platform_manager'){
						echo '<input type="hidden" name="shipID" value="'.$shipID.'">';
						} ?>
                      <button type="submit" class="btn">Submit</button>
                      <button type="button" class="btn cancel" onclick="MReportClose()">Cancel</button>
                    </form>
                  </div> 
				  
				  <div class="row form-popup" id="MRole">
                    <form style="border: lightcyan;" method="post" action="makeRole.php">
                      <h1 style="text-align: center;">Change Role of User</h1>
                      <label for="username"><p style="text-align: center;">Username</p></label>
                      <input for="username" type="text" placeholder="Enter Name Here" name="username" required>
					  <label for="role"><p style="text-align: center;">Role</p></label>
					    <select name="role">
						<?php if ($role == 'manager' || $role == 'platform_manager'){
						echo "<option value='manager'>Manager</option>";
						echo "<option value='employee'>Employee</option>";
						}
						if ($role == 'teacher' || $role == 'platform_manager'){
						echo "<option value='teacher'>Teacher</option>";
						echo "<option value='student'>Student</option>";
						} ?>
						</select>
					  
					  <?php if ($role != 'platform_manager'){
						echo '<input type="hidden" name="shipID" value="'.$shipID.'">';
						} ?>
                      <button type="submit" class="btn">Change Role</button>
                      <button type="button" class="btn cancel" onclick="MRoleClose()">Cancel</button>
                    </form>
                  </div>
				  
				   <div class="row form-popup" id="userSearch">
                    <form style="border: lightcyan;" method="post" action="userSearch.php">
                      <h1 style="text-align: center;">Search for User</h1>
					  <label for="username"><p style="text-align: center;">Username</p></label>
                      <input for="username" type="text" placeholder="Enter Name Here" name="username" required>
                      <button type="submit" class="btn">Search</button>
                      <button type="button" class="btn cancel" onclick="userSearchClose()">Cancel</button>
                    </form>
                  </div>  

			
				 
				 <?php
					if (isset ($_SESSION['info'])){
							
							$info = $_SESSION['info'];
							
							echo "<div class='userSearch'>";
							echo "<b>".strtoupper($info[0])."</b><br>";						
							echo "<br>User ".$info[1]."<br>";
							echo "<br>".$info[2]."<br>";
							echo "<br>".ucfirst($info[3])."<br>";
							if ($info[4] == 1) {
								echo "<br>".$info[4]." Report<br>";
							} else {
								echo "<br>".$info[4]." Reports<br>";
							}
							if ($info[5] == 0) {
								echo "<br>2FA Disabled<br>";
							} else {
								echo "<br>2FA Enabled<br>";
							}
							if ($info[6] == 1) {
								echo "<br>".$info[6]." Ship";
							} else {
								echo "<br>".$info[6]." Ships";
							}
							
							echo "</div>";
									
					}
					
					$_SESSION['info'] = null;
					?>
					
				  <?php 
				  
				  if (isset ($_SESSION['stats'])){
					  
					  $stats = $_SESSION['stats'];
					  

							
							echo "<div class='userSearch'>";
							echo "<b>Total Users: </b>".$stats[0]."<br>";
							echo "<br><b>Total Platform Managers: </b>".$stats[1]."<br>";
							echo "<br><b>Total Managers: </b>".$stats[3]."<br>";
							echo "<br><b>Total Employees: </b>".$stats[4]."<br>";
							echo "<br><b>Total Teachers: </b>".$stats[5]."<br>";
							echo "<br><b>Total Students: </b>".$stats[6]."";
							echo "</div>";
							
							echo "<div class='userSearch'>";
							echo "<b>Total Admins: </b>".$stats[2]."<br>";
							echo "<br><b>Total Users with 2FA: </b>".$stats[7]."<br>";
							echo "<br><b>Total Users Reported: </b>".$stats[8]."<br>";
							echo "<br><b>Total Reports: </b>".$stats[9]."<br>";
							echo "<br><b>Total Users Banned: </b>".$stats[10]."<br>";
							echo "<br><b>Total Ships: </b>".$stats[11]."";
							echo "</div>";
							
					
				  }
				  
				  $_SESSION['stats'] = null;
				  

				  
				  ?>
				  
				  <?php 
				  
				  if (isset ($_SESSION['shipStats'])){
					  
					  $stats = $_SESSION['shipStats'];
					  

							
							echo "<div class='userSearch'>";
							echo "<b>Ship: </b>".$stats[0]."<br>";
							echo "<br><b>Description: </b>".$stats[1]."<br>";
							echo "<br><b>Crew Size: </b>".$stats[2]."<br>";
							echo "<br><b>Captain: </b>".$stats[3]."<br>";
							if ($stats[4][0] == 0){
								echo "<br><b>Quartermasters: </b>".$stats[4][0]."<br>";
							} else {
								echo "<br><b>Quartermasters:</b><br>";
								foreach($stats[4] as $m) {
									echo "$m<br>";
									}
							}
							if ($stats[5][0] == 0){
								echo "<br><b>Shipmates: </b>".$stats[5][0]."<br>";
							} else {
								echo "<br><b>Shipmates:</b><br>";
								foreach($stats[5] as $m) {
									echo "$m<br>";
									}
							}
							echo "</div>";
							
							echo "<div class='userSearch'>";					
							echo "<b>Current Quests: </b>".$stats[6]."<br>";
							echo "<br><b>Completed Quests: </b>".$stats[7]."<br>";
							echo "<br><b>Decks: </b>".$stats[8]."<br>";
							if ($stats[9][0] == 0){
								echo "<br><b>Invited: </b>".$stats[9][0]."";
							} else {
								echo "<br><b>Invited:</b><br>";
								foreach($stats[9] as $i) {
									echo "$i";
									}
							}
							echo "</div>";
							echo "</div>";
							
					
				  }
				  
				  $_SESSION['shipStats'] = null;
				  

				  
				  ?>
				  
				  <div id="requests">
				  
				  <?php 
				  
					$requestQuery = "SELECT userID, shipID FROM memberInvites WHERE userID = $userID AND isRequest = 1";
					$request = mysqli_query($link, $requestQuery);
					
					if (mysqli_num_rows($request) > 0) {
						
						echo "<script>speech('New Presence Request(s)',true);</script>";
						
						while ($row = mysqli_fetch_assoc($request)) {
							
							
							$userQuery = "SELECT shipID AS shipID, shipName FROM Ship WHERE Ship.shipID = ".$row['shipID']."";
							$user = mysqli_query($link, $userQuery);
							
							$userRow = mysqli_fetch_assoc($user);

								echo "<div class='userSearch'>";
								echo "<b>PRESENCE REQUEST</b><br>";
								echo "<br><b>Ship Name: </b>".$userRow['shipName']."<br>";
								echo "<br><a href='go.php?shipID=".$userRow['shipID']."'><button>Go to Ship</button></a>";
								echo "</div>";		
						}	
					} else {
						echo "<div class='userSearch'>";
							echo "<b>NO PRESENCE REQUESTS</b>";
						echo "</div>";
					}					
				  ?>
				  
				  </div>
				  
				  <div id="roleRequests">
				  
				  <?php 
				  
					if ($role == 'platform_manager'){
						
						$roleRequestQuery = "SELECT requestID, role, roleRequests.userID AS userID FROM roleRequests";
					} else if ($role == 'manager'){
						
						$roleRequestQuery = "SELECT requestID, role, roleRequests.userID AS userID, shipID FROM roleRequests JOIN team ON team.userID = roleRequests.userID WHERE role = 'manager' AND shipID = $shipID";
					} else if ($role == 'teacher'){
						
						$roleRequestQuery = "SELECT requestID, role, roleRequests.userID AS userID FROM roleRequests JOIN team ON team.userID = roleRequests.userID WHERE role = 'teacher' AND shipID = $shipID";
					}
					
					
					$roleRequest = mysqli_query($link, $roleRequestQuery);
					
					if (mysqli_num_rows($roleRequest) > 0) {
						
						echo "<script>speech('New Role Request(s)',true);</script>";
						
						while ($roleRequestRow = mysqli_fetch_assoc($roleRequest)) {
							
							
							$userRoleQuery = "SELECT username, email, roleChoice FROM user WHERE userID = ".$roleRequestRow['userID']."";
							$userRole = mysqli_query($link, $userRoleQuery);
							
							$userRoleRow = mysqli_fetch_assoc($userRole);

								echo "<div class='userSearch'>
								<b>".strtoupper($roleRequestRow['role'])." REQUEST</b><br>
								<br><b>Username: </b>".$userRoleRow['username']."<br>
								<br><b>Email: </b>".$userRoleRow['email']."<br>
								<br><a href='roleRequest.php?requestID=".$roleRequestRow['requestID']."&response=true'><button>Accept</button></a>
								<a href='roleRequest.php?requestID=".$roleRequestRow['requestID']."&response=false'><button>Deny</button></a>
								</div>";		
						}	
					} else {
						echo "<div class='userSearch'>";
							echo "<b>NO ROLE REQUESTS</b>";
						echo "</div>";
					}					
				  ?>
				  
				  </div>
				  
				  <div id="reports">
				  
				  <?php 
				  
					if ($role == 'teacher' || $role == 'manager'){
						$reportQuery = "SELECT reportID FROM reports WHERE shipID = $shipID AND forPM = 0";
					} else if ($role == 'platform_manager'){
						
						$reportQuery = "SELECT reportID FROM reports WHERE forPM = 1";
					}
					
					$report = mysqli_query($link, $reportQuery);
					
					if (mysqli_num_rows($report) > 0) {
						
						echo "<script>speech('New Report(s)',true);</script>";
						
						while ($reportRow = mysqli_fetch_assoc($report)) {
							
							
							$reportInfo = "SELECT * FROM reports WHERE reportID = ".$reportRow['reportID']."";
							$info = mysqli_query($link, $reportInfo);
							$infoRow = mysqli_fetch_assoc($info);
							
							$reportedInfo = "SELECT username FROM user WHERE userID = ".$infoRow['reported']."";
							$reportedI = mysqli_query($link, $reportedInfo);						
							$repRow = mysqli_fetch_assoc($reportedI);
							
							$byInfo = "SELECT username FROM user WHERE userID = ".$infoRow['userID']."";
							$by = mysqli_query($link, $byInfo);						
							$byRow = mysqli_fetch_assoc($by);
							
							$shipInfo = "SELECT shipName FROM Ship WHERE shipID = ".$infoRow['shipID']."";
							$ship = mysqli_query($link, $shipInfo);						
							$shipRow = mysqli_fetch_assoc($ship);

								echo "<div class='userSearch'>";
								echo "<b>".strtoupper($repRow['username'])." REPORTED</b><br>";
								echo "<br><b>On Ship </b>".$shipRow['shipName']."<br>";
								echo "<br><b>By User </b>".$byRow['username']."<br>";
								echo "<br><b>Reason: </b>".$infoRow['reason']."<br>";
								echo "<a href='investigate.php?shipID=".$infoRow['shipID']."&reportID=".$infoRow['reportID']."'><button>Investigate</button></a>";
								echo "</div>";		
						}	
					} else {
						echo "<div class='userSearch'>";
							echo "<b>NO REPORTS</b>";
						echo "</div>";
					}					
				  ?>
				  
				  </div>
				  
				  </div>
				  
				 
            </div>
        </div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">



</script>

</html>
