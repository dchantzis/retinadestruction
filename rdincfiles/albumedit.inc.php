<?php

function album_edit($editType,$callType)
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
	
	if($editType=='update'){
		$validatorFormID = 'album'; $validatorCSRFaction = 'submiteditalbum'; $categoryName = trim($_POST['category']);
		$albumID = $_POST['aid'];

		$arVals = array("aid"=>"","name"=>"","description"=>"","embeddedvideos"=>"","tid"=>"0","lastupdatedtimestamp"=>"");
		$arValsRequired = array();
		$arValsMaxSize = array("name"=>50, "description"=>ALBUM_DESCRIPTION_MAX_LENGTH,"embeddedvideos"=>ALBUM_DESCRIPTION_MAX_LENGTH,"category"=>50);
		$arValsValidations = array();
		
		$embeddedVideos = '"'.$_POST['embeddedvideos'].'"';
	}//if
	elseif($editType == 'delete'){
		$albumID = $_POST['aid'];
		$validatorFormID = 'albumdelete'; $validatorCSRFaction = 'submitalbumdelete';
		
		$arValsRequired = array("aid"=>"");
		$arVals = array("aid"=>"","lastupdatedtimestamp"=>"");
		$arValsMaxSize = array();
		$arValsValidations = array();
	}//elseif
	elseif($editType=='insert'){
		$validatorFormID = 'createalbum'; $validatorCSRFaction = 'submitcreatealbum';
	
		$arVals = array("uid"=>$userData['uid'],"name"=>"unamed album","description"=>"","embeddedvideos"=>"","tid"=>"0","coverid"=>"0","imagesorder"=>"","visibilitystatus"=>"visible","imageview"=>"thumbnails","views"=>"0","imagescounter"=>"0","creationtimestamp"=>"","lastupdatedtimestamp"=>"");
		$arValsRequired = array();
		$arValsMaxSize = array();
		$arValsValidations = array();
		
	}//elseif

	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok

	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok

	if($editType=='insert')
	{	
		reset ($_POST);
		$arVals = $validator->customEncodeInputValues($_POST,$arVals);
		unset($_POST);
		
		$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
		$arVals['creationtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	}//outer if
	else if($editType=='delete')
	{
		reset ($_POST);
		$arVals = $validator->customEncodeInputValues($_POST,$arVals);
		unset($_POST);
		
		$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	}//elseif
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
		$dbobj = new TBDBase(1);
		
		$selectTagsQuery = 'SELECT uid, tags, gender, visibilitystatus FROM user WHERE username="'.$username.'" AND email="'.$email.'"; ';
		$dbVars = $dbobj->executeSelectQuery($selectTagsQuery);	
		$userID = $dbVars['RESULT'][0]['uid'];

		if($editType=='insert')
		{	
			
			$selectQuery = "SELECT count(*) AS albumcount FROM album WHERE uid=".$userID."; ";
			$dbVars = $dbobj->executeSelectQuery($selectQuery);
			$albumCount = ($dbVars['RESULT'][0]['albumcount'])+1;
			
			$arVals['name'] = "'".removeQuotesSingleValue($arVals['name'])." ".$albumCount."'";
			
			//create the user
			$insertQuery = $dbobj->createInsertQuery("album", $arVals);
			$insertID = $dbobj->executeInsertQuery($insertQuery);
			$arVals = removeQuotes($arVals);
			//create the website	
			
			unset($dbobj);
			handle_validateNsubmit_createAlbum($errorsVarsArr['errorCode'],$formID,$callType,$insertID);
			
		}//if
		elseif($editType=='delete')
		{
			//get all the images of that album
			$selectAlbumImages = "SELECT iid, fileurl FROM image WHERE aid='".$albumID."' AND uid='".$userID."'; ";
			$dbVars_SelectAlbumImages = $dbobj->executeSelectQuery($selectAlbumImages);
			if($dbVars_SelectAlbumImages['NUM_ROWS']!=0)
			{
				$deleteAlbumImagesQuery = '';
				for($i=0; $i<$dbVars_SelectAlbumImages['NUM_ROWS']; $i++)
				{
					$deleteAlbumImagesQuery = "DELETE FROM image WHERE iid='".$dbVars_SelectAlbumImages['RESULT'][$i]['iid']."' AND aid='".$albumID."'; ";
					$dbVarsTemp = $dbobj->executeDeleteQuery($deleteAlbumImagesQuery);
					
					$deleteResultTemp = deleteFromFileserver(IMAGES_FOLDER_FULL_RESOLUTION.'/'.$dbVars_SelectAlbumImages['RESULT'][$i]['fileurl'],'image',$username);
					$deleteResultTemp = deleteFromFileserver(IMAGES_FOLDER_LARGE_THUMBNAILS.'/'.$dbVars_SelectAlbumImages['RESULT'][$i]['fileurl'],'image',$username);
					$deleteResultTemp = deleteFromFileserver(IMAGES_FOLDER_THUMBNAILS.'/'.$dbVars_SelectAlbumImages['RESULT'][$i]['fileurl'],'image',$username);
					
				}//for
			}//if
			
		
			$formID = 'albumdelete_'.$albumID;
			$deleteQuery = "DELETE FROM album WHERE uid='".$userID."' AND aid='".$albumID."'; ";			
			$dbVars = $dbobj->executeDeleteQuery($deleteQuery);
		
			unset($dbobj);
			$arVals = removeQuotes($arVals);
			handle_validateNsubmit_deleteAlbum($errorsVarsArr['errorCode'],$formID,$callType);
		}
		elseif($editType=='update')
		{
			
			if($categoryName != '')
			{
				$selectCategory = "SELECT * FROM tag WHERE uid='".$userID."' AND name=".$arVals['category']." AND type='album'; ";
				$dbVars2 = $dbobj->executeSelectQuery($selectCategory);	
				
				if($dbVars2['NUM_ROWS'] == 0)
				{
					$arValsTag = array("uid"=>$userID,"name"=>$categoryName,"description"=>"album_category","type"=>"album");				
					while (list($key, $val) = each ($arValsTag)){$arValsTag[$key] = "'".strtolower($arValsTag[$key])."'";}
					$insertQuery = $dbobj->createInsertQuery("tag", $arValsTag);
					
					$insertID = $dbobj->executeInsertQuery($insertQuery);
					$arVals['tid'] = "'".$insertID."'";
				}//if
				else
				{
					$arVals['tid'] = "'".$dbVars2['RESULT'][0]['tid']."'";
				}//else
			}else{$arVals['tid'] = "'0'";}
			
			unset($arVals['category']);

			$arVals['embeddedvideos'] = $embeddedVideos;
			//$arVals['embeddedvideos'] = real_strip_tags($arVals['embeddedvideos'], array('object', 'embed', 'param'), TRUE);
			//$arVals['embeddedvideos'] = (get_magic_quotes_gpc()) ? $arVals['embeddedvideos'] : addslashes($arVals['embeddedvideos']);
			//$arVals['embeddedvideos'] = "'".$arVals['embeddedvideos']."'";

			$query = "UPDATE album SET "
				."name=".$arVals['name'].", "
				."description=".$arVals['description'].", "
				."embeddedvideos=".$arVals['embeddedvideos'].", "
				."tid=".$arVals['tid'].", "
				."lastupdatedtimestamp=".$arVals['lastupdatedtimestamp']." "
				."WHERE uid='".$userID."' AND aid=".$arVals['aid']."; ";	
			$updateResult = $dbobj->executeUpdateQuery($query);

			unset($dbobj);
			$arVals = removeQuotes($arVals);
			$formID = 'album_'.$arVals['aid'];
			
			handle_validateNsubmit_updateAlbum($errorsVarsArr['errorCode'],$formID,$callType);
		}//elseif
	}//
	
}//album_edit($editType,$callType)


