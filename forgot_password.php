<!DOCTYPE html>


<html>
  
  <head>
    <title>Reset Password</title>
    <style>
      * {
        box-sizing: border-box;
      }
      body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0;
        background-color:#004aad;
      }
      .container {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
      }
      h1 {
        text-align: center;
      }
      form {
        background-color: #f2f2f2;
        border-radius: 5px;
        padding: 20px;
        
      }
      input[type="text"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
      }
      button[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
      }
      button[type="submit"]:hover {
        background-color: #45a049;
      }
    </style>
  </head>
  <body>
    <div class="container" >
      <h1>Reset password</h1>
      <form method="POST" action="forgot_password.php">
        <label for="emailadd">Please enter your email here:</label>
        <input type="text" name="email"  required>
        <button name="enter_email" type="submit">Reset Password</button>
      </form>
    </div>
    <script src="script.js"></script>
  
  
  
    <?php
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 
 //Load composer's autoloader
 require 'phpmailer/src/Exception.php';
 require 'phpmailer/src/PHPMailer.php';
 require 'phpmailer/src/SMTP.php';
 
 session_start();

 require ( 'connect_db.php' ) ;
 require ( 'login_tools.php' ) ;

 


 
    
   

 if (isset($_POST["enter_email"]))
 {
     $email = $_POST["email"];
     $user_id = $_SESSION["userID"];
     


      $sql = "SELECT `email` FROM `user` WHERE `userID` = $user_id";
      $result = mysqli_query($link, $sql);
      $row = mysqli_fetch_assoc($result);
      $check_email = $row['email'];
      
    
     

     
    
     
     
     if ($check_email==$email)
     {
    

        $token= bin2hex(random_bytes(10));
        
        $token_hash= hash("sha256", $token);
       
        $sql = "UPDATE user SET pass = '$token_hash' WHERE email = '$email'";
        mysqli_query($link, $sql);
         
        
            
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
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
            $mail->Subject = 'Password reset';
            $mail->Body    = ('This is your OTP verification pin: ' );
            $mail->Body    = $token;
           

            $mail->send();

            echo 'New password has been sent your email';
       
            
       
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }

         load('login.php');
         exit;
         
     }
     else
     {
    
         echo "<script>alert(Email is incorrect! Please try again');</script>";
         
        
        
         
     }
 
 
     mysqli_close( $link ) ;
 }
 
 ?>
  
  
  
  
  
  </body>
  
</html>
