<?php
	/*---------------------------------------
		Your MySQL-Settings
	  ---------------------------------------*/
	
//	define('DBHOST'	, "localhost");								/* Server der Datenbank */
//	define('DBNAME'	, "simplegallery");							/* Datenbankname */
//	define('DBUSER'	, "root");									/* Datenbankbenutzer */
//	define('DBPW'	, "121187");								/* Passwort der Datenbank */
	
	define('DBHOST'	, "localhost");								/* Server der Datenbank */
	define('DBNAME'	, "d00a500c");								/* Datenbankname */
	define('DBUSER'	, "d00a500c");								/* Datenbankbenutzer */
	define('DBPW'	, "simpleGallery");							/* Passwort der Datenbank */

	/*---------------------------------------------
		Navigations-Einträge
	  ---------------------------------------------*/
	$dateien = array(); 
	$dateien['overview']	= "content/gallery.php";			/* Nicht sichtbar, wird aber zur internen Verwendung benutzt! */
	$dateien['rss']			= "content/gallery.php";			/* Nicht sichtbar, wird aber zur internen Verwendung benutzt! */
	$dateien['admincp']		= "content/admin/login.php";		/* Administrationsseite zum Einloggen */
	$dateien['upload']		= "content/upload.php";				/* Upload-Seite */
	$dateien['addfolder']	= "content/addfolder.php";			/* Seite zum Hinzufügen neuer Alben */
	$dateien['acp']			= "admin/gallery_settings.php";		/* Login-Seite für die Administrationsoberläche */
	$dateien['user_reg']	= "admin/user_registration.php";	/* Registrations-Seite */
	
	/*---------------------------------------------
	Do not modify, unless you know what you do!
	 ---------------------------------------------*/
	ini_set('memory_limit', '64M');	
	
?>