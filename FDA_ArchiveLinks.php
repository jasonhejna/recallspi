<?php
set_time_limit(10000);
include('simple_html_dom.php');
$l=0;
for($a=0;$a<2;$a++)
{
	$l++;
 
$html = new simple_html_dom();

$html = file_get_html('http://www.fda.gov/Safety/Recalls/ArchiveRecalls/2011/default.htm?Page='.$l);


foreach($html->find('tr') as $row)
{
	tabledata($row);
}
}

function tabledata($row)
{
	$i=0;
	foreach($row->find('td') as $row) {
		if ($i==0){
			echo 'date: ';
			echo $row.'<br>';
		}
		if ($i==1){
			echo 'brand name/article link: ';
			//echo $row.'<br>';
			preg_match('~calls/(.*?)">~',$row,$linkmatch);

			echo 'http://www.fda.gov/Safety/Recalls/'.$linkmatch[1].'<br>';
			preg_match('~htm">(.*?)</a>~', $row, $titlematch);
			echo $titlematch[1].'<br>';

		}
		if ($i==2){
			echo 'Product Description: ';
			echo $row.'<br>';
		}
		if ($i==3){
			echo 'Reason/Problem: ';
			echo $row.'<br>';
		}
		if ($i==4){
			echo 'Company: ';
			echo $row.'<br>';
		}
		if ($i==5){
			//echo 'Details/photo/junk<br>';
			//echo $row;
		}
		$i++;
		//echo $i.'<br>'.$row.'<br>';

	}
}


?>