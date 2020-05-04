<?php
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/

$open = true;
require '../lib/clue.inc.php';

$controller = new Clue\UserController($site, $_POST);
header("location: " . $controller->getRedirect());
