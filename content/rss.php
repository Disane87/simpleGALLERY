<?php
header("Content-Type: application/rss+xml");
echo('<?xml version="1.0" encoding="ISO-8859-1"?>');

// Erforderliche Klassen includen
require_once("../classes/cDatabase.inc.php");
require_once("../classes/cLogin.inc.php");
require_once("../classes/cCommon.inc.php");
require_once("../conf/sqlstrings.inc.php");

// Instantiieren der Klassen
$common		= new cCommon();

// Erforderliche Includes durchführen
require_once("../conf/settings.inc.php");

// Instantiieren der Klassen
$database	= new cDatabase(DBHOST, DBPW, DBUSER, DBNAME);
$login		= new cLogin();

if($_GET['mode']!='subfolder'){
	
	//$resultset = $database->receiveData("SELECT Name, Aufnahme_Datum as Aufnahmedatum FROM ordner WHERE id = ".$_GET['id'].";");
	$resultset = $database->executeMySQLSP("GetOrdnerByID",$_GET['id'].", '".$database->GetSetting('SQL_DATELAYOUT')."'");
	if(mysqli_num_rows($resultset)) {
		while ($row = mysqli_fetch_object($resultset)) {
			$albumname		= $row->Name;
			$aufnahmedatum	= $row->Aufnahmedatum; 
		}
	}
}
else{
	//$resultset = $database->receiveData("SELECT Name, Aufnahme_Datum as Aufnahmedatum FROM ordner_sub WHERE id = ".$_GET['id'].";");
	$resultset = $database->executeMySQLSP("GetFolderByID",$_GET['id'].", '".$database->GetSetting('SQL_DATELAYOUT')."'");
	if(mysqli_num_rows($resultset)) {
		while ($row = mysqli_fetch_object($resultset)) {
			$albumname		= $row->Name;
			$aufnahmedatum	= $row->Aufnahmedatum; 
		}
	}
}
?>
<rss version="2.0">
	<channel>
		<title><?php echo $database->GetSetting('PAGE_NAME') ?> - <?php echo $albumname; ?></title>
		<link><?php echo($database->GetSetting('GALLERY_PATH')) ?>pics<?php echo $_GET['id']; ?>p1.html</link>
		<description>Das ist der RSS-Feed des Albums '<?php echo $albumname; ?>'!</description>
		<language>de-de</language>
		<pubDate><?php echo $row->Aufnahmedatum; ?></pubDate>
		
		<image>
			<title><?php echo $database->GetSetting('PAGE_NAME') ?> - <?php echo $albumname; ?></title>
			<link><?php echo($database->GetSetting('GALLERY_PATH')) ?>pics<?php echo $_GET['id']; ?>p1.html</link>
			<url></url>

			<width>120</width>
			<height>60</height>
			<description>vergiss' niemals neue Bilder!</description>
		</image>

		 

<?php

if($_GET['mode']!='subfolder'){
	$resultset = $database->executeMySQLSP("GetAllBilderByOrdnerID",$_GET['id']);
}
else{
	$resultset = $database->executeMySQLSP("GetAllBilderByOrdnerSubID",$_GET['id']);
}
if(mysqli_num_rows($resultset)) {
	while ($row = mysqli_fetch_object($resultset)) {
		?>
			<item>
				<title><?php echo($row->Titel); ?></title>
				<link><?php echo($database->GetSetting('GALLERY_PATH').$row->Pfad); ?></link>
				<description><?php echo($row->Name)."\r\n"; ?> (Größe: <?php echo $row->Breite."x".$row->Hoehe." ) ".$row->Hoehe ?> </description>
				<pubDate><?php echo date("r", mktime($row->hour, $row->minute, $row->second, $row->month, $row->day, $row->year)); ?></pubDate>
			</item>	
		<?php	
	}
}
?>
  </channel>
</rss>

