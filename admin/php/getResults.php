<?php
    //Session data
    session_start();
    $table = $_SESSION['table'];

    header('Content-Type: application/json');
    $aResult = array();
    require "./main.php";
    
    //Connecting to SQL table
    $con = connectSQL();
    
    //Getting results
    list($nbVotes,$res) = getResults($con, $table);

    //Closing SQL connection
    closeSQL($con);
    
    //Retour du script php
    $aResult['status'] = "ok";
    $aResult['nbVotes'] = $nbVotes;
    $aResult['resultats'] = $res;
    echo json_encode($aResult);
?>