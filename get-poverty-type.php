<?php

include 'conn-rir.php';

try{
	
 $dbh = new PDO("sqlsrv:Server=$server;Database=Results", $username, $password);

 $sql = "SELECT  * from PovertyType";

 
 $i=1;
 $dois = 'PovertyType, Description';
  
    foreach ($dbh->query($sql) as $row)    {
	
	
	$dimension = $row['PovertyDimension'];
	$desc = $row['Description'];
	
	
		$replacements = array(
	'Food security and nutrition' => 'Food security',
    'Water (Consumption)' => 'Water security',
    'ES valuation' => 'Natural capital and valuation',
    'Security' => 'Protection from natural disasters/extreme weather',
    'Equity and justice' => 'Social Justice',
	'Empowerment and agency' => 'Empowerment and influence',
	'Property rights' => 'Property rights and land access',
	'Access to public goods' => 'Access to well-functioning environment e.g. clean air',

);
	
 $poverty = str_replace(array_keys($replacements), $replacements, $dimension);
	
  	$dois .=  PHP_EOL . '"' . $poverty . '","' . $desc . '"';
	 //	echo $i . ' - ' . $row['doi'] . "<br>";
	 $i++;
        }
$my_file = 'poverty-types.csv';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file


fwrite($handle, $dois);

//echo 'pubs written to ' . $my_file . '<br><br>';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
print 'Poverty types written to ' . $my_file;
?>