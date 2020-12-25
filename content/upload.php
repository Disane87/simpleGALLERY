<?php
srand();
$wert = rand(100000, 999999);
?>
<div align="left"><b>Bild-Upload:</b></div>
<div align="center">
	<form action="upload<?php echo $_GET['id']; ?><?php if(!isset($_POST['mode'])){ echo $_GET['mode'];} else { echo $_POST['mode']; } ;?>done.html" method="POST" enctype="multipart/form-data">
		<table width="50%" border="0" ID="FileTable">
			<tr>
				<td colspan="4" valign="top">
					<fieldset width="100%">
						<legend>Optionen:</legend>
						<table border="0" width="100%">
						<tr>
							<td valign="top">
								<input type="checkbox" name="TitelBild" value="true"><label>Titel = Bildname</label><br>
								<?php if($database->GetSetting('ALLOW_ZIP_UPLOAD') == "true"){ ?>
									<input type="checkbox" name="ZIP_Upload" value="true"><label>ZIP-Upload?</label>
								<?php } ?>
							</td>
							<td>
								<img src="content/captcha.img.php?wert=<?php echo $wert ?>" title="Sicherheitscode" id="secure">
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="Submit" value="Hochladen" onclick="$('#upload').dialog('open');">
								<input type="reset" value="Zurücksetzen">
								<input type="hidden" value="<?php if(!isset($_POST['mode'])){ echo $_GET['mode'];} else { echo $_POST['mode']; } ;?>" name="mode" />
								<input type="button" name="Mehr Files" value="Mehr Files" onClick="start(); return false;">
							</td>
							<td>
								<input type="text" name="captcha" value="" width="50px">
								<input type="hidden" name="wert" value="<?php echo $wert ?>" />
							</td>
						</tr>
						
							
						</table>
					</fieldset>	
				</td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td height="10px" width="25%"><legend>Titel:</legend></td>
				<td><input type="text" name="Titel"></td>
				<!--<td height="10px" width="25%"><legend>Ort:</legend></td>
				<td><input type="text" name="Ort"></td>-->
			</tr>
			<!--<tr>
				<td colspan="4">&nbsp;</td>
			</tr>-->
			<tr>
				<td height="10px"><legend>Bild:</legend></td>
				<td><input type="file" name="Bild[]" accept="gif|jpg|png" size="60"></td>
				<!--<td height="10px" width="25%"><legend>Aufnahme am/um:</legend></td>
				<td><input type="text" name="Aufnahme-Datum" id="datepicker"></td>-->
			</tr>	
				
		</table>
	</form>
</div>
<?php


