<?php


namespace Clue;


class Board
{

    const NUM_ROWS = 25;
    const NUM_COLS = 24;

    /**
     * board constructor.
     * @param $game Game
     * @param $seed seed for dice rolls
     */
    public function __construct($game, $seed = null){
        if($seed === null){
            $seed = time();
        }
        srand($seed);

        $this->game = $game;
        $this->board = array();
        for($i = 0; $i < self::NUM_ROWS; $i++) {
            $row = array();
            for($j = 0; $j < self::NUM_COLS; $j++) {
                $column = new Node($i, $j);
                $row[] = $column;
            }
            $this->board[] = $row;
        }

        foreach($game->getPlayers() as $player) {
            $player->setGame($game);
        }

        $game->setDice(array($this->rollNum(), $this->rollNum()));


        $this->connect();
        $this->constructRooms();
        //set nodes outside of playing area as blocked
        //top row (without owen/mccullen positions)
        $this->getNode(0,0)->setBlocked();
        $this->getNode(0,1)->setBlocked();
        $this->getNode(0,2)->setBlocked();
        $this->getNode(0,3)->setBlocked();
        $this->getNode(0,4)->setBlocked();
        $this->getNode(0,5)->setBlocked();
        $this->getNode(0,6)->setBlocked();
        $this->getNode(0,7)->setBlocked();
        $this->getNode(0,8)->setBlocked();
        $this->getNode(0,10)->setBlocked();
        $this->getNode(0,11)->setBlocked();
        $this->getNode(0,12)->setBlocked();
        $this->getNode(0,13)->setBlocked();
        $this->getNode(0,15)->setBlocked();
        $this->getNode(0,16)->setBlocked();
        $this->getNode(0,17)->setBlocked();
        $this->getNode(0,18)->setBlocked();
        $this->getNode(0,19)->setBlocked();
        $this->getNode(0,20)->setBlocked();
        $this->getNode(0,21)->setBlocked();
        $this->getNode(0,22)->setBlocked();
        $this->getNode(0,23)->setBlocked();

        //left side (without onsay start and room nodes)
        $this->getNode(6,0)->setBlocked();
        $this->getNode(8,0)->setBlocked();
        $this->getNode(16,0)->setBlocked();
        $this->getNode(18,0)->setBlocked();

        //right side(without day and plum start and room nodes)
        $this->getNode(5,23)->setBlocked();
        $this->getNode(6,23)->setBlocked();
        $this->getNode(13,23)->setBlocked();
        $this->getNode(14,23)->setBlocked();
        $this->getNode(18,23)->setBlocked();
        $this->getNode(20,23)->setBlocked();

        //bottom side(without enbody start an room nodes)
        $this->getNode(24,6)->setBlocked();
        $this->getNode(24,8)->setBlocked();
        $this->getNode(24,15)->setBlocked();
        $this->getNode(24,17)->setBlocked();
    }

    public function rollNum(){
        return rand(1,6);
    }

    public function GetAvailableTiles($player){

    }

    public function setPlayerInitialPositions() {

        $players = $this->game->getPlayers();
        foreach($players as $player) {
            if ($player->getCharacter() == \Clue\Player::ONSAY) {
                $player->setNode($this->getNode(17, 0));
            } else if ($player->getCharacter() == \Clue\Player::DAY){
                $player->setNode($this->getNode(7,23));
            } else if ($player->getCharacter() == \Clue\Player::PLUM) {
                $player->setNode($this->getNode(19,23));
            } else if ($player->getCharacter() == \Clue\Player::ENBODY) {
                $player->setNode($this->getNode(24, 7));
            } else if ($player->getCharacter() == \Clue\Player::MCCULLEN) {
                $player->setNode($this->getNode(0, 9));
            } else if ($player->getCharacter() == \Clue\Player::OWEN) {
                $player->setNode($this->getNode(0, 14));
            }
        }
    }

    /**
     * Get the most recent dice roll values
     * @return array (dice1, dice2)
     *
     */
    public function getRoll(){
        return $this->rolled;
    }

    public function getState(){
        return $this->state;
    }

    public function setPlayer($player){
        $this->player = $player;
    }

    public function roll($num1, $num2){
        $this->rolled = array($num1, $num2);
    }

    /**
     * Called after a player's turn is finished, this resets all the nodes on the board to not reachable.
     */
    public function resetReachable() {
        for($row = 0; $row < 25; $row++) {
            for ($col = 0; $col < 24; $col++) {
                $node = $this->board[$row][$col];
                $node->setReachable(false);
            }
        }
    }

    /**
     * Get the node as position [$row][$col]
     * @param $row int the row
     * @param $col int the column
     * @return Node the node at position [$row][$col]
     */
    public function getNode($row, $col) {
        return $this->board[$row][$col];
    }




    public function getboard() {
        return $this->board;
    }

