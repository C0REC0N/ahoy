<!DOCTYPE html>
<?php
# Access session.
session_start();


$msg = $_SESSION['msg'];
$page = 'home';
# Open database connection.
require ( 'connect_db.php' ) ;

$userID = $_SESSION['userID'];

$isPMQuery = "SELECT user.userID AS pID FROM user WHERE userID = $userID AND roleChoice = 'platform_manager'";
    $result = mysqli_query($link, $isPMQuery);
    
    if (mysqli_num_rows($result) > 0) {
        $isPM = true;
    } 
    else {
        $isPM = false;
    }

   
    $sql = "SELECT is_2fa_enabled FROM user WHERE userID = $userID";
    $result = mysqli_query($link, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $is2FAEnabled = $row['is_2fa_enabled'] == 1;
    } else {
        // Handle unexpected error
        echo "Error: Could not determine 2FA status.";
    }



    

    $sql = "SELECT pfp FROM user WHERE userID= '$userID'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
          $pfp = $row['pfp'];
        
        
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

    <?php
	$userID = $_SESSION['userID'];
	//Checking to see if the user is new
	$checkFirst = "SELECT first_login FROM user WHERE userID = $userID";
	$result = mysqli_query($link, $checkFirst);

	// Assigning result to variable
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$first = $row['first_login'];
		}
	}
	else{
	
	}
	?>

<style>

.pfp_view {
    display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
  width: 100px;
  height: 100px;
  padding: 10px 10px;
  border-radius: 100%;

}



    </style>
</head>

<body style="background-color: lightcyan;" onload="welcome(<?php echo "$first"; ?>, false, '<?php echo $msg; ?>')">
<header>
	<?php include 'parrot.php';

    $changeFirst = "UPDATE user SET first_login = 0 WHERE userID = $userID";
	$result = mysqli_query($link, $changeFirst);
	
	$_SESSION['msg'] = null;
	
	?>
</header>
    <div class="page-wrapper">
        <div class="row">
            <div class="flex-left">
                <div class="side-nav">
                    <div class="side">
                        <a href="ship_select.php" class="logo">
                            <img src="AHOYYYYY.png" class="logo-img">
                        </a>
                      
                       <div class="pfp">
                        <img src="<?php echo $pfp; ?>"  class="profile-image" onclick="openpfpForm()">
                <div class="form-popup" id="mypfpForm">
                <form action="/action_page.php" class="form-container">
                

                    <a href="custom1.php?imageName=<?php echo "starfish.png" ?>">  <img src="starfish.png" class="pfp_view"  />
                    </a>

                    <a href="custom1.php?imageName=<?php echo "proflogo2.png" ?>">  <img src="proflogo2.png" class="pfp_view" />
                    </a>

                    <a href="custom1.php?imageName=<?php echo "proflogo3.png" ?>">  <img src="proflogo3.png" class="pfp_view" />

                    
                    </a>

                    <a href="custom1.php?imageName=<?php echo "proflogo4.png" ?>">  <img src="proflogo4.png" class="pfp_view"  />

                    </a>

                    <a href="custom1.php?imageName=<?php echo "proflogo5.png" ?>">  <img src="proflogo5.png" class="pfp_view" />
                    
                    
                    </a>

                    <button type="button" class="btn cancel" onclick="closepfpForm()">Close</button>
                </form>
                </div>
				</div>

<script>
function openpfpForm() {
  document.getElementById("mypfpForm").style.display = "block";
}

