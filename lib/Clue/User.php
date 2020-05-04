<?php


namespace Clue;


class User
{
    const SESSION_NAME = 'user';
    private $id;		// The internal ID for the user
    private $email;		// Email address
    private $name; 		// Name as last, first
    private $username;
    private $player;
    public function __construct($row) {
        $this->id = $row['id'];
        $this->email = $row['email'];
        $this->name = $row['name'];
        $this->username = $row['username'];
        $this->player = $row['player'];
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    public function getPlayer()
    {
        return $this->player;
    }
    public function getUsername()
    {
        return $this->username;
    }
}