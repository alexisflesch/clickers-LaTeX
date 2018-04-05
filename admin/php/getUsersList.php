<?php    
    //Connecting to SQL table
    $con = connectSQL();
    
    //Asking for list of all users
    $table = $_SESSION['idtable'];
    $users = getUsersList($con, $table);
    
    //Closing SQL connection
    closeSQL($con);
?>