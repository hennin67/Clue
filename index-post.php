<?php
require __DIR__ . '/lib/clue.inc.php';

// Create all of the players for the game
$players = [];

if (isset($_GET['Owen'])) {
    $owen = new \Clue\Player('Owen');
    $players[] = $owen;
}
if (isset($_GET['Enbody'])) {
    $enbody = new \Clue\Player('Enbody');
    $players[] = $enbody;
}
if (isset($_GET['McCullen'])) {
    $mccullen = new \Clue\Player('Mccullen');
    $players[] = $mccullen;
}
if (isset($_GET['Plum'])) {
    $plum = new \Clue\Player('Plum');
    $players[] = $plum;
}
if (isset($_GET['Day'])) {
    $day = new \Clue\Player('Day');
    $players[] = $day;
}
if (isset($_GET['Onsay'])) {
    $onsay = new \Clue\Player('Onsay');
    $players[] = $onsay;
}

// Assign player -> userIds
$users = new \Clue\Users($site);
$ids = $users->getGameUserIds($_GET['id']);

for($i = 0; $i < count($ids); $i++) {
    $players[$i]->setUserId($ids[$i]);
}

// Construct players
$game = new \Clue\Game($players);
$board = new \Clue\Board($game);
$game->setBoard($board);
$game->dealHands();
$board->setPlayerInitialPositions();
$game->setId($_GET['id']);

$_SESSION['game'] = $game;
$_SESSION['board'] = $board;

//$num = sizeof($state->getPlayers());
//print_r($state->getPlayers());
//echo "<p>$num</p>";
$num = $game->countPlayers();

$games = new \Clue\GameTable($site);
$games->saveGame($game);

header("location: print.php?id=" . $_GET['id']);

exit;