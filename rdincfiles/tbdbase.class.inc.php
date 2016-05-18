<?php

if (!isset($_SESSION["SESSION"])) require ( "rdconfig.inc.php");

class TBDBase
{
	//$connectionType = 0 if we want to create a LOW CREDENTIAL connection to the DB
	//$connectionType = 1 if we want to find the appropriate credentials sto connect to the the DB
	function __construct($connectionType)
	{
		$validationResult = NULL;
		$validateCredentials = validate_user_login();

	if($connectionType == 0){
		@mysql_connect(TBDB_HOST,TBDB_COMMON_USER,TBDB_COMMON_PASSWORD) or die('cannot connect '.TBDB_HOST.TBDB_COMMON_USER.TBDB_COMMON_PASSWORD);
		@mysql_select_db(TBDB_DATABASE) or die('cannot select database'.TBDB_DATABASE);
	}//if
	else
	{
		switch($validateCredentials)
		{
			case 'administrator':
				//$validationResult = $validator->validate_login_credentialsAdmin();
				$validationResult = validate_login_credentialsAdmin();
				if($validationResult['usertype']!='administrator'){user_logout('ajax');}
				break;
			case 'registereduser':	
				//create a temp connection to the database
				@mysql_connect(TBDB_HOST,TBDB_COMMON_USER,TBDB_COMMON_PASSWORD);
				@mysql_select_db(TBDB_DATABASE);
				

				//$validationResult = $validator->validate_login_credentialsRegisteredUser();
				$validationResult = validate_login_credentialsRegisteredUser();
				if($validationResult['usertype']!='registereduser'){user_logout('ajax');}
				else
				{
					@mysql_close(); //close the temp connection to the database
					@mysql_connect(TBDB_HOST,TBDB_REGISTERED_USER,TBDB_REGISTERED_PASSWORD) or user_logout('ajax');
					@mysql_select_db(TBDB_DATABASE) or user_logout('ajax');
				}//
				break;

			case 'unregistereduser':
				@mysql_connect(TBDB_HOST,TBDB_COMMON_USER,TBDB_COMMON_PASSWORD);
				@mysql_select_db(TBDB_DATABASE);
				break;
			default:
				@mysql_connect(TBDB_HOST,TBDB_COMMON_USER,TBDB_COMMON_PASSWORD);
				@mysql_select_db(TBDB_DATABASE) or user_logout('ajax');
				break;
		}//switch
	}//else
	
	}//construct()
	
	function __destruct() 
	{
		@mysql_close();//closes the connection to the DB
	}//destruct()
	
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
		
		$_SESSION['query'] = $query;
		return $query;
	}//createInsertQuery
	
	
	public function executeInsertQuery($query)
	{
		@mysql_query($query) or die(mysql_error());// or dbErrorHandler(802,mysql_error(),$query,'ajax','','','executeInsertQuery','false');
		return mysql_insert_id();
	}//executeInsertQuery()

	public function executeSelectQuery($query)
	{	
		$dbVars = array();
		
		$dbVars['RESULT'] = @mysql_query($query);
		$dbVars['NUM_ROWS'] = @mysql_num_rows($dbVars['RESULT']);

		$tempCounter = 0;
		while ($row = @mysql_fetch_assoc($dbVars['RESULT'])) {
			reset($row);
			while (list($key, $val) = each ($row))
			{
				$tempArr[$tempCounter][$key] = $val;
			}//inner while
			$tempCounter++;
		}//outer while
		$dbVars['RESULT']=$tempArr;

		return $dbVars;
	}//executeSelectQuery($query)

	public function executeUpdateQuery($query)
	{
		$dbVars = array();
		
		$dbVars['RESULT'] = @mysql_query($query);

		return $dbVars;
	}//executeUpdateQuery($query)
	
	public function executeDeleteQuery($query)
	{
		$dbVars = array();
		
		$dbVars['RESULT'] = @mysql_query($query);

		return $dbVars;
	}//executeDeleteQuery($query)
	

	
}//TBDBase class

?>