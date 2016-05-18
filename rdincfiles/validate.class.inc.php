<?php

// Class supports AJAX and PHP web form validation
class Validate
{	
	function __construct() {}
	function __destruct() {}
	
	// supports AJAX validation, verifies a single value
	public function ValidateAJAX($val, $key, $arValsRequired, $arValsMaxSize, $arValsValidations)
	{	
		$errorsExist = 0;// error flag, becomes 0 when no errors are found.
		
		if(!isset($arValsRequired[$key])) {}
		else
		{	
			if(!$this->variablesSet($key))
			{
				$_SESSION['errorformvalues'][$key] = 'error';
				$errorsExist = 101;
				return $errorsExist;
			}
		}//else

		if(!isset($arValsRequired[$key])){}
		else
		{
			if(!$this->variablesFilled($key))
			{
				$_SESSION['errorformvalues'][$key] = 'error';
				$errorsExist = 101;
				return $errorsExist;
			}
		}//else

		if(!isset($arValsMaxSize[$key])){}
		else
		{
			if(!$this->variablesCheckRange($key, $arValsMaxSize[$key]))
			{
				$_SESSION['errorformvalues'][$key] = 'error';
				$errorsExist = 102;
				return $errorsExist;
			}
		}//else

		if(!isset($arValsValidations[$key])){}
		else
		{
			if(!$this->variablesValidate($key, $arValsValidations[$key]))
			{
				$_SESSION['errorformvalues'][$key] = 'error';
				$errorsExist = 103;
				return $errorsExist;
			}
		}//else
		
		//ALL OK
		$_SESSION['errorformvalues'][$key] = 'hidden';
		return 0;
	}//ValidateAJAX
	