    public function connect() {
        $board = $this->getboard();
        //mccullen
        $board[0][9]->addTo($board[1][9]);

        //owen
        $board[0][14]->addTo($board[1][14]);

        $board[1][7]->addTo($board[2][7]);
        $board[1][7]->addTo($board[1][8]);
        $board[1][8]->addTo($board[1][7]);
        $board[1][8]->addTo($board[1][9]);
        $board[1][9]->addTo($board[1][8]);
        $board[1][9]->addTo($board[0][9]);
        $board[1][14]->addTo($board[1][15]);
        $board[1][14]->addTo($board[0][14]);
        $board[1][15]->addTo($board[1][14]);
        $board[1][15]->addTo($board[1][16]);
        $board[1][16]->addTo($board[1][15]);
        $board[1][16]->addTo($board[2][16]);
        $board[2][6]->addTo($board[2][7]);
        $board[2][6]->addTo($board[3][6]);
        $board[2][7]->addTo($board[2][6]);
        $board[2][7]->addTo($board[3][7]);
        $board[2][16]->addTo($board[2][17]);
        $board[2][16]->addTo($board[3][16]);
        $board[2][17]->addTo($board[3][17]);
        $board[2][17]->addTo($board[2][16]);
        $board[3][6]->addTo($board[3][7]);
        $board[3][6]->addTo($board[2][6]);
        $board[3][6]->addTo($board[4][6]);
        $board[3][7]->addTo($board[3][6]);
        $board[3][7]->addTo($board[2][7]);
        $board[3][7]->addTo($board[4][7]);
        $board[3][16]->addTo($board[3][17]);
        $board[3][16]->addTo($board[4][16]);
        $board[3][16]->addTo($board[2][16]);
        $board[3][17]->addTo($board[3][16]);
        $board[3][17]->addTo($board[4][17]);
        $board[3][17]->addTo($board[2][17]);
        $board[4][6]->addTo($board[3][6]);
        $board[4][6]->addTo($board[4][7]);
        $board[4][6]->addTo($board[5][6]);
        $board[4][7]->addTo($board[3][7]);
        $board[4][7]->addTo($board[4][6]);
        $board[4][7]->addTo($board[5][7]);
        $board[4][16]->addTo($board[3][16]);
        $board[4][16]->addTo($board[4][17]);
        $board[4][16]->addTo($board[5][16]);
        $board[4][17]->addTo($board[3][17]);
        $board[4][17]->addTo($board[4][16]);
        $board[4][17]->addTo($board[5][17]);
        $board[5][6]->addTo($board[5][7]);
        $board[5][6]->addTo($board[4][6]);
        $board[5][6]->addTo($board[6][6]);
        $board[5][7]->addTo($board[4][7]);
        $board[5][7]->addTo($board[6][7]);
        $board[5][7]->addTo($board[5][6]);
        $board[5][16]->addTo($board[4][16]);
        $board[5][16]->addTo($board[5][17]);
        $board[5][16]->addTo($board[6][16]);
        $board[5][17]->addTo($board[4][17]);
        $board[5][17]->addTo($board[5][16]);
        $board[5][17]->addTo($board[6][17]);
        $board[6][6]->addTo($board[5][6]);
        $board[6][6]->addTo($board[6][7]);
        $board[6][6]->addTo($board[7][6]);
        $board[6][7]->addTo($board[6][6]);
        $board[6][7]->addTo($board[5][7]);
        $board[6][7]->addTo($board[7][7]);
        $board[6][16]->addTo($board[5][16]);
        $board[6][16]->addTo($board[6][17]);
        $board[6][16]->addTo($board[7][16]);
        $board[6][17]->addTo($board[6][16]);
        $board[6][17]->addTo($board[5][17]);
        $board[6][17]->addTo($board[6][18]);
        $board[6][17]->addTo($board[7][17]);
        $board[6][18]->addTo($board[6][17]);
        $board[6][18]->addTo($board[6][19]);
        $board[6][18]->addTo($board[7][18]);
        $board[6][19]->addTo($board[6][18]);
        $board[6][19]->addTo($board[7][19]);
        $board[6][19]->addTo($board[6][20]);
        $board[6][20]->addTo($board[6][19]);
        $board[6][20]->addTo($board[7][20]);
        $board[6][20]->addTo($board[6][21]);
        $board[6][21]->addTo($board[6][20]);
        $board[6][21]->addTo($board[7][21]);
        $board[6][21]->addTo($board[6][22]);
        $board[6][22]->addTo($board[6][21]);
        $board[6][22]->addTo($board[7][22]);
        $board[7][0]->addTo($board[7][1]);
        $board[7][1]->addTo($board[7][0]);
        $board[7][1]->addTo($board[7][2]);
        $board[7][1]->addTo($board[8][1]);
        $board[7][2]->addTo($board[7][1]);
        $board[7][2]->addTo($board[7][3]);
        $board[7][2]->addTo($board[8][2]);
        $board[7][3]->addTo($board[7][2]);
        $board[7][3]->addTo($board[7][4]);
        $board[7][3]->addTo($board[8][3]);
        $board[7][4]->addTo($board[7][3]);
        $board[7][4]->addTo($board[7][5]);
        $board[7][4]->addTo($board[8][4]);
        $board[7][5]->addTo($board[7][4]);
        $board[7][5]->addTo($board[7][6]);
        $board[7][5]->addTo($board[8][5]);
        $board[7][6]->addTo($board[7][5]);
        $board[7][6]->addTo($board[7][7]);
        $board[7][6]->addTo($board[8][6]);
        $board[7][6]->addTo($board[6][6]);
        $board[7][7]->addTo($board[6][7]);
        $board[7][7]->addTo($board[7][6]);
        $board[7][7]->addTo($board[8][7]);
        $board[7][16]->addTo($board[6][16]);
        $board[7][16]->addTo($board[8][16]);
        $board[7][16]->addTo($board[7][17]);
        $board[7][17]->addTo($board[6][17]);
        $board[7][17]->addTo($board[8][17]);
        $board[7][17]->addTo($board[7][16]);
        $board[7][17]->addTo($board[7][18]);
        $board[7][18]->addTo($board[7][17]);
        $board[7][18]->addTo($board[6][18]);
        $board[7][18]->addTo($board[7][19]);
        $board[7][19]->addTo($board[7][18]);
        $board[7][19]->addTo($board[6][19]);
        $board[7][19]->addTo($board[7][20]);
        $board[7][20]->addTo($board[7][19]);
        $board[7][20]->addTo($board[6][20]);
        $board[7][20]->addTo($board[7][21]);
        $board[7][21]->addTo($board[7][20]);
        $board[7][21]->addTo($board[6][21]);
        $board[7][21]->addTo($board[7][22]);
        $board[7][22]->addTo($board[7][21]);
        $board[7][22]->addTo($board[6][22]);
        $board[7][22]->addTo($board[7][23]);

        //day
        $board[7][23]->addTo($board[7][22]);
        $board[8][1]->addTo($board[7][1]);
        $board[8][1]->addTo($board[8][2]);
        $board[8][2]->addTo($board[7][2]);
        $board[8][2]->addTo($board[8][3]);
        $board[8][2]->addTo($board[8][1]);
        $board[8][3]->addTo($board[7][3]);
        $board[8][3]->addTo($board[8][4]);
        $board[8][3]->addTo($board[8][2]);
        $board[8][4]->addTo($board[7][4]);
        $board[8][4]->addTo($board[8][5]);
        $board[8][4]->addTo($board[8][3]);
        $board[8][5]->addTo($board[7][5]);
        $board[8][5]->addTo($board[8][6]);
        $board[8][5]->addTo($board[9][5]);
        $board[8][5]->addTo($board[8][4]);
        $board[8][6]->addTo($board[8][5]);
        $board[8][6]->addTo($board[8][7]);
        $board[8][6]->addTo($board[9][6]);
        $board[8][6]->addTo($board[7][6]);
        $board[8][7]->addTo($board[7][7]);
        $board[8][7]->addTo($board[8][8]);
        $board[8][7]->addTo($board[9][7]);
        $board[8][7]->addTo($board[8][6]);
        $board[8][8]->addTo($board[8][9]);
        $board[8][8]->addTo($board[9][8]);
        $board[8][8]->addTo($board[8][7]);
        $board[8][9]->addTo($board[8][10]);
        $board[8][9]->addTo($board[9][9]);
        $board[8][9]->addTo($board[8][8]);
        $board[8][10]->addTo($board[8][11]);
        $board[8][10]->addTo($board[9][10]);
        $board[8][10]->addTo($board[8][9]);
        $board[8][11]->addTo($board[8][12]);
        $board[8][11]->addTo($board[9][11]);
        $board[8][11]->addTo($board[8][10]);
        $board[8][12]->addTo($board[8][13]);
        $board[8][12]->addTo($board[9][12]);
        $board[8][12]->addTo($board[8][11]);
        $board[8][13]->addTo($board[8][14]);
        $board[8][13]->addTo($board[9][13]);
        $board[8][13]->addTo($board[8][12]);
        $board[8][14]->addTo($board[8][15]);
        $board[8][14]->addTo($board[9][14]);
        $board[8][14]->addTo($board[8][13]);
        $board[8][15]->addTo($board[8][16]);
        $board[8][15]->addTo($board[9][15]);
        $board[8][15]->addTo($board[8][14]);
        $board[8][16]->addTo($board[7][16]);
        $board[8][16]->addTo($board[8][17]);
        $board[8][16]->addTo($board[9][16]);
        $board[8][16]->addTo($board[8][15]);
        $board[8][17]->addTo($board[7][17]);
        $board[8][17]->addTo($board[9][17]);
        $board[8][17]->addTo($board[8][16]);
        $board[9][5]->addTo($board[8][5]);
        $board[9][5]->addTo($board[9][6]);
        $board[9][6]->addTo($board[8][6]);
        $board[9][6]->addTo($board[9][7]);
        $board[9][6]->addTo($board[9][5]);
        $board[9][7]->addTo($board[8][7]);
        $board[9][7]->addTo($board[9][8]);
        $board[9][7]->addTo($board[9][6]);
        $board[9][8]->addTo($board[8][8]);
        $board[9][8]->addTo($board[9][9]);
        $board[9][8]->addTo($board[10][8]);
        $board[9][8]->addTo($board[9][7]);
        $board[9][9]->addTo($board[8][9]);
        $board[9][9]->addTo($board[9][10]);
        $board[9][9]->addTo($board[10][9]);
        $board[9][9]->addTo($board[9][8]);
        $board[9][10]->addTo($board[8][10]);
        $board[9][10]->addTo($board[9][11]);
        $board[9][10]->addTo($board[9][9]);
        $board[9][11]->addTo($board[8][11]);
        $board[9][11]->addTo($board[9][12]);
        $board[9][11]->addTo($board[9][10]);
        $board[9][12]->addTo($board[8][12]);
        $board[9][12]->addTo($board[9][13]);
        $board[9][12]->addTo($board[9][11]);
        $board[9][13]->addTo($board[8][13]);
        $board[9][13]->addTo($board[9][14]);
        $board[9][13]->addTo($board[9][12]);
        $board[9][14]->addTo($board[8][14]);
        $board[9][14]->addTo($board[9][15]);
        $board[9][14]->addTo($board[9][13]);
        $board[9][15]->addTo($board[8][15]);
        $board[9][15]->addTo($board[9][16]);
        $board[9][15]->addTo($board[10][15]);
        $board[9][15]->addTo($board[9][14]);
        $board[9][16]->addTo($board[8][16]);
        $board[9][16]->addTo($board[9][17]);
        $board[9][16]->addTo($board[10][16]);
        $board[9][16]->addTo($board[9][15]);
        $board[9][17]->addTo($board[8][17]);
        $board[9][17]->addTo($board[10][17]);
        $board[9][17]->addTo($board[9][16]);
        $board[10][8]->addTo($board[9][8]);
        $board[10][8]->addTo($board[10][9]);
        $board[10][8]->addTo($board[11][8]);
        $board[10][9]->addTo($board[9][9]);
        $board[10][9]->addTo($board[11][9]);
        $board[10][9]->addTo($board[10][8]);
        $board[10][15]->addTo($board[9][15]);
        $board[10][15]->addTo($board[10][16]);
        $board[10][15]->addTo($board[11][15]);
        $board[10][16]->addTo($board[9][16]);
        $board[10][16]->addTo($board[10][17]);
        $board[10][16]->addTo($board[11][16]);
        $board[10][16]->addTo($board[10][15]);
        $board[10][17]->addTo($board[9][17]);
        $board[10][17]->addTo($board[11][17]);
        $board[10][17]->addTo($board[10][16]);
        $board[11][8]->addTo($board[10][8]);
        $board[11][8]->addTo($board[11][9]);
        $board[11][8]->addTo($board[12][8]);
        $board[11][9]->addTo($board[10][9]);
        $board[11][9]->addTo($board[12][9]);
        $board[11][9]->addTo($board[11][8]);
        $board[11][15]->addTo($board[10][15]);
        $board[11][15]->addTo($board[11][16]);
        $board[11][15]->addTo($board[12][15]);
        $board[11][16]->addTo($board[10][16]);
        $board[11][16]->addTo($board[11][17]);
        $board[11][16]->addTo($board[12][16]);
        $board[11][16]->addTo($board[11][15]);
        $board[11][17]->addTo($board[10][17]);
        $board[11][17]->addTo($board[12][17]);
        $board[11][17]->addTo($board[11][16]);
        $board[12][8]->addTo($board[11][8]);
        $board[12][8]->addTo($board[12][9]);
        $board[12][8]->addTo($board[13][8]);
        $board[12][9]->addTo($board[11][9]);
        $board[12][9]->addTo($board[13][9]);
        $board[12][9]->addTo($board[12][8]);
        $board[12][15]->addTo($board[11][15]);
        $board[12][15]->addTo($board[12][16]);
        $board[12][15]->addTo($board[13][15]);
        $board[12][16]->addTo($board[11][16]);
        $board[12][16]->addTo($board[12][17]);
        $board[12][16]->addTo($board[13][16]);
        $board[12][16]->addTo($board[12][15]);
        $board[12][17]->addTo($board[11][17]);
        $board[12][17]->addTo($board[13][17]);
        $board[12][17]->addTo($board[12][16]);
        $board[13][8]->addTo($board[12][8]);
        $board[13][8]->addTo($board[13][9]);
        $board[13][8]->addTo($board[14][8]);
        $board[13][9]->addTo($board[12][9]);
        $board[13][9]->addTo($board[14][9]);
        $board[13][9]->addTo($board[13][8]);
        $board[13][15]->addTo($board[12][15]);
        $board[13][15]->addTo($board[13][16]);
        $board[13][15]->addTo($board[14][15]);
        $board[13][16]->addTo($board[12][16]);
        $board[13][16]->addTo($board[13][17]);
        $board[13][16]->addTo($board[14][16]);
        $board[13][16]->addTo($board[13][15]);
        $board[13][17]->addTo($board[12][17]);
        $board[13][17]->addTo($board[13][18]);
        $board[13][17]->addTo($board[14][17]);
        $board[13][17]->addTo($board[13][16]);
        $board[13][18]->addTo($board[13][19]);
        $board[13][18]->addTo($board[13][17]);
        $board[13][19]->addTo($board[13][20]);
        $board[13][19]->addTo($board[13][18]);
        $board[13][20]->addTo($board[13][21]);
        $board[13][20]->addTo($board[13][19]);
        $board[13][21]->addTo($board[13][22]);
        $board[13][21]->addTo($board[13][20]);
        $board[13][22]->addTo($board[13][21]);
        $board[14][8]->addTo($board[13][8]);
        $board[14][8]->addTo($board[14][9]);
        $board[14][8]->addTo($board[15][8]);
        $board[14][9]->addTo($board[13][9]);
        $board[14][9]->addTo($board[15][9]);
        $board[14][9]->addTo($board[14][8]);
        $board[14][15]->addTo($board[13][15]);
        $board[14][15]->addTo($board[14][16]);
        $board[14][15]->addTo($board[15][15]);
        $board[14][16]->addTo($board[13][16]);
        $board[14][16]->addTo($board[14][17]);
        $board[14][16]->addTo($board[15][16]);
        $board[14][16]->addTo($board[14][15]);
        $board[14][17]->addTo($board[13][17]);
        $board[14][17]->addTo($board[14][16]);
        $board[15][8]->addTo($board[14][8]);
        $board[15][8]->addTo($board[15][9]);
        $board[15][8]->addTo($board[16][8]);
        $board[15][9]->addTo($board[14][9]);
        $board[15][9]->addTo($board[16][9]);
        $board[15][9]->addTo($board[15][8]);
        $board[15][15]->addTo($board[14][15]);
        $board[15][15]->addTo($board[15][16]);
        $board[15][15]->addTo($board[16][15]);
        $board[15][16]->addTo($board[14][16]);
        $board[15][16]->addTo($board[16][16]);
        $board[15][16]->addTo($board[15][15]);
        $board[16][1]->addTo($board[16][2]);
        $board[16][1]->addTo($board[17][1]);
        $board[16][2]->addTo($board[16][3]);
        $board[16][2]->addTo($board[17][2]);
        $board[16][2]->addTo($board[16][1]);
        $board[16][3]->addTo($board[16][4]);
        $board[16][3]->addTo($board[17][3]);
        $board[16][3]->addTo($board[16][2]);
        $board[16][4]->addTo($board[16][5]);
        $board[16][4]->addTo($board[17][4]);
        $board[16][4]->addTo($board[16][3]);
        $board[16][5]->addTo($board[16][6]);
        $board[16][5]->addTo($board[17][5]);
        $board[16][5]->addTo($board[16][4]);
        $board[16][6]->addTo($board[16][7]);
        $board[16][6]->addTo($board[17][6]);
        $board[16][6]->addTo($board[16][5]);
        $board[16][7]->addTo($board[16][8]);
        $board[16][7]->addTo($board[17][7]);
        $board[16][7]->addTo($board[16][6]);
        $board[16][8]->addTo($board[15][8]);
        $board[16][8]->addTo($board[16][9]);
        $board[16][8]->addTo($board[17][8]);
        $board[16][8]->addTo($board[16][7]);
        $board[16][9]->addTo($board[15][9]);
        $board[16][9]->addTo($board[17][9]);
        $board[16][9]->addTo($board[16][8]);
        $board[16][15]->addTo($board[15][15]);
        $board[16][15]->addTo($board[16][16]);
        $board[16][15]->addTo($board[17][15]);
        $board[16][16]->addTo($board[15][16]);
        $board[16][16]->addTo($board[17][16]);
        $board[16][16]->addTo($board[16][15]);

        //onsay
        $board[17][0]->addTo($board[17][1]);
        $board[17][1]->addTo($board[16][1]);
        $board[17][1]->addTo($board[17][2]);
        $board[17][1]->addTo($board[18][1]);
        $board[17][1]->addTo($board[17][0]);
        $board[17][2]->addTo($board[16][2]);
        $board[17][2]->addTo($board[17][3]);
        $board[17][2]->addTo($board[18][2]);
        $board[17][2]->addTo($board[17][1]);
        $board[17][3]->addTo($board[16][3]);
        $board[17][3]->addTo($board[17][4]);
        $board[17][3]->addTo($board[18][3]);
        $board[17][3]->addTo($board[17][2]);
        $board[17][4]->addTo($board[16][4]);
        $board[17][4]->addTo($board[17][5]);
        $board[17][4]->addTo($board[18][4]);
        $board[17][4]->addTo($board[17][3]);
        $board[17][5]->addTo($board[16][5]);
        $board[17][5]->addTo($board[17][6]);
        $board[17][5]->addTo($board[18][5]);
        $board[17][5]->addTo($board[17][4]);
        $board[17][6]->addTo($board[16][6]);
        $board[17][6]->addTo($board[17][7]);
        $board[17][6]->addTo($board[18][6]);
        $board[17][6]->addTo($board[17][5]);
        $board[17][7]->addTo($board[16][7]);
        $board[17][7]->addTo($board[17][8]);
        $board[17][7]->addTo($board[18][7]);
        $board[17][7]->addTo($board[17][6]);
        $board[17][8]->addTo($board[16][8]);
        $board[17][8]->addTo($board[17][9]);
        $board[17][8]->addTo($board[18][8]);
        $board[17][8]->addTo($board[17][7]);
        $board[17][9]->addTo($board[16][9]);
        $board[17][9]->addTo($board[17][10]);
        $board[17][9]->addTo($board[17][8]);
        $board[17][10]->addTo($board[17][11]);
        $board[17][10]->addTo($board[17][9]);
        $board[17][11]->addTo($board[17][12]);
        $board[17][11]->addTo($board[17][10]);
        $board[17][12]->addTo($board[17][13]);
        $board[17][12]->addTo($board[17][11]);
        $board[17][13]->addTo($board[17][14]);
        $board[17][13]->addTo($board[17][12]);
        $board[17][14]->addTo($board[17][15]);
        $board[17][14]->addTo($board[17][13]);
        $board[17][15]->addTo($board[16][15]);
        $board[17][15]->addTo($board[17][16]);
        $board[17][15]->addTo($board[18][15]);
        $board[17][15]->addTo($board[17][14]);
        $board[17][16]->addTo($board[16][16]);
        $board[17][16]->addTo($board[18][16]);
        $board[17][16]->addTo($board[17][15]);
        $board[18][1]->addTo($board[17][1]);
        $board[18][1]->addTo($board[18][2]);
        $board[18][2]->addTo($board[17][2]);
        $board[18][2]->addTo($board[18][3]);
        $board[18][2]->addTo($board[18][1]);
        $board[18][3]->addTo($board[17][3]);
        $board[18][3]->addTo($board[18][4]);
        $board[18][3]->addTo($board[18][2]);
        $board[18][4]->addTo($board[17][4]);
        $board[18][4]->addTo($board[18][5]);
        $board[18][4]->addTo($board[18][3]);
        $board[18][5]->addTo($board[17][5]);
        $board[18][5]->addTo($board[18][6]);
        $board[18][5]->addTo($board[18][4]);
        $board[18][6]->addTo($board[17][6]);
        $board[18][6]->addTo($board[18][7]);
        $board[18][6]->addTo($board[18][5]);
        $board[18][7]->addTo($board[17][7]);
        $board[18][7]->addTo($board[18][8]);
        $board[18][7]->addTo($board[19][7]);
        $board[18][7]->addTo($board[18][6]);
        $board[18][8]->addTo($board[17][8]);
        $board[18][8]->addTo($board[19][8]);
        $board[18][8]->addTo($board[18][7]);
        $board[18][15]->addTo($board[17][15]);
        $board[18][15]->addTo($board[18][16]);
        $board[18][15]->addTo($board[19][15]);
        $board[18][16]->addTo($board[17][16]);
        $board[18][16]->addTo($board[18][17]);
        $board[18][16]->addTo($board[19][16]);
        $board[18][16]->addTo($board[18][15]);
        $board[18][17]->addTo($board[19][17]);
        $board[18][17]->addTo($board[18][16]);
        $board[19][7]->addTo($board[18][7]);
        $board[19][7]->addTo($board[19][8]);
        $board[19][7]->addTo($board[20][7]);
        $board[19][8]->addTo($board[18][8]);
        $board[19][8]->addTo($board[20][8]);
        $board[19][8]->addTo($board[19][7]);
        $board[19][15]->addTo($board[18][15]);
        $board[19][15]->addTo($board[19][16]);
        $board[19][15]->addTo($board[20][15]);
        $board[19][16]->addTo($board[18][16]);
        $board[19][16]->addTo($board[19][17]);
        $board[19][16]->addTo($board[20][16]);
        $board[19][16]->addTo($board[19][15]);
        $board[19][17]->addTo($board[18][17]);
        $board[19][17]->addTo($board[19][18]);
        $board[19][17]->addTo($board[20][17]);
        $board[19][17]->addTo($board[19][16]);
        $board[19][18]->addTo($board[19][19]);
        $board[19][18]->addTo($board[20][18]);
        $board[19][18]->addTo($board[19][17]);
        $board[19][19]->addTo($board[19][20]);
        $board[19][19]->addTo($board[20][19]);
        $board[19][19]->addTo($board[19][18]);
        $board[19][20]->addTo($board[19][21]);
        $board[19][20]->addTo($board[20][20]);
        $board[19][20]->addTo($board[19][19]);
        $board[19][21]->addTo($board[19][22]);
        $board[19][21]->addTo($board[20][21]);
        $board[19][21]->addTo($board[19][20]);
        $board[19][22]->addTo($board[19][23]);
        $board[19][22]->addTo($board[20][22]);
        $board[19][22]->addTo($board[19][21]);
        //plum
        $board[19][23]->addTo($board[19][22]);
        $board[20][7]->addTo($board[19][7]);
        $board[20][7]->addTo($board[20][8]);
        $board[20][7]->addTo($board[21][7]);
        $board[20][8]->addTo($board[19][8]);
        $board[20][8]->addTo($board[21][8]);
        $board[20][8]->addTo($board[20][7]);
        $board[20][15]->addTo($board[19][15]);
        $board[20][15]->addTo($board[20][16]);
        $board[20][15]->addTo($board[21][15]);
        $board[20][16]->addTo($board[19][16]);
        $board[20][16]->addTo($board[20][17]);
        $board[20][16]->addTo($board[21][16]);
        $board[20][16]->addTo($board[20][15]);
        $board[20][17]->addTo($board[19][17]);
        $board[20][17]->addTo($board[20][18]);

        $board[20][17]->addTo($board[20][16]);
        $board[20][18]->addTo($board[19][18]);
        $board[20][18]->addTo($board[20][19]);
        $board[20][18]->addTo($board[20][17]);
        $board[20][19]->addTo($board[19][19]);
        $board[20][19]->addTo($board[20][20]);
        $board[20][19]->addTo($board[20][18]);
        $board[20][20]->addTo($board[19][20]);
        $board[20][20]->addTo($board[20][21]);
        $board[20][20]->addTo($board[20][19]);
        $board[20][21]->addTo($board[19][21]);
        $board[20][21]->addTo($board[20][22]);
        $board[20][21]->addTo($board[20][20]);
        $board[20][22]->addTo($board[19][22]);
        $board[20][22]->addTo($board[20][21]);
        $board[21][7]->addTo($board[20][7]);
        $board[21][7]->addTo($board[21][8]);
        $board[21][7]->addTo($board[22][7]);
        $board[21][8]->addTo($board[20][8]);
        $board[21][8]->addTo($board[22][8]);
        $board[21][8]->addTo($board[21][7]);
        $board[21][15]->addTo($board[20][15]);
        $board[21][15]->addTo($board[21][16]);
        $board[21][15]->addTo($board[22][15]);
        $board[21][16]->addTo($board[20][16]);
        $board[21][16]->addTo($board[22][16]);
        $board[21][16]->addTo($board[21][15]);
        $board[22][7]->addTo($board[21][7]);
        $board[22][7]->addTo($board[22][8]);
        $board[22][7]->addTo($board[23][7]);
        $board[22][8]->addTo($board[21][8]);
        $board[22][8]->addTo($board[23][8]);
        $board[22][8]->addTo($board[22][7]);
        $board[22][15]->addTo($board[21][15]);
        $board[22][15]->addTo($board[22][16]);
        $board[22][15]->addTo($board[23][15]);
        $board[22][16]->addTo($board[21][16]);
        $board[22][16]->addTo($board[23][16]);
        $board[22][16]->addTo($board[22][15]);
        $board[23][7]->addTo($board[22][7]);
        $board[23][7]->addTo($board[23][8]);
        $board[23][7]->addTo($board[24][7]);
        $board[23][8]->addTo($board[22][8]);
        $board[23][8]->addTo($board[23][7]);
        $board[23][15]->addTo($board[22][15]);
        $board[23][15]->addTo($board[23][16]);
        $board[23][16]->addTo($board[22][16]);
        $board[23][16]->addTo($board[24][16]);
        $board[23][16]->addTo($board[23][15]);
        //enbody
        $board[24][7]->addTo($board[23][7]);
        $board[24][16]->addTo($board[23][16]);
    }
    public function get_building_part()
    {
        return $this->building_part;
    }

