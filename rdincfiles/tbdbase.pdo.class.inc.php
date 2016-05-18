<?php

if (!isset($_SESSION["SESSION"])) require ( "rdconfig.inc.php");

class TBDBase
{
	public $dbhandler = NULL;

	//$connectionType = 0 if we want to create a LOW CREDENTIAL connection to the DB
	//$connectionType = 1 if we want to find the appropriate credentials sto connect to the the DB
	function __construct($connectionType)
	{
		$validationResult = NULL;
		$validateCredentials = validate_user_login();

	if($connectionType == 0){
		try{
			$this->dbhandler = new PDO("mysql:host=".TBDB_HOST.";dbname=".TBDB_DATABASE, TBDB_COMMON_USER, TBDB_COMMON_PASSWORD);
			$this->dbhandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
		catch(PDOException $e){$this->catch_handler($e);}
	}//if
	else
	{
		switch($validateCredentials)
		{
			case 'administrator':
				$validationResult = validate_login_credentialsAdmin();
				if($validationResult['usertype']!='administrator'){ user_logout('ajax');}
				break;
			case 'registereduser':	
				//create a temp connection to the database
				try{
					$this->dbhandler = new PDO("mysql:host=".TBDB_HOST.";dbname=".TBDB_DATABASE, TBDB_COMMON_USER, TBDB_COMMON_PASSWORD);
					$this->dbhandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
				catch(PDOException $e){$this->catch_handler($e);}
				
				$validationResult = validate_login_credentialsRegisteredUser();
				if($validationResult['usertype']!='registereduser'){user_logout('ajax');}
				else
				{
					$this->dbhandler = NULL; //close the temp connection to the DB
					try{
						$this->dbhandler = new PDO("mysql:host=".TBDB_HOST.";dbname=".TBDB_DATABASE, TBDB_REGISTERED_USER, TBDB_REGISTERED_PASSWORD);
						$this->dbhandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
					catch(PDOException $e){$this->catch_handler($e);}
				}//
				break;
			case 'unregistereduser':
				try{
					$this->dbhandler = new PDO("mysql:host=".TBDB_HOST.";dbname=".TBDB_DATABASE, TBDB_COMMON_USER, TBDB_COMMON_PASSWORD);
					$this->dbhandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
				catch(PDOException $e){$this->catch_handler($e);}
				break;
			default:
				try{
					$this->dbhandler = new PDO("mysql:host=".TBDB_HOST.";dbname=".TBDB_DATABASE, TBDB_COMMON_USER, TBDB_COMMON_PASSWORD);
					$this->dbhandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
				catch(PDOException $e){$this->catch_handler($e);}
				break;
		}//switch
	}//else
	}//construct()
	
	function __destruct() 
	{
		$this->dbhandler = NULL;
		//@mysql_close();//closes the connection to the DB
	}//destruct()

	public function catch_handler($e)
	{
		echo $e->getMessage(); $this->dbhandler = NULL; exit;
	}//catch_handler()
	
	public function createInsertQuery($DBtableName, $arrayValues)
	{
		$query = "INSERT INTO " . $DBtableName . " (";
		$i=0;
		while (list($key, $val) = each($arrayValues))
		{
			if($i==(count($arrayValues)-1))//don't add a comma after the table field name
			{
				$query .= $key . " ";
			}//
			else {$query .= $key . ", ";}
			$i++;
		}//while
		$query .=") VALUES (";
		
		reset ($arrayValues);
		$i=0;
		while (list($key, $val) = each($arrayValues))
		{
			if($i==(count($arrayValues)-1))//don't add a comma after the table field value
			{
				$query .= $val . " ";
			}//
			else {$query .= $val . ", ";}
			$i++;
		}//while
		
		$query .=");";
		
		//$_SESSION['query'] = $query;
		return $query;
	}//createInsertQuery
	
	
	public function executeInsertQuery($query)
	{
		$effectedRows = $this->dbhandler->exec($query);
		$insertID = $this->dbhandler->lastInsertId();
		
		return $insertID;
	}//executeInsertQuery()

	public function executeUpdateQuery($query)
	{
		$dbVars = array();
		$dbErrors = array();
				
		$dbVars['EFFECT_ROWS'] = $this->dbhandler->exec($query);
		
		$dbErrors = $this->dbhandler->errorInfo(); reset($dbErrors);
		while (list($key, $val) = each ($dbErrors)){
			if($key==0 && $val==00000){}//all ok
			else{}//{echo $key . '=>' . $val . '<br>';} //ERROR: HANDLE IT...
		}//while
		
		return $dbVars;
	}//executeUpdateQuery($query)

	public function executeSelectQuery($query)
	{	
		//$dbobj = new TBDBase();
		$dbVars = array();
		$dbErrors = array();
		
		$STHandler = $this->dbhandler->query($query);
		//$dbVars['RESULT'] = $STHandler->fetch(PDO::FETCH_ASSOC);
		$dbVars['NUM_ROWS'] = $STHandler->rowCount(); 

		for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
		{
			$tempVars[$i] = $STHandler->fetch(PDO::FETCH_ASSOC);
			while (list($key, $val) = each($tempVars[$i]))
			{
				$tempVars[$i][$key] = $val;
			}//while			
		}//for
		$dbVars['RESULT'] = $tempVars;
	
		$dbErrors = $this->dbhandler->errorInfo(); reset($dbErrors);
		while (list($key, $val) = each ($dbErrors)){
			if($key==0 && $val==00000){}//all ok
			else{}//{echo $key . '=>' . $val . '<br>';} //ERROR: HANDLE IT...
		}//while

		return $dbVars;
	}//executeSelectQuery($query)
	
	public function executeDeleteQuery($query)
	{
		$dbVars = array();
		$dbErrors = array();
				
		$dbVars['EFFECT_ROWS'] = $this->dbhandler->exec($query);
		
		$dbErrors = $this->dbhandler->errorInfo(); reset($dbErrors);
		while (list($key, $val) = each ($dbErrors)){
			if($key==0 && $val==00000){}//all ok
			else{}//{echo $key . '=>' . $val . '<br>';} //ERROR: HANDLE IT...
		}//while
		
		return $dbVars;
	}//executeDeleteQuery($query)
	

	
}//TBDBase class

?>