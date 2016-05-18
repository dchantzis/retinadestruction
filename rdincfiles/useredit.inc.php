<?php

function user_edit($editType,$callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbVars = array();
	
	$explodeArr = array();
	if(isset($_POST['formid'])){$formID = $_POST['formid'];}
	if(isset($_POST['fieldid'])){$fieldID = $_POST['fieldid']; unset($_POST['fieldid']); }

	$pageID = $_POST['pageid'];
	$errorsVarsArr = array();
	$errorCode = NULL;

	if($editType=='update'){
		if(isset($_POST['username'])){$username = $_POST['username']; unset($_POST['username']);}
		if(isset($_POST['email'])){$email = $_POST['email']; unset($_POST['email']);}
		$validatorFormID = 'user'; $validatorCSRFaction = 'submitedituser';
		
		$arValsRequired = array(); //none of the form fields are required
		
		$arVals = array("name"=>"","description"=>"","facebook"=>"","myspace"=>"","twitter"=>"","youtube"=>"","lastupdatedtimestamp"=>"");
		
		$arValsValidations = array("website"=>"/(http:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/","phone"=>"/^[0-9]([0-9]+)/");
		$arValsMaxSize = array("name"=>"80","description"=>PROFILE_DESCRIPTION_MAX_LENGTH,"lastupdatedtimestamp"=>"");
		
	}//if
	elseif($editType=='insert'){
		$validatorFormID = 'register'; $validatorCSRFaction = 'submitregister';
		$arValsRequired = array("username"=>"","email"=>"","password"=>"");
		
		$arVals = array("username"=>"", "password"=>"", "email"=>"", "name"=>"", "accountstatus"=>"active", "avatar"=>"", "description"=>"","facebook"=>"","myspace"=>"","twitter"=>"","youtube"=>"","vimeo"=>"","newsletter"=>"enabled", "albumcomments"=>"enabled","featured"=>"false","blogpostcomments"=>"enabled","commentnotifications"=>"enabled","registrationstatus"=>"incomplete","lastupdatedtimestamp"=>"","registrationtimestamp"=>"");
		
		$arValsMaxSize = array("username"=>"80","email"=>"100","password"=>"30","name"=>"80","description"=>PROFILE_DESCRIPTION_MAX_LENGTH);
		$arValsValidations = array("username"=>"/^[a-z][\w]+[a-z0-9]$/","email"=>"/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/");
		
	}//elseif
	
	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok

	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok

	if($editType=='insert')
	{
		if(isset($_POST['terms']))
		{
			if($_POST['terms']=='checked'){unset($_POST['terms']);}//all ok
			else
				{$errorsVarsArr['errorCode'] = 112; $errorsVarsArr['errorFieldID'] = 'terms'; $errorsVarsArr['errorFieldValue'] = 'off';
				handle_validationError($errorsVarsArr,$formID,$callType); exit;}		
		}//inner if
		
		reset ($_POST);
		$arVals = $validator->customEncodeInputValues($_POST,$arVals);
		unset($_POST);
		
		$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
		$arVals['registrationtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
		
		$arVals['password'] = hash('sha256',$arVals['password']);
		$arVals['password'] = kevin($arVals['password']);
		//$arVals['password'] = $arVals['password'];	
	}//outer if
	else if($editType=='update')
	{
		reset ($_POST);
		$arVals = $validator->customEncodeInputValues($_POST,$arVals);
		unset($_POST);
		
		$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	}//elseif()
	
	reset($arVals);
	while (list($key, $val) = each ($arVals))
	{
		if($key == 'description'){$arVals[$key] = "'".$arVals[$key]."'";}
		else{$arVals[$key] = "'".strtolower($arVals[$key])."'";}
	}//while

	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}

	if($errorsVarsArr['errorCode'] == 0)
	{
		if($editType=='insert')
		{		
			if(check_combined_search(removeQuotesSingleValue($arVals['username'])))
			{
				$errorsVarsArr['errorCode'] = 104;
				$errorsVarsArr['errorFieldID'] = 'username';
				$errorsVarsArr['errorFieldValue'] = substr($arVals['username'],1,-1);
				handle_validationError($errorsVarsArr,$formID,$callType); exit;
			}//
			if(check_email_exists(removeQuotesSingleValue($arVals['email']))){
				$errorsVarsArr['errorCode'] = 104;
				$errorsVarsArr['errorFieldID'] = 'email';
				$errorsVarsArr['errorFieldValue'] = substr($arVals['email'],1,-1);
				handle_validationError($errorsVarsArr,$formID,$callType); exit;
			}//
			
			$dbobj = new TBDBase(0);
			//create the user
			$insertQuery = $dbobj->createInsertQuery("user", $arVals);
			$insertID = $dbobj->executeInsertQuery($insertQuery);
			$arVals = removeQuotes($arVals);
			
			//create the website
			//set the new website variables
			$arValsWebsite = array("uid"=>$insertID,"title"=>$arVals['username']." portfolio","urltitle"=>NULL,"views"=>"0","tid"=>"0","wallpaper"=>"wallpaper.png","logo"=>"logo.png","designcsscode"=>"","designformoptions"=>"","sidebarinfo"=>"updated:1:.:.:copyright:0:.:.:email:1","sidebarorientation"=>"right","imagetagcloud"=>"yes","uploadimageswidth"=>"800","uploadimagestype"=>"3","visibilitystatus"=>"visible","status"=>"active","coverpage"=>"empty","lastupdatedtimestamp"=>$arVals['lastupdatedtimestamp'],"creationtimestamp"=>$arVals['lastupdatedtimestamp']);
			//set the new website default design
			$arValsWebsite["designformoptions"] ='wallpaper:image:..::..::wallpapercolor:FFFFFF:..::..::logo:text_username:..::..::sidebarorientation:right:..::..::text:textcolor_333333:..::..::text:linkcolor_333333:..::..::text:linkbgcolor_:..::..::text:linkhovercolor_ffffff:..::..::text:linkhoverbgcolor_000000:..::..::text:navilinksize_small:..::..::sectionsbg:trans_color_FFFFFF:..::..::text:fontfamily_Georgia, "Times New Roman", Times, serif:..::..::text:highlightcolor_333333:..::..::text:bghighlightcolor_:..::..::wallpapertype:empty';
			
			reset($arValsWebsite); while (list($key, $val) = each ($arValsWebsite)){$arValsWebsite[$key] = "'".strtolower($arValsWebsite[$key])."'";}
			$insertQueryWebsite = $dbobj->createInsertQuery("website", $arValsWebsite);
			$insertIDWebsite = $dbobj->executeInsertQuery($insertQueryWebsite);			

			unset($dbobj);
			
			$profileDirectory = createDirectory(TBUSERS_DIR,$arVals['username']);
			$profileDirectoryBin = createDirectory(TBRAW_DIR,$arVals['username']);
			generateProfileDirectoryIndexFile($arVals['username']);
			
			if(SYSTEM_MODE == 'server'){
				email_profileactivation_send($arVals);
				handle_validateNsubmit_registration($errorsVarsArr['errorCode'], $formID, $callType);
			}else{
				//TEMP CODE FOR TESTING
				//instaid of sending an activation email, this way it returns to the user the activation code via javascript alert.
				//with this code, the user can activate his account. The account is unusable without activation.
				if(SYSTEM_EMAILS == 'disabled')
				{
					$activationURL = SERVER_NAME.'/index.php?hobbs=';
					$code = hash('sha256','activateuserwithemail'.$arVals['email'].'andusername'.$arVals['username']).'8543z';
					$activationURL = $activationURL.$code;
					handle_validateNsubmit_registrationTESTING($errorsVarsArr['errorCode'], $formID,$activationURL,$callType);
				}else{
					email_profileactivation_send($arVals);
					handle_validateNsubmit_registration($errorsVarsArr['errorCode'], $formID, $callType);
				}//else
			}//else
		}//if
		elseif($editType=='update')
		{		
			if(!(check_combined_search($username)))
			{
				$errorsVarsArr['errorCode'] = 111;
				$errorsVarsArr['errorFieldID'] = 'username';
				$errorsVarsArr['errorFieldValue'] = $username;
				handle_validationError($errorsVarsArr,$formID,$callType); exit;
			}//
			else{}//ok
			if(!(check_email_exists($email)))
			{
				$errorsVarsArr['errorCode'] = 111;
				$errorsVarsArr['errorFieldID'] = 'email';
				$errorsVarsArr['errorFieldValue'] = $email;
				handle_validationError($errorsVarsArr,$formID,$callType); exit;
			}//
			else{}//ok
			$dbobj = new TBDBase(1);
			
			$query = "UPDATE user SET "
				."name=".$arVals['name'].", "
				."description=".$arVals['description'].", "
				."facebook=".$arVals['facebook'].", "
				."myspace=".$arVals['myspace'].", "
				."twitter=".$arVals['twitter'].", "
				."youtube=".$arVals['youtube'].", "
				."vimeo=".$arVals['vimeo'].", "
				."lastupdatedtimestamp=".$arVals['lastupdatedtimestamp']." "
				."WHERE username='".$username."' AND email='".$email."'; ";	
			$updateResult = $dbobj->executeUpdateQuery($query);
			
			unset($dbobj);
			$arVals = removeQuotes($arVals);
			
			user_session_reset($username,$email);
			
			handle_validateNsubmit_updateUser($errorsVarsArr['errorCode'], $formID,$callType);
			
		}//elseif
	}//

}//user_edit($editType)

