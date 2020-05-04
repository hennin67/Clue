<?php


namespace Clue;


class Deck
{
    //array where 3 key cards will be stored
    private $keyArray;

    /**
     * @var Card[] the cards in this deck
     */
    private $deck;



    public function __construct()
    {
        $this->populateCards();
    }

    public function populateCards(){
        $this->keyArray = array();
        $this->deck = array();

        $arrsuspect= array();

        $card1 = new SuspectCard("Owen",false, "owen.jpg");
        $card2 = new SuspectCard("McCullen",false, "mccullen.jpg");
        $card3 = new SuspectCard("Onsay",false, "onsay.jpg");
        $card4 = new SuspectCard("Enbody",false, "enbody.jpg");
        $card5 = new SuspectCard("Plum",false, "plum.jpg");
        $card6 = new SuspectCard("Day",false, "day.jpg");
        array_push($arrsuspect, $card1, $card2, $card3, $card4, $card5, $card6);

        //randomize order of array
        shuffle($arrsuspect);

        //select key card & add to key array
        $keySuspect = $arrsuspect[0];
        $keySuspect->setSpecialTrue();
        array_push($this->keyArray, $keySuspect);

        //add remaining elements to remaining array
        foreach ($arrsuspect as $card){
            array_push($this->deck, $card);
        }


        $arrweapon= array();

        $card7 = new WeaponCard("Final Exam",false, "final.jpg");
        $card8 = new WeaponCard("Programming Assignment",false, "programming.jpg");
        $card9 = new WeaponCard("Written Assignment",false, "written.jpg");
        $card10 = new WeaponCard("Project",false, "project.jpg");
        $card11 = new WeaponCard("Quiz",false, "quiz.jpg");
        $card12 = new WeaponCard("Midterm Exam",false, "midterm.jpg");
        array_push($arrweapon, $card7, $card8, $card9, $card10, $card11, $card12);
        //randomize order of array
        shuffle($arrweapon);

        //select key card & add to key array
        $keyWeapon = $arrweapon[0];
        $keyWeapon->setSpecialTrue();
        array_push($this->keyArray, $keyWeapon);

        //add remaining elements to remaining array
        foreach ($arrweapon as $card){
            array_push($this->deck, $card);
        }

        $arrlocation= array();

        $card13 = new LocationCard("International Center",false, "international.jpg");
        $card14 = new LocationCard("Breslin Center",false, "breslin.jpg");
        $card15 = new LocationCard("Beaumont Tower",false,"beaumont.jpg");
        $card16 = new LocationCard("University Union",false, "union.jpg");
        $card17 = new LocationCard("Art Museum",false, "museum.jpg");
        $card18 = new LocationCard("Library",false, "library.jpg");
        $card19 = new LocationCard("Wharton Center",false, "wharton.jpg");
        $card20 = new LocationCard("Spartan Stadium",false, "stadium.jpg");
        $card21 = new LocationCard("Engineering Building",false, "engineering.jpg");

        array_push($arrlocation, $card13, $card14, $card15, $card16,
            $card17, $card18, $card19,$card20, $card21);
        //randomize order of array
        shuffle($arrlocation);

        //select key card & add to key array
        $keyLocation = $arrlocation[0];
        $keyLocation->setSpecialTrue();
        array_push($this->keyArray, $keyLocation);

        //add remaining elements to remaining array
        foreach ($arrlocation as $card) {
            array_push($this->deck, $card);
        }

        //SHUFFLE REMAINING CARDS IN DECK (INCLUDING SPECIAL CARDS)
        shuffle($this->deck);

    }

    public function getCards(){
        //gets cards including the key cards (already shuffled)
        return $this->deck;
    }

    public function getSpecialCards(){
        //gets 3 special cards
        return $this->keyArray;
    }

    //for testing purposes to see if all 21 cards were added
    public function countDeck(){
        return count($this->deck);
    }

    public function getDuplicateCardCopy() {
        $cards = [];
        foreach($this->deck as $card) {
            $info = $card->getCopyInfo();
            $new = new Card($info[0], $info[1], $info[2]);
            $cards[] = $new;
        }

        return $cards;
    }

    /**
     * @param $card Card the card to remove from this->deck
     */
    public function removeCard($card) {
        $position = array_search($card, $this->deck);
        unset($this->deck[$position]);
        $this->deck = array_values($this->deck);
    }


}