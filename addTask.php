<?php
# Access session.
session_start();
# Check form submitted.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["shipID"])) {
    $shipID = $_POST["shipID"];//store ship id in relevant variable
    # Connect to the database.
    require('connect_db.php');
    
    # Initialize an error array.
    $errors = array();

    # Check for a task name:
    if (empty($_POST['taskName'])) {
        $errors[] = 'Please enter a task name.';
    } else {
        $tn = mysqli_real_escape_string($link, trim($_POST['taskName']));
    }

    # Check for a task description
    if (!empty($_POST['taskDesc'])) {
        $td = mysqli_real_escape_string($link, trim($_POST['taskDesc']));
    } else {
        $errors[] = 'Please enter a description of your task.';
    }




    # On success update database for new task name and description
    if (empty($errors)) {
        $insertTaskQuery = "INSERT INTO task (taskName, taskDesc, shipID) VALUES ('$tn', '$td', $shipID)";
        
        
        $r = @mysqli_query($link, $insertTaskQuery);
        
        if ($r) {
                        $_SESSION['msg'] = 'Task Created';
                        header("Location: task_select.php?shipID=$shipID");
                        exit();
                    } else {
                        echo "Error updating record: " . mysqli_error($link);
                    } 
        
        # Close database connection.
        mysqli_close($link);
    } 
    #else echo error messages
    else {
        echo '<body style="color:white"> <div style="margin-top:10px;"class="container"><div class="alert alert-dark alert-dismissible fade show">
        <h1><strong>Error!</strong></h1><p>The following error(s) occurred:<br>';
        foreach ($errors as $msg) {
            echo " - $msg<br>";
        }
        echo 'Please try again.</div></div></body>';
        
        # Close database connection.
        mysqli_close($link);
    }
}
?>
