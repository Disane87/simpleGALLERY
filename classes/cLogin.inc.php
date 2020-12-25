<?php
/* 
	Login Class
	Author:			Marco Franke
	Date:			22.12.2008
	Last Change:	18.04.2009
*/

class cLogin {
	var $name; 
	var $password; 
	var $logindate;
	var $errormessage = array(); 

	
	// Konstruktor	
	//
	public function __construct(){
		
	}
	
	// Get & Set
	//
	public function setName ($Name){
		$this -> name = $Name;
	}
	public function getName (){
		$Name = $this -> name;
		return $Name;
	}
	
	public function setPassword ($Password){
		$this -> password = $Password;
	}
	
	public function setLogindate ($page_timelayout){
		$this -> logindate = date($page_timelayout, time());
	}
	public function getLogindate (){
		$Logindate = $this -> logindate;
		return $Logindate;
	}
	public function getErrorMessages()
	{
		return $this->errormessage;
	}
	public function setErrorMessage($ErrorMessage)
	{
		array_push($this->errormessage,$ErrorMessage);
	}
	
	// Methoden
	//
	public function logoutUser(){
		session_destroy();
		session_unset();
		$this->logindate = NULL;
		$_SESSION = array();
	}
	public function setData($sessionPwd, $sessionUsrname){
		$this -> setName($sessionUsrname);
		$this -> setPassword($sessionPwd);
	}
	
	public function validateData($Pwd, $Usrname, $DatabaseObj, $pw_minsize, $page_timelayout){
		//$resultset = $DatabaseObj -> receiveData("SELECT * FROM users WHERE Nick = lower('".$Usrname."') AND Aktiv = 1");
		$resultset = $DatabaseObj->executeMySQLSP("GetUserDataByID","'".$Usrname."'");
		/*if($resultset)
		{*/
		
		$Pw  = md5($Pwd);
		$nick = strtolower($Usrname);
		$len = strlen($Pwd);
		
			while ($row = mysqli_fetch_object($resultset)) {
			if(($row->Passwort == md5($Pwd) AND strtolower($row->Nick) == strtolower($Usrname) AND strlen($Pwd) > $pw_minsize)) 
				{
					if(!isset($_SESSION['UsrID'])){
						$this->setLogindate($page_timelayout);
						$_SESSION['UsrID']		= $row->ID;
						$_SESSION['UsrName']	= $row->Nick;
						$_SESSION['UsrPw']		= $row->Passwort;
						$_SESSION['LoginDate']	= $this->logindate;
						$_SESSION['UserRole']	= $row->Role_ID;
						$this->validateState	= true;
					}
					else{
						$this->setErrorMessage("Du bist bereits angemeldet!");
					}				
				}
				elseif(strlen($Pwd) <= 0){
					$this->validateState = false;
					$this->setErrorMessage("Dein Passwort ist zu kurz!");
				}
				else{
					$this->validateState = false;
					$this->setErrorMessage("Deine Anmeldedaten stimmen nicht überein!");
					
				}
			}	
		/*}*/
		/*else{
			$this->setErrorMessage('Etwas stimmte mit der SQL-Verbindung nicht nicht!'.$resultset->error);
		}*/
	}

	public function Login($Pwd, $Usrname, $DatabaseObj, $pw_minsize, $page_timelayout)
	{
		$validateState = $this -> validateData($Pwd,$Usrname, $DatabaseObj, $pw_minsize, $page_timelayout);
		if($this->validateState == true)
		{
			$this  -> setLogindate($page_timelayout);
			return true;
		}
		else
		{
			$this->setErrorMessage("Deine Anmeldedaten konnten nicht verifiziert werden. Bitte überprüfe deine Eingaben!");
			return false;	
		}	
	}
}
?>