<head>
    <meta charset="utf-8" />
    <link href="css/minimal.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <div class="results">
    <?php
        if ($_GET["res"]=="ok"){
            echo 'Thank you !';
            }
        else{
            echo 'Sorry, poll is closed.';
            }
    ?>
  </div>
</body>