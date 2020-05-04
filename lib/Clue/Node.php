<?php


namespace Clue;


/**
 * A node in the game graph
 */
class Node {

    /**
     * Node constructor.
     * @param $row int the row this node is located in (starting at 0)
     * @param $col int the column this node is located in (starting at 0)
     */
    public function __construct($row, $col){
        $this->row = $row;
        $this->col = $col;
    }

    public function getNeighbors(){
        return $this->to;
    }

    /**
     * @return array (row, column)
     */
    public function getPosition() {
        return array($this->row, $this->col);
    }

    /**
     * Add a neighboring node
     * @param Node $to Node we can step into
     */
    public function addTo($to) {
        $this->to[] = $to;
    }

    public function searchReachable($distance) {
        // The path is done if it at the end of the distance
        // TODO: figure out how to integrate entering rooms into this algorithm.
        if($distance === 0) {
            $this->reachable = true;
            return;
        }

        $this->onPath = true;

        if ($this->roomConnection) {
            $this->room->setReachable(true);
        }

        foreach($this->to as $to) {
            if(!$to->blocked && !$to->onPath && !$to->getOccupier()) {
                $to->searchReachable($distance-1);
            }
        }

        $this->onPath = false;
    }

    public function setOccupier($occupier) {
        $this->occupier = $occupier;
    }

    public function getOccupier() {
        return $this->occupier;
    }

    public function setReachable($reachable) {
        if (!$this->occupier) {
            $this->reachable = $reachable;
        }
    }

    public function getReachable(){
        return $this->reachable;
    }
    public function getTo() {
        return $this->to;
    }
    public function setBlocked(){
        $this->blocked = true;
    }

    /**
     * Get the Room object this node is the entrance tile for
     * @return Room the room this node is connected to.
     */
    public function getRoom() {
        return $this->room;
    }

    /**
     * Set the room that this node is the entrance tile for
     * @param $room Room the Room this node is the entrance to
     */
    public function setRoomConnection($room) {
        $this->room = $room;
        $this->roomConnection = true;
    }

    /**
     * Set that this Node is in a room
     * @param $room Room the room this Node is inside of
     */
    public function setInRoom($room) {
        $this->inRoom = true;
        $this->room = $room;
    }

    /**
     * @return bool whether or not this node is the entrance to a room
     */
    public function isRoomConnected() {
        return $this->roomConnection;
    }

    /**
     * @return bool whether or not this node is in a room
     */
    public function isInRoom() {
        return $this->inRoom;
    }


    // Pointers to adjacent nodes
    private $to = [];

    /**
     * @var Room the room this Node is connected to / inside of
     */
    private $room;

    // Whether or not this node is the entrance tile to a room
    private $roomConnection = false;

    // This node is blocked and cannot be visited
    private $blocked = false;

    // This node is on a current path
    private $onPath = false;

    // Node is reachable in the current move
    private $reachable = false;

    //check if there is already a player
    private $occupier = false;

    // Whether or not this node is IN a room
    private $inRoom = false;

    private $row = -1;
    private $col = -1;
}