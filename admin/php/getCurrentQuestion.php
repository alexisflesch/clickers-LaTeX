<?php
    //Session data
    session_start();
    $table = $_SESSION['table'];

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Connecting to SQL table
    $con = connectSQL();
    
    //Getting number of current poll
    $num = getNum($con, $table);
    
    //Closing SQL connection
    closeSQL($con);
    
    //Getting the question and the corresponding answers
    list($question, $reponses) = getQuestion($num, $table);
    
    //Output for javascript function
    $aResult['question'] = $question;
    $aResult['reponses'] = $reponses;
    $aResult['status'] = "ok";
    echo json_encode($aResult);
?>