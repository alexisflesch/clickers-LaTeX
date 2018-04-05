<?php
    //Session data
    session_start();
    $table = $_SESSION['idtable'];
    $user = $_SESSION['user'];


    header('Content-Type: application/json');
    $id = $_POST['id'];
    $aResult = array();
    require "./main.php";
    
    //Connecting to SQL
    $con = connectSQL();
   
    //Is poll id already used by someone else ?
    $res = checkPollId($con, $table, $user, $id);
    $aResult['result'] = $res;
   
    //Setting poll id
    if ($res == "ok"){
        $cmd = "INSERT INTO " .$table . " (name, voteid) VALUES ('";
        $cmd .= $user;
        $cmd .= "','";
        $cmd .= $id;
        $cmd .= "') ON DUPLICATE KEY UPDATE voteid = '" .$id . "'";
        mysqli_query($con, $cmd);
        $aResult['result'] .= ' - id changed to ' . $id;
    }
      
    //Closing SQL connection
    closeSQL($con);
    
    echo json_encode($aResult);
?>