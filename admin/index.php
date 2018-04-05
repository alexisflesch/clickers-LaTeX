<?php
    //Session data
    session_start();
    $_SESSION['user'] = $_SERVER['REMOTE_USER'];
    $_SESSION['idtable'] = "clickers";
    $_SESSION['table'] = "clickers_" . $_SESSION['user'];
    $_SESSION['base_dir'] = dirname(__FILE__);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
    <meta charset="utf-8" />
    <link href="./css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/reload.js"></script>
    <script type="text/javascript" src="js/clock.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
    </script>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>
    <script type="text/javascript">
    function getfile(){
        document.getElementById('fileToUpload').click();
    }
    function getvalue(){
        var text = document.getElementById('fileToUpload').value;
        text = text.split('\\');
        text = text[text.length-1];
        document.getElementById('test').innerHTML = text;
    }
    </script>
        <script type="text/javascript">
        $(window).load(function(){
            select();
            makeListClickable();
            getPollStatus();
            getPollId();
            setTimeout(updatePollStatusFromIndex,100);
        });
        window.setInterval(updateFromIndex, 2000);
    </script>
</head>


<body>

      <div class="oneb">
           <?php require "./php/getQuestions.php"?>
     </div>

 <div class="twob">
 
    <ul>
        <li class="foo">
            Administration du sondage
        </li>
    </ul>

    Bonjour <?php echo $_SESSION['user']; ?> ! <br/><br/>
    
    
    Utilisez les boutons ci-dessous pour envoyer un nouveau fichier.
    <br/><br/>
    
    <form class="normal" action="php/uploadFile.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload" style="display:none" onChange="getvalue();">
        <input type="button" class="normal" value="Parcourir" onclick="getfile()" />
        <span id="test">...</span>
        <input type="submit" class="normal" value="Envoyer" name="submit">
    </form>
    <br/><br/>
    
    <a target="_blank" href="./results.php">Afficher les résultats</a>
    <br/><br/>
    
    <a target="_blank" href="./timer.php">Afficher le timer</a>
    <br/><br/>
    
        <span style="left">
            État du sondage : <span id="etatSondage">loading...</span>
        </span>
        <span style="float:right">
            <form class="bla" action="javascript:resumeFromIndex();">
            <input class="untoggled" type="submit" value="Open">
            </form>
            <form class="bla" action="javascript:pauseFromIndex();">
                <input class="untoggled" type="submit" value="Close">
            </form>
            <form class="bla" action="javascript:resetFromIndex();">
                <input class="untoggled" type="submit" value="Reset">
            </form>
        </span>
    

    
    <br/><br/>
        <span style="left">Identifiant du sondage :</span>
        <span style="float:right">
          <form class="bla" name="idForm" action="javascript:changePollId();">
            <input class="noblink" id="voteId" type="text" name="id" value="loading...">
            <input type="submit" value="Change">
          </form>
        </span>
    
    
    <br/><br/>

</div>

<?php
    if ($_SESSION['user']=="root"){
        require "./php/admin.php";
        }
?>
        
    

</body>
</html>