	// validates all form fields on form submit
	public function ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations)
	{
		$errorsVarsArr = array();
		$errorsVarsArr['errorCode'] = 0;// error flag, becomes 0 when no errors are found.
		$errorsVarsArr['errorFieldID'] = '';
		$errorsVarsArr['errorFieldValue'] = '';

		if (isset($_SESSION['errorformvalues'])) { unset($_SESSION['errorformvalues']); } // clears the errors session flag
	
		// By default all fields are considered valid
		reset($arValsRequired); 		
		while(list($key, $val) = each ($arValsRequired))
		{
			$_SESSION['errorformvalues'][$key] = 'hidden';
		}//while
		
		reset($arValsRequired);
		while(list($key, $val) = each ($arValsRequired))
		{
			if(!$this->variablesSet($key))
			{
				$_SESSION['errorformvalues'][$key] = 'error';
				$errorsVarsArr['errorCode'] = 101;
				$errorsVarsArr['errorFieldID'] = $key;
				$errorsVarsArr['errorFieldValue'] = $val;
				return $errorsVarsArr;
			}//if
		}//while
		
		reset($arValsRequired);
		while(list($key, $val) = each ($arValsRequired))
		{
			if(!$this->variablesFilled($key))
			{
				$_SESSION['errorformvalues'][$key] = 'error';
				$errorsVarsArr['errorCode'] = 101;
				$errorsVarsArr['errorFieldID'] = $key;
				$errorsVarsArr['errorFieldValue'] = $val;
				return $errorsVarsArr;
			}//if
		}//while
		
		reset($arValsMaxSize);
		while(list($key, $val) = each ($arValsMaxSize))
		{
			if(!$this->variablesCheckRange($key, $val))
			{
				$_SESSION['errorformvalues'][$key] = 'error';
				$errorsVarsArr['errorCode'] = 102;
				$errorsVarsArr['errorFieldID'] = $key;
				$errorsVarsArr['errorFieldValue'] = $val;
				return $errorsVarsArr;
			}//if
		}//while
		
		reset($arValsValidations);
		while(list($key, $val) = each ($arValsValidations))
		{
			if(!$this->variablesValidate($key, $val))
			{
				$_SESSION['errorformvalues'][$key] = 'error';
				$errorsVarsArr['errorCode'] = 103;
				$errorsVarsArr['errorFieldID'] = $key;
				$errorsVarsArr['errorFieldValue'] = NULL ; //$errorsVarsArr['errorFieldValue'] = 'null';
				return $errorsVarsArr;
			}//if
		}//while
		
		unset($_SESSION['errorformvalues']);
		
		if ($errorsExist == 0) { return $errorsVarsArr; }//all ok
		else { $errorsVarsArr['errorsExist']=$errorsExist; return $errorsVarsArr; }// ERROR: return error code
	}//ValidatePHP()
	
	
	
	// function that checks to see if these variables have been set...
	private function variablesSet($key)
	{
		if ( (!isset($_SESSION['formvalues'][$key])) ){ return 0; }
		else{ return 1; }//valid
	}//variablesSet
	
	// function that checks if the form variables have something in them...
	private function variablesFilled($key)
	{
		if ($_SESSION['formvalues'][$key] == "" ){ return 0; }
		else{ return 1; }//valid
	}//variablesFilled()
	
	//function that checks if the fields are within the proper range...
	private function variablesCheckRange($key, $val)
	{
		if (strlen($_SESSION['formvalues'][$key]) > $val){ return 0; }
		else{ return 1; }//valid
	}//variable
	
	//function that makes sure fields are within the proper range... else cuts off any extra...
	private function  variablesCheckRangeCutExtra($key, $val)
	{	
		while (list($key, $val) = each($arrayValues))
		{
			if (strlen($_SESSION['formvalues'][$key]) > $val) { $_SESSION[$key] = substr($_SESSION[$key],0,$val); }
		}//while
	}//variablesCheckRangeCutExtra
	
	private function variablesValidate($key, $val)
	{
		if($_SESSION['formvalues'][$key] == NULL){return 1;}
		else
		{
			if(!preg_match($val, $_SESSION['formvalues'][$key] )) { return 0; } 
			else { return 1; }
		}
	}//variablesValidate
	
	public function checkPost()
	{
		//check if $_POST is set. If it's not set, then the form was not submitted normaly.
		//if(!isset($_POST)){ return 0; } //error
		if(!isset($_POST['formid'])){ return 0; } //error		
		else { return 1; }//all ok
	}//checkPost()
	
	public function checkCSRF($submittedCSRF, $referenceCSRF, $submittedFormPage)
	{
		//check for CSRF (Cross Site Request Forgery)
		$referenceCSRF = hash('sha256', $submittedFormPage) . $referenceCSRF;
		if($submittedCSRF != $referenceCSRF){ return 0; }//error
		else { return 1; } //all ok
	}//checkCSRF($submittedCSRF, $referenceCSRF, $submittedForm)
	
	//uploadErrorMessages()
	public function uploadFilesErrorMessages($errorid)
	{	
		/*
		Since PHP 4.2.0, PHP returns an appropriate error code along with the file array. 
		The error code can be found in the error segment of the file array that is created 
		during the file upload by PHP. In other words, the error might be found in $_FILES['userfile']['error'].
		*/
		switch($errorid)
		{
			case 0:
				//UPLOAD_ERR_OK
				//There is no error, the file uploaded with success.
				return 0; //no error
				break;
			case 1:
				//UPLOAD_ERR_INI_SIZE
				//The uploaded file exceeds the upload_max_filesize directive in php.ini.
				return 191;
				break;
			case 2:
				//UPLOAD_ERR_FORM_SIZE
				//The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
				return 192;
				break;
			case 3:
				//UPLOAD_ERR_PARTIAL
				//The uploaded file was only partially uploaded.
				return 193;
				break;
			case 4:
				//UPLOAD_ERR_NO_FILE
				//No file was uploaded.
				return 194;
				break;
			case 6:
				//UPLOAD_ERR_NO_TMP_DIR
				//Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.
				return 196;
				break;
			case 7:
				//UPLOAD_ERR_CANT_WRITE
				//Failed to write file to disk. Introduced in PHP 5.1.0.
				return 197;
				break;
			case 8:
				//UPLOAD_ERR_EXTENSION
				//File upload stopped by extension. Introduced in PHP 5.2.0.
				return 198;
				break;
			default:
				//do nothing
				break;
		}//switch
	}//uploadFilesErrorMessages()
	
	public function checkDBValueAvailability($tableName, $field, $value)
	{
		//check in $tableName if $field with $value already exists.
		//prevents duplicate entries.
		//$dbobj = new JDDBase();
	}//checkDBValueAvailability()
	
	//variablesnumber: how many variable should the $_GET array have for this page
	//on error redirect to $redirectpage 
	//$variablestype: what type of variable should each $_GET be
	public function checkGetVariable($variablesNumber,$redirectPage,$variablesType)
	{
		######################
		######################
		if(count($_GET) == $variablesNumber)
		{
			//OK
			reset($_GET);
			while(list($key, $val) = each ($_GET))
			{
				if(!isset($_GET[$key])){ redirects($redirectPage,""); }//if
				else
				{
					//OK
					if($_GET[$key] == ""){ redirects($redirectPage,"",""); }//if
					else
					{
						//OK
						if ( preg_match($variablesType[$key],$_GET[$key])){ redirects($redirectPage,"",""); }//if
						else
						{
							//ALL OK
							$validated_vars[$key] = trim($_GET[$key]);
						}//
					}//
				}//else
			}//while
			return $validated_vars;
		}//if
		else
		{
			Redirects(0,"","");
		}//
		######################
		######################
	}//checkGetVariable
	
	
	public function customEncodeInputValues($method,$arVals)
	{
		while (list($key, $val) = each ($_POST))
		{	
			if (trim($val) == "") { $val = "NULL";}
			$arVals[$key] = (get_magic_quotes_gpc()) ? $val : addslashes($val);
			$arVals[$key] = htmlentities($arVals[$key], ENT_NOQUOTES, "UTF-8");
			$arVals[$key] = trim($arVals[$key]);
			if ($val == "NULL"){ $_SESSION['formvalues'][$key] = NULL;}
			else{ $_SESSION['formvalues'][$key] = strtolower($val);}//
		}//while		
		return $arVals;
	}//customEncodeInputValues($method,$arVals)
}//class

