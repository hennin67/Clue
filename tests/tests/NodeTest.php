<?php

use \Clue\Node as Node;

class NodeTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct(){
        $node = new Node(0,0);

        $node1 = new Node(1,1);
        $node2 = new Node(2,2);
        $node3 = new Node(3,3);

        $node->addTo($node1);
        $this->assertContains($node1, $node->getNeighbors());
        $node->addTo($node2);
        $this->assertContains($node1, $node->getNeighbors());
        $this->assertContains($node2, $node->getNeighbors());
        $node->addTo($node3);
        $this->assertContains($node1, $node->getNeighbors());
        $this->assertContains($node2, $node->getNeighbors());
        $this->assertContains($node3, $node->getNeighbors());

        $this->assertEquals($node->getPosition()[0], 0);
        $this->assertEquals($node->getPosition()[1], 0);
        $this->assertEquals($node1->getPosition()[0], 1);
        $this->assertEquals($node1->getPosition()[1], 1);



    }

    public function testRoomConnections() {
        $day = new \Clue\Player("day");
        $onsay = new \Clue\Player("onsay");

        $game = new \Clue\Game(array($day, $onsay));
        $board = new \Clue\Board($game);

        $rooms = $board->getRooms();

        $intl = $rooms['international_center'];
        $connections = $intl->getEntryNodes();
        $this->assertEquals($board->getNode(7,4), $connections[0]);

        $breslin = $rooms['breslin_center'];
        $connections = $breslin->getEntryNodes();
        $this->assertEquals($board->getNode(5,7), $connections[0]);
        $this->assertEquals($board->getNode(5,16), $connections[1]);
        $this->assertEquals($board->getNode(8,9), $connections[2]);
        $this->assertEquals($board->getNode(8,14), $connections[3]);

        $beaumont = $rooms['beaumont_tower'];
        $connections = $beaumont->getEntryNodes();
        $this->assertEquals($board->getNode(6,18), $connections[0]);

        $union = $rooms['union'];
        $connections = $union->getEntryNodes();
        $this->assertEquals($board->getNode(12,8), $connections[0]);
        $this->assertEquals($board->getNode(16,6), $connections[1]);

        $art = $rooms['art_museum'];
        $connections = $art->getEntryNodes();
        $this->assertEquals($board->getNode(9,17), $connections[0]);
        $this->assertEquals($board->getNode(13,22), $connections[1]);

        $lib = $rooms['library'];
        $connections = $lib->getEntryNodes();
        $this->assertEquals($board->getNode(13,20), $connections[0]);
        $this->assertEquals($board->getNode(16,16), $connections[1]);

        $wharton = $rooms['wharton_center'];
        $connections = $wharton->getEntryNodes();
        $this->assertEquals($board->getNode(18,6), $connections[0]);

        $stadium = $rooms['spartan_stadium'];
        $connections = $stadium->getEntryNodes();
        $this->assertEquals($board->getNode(17,11), $connections[0]);
        $this->assertEquals($board->getNode(17,12), $connections[1]);

        $engineer = $rooms['engineering_building'];
        $connections = $engineer->getEntryNodes();
        $this->assertEquals($board->getNode(20,17), $connections[0]);

        $this->assertEquals($engineer->getConnectedRoom(), $intl);
        $this->assertEquals($intl->getConnectedRoom(), $engineer);
        $this->assertEquals($beaumont->getConnectedRoom(), $wharton);
        $this->assertEquals($wharton->getConnectedRoom(), $beaumont);
    }

}