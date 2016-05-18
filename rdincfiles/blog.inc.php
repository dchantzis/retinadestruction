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
			get_blog_list('edit_short',$_SESSION['user']['uid']);
			break;
		case 2:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			require("../rdincfiles/image.inc.php");
			get_blog_list('live',$_GET['uid']);
			break;
		case '3':
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			require("../rdincfiles/image.inc.php");

			get_blog_rssfeed($_GET['username']);
			break;
		default:
			//IMPORTANT TO HAVE NO ACTION
			break;
	}//switch
}//

function blog_get($tokenKey,$tokenValue)
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
		case 'pid':
			$pid = $tokenValue;
			$pid = (get_magic_quotes_gpc()) ? $pid : addslashes($pid);
			$pid = htmlentities($pid, ENT_QUOTES, "UTF-8");
			$pid = trim($pid);

			$pid = "'".$pid."'";
			$query = "SELECT * FROM posts WHERE pid=".$pid."; ";
			$queryBlogCategories = "SELECT * FROM tag WHERE type='post'; ";
			break;
		case 'uid':
			$uid = $tokenValue;
			$uid = (get_magic_quotes_gpc()) ? $uid : addslashes($uid);
			$uid = htmlentities($uid, ENT_QUOTES, "UTF-8");
			$uid = trim($uid);

			$uid = "'".$uid."'";
			$query = "SELECT * FROM posts WHERE uid=".$uid." ORDER BY creationtimestamp DESC; ";

			$queryBlogCategories = "SELECT * FROM tag WHERE uid=".$uid." AND type='post'; ";
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
			/*
			$dbVars2 = $dbobj->executeSelectQuery($queryBlogCategories);
			if($dbVars2['NUM_ROWS'] != 0)
			{
				for($j=0; $j<$dbVars2['NUM_ROWS']; $j++)
				{
					$tagInfo[$dbVars2['RESULT'][$j]['tid']] = $dbVars2['RESULT'][$j]['name'];
				}//for
			}//if
			*/
			$dbVars2 = $dbobj->executeSelectQuery($queryBlogCategories);
			$tagNameString = '';
			if($dbVars2['NUM_ROWS'] != 0)
			{
				for($j=0; $j<$dbVars2['NUM_ROWS']; $j++)
					{$categoryArr[$dbVars2['RESULT'][$j]['tid']] = $dbVars2['RESULT'][$j]['name']; $tagNameString.=$dbVars2['RESULT'][$j]['name'].',';}
				$_SESSION['blogTagNameString'] = $tagNameString;
			}//if

				for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
				{
					$postInfo[$i]['pid'] = $dbVars['RESULT'][$i]['pid'];
					$postInfo[$i]['uid'] = $dbVars['RESULT'][$i]['uid'];
					$postInfo[$i]['headline'] = $dbVars['RESULT'][$i]['headline'];
					$postInfo[$i]['body'] = $dbVars['RESULT'][$i]['body'];
					$postInfo[$i]['embeddedvideos'] = $dbVars['RESULT'][$i]['embeddedvideos'];
					$postInfo[$i]['type'] = $dbVars['RESULT'][$i]['type'];
					if($dbVars['RESULT'][$i]['tags'] == '0'){$dbVars['RESULT'][$i]['tags']='';}
					$postInfo[$i]['tags'] = $dbVars['RESULT'][$i]['tags'];
					$postInfo[$i]['tids'] = $dbVars['RESULT'][$i]['tags'];
					$postInfo[$i]['images'] = $dbVars['RESULT'][$i]['images'];
					$postInfo[$i]['views'] = $dbVars['RESULT'][$i]['views'];
					$postInfo[$i]['imageview'] = $dbVars['RESULT'][$i]['imageview'];
					$postInfo[$i]['visibilitystatus'] = $dbVars['RESULT'][$i]['visibilitystatus'];
					$postsArr[$i]['commentscounter'] = $dbVars['RESULT'][$i]['commentscounter'];
					$postInfo[$i]['imagescounter'] = $dbVars['RESULT'][$i]['imagescounter'];
					$postInfo[$i]['lastupdatedtimestamp'] = $dbVars['RESULT'][$i]['lastupdatedtimestamp'];
					$postInfo[$i]['creationtimestamp'] = $dbVars['RESULT'][$i]['creationtimestamp'];


					if( $postInfo[$i]['tags'] != '0' )
					{
						$postInfoTags_temp = explode('::..::',$postInfo[$i]['tags']);
						if( $postInfoTags_temp[(count($postInfoTags_temp))-1] == '' ){unset($postInfoTags_temp[(count($postInfoTags_temp)-1)]);}

						$postInfoTags_temp_final = '';
						for($p=0; $p<count($postInfoTags_temp); $p++)
						{
							if($p == (count($postInfoTags_temp)-1)){$postInfoTags_temp_final .= $categoryArr[$postInfoTags_temp[$p]];}
							else{$postInfoTags_temp_final .= $categoryArr[$postInfoTags_temp[$p]].",";}
						}//for
						$postInfo[$i]['tags'] = $postInfoTags_temp_final;
					}//if



				}//for


			//for($i=0; $i<$dbVarsCoverImages['NUM_ROWS']; $i++)
				//{$coverImag/es[$dbVarsCoverImages['RESULT'][$i]['iid']] = $dbVarsCoverImages['RESULT'][$i]['fileurl'];}

			if($tokenKey == 'pid')
			{
				$postInfo = $postInfo[0];

				reset($postInfo); while (list($key, $val) = each ($postInfo))
					{ if(($val == NULL)||(strtolower($val) == 'null')){$postInfo[$key] = '';} else {$postInfo[$key] = $val;}}


				$selectBlogImages = "SELECT iid, fileurl FROM image WHERE uid='".$postInfo['uid']."' AND pid='".$postInfo['pid']."' AND imagetype='regular'; ";
				$dbVarsImages = $dbobj->executeSelectQuery($selectBlogImages);
				for($i=0; $i<$dbVarsImages['NUM_ROWS']; $i++)
					{$blogImages[$dbVarsImages['RESULT'][$i]['iid']] = $dbVarsImages['RESULT'][$i]['fileurl'];}
			}//if

			unset($validator);
			unset($dbVars);
			return $postInfo;
		}//if
		else
		{
			//no result found
		}//else
	}//
	else{} //query was NULL

}//blog_get($token)



