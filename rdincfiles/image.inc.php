<?php
if(isset($_GET['requesting']))
{ 
	if(!preg_match("/^[0-9]([0-9]*)/",$_GET['requesting'])){$_GET['requesting'] = NULL; }
	else{$get_type = $_GET['requesting']; }
	unset($_GET['requesting']);	
	switch($get_type)
	{
		case 1:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			$_GET['tempid'] = $_SESSION['user']['uid'];  image_get('iid',$_GET['iid']); unset($_GET['tempid']);
			break;
		case 2:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			$_GET['tempid'] = $_SESSION['user']['uid'];  image_get('aid',$_GET['aid']); unset($_GET['tempid']);
			break;
		case 3:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			$_GET['tempid'] = $_SESSION['user']['uid'];  $imageData = image_get('iid',$_GET['iid']); unset($_GET['tempid']);
			image_get_src($imageData,'thumbnails');
			break;
		case 4:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			$_GET['tempid'] = $_GET['uid'];  $imageData = image_get('uid',$_GET['uid']); unset($_GET['tempid']);
			get_image_list_tagged($_GET['uid'],$imageData,$_GET['tid']);
			break;			
		default:
			//IMPORTANT TO HAVE NO ACTION
			break;
	}//switch
}//

function image_get($tokenKey,$tokenValue)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$userInfo = array();
	$userData = $_SESSION['user'];
	$query = NULL;
	$imagesOrder = '';
	
	switch($tokenKey)
	{
		case 'iid':
			//SEARCH FOR ONE IMAGE WITH THIS ID
			$iid = $tokenValue;
			$iid = (get_magic_quotes_gpc()) ? $iid : addslashes($iid);
			$iid = htmlentities($iid, ENT_QUOTES, "UTF-8");
			$iid = trim($iid);
			
			$iid = "'".$iid."'";
			$query = "SELECT * FROM image WHERE iid=".$iid."; ";
			break;
		case 'aid':
			//SEARCH FOR ALL THE IMAGES WITH THIS ID
			$aid = $tokenValue;
			$aid = (get_magic_quotes_gpc()) ? $aid : addslashes($aid);
			$aid = htmlentities($aid, ENT_QUOTES, "UTF-8");
			$aid = trim($aid);
			
			$aid = "'".$aid."'";
			$query = "SELECT * FROM image WHERE aid=".$aid." AND imagetype = 'regular'; ";
			$query2 = "SELECT imagesorder FROM album WHERE aid=".$aid."; ";
			break;
		case 'pid':
			//SEARCH FOR ALL THE IMAGES WITH THIS ID
			$pid = $tokenValue;
			$pid = (get_magic_quotes_gpc()) ? $pid : addslashes($pid);
			$pid = htmlentities($pid, ENT_QUOTES, "UTF-8");
			$pid = trim($pid);
			
			$pid = "'".$pid."'";
			$query = "SELECT * FROM image WHERE pid=".$pid." AND imagetype = 'regular' ORDER BY submitiontimestamp ASC; ";
			break;
		case 'uid':
			//SEARCH FOR ALL THE IMAGES WITH THIS ID
			$uid = $tokenValue;
			$uid = (get_magic_quotes_gpc()) ? $uid : addslashes($uid);
			$uid = htmlentities($uid, ENT_QUOTES, "UTF-8");
			$uid = trim($uid);
			
			$uid = "'".$uid."'";
			$query = "SELECT * FROM image WHERE uid=".$uid." AND imagetype = 'regular' ORDER BY submitiontimestamp ASC; ";
			break;
		default:
			//nothing for the time being
			break;
	}//switch($key)
	

	if($query != NULL)
	{
		$dbVars = $dbobj->executeSelectQuery($query);
		if($dbVars['NUM_ROWS'] != 0)
		{
			
			if($tokenValue == 'aid')
			{
				$dbVars2 = $dbobj->executeSelectQuery($query2);
				if($dbVars2['NUM_ROWS'] != 0)
				{
					$imagesOrder = $dbVars2['RESULT'][0]['imagesorder'];		
				}//if
			}//
			
			//GET THE TAG NAMES
			$queryTagNames = "SELECT tid, name FROM tag WHERE uid='".$_GET['tempid']."' AND type='image'; ";
			$dbVars_tagNames = $dbobj->executeSelectQuery($queryTagNames);
			
			$tagNameString = '';
			if($dbVars_tagNames['NUM_ROWS'] != 0)
			{
				for($j=0; $j<$dbVars_tagNames['NUM_ROWS']; $j++)
					{$albumTags[$dbVars_tagNames['RESULT'][$j]['tid']] = $dbVars_tagNames['RESULT'][$j]['name']; $tagNameString.=$dbVars_tagNames['RESULT'][$j]['name'].',';}
				$_SESSION['tagNameString'] = $tagNameString;
			}//if
			
			for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
			{
				$imageInfo[$i]['iid'] = $dbVars['RESULT'][$i]['iid']; 
				$imageInfo[$i]['uid'] = $dbVars['RESULT'][$i]['uid'];
				$imageInfo[$i]['aid'] = $dbVars['RESULT'][$i]['aid'];
				$imageInfo[$i]['pid'] = $dbVars['RESULT'][$i]['pid'];
				$imageInfo[$i]['fid'] = $dbVars['RESULT'][$i]['fid'];
				$imageInfo[$i]['views'] = $dbVars['RESULT'][$i]['views'];
				$imageInfo[$i]['tags'] = $dbVars['RESULT'][$i]['tags'];
				$imageInfo[$i]['tids'] = $dbVars['RESULT'][$i]['tags'];
				$imageInfo[$i]['caption'] = $dbVars['RESULT'][$i]['caption'];
				$imageInfo[$i]['filename'] = $dbVars['RESULT'][$i]['filename'];
				$imageInfo[$i]['filesize'] = $dbVars['RESULT'][$i]['filesize'];
				$imageInfo[$i]['fileurl'] = $dbVars['RESULT'][$i]['fileurl'];
				$imageInfo[$i]['imagetype'] = $dbVars['RESULT'][$i]['imagetype'];
				$imageInfo[$i]['uploadtype'] = $dbVars['RESULT'][$i]['uploadtype'];
				$imageInfo[$i]['orientation'] = $dbVars['RESULT'][$i]['orientation'];
				$imageInfo[$i]['submitiontimestamp'] = $dbVars['RESULT'][$i]['submitiontimestamp'];
				
				if($imageInfo[$i]['caption'] == '' || $imageInfo[$i]['caption'] == NULL || strtolower($imageInfo[$i]['caption']) == 'null' )
					{$imageInfo[$i]['caption'] = '';}
				
				if( ($imageInfo[$i]['tags'] != NULL) && (strtolower($imageInfo[$i]['tags']) != 'null') && ($imageInfo[$i]['tags'] != '') && ($imageInfo[$i]['tags'] !=0) )
				{
					$imageInfoTags_temp = explode('::..::',$imageInfo[$i]['tags']);
					if($imageInfoTags_temp[(count($imageInfoTags_temp)-1)] == ''){unset($imageInfoTags_temp[(count($imageInfoTags_temp)-1)]);}
					
					$imageInfoTags_temp_final = '';
					for($p=0; $p<count($imageInfoTags_temp); $p++)
					{
						if($p == (count($imageInfoTags_temp)-1)){$imageInfoTags_temp_final .= $albumTags[$imageInfoTags_temp[$p]];}
						else{$imageInfoTags_temp_final .= $albumTags[$imageInfoTags_temp[$p]].",";}
					}//for
					$imageInfo[$i]['tags'] = $imageInfoTags_temp_final;
				}//if
				
				if($tokenKey == 'iid')
					{$imagesOrder .= $imageInfo[$i]['iid'].':..::..:';}

			}//for	
			
			//UPDATE THE 'views' FIELD
			//user_update_views($userInfo['views'], $userInfo['uid']);
			
			if($tokenKey == 'iid')
			{
				$imageInfo = $imageInfo[0];
				
				reset($imageInfo); while (list($key, $val) = each ($imageInfo))
					{ if(($val == NULL)||(strtolower($val) == 'null')){$imageInfo[$key] = '';} else {$imageInfo[$key] = $val;}}
			}
			
			unset($validator);
			unset($dbVars);
			
			return $imageInfo;
		}//if
		else
		{
			//no result found
		}//else
	}//
	else{} //query was NULL


}//image_get()

