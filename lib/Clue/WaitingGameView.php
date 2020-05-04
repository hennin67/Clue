<?php


namespace Clue;


class WaitingGameView
{
    public function __construct($site, $gameID) {
        $this->site = $site;
        $this->gameID = $gameID;
    }

    public function present() {
        $html = "<h1>Players</h1><input type='hidden' id='gameid' name='gameid' value=$this->gameID>";

        $users = new Users($this->site);
        $playing = $users->getUsersPlaying($this->gameID);
        foreach ($playing as $player) {
            $html .= "<p>$player</p>";
        }

        $html .= "<p><button type=\"submit\" id='exit' name=\"exit\" style=\"margin:5px\">Exit Game</button>";
        if (count($playing) >= 2) {
            $html .= "<input type=\"submit\" id='start' value=\"Start Game\" style=\"margin:5px\"></p>";
        } else {
            $html .= "</p>";
        }

        return $html;

    }

    private $site;
    private $gameID;

}