<?php

function blog_edit($editType,$callType)
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
		$validatorFormID = 'blog'; $validatorCSRFaction = 'submiteditblog'; $categoryName = trim($_POST['category']);
		$postID = $_POST['pid'];

		$arVals = array("pid"=>"","headline"=>"","body"=>"","embeddedvideos"=>"","tags"=>"0","lastupdatedtimestamp"=>"");
		$arValsRequired = array();
		$arValsMaxSize = array("headline"=>70, "body"=>POST_MESSAGE_MAX_LENGTH,"embeddedvideos"=>POST_MESSAGE_MAX_LENGTH,"category"=>50);
		$arValsValidations = array();
		
		$embeddedVideos = '"'.$_POST['embeddedvideos'].'"';
	}//if
	elseif($editType == 'delete'){
		$postID = $_POST['pid'];
		$validatorFormID = 'blogdelete'; $validatorCSRFaction = 'submitpostdelete';
		
		$arValsRequired = array("pid"=>"");
		$arVals = array("pid"=>"","lastupdatedtimestamp"=>"");
		$arValsMaxSize = array();
		$arValsValidations = array();
	}//elseif
	elseif($editType=='insert'){
		$validatorFormID = 'createpost'; $validatorCSRFaction = 'submitcreatepost';
	
		$arVals = array("uid"=>$userData['uid'],"headline"=>"new post entry","body"=>"","embeddedvideos"=>"","type"=>"newspost","tags"=>"0","visibilitystatus"=>"visible","imageview"=>"largethumbnails","views"=>"0","creationtimestamp"=>"","lastupdatedtimestamp"=>"");
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
		if($key == 'body'){$arVals[$key] = "'".$arVals[$key]."'";}
		else{$arVals[$key] = "'".strtolower($arVals[$key])."'";}
	}//while
	

	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}

	if($errorsVarsArr['errorCode'] == 0)
	{
		$dbobj = new TBDBase(1);
		
		$selectUserQuery = 'SELECT uid, tags, visibilitystatus FROM user WHERE username="'.$username.'" AND email="'.$email.'"; ';
		$dbVars = $dbobj->executeSelectQuery($selectUserQuery);	
		$userID = $dbVars['RESULT'][0]['uid'];
	
		if($editType=='insert')
		{	
			
			$selectQuery = "SELECT count(*) AS postscount FROM posts WHERE uid=".$userID."; ";
			$dbVars = $dbobj->executeSelectQuery($selectQuery);
			$postsCount = ($dbVars['RESULT'][0]['postscount'])+1;
			
			$arVals['headline'] = "'".removeQuotesSingleValue($arVals['headline'])." ".$postsCount."'";
			
			//create the user
			$insertQuery = $dbobj->createInsertQuery("posts", $arVals);
			$insertID = $dbobj->executeInsertQuery($insertQuery);
			$arVals = removeQuotes($arVals);
			//create the website	
			
			unset($dbobj);
			handle_validateNsubmit_createAlbum($errorsVarsArr['errorCode'],$formID,$callType,$insertID);
			
		}//if
		elseif($editType=='delete')
		{		
			$formID = 'blogdelete_'.$postID;
			$deleteQuery = "DELETE FROM posts WHERE uid='".$userID."' AND pid='".$postID."'; ";			
			$dbVars = $dbobj->executeDeleteQuery($deleteQuery);
		
			$arVals = removeQuotes($arVals);
			unset($dbobj);
			handle_validateNsubmit_deletePost($errorsVarsArr['errorCode'],$formID,$callType);
		}
		elseif($editType=='update')
		{		
			if($categoryName != '')
			{
				//Take the category tags  from the form
				$blogTags = explode(',', $categoryName);
				//check the last element
				if(strtolower($blogTags[(count($blogTags)-1)]) == ',' || $blogTags[(count($blogTags)-1)] == '')
					{unset($blogTags[(count($blogTags)-1)]);}
				
				//check for duplicates and delete them
				for($o=0; $o<count($blogTags); $o++)
					{$blogTags2[strtolower(trim($blogTags[$o]))] = 0;}
					
				$blogTags = $blogTags2;
				$blogTags_final = '';
				$blogTagNames_final = '';
	
				reset($blogTags);
				while (list($key, $val) = each ($blogTags))
				{
						$blogTags[$key] = strtolower($key);
					
						$query_temp = "SELECT tid FROM tag WHERE name='".$blogTags[$key]."' AND uid='".$userID."'; ";
						$dbVars_temp = $dbobj->executeSelectQuery($query_temp);
		
						if($dbVars_temp['NUM_ROWS'] == 0)
						{
							$arVals_temp['uid'] = "'".$userID."'";
							$arVals_temp['name'] = "'".$blogTags[$key]."'";
							$arVals_temp['description'] = "'post_tag'";
							$arVals_temp['type'] = "'post'";
							
							$insertQuery_temp = $dbobj->createInsertQuery("tag", $arVals_temp);
							$insertID_temp = $dbobj->executeInsertQuery($insertQuery_temp);
							$tagID_temp = $insertID_temp;
						}//if
						else if($dbVars_temp['NUM_ROWS'] != 0)
						{
							$tagID_temp = $dbVars_temp['RESULT'][0]['tid'];
						}//else if
						
						$blogTags_final .= $tagID_temp."::..::";
						$blogTagNames_final .= $blogTags[$key].", ";
						$arVals['tags'] = "'".$blogTags_final."'";
				}//while
			}else{$arVals['tags'] = "'0'";}
			unset($arVals['category']);
			/*	
				$selectCategory = "SELECT * FROM tag WHERE uid='".$userID."' AND name='".$categoryName."' AND type='post'; ";
				$dbVars2 = $dbobj->executeSelectQuery($selectCategory);	

				if($dbVars2['NUM_ROWS'] == 0)
				{
					$arValsTag = array("uid"=>$userID,"name"=>$categoryName,"description"=>"post_category","type"=>"post");				
					while (list($key, $val) = each ($arValsTag)){$arValsTag[$key] = "'".strtolower($arValsTag[$key])."'";}
					$insertQuery = $dbobj->createInsertQuery("tag", $arValsTag);
					
					$insertID = $dbobj->executeInsertQuery($insertQuery);
					$arVals['tags'] = "'".$insertID."'";
				}//if
				else
				{
					$arVals['tags'] = "'".$dbVars2['RESULT'][0]['tid']."'";
				}//else
			}else{$arVals['tags'] = "'0'";}
			
			unset($arVals['category']);
			*/
			
			$arVals['embeddedvideos'] = $embeddedVideos;
			//$arVals['embeddedvideos'] = real_strip_tags($arVals['embeddedvideos'], array('object', 'embed', 'param'), TRUE);
			//$arVals['embeddedvideos'] = (get_magic_quotes_gpc()) ? $arVals['embeddedvideos'] : addslashes($arVals['embeddedvideos']);
			//$arVals['embeddedvideos'] = "'".$arVals['embeddedvideos']."'";
	
			
			$query = "UPDATE posts SET "
				."headline=".$arVals['headline'].", "
				."body=".$arVals['body'].", "
				."embeddedvideos=".$arVals['embeddedvideos'].", "
				."tags=".$arVals['tags'].", "
				."lastupdatedtimestamp=".$arVals['lastupdatedtimestamp']." "
				."WHERE uid='".$userID."' AND pid=".$arVals['pid']."; ";	
			$updateResult = $dbobj->executeUpdateQuery($query);

			$arVals = removeQuotes($arVals);
			$formID = 'blog_'.$arVals['pid'];
			unset($dbobj);
			handle_validateNsubmit_updateBlog($errorsVarsArr['errorCode'],$formID,$callType);
		}//elseif
	}//
	
}//blog_edit($editType,$callType)