if(isset($_GET['id']) AND $_GET['action']=='done' AND $_SESSION['UserRole'] == 3 AND $_POST['captcha'] == $_POST['wert']){
	{
		for ($i = 0; $i < count($_FILES['Bild']['tmp_name']); $i++) {
			set_time_limit(200);
			$nameTitel	= $_POST['TitelBild'];
				
			$AlbumID	= $_GET['id'];
			
			if($nameTitel == "true"){
				$BildTitel   = $_FILES['Bild']['name'][$i];
			}
			else{
				$BildTitel 	= $_POST['Titel'];	
			}
						
			$name		= $_FILES['Bild']['name'][$i];
			$type 		= $_FILES['Bild']['type'][$i];
			$tmp_name	= $_FILES['Bild']['tmp_name'][$i];
			$size 		= ($_FILES['Bild']['size'][$i])/1024;
			$hash 		= md5($name.$type.$size.$tmp_name.time());  
			$pfad		= $database->GetSetting('PICTURE_PATH');
			$ZIP_Path	= $database->GetSetting('UNZIP_PATH').str_replace('.zip','', $name)."/";
			
			/* Wird nur aufgerufen wenn eine ZIP hochgeladen wird */
			if(($type == "application/zip" OR $type == "application/x-zip-compressed" OR $type == "application/octetstream") AND $database->GetSetting('ALLOW_ZIP_UPLOAD') == true){
				if(move_uploaded_file($tmp_name, $database->GetSetting('UNZIP_PATH').$name)){
					$common->unzip($database->GetSetting('UNZIP_PATH').$name,$database->GetSetting('UNZIP_PATH'),$name);
					$verzeichnis = opendir($ZIP_Path);
					$i = 0;
					while($datei = readdir($verzeichnis)) {
						if (!is_dir($datei)){                 
							$bilder[$j]		= explode(".",$datei);
							$size			= filesize($ZIP_Path.$datei);
							$hashes[$j]		= md5($datei.time().$bilder[$j][1]).".".$bilder[$j][1];
							
							if (file_exists($ZIP_Path.$hashes[$j].".".$bilder[$j][1])){
								unlink($ZIP_Path.$hashes[$j].".".$bilder[$j][1]);
							}
							
							$bildname	= $bilder[$j][0];
							$bildtyp	= $bilder[$j][1];
							$bildhash	= $hashes[$j];
							$bildsize	= $size;
							$dest		= $ZIP_Path;
							
							$common->moveorunlinkPicture($pfad, $dest, $bildname, $bildhash, $bildtyp, 1);
							/*rename($ZIP_Path.$bildname.".".$bildtyp, $dest.$hashes[$i]);*/
							
							switch(strtoupper($bilder[$j][1])){
								case 'JPG':		$imgsrc = imagecreatefromjpeg($pfad.$bildhash);  break;
								case 'GIF':		$imgsrc = imagecreatefromgif($pfad.$bildhash); break;
								case 'PNG':		$imgsrc = imagecreatefrompng($pfad.$bildhash); break;
								case 'JPEG':	$imgsrc = imagecreatefromjpeg($pfad.$bildhash); break;
								case 'PJPEG':	$imgsrc = imagecreatefromjpeg($pfad.$bildhash); break;
							}
							$common->createThumb($pfad.$bildhash, $bildhash, $database->GetSetting('THUMBNAIL_PATH'), $database->GetSetting('THUMB_WIDTH'), $database->GetSetting('THUMB_HEIGHT'));
							
							if($database->GetSetting('USE_WATERMARK')=='true')
							{
								$common->insertWatermark($imgsrc, $bildtyp, $pfad.$bildhash);
							}
							
							$PicHoehe	= $common->height;
							$PicBreite	= $common->width;
							
							if($_GET['mode'] == 'subfolder' OR $_POST['mode'] == 'subfolder'){
								$database->executeMySQLSP("AddOrdnerSubBild","'".$bildname."','".$bildname."', '".$bildtyp."','".ceil($bildsize)."','".$PicHoehe."','".$PicBreite."','".$pfad."','".str_replace(".".$bildtyp,"", $bildhash)."',".$_SESSION['UsrID'].",".$AlbumID.", '".$database->GetSetting('THUMBNAIL_PATH').str_replace(".".$bildtyp,"", $bildhash)."'");
							}
							else
							{
								$database->executeMySQLSP("AddOrdnerBild","'".$bildname."','".$bildname."', '".$bildtyp."','".ceil($bildsize)."','".$PicHoehe."','".$PicBreite."','".$pfad."','".str_replace(".".$bildtyp,"", $bildhash)."',".$_SESSION['UsrID'].",".$AlbumID.", '".$database->GetSetting('THUMBNAIL_PATH').str_replace(".".$bildtyp,"", $bildhash)."'");
							}
						}
						$j++;
					}
					closedir($verzeichnis);
					unlink($database->GetSetting('UNZIP_PATH').$name);
					rmdir($ZIP_Path);
					
				}
				
			/* Normaler Bildupload */
			}else{
				if($_FILES['Bild']['name'][$i]!='' OR $_POST['Titel']!=''){		
					
					if($type != "image/gif" && $type != "image/jpeg" && $type !="image/png" && $type !="image/pjpeg" && $type != "application/zip" && $type != "application/x-zip-compressed" && $type != "application/octetstream") { 
						$err[] = 	"<div id='error' align='center'>
								Nur gif, png, jpeg und zip Dateien duerfen hochgeladen werden.
								</div>"; 
						echo "<td>".$err[0]."</td>";
					} 
					if($size > $database->GetSetting('MAX_FILESIZE')) { 
						$err[] = 	"<div id='error' align='center'>
								Die Datei welche du hochladen willst, ist zu gross!<br>
								Maximale Dateigrosse betraegt 500 KB!
								</div>"; 
						echo "<td>".$err[0]."</td>";
					} 
					
					if(empty($err)) 
					{						
						if(move_uploaded_file($tmp_name, $database->GetSetting('PICTURE_PATH').$name) OR ($type == "application/zip" OR $type == "application/x-zip-compressed" OR $type == "application/octetstream"))
						{
							$dest = $pfad.$hash;
							switch($type)
							{
								case "image/gif":
									$dateiendung = "gif";
									$imgsrc = $common->moveorunlinkPicture($pfad, $dest, $name, $hash, $dateiendung, 0);
									break;
								case "image/jpeg":
									$dateiendung = "jpg";
									$imgsrc = $common->moveorunlinkPicture($pfad, $dest, $name, $hash, $dateiendung, 0);
									break;
								
								case "image/png":
									$dateiendung = "png";
									$imgsrc = $common->moveorunlinkPicture($pfad, $dest, $name, $hash.".".$dateiendung, $dateiendung, 0);
									break;
								
								case "image/pjpeg":	
									$dateiendung = "jpg";
									$imgsrc = $common->moveorunlinkPicture($pfad, $dest, $name, $hash.".".$dateiendung, $dateiendung, 0);
									break;
							}
							$common->createThumb($pfad.$hash.".".$dateiendung, $hash.".".$dateiendung, $database->GetSetting('THUMBNAIL_PATH'), $database->GetSetting('THUMB_WIDTH'), $database->GetSetting('THUMB_HEIGHT'));
							
							if($database->GetSetting('USE_WATERMARK')== 'true')
							{
								$common->insertWatermark($imgsrc, $type, $pfad.$hash.".".$dateiendung);
							}

							$PicHoehe	= $common->height;
							$PicBreite	= $common->width;
							
							if($_GET['mode'] == 'subfolder' OR $_POST['mode'] == 'subfolder'){
								$database->executeMySQLSP("AddOrdnerSubBild","'".$name."','".$BildTitel."', '".$dateiendung."','".ceil($size)."','".$PicHoehe."','".$PicBreite."','".$pfad."','".str_replace(".".$type,"", $hash)."',".$_SESSION['UsrID'].",".$AlbumID.", '".$database->GetSetting('THUMBNAIL_PATH').str_replace(".".$dateiendung,"", $hash)."'");
							}
							else
							{
								$database->executeMySQLSP("AddOrdnerBild","'".$name."','".$BildTitel."', '".$dateiendung."','".ceil($size)."','".$PicHoehe."','".$PicBreite."','".$pfad."','".str_replace(".".$type,"", $hash)."',".$_SESSION['UsrID'].",".$AlbumID.", '".$database->GetSetting('THUMBNAIL_PATH').str_replace(".".$dateiendung,"", $hash)."'");
							}
						}
						else{
							
						}
					}
				}
				else{
					$err[] = 	"<div id='error' align='center'>
							Bitte fülle alle Felder aus!
							</div>"; 
					echo "<td>".$err[0]."</td>";	
				}
			}
		}
	}
	/*else
	{
		$err[] = 	"<div id='error' align='center'>
				Bitte bestätige die Änderung mit deinem PIN!
				</div>"; 
		echo "<td>".$err[0]."</td>";
	}*/
}
?>
<div id='pageing'><div id='back2Uebersicht'><a href='index.php'>Zurück zur Übersicht</a></div></div>