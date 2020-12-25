<?php
/* 
	Security-Class
	Author:			Marco Franke
	Date:			17.04.2008
	Last Change:	17.04.2009
*/
class cDatabase
{
	var $host; 
	var $password; 
	var $username;
	var $userid;
	var $database;
	var $connState;
	var $queryCount;
	
	// Konstruktor & Destruktor
	public function __construct($DBHost, $DBPassword, $DBUsername, $DBName){
		$this->setProperties($DBHost, $DBPassword, $DBUsername, $DBName);
		$this->connect();
	}
	
	// Wird nicht aufgerufen von PHP4
	public function __destruct(){
		mysql_close();
	}
	
	// Get & Set
	public function getConnState()
	{
		return $this->connState;
	}
	public function getUserID()
	{
		return $this->userid;
	}
	
	public function getQueryCount()
	{
		return $this->queryCount;
	}
	public function setQueryCount($QueryCount)
	{
		$this->queryCount = $QueryCount;
	}
	
	public function setUserID($UserID)
	{
		$this->userid = $UserID;
	}
	
	public function setProperties ($DBHost, $DBPassword, $DBUsername, $DBName){
		$this -> host		= $DBHost;
		$this -> password	= $DBPassword;
		$this -> username	= $DBUsername;
		$this -> database	= $DBName;
	}
	
	private function connect(){
		try{
			if(mysql_connect($this->host, $this->username,$this->password ))
			{
				mysql_select_db($this->database);
				$this-> connState = true;	
				return $this->connState;
			}
			else
			{
				throw new Exception("Verbindung zur Datenbank konnte nicht hergestellt werden!");
				$this-> connState = false;	
				return $this->connState;
			}
		}
		catch(Exception $e)
		{
			echo "<div id='error'><b>Fehler: </b><br>".$e->getMessage()."<br><br><b>Datei:</b><br>'".$e->getFile()."'<br><br><b>Zeile:</b><br>".$e->getLine().'</div>';
		}
		
	}
	public function receiveData($Statement)
	{
		try
		{	
			if( $this->connState == true)
			{
				if (!$result = mysql_query($Statement)) { 
					throw new Exception("Ungültige Abfrage: ".mysql_error());
				}
				else
				{
					$count = $this->getQueryCount();
					$resultset = mysql_query($Statement);
					$this->setQueryCount($count+1);
					return $resultset;
				}
			}
			else
			{
				return false;
			}
		}
		catch(Exception $e){
			echo "<div id='error'><b>Fehler: </b><br>".$e->getMessage()."<br><br><b>Datei:</b><br>'".$e->getFile()."'<br><br><b>Zeile:</b><br>".$e->getLine().'</div>';
		}	
	}
	
	public function executeMySQLSP($Spname, $Parameter)
	{
		try{
			$mysqli = new mysqli($this->host, $this->username, $this->password, $this->database);
			
			$query = "Call ".$Spname."(".$Parameter.")";
			if ($result = $mysqli->query($query)) 
			{
				return $result;
			}
			mysqli_close($mysqli);
		}
		catch(Exception $e){
			echo "<div id='error'><b>Fehler: </b><br>".$e->getMessage()."<br><br><b>Datei:</b><br>'".$e->getFile()."'<br><br><b>Zeile:</b><br>".$e->getLine().'</div>';
		}	
	}
	
	public function insertupdateData($Statement)
	{
		try
		{
			if( $this->connState == true)
			{
				$count = $this->getQueryCount();
				$resultset = mysql_query($Statement);
			}
		}
		catch(Exception $e){
			echo "<div id='error'><b>Fehler: </b><br>".$e->getMessage()."<br><br><b>Datei:</b><br>'".$e->getFile()."'<br><br><b>Zeile:</b><br>".$e->getLine().'</div>';
		}	
	}
}
?>