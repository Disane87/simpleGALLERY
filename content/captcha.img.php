<?php
    // Erstellen eine 40 mal 100 px großen Bildes
	$captcha_width	= 120;
	$captcha_height	= 40;
	$captcha_font	= "TTF/verdana.ttf";
    $bild			= imagecreate($captcha_width,$captcha_height);
	
    // Farben definieren
    $schwarz = imagecolorallocate($bild,0,0,0);
    $weiss = imagecolorallocate($bild,255,255,255);
	
    // Schrift einfügen
    //imagestring($bild,20,20,10,$wert,$weiss);
    // Störlinien setzen
    
    imageline($bild,0,10,120,30,$weiss);
    imageline($bild,20,0,80,40,$weiss);
	imageline($bild,30,0,10,40,$weiss);
	imageline($bild,0,10,10,40,$weiss);
	imageline($bild,0,30,120,5,$weiss);

	
    // Type im Header definieren und Bild ausgeben
    header("Content-Type: image/jpeg");
	imagettftext($bild, "20", "0", "10", "30", $weiss, $captcha_font , $_GET['wert']);
    imagejpeg($bild);
	
    // Bild löschen
    imagedestroy($bild);
?>