<?php

include 'conn-rir.php';

try{
	
 $dbh = new PDO("sqlsrv:Server=$server;Database=EdinaImports", $username, $password);

 $sql = "SELECT        TOP (100) PERCENT Results.dbo.Project.ProjectID, Results.dbo.Project.ProjectCode, Results.dbo.Project.ProjectTitle, Results.dbo.Project.LeadProject, 
                         Results.dbo.Project.LeadProjectRef, Results.dbo.View_ESPAProjectCoding.Region1, Results.dbo.View_ESPAProjectCoding.Region2, 
                         Results.dbo.View_ESPAProjectCoding.Countries AS ProjectCountries, Results.dbo.View_ESPAProjectCoding.Poverty, Results.dbo.View_ESPAProjectCoding.ESType, 
                         Results.dbo.View_ESPAProjectCoding.CICESSection, Results.dbo.View_ESPAProjectCoding.CICESDivision, Results.dbo.View_ESPAProjectCoding.CICESGroup, 
                         Results.dbo.View_ESPAProjectCoding.ESPACategories
FROM            Results.dbo.Project INNER JOIN
                         Results.dbo.View_ESPAProjectCoding ON Results.dbo.Project.LeadProjectRef = Results.dbo.View_ESPAProjectCoding.LeadProjectRef
WHERE        (Results.dbo.Project.LeadProject = 1)";

 
 $i=1;
 $dois = ' NercID, SafeCode, ProjectTitle, Country, ESType, CICESGroup, ESPATheme, PovertyType' . PHP_EOL;
  
    foreach ($dbh->query($sql) as $row)    {
	
	
	$code = $row['ProjectCode'];
	$codelower = strtolower($code);
	$safecode = str_replace('/','-',$codelower);
	$title = $row['ProjectTitle'];
	$country = str_replace("; ",";",$row['ProjectCountries']);
	$estype = str_replace("; ",";",$row['ESType']);
	$cicessection = str_replace("; ",";",$row['CICESGroup']);
	$espacategories = str_replace("; ",";",$row['ESPACategories']);
	$povertytype = str_replace("; ",";",$row['Poverty']);
	
	//$cices = str_replace('Biomass, Fibre','Natural products e.g. timber',$cicessection);
	
	$cicesreplacements = array(
    'Biomass, Fibre' => 'Natural products e.g. timber',
    'Biomass energy' => 'Fuelwood and other biofuels',
    'Mechanical energy' => 'Mechanical energy e.g. hydropower',
    'Waste regulation by  organisms' => 'Nutrient cycling',
	'Waste regulation by ecosystems' => 'Environmental waste regulation',
	'Erosion protection' => 'Land erosion protection',
    'Mediation of air flows' => 'Land cover which regulates air flow',
	'Pest and disease control' => 'Pest and disease regulation',
	'Regulation of water quality' => 'Water quality regulation',
	'Climate regulation and air quality' => 'Climate regulation',
    'Cultural heritage and intellectual interactions' => 'Cultural heritage',
	'Spiritual and/or emblematic services' => 'Spiritual, religious',
);

    $cices = str_replace(array_keys($cicesreplacements), $cicesreplacements, $cicessection);
	
		$espathemesreplace = array(
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
	$espatheme = str_replace(array_keys($espathemesreplace), $espathemesreplace, $espacategories);
	
		$povertyreplace = array(
	'Food security and nutrition' => 'Food security',
    'Water (Consumption)' => 'Water security',
    'ES valuation' => 'Natural capital and valuation',
    'Security' => 'Protection from natural disasters/extreme weather',
    'Equity and justice' => 'Social Justice',
	'Empowerment and agency' => 'Empowerment and influence',
	'Property rights' => 'Property rights and land access',
	'Access to public goods' => 'Access to well-functioning environment e.g. clean air',

);
	
 $poverty = str_replace(array_keys($povertyreplace), $povertyreplace, $povertytype);	

	
  	$dois .= '"' . $code . '","' . $safecode . '","' . $title . '","' . $country . '","' . $estype . '","' . $cices .  '","' . $espatheme . '","' . $poverty . '"' . PHP_EOL;
	 //	echo $i . ' - ' . $row['doi'] . "<br>";
	 $i++;
        }
$my_file = 'project-categories.csv';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file


fwrite($handle, $dois);

//echo 'pubs written to ' . $my_file . '<br><br>';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
print 'done';
?>