<?php
$open = true;		// Can be accessed when not logged in
require '../lib/clue.inc.php';

$controller = new Clue\PasswordValidateController($site, $_POST);
header("location: " . $controller->getRedirect());