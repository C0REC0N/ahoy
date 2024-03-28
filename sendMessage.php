<?php
session_start();

# Open database connection.
require('connect_db.php');

// Function to encrypt the message
function encryptMessage($message, $key) {
    return openssl_encrypt($message, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['msg']) && isset($_GET['shipID'])) {
    $msg = $_POST['msg'];
    $shipID = $_GET['shipID'];
    $userID = $_SESSION['userID'];
    
    $encryptionKey = "EncryptionKey123";

    // Encrypt the message
    $encryptedMessage = encryptMessage($msg, $encryptionKey);

    // Insert the encrypted message into the Chat database
    $insertMessageQuery = "INSERT INTO Chat (message, date, userID, shipID) VALUES ('$encryptedMessage', NOW(), $userID, $shipID)";
    $result = mysqli_query($link, $insertMessageQuery);

    // Check if the query was successful
    if ($result) {

        $newMessageQuery = "UPDATE team SET newMessage = 1 WHERE shipID = $shipID AND userID <> $userID";
		$new = mysqli_query($link, $newMessageQuery);
        // Redirect back to the chat page
		$_SESSION['chat'] = true;
        header("Location: ship_page.php?shipID=$shipID");
        exit();
    } else {
        echo "Error: " . mysqli_error($link);
    }

    // Close the database connection
    mysqli_close($link);
} else {
    echo "Invalid request";
}
?>
