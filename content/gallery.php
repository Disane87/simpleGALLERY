<?php
// Gesamtanzahl der anzuzeigenden Bilder errechnen
$PicsPerPage = $database->GetSetting('COLS_PER_PAGE') * $database->GetSetting('ROWS_PER_PAGE');


/**
 * Übersicht aller Alben
 **/

if($_GET['action'] == 'folderdelete' AND isset($_GET['id'])){
	if($_GET['mode'] == 'subfolder'){
		//$resultset = $database->receiveData("SELECT * FROM bilder WHERE ordner_subID = ".$_GET['id'].";");
		$resultset = $database->executeMySQLSP("GetAllBilderByOrdnerSubID",$_GET['id']);
		if(mysqli_num_rows($resultset)) {
			while ($row = mysqli_fetch_object($resultset)) {
				unlink($row->Pfad);
				unlink($row->Thumbnail_Pfad);		
			}
		}
		$database->insertupdateData("DELETE FROM ordner_sub WHERE id = ".$_GET['id'].";");
		$database->insertupdateData("DELETE FROM bilder WHERE ordner_id = ".$_GET['id'].";");
	}
	else{
		$database->insertupdateData("DELETE FROM ordner WHERE ordner.id = ".$_GET['id'].";");
		
		$resultset = $database->receiveData("SELECT * FROM bilder WHERE ordner_id = ".$_GET['id'].";");
		if(mysql_num_rows($resultset)) {
			while ($row = mysql_fetch_object($resultset)) {
				unlink($row->Pfad);
				unlink($row->Thumbnail_Pfad);		
			}
		}
		$database->insertupdateData("DELETE FROM ordner_sub WHERE id = ".$_GET['id'].";");
		$database->insertupdateData("DELETE FROM bilder WHERE ordner_id = ".$_GET['id'].";");
	}
}
if($_GET['action'] == 'picdelete' AND isset($_GET['picid']) AND isset($_GET['picname'])){
	$database->insertupdateData("DELETE FROM bilder WHERE id = ".$_GET['picid'].";");
	unlink($database->GetSetting('PICTURE_PATH').$_GET['picname']);
	unlink($database->GetSetting('THUMBNAIL_PATH').$_GET['picname']);
}

