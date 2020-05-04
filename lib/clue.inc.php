<?php
require __DIR__ . "/../vendor/autoload.php";

$site = new Clue\Site();
$localize = require 'localize.inc.php';
if(is_callable($localize)) {
    $localize($site);
}

// Start the PHP session system
session_start();

define("CLUE_SESSION", 'game');
define("BOARD_SESSION", 'board');
define("PUSH_KEY_PREFIX", "cse477wabaningo");

if (isset($_SESSION[CLUE_SESSION])) {
    $game = $_SESSION[CLUE_SESSION];
    $board = $_SESSION[BOARD_SESSION];
}

$user = null;
if(isset($_SESSION[Clue\User::SESSION_NAME])) {
    $user = $_SESSION[Clue\User::SESSION_NAME];
}

$gameInstance = null;
if(isset($_SESSION[Clue\Game::SESSION_NAME])) {
    $gameInstance = $_SESSION[Clue\Game::SESSION_NAME];
}

// redirect if user is not logged in
if((!isset($open) || !$open) && $user === null && !isset($_GET['x'])) {
    header("location: login.php");
    exit;
}

// redirect if user is in game
//if($gameInstance !== null) {
//    header("location: waitingGame.php");
//    exit;
//}