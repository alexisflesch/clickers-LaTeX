<?php
    //Session data
    session_start();
    $table = $_SESSION['table'];

    require "./php/main.php";
    $questions = getQuestions($table);
    echo prettyPrintQuestions($questions);
?>