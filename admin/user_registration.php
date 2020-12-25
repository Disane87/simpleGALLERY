<?php
srand();
$wert = rand(100000, 999999);
if(($_GET['email']==$_GET['emailverify']) AND ($_GET['pass']==$_GET['passverify']) AND $_POST['captcha']==$_POST['wert']){
	
	$akt_code = md5($_POST['nick'].$_POST['email']);
	/*GetMySQLData("INSERT INTO benutzer (NICK, EMAIL, PASSWORT, AKT_CODE, REG_DATE) 
				VALUES ('".$_POST['nick']."', '".$_POST['email']."', '".md5($_POST['pass'])."', '".$akt_code."', '".time()."')");*/
				
	$property = "'".$_POST['vorname']."','".$_POST['nachname']."','".$_POST['nick']."','".$_POST['email']."','".$_POST['gebdatum']."','".$_POST['icq']."',null,null,'".md5($_POST['pass'])."',1,1";
	$database->executeMySQLSP("AddNewUser",$property);
	
	//INSERT INTO users
   //(`ID`, `Vorname`, `Nachname`, `Nick`, `Email`, `Gebdatum`, `ICQ`, `MSN`, `Bild_ID`, `Passwort`, `Aktiv`, `Role_ID`)
   //Vorname VARCHAR(45), Nachname VARCHAR(45), Nick VARCHAR(45), Mail VARCHAR(45), Gebdatum DATE, Icquin INT(11), Msn VARCHAR(45), BildID INT(11), pw VARCHAR(45), active INT(1), SecID INT(11)
	$mail_empfaenger=$_POST['email'];
	$mail_absender=$database->GetSetting('MAIL_SENDER');
	$betreff="Deine Anmeldung auf ".$database->GetSetting('PAGE_NAME');
	$text="<style type='text/css'>
			body{
			font-family:verdana;
			font-size:10px;
			}
			</style>
			<body>
			Hallo <b>".$_POST['nick']."</b>, <br>
			dein Account wurde erfolgreich angelegt.<br><br>
			Deine Daten:<br>
			-------------<br>
			Anmeldename: ".$_POST['nick']."<br>
			Passwort:	".$_POST['pass']."<br>
			
			Ich wünsche dir viel Spaß auf meiner Seite.<br>
			<br>
			Mit freundlichen Grüßen<br>
			".$database->GetSetting('PAGE_NAME')."-Team<br></body>";
	mail($mail_empfaenger, $betreff, $text,"from:$mail_absender\r\nContent-Type:text/html\r\nContent-Transfer-Encoding: 8bit\r\n");
}
?>
<table width="100%" border="0" >
    <form action="user_registration_done.html" method="POST">
	<tr >
    	<th width="25%" class="tabledescription">Vorname:</th>
        <td>
        	<input type="text" name="vorname" value="Dein Vorname">
		</td>
    </tr>
	<tr >
    	<th width="25%" class="tabledescription">Nachname:</th>
        <td>
        	<input type="text" name="nachname" value="Dein Nachname">
		</td>
    </tr>
	<tr >
    	<th width="25%" class="tabledescription">Geburtsdatum:</th>
        <td>
        	<input type="text" name="gebdatum" value="Geburtsdatum" id="datepicker">
		</td>
    </tr>
    <tr >
    	<th width="25%" class="tabledescription">Dein Nickname:</th>
        <td>
        	<input type="text" name="nick" value="Dein Nick-Name">
		</td>
    </tr>
	 <tr >
    	<th width="25%" class="tabledescription">ICQ:</th>
        <td>
        	<input type="text" name="icq" value="Deine ICQ">
		</td>
    </tr>
    <tr>
    	<th class="tabledescription">2x Deine E-Mail:</th>
        <td>
        	<input type="text" name="email" value="hier@da.de">
		</td>
    </tr>
    <tr>
    	<th class="tabledescription">&nbsp;</th>
        <td>
        	<input type="text" name="emailverify" value="hier@da.de">
		</td>
    </tr>
    <tr>
    	<th class="tabledescription">2x Dein Passwort:</th>
        <td>
        	<input type="password" name="pass" value="hier@da.de">
		</td>
    </tr>
    <tr>
    	<th class="tabledescription">&nbsp;</th>
        <td>
        	<input type="password" name="passverify" value="hier@da.de">
		</td>
    </tr>
    <tr>
        	<th>Anti-Spam:</th>
            <th><img src="content/captcha.img.php?wert=<?php echo $wert ?>" border="0" title="Sicherheitscode" id="secure"><br /><input type="text" name="captcha" value="" width="140px">
            <input type="hidden" name="wert" value="<?php echo $wert ?>" /></th>
			
        </tr>
	<tr>
	<th class="tabledescription">&nbsp;</th>
        <td>
			&nbsp;
		</td>
    </tr>
    <th class="tabledescription">&nbsp;</th>
        <td>
        	<input type="Submit" Value="Registrieren"> <input type="Reset"  Value="Reset">
		</td>
    </tr>
	
    </form>
</table>
<div id='pageing'><div id='back2Uebersicht'><a href='index.php'>Zurück zur Übersicht</a></div></div>