function validate_login_credentialsAdmin()
{
	//if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	//else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	//$dbobj = new TBDBase();
	$userInfo = array();
	$accountFound = false;

	//find the credential positions
	$position = bob($peter);
	$username = $_SESSION['USERSARR'][$position]['username'];
	$password = $_SESSION['USERSARR'][$position]['password'];
	$realpassword = kevin($password);
	
	//attemp to connect to the database with the given username & password, and then select the database
	//if this action is successfull, then the given credentials can connect to the database
	if(SYSTEM_MODE == 'server'){$username = SERVER_DB_PREFIX.$username;}

	if(DATABASE_MODE == 'default')
	{
		if( (@mysql_connect(TBDB_HOST,$username,$realpassword)) && (@mysql_select_db(TBDB_DATABASE))){
			//attempt to run a SELECT query to the DB table usersactionlog. Only the SYSTEM ADMINISTRATOR can run such a query
			@mysql_select_db(TBDB_DATABASE) or die("error selecting db"); 
			$query01 = "SELECT COUNT(*) FROM usersactionlog; ";
			$result01 = @mysql_query($query01) or jsonresponse_validateNsubmit_loginError(111); //can't execute this query, user is an imposter.
			$num01 = @mysql_num_rows($result01);//num
	
			$checkProfileQuery = "SELECT * FROM user WHERE uid='0' AND username='jedimaster'; ";
			$dbVars = $dbobj->executeSelectQuery($checkProfileQuery);
		
			for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
			{
				$userInfo['usertype'] = 'administrator'; 
				$userInfo['username'] = $dbVars['RESULT'][$i]['username'];
				$userInfo['email'] = $dbVars['RESULT'][$i]['email'];
				$userInfo['uid'] = $dbVars['RESULT'][$i]['uid'];
				$userInfo['password'] = hash('sha256','bagger off, wanker');
				$userInfo['name'] = $dbVars['RESULT'][$i]['name'];
				$userInfo['avatar'] = $dbVars['RESULT'][$i]['avatar'];
				$userInfo['description'] = $dbVars['RESULT'][$i]['description'];
				$userInfo['lastupdatedtimestamp'] = $dbVars['RESULT'][$i]['lastupdatedtimestamp'];
				$userInfo['registrationtimestamp'] = $dbVars['RESULT'][$i]['registrationtimestamp'];
			}//for
			
			unset($dbobj);
			return $userInfo;
		}//if
		else
		{
			$userInfo['usertype'] = 'notadministrator'; $userInfo['username'] = ''; $userInfo['password'] = '';
			
			unset($dbobj);
			return $userInfo;
		}//else
	}//if(DATABASE_MODE == 'default')
	else if(DATABASE_MODE == 'pdo')
	{
		try{
			$DBHandler = new PDO("mysql:host=".TBDB_HOST.";dbname=".TBDB_DATABASE, $username, $realpassword);
			$DBHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$query = "SELECT COUNT(*) FROM usersactionlog; ";
			$stmt = $DBHandler->query($query);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$dbErrors = $DBHandler->errorInfo(); reset($dbErrors);
			while (list($key, $val) = each ($dbErrors)){
				if($key==0 && $val==00000){}//all ok
				else{jsonresponse_validateNsubmit_loginError(111); exit;} //ERROR: HANDLE IT...
			}//while
		
			$checkProfileQuery = "SELECT * FROM user WHERE uid='0' AND username='superadmin'; ";
			$dbVars = $dbobj->executeSelectQuery($checkProfileQuery);
		
			for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
			{
				$userInfo['usertype'] = 'administrator'; 
				$userInfo['username'] = $dbVars['RESULT'][$i]['username'];
				$userInfo['email'] = $dbVars['RESULT'][$i]['email'];
				$userInfo['uid'] = $dbVars['RESULT'][$i]['uid'];
				$userInfo['password'] = kevin(hash('sha256','bagger off, wanker'));
				$userInfo['name'] = $dbVars['RESULT'][$i]['name'];
				$userInfo['accountstatus'] = $dbVars['RESULT'][$i]['accountstatus'];
				$userInfo['gender'] = $dbVars['RESULT'][$i]['gender'];
				$userInfo['avatar'] = $dbVars['RESULT'][$i]['avatar'];
				$userInfo['description'] = $dbVars['RESULT'][$i]['description'];
				$userInfo['lastupdatedtimestamp'] = $dbVars['RESULT'][$i]['lastupdatedtimestamp'];
				$userInfo['registrationtimestamp'] = $dbVars['RESULT'][$i]['registrationtimestamp'];
			}//for
			
			unset($dbobj);
			return $userInfo;
		}catch(PDOException $e)
		{
			$userInfo['usertype'] = 'notadministrator'; $userInfo['username'] = ''; $userInfo['password'] = '';
			
			unset($dbobj);
			return $userInfo;
		}//catch
	}//else if(DATABASE_MODE == 'pdo')
}//validate_login_credentialsAdmin()
	
