<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
    <meta charset="utf-8" />
    <link href="css//minimal.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
    </script>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>
    <script type="text/javascript">
       var voteId = "<?php echo htmlspecialchars($_POST['voteId']); ?>";
    </script>
</head>


<body>

      <div class="results">
            <?php 
                $id = htmlspecialchars($_POST['voteId']);
                require "./php/main.php";
                $con = connectSQL($table);
                $table = getTablename($id, $con);
                closeSQL($con);
                if ($table == "id error"){
                    echo "You entered a wrong id";
                    }
                else{
                    $question = "./questions/question_" . $table . ".php";
                    require $question;
                    }
            ?>
     </div>
     
</body>
</html>
