<?php


namespace Clue;

class Users extends Table {

    public function __construct(Site $site) {
        parent::__construct($site, "user");
    }

    public function login($email, $password) {
        $sql =<<<SQL
SELECT * from clue_user
where email=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute([$email]);
        if($statement->rowCount() === 0) {
            return null;
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        // Get the encrypted password and salt from the record
        $hash = $row['password'];
        $salt = $row['salt'];

        // Ensure it is correct
        if($hash !== hash("sha256", $password . $salt)) {
            return null;
        }

        return new User($row);
    }

    public function exists($email) {
        $sql = <<<SQL
select id from $this->tableName
where email=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($email));
        return $statement->rowCount() === 1;

    }

    public function add(User $user, Email $mailer) {
        // Ensure we have no duplicate email address
        if($this->exists($user->getEmail())) {
            return "Email address already exists.";
        }

        // Add a record to the user table
        $sql = <<<SQL
INSERT INTO clue_user (username, email, name)
values(?, ?, ?)
SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute([$user->getUsername(), $user->getEmail(), $user->getName()]);
        $id = $this->pdo()->lastInsertId();

        // Create a validator and add to the validator table
        $validators = new Validators($this->site);
        $validator = $validators->newValidator($id);

        // Send email with the validator in it
        $link = "http://webdev.cse.msu.edu"  . $this->site->getRoot() .
            '/password-validate.php?x&v=' . $validator;

        $from = $this->site->getEmail();
        $name = $user->getName();
        $player = $user->getPlayer();
        $subject = "Confirm your email";
        $message = <<<MSG
<html>
<p>Greetings, $name,</p>

<p>Welcome to Clue. In order to complete your registration,
please verify your email address by visiting the following link:</p>

<p><a href="$link">$link</a></p>
</html>
MSG;
        $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso=8859-1\r\nFrom: $from\r\n";
        $mailer->mail($user->getEmail(), $subject, $message, $headers);
    }

    public function setPassword($userid, $password) {
        $sql =<<<SQL
UPDATE clue_user
SET password=?, salt=?
WHERE id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $salt = self::randomSalt();
        $password = hash("sha256", $password . $salt);
        $statement->execute(array($password, $salt, $userid));
    }

    public static function randomSalt($len = 16) {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }

    /**
     * Get a user based on the id
     * @param $id ID of the user
     * @return User object if successful, null otherwise.
     */
    public function get($id) {
        $sql =<<<SQL
SELECT * from clue_user
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($id));
        if($statement->rowCount() === 0) {
            return null;
        }

        return new User($statement->fetch(\PDO::FETCH_ASSOC));
    }

    public function getUsersPlaying($gameid) {
        $sql = <<<SQL
SELECT username FROM clue_user
WHERE gameid=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($gameid));

        if($statement->rowCount() === 0) {
            return [];
        }

        $row = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $arr = [];

//        $players = ['owen', 'mccullen', 'onsay', 'embody', 'plum', 'day'];
        for ($i = 0; $i < count($row); $i++) {
//            $row[$i]['username']->setPlayer(new Player($players[$i]));
            $arr[] = $row[$i]['username'];
        }

        return $arr;
    }

    public function getGameSession($id) {
        $sql = <<<SQL
SELECT gameid FROM clue_user
WHERE id=?
SQL;
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($id));

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        $gameid = $row['gameid'];

        if ($gameid === null) return "no game";

        $sql = <<<SQL
SELECT state FROM clue_user
WHERE id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($gameid));

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        $state = $row['state'];

        if ($state === null) return "new game, $gameid";

        return "playing game";
    }

    public function removeGameID($user) {
        $sql = <<<SQL
UPDATE clue_user
SET gameid = NULL
WHERE id=?
SQL;
        $id = $user->getId();
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($id));
    }

    public function saveGameID($gameid, $user) {
        $sql = <<<SQL
UPDATE clue_user
SET gameid=?
WHERE id=?
SQL;
        $id = $user->getId();
        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($gameid, $id));
    }

    public function randomlySetPlayers($gameid) {
        $chars = ['Owen', 'McCullen', 'Onsay', 'Enbody', 'Plum', 'Day'];
        shuffle($chars);

        $sql = <<<SQL
SELECT id FROM clue_user
WHERE gameid=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(array($gameid));
        $row = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $players = [];
        for ($i = 0; $i < count($row); $i++) {
            $players[] = $row[$i]['id'];
        }

        $used = [];
        for ($i = 0; $i < count($players); $i++) {
            $sql = <<<SQL
UPDATE clue_user
SET player=?
WHERE id=?
SQL;

            $pdo = $this->pdo();
            $statement = $pdo->prepare($sql);
            $statement->execute(array($chars[$i],$players[$i]));
            $used[] = $chars[$i];
        }

        return $used;
    }

    public function getGameUserIds($gameId) {
        $sql = <<<SQL
select id from $this->tableName
where gameid = ?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($gameId));

        if ($statement->rowCount() === 0) {
            return null;
        }

        $ids = array();

        foreach($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $ids[] = $row['id'];
        }

        return $ids;

    }

    /**
     * Get a user based on the id
     * @param $id ID of the user
     * @return User object if successful, null otherwise.
     * !!! USED ONLY FOR TESTING !!!
     */
    public function getIds($id) {
        $sql =<<<SQL
SELECT * from $this->tableName 
where id=?
SQL;

        $pdo = $this->pdo();
        $statement = $pdo->prepare($sql);

        $statement->execute(array($id));
        if($statement->rowCount() === 0) {
            return null;
        }

        return new User($statement->fetch(\PDO::FETCH_ASSOC));
    }


}