function validate_login_credentialsRegisteredUser()
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(0);
	$userInfo = array();
	$dbVars = array();
	$accountFound = false;

	$position = bob($peter);
	$username = $_SESSION['USERSARR'][$position]['username'];
	$password = $_SESSION['USERSARR'][$position]['password'];
	$generatedPasswordFromSubmitted = kevin($password);
	
	//this means that the user is either a registered user or an imposter
	//find the user in the profiles database
	$checkProfileQuery = "SELECT * FROM user, website WHERE registrationstatus='complete' AND user.uid = website.uid; ";
	$dbVars = $dbobj->executeSelectQuery($checkProfileQuery);
	
	if($dbVars['RESULT'][0] != 0)
	{
		reset($dbVars['RESULT'][0]);
		while (list($key, $val) = each ($dbVars['RESULT'][0])){if( (trim($val) == "")||($val == NULL)||(strtolower($val)=='null') ){ $val = "";}}//while
	}//if
	
	for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
	{	
		$tempID = $dbVars['RESULT'][$i]['uid'];
		$tempUsername = $dbVars['RESULT'][$i]['username'];
		$tempPassword = $dbVars['RESULT'][$i]['password'];
		$tempEmail = $dbVars['RESULT'][$i]['email'];
		$tempName = $dbVars['RESULT'][$i]['name'];
		$tempGender = $dbVars['RESULT'][$i]['gender'];
		$tempAccountStatus = $dbVars['RESULT'][$i]['accountstatus'];
		$tempVisibilityStatus = $dbVars['RESULT'][$i]['visibilitystatus'];
		$tempTags = $dbVars['RESULT'][$i]['tags'];
		$tempAvatar = $dbVars['RESULT'][$i]['avatar'];
		
		if($tempAvatar != 0)
		{
			$queryAvatar = "SELECT iid,fileurl FROM image WHERE imagetype = 'usercover' AND uid='".$tempID."' AND iid='".$tempAvatar."'; ";
			$dbVars2 = $dbobj->executeSelectQuery($queryAvatar);
			if($dbVars2['NUM_ROWS'] != 0){$tempCoverImage = $dbVars2['RESULT'][0]['fileurl'];}//if
			else{$tempCoverImage = DEFAULT_BLANK_USER_THUMBNAIL;}
		}//if
		else{$tempCoverImage = DEFAULT_BLANK_USER_THUMBNAIL;}
		
		$tempDescription = $dbVars['RESULT'][$i]['description'];
			
		$tempFacebook =  $dbVars['RESULT'][$i]['facebook'];
			if(strtolower($tempFacebook) == 'null'){$tempFacebook = '';}
		$tempMyspace =  $dbVars['RESULT'][$i]['myspace'];
			if(strtolower($tempMyspace) == 'null'){$tempMyspace = '';}
		$tempTwitter =  $dbVars['RESULT'][$i]['twitter'];
			if(strtolower($tempTwitter) == 'null'){$tempTwitter = '';}
		$tempYoutube =  $dbVars['RESULT'][$i]['youtube'];
			if(strtolower($tempYoutube) == 'null'){$tempYoutube = '';}
		$tempVimeo =  $dbVars['RESULT'][$i]['vimeo'];
			if(strtolower($tempVimeo) == 'null'){$tempVimeo = '';}		
		
		
		$tempNewsletter =  $dbVars['RESULT'][$i]['newsletter'];
		$tempAlbumComments =  $dbVars['RESULT'][$i]['albumcomments'];
		$tempBlogPostComments =  $dbVars['RESULT'][$i]['blogpostcomments'];
		$tempCommentNotifications =  $dbVars['RESULT'][$i]['commentnotifications'];
		
		$tempWebsiteTitle = $dbVars['RESULT'][$i]['title'];
		$tempWebsiteURLtitle = $dbVars['RESULT'][$i]['urltitle'];
		$tempUploadImagesWidth = $dbVars['RESULT'][$i]['uploadimageswidth'];
		$tempUploadImagesType = $dbVars['RESULT'][$i]['uploadimagestype'];
		
		$tempViews = $dbVars['RESULT'][$i]['views'];
		$tempTags = $dbVars['RESULT'][$i]['tags'];
		$tempLastUpdatedTimestamp = $dbVars['RESULT'][$i]['lastupdatedtimestamp'];		
		$tempRegistrationTimestamp = $dbVars['RESULT'][$i]['submissiontimestamp'];
		
		
		//$generatedPasswordFromDB = kevin($tempPassword);
		$generatedPasswordFromDB = $tempPassword;
		if(($generatedPasswordFromDB == $generatedPasswordFromSubmitted)&&($tempEmail == $username)){$accountFound = true; break;}			
	}//for

	if($accountFound){
		$userInfo['usertype'] = 'registereduser'; 
		$userInfo['username'] = $tempUsername;
		$userInfo['email'] = $tempEmail;
		$userInfo['uid'] = $tempID;
		$userInfo['password'] = kevin(hash('sha256','REALLY, bagger off, wanker'));
		$userInfo['name'] = $tempName;
		$userInfo['gender'] = $tempGender;
		$userInfo['tags'] = $tempTags;
			
		$userInfo['facebook'] = $tempFacebook;
		$userInfo['myspace'] = $tempMyspace;
		$userInfo['twitter'] = $tempTwitter;
		$userInfo['youtube'] = $tempYoutube;
		$userInfo['vimeo'] = $tempVimeo;
		$userInfo['newsletter'] = $tempNewsletter;
		$userInfo['albumcomments'] = $tempAlbumComments;
		$userInfo['blogpostcomments'] = $tempBlogPostComments;
		$userInfo['commentnotifications'] = $tempCommentNotifications;

		$userInfo['coverimage'] = $tempCoverImage;

		$userInfo['title'] = $tempWebsiteTitle;
		$userInfo['urltitle'] = $tempWebsiteURLtitle;
		$userInfo['uploadimageswidth'] = $tempUploadImagesWidth;
		$userInfo['uploadimagestype'] = $tempUploadImagesType;

		$userInfo['accountstatus'] = $tempAccountStatus;
		$userInfo['visibilitystatus'] = $tempVisibilityStatus;
		$userInfo['avatar'] = $tempAvatar;
		$userInfo['description'] = $tempDescription;
		$userInfo['views'] = $tempViews;		
		$userInfo['lastupdatedtimestamp'] = $tempLastUpdatedTimestamp;
		$userInfo['registrationtimestamp'] = $tempRegistrationTimestamp;
		
		unset($dbobj);
		return $userInfo;
	}else{
		$userInfo['usertype'] = 'imposter'; $userInfo['username'] = ''; $userInfo['password'] = '';	
		
		unset($dbobj);
		return $userInfo;
	}//else
}//validate_login_credentialsRegisteredUser


