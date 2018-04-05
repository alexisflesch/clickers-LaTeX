<?php
    //Session data
    session_start();
    $_SESSION['table'] = "clickers_" . $_SESSION['user'];
    $_SESSION['base_dir'] = dirname(__FILE__);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
    <meta charset="utf-8" />
    <link href="./css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="js/reload.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
    </script>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>
    <script type="text/javascript">
        $(window).load(firstUpdateResults);
        window.setInterval(updateFromResults, 2000);
    </script>
</head>


<body>
    <div class="results">
      <ul id="plop">
     </ul>
    </div>
</body>
</html>