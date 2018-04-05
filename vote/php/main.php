<?php
  
  function connectSQL()
  {
    $con = mysqli_connect("please","set","this","up");
    if (mysqli_connect_errno()){
        return(json_encode("Failed to connect to MySQL: " . mysqli_connect_error()));
    }
    else{
        return $con;
    }
  }

  function closeSQL($con)
  {
    mysqli_close($con);
  }
  

  function getTablename($id, $con)
  //Fetches the name of the table corresponding to vote $id
  {
    //
    $cmd = "SELECT name FROM clickers WHERE voteId = '" . $id . "'";
    $res = mysqli_query($con, $cmd);
    $res = mysqli_fetch_row($res);
    if (is_null($res)){
        return "id error";
    }
    else{
        $table = "clickers_" . $res[0];
        return $table;
    }
  }

  function getPollStatus($con, $table)
  //Fetches poll status
  {
    $cmd = "SELECT SUM(votes) FROM " . $table . " WHERE reponse=1000";
    $res = mysqli_query($con, $cmd);
    $res = mysqli_fetch_row($res);
    return $res[0];
  }
  
  function insertVote($con, $table, $num)
  //If poll is open then insert vote $num in $table
  {
    $closed = getPollStatus($con, $table);
    if ($closed==0){
        $cmd = "INSERT INTO " . $table . "(reponse, votes) VALUES (" ;
        $cmd .= $num;
        $cmd .= ",1) ON DUPLICATE KEY UPDATE votes = votes + 1";
        mysqli_query($con, $cmd);
        return "ok";
    }
    else{
        return "poll closed";
    }
  }

?>