function user_edit_toggleValues($editType,$callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbVars = array();

	$explodeArr = array();
	if(isset($_POST['formid'])){$formID = $_POST['formid'];}
	if(isset($_POST['fieldid'])){$fieldID = $_POST['fieldid']; unset($_POST['fieldid']); }

	$pageID = $_POST['pageid'];
	$errorsVarsArr = array();
	$errorCode = NULL;

	if(isset($_POST['username'])){$username = $_POST['username']; unset($_POST['username']);}
	if(isset($_POST['email'])){$email = $_POST['email']; unset($_POST['email']);}

	switch($formID)
	{
		case 'profileartisttag':
			
			$artistCategoryID = $_POST['tid'];
			$editTagAction = $_POST['tagaction'];
			$validatorFormID = 'profileartisttag'; $validatorCSRFaction = 'submitprofileartistcategories';
			
			$arValsRequired = array("tid"=>"");
			$arVals = array("tid"=>"","lastupdatedtimestamp"=>"");
			
			break;
		case 'profileartistgender':
			
			$artistGender = $_POST['gender'];
			$validatorFormID = 'profileartistgender'; $validatorCSRFaction = 'submitprofileartistgender';

			$arValsRequired = array("gender"=>"");
			$arVals = array("gender"=>"","lastupdatedtimestamp"=>"");
			
			break;
		case 'profileartistvisibility':

			$artistVisibilityStatus = $_POST['visibilitystatus'];
			$validatorFormID = 'profileartistvisibility'; $validatorCSRFaction = 'submitprofileartistvisibilitystatus';

			$arValsRequired = array("visibilitystatus"=>"");
			$arVals = array("visibilitystatus"=>"","lastupdatedtimestamp"=>"");
			break;
			
			
			
		case 'profileartistnewsletter':
		
			$artistStatus = $_POST['newsletter'];
			$validatorFormID = 'profileartistnewsletter'; $validatorCSRFaction = 'submitprofileartistnewsletter';

			$arValsRequired = array("newsletter"=>"");
			$arVals = array("newsletter"=>"","lastupdatedtimestamp"=>"");
		
			break;
		case 'profileartistalbumcomments':
		
			$artistStatus = $_POST['albumcomments'];
			$validatorFormID = 'profileartistalbumcomments'; $validatorCSRFaction = 'submitprofileartistalbumcomments';

			$arValsRequired = array("albumcomments"=>"");
			$arVals = array("albumcomments"=>"","lastupdatedtimestamp"=>"");
		
			break;
		case 'profileartistblogpostcomments':
		
		
			$artistStatus = $_POST['blogpostcomments'];
			$validatorFormID = 'profileartistblogpostcomments'; $validatorCSRFaction = 'submitprofileartistblogpostcomments';

			$arValsRequired = array("blogpostcomments"=>"");
			$arVals = array("blogpostcomments"=>"","lastupdatedtimestamp"=>"");
		
			break;
		case 'profileartistcommentnotifications':
		
		
			$artistStatus = $_POST['commentnotifications'];
			$validatorFormID = 'profileartistcommentnotifications'; $validatorCSRFaction = 'submitprofileartistcommentnotifications';

			$arValsRequired = array("commentnotifications"=>"");
			$arVals = array("commentnotifications"=>"","lastupdatedtimestamp"=>"");
		
			break;
			
		default:
			break;
	}//

	
	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok
	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok

	$arValsValidations = array();
	$arValsMaxSize = array();
	
	reset ($_POST);
	$arVals = $validator->customEncodeInputValues($_POST,$arVals);
	unset($_POST);
	
	$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	
	reset($arVals);
	while (list($key, $val) = each ($arVals))
	{
		if($key == 'description'){$arVals[$key] = "'".$arVals[$key]."'";}
		else{$arVals[$key] = "'".strtolower($arVals[$key])."'";}
	}//while
	
	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}
	
	if($errorsVarsArr['errorCode'] == 0)
	{
		$dbobj = new TBDBase(1);
		
		$selectTagsQuery = 'SELECT uid, tags, gender, visibilitystatus FROM user WHERE username="'.$username.'" AND email="'.$email.'"; ';
		$dbVars = $dbobj->executeSelectQuery($selectTagsQuery);	
		
		if($dbVars['NUM_ROWS'] != 0)
		{
			switch($formID)
			{
				case 'profileartisttag':
			
					$tagsOLD = $dbVars['RESULT'][0]['tags'];
					if($tagsOLD == '' || strtolower($tagsOLD) == 'null'){$tagsOLD = '';}
					else
					{
						//check if the selected tag is already in the tag list
						$tagsOLDArray = explode(':..::..:', $tagsOLD);
						$tagsOLD = '';
						
						unset($tagsOLDArray[count($tagsOLDArray)-1]);
						if(count($tagsOLDArray) !=0 )
						{
							for($k=0; $k<count($tagsOLDArray); $k++)
								{if($tagsOLDArray[$k] == $artistCategoryID){$indexOfValueToUnset = $k; }else{}}//for
							if(isset($tagsOLDArray[$indexOfValueToUnset])){unset($tagsOLDArray[$indexOfValueToUnset]);}
							while (list($key, $val) = each ($tagsOLDArray))
								{if(isset($tagsOLDArray[$key])){$tagsOLD = $tagsOLD . $tagsOLDArray[$key] . ':..::..:';}}//while
						}//if
					}//else
					
					if($editTagAction == 'addtag')
						{$tagsNEW = $tagsOLD.$artistCategoryID.':..::..:'; $formID = 'profileartisttag'.'_addtag'.'_'.$artistCategoryID;}
					elseif($editTagAction == 'removetag')
						{$tagsNEW = $tagsOLD; $formID = 'profileartisttag'.'_removetag'.'_'.$artistCategoryID;}
					
					$updateProfileQuery = "UPDATE user"
							. " SET tags="."'".$tagsNEW."', "
							. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
							. " WHERE username='".$username."' AND email='".$email."'; ";
					$updateResult = $dbobj->executeUpdateQuery($updateProfileQuery);
						
					break;
				case 'profileartistgender':
					
					$formID = 'profileartistgender'.'_'.$artistGender;
					$updateProfileQuery = "UPDATE user"
							. " SET gender="."'".$artistGender."', "
							. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
							. " WHERE username='".$username."' AND email='".$email."'; ";
					$updateResult = $dbobj->executeUpdateQuery($updateProfileQuery);
					
					break;
				case 'profileartistvisibility':
				
					$formID = 'profileartistvisibility'.'_'.$artistVisibilityStatus;
					$updateProfileQuery = "UPDATE user"
							. " SET visibilitystatus="."'".$artistVisibilityStatus."', "
							. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
							. " WHERE username='".$username."' AND email='".$email."'; ";
					$updateResult = $dbobj->executeUpdateQuery($updateProfileQuery);
				
					break;
					
				case 'profileartistnewsletter':
					
					$formID = 'profileartistnewsletter'.'_'.$artistStatus;
					$updateProfileQuery = "UPDATE user"
							. " SET newsletter="."'".$artistStatus."', "
							. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
							. " WHERE username='".$username."' AND email='".$email."'; ";
					$updateResult = $dbobj->executeUpdateQuery($updateProfileQuery);		
					
					break;
				case 'profileartistalbumcomments':
					
					$formID = 'profileartistalbumcomments'.'_'.$artistStatus;
					$updateProfileQuery = "UPDATE user"
							. " SET albumcomments="."'".$artistStatus."', "
							. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
							. " WHERE username='".$username."' AND email='".$email."'; ";
					$updateResult = $dbobj->executeUpdateQuery($updateProfileQuery);		
					
					break;
				case 'profileartistblogpostcomments':
					
					$formID = 'profileartistblogpostcomments'.'_'.$artistStatus;
					$updateProfileQuery = "UPDATE user"
							. " SET blogpostcomments="."'".$artistStatus."', "
							. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
							. " WHERE username='".$username."' AND email='".$email."'; ";
					$updateResult = $dbobj->executeUpdateQuery($updateProfileQuery);		
					
					break;
				case 'profileartistcommentnotifications':
					
					$formID = 'profileartistcommentnotifications'.'_'.$artistStatus;
					$updateProfileQuery = "UPDATE user"
							. " SET commentnotifications="."'".$artistStatus."', "
							. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
							. " WHERE username='".$username."' AND email='".$email."'; ";
					$updateResult = $dbobj->executeUpdateQuery($updateProfileQuery);		
					
					break;				
				default:
					break;			
			}//switch
			
		}//if
		

		unset($dbobj);
		$arVals = removeQuotes($arVals);
		user_session_reset($username,$email);
		handle_validateNsubmit_updateUserArtistTags($errorsVarsArr['errorCode'],$formID,$callType);
	}//
	

}//user_edit_toggleValues($editType,$callType)

