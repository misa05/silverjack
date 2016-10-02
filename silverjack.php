<?php
function playAgain()
{  
    if (isset($_GET['submitForm'])) 
    {
        return "https://cst336--agui3668.c9users.io/labs/lab3/silver.php";
    }
 
}


    //randomly chooses a card out of deck of 52
    //chooses random integer for the index which is the
    //value of the card
    //then chooses random indeger for the suit. To get the card value
    //multiply the suit value by 13 then add the index value
    //passes the users card deck in by reference in order to update which card
    //was selected
    function displayRandomCard(&$array,&$deck){
        //deck of the cards from 1..52
        $suits = array("clubs", "diamonds", "spades", "hearts");
    
        $randSuite = rand(0,3); //random suit integer
        $randIndex = rand(1,13); //random index integer(value)
        $randNumber = ($randSuite * 13) + $randIndex; //gets the card value in deck
        
        /*
        If the value selected at random is already in the users deck then it keeps
        selecting new cards unntil it gets a card that isnt already in the deck
        */
        while (sequentialSearch($array,$randNumber) || sequentialSearch($deck,$randNumber)){
            $randSuite = rand(0,3);
            $randIndex = rand(1,13);
            $randNumber = ($randSuite * 13) + $randIndex;
        }
        
        //creates the image with the given card value
        echo "<img src='img/cards/$suits[$randSuite]/" . $randIndex. ".png' />";
        
        //adds the card value to the users deck to update it
        $array[] = $randNumber;
        $deck[] = $randNumber;
    }
    
    //picks a random index of the players array to display their cards
     function getPlayerByRamdomIndex(&$hasBeenDisplayed,&$index){
        global $benCards,$cruzCards,$misaelCards,$player4Cards;
        $players = array($benCards,$cruzCards,$misaelCards,$player4Cards);
        $randIndex = rand(0,3);
        while (sequentialSearch($hasBeenDisplayed,$randIndex)){
            $randIndex = rand(0,3);
        }
        $hasBeenDisplayed[] = $randIndex;
        $index = $randIndex;
        return $players[$randIndex];
    }
    
    
    //checks if a given element is already in an array of hands. If it is
    //it returns true else returns false
    function sequentialSearch($array,$element){
        for ($i = 0; $i < count($array);$i++){
            
            //if card is already in deck returns true
            if ($array[$i] == $element){
                return true;
            }
        }
        
        return false;
    }
    
    
    /*Takes in the value of a card in the users deck to determine the value of it corrsponding
    to the value in the 52 deck*/
    function getCardValue($card){
    
        //special case Jack
        if ($card % 13 == 11){
            return 11;
        }
        //special case queen
        else if ($card % 13 == 12){
            return 12;
        }
        //special case king
        else if ($card % 13 == 0 && $card != 0){
            return 13;
        }
        //all other cards
        else{
            return $card % 13;
        }
    
    }
    
    //calculates the total card value of all the cards in a users deck
    function deckSum($array){
        
        if (count($array) < 1){
            return 0;
        }
        else{
            $sum = 0;
            
            for ($i = 0; $i < count($array);$i++){
                $sum += getCardValue($array[$i]);
            }
            
        return $sum;
        }
    }
    
 
    
    /*
    Returns array of the scores for the winning player
    Takes in array of the names of the winners
    adds the sum of the score of every othe player besides the winner
    and returns the sum in an array in case there are multiple winners
    */
    function getWinningSum($winner,$score1, $score2,$score3,$score4){
        
        //if all players busted fill sum with zero and return
        if ($winner[0] == "Everyone Busted! No winner!"){
            $sums = array();
            $sums[] = 0;
            return $sums;
        }
        else{
            $scores = array();
            $scores[] = $score1;
            $scores[] = $score2;
            $scores[] = $score3;
            $scores[] = $score4;
            $players = array("Ben","Cruz","Misael","Mario");
            $sums = array();
            
            for ($i = 0; $i < count($winner);$i++){
                for ($j = 0; $j < count($scores);$j++){
                    if ($winner[$i] != $players[$j]){
                        $sums[$i] += $scores[$j];
                    }
                }
            }
            
            return $sums;
        }
    }
    
    
    /*
    Finds the value of the winning score
    If all players busted prints that there is no winner
    else returns array with names of all the winners
    */
    function getWinners($score1, $score2,$score3,$score4,&$winners){
        
        $high_score = getHighScore($score1, $score2,$score3,$score4);
        
        //SPECIAL CASE: ALL PLAYERS BUSTED
        if ($high_score == -1){
            $winners[] = "Everyone Busted! No winner!";
        }
        //every other case
        else{
            $players = array("Ben","Cruz","Misael","Mario");
            $scores = array();
            
            $scores[] = $score1;
            $scores[] = $score2;
            $scores[] = $score3;
            $scores[] = $score4;
            
            for ($i = 0; $i < count($players);$i++){
                if ($scores[$i] ==$high_score){
                    $winners[] = $players[$i];
                }
            }
            
        }
    }
    
    
    
    /*
    Takes in every player score and determines winning score
    Sequentailly searches every score exluding those over 42 to find highest
    If every players score is over 42 ('Busted') return -1
    else it returns the highest score
    */
    function getHighScore($score1, $score2,$score3,$score4){
        
        //puts scores in array for easy searching
        $scores = array();
        $scores[] = $score1;
        $scores[] = $score2;
        $scores[] = $score3;
        $scores[] = $score4;
        $players = array("Ben","Cruz","Misael","Mario");
        $high_score = 0;
        $allBusted = true;
        
        //checks for special case if all players scores are over 42
        //if this happens all players bust and returns -1
        for ($i = 0; $i < count($scores);$i++){
            if ($scores[$i] <= 42){
                $allBusted = false;
            }
        }
        
        if ($allBusted){
            return -1;
        }
        else{
        
            //finds highest score
            for ($i = 0; $i < count($scores);$i++){
                if (($scores[$i] > $high_score) && ($scores[$i] <= 42)){
                    $high_score = $scores[$i];
                }
            }
            
            return $high_score;
        }
    }
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title> </title>
        <link rel="stylesheet" href="css/lab3.css" type="text/css" />
    </head>
    <body>
        <?php
        
        //deck holds every card that was used
        $deck = array();
        
        //all players name to use with scores
        $players = array("Ben","Cruz","Misael","Mario");
        
        //holds player scores
        $scores = array();
        
        //decks to hold players pictures and cards
        $benCards = array();
        $cruzCards = array();
        $misaelCards = array();
        $player4Cards = array();
        
        //sets the picture for the users cards
        $benCards[0] = "players/ben.png";
        $cruzCards[0] = "players/eddy.jpg";
        $misaelCards[0] = "players/mi.png";
        $player4Cards[0] = "players/mario.png";
        
        
        echo "<main>";
        //displays all players cardss
        echo "<h1> Silver Jack </h1>";
        for ($j = 0; $j < 4;$j++){
            echo "<table>";
            $tempIndex = 0;
            $tempArr = getPlayerByRamdomIndex($hasBeenDisplayed,$tempIndex);
            echo "<tr><td> <img src='img/$tempArr[0]'/>";
            $scores[$tempIndex] = 0;
            
            for ($i = 0; deckSum($tempArr) < 42 ;$i++){
                if (abs(42 - deckSum($tempArr) < 7)){
                    break;
                }
                displayRandomCard($tempArr,$deck);
                $scores[$tempIndex] = deckSum($tempArr);
              
            }
            
            echo "<text>".$players[$tempIndex] . "  " . $scores[$tempIndex]. "</text>";
            echo "<br/>";
            $tempIndex = 0;
            echo "</tr></td>";
            echo "</table>";
            
        }   
        
        //these fuctions get the winner of the game stored inside of winners and sums;
        $winners = array();
        getWinners($scores[0],$scores[1],$scores[2],$scores[3],$winners);
        $sums = getWinningSum($winners,$scores[0],$scores[1],$scores[2],$scores[3]);
        echo "<h2 id=winner style=color:#290a6b;>$winners[0] Wins! $sums[0] points</h2>";
        echo "<br/>";
        ?>
        
        <form method="GET">
         <input type="submit" name="submitForm" value="Play Again"/>
        </form>
        <br />
        <img src="../../img/csumb-logo.png" />
         </main>
        
        </div>
        <br />
        <footer>
            &copy; Aguilar, Cruz, Dagg, 2016. <br/> Disclamer; The information on this page might not be accurate. It is used for academic purposes.
            <br/>
            
        </footer>
    </body>
</html>