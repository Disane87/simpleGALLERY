<?php

	/**
	* ----------------------------------------------------------------------------
	* "THE BEER-WARE LICENSE" (Revision 42):
	* <admin@real-insanity.com> wrote this file. As long as you retain this notice 
	* you can do whatever you want with this stuff. If we meet some day, and you 
	* think this stuff is worth it, you can buy me a beer in return 
	* Marco Matthias Franke
	* ----------------------------------------------------------------------------
	**/
	
	/*	
		Ansprechen einer Stored-Procedure per MySQL
		
		$result = $database->executeMySQLSP("GetAllBilder","");
		while ($obj = mysqli_fetch_object($result)) {
			echo($obj->Titel."<br>");
		}	
	*/
	
	//error_reporting(E_ALL);
	require("conf/includes.inc.php");
	
	// Anfangszeit für die Rendertime-Messung setzen
	$common->setAnfangszeit();
	
	/*	
		Setzen des Standard-Layouts der Gallery
		Prüfen ob es bereits einen Cookie gibt.
	*/	
	if(!isset($_COOKIE['Overview'])){
		setcookie("Overview", $database->GetSetting('DEFAULT_OVERVIEW'), time()+864000);	
	}
	
	session_start();
	session_name ("UserLogin");	
	
	switch($_GET['action']){
		case 'login':	
			if($_POST['UserName'] !='' AND $_POST['UserPassword'] != ''){
				
				$CleanUsername = $common->safeEscapeString($_POST['UserName']);
				$CleanPassword = $common->safeEscapeString($_POST['UserPassword']);

				if($login -> Login($CleanPassword, $CleanUsername, $database, $database->GetSetting('PASSWORD_MIN_SIZE'), $database->GetSetting('PAGE_TIMELAYOUT') )){
					$Errors = false;
				}
				else{
					$Errors = true;
				}
			}
			break;
		
		case 'logout':
			$lastName = $_SESSION['UsrName'];
			
			$login->logoutUser();
			break;	
	}
	$result = $database->executeMySQLSP("GetDesignByID",$database->GetSetting('PAGE_DESIGN'));
	while ($obj = mysqli_fetch_object($result)) {
		$pagestyle = $obj->Name;
	}	
	
	$pagename		= $database->GetSetting('PAGE_NAME');
	$pageversion	= $database->GetSetting('PAGE_VERSION');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<HEAD>
		<TITLE>
			&raquo; <?php echo $pagename." &rsaquo; v".$pageversion; ?>
		</TITLE>
		<LINK rel="stylesheet" type="text/css" href="styles/<?php echo $pagestyle; ?>/pagestyle.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="styles/<?php echo $pagestyle; ?>/jquery-ui-1.7.1.custom.css" media="screen" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script type="text/javascript" src="javascript/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="javascript/common.js"></script>
		<script type="text/javascript" src="javascript/thickbox.js"></script>
		<script type="text/javascript" src="javascript/multifile.js"></script>
		<script type="text/javascript" src="javascript/ui/i18n/ui.datepicker-de.js"></script>
		<script type="text/javascript" src="javascript/jquery-ui-1.7.1.custom.min.js"></script>

		<link rel="shortcut icon" href="images/icons/picture_go.png" />

		<?php 
		if($database->GetSetting('ALLOW_RSSFEEDS') == "true" AND isset($_GET['id']) AND $_GET['section']=='pics'){ 
			echo '<link rel="alternate" type="text/xml" title="RSS 2.0" href="'.$database->GetSetting('GALLERY_PATH').'rss'.$_GET['id'].'.html" />';
		} 
		?>

	</head>
	<body>
		<?php 
		if($database->GetSetting('STANDALONE') != "true"){
			echo "<div id='header'>";
			echo "<table width='100%' border='0'><tr>";
			echo "<td align='left' style='font-size:large'>".$pagename." &rsaquo; v".$pageversion."</td>";
			if($database->GetSetting('USE_PICSEARCH')=="true"){
				echo "<td align='left'><input type='text' id='txtSuche' onkeyup='showHint(this.value)'></td>";
			}
		?>
		<td align='center'>
		<?php 
		if($database->GetSetting('ALLOW_REGISTRATION') == "true")
		{
			echo "<a href='user_registration.html'><img src='images/icons/user_add.png' title='Registrieren'></a> ";
		}
		
		if($_SESSION['UserRole'] == 3)
		{
			echo "<a href='admin_panel.html'><img src='images/icons/wrench.png' title='Administrations Oberfläche'></a> ";
		}
		if(!isset($_SESSION['UsrID'])){ 
		?>
			<img src='images/icons/login.png'> <input type="button" value="Login" onclick="$('#login').dialog('open');">
		<?php } else { ?>
			<a href="userlogout.html"><img src='images/icons/logout.png' title="<?php echo $_SESSION['UsrName']; ?> ausloggen"></a> Eingeloggt als: <span id="markedItem"><?php echo $_SESSION['UsrName']; ?></span>
		<?php 
		} 		
		?>
		</td>
		<?php
			echo "<td align='right' style='padding-right:5px;'>";

			switch($database->GetSetting('ALLOW_COMMENTS')){
				case "true":
							echo "<img src='images/icons/comment.png'> An ";
							break;
				case "false":
							echo "<img src='images/icons/comment.png'> Aus ";
							break;
			}
			switch($database->GetSetting('ALLOW_RATING')){
				case "true":
							echo "<img src='images/icons/medal_gold_2.png'> An ";
							break;
				case "false":
							echo "<img src='images/icons/medal_gold_2.png'> Aus ";
							break;
			}
			switch($database->GetSetting('ALLOW_RSSFEEDS')){
				case "true":
					echo "<img src='images/icons/folder_feed.png'> An ";
					break;
				case "false":
					echo "<img src='images/icons/folder_feed.png'> Aus ";
					break;
			}
			echo "</td></table>";
			echo "</div>";
		}
		?>
		<div id="content">
		<?php
		try 	
		{
			include_once("conf/content.inc.php");
		}
		catch(Exception $e)
		{
			echo "<div id='error'><b>Fehler: </b><br>".$e->getMessage()."<br><br><b>Datei:</b><br>'".$e->getFile()."'<br><br><b>Zeile:</b><br>".$e->getLine().'</div>';
		}
		?>
		</div>

		
		<div id="footer">
			<?php
				// Endzeit für die Rendertime-Messung setzen
				$common->setEndzeit();
				mysqli_fetch_object($database->executeMySQLSP("OptimizeTables",""));
				echo "<div align='left' style='width:auto;'>Renderzeit: <b>" . str_replace(".",",",$common->getRendertime($database->GetSetting('ROUND_PRECISION'))) . "</b> Sekunden - ".date(DATE_RFC822, time())."</div>";
			?>
		</div>
		
		
		<!-- Unsichtbare Dialoge -->
		<div id="saving">
			<div align="center">
				<img src="images/loadingAnimation.gif">
			</div>
		</div>
		
		<div id='login'">
			<form action="userlogin.html" method="POST">
				<table>
					<tr>
						<td>Nickname:</td>
						<td><input type="text" name="UserName"></td>
					</tr>
					<tr>
						<td>Passwort:</td>
						<td><input type="password" name="UserPassword"></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" name="Submit" value="Einloggen"> <input type="reset" value="Zurücksetzen"> 
							<input type="button" value="Abbrechen">
						</td>
					</tr>
				</table>
			</form>
		</div>
		
		<div id="upload">
		
		</div>
		<!-- -->

	</body>
</html>