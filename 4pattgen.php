<?php

//"1070": "~[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[^0-9]{1}[0-9]{1}[^0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}[0-9]{1}~",
/*[0-9]{1}
[0-9]{2}
[0-9]{3}
[0-9]{4}
[0-9]{5}
[0-9]{6}
[0-9]{7}
[0-9]{8}
[0-9]{9}

[0-9]{1}[^0-9]{1}[0-9]{1}[^0-9]{1}[0-9]{1}[^0-9]{1}[0-9]{1}
1.1.1.1
1.1.1.1.1
*/


require_once 'Combinatorics.php';

ob_start();

$combinatorics = new Math_Combinatorics;
var_dump($combinatorics->permutations(array(
    '1'   => '1',
    '2'   => '2',
    '3' => '3',
    '4'  => '4',
    '5'  => '5',
    '6'  => '6',
    ), 5));

$result = ob_get_clean();

//print_r($result);

$pattern = '~"(.*?)"~';
  //echo $pattern;
  preg_match_all($pattern, $result, $match);
  //print_r($match);
  
  foreach ($match as $key => $value) {
  	$i=0;
  	$c=1439;
  	foreach ($value as $key => $value) {
		
		
  		if($i<=0){
  			$c++;
  			echo '"'.$c.'": ';
  			echo '"~';
  		}
  		$i++;
  		if($i<=4)
  		{
  			echo '[0-9]{'.$value.'}[^0-9]{1}';
  		}
  		else
  		{
  			echo '[0-9]{'.$value.'}';
  		}
  		
  		if($i>=5)
  		{
  			$i=0;
  			
  			echo '~",<br>';
  		}
  		//$c = $c - 3;
  	}
  	
  }




?>