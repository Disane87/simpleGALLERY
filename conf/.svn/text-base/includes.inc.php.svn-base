<?php

// Erforderliche Klassen includen
require_once("classes/cDatabase.inc.php");
require_once("classes/cLogin.inc.php");
require_once("classes/cCommon.inc.php");
require_once("conf/sqlstrings.inc.php");

// Instantiieren der Klassen
$common		= new cCommon();

// Erforderliche Includes durchführen
require_once("conf/settings.inc.php");

// Instantiieren der Klassen
$database	= new cDatabase(DBHOST, DBPW, DBUSER, DBNAME);
$common->setDBObj($database);
$login		= new cLogin();

?>