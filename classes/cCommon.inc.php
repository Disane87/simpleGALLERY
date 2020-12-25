<?php

/* 
	Class Common
	Author:			Marco Franke
	Date:			22.12.2008
	Last Change:	17.04.2009
*/


/**
 * The Class "Common" have the most usefull functions in a package.
 *
 */
class cCommon {
	
	/* Eigenschaften für die Messung der Renderzeit */
	var $Anfangszeit;
	var $Endzeit;
	var $renderTime;
	var $DBobj;
	
	var $monate;
	
	/* Eigenschaften für die Bilder-Methoden */
	var	$url;
	var	$info;
	var	$width; 
	var	$height; 
	var	$type;
	
	/* GET & SET */
	public function getPicWidth()		{ return $width; }
	public function getPicHeight()		{ return $height; }
	public function getPicInfo()		{ return $info; }
	public function setDBObj($dbobj)	{ 
		$this->DBobj = $dbobj; 
	}
	
	
	public function getMonthsLong(){
		$monate = array(
				1=>"Januar",
				2=>"Februar",
				3=>"M&auml;rz",
				4=>"April",
				5=>"Mai",
				6=>"Juni",
				7=>"Juli",
				8=>"August",
				9=>"September",
				10=>"Oktober",
				11=>"November",
				12=>"Dezember");
				
		return $monate;
	}
	public function getMonthsShort(){
		$monate = array(
				1=>"Jan",
				2=>"Feb",
				3=>"M&auml;r",
				4=>"Apr",
				5=>"Mai",
				6=>"Jun",
				7=>"Jul",
				8=>"Aug",
				9=>"Sep",
				10=>"Okt",
				11=>"Nov",
				12=>"Dez");
		
		return $monate;
	}
	
	public function getAktuellerMonat()
	{
		$datum	= date("n");
		return $datum;
	}
	public function getAktuellesJahr()
	{
		$datum	= date("Y");
		return $datum;
	}
	
	public function setAnfangszeit(){
		$this->Anfangszeit = $this->getmicrotime();
	}
	public function setEndzeit(){
		$this->Endzeit = $this->getmicrotime();
	}
	public function getRendertime($round_precision){
		return round($this->Endzeit - $this->Anfangszeit,$round_precision);
	}
	
	/**
	 * This function returns ja "clean" user input to save the Database for SQL-Injection
	 *
	 * @param string $UserInput This is the User-In put to validate
	 * @return mixed The is the cleaned up and escaped User-Input
	 *
	 */
	public function safeEscapeString($UserInput)
	{
		if (get_magic_quotes_gpc()) {
			return $UserInput;
		} else {
			return mysql_real_escape_string($UserInput);
		}
	} 
	
	public function getmicrotime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	public function createThumb($bild, $BildName, $thumbnail_path, $thumbnail_width, $thumbnail_height)
	{
		$this->url    	= $bild;
		$this->info  	= getimagesize($this->url);
		$this->width  	= $this->info[0];
		$this->height 	= $this->info[1];
		$this->type  	= $this->info[2];
		
		if ($this->type == 1){
			$image = imagecreatefromgif($this->url);
		}
		else if ($this->type == 2){
			$image = imagecreatefromjpeg($this->url);
		}
		else if ($this->type == 3){
			$image = imagecreatefrompng($this->url);
		}
		$nwidth  = $thumbnail_width;
		$nheight = $this->height/($this->width/$nwidth);
		
		$bheight = $thumbnail_height;
		$image2  = imagecreatetruecolor($nwidth, $nheight);
		imagecopyresampled($image2, $image, 0, 0, 0, 0, $nwidth, $nheight, $this->width, $this->height);
		
		if ($this->type == 1){
			imagegif($image2, $thumbnail_path.$BildName);
		}
		else if ($this->type == 2){
			imagejpeg($image2, $thumbnail_path.$BildName);
		}
		else if ($this->type == 3){
			imagepng($image2, $thumbnail_path.$BildName);
		}
		if ($nheight > 75) 
		{
			$bheight = 75;
			$npos = ($nheight - $bheight) / 2;
			$image3  = imagecreatetruecolor($nwidth, $bheight);
			imagecopyresampled($image3, $image2, 0, 0, 0, $npos, $nwidth, $bheight, $nwidth, $bheight);
			if ($this->type == 1){
				imagegif($image3, $thumbnail_path.$BildName);
			}
			else if ($this->type == 2){
				imagejpeg($image3, $thumbnail_path.$BildName);
			}
			else if ($this->type == 3){
				imagepng($image3, $thumbnail_path.$BildName);
			}
			imagedestroy($image3);
		}
		imagedestroy($image);
		imagedestroy($image2);
	} 
	public function insertWatermark($bildhandler, $type, $dest){
		$imgzeichen = imagecreatefrompng($this->DBobj->GetSetting('WATERMARK_PATH'));
		
		$width	= imagesx($bildhandler);
		$height = imagesy($bildhandler);
		
		// Wasserbildhöhe und Breite auslesen
		$widthwater = imagesx($imgzeichen);
		$heightwater = imagesy($imgzeichen);
		
		// Bilder erzeugen
		$img = imagecreatetruecolor($width, $height);
		
		// Bild einfügen
		imagecopy($img, $bildhandler, 0, 0, 0, 0, $width, $height);
		
		// Wasserzeichen einfügen
		//imagecopy($img, $imgzeichen, 0, 0, 0, 0, 250, 50);
		
		imagecopy($img,	$imgzeichen, $width-$widthwater, $height-$heightwater, 0, 0, $widthwater, $heightwater);
		
		// Bild speichern
		switch(strtolower($type))
		{
			case "image/gif":	imagegif($img, $dest);	break;
			case "image/jpeg":	imagejpeg($img, $dest);	break;
			case "image/png":	imagepng($img, $dest);	break;
			case "image/pjpeg":	imagejpeg($img, $dest);	break;
			
			/* Nötig für dann Upload von ZIP-Dateien */
			case "gif":			imagegif($img, $dest);	break;
			case "jpeg":		imagejpeg($img, $dest);	break;
			case "png":			imagepng($img, $dest);	break;
			case "jpg":			imagejpeg($img, $dest);	break;
		}
		imagedestroy($img);
	}
	
