<?php

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Getting new poll number
    $num = $_POST['answer'];
    $id = $_POST['voteId'];
    
    //Connecting to SQL table
    $con = connectSQL($table);
    
    //Finding table name
    $table = getTablename($id, $con);
    
    //Inserting vote (if poll is open)
    $res = insertVote($con, $table, $num);
    
    //Closing SQL connection
    closeSQL($con);
    
    //Output for javascript function
    $aResult['result'] = $res;
    $aResult['id'] = $id;
    $aResult['num'] = $num;
    $aResult['table'] = $table;
    echo json_encode($aResult);

?>