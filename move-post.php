<?php
require __DIR__ . '/lib/clue.inc.php';
$controller = new Clue\GameController($game, $_POST, $site);

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'pass':
            $controller->onPass();
            break;
        case 'suggest':
            $controller->setAction('suggest');
            $controller->SetState(2);
            break;
        case 'accuse':
            $controller->setAction('accuse');
            $controller->SetState(2);
            break;
    }
}

elseif (isset($_POST['suspect'])) {
    $controller->setState(3);
    $controller->addToGuess(strip_tags($_POST['suspect']));
}

elseif (isset($_POST['weapon'])) {
    $controller->setState(4);
    $controller->addToGuess(strip_tags($_POST['weapon']));
}

else {
    $controller->onPass();
}

$games = new \Clue\GameTable($site);
$games->saveGame($game);

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


header("location: " . $controller->getPage());