	public function moveorunlinkPicture($pfad, $dest, $name, $hash, $dateiendung, $zipupload)
	{
		switch($zipupload){
			case 1:
				$dateiendungUcase	= strtoupper($dateiendung);
				if (file_exists($this->DBobj->GetSetting('PICTURE_PATH').$hash.".".$dateiendung)){
					unlink($pfad.$name.".".$dateiendung);
				}
				$bname = $name;
				rename($dest.$name.".".$dateiendung, $pfad.$hash );
				
				switch($dateiendungUcase)
				{
					case 'JPG':		$imgsrc = imagecreatefromjpeg($pfad.$hash);  break;
					case 'GIF':		$imgsrc = imagecreatefromgif($pfad.$hash); break;
					case 'PNG':		$imgsrc = imagecreatefrompng($pfad.$hash); break;
					case 'JPEG':	$imgsrc = imagecreatefromjpeg($pfad.$hash); break;
					case 'PJPEG':	$imgsrc = imagecreatefromjpeg($pfad.$hash); break;
				}
				return $imgsrc;	
				break;
			case 0:
				$dateiendungUcase	= strtoupper($dateiendung);
				if (file_exists($this->DBobj->GetSetting('PICTURE_PATH').$hash.".".$dateiendung)){
					unlink($pfad.$name.".".$dateiendung);
				}
				$bname = $name;
				rename($pfad.$name, $pfad.$hash.".".$dateiendung );
				
				switch($dateiendungUcase)
				{
					case 'JPG':		$imgsrc = imagecreatefromjpeg($pfad.$hash.".".$dateiendung);  break;
					case 'GIF':		$imgsrc = imagecreatefromgif($pfad.$hash.".".$dateiendung); break;
					case 'PNG':		$imgsrc = imagecreatefrompng($pfad.$hash.".".$dateiendung); break;
					case 'JPEG':	$imgsrc = imagecreatefromjpeg($pfad.$hash.".".$dateiendung); break;
					case 'PJPEG':	$imgsrc = imagecreatefromjpeg($pfad.$hash.".".$dateiendung); break;
				}
				return $imgsrc;	
				break;
		}
			
	}
	
	public function GetTime($time)
	{
		$timestamp = $time;
		$datum = date("d.m.Y",$timestamp);
		$uhrzeit = date("H:i",$timestamp);
		return $datum." - ".$uhrzeit;
	}
	
	public function GetYear()
	{
		$timestamp = time();
		$jahr = date("Y",$timestamp);
		return $jahr;
	}  
	
	public function alter($gebtag, $gebmonat, $gebjahr) {
		//Geburtstag in Timestamp konvertieren
		//list($gebjahr, $gebmonat, $gebtag) = explode("-", $geburtsdatum);
		$geburt = mktime(0,0,0,$gebmonat,$gebtag,$gebjahr);
		// Aktuelles Datum als Timestamp
		$aktuell=time();
		// Millisekunden seid Geburt
		$msek = $aktuell - $geburt;
		//Alter in Tagen
		$tage = floor($msek/(3600*24));
		//Alter in Jahren
		$jahr = floor($tage/365);
		$gesamt = $gebjahr+$jahr;
		// Schaltjahre berücksichtigen (wenn die Jahreszahl durch 4 teilbar, dann Schaltjahr)
		$i=0;
		for($gebjahr; $gebjahr < $gesamt; $gebjahr++) {
			if($gebjahr % 4 == 0) {
				$i=$i+1;
			}
		}
		if ($tage-((365*$jahr)+$i) < 0) {
			$jahr--;
		}
		return $jahr;
	}
	
