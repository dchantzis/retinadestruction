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
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			get_album_list('edit_short',$_SESSION['user']);
			break;
		case 2:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			get_album_list('view_covers_short',$_SESSION['user']);
			break;
		case 3:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			require("../rdincfiles/image.inc.php");

			get_album_view($_SESSION['user']['uid'], $_GET['albumid'], 'demo');
			break;
		case 4:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			require("../rdincfiles/image.inc.php");
			
			get_album_view($_GET['userid'], $_GET['albumid'], 'live');
			break;
		case 5:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			require("../rdincfiles/image.inc.php");
						
			get_album_list_portfolio($_GET['uid'],$_GET['username']);
			break;
		default:
			//IMPORTANT TO HAVE NO ACTION
			break;
	}//switch
}//

function album_get($tokenKey,$tokenValue)
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
		case 'aid':
			$aid = $tokenValue;
			$aid = (get_magic_quotes_gpc()) ? $aid : addslashes($aid);
			$aid = htmlentities($aid, ENT_QUOTES, "UTF-8");
			$aid = trim($aid);
			
			$aid = "'".$aid."'";
			$query = "SELECT * FROM album WHERE aid=".$aid." ORDER BY name ASC; ";
			$queryAlbumCategories = "SELECT * FROM tag WHERE type='album'; ";
			break;
		case 'uid':
			$uid = $tokenValue;
			$uid = (get_magic_quotes_gpc()) ? $uid : addslashes($uid);
			$uid = htmlentities($uid, ENT_QUOTES, "UTF-8");
			$uid = trim($uid);
			
			$uid = "'".$uid."'";
			$query = "SELECT * FROM album WHERE uid=".$uid." ORDER BY name ASC; ";
			$queryAlbumCategories = "SELECT * FROM tag WHERE uid=".$uid." AND type='album'; ";
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
						
			$dbVars2 = $dbobj->executeSelectQuery($queryAlbumCategories);
			if($dbVars2['NUM_ROWS'] != 0)
			{
				for($j=0; $j<$dbVars2['NUM_ROWS']; $j++)
				{
					$tagInfo[$dbVars2['RESULT'][$j]['tid']] = $dbVars2['RESULT'][$j]['name'];
				}//for			
			}//if
		
				for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
				{
					$albumInfo[$i]['aid'] = $dbVars['RESULT'][$i]['aid'];
					$albumInfo[$i]['uid'] = $dbVars['RESULT'][$i]['uid'];
					$albumInfo[$i]['name'] = $dbVars['RESULT'][$i]['name'];
					$albumInfo[$i]['description'] = $dbVars['RESULT'][$i]['description'];
					$albumInfo[$i]['embeddedvideos'] = $dbVars['RESULT'][$i]['embeddedvideos'];
					$albumInfo[$i]['tid'] = $dbVars['RESULT'][$i]['tid'];
					$albumInfo[$i]['categoryname'] = $tagInfo[$dbVars['RESULT'][$i]['tid']];
					$albumInfo[$i]['coverid'] = $dbVars['RESULT'][$i]['coverid'];
					$albumInfo[$i]['imagesorder'] = $dbVars['RESULT'][$i]['imagesorder'];
					$albumInfo[$i]['visibilitystatus'] = $dbVars['RESULT'][$i]['visibilitystatus'];
					$albumInfo[$i]['imageview'] = $dbVars['RESULT'][$i]['imageview'];
					$albumInfo[$i]['commentscounter'] = $dbVars['RESULT'][$i]['commentscounter'];
					$albumInfo[$i]['views'] = $dbVars['RESULT'][$i]['views'];
					$albumInfo[$i]['type'] = $dbVars['RESULT'][$i]['type'];
					$albumInfo[$i]['imagescounter'] = $dbVars['RESULT'][$i]['imagescounter'];
					$albumInfo[$i]['lastupdatedtimestamp'] = $dbVars['RESULT'][$i]['lastupdatedtimestamp'];		
					$albumInfo[$i]['creationtimestamp'] = $dbVars['RESULT'][$i]['creationtimestamp'];
				}//for	
			
			//UPDATE THE 'views' FIELD
			//user_update_views($userInfo['views'], $userInfo['uid']);
		
			for($i=0; $i<$dbVarsCoverImages['NUM_ROWS']; $i++)
				{$coverImages[$dbVarsCoverImages['RESULT'][$i]['iid']] = $dbVarsCoverImages['RESULT'][$i]['fileurl'];}
			
			
			if($tokenKey == 'aid')
			{
				$albumInfo = $albumInfo[0];
				
				reset($albumInfo); while (list($key, $val) = each ($albumInfo))
					{ if(($val == NULL)||(strtolower($val) == 'null')){$albumInfo[$key] = '';} else {$albumInfo[$key] = $val;}}
				
				
				$selectCoverImages = "SELECT iid, fileurl FROM image WHERE uid='".$albumInfo['uid']."' AND aid='".$albumInfo['aid']."' AND imagetype='albumcover'; ";
				$dbVarsCoverImages = $dbobj->executeSelectQuery($selectCoverImages);
				for($i=0; $i<$dbVarsCoverImages['NUM_ROWS']; $i++)
					{$coverImages[$dbVarsCoverImages['RESULT'][$i]['iid']] = $dbVarsCoverImages['RESULT'][$i]['fileurl'];}

				if($albumInfo['coverid'] != 0 && isset($coverImages[$albumInfo['coverid']]))
					{$coverThumb = $coverImages[$albumInfo['coverid']]; }
				else
					{$coverThumb = DEFAULT_BLANK_COVER_THUMBNAIL;}

				$albumInfo['coverimage'] = $coverThumb;
			}//if
			
			unset($validator);
			unset($dbVars);
			return $albumInfo;
		}//if
		else
		{
			//no result found
		}//else
	}//
	else{} //query was NULL
	
}//album_get($token)


