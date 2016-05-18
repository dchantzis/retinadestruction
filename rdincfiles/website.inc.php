<?php

function website_get($tokenKey,$tokenValue)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$userInfo = array();
	$query = NULL;
	
	switch($tokenKey)
	{
		case 'uid':
			$uid = $tokenValue;
			$uid = (get_magic_quotes_gpc()) ? $uid : addslashes($uid);
			$uid = htmlentities($uid, ENT_QUOTES, "UTF-8");
			$uid = trim($uid);
			
			$uid = "'".$uid."'";
			$query = "SELECT * FROM website WHERE uid=".$uid."; ";
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
			$websiteInfo['wid'] = $dbVars['RESULT'][0]['wid'];
			$websiteInfo['uid'] = $dbVars['RESULT'][0]['uid'];
			$websiteInfo['title'] = $dbVars['RESULT'][0]['title'];
			$websiteInfo['views'] = $dbVars['RESULT'][0]['views'];
			$websiteInfo['tid'] = $dbVars['RESULT'][0]['tid'];
			$websiteInfo['wallpaper'] = $dbVars['RESULT'][0]['wallpaper'];
			$websiteInfo['logo'] = $dbVars['RESULT'][0]['logo'];
			$websiteInfo['sidebarinfo'] = $dbVars['RESULT'][0]['sidebarinfo'];
			$websiteInfo['coverpage'] = $dbVars['RESULT'][0]['coverpage'];
			$websiteInfo['sidebarorientation'] = $dbVars['RESULT'][0]['sidebarorientation'];
			$websiteInfo['imagetagcloud'] = $dbVars['RESULT'][0]['imagetagcloud'];
			$websiteInfo['designcsscode'] = $dbVars['RESULT'][0]['designcsscode'];
			$websiteInfo['designformoptions'] = $dbVars['RESULT'][0]['designformoptions'];
			$websiteInfo['uploadimageswidth'] = $dbVars['RESULT'][0]['uploadimageswidth'];
			$websiteInfo['uploadimagestype'] = $dbVars['RESULT'][0]['uploadimagestype'];
			$websiteInfo['visibilitystatus'] = $dbVars['RESULT'][0]['visibilitystatus'];
			$websiteInfo['status'] = $dbVars['RESULT'][0]['status'];
			$websiteInfo['lastupdatedtimestamp'] = $dbVars['RESULT'][0]['lastupdatedtimestamp'];
			$websiteInfo['creationtimestamp'] = $dbVars['RESULT'][0]['creationtimestamp'];	
			
			reset($websiteInfo); while (list($key, $val) = each ($websiteInfo))
					{ if(($val == NULL)||(strtolower($val) == 'null')){$websiteInfo[$key] = '';} else {$websiteInfo[$key] = $val;}}
			
			unset($validator);
			unset($dbVars);
			unset($dbobj);
			return $websiteInfo;
		}//if
		else
		{
			//no result found
		}//else
	}//
	else{} //query was NULL
	
}//website_info($tokenKey,$tokenValue)

function website_coverpage($userData,$websiteInfo)
{
	switch($websiteInfo['coverpage'])
	{
		case 'empty':
			break;
		case 'blogsection':
			$_GET['l'] = 0;
			$_GET['cp'] = 1;
			$_GET['blognavidir'] = 'latest'; 
			get_blog_list('live',$userData['uid']);
?>
            	<script type="text/javascript" language="javascript">blog_initialize_elements();</script>
<?
			break;
		case 'randomimage':
			$coverImage = get_image_coverpage($userData);
			echo $coverImage;
			break;
		default:
			//default is 'empty'
			break;
	}//switch
}//website_coverpage($userData,$websiteInfo)

function website_update_views($currentViewsCounter, $websiteID)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);//CALLS THE CLASS CONSTRUCTOR AND CONNECTS TO THE DB WITH THE APPORPRIATE DB ACCOUNT
	$dbVars = array();
	
	$currentViewsCounter++;
	$query = "UPDATE website"
				." SET views='".$currentViewsCounter."'"
				." WHERE wid='".$websiteID."'; ";
	$dbVars = $dbobj->executeUpdateQuery($query);
	
	unset($dbobj);
	return $currentViewsCounter;
}//album_update_views($currentViewsCounter, $websiteID)
?>