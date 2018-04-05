<?php
    //Session data
    session_start();
    $table = $_SESSION['table'];

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Connecting to SQL table
    $con = connectSQL();

    //Getting poll number and number of possible answers to fill SQL table
    $num = getNum($con, $table);
    $nbRep = getNbRep($con, $table);
    
    //Initiating SQL table
    initiateSQL($con, $table, $num, $nbRep);
    
    //Closing SQL connection
    closeSQL($con);
    
    $aResult['result'] = "SQL table " . $table . " cleared. Current poll : ";
    $aResult['result'] .= $num;
    echo json_encode($aResult);

?>