function get_album_list($listType,$userData)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(1);
	$tagArr = array();
	$dbVarsAlbums = array();
	$dbVarsCategories = array();
	$dbVarsCoverImages = array();
	
	$selectAlbums = "SELECT * FROM album WHERE uid='".$userData['uid']."' ORDER BY creationtimestamp DESC; ";
	$dbVarsAlbums = $dbobj->executeSelectQuery($selectAlbums);
	
	if($dbVarsAlbums['NUM_ROWS'] != 0)
	{
		$selectCategories = "SELECT * FROM tag WHERE uid='".$userData['uid']."' AND type='album' ORDER BY tid ASC; ";
		$dbVarsCategories = $dbobj->executeSelectQuery($selectCategories);
		
		if($dbVarsCategories['NUM_ROWS'] != 0)
		{
			$albumCategories = '';
			for($j=0; $j<$dbVarsCategories['NUM_ROWS']; $j++)
			{
				$categoryArr[$dbVarsCategories['RESULT'][$j]['tid']] = $dbVarsCategories['RESULT'][$j]['name'];
				$albumCategories .= $dbVarsCategories['RESULT'][$j]['name']."::"; 
			}//
		}//inner if

		for($i=0; $i<$dbVarsAlbums['NUM_ROWS']; $i++)
		{
			$albumArr[$i]['aid'] = $dbVarsAlbums['RESULT'][$i]['aid'];
			$albumArr[$i]['tid'] = $dbVarsAlbums['RESULT'][$i]['tid'];
			$albumArr[$i]['name'] = $dbVarsAlbums['RESULT'][$i]['name'];
			$albumArr[$i]['coverid'] = $dbVarsAlbums['RESULT'][$i]['coverid'];
			$albumArr[$i]['commentscounter'] = $dbVarsAlbums['RESULT'][$i]['commentscounter'];
			$albumArr[$i]['visibilitystatus'] = $dbVarsAlbums['RESULT'][$i]['visibilitystatus'];
			$albumArr[$i]['views'] =  $dbVarsAlbums['RESULT'][$i]['views'];
			$albumArr[$i]['lastupdatedtimestamp'] = $dbVarsAlbums['RESULT'][$i]['lastupdatedtimestamp'];

			if(isset($categoryArr[$albumArr[$i]['tid']])){$albumArr[$i]['categoryname'] = $categoryArr[$albumArr[$i]['tid']]; }
				else{$albumArr[$i]['categoryname'] = ''; }
		}//
		
	}//outer if
	
	echo "<span id='user_username' class='displaynone'>".$userData['username']."</span>";
    echo "<span id='user_email' class='displaynone'>".$userData['email']."</span>";

	switch($listType)
	{
		case 'edit_short':
		
			echo "<div id='album_create_button' class='simple'><span class='blackbackground'>+</span> Add New Album</div><br />";
			echo "<ul id='account_albums_navigation'>";
			if(count($albumArr) != 0)
			{
				for($i=0; $i<count($albumArr); $i++)
				{
					$visibilityClass = 'albumvisible';
					if($albumArr[$i]['visibilitystatus'] == 'invisible'){$visibilityClass = 'albuminvisible';}
					
					$newAlbumClass = '';
					if($_GET['newaid'] == $albumArr[$i]['aid']){unset($_GET['newaid']); $newAlbumClass = 'newalbum';}
					
					
					echo "<li id='account_album_".$albumArr[$i]['aid']."' class='account_albums ".$newAlbumClass." ".$visibilityClass."'>";
						echo "<span id='album_container_".$albumArr[$i]['aid']."' class='album_containers'>";
							echo "<span id='album_delete_".$albumArr[$i]['aid']."' class='album_deletes'>x</span>";
							echo "<span id='album_visibility_".$albumArr[$i]['aid']."' class='album_visibilitys ".$visibilityClass."'>".$albumArr[$i]['visibilitystatus']."</span>";
							echo "<span id='album_comments_".$albumArr[$i]['aid']."' class='album_comments'>Comments</span>";
							echo "<span id='album_name_".$albumArr[$i]['aid']."' class='album_names'>".strtoupper($albumArr[$i]['name'])."</span>";
							echo "<span id='album_category_".$albumArr[$i]['aid']."' class='album_categorys'>".$albumArr[$i]['categoryname']."</span>";
						echo "</span>";
						echo "<span id='album_container_deleteoptions_".$albumArr[$i]['aid']."' class='album_container_deleteoptionss displaynone'>";
							echo "<span class='highlight_color'>Delete this album and all its images?</span>";
							echo "<span id='delete_album_yes_".$albumArr[$i]['aid']."' class='togglers'>yes</span>";
							echo "<span id='delete_album_no_".$albumArr[$i]['aid']."' class='togglers'>no</span>";
						echo "</span>";
					echo "</li>";
				}//for
			}//
			else
				{echo "<li class='edit_short_noalbums'>No albums to edit</li>";}
			echo "</ul>";
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			break;
		case 'view_covers_short':
	
			echo "<span class='refreshsection simple' id='refreshsection_albumlist'>Refresh</span> <span id='demosection' class=''>Demostration View</span>";
		
			$selectCoverImages = "SELECT iid, fileurl FROM image WHERE uid='".$userData['uid']."' AND imagetype='albumcover'; ";
			$dbVarsCoverImages = $dbobj->executeSelectQuery($selectCoverImages);
		
			for($i=0; $i<$dbVarsCoverImages['NUM_ROWS']; $i++)
				{$coverImages[$dbVarsCoverImages['RESULT'][$i]['iid']] = $dbVarsCoverImages['RESULT'][$i]['fileurl'];}
		
			echo "<ul id='view_albums_navigation'>";
			if(count($albumArr) != 0)
			{		
				for($i=0; $i<count($albumArr); $i++)
				{
						
					if($albumArr[$i]['coverid'] != 0 && isset($coverImages[$albumArr[$i]['coverid']]) )
						{$coverThumb = './rdusers/'.$userData['username']."/".IMAGES_FOLDER_COVER_IMAGES."/".$coverImages[$albumArr[$i]['coverid']]; }
					else
						{$coverThumb = DEFAULT_BLANK_COVER_THUMBNAIL;}
						
					$visibilityClass = 'albumvisible';
					if($albumArr[$i]['visibilitystatus'] == 'invisible'){$visibilityClass = 'albumvisible';}
					$newAlbumClass = '';
					if($_GET['newaid'] == $albumArr[$i]['aid']){unset($_GET['newaid']); $newAlbumClass = 'newalbum';}
					
					if($albumArr[$i]['views'] == 1){$albumArr[$i]['views'] = "(".$albumArr[$i]['views']." view)";}
					else{$albumArr[$i]['views'] = "(".$albumArr[$i]['views']." views)";}
					
					if($albumArr[$i]['commentscounter'] == 1){$albumArr[$i]['commentscounter'] = $albumArr[$i]['commentscounter']." comment";}
					else{$albumArr[$i]['commentscounter'] = $albumArr[$i]['commentscounter']." comments";}
					
					echo "<li id='view_album_".$albumArr[$i]['aid']."' class='view_albums ".$visibilityClass." ".$newAlbumClass."'>";
						echo "<img id='' class='view_albumcovers thumbsimages' src='".$coverThumb."'>";
						echo "<span id='view_name_".$albumArr[$i]['aid']."' class='view_names simple'>".strtoupper($albumArr[$i]['name'])."</span>";
						echo "<span id='view_category_".$albumArr[$i]['aid']."' class='view_categorys'>".$albumArr[$i]['categoryname']."</span>";
						echo "<span id='view_comments_".$albumArr[$i]['aid']."' class='view_comments'>".$albumArr[$i]['commentscounter']."</span>";
						echo "<span id='view_views_".$albumArr[$i]['aid']."' class='view_views'>".$albumArr[$i]['views']."</span>";
						echo "<span id='view_timestamp_".$albumArr[$i]['aid']."' class='view_timestamps'>Updated on ".convertTimeStamp($albumArr[$i]['lastupdatedtimestamp'],'reallylong')."</span>";
						echo "<div class='clear'></div>";
					echo "</li>";
				}//for
			}//if
			else
				{echo "<li class='view_covers_short_noalbums'>No albums to list</li>";}
			echo "</ul>";
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			break;
		default:
			break;
	}//switch
	
	$_SESSION['layout_useralbumcategories'] = $albumCategories;
	
	unset($dbobj);
}//get_album_list($listType,$uID)