function user_edit_privacy($editType,$callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	//require_once('emailfunctionsinc.php');
	
	$validator = new Validate();
	$dbVars = array();
	
	$explodeArr = array();
	if(isset($_POST['formid'])){$formID = $_POST['formid'];}
	if(isset($_POST['fieldid'])){$fieldID = $_POST['fieldid']; unset($_POST['fieldid']); }

	$pageID = $_POST['pageid'];
	$errorsVarsArr = array();
	$errorCode = NULL;
	
	if($editType=='update'){
		if(isset($_POST['username'])){$username = $_POST['username']; unset($_POST['username']);}
		if(isset($_POST['email'])){$email = $_POST['email']; unset($_POST['email']);}
		$validatorFormID = 'userprivacy'; $validatorCSRFaction = 'submitedituserprivacy';
		
		$arValsRequired = array(); //none of the form fields are required
		
		$arVals = array("visibilitystatus"=>"","lastupdatedtimestamp"=>"");
		
		$arValsValidations = array();
		$arValsMaxSize = array();
		
		reset ($_POST);
		$arVals = $validator->customEncodeInputValues($_POST,$arVals);
		unset($_POST);
		
		$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	}//if()
	
	reset($arVals);
	while (list($key, $val) = each ($arVals))
	{
		if($key == 'description'){$arVals[$key] = "'".$arVals[$key]."'";}
		else{$arVals[$key] = "'".strtolower($arVals[$key])."'";}
	}//while
	
	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}

	if($errorsVarsArr['errorCode'] == 0)
	{
		if($editType=='update')
		{		
			if(!(check_combined_search($username)))
			{
				$errorsVarsArr['errorCode'] = 111;
				$errorsVarsArr['errorFieldID'] = 'username';
				$errorsVarsArr['errorFieldValue'] = $username;
				handle_validationError($errorsVarsArr,$formID,$callType); exit;
			}//
			else{}//ok
			if(!(check_email_exists($email)))
			{
				$errorsVarsArr['errorCode'] = 111;
				$errorsVarsArr['errorFieldID'] = 'email';
				$errorsVarsArr['errorFieldValue'] = $email;
				handle_validationError($errorsVarsArr,$formID,$callType); exit;
			}//
			else{}//ok
			$dbobj = new TBDBase(1);
			
			$query = "UPDATE user SET "
				."visibilitystatus=".$arVals['visibilitystatus'].", "
				."lastupdatedtimestamp=".$arVals['lastupdatedtimestamp']." "
				."WHERE username='".$username."' AND email='".$email."'; ";	
			$updateResult = $dbobj->executeUpdateQuery($query);
			
			unset($dbobj);
			$arVals = removeQuotes($arVals);
			
			user_session_reset($username,$email);
			
			handle_validateNsubmit_updateUserPrivacy($errorsVarsArr['errorCode'], $formID,$callType);
			
		}//elseif
	}//
}//user_edit_privacy('update','ajax')

