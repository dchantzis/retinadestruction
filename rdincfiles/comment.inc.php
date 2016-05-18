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
			get_comment_list($_GET['uid'],$_GET['aid'],$_GET['pid'],$_GET['commentfor']);
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
			get_comment_list($_GET['uid'],$_GET['aid'],$_GET['pid'],$_GET['commentfor']);
			break;
		default:
			//IMPORTANT TO HAVE NO ACTION
			break;
	}//switch
}//

function comment_get($userID,$albumID,$postID,$commentFor)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$userInfo = array();
	$query = NULL;
	
	$userID = (get_magic_quotes_gpc()) ? $userID : addslashes($userID);
	$userID = htmlentities($userID, ENT_QUOTES, "UTF-8");
	$userID = trim($userID);
						
	$albumID = (get_magic_quotes_gpc()) ? $albumID : addslashes($albumID);
	$albumID = htmlentities($albumID, ENT_QUOTES, "UTF-8");
	$albumID = trim($albumID);

	$postID = (get_magic_quotes_gpc()) ? $postID : addslashes($postID);
	$postID = htmlentities($postID, ENT_QUOTES, "UTF-8");
	$postID = trim($postID);

	switch($commentFor)
	{
		case 'post':
			$query = "SELECT * FROM comment WHERE uid='".$userID."' AND pid='".$postID."' ORDER BY submitiontimestamp DESC; ";
			break;
		case 'album':
			$query = "SELECT * FROM comment WHERE uid='".$userID."' AND aid='".$albumID."' ORDER BY submitiontimestamp DESC; ";
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
				$commentData[$i]['cid'] = $dbVars['RESULT'][$i]['cid'];
				$commentData[$i]['pid'] = $dbVars['RESULT'][$i]['pid'];
				$commentData[$i]['uid'] = $dbVars['RESULT'][$i]['uid'];
				$commentData[$i]['aid'] = $dbVars['RESULT'][$i]['aid'];
				$commentData[$i]['name'] = $dbVars['RESULT'][$i]['name'];
				$commentData[$i]['email'] = $tagInfo[$dbVars['RESULT'][$i]['email']];
				$commentData[$i]['body'] = nl2br($dbVars['RESULT'][$i]['body']);		
				$commentData[$i]['submitiontimestamp'] = $dbVars['RESULT'][$i]['submitiontimestamp'];
			}//for	

			unset($validator);
			unset($dbVars);
			return $commentData;
		}//if
		else
		{
			//no result found
		}//else
	}//
	else{} //query was NULL
	
}//blog_getcomment_get($userID,$albumID,$postID,$commentFor)


function get_comment_list($userID,$albumID,$postID,$commentFor)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$userInfo = array();
	$query = NULL;
	
	$userID = (get_magic_quotes_gpc()) ? $userID : addslashes($userID);
	$userID = htmlentities($userID, ENT_QUOTES, "UTF-8");
	$userID = trim($userID);
						
	$albumID = (get_magic_quotes_gpc()) ? $albumID : addslashes($albumID);
	$albumID = htmlentities($albumID, ENT_QUOTES, "UTF-8");
	$albumID = trim($albumID);

	$postID = (get_magic_quotes_gpc()) ? $postID : addslashes($postID);
	$postID = htmlentities($postID, ENT_QUOTES, "UTF-8");
	$postID = trim($postID);
	
	$commentData = comment_get($userID,$albumID,$postID,$commentFor);
	
	$dbobj = new TBDBase(0);
	switch($commentFor)
	{
		case 'post':
			$query = "SELECT headline FROM posts WHERE uid='".$userID."' AND pid='".$postID."'; ";
			$dbVars = $dbobj->executeSelectQuery($query);
			$commentRegarding = $dbVars['RESULT'][0]['headline'];
			$clark = $postID;
			//echo $query;
			break;
		case 'album':
			$query = "SELECT name FROM album WHERE uid='".$userID."' AND aid='".$albumID."'; ";
			$dbVars = $dbobj->executeSelectQuery($query);
			$commentRegarding = $dbVars['RESULT'][0]['name'];
			$clark = $albumID;
			break;
	}//switch

	
	echo "<div class='comment_container'>";

		echo "<div class='comment_title'>comments </div>";
		echo "<div class='comment_for'>For ".$commentFor.": <span class='comment_for_name'>".$commentRegarding."</span></div>";
	
		echo "<div id='comment_close'>close</div>";
	
		echo "<div id='comment_body'>";

        echo "<div id='previous_comment_container'>";
			if(count($commentData) != 0)
			{
				echo "<ul id='previous_comment'>";
				reset($commentData);
				for($i=0; $i<count($commentData); $i++)
				{
					echo "<li class='".$commentData[$i]['cid']."'>";
						echo "<div class='oldcommentwho'>";
							echo "<a href='mailto:".$commentData[$i]['email']."' class='' title='Contact ".$commentData[$i]['name']."'>".$commentData[$i]['name']."</a> said,";
						echo "</div>";
						echo "<div class='oldcommentwhen'>";
							echo "Posted on: ".convertTimeStamp($commentData[$i]['submitiontimestamp'],'reallylong');
						echo "</div>";
						echo "<div class='oldcommentwhat'>";
							echo "<span class='bigquotes'>\"</span> ".$commentData[$i]['body']." <span class='bigquotes'>\"</span>";
						echo "</div>";
					echo "</li>";
				}//for
				echo "</ul>";
			}//if
			else
			{
				echo "<div id='previous_comment_container_text'>Noone has commented on this ".$commentFor." yet.</div>";
			}
        echo "</div>"; //<!--previous_comments_container-->
	
	
	    echo "<div id='new_comment_container'>";

			echo "<div id='comment_frm_messages' class='messages'></div>";
			echo "<div id='comment_frm_loader'></div>";
			echo "<ul id='comment_frm'>";
			
			if(isset($_SESSION['user']) && ($_SESSION['user']['uid'] == $userID))
			{
				echo "<li class='displaynone'>"."<input type='hidden' maxlength='30'  class='text' id='comment_name' name='comment_name' value='".$_SESSION['user']['username']."' />"."</li>";
				echo "<li class='displaynone'>"."<input type='hidden' maxlength='90'  class='text' id='comment_email' name='comment_email' value='".$_SESSION['user']['email']."' />"."</li>";
			}//if
			else
			{
				echo "<li>"."<input type='text' maxlength='30'  class='text' id='comment_name' name='comment_name' value='(your name)(required)' title='Your name' />"."</li>";
				echo "<li>"."<input type='text' maxlength='90'  class='text' id='comment_email' name='comment_email' value='(your email)(required)' title='Your email' />"."</li>";
			}//else
			
			echo "<li>";
				echo "<textarea class='text' name='comment_reply' id='comment_reply' cols='' rows='' wrap='soft' title='Your comment'>(your comment)(required)</textarea>";
				echo "<div class='charcounters'><span class='counters' id='ccounter'>".COMMENT_MESSAGE_MAX_LENGTH."</span> remaining characters</div>";
			echo "</li>";
			
			echo "<li class='submit' id='comment_submit'>";
				echo "<div class='displaynone'><input type='hidden' class='displaynone' id='commentfor' value='".$commentFor."'/></div>";
				echo "<div class='displaynone' id='clark'>".$clark."</div>";
				echo "<div>submit</div>";
			echo "</li>";
			echo "</ul>";
					
			echo "</div>"; //<!--new_comment_container-->
	
		echo "</div>";
	
	echo "</div>"; //comment_container
	
	
}//get_comment_list($userID,$albumID,$postID,$commentType)



?>