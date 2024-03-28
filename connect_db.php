<?php # CONNECT TO MySQL DATABASE. (COMPLETED FUNCTIONALITY)

# NEEDS TO BE CHANGED TO SQL SERVER USED FOR PROJECT
$link = mysqli_connect('132.145.18.222', 'cp2020', 'Ah0yM4t3y!', 'cp2020');

if (!$link) 
{ 
    # Otherwise fail gracefully and explain the error. 
    die('Could not connect to MySQL: ' . mysqli_connect_error()); 
} 
?>
