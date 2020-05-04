<?php
require 'lib/clue.inc.php';
if ($user === null) header("location: ./login.php");
$games = new Clue\GameTable($site);
$games->DeleteEmptyGames();
$rows = $games->getAllPendingGames();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Waiting Room</title>
    <link href="lib/game.css" type="text/css" rel="stylesheet">
</head>

<body id="welcome">
<header>
    <h1> Welcome to Waiting Room Area</h1>
</header>
<h1> Join a Game </h1>
<table>
    <tr>
        <th>Game Host</th>
        <th>Select this game</th>
    </tr>
    <?php
        foreach ($rows as $row){
            $game_name = $row["id"];
            echo "<tr><td>$game_name</td> <td><form method=\"post\" action=\"post/waitingRoom.php\"><p>
        <input type='hidden' id='gameid' name='gameid' value='$game_name'>
        <input type=\"submit\" value=\"Join\"></p></form></td></tr>";
        }
        ?>
</table>
<form action="post/waitingRoom.php" method="POST">
    <p><input type="submit" name="submitbutton" value="Create A New Game"></p>

<div class="instrcution">
    <p><a href="instruction.php">Game Instruction</a></p>
    <p><a href="login.php">Log Out</a></p>
</div>


</body>
