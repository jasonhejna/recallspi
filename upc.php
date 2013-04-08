<?php
set_time_limit(36000);

include('simple_html_dom.php');
$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');

$statement = $db->prepare("SELECT link, id FROM sources WHERE flag = 0 ORDER BY id DESC LIMIT 2");
$statement->execute();
$linkresult = $statement->fetchAll();
$db = null;

//print_r($linkresult);


foreach ($linkresult as $data) 
{
	articlelook($data['link'],$data['id']);

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');
	$sql = "UPDATE sources SET flag=1 WHERE id=?";
	$q = $db->prepare($sql);
	$q->execute(array($data['id']));
	$db = null;
}


function articlelook($link,$id)
{
	echo $link;
	//$page = file_get_contents('http://www.fda.gov/Safety/Recalls/'.$link);


	$html = new simple_html_dom();

	$html = file_get_html('http://www.fda.gov/Safety/Recalls/'.$link);
	$ret = $html->find('.middle-column', 0)->plaintext;
	//preg_match('~class="middle-column"(.*?)MIDDLE-COLUMN~', $page, $partpage);
	//print_r($ret);
	
	$jsonstring = file_get_contents("patterns.json");
	$json_a = json_decode($jsonstring, true);

	foreach ($json_a as $regex) {
		//regexloop($value,$page);
		//echo $value;
		preg_match_all($regex, $ret, $out, PREG_SET_ORDER);
		//print_r($out);
		foreach ($out as $key => $value) {
			echo $value[0].',';
			$numericupc = preg_replace("/[^0-9]/", "", $value[0]);
			matchstoreage($numericupc,$id);

		}
	}


}

function matchstoreage($upc,$id)
{
	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');
	$sql = "INSERT INTO upc (source_id,upc) VALUES (:id,:upc)";

	$mttm = $db->prepare($sql);
	$mttm->execute(array(':id'=>$id,
	                  ':upc'=>$upc,
	                  ));

	$db = null;

}

?>