function blog_edit_newAlbumImages($userID)
{	
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(1);
	$validator = new Validate();
	$dbVars = array();
	
	$nowDate = date("Y-m-d");
	$nowTimestamp = date("Y-m-d")." ".date("H:i:s");
	
	$explodeArr = array();
	$errorsVarsArr = array();
	$errorCode = NULL;

	$arVals = array("uid"=>$userID,"headline"=>"Images Update","body"=>"These are the images I uploaded within 24 hours!","type"=>"imagesupdate","tags"=>"0","visibilitystatus"=>"visible","imageview"=>"fullsize","views"=>"0","creationtimestamp"=>"","lastupdatedtimestamp"=>"");
	$arValsRequired = array();
	$arValsMaxSize = array();
	$arValsValidations = array();
		
	$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	$arVals['creationtimestamp'] = date("Y-m-d") . " " . date("H:i:s");

	reset($arVals);
	while (list($key, $val) = each ($arVals))
	{
		$arVals[$key] = "'".strtolower($arVals[$key])."'";
	}//while
		
	//$query_currentPosts = "SELECT pid FROM posts WHERE uid='".$userID."' AND type = 'imagesupdate' AND DATE_SUB(NOW(),INTERVAL 1 DAY); "; //SELECT ALL THE POST THAT WERE POSTED WITHIN 24 HOURS
	$query_currentPosts = "SELECT pid,creationtimestamp FROM posts WHERE uid='".$userID."' AND type = 'imagesupdate' ORDER BY creationtimestamp DESC; ";
	$dbVars_currentPosts = $dbobj->executeSelectQuery($query_currentPosts);	
	
	if($dbVars_currentPosts['NUM_ROWS'] == 0){$createNewAlbumImagesBlogEntry = true;}//if
	else
	{
		//FIND THE IMAGES-UPDATE BLOG POST AND RETURN THE postID
		$postID = $dbVars_currentPosts['RESULT'][0]['pid'];
		$postCreated = $dbVars_currentPosts['RESULT'][0]['creationtimestamp'];
		
		$explodeDateArr = explode(' ',$postCreated);
		if($explodeDateArr[0]!=$nowDate){$createNewAlbumImagesBlogEntry = true;}
		else{$createNewAlbumImagesBlogEntry = false;}
	}//
	
	if($createNewAlbumImagesBlogEntry == true)
	{
		//CREATE NEW IMAGES-UPDATE BLOG POST AND RETURN THE postID
		$insertQuery = $dbobj->createInsertQuery("posts", $arVals);
		$newPostID = $dbobj->executeInsertQuery($insertQuery);
		$arVals = removeQuotes($arVals);

		unset($dbobj);
		return $newPostID;
	}//if
	else
	{
		unset($dbobj);
		return $postID;
	}//else
}//blog_edit_newAlbumImages($userID)