function get_blog_list($listType,$userID)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(1);
	$tagArr = array();
	$dbVarsAlbums = array();
	$dbVarsCategories = array();
	$dbVarsCoverImages = array();
	$blogNavigationDirection = ''; $blogNavigationDirection = $_GET['blognavidir']; unset($_GET['blognavidir']);
	$limit = $_GET['l'];
	if(isset($_GET['tid'])){$tagID = $_GET['tid']; $tagSQLparameter = " AND posts.tags LIKE '%".$tagID."::..::%'"; $tagHTMLgetParameter = '&tid='.$tagID."&tname=".$_GET['tname'];}
	else{$tagSQLparameter = ''; $tagHTMLgetParameter = '';}

	$currentPage = (int)$_GET['cp'];
	$postsPerPage = 5;

	if($blogNavigationDirection == '')
		{$selectPosts = "SELECT * FROM posts WHERE uid='".$userID."' ORDER BY creationtimestamp DESC; ";}
	else
	{
		//DISPLAY LIVE!
		$selectPostsAll = "SELECT count(*) AS counter FROM posts WHERE uid='".$userID."' AND visibilitystatus='visible' ".$tagSQLparameter." ORDER BY creationtimestamp DESC;";
		$dbVarsPostsAll = $dbobj->executeSelectQuery($selectPostsAll);
		$postsTotalNumber = $dbVarsPostsAll['RESULT'][0]['counter'];

		if (is_float(($postsTotalNumber/$postsPerPage)) )
		{
			$pagesNum = '';
			$pagesNum .= ($postsTotalNumber/$postsPerPage);
			$resultsNum = explode('.',$pagesNum);
			if(isset($resultsNum[1])){$pagesNum = ((int)$resultsNum[0]) + 1;}
		}
		else
		{
			$pagesNum = $postsTotalNumber/$postsPerPage;
		}

		$currentLimit = $limit;
		$currentPage = (int)$currentPage;
		$previousPage = $currentPage-1; if($currentPage == -1){$currentPage = 0;}
		$nextPage = $currentPage+1;


		$nextLimit = ($currentPage * $postsPerPage);
		$prevLimit = $nextLimit-($postsPerPage*2); if($prevLimit == $postsPerPage+1){$prevLimit=0;} if($prevLimit == $postsPerPage+1){$prevLimit=0;}
		//$prevLimit = ($previousPage * $postsPerPage) + 1; if($prevLimit == $postsPerPage+1){$prevLimit=0;} //if($currentPage == $pagesNum){$prevLimit = $currentLimit - $postsPerPage;}

		if($limit != 0)
			{$nextButton = "<span id='navi_blog_navigate_next' class='navi_blog_navigate' rel='&l=".$prevLimit."&cp=".$previousPage.$tagHTMLgetParameter."' >next &gt;</span>";}

		for($k=0; $k<$pagesNum; $k++)
		{
			$pageNum = $k+1;
			$newLimit = ($k * $resultsPerPage)+1; if($newLimit == 1){$newLimit=0;}
		}
		if($currentPage != $pagesNum)
			{$previousButton = "<span id='navi_blog_navigate_previous' class='navi_blog_navigate' rel='&l=".$nextLimit."&cp=".$nextPage.$tagHTMLgetParameter."' >&lt; previous</span>";}

		$selectPosts = "SELECT posts.pid, posts.tags, posts.headline, posts.body, posts.embeddedvideos, posts.type, posts.tags, posts.commentscounter, posts.visibilitystatus, posts.imageview, posts.views, posts.lastupdatedtimestamp, posts.creationtimestamp, user.blogpostcomments "
					."FROM posts, user WHERE user.uid=posts.uid AND posts.uid='".$userID."' AND posts.visibilitystatus='visible' ".$tagSQLparameter." ORDER BY posts.creationtimestamp DESC LIMIT ".$limit.",".$postsPerPage."; ";
	}//outer else
	$dbVarsPosts = $dbobj->executeSelectQuery($selectPosts);

	if($dbVarsPosts['NUM_ROWS'] != 0)
	{

		$selectCategories = "SELECT * FROM tag WHERE uid='".$userID."' AND type='post' ORDER BY tid ASC; ";
		$dbVarsCategories = $dbobj->executeSelectQuery($selectCategories);

		if($dbVarsCategories['NUM_ROWS'] != 0)
		{
			$blogCategories = '';
			for($j=0; $j<$dbVarsCategories['NUM_ROWS']; $j++)
			{
				$categoryArr[$dbVarsCategories['RESULT'][$j]['tid']] = $dbVarsCategories['RESULT'][$j]['name'];
				$blogCategories .= $dbVarsCategories['RESULT'][$j]['name']."::";
			}//
		}//inner if

		for($i=0; $i<$dbVarsPosts['NUM_ROWS']; $i++)
		{
			$postsArr[$i]['pid'] = $dbVarsPosts['RESULT'][$i]['pid'];
			$postsArr[$i]['tags'] = $dbVarsPosts['RESULT'][$i]['tags'];
			$postsArr[$i]['headline'] = $dbVarsPosts['RESULT'][$i]['headline'];

			$postsArr[$i]['body'] = nl2br($dbVarsPosts['RESULT'][$i]['body']);
				if($postsArr[$i]['body'] == 'NULL'){$postsArr[$i]['body'] = '-';}

			$postsArr[$i]['embeddedvideos'] = $dbVarsPosts['RESULT'][$i]['embeddedvideos'];

			$postsArr[$i]['type'] = $dbVarsPosts['RESULT'][$i]['type'];
			$postsArr[$i]['tags'] = $dbVarsPosts['RESULT'][$i]['tags'];
			$postsArr[$i]['tids'] = $dbVarsPosts['RESULT'][$i]['tags'];

			$postsArr[$i]['commentscounter'] = $dbVarsPosts['RESULT'][$i]['commentscounter'];

			$postsArr[$i]['visibilitystatus'] = $dbVarsPosts['RESULT'][$i]['visibilitystatus'];
			$postsArr[$i]['imageview'] = $dbVarsPosts['RESULT'][$i]['imageview'];
			$postsArr[$i]['views'] =  $dbVarsPosts['RESULT'][$i]['views'];
			$postsArr[$i]['lastupdatedtimestamp'] = $dbVarsPosts['RESULT'][$i]['lastupdatedtimestamp'];
			$postsArr[$i]['creationtimestamp'] = $dbVarsPosts['RESULT'][$i]['creationtimestamp'];
			$userComments_blog_status = $dbVarsPosts['RESULT'][$i]['blogpostcomments'];

			if( ($postsArr[$i]['tags'] != NULL) && (strtolower($postsArr[$i]['tags']) != 'null') && ($postsArr[$i]['tags'] != '') && ($postsArr[$i]['tags'] !=0) )
			{
				$postInfoTags_temp = explode('::..::',$postsArr[$i]['tags']);
				if( $postInfoTags_temp[(count($postInfoTags_temp))-1] == '' ){unset($postInfoTags_temp[(count($postInfoTags_temp)-1)]);}

				$postInfoTags_temp_final = '';
				for($p=0; $p<count($postInfoTags_temp); $p++)
				{
					if($p == (count($postInfoTags_temp)-1)){$postInfoTags_temp_final .= $categoryArr[$postInfoTags_temp[$p]];}
					else{$postInfoTags_temp_final .= $categoryArr[$postInfoTags_temp[$p]].",";}
				}//for
				$postsArr[$i]['tags'] = $postInfoTags_temp_final;
			}//if

		}//for

	}//outer if

	$selectUser = "SELECT username, email, name FROM user WHERE uid='".$userID."'; ";
	$dbVarsUser = $dbobj->executeSelectQuery($selectUser);
	$username = $dbVarsUser['RESULT'][0]['username'];
	$email = $dbVarsUser['RESULT'][0]['email'];
	$name = $dbVarsUser['RESULT'][0]['name']; if($name == ''){$name = $username;}

	echo "<span id='user_username' class='displaynone'>".$username."</span>";
    echo "<span id='user_email' class='displaynone'>".$email."</span>";

	switch($listType)
	{
		case 'edit_short':

			echo "<div id='blog_create_button' class='simple'><span class='blackbackground'>+</span> Add New Blog Entry</div><br />";
			echo "<ul id='account_blog_navigation'>";
				if(count($postsArr) != 0)
				{
					for($i=0; $i<count($postsArr); $i++)
					{
						$visibilityClass = 'blogvisible';
						if($postsArr[$i]['visibilitystatus'] == 'invisible'){$visibilityClass = 'bloginvisible';}

						$newPostClass = '';
						if($_GET['newpid'] == $postsArr[$i]['pid']){unset($_GET['newpid']); $newPostClass = 'newpost';}

						echo "<li id='account_blog_".$postsArr[$i]['pid']."' class='account_blogs ".$newPostClass." ".$visibilityClass."'>";
							echo "<span id='blog_container_".$postsArr[$i]['pid']."' class='blog_containers'>";
								echo "<span id='blog_delete_".$postsArr[$i]['pid']."' class='blog_deletes'>x</span>";
								echo "<span id='blog_visibility_".$postsArr[$i]['pid']."' class='blog_visibilitys ".$visibilityClass."'>".$postsArr[$i]['visibilitystatus']."</span>";
								echo "<span id='blog_comments_".$postsArr[$i]['pid']."' class='blog_comments'>Comments</span>";
								echo "<span class='blog_timestamp'>".convertTimeStamp($postsArr[$i]['creationtimestamp'],'reallyshort')."</span>";
								if($postsArr[$i]['type'] == 'imagesupdate')
									{echo "<span id='blog_headline_".$postsArr[$i]['pid']."' class='blog_headlines'>".strtoupper($postsArr[$i]['headline'])."</span>";}
								else
									{echo "<span id='blog_headline_".$postsArr[$i]['pid']."' class='blog_headlines'>".strtoupper($postsArr[$i]['headline'])."</span>";}

							echo "</span>";
							echo "<span id='blog_container_deleteoptions_".$postsArr[$i]['pid']."' class='blog_container_deleteoptionss displaynone'>";
								echo "<span class='highlight_color'>Delete this post?</span>";
								echo "<span id='delete_blog_yes_".$postsArr[$i]['pid']."' class='togglers'>yes</span>";
								echo "<span id='delete_blog_no_".$postsArr[$i]['pid']."' class='togglers'>no</span>";
							echo "</span>";
						echo "</li>";
					}//for
				}//
				else
				{echo "<li class='view_covers_short_noalbums'>No blog entries to list</li>";}

			echo "</ul>";
			echo "<div class='clear'></div>";
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			break;
		case 'live':

			echo "<div id='display_bloginfo'>";
				echo "<span id='display_blogcategory'>news</span>";
				echo "/";
				echo "<span id='display_blogname' class='text_highlight text_highlight_bg'>latest news</span>";
				if(isset($_GET['tname']))
					{echo "/"."<span id='display_blogname' class='text_highlight text_highlight_bg'>"."<i>".$_GET['tname']."</i>"."</span>";}
			echo"</div>";

			echo "<div class='display_blogname_big  text_highlight text_highlight_bg'>news</div>";

			if($postsTotalNumber != 0)
			{
				echo "<ul id='blog_entrys'>";

					for($i=0; $i<count($postsArr); $i++)
					{
						$showEntry = false;
						if($postsArr[$i]['visibilitystatus'] != 'invisible')
						{
							echo "<li class='blog_entry_li' id='blog_entry_".$postsArr[$i]['pid']."'>";

								echo "<div class='blog_entry_headline'>";
									echo "<span class='blog_entry_timestamp'>".convertTimeStamp($postsArr[$i]['creationtimestamp'],'reallylong')."</span>";
									echo "<span class='blog_entry_title'>".$postsArr[$i]['headline']."</span>";
									//echo "<span class='clear'></span>";
								echo "</div>";

								//echo "<div class='clear'></div>";

								echo "<div class='blog_entry_body'>";
									echo "<div class='blog_entry_message'>".$postsArr[$i]['body']."</div>";
									if($postsArr[$i]['embeddedvideos'] != '' && strtolower($postsArr[$i]['embeddedvideos']) != 'null')
										{echo "<div class='blog_entry_embeddedvideos'>".$postsArr[$i]['embeddedvideos']."</div>";}
									echo "<div class='blog_entry_images'>";
										$_GET['tempid'] = $userID;
										get_image_list($userID,'',$postsArr[$i]['pid'],$postsArr[$i]['imageview'],'live');
										unset($_GET['tempid']);
									echo "</div>";
								echo "</div>";
								//echo "<div class='clear'></div>";


								$postTags = $postsArr[$i]['tags'];
								if($postTags != '0')
								{
									$postTagsNames = explode(',',$postsArr[$i]['tags']);
									$postTagIDs = explode('::..::',$postsArr[$i]['tids']);
									$postTagsHTML = '';
									for($f=0; $f<count($postTagsNames); $f++)
									{
										$postTagsHTML .= "<span class='postentry_tags' id='postentry_tag_".$postTagIDs[$f]."'>".$postTagsNames[$f]."</span>";

										if($f != (count($postTagsNames)-1) ){$postTagsHTML .= ", ";}
									}//
									$postTags = $postTagsHTML;
								}//if

								echo "<ul class='blog_entry_footer'>";
									echo "<li class='blog_entry_footer_posted'>Posted by: "."<span class='text_highlight text_highlight_bg'>".$name."</span></li>";

									if($postTags != '0')
										{echo "<li class='blog_entry_filed'>Filed under: <span class='text_highlight text_highlight_bg'>".$postTags."</span>"."</li>";}

									$postsArr[$i]['views'] = blog_update_views($postsArr[$i]['views'], $postsArr[$i]['pid']);
									if($postsArr[$i]['views'] == 1){$postsArr[$i]['views'] = "(".$postsArr[$i]['views']." view)";}
									else{$postsArr[$i]['views'] = "(".$postsArr[$i]['views']." views)";}
									echo "<li class='blog_entry_views'>"."<span class='text_highlight text_highlight_bg'>".$postsArr[$i]['views']."</span></li>";

									if($userComments_blog_status == 'enabled')
									{
									echo "<li class='blog_entry_comments' id='blog_entry_comments_".$postsArr[$i]['pid']."'><span class='text_highlight text_highlight_bg'>";

										if($postsArr[$i]['commentscounter'] == 1)
											{echo "<span id='pccounter_".$postsArr[$i]['pid']."'>".$postsArr[$i]['commentscounter']."</span>"." comment";}
										else
											{echo "<span id='pccounter_".$postsArr[$i]['pid']."'>".$postsArr[$i]['commentscounter']."</span>"." comments";}

									echo "</span></li>";
									}//if
								echo "</ul>";
								//cho "<div class='clear'></div>";
							echo "</li>";
							echo "<div id='viewalbumimagespresentationtype_".$postsArr[$i]['pid']."' class='displaynone'>".$postsArr[$i]['imageview']."</div>";

							$entriesOnDisplayIDs .= $postsArr[$i]['pid']."::::";
							$counter++;

						}//if
					}//for
				echo "</ul>";
				echo "<div id='entriesOnDisplayID' class='displaynone'>".$entriesOnDisplayIDs."</div>";
			}//if
			else
			{
				echo "<div class='blog_noentries'>No entries to display.</div>";
			}//
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			if($postsTotalNumber != 0){echo "<div class='blog_navi_button_container'>".$previousButton.$nextButton."</div>";}
			echo "<div class='clear'></div>";
			echo "<div class='clear'></div><div class='muchneededhight'></div>";
			break;
		default:
			break;
	}//switch

	$_SESSION['layout_userblogcategories'] = $blogCategories;
	unset($dbobj);
}//get_blog_list($listType,$userID)


