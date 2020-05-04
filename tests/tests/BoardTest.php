<?php

use \Clue\Board as Board;
use \Clue\Game as Game;
use \Clue\Player as Player;
use \Clue\Room as Room;

class BoardTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $plum = new Player("plum");
        $day = new Player("day");
        $owen = new Player("owen");

        $game = new Game([$plum, $day, $owen]);

        $board = new Board($game);

        $this->assertInstanceOf("\Clue\Board", $board);
    }

    public function testConnect()
    {
        $plum = new Player("plum");
        $day = new Player("day");
        $game = new Game([$plum, $day]);
        $board = new Board($game);
        $this->assertEquals(25, count($board->getboard()));
    }

    public function test_GettersSetters(){
        $plum = new Player("plum");
        $day = new Player("day");
        $game = new Game([$plum, $day]);
        $board = new Board($game);

        $board->roll(3,4);
        $this->assertEquals(array(3,4), $board->getRoll());
    }

}