    // ###### OUT OF USE ######
    public function connect_buildings() {


        $int_center = new Node(3,2);
        $int_center->setRoom('international_center');
        $this->building_part['international_center'] = $int_center;

        $breslin = new Node(3,9);
        $breslin->setRoom('breslin_center');
        $this->building_part['breslin_center'] = $breslin;

        $beaumont = new Node(2,19);
        $beaumont->setRoom('beaumont_tower');
        $this->building_part['beaumont_tower'] = $beaumont;

        $union = new Node(12,1);
        $union->setRoom("university_union");
        $this->building_part['university_union'] = $union;

        $art = new Node(10, 19);
        $art->setRoom("art_museum");
        $this->building_part['art_museum'] = $art;

        $lib = new Node(16, 19);
        $lib->setRoom("library");
        $this->building_part['library'] = $lib;

        $wharton = new Node(21, 1);
        $wharton->setRoom("wharton_center");
        $this->building_part['wharton_center'] = $wharton;

        $stadium = new Node(21,10);
        $stadium->setRoom("spartan_stadium");
        $this->building_part['spartan_stadium'] = $stadium;

        $eb = new Node(22, 18);
        $eb->setRoom("engineering_building");
        $this->building_part['engineering_building'] = $eb;

/*        $this->building_part = array(
            'international_center'=>new Node(3, 2),
            'breslin_center'=> new Node(3,9), //array(new Node(5, 16), new Node(8, 9), new Node(8, 14), new Node(5, 7)),
            'beaumont_tower'=>new Node(6, 18), //6 18
            'university_union'=> new Node(12,8), //array(new Node(12, 8), new Node(16, 6)),
            'art_museum'=> new Node(9,17), //array(new Node(9, 17), new Node(13, 22)),
            'library'=> new Node(13,20), //array(new Node(13, 20), new Node(16, 16)),
            'wharton_center'=>new Node(18, 6),
            'spartan_stadium'=> new Node (17,12), //array(new Node(17, 12), new Node(17, 11)),
            'engineering_building'=>new Node(20, 17)
        );*/

        //connected
        $this->building_part['international_center']->addTo($this->building_part['engineering_building']);
        $this->building_part['engineering_building']->addTo($this->building_part['international_center']);
        $this->building_part['wharton_center']->addTo($this->building_part['beaumont_tower']);
        $this->building_part['beaumont_tower']->addTo($this->building_part['wharton_center']);

        // connect the entrance tiles with the building nodes

        // international center:
        $board = $this->getboard();
        $board[7][4]->addTo($int_center);
        $int_center->addTo($board[7][4]);

        // Beaumont
        $board[6][18]->addTo($beaumont);
        $beaumont->addto($board[6][18]);

        // Breslin
        $board[5][7]->addTo($breslin);
        $board[5][16]->addTo($breslin);
        $board[8][9]->addTo($breslin);
        $board[8][14]->addTo($breslin);
        $breslin->addTo($board[5][7]);
        $breslin->addTo($board[5][16]);
        $breslin->addTo($board[8][9]);
        $breslin->addTo($board[8][14]);

        // Union
        $board[12][8]->addTo($union);
        $board[16][6]->addTo($union);
        $union->addTo($board[12][8]);
        $union->addTo($board[16][6]);

        // Art museum
        $board[9][17]->addTo($art);
        $board[13][22]->addTo($art);
        $art->addTo($board[9][17]);
        $art->addTo($board[13][22]);

        // Library
        $board[13][20]->addTo($lib);
        $board[16][16]->addTo($lib);
        $lib->addTo($board[13][20]);
        $lib->addTo($board[16][16]);

        // Wharton center
        $board[18][6]->addTo($wharton);
        $wharton->addTo($board[18][6]);

        // Spartan Stadium
        $board[17][11]->addTo($stadium);
        $board[17][12]->addTo($stadium);
        $stadium->addTo($board[17][11]);
        $stadium->addTo($board[17][12]);

        // Engineering Building
        $board[20][17]->addTo($eb);
        $eb->addTo($board[20][17]);

    }

