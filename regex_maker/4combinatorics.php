<?php
//"1079": "~[0-9]{18}~",
$file = file_get_contents('4combinatorics.txt');
//print_r($file);


  $pattern = '~"(.*?)"~';
  //echo $pattern;
  preg_match_all($pattern, $file, $match);
  //print_r($match);

  foreach ($match as $key => $value) {
  	$i=0;
  	foreach ($value as $key => $value) {
  		$i++;
  		if($i<=3)
  		{
  			echo '[0-9]{'.$value.'}[^0-9]{1}';
  		}
  		else
  		{
  			echo '[0-9]{'.$value.'}';
  		}
  		
  		if($i>=4)
  		{
  			$i=0;
  			
  			echo '<br>';
  		}
  	}
  	
  }
  
?>