function image_get_src($imageData,$sizeType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	
	$selectQuery = "SELECT username FROM user WHERE uid='".$imageData['uid']."'; ";
	$dbVars_select = $dbobj->executeSelectQuery($selectQuery);
	$username = $dbVars_select['RESULT'][0]['username'];
	
	if($imageData['imagetype'] == 'regular')
		{$imageURL = './rdusers/'.$username.'/portfolioimages/'.$sizeType.'/'.$imageData['fileurl']; $formID = 'imagesrc';}
	else if($imageData['imagetype'] == 'albumcover')
		{$imageURL = './rdusers/'.$username.'/coverimages/'.$imageData['fileurl']; $formID = 'albumcoverimagesrc';}
	else if($imageData['imagetype'] == 'usercover')
		{$imageURL = './rdusers/'.$username.'/coverimages/'.$imageData['fileurl']; $formID = 'usercoverimagesrc';}
		
	unset($dbobj);
	
	$imageID = $imageData['iid'];
	$imageOrientation = $imageData['orientation'];
	jsonresponse_image_src_thumbnail(0,$formID,$imageID,$imageOrientation,$imageURL);
}//image_get_src($imageData)

function image_get_ordered($imageDataOriginal,$albumID,$userID)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$userInfo = array();
	$query = NULL;

	if(count($imageDataOriginal) != 0){
		for($i=0; $i<count($imageDataOriginal); $i++)
		{
			$imageID = $imageDataOriginal[$i]['iid'];
			$imageData[$imageID]['iid'] = $imageDataOriginal[$i]['iid']; 
			$imageData[$imageID]['uid'] = $imageDataOriginal[$i]['uid'];
			$imageData[$imageID]['aid'] = $imageDataOriginal[$i]['aid'];
			$imageData[$imageID]['fid'] = $imageDataOriginal[$i]['fid'];
			$imageData[$imageID]['pid'] = $imageDataOriginal[$i]['pid'];
			$imageData[$imageID]['views'] = $imageDataOriginal[$i]['views'];
			$imageData[$imageID]['tags'] = $imageDataOriginal[$i]['tags'];
			$imageData[$imageID]['tids'] = $imageDataOriginal[$i]['tids'];
			$imageData[$imageID]['caption'] = $imageDataOriginal[$i]['caption'];
			$imageData[$imageID]['filename'] = $imageDataOriginal[$i]['filename'];
			$imageData[$imageID]['filesize'] = $imageDataOriginal[$i]['filesize'];
			$imageData[$imageID]['fileurl'] = $imageDataOriginal[$i]['fileurl'];
			$imageData[$imageID]['imagetype'] = $imageDataOriginal[$i]['imagetype'];
			$imageData[$imageID]['uploadtype'] = $imageDataOriginal[$i]['uploadtype'];
			$imageData[$imageID]['orientation'] = $imageDataOriginal[$i]['orientation'];
			$imageData[$imageID]['submitiontimestamp'] = $imageDataOriginal[$i]['submitiontimestamp'];
		}//for

		$selectPortfolioImagesOrderQuery = "SELECT imagesorder FROM album WHERE aid='".$albumID."' AND uid='".$userID."'; ";
		$dbVars = $dbobj->executeSelectQuery($selectPortfolioImagesOrderQuery);	
		$portfolioImagesOrder = $dbVars['RESULT'][0]['imagesorder'];
		
		$portfolioImagesOrder = explode(':..::..:',$portfolioImagesOrder);
		unset($portfolioImagesOrder[(count($portfolioImagesOrder)-1)]);
	
		for($k=0; $k<count($portfolioImagesOrder); $k++)
		{
			if(isset($imageData[$portfolioImagesOrder[$k]]))
				{$portfolioImagesArrOrdered[$portfolioImagesOrder[$k]] = $imageData[$portfolioImagesOrder[$k]];}
		}//for

		reset($portfolioImagesArrOrdered);
		reset($imageData);
		$counter = 0;
		while (list($key, $val) = each ($portfolioImagesArrOrdered))
		{
			$imageData[$counter] = $imageData[$key];
			unset($imageData[$key]);
			$counter++;
		}//while
		unset($dbobj);

		return $imageData;
	}//if
}//image_get_ordered($imageData)

