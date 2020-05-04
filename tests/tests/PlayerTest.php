<?php

use \Clue\Player as Player;
use \Clue\Node as Node;
use \Clue\Game as Game;
use \Clue\Board as Board;

class PlayerTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct() {
        $enbody = new Player("enbody");
        $mccullan = new Player("mccullen");
        $owen = new Player("owen");
        $day = new Player("day");
        $plum = new Player("plum");
        $onsay = new Player("onsay");

        $this->assertInstanceOf("\Clue\Player", $enbody);

        $this->assertEquals($enbody::ENBODY, $enbody->getCharacter());
        $this->assertEquals($enbody::OWEN, $owen->getCharacter());
        $this->assertEquals($enbody::ONSAY, $onsay->getCharacter());
        $this->assertEquals($enbody::PLUM, $plum->getCharacter());
        $this->assertEquals($enbody::DAY, $day->getCharacter());
        $this->assertEquals($enbody::MCCULLEN, $mccullan->getCharacter());
    }

    public function testSetNode()
    {
        $player = new Player("enbody");
        $game = new Game([$player]);
        $board = new Board($game);
        $game->setBoard($board);

        $index = new Node(3, 4);
        $player->setNode($index);
        $this->assertEquals(array(3, 4), $player->getNode()->getPosition());

        $index = new Node(26, 25);
        $player->setNode($index);
        $this->assertEquals(array(0, 0), $player->getNode()->getPosition());
    }

    public function testInRoom()
    {
        $enbody = new Player("enbody");
        $game = new Game([$enbody]);
        $board = new Board($game);
        $game->setBoard($board);
        $board = $game->getBoard();

        $enbody->setNode($board->getNode(24, 7));
        $this->assertEquals(false, $enbody->getInRoom());

        $enbody->setNode($board->getNode(3, 3));
        $this->assertEquals(false, $enbody->getInRoom());

        $enbody->setNode($board->getNode(3, 5));
        $this->assertEquals(false, $enbody->getInRoom());
    }
}