function validate_user_login()
{
	$validateCredentials = 'unregistereduser';
	$loggedin_administrator = false;
	$loggedin_registereduser = false;	
	
	$loggedin_administrator = login_is_admin(); if($loggedin_administrator){$validateCredentials = "administrator";}
	$loggedin_registereduser = login_is_registered(); if($loggedin_registereduser){$validateCredentials = "registereduser";}

	return $validateCredentials;
}//validate_user_login()


function login_is_admin()
{
	$loggedin_administrator = false;
	
	if((isset($_SESSION['user']['admin_login'])) && ($_SESSION['user']['admin_login']==TRUE) &&
		(isset($_SESSION['user']['uid'])) && ($_SESSION['user']['uid']=='0') &&
		(isset($_SESSION['user']['email'])) && ($_SESSION['user']['email']!=''))
	{ $loggedin_administrator = true; }
	
	return $loggedin_administrator;	
}//login_is_admin()


function login_is_registered()
{
	$loggedin_registereduser = false;

	if((isset($_SESSION['user']['rguser_login'])) && ($_SESSION['user']['rguser_login']==TRUE) &&
		(isset($_SESSION['user']['uid'])) && ($_SESSION['user']['uid']!='') &&
		(isset($_SESSION['user']['email'])) && ($_SESSION['user']['email']!=''))
	{ $loggedin_registereduser = true;}

	return $loggedin_registereduser;
}//login_is_admin()


