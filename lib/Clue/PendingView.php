<?php


namespace Clue;

//TODO: figure out the push key stuff

class PendingView
{
    /**
     * PendingView constructor.
     * @param $site Site the site object
     * @param $gameID int the id of the game
     * @param $user User the session array
     */
    public function __construct($site, $gameID, $user) {
        $this->site = $site;
        $this->gameID = $gameID;

        $games = new GameTable($site);
        $currId = $games->getCurrentPlayerId($gameID);

        // if the current logged in user's id is the id of the current player whose turn it is
        if ($user->getId() === $currId) {
            $this->isRedirect = true;
            $redirect = "post/pending.php?id=" . $gameID;
            $this->redirect = $redirect;
        }
    }

    public function isRedirect() {
        return $this->isRedirect;
    }

    public function getRedirect() {
        return $this->redirect;
    }

    public function presentScript() {
        $key = PUSH_KEY_PREFIX . +$this->gameID;

        $script = <<<HTML
<script>
    /**
     * Initialize monitoring for a server push command.
     * @param key Key we will receive.
     */
    function pushInit(key) {
        var conn = new WebSocket('ws://webdev.cse.msu.edu/ws');
        conn.onopen = function (e) {
            console.log("Connection to push established!");
            conn.send(key);
        };

        conn.onmessage = function (e) {
            try {
                var msg = JSON.parse(e.data);
                if (msg.cmd === "reload") {
                    location.reload();
                }
            } catch (e) {
            }
        };
    }

    pushInit("$key");
</script>
HTML;

        return $script;
    }

    public function present() {
        $html = <<<HTML
<h1>Waiting for your turn...</h1>
HTML;

        return $html;
    }

    private $site;
    private $gameID;
    private $isRedirect = false;
    private $redirect;

}