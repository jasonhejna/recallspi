<?php

$apikey = htmlspecialchars($_GET['apikey']);



//$str = "1";
//echo md5($str);

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
	echo 'welcome to the martix ';
	echo $apikey;
	echo '.....';

	$thiscall = htmlspecialchars($_GET['thiscall']);
	if($thiscall == 'brandname')
	{
		echo 'yay!';
	}
	elseif ($thiscall == 'boomname') {
		echo 'boom';
	}
	else
	{
		echo 'no recognized call (thiscall).';
	}
}

//brandname('kellogg');
function brandname($search)
{
	$input1 = htmlspecialchars($_GET['input1']);

	$db = new PDO('mysql:host=recalldb.db.8532513.hostedresource.com;dbname=recalldb;charset=UTF8', 'recallapi', 'f6%!T7y#85!e62');
	$statement = $db->prepare("SELECT * FROM sources WHERE brand_name LIKE :search");
	$statement->execute(array(':search' => $input1));
	$result = $statement->fetchAll();
	$db = null;
	print_r($result);
}


?>