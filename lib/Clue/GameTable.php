<?php


namespace Clue;


class GameTable extends Table
{
    public function __construct(Site $site)
    {
        parent::__construct($site, "game");
    }

    /**
     * Serialize the param game into it's current state
     * @param $game Game the game to generate the JSON serial for
     * @return string JSON encoding
     */
    public function EncodeGameState($game) {
    /*
    <?php
    $myObj->name->first = "Goku";
    $myObj->name->last = "Smith";
    $myObj->foods = ["chinese","american","ethiopian"];
    $JSON = json_encode($myObj);
    echo $JSON;

    ?>*/

    /// WHAT WE WILL NEED ///
    //who is playing?
    //where are the pieces? (location)
    //what cards are currently in the player's hand?

        $state = (object) [];

        // Save current player
        $currPlayer = $game->getCurrentPlayer();

        $state->currPlayer = (object) [];
        $state->currPlayer->character = $currPlayer->getCharacter();
        $state->currPlayer->id = $currPlayer->getUserId();

        // Save solution hand
        $solution = $game->getDeck();
        $state->solution = array();

        foreach($solution as $solutionCard) {
            $solCard = (object)[];
            $solCard->name = $solutionCard->getCardName();
            $solCard->image = $solutionCard->getCardImg();
            $state->solution[] = $solCard;
        }

        // Save players' positions / cards
        $players = $game->getPlayers();
        $state->players = array();

        foreach($players as $playerObj) {
            $player = (object) [];

            // Position
            $player->character = $playerObj->getCharacter();
            $player->id = $playerObj->getUserId();
            $currNode = $playerObj->getNode();
            $player->position = array($currNode->getPosition()[0], $currNode->getPosition()[1]);

            // Held cards
            $player->heldCards = array();

            $heldCards = $playerObj->getHand();
            foreach($heldCards as $card) {
                $heldCard = (object)[];
                $heldCard->name = $card->getCardName();
                $heldCard->image = $card->getCardImg();
                $player->heldCards[] = $heldCard;
            }

            // Other cards
            $otherCards = $playerObj->getOther();

            $player->otherCards = array();

            foreach($otherCards as $otherCard) {
                $other = (object) [];

                $other->name = $otherCard->getCardName();
                $other->alias = $otherCard->getAlias();
                $other->image = $otherCard->getCardImg();

                $player->otherCards[] = $other;
            }

            $state->players[] = $player;
        }

        // This might not work..?
        return json_encode($state);
    }

    /**
     * Creates a new row in the game table & returns the new id
     * @return int the id of the game that was created
     */
    public function createNewGame() {
        $sql = <<<SQL
insert into $this->tableName()
values ()
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute();

        return $pdo->lastInsertId();
    }

    public function getAll() {
        $sql = <<<SQL
select * from $this->tableName
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function deleteGameID($gameid) {
        $sql = <<<SQL
update clue_user
set gameid=null
where gameid=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($gameid));

        $sql = <<<SQL
delete from clue_game
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($gameid));

    }

    /**
     * tests to see if a game exists in the game table
     * @param $gameId int the gameId
     */
    public function exists($gameId) {
        $sql = <<<SQL
select * from $this->tableName
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($gameId));

        if ($statement->rowCount() === 0) {
            return false;
        }

        return true;



    }

    /**
     * Get all games from the Database that haven't yet been started
     */
    public function GetAllPendingGames() {
        $sql = <<<SQL
select id from $this->tableName
where state=""
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function DeleteEmptyGames() {
        $sql = <<<SQL
delete from clue_game
where id not in 
(select gameid from clue_user
where gameid is not null
group by gameid )
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute();
    }

    /**
     * @param $game Game the game object to save
     */
    public function saveGame($game) {
        $gameId = $game->getId();
        $json = $this->EncodeGameState($game);

        $sql = <<<SQL
update $this->tableName
set state=?
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($json, $gameId));
    }

    /**
     * @param $gameId int the gameId of the game to check the player of
     * @return int the id of the user that's turn it is
     */
    public function getCurrentPlayerId($gameId) {
        $sql = <<<SQL
select state from $this->tableName
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($gameId));

        if ($statement->rowCount() === 0) {
            return null;
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        $state = json_decode($row['state'], true);

        return $state['currPlayer']['id'];
    }

    /**
     * @param $id int the ID of the game to load
     * @return Game the game object matching the ID
     */
    public function loadGame($id) {
        $sql = <<<SQL
select * from $this->tableName
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($id));

        if($statement->rowCount() === 0) {
            return null;
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($statement->rowCount() === 0) {
            return null;
        }

        $state = json_decode($row['state'], true);

        // Populate players
        $players = array();
        foreach($state['players'] as $player) {
            $newPlayer = new Player($player['character']);
            $newPlayer->setUserId($player['id']);

            // populate held cards
            $heldCards = array();
            foreach($player['heldCards'] as $heldCard) {
                $card = new Card($heldCard['name'], false, $heldCard['image']);
                $heldCards[] = $card;
            }
            $newPlayer->setHandCards($heldCards);

            // populate other cards
            $otherCards = array();
            foreach($player['otherCards'] as $otherCard) {
                $other = new Card($otherCard['name'], false, $otherCard['image']);
                $other->setAlias($otherCard['alias']);
                $otherCards[] = $other;
            }
            $newPlayer->setOtherCards($otherCards);

            $players[] = $newPlayer;
        }

        // Create game / board
        $game = new Game($players);
        $board = new Board($game);
        $game->setBoard($board);

        // Set player's positions
        for($i = 0; $i < count($state['players']); $i++) {
            $statePlayer = $state['players'][$i];
            $game->getPlayers()[$i]->setNode($board->getNode($statePlayer['position'][0], $statePlayer['position'][1]));
        }

        // Set solution
        $solution = array();
        foreach($state['solution'] as $solutionCard) {
            $solCard = new Card($solutionCard['name'], true, $solutionCard['image']);
            $solution[] = $solCard;
        }

        $game->setId($id);
        $game->setCurrentPlayer($state['currPlayer']['id']);
        $game->setSolution($solution);

        return $game;
    }
}