function get_album_list_portfolio($userID,$username)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$tagArr = array();
	$dbVarsAlbums = array();
	$dbVarsCategories = array();
	$dbVarsCoverImages = array();

	$userID = (get_magic_quotes_gpc()) ? $userID : addslashes($userID);
	$userID = htmlentities($userID, ENT_QUOTES, "UTF-8");
	$userID = trim($userID);

	$selectAlbum = "SELECT *  FROM album WHERE uid='".$userID."' AND visibilitystatus='visible' ORDER BY name ASC; ";	
	$dbVarsAlbum = $dbobj->executeSelectQuery($selectAlbum);
	
	
	echo "<div id='display_albumsinfo'>";
	echo "<span id='display_albumsname' class='text_highlight text_highlight_bg'>albums</span>";
	echo"</div>";
	
	echo "<div class='display_albumsname_big  text_highlight text_highlight_bg'>albums</div>";


	echo "<ul id='view_albums_navigation'>";
	if($dbVarsAlbum['NUM_ROWS'] != 0)
	{	
		$selectCoverImages = "SELECT iid, fileurl FROM image WHERE uid='".$userID."' AND imagetype='albumcover'; ";
		$dbVarsCoverImages = $dbobj->executeSelectQuery($selectCoverImages);
	
		for($i=0; $i<$dbVarsCoverImages['NUM_ROWS']; $i++)
			{$coverImages[$dbVarsCoverImages['RESULT'][$i]['iid']] = $dbVarsCoverImages['RESULT'][$i]['fileurl'];}
				
		for($i=0; $i<$dbVarsAlbum['NUM_ROWS']; $i++)
		{
			$albumArr[$i]['aid'] = $dbVarsAlbum['RESULT'][$i]['aid'];
			$albumArr[$i]['tid'] = $dbVarsAlbum['RESULT'][$i]['tid'];
			$albumArr[$i]['name'] = $dbVarsAlbum['RESULT'][$i]['name'];
			$albumArr[$i]['commentscounter'] = $dbVarsAlbum['RESULT'][$i]['commentscounter'];
			$albumArr[$i]['description'] = nl2br($dbVarsAlbum['RESULT'][$i]['description']);
			$albumArr[$i]['embeddedvideos'] = $dbVarsAlbum['RESULT'][$i]['embeddedvideos'];
			
			$albumArr[$i]['imagesorder'] = $dbVarsAlbum['RESULT'][$i]['imagesorder'];
			
			$albumArr[$i]['coverid'] = $dbVarsAlbum['RESULT'][$i]['coverid'];
			$albumArr[$i]['visibilitystatus'] = $dbVarsAlbum['RESULT'][$i]['visibilitystatus'];
			$albumArr[$i]['views'] =  $dbVarsAlbum['RESULT'][$i]['views'];
			$albumArr[$i]['lastupdatedtimestamp'] = $dbVarsAlbum['RESULT'][$i]['lastupdatedtimestamp'];
			$albumArr[$i]['imageview'] = $dbVarsAlbum['RESULT'][$i]['imageview'];
			$albumArr[$i]['creationtimestamp'] = $dbVarsAlbum['RESULT'][$i]['creationtimestamp'];
			
			if($albumArr[$i]['description'] == '' || strtolower($albumArr[$i]['description']) == 'null')
				{$albumArr[$i]['description'] = '';}
	
			if( ($albumArr[$i]['imagesorder'] != '') || ($albumArr[$i]['imagesorder'] != NULL))
			{
				$albumArr[$i]['imagesorder'] = explode(':..::..:',$albumArr[$i]['imagesorder']);
				$albumArr[$i]['imagescount'] = count($albumArr[$i]['imagesorder']) - 1;
				
				if($albumArr[$i]['imagescount'] == 1){$albumArr[$i]['imagescount'] .= ' image';}
				else{$albumArr[$i]['imagescount'].=' images';}
			}
			else
			{
				$albumArr[$i]['imagescount'].=' images';
			}
	
			if(isset($categoryArr[$albumArr[$i]['tid']])){$albumArr[$i]['categoryname'] = $categoryArr[$albumArr[$i]['tid']]; }
				else{$albumArr[$i]['categoryname'] = ''; }
	
			if($albumArr[$i]['tid'] !=0 && $albumArr[$i]['tid'] != '' && strtolower($albumArr[$i]['tid']) != 'null')
			{
				$selectCategory = "SELECT * FROM tag WHERE tid='".$albumArr[$i]['tid']."' AND uid='".$userID."' AND type='album'; ";
				$dbVarsCategory = $dbobj->executeSelectQuery($selectCategory);
				$albumArr[$i]['categoryname'] = $dbVarsCategory['RESULT'][0]['name'];
			}//if
			
			if($albumArr[$i]['coverid'] != 0 && isset($coverImages[$albumArr[$i]['coverid']]) )
				{$coverThumb = './rdusers/'.$username."/".IMAGES_FOLDER_COVER_IMAGES."/".$coverImages[$albumArr[$i]['coverid']]; }
			else
				{$coverThumb = DEFAULT_BLANK_COVER_THUMBNAIL;}		
			
			if($albumArr[$i]['commentscounter'] == 1){$albumArr[$i]['commentscounter'] = $albumArr[$i]['commentscounter']." comment";}
					else{$albumArr[$i]['commentscounter'] = $albumArr[$i]['commentscounter']." comments";}
			
			if($albumArr[$i]['views'] == 1){$albumArr[$i]['views'] = "(".$albumArr[$i]['views']." view)";}
				else{$albumArr[$i]['views'] = "(".$albumArr[$i]['views']." views)";}
								
			echo "<li id='view_album_".$albumArr[$i]['aid']."' class='view_albums ".$visibilityClass." ".$newAlbumClass."'>";
				echo "<img id='' class='view_albumcovers thumbsimages' src='".$coverThumb."'>";
				echo "<span id='view_name_".$albumArr[$i]['aid']."' class='view_names simple'>".strtoupper($albumArr[$i]['name'])."</span>";
				echo "<span id='view_category_".$albumArr[$i]['aid']."' class='view_categorys'>".$albumArr[$i]['categoryname']."</span>";
				echo "<span id='view_imagecount_".$albumArr[$i]['aid']."' class='view_imagecounts'>".$albumArr[$i]['imagescount']."</span>";
				echo "<span id='view_comments_".$albumArr[$i]['aid']."' class='view_comments'>".$albumArr[$i]['commentscounter']."</span>";
				echo "<span id='view_views_".$albumArr[$i]['aid']."' class='view_views'>".$albumArr[$i]['views']."</span>";
				echo "<span id='view_timestamp_".$albumArr[$i]['aid']."' class='view_timestamps'>Updated on: ".convertTimeStamp($albumArr[$i]['lastupdatedtimestamp'],'reallylong')."</span>";
				echo "<div class='clear'></div>";
			echo "</li>";
			
		}//outer if
	}//if
	else
		{echo "<li class='view_covers_short_noalbums'>No albums to list</li>";}
	echo "<ul id='view_albums_navigation'>";
	echo "<div class='muchneededhight'></div>";
	
	
	unset($dbobj);
}//get_album_list_portfolio($userID)

