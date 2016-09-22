<?php

function makeDeck(){
    
    $deck = array();
    for($i = 1; $i < 52; $i++) {
        $deck[] = $i;
    }
    
    shuffle($deck);
    $card = array_pop($deck);
    $c = count($deck);
    echo "$c";
    
}



?>

<!DOCTYPE html>
<html>
    <head>
        <title> Silverjack </title>
    </head>
    <body>

        <?=makeDeck() ?>
        
    </body>
</html>