    ///// ######## OUT OF USE ########
    public function connect_room()
    {
        $this->board[1][0] = $this->building_part['international_center'];
        $this->board[1][1] = $this->building_part['international_center'];
        $this->board[1][2] = $this->building_part['international_center'];
        $this->board[1][3] = $this->building_part['international_center'];
        $this->board[1][4] = $this->building_part['international_center'];
        $this->board[1][5] = $this->building_part['international_center'];
        $this->board[2][0] = $this->building_part['international_center'];
        $this->board[2][1] = $this->building_part['international_center'];
        $this->board[2][2] = $this->building_part['international_center'];
        $this->board[2][3] = $this->building_part['international_center'];
        $this->board[2][4] = $this->building_part['international_center'];
        $this->board[2][5] = $this->building_part['international_center'];
        $this->board[3][0] = $this->building_part['international_center'];
        $this->board[3][1] = $this->building_part['international_center'];
        $this->board[3][2] = $this->building_part['international_center'];
        $this->board[3][3] = $this->building_part['international_center'];
        $this->board[3][4] = $this->building_part['international_center'];
        $this->board[3][5] = $this->building_part['international_center'];
        $this->board[4][0] = $this->building_part['international_center'];
        $this->board[4][1] = $this->building_part['international_center'];
        $this->board[4][2] = $this->building_part['international_center'];
        $this->board[4][3] = $this->building_part['international_center'];
        $this->board[4][4] = $this->building_part['international_center'];
        $this->board[4][5] = $this->building_part['international_center'];
        $this->board[5][0] = $this->building_part['international_center'];
        $this->board[5][1] = $this->building_part['international_center'];
        $this->board[5][2] = $this->building_part['international_center'];
        $this->board[5][3] = $this->building_part['international_center'];
        $this->board[5][4] = $this->building_part['international_center'];
        $this->board[5][5] = $this->building_part['international_center'];
        $this->board[6][1] = $this->building_part['international_center'];
        $this->board[6][2] = $this->building_part['international_center'];
        $this->board[6][3] = $this->building_part['international_center'];
        $this->board[6][4] = $this->building_part['international_center'];
        $this->board[6][5] = $this->building_part['international_center'];

        $this->board[1][10] = $this->building_part['breslin_center'];
        $this->board[1][11] = $this->building_part['breslin_center'];
        $this->board[1][12] = $this->building_part['breslin_center'];
        $this->board[1][13] = $this->building_part['breslin_center'];
        $this->board[2][8] = $this->building_part['breslin_center'];
        $this->board[2][9] = $this->building_part['breslin_center'];
        $this->board[2][10] = $this->building_part['breslin_center'];
        $this->board[2][11] = $this->building_part['breslin_center'];
        $this->board[2][12] = $this->building_part['breslin_center'];
        $this->board[2][13] = $this->building_part['breslin_center'];
        $this->board[2][14] = $this->building_part['breslin_center'];
        $this->board[2][15] = $this->building_part['breslin_center'];
        $this->board[3][8] = $this->building_part['breslin_center'];
        $this->board[3][9] = $this->building_part['breslin_center'];
        $this->board[3][10] = $this->building_part['breslin_center'];
        $this->board[3][11] = $this->building_part['breslin_center'];
        $this->board[3][12] = $this->building_part['breslin_center'];
        $this->board[3][13] = $this->building_part['breslin_center'];
        $this->board[3][14] = $this->building_part['breslin_center'];
        $this->board[3][15] = $this->building_part['breslin_center'];
        $this->board[4][8] = $this->building_part['breslin_center'];
        $this->board[4][9] = $this->building_part['breslin_center'];
        $this->board[4][10] = $this->building_part['breslin_center'];
        $this->board[4][11] = $this->building_part['breslin_center'];
        $this->board[4][12] = $this->building_part['breslin_center'];
        $this->board[4][13] = $this->building_part['breslin_center'];
        $this->board[4][14] = $this->building_part['breslin_center'];
        $this->board[4][15] = $this->building_part['breslin_center'];
        $this->board[5][8] = $this->building_part['breslin_center'];
        $this->board[5][9] = $this->building_part['breslin_center'];
        $this->board[5][10] = $this->building_part['breslin_center'];
        $this->board[5][11] = $this->building_part['breslin_center'];
        $this->board[5][12] = $this->building_part['breslin_center'];
        $this->board[5][13] = $this->building_part['breslin_center'];
        $this->board[5][14] = $this->building_part['breslin_center'];
        $this->board[5][15] = $this->building_part['breslin_center'];
        $this->board[6][8] = $this->building_part['breslin_center'];
        $this->board[6][9] = $this->building_part['breslin_center'];
        $this->board[6][10] = $this->building_part['breslin_center'];
        $this->board[6][11] = $this->building_part['breslin_center'];
        $this->board[6][12] = $this->building_part['breslin_center'];
        $this->board[6][13] = $this->building_part['breslin_center'];
        $this->board[6][14] = $this->building_part['breslin_center'];
        $this->board[6][15] = $this->building_part['breslin_center'];
        $this->board[7][8] = $this->building_part['breslin_center'];
        $this->board[7][9] = $this->building_part['breslin_center'];
        $this->board[7][10] = $this->building_part['breslin_center'];
        $this->board[7][11] = $this->building_part['breslin_center'];
        $this->board[7][12] = $this->building_part['breslin_center'];
        $this->board[7][13] = $this->building_part['breslin_center'];
        $this->board[7][14] = $this->building_part['breslin_center'];
        $this->board[7][15] = $this->building_part['breslin_center'];

        $this->board[1][18] = $this->building_part['beaumont_tower'];
        $this->board[1][19] = $this->building_part['beaumont_tower'];
        $this->board[1][20] = $this->building_part['beaumont_tower'];
        $this->board[1][21] = $this->building_part['beaumont_tower'];
        $this->board[1][22] = $this->building_part['beaumont_tower'];
        $this->board[1][23] = $this->building_part['beaumont_tower'];
        $this->board[2][18] = $this->building_part['beaumont_tower'];
        $this->board[2][19] = $this->building_part['beaumont_tower'];
        $this->board[2][20] = $this->building_part['beaumont_tower'];
        $this->board[2][21] = $this->building_part['beaumont_tower'];
        $this->board[2][22] = $this->building_part['beaumont_tower'];
        $this->board[2][23] = $this->building_part['beaumont_tower'];
        $this->board[3][18] = $this->building_part['beaumont_tower'];
        $this->board[3][19] = $this->building_part['beaumont_tower'];
        $this->board[3][20] = $this->building_part['beaumont_tower'];
        $this->board[3][21] = $this->building_part['beaumont_tower'];
        $this->board[3][22] = $this->building_part['beaumont_tower'];
        $this->board[3][23] = $this->building_part['beaumont_tower'];
        $this->board[4][18] = $this->building_part['beaumont_tower'];
        $this->board[4][19] = $this->building_part['beaumont_tower'];
        $this->board[4][20] = $this->building_part['beaumont_tower'];
        $this->board[4][21] = $this->building_part['beaumont_tower'];
        $this->board[4][22] = $this->building_part['beaumont_tower'];
        $this->board[4][23] = $this->building_part['beaumont_tower'];
        $this->board[5][18] = $this->building_part['beaumont_tower'];
        $this->board[5][19] = $this->building_part['beaumont_tower'];
        $this->board[5][20] = $this->building_part['beaumont_tower'];
        $this->board[5][21] = $this->building_part['beaumont_tower'];
        $this->board[5][22] = $this->building_part['beaumont_tower'];

        $this->board[9][0] = $this->building_part['university_union'];
        $this->board[9][1] = $this->building_part['university_union'];
        $this->board[9][2] = $this->building_part['university_union'];
        $this->board[9][3] = $this->building_part['university_union'];
        $this->board[9][4] = $this->building_part['university_union'];
        $this->board[10][0] = $this->building_part['university_union'];
        $this->board[10][1] = $this->building_part['university_union'];
        $this->board[10][2] = $this->building_part['university_union'];
        $this->board[10][3] = $this->building_part['university_union'];
        $this->board[10][4] = $this->building_part['university_union'];
        $this->board[10][5] = $this->building_part['university_union'];
        $this->board[10][6] = $this->building_part['university_union'];
        $this->board[10][7] = $this->building_part['university_union'];
        $this->board[11][0] = $this->building_part['university_union'];
        $this->board[11][1] = $this->building_part['university_union'];
        $this->board[11][2] = $this->building_part['university_union'];
        $this->board[11][3] = $this->building_part['university_union'];
        $this->board[11][4] = $this->building_part['university_union'];
        $this->board[11][5] = $this->building_part['university_union'];
        $this->board[11][6] = $this->building_part['university_union'];
        $this->board[11][7] = $this->building_part['university_union'];
        $this->board[12][0] = $this->building_part['university_union'];
        $this->board[12][1] = $this->building_part['university_union'];
        $this->board[12][2] = $this->building_part['university_union'];
        $this->board[12][3] = $this->building_part['university_union'];
        $this->board[12][4] = $this->building_part['university_union'];
        $this->board[12][5] = $this->building_part['university_union'];
        $this->board[12][6] = $this->building_part['university_union'];
        $this->board[12][7] = $this->building_part['university_union'];
        $this->board[13][0] = $this->building_part['university_union'];
        $this->board[13][1] = $this->building_part['university_union'];
        $this->board[13][2] = $this->building_part['university_union'];
        $this->board[13][3] = $this->building_part['university_union'];
        $this->board[13][4] = $this->building_part['university_union'];
        $this->board[13][5] = $this->building_part['university_union'];
        $this->board[13][6] = $this->building_part['university_union'];
        $this->board[13][7] = $this->building_part['university_union'];
        $this->board[14][0] = $this->building_part['university_union'];
        $this->board[14][1] = $this->building_part['university_union'];
        $this->board[14][2] = $this->building_part['university_union'];
        $this->board[14][3] = $this->building_part['university_union'];
        $this->board[14][4] = $this->building_part['university_union'];
        $this->board[14][5] = $this->building_part['university_union'];
        $this->board[14][6] = $this->building_part['university_union'];
        $this->board[14][7] = $this->building_part['university_union'];
        $this->board[15][0] = $this->building_part['university_union'];
        $this->board[15][1] = $this->building_part['university_union'];
        $this->board[15][2] = $this->building_part['university_union'];
        $this->board[15][3] = $this->building_part['university_union'];
        $this->board[15][4] = $this->building_part['university_union'];
        $this->board[15][5] = $this->building_part['university_union'];
        $this->board[15][6] = $this->building_part['university_union'];
        $this->board[15][7] = $this->building_part['university_union'];

        $this->board[8][18] = $this->building_part['art_museum'];
        $this->board[8][19] = $this->building_part['art_museum'];
        $this->board[8][20] = $this->building_part['art_museum'];
        $this->board[8][21] = $this->building_part['art_museum'];
        $this->board[8][22] = $this->building_part['art_museum'];
        $this->board[8][23] = $this->building_part['art_museum'];
        $this->board[9][18] = $this->building_part['art_museum'];
        $this->board[9][19] = $this->building_part['art_museum'];
        $this->board[9][20] = $this->building_part['art_museum'];
        $this->board[9][21] = $this->building_part['art_museum'];
        $this->board[9][22] = $this->building_part['art_museum'];
        $this->board[9][23] = $this->building_part['art_museum'];
        $this->board[10][18] = $this->building_part['art_museum'];
        $this->board[10][19] = $this->building_part['art_museum'];
        $this->board[10][20] = $this->building_part['art_museum'];
        $this->board[10][21] = $this->building_part['art_museum'];
        $this->board[10][22] = $this->building_part['art_museum'];
        $this->board[10][23] = $this->building_part['art_museum'];
        $this->board[11][18] = $this->building_part['art_museum'];
        $this->board[11][19] = $this->building_part['art_museum'];
        $this->board[11][20] = $this->building_part['art_museum'];
        $this->board[11][21] = $this->building_part['art_museum'];
        $this->board[11][22] = $this->building_part['art_museum'];
        $this->board[11][23] = $this->building_part['art_museum'];
        $this->board[12][18] = $this->building_part['art_museum'];
        $this->board[12][19] = $this->building_part['art_museum'];
        $this->board[12][20] = $this->building_part['art_museum'];
        $this->board[12][21] = $this->building_part['art_museum'];
        $this->board[12][22] = $this->building_part['art_museum'];
        $this->board[12][23] = $this->building_part['art_museum'];

        $this->board[14][18] = $this->building_part['library'];
        $this->board[14][19] = $this->building_part['library'];
        $this->board[14][20] = $this->building_part['library'];
        $this->board[14][21] = $this->building_part['library'];
        $this->board[14][22] = $this->building_part['library'];
        $this->board[15][17] = $this->building_part['library'];
        $this->board[15][18] = $this->building_part['library'];
        $this->board[15][19] = $this->building_part['library'];
        $this->board[15][20] = $this->building_part['library'];
        $this->board[15][21] = $this->building_part['library'];
        $this->board[15][22] = $this->building_part['library'];
        $this->board[15][23] = $this->building_part['library'];
        $this->board[16][17] = $this->building_part['library'];
        $this->board[16][18] = $this->building_part['library'];
        $this->board[16][19] = $this->building_part['library'];
        $this->board[16][20] = $this->building_part['library'];
        $this->board[16][21] = $this->building_part['library'];
        $this->board[16][22] = $this->building_part['library'];
        $this->board[16][23] = $this->building_part['library'];
        $this->board[17][17] = $this->building_part['library'];
        $this->board[17][18] = $this->building_part['library'];
        $this->board[17][19] = $this->building_part['library'];
        $this->board[17][20] = $this->building_part['library'];
        $this->board[17][21] = $this->building_part['library'];
        $this->board[17][22] = $this->building_part['library'];
        $this->board[17][23] = $this->building_part['library'];
        $this->board[18][18] = $this->building_part['library'];
        $this->board[18][19] = $this->building_part['library'];
        $this->board[18][20] = $this->building_part['library'];
        $this->board[18][21] = $this->building_part['library'];
        $this->board[18][22] = $this->building_part['library'];

        $this->board[19][0] = $this->building_part['wharton_center'];
        $this->board[19][1] = $this->building_part['wharton_center'];
        $this->board[19][2] = $this->building_part['wharton_center'];
        $this->board[19][3] = $this->building_part['wharton_center'];
        $this->board[19][4] = $this->building_part['wharton_center'];
        $this->board[19][5] = $this->building_part['wharton_center'];
        $this->board[19][6] = $this->building_part['wharton_center'];
        $this->board[20][0] = $this->building_part['wharton_center'];
        $this->board[20][1] = $this->building_part['wharton_center'];
        $this->board[20][2] = $this->building_part['wharton_center'];
        $this->board[20][3] = $this->building_part['wharton_center'];
        $this->board[20][4] = $this->building_part['wharton_center'];
        $this->board[20][5] = $this->building_part['wharton_center'];
        $this->board[20][6] = $this->building_part['wharton_center'];
        $this->board[21][0] = $this->building_part['wharton_center'];
        $this->board[21][1] = $this->building_part['wharton_center'];
        $this->board[21][2] = $this->building_part['wharton_center'];
        $this->board[21][3] = $this->building_part['wharton_center'];
        $this->board[21][4] = $this->building_part['wharton_center'];
        $this->board[21][5] = $this->building_part['wharton_center'];
        $this->board[21][6] = $this->building_part['wharton_center'];
        $this->board[22][0] = $this->building_part['wharton_center'];
        $this->board[22][1] = $this->building_part['wharton_center'];
        $this->board[22][2] = $this->building_part['wharton_center'];
        $this->board[22][3] = $this->building_part['wharton_center'];
        $this->board[22][4] = $this->building_part['wharton_center'];
        $this->board[22][5] = $this->building_part['wharton_center'];
        $this->board[22][6] = $this->building_part['wharton_center'];
        $this->board[23][0] = $this->building_part['wharton_center'];
        $this->board[23][1] = $this->building_part['wharton_center'];
        $this->board[23][2] = $this->building_part['wharton_center'];
        $this->board[23][3] = $this->building_part['wharton_center'];
        $this->board[23][4] = $this->building_part['wharton_center'];
        $this->board[23][5] = $this->building_part['wharton_center'];
        $this->board[23][6] = $this->building_part['wharton_center'];
        $this->board[24][0] = $this->building_part['wharton_center'];
        $this->board[24][1] = $this->building_part['wharton_center'];
        $this->board[24][2] = $this->building_part['wharton_center'];
        $this->board[24][3] = $this->building_part['wharton_center'];
        $this->board[24][4] = $this->building_part['wharton_center'];
        $this->board[24][5] = $this->building_part['wharton_center'];

        $this->board[18][9] = $this->building_part['spartan_stadium'];
        $this->board[18][10] = $this->building_part['spartan_stadium'];
        $this->board[18][11] = $this->building_part['spartan_stadium'];
        $this->board[18][12] = $this->building_part['spartan_stadium'];
        $this->board[18][13] = $this->building_part['spartan_stadium'];
        $this->board[18][14] = $this->building_part['spartan_stadium'];
        $this->board[19][9] = $this->building_part['spartan_stadium'];
        $this->board[19][10] = $this->building_part['spartan_stadium'];
        $this->board[19][11] = $this->building_part['spartan_stadium'];
        $this->board[19][12] = $this->building_part['spartan_stadium'];
        $this->board[19][13] = $this->building_part['spartan_stadium'];
        $this->board[19][14] = $this->building_part['spartan_stadium'];
        $this->board[20][9] = $this->building_part['spartan_stadium'];
        $this->board[20][10] = $this->building_part['spartan_stadium'];
        $this->board[20][11] = $this->building_part['spartan_stadium'];
        $this->board[20][12] = $this->building_part['spartan_stadium'];
        $this->board[20][13] = $this->building_part['spartan_stadium'];
        $this->board[20][14] = $this->building_part['spartan_stadium'];
        $this->board[21][9] = $this->building_part['spartan_stadium'];
        $this->board[21][10] = $this->building_part['spartan_stadium'];
        $this->board[21][11] = $this->building_part['spartan_stadium'];
        $this->board[21][12] = $this->building_part['spartan_stadium'];
        $this->board[21][13] = $this->building_part['spartan_stadium'];
        $this->board[21][14] = $this->building_part['spartan_stadium'];
        $this->board[22][9] = $this->building_part['spartan_stadium'];
        $this->board[22][10] = $this->building_part['spartan_stadium'];
        $this->board[22][11] = $this->building_part['spartan_stadium'];
        $this->board[22][12] = $this->building_part['spartan_stadium'];
        $this->board[22][13] = $this->building_part['spartan_stadium'];
        $this->board[22][14] = $this->building_part['spartan_stadium'];
        $this->board[23][9] = $this->building_part['spartan_stadium'];
        $this->board[23][10] = $this->building_part['spartan_stadium'];
        $this->board[23][11] = $this->building_part['spartan_stadium'];
        $this->board[23][12] = $this->building_part['spartan_stadium'];
        $this->board[23][13] = $this->building_part['spartan_stadium'];
        $this->board[23][14] = $this->building_part['spartan_stadium'];
        $this->board[24][9] = $this->building_part['spartan_stadium'];
        $this->board[24][10] = $this->building_part['spartan_stadium'];
        $this->board[24][11] = $this->building_part['spartan_stadium'];
        $this->board[24][12] = $this->building_part['spartan_stadium'];
        $this->board[24][13] = $this->building_part['spartan_stadium'];
        $this->board[24][14] = $this->building_part['spartan_stadium'];

        $this->board[21][17] = $this->building_part['engineering_building'];
        $this->board[21][18] = $this->building_part['engineering_building'];
        $this->board[21][19] = $this->building_part['engineering_building'];
        $this->board[21][20] = $this->building_part['engineering_building'];
        $this->board[21][21] = $this->building_part['engineering_building'];
        $this->board[21][22] = $this->building_part['engineering_building'];
        $this->board[21][23] = $this->building_part['engineering_building'];
        $this->board[22][17] = $this->building_part['engineering_building'];
        $this->board[22][18] = $this->building_part['engineering_building'];
        $this->board[22][19] = $this->building_part['engineering_building'];
        $this->board[22][20] = $this->building_part['engineering_building'];
        $this->board[22][21] = $this->building_part['engineering_building'];
        $this->board[22][22] = $this->building_part['engineering_building'];
        $this->board[22][23] = $this->building_part['engineering_building'];
        $this->board[23][17] = $this->building_part['engineering_building'];
        $this->board[23][18] = $this->building_part['engineering_building'];
        $this->board[23][19] = $this->building_part['engineering_building'];
        $this->board[23][20] = $this->building_part['engineering_building'];
        $this->board[23][21] = $this->building_part['engineering_building'];
        $this->board[23][22] = $this->building_part['engineering_building'];
        $this->board[23][23] = $this->building_part['engineering_building'];
        $this->board[24][18] = $this->building_part['engineering_building'];
        $this->board[24][19] = $this->building_part['engineering_building'];
        $this->board[24][20] = $this->building_part['engineering_building'];
        $this->board[24][21] = $this->building_part['engineering_building'];
        $this->board[24][22] = $this->building_part['engineering_building'];
        $this->board[24][23] = $this->building_part['engineering_building'];
    }

