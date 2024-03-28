<!DOCTYPE html>
<html>
  
  <head>
    <title>OTP Code Verification Form</title>
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
      <h1>OTP Code Verification</h1>
      <form method="POST" action="enter_pin.php">
        <label for="otpCode">Enter the OTP code sent to your email:</label>
        <input type="text" name="otp" maxlength="6" minlength="6" required>
        <button name="enter_pin" type="submit">Verify OTP Code</button>
      </form>
    </div>
    <script src="script.js"></script>
  
  
  
    <?php
 
 session_start();

 require ( 'connect_db.php' ) ;
 require ( 'login_tools.php' ) ;


 
    
   

 if (isset($_POST["enter_pin"]))
 {
     $otp = $_POST["otp"];
     $user_id = $_SESSION["userID"];


      $sql = "SELECT `otp` FROM `user` WHERE `userID` = $user_id";
      $result = mysqli_query($link, $sql);
      $row = mysqli_fetch_assoc($result);
      $check_otp = $row['otp'];
    
     

     
    
     
     
     if ($check_otp==$otp)
     {
         $sql = "UPDATE user SET otp = '' WHERE userID = '$user_id'";
         mysqli_query($link, $sql);

         
         load('ship_select.php');
     }
     else
     {
    
         echo "<script>alert('Wrong pin! Please try again');</script>";
         
        
        
         
     }
 
 
     mysqli_close( $link ) ;
 }
 
 ?>
  
  
  
  
  
  </body>
  
</html>
