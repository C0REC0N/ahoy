<?php
# Access session.
session_start() ;

# Check form submitted.
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  # Connect to the database.
  require ('connect_db.php');

# Initialize an error array.
  $errors = array();

# Check for an email address:
  if ( empty( $_POST[ 'username' ] ) )
  { $errors[] = 'Please enter a new username.'; }
  else
  { $username = mysqli_real_escape_string( $link, trim( $_POST[ 'username' ] ) ) ; }

# Check for a password and matching input passwords.
  if ( !empty($_POST[ 'password1' ] || !empty($_POST[ 'password2' ]) ) )
  {
    { $passwordNew = mysqli_real_escape_string( $link, trim( $_POST[ 'password1' ] ) ) ; }
  }
  else { $errors[] = 'Please enter a new password.' ; }

  if ( !empty($_POST[ 'password1' ] == !empty($_POST[ 'password2' ]) ) )
  {
    { $passwordNew = mysqli_real_escape_string( $link, trim( $_POST[ 'password1' ] ) ) ; }
  }
  else { $errors[] = 'Passwords do not match.' ; }

  if ( !empty($_POST[ 'password3' ] ) )
  {
    { $passwordOld = mysqli_real_escape_string( $link, trim( $_POST[ 'password3' ] ) ) ; }
  }
  else { $errors[] = 'Please enter your old password.' ; }

# On success update database for new username and password
  if ( empty( $errors ) ) 
  {
    
    $check = "SELECT userID, username FROM user WHERE userID={$_SESSION['userID']} AND pass=SHA2('$passwordOld',256)";
    $result = mysqli_query($link, $check);

    //check if query matches
    if (!$result) 
    {
        $errors[] = 'Error: ' . mysqli_error($link);
    } 
    else {
        //update with new password
        $updateQuery = "UPDATE user SET pass=SHA2('$passwordNew',256), username='$username' WHERE userID={$_SESSION['userID']}";

        $r = @mysqli_query ( $link, $updateQuery ) ;
        if ($r)
        {
          header("Location: ship_select.php");
        } else {
            echo "Error updating record: " . $link->error;
        }
    }
# Close database connection.
    
	mysqli_close($link); 
    exit();
  }
# Or report errors.
  else 
  {	  
    echo '<body style="color:white"> <div style="margin-top:10px;"class="container"><div class="alert alert-dark alert-dismissible fade show">
	<h1><strong>Error!</strong></h1><p>The following error(s) occurred:<br>' ;
    foreach ( $errors as $msg )
    { echo " - $msg<br>" ; }
    echo 'Please try again.</div></div></body>';
    # Close database connection.
    mysqli_close( $link );
  }  
}
?>