if(!$_GET['ordnerid'] AND $_GET['section']!='pics' AND $_GET['section']!='rss'){
	$resultset = $database->executeMySQLSP("GetAllOrdnerWithSubOrdner","");
	
	// wenn ja gucke nach, ob wir Ergebnisse in der Datenbank haben
	if(mysqli_num_rows($resultset)) {
		echo "<table width='100%' border='0'>"; ?>
				<tr>	
					<td colspan='7' align='left'><b>Übersicht aller Foto-Alben</b>:</td>
					<td>
						<a href='tile_overview.html' onClick=""><img src='images/icons/view_tile.png' onclick="SetCookie('Overview', 'tile' , 999);"></a>
						<a href='list_overview.html' onClick=""><img src='images/icons/view_list.png' onclick="SetCookie('Overview', 'list' , 999);"></a>
					</td>
				</tr>
		<?php
	
		switch($_COOKIE['Overview']){
			case 'tile': 
				/* 
					TO-DO:
					Hier müss die Ansicht der Alben wie in den Alben erfolgen. Sprich es sollen Bilder bzw. ein Vorschaubild angezeigt werden.
					Dieses muss über den Upload festgelegt werden.
				*/
			
				echo "<tr onmouseover=\"this.className='GalleryHover'\" onmouseout=\"this.className=''\">";
				echo "	<td align='right'>&nbsp;</td>";
				echo "	<td align='right'>&nbsp;</td>";
				echo '	<td align="left">&nbsp;</td>';
				echo '	<td align="center">Nicht unterstützt momentan!</td>';
				echo '	<td align="left">&nbsp;</td>';
				echo '	<td align="left" width="30%">&nbsp;</td>'; 
				echo '	<td align="center" width="36">&nbsp;</td>';									
				echo '	<td align="center" width="36"></td>';
				echo "</tr>";
							
				break;
				
			case 'list': 
				echo "<tr onmouseover=\"this.className='GalleryHover'\" onmouseout=\"this.className=''\">";
				echo "	<td width='15px' align='right'>&nbsp;</td>";
				echo "	<td width='15px' align='right'>&nbsp;</td>";
				echo '	<td align="left">&nbsp;</td>';
				echo '	<td align="left">&nbsp;</td>';
				echo '	<td align="left">&nbsp;</td>';
				
				echo '	<td align="left" width="30%">&nbsp;</td>'; 
				echo '	<td align="center" width="36">&nbsp;</td>';									
				echo '	<td align="center" width="36"></td>';
				echo "</tr>";
				
				// Gehe die einzelnen Datensätze durch
				while ($row = mysqli_fetch_object($resultset)) {
					$piccount = $row->Anz_Bilder_inAlbum;
					
					if($lastID != $row->AlbumID){
						echo "<tr onmouseover=\"this.className='GalleryHover'\" onmouseout=\"this.className=''\">";
						
						echo "	<td align='left'><img src='images/icons/folder.png'></td>";
						echo "	<td align='right'>";
						if($row->AlbumID == $_GET['AlbumID']){
							switch($_GET['action']){
								case "collapse": echo "<a href='expand".$row->AlbumID.".html'><img src='images/icons/expand.png' title='Album \"".$row->Albumname."\" aufklappen'></td>"; break;
								case "expand": echo "<a href='collapse".$row->AlbumID.".html'><img src='images/icons/collapse.png' title='Album \"".$row->Albumname."\" zuklappen'></td>"; break;
								default: echo "<a href='expand".$row->AlbumID.".html'><img src='images/icons/expand.png' title='Album \"".$row->Albumname."' aufklappen\"></td>"; break;
							}
						}else{
							echo "<a href='expand".$row->AlbumID.".html'><img src='images/icons/expand.png' title='Album \"".$row->Albumname."\" aufklappen'></td>";
						}
						echo '	<td width="20%" align="left"><span id="markedItem">' . $row->Albumname . '</span></td>';
						echo '	<td align="left">'.$row->AlbumBeschreibung.'</td>';
						echo '	<td align="left">'.$row->Album_Datum.'</td>';
						
						if($piccount==0){ 
							echo '<td align="left" width="30%"><span id="markedItem">0</span> Foto(s) / <span id="markedItem">0</span> Seiten</td>'; 
						}
						elseif(round($piccount/$PicsPerPage,0)  == 0) { 
							echo '<td align="left" width="30%"><b><span id="markedItem">'. $piccount .'</span></b> Foto(s) / <span id="markedItem">1</span></b> Seite</td>'; }
						else { 
							echo '<td align="left" width="30%"><b><span id="markedItem">'. $piccount .'</span></b> Fotos /<b><span id="markedItem">'. ceil($piccount/$PicsPerPage) . '</span></b> Seiten</td>'; 
						}
						if($piccount>0){
							echo '<td align="center" width="36"><a href="pics' . $row->AlbumID. 'p'.$database->GetSetting('DEFAULT_PAGE').'.html"><img src="images/icons/folder_image.png" title="Ordner öffnen"></a></td>';
						}
						elseif($_SESSION['UserRole'] == 3){
							echo '<td align="center" width="36"><a href="upload' . $row->AlbumID.'.html"><img src="images/icons/picture_add.png" title="Bilder hochladen"></a></td>';
						}		
						//
						if($_SESSION['UserRole'] == 3){
							echo '	<td align="center" width="36"><a href="folderdelete' . $row->AlbumID.'.html">
									<img src="images/icons/folder_delete.png" title="Album löschen" onclick="return confirmDelete(\'' . $row->Albumname . '\', \''.$row->Album_Datum.'\')">
									</td>';	
							echo '</tr>';
						}
						
						if($row->UnterAlbumID <> 0 AND isset($_GET['action']) AND $_GET['action']=='expand' AND $row->AlbumID == $_GET['AlbumID'] AND$_SESSION['UserRole'] == 3){
							$result = $database->receiveData("SELECT *,  DATE_FORMAT(Datum,'".$database->GetSetting('SQL_DATELAYOUT')."') as Album_Datum FROM ordner_sub WHERE ordner_sub.ParentID ='".$row->AlbumID."'");
							
							// wenn ja gucke nach, ob wir Ergebnisse in der Datenbank haben
							if(mysql_num_rows($result)) {
								while ($spalte = mysql_fetch_object($result)) {
									
									$result1 = $database->receiveData("SELECT COUNT(ID) as Anz_Bilder_inUnterAlbum FROM bilder WHERE ordner_SubID ='".$spalte->ID."'");
									$spalte1 = mysql_fetch_object($result1);
									$anz_bilder = $spalte1->Anz_Bilder_inUnterAlbum;
									
									echo "<tr onmouseover=\"this.className='GalleryHover'\" onmouseout=\"this.className=''\">";
									echo "	<td align='right'>&nbsp;</td>";
									echo "	<td align='right'><img src='images/icons/option.png'></td>";
									echo '	<td width="20%" align="left">' . $spalte->Name . '</td>';
									echo '	<td align="left">'.$spalte->Beschreibung.'</td>';
									echo '	<td align="left">'.$spalte->Album_Datum.'</td>';
									
									if($anz_bilder==0){ 
										echo '<td align="left" width="30%"><span id="markedItem">0</span> Foto(s) / <span id="markedItem">0</span> Seiten</td>'; 
									}
									elseif(round($anz_bilder/$PicsPerPage,0)  == 0) { 
									echo '	  <td align="left" width="30%"><span id="markedItem">'. $anz_bilder .'</span> Foto(s) / <span id="markedItem">1</span> Seite</td>'; }
									else { 
										echo '<td align="left" width="30%"><span id="markedItem">'. $anz_bilder .'</span> Fotos /<span id="markedItem">'. ceil($anz_bilder/$PicsPerPage) . '</span> Seiten</td>'; 
									}
									if($anz_bilder>0){
										echo '<td align="center" width="36"><a href="pics' . $spalte->ID. 'p'.$database->GetSetting('DEFAULT_PAGE').'subfolder.html"><img src="images/icons/folder_image.png" title="Ordner öffnen"></a></td>';
									}
									elseif($_SESSION['UserRole'] == 3){
										echo '<td align="center" width="36"><a href="upload' . $spalte->ID.'subfolder.html"><img src="images/icons/picture_add.png" title="Bilder hochladen"></a></td>';
									}		
									//
									if($_SESSION['UserRole'] == 3){
										echo '	<td align="center" width="36"><a href="folderdelete' . $spalte->ID.'subfolder.html">
												<img src="images/icons/folder_delete.png" title="Album löschen" onclick="return confirmDelete(\'' . $spalte->Name . '\', \''.$spalte->Album_Datum.'\')">
												</td>';
										echo "</tr>";
									}
									
								}
							}
						}
						$lastID = $row->AlbumID;
					}
				}
				break;
		}
		
	}	
	else
	{
		echo "<tr><td colspan='8'>Es wurden bisher keine Ordner angelegt!";
	}
	if($_SESSION['UserRole'] == 3){
		echo "	<tr><td colspan='8'>&nbsp;</tr></td>
				<tr>
					<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
					<td colspan='4' align='right' style='padding-right:10px;'>
						<a href='addfolder.html'>
							Album hinzufügen <img src='images/icons/folder_add.png'>
						</a>
					</td>
				</tr>";	
	}
	else{
		echo "	<tr><td colspan='8'>&nbsp;</tr></td>
			<tr>
				<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
				<td colspan='4' align='right' style='padding-right:10px;'>
					&nbsp;
				</td>
			</tr>";	
	}
	echo "</table>";	
}

