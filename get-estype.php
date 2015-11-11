<?php

include 'conn-rir.php';

try{
	
 $dbh = new PDO("sqlsrv:Server=$server;Database=Results", $username, $password);

 $sql = "SELECT  * from ESType";

 
 $i=1;
 $dois = 'ESType, Description';
  
    foreach ($dbh->query($sql) as $row)    {
	
	
	$type = $row['ESType'];
	$desc = $row['Description'];

	
  	$dois .=  PHP_EOL . '"' . $type . '","' . $desc . '"';
	 //	echo $i . ' - ' . $row['doi'] . "<br>";
	 $i++;
        }
$my_file = 'es-types.csv';
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