function album_edit_toggleValues($editType,$callType)
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
		case 'albumvisibility':
			
			$albumID = $_POST['aid'];
			$visibilityStatus = $_POST['visibilitystatus'];
			
			if($visibilityStatus == 'albumvisible'){$visibilityStatus = 'invisible';}
			else{$visibilityStatus = 'visible';}
			
			$validatorFormID = 'albumvisibility'; $validatorCSRFaction = 'submitalbumvisibilitystatus';
			
			$arValsRequired = array("aid"=>"","visibilitystatus"=>"");
			$arVals = array("aid"=>"","visibilitystatus"=>"","lastupdatedtimestamp"=>"");
			
			break;
		case 'albumimageview':
			$albumID = $_POST['aid'];
			$imageview = $_POST['imageview'];
			$validatorFormID = 'albumimageview'; $validatorCSRFaction = 'submitalbumimageview';

			$arValsRequired = array("aid"=>"","imageview"=>"");
			$arVals = array("aid"=>"","imageview"=>"","lastupdatedtimestamp"=>"");
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
		$arVals[$key] = "'".strtolower($arVals[$key])."'";
	}//while
	
	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}
	
	if($errorsVarsArr['errorCode'] == 0)
	{
		$dbobj = new TBDBase(1);
		
		$selectTagsQuery = 'SELECT uid, tags, gender, visibilitystatus FROM user WHERE username="'.$username.'" AND email="'.$email.'"; ';
		$dbVars = $dbobj->executeSelectQuery($selectTagsQuery);	
		$userID = $dbVars['RESULT'][0]['uid'];
		switch($formID)
		{
			case 'albumvisibility':
				$formID = 'albumvisibility_'.$albumID.'_'.$visibilityStatus;
				$updateAlbumQuery = "UPDATE album"
						. " SET visibilitystatus="."'".$visibilityStatus."', "
						. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
						. " WHERE aid='".$albumID."' AND uid='".$userID."'; ";
				$updateResult = $dbobj->executeUpdateQuery($updateAlbumQuery);
				break;
			case 'albumimageview':
				$formID = 'album_imageview_'.$albumID.'_'.$imageview;
				$updateAlbumQuery = "UPDATE album"
						. " SET imageview="."".$arVals['imageview'].", "
						. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
						. " WHERE aid='".$albumID."' AND uid='".$userID."'; ";
				$updateResult = $dbobj->executeUpdateQuery($updateAlbumQuery);
				break;
			default:
				break;			
		}//switch
		
		unset($dbobj);
		$arVals = removeQuotes($arVals);
		//user_session_reset($username,$email);
		handle_validateNsubmit_updateAlbumVisibility($errorsVarsArr['errorCode'],$formID,$callType);
	}//

}//album_edit_toggleValues($editType,$callType)


