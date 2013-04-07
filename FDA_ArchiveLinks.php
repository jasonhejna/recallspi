<?php
set_time_limit(36000);

include('simple_html_dom.php');
$l=0;
for($a=0;$a<3;$a++)
{
	$l++;
	echo $l;
 
$html = new simple_html_dom();

$html = file_get_html('http://www.fda.gov/Safety/Recalls/ArchiveRecalls/2013/default.htm?Page='.$l);


foreach($html->find('tr') as $row)
{
	tabledata($row);
}
}

function tabledata($row)
{
	$unixtime=0;
	$linkmatch[1] = 0;
	$titlematch[1] = 0;
	$descr = 0;
	$problem = 0;
	$company = 0;
	$i=0;
	foreach($row->find('td') as $row) {
		$row = rtrim($row, '</td>');
		$row = ltrim($row, '<td>');
		//good morning, this is where i left off
		//$row = str_replace('&nbsp;', '', $row);
		if ($i==0){
			//echo 'date:';
			preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $row, $datematch);
			
			$time = strtotime($datematch[0]);
			$unixtime = $time + 68400;
			//echo $unixtime.'<br>';
			//echo $row.'<br>';

		}
		if ($i==1){
			
			//echo 'brand name/article link: ';
			//echo $row.'<br>';
			preg_match('~calls/(.*?)">~',$row,$linkmatch);
			//echo 'http://www.fda.gov/Safety/Recalls/'.$linkmatch[1].'<br>';
			
			preg_match('~htm">(.*?)</a>~', $row, $titlematch);
			//echo $titlematch[1].'<br>';
			//$row = str_replace('</a> &nbsp;', '', $row);

		}
		if ($i==2){
			//echo 'Product Description: ';
			//echo $row.'<br>';
			$descr = str_replace('</a> &nbsp;', '', $row);
			//$descr = $row;
		}
		if ($i==3){
			//echo 'Reason/Problem: ';
			//echo $row.'<br>';
			$problem = $row;
		}
		if ($i==4){
			
			//echo 'Company: ';
			//echo $row.'<br>';
			$company = substr($row, 2);
		}
		if ($i==5){
			//echo 'Details/photo/junk<br>';
			//echo $row;
		}
		$i++;
		//echo $i.'<br>'.$row.'<br>';

	}
	//if to remove zero from function change
	if ($unixtime != 0) {
	sql($unixtime,$linkmatch[1],$titlematch[1],$descr,$problem,$company);
	}

}

function sql($e,$r,$t,$y,$u,$i){

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');

	$statement = $db->prepare("select link from sources where link = :link");
	$statement->execute(array(':link' => $r));
	$linkresult = $statement->fetch();
	//print_r($linkresult);
	if ($linkresult[0] != $r) {

	$sql = "INSERT INTO sources (unixtime,link,brand_name,descr,prob_desc,company) VALUES (:unixtime,:link,:brand_name,:descr,:prob_desc,:company)";

	$q = $db->prepare($sql);
	$q->execute(array(':unixtime'=>$e,
	                  ':link'=>$r,
	                  ':brand_name'=>$t,
	                  ':descr'=>$y,
	                  ':prob_desc'=>$u,
	                  ':company'=>$i,
	                  ));
	$db = null;
	}
}

?>