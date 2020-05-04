<?php

use \Clue\Game as Game;
use \Clue\Board as Board;

class GameTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct() {
        $enbody = new \Clue\Player("enbody");
        $mccullan = new \Clue\Player("mccullan");

        $game = new Game([$enbody, $mccullan]);
        $board = new Board($game);
        $game->setBoard($board);

        $this->assertInstanceOf("\Clue\Game", $game);

        $this->assertEquals(2, sizeof($game->getPlayers()));

        $this->assertEquals($game->getCurrentPlayer(), $enbody);
        $game->cycleTurn();
        $this->assertEquals($game->getCurrentPlayer(), $mccullan);
        $game->cycleTurn();
        $this->assertEquals($game->getCurrentPlayer(), $enbody);
    }

    public function testSetCurrPlayer() {
        $player1 = new \Clue\Player(\Clue\Player::ONSAY);
        $player2 = new \Clue\Player(\Clue\Player::OWEN);
        $player1->setUserId(7);
        $player2->setUserId(8);

        $game = new Game(array($player1, $player2));
        $board = new Board($game);
        $game->dealHands();
        $board->setPlayerInitialPositions();

        $this->assertEquals($player1->getUserId(), $game->getCurrentPlayer()->getUserId());

        $game->setCurrentPlayer($player2->getUserId());

        $this->assertEquals($player2->getUserId(), $game->getCurrentPlayer()->getUserId());


    }


}