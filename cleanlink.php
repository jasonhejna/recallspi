<?php

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recalldb', 'g4%Gb7S%88@i2#');

	$statement = $db->prepare("SELECT link, id FROM sources WHERE flag = 0 LIMIT 44");
	$statement->execute();
	$linkresult = $statement->fetchAll();

//print_r($linkresult);

	foreach ($linkresult as $key => $value) {
		
		$fixedlink = ltrim($value[0], 'ArchiveRecalls/2010/');
		//echo $fixedlink.'<br>';
		//echo $value[1].'<br>';


	$sql = "UPDATE sources SET link=:fixedlink,flag=1 WHERE id=:linkvalue";

	$q = $db->prepare($sql);
	$q->execute(array(':fixedlink'=>$fixedlink,
	                  ':linkvalue'=>$value[1],
	                  ));
	

	}

	$db = null;

?>