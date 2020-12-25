<?php

// Erforderliche Klassen includen
require_once("../classes/cDatabase.inc.php");

// Erforderliche Includes durchführen
require_once("../conf/settings.inc.php");

// Instantiieren der Klassen
$database	= new cDatabase(DBHOST, DBPW, DBUSER, DBNAME);

$q = $_GET["q"];

if (strlen($q) > 0){
	
	$hint="";
	
	$resultset	= $database->receiveData("Select pfad, titel From bilder");
	
	// wenn ja gucke nach, ob wir Ergebnisse in der Datenbank haben
	if(mysql_num_rows($resultset)) {
				
		// Gehe die einzelnen Datensätze durch
		while ($row = mysql_fetch_array($resultset)) {
			for($i=0; $i<count($row); $i++)
			{
				if (strtolower($q)==strtolower(substr($row[$i],0,strlen($q))))
				{
					if ($hint=="")
					{
						$hint="<a href=".$row['pfad']." class='thickbox' rel='Suche'>".str_replace($q,"<font color='red'>".$q."</a></font>",$row[$i])."</a>";
					}
					else
					{
						$hint .= "<br><a href=".$row['pfad']." class='thickbox' rel='Suche'>".str_replace($q,"<font color='red'>".$q."</font>",$row[$i])."</a>";
					}
				}
			}
		}
		/*echo $hint;*/
				
		//Set output to "no suggestion" if no hint were found
		//or to the correct values
		if ($hint == "")
		{
			$response="Kein Bild zum Stichwort <font color='red'><b>".$q."</b></font> gefunden!";
		}
		else
		{
			$response=$hint;
		}
		
		//output the response
		echo $response;
	}
	else
	{
		$response = "Keine Bilder hochgeladen!";
		echo $response;
	}
}
?>