/**
 * Übersicht der einzelnen Bilder eines Albums
 **/

elseif($_GET['section']=='pics'){
	if($_GET['mode']=='subfolder'){
		$Albumname  = mysqli_fetch_object($resultset = $database->executeMySQLSP("GetBildCountByOrdnerSubID",$_GET['id']));
	}
	else{
		$Albumname  = mysqli_fetch_object($resultset = $database->executeMySQLSP("GetBildCountByOrdnerID",$_GET['id']));
	}
	
	
	echo "	<div align='left'>
				<b>
					Inhalt des Ordners '<span id='markedItem'>".$Albumname->Name."</span>' (".$Albumname->Anzahl_Bilder." Bilder):
				</b>
			</div><br>";
	if($_SESSION['UserRole'] == 3){
		echo "	<td align='center' width='36'><a href='upload" . $_GET['id'].$_GET['mode'].".html'><img src='images/icons/picture_add.png' title='Bilder hochladen'></a>";
	}
	else{
		echo "	<td align='center' width='36'>&nbsp;</a>";
	}
	if($database->GetSetting('ALLOW_RSSFEEDS')=="true"){
		if($_GET['mode'] != 'subfolder'){
			echo "	<td align='center' width='36'>
						<a href='rss".$_GET['id'].".html' target='_blank'>
							<img src='images/icons/rss_add.png' title='RSS-Feed des Ordners " . $row->Name . " abonieren'>
						</a>
					</td>";
		}
		else{
			echo "	<td align='center' width='36'>
					<a href='rss".$_GET['id']."subfolder.html' target='_blank'>
						<img src='images/icons/rss_add.png' title='RSS-Feed des Ordners " . $row->Name . " abonieren'>
					</a>
				</td>";
		}
		
	}
	
	// Zeige '$PicsPerPage' Bilder pro Seite an. Davon "$spalten" Spalten und "$zeilen" Zeilen
	$PicsPerPage = $database->GetSetting('COLS_PER_PAGE') * $database->GetSetting('ROWS_PER_PAGE');;
	$spalten	 = $database->GetSetting('COLS_PER_PAGE');
	$zeilen		 = $database->GetSetting('ROWS_PER_PAGE');
	
	// Als Startseite soll die 'DEFAULT_PAGE' angezeigt werden
	$pageNum = $database->GetSetting('DEFAULT_PAGE');
	
	// Prüfe ob eine Seite im Query-String gesetzt wurde
	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	
	// Errechne das Offset
	$offset = ($pageNum - 1) * $PicsPerPage;
	if($_GET['mode']=='subfolder'){
		$row     = mysql_fetch_object($database->receiveData("SELECT COUNT(id) as numrows FROM bilder WHERE Ordner_SubID = ".$_GET['id'].""));
	}
	else{
		$row     = mysql_fetch_object($database->receiveData("SELECT COUNT(id) as numrows FROM bilder WHERE Ordner_ID = ".$_GET['id'].""));
	}
	
	$numrows = $row->numrows;
	
	/* Wieviele Seiten hätten wir, wenn wir das Paging benutzen? */
	$maxPage = ceil($numrows/$PicsPerPage);
	
	/* Setze ein SQL-Statement gegen das Database-Objekt ab... */
	if($_GET['mode']=='subfolder')
	{
		if($database->GetSetting('ALLOW_COMMENTS') == false AND $database->GetSetting('EXTEND_PIC_DETAILS')=="true"){
		$resultset = $database->receiveData("
					SELECT  *, 
						date_format(Bild_Datum, '".$database->GetSetting('SQL_DATELAYOUT')."') as Datum 
					FROM bilder 
					WHERE Ordner_SubID = ".$_GET['id']." 
					ORDER BY bilder.ID ASC 
					LIMIT ".$offset.", ".$PicsPerPage.";");
		}
		else{
			$resultset = $database->receiveData("
						SELECT  *, 
							bilder.ID as BildID,
							bilder.Titel as Titel,
							date_format(Bild_Datum, '".$database->GetSetting('SQL_DATELAYOUT')."') as Datum, 
							count(kommentare.Bild_ID ) AS Anzahl_Kommentare
						FROM bilder LEFT OUTER JOIN kommentare ON bilder.ID = kommentare.Bild_ID 
						WHERE Ordner_SubID = ".$_GET['id']."
						 
						GROUP BY bilder.ID
						ORDER BY bilder.ID ASC
						LIMIT ".$offset.", ".$PicsPerPage.";");
		}
	}
	else{
		if($database->GetSetting('ALLOW_COMMENTS') == false AND $database->GetSetting('EXTEND_PIC_DETAILS')=="true"){
		$resultset = $database->receiveData("
					SELECT  *, 
						date_format(Bild_Datum, '".$database->GetSetting('SQL_DATELAYOUT')."') as Datum 
					FROM bilder 
					WHERE Ordner_ID = ".$_GET['id']." 
					ORDER BY bilder.ID ASC 
					LIMIT ".$offset.", ".$PicsPerPage.";");
		}
		else{
			$resultset = $database->receiveData("
						SELECT  *, 
							bilder.ID as BildID,
							bilder.Titel as Titel,
							date_format(Bild_Datum, '".$database->GetSetting('SQL_DATELAYOUT')."') as Datum, 
							count(kommentare.Bild_ID ) AS Anzahl_Kommentare
						FROM bilder LEFT OUTER JOIN kommentare ON bilder.ID = kommentare.Bild_ID 
						WHERE Ordner_ID = ".$_GET['id']."
						 
						GROUP BY bilder.ID
						ORDER BY bilder.ID ASC
						LIMIT ".$offset.", ".$PicsPerPage.";");
		}
	}
	
		
	/* wenn ja gucke nach, ob wir Ergebnisse in der Datenbank haben */
	if(mysql_num_rows($resultset)) {
		echo "<div align='center'>";
		echo "<table cellspacing='10' cellpadding='5'>";
		// Gehe die einzelnen Datensätze durch
		echo "<tr>";
		
		/* Zählervariable auf 0 setzen */
		$i = 0;
		
		while ($row = mysql_fetch_object($resultset)) {
			if($database->GetSetting('EXTEND_PIC_DETAILS') == "true"){
				if($database->GetSetting('ALLOW_RATING') == "true"){
					if($row->Bewertung = 0){
						$bewertung = "<img src='images/icons/medal_silver_add.png ' alt='Bewertung hinzufügen' title='Bewertung hinzufügen'>";
					}
					elseif($row->Bewertung < 3){
						$bewertung = "<img src='images/icons/medal_bronze_2.png' alt='Bronze (".$row->Bewertung.")' title='Bronze (".$row->Bewertung.")'>";
					}
					elseif($row->Bewertung < 6){
						$bewertung = "<img src='images/icons/medal_silver_2.png' alt='Silber (".$row->Bewertung.")' title='Silber (".$row->Bewertung.")'>";
					}
					elseif($row->Bewertung <= 10){
						$bewertung = "<img src='images/icons/medal_gold_2.png' alt='Gold (".$row->Bewertung.")' title='Gold (".$row->Bewertung.")'>";
					}
					$bewertung_Ausgabe = $bewertung." (".$row->Bewertung_Anz.")"; 
				}
				else{
					$bewertung_Ausgabe = "&nbsp;";
				}
				if($database->GetSetting('ALLOW_COMMENTS') == "true"){
					$kommentare_ausgabe = "<a href='comments".$row->BildID.".html'><img src='images/icons/comment.png'> (".$row->Anzahl_Kommentare.")</a>";
				}
				else{
					$kommentare_ausgabe = "&nbsp;";
				}
				$extend_details  = 	"			<td align='right'>".$bewertung_Ausgabe."</td>
											</tr>
											<tr>
												<td align='left'>".$row->Groesse." kB</td>
												<td align='left' colspan='2'>
													".$kommentare_ausgabe."
												</td>
											</tr>
												";
			}
			else
			{
				$extend_details = "</tr>";
			}
						
			/* Zähler +1, da wir einen Datensatz ausgegeben haben */
			$i += 1; 
			$aktuelleReihe = ceil($i/$spalten);
			if($i<=$PicsPerPage){
				/* 	<a href='".PICTURE_PATH.$row->Hash.$row->Dateityp."' title='".$row->Titel."' class='thickbox' rel='".$Albumname->Name."' > */
				echo "	<td id='PictureFrame'>
						
							<a href='".$database->GetSetting('PICTURE_PATH').$row->Hash.$row->Dateityp."' title='".$row->Titel."' class='thickbox' rel='".$Albumname->Name."'>
								<img id='Picture' src='".$database->GetSetting('THUMBNAIL_PATH').$row->Hash.".".$row->Dateityp."' border='0' alt='".$row->Name."' />
							</a>
							<table width='100%'  border='0'>
								<tr>
									<td align='left'>". substr($row->Titel,$database->GetSetting('PICTILE_START_CUTOFF'),$database->GetSetting('PICTILE_STOP_CUTOFF')) ."...</td>
									";
				if($_SESSION['UserRole'] == 3){
					echo $extend_details.'
							<td align="right" colspan="2">
							<a href="picdelete'.$row->BildID.'folder'.$_GET['id'].'p'.$pageNum.'_'.$row->Hash.$row->Dateityp.'.html">
							<img src="images/icons/picture_delete.png" onclick="return confirmDelete(\'' . $row->Titel . '\', \''.$row->Datum.'\')">
							</a>
							</td>
							</table>
							</td>';
				}
				else{
					echo $extend_details.'
							<td align="right" colspan="2">
							&nbsp;
							</td>
							</table>
							</td>';
				}	
			}
					
			/* Prüfen ob wir das maximale Anzahl der Spalten erreicht haben. Wenn ja brech die Zeile um. */
			if($i>=($spalten*$aktuelleReihe)){
				echo "</tr>";
			}
		}

		echo "</table>";	
		echo "</div>";
		// inc/create_pic.php?dir=".$_GET['dir']."&id=".($n*$spalten+$i+$_GET['seite']*$PicsPerPage)."&thumb
	}
	
	
	/*
		Berechnungen für das Paging
	*/
					
	if ($pageNum > 1)
	{
		$page  = $pageNum - 1;
		$prev  = " <a href='pics".$_GET['id']."p".$page.".html'>".$database->GetSetting('PREV_SYMBOL')."</a> ";		
		$first = " <a href='pics".$_GET['id']."p1.html'>".$database->GetSetting('FIRST_SYMBOL')."</a> ";
	}
	else
	{
		$prev  = ''; /* Wir sind auf der ersten Seite, wir brauchen keinen vorherigen Link...*/
		$first = ''; /* ... und wir brauchen keinen ersten Link.*/
	}
	
	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next = " <a href='pics".$_GET['id']."p".$page.".html'>".$database->GetSetting('NEXT_SYMBOL')."</a> ";
		$last = " <a href='pics".$_GET['id']."p".$maxPage.".html'>".$database->GetSetting('LAST_SYMBOL')."</a> ";
	}
	else
	{
		$next = ''; /* Wir sind auf der letzten Seite, wir brauchen keinen nächsten Link...*/
		$last = ''; /* ... und wir brauchen keinen letzten Link.*/
	}
	
	/* Ausgabe der Paging-Links*/
	if($numrows > 0 AND $_GET['action'] != 'edit')
	{
		$pageing = $first . $prev . " Seite <b><span id='markedItem'>".$pageNum."</span></b> von <b><span id='markedItem'>".$maxPage."</b></span> " . $next . $last;
	}
	echo "<div id='pageing'>".$pageing."<br><div id='back2Uebersicht'><a href='index.php'>Zurück zur Übersicht</a></div></div>";
}					
?>