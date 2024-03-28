<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="parrot.css">
    <script src="main.js"></script> 
	<script src="parrot.js"></script> 
    <title>Private Chat Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            background-color: lightcyan;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-image: url('Ahoy_Wood.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .chat-box {
            height: 500px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            background-color: white;        
        }
        .message {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 5px 10px;
            margin-bottom: 5px;
            word-wrap: break-word;
        }
        .return-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .return-button:hover {
            background-color: #45a049;
        }
        .chat-form {
            margin-top: 20px;
        }
        .chat-form textarea {
            width: calc(100% - 80px);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: none;
            margin-bottom: 10px;
        }
        .chat-btn {
            width: 80px;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .chat-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="flex-left">
                <div class="side-nav">
                    <div class="side">
                        <a href="ship_select.php" class="logo">
                            <img src="AHOYYYYY.png" class="logo-img">
                        </a>
                        <a>
                            <img src="starfish.png" class="profile-image">
                        </a>
                        <ul class="prof-links">
                            <li><button class="login-button" onclick="returnToShipPage()"> <p style="text-align: center;"> Return To Ship </p> </button></li>
                        </ul>
                    </div>
                </div>
            </div>

    <div class="container">
        <div class="chat-box">
            <?php

            # Access session.
            session_start();
			
			require ( 'connect_db.php' ) ;

            $checkBlocked = "SELECT reports FROM user WHERE userID = ".$_SESSION['userID']."";
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

            # Open database connection.
         

            // Function for decrypting messages in the database
            function decryptMessage($encryptedMessage, $key) {
                return openssl_decrypt($encryptedMessage, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recipientUser']) && isset($_POST['shipID'])) {

                $shipID = mysqli_real_escape_string($link, $_POST['shipID']);
                $userID = $_SESSION['userID'];
                $recipientUsername = mysqli_real_escape_string($link, $_POST['recipientUser']);
                $retrieveUserID = "SELECT userID FROM user WHERE username = '$recipientUsername'";
                $getUserIDResult = mysqli_query($link, $retrieveUserID);

                if($getUserIDResult) {
                    if(mysqli_num_rows($getUserIDResult) > 0) {  
                        $row = mysqli_fetch_assoc($getUserIDResult);
                        $recipientUserID = $row['userID'];
                        
                        $sql = "SELECT privateChatID, PrivateChat.message, PrivateChat.messageDate, sender.username AS senderUsername, isNew
                        FROM PrivateChat
                        INNER JOIN user sender ON PrivateChat.SenderUserID = sender.userID
                        WHERE PrivateChat.ShipID = $shipID
                        AND (($userID = PrivateChat.SenderUserID AND $recipientUserID = PrivateChat.ReceiverUserID)
                        OR ($userID = PrivateChat.ReceiverUserID AND $recipientUserID = PrivateChat.SenderUserID))
                        ORDER BY PrivateChat.messageDate DESC";
                        
                        $result = mysqli_query($link, $sql);
                        echo "<h1> Private Chat </h1><br>";
                        if ($result) {
                            // Check if there are any messages
                            if (mysqli_num_rows($result) > 0) {
                                // Display all messages between these two users
                                while ($row = mysqli_fetch_assoc($result)) {

                                    if ($row['isNew'] == 1){
										$seenQuery = "UPDATE PrivateChat SET isNew = 0 WHERE privateChatID = ".$row['privateChatID']."";
										$seen = mysqli_query($link, $seenQuery);
									}
                                    
                                    $decryptedMessage = decryptMessage($row['message'], "EncryptionKey123");

                                    $senderName = ($row['senderUsername'] == $_SESSION['username']) ? 'You' : $row['senderUsername'];
                                    echo "<strong>$senderName</strong> ({$row['messageDate']}): {$decryptedMessage}<br><br>";
                                }
                            } else {
                                echo "No messages found.";
                            }
                        } else {
                            echo "Error executing query: " . mysqli_error($link);
                        }
                    } else {
                        echo "Recipient user not found.";
                    }
                } else {
                    echo "Error: " . mysqli_error($link);
                }
            } else {
                echo "Incomplete data provided.";
            }
            ?>           
        </div>
        <form class="chat-form" method="post" action="sendPrivateMessage.php">
            <textarea placeholder="Type message.." name="msg" required></textarea>
            <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
            <input type="hidden" name="recipientUser" value="<?php echo $recipientUsername ?>">
            <input type="submit" class="chat-btn" value="Send">
        </form>
    </div>

    <script>
        function returnToShipPage() {
            var shipID = "<?php echo $shipID; ?>";
            window.location.href = "ship_page.php?shipID=" + shipID;
        }
    </script>
</body>
</html>

