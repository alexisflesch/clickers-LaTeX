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
    $idtable = $_SESSION['idtable'];
    $usertable = $_SESSION['table'];
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    //Create user
    $res = createUser($con, $idtable, $usertable, $username, $password);
    
    if ($res=="ok"){
        echo 'User ' . $username . ' has been created. ';
        echo "<a href='../index.php'>You can now browse back.</a>";
    }
    else{
        echo $res;
    }
    
    //Closing SQL connection
    closeSQL($con);
?>