function blog_edit_toggleValues($editType,$callType)
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
		case 'blogvisibility':
			
			$postID = $_POST['pid'];
			$visibilityStatus = $_POST['visibilitystatus'];
			
			if($visibilityStatus == 'blogvisible'){$visibilityStatus = 'invisible';}
			else{$visibilityStatus = 'visible';}
			
			$validatorFormID = 'blogvisibility'; $validatorCSRFaction = 'submitpostvisibilitystatus';
			
			$arValsRequired = array("pid"=>"","visibilitystatus"=>"");
			$arVals = array("pid"=>"","visibilitystatus"=>"","lastupdatedtimestamp"=>"");
			
			break;
		case 'blogimageview':
			$postID = $_POST['pid'];
			$imageview = $_POST['imageview'];
			$validatorFormID = 'blogimageview'; $validatorCSRFaction = 'submitpostimageview';

			$arValsRequired = array("pid"=>"","imageview"=>"");
			$arVals = array("pid"=>"","imageview"=>"","lastupdatedtimestamp"=>"");
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
		
		$selectTagsQuery = 'SELECT uid, tags, visibilitystatus FROM user WHERE username="'.$username.'" AND email="'.$email.'"; ';
		$dbVars = $dbobj->executeSelectQuery($selectTagsQuery);	
		$userID = $dbVars['RESULT'][0]['uid'];
		switch($formID)
		{
			case 'blogvisibility':
				$formID = 'blogvisibility_'.$postID.'_'.$visibilityStatus;
				$updatePostQuery = "UPDATE posts"
						. " SET visibilitystatus="."'".$visibilityStatus."', "
						. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
						. " WHERE pid='".$postID."' AND uid='".$userID."'; ";
				$updateResult = $dbobj->executeUpdateQuery($updatePostQuery);
				break;
			case 'blogimageview':
				$formID = 'blog_imageview_'.$postID.'_'.$imageview;
				$updatePostQuery = "UPDATE posts"
						. " SET imageview="."".$arVals['imageview'].", "
						. " lastupdatedtimestamp = ".$arVals['lastupdatedtimestamp']
						. " WHERE pid='".$postID."' AND uid='".$userID."'; ";
				$updateResult = $dbobj->executeUpdateQuery($updatePostQuery);
				break;
			default:
				break;			
		}//switch
		
		unset($dbobj);
		$arVals = removeQuotes($arVals);
		//user_session_reset($username,$email);
		handle_validateNsubmit_updateBlogVisibility($errorsVarsArr['errorCode'],$formID,$callType);
	}//

}//blog_edit_toggleValues($editType,$callType)
?>