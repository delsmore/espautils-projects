<?php

include 'conn-rir.php';

try{
	
 $dbh = new PDO("sqlsrv:Server=$server;Database=Results", $username, $password);

 $sql = "SELECT  * from ESPACategories";

 
 $i=1;
 $dois = 'Category, Description';
  
    foreach ($dbh->query($sql) as $row)    {
	
	
	$category = $row['Category'];
	$desc = $row['Description'];
	
	
		$replacements = array(
	'Vulnerability and Resilience' => 'Environmental vulnerability and resilience',
    'Carbon (above/below ground)' => 'Carbon sinks',
    'ES valuation' => 'Natural capital and valuation',
    'Feedbacks between ES' => 'Feedback between ecosystem services',
    'Rights and/or tenure' => 'Social Justice',
	'Political economy/ecology' => 'Politics, policies and decision making',
	'Integration (Method)' => 'New approaches/ tools to integrate data',
	'Scenarios (Method)' => 'Scenarios',
	'Social disaggregation (method)' => 'Social disaggregation',
);
	
 $theme = str_replace(array_keys($replacements), $replacements, $category);
	
  	$dois .=  PHP_EOL . '"' . $theme . '","' . $desc . '"';
	 //	echo $i . ' - ' . $row['doi'] . "<br>";
	 $i++;
        }
$my_file = 'espa-themes.csv';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file


fwrite($handle, $dois);

//echo 'pubs written to ' . $my_file . '<br><br>';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
print 'es types written to ' . $my_file;
?>