function check_browseraddressGET_page_exists()
{
	$pageResult = NULL;
	$pagesMapArray = array(
		"mywebsite"=>"portfolioindex.php",
		"myaccount"=>"accountmanageindex.php",
		"featured"=>"search.php",
		"admin"=>"administratorindex.php",
		"hobbs"=>"main_content_accountactions.php",
		"index"=>"main_content_home.php",
		"home"=>"main_content_home.php",
		""=>"main_content_home.php"
	);
	
	if(count($_GET)==0){$pageResult = NULL;}//
	else
	{
		reset($_GET);
		while (list($key_get, $val_get) = each ($_GET))
		{
			reset($pagesMapArray);
			while (list($key_pages, $val_pages) = each ($pagesMapArray)){
				//if($key_get == $val_pages){$pageResult = $val_pages; break;}
				if(strtolower($key_get) == $key_pages){$pageResult = $val_pages; break;}
			}//inner while
			//I WANT THE OUTER WHILE TO RUN ONLY ONCE. WE ARE SEARCHING FOR ONLY THE FIRST $_GET PARAMETER
			break;
		}//outer while
	}//else
	return $pageResult;
}//check_browseraddressGET_page_exists()
//returns a boolean value. TRUE is for  OK
function check_user_access($pageID)
{
	$result = false;
	
	switch($pageID)
	{
		case 'index.php':
			//viewed by all users
			break;
		case 'accountmanageindex.php':
			//viewed only by registered user
			if(login_is_registered()){$result = true;}
			break;
		case 'administratorindex.php':
			if(login_is_admin()){$result = true;}
			//viewed by registered users
			break;
		case 'browsersettings.php':
			//viewed by all users
			break;
		case 'portfolioindex.php':
			$result = false;
			break;
		case 'search.php':
			break;
		case 'main_content_home':
			$result = false;
			break;
		case '':
			break;
		default:
			break;
	}//switch($pageID)
	
	return $result;
}//check_user_access($pageID)



function check_filename_exists($parentDirectory,$tokenName) //parameter is the directory to search inside.
{
	$result = false;
	$fileNamesArr = array();
	
	$tokenNameArr = explode('.',$tokenName);
	$tokenNameStripped = $tokenNameArr[0];
	unset($tokenNameArr);
	
	//get all files names and directory names from $parentDirectory
	if ($handle = opendir($parentDirectory)) {
		$i=0;
		while (false !== ($file = readdir($handle))) {
			if(($file!='.') && ($file!='..'))
				{$fileNamesArr[$i] = $file; $i++;}
		}//while
		closedir($handle);
	}//if
	
	//initerate if the $tokenName exists in the list
	reset($fileNamesArr);
	while (list($key, $val) = each ($fileNamesArr))
	{
		if( ($val == $tokenName) || ($val == $tokenNameStripped)){$result = true; break;}
	}//while
	return $result;
}//check_filename_exists($tokenName);

