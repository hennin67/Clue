<?php

namespace Clue;

use Murder\Words;

class Player
{
    const OWEN = "Owen";
    const MCCULLEN = "McCullen";
    const ONSAY = "Onsay";
    const ENBODY = "Enbody";
    const PLUM = "Plum";
    const DAY = "Day";

    public function __construct($character)
    {
        $lower = strtolower($character);
        if ($lower === "owen"){
            $this->character = self::OWEN;
        } else if ($lower === "mccullen"){
            $this->character = self::MCCULLEN;
        } else if ($lower === "onsay"){
            $this->character = self::ONSAY;
        } else if ($lower === "enbody") {
            $this->character = self::ENBODY;
        } else if ($lower === "plum"){
            $this->character = self::PLUM;
        } else if ($lower === "day"){
            $this->character = self::DAY;
        }
    }

    /*
     * @param $game Game game
     */
    public function setGame($game) {
        $this->game = $game;
    }

    /**
     * Get the node that this player is on
     * @return Node the current node this player is on
     */
    public function getNode() {
        return $this->currNode;
    }

    /**
     * Set this player's current node
     * @param $node Node the node to set this player on
     */
    public function setNode($node) {
        $node->setOccupier(false);
        if ($node->getPosition()[0] <= 25 and $node->getPosition()[1] <= 25) {
            $this->currNode = $node;
        }
        else{
            $this->currNode = new Node(0, 0);
        }
        $this->currNode->setOccupier(true);
    }

    /**
     * @return string the character
     */
    public function getCharacter() {
        return $this->character;
    }

    /**
     * @return Card[] the Hand
     */
    public function getHand() {
        return $this->hand;
    }

    //get other cards (that current player is not holding)
    public function getOther() {
        return $this->other;
    }

    public function getInRoom () {
        return $this->inRoom;
    }

    public function setInRoom($bool) {
        $this->inRoom = $bool;
    }

    /**
     * Set this player's Hand, as well as set this players $others with unique aliases
     * @param Hand the Hand to set
     */
    public function setHand($hand)
    {
        $this->hand = $hand->getHand();

        // Set other based on what the original deck has
        foreach($this->deckCards as $card){
            if (!$this->handSearch($card)) {
                $card->setAlias($this->game->getRandomAlias());
                $this->other[] = $card;
            }
        }
    }

    /**
     * If $needle is in this Player's hand, return true
     * @param Card the thing to try & find in Hand
     * @return bool True if this player's hand contains $needle
     */
    private function handSearch($needle) {
        foreach($this->hand as $card) {
            if ($card->getCardName() === $needle->getCardName()) {
                return true;
            }
        }

        return false;
    }
    public function handSearchPartial($needle)
    {
        foreach ($this->hand as $card) {
            if (explode(" ", $card->getCardName())[0] === $needle) {
                return $card;
            }
        }

        return false;
    }



    public function hasLost() {
        return $this->lost;
    }

    public function setLost() {
        $this->lost = true;
    }

    /**
     * Set the game's Deck containing all the cards
     * @param $cards Card[] the Deck
     */
    public function setGameDeckCards($cards) {
        $this->deckCards = $cards;
    }

    public function getDeckAlias($card) {
        /*$key = array_search($card, $this->other);
        return $this->other[$key]->getAlias();*/
        foreach($this->other as $other){
            if($card->getCardName() == $other->getCardName()){
                return $other->getAlias();
            }
        }
        return "Silence";
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param $others Card[] the cards to set as this player's other cards
     */
    public function setOtherCards($cards) {
        $this->other = $cards;
    }

    /**
     * @param $cards Card[] the cards to set this player's hand cards
     */
    public function setHandCards($cards) {
        $this->hand = $cards;
    }

    /**
     * @param $id int the id to set
     */
    public function setUserId($id) {
        $this->userId = $id;
    }

    /**
     * @var string the name of thic character
     */
    private $character = "";

    /**
     * @var Game the Game this Player is playing in
     */
    private $game;

    /**
     * @var Node the current Node this character is located on
     */
    private $currNode;

    /**
     * @var Card[] this player's held hand of cards
     */
    private $hand;

    /**
     * @var Card[] the cards this player is not holding
     */
    private $other;

    /**
     * @var bool Whether or not this player has lost the game and cannot make another accusation
     */
    private $lost = false;

    /**
     * @var Card[] All the cards in the game
     */
    private $deckCards;

    /**
     * @var bool whether or not this player is located in a room
     */
    private $inRoom;

    /**
     * @var int the user Id that is using this player
     */
    private $userId;


}