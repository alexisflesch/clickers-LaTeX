<?php
    //Session data
    session_start();
    $table = $_SESSION['idtable'];
    $user = $_SESSION['user'];

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Connecting to SQL table
    $con = connectSQL();
    
    //Initiating SQL table
    $id = getPollId($con, $table, $user);
    
    //Closing SQL connection
    closeSQL($con);    
    
    $aResult['id'] = $id;
    echo json_encode($aResult);
?>