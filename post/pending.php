<?php
require '../lib/clue.inc.php';

// This file will handle loading the game state from the db

$gameid = -1;
if (isset($_GET['id'])){
    $gameid = $_GET['id'];
}

$games = new \Clue\GameTable($site);
$game = $games->loadGame($gameid);

$currPlayer = $game->getCurrentPlayer();
if ($currPlayer->getNode()->isInRoom()) {
    $game->getCurrentPlayer()->setInRoom(true);
    $game->setState(1);
}

$game->setRoomState();

$_SESSION[CLUE_SESSION] = $game;
$_SESSION[BOARD_SESSION] = $game->getBoard();

header("location: ../gameboard.php?id=" . $game->getId());
