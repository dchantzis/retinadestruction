<?php

function images_album_edit($imageTypeOption)
{
	if (isset($_POST["PHPSESSID"])) { session_id($_POST["PHPSESSID"]);}
	session_start(); ini_set("html_errors", "0");
	require("rdconfig.inc.php");	
	
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(1);
	$validator = new Validate();
	$dbVars = array();
	
	$file = array();
	$arVals = array();
	$uploadImagesResults = array();
	$errorCode = NULL;
	$redirectPage = NULL;
	$uploadedImageIDs = '';
	$postID = 0;

	$username = $_GET['username'];
	$email = $_GET['email'];
	$albumID = $_GET['albumid'];
	$postID = $_GET['postid'];

	$newImagesOrder = 0;
	$redirectPage = 0;


	reset($_FILES);
	$tempcounter=0;
	while (list($key, $val) = each ($_FILES))
	{	
		$errorCode = $validator->uploadFilesErrorMessages($_FILES[$key]['error']);
		if($tempcounter==5 || $errorCode==194){unset($_FILES[$key]);}
		$tempcounter++;
	}//while
	
	//if this flag turns to '1' then there's at least 1 image to be uploaded. Else Abort.
	reset($_FILES);
	$temp_flag = 0;
	while (list($key, $val) = each ($_FILES))
	{
		$uploadImagesResults[$key]['name'] =  htmlentities($_FILES[$key]['name'], ENT_QUOTES, "UTF-8");
		$uploadImagesResults[$key]['size'] = $_FILES[$key]['size'];
		
		$errorCode = $validator->uploadFilesErrorMessages($_FILES[$key]['error']);
		if($errorCode == 0){$temp_flag = 1;}//all ok
		else{ $uploadImagesResults[$key]['error'] = $errorCode; unset($_FILES[$key]);}//error
	}//while
	
	$selectUserIDQuery = "SELECT user.uid, website.uploadimageswidth, website.uploadimagestype FROM user,website WHERE user.username='".$username."' AND user.email='".$email."' AND user.uid = website.uid; ";
	$dbVars_selectUserID = $dbobj->executeSelectQuery($selectUserIDQuery);
	$userID = $dbVars_selectUserID['RESULT'][0]['uid'];
		
	if($imageTypeOption == 'regular' || $imageTypeOption == 'regular_blogpost' || $imageTypeOption == 'regular_coverpage')
	{
		$userUploadImagesWidth = $dbVars_selectUserID['RESULT'][0]['uploadimageswidth'];
		$userUploadImagesType = $dbVars_selectUserID['RESULT'][0]['uploadimagestype'];
	}
	
	//find which image files formats are supported
	$selectSupportedFileFormatsQuery = "SELECT fid, extension, mimetype FROM fileformat WHERE type='image'; ";
	$dbVars_selectFileFormat = $dbobj->executeSelectQuery($selectSupportedFileFormatsQuery);


	if($dbVars_selectFileFormat['NUM_ROWS'] != 0)
	{
		for($i=0; $i<$dbVars_selectFileFormat['NUM_ROWS']; $i++)
		{
			$accepted_file_formats[$i]['id'] = $dbVars_selectFileFormat['RESULT'][$i]['fid'];
			$accepted_file_formats[$i]['extension'] = $dbVars_selectFileFormat['RESULT'][$i]['extension'];
			$accepted_file_formats[$i]['mimetype'] = $dbVars_selectFileFormat['RESULT'][$i]['mimetype'];
		}//
	}//if
	else{} //No file types in the database//else

	reset($_FILES);
	while (list($key, $val) = each ($_FILES))
	{
		$temp = 0;
		if($_FILES[$key]['error']!=0)
		{
			$uploadImagesResults[$key]['error'] = $_FILES[$key]['error'];
			unset($_FILES[$key]);
			continue;
		}//

		for($j=0; $j<count($accepted_file_formats); $j++)
		{
			if(FILES_FF_CHECK == 'extension')
			{
				if($accepted_file_formats[$j]['extension'] == findFileExtension($_FILES[$key]['name']))
				{
					$temp = 1;//image file type is accepted
					$arVals[$key]['fid'] = $accepted_file_formats[$j]["id"];
				}//
			}//if
			elseif(FILES_FF_CHECK == 'mimetype')
			{
				if($accepted_file_formats[$j]['mimetype'] == $_FILES[$key]['type'])
				{
					$temp = 1;//image file type is accepted
					$arVals[$key]['fid'] = $accepted_file_formats[$j]['id'];
				}//	
			}//else
		}//for
		if($temp == 0){ $uploadImagesResults[$key]['error'] = 105; unset($_FILES[$key]);}
	}//while

	//overide the results of the previous while if the global variable PRESERVE_ORIGINAL_IMAGE_FILETYPE is set to 'false'
	if(strtolower(PRESERVE_ORIGINAL_IMAGE_FILETYPE) == 'false')
	{
		reset($arVals);
		while(list($key,$val) = each($arVals))
		{
			for($j=0; $j<count($accepted_file_formats); $j++)
			{if(UPLOADED_IMAGES_FILETYPE == $accepted_file_formats[$j]['extension']){$arVals[$key]['fid'] = $accepted_file_formats[$j]['id'];}}
		}//
	}//


	reset($_FILES);
	while (list($key, $val) = each ($_FILES))
	{
		if(FILES_UPLOAD_TYPE == 'database')
		{	
			//not implemented yet	
		}//if database
		if(FILES_UPLOAD_TYPE == 'fileserver')
		{	
		
			switch($imageTypeOption)
			{
				case 'regular':
					//create the 'portfolioimages directory'
					createDirectory(TBUSERS_DIR.$username.'/','portfolioimages');
					
					$uploadDirectory = TBUSERS_DIR.$username.'/portfolioimages/';
					$partialFileName = '';//removeExtension($_FILES[$key]['name'])."_";
					$fieldName = $key; //name of they type=file in the form
					
					$defineValsArr['imagetype'] = 'regular';
					$defineValsArr['fullRezWidth'] = $userUploadImagesWidth;
					$defineValsArr['videoPlayerWidth'] = $userUploadImagesWidth;
					$defineValsArr['resizeOption'] = $userUploadImagesType;
					
					unset($dbobj);
					$postID = blog_edit_newAlbumImages($userID);
					$dbobj = new TBDBase(1);
					break;
				case 'regular_blogpost':
					//create the 'portfolioimages directory'
					createDirectory(TBUSERS_DIR.$username.'/','portfolioimages');
					
					$uploadDirectory = TBUSERS_DIR.$username.'/portfolioimages/';
					$partialFileName = '';//removeExtension($_FILES[$key]['name'])."_";
					$fieldName = $key; //name of they type=file in the form
					
					//SEARCH IF THERE IS AN ALBUM FOR BLOG IMAGES FOR THIS USER
					//IF NOT, CREATE IT
					$queryA = "SELECT aid FROM album WHERE uid='".$userID."' AND type='blogpostsimagesalbum'; ";
					$dbVars_A = $dbobj->executeSelectQuery($queryA);
					if($dbVars_A['NUM_ROWS'] == 0)
					{
						$albumArr['uid'] = "'".$userID."'";
						$albumArr['name'] = "'Blog images'";
						$albumArr['description'] = "'This album contains every image that you upload in your blog posts. By default it\'s invisible to your website visitors.'";
						$albumArr['tid']= "'0'";
						$albumArr['visibilitystatus'] = "'invisible'";
						$albumArr['type'] = "'blogpostsimagesalbum'";
						$albumArr['creationtimestamp'] = "'".date("Y-m-d")." ".date("H:i:s")."'";
						$albumArr['lastupdatedtimestamp'] = "'".date("Y-m-d")." ".date("H:i:s")."'";
						
						$insertQueryA = $dbobj->createInsertQuery("album", $albumArr);
						$albumID = $dbobj->executeInsertQuery($insertQueryA);	
					}//if
					else
						{$albumID = $dbVars_A['RESULT'][0]['aid'];}
					
					$imageTypeOption = 'regular';
					$defineValsArr['imagetype'] = 'regular';
					$defineValsArr['fullRezWidth'] = $userUploadImagesWidth;
					$defineValsArr['videoPlayerWidth'] = $userUploadImagesWidth;
					$defineValsArr['resizeOption'] = $userUploadImagesType;
					$blogPostImages = true;				
					break;
				case 'regular_coverpage':
					//create the 'portfolioimages directory'
					createDirectory(TBUSERS_DIR.$username.'/','portfolioimages');
					
					$uploadDirectory = TBUSERS_DIR.$username.'/portfolioimages/';
					$partialFileName = '';//removeExtension($_FILES[$key]['name'])."_";
					$fieldName = $key; //name of they type=file in the form
					
					//SEARCH IF THERE IS AN ALBUM FOR BLOG IMAGES FOR THIS USER
					//IF NOT, CREATE IT
					$queryA = "SELECT aid FROM album WHERE uid='".$userID."' AND type='coverpageimagesalbum'; ";
					$dbVars_A = $dbobj->executeSelectQuery($queryA);
					if($dbVars_A['NUM_ROWS'] == 0)
					{
						$albumArr['uid'] = "'".$userID."'";
						$albumArr['name'] = "'Cover page images'";
						$albumArr['description'] = "'This album contains every image that you upload for cover pages. By default it\'s invisible to your website visitors.'";
						$albumArr['tid']= "'0'";
						$albumArr['visibilitystatus'] = "'invisible'";
						$albumArr['type'] = "'coverpageimagesalbum'";
						$albumArr['creationtimestamp'] = "'".date("Y-m-d")." ".date("H:i:s")."'";
						$albumArr['lastupdatedtimestamp'] = "'".date("Y-m-d")." ".date("H:i:s")."'";
						
						$insertQueryA = $dbobj->createInsertQuery("album", $albumArr);
						$albumID = $dbobj->executeInsertQuery($insertQueryA);	
					}//if
					else
						{$albumID = $dbVars_A['RESULT'][0]['aid'];}
					
					$imageTypeOption = 'regular';
					$defineValsArr['imagetype'] = 'regular';
					$defineValsArr['fullRezWidth'] = $userUploadImagesWidth;
					$defineValsArr['videoPlayerWidth'] = $userUploadImagesWidth;
					$defineValsArr['resizeOption'] = $userUploadImagesType;
					$blogPostImages = true;
					break;					
				case 'albumcover':
					//create the 'portfolioimages directory'
					createDirectory(TBUSERS_DIR.$username.'/','coverimages');
					
					$uploadDirectory = TBUSERS_DIR.$username.'/coverimages/';
					$partialFileName = '';//removeExtension($_FILES[$key]['name'])."_";
					$fieldName = $key; //name of they type=file in the form
					
					
					$defineValsArr['imagetype'] = $imageTypeOption;
					$defineValsArr['imagewidth'] = IMAGES_COVERS_PIXELS_WIDTH;
					$defineValsArr['imageheight'] = IMAGES_COVERS_PIXELS_HEIGHT;
					break;
				case 'usercover':
					//create the 'portfolioimages directory'
					createDirectory(TBUSERS_DIR.$username.'/','coverimages');
					
					$uploadDirectory = TBUSERS_DIR.$username.'/coverimages/';
					$partialFileName = '';//removeExtension($_FILES[$key]['name'])."_";
					$fieldName = $key; //name of they type=file in the form
					
					$defineValsArr['imagetype'] = $imageTypeOption;
					$defineValsArr['imagewidth'] = IMAGES_AVATARS_PIXELS_WIDTH;
					$defineValsArr['imageheight'] = IMAGES_AVATARS_PIXELS_HEIGHT;
					break;
				case 'wallpaper':
					//create the 'portfolioimages directory'
					createDirectory(TBUSERS_DIR.$username.'/','designimages');
					
					$uploadDirectory = TBUSERS_DIR.$username.'/designimages/';
					$partialFileName = '';//removeExtension($_FILES[$key]['name'])."_";
					$fieldName = $key; //name of they type=file in the form
					
					$defineValsArr['imagetype'] = $imageTypeOption;
					$defineValsArr['imagewidth'] = IMAGES_WALLPAPER_PIXELS_WIDTH;
					$defineValsArr['imageheight'] = IMAGES_WALLPAPER_PIXELS_HEIGHT;
					break;
				case 'logo':
					//create the 'portfolioimages directory'
					createDirectory(TBUSERS_DIR.$username.'/','designimages');
					
					$uploadDirectory = TBUSERS_DIR.$username.'/designimages/';
					$partialFileName = '';//removeExtension($_FILES[$key]['name'])."_";
					$fieldName = $key; //name of they type=file in the form
					
					$defineValsArr['imagetype'] = $imageTypeOption;
					$defineValsArr['imagewidth'] = IMAGES_LOGO_PIXELS_WIDTH;
					$defineValsArr['imageheight'] = IMAGES_LOGO_PIXELS_HEIGHT;
					break;
			}//switch
			
			//Upload the file. Function returns array
			//THE FOLLOWING VALUES ARE TAKEN FROM THE USER DATABASE TABLE,
			//$userUploadImgesWidth, $userUploadImgesWidth, $userUploadImagesType
			//IF THESE VALUES ARE NOT SAVED IN THAT DATABASE, LOAD THESE INSTAID (in that order)
			//IMAGES_FULL_RESOLUTION_PIXELS, VIDEO_PLAYER_PIXELS_WIDTH, FULL_RESOLUTION_IMAGES_RESIZE_OPTION

			$file = uploadToFileserver($fieldName,$uploadDirectory,$partialFileName,'image',$defineValsArr);
		
			if($imageTypeOption != 'wallpaper' && $imageTypeOption != 'logo' )
			{
				if(isset($file['error'])){ $uploadImagesResults[$key]['error'] = $file['error']; continue; }
				$arVals[$key]['caption'] = "''";
				$arVals[$key]['uid'] = "'".$userID."'";
				$arVals[$key]['aid'] = "'".$albumID."'";
				$arVals[$key]['pid'] = "'".$postID."'";
				$arVals[$key]['orientation'] = "'".$file['imageorientation']."'";
				$arVals[$key]['submitiontimestamp'] = "'" . date("Y-m-d") . " " . date("H:i:s") . "'";
				$arVals[$key]['lastupdatedtimestamp'] = "'" . date("Y-m-d") . " " . date("H:i:s") . "'";
				$arVals[$key]['fid'] = "'" . $arVals[$key]['fid'] . "'";
				$arVals[$key]['filename'] = "'" . $file['filename'] . "'";
				$arVals[$key]['filesize'] = "'" . $file['filesize'] . "'";
				$arVals[$key]['imagetype'] = "'".$defineValsArr['imagetype']."'";
				//$arVals[$key]['filecontent'] = "'NULL'";
				$arVals[$key]['fileurl'] = "'" . $file['fileurl'] . "'";
				$arVals[$key]['uploadtype'] = "'" . 'fileserver' . "'";
			}//if
			
		}// fileserver
		if($imageTypeOption != 'wallpaper' && $imageTypeOption != 'logo' )
		{
			$insertQuery = $dbobj->createInsertQuery("image", $arVals[$key]);
			$insertID = $dbobj->executeInsertQuery($insertQuery);	
			$uploadedImageIDs .= $insertID.':..::..:';
		}
	}//while
	
	
	switch($imageTypeOption){
		case 'regular':			
			if($uploadedImageIDs!='')
			{
				//UPDATE THE IMAGES ORDER OF THE USERS PORTFOLIO
				$selectImagesOrderQuery= "SELECT imagesorder FROM album WHERE uid='".$userID."' AND aid='".$albumID."'; ";
				$dbVars_selectImagesOrder = $dbobj->executeSelectQuery($selectImagesOrderQuery);
				
				$newImagesOrder = $uploadedImageIDs;
				if($dbVars_selectImagesOrder['NUM_ROWS'] != 0)
				{
					$oldImagesOrder = $dbVars_selectImagesOrder['RESULT'][0]['imagesorder'];
					if(($oldImagesOrder=='0')||($oldImagesOrder==''))
						{$newImagesOrder = $uploadedImageIDs;}
					else
						{$newImagesOrder = $oldImagesOrder.$uploadedImageIDs;}
				}
				
				$updateImagesOrderQuery = "UPDATE album"
					." SET lastupdatedtimestamp='".date("Y-m-d") . " " . date("H:i:s")."', imagesorder='".$newImagesOrder."'"
					." WHERE uid='".$userID."' AND aid='".$albumID."'; ";		
				$updateResult = $dbobj->executeUpdateQuery($updateImagesOrderQuery);
				
				//GET THE IMAGES ORDER FROM THE BLOG POST WITH ID $postID
			
				//UPDATE THE IMAGES ORDER OF THE USERS PORTFOLIO
				$selectImagesOrderQuery= "SELECT images FROM posts WHERE uid='".$userID."' AND pid='".$postID."'; ";
				$dbVars_selectImagesOrder = $dbobj->executeSelectQuery($selectImagesOrderQuery);
				
				$newImagesOrder = $uploadedImageIDs;
				if($dbVars_selectImagesOrder['NUM_ROWS'] != 0)
				{
					$oldImagesOrder = $dbVars_selectImagesOrder['RESULT'][0]['images'];
					if(($oldImagesOrder=='0')||($oldImagesOrder==''))
						{$newImagesOrder = $uploadedImageIDs;}
					else
						{$newImagesOrder = $oldImagesOrder.$uploadedImageIDs;}
				}//		
				$updateImagesOrderQuery = "UPDATE posts"
					." SET lastupdatedtimestamp='".date("Y-m-d")." ".date("H:i:s")."', images='".$newImagesOrder."'"
					." WHERE uid='".$userID."' AND pid='".$postID."'; ";		
				$updateResult = $dbobj->executeUpdateQuery($updateImagesOrderQuery);
			}//if
		break;
		case 'albumcover':
			if($uploadedImageIDs!='')
			{		
				$updateAlbum = "UPDATE album"
						." SET lastupdatedtimestamp='".date("Y-m-d") . " " . date("H:i:s")."', coverid='".$insertID."'"
						." WHERE uid='".$userID."' AND aid='".$albumID."'; ";
				$updateResult = $dbobj->executeUpdateQuery($updateAlbum);
			}//if
			break;
		case 'usercover':
			if($uploadedImageIDs!='')
			{			
				$updateUser = "UPDATE user"
						." SET lastupdatedtimestamp='".date("Y-m-d") . " " . date("H:i:s")."', avatar='".$insertID."'"
						." WHERE uid='".$userID."'; ";
				$updateResult = $dbobj->executeUpdateQuery($updateUser);
			}//if
			break;
		case 'wallpaper':
			$updateUser = "UPDATE website"
					." SET lastupdatedtimestamp='".date("Y-m-d") . " " . date("H:i:s")."', wallpaper='wallpaper.png'"
					." WHERE uid='".$userID."'; ";
			$updateResult = $dbobj->executeUpdateQuery($updateUser);
			break;
		case 'logo':
		
			$updateUser = "UPDATE website"
					." SET lastupdatedtimestamp='".date("Y-m-d") . " " . date("H:i:s")."', ='logo.png'"
					." WHERE uid='".$userID."'; ";
			$updateResult = $dbobj->executeUpdateQuery($updateUser);

			break;
		default:
			break;
	}//

	unset($dbobj);
	//$_SESSION['uploadImagesResults'] = $uploadImagesResults;

	//redirects(0,'');

	echo 'FILEID:'.$insertID;

}//images_album_edit()

