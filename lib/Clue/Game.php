<?php


namespace Clue;

class Game
{
    const SESSION_NAME = 'game';
    const NUM_ROWS = 25;
    const NUM_COLS = 24;
    const NUM_CARDS = 21;

    /**
     * Game constructor.
     * @param $players array the array of Player objects that will be playing in the game
     */
    public function __construct($players)
    {
        $this->players = $players;
        foreach ($players as $player) {
            $player->setGame($this);
        }
        $this->currPlayer = $players[0];
        $this->deck = new Deck();
        $this->specialDeck = $this->deck->getSpecialCards();
        $this->words = Words::WORDS;

        //$this->dealHands();
    }

    public function dealHands(){
        $numCards = -1;
        $numPlayers = $this->countPlayers();

        if ($numPlayers <= 3) {
            $numCards = 6;
        } else if ($numPlayers === 4) {
            $numCards = 4;
        } else if ($numPlayers >= 5) {
            $numCards = 3;
        }

        // Set the deck for each player
        foreach($this->players as $player) {
            $player->setGameDeckCards($this->deck->getDuplicateCardCopy());
        }

        // Remove the Solution hand from the deck
        foreach($this->specialDeck as $item) {
            $this->deck->removeCard($item);
        }

        // Assign Hands to each player
        $cardIndex = 0;
        $remainingCards = $this->deck->getCards();
        for($i = 0; $i < $numPlayers; $i++) {

            $player = $this->players[$i];
            $playerCards = [];
            for($j = 0; $j < $numCards; $j++) {
                $playerCards[] = $remainingCards[$cardIndex];
                $cardIndex++;
            }

            $hand = new Hand($playerCards);
            $player->setHand($hand);
        }

    }

    /**
     * Get this game's players
     * @return Player[] the players in this game
     */
    public function getPlayers() {
        return $this->players;
    }

    /**
     * Cycles the turn from one player to the next
     */
    public function cycleTurn() {
        $this->dice = array($this->board->rollNum(), $this->board->rollNum());
        $totalPlayers = sizeof($this->players);
        $this->currPlayerPos = ($this->currPlayerPos + 1) % $totalPlayers;
        $this->currPlayer = $this->players[$this->currPlayerPos];
        $this->action = null;
        $this->state = 0;
        $this->guess = array();
    }

    public function cyclePlayers($count) {
        $totalPlayers = sizeof($this->players);
        return $this->players[($this->currPlayerPos + $count) % $totalPlayers];
    }

    /**
     * Gets the current player whose turn it is
     * @return Player the current player whose turn it is
     */
    public function getCurrentPlayer() {
        return $this->currPlayer;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getState() {
        return $this->state;
    }

    public function setAction($action) {
        $this->action = $action;
    }

    public function getAction() {
        return $this->action;
    }

    //count number of players in game
    public function countPlayers(){
       return count($this->players);
    }

    public function getCurrentPos(){

        return $this->currPlayerPos;
    }

    /**
     * @return Board the board
     */
    public function getBoard() {
        return $this->board;
    }

    /**
     * @param $board Board the board
     */
    public function setBoard($board) {
        $this->board = $board;
    }

    public function addToGuess($val) {
        array_push($this->guess, $val);
    }

    public function getGuess() {
        return $this->guess;
    }

    public function setCurrentPlayer($id) {
        $count = 0;
        foreach($this->players as $player) {
            if ($player->getUserId() === $id) {
                $this->currPlayer = $player;
                break;
            }
            $count++;
        }
        $this->currPlayerPos = $count;
    }

    /**
     * Based on the current Node of the current Player, set the state appropriately.
     */
    public function setRoomState() {
        $currNode = $this->currPlayer->getNode();
        if ($currNode->isInRoom()) {
            $room = $currNode->getRoom();
            $room->setAdjacentRoom(true);
            $room->setExitTiles(true);
        }
    }

    public function resetRoomState() {
        foreach($this->board->getRooms() as $room) {
            $room->setExitTiles(false);
            $room->setAdjacentRoom(false);
        }
    }

    public function newGame(){
        $this->players = [];
    }

    public function setDice($dice) {
        $this->dice = $dice;
    }

    public function getDice() {
        return $this->dice;
    }

    public function getDeck() {
        return $this->specialDeck;
    }

    /**
     * @param $solution Card[] the solution to this game
     */
    public function setSolution($solution) {
        $this->specialDeck = $solution;
    }

    /**
     * Get a random alias name out of the avaliable words,
     * and remove it (so one alias can't be used twice)
     *
     * @return string a random string alias for a card
     */
    public function getRandomAlias() {
        shuffle($this->words);
        $alias = $this->words[0];
        unset($this->words[0]);
        $this->words = array_values($this->words);
        return $alias;
    }

    /**
     * @param $id int the id in the Games table for this game
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int the id in the Games table for this game
     */
    public function getId() {
        return $this->id;
    }


    //// MEMBER VARIABLES ////

    /**
     * @var Player[] All of the players in the game
     */
    private $players = [];

    /**
     * @var Player The current player whose turn it is
     */
    private $currPlayer;
    /// the position in the players array of the current player
    private $currPlayerPos = 0;

    /**
     * @var Board The game's board
     */
    private $board;

    /// The solution for this game
    private $solutionHand;

    // The current state of the game (dice, action, who, with what)
    private $state = 0;
    // accuse or suggest
    private $action = null;

    /**
     * @var Deck the deck object for this game
     */
    private $deck;

    /**
     * @var Card[] array of solution cards
     */
    private $specialDeck;

    private $guess = array();

    /**
     * @var string[] the list of avaliable words to be used as aliases
     */
    private $words;

    private $dice;

    private $id;
}