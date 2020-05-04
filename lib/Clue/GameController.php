<?php


namespace Clue;


class GameController
{
    /**
     * GameController constructor.
     * @param $game Game $game
     * @param $post array the post array
     * @param $site Site the site object
     */
    public function __construct($game, $post, $site)
    {
        $this->site = $site;
        $this->game = $game;

        // TODO: redirect to gameboard.php if the node is in a room
        $this->page = "pending.php?id=" . $game->getId();

        // Analyze the $post array
        if(isset($post['clear'])) {
            $this->reset = true;
            $this->game->newGame();
        }

        // If a node was clicked
        // THIS WORKS
        else if(isset($post['node'])){
            $position = [];
            $position = explode(",", $post['node']);
            $x = +substr($position[0], 1);
            $y = +substr($position[1], 0, -1);

            $this->position = array($x, $y);

            // Moving the player
            $player = $this->game->getCurrentPlayer();
            $node = $player->getNode();
            $node->setOccupier(false);
            $player->setNode($this->game->getBoard()->getNode($x, $y));
            $player->setInRoom($this->game->getBoard()->getNode($x, $y)->isInRoom());

            $this->setState(1);
        }
    }

    public function backToGameboard() {
        return $this->backToGameboard;
    }

    public function onPass() {
        $this->game->cycleTurn();
    }

    public function setAction($action) {
        $this->game->setAction($action);
    }

    public function setState($state) {
        $this->state = $state;
        // if player didn't make it into a room, go to next player
        if ($state == 1 and !$this->game->getCurrentPlayer()->getInRoom()) {
            $this->game->cycleTurn();
        } else {
            $this->game->setState($state);
            $this->page = "gameboard.php?id=" . $this->game->getId();
        }

    }

    public function getState() {
        return $this->state;
    }

    public function getPage() {
        return $this->page;
    }

    public function getPosition() {
        return $this->position;
    }

    public function isReset(){
        return $this->reset;
    }

    public function addToGuess($val) {
        $this->game->addToGuess($val);
    }

    public function getGame() {
        return $this->game;
    }

    private $backToGameboard = false;
    private $state;
    private $site;
    private $game;
    private $page; // the next page we're going to
    private $position = array(-1, -1); // (row, col) the position last clicked
    private $reset = false; // true if we need to reset the game
}