function user_session_reset($username, $email)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(1);
	$userInfo = array();
	$dbVars = array();
	$accountFound = false;
	
	//unset($_SESSION['user']);
	
	//find the user in the profiles database
	$checkProfileQuery = "SELECT * FROM user,website WHERE registrationstatus='complete' AND user.uid = website.uid AND username='".$username."' AND email='".$email."'; ";
	$dbVars = $dbobj->executeSelectQuery($checkProfileQuery);

	for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
	{	
		$_SESSION['user']['username'] = $dbVars['RESULT'][$i]['username'];
		$_SESSION['user']['email'] = $dbVars['RESULT'][$i]['email'];
		$_SESSION['user']['uid'] = $dbVars['RESULT'][$i]['uid'];
		$_SESSION['user']['password'] =  kevin(hash('sha256','REALLY, bagger off, wanker'));
		$_SESSION['user']['name'] = $dbVars['RESULT'][$i]['name'];
		$_SESSION['user']['gender'] = $dbVars['RESULT'][$i]['gender'];		
		$_SESSION['user']['accountstatus'] = $dbVars['RESULT'][$i]['accountstatus'];
		$_SESSION['user']['visibilitystatus'] = $dbVars['RESULT'][$i]['visibilitystatus'];
		
		$_SESSION['user']['facebook'] = $dbVars['RESULT'][$i]['facebook'];
		$_SESSION['user']['myspace'] = $dbVars['RESULT'][$i]['myspace'];
		$_SESSION['user']['twitter'] = $dbVars['RESULT'][$i]['twitter'];
		$_SESSION['user']['youtube'] = $dbVars['RESULT'][$i]['youtube'];
		$_SESSION['user']['vimeo'] = $dbVars['RESULT'][$i]['vimeo'];		
		$_SESSION['user']['newsletter'] = $dbVars['RESULT'][$i]['newsletter'];
		$_SESSION['user']['albumcomments'] = $dbVars['RESULT'][$i]['albumcomments'];
		$_SESSION['user']['blogpostcomments'] = $dbVars['RESULT'][$i]['blogpostcomments'];
		$_SESSION['user']['commentnotifications'] = $dbVars['RESULT'][$i]['commentnotifications'];
	
		$_SESSION['user']['title'] = $dbVars['RESULT'][$i]['title'];
		$_SESSION['user']['urltitle'] = $dbVars['RESULT'][$i]['urltitle'];
		$_SESSION['user']['uploadimageswidth'] = $dbVars['RESULT'][$i]['uploadimageswidth'];
		$_SESSION['user']['uploadimagestype'] = $dbVars['RESULT'][$i]['uploadimagestype'];	
		
		$_SESSION['user']['avatar'] = $dbVars['RESULT'][$i]['avatar'];
		
		if($_SESSION['user']['avatar'] != 0)
		{
			$queryAvatar = "SELECT iid,fileurl FROM image WHERE imagetype = 'usercover' AND uid='".$_SESSION['user']['uid']."' AND iid='".$_SESSION['user']['avatar']."'; ";
			$dbVars2 = $dbobj->executeSelectQuery($queryAvatar);
			if($dbVars2['NUM_ROWS'] != 0){$_SESSION['user']['coverimage'] = $dbVars2['RESULT'][0]['fileurl'];}//if
			else{$_SESSION['user']['coverimage'] = DEFAULT_BLANK_USER_THUMBNAIL;}
		}//if
		else{$_SESSION['user']['coverimage'] = DEFAULT_BLANK_USER_THUMBNAIL;}
		
		$_SESSION['user']['tags'] = $dbVars['RESULT'][$i]['tags'];
		$_SESSION['user']['description'] = $dbVars['RESULT'][$i]['description'];
		$_SESSION['user']['lastupdatedtimestamp'] = $dbVars['RESULT'][$i]['lastupdatedtimestamp'];	
		$_SESSION['user']['registrationtimestamp'] = $dbVars['RESULT'][$i]['submissiontimestamp'];
		$_SESSION['user']['views'] = $dbVars['RESULT'][$i]['views'];
		
		$_SESSION['user']['rguser_login'] = TRUE;
		$_SESSION['user']['user_tm'] = $_SESSION['FIRST_VISIT_TM']; //TIMESTAMP OF WHEN THE USER FIRST ENTERED THE SYSTEM			
	}//for
	
	reset($_SESSION['user']); while (list($key, $val) = each ($_SESSION['user']))
	{ if(($val == NULL)||(strtolower($val) == 'null')){$_SESSION['user'][$key] = '';} else {$_SESSION['user'][$key] = $val;}}
	
	unset($dbobj);

}//user_session_reset($username, $email)


