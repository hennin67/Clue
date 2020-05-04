<?php

namespace tests;

//use CardTest;
use \Clue\Card as Card;
use \Clue\Deck as Deck;
use Clue\Game;
use Clue\Player;

class CardTest extends \PHPUnit\Framework\TestCase
{

// test if decimal or integer

    public function testConstruct() {

        // test instance of card
        $enbody = new Card("enbody", false, "enbody");

        $this->assertInstanceOf("\Clue\Card", $enbody);
        $this->assertEquals("enbody", $enbody->getCardName());
    }

    public function testDeck(){

        $deck = new Deck();
        $this->assertInstanceOf("\Clue\Deck", $deck);

        $amt = 21;
        $this->assertEquals($amt, $deck->countDeck());
        $this->assertEquals(3, floor( (int) 22/ (int) 7));
    }

    public function  testDeal(){
        $players = [];
        $owen = new Player("owen");
        $onsay = new Player("onsay");
        $players[] = $owen;
        $players[] = $onsay;

        $game = new Game($players);
        $game->dealHands();

        $players = $game->getPlayers();
        foreach($players as $player) {
            $this->assertEquals(6, count($player->getHand()));
            $this->assertEquals(15, count($player->getOther()));
        }
    }

    public function testAliases() {
        $owen = new Player('owen');
        $onsay = new Player('onsay');
        $game = new Game(array($owen, $onsay));
        $game->dealHands();

        $players = $game->getPlayers();
        $owen = $players[0];
        $onsay = $players[1];

        $owenOther = $owen->getOther();
        $onsayOther = $onsay->getOther();
        for($i = 0; $i < 15; $i++) {
            $cardOwen = $owenOther[$i];
            foreach($onsayOther as $card) {
                if ($cardOwen->getCardName() === $card->getCardName()) {
                    $this->assertNotEquals($cardOwen->getAlias(), $card->getAlias());
                }
            }
        }
    }

    public function testRemoveCard() {
        $deck = new Deck();

        $cards = $deck->getCards();
        $toRemove = $cards[0];

        $this->assertEquals(21, count($cards));

        $deck->removeCard($toRemove);

        $cards2 = $deck->getCards();

        $this->assertEquals(20, count($cards2));
    }


}
