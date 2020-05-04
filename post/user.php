<?php
require '../lib/clue.inc.php';
$controller = new Clue\UserController($site, $_POST);
header("location: " . $controller->getRedirect());
