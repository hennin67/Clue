<?php
require 'lib/clue.inc.php';
$open = true;
$view = new Clue\PasswordValidateView($site, $_GET);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Clue Password Validation</title>
    <link href="lib/game.css" type="text/css" rel="stylesheet">
</head>

<body id="welcome">

    <?php
    echo $view->present();
    ?>

</body>
</html>
