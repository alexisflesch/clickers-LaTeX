<?php
    //Session data
    session_start();
    $table = $_SESSION['table'];

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Connecting to SQL
    $con = connectSQL();
   
    //Setting poll to paused
    $cmd = "INSERT INTO " .$table . " (reponse, votes) VALUES (1000,1) ON DUPLICATE KEY UPDATE votes = 1";
    mysqli_query($con, $cmd);
    
    //Closing SQL connection
    closeSQL($con);
    

    $aResult['result'] = "Poll paused";
    echo json_encode($aResult);
?>