function images_album_editinfo($editType,$callType)
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
	if(isset($_POST['aid'])){$albumID = $_POST['aid'];}

	if($formID=='imagecaption'){
		$validatorFormID = 'imagecaption'; $validatorCSRFaction = 'submiteditimagecaption';

		$arVals = array("aid"=>"","username"=>"","caption"=>"","lastupdatedtimestamp"=>"");
		$arValsRequired = array();
		$arValsMaxSize = array("caption"=>100);
		$arValsValidations = array();
	}//if
	else if($formID == 'imagetags'){
		$validatorFormID = 'imagetags'; $validatorCSRFaction = 'submiteditimagetags';

		$arVals = array("aid"=>"","username"=>"","tags"=>"","lastupdatedtimestamp"=>"");
		$arValsRequired = array();
		$arValsMaxSize = array("tags"=>160);
		$arValsValidations = array();
	}//elseif
		
	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok

	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok
	
	if($formID=='imagecaption')
	{	
		reset ($_POST);
		$arVals = $validator->customEncodeInputValues($_POST,$arVals);
		unset($_POST);
		
		$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	}//outer if
	else if($formID == 'imagetags')
	{
		reset ($_POST);
		$arVals = $validator->customEncodeInputValues($_POST,$arVals);
		unset($_POST);
		
		$arVals['lastupdatedtimestamp'] = date("Y-m-d") . " " . date("H:i:s");
	}//elseif
	

	reset($arVals);
	while (list($key, $val) = each ($arVals))
	{
		if($key == 'caption'){$arVals[$key] = "'".$arVals[$key]."'";}
		else{$arVals[$key] = "'".strtolower($arVals[$key])."'";}
	}//while
	
	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}


	if($errorsVarsArr['errorCode'] == 0)
	{
		$dbobj = new TBDBase(1);
		
		if($formID=='imagecaption')
		{	
		
			$query = "UPDATE image SET "
				."caption=".$arVals['caption'].", "
				."lastupdatedtimestamp=".$arVals['lastupdatedtimestamp']." "
				."WHERE iid=".$arVals['iid']." AND aid=".$arVals['aid']."; ";	
			$updateResult = $dbobj->executeUpdateQuery($query);

			
			$arVals = removeQuotes($arVals);
			$formID = 'imagecaption_'.$arVals['iid'];
			$fieldID = $arVals['imagecaption_caption'];
			$fieldValue = $arVals['caption'];
			unset($dbobj);
			handle_validateNsubmit_imageCaption($errorsVarsArr['errorCode'],$formID,$fieldID,$fieldValue,$callType);
			
		}//if
		else if($formID == 'imagetags')
		{		
			$imageTags = explode(',', removeQuotesSingleValue($arVals['tags']));
			//check the last element
			if(strtolower($imageTags[(count($imageTags)-1)]) == ',' || $imageTags[(count($imageTags)-1)] == '')
				{unset($imageTags[(count($imageTags)-1)]);}
			
			//check for duplicates and delete them
			for($o=0; $o<count($imageTags); $o++)
				{$imageTags2[strtolower(trim($imageTags[$o]))] = 0;}
				
			$imageTags = $imageTags2;
			$imageTags_final = '';
			$imageTagNames_final = '';

			reset($imageTags);
			while (list($key, $val) = each ($imageTags))
			{
					$imageTags[$key] = strtolower($key);
				
					$query_temp = "SELECT tid FROM tag WHERE name='".$imageTags[$key]."' AND uid='".$userData['uid']."'; ";
					$dbVars_temp = $dbobj->executeSelectQuery($query_temp);
	
					if($dbVars_temp['NUM_ROWS'] == 0)
					{
						$arVals_temp['uid'] = "'".$userData['uid']."'";
						$arVals_temp['name'] = "'".$imageTags[$key]."'";
						$arVals_temp['description'] = "'image_tag'";
						$arVals_temp['type'] = "'image'";
						
						$insertQuery_temp = $dbobj->createInsertQuery("tag", $arVals_temp);
						$insertID_temp = $dbobj->executeInsertQuery($insertQuery_temp);
						$tagID_temp = $insertID_temp;
					}//if
					else if($dbVars_temp['NUM_ROWS'] != 0)
					{
						$tagID_temp = $dbVars_temp['RESULT'][0]['tid'];
					}//else if
					
					$imageTags_final .= $tagID_temp."::..::";
					$imageTagNames_final .= $imageTags[$key].", ";
			}//while
			$query = "UPDATE image SET "
				."tags='".$imageTags_final."', "
				."lastupdatedtimestamp=".$arVals['lastupdatedtimestamp']." "
				."WHERE iid=".$arVals['iid']." AND uid='".$userData['uid']."'; ";	
			$updateResult = $dbobj->executeUpdateQuery($query);
			
			unset($dbobj);
			$arVals = removeQuotes($arVals);
			$formID = 'imagetags_'.$arVals['iid'];
			$fieldID = 'imagetags_tags';
			$fieldValue = $imageTagNames_final;
			handle_validateNsubmit_imageTags($errorsVarsArr['errorCode'],$formID,$fieldID,$fieldValue,$callType);
		}//else if
	}//
		
}//images_album_editinfo($editType,$callType)


