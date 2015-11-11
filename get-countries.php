<?php

include 'conn-rir.php';

try{
	
 $dbh = new PDO("sqlsrv:Server=$server;Database=Results", $username, $password);

 $sql = "SELECT DISTINCT dbo.Country.CountryName
FROM            dbo.ProjectCountry INNER JOIN
                         dbo.Country ON dbo.ProjectCountry.CountryID = dbo.Country.CountryID order by CountryName";

 
 $i=1;
 $dois = 'Country';
  
    foreach ($dbh->query($sql) as $row)    {
	
	
	$country = $row['CountryName'];
	

	
  	$dois .=  PHP_EOL . '"' . $country . '"';
	 //	echo $i . ' - ' . $row['doi'] . "<br>";
	 $i++;
        }
$my_file = 'countries.csv';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file


fwrite($handle, $dois);

//echo 'pubs written to ' . $my_file . '<br><br>';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
print 'countries written to ' . $my_file;
?>