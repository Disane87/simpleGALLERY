<div align="left"><b>Bild-Upload:</b></div>
<div align="center">
	<form action="#" method="POST" enctype="multipart/form-data">
		<table width="50%" border="0" ID="FileTable">
			<tr>
				<td>Titel:</td>
				<td><input type="text" name="Titel"></td>
			</tr>	
			<tr>
				<td>Beschreibung:</td>
				<td><input type="text" name="Beschreibung"></td>
			</tr>
			<tr>
				<td>Eigenständiger Ordner?</td>
				<td><input type="checkbox" name="Album_Self" value="true"></td>
			</tr>
			<tr>
				<td>Datum:</td>
				<td><input type="text" name="Datum" id="datepicker"></td>
			</tr>
			
			<tr>
				<td colspan="2">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td>Übergeordneter Ordner:</td>
				<td>
					<select name="Folder" size="5">
						<?php
							$result = $database->executeMySQLSP("GetAllFolder","'".$database->GetSetting('SQL_DATELAYOUT')."'");

							// = $database->receiveData("SELECT *, DATE_FORMAT(Aufnahme_Datum,) as Folder_Datum FROM ordner");
					
							// wenn ja gucke nach, ob wir Ergebnisse in der Datenbank haben
							if(mysqli_num_rows($result)) {
								while ($spalte = mysqli_fetch_object($result)) {
									echo "<option value='".$spalte->ID."'>".$spalte->Name." (".$spalte->Folder_Datum.")</option>";
								}
							}else{
								echo "<option>Keine Ordner bisher angelegt!</option>";	
							}
						?>
						
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="Submit" value="Speichern" onclick="$('#saving').dialog('open');">
					<input type="reset" value="Zurücksetzen">
				</td>
			</tr>				
		</table>
	</form>
</div>

<?php
if(isset($_POST['Submit']) AND $_POST['Titel']!='' AND $_SESSION['UserRole'] == 3 AND $i != 1)
{
	$i += 1;
	if(isset($_POST['Album_Self'])){
		$database->insertupdateData("INSERT INTO ordner 
					(ID, Name, Aufnahme_Datum, Beschreibung, Datum)
					VALUES 
					(NULL , '".$_POST['Titel']."', CURDATE(), '".$_POST['Beschreibung']."','".$_POST['Datum']."');");
	}
	else{
		$database->insertupdateData("INSERT INTO ordner_sub 
					(ID, ParentID, Name, Aufnahme_Datum, Beschreibung, Datum)
					VALUES 
					(NULL ,'".$_POST['Folder']."' , '".$_POST['Titel']."',  CURDATE(), '".$_POST['Beschreibung']."','".$_POST['Datum']."');");
	}
?>
<?php
}
?>
<div id='pageing'><div id='back2Uebersicht'><a href='index.php'>Zurück zur Übersicht</a></div></div>