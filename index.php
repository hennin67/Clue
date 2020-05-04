<?php
require 'lib/clue.inc.php';

$controller = new Clue\IndexController($site, $user);
header("location: " . $controller->getRedirect());
?>
<!doctype html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Who Murdered My Grade?</title>
        <link href="lib/scss/partials/_print.scss" type="text/css" rel="stylesheet">
    </head>

<body>
<h1>Team Wabaningo<br>Team Members: Alex Black, Becky Henning, Dean Dawson, Aihong Chen, Tia Fowlkes, Tom Choi</h1>
<form method="post" action="index-post.php">
    <div class="players">
        <p><input type="checkbox" name="Owen" id="Owen">
            <label for="Owen">Prof. Owen</label>
        </p>
        <p><input type="checkbox" name="McCullen" id="McCullen">
            <label for="McCullen">Prof. McCullen</label>
        </p>
        <p><input type="checkbox" name="Onsay" id="Onsay">
            <label for="Onsay">Prof. Onsay</label>
        </p>
        <p><input type="checkbox" name="Enbody" id="Enbody">
            <label for="Enbody">Prof. Enbody</label>
        </p>
        <p><input type="checkbox" name="Plum" id="Plum">
            <label for="Plum">Prof. Plum</label>
        </p>
        <p><input type="checkbox" name="Day" id="Day">
            <label for="Day">Prof. Day</label>
        </p>
        <p>Select at least 2 players to play the game.</p>
        <p><a href="instruction.php">Instructions</a></p>
        <p><a href="tests/index.php">Test Page</a></p>
        <p><input type="submit" value="Submit"></p>
    </div>
</form>

</body>