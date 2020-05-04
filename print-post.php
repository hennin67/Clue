<?php
require __DIR__ . '/lib/clue.inc.php';
$players = [];
# $num = sizeof($state->getPlayers());
$num = $game->countPlayers();
//$i = 1;
//
//$name = $game->getPlayers();
//$new = $name[1]->getCharacter();
$pos = $game->getCurrentPos();

echo "<p>$pos</p>";
if ($pos < $num -1){
    $game->cycleTurn();
    header("location: print.php?id=" . $_GET['id']);
}
else{
    header("location: pending.php?id=" . $_GET['id']);
}

exit;