function password_forgot_request($callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$dbVars = array();
	$explodeArr = array();
	if(isset($_POST['formid'])){$formID = $_POST['formid'];}
	$pageID = $_POST['pageid'];
	$errorsVarsArr = array();
	$errorCode = NULL;

	$validatorFormID = 'forgotpassword'; 
	$validatorCSRFaction = 'submitforgotpassword';

	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok
	
	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok

	$arVals = array("email"=>"");
	$arValsRequired = array("email"=>"");
	$arValsMaxSize = array("email"=>"100");
	$arValsValidations = array("email"=>"/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/");
	
	reset ($_POST);
	$arVals = $validator->customEncodeInputValues($_POST,$arVals);
	unset($_POST);
	
	reset($arVals);
	while (list($key, $val) = each ($arVals))
		{$arVals[$key] = "'".strtolower($arVals[$key])."'";}//while

	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}
	
	if($errorsVarsArr['errorCode'] == 0)
	{
		$checkEmailQuery = "SELECT username,email FROM user WHERE email=".$arVals['email']."; ";
		$dbVars = $dbobj->executeSelectQuery($checkEmailQuery);
		
		if($dbVars['NUM_ROWS'] != 1){ //ERROR
			$errorsVarsArr['errorCode'] = 114;
			$errorsVarsArr['errorFieldID'] = 'email';
			$errorsVarsArr['errorFieldValue'] = substr($arVals['email'],1,-1);
			handle_validationError($errorsVarsArr,$formID,$callType);
		}else{
			$arVals['username'] = "'".$dbVars['RESULT'][0]['username']."'";
			unset($dbobj);
			$arVals = removeQuotes($arVals);

			if(SYSTEM_MODE == 'server'){
				email_resetpassword_send($arVals);
				handle_validateNsubmit_forgotpassword($errorsVarsArr['errorCode'], $formID, $callType);
			}else{
				if(SYSTEM_EMAILS == 'disabled')
				{
					$timestamp = date("Y-m-d").date("H");
					$accountSettingsURL = SERVER_NAME.'/index.php?hobbs=';
					$code = hash('sha256',$timestamp.'resetpasswordofuserwithemail'.$arVals['email'].'andusername'.$arVals['username']).'2541d';
					$accountSettingsURL = $accountSettingsURL.$code;
					handle_validateNsubmit_forgotpasswordTESTING($errorsVarsArr['errorCode'], $formID,$accountSettingsURL,$callType);
				}else{
					email_resetpassword_send($arVals);
					handle_validateNsubmit_forgotpassword($errorsVarsArr['errorCode'], $formID, $callType);					
				}//else
			}//else

		}//else
	}//	
	
}//password_forgot_request()