function images_album_delete($callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(1);
	$validator = new Validate();
	$dbVars = array();
	
	$explodeArr = array();
	$errorsVarsArr = array();
	$imagesToDeleteArr = array();
	$imagesArrVars = array();
	$portfolioImagesArrOrderedOLD = array();
	$portfolioImagesOrderNEW = '';
	$errorCode = NULL;
		
	if(isset($_POST['formid'])){$formID = $_POST['formid'];}
	if(isset($_POST['fieldid'])){$fieldID = $_POST['fieldid']; unset($_POST['fieldid']); }
	$email = $_POST['email']; unset($_POST['email']);
	$username = $_POST['username']; unset($_POST['username']);

	$selectUserIDQuery = "SELECT uid FROM user WHERE username='".$username."' AND email='".$email."'; ";
	$dbVarsUserID = $dbobj->executeSelectQuery($selectUserIDQuery);
	$userID = $dbVarsUserID['RESULT'][0]['uid'];
	
	if($formID == 'albumimagedelete')
	{

		$albumID = $_POST['aid']; unset($_POST['aid']);
		$imagesToDeleteOrder = $_POST['albumimagesdeleteorder']; unset($_POST['albumimagesdeleteorder']);
		
		$validatorFormID = 'albumimagedelete';
		$validatorCSRFaction = 'submitalbumimagesdeletelist';
		$fieldValue = $imagesToDeleteOrder;
		$fieldID = 'albumimages_deleteorder';
		$arValsRequired = array();
	}//
	else if($formID == 'blogimagedelete')
	{
		$postID = $_POST['pid']; unset($_POST['pid']);
		
		$queryA = "SELECT aid FROM album WHERE uid='".$userID."' AND type='blogpostsimagesalbum'; ";
		$dbVars_A = $dbobj->executeSelectQuery($queryA);
		$albumID = $dbVars_A['RESULT'][0]['aid'];

		$imagesToDeleteOrder = $_POST['blogimagesdeleteorder']; unset($_POST['blogimagesdeleteorder']);
		
		$validatorFormID = 'blogimagedelete';
		$validatorCSRFaction = 'submitpostimagesdeletelist';
		$fieldValue = $imagesToDeleteOrder;
		$fieldID = 'blogimages_deleteorder';
		$arValsRequired = array();
	}//
	else if($formID == 'coverpageimagedelete')
	{
		$postID = $_POST['pid']; unset($_POST['pid']);
		
		$queryA = "SELECT aid FROM album WHERE uid='".$userID."' AND type='coverpageimagesalbum'; ";
		$dbVars_A = $dbobj->executeSelectQuery($queryA);
		$albumID = $dbVars_A['RESULT'][0]['aid'];

		$imagesToDeleteOrder = $_POST['coverpageimagesdeleteorder']; unset($_POST['coverpageimagesdeleteorder']);
		
		$validatorFormID = 'coverpageimagedelete';
		$validatorCSRFaction = 'submitcoverpageimagesdeletelist';
		$fieldValue = $imagesToDeleteOrder;
		$fieldID = 'coverpageimages_deleteorder';
		$arValsRequired = array();
	}//
	
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
	
		$selectImagesOrderQuery = "SELECT imagesorder FROM album WHERE aid='".$albumID."' AND uid='".$userID."'; ";
		$dbVarsImagesOrder = $dbobj->executeSelectQuery($selectImagesOrderQuery);
		if($dbVarsImagesOrder['NUM_ROWS']!=0)
		{
			$oldImagesOrder = $dbVarsImagesOrder['RESULT'][0]['imagesorder'];
			$oldImagesOrder = explode(':..::..:', $oldImagesOrder);
			unset($oldImagesOrder[(count($oldImagesOrder)-1)]);
		}//if
		for($k=0; $k<count($oldImagesOrder); $k++)
		{
			$portfolioImagesArrOrderedOLD[$oldImagesOrder[$k]] = $oldImagesOrder[$k];
		}//for
		reset($portfolioImagesArrOrderedOLD);

		$imagesToDeleteOrder = explode(':..::..:', $imagesToDeleteOrder);
		unset($imagesToDeleteOrder[(count($imagesToDeleteOrder)-1)]);
			
		//GET ALL THE IMAGES OF THIS ALBUM
		$selectImageQuery = "SELECT iid, fileurl FROM image WHERE uid='".$userID."' AND aid='".$albumID."'; ";
		$dbVarsImages = $dbobj->executeSelectQuery($selectImageQuery);
		if($dbVarsImages['NUM_ROWS'] != 0)
		{
			for($k=0; $k<$dbVarsImages['NUM_ROWS']; $k++)
			{
				$imageID = $dbVarsImages['RESULT'][$k]['iid'];
				$imagesArrVars[$imageID]['fileurl'] = $dbVarsImages['RESULT'][$k]['fileurl'];
			}//for
		}//if

		reset($imagesToDeleteOrder);
		for($j=0; $j<count($imagesToDeleteOrder); $j++)
		{
			//GET THE FILEURL OF THE IMAGES THAT I WANT TO DELETE
			$imagesToDeleteArr[$imagesToDeleteOrder[$j]] = $imagesArrVars[$imagesToDeleteOrder[$j]];
			unset($portfolioImagesArrOrderedOLD[$imagesToDeleteOrder[$j]]);
		}//for
		

		reset($imagesToDeleteArr);
		while (list($key, $val) = each ($imagesToDeleteArr))
		{
			$deleteImageQuery = "DELETE FROM image WHERE iid='".$key."' AND uid='".$userID."' AND aid='".$albumID."'; ";
			$deleteImageResult = $dbobj->executeDeleteQuery($deleteImageQuery);
			
			$deleteResult = deleteFromFileserver(IMAGES_FOLDER_FULL_RESOLUTION.'/'.$imagesToDeleteArr[$key]['fileurl'],'image',$username);
			$deleteResult = deleteFromFileserver(IMAGES_FOLDER_LARGE_THUMBNAILS.'/'.$imagesToDeleteArr[$key]['fileurl'],'image',$username);
			$deleteResult = deleteFromFileserver(IMAGES_FOLDER_THUMBNAILS.'/'.$imagesToDeleteArr[$key]['fileurl'],'image',$username);
		}//while
		
		//CREATE THE NEW PORTFOLIO IMAGES ORDER AND UPDATE THE DATABASE
		reset($portfolioImagesArrOrderedOLD);
		while (list($key, $val) = each ($portfolioImagesArrOrderedOLD))
		{
			$portfolioImagesOrderNEW = $portfolioImagesOrderNEW.$key.':..::..:';
		}//while

		$updateImagesOrderQuery = "UPDATE album"
					." SET lastupdatedtimestamp='".date("Y-m-d") . " " . date("H:i:s")."', imagesorder='".$portfolioImagesOrderNEW."'"
					." WHERE aid='".$albumID."' AND uid='".$userID."'; ";
		$updateImagesOrderResult = @mysql_query($updateImagesOrderQuery);
		unset($dbobj);
		handle_validateNsubmit_albumimagesdelete($errorsVarsArr['errorCode'], $formID, $fieldID, $fieldValue, 'ajax');
	}//if
	
}//images_album_delete()


function image_update_views_ajax($editType,$callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(1);
	$validator = new Validate();
	$dbVars = array();

	$imageID = $_POST['iid'];
	$currentViews = $_POST['views'];
	
	if (trim($imageID) == "") { $imageID = "0";}
	$imageID = (get_magic_quotes_gpc()) ? $imageID : addslashes($imageID);
	$imageID = htmlentities($imageID, ENT_QUOTES, "UTF-8");
	$imageID = trim($imageID);
	$imageID = $imageID;
	
	if (trim($currentViews) == "") { $currentViews = "0";}
	$currentViews = (get_magic_quotes_gpc()) ? $currentViews : addslashes($currentViews);
	$currentViews = htmlentities($currentViews, ENT_QUOTES, "UTF-8");
	$currentViews = trim($currentViews);
	$currentViews = $currentViews;
	
	image_update_views($currentViews, $imageID);
	
	unset($dbobj);
	handle_validateNsubmit_imageupdateviews(0,'imageupdateviews','ajax');
	
}//image_update_views_ajax($editType,$callType)
?>