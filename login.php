<!DOCTYPE html>




<?php # DISPLAY COMPLETE LOGIN PAGE. (COMPLETED FUNCTIONALITY)

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';




if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
 # Open database connection.
 require ( 'connect_db.php' ) ;
 # Get connection, load, and validate functions.
 require ( 'login_tools.php' ) ;
 # Check login.
 list ( $check, $data ) = validate ( $link, $_POST[ 'email' ], $_POST[ 'pass' ] ) ;
 
 

 # On success set session data and display logged in page.
if ($check) {
    $user_id = $data['userID'];
    $sql = "SELECT `is_2fa_enabled` FROM `user` WHERE `userID` = $user_id";
    $results = mysqli_query($link, $sql);

    $reported = $data['userID'];
	
    $checkBlocked = "SELECT reports FROM user WHERE userID = $reported";
    $result = mysqli_query($link, $checkBlocked);


    // Check if reports are 10 or more
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if ($row['reports'] > 9) {
					
					load('blocked.html');
				};
			}
		}

        $isPMQuery = "SELECT * FROM user WHERE userID = $reported AND roleChoice = 'platform_manager'";
		$PMresult = mysqli_query($link, $isPMQuery);
		
		if (mysqli_num_rows($PMresult) > 0) {
			$isPM = true;
		} 
		else {
			$isPM = false;
		}

    

   if (mysqli_num_rows($results) == 1) {
        $row = mysqli_fetch_assoc($results);
        $is_2fa_enabled = $row['is_2fa_enabled'];

        # Start session
        session_start();
        $_SESSION['userID'] = $user_id;
        $_SESSION['username'] = $data['username'];
        $email=$_POST['email'];
        if ($isPM){
				$_SESSION['msg'] = 'Ahoy, Captain!';
			} else {
				
				$_SESSION['msg'] = 'Ahoy, Matey!';
			}

        

        if ($is_2fa_enabled == 1) {

            $otp = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);


            $sql = "UPDATE `user` SET `otp` = '$otp' WHERE `userID` = $user_id";
            $result = mysqli_query($link, $sql);





            
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'sevenseassoftware123@gmail.com';                 // SMTP username
            $mail->Password = 'jeseutidewosqras';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('sevenseassoftware123@gmail.com');
            $mail->addAddress($email);     // Add a recipient
            

            //Attachments
            
            
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'OTP verifaction';
            $mail->Body    = ('This is your OTP verification pin: ' );
            $mail->Body    = $otp;
           

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
           

            // Redirect to OTP verification page
            
            load( 'enter_pin.php');
            exit;
        } else {
            # 2FA disabled, continue to ship_select.php
            load('ship_select.php');
        }
    } else {
        # Error fetching 2FA status, handle appropriately
        echo "Error: Could not determine 2FA status.";
    }
}
 # Or on failure set errors.
 else { $errors = $data; } 
 # Close database connection.
 mysqli_close( $link ) ; 
}

# Display any error messages if present.
if ( isset( $errors ) && !empty( $errors ) ){
    $cheese = "";
    foreach ( $errors as $msg ) 
        {
            $cheese .= $msg ;
            $cheese .= " "; 
        }

    echo "<script>alert('Oops, looks like you have not entered all your data: $cheese');</script>";
}
?>


<!-- Display body section. -->
<html>
<head>

    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title> Please Login </title>

</head>

<body class="login-page">

<img src="AHOYYYYY.png" alt="ahoy logo" class="centerlogo" style="padding-bottom: 50px;">

<form class="box" action="login.php" method="post" style="border-color:#004aad">
    <div class="row" style="justify-content: center;">

        <label for="email"><b style="color: #ffd79b;">Enter Email</b></label>
        <input for="inputEmail" type="text" name="email" placeholder="Email">

        <label for="pass"><b style="color: #ffd79b">Enter Password</b></label>
        <input for="inputPassword" type="password" name="pass" placeholder="Password">

        <button class="login-button two" type="submit">Login</button>

    </div>
	
	<br>

    <div class="row" style="background-color:#004aad">
        <p style="color: #ffd79b;"> Don't have an account?</p>
        <a href="register.php" style="color:#3498db"> Register here </a>

        <p style="color: #ffd79b;"> Forgotten your password?</p>
        <a href="forgot_password.php" style="color:#3498db"> Reset it here </a>
    </div>
</form>

</body>
</html>


<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXz2htPH01sSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+TbbvYUew+OrCXaRkfj" crossorigin="anonymous"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqks2by6br4gND7DXjqke9RmUpD8jgGtD72P9yug3goQfGIIeyAns" crossorigin="anonymous"></script>