function password_reset_request($callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$dbVars = array();
	$explodeArr = array();
	if(isset($_POST['formid'])){$formID = $_POST['formid'];}
	$pageID = $_POST['pageid'];
	$errorsVarsArr = array();
	$errorCode = NULL;
	$accountFound = false;

	$validatorFormID = 'resetpassword'; 
	$validatorCSRFaction = 'submitresetpassword';

	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok

	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok

	if($_POST['password']==$_POST['repeatpassword']){unset($_POST['repeatpassword']);}//all ok
	else{
		//typed passwords do not match
		$errorsVarsArr['errorCode'] = 113;
		$errorsVarsArr['errorFieldID'] = 'password';
		$errorsVarsArr['errorFieldValue'] = $_POST['password'];
		handle_validationError($errorsVarsArr,$formID,$callType);	
	}
	
	$arVals = array("password"=>"","hash"=>"");
	$arValsRequired = array("password"=>"","hash"=>"");
	$arValsMaxSize = array("password"=>"30");
	$arValsValidations = array();
	
	reset ($_POST);
	$arVals = $validator->customEncodeInputValues($_POST,$arVals);
	unset($_POST);
	
	$arVals['password'] = kevin(hash('sha256',$arVals['password']));
	//$arVals['password'] = hash('sha256',$arVals['password']);
	
	reset($arVals);
	while (list($key, $val) = each ($arVals))
		{$arVals[$key] = "'".strtolower($arVals[$key])."'";}//while

	$arVals['hash']=substr($arVals['hash'],1,-1);

	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}
	
	if($errorsVarsArr['errorCode'] == 0)
	{
		$checkUserIDQuery = "SELECT uid FROM user; ";
		$dbVars = $dbobj->executeSelectQuery($checkUserIDQuery);
		
		//$checkUserIDResult = @mysql_query($checkUserIDQuery); //or dbErrorHandler(802,mysql_error(),$checkEmailQuery,'php','','','category','false');
		for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
		{
			$tempID = $dbVars['RESULT'][$i]['uid'];
			$tempCode = hash('sha256',$tempID);
			
			if($tempCode == $arVals['hash']){$accountFound = true; $sUserID = $tempID; break;}			
		}//for

		if($accountFound){
		
			$updateProfileQuery = 'UPDATE user'
					. " SET password=".$arVals['password']
					. " WHERE uid='".$sUserID."'; ";
			$dbVars = $dbobj->executeUpdateQuery($updateProfileQuery);
			unset($dbobj);

			handle_validateNsubmit_resetpassword($errorsVarsArr['errorCode'], $formID, $callType);
		}//if
		else{
			//account not found
			$errorsVarsArr['errorCode'] = 114;
			$errorsVarsArr['errorFieldID'] = 'email';
			$errorsVarsArr['errorFieldValue'] = substr($arVals['email'],1,-1);
			handle_validationError($errorsVarsArr,$formID, $callType);
		}

	}//	
}//password_reset_request()

