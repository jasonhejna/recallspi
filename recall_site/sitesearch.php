<?php



$input = htmlspecialchars($_POST['input']);
$value = file_get_contents('http://localhost/recallspi/recall_site/api.php?apikey=adsfd&thiscall=brandname&input=kellogg');

print_r($value);

?>