    /**
     * Construct all the rooms on the board, connect them with adjacent rooms, and set
     * their entry nodes
     */
    public function constructRooms() {
        // International Center

        $intl_nodes = [];
        for ($i = 1; $i < 6; $i++) {
            for($j = 0; $j < 6; $j++) {
                $intl_nodes[] = $this->board[$i][$j];
            }
        }
        $intl_nodes[] = $this->board[6][1];
        $intl_nodes[] = $this->board[6][2];
        $intl_nodes[] = $this->board[6][3];
        $intl_nodes[] = $this->board[6][4];
        $intl_nodes[] = $this->board[6][5];

        $intl_center = new Room("international_center", $intl_nodes);
        $intl_center->addEntryNode($this->board[7][4]);

        //Breslin Center

        $breslin_nodes = [];
        for ($i = 2; $i < 8; $i++) {
            for ($j = 8; $j < 16; $j++) {
                $breslin_nodes[] = $this->board[$i][$j];
            }
        }
        $breslin_nodes[] = $this->board[1][10];
        $breslin_nodes[] = $this->board[1][11];
        $breslin_nodes[] = $this->board[1][12];
        $breslin_nodes[] = $this->board[1][13];

        $breslin_center = new Room("breslin_center", $breslin_nodes);
        $breslin_center->addEntryNode($this->board[5][7]);
        $breslin_center->addEntryNode($this->board[5][16]);
        $breslin_center->addEntryNode($this->board[8][9]);
        $breslin_center->addEntryNode($this->board[8][14]);

        // Beaumont Tower

        $beaumont_nodes = [];
        for ($i = 1; $i < 5; $i++) {
            for ($j = 18; $j < 24; $j++) {
                $beaumont_nodes[] = $this->board[$i][$j];
            }
        }
        $beaumont_nodes[] = $this->board[5][18];
        $beaumont_nodes[] = $this->board[5][19];
        $beaumont_nodes[] = $this->board[5][20];
        $beaumont_nodes[] = $this->board[5][21];
        $beaumont_nodes[] = $this->board[5][22];

        $beaumont = new Room("beaumont_tower", $beaumont_nodes);
        $beaumont->addEntryNode($this->board[6][18]);

        // Union

        $union_nodes = [];
        for ($i = 10; $i < 16; $i++) {
            for ($j = 0; $j < 8; $j++) {
                $union_nodes[] = $this->board[$i][$j];
            }
        }
        $union_nodes[] = $this->board[9][0];
        $union_nodes[] = $this->board[9][1];
        $union_nodes[] = $this->board[9][2];
        $union_nodes[] = $this->board[9][3];
        $union_nodes[] = $this->board[9][4];

        $union = new Room("university_union", $union_nodes);
        $union->addEntryNode($this->board[12][8]);
        $union->addEntryNode($this->board[16][6]);

        // Art museum

        $art_nodes = [];
        for($i = 8; $i < 13; $i++) {
            for ($j = 18; $j < 24; $j++) {
                $art_nodes[] = $this->board[$i][$j];
            }
        }

        $art = new Room("art_museum", $art_nodes);
        $art->addEntryNode($this->board[9][17]);
        $art->addEntryNode($this->board[13][22]);

        // Library

        $lib_nodes = [];
        for ($i = 14; $i < 19; $i++) {
            for ($j = 18; $j < 23; $j++) {
                $lib_nodes[] = $this->board[$i][$j];
            }
        }
        $lib_nodes[]= $this->board[15][17];
        $lib_nodes[]= $this->board[16][17];
        $lib_nodes[]= $this->board[17][17];

        $lib_nodes[] = $this->board[15][23];
        $lib_nodes[] = $this->board[16][23];
        $lib_nodes[] = $this->board[17][23];

        $lib = new Room("library", $lib_nodes);
        $lib->addEntryNode($this->board[13][20]);
        $lib->addEntryNode($this->board[16][16]);

        // Wharton center

        $wharton_nodes = [];
        for ($i = 19; $i < 24; $i++) {
            for ($j = 0; $j < 7; $j++) {
                $wharton_nodes[] = $this->board[$i][$j];
            }
        }
        $wharton_nodes[] = $this->board[24][0];
        $wharton_nodes[] = $this->board[24][1];
        $wharton_nodes[] = $this->board[24][2];
        $wharton_nodes[] = $this->board[24][3];
        $wharton_nodes[] = $this->board[24][4];
        $wharton_nodes[] = $this->board[24][5];

        $wharton = new Room("wharton_center", $wharton_nodes);
        $wharton->addEntryNode($this->board[18][6]);

        // Spartan Stadium

        $stadium_nodes = [];
        for ($i = 18; $i < 25; $i++) {
            for($j = 9; $j < 15; $j++) {
                $stadium_nodes[] = $this->board[$i][$j];
            }
        }

        $stadium = new Room("spartan_stadium", $stadium_nodes);
        $stadium->addEntryNode($this->board[17][11]);
        $stadium->addEntryNode($this->board[17][12]);

        // Engineering Building

        $engineering_nodes = [];
        for ($i = 21; $i < 24; $i++) {
            for ($j  = 17; $j < 24; $j++) {
                $engineering_nodes[] = $this->board[$i][$j];
            }
        }
        $engineering_nodes[] = $this->board[24][18];
        $engineering_nodes[] = $this->board[24][19];
        $engineering_nodes[] = $this->board[24][20];
        $engineering_nodes[] = $this->board[24][21];
        $engineering_nodes[] = $this->board[24][22];
        $engineering_nodes[] = $this->board[24][23];

        $engineering = new Room("engineering_building", $engineering_nodes);
        $engineering->addEntryNode($this->board[20][17]);

        // Room connections
        $intl_center->addRoomConnection($engineering);
        $beaumont->addRoomConnection($wharton);
        $wharton->addRoomConnection($beaumont);
        $engineering->addRoomConnection($intl_center);

        $this->rooms["international_center"] = $intl_center;
        $this->rooms["beaumont_tower"] = $beaumont;
        $this->rooms["breslin_center"] = $breslin_center;
        $this->rooms["library"] = $lib;
        $this->rooms["union"] = $union;
        $this->rooms["art_museum"] = $art;
        $this->rooms["wharton_center"] = $wharton;
        $this->rooms["spartan_stadium"] = $stadium;
        $this->rooms["engineering_building"] = $engineering;

    }

    public function inRoom($position) {
        return in_array($position, $this->rooms);
    }

    /**
     * @return Room[] the rooms on the board
     */
    public function getRooms() {
        return $this->rooms;
    }

    // number rolled
    private $rolled = array(-1,-1);

    // state the board is in
    private $state;

    // game connection
    private $game;

    // node connection
    private $node;

    // player connection
    private $player;

    //array of cells in board
    private $board;

    //part of building
    private $building_part = [];

    /**
     * @var Room[] collection of the Rooms on the board
     */
    private $rooms;



}