function get_image_list($userID,$albumID,$postID,$imagesSize,$presentationMode) //'thumbnails', 'fullsize'
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$userInfo = array();
	$query = NULL;
	
	$selectQuery = "SELECT username FROM user WHERE uid='".$userID."'; ";
	$dbVars_select = $dbobj->executeSelectQuery($selectQuery);
	$username = $dbVars_select['RESULT'][0]['username'];
	unset($dbobj);
	
	if($postID == 0)
	{
		$_GET['tempid'] = $userID; $imageData = image_get('aid',$albumID); unset($_GET['tempid']);
		if(count($imageData) != 0){$imageData = image_get_ordered($imageData,$albumID,$userID);}
	}//if
	if($postID != 0)
	{
		$imageData = image_get('pid',$postID);

		if(count($imageData) != 0)
		{
			$_GET['tempid'] = $userID; $albumID = $imageData[0]['aid'];  unset($_GET['tempid']);
			$imageData = image_get_ordered($imageData,$albumID,$userID);
		}//if
	}//if

	$albumImagesFullsizeURL = './rdusers/'.$username."/portfolioimages/fullresolution/";
	$albumImagesLargeThumbsURL = './rdusers/'.$username."/portfolioimages/largethumbnails/";
	$albumImagesThumbsURL = './rdusers/'.$username."/portfolioimages/thumbnails/";
	
	if($imagesSize == 'coverpage_thumbnails'){$imagesSize = 'thumbnails'; echo "<div id='viewalbumimagespresentationtype' class='clear displaynone'>thumbnails</div>";}
	
	switch($imagesSize)
	{
		case 'thumbnails':
			//echo "<span id='portfolioimageviewsize' class='displaynone' >thumbnails</span>";
			$imageFolderURL = $albumImagesThumbsURL;
			$imageWidth = IMAGES_FOLDER_THUMBNAILS_PIXELS;
			$imageHeight = IMAGES_FOLDER_THUMBNAILS_PIXELS;
			$ulID = 'thumbnails';
			
			if($presentationMode == 'edit'){
				$ulID = 'thumbnails';
				$ulClass = 'ul_portfolio_images_thumbnails';
				$imgClass = 'pi_thumbs';
			}
			else{
				$ulID = 'view_thumbnails';
				$ulClass = 'ul_portfolio_images_thumbnails_view';
				$imgClass = 'pi_thumbs_view';
				
				//echo "<div class='titles2'>click images to enlarge</div>";
				echo "<div id='viewalbumimagespresentationtype' class='clear displaynone'>thumbnails</div>";
			}
			
			break;
		case 'fullsize':
			//echo "<span id='portfolioimageviewsize' class='displaynone' >fullsize</span>";
			$imageFolderURL = $albumImagesFullsizeURL;
			$imageWidth = '';
			$imageHeight = '';
			$ulID = 'fullsize';
			
			if($presentationMode == 'edit'){
				$ulID = 'fullsize';
				$ulClass = 'ul_portfolio_images_fullsize';
				$imgClass = 'pi_thumbs';
			}
			else{
				$ulID = 'view_fullsize';
				$ulClass = 'ul_portfolio_images_fullsize_view';
				$imgClass = 'pi_thumbs_view';
				
				echo "<div id='viewalbumimagespresentationtype' class='clear displaynone'>fullsize</div>";
			}
			//update all the images
			
					
			break;
		case 'largethumbnails':
			//echo "<span id='portfolioimageviewsize' class='displaynone' >fullsize</span>";
			$imageFolderURL = $albumImagesLargeThumbsURL;
			$imageWidth = IMAGES_FOLDER_LARGE_THUMBNAILS_PIXELS;
			$imageHeight = IMAGES_FOLDER_LARGE_THUMBNAILS_PIXELS;
			$ulID = 'thumbnails';
			
			if($presentationMode == 'edit'){
				$ulID = 'thumbnails';
				$ulClass = 'ul_portfolio_images_largethumbnails';
				$imgClass = 'pi_thumbs';
			}
			else{
				$ulID = 'view_thumbnails';
				$ulClass = 'ul_portfolio_images_largethumbnails_view';
				$imgClass = 'pi_thumbs_view';
				
				//echo "<div class='titles2'>click images to enlarge</div>";
				echo "<div id='viewalbumimagespresentationtype' class='clear displaynone'>largethumbnails</div>";
			}
			break;
		default:
			break;
	}//

	$imagesOrder = '';
	if( ($presentationMode == 'edit') && (count($imageData)!=0)){echo "<div id='editimagesinstructions' class='titles2 displaynone'>click on images to edit</div>";}
	echo "<ul id='".$ulID."' class='".$ulClass."'>";
	if(count($imageData)!=0)
	{
		reset($imageData);
		$loadMoreCounter = 1;
		while (list($key, $val) = each ($imageData))
		{
			$j = $key;
			//update the views of all the images of this album
			if($imagesSize == 'fullsize'){
				$imageData[$j]['views'] = image_update_views($imageData[$j]['views'], $imageData[$j]['iid']);
				if($imageData[$j]['views'] == 1){$imageData[$j]['views'] = "(".$imageData[$j]['views']." view)";}
				else{$imageData[$j]['views'] = "(".$imageData[$j]['views']." views)";}
			}//
			$imageID = $imageData[$j]['iid'];
			$imageFileURL = $imageFolderURL.$imageData[$j]['fileurl'];
			$imageOrientation = $imageData[$j]['orientation'];
			$imageCaption = $imageData[$j]['caption'];
			$imageViews = $imageData[$j]['views'];
			$imageTags = $imageData[$j]['tags'];
			$imageTagIDs = $imageData[$j]['tids'];

			if($imageTags == NULL || strtolower($imageTags) == 'null'){$imageTags='';}
			
			$imageUploadedTimestamp = $imageData[$j]['submitiontimestamp'];
			
			$imagesOrder .= $imageID.",";		
			if($loadMoreCounter > 5)
				{if(($presentationMode == 'live') && ($ulClass == 'ul_portfolio_images_fullsize_view') ){$ulClass .= ' displaynone'; }}
			
			echo "<li id='portfolioimageli_".$imageID."' class='pimages ".$ulClass."' rel='".$imageID."'>";
				echo "<img src='".$imageFileURL."' id='portfolioimage_".$imageID."' height='".$imageHeight."' width='".$imageWidth."' class='".$imgClass."' />";
				echo "<span id='portfolioimageorientation_".$imageID."' class='displaynone'>".$imageOrientation."</span>";
				echo "<span id='portfolioimagecaption_".$imageID."' class='portfolioimagecaptions displaynone'>".$imageCaption."</span>";
				if($imageTags!='' && $imgClass != 'pi_thumbs'){echo "<span class='portfolioimagetagstext displaynone'>Filed under: </span>";}
				
				if($presentationMode == 'live')
				{	
					if($imageTags != '')
					{
						$imageTagsNames = explode(',',$imageTags);
						$imageTagIDs = explode('::..::',$imageTagIDs);
						$imageTagsHTML = '';
						for($f=0; $f<count($imageTagsNames); $f++)
						{
							$imageTagsHTML .= "<span class='portfolio_tags' id='portfolio_tag_".$imageTagIDs[$f]."'>".$imageTagsNames[$f]."</span>";
							
							if($f != (count($imageTagsNames)-1) ){$imageTagsHTML .= ", ";}
						}//
						$imageTags = $imageTagsHTML;
					}//if
				}//if
				echo "<span id='portfolioimagetags_".$imageID."' class='portfolioimagetags displaynone'>".$imageTags."</span>";
				echo "<span id='portfolioimageviews_".$imageID."' class='portfolioimageviews displaynone'>".$imageViews."</span>";
				echo "<span id='portfolioimageuploaded_".$imageID."' class='displaynone'>".convertTimeStamp($imageUploadedTimestamp,'reallylong')."</span>";
				echo "<span id='portfolioimageusername_".$imageID."' class='displaynone'>".$username."</span>";
				echo "<span id='portfolioimagealbum_".$imageID."' class='displaynone'>".$albumID."</span>";
			echo "</li>";
			
			$loadMoreCounter++;
		}//while
	}//		
	echo "</ul>";
	if(($presentationMode == 'live') && ($ulID == 'view_fullsize') && !(count($imageData) < 5 ))
	{
		echo "<div class='clear'></div>";
		echo "<div id='portfolio_album_images_get_more_container'><span id='portfolio_album_images_get_more'>load all images</span></div>";	
	}
	
	$tagNameString = $_SESSION['tagNameString']; unset($_SESSION['tagNameString']);
	
	echo "<div class='albumportfolioimagesorder clear displaynone'>".$imagesOrder."</div>";
	echo "<div class='imagesalltagnames clear displaynone'>".$tagNameString."</div>";
	unset($dbobj);
	
	return count($imageData);
	/*
	echo "<div id='currentportfolioimagescountercontainer'>";
		echo "<span id='currentportfolioimagescounter'>".$selectPortfolioImagesNum."</span>"." images"
	echo "</div>";
	echo "<span id='lastdeletedportfolioimagescounter'></span>";	
	*/
}//get_image_list($userID,$albumID,$postID,$imagesSize,$presentationMode)

