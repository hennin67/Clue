<?php


class GameTableTest extends \PHPUnit\Framework\TestCase
{
    private static $site;

    public static function setUpBeforeClass() {
        self::$site = new \Clue\Site();
        $localize  = require 'localize.inc.php';
        if(is_callable($localize)) {
            $localize(self::$site);
        }
    }

    protected function setUp() {
        $games = new \Clue\GameTable(self::$site);
        $tableName = $games->getTableName();

        $sql = <<<SQL
delete from $tableName;
insert into $tableName(id, state)
values (7, "");
insert into $tableName(id, state)
values(8, "blahblahblahblahsomestatethatisntnullblahblahblahblah");
insert into $tableName(id, state)
values(9, "");
SQL;

        self::$site->pdo()->query($sql);
    }

    public function testGameStateSave() {
        $player1 = new \Clue\Player("onsay");
        $player2 = new \Clue\Player("owen");

        $game = new \Clue\Game(array($player1, $player2));
        $board = new \Clue\Board($game);
        $game->dealHands();
        $board->setPlayerInitialPositions();

        $games = new \Clue\GameTable(self::$site);
        $json = $games->EncodeGameState($game);

        $this->assertEquals(2, count($game->getPlayers()));

        $state = json_decode($json, true);

        $this->assertEquals($state['currPlayer']['character'], \Clue\Player::ONSAY);

        // Test solution hand
        $solution = $game->getDeck();
        for($i = 0; $i < 3; $i++) {
            $this->assertEquals($solution[$i]->getCardName(), $state['solution'][$i]['name']);
            $this->assertEquals($solution[$i]->getCardImg(), $state['solution'][$i]['image']);
        }

        $players = $game->getPlayers();

        for($i = 0; $i < count($players); $i++) {
            $statePlayer = $state['players'][$i];

            // Test player position
            $position = $statePlayer['position'];

            $playerNode = $players[$i]->getNode();

            $this->assertEquals($playerNode->getPosition()[0], $position[0]);
            $this->assertEquals($playerNode->getPosition()[1], $position[1]);

            // Test player hand cards

            $handCards = $players[$i]->getHand();

            for($j = 0; $j < count($handCards); $j++) {
                $this->assertEquals($handCards[$j]->getCardName() , $statePlayer['heldCards'][$j]['name']);
                $this->assertEquals($handCards[$j]->getCardImg() , $statePlayer['heldCards'][$j]['image']);
            }

            // Test player other cards
            $otherCards = $players[$i]->getOther();

            for ($j = 0; $j < count($otherCards); $j++) {
                $this->assertEquals($otherCards[$j]->getCardName(), $statePlayer['otherCards'][$j]['name']);
                $this->assertEquals($otherCards[$j]->getAlias(), $statePlayer['otherCards'][$j]['alias']);
                $this->assertEquals($otherCards[$j]->getCardImg(), $statePlayer['otherCards'][$j]['image']);
            }

        }
    }

    public function testGetAllPendingGames()
    {
        $games = new \Clue\GameTable(self::$site);
        $pendingGames = $games->GetAllPendingGames();

        $this->assertEquals(2, sizeof($pendingGames));
    }

    public function testCreateNewGame() {
        $games = new \Clue\GameTable(self::$site);

        $prevNum = sizeof($games->getAll());
        $games->createNewGame();
        $newNum = sizeof($games->getAll());

        $this->assertEquals($prevNum + 1, $newNum);
    }

    public function testGameStateSaveLoad() {
        $player1 = new \Clue\Player("onsay");
        $player2 = new \Clue\Player("owen");
        $game1 = new \Clue\Game(array($player1, $player2));
        $board1 = new \Clue\Board($game1);
        $game1->setBoard($board1);
        $game1->dealHands();
        $board1->setPlayerInitialPositions();
        $player1 = $game1->getPlayers()[0];
        $player2 = $game1->getPlayers()[1];

        $games = new \Clue\GameTable(self::$site);

        // Onsay: (17, 0)
        // Owen: (0, 14)

        $gameId = $games->createNewGame();
        $game1->setId($gameId);

        $games->saveGame($game1);

        $game2 = $games->loadGame($gameId);
        $this->assertNotNull($game2);

        $loadedPlayer1 = $game2->getPlayers()[0];
        $loadedPlayer2 = $game2->getPlayers()[1];

        // Test player 1's position:
        $this->assertEquals($player1->getNode()->getPosition(), $loadedPlayer1->getNode()->getPosition());

        // Test player 2's position
        $this->assertEquals($player2->getNode()->getPosition(), $loadedPlayer2->getNode()->getPosition());

        // Test player 1's hand cards
        for ($i = 0; $i < count($player1->getHand()); $i++) {
            $former = $player1->getHand()[$i];
            $loaded = $loadedPlayer1->getHand()[$i];

            $this->assertEquals($former->getCardName(), $loaded->getCardName());
        }

        // test other cards
        for ($j = 0; $j < count($player1->getOther()); $j++) {
            $former = $player1->getOther()[$i];
            $loaded = $loadedPlayer1->getOther()[$i];

            $this->assertEquals($former->getCardName(), $loaded->getCardName());
            $this->assertEquals($former->getAlias(), $loaded->getAlias());
        }

        // Test solution cards
        for ($k = 0; $k < count($game1->getDeck()); $k++) {
            $former = $game1->getDeck()[$k];
            $loaded = $game2->getDeck()[$k];

            $this->assertEquals($former->getCardName(), $loaded->getCardName());
        }

    }

    public function testGetCurrPlayerUserId() {
        $player1 = new \Clue\Player("onsay");
        $player1->setUserId(7);
        $player2 = new \Clue\Player('owen');
        $player2->setUserId(8);

        $game = new \Clue\Game(array($player1, $player2));
        $board = new \Clue\Board($game);
        $game->dealHands();
        $board->setPlayerInitialPositions();

        $games = new \Clue\GameTable(self::$site);

        $gameId = $games->createNewGame();
        $game->setId($gameId);

        $games->saveGame($game);

        $loadedGame = $games->loadGame($gameId);

        $this->assertEquals($player1->getUserId(), $loadedGame->getCurrentPlayer()->getUserId());

        $loadedGame->cycleTurn();
        $this->assertEquals($player2->getUserId(), $loadedGame->getCurrentPlayer()->getUserId());

        $games->saveGame($loadedGame);
        $loadedGame2 = $games->loadGame($gameId);

        $this->assertEquals($player2->getUserId(), $loadedGame2->getCurrentPlayer()->getUserId());
    }

    public function testGetCurrPlayerId() {
        $p1 = new \Clue\Player("owen");
        $p2 = new \Clue\Player('onsay');
        $p1->setUserId(88);
        $p2->setUserId(77);

        $game = new \Clue\Game(array($p1, $p2));
        $board = new \Clue\Board($game);
        $game->setBoard($board);
        $game->dealHands();
        $board->setPlayerInitialPositions();

        $games = new \Clue\GameTable(self::$site);
        $gameid = $games->createNewGame();
        $game->setId($gameid);

        $games->saveGame($game);

        $this->assertEquals($game->getCurrentPlayer(), $p1);
        $games = new \Clue\GameTable(self::$site);

        $currPlayerId = $games->getCurrentPlayerId($gameid);

        $this->assertEquals($p1->getUserId(), $currPlayerId);
    }
}