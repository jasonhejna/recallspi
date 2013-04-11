<?php
set_time_limit(3600);
error_reporting(0);
$apikey = htmlspecialchars($_GET['apikey']);


if (isset($apikey))
{
	
	
	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$sql = $db->prepare("SELECT md5 FROM api_users WHERE md5 = :key");
	$sql->execute(array(':key' => $apikey,));
	$keymatch = $sql->fetch();
	$db = null;

	//print_r($keymatch);
	
	if($apikey == $keymatch['md5'])
	{
		//Add count checker
		thiscall($keymatch['md5']);
	}
	if($apikey != $keymatch['md5'])
	{
		echo 'API Key not found';
	}
}
if (empty($apikey))
{
	echo 'no API Key';
}

function thiscall($apikey)
{

	$thiscall = htmlspecialchars($_GET['thiscall']);
	if($thiscall == 'brandname')
	{
		brand_name();
	}
	elseif ($thiscall == 'brandcompany') {
		brand_company_name();
	}
	elseif ($thiscall == 'company') {
		company();
	}
	elseif ($thiscall == 'desc') {
		desc();
	}
	elseif ($thiscall == 'problem') {
		prob_desc();
	}
	elseif ($thiscall == 'desc/problem') {
		desc_probdesc();
	}
	elseif ($thiscall == 'upc') {
		upc();
	}
	else
	{
		echo 'not a recognized call. ';
	}
}


function output($result)
{
	if(isset($_GET['output']))
	{
		$output = htmlspecialchars($_GET['output']);

		//json, xml, php
		if($output == 'json')
		{
			echo json_encode($result);
		}
		elseif($output == 'xml')
		{
			$xml = xmlrpc_encode_request(NULL, $result); 
			print_r($xml);
		}
		elseif($output == 'php')
		{
			print_r($result);
		}
		else
		{
			echo json_encode($result);
		}
	}
	else
	{

		echo json_encode($result);

	}
}

function whereupcgetter($result)
{
	//print_r($result);
	$i=0;
	foreach ($result as $key => $value) {
	//print_r($value);

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT upccode FROM upc WHERE source_id=:id");
	$statement->execute(array(':id' => $value['id']));
	$sqlresult = $statement->fetchAll();
	$db = null;

	$merged = array_merge($value, $sqlresult);
	$array[$i] = $merged;
	$i++;	
	}
	output($array);
}

//START OF API FUNCTIONS

function brand_name()
{
	$input = '%'.htmlspecialchars($_GET['input']).'%';

	if(isset($_GET['date']))
	{
		$date = htmlspecialchars($_GET['date']);
	}
	else
	{
		$date = 0;
	}

	$input = '%'.htmlspecialchars($_GET['input']).'%';

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT * FROM sources WHERE (brand_name LIKE :search) AND unixtime>:datetime ORDER BY unixtime DESC");
	$statement->execute(array(':search' => $input,':datetime' => $date));
	$result = $statement->fetchAll();
	$db = null;

	whereupcgetter($result);
}

function brand_company_name()
{
	$input = '%'.htmlspecialchars($_GET['input']).'%';

	if(isset($_GET['date']))
	{
		$date = htmlspecialchars($_GET['date']);
	}
	else
	{
		$date = 0;
	}

	$input = '%'.htmlspecialchars($_GET['input']).'%';
	//$input2 = '%'.htmlspecialchars($_GET['input2']).'%';

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT * FROM sources WHERE (brand_name LIKE :search OR company LIKE :searchtwo OR descr LIKE :searchthree) AND unixtime>:datetime ORDER BY unixtime DESC");
	$statement->execute(array(':search' => $input,':searchtwo' => $input, ':searchthree' => $input, ':datetime' => $date));
	$result = $statement->fetchAll();
	$db = null;

	whereupcgetter($result);
}

function company()
{
	$input = '%'.htmlspecialchars($_GET['input']).'%';

	if(isset($_GET['date']))
	{
		$date = htmlspecialchars($_GET['date']);
	}
	else
	{
		$date = 0;
	}

	$input = '%'.htmlspecialchars($_GET['input']).'%';

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT * FROM sources WHERE (company LIKE :search) AND unixtime>:datetime ORDER BY unixtime DESC");
	$statement->execute(array(':search' => $input,':datetime' => $date));
	$result = $statement->fetchAll();
	$db = null;

	whereupcgetter($result);
}

function desc()
{

	$input = '%'.htmlspecialchars($_GET['input']).'%';

	if(isset($_GET['date']))
	{
		$date = htmlspecialchars($_GET['date']);
	}
	else
	{
		$date = 0;
	}

	$input = '%'.htmlspecialchars($_GET['input']).'%';

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT * FROM sources WHERE (descr LIKE :search) AND unixtime>:datetime ORDER BY unixtime DESC");
	$statement->execute(array(':search' => $input,':datetime' => $date));
	$result = $statement->fetchAll();
	$db = null;

	whereupcgetter($result);
}

function prob_desc()
{
	$input = '%'.htmlspecialchars($_GET['input']).'%';

	if(isset($_GET['date']))
	{
		$date = htmlspecialchars($_GET['date']);
	}
	else
	{
		$date = 0;
	}

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT * FROM sources WHERE (prob_desc LIKE :search) AND unixtime>:datetime ORDER BY unixtime DESC");
	$statement->execute(array(':search' => $input,':datetime' => $date));
	$result = $statement->fetchAll();
	$db = null;

	whereupcgetter($result);
}

function desc_probdesc()
{
	$input = '%'.htmlspecialchars($_GET['input']).'%';
	//$input2 = '%'.htmlspecialchars($_GET['input2']).'%';
	if(isset($_GET['date']))
	{
		$date = htmlspecialchars($_GET['date']);
	}
	else
	{
		$date = 0;
	}


	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT * FROM sources WHERE (descr LIKE :search OR prob_desc LIKE :searchtwo) AND unixtime>:datetime ORDER BY unixtime DESC");
	$statement->execute(array(':search' => $input,':searchtwo' => $input,':datetime' => $date));
	$result = $statement->fetchAll();
	$db = null;

	whereupcgetter($result);
}

function upc()
{
	$input = htmlspecialchars($_GET['input']);
	if(isset($_GET['date']))
	{
		$date = htmlspecialchars($_GET['date']);
	}
	else
	{
		$date = 0;
	}

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT * FROM sources INNER JOIN upc ON sources.id=upc.source_id WHERE upc.upccode = :search AND unixtime>:datetime");
	$statement->execute(array(':search' => $input,':datetime' => $date));
	$result = $statement->fetchAll();
	$db = null;

	whereupcgetter($result);
}

?>