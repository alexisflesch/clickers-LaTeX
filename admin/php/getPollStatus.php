<?php
    //Session data
    session_start();
    $table = $_SESSION['table'];

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Connecting to SQL table
    $con = connectSQL();
    
    //Initiating SQL table
    $status = getPollStatus($con, $table);
    
    //Closing SQL connection
    closeSQL($con);    
  
    if ($status == 0){
        $aResult['status'] = 'open';
    }
    else{
        $aResult['status'] = 'closed';
    }
    
    echo json_encode($aResult);
?>