function get_image_list_tagged($userID,$imageData,$tagID)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$tagArr = array();
	$dbVarsAlbums = array();
	$dbVarsCategories = array();
	$dbVarsCoverImages = array();
	
	$selectUsername = "SELECT username FROM user WHERE uid='".$userID."'; ";	
	$dbVarsUsername = $dbobj->executeSelectQuery($selectUsername);
	$username = $dbVarsUsername['RESULT'][0]['username'];
	
	$selectTagName = "SELECT name FROM tag WHERE tid='".$tagID."'; ";
	$dbVarsTagName = $dbobj->executeSelectQuery($selectTagName);
	$tagName = $dbVarsTagName['RESULT'][0]['name'];
	
	echo "<div id='display_tagnameinfo'>";
		echo "<span id='display_tagcategory'>tags</span>";
		echo "/";
		echo "<span id='display_tagname' class='text_highlight text_highlight_bg'>".$tagName."</span>";
	echo"</div>";
	
	echo "<div class='display_tagname_big text_highlight text_highlight_bg'>Images tagged as: ".$tagName."</div>";
	
	if(count($imageData)!=0)
	{
		
		for($i=0; $i<count($imageData); $i++)
		{
			if(($imageData[$i]['tids'] != '') && ($imageData[$i]['tids'] != NULL))
			{
				$tempImageTags = explode('::..::',$imageData[$i]['tids']);
				if($tempImageTags[count($tempImageTags)-1]==''){unset($tempImageTags[count($tempImageTags)-1]);}
				for($k=0; $k<count($tempImageTags); $k++)
					{if($tempImageTags[$k] == $tagID){$imageDataTagged[$imageData[$i]['iid']] = $imageData[$i];}}
			}//if
		}//for
		if(count($imageDataTagged) != 0)
		{
			unset($imageData);
			$imageData = $imageDataTagged;
			unset($imageDataTagged);
			
			$albumImagesThumbsURL = './rdusers/'.$username."/portfolioimages/thumbnails/";

			$imageFolderURL = $albumImagesThumbsURL;
			$imageWidth = IMAGES_FOLDER_THUMBNAILS_PIXELS;
			$imageHeight = IMAGES_FOLDER_THUMBNAILS_PIXELS;
			$ulID = 'thumbnails';
			
			$ulID = 'view_thumbnails';
			$ulClass = 'ul_portfolio_images_thumbnails_view';
			$imgClass = 'pi_thumbs_view';
			
			//echo "<div class='titles2'>click images to enlarge</div>";
			echo "<div id='viewalbumimagespresentationtype' class='clear displaynone'>thumbnails</div>";

		
			$imagesOrder = '';
			if( ($presentationMode == 'edit') && (count($imageData)!=0) ){echo "<div class='titles2'>click images to edit</div>";}
			echo "<ul id='".$ulID."' class='".$ulClass."'>";
			if(count($imageData)!=0)
			{
				reset($imageData);
				$loadMoreCounter = 1;
				while (list($key, $val) = each ($imageData))
				{
					$j = $key;
					//update the views of all the images of this album
					$imageID = $imageData[$j]['iid'];
					$imageFileURL = $imageFolderURL.$imageData[$j]['fileurl'];
					$imageOrientation = $imageData[$j]['orientation'];
					$imageCaption = $imageData[$j]['caption'];
					$imageViews = $imageData[$j]['views'];
					$imageTags = $imageData[$j]['tags'];
					$imageTagIDs = $imageData[$j]['tids'];
					
					if($imageTags == NULL || strtolower($imageTags) == 'null'){$imageTags='';}
					
					$imageUploadedTimestamp = $imageData[$j]['submitiontimestamp'];
					
					$imagesOrder .= $imageID.",";		
					if($loadMoreCounter > 5)
						{if(($presentationMode == 'live') && ($ulClass == 'ul_portfolio_images_fullsize_view') ){$ulClass .= ' displaynone'; }}
					
					if($imageTags != '')
					{
						$imageTagsNames = explode(',',$imageTags);
						$imageTagIDs = explode('::..::',$imageTagIDs);
						$imageTagsHTML = '';
						for($f=0; $f<count($imageTagsNames); $f++)
						{
							$imageTagsHTML .= "<span class='portfolio_tags' id='portfolio_tag_".$imageTagIDs[$f]."'>".$imageTagsNames[$f]."</span>";
							
							if($f != (count($imageTagsNames)-1) ){$imageTagsHTML .= ", ";}
						}//
						$imageTags = $imageTagsHTML;
					}//if
					
					echo "<li id='portfolioimageli_".$imageID."' class='pimages ".$ulClass."' rel='".$imageID."'>";
						echo "<img src='".$imageFileURL."' id='portfolioimage_".$imageID."' height='".$imageHeight."' width='".$imageWidth."' class='".$imgClass."' />";
						echo "<span id='portfolioimageorientation_".$imageID."' class='displaynone'>".$imageOrientation."</span>";
						echo "<span id='portfolioimagecaption_".$imageID."' class='portfolioimagecaptions displaynone'>".$imageCaption."</span>";
						if($imageTags!='' && $imgClass != 'pi_thumbs'){echo "<span class='portfolioimagetagstext displaynone'>Filed under: </span>";}
						echo "<span id='portfolioimagetags_".$imageID."' class='portfolioimagetags displaynone'>".$imageTags."</span>";
						echo "<span id='portfolioimageviews_".$imageID."' class='portfolioimageviews displaynone'>".$imageViews."</span>";
						echo "<span id='portfolioimageuploaded_".$imageID."' class='displaynone'>".convertTimeStamp($imageUploadedTimestamp,'reallylong')."</span>";
						echo "<span id='portfolioimageusername_".$imageID."' class='displaynone'>".$username."</span>";
						echo "<span id='portfolioimagealbum_".$imageID."' class='displaynone'>".$albumID."</span>";
					echo "</li>";
					
					$loadMoreCounter++;
				}//while
			}//		
			echo "</ul>";
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			$tagNameString = $_SESSION['tagNameString']; unset($_SESSION['tagNameString']);
			
			echo "<div class='albumportfolioimagesorder clear displaynone'>".$imagesOrder."</div>";
			echo "<div class='imagesalltagnames clear displaynone'>".$tagNameString."</div>";
		}//if
	}//if
	else
	{
		echo "<div>No images found</div>";
	}
	unset($dbobj);
}//get_image_list_tagged($imageData,$tagID)


