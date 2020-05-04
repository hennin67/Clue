<?php
require '../lib/clue.inc.php';
if (isset($_POST['gameid'])) {
    $gameid = $_POST['gameid'];
}
$users = new \Clue\Users($site);
$users->saveGameID($gameid, $user);
$games = new Clue\GameTable($site);

if (isset($_POST['submitbutton'])){
    $gameid = $games ->CreateNewGame();
    $users->saveGameID($gameid, $user);
    header("location: ../waitingGame.php?id=$gameid");
} else if (count($users->getUsersPlaying($gameid)) == 6) {
    $used = $users->randomlySetPlayers($gameid);
    $str = "../index-post.php?id=" . $gameid;
    foreach ($used as $character) {
        $str .= "&$character=1";
    }
    header("location: $str");
} else {
    $rows = $games->getAllPendingGames();
    $objs = [];
    foreach ($rows as $row){
        $objs[] = $row["id"];
    }
    if (in_array($gameid,$objs)) {
        header("location: ../waitingGame.php?id=$gameid");
    } else {
        phpinfo();
        header("location: ../pending.php?id=$gameid");
    }
}