function get_blog_rssfeed($username)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$userInfo = array();
	$query = NULL;

	$query = "SELECT user.uid, user.email, website.title FROM user,website WHERE user.uid = website.uid AND user.username='".$username."'; ";
	$dbVarsUser = $dbobj->executeSelectQuery($query);

	$userID = $dbVarsUser['RESULT'][0]['uid'];
	$websiteTitle = $dbVarsUser['RESULT'][0]['title'];
	$email = $dbVarsUser['RESULT'][0]['email'];
	$postInfo = blog_get('uid',$userID);


	$currentDate = date("D, d M Y H:i:s T");
	$rssDataOutput = '<?xml version=\"1.0\" encoding=\"UTF-8\" ?>'
				.'<rss version=\"2.0\">
					<channel>
						<title>'.$websiteTitle.'</title>
						<link>http://www.retinadestruction.com?'.$username.'</link>
						<description></description>
						<language>en-us</language>
						<pubDate>'.$currentDate.'</pubDate>
						<lastBuildDate>'.$currentDate.'</lastBuildDate>
						<webMaster>mail@retinadestruction.com</webMaster>';



	reset($postInfo);
	while (list($key, $val) = each ($postInfo))
	{

		$selectBlogImages = "SELECT iid, fileurl FROM image WHERE uid='".$postInfo[$key]['uid']."' AND pid='".$postInfo[$key]['pid']."' AND imagetype='regular'; ";
		$dbVarsImages = $dbobj->executeSelectQuery($selectBlogImages);
		for($i=0; $i<$dbVarsImages['NUM_ROWS']; $i++)
			{$blogImages[$dbVarsImages['RESULT'][$i]['iid']] = './rdusers/'.$username.'/portfolioimages/fullresolution/'.$dbVarsImages['RESULT'][$i]['fileurl'];}

		$rssDataOutput .= "<item>";
		$rssDataOutput .= "<title>".strtoupper($postInfo[$key]['headline'])."</title>";
		$rssDataOutput .= "<link>".SERVER_NAME."?jamesdoe</link>";
		$rssDataOutput .= "<description>".strtoupper($postInfo[$key]['body'])."</description>";

		$rssDataOutput .= "</item>";

	}//while

	$rssDataOutput .= '</channel>'
			.'</rss>';
	header("Content-Type: application/rss+xml");
	echo $rssDataOutput;

	unset($dbobj);
}//get_blog_rssfeed()


