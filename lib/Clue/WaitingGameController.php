<?php


namespace Clue;


class WaitingGameController
{
    private $redirect;	// Page we will redirect the user to.

    public function __construct(Site $site, $post, $user) {
        $gameId = $post['gameid'];
        $users = new Users($site);
        if (isset($post['exit'])) {
            $users->removeGameID($user);
            $this->redirect = "../waitingRoom.php";
        } else {
            $used = $users->randomlySetPlayers($gameId);


            $str = "../index-post.php?id=" . $gameId;
            foreach ($used as $character) {
                $str .= "&$character=1";
            }
            $this->redirect = $str;
        }
    }

    public function getRedirect() {
        return $this->redirect;
    }
}