function user_activatenewaccount($actionCredentials)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$dbVars = array();
	
	$getProfilesQuery = 'SELECT username, email, accountstatus, registrationstatus FROM user; ';
	$dbVars = $dbobj->executeSelectQuery($getProfilesQuery);
	
	$accountFound = false;

	for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
	{
		$tempUsername = $dbVars['RESULT'][$i]['username'];
		$tempEmail = $dbVars['RESULT'][$i]['email'];
		$tempAccountStatus = $dbVars['RESULT'][$i]['accountstatus'];
		$tempRegistrationStatus = $dbVars['RESULT'][$i]['registrationstatus'];
		$tempCode = hash('sha256','activateuserwithemail'.$tempEmail.'andusername'.$tempUsername);

		if($tempCode == $actionCredentials){$accountFound = true; break;}
	}//for
	
	if($accountFound){
		if($tempRegistrationStatus == 'incomplete'){
			$updateProfileQuery = 'UPDATE user'
							. " SET registrationstatus='complete'"
							. " WHERE username='".$tempUsername."' AND email='".$tempEmail."'; ";
			$dbVars = $dbobj->executeUpdateQuery($updateProfileQuery);
		
			echo "<span class='messages'>";
			echo 'Account with username: <span class="highlight_color">'.$tempUsername.'</span><br /> and email: <span class="highlight_color">'.$tempEmail.'</span> has been activated';
			echo '<br /><br /><a class="blacklink home_links" href="./index.php" class="whitelink">Return to home page.</a>';
			echo "</span>";
		}//if
		else{
		echo "<span class='messages'>";
			echo "Account not found.";
			echo '<br /><br /><a class="blacklink home_links" href="./index.php" class="whitelink">Return to home page.</a>';
		echo "</span>";
		}//else
	}
	else{
		echo "<span class='messages'>";
			echo "Account not found.";
			echo '<br /><br /><a class="blacklink home_links" href="./index.php" class="whitelink">Return to home page.</a>';
		echo "</span>";
	}//else
	
}//user_activatenewaccount($actionCredentials)

