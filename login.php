<?php
$open = true;
require 'lib/clue.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Clue Login</title>
    <link href="lib/game.css" type="text/css" rel="stylesheet">
</head>

<body id="welcome">
<header>
    <h1> Who Murdered My Grade? </h1>
</header>

<div class="login">
    <form method="post" action="post/login.php">
        <fieldset>
            <legend>Login</legend>
            <p><?php if (isset($_GET['e'])) echo "Invalid login credentials" ?></p>
            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" placeholder="Email">
            </p>
            <p>
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" placeholder="Password">
            </p>
            <p>
                <input type="submit" value="Log in">
            </p>
            <p><a href="register.php?x">Register with Clue</a></p>
        </fieldset>
    </form>
</div>

</body>
</html>
