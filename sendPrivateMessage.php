<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> AHOY </title>
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="parrot.css">
	<script src="parrot.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
</head>
<body>

<?php
session_start();

# Open database connection.
require ('connect_db.php');

// Function to encrypt the message
function encryptMessage($message, $key) {
    return openssl_encrypt($message, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['msg']) && isset($_POST['recipientUser']) && isset($_POST['shipID'])) {
    $msg = $_POST['msg'];
    $shipID = mysqli_real_escape_string($link, $_POST['shipID']);
    $recipientUsername = mysqli_real_escape_string($link, $_POST['recipientUser']);
    $userID = $_SESSION['userID'];

    $encryptionKey = "EncryptionKey123";
    // Encrypt the message
    $encryptedMessage = encryptMessage($msg, $encryptionKey);

    $retrieveUserID = "SELECT userID FROM user WHERE username = '$recipientUsername'";
    $getUserIDResult = mysqli_query($link, $retrieveUserID);

    if($getUserIDResult) {
        if(mysqli_num_rows($getUserIDResult) > 0) {
            $row = mysqli_fetch_assoc($getUserIDResult);
            $recipientUserID = $row['userID'];
        }
        else {
            echo "Error getting recipient userID";
        }
    }
    
    //Insert the message into the Chat database
    $insertMessageQuery = "INSERT INTO PrivateChat (message, messageDate, SenderUserID, ReceiverUserID, shipID) VALUES ('$encryptedMessage', NOW(), $userID, $recipientUserID, $shipID)";
    $result = mysqli_query($link, $insertMessageQuery);

     //Check if the query was successful
     if ($result) {
        echo '
        <form id="openPrivateChat" method="post" action="privateChat.php">
        <input type="hidden" name="shipID" value="' . $shipID . '">
        <input type="hidden" name="recipientUser" value="' . $recipientUsername . '">
        </form>';        

        // Add JavaScript to submit the form automatically
        echo '<script>document.getElementById("openPrivateChat").submit();</script>';
        exit();
    } else {
        echo "Error: " . mysqli_error($link);
    }

    //Close the database connection
    mysqli_close($link);
} else {
    echo "You have not provided a shipID, recipientUserID or msg";
}
?>

</body>
</html>
