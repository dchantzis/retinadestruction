<?php

function website_edit($editType,$callType)
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
		switch($formID)
		{
			case 'website':
				if(isset($_POST['username'])){$username = $_POST['username']; unset($_POST['username']);}
				if(isset($_POST['email'])){$email = $_POST['email']; unset($_POST['email']);}
				$validatorFormID = 'website'; $validatorCSRFaction = 'submiteditwebsite';
				
				$arValsRequired = array(); //none of the form fields are required
				$arVals = array("title"=>"","urltitle"=>"","lastupdatedtimestamp"=>"");
				$arValsValidations = array("urltitle"=>"/^[a-z][\w]+[a-z0-9]$/");
				$arValsMaxSize = array("title"=>"80","urltitle"=>"25");
				break;
			case 'uploadimagessettingswidth':
				if(isset($_POST['username'])){$username = $_POST['username']; unset($_POST['username']);}
				if(isset($_POST['email'])){$email = $_POST['email']; unset($_POST['email']);}
				$validatorFormID = 'uploadimagessettingswidth'; $validatorCSRFaction = 'submituploadimageswidth';
			
				$uploadImagesWidth = (int)$_POST['uploadimageswidth'];

				if($uploadImagesWidth > 1024){$uploadImagesWidth = '1024';}
				else if($uploadImagesWidth < 350){$uploadImagesWidth = '800';}

				$arValsRequired = array(); //none of the form fields are required
				$arVals = array("uploadimageswidth"=>"","lastupdatedtimestamp"=>"");
				$arValsValidations = array();
				$arValsMaxSize = array("uploadimageswidth"=>"4");
				break;
			case 'designeditor':
				if(isset($_POST['uid'])){$userID = $_POST['uid']; unset($_POST['uid']);}
				if(isset($_POST['templateid'])){$templateID = $_POST['templateid']; unset($_POST['templateid']);}
				$textfontfamily = $_POST['textfontfamily'];
				$validatorFormID = 'designeditor'; $validatorCSRFaction = 'submitdesigneditor';

				$arValsRequired = array(); //none of the form fields are required
				
				$arVals = array("wallpapertype"=>"","wallpaper"=>"","wallpapercolor"=>"","logotext"=>"","sidebarorientation"=>"","texttextcolor"=>"","textlinkcolor"=>"","linkbgcolor"=>"","textlinkhovercolor"=>"","textlinkhoverbgcolor"=>"","textnavilinksize"=>"","sectionsbg"=>"","textfontfamily"=>'',"texthighlightcolor"=>"","textbghighlightcolor"=>"","imagetagcloud"=>"","sidebar_updated"=>"","sidebar_copyright"=>"","sidebar_email"=>"");
				
				$arValsValidations = array();
				$arValsMaxSize = array("uploadimageswidth"=>"6");				
				break;
		}//switch
	}//if
	
	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok

	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok
	
	if($editType=='update')
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
		if($editType=='update')
		{
			switch($formID)
			{
				case 'website':
					if(check_email_exists(removeQuotesSingleValue($arVals['email']))){
						$errorsVarsArr['errorCode'] = 104;
						$errorsVarsArr['errorFieldID'] = 'email';
						$errorsVarsArr['errorFieldValue'] = substr($arVals['email'],1,-1);
						handle_validationError($errorsVarsArr,$formID,$callType); exit;
					}//
					
					$dbobj = new TBDBase(1);
					
					$selectQuery = "SELECT uid FROM user WHERE username='".$username."' AND email='".$email."'; ";	
					$dbVars = $dbobj->executeSelectQuery($selectQuery);
					$userID = $dbVars['RESULT'][0]['uid'];
					
					$query = "UPDATE website SET "
						."title=".$arVals['title'].", "
						."urltitle=".$arVals['urltitle'].", "
						."lastupdatedtimestamp=".$arVals['lastupdatedtimestamp']." "
						."WHERE uid='".$userID."'; ";	
					$updateResult = $dbobj->executeUpdateQuery($query);
					
					unset($dbobj);
					$arVals = removeQuotes($arVals);
					
					handle_validateNsubmit_updateWebsite($errorsVarsArr['errorCode'], $formID,$callType);
				break;
				case 'uploadimagessettingswidth':
					
					$dbobj = new TBDBase(1);
					
					$selectQuery = "SELECT uid FROM user WHERE username='".$username."' AND email='".$email."'; ";	
					$dbVars = $dbobj->executeSelectQuery($selectQuery);
					$userID = $dbVars['RESULT'][0]['uid'];
					
					$query = "UPDATE website SET "
						."uploadimageswidth='".$uploadImagesWidth."', "
						."lastupdatedtimestamp=".$arVals['lastupdatedtimestamp']." "
						."WHERE uid='".$userID."'; ";	
					$updateResult = $dbobj->executeUpdateQuery($query);
					
					unset($dbobj);
					$arVals = removeQuotes($arVals);
					$_SESSION['user']['uploadimageswidth'] = $uploadImagesWidth;
					
					handle_validateNsubmit_updateWebsite($errorsVarsArr['errorCode'], $formID,$callType);
				break;
			case 'designeditor':
			$dbobj = new TBDBase(1);
				$arVals = removeQuotes($arVals);
				reset($arVals);
				while (list($key, $val) = each ($arVals))
				{
					if (strtolower($val) == "null"){ $arVals[$key] = '';}
					if (strtolower($val) == NULL){ $arVals[$key] = '';}
				}//while
				
				$designformoptions = '';
				$designformoptions .= 'wallpaper:'.$arVals['wallpaper'].':..::..::';
				$designformoptions .= 'wallpapercolor:'.$arVals['wallpapercolor'].':..::..::';
				$designformoptions .= 'logo:text_'.$arVals['logotext'].':..::..::';
				$designformoptions .= 'sidebarorientation:'.$arVals['sidebarorientation'].':..::..::';
				$designformoptions .= 'text:textcolor_'.$arVals['texttextcolor'].':..::..::';
				$designformoptions .= 'text:linkcolor_'.$arVals['textlinkcolor'].':..::..::';
				$designformoptions .= 'text:linkbgcolor_'.$arVals['linkbgcolor'].':..::..::';
				$designformoptions .= 'text:linkhovercolor_'.$arVals['textlinkhovercolor'].':..::..::';
				$designformoptions .= 'text:linkhoverbgcolor_'.$arVals['textlinkhoverbgcolor'].':..::..::';
				$designformoptions .= 'text:navilinksize_'.$arVals['textnavilinksize'].':..::..::';
				$designformoptions .= 'sectionsbg:'.$arVals['sectionsbg'].':..::..::';
				$designformoptions .= 'text:fontfamily_'.$textfontfamily.':..::..::';
				$designformoptions .= 'text:highlightcolor_'.$arVals['texthighlightcolor'].':..::..::';
				$designformoptions .= 'text:bghighlightcolor_'.$arVals['textbghighlightcolor'].':..::..::';
				$designformoptions .= 'wallpapertype:'.$arVals['wallpapertype'].'';
			
				$sidebarinfo = 'updated:'.$arVals['sidebar_updated'].':.:.:copyright:'.$arVals['sidebar_copyright'].':.:.:email:'.$arVals['sidebar_email'] ;
				
				if($arVals['wallpaper'] == 'null'){$arVals['wallpaper'] = '';}
				if($arVals['wallpaper'] == 'image'){$arVals['wallpaper'] = 'wallpaper.png';}
				if($arVals['logotext'] == 'image'){$arVals['logotext'] = 'logo.png';}
				
				
				$query = "UPDATE website SET "
					."wallpaper='".$arVals['wallpaper']."', "
					."logo='".$arVals['logotext']."', "
					."sidebarinfo='".$sidebarinfo."', "
					."sidebarorientation='".$arVals['sidebarorientation']."', "
					."imagetagcloud='".$arVals['imagetagcloud']."',"
					."designformoptions='".$designformoptions."', "
					."lastupdatedtimestamp='".$arVals['lastupdatedtimestamp']."' "
					."WHERE uid='".$userID."' ; ";	
				$updateResult = $dbobj->executeUpdateQuery($query);

				
				handle_validateNsubmit_updateWebsiteDesign($errorsVarsArr['errorCode'], $formID,$callType);
				unset($dbobj);
				break;
			}//switch
		}//elseif
	}//
}//album_edit_toggleValues($editType,$callType)

