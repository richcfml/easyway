<?php
echo(replaceBhSpecialChars("Bianco D'Oro® Salame   French Gruyère Cheese"));

function replaceBhSpecialChars($pDescription)
{
    $pDescription = str_replace("'", "&#39;", $pDescription);
    $pDescription = str_replace("®", "&#174;", $pDescription);
    $pDescription = str_replace("ä", "&#228;", $pDescription);
    $pDescription = str_replace("è", "&#232;", $pDescription);
    $pDescription = str_replace("ñ", "&#241;", $pDescription);
    $pDescription = str_replace(" ", " ", $pDescription);
    
    return $pDescription;
}
?>