<?php
# Access session.
session_start();

# Open database connection.
require ('connect_db.php');

// Function for decrypting messages in the database
function decryptMessage($encryptedMessage, $key) {
    return openssl_decrypt($encryptedMessage, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
}

if (isset($_GET['shipID'])) {
    $shipID = $_GET['shipID'];
    $seen = $_GET['seen'];

    // Query to select messages linked to the specific ship
    $sql = "SELECT Chat.message, Chat.date, user.username
            FROM Chat
            INNER JOIN user ON Chat.userID = user.userID
            WHERE Chat.shipID = $shipID
            ORDER BY Chat.date DESC";

    $result = mysqli_query($link, $sql);

    //If query was successful
    if ($result) {
        //Check if there are any messages in the Chat table
        if (mysqli_num_rows($result) > 0) {

            if ($seen){
				$newChat = "UPDATE team SET newMessage = 0 WHERE userID = ".$_SESSION['userID']."";
				$chat = mysqli_query($link, $newChat);
			}

            // Loop through each message and display it
            while ($row = mysqli_fetch_assoc($result)) {
                // Decrypt the message so that it can be displayed
                $decryptedMessage = decryptMessage($row['message'], "EncryptionKey123");

                // Display the message along with its elements
                $username = $row['username'];
                $date = $row['date'];
                echo "<strong>{$username}</strong> ({$date}): {$decryptedMessage}<br><br>";
            }
        } else {
            echo "No messages in ship.";
        }
    } else {
        echo "Error executing query: " . mysqli_error($link);
    }
} else {
    echo "No shipID provided.";
}
?>
