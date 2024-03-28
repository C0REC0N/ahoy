<!DOCTYPE html>
<?php
# Access session.
session_start();

# Open database connection.
require ( 'connect_db.php' ) ;

$page = 'ship';

$msg = $_SESSION['msg'];
$userID = $_SESSION['userID'];


//storing if the current user is an admin or an owner for role based access controls
if(isset($_GET['shipID'])) {            
    $shipID = $_GET['shipID'];

    $isOwner = false;

    $isOwnerQuery = "SELECT *
    FROM team 
    WHERE shipID = $shipID AND userID = $userID AND isOwner = 1";
    $oResult = mysqli_query($link, $isOwnerQuery);
    
    if (mysqli_num_rows($oResult) > 0) {
        $isOwner = true;
    } 

	$isAdmin = false;

    $isAdminQuery = "SELECT *
    FROM team
    WHERE shipID = $shipID AND userID = $userID and isAdmin = 1";
    $aResult = mysqli_query($link, $isAdminQuery);
    
    if (mysqli_num_rows($aResult) > 0) {
        $isAdmin = true;
    } 
}

$isPMQuery = "SELECT user.userID AS pID FROM user WHERE userID = $userID AND roleChoice = 'platform_manager'";
    $result = mysqli_query($link, $isPMQuery);
    
    if (mysqli_num_rows($result) > 0) {
		
        $isPM = true;
    } 
    else {
        $isPM = false;
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
	<script src="parrot.js"></script>
    <script>var crewUsername;</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 

    <script>setHasSeen(false);</script> 
</head>

<body style="background-color: lightcyan;" onload="welcome(false, true, '<?php echo $msg; ?>')">

<?php $_SESSION['msg'] = null; ?>
<header>
	<?php include "parrot.php" ?>
</header>
    
        <div class="row">
            <div class="flex-left">
                <div class="side-nav">
                    <a href="ship_select.php" class="logo">
                        <img src="AHOYYYYY.png" class="logo-img">
                    </a>
                    <ul class="prof-links">
						<li> <a href='ship_select.php'> <button class='login-button'>Return to Sea</button></a></li>
                        <li> <a href="task_select.php?shipID=<?php echo $shipID ?>"> <button class="login-button">Current Quests</button> 
                        <li> <a href="#"> <button class="login-button" onclick="openFiles()">View Decks</button> </a></li>
                        <li> <a href="#"> <button class="login-button" onclick="openUploadFiles()">Upload Deck</button> </a></li>


                        
                        <?php if ($isOwner || $isAdmin || $isPM) {
                            // If the current user is the owner or an admin then display the manage ship button else hide it
                            echo "<li><a href='privileges.php?shipID=$shipID'><button class='login-button'>The Bridge</button></a></li>";
                            }
                            ?> 

                            <li onclick="openReport()"><button class='login-button' onclick="speech('Remember: intentionally reporting someone without cause may lead you to walk the plank!',false)">Report Member</button></li>

                        <?php if (!$isOwner) : #button to remove a member from the team appears if user is owner of ship?>
                                <form action="leave_ship.php" method="post">
                                    <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                                    <input type="hidden" name="userID" value="<?php echo $_SESSION['userID']; ?>">
                                    <button type="submit" class="btn cancel" onclick="confirmLeave()">Leave Ship</button>
                                </form>
                                <?php endif; ?>
                    </ul>
                    
          

               <div id="reportForm" class="file-popup"style="display: none;">
					<form method="post" action="reportMember.php">
						<input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
						<input type="hidden" name="userID" value="<?php echo $userID; ?>">
						<input type="hidden" name="role" value="<?php echo $role; ?>">
						<label for="reason">Enter Username to Report</label>
						<input type="text" name="username" required>
						<label for="reason">Select Reason for Report:</label>
						<select name="reason" required>
							<option value="Abuse of Power">Abuse of Power</option>
							<option value="Offensive Message">Offensive Message</option>
							<option value="Sabotage">Sabotage</option>
							<option value="Other">Other</option>
						</select>
						<p></p>
						<button type="submit" class="btn cancel">Report Member</button>
						<p onclick="closeReport()"><button type="button" class="btn cancel" onclick="hide()">Cancel</button></p>
					</form>
				</div>

                <div id="privateMessages" class="file-popup" style="display: none;">
                
                <!--Div for opening the private message box-->
                <h2>Private Messages</h2>
                <ul>
                    <?php
                    // Query to retrieve the list of users in the ship
                    $userListQuery = "SELECT user.username 
                    FROM user 
                    INNER JOIN team ON user.userID = team.userID 
                    WHERE team.shipID = $shipID";
                    $userListResult = mysqli_query($link, $userListQuery);

                    if ($userListResult && mysqli_num_rows($userListResult) > 0) {
                        while ($user = mysqli_fetch_assoc($userListResult)) {
                            $username = $user['username'];
                            echo "<li><a href='#' onclick=\"openPrivateChat('$username')\">$username</a></li>";
                        }
                    } else {
                        echo "<li>No users found in the ship.</li>";
                    }
                    ?>
                    </ul>
                    <button type="button" class="btn cancel" onclick="closePrivateMessages()">Close</button>
                </div>

                    <!--Div for opening the file list popup-->
                    <div id="fileList" class="file-popup" style="display:none">
                    <div class="file-content-popup">
                    <h2>Shared Files</h2>
                    <ul>
                        <?php
                        //Displaying a list of all files in the ship
                        $filesQuery = "SELECT fileID, filename FROM test_files WHERE shipID = $shipID";
                        $filesResult = mysqli_query($link, $filesQuery);

                        if ($filesResult && mysqli_num_rows($filesResult) > 0) {
                            while ($file = mysqli_fetch_assoc($filesResult)) {
                                $fileID = $file['fileID'];
                                $filename = $file['filename'];
                                echo "<li><a href='download_file.php?fileID=$fileID'>$filename</a></li>";
                            }
                        } else {
                            echo "<li>No shared files.</li>";
                        }
                        ?>
                        </ul>
                        <button type='button' class='btn cancel' onclick='closeFiles()'>Close File List</button>
                        </div>
                    </div>


                <div id="uploadFile" class="file-popup"style="display:none">
                <h2>Upload File to Ship</h2>
                <form action="upload_file.php" method="post" enctype="multipart/form-data" class="upload-form">
                    <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                    <div class="file-upload-container">
                    <input type="file" name="file" required>
                    </div>
                    <button type="submit">Upload File</button>
                </form>
                <button type='button' class='btn cancel' onclick='closeUploadFiles()'>Close Upload Files</button>
                </div>

                    
                </div>
            </div>

            <div class="flex-right" style="background-color: #ffd79b; padding-left: 200px">
                <div class="btn-container" id="button-container">
                <label for="lineWidth"><p style="color: #004aad; background-color: #ffd79b; text-align: center; outline-color: #d49307; outline-style: solid; outline-width: 1px; padding: 2px">Line Width</p></label>
                <input id="line-width" name='lineWidth' type="number" value="5" style="width: 90px;">
                <button id="clear-btn" class="btn2" style="color: white;">Clear</button>
                <button id="black-btn" class="btn2">Black</button>
                <button id="red-btn" class="btn2" style="color: red">Red</button>
                <button id="blue-btn" class="btn2" style="color: #004aad">Blue</button>
                <button id="green-btn" class="btn2" style="color: green">Green</button>
                <input type="color" class="btn2" id="color-picker" />
                <button class="btn2" id="buttondown">Download</button>
                <input type="file" id="uploader" hidden/>
                <button class="btn2" id="buttonup">Upload</button>
                </div>
                <canvas id="my-canvas" style="background-color: white; border: black; "></canvas>
            <div class="flex-right">
                <div id="crewNav" class="crew-nav">
                    <ul class="prof-links">
                        
                        <li> <a href="#"> <p> Crew Members: </p> </a></li>

                        <?php  #php section to display a list of all current members in the ship
                        if(isset($_GET['shipID'])) {            
                            $shipID = $_GET['shipID'];

                            #Query to retrieve users that match with the shipID
                            $memberList = "SELECT user.username, team.isOwner, team.isAdmin
                            FROM user 
                            INNER JOIN team ON user.userID = team.userID 
                            WHERE team.shipID = $shipID AND roleChoice != 'platform_manager'";
                            $result = mysqli_query($link, $memberList);
                            
                            if($result) {
                                while($row = mysqli_fetch_assoc($result)) 
                                {
                                    echo "<ul>";
                                    echo "<button class='crew-member-button' onclick=\"openCrewMember('{$row['username']}', {$row['isAdmin']}, {$row['isOwner']})\">{$row['username']}";
                                    if ($row['isOwner'] == 1) 
                                    {
                                        echo " <i class='fas fa-crown'></i></button></a>";
                                    }
                                    echo "</ul>";
                                }
                            } else {
                                 echo "Error retrieving team members: " . mysqli_error($link);
                                }
                            } else {
                                echo "No shipID provided.";
                            }

                            
                            ?>                        
                    </ul>

                    <div class="file-popup" id="crewMember">
                        <label for="memberName"><b>Crew Member Name:</b><p id="memberName"></p></label>                
                        <label for="memberStatus"><b>Crew Member Status:</b><p id="memberStatus"></p></label>
                        
                        <form id="reportMemberForm" method="post" action="reportMember.php" style="display:none">
                        <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                        <input type="hidden" id="usernameToReport" name="username">
                        <label for="reason">Enter reason for report:</label>
                        <input type="text" id="reason" name="reason" required>
                    </form>
                                
                <!--Div for section that gets hidden when opening a chat or report menu on a user-->
                <div id="userSection">
                    <?php
                    $adminStatus = false;
                    $ownerStatus = false;
                    #Checking to see if current user is an admin or an owner
                    if(isset($_SESSION['userID'])) {
                                $userID = $_SESSION['userID']; 

                                #Query to check if the user is an admin or owner
                                $checkOwnerAdmin = "SELECT isAdmin, isOwner FROM team WHERE userID = $userID AND shipID = $shipID"; //Query to check if current user is owner or admin
                                $checkResult = mysqli_query($link, $checkOwnerAdmin);

                                if ($checkResult) {
                                $row = mysqli_fetch_assoc($checkResult);   #if they are owner or admin store it in the designated variables
                                $adminStatus = (bool) $row['isAdmin'];
                                $ownerStatus = (bool) $row['isOwner'];
                            } else {
                                echo "Error retrieving user status: " . mysqli_error($link);
                            }
                        } else {
                            echo "No userID provided.";
                        } ?>

                            
                            <?php if ($ownerStatus) : #button to make a member an admin will appear if user is the owner ?>
                                <form id="makeAdminForm" action="create_admin.php" method="post">
                                    <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                                    <input type="hidden" id="crewAdminName" name="username" value="">
                                    <button type="submit" class="btn" onclick="makeAdmin()">Make Admin</button>
                                </form>
                                <?php endif; ?>

                                <form id="openPrivateChat" method="post" action="privateChat.php">
                                <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                                <input type="hidden" id="recipientUsername" name="recipientUser">
                                <button type="submit" class="btn" onclick="openPrivateChat()">Private Chat</button>
                            </form>

                            <?php if ($ownerStatus) : #button to remove a member from the team appears if user is owner of ship?>
                                <form id="removeMemberForm" action="remove_member.php" method="post">
                                    <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                                    <input type="hidden" id="usernameToRemove" name="username" value="">
                                    <button type="submit" class="btn cancel" onclick="removeMember()">Remove Member</button>
                                </form>
                                <?php endif; ?>
                  
                                <button type="submit" class="btn cancel" onclick="closeCrewMember()">Close</button>
                            </div>
                        </div>

                    <!-- Open crew chat button -->
                    <div class = footer> 
                        <p onclick="setHasSeen(true)"><button class="login-button" onclick="openNav()">Crew Chat</button></p>
                    </div>
                </div>
            </div>

            <div class="flex-right">
                <!-- The chat box -->
                <div id="chatNav" class="chat-nav">
                        <!-- Chat heading -->
                        <h1>Chat</h1>
                        <script>var hasSeen = hasSeen();</script>
                        <!-- an iframe which will display all of the messages -->
                        <iframe src="chat.php?shipID=<?php echo $shipID; ?>&seen=hasSeen" width="100%" height="300" style="background-color: lightcyan;" scrolling="yes"></iframe>
                        
                        <!-- drop-down to select recipient (might just be deleted) -->
                        <p1>Message<p1>

                        <!-- form to post messages to the server -->
                        <form method="post" action="sendMessage.php?shipID=<?php echo $shipID; ?>">
                        <textarea placeholder="Type message.." name="msg" required></textarea>
                        <input type="submit" class ="btn" value="Send" onclick="speech('You have sent a message!',true)">
                    </form>

                    <!-- close chat button at the bottom of the menu -->
                    <div class = footer> 
                        <button class="login-button" onclick="closeNav()">Close Chat</button>
                    </div>
                </div>
            </div>

        </div>
    <script src="main.js"></script>
    <link rel="stylesheet" href="style.css">
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">

</script>

<?php 
	$chatQuery = "SELECT SenderUserID AS userID FROM PrivateChat WHERE ReceiverUserID = $userID AND ShipID = $shipID AND isNew = 1";
	$chatResult = mysqli_query($link, $chatQuery);
	
	if (mysqli_num_rows($chatResult) > 0) {
		
		while ($chatRow = mysqli_fetch_assoc($chatResult)){
			
			$senderQuery = "SELECT username FROM user WHERE userID = ".$chatRow['userID']."";
			$senderResult = mysqli_query($link, $senderQuery);
			$senderRow = mysqli_fetch_assoc($senderResult);
			
			echo '<script>speech("You have a new message from '.$senderRow['username'].'!", true)</script>';
			
			
		}
    } 
	
	
	$chatQuery = "SELECT * FROM team WHERE userID = $userID AND ShipID = $shipID AND newMessage = 1";
	$chatResult = mysqli_query($link, $chatQuery);
	
	if (mysqli_num_rows($chatResult) > 0) {
		
		echo '<script>speech("There are new messages in the ship chat!", true)</script>';
    }
	

?>

</html>
