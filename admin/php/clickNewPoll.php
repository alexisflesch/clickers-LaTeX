<?php
    //Session data
    session_start();
    $table = $_SESSION['table'];

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Getting new poll number
    $num = $_POST['question'];
    
    //Creating poll
    $nbRep = generatePoll($num, $table);
    
    //Connecting to SQL table
    $con = connectSQL();
    
    //Initiating SQL table
    initiateSQL($con, $table, $num, $nbRep);
    
    //Closing SQL connection
    closeSQL($con);
    

    //Output for javascript function
    $aResult['result'] = "SQL table " . $table . " has been cleared. Poll number ";
    $aResult['result'] .= $num;
    $aResult['result'] .= " has been generated. It contains " . $nbRep . " possible answers.";

    echo json_encode($aResult);

?>