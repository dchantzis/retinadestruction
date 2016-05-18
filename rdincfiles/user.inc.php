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
			require("../rdincfiles/album.inc.php");
			require("../rdincfiles/blog.inc.php");
			require("../rdincfiles/image.inc.php");
			require("../rdincfiles/website.inc.php");
			get_user_overview('live',$_GET['uid']);
			break;
		default:
			//IMPORTANT TO HAVE NO ACTION
			break;
	}//switch
}//



function user_get($tokenKey,$tokenValue)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);//CALLS THE CLASS CONSTRUCTOR AND CONNECTS TO THE DB WITH THE APPORPRIATE DB ACCOUNT
	$userInfo = array();
	$query = NULL;


	switch($tokenKey)
	{
		case 'username':
			$username = $tokenValue;
			$username = (get_magic_quotes_gpc()) ? $username : addslashes($username);
			$username = htmlentities($username, ENT_QUOTES, "UTF-8");
			$username = trim($username);

			$username = "'".$username."'";
			$query = "SELECT * FROM user WHERE username=".$username."; ";
			break;
		case 'uid':
			$uid = $tokenValue;
			$uid = (get_magic_quotes_gpc()) ? $uid : addslashes($uid);
			$uid = htmlentities($uid, ENT_QUOTES, "UTF-8");
			$uid = trim($uid);

			$uid = "'".$uid."'";
			$query = "SELECT * FROM user WHERE uid=".$uid."; ";
			break;
		case 'email':
			$email = $tokenValue;
			$email = (get_magic_quotes_gpc()) ? $email : addslashes($email);
			$email = htmlentities($email, ENT_QUOTES, "UTF-8");
			$email = trim($email);

			$uid = "'".$email."'";
			$query = "SELECT * FROM user WHERE uid=".$email."; ";
			break;
		case 'all':
			$query = "SELECT user.uid, user.name, user.username, user.password, user.email, user.avatar, user.views, user.tags, "
				."user.gender, user.description, user.facebook, user.myspace, user.twitter, user.youtube, user.vimeo, user.newsletter, user.albumcomments, "
				."user.blogpostcomments, user.commentnotifications, user.visibilitystatus, user.accountstatus, user.registrationstatus, "
				."user.lastupdatedtimestamp, user.registrationtimestamp, website.title "
				."FROM user,website "
				."WHERE user.uid = website.uid AND user.registrationstatus = 'complete' AND user.visibilitystatus='visible' AND user.accountstatus='active' ORDER BY user.views DESC;";
			break;
		case 'featured':
			$query = "SELECT user.uid, user.name, user.username, user.password, user.email, user.avatar, user.views, user.tags, "
				."user.gender, user.description, user.facebook, user.myspace, user.twitter, user.youtube, user.vimeo, user.newsletter, user.albumcomments, "
				."user.blogpostcomments, user.commentnotifications, user.visibilitystatus, user.accountstatus, user.registrationstatus, "
				."user.lastupdatedtimestamp, user.registrationtimestamp, website.title "
				."FROM user,website "
				."WHERE user.uid = website.uid AND user.registrationstatus = 'complete' AND user.visibilitystatus='visible' AND user.accountstatus='active' ORDER BY user.views DESC LIMIT 0,5;";
				//."WHERE user.uid = website.uid AND user.registrationstatus = 'complete' AND user.visibilitystatus='visible' AND user.accountstatus='active' AND user.featured='true' ORDER BY user.views DESC;";
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
			for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
			{
				$userInfo[$i]['usertype'] = 'registereduser';
				$userInfo[$i]['uid'] = $dbVars['RESULT'][$i]['uid'];
				$userInfo[$i]['username'] = $dbVars['RESULT'][$i]['username'];
				$userInfo[$i]['email'] = $dbVars['RESULT'][$i]['email'];
				$userInfo[$i]['name'] = $dbVars['RESULT'][$i]['name'];
				$userInfo[$i]['gender'] = $dbVars['RESULT'][$i]['gender'];
				$userInfo[$i]['accountstatus'] = $dbVars['RESULT'][$i]['accountstatus'];
				$userInfo[$i]['visibilitystatus'] = $dbVars['RESULT'][$i]['visibilitystatus'];
				$userInfo[$i]['avatar'] = $dbVars['RESULT'][$i]['avatar'];
				$userInfo[$i]['featured'] = $dbVars['RESULT'][$i]['featured'];
				$userInfo[$i]['description'] = $dbVars['RESULT'][$i]['description'];
					if(strtolower($userInfo[$i]['description']) == 'null'){$userInfo[$i]['description'] = '';}
				$userInfo[$i]['views'] = $dbVars['RESULT'][$i]['views'];
				$userInfo[$i]['lastupdatedtimestamp'] = $dbVars['RESULT'][$i]['lastupdatedtimestamp'];
				$userInfo[$i]['registrationtimestamp'] = $dbVars['RESULT'][$i]['registrationtimestamp'];

				$userInfo[$i]['tags'] = $dbVars['RESULT'][$i]['tags'];
				$userInfo[$i]['tids'] = $dbVars['RESULT'][$i]['tags'];

				$userInfo[$i]['facebook'] = $dbVars['RESULT'][$i]['facebook'];
					if(strtolower($userInfo[$i]['facebook']) == 'null'){$userInfo[$i]['facebook'] = '';}
				$userInfo[$i]['myspace'] = $dbVars['RESULT'][$i]['myspace'];
					if(strtolower($userInfo[$i]['myspace']) == 'null'){$userInfo[$i]['myspace'] = '';}
				$userInfo[$i]['twitter'] = $dbVars['RESULT'][$i]['twitter'];
					if(strtolower($userInfo[$i]['twitter']) == 'null'){$userInfo[$i]['twitter'] = '';}
				$userInfo[$i]['youtube'] = $dbVars['RESULT'][$i]['youtube'];
					if(strtolower($userInfo[$i]['youtube']) == 'null'){$userInfo[$i]['youtube'] = '';}
				$userInfo[$i]['vimeo'] = $dbVars['RESULT'][$i]['vimeo'];
					if(strtolower($userInfo[$i]['vimeo']) == 'null'){$userInfo[$i]['vimeo'] = '';}

				$userInfo[$i]['newsletter'] = $dbVars['RESULT'][$i]['newsletter'];
				$userInfo[$i]['albumcomments'] = $dbVars['RESULT'][$i]['albumcomments'];

				$userInfo[$i]['albumcomments'] = $dbVars['RESULT'][$i]['albumcomments'];
				$userInfo[$i]['blogpostcomments'] = $dbVars['RESULT'][$i]['blogpostcomments'];
				$userInfo[$i]['commentnotifications'] = $dbVars['RESULT'][$i]['commentnotifications'];

				if(($tokenKey == 'all') || ($tokenKey == 'featured')){$userInfo[$i]['title'] = $dbVars['RESULT'][$i]['title'];}

			}//for

			if(($tokenKey != 'all') && ($tokenKey != 'featured')){$userInfo = $userInfo[0];}//

			//UPDATE THE 'views' FIELD
			user_update_views($userInfo['views'], $userInfo['uid']);

			unset($validator);
			unset($dbVars);
			unset($dbobj);
			return $userInfo;
		}//if
		else
		{
			return NULL;
		}//else
	}//
	else{return NULL;} //query was NULL


}//user_get($tokenKey,$tokenValue)


