<?php
	
$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');

$statement = $db->prepare("SELECT link, id FROM sources WHERE flag = 0 ORDER BY id DESC LIMIT 1");
$statement->execute();
$linkresult = $statement->fetchAll();
$db = null;

//print_r($linkresult);


foreach ($linkresult as $data) 
{
	articlelook($data['link']);
		// echo 'http://www.fda.gov/Safety/Recalls/'.$data['link'].'<br>';
		// echo $data['id'].'<br>';
		// $page = file_get_contents('http://www.fda.gov/Safety/Recalls/'.$data['link'].'');
		// echo $page;

		//preg_match("~[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[^0-9]{1}[0-9]{1}~", subject)



}


function articlelook($link)
{
	echo $link;
	$page = file_get_contents('http://www.fda.gov/Safety/Recalls/'.$link);
	
	$jsonstring = file_get_contents("patterns.json");
	$json_a = json_decode($jsonstring, true);

	foreach ($json_a as $value) {
		//regexloop($value,$page);
		//echo $value;
		preg_match_all($value, $page, $out, PREG_SET_ORDER);
		print_r($out);
	}


}
	
function regexloop($value,$page)
{
	
	//echo $page;
	//echo $value;
	preg_match_all($value, $page, $out, PREG_SET_ORDER);
	print_r($out);
}


?>