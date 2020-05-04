<?php


namespace Clue;

class LoginController
{
    private $redirect;	// Page we will redirect the user to.

    public function __construct(Site $site, array &$session, array $post) {
        // Create a Users object to access the table
        $users = new Users($site);

        $email = strip_tags($post['email']);
        $password = strip_tags($post['password']);
        $user = $users->login($email, $password);
        $session[User::SESSION_NAME] = $user;

        //$result = $users->getGameSession($user->getId());

        if($user === null) {
            // Login failed
            $this->redirect = "../login.php?e";
        } else if ($users->getGameSession($user->getId()) === "no game") {
            $this->redirect = "../waitingRoom.php";
        }  else if (explode(", ", $users->getGameSession($user->getId()))[0] === "new game") {
            $gameid = explode(", ", $users->getGameSession($user->getId()))[1];
            $games = new GameTable($site);

            $rows = $games->getAllPendingGames();
            $objs = [];
            foreach ($rows as $row){
                $objs[] = $row["id"];
            }
            if (in_array($gameid,$objs)) {
                $this->redirect = "../waitingGame.php?id=$gameid";
            } else {
                $this->redirect = "pending.php?id=$gameid";
            }
        } else {
            $this->redirect = "../login.php?help";
        }
    }

    public function getRedirect() {
        return $this->redirect;
    }

}