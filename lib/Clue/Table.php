<?php


namespace Clue;

class Table {

    public function __construct(Site $site, $name) {
        $this->site = $site;
        $this->tableName = $site->getTablePrefix() . $name;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function sub_sql($query, $params) {
        $keys = array();
        $values = array();

        // build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_numeric($value)) {
                $values[] = intval($value);
            } else {
                $values[] = '"' . $value . '"';
            }
        }

        $query = preg_replace($keys, $values, $query, 1, $count);
        return $query;
    }

    public function pdo() {
        return $this->site->pdo();
    }

    protected $site;        // The Site object
    protected $tableName;   // The table name to use
}