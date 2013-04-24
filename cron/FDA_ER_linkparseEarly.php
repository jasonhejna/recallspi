<?php
set_time_limit(3600);
error_reporting(E_ALL);
include('simple_html_dom.php');
$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');

$statement = $db->prepare("SELECT link, id FROM FDA_enforcementlinks WHERE flag = 0 ORDER BY id ASC LIMIT 1");
$statement->execute();
$linkresult = $statement->fetchAll();
$db = null;

//print_r($linkresult[0]);

foreach ($linkresult as $data) 
{
	//echo 'http://www.fda.gov/Safety/Recalls/EnforcementReports/'.$data['link'].'.htm';
	//echo $data['id'];
	$page = file_get_contents('http://www.fda.gov/Safety/Recalls/EnforcementReports/'.$data['link']);
	//print_r($page);
	preg_match_all('~<h2>(.*)</h2>[^k]{90}~', $page, $matches);

	$i=0;

	foreach ($matches[0] as $key => $value) {
		echo $value.'<br><br>';

	}




}


?>