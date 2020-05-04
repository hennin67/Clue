<?php


namespace Clue;



class Room
{

    /**
     * Room constructor.
     * @param $roomName string the name of the room this object represents
     * @param $nodes Node[] the Nodes that make up this room
     */
    public function __construct($roomName, $nodes)
    {
        foreach($nodes as $node) {
            $node->setInRoom($this);
        }
        $this->nodes = $nodes;
        $this->roomName = $roomName;
    }

    /**
     * @return string the name of this room
     */
    public function getRoomName() {
        return $this->roomName;
    }

    /**
     * Add an entry Node for this room
     * @param $node Node the Node to add
     */
    public function addEntryNode($node) {
        $this->entryNodes[] = $node;
        $node->setRoomConnection($this);
    }

    /**
     * Add a Room this Room is connected to via a secret passage
     * @param $room Room a Room that this Room is connected to
     */
    public function addRoomConnection($room) {
        $this->connectedRoom = $room;
        $this->isRoomConnected = true;
    }

    /**
     * Set if this room is reachable in the current move
     * @param $reachable bool whether or not this room is reachable
     */
    public function setReachable($reachable) {
        $this->reachable = $reachable;
        foreach($this->nodes as $node) {
            $node->setReachable($reachable);
        }
    }

    /**
     * Set this room's entry/exit tiles reachable
     * @param $exit bool whether or not to set the entry/exit tiles for this room to reachable
     */
    public function setExitTiles($exit)
    {
        foreach ($this->entryNodes as $node) {
            $node->setReachable($exit);
        }
    }

    public function setAdjacentRoom($reachable) {
        if ($this->isRoomConnected) {
            $this->connectedRoom->setReachable($reachable);
        }
    }

    public function getEntryNodes() {
        return $this->entryNodes;
    }

    public function getConnectedRoom() {
        return $this->connectedRoom;
    }

    // The nodes that this Room consists of
    private $nodes;

    /**
     * @var Node[] nodes that this room can be accessed from.
     */
    private $entryNodes;

    /**
     * @var Room the room this room is connected to via a secret passage
     */
    private $connectedRoom;

    /**
     * @var bool whether or not this room is connected to another one via a secret passage
     */
    private $isRoomConnected = false;

    // The name of this room
    private $roomName = "";

    // Whether or not this room is reachable
    private $reachable = false;
}