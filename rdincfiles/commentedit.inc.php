<?php

function comment_edit($editType,$callType)
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
	$userID = $_POST['uid'];
	$albumID = $_POST['aid'];
	$postID = $_POST['pid'];
	$commentorEmail = $_POST['email'];
	$commentFor = $_POST['commentfor']; unset($_POST['commentfor']);
	
	if($editType=='insert'){
		$validatorFormID = 'comment'; $validatorCSRFaction = 'submitcommentwhatever';
	
		$arVals = array("pid"=>"","uid"=>"","aid"=>"","name"=>"","email"=>"","body"=>"","submitiontimestamp"=>"");
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

		$arVals['submitiontimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	}//outer if
	
	reset($arVals);
	while (list($key, $val) = each ($arVals))
	{
		if($key == 'body'){$arVals[$key] = "'".$arVals[$key]."'";}
		else{$arVals[$key] = "'".strtolower($arVals[$key])."'";}
	}//while
	
	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}

	if($errorsVarsArr['errorCode'] == 0)
	{
		$dbobj = new TBDBase(0);
	
		//find the profile username, uid, email
		$queryUser = "SELECT uid, username, email,commentnotifications FROM user WHERE uid='".$userID."'; ";
		$dbVarsUser = $dbobj->executeSelectQuery($queryUser);
		
		$ownerUserID = $dbVarsUser['RESULT'][0]['uid'];
		$ownerUsername = $dbVarsUser['RESULT'][0]['username'];
		$ownerUserEmail = $dbVarsUser['RESULT'][0]['email'];
		$emailNotifications = $dbVarsUser['RESULT'][0]['commentnotifications'];
		
		if($editType=='insert')
		{	
			$insertQuery = $dbobj->createInsertQuery("comment", $arVals);
			$insertID = $dbobj->executeInsertQuery($insertQuery);
			$arVals = removeQuotes($arVals);
			
			switch($commentFor)
			{
				case 'album':
					$queryAlbumName = "SELECT name FROM album WHERE aid='".$arVals['aid']."' AND uid='".$userID."'; ";
					$dbVars = $dbobj->executeSelectQuery($queryAlbumName);
					$arVals['comment_where'] = $dbVars['RESULT'][0]['name'];
					$arVals['comment_where'] = " album, named \"".$arVals['comment_where']."\".";
					
					unset($dbobj);
					$currentCommentsCounter = album_update_comments_counter($albumID);
					break;
				case 'post':
					$queryAlbumName = "SELECT headline FROM posts WHERE pid='".$arVals['pid']."' AND uid='".$userID."'; ";
					$dbVars = $dbobj->executeSelectQuery($queryAlbumName);
					$arVals['comment_where'] = $dbVars['RESULT'][0]['headline'];
					$arVals['comment_where'] = " news entry, titled \"".$arVals['comment_where']."\".";
					
					unset($dbobj);
					$currentCommentsCounter = blog_update_comments_counter($postID);
					break;
			}//switch
			
			if(SYSTEM_EMAILS == 'enabled')
			{
				if($emailNotifications == 'enabled')//check if the registered user for whom the email is about, wants to receive this kind of email notifications
				{
					if($ownerUserEmail == $commentorEmail){}//  the commentor is the owner registered user, do not send him an email
					else{email_newcomment_send($arVals, $dbVarsUser['RESULT'][0]);}
				}//if
			}//if
		
			handle_validateNsubmit_comment($errorsVarsArr['errorCode'],$formID,$userID,$albumID,$postID,$currentCommentsCounter,$commentFor,$callType);
		}//if
	}//
	
}//comment_edit($editType,$callType)

?>