<?php

    session_start(); 
    //Is it root ?
    if ($_SESSION['user'] != 'root'){
        exit();
        }

    require 'main.php';
    
    //Connecting to SQL table
    $con = connectSQL();
    
    //Getting info
    $table = $_SESSION['idtable'];
    $user = htmlspecialchars($_POST['username']);
    
    //Removing $user from the system
    
    $res = deleteUser($con, $user);
    
    //Closing SQL connection
    closeSQL($con);
    
    echo $res;
?>