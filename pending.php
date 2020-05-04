<?php
require 'lib/clue.inc.php';

$games = new \Clue\GameTable($site);

if (!$games->exists($_GET['id'])) {
    header("location: waitingRoom.php");
}

$game = $games->loadGame($_GET['id']);
$board = $game->getBoard();

$_SESSION[CLUE_SESSION] = $game;
$_SESSION[BOARD_SESSION] = $board;

$game = $_SESSION[CLUE_SESSION];
$board = $_SESSION[BOARD_SESSION];

$key = PUSH_KEY_PREFIX . $_GET['id'];
$view = new \Clue\PendingView($site, $_GET['id'], $user);
if ($view->isRedirect()) {
    header("location: " . $view->getRedirect());
    exit;
}

$games = new \Clue\GameTable($site);
$starterId = $games->getCurrentPlayerId($_GET['id']);

echo"<p>Starter: " . $starterId . "</p>";
echo "<p>User: " . $user->getId() . "</p>";
?>
<!doctype html>
<html lang="en">
<head>
    <?php
    echo $view->presentScript();
    ?>
    <meta charset="UTF-8">
    <title>Pending...</title>
    <link href="lib/scss/partials/_instructions.scss" type="text/css" rel="stylesheet">
</head>
<body>
<?php
echo $view->present();
?>
</body>
</html>
