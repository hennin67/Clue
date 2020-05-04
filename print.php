<?php
require __DIR__ . "/lib/clue.inc.php";

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Printing Cards</title>
    <link href="lib/game.css" type="text/css" rel="stylesheet">
</head>

<body id="print">
<?php
$redirect = "print-post.php?id=" . $_GET['id'];
$str = "<form class=\"no-print\" method=\"POST\" action=\"$redirect\">";
echo $str;
?>
    <div class="printing">
        <?php
        $name = $game->getCurrentPlayer()->getCharacter();
        echo "<p class='professor'>Cards for Prof. $name</p>";
        ?>
        <p class="printingButtons"><input type="submit" name="submitbutton" onclick="window.print();return false;" value="Print">

            <input type="submit" value="Next" class="professor">
        </p>
    </div>
</form>

<div class="print-only">
    <?php
        $name = $game->getCurrentPlayer()->getCharacter();
        echo "<p class=\"professor\">Cards for Prof. $name</p>";
        echo "<p class=\"category\">Held Cards</p>";
        $MainCard = $game->getCurrentPlayer()->getHand();
        $OtherCard = $game->getCurrentPlayer()->getOther();
        foreach ($MainCard as $card){
            $Img = $card->getCardImg();
            echo "<img src=\"images/$Img\" alt=\"Image of card\" width=\"96.3&quot;\" height=\"150\">";
        }
        echo "<p class=\"category\">Other Cards</p>";

        foreach ($OtherCard as $other){
            $img = $other->getCardImg();
            $otherName = $other->getAlias();
            echo " <figure class=\"item\"><img src=\"images/$img\" alt=\"Image of card\" width=\"96.3\" height=\"150\"><figcaption class=\"caption\">$otherName</figcaption></figure>";

        }

    ?>
</div>

</body>
</html>

