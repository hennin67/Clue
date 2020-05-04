<?php


namespace Clue;


class Hand
{
    private $hand = array();

    /**
     * Hand constructor.
     * @param $cards Card[] the cards this hand consists of
     */
    public function __construct($cards)
    {
        $this->hand = $cards;
    }

    /**
     * @return Card[] list of cards found in this hand
     */
    public function getHand(){
        return $this->hand;
    }

    public function addToHand(Card $card){
        array_push($hand, $card);
    }

    //empty array & call Hand in Board
}