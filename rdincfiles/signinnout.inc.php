<?php

function user_login($callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$explodeArr = array();
	if(isset($_POST['formid'])){$formID = $_POST['formid'];}
	$pageID = $_POST['pageid'];
	$errorsVarsArr = array();
	$errorCode = NULL;

	$validatorFormID = 'login'; 
	$validatorCSRFaction = 'submitlogin';

	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok
	
	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok

	$arVals = array("email"=>"","password"=>"");
	$arValsRequired = array("email"=>"","password"=>"");
	$arValsMaxSize = array("email"=>"100","password"=>"25");
	$arValsValidations = array(); //don't need this actually

	reset($_POST);
	$arVals = $validator->customEncodeInputValues($_POST,$arVals);
	unset($_POST);

	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}
	
	if($errorsVarsArr['errorCode'] == 0)
	{
		$username = $arVals['email']; 
		$password = $arVals['password'];
		$password = hash('sha256',$password);
	}//if
	jason($username,$password);
	$loginResult = validate_login_credentialsAdmin();
	
	switch($loginResult['usertype'])
	{
		case 'administrator':
			$_SESSION['user']['username'] = $loginResult['username'];
			$_SESSION['user']['email'] = $loginResult['email'];
			$_SESSION['user']['uid'] = $loginResult['uid'];
			$_SESSION['user']['password'] = $loginResult['password'];
			$_SESSION['user']['name'] = $loginResult['name'];
			$_SESSION['user']['avatar'] = $loginResult['avatar'];
			$_SESSION['user']['description'] = $loginResult['description'];
			$_SESSION['user']['lastupdatedtimestamp'] = $loginResult['lastupdatedtimestamp'];
			$_SESSION['user']['registrationtimestamp'] = $loginResult['registrationtimestamp'];
			
			$_SESSION['user']['admin_login'] = TRUE;
			$_SESSION['user']['user_tm'] = $_SESSION['FIRST_VISIT_TM']; //TIMESTAMP OF WHEN THE USER FIRST ENTERED THE SYSTEM

			unset($validator);
			unset($dbobj);
			handle_validateNsubmit_login($loginResult['username'],'administrator',$formID,$callType);
			break;
		case 'notadministrator':
			$loginResult = validate_login_credentialsRegisteredUser();
			unset($validator);
			switch($loginResult['usertype'])
			{
				case 'registereduser':
				
					$_SESSION['user']['username'] = $loginResult['username'];
					$_SESSION['user']['email'] = $loginResult['email'];
					$_SESSION['user']['uid'] = $loginResult['uid'];
					$_SESSION['user']['password'] = $loginResult['password'];
					$_SESSION['user']['name'] = $loginResult['name'];
					$_SESSION['user']['gender'] = $loginResult['gender'];
					$_SESSION['user']['accountstatus'] = $loginResult['accountstatus'];
					$_SESSION['user']['visibilitystatus'] = $loginResult['visibilitystatus'];
					$_SESSION['user']['tags'] = $loginResult['tags'];
									
					$_SESSION['user']['facebook'] = $loginResult['facebook'];
					$_SESSION['user']['myspace'] = $loginResult['myspace'];
					$_SESSION['user']['twitter'] = $loginResult['twitter'];
					$_SESSION['user']['youtube'] = $loginResult['youtube'];
					$_SESSION['user']['vimeo'] = $loginResult['vimeo'];
					$_SESSION['user']['newsletter'] = $loginResult['newsletter'];
					$_SESSION['user']['albumcomments'] = $loginResult['albumcomments'];
					$_SESSION['user']['blogpostcomments'] = $loginResult['blogpostcomments'];
					$_SESSION['user']['commentnotifications'] = $loginResult['commentnotifications'];
		
					$_SESSION['user']['coverimage'] = $loginResult['coverimage'];
		
					$_SESSION['user']['title'] = $loginResult['title'];
					$_SESSION['user']['urltitle'] = $loginResult['urltitle'];
					$_SESSION['user']['uploadimageswidth'] = $loginResult['uploadimageswidth'];
					$_SESSION['user']['uploadimagestype'] = $loginResult['uploadimagestype'];
					
					$_SESSION['user']['avatar'] = $loginResult['avatar'];
					$_SESSION['user']['description'] = $loginResult['description'];
					$_SESSION['user']['lastupdatedtimestamp'] = $loginResult['lastupdatedtimestamp'];
					$_SESSION['user']['registrationtimestamp'] = $loginResult['registrationtimestamp'];
					$_SESSION['user']['views'] = $loginResult['views'];
					
					$_SESSION['user']['rguser_login'] = TRUE;
					$_SESSION['user']['user_tm'] = $_SESSION['FIRST_VISIT_TM']; //TIMESTAMP OF WHEN THE USER FIRST ENTERED THE SYSTEM					
					unset($dbobj);
					handle_validateNsubmit_login($loginResult['username'],'registereduser',$formID,$callType);	
					break;
				case 'imposter':
					unset($dbobj);
					handle_validateNsubmit_loginError('imposter',111,$formID,$callType);
					break;
				default:
					unset($dbobj);
					handle_validateNsubmit_loginError('imposter',111,$formID,$callType);
					break;	
			}//inner switch
			break;
		default:
			unset($dbobj);
			handle_validateNsubmit_loginError('imposter',111,$formID,$callType);
			break;
	}//switch
	

}//user_login()


function user_logout($callType)
{
	require_once('validate.class.inc.php');
	$validator = new Validate();
	$formID = $_POST['formid'];
	$pageID = $_POST['pageid'];
	
	session_unset();
	// Clear the session cookie
	unset($_COOKIE[session_name()]);
	// Destroy session data
	session_destroy();
	
	handle_validateNsubmit_logout('logout',$callType);	
}//user_logout()

?>
