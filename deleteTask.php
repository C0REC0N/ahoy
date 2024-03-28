<?php
# Access session.
session_start();
# Check form submitted.

   
    # Connect to the database.
    require('connect_db.php');
	
	$shipID = $_GET['shipID'];
	$taskID = $_GET['taskID'];
	
    # On success update database for new task name and description
	$deleteQuery = "DELETE FROM task WHERE taskID = $taskID";
		
	$result = mysqli_query($link, $deleteQuery);
	
	if($result){
		
		$_SESSION['msg'] = 'Task Deleted';
		header("Location: task_select.php?shipID=$shipID");
	} else {
		
		$_SESSION['msg'] = 'Task Not Deleted';
		header("Location: task_select.php?shipID=$shipID");
	}
        
 

?>
