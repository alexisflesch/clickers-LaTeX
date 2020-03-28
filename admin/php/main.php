<?php
  // Session data
  session_start();
  
  function join_paths() {
    //Utility function to deal with pathnames
    $paths = array();
    foreach (func_get_args() as $arg) {
        if ($arg !== '') { $paths[] = $arg; }
    }
    return preg_replace('#/+#','/',join('/', $paths));
  }


  function connectSQL()
  {
    $con = mysqli_connect("mysql51-103.perso","alexisflscore","XjN7ARbv4j6Y","alexisflscore");
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
  

  function getQuestions($table)
  //Fetches all questions (but not the corresponding answers)
  {
    $content = "";
    $filename = join_paths($_SESSION['base_dir'], "/tex" ,$table.".tex");
    if (file_exists($filename)){
        $f = fopen($filename, "r") or exit("Unable to open file!");
        //Output a line of the file until the end is reached
        while(!feof($f))
        {
            $content = $content . "    " . fgets($f);
        }
        fclose($f);
        $clickers = explode("\begin{clickers}",$content);
        $nbQuestions = count($clickers);
        $questions = array();
        foreach (range(1,$nbQuestions-1) as $i){
            $bla = explode("\begin{itemize}", $clickers[$i]);
            $bla = $bla[0];
            $bla = stripFirstLine($bla);//Removing first line in case of a label
            $questions[] = $bla;
        }
    }
    else{
        $questions = false;
    }
    return $questions;
  }

  
  function prettyPrintQuestions($questions)
  //Formate la question et les réponses pour affichage web
  {
     if ($questions==false){
         $text = "<ul><li class='foo'>Veuillez uploader un fichier tex</li></ul>\n";
     }
     else{
        $text = "<ul><li class='foo'>Sélectionnez une question</li></ul>\n";
        $text .= "<ul id='myid'>\n";
        $i = 0;
        foreach ($questions as $question){
            $text .= "    <li class='rep' id=" . $i . ">" . $question . "</li>\n";
            $i += 1;
        }
        $text .= "</ul>";
     }
     return $text;
  }
  

  function stripFirstLine($text)
  //Strips the first line of a string
  {        
    return substr( $text, strpos($text, "\n")+1 );
  }

  function checkPollId($con, $table, $user, $id)
  //Checks if $id is already used by someone else
  {
    $cmd = "SELECT name FROM " . $table . " WHERE voteId='" . $id ."'";
    $res = mysqli_query($con, $cmd);
    $res = mysqli_fetch_row($res);
    if (is_null($res)){
        return "ok";
    }
    elseif ($res[0]==$user){
        return "nothing to change";
    }
    else{
        return "id already in use";
    }
  }
  
  
  function getPollId($con, $table, $user)
  //Gets poll id
  {
    $cmd = "SELECT voteId FROM " . $table . " WHERE name='" . $user ."'";
    $res = mysqli_query($con, $cmd);
    $res = mysqli_fetch_row($res);
    if (is_null($res)){
        return "Please set an id";
    }
    else{
        return $res[0];
    }
  }
  
  function setPollId($con, $table, $user)
  //Sets poll id
  {
    $cmd = "INSERT INTO " .$table . " (reponse, votes) VALUES (1000,1) ON DUPLICATE KEY UPDATE votes = 1";
    mysqli_query($con, $cmd);
  }
  
  function getNum($con, $table)
  //Fetches question number of current poll
  {
    $cmd = "SELECT SUM(votes) FROM " . $table . " WHERE reponse=1001";
    $res = mysqli_query($con, $cmd);
    $res = mysqli_fetch_row($res);
    return $res[0];
  }

  function getPollStatus($con, $table)
  //Fetches poll status
  {
    $cmd = "SELECT SUM(votes) FROM " . $table . " WHERE reponse=1000";
    $res = mysqli_query($con, $cmd);
    $res = mysqli_fetch_row($res);
    return $res[0];
  }  
  
  function getNbRep($con, $table)
  //Fetches how many questions there are in the current poll
  {
    $cmd = "SELECT COUNT(reponse) FROM " . $table . " WHERE reponse<1000";
    $res = mysqli_query($con, $cmd);
    $res = mysqli_fetch_row($res);
    return $res[0];
  }

  function getResults($con, $table)
  //Get the results of the current poll
  {
    $result = mysqli_query($con,"SELECT * FROM " . $table . " ORDER BY reponse ASC");
    $nbVotes = 0;
    $res = array();
    while($row = mysqli_fetch_array($result)){
        if ($row['reponse']<1000){
            $nbVotes += $row['votes'];
            $res[] = intval($row['votes']);
        }
    }
    return array($nbVotes,$res);
  }
  
  function getQuestion($num, $table)
  //Get question number $num from $table.tex file.
  {
    $content = "";
    $filename = join_paths($_SESSION['base_dir'], "/tex" ,$table.".tex");
    $f = fopen($filename, "r") or exit("Unable to open file!");
    //Output a line of the file until the end is reached and save it into $content
    while(!feof($f))
    {
        $content = $content . "    " . fgets($f);
    }
    fclose($f);
    $clickers = explode("\end{clickers}",$content);
    $clicker = $clickers[$num];
    $clicker = explode("\begin{clickers}", $clicker);
    $clicker = $clicker[1];
    $clicker = stripFirstLine($clicker); //On enlève la première ligne pour virer le label éventuel
    $clicker = explode("\begin{itemize}", $clicker);
    $question = $clicker[0];
    $reponses = str_replace("\end{itemize}", "", $clicker[1]);
    $reponses = explode("\item", $reponses);
    unset($reponses[0]);
    $reponses = array_values($reponses);
    return array($question, $reponses);
  }

  
  function prettyPrintPoll($question, $reponses)
  //Formate la question et les réponses pour affichage web
  {
     $text = "<ul><li class='foo'>" . $question . "</li></ul>\n";
     $text .= "<ul id='myid'>\n";
     $i = 0;
     foreach ($reponses as $reponse){
        $text .= "    <li class='rep' id=" . $i . ">" . $reponse . "</li>\n";
        $i += 1;
     }
     $text .= "</ul>";
     return $text;
  }
  
  function generatePoll($num, $table)
  //Creates file question_$table.php with poll number $num
  {
      list($question, $reponses) = getQuestion($num, $table);
      $text = prettyPrintPoll($question, $reponses);
      $filename = join_paths($_SESSION['base_dir'], "/../vote/questions/", "question_" . $table .".php");
      $f = fopen($filename, "w") or exit("Unable to open file!");
      fwrite($f, $text);
      fclose($f);
      return count($reponses);//To fill the SQL table
  }
  
  function initiateSQL($con, $table, $num, $nbRep)
  //Clears SQL table and writes the necessary data
  {
    //Clearing table
    mysqli_query($con,"TRUNCATE TABLE " . $table);
    
    //Filling the SQL table
    foreach (range(0, $nbRep-1) as $i) {
        $cmd = "INSERT INTO " . $table . " (reponse, votes) VALUES (" ;
        $cmd .= $i;
        $cmd .= ",0)";
        mysqli_query($con, $cmd);
    }
    
    //Setting poll as closed
    $cmd = "INSERT INTO " . $table . " (reponse, votes) VALUES (1000,0)";
    mysqli_query($con, $cmd);
    
    //Registering poll number in the table
    $cmd = "INSERT INTO " . $table . " (reponse, votes) VALUES (1001,";
    $cmd .= $num;
    $cmd .= ")";
    mysqli_query($con, $cmd);
  }

  function getUsersList($con, $table)
  //Gets list of users (for root)
  {
    $cmd = "SELECT name FROM " . $table . " WHERE 1 ORDER BY name";
    $res = mysqli_query($con, $cmd);
    $users = array();
    while($row = mysqli_fetch_row($res)){
        $users[] = $row[0];
    }
    return $users;
  }

  function deleteUser($con, $user)
  //Remove $user data (in "clickers" table) and remove its own table (clickers_$user)
  //Finally, remove user from .htpasswd
  {
    //Delete $user data (his id in the main table - clickers)
    $cmd = "DELETE FROM clickers WHERE name='" .$user . "'";
    mysqli_query($con, $cmd);
    
    //Drop user table (clickers_$user)
    $cmd = "DROP TABLE clickers_" .$user;
    mysqli_query($con, $cmd);
    
    //Delete user from .htpasswd
    $filename = join_paths($_SESSION['base_dir'],"../",".htpasswd");
    if (file_exists($filename)){
        $f = fopen($filename, "r") or exit("Unable to open file!");
        $content = "";
        while(!feof($f))
        {
            $line = fgets($f);
            $exp = explode(":", $line);
            if ($exp[0]!=$user){
                $content .=  $line;
            }
        }
        fclose($f);
        $f = fopen($filename, "w") or exit("Unable to write password file !");
        fwrite($f, $content);
        fclose($f);
        return "User " . $user . " has been deleted.";
    }
    else{
        return "Couldn't open file " . $filename;
    }
  }
  
  function createUser($con, $idtable, $usertable, $username, $password)
  //Creates a new user
  {
    //Add $username/$password to .htpasswd if $username doesn't already exist
    $filename = join_paths($_SESSION['base_dir'],"../",".htpasswd");
    if (file_exists($filename)){
        $f = fopen($filename, "r") or exit("Unable to open file!");
        $content = "";
        while(!feof($f))
        {
            $line = fgets($f);
            $exp = explode(":", $line);
            if ($exp[0]==$username){
                return "Error : user already exists";
            }
            elseif($line != "\n") { //Strip last empty line
                $content .=  $line;
            }
        }
        fclose($f);
        $content .= $username . ":" . crypt($password, base64_encode($password));
        $content .= "\n"; //.htpasswd should end with an empty line
        $f = fopen($filename, "w") or exit("Unable to write password file !");
        fwrite($f, $content);
        fclose($f);
    }
    else{
        echo "Couldn't open file " . $filename;
    }
    
    //Then, create a new table for $username (clickers_$username)
    $cmd = "CREATE TABLE clickers_" . $username . " (
                reponse INT(11) PRIMARY KEY, 
                votes INT(11) NOT NULL
            )";
    mysqli_query($con, $cmd);
    
    //Finally, add temporary id to the main table (clickers)
    $cmd = "INSERT INTO " . $idtable . " (name, voteId) VALUES ('" . $username ."','Please set an id')";
    mysqli_query($con, $cmd);
    
    //Return
    return "ok";
  }
  
  
?>