function blog_update_views($currentViewsCounter, $postID)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);//CALLS THE CLASS CONSTRUCTOR AND CONNECTS TO THE DB WITH THE APPORPRIATE DB ACCOUNT
	$dbVars = array();

	$currentViewsCounter++;
	$query = "UPDATE posts"
				." SET views='".$currentViewsCounter."'"
				." WHERE pid='".$postID."'; ";
	$dbVars = $dbobj->executeUpdateQuery($query);

	unset($dbobj);
	return $currentViewsCounter;
}//blog_update_views($currentViewsCounter, $postID)

function blog_update_comments_counter($postID)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);//CALLS THE CLASS CONSTRUCTOR AND CONNECTS TO THE DB WITH THE APPORPRIATE DB ACCOUNT
	$dbVars = array();

	$queryCounter = "SELECT commentscounter FROM posts WHERE pid='".$postID."'; ";
	$dbVarsCounter = $dbobj->executeSelectQuery($queryCounter);
	$currentCommentsCounter = $dbVarsCounter['RESULT'][0]['commentscounter'];
	$currentCommentsCounter++;

	$query = "UPDATE posts"
				." SET commentscounter='".$currentCommentsCounter."'"
				." WHERE pid='".$postID."'; ";
	$dbVars = $dbobj->executeUpdateQuery($query);

	unset($dbobj);
	return $currentCommentsCounter;
}
?>
