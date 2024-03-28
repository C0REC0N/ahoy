<?php
# Access session.
session_start();
# Check form submitted.

   
    # Connect to the database.
    require('connect_db.php');
	
	$shipID = $_GET['shipID'];
	$taskID = $_GET['taskID'];
	
    # On success update database for new task name and description
	$updateQuery = "UPDATE task SET completed = 1 WHERE taskID=$taskID";
		
	$result = mysqli_query($link, $updateQuery);
	
	if($result){
		
		$_SESSION['msg'] = 'Task Completed';
		header("Location: task_select.php?shipID=$shipID");
	} else {
		
		$_SESSION['msg'] = 'Task Not Completed';
		header("Location: task_select.php?shipID=$shipID");
	}
        
 

?>