function album_edit_imagesorder($callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(1);
	$validator = new Validate();
	$dbVars = array();
	
	$explodeArr = array();
	if(isset($_POST['formid'])){$formID = $_POST['formid'];}
	if(isset($_POST['fieldid'])){$fieldID = $_POST['fieldid']; unset($_POST['fieldid']); }
	$email = $_POST['email']; unset($_POST['email']);
	$username = $_POST['username']; unset($_POST['username']);
	$albumID = $_POST['aid']; unset($_POST['aid']);
	$imagesOrder = $_POST['albumimagesorder']; //unset($_POST['albumimagesorder']);
	
	$errorsVarsArr = array();
	$imagesArrVars = array();
	$portfolioImagesArrOrderedOLD = array();
	$portfolioImagesOrderNEW = '';
	$errorCode = NULL;
	
	$validatorFormID = 'albumimagereorder';
	$validatorCSRFaction = 'submitalbumimagesreorderlist';
	$fieldValue = $_POST['albumimagesorder'];
	$fieldID = 'albumimages_order';
	$arValsRequired = array();
	
	
	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok

	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok
	
	reset ($_POST);
	$arVals = $validator->customEncodeInputValues($_POST,$arVals);
	unset($_POST);

	$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	
	reset($arVals);
	while (list($key, $val) = each ($arVals))
		{$arVals[$key] = "'".strtolower($arVals[$key])."'";}//while

/*
DON'T NEED THIS
	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0)
		{validationErrorsXMLResponse($errorsVarsArr,$formID); exit;}
*/
	$errorsVarsArr['errorCode'] = 0;
	if($errorsVarsArr['errorCode'] == 0)
	{
		$selectUserIDQuery = "SELECT uid FROM user WHERE username='".$username."' AND email='".$email."'; ";
		$dbVarsUserID = $dbobj->executeSelectQuery($selectUserIDQuery);
		$userID = $dbVarsUserID['RESULT'][0]['uid'];
	
		$updateImagesOrderQuery = "UPDATE album"
					." SET lastupdatedtimestamp=".$arVals['lastupdatedtimestamp'].", imagesorder=".$arVals['albumimagesorder'].""
					." WHERE uid='".$userID."' AND aid='".$albumID."'; ";
		$updateImagesOrderResult = @mysql_query($updateImagesOrderQuery);
		
		$arVals = removeQuotes($arVals);
		
		$arVals['albumimagesorder'] = explode(':..::..:',$arVals['albumimagesorder']);
		unset($arVals['albumimagesorder'][count($arVals['albumimagesorder'])-1]);
		$newAlbumImagesOrder='';
		for($p=0; $p<count($arVals['albumimagesorder']); $p++){$newAlbumImagesOrder.=$arVals['albumimagesorder'][$p].',';} 
		$fieldValue = $newAlbumImagesOrder;
		
		unset($dbobj);
		handle_validateNsubmit_albumimagesorder($errorsVarsArr['errorCode'], $formID, $fieldID, $fieldValue, 'ajax');
	}//if


}//album_edit_imagesorder($callType)


?>