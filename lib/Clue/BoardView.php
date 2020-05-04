<?php


namespace Clue;


class BoardView
{
    /** Constructor
     * @param $game Game object */
    public function __construct($game) {
        $this->game = $game;
        $this->suspects = ["McCullen", "Owen", "Onsay", "Enbody", "Plum", "Day"];
        $this->weapons = ["Final Exam", "Midterm Exam", "Programming Assignment", "Project", "Written Assignment", "Quiz"];
    }

    public function present() {
        if (isset($_GET['sol'])) {
            $sol = $this->game->getDeck();
            echo "<h1>Solution:</h1>";
            foreach($sol as $card) {
                $name = $card->getCardName();
                echo "<p>$name</p>";
            }
        }
        echo "<p>" . $this->game->getState() . "</p>";
        $num1 = $this->game->getBoard()->rollNum();
        $num2 = $this->game->getBoard()->rollNum();

        $playerNode = $this->game->getCurrentPlayer()->getNode();
        $playerNode->searchReachable($num1 + $num2);



        $html = '<div class="game"><div class="board">';

        $players = $this->game->getPlayers();
        for ($row = 0; $row < 25; $row++) {
            $html .= '<div class="row">';
            for ($cell = 0; $cell < 24; $cell++) {
                $html .= '<div class="cell">';

                $node = $this->game->getBoard()->getNode($row, $cell);

                if ($node->getReachable()) {
                    $html .= "<form method=\"post\" action=\"game-post.php\"><button type=\"submit\" name=\"node\" value=\"($row,$cell)\"></form>";
                }

                foreach ($players as $player) {
                    $currNode = $player->getNode();
                    $position = $currNode->getPosition();
                    $name = $player->getCharacter();
                    $name = strtolower($name);
                    $imageName = "images/" . $name . "-piece.png";

                    if ($row === +$position[0] && $cell === +$position[1]) {
                        $html .= "<p><img src=\"$imageName\" style=\"width: 35.641px; height: 35.234px;\" alt=\"not showing\"> </p>";
                    }
                }

                $html .= '</div>';
            }
            $html .= '</div>';
        }

        $currPlayer = $this->game->getCurrentPlayer()->getCharacter();
        $html.= "<div class=\"play\"><h3>Player</h3><h3>$currPlayer</h3>";

        // Dice (if action == null)
        if ($this->game->getState()==0) {
            $this->game->getBoard()->roll($num1, $num2);

            $dice1 = "images/dice" . $num1 . ".png";
            $dice2 = "images/dice" . $num2 . ".png";

            $html .= <<<HTML
<div class="dice"><img class="dice1" ><img src="$dice1"><img src="$dice2"></div></div></div>
</div>
HTML;

        }

        // Choose your move (if state == 1)
        if ($this->game->getState()==1) {
            $html .= <<<HTML
<form method="post" action="move-post.php">
<p class="question">What do you wish to do?</p>
<p class="radio"><input type="radio" name="action" id="pass" value="pass"><label for="pass">Pass</label> </p>
<p class="radio"><input type="radio" name="action" id="suggest" value="suggest"><label for="suggest">Suggest</label> </p>
HTML;
            if (!$this->game->getCurrentPlayer()->hasLost()) {
                $html .= '<p class="radio"><input type="radio" name="action" id="accuse" value="accuse"><label for="accuse">Accuse</label></p>';
            }

            $html .= '<p class="submit"><input type="submit" name="roomOption" value="Go"></p></form></div>';
        }

        // Who did it (if state == 2)
        if ($this->game->getState()==2) {
            $html .= <<<HTML
<form method="post" action="move-post.php">
<p class="question">Who done it?</p>
HTML;
            foreach ($this->suspects as $player) {
                $html .= "<p class='radio'><input type='radio' name='suspect' id=$player value=$player><label for='pass'>$player</label> </p>";
            }
            $html .= '<p class="submit"><input type="submit" name="whoDoneIt" value="Go"></p></form></div>';

        }

        // With what (if state == 3)
        if ($this->game->getState()==3) {
            $html .= <<<HTML
<form method="post" action="move-post.php">
<p class="question">With what?</p>
HTML;
            foreach ($this->weapons as $weapon) {
                $html .= "<p class='radio'><input type='radio' name='weapon' id=$weapon value=$weapon><label for='pass'>$weapon</label> </p>";
            }
            $html .= '<p class="submit"><input type="submit" name="withWhat" value="Go"></p></form></div>';

        }

        // Completed accusation
        if ($this->game->getState()==4 and $this->game->getAction()=="accuse") {
            $html .= '<form method="post" action="move-post.php">';

            $accusation = $this->game->getGuess();
            $player = $accusation[0];
            $weapon = $accusation[1];
            $room = $this->game->getCurrentPlayer()->getNode()->getRoom()->getRoomName();
            $room = ucwords(explode("_", $room)[0]);

            $solutionDeck = $this->game->getDeck();
            $solutionPlayer = $solutionDeck[0]->getCardName();
            $solutionWeapon = ucwords(explode(" ", $solutionDeck[1]->getCardName())[0]);
            $solutionRoom = explode(" ", $solutionDeck[2]->getCardName())[0];

            if ($player==$solutionPlayer and $weapon==$solutionWeapon and $room==$solutionRoom) {
                $html .= "<br><h3>WINS</h3>";
                $html .= '</form></div>';
            } else {
                $this->game->getCurrentPlayer()->setLost();
                $html .= "<br><h3>LOSES</h3>";
                $html .= '<br><p class="submit"><input type="submit" name="withWhat" value="Go"></p></form></div>';
            }
        }

        // Completed Suggestion
        if ($this->game->getState()==4 and $this->game->getAction()=="suggest") {
            $suggestion = $this->game->getGuess();
            $player = $suggestion[0];
            $weapon = $suggestion[1];
            $room = $this->game->getCurrentPlayer()->getNode()->getRoom()->getRoomName();
            $room = ucwords(explode("_", $room)[0]);
            $suggestionArray = [$player, $weapon, $room];

            $html .= <<<HTML
<form method="post" action="move-post.php">
HTML;

            $break = false;
            $count = 1;

            while (true){
                $playerCheck = $this->game->cyclePlayers($count);
                $count += 1;

                // break if card found or if you reach current player
                if ($break or $playerCheck->getCharacter()==$currPlayer) break;

                foreach ($suggestionArray as $value) {
                    $card = $playerCheck->handSearchPartial($value);
                    if ($card) {
                        $html .= '<p class="question">Word on the street is:</p><br>';
                        //$alias = $card->getAlias();
                        $alias = $this->game->getCurrentPlayer()->getDeckAlias($card);
                        $html .= "<h3>$alias</h3><br>";
                        $break = true;
                        break;
                    }
                }
            }

            if (!$break) {
                $html .= '<p class="question">The street is silent...</p><br>';
            }

            $html .= <<<HTML
<p class="submit"><input type="submit" name="go" value="Go"></p>
</form></div>
HTML;
        }

        $this->game->getBoard()->resetReachable();

        $html .= '<form method="post" action="game-post.php"><div class = "newGame"><input type = "submit" name = "clear" value = "New Game"></form></div>';
        return $html;
    }

    private $game; // Clue object

    private $suspects;
    private $weapons;
}