	public function Convert2Date($timestamp)
	{
		$Date = array(date("d",$timestamp),date("m",$timestamp),date("Y",$timestamp));
		
		return $Date;
	}
	
	
	/**
	 * ZIP-Funktionalität
	 **/
	
	public function unzip($file, $path, $filename) {
		/*$zip = zip_open($file);
		echo "<br>".$file." - ".$path." - ".$zip."<br>";
		if ($zip) {
			while ($zip_entry = zip_read($zip)) {
				if (zip_entry_filesize($zip_entry) > 0) {
					// str_replace must be used under windows to convert "/" into "\"
					$complete_path = $path.str_replace('/','\\',dirname(zip_entry_name($zip_entry)));
					$complete_name = $path.str_replace ('/','\\',zip_entry_name($zip_entry));
					if(!file_exists($complete_path)) {
						$tmp = '';
						foreach(explode('\\',$complete_path) AS $k) {
							$tmp .= $k.'\\';
							if(!file_exists($tmp)) {
								mkdir($tmp, 0777);
							}
						}
					}
					if(zip_entry_name($zip_entry) != dirname(zip_entry_name($zip_entry)).'/Thumbs.db' AND zip_entry_name($zip_entry) != dirname(zip_entry_name($zip_entry)).'/photothumb.db'){
						if (zip_entry_open($zip, $zip_entry, "r")) {
							$fd = fopen($complete_name, 'w');
							fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
							fclose($fd);
							zip_entry_close($zip_entry);
						}
					}
				}
				echo zip_entry_name($zip_entry)." (".dirname(zip_entry_name($zip_entry)).'\Thumbs.db)<br>';
			}
			zip_close($zip);
		}*/
		
		
		if (function_exists('zip_open'))
		{
			$ZIP_Path = $_SERVER['DOCUMENT_ROOT']."/".$file;
			/* Absolute Pfadangabe ist hier erforderlich! */
			$zip_datei = $ZIP_Path;
			
			/* relative Pfadangabe mit abschließendem Slash " / " */
			$ziel_ordner = $path.str_replace('.zip','', $filename)."/";
			
			
			if (file_exists($zip_datei) && ($zip = zip_open($zip_datei)))
			{
				while($zip_entry = zip_read($zip))
				{
					$file_name = zip_entry_name($zip_entry);
					$file_size = zip_entry_filesize($zip_entry);
					$comp_meth = zip_entry_compressionmethod($zip_entry);
					
					if (zip_entry_open($zip, $zip_entry, 'rb'))
					{
						$buffer = zip_entry_read($zip_entry, $file_size);
						@mkdir($ziel_ordner, 0777);
						if (preg_match('/\/$/', $file_name) && ($comp_meth == 'stored'))
						{
							if (!is_dir($ziel_ordner . $file_name))
								@mkdir($ziel_ordner . $file_name, 0777);
						}
						else
						{
							$fp = fopen($ziel_ordner . $file_name, 'wb');
							fwrite($fp, $buffer);
							fclose($fp);
						}
						
						zip_entry_close($zip_entry);
					}
				}
				
				zip_close($zip);
			}
			else
				echo 'Konnte die Datei <font color="#ff0000">' . basename($zip_datei) . '</font> nicht öffnen!';
		}
		else
			echo   'Bitte aktivieren Sie in der php.ini die Extensions '
				. '<font color="#ff0000">php_zip.dll</font> in dem sie '
				. 'das Semikolon vor dieser Zeile <font color="#ff0000"><b>;</b></font>'
				. '<font color="#0000ff">extension=php_zip.dll</font> entfernen.';
		
	}
	public function zip($datei, $outputfile){
		$dateihandle = fopen($datei,r); //Hier das Bild angeben
		$bild = fread($dateihandle,1024000); //Da wird das Bild ausgelesen (bis zu 1MB atm)
		
		$zipfile = new zipfile();
		
		// add the subdirectory ... important! 
		$zipfile -> add_dir("/");
		
		// add the binary data stored in the string 'filedata' 
		$filedata = $bild;
		$zipfile -> add_file($filedata, $datei);
		
		// OR instead of doing that, you can write out the file to the loca disk like this: 
		$filename = $outputfile; 
		$fd = fopen ($filename, "wb"); 
		$out = fwrite ($fd, $zipfile -> file());
		fclose ($fd); 
		
		// then offer it to the user to download: 
		echo "<a href=output.zip>Click here to download the new zip file.</a> ";
		
	}
}

?>