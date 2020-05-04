<?php


namespace Clue;


class IndexController
{
    private $redirect;	// Page we will redirect the user to.

    public function __construct(Site $site, $user) {
        $users = new Users($site);
        $redirect = null;
        if($user === null) {
            $this->redirect = "./login.php";
        } else if ($users->getGameSession($user->getId()) === "no game") {
            $this->redirect = "./waitingRoom.php";
        }  else if (explode(", ", $users->getGameSession($user->getId()))[0] === "new game") {
            $gameid = explode(", ", $users->getGameSession($user->getId()))[1];
            $games = new GameTable($site);

            $rows = $games->getAllPendingGames();
            $objs = [];
            foreach ($rows as $row){
                $objs[] = $row["id"];
            }
            if (in_array($gameid,$objs)) {
                $this->redirect = "waitingGame.php?id=$gameid";
            } else {
                $this->redirect = "pending.php?id=$gameid";
            }
        }
    }

    public function getRedirect() {
        return $this->redirect;
    }
}