function closepfpForm() {
  document.getElementById("mypfpForm").style.display = "none";
}
</script>
                            
                            <p style="text-align: center; font-family: papyrus; color: white"><?php echo "{$_SESSION['username']}"; ?></p>
                        </a>
                        <ul class="prof-links">
                            <li> <button class="login-button" onclick="openAcc()"> <p style="text-align: center;"> Edit Account </p> </button></li>
                            <li> <button class="login-button" onclick="openInvites()"> <p style="text-align: center;"> Open Invites </p> </button></li>
                            <li> <button class="login-button" onclick="open2FA()"> <p style="text-align: center;"> Enable 2FA </p> </button></li>
                        
                          
                            <?php if ($isPM) {
                            // If the current user is the owner or an admin then display the manage ship button else hide it
                            echo "<li><a href='privileges.php'><button class='login-button'>Sea Captain's Quarters</button></a></li>";
                            }
                            ?> 
                        </ul>

                        <a href="login.php"><button class="login-button">Logout</button> </a>
                    </div>

                    <div class="file-popup" id="2FAForm">
                        <form action="" class="form-container" style="padding-left: 20px;">
                            <p style="text-align: center;">Would you like to enable two factor authentication?</p>
                        
                            <label style="display: flex; color: #ffffff; align-items: center; padding-left: 30px">
                               
                            <input type="radio" name="enable2fa" value="1" <?php if ($is2FAEnabled == '1') echo "checked"; ?> /> Yes<br />


                           
                            <input type="radio" name="enable2fa" value="0" <?php if ($is2FAEnabled == '0') echo "checked"; ?> /> No<br />
                    
                            
                            
                            
                            </label>
                        
                            <button type="submit" name="submit" class="btn" onclick="close2Fa()" onsubmit="speech('Youve changed your 2FA setting!',true)">Save & Exit</button>
                            <button type="button" class="btn cancel" onclick="close2Fa()">Exit</button>
                          </form>
                    </div>




                    <?php
                        if(isset($_REQUEST["submit"])){
                                                

                            
                            $enable2FA = $_REQUEST['enable2fa']; // Get the selected value from the form
                        
                            $sql = "UPDATE user SET is_2fa_enabled = $enable2FA WHERE userID = $userID";
                            $result = mysqli_query($link, $sql);
                            
                            
                            }
                            


                            ?>



                    <div class="file-popup" id="invitationList">
                            <h1 style="text-align: center; color: #004aad; text-shadow: #ffd79b 0 0 20px;">Ship Invitations</h1>
                            <?php
                                $userID = $_SESSION['userID'];
                                //Checking to see if there are any invites for the user
                                $checkInvites = "SELECT Ship.shipID, Ship.shipName FROM memberInvites JOIN Ship ON memberInvites.shipID = Ship.shipID WHERE memberInvites.userID = $userID AND isRequest = 0";
                                $result = mysqli_query($link, $checkInvites);

                                // Check if there are any pending invites.
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<script> speech('You have new invites!', true); </script>";

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $shipID = $row['shipID'];
                                        $shipName = $row['shipName'];
                                        echo "<p>You have been invited to join Ship with the name: $shipName. Do you want to join?</p>";
                                        echo "<form method='post' action='acceptInvite.php'>";
                                        echo "<input type='hidden' name='shipID' value='$shipID'>";
                                        echo "<button type='submit' name='accept'>Accept Invite</button>";
                                        echo "</form>";
                                        echo "<form method='post' action='declineInvite.php'>";
                                        echo "<input type='hidden' name='shipID' value='$shipID'>";
                                        echo "<button type='submit' name='accept'>Decline Invite</button>";
                                        echo "</form>";
                                    }
                                }
                                else{
                                    echo "<p style='text-align: center; color: red;'>No pending invites.</p>";
                              
                                }
                            ?>
                        
                            <button type="button" class="btn cancel" style="" onclick="closeInvites()">Exit</button>
                    </div>

                    <div class="file-popup" id="accountForm">
                        <form action="updateDetails.php" method="post" style="padding-left: 25px;">

                            <h2>Edit Account</h2>
                            <p>&nbsp;</p>
                        
                            <label for="username"><b>Enter New Username</b></label>
                            <input for="username" type="text" name="username" placeholder="Username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" required>
                        
                            <label for="password"><b>Enter New Password</b></label>
                            <input for="password" type="password" name="password1" placeholder="New Password" value="<?php if (isset($_POST['password1'])) echo $_POST['password1']; ?>" required>

                            <input for="password" type="password" name="password2" placeholder="Confirm Password" value="<?php if (isset($_POST['password2'])) echo $_POST['password2']; ?>" required>
                        
                            <label for="password"><b>Enter Old Password to Confirm</b></label>
                            <input for="password" type="password" name="password3" placeholder="Current Password" value="<?php if (isset($_POST['password3'])) echo $_POST['password3']; ?>" required>

                            <input type="submit" name="" class="btn" value="Save Changes"/>
                            <button type="button" class="btn cancel" onclick="closeAcc()">Exit</button>

                          </form>
                    </div>
                </div>
            </div>
            <div class="flex-right">
                <h1 style="text-align: center; padding: 20px; color: #d49307; text-shadow: #ffd79b 0 0 20px; font-size: 50px;">
                    Select Your Ship
                </h1>
                <?php
                # Retrieve all current ships from 'ship' database table.
                $shipQuery = "SELECT Ship.shipID, Ship.shipName, Ship.shipDescription 
                FROM Ship 
                WHERE Ship.shipID IN 
                  (SELECT team.shipID 
                   FROM team 
                   WHERE team.userID = {$_SESSION['userID']})";
                
                $r = mysqli_query( $link, $shipQuery ) ;
                echo '<div class="row">';
                if ( mysqli_num_rows( $r ) > 0 )
                {
                    while ($row = mysqli_fetch_assoc($r)) {
                        $shipID = $row['shipID'];
                        $shipName = $row['shipName'];
                        $shipDescription = $row['shipDescription'];

                        
                
                        //echo '<button class="ship-button">';
                        echo '<a class="ship-button" href="msg.php?shipID=' . $shipID . '" style="text-align: center; padding: 60px; font-size: 30px; font-family: papyrus; text-decoration: none;">' . $shipName . '</a>';
                        //echo '</button>';

                        
                    }
                }
                
                echo'<button class="ship-button" onclick="openForm()">
                <img src="PLUS-2.png" style="height: 50px; width: 50px">
                <p>ADD NEW SHIP</p>
                </button></row>' 

                ?>
               <div class="row form-popup" id="myForm">
                    <form style="border: lightcyan;" method="post" action="addShip.php">
                      <h1 style="text-align: center;">Build A Ship!</h1>
                  
                      <label for="shipName"><p style="text-align: center;">Name Yer Ship</p></label>
                      <input for="shipName" type="text" placeholder="Enter Name Here" name="shipName" value="<?php if (isset($_POST['shipName'])) echo $_POST['shipName']; ?>" required>
                  
                      <label for="shipDescription"><p style="text-align: center;">Describe Yer Ship</p></label>
                      <input for="shipDescription" type="text" placeholder="Enter Description Here" name="shipDescription" value="<?php if (isset($_POST['shipDescription'])) echo $_POST['shipDescription']; ?>" required>
                  
                      <button type="submit" class="btn">Finish Construction</button>
                      <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                    </form>
                </div>  
                <div class="row form-popup" id="imgSelect">

                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">



</script>

</html>
