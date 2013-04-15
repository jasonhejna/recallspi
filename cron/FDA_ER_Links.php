<?php
set_time_limit(3600);
error_reporting(E_ALL);
$file = file_get_contents('http://www.fda.gov/Safety/Recalls/EnforcementReports/2012/');
//print_r($file);
preg_match_all('~href="/Safety/Recalls/EnforcementReports/ucm(.*)</a>~', $file, $matches);
//print_r($matches[0]);
foreach ($matches[0] as $key => $value) {
	//echo $value;
	preg_match('~/Recalls/EnforcementReports/(.*).htm">~', $value, $link);
	echo $link[1];
echo '<br>';

	preg_match('~Enforcement Report for (.*)~', $value, $date);

$dateInfo = date_parse_from_format('m d, Y', $date[1]);
//print_r($dateInfo);
$unixTimestamp = mktime(
    8, 0, 0,
    $dateInfo['month'], $dateInfo['day'], $dateInfo['year']
);
echo $unixTimestamp;

echo '<br>';

post($link[1],$unixTimestamp);

}


function post($link,$post)
{

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');
	$statement = $db->prepare("SELECT link FROM FDA_enforcementlinks WHERE link = :link");
	$statement->execute(array(':link'=>$link,
	                  ));
	$linkresult = $statement->fetch();
	//print_r($linkresult);

		//echo $linkresult[0].'asldkjhsaljd';
	
	if($linkresult['link'] === $link)
	{
		
		echo "Link already in db<br>";
	}
	else
	{
		echo "run insert<br>";
		$sql = "INSERT INTO FDA_enforcementlinks (time,link) VALUES (:time,:link)";
		$q = $db->prepare($sql);
		$q->execute(array(':time'=>$post,
	                  ':link'=>$link,
	                  ));
		print_r($q->errorInfo());
	}




	$db = null;
}

?>