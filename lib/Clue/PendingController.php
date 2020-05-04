<?php
/**
 * Created by PhpStorm.
 * User: jwcho
 * Date: 4/17/2020
 * Time: 3:42 PM
 */


namespace Clue;


class PendingController
{
    private $redirect;	// Page we will redirect the user to.

    public function __construct(Site $site, array &$session, array $post) {
        $this->redirect = "../pending.php";
    }

    public function getRedirect() {
        return $this->redirect;
    }
}