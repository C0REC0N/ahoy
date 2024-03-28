<?php
# Access session.
session_start();

$_SESSION['msg'] = null;

$userID = $_SESSION['userID'];

# Open database connection.
require ( 'connect_db.php' ) ;


$imageName = $_GET['imageName'];

  

$sql = "UPDATE user SET pfp = '$imageName' WHERE userID = '$userID'";
$result = mysqli_query($link, $sql);

header("Location: ship_select.php");


