<?php
    //Session data
    session_start();
    $table = $_SESSION['table'];
    $base_dir = $_SESSION['base_dir'];
?>

<meta charset="utf-8" />

<?php
    require "./main.php";
    $target_file = join_paths($base_dir,"tex", $table.".tex");

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Le fichier ". basename( $_FILES["fileToUpload"]["name"]). " a été mis en ligne";
        echo "<br>";
        echo "<a href='../index.php'>Vous pouvez retourner à la page précédente</a>";
             
        //Connecting to SQL table
        $con = connectSQL();
    
        //Initiating SQL table
        initiateSQL($con, $table, -1, 1);
    
        //Closing SQL connection
        closeSQL($con);
    }
    else {
        echo "Erreur durant le transfert";
    }
?>