function user_resetpassword_check($actionCredentials)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$dbVars = array();

	$getProfilesQuery = 'SELECT uid, username, email, accountstatus, registrationstatus FROM user; ';
	$dbVars = $dbobj->executeSelectQuery($getProfilesQuery);
	
	$timestamp = date("Y-m-d").date("H");
	$accountFound = false;
	
	for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
	{
		$tempUserID = $dbVars['RESULT'][$i]['uid'];
		$tempUsername = $dbVars['RESULT'][$i]['username'];
		$tempEmail = $dbVars['RESULT'][$i]['email'];
		$tempAccountStatus = $dbVars['RESULT'][$i]['accountstatus'];
		$tempRegistrationStatus = $dbVars['RESULT'][$i]['registrationstatus'];
		$tempCode = hash('sha256',$timestamp.'resetpasswordofuserwithemail'.$tempEmail.'andusername'.$tempUsername);
		if($tempCode == $actionCredentials){$accountFound = true; $sUserID = $tempUserID; $aaa = $tempUsername; break;}
	}//for

	if(!$accountFound)
	{
		echo "<span class='messages'>";
			echo "Account not found.";
			echo '<br /><br /><a class="blacklink home_links" href="./index.php" class="whitelink">Return to home page.</a>';
		echo "</span>";
	}//
	else if($accountFound){
		layout_get_resetpasswordform($sUserID);
	}
	else{
		echo "<span class='messages'>";
			echo "Account not found.";
			echo '<br /><br /><a class="blacklink home_links" href="./index.php" class="whitelink">Return to home page.</a>';
		echo "</span>";
	}//else

}//user_resetpassword_check($actionCredentials)


function user_get_artistcategories($profileTags)
{	
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$tagArr = array();
	$dbVars = array();

	if($profileTags != '')
	{
		$profileTags = explode(':..::..:',$profileTags);
		unset($profileTags[count($profileTags)-1]);
	}//

	$selectTagsQuery = "SELECT * FROM tag WHERE description = 'artist_type' AND type = 'profile' ORDER BY tid ASC; ";
	$dbVars = $dbobj->executeSelectQuery($selectTagsQuery);	
	
	if($dbVars['NUM_ROWS'] != 0)
	{
		for($j=0; $j<$dbVars['NUM_ROWS']; $j++)
		{
			$tagArr[$j]['tid'] =  $dbVars['RESULT'][$j]['tid'];
			$tagArr[$j]['name'] = $dbVars['RESULT'][$j]['name'];
			$tagArr[$j]['description'] = $dbVars['RESULT'][$j]['description'];
			$tagArr[$j]['type'] = $dbVars['RESULT'][$j]['type'];
		}//
	}else{}//no query result

	echo "<span id='profileartistcategorieslist'>";
	    echo "<span id='profileartisttag_frm_messages' class='messages'></span>";
		echo "<span id='profileartisttag_frm' class=''>";                  
		for($i=0; $i<count($tagArr); $i++)
		{
			$tagClass = '';
			if($profileTags != '')
			{
				for($k=0; $k<count($profileTags); $k++)
					{if($tagArr[$i]['tid'] == $profileTags[$k]){$tagClass = 'selectedtag';}}//for
			}//if
			
			echo "<span id='"."profileartisttag"."_".$tagArr[$i]['tid'] ."' class='profileartisttags ".$tagClass."'>".$tagArr[$i]['name']."</span>";
		}//for
		echo "</span>";
		echo "<span class='field_messages' id='profileartisttag_message'></span>";
		echo "<span class='' id='profileartisttag_loader_placeholder'></span>";

	echo "</span>";
	unset($dbobj);
}//user_get_artistcategories($profileTags)

?>