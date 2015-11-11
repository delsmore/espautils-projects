<?php

include 'conn-rir.php';

try{
	
 $dbh = new PDO("sqlsrv:Server=$server;Database=Results", $username, $password);

 $sql = "SELECT        ESPANarrative
FROM            CICESGroup
ORDER BY SortOrder";

 
 $i=1;
 $dois = 'Group';
  
    foreach ($dbh->query($sql) as $row)    {
	
	
	$group = $row['ESPANarrative'];
	

	
  	$dois .=  PHP_EOL . '"' . $group . '"';
	 //	echo $i . ' - ' . $row['doi'] . "<br>";
	 $i++;
        }
$my_file = 'cices-groups.csv';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file


fwrite($handle, $dois);

//echo 'pubs written to ' . $my_file . '<br><br>';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
print 'CICES Groups written to ' . $my_file;
?>