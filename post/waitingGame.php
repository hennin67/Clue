<?php
require '../lib/clue.inc.php';

$controller = new Clue\WaitingGameController($site, $_POST, $user);
header("location: " . $controller->getRedirect());
