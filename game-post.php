<?php
require __DIR__ . '/lib/clue.inc.php';

$controller = new Clue\GameController($game, $_POST, $site);

$_SESSION[CLUE_SESSION] = $controller->getGame();
echo "<p>" . $controller->getState() . "</p>";

/*if (isset($_POST['node'])) {
    $controller->setState(1);
}*/

if($controller->isReset()) {
    unset($_SESSION[CLUE_SESSION]);
    unset($_SESSION[BOARD_SESSION]);

    $gameTable = new \Clue\GameTable($site);
    $gameTable->deleteGameID($controller->getGame()->getId());
}

if (isset($_SESSION[CLUE_SESSION])) {
    $_SESSION[CLUE_SESSION]->resetRoomState();

    $games = new \Clue\GameTable($site);
    $games->saveGame($game);
}

// Add push support!
/*
 * PHP code to cause a push on a remote client.
 */
$key = PUSH_KEY_PREFIX . $game->getId();

$msg = json_encode(array('key'=> $key, 'cmd'=>'reload'));

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$sock_data = socket_connect($socket, '127.0.0.1', 8078);
if(!$sock_data) {
    echo "Failed to connect";
} else {
    socket_write($socket, $msg, strlen($msg));
}
socket_close($socket);

header('location: ' . $controller->getPage());