<?php


namespace Clue;

class Validators extends Table {

    public function __construct(Site $site) {
        parent::__construct($site, "validator");
    }

    public function createValidator($len = 32) {
        $bytes = openssl_random_pseudo_bytes($len / 2);
        return bin2hex($bytes);
    }

    public function newValidator($id) {
        $validator = $this->createValidator();

        $sql = <<<SQL
INSERT INTO clue_validator (validator, clue_userid)
values(?, ?)
SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute([$validator, $id]);

        return $validator;
    }

    public function get($validator) {
        $sql = <<<SQL
select clue_userid from clue_validator
where validator=?
SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(array($validator));

        if($statement->rowCount() === 0) {
            return null;
        }
        $rows = $statement->fetchAll();

        return $rows[0][0];
    }

    public function remove($userid) {
        $sql = <<<SQL
delete from clue_validator
where clue_userid=?
SQL;

        $statement = $this->pdo()->prepare($sql);
        $statement->execute(array($userid));
    }

}