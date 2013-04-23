<?php
set_time_limit(3600);
error_reporting(E_ALL);
$file = file_get_contents('http://www.fda.gov/Safety/Recalls/EnforcementReports/default.htm');
//print_r($file);
preg_match_all('~<li>(.*)<a href="http://www.accessdata.fda.gov/scripts/enforcement/enforce_rpt-Product-Tabs.cfm?(.*)Enforcement Report~', $file, $matches);
//print_r($matches);
foreach ($matches[0] as $key => $value) {
	//echo $value.'<br>';
	preg_match('~http://www.accessdata.fda.gov/scripts/enforcement/enforce_rpt-Product-Tabs.cfm?(.*)">~', $value, $link);
	//print_r($link);
	echo $link[1];
	echo '<br>';

//get time off the front of the matched string
	preg_match('~[0-9]{8}~', $link[1], $date);
	//print_r($date[1]);
	//echo '<br>';
//$timestamp = 15;
$timestamp = parsetime($date[0]);


post($link[1],$timestamp);

}


function post($link,$post)
{
	
	$esclink = preg_replace('~&amp;~', "&", $link);
	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');
	$statement = $db->prepare("SELECT link FROM FDA_enforcementlinks WHERE link = :link");
	$statement->execute(array(':link'=>$esclink,
	                  ));
	$linkresult = $statement->fetch();
	//print_r($linkresult);

		//echo $linkresult[0].'asldkjhsaljd';
	
	if($linkresult['link'] === $esclink)
	{
		
		echo "Link already in db<br>";
	}
	else
	{
		//echo $link;
		//$escapeprooflink = preg_replace('~&amp;~', "&", $link);
		//echo $escapeprooflink;
		echo "run insert<br>";
		$sql = "INSERT INTO FDA_enforcementlinks (time,link) VALUES (:time,:link)";
		$q = $db->prepare($sql);
		$q->execute(array(':time'=>$post,
	                  ':link'=>$esclink,
	                  ));
		print_r($q->errorInfo());

	}




	$db = null;
}

function parsetime($rawinput){

//echo $rawinput;
$month = substr($rawinput, 0, 1);
$day = substr($rawinput, 2, 3);
$year = substr($rawinput, 4, 7);

$unixTimestamp = mktime(0, 0, 0, $month, $day, $year);
//echo $unixTimestamp;

//echo '<br>';
return $unixTimestamp;
}

?>