function get_image_coverpage($userData)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$imageVars = array();
	$welcomeImageHTML = '';
	
	$query = "SELECT image.iid, image.fileurl FROM image,album WHERE image.uid='".$userData['uid']."' AND image.aid=album.aid AND album.type='coverpageimagesalbum'; ";	
	$dbVars = $dbobj->executeSelectQuery($query);
	
	if($dbVars['NUM_ROWS'] == 0){$coverImage = '';}
	else
	{
		for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
		{
			$imageVars[$i]['iid'] = $dbVars['RESULT'][$i]['iid'];
			$imageVars[$i]['imageurl'] = "./rdusers/".$userData['username']."/portfolioimages/fullresolution/".$dbVars['RESULT'][$i]['fileurl'];
		}//for
		$randomInt = rand(0,($dbVars['NUM_ROWS']-1));
		$coverImage = "<img src='".$imageVars[$randomInt]['imageurl']."' id='' class='coverimage'/>";
	}//else
	
	unset($dbobj);
	return $coverImage;
}//get_image_coverpage($userData)

function image_update_views($currentViewsCounter, $imageID)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);//CALLS THE CLASS CONSTRUCTOR AND CONNECTS TO THE DB WITH THE APPORPRIATE DB ACCOUNT
	$dbVars = array();
	
	$currentViewsCounter++;
	$query = "UPDATE image"
				." SET views='".$currentViewsCounter."'"
				." WHERE iid='".$imageID."'; ";
	$dbVars = $dbobj->executeUpdateQuery($query);
	
	unset($dbobj);
	return $currentViewsCounter;
}//image_update_views($currentViewsCounter, $imageID)


?>