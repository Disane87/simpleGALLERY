<?php
if(isset($_SESSION['UsrID'])){

/* Alle Einstellungen laden */

$result = $database->executeMySQLSP("GetAllSettingCategories","");
$ausgabe	= "";
$list		= "";
while ($categories = mysqli_fetch_object($result)) {
	if(mysqli_num_rows($result)) {
		/*echo "<a href='settings_cat_".$categories->Kategorie.".html'>".$categories->Kategorie."</a> | ";*/
		
			$list		.= "<li><a href='#".$categories->Kategorie."'><span>".$categories->Kategorie."</span></a></li>\n";
									
			$ausgabe	.= "<div id='".$categories->Kategorie."'>\n";
			$result1	= $database->executeMySQLSP("GetAllSettingsByCategory","'".$categories->Kategorie."'");
			$ausgabe	.=  "<table border='0' width='100%'>\n";
			while ($obj = mysqli_fetch_object($result1)) {
				if(mysqli_num_rows($result1)) {
				
					$ausgabe .=	 "<tr onmouseover=\"this.className='GalleryHover'\" onmouseout=\"this.className=''\">\n";
					$ausgabe .=		"<td align='left' width='20%'>".$obj->Eigenschaft."</td>\n";
					if($obj->Wert == 'true' OR $obj->Wert == "false"){
						$ausgabe .= "<td align='left' width='30%'>\n";
						$ausgabe .= "<select name='".$obj->Eigenschaft."'>\n";
						if($obj->Wert == "true"){
							$ausgabe .= "<option value='true' selected='selected'>true</option>\n";
							$ausgabe .= "<option value='false'>false</option>\n";
						}
						else{
							$ausgabe .= "<option value='true'>true</option>\n";
							$ausgabe .= "<option value='false' selected='selected'>false</option>\n";
						}
						
						
						$ausgabe .= "</select>\n";
						$ausgabe .= "</td>\n";						
						}
						elseif($obj->Eigenschaft != "PAGE_DESIGN"){
							$ausgabe .=		"<td align='left'><input name='".$obj->Eigenschaft."' type='text' value='".$obj->Wert."' size='100'></td>\n";
						}
						else{
							$designs  = $database->executeMySQLSP("GetAllDesigns","");
							$ausgabe .= "<td align='left'><select name='".$obj->Eigenschaft."'>\n";
							while ($DesignObj = mysqli_fetch_object($designs)) {
								if(mysqli_num_rows($designs)) {
									if($database->GetSetting('PAGE_DESIGN') == $DesignObj->ID){
										$ausgabe .= "<option value='".$DesignObj->ID."' selected='selected'>".$DesignObj->Name."</option>\n";
									}
									else{
										$ausgabe .= "<option value='".$DesignObj->ID."'>".$DesignObj->Name."</option>\n";
									}
								}
							}
							$ausgabe .= "</select></td>\n";
						}
						
					}
					$ausgabe .= "<td align='left' width='*'>".$obj->Kommentar."</td>";
					$ausgabe .=	"</tr>\n";	
				}
			$ausgabe .= "</table></div>\n";
	}
}


?>

<form action="save_settings.html" method="POST">
	<table border="0" width='100%' id="Settings">
	<tr>
		<td>
			<div id="tabs">
				<ul><?php echo $list; ?></ul>
				<?php
					echo $ausgabe;
				?>
			</div>
		</td>
		<td>
			&nbsp;
		</td>
	</tr>	
	</table>
	
	<table>
		<tr>
			<td colspan="2" align="left">
				<input type="submit" name="Submit" value="Speichern" onclick="$('#saving').dialog('open');"> 
				<input type="reset" value="Zurücksetzen"><input type="hidden" value="<?php echo $cat; ?>">
			</td>
		</tr>	
	</table>
</form>
<?php
} else {
	echo "Dazu müssen Sie eingeloggt sein!";	
}
if(isset($_POST['Submit']) AND $_GET['action'] == 'savesettings')
{
	foreach($_POST as $key => $val){
		if($key != "Submit"){
			$database->insertupdateData("UPDATE einstellungen SET Wert = '".$val."' WHERE Eigenschaft = '".$key."'");
		}	
	}
}
echo "<div id='pageing'><div id='back2Uebersicht'><a href='index.php'>Zurück zur Übersicht</a></div></div>";
?>
