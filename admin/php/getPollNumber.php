<?php
    //session data
    session_start();
    $table = $_SESSION['table'];

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Connecting to SQL table
    $con = connectSQL();
    
    //Initiating SQL table
    $num = getNum($con, $table);
    
    //Closing SQL connection
    closeSQL($con);    
    
    //Outpu
    $aResult['num'] = $num;
    echo json_encode($aResult);
?>