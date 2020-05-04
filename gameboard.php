<?php
require __DIR__ . '/lib/clue.inc.php';

$view = new Clue\BoardView($game);
?>
<!doctype html>
<html lang="en">
<link href="lib/game.css" type="text/css" rel="stylesheet" />

<meta charset="utf-8">
<title>Clue Gameboard</title>

<body id="gameboard">
<?php echo $view->present(); ?>
</body>
</html>