function website_edit_toggleValues($editType,$callType)
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
	$userData = $_SESSION['user'];

	if(isset($_POST['username'])){$username = $_POST['username']; unset($_POST['username']);}
	if(isset($_POST['email'])){$email = $_POST['email']; unset($_POST['email']);}


	switch($formID)
	{
		case 'uploadimagessettingsoption':		
			$uploadImagesType = $_POST['uploadimagestype'];
			
			switch($_POST['uploadimagestype'])
			{
				case 'option1': $uploadImagesType = '1'; break;
				case 'option2': $uploadImagesType = '2'; break;
				case 'option3': $uploadImagesType = '3'; break;
				default: $uploadImagesType = '3'; break;
			}//switch
			
			$validatorFormID = 'uploadimagessettingsoption'; $validatorCSRFaction = 'submituploadimagessettingsoption';
			
			$arValsRequired = array("uploadimagestype"=>"");
			$arVals = array("uploadimagestype"=>"","lastupdatedtimestamp"=>"");
			
			break;
		case 'coverpage':
			$uploadImagesType = $_POST['uploadimagestype'];
			
			switch($_POST['coverpage'])
			{
				case 'empty': $coverPageType = 'empty'; break;
				case 'blogsection': $coverPageType = 'blogsection'; break;
				case 'randomimage': $coverPageType = 'randomimage'; break;
				default: $coverPageType = 'empty'; break;
			
			}//switch
			
			$validatorFormID = 'coverpage'; $validatorCSRFaction = 'submitcoverpage';
			
			$arValsRequired = array("coverpage"=>"");
			$arVals = array("uploadimagestype"=>"","lastupdatedtimestamp"=>"");
			
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
		$arVals[$key] = "'".strtolower($arVals[$key])."'";
	}//while
	
	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}
	
	if($errorsVarsArr['errorCode'] == 0)
	{
		$dbobj = new TBDBase(1);

		switch($formID)
		{
			case 'uploadimagessettingsoption':			
				$updateAlbumQuery = "UPDATE website"
						. " SET uploadimagestype="."'".$uploadImagesType."', "
						. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
						. " WHERE uid='".$userData['uid']."'; ";
				$updateResult = $dbobj->executeUpdateQuery($updateAlbumQuery);
				
				$formID = 'uploadimagessettingsoption'.'_'.'option'.$uploadImagesType;
				$_SESSION['user']['uploadimagestype'] = $uploadImagesType;
				break;
			case 'coverpage':
				$updateCoverPageQuery = "UPDATE website"
						. " SET coverpage="."'".$coverPageType."', "
						. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
						. " WHERE uid='".$userData['uid']."'; ";
				$updateResult = $dbobj->executeUpdateQuery($updateCoverPageQuery);
				
				$formID = 'coverpage'.'_'.$coverPageType;
				break;
			default:
				break;			
		}//switch
		
		unset($dbobj);
		$arVals = removeQuotes($arVals);
		handle_validateNsubmit_updateImageSettingsOptions($errorsVarsArr['errorCode'],$formID,$callType);
	}//

}//website_edit_toggleValues($editType,$callType)

?>