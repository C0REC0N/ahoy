<!DOCTYPE html>
<?php
# Access session.
session_start();

# Open database connection.
require ( 'connect_db.php' ) ;
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> AHOY </title>
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="parrot.css">
    <script src="main.js"></script> 
	<script src="parrot.js"></script>
</head>

<body style="background-color: lightcyan;" onload="blocked()">
<header>


		<img src="parrot.png" alt="Parrot" id="blocked">
		<br>
		<div id="blockedMessage">Ahoy, Ya Bastard! You've been asked to walk the plank! Please contact yer captain if you think yer still sea worth!</div>


</header>
</html>