//searches the user DB, the user folder and the bin folder
//for case '0' it returns TRUE if ANY of the 3 sub-searches is true
//for case '1' it returns TRUE if ALL of the 3 sub-searches are true
function check_username_exists($tokenName,$case) 
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj= new TBDBase(0);
	$result_combined = false;
	$result_db = false;
	$result_folder_users = false;
	$result_folder_bin = false;
	
	$tokenName = (get_magic_quotes_gpc()) ? $tokenName : addslashes($tokenName);
	$tokenName = htmlentities($tokenName, ENT_QUOTES, "UTF-8");
	$tokenName = trim($tokenName);
	
	//check the database
	$checkUsernameQuery = "SELECT count(*) AS count FROM user WHERE username='".$tokenName."'; ";
	$dbVars = $dbobj->executeSelectQuery($checkUsernameQuery);
	if($dbVars['RESULT'][0]['count'] != 0){$result_db = true;}//
	unset($dbobj);
	
	//check the users folder
	if(check_filename_exists(TBPARENT_DIR.'rdusers',$tokenName)){$result_folder_users = true;};

	//check the bin folder
	if(check_filename_exists(TBPARENT_DIR.'rdbin',$tokenName)){$result_folder_bin = true;};
	
	if($case == 0)
		{if($result_db || $result_folder_users || $result_folder_bin){$result_combined = true;}}
	else if($case == 1)
		{if($result_db && $result_folder_users && $result_folder_bin){$result_combined = true;}}

	return $result_combined;
}// checkUserNameDBExists($tokenName)

function check_story_exists($tokenName,$case)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj= new TBDBase(0);
	$result_combined = false;
	$result_db = false;

	$tokenName = (get_magic_quotes_gpc()) ? $tokenName : addslashes($tokenName);
	$tokenName = htmlentities($tokenName, ENT_QUOTES, "UTF-8");
	$tokenName = trim($tokenName);

	//check the database
	$checkStoryURLQuery = "SELECT count(*) AS count FROM story WHERE urltitle='".$tokenName."'; ";
	$dbVars = $dbobj->executeSelectQuery($checkStoryURLQuery);
	if($dbVars['RESULT'][0]['count'] != 0){$result_db = true;}//
	unset($dbobj);
	
	if($case == 0)
		{if($result_db){$result_combined = true;}}
	
	return $result_combined;
}//check_story_exists($tokenName,$case)

function check_email_exists($tokenName)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(0);
	$result = false;
	
	$checkEmailQuery = "SELECT count(*) AS count FROM user WHERE email='".$tokenName."'; ";
	$dbVars = $dbobj->executeSelectQuery($checkEmailQuery);
	if($dbVars['RESULT'][0]['count'] != 0){$result = true;}//
	unset($dbobj);
	
	return $result;
}//check_email_exists($tokenName)

function check_keyword_exists($tokenName)
{
	$result = false;
	$keywordsArr = array(
		0=>'index',
		1=>'index.htm',
		2=>'index.html',
		3=>'index.php',
		4=>'index.asp',
		5=>'index.jsp',
		6=>'index.xml',
		7=>'default',
		8=>'default.htm',
		9=>'default.php',
		10=>'default.asp',
		11=>'default.jsp',
		12=>'default.xml',
		13=>'hobbs'
	);
	
	$tokenNameArr = explode('.',$tokenName);
	$tokenNameStripped = $tokenNameArr[0];
	unset($tokenNameArr);
	
	reset($keywordsArr);
	while (list($key, $val) = each ($keywordsArr))
	{
		if( ($val == $tokenName) || ($val == $tokenNameStripped)){$result = true; break;}
	}//while
	return $result;
}// checkUserNameDBExists($tokenName)

function check_combined_search($tokenName)
{
	$result = false;
	
	if((check_filename_exists(TBPARENT_DIR,$tokenName))||(check_username_exists($tokenName,0))||(check_keyword_exists($tokenName)))
		{$result = true;}
	
	return $result;
}//check_combined_search($tokenName)


?>