function get_user_overview($listType,$userID)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$tagArr = array();
	$dbVarsAlbums = array();
	$dbVarsCategories = array();
	$dbVarsCoverImages = array();

	if($listType == 'live')
	{
		$visibilitystatus = " AND visibilitystatus = 'visible' ";
	}//if

	//get user data
	$userData = user_get('uid',$userID);
	$websiteData = website_get('uid',$userID);

	$dbobj = new TBDBase(0);
	//get user avatar
	$queryAvatar = "SELECT image.fileurl AS avatar FROM user,image WHERE user.avatar = image.iid AND user.uid='".$userID."'; ";
	$dbVarsAvatar = $dbobj->executeSelectQuery($queryAvatar);
	if($dbVarsAvatar['NUM_ROWS'] == 0)
		{$userAvatar = './rdlayout/images/defaultuser.png';}
	else
		{$userAvatar = "./rdusers/".$userData['username']."/coverimages/".$dbVarsAvatar['RESULT'][0]['avatar'];}



	//get albums count and total views (only visible)
	$queryAlbums = "SELECT aid, views FROM album WHERE uid='".$userID."' ".$visibilitystatus."; ";
	$dbVarsAlbums = $dbobj->executeSelectQuery($queryAlbums);

	$albumsCounter = 0;
	$albumViews_total = 0;
	if($dbVarsAlbums['NUM_ROWS'] !=0 )
	{
		for($i=0; $i<$dbVarsAlbums['NUM_ROWS']; $i++)
		{
			$albumViews_total += (int)$dbVarsAlbums['RESULT'][$i]['views'];
			$albumsCounter++;
		}//for
	}//

	//get images count and total views (only visible)
	$queryImages = "SELECT iid, views FROM image WHERE uid='".$userID."'; ";
	$dbVarsImages = $dbobj->executeSelectQuery($queryImages);

	$imagesCounter = 0;
	$imagesViews_total = 0;
	if($dbVarsImages['NUM_ROWS'] !=0 )
	{
		for($i=0; $i<$dbVarsImages['NUM_ROWS']; $i++)
		{
			$imagesViews_total += (int)$dbVarsImages['RESULT'][$i]['views'];
			$imagesCounter++;
		}//for
	}//


	//get blog posts count and total views (only visible)
	$queryPosts = "SELECT pid, views FROM posts WHERE uid='".$userID."' ".$visibilitystatus."; ";
	$dbVarsPosts = $dbobj->executeSelectQuery($queryPosts);

	$postsCounter = 0;
	$postsViews_total = 0;
	if($dbVarsPosts['NUM_ROWS'] !=0 )
	{
		for($i=0; $i<$dbVarsPosts['NUM_ROWS']; $i++)
		{
			$postsViews_total += (int)$dbVarsPosts['RESULT'][$i]['views'];
			$postsCounter++;
		}//for
	}//


	//get the user artists category
	$queryUserTags = "SELECT tid, name FROM tag WHERE type='profile'; ";
	$dbVarsUserTags = $dbobj->executeSelectQuery($queryUserTags);
	if($dbVarsUserTags['NUM_ROWS'] !=0 )
	{
		for($i=0; $i<$dbVarsUserTags['NUM_ROWS']; $i++)
		{
			$userTags[$dbVarsUserTags['RESULT'][$i]['tid']] = $dbVarsUserTags['RESULT'][$i]['name'];
		}//for
	}//

	$userData['tids'] = $userData['tags'];
	if( ($userData['tags'] != 'NULL') || ($userData['tags'] != '') )
	{
		$userTagNames = explode(':..::..:',$userData['tags']);
		if( $userTagNames[(count($userTagNames)-1)] == ''){ unset($userTagNames[(count($userTagNames)-1)]); }

		$userTagIDs = $userTagNames;

		$userTagsHTML = '';
		for($f=0; $f<count($userTagNames); $f++)
		{
			$userTagsHTML .= "<span class='user_tags' id='user_tag_".$userTagIDs[$f]."'>".$userTags[$userTagNames[$f]]."</span>";

			if($f != (count($userTagNames)-1) ){$userTagsHTML .= ", ";}
		}//
		$userData['tags'] = $userTagsHTML;

	}//if

	switch($listType)
	{
		case 'overview':
			echo "<ul class='display_portfolio_info'>";
				echo "<li class='display_portfolio_info_avatar'><img src='".$userAvatar."' class='thumbsimages' width='300' height='200' /></li>";

				echo "<li class='display_portfolio_info_otherinfo'>";

					echo "<ul class='display_portfolio_info_otherinfo_ul'>";
						echo "<li><b>Username:</b> "."<span id='' class='text_highlight text_highlight_bg'>".$userData['username']."</span>"."</li>";
						echo "<li><b>Website URL:</b> "."<a href='".SERVER_NAME."?".$userData['username']."' class='blacklink'>".SERVER_NAME."?".$userData['username']."</a>"."</li>";
						echo "<li><b>Name: </b>"."<span id='' class='text_highlight text_highlight_bg'>".$userData['name']."</span>"."</li>";
						echo "<li><b>E-Mail: </b>"."<span id='' class='text_highlight text_highlight_bg'>".$userData['email']."</span>"."</li>";
						echo "<li><b>Gender: </b>"."<span id='' class='text_highlight text_highlight_bg'>".$userData['gender']."</span>"."</li>";
						echo "<li><b>Description: </b><br/><span class='profile_description'>".nl2br($userData['description'])."</span></li>";
						echo "<li><b>Account is: </b><span class='profile_visibilitystatus'>".$userData['visibilitystatus']."</span></li>";

						echo "<li class='liseperator'></li>";
						echo "<li><b>User filed under: </b> ".$userData['tags']." </li>";
						echo "<li><b>Joined on: </b>"."<span id='' class='text_highlight text_highlight_bg'>".convertTimeStamp($userData['registrationtimestamp'],'reallylong')."</span>"."</li>";
						echo "<li class='liseperator'></li>";
						echo "<li><b>My other accounts: </b></li>";
						if($userData['facebook'] != '')
							{echo "<li><b>Facebook:</b> "."<a href='http://www.facebook.com/".$userData['facebook']."' class='blacklink'>".$userData['facebook']."</a>"."</li>";}
						if($userData['myspace'] != '')
							{echo "<li><b>MySpace:</b> "."<a href='http://www.myspace.com/".$userData['myspace']."' class='blacklink'>".$userData['myspace']."</a>"."</li>";}
						if($userData['twitter'] != '')
							{echo "<li><b>Twitter:</b> "."<a href='http://www.twitter.com/".$userData['twitter']."' class='blacklink'>".$userData['twitter']."</a>"."</li>";}
						if($userData['youtube'] != '')
							{echo "<li><b>Youtube:</b> "."<a href='http://www.youtube.com/".$userData['youtube']."' class='blacklink'>".$userData['youtube']."</a>"."</li>";}
						if($userData['vimeo'] != '')
							{echo "<li><b>Vimeo:</b> "."<a href='http://www.vimeo.com/".$userData['vimeo']."' class='blacklink'>".$userData['vimeo']."</a>"."</li>";}

						echo "<li class='liseperator'></li>";
						echo "<li><b>Comments settings</b></li>";
						echo "<li><b>Album comments:</b> ".$userData['albumcomments']."</li>";
						echo "<li><b>Blog entries comments:</b> ".$userData['blogpostcomments']."</li>";
						echo "<li><b>E-Mail notifications:</b> ".$userData['commentnotifications']."</li>";

						echo "<li class='liseperator'></li>";
						echo "<li><b>My account in numbers: </b></li>";
						echo "<li><b>".$albumsCounter."</b> albums</li>";
						echo "<li><b>".$imagesCounter."</b> images</li>";
						echo "<li><b>".$postsCounter."</b> news entries</li>";
						echo "<li><b>".$websiteData['views']."</b> website views</li>";
						echo "<li><b>".$albumViews_total."</b> album views</li>";
						echo "<li><b>".$imagesViews_total."</b> image views</li>";
						echo "<li><b>".$postsViews_total."</b> blog entries views</li>";

					echo "</ul>";

				echo "</li>";
			echo "</ul>";
			echo "<div class='clear'></div>";
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			break;
		case 'live':

			echo "<div id='display_info'>";
				echo "<span id='display_infoname' class='text_highlight text_highlight_bg'>about</span>";
			echo"</div>";

			echo "<div class='display_infoname_big  text_highlight text_highlight_bg'>about me</div>";

			echo "<div class='display_portfolio_info_container'>";
			echo "<ul class='display_portfolio_info'>";

				echo "<li class='display_portfolio_avatar_description_container'>";
					echo "<div class='display_portfolio_description'>".nl2br($userData['description'])."</div>";
					echo "<div class='display_portfolio_info_avatar'><img src='".$userAvatar."' class='thumbsimages' width='300' height='200' /></div>";
				echo "</li>";

				echo "<li class='display_portfolio_info_otherinfo'>";
					echo "<ul class='display_portfolio_info_otherinfo_ul'>";
						echo "<li><span id='' class=''>";
                        	echo "<a href='mailto:".$userData['email']."' title='e-mail me' class='userSpecific'>".$userData['email']."</a>";
                        echo "</span><li>";
						echo "<li>";
							echo "Name: "."<span id='' class='display_portfolio_name'>".$userData['name']."</span>, ";
							echo "Username: "."<span id='' class='display_portfolio_username'>".$userData['username']."</span>, ";
							echo "Gender: "."<span id='' class='display_portfolio_gender'>".$userData['gender']."</span>";
						echo "</li>";
						echo "<li>User filed under: ".$userData['tags']." </li>";

						/*
						echo "<li class='liseperator2'></li>";
						echo "<li><b>Account in numbers: </b></li>";
						echo "<li>";
							echo "<span class='text_highlight text_highlight_bg'>".$albumsCounter."</span> albums, ";
							echo "<span class='text_highlight text_highlight_bg'>".$imagesCounter."</span> images, ";
							echo "<span class='text_highlight text_highlight_bg'>".$postsCounter."</span> news entries ";
						echo "<li>";
						echo "<li>";
							echo "<span class='text_highlight text_highlight_bg'>".$websiteData['views']."</span> website views, ";
							echo "<span class='text_highlight text_highlight_bg'>".$albumViews_total."</span> album views, ";
							echo "<span class='text_highlight text_highlight_bg'>".$imagesViews_total."</span> image views, ";
							echo "<span class='text_highlight text_highlight_bg'>".$postsViews_total."</span> news entries views";
						echo "</li>";
						*/
						echo "<li>";
						if($userData['facebook'] != '')
							{echo "Facebook: "."<a href='http://www.facebook.com/".$userData['facebook']."' class='userSpecific'>".$userData['facebook']."</a>, ";}
						if($userData['myspace'] != '')
							{echo "MySpace: "."<a href='http://www.myspace.com/".$userData['myspace']."' class='userSpecific'>".$userData['myspace']."</a>, ";}
						if($userData['twitter'] != '')
							{echo "Twitter: "."<a href='http://www.twitter.com/".$userData['twitter']."' class='userSpecific'>".$userData['twitter']."</a>, ";}
						if($userData['youtube'] != '')
							{echo "YouTube: "."<a href='http://www.youtube.com/".$userData['youtube']."' class='userSpecific'>".$userData['youtube']."</a>";}
						if($userData['vimeo'] != '')
							{echo "Vimeo: "."<a href='http://www.vimeo.com/".$userData['vimeo']."' class='userSpecific'>".$userData['vimeo']."</a>";}
						echo "</li>";

						echo "<li>Joined on: <span id='' class='display_portfolio_join'>".convertTimeStamp($userData['registrationtimestamp'],'reallylongwithouttime')."</span></li>";

					echo "</ul>";

				echo "</li>";
			echo "</ul>";
			echo "<div class='clear'></div>";

			echo "<div class='display_portfolio_website'>Website available at: "."<a href='" . SERVER_NAME . "'?".$userData['username']."' class='whitelink'>www.retinadestruction.com?".$userData['username']."</a>"."</div>";

			echo "</div>";
			echo "<div class='clear'></div>";
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			break;
	}//switch($listType)

	unset($dbobj);
}//get_user_overview($listType,$userID)



function user_update_views($currentViewsCounter, $userID)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);//CALLS THE CLASS CONSTRUCTOR AND CONNECTS TO THE DB WITH THE APPORPRIATE DB ACCOUNT
	$dbVars = array();

	$currentViewsCounter++;
	$query = "UPDATE user"
				." SET views='".$currentViewsCounter."'"
				." WHERE uid='".$userID."'; ";
	$dbVars = $dbobj->executeUpdateQuery($query);

	unset($dbobj);
}//user_update_views($currentViewsCounter, $userID)

?>
