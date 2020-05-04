<?php
/**
 * Created by PhpStorm.
 * User: ellachen
 * Date: 2020-02-21
 * Time: 16:38
 */
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Instructions</title>
    <link href="lib/game.css" type="text/css" rel="stylesheet">
</head>

<body id="instruction">
    <p>The beginning screen is for selecting characters for the game. Pick the same number of characters as people that are currently playing
    the game.</p>

    <p>There are 21 cards: 6 suspect cards, 6 weapon cards, and 9 location cards. The game selects one of each as the murderer, weapon, and location.The remaining cards are dealt to the players, with a maximum of 6 cards per player.
        The next page will allow each player to print their cards for the game. After printing, you may press next to move on to the next player's cards.</p>

    <p>After all players have printed their cards, the game board is shown and a random player is selected to go first.</p>

    <p>A turn begins by rolling the two dice in the center of the screen. This number is the EXACT number of movements that your character must make.
    The only special case is that an exact number of moves is not required to move into a room. The game will indicate all possible moves with green squares.
    If a player has not entered a room, the turn will go around between players until someone enters a room.</p>

    <p>Once a player enters a room, they will have 3 options: Pass(which ends their turn), Suggest or Accuse. Suggest and Accuse are basically the same move,
    except they have different outcomes. When either is selected, the suspect is moved into the room. Then the player must choose a weapon from the six possible options.
    The game will then give a special keyword that tells the player that the crime has not taken place in the room that they were in. Accusations work
    the same way, except if you make an incorrect accusation, then you will only be able to make suggestions for the rest of the game, which effectively means that
    player has lost.</p>

    <p>Click <a href="https://facweb.cse.msu.edu/ghassem3/cse477/project1/game-play.php">here</a> for a more detailed description of the game.</p>
    <p><a href="waitingRoom.php">Click here to return to the beginning game page.</a></p>

</body>
</html>
