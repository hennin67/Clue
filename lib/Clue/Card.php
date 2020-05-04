<?php


namespace Clue;



class Card
{
    private $name;
    private $special;
    private $image;
    private $alias;

    public function __construct($name, $special, $image)
    {
        $this->name = $name;
        $this->special = $special;
        $this->image = $image;
    }

    /**
     * @return array the information necessary to copy a card
     */
    public function getCopyInfo() {
        return array($this->name, $this->special, $this->image);
    }

    public function getCardImg(){
        return $this->image;
    }

    // set as keyCard
    public function setSpecialTrue(){
        $this->special = true;
    }

    public function getCardName(){
        return $this->name;
    }

    /**
     * @param string the alias to set as this card
     */
    public function setAlias($alias){
        $this->alias = $alias;
    }

    /**
     * @return string the alias
     */
    public function getAlias() {
        ini_set('memory_limit','3072M');
        return $this->alias;
    }
}