function get_album_view($userID, $albumID, $presentationMode)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$tagArr = array();
	$dbVarsAlbums = array();
	$dbVarsCategories = array();
	$dbVarsCoverImages = array();

	$userID = (get_magic_quotes_gpc()) ? $userID : addslashes($userID);
	$userID = htmlentities($userID, ENT_QUOTES, "UTF-8");
	$userID = trim($userID);

	$albumID = (get_magic_quotes_gpc()) ? $albumID : addslashes($albumID);
	$albumID = htmlentities($albumID, ENT_QUOTES, "UTF-8");
	$albumID = trim($albumID);

	$selectAlbum = "SELECT album.aid, album.tid, album.name, album.commentscounter, album.description, album.coverid, album.visibilitystatus, album.views, album.lastupdatedtimestamp, album.imageview, album.creationtimestamp, album.embeddedvideos, user.albumcomments "
	 			. " FROM album, user WHERE album.uid='".$userID."' AND user.uid=album.uid AND album.aid='".$albumID."'; ";	
	$dbVarsAlbum = $dbobj->executeSelectQuery($selectAlbum);

	if($dbVarsAlbum['NUM_ROWS'] != 0)
	{
		$albumArr['aid'] = $dbVarsAlbum['RESULT'][0]['aid'];
		$albumArr['tid'] = $dbVarsAlbum['RESULT'][0]['tid'];
		$albumArr['name'] = $dbVarsAlbum['RESULT'][0]['name'];
		$albumArr['commentscounter'] = $dbVarsAlbum['RESULT'][0]['commentscounter'];
		$albumArr['description'] = nl2br($dbVarsAlbum['RESULT'][0]['description']);
		$albumArr['embeddedvideos'] = $dbVarsAlbum['RESULT'][0]['embeddedvideos'];
		
		$albumArr['coverid'] = $dbVarsAlbum['RESULT'][0]['coverid'];
		$albumArr['visibilitystatus'] = $dbVarsAlbum['RESULT'][0]['visibilitystatus'];
		$albumArr['views'] =  $dbVarsAlbum['RESULT'][0]['views'];
		$albumArr['lastupdatedtimestamp'] = $dbVarsAlbum['RESULT'][0]['lastupdatedtimestamp'];
		$albumArr['imageview'] = $dbVarsAlbum['RESULT'][0]['imageview'];
		$albumArr['creationtimestamp'] = $dbVarsAlbum['RESULT'][0]['creationtimestamp'];
		$userComments_album_status = $dbVarsAlbum['RESULT'][0]['albumcomments']; 
		
		if($albumArr['description'] == '' || strtolower($albumArr['description']) == 'null')
			{$albumArr['description'] = '';}

		if($albumArr['tid'] !=0 && $albumArr['tid'] != '' && strtolower($albumArr['tid']) != 'null')
		{
			$selectCategory = "SELECT * FROM tag WHERE tid='".$albumArr['tid']."' AND uid='".$userID."' AND type='album'; ";
			$dbVarsCategory = $dbobj->executeSelectQuery($selectCategory);
			$albumArr['categoryname'] = $dbVarsCategory['RESULT'][0]['name'];
		}//if
	unset($dbobj);
	}//outer if

	switch($presentationMode)
	{
		case 'demo':
			echo "<span class='refreshsection simple' id='refreshsection_album'>Refresh</span> <span id='demosection' class=''><b>Demostration View:</b> Displaying all album information.</span>";
			
			echo "<div id='display_albumnameinfo'>";
				if($albumArr['categoryname'] != '')
					{echo "Album: <span id='display_albumcategory'>".$albumArr['categoryname']."/</span>";}
				echo "<span id='display_albumname' class='red'>".$albumArr['name']."</span>";
			echo"</div>";

			if($albumArr['imageview'] == 'fullsize')
				{$imagesSize = 'fullsize';}
			else if($albumArr['imageview'] == 'thumbnails')
				{$imagesSize = 'thumbnails';}
			else if($albumArr['imageview'] == 'largethumbnails')
				{$imagesSize = 'largethumbnails';}
				
			echo "<ul class='display_albuminfo'>";
				echo "<li id='display_albumdescription'>"."<span class='lightgraybackground'>".$albumArr['description']."</span>"."</li>";
				if($albumArr['embeddedvideos'] != '' && strtolower($albumArr['embeddedvideos']) != 'null')
					{echo "<li id='display_albumembeddedvideos'>"."<span class=''>".$albumArr['embeddedvideos']."</span>"."</li>";}
			echo "</ul>";
			
			$albumImagesCounter = get_image_list($userID,$albumID,0,$imagesSize,'demo');
			
			echo "<br class='clear'/>";
				
			$albumArr['views'] = album_update_views($albumArr['views'], $albumArr['aid']); //UPDATE THE ALBUM VIEWS
			if($albumArr['views'] == 1){$albumArr['views'] = "(".$albumArr['views']." view)";}
			else{$albumArr['views'] = "(".$albumArr['views']." views)";}
			
			if($albumArr['commentscounter'] == 1){$albumArr['commentscounter'] = $albumArr['commentscounter']." comment";}
			else{$albumArr['commentscounter'] = $albumArr['commentscounter']." comments";}
			
			echo "<ul class='display_albuminfo'>";
				echo "<li id='display_albumcreated' class='display_albumcreated'>Created on: "."<span class='blackbackground'>".convertTimeStamp($albumArr['creationtimestamp'],'reallylong')."</span></li>";
				echo "<li id='display_albumupdated' class='display_albumupdated'>Updated on: "."<span class='blackbackground'>".convertTimeStamp($albumArr['lastupdatedtimestamp'],'reallylong')."</span></li>";
				echo "<li id='display_albumviews' class='display_albumviews'>"."<span class='blackbackground'>".$albumArr['views']."</span></li>";
				echo "<li id='display_albumcategory' class='display_albumcategory'>Filed Under: "."<span class='blackbackground'>".$albumArr['categoryname']."</span></li>";
				echo "<li id='display_albumvisibility' class='display_albumvisibility'>Album is "."<span class='blackbackground'>".$albumArr['visibilitystatus']."</span></li>";
				echo "<li id='display_albumimagesnumber' class='display_albumimagesnumber'>In this album: <span class='blackbackground'>".$albumImagesCounter." images</span>"."</li>";
				echo "<li id='display_albumcomments'><span class='blackbackground'>".$albumArr['commentscounter']."</span></li>";

			echo "</ul>";	
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			break;
			
		case 'live':
			
			echo "<div id='display_albumnameinfo'>";
			
				if($albumArr['categoryname'] != '')
					{echo "<span id='display_albumcategory'>".$albumArr['categoryname']."</span>"; echo "/";}
				echo "<span id='display_albumname' class='text_highlight text_highlight_bg'>".$albumArr['name']."</span>";
			echo"</div>";

			echo "<div class='display_albumname_big text_highlight text_highlight_bg'>".$albumArr['name']."</div>";
			
			if($albumArr['imageview'] == 'fullsize')
				{$imagesSize = 'fullsize';}
			else if($albumArr['imageview'] == 'thumbnails')
				{$imagesSize = 'thumbnails';}
			else if($albumArr['imageview'] == 'largethumbnails')
				{$imagesSize = 'largethumbnails';}
			
			if($albumArr['description'] != ''){
				echo "<ul class='display_albuminfo'>"; 
					echo "<li id='display_albumdescription'>"."<span class=''>".$albumArr['description']."</span>"."</li>";
				if($albumArr['embeddedvideos'] != '' && strtolower($albumArr['embeddedvideos']) != 'null')
					{echo "<li id='display_albumembeddedvideos'>"."<span class=''>".$albumArr['embeddedvideos']."</span>"."</li>";}
				echo "</ul>";
			}//
			
			$albumImagesCounter = get_image_list($userID,$albumID,0,$imagesSize,'live');
			
			
				
			$albumArr['views'] = album_update_views($albumArr['views'], $albumArr['aid']); //UPDATE THE ALBUM VIEWS
			if($albumArr['views'] == 1){$albumArr['views'] = "(".$albumArr['views']." view)";}
			else{$albumArr['views'] = "(".$albumArr['views']." views)";}
			
			echo "<ul class='display_albuminfo_footer'>";
				echo "<li id='display_albumcreated_".$albumArr['aid']."' class='display_albumcreated'>Created on: "."<span class='text_highlight text_highlight_bg'>".convertTimeStamp($albumArr['creationtimestamp'],'reallylong')."</span></li>";
				echo "<li id='display_albumupdated_".$albumArr['aid']."' class='display_albumupdated'>Updated on: "."<span class='text_highlight text_highlight_bg'>".convertTimeStamp($albumArr['lastupdatedtimestamp'],'reallylong')."</span></li>";
				echo "<li id='display_albumviews_".$albumArr['aid']."' class='display_albumviews'>"."<span class='text_highlight text_highlight_bg'>".$albumArr['views']."</span></li>";
				if($albumArr['categoryname']!='')
					{echo "<li id='display_albumcategory_".$albumArr['aid']."' class='display_albumcategory'>Filed Under: "."<span class='text_highlight text_highlight_bg'>".$albumArr['categoryname']."</span></li>";}
					
				echo "<li id='display_albumimagesnumber_".$albumArr['aid']."' class='display_albumimagesnumber'>In this album: <span class='text_highlight text_highlight_bg'>".$albumImagesCounter." images</span>"."</li>";
				
				if($userComments_album_status == 'enabled')
				{
					echo "<li id='display_albumcomments_".$albumArr['aid']."' class='display_albumcomments'><span class='text_highlight text_highlight_bg'>";
					echo "<span id='accounter_".$albumArr['aid']."'>".$albumArr['commentscounter']."</span>";
					if($albumArr['commentscounter'] == 1){echo " comment</span></li>";}
					else {echo " comments</span></li>";}
				}
			echo "</ul>";
			echo "<div class=''></div><div class='muchneededhight'></div>";
			break;
	}//switch
}//get_album_view($userID, $albumID)


function album_update_views($currentViewsCounter, $albumID)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);//CALLS THE CLASS CONSTRUCTOR AND CONNECTS TO THE DB WITH THE APPORPRIATE DB ACCOUNT
	$dbVars = array();
	
	$currentViewsCounter++;
	$query = "UPDATE album"
				." SET views='".$currentViewsCounter."'"
				." WHERE aid='".$albumID."'; ";
	$dbVars = $dbobj->executeUpdateQuery($query);
	
	unset($dbobj);
	return $currentViewsCounter;
}//album_update_views($currentViewsCounter, $albumID)

function album_update_comments_counter($albumID)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$dbVars = array();
	
	$queryCounter = "SELECT commentscounter FROM album WHERE aid='".$albumID."'; ";
	$dbVarsCounter = $dbobj->executeSelectQuery($queryCounter);
	
	$currentCommentsCounter = $dbVarsCounter['RESULT'][0]['commentscounter'];
	$currentCommentsCounter++;
	
	$query = "UPDATE album"
				." SET commentscounter='".$currentCommentsCounter."'"
				." WHERE aid='".$albumID."'; ";
	$dbVars = $dbobj->executeUpdateQuery($query);
	
	unset($dbobj);
	return $currentCommentsCounter;
}
?>