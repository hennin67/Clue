<?php
require __DIR__ . '/lib/clue.inc.php';
$open = true;
$view = new Clue\RegisterView($site);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Clue Register</title>
    <link href="lib/scss/game.css" type="text/css" rel="stylesheet">
</head>

<body id="welcome">
<?php echo $view->present(); ?>

</body>
</html>
