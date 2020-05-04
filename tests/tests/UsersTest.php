<?php


class UsersTest extends \PHPUnit\Framework\TestCase
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
        $users = new \Clue\Users(self::$site);
        $tableName = $users->getTableName();

        $sql = <<<SQL
delete from $tableName;
insert into $tableName(id, email, name, password, salt )
values (7, "dudess@dude.com", "Dudess, The",
        "49506d29656ad62805497b221a6bedacc304ad6496997f17fb39431dd462cf48", "Nohp6^v\$m(`qm#\$o"),
        (8, "cbowen@cse.msu.edu", "Owen, Charles",
        "14831e3f21b423a557a0aa99a391a57a2400ef0fdade328890c9048ad3a8ab6a", "aeLWK6k`jzPpgZMi"),
        (9, "bart@bartman.com", "Simpson, Bart", 
        "a747a49bf74523c1760f649707bf3d2b4a858f088520fd98b35def1e6929ca26", "7xNhdV-8P#\$p)1c9"),
        (10, "marge@bartman.com", "Simpson, Marge",  
        "edfc83ceca3a49aef204cee0e51eeb1728f728c56b2ea9037017230cc39ae938", "!yhLrEo3d8vD_LNV")
SQL;

        self::$site->pdo()->query($sql);
    }

    public function test_pdo() {
        $users = new \Clue\Users(self::$site);
        $this->assertInstanceOf('\PDO', $users->pdo());
    }

    public function test_get() {
        $users = new \Clue\Users(self::$site);

        $user = $users->getIds(7);
        $this->assertInstanceOf('Clue\User', $user);
        $this->assertEquals("dudess@dude.com", $user->getEmail());
        $this->assertEquals("Dudess, The", $user->getName());

        $user = $users->getIds(9);
        $this->assertInstanceOf("Clue\User", $user);

        // Try getting a user that doesn't exist
        $user = $users->getIds(-1);
        $this->assertNull($user);
    }

}