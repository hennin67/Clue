<?php
$gameInstance = true;
require __DIR__ . '/lib/clue.inc.php';
$view = new Clue\WaitingGameView($site, $_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Waiting Game</title>
    <link href="lib/game.css" type="text/css" rel="stylesheet">
</head>

<body id="welcome">
<header>
    <h1> Welcome to Waiting Game Area</h1>
</header>

<div class="usersPlaying">
    <form method="post" action="post/waitingGame.php">
            <?php echo $view->present(); ?>
    </form>
</div>

</body>
</html>