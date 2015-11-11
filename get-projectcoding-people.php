<?php

include 'conn-rir.php';

try{
	
 $dbh = new PDO("sqlsrv:Server=$server;Database=Results", $username, $password);

 $sql = "SELECT   dbo.Project.ProjectID, dbo.Project.ProjectCode, dbo.Project.ProjectTitle, dbo.Project.LeadProject, dbo.Project.LeadProjectRef, 
                         View_ESPAProjectCoding_1.Region1, View_ESPAProjectCoding_1.Region1,View_ESPAProjectCoding_1.Region2, View_ESPAProjectCoding_1.Countries AS ProjectCountries, 
                         View_ESPAProjectCoding_1.Poverty, View_ESPAProjectCoding_1.ESType, View_ESPAProjectCoding_1.CICESSection, View_ESPAProjectCoding_1.CICESDivision, 
                         View_ESPAProjectCoding_1.CICESGroup, View_ESPAProjectCoding_1.ESPACategories, dbo.ProjectPeople.ProjectID AS Expr1, dbo.ProjectPeople.RoleID, 
                         dbo.People.Surname, dbo.People.Title as PeopleTitle, dbo.People.Firstname, dbo.People.PeopleID
FROM            dbo.People INNER JOIN
                         dbo.ProjectPeople ON dbo.People.PeopleID = dbo.ProjectPeople.PeopleID INNER JOIN
                         dbo.Project INNER JOIN
                         dbo.View_ESPAProjectCoding AS View_ESPAProjectCoding_1 ON dbo.Project.LeadProjectRef = View_ESPAProjectCoding_1.LeadProjectRef ON 
                         dbo.ProjectPeople.ProjectID = dbo.Project.ProjectID
WHERE        (dbo.Project.LeadProject = 1) AND (dbo.ProjectPeople.RoleID = 1)";

 
 $i=1;
 $dois = ' NercID, SafeCode, ProjectTitle, Country, ESType, CICESGroup, ESPATheme, PovertyType,Region1,Region2,Surname,Firstname,Title' . PHP_EOL;
  
    foreach ($dbh->query($sql) as $row)    {
	
	
	$code = $row['ProjectCode'];
	$codelower = strtolower($code);
	$safecode = str_replace('/','-',$codelower);
	$title = $row['ProjectTitle'];
	$country = $row['ProjectCountries'];
	$estype = $row['ESType'];
	$cicessection = $row['CICESGroup'];
	$espacategories = $row['ESPACategories'];
	$povertytype = $row['Poverty'];
	$region1 = $row['Region1'];
	$region2 = $row['Region2'];
	$peopleTitle = $row['PeopleTitle'];
	$peopleSurname = $row['Surname'];
	$peopleFirstname = $row['Firstname'];
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

	
  	$dois .= '"' . $code . '","' . $safecode . '","' . $title . '","' . $country . '","' . $estype . '","' . $cices .  '","' . $espatheme . '","' . $poverty . '","' . $region1 . '","' . $region2 . '","' . $peopleSurname . '","' . $peopleFirstname . '","' . $peopleTitle . '"' . PHP_EOL;
	 //	echo $i . ' - ' . $row['doi'] . "<br>";
	 $i++;
        }
$my_file = 'files/project-categories-people.csv';
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