<?php
session_start();

require("rdconfig.inc.php");
require("responses.inc.php");
require("commonfunctions.inc.php");
require("validate.class.inc.php");
require("user.inc.php");
require("album.inc.php");
require("image.inc.php");
require("blog.inc.php");
require("comment.inc.php");
require("website.inc.php");
require("uploaddelete.inc.php");
require("signinnout.inc.php");
require("layout.inc.php");
require("email.inc.php");


//$_GET['type']=7;
//$_POST = array("email"=>"james.doe@gmail.com","password"=>"qwaszxqwaszx","clint"=>"0d6e4079e36703ebd37c00722f5891d28b0e2811dc114b129215123adcce36050a1d120bd215e3submitlogin","formid"=>"login","pageid"=>"main");


reset($_GET);
if(isset($_GET['type']))
{ 
	if(!preg_match("/^[0-9]([0-9]*)/",$_GET['type'])){$_GET['type'] = NULL; }
	else{$get_type = $_GET['type']; }
	unset($_GET['type']);
}else { $get_type = NULL; }


switch($get_type)
{
	case 3:
		require('useredit.inc.php');
		user_edit('insert','ajax');
		break;
	case 4:
		require('useredit.inc.php');
		user_edit('update','ajax');
		break;
	case 5:
		require('useredit.inc.php');
		password_forgot_request('ajax');
		break;
	case 6:
		require('useredit.inc.php');
		password_reset_request('ajax');
		break;
	case 7:
		//require("signinnout.inc.php");
		user_login('ajax');
		break;
	case 8:
		//require("signinnout.inc.php");
		user_logout('ajax');
		break;
	case 9:
		require('useredit.inc.php');
		user_edit_privacy('update','ajax');
		break;
	case 10:
		require('useredit.inc.php');
		user_edit_toggleValues('update','ajax');
		break;
	case 11:
		require('websiteedit.inc.php');
		website_edit('update','ajax');
		break;
	case 12:
		require('albumedit.inc.php');
		album_edit('insert','ajax');
		break;
	case 13:
		require('albumedit.inc.php');
		album_edit_toggleValues('update','ajax');
		break;
	case 14:
		require('albumedit.inc.php');
		album_edit('delete','ajax');
		break;
	case 15:
		require('albumedit.inc.php');
		album_edit('update','ajax');
		break;
	case 16:
		require('useredit.inc.php');
		require('imageedit.inc.php');
		require('blogedit.inc.php');
		images_album_edit('regular');
		break;
	case 17:
		require('imageedit.inc.php');
		images_album_delete('ajax');
		break;
	case 18:
		require('albumedit.inc.php');
		album_edit_imagesorder('ajax');
		break;
	case 19:
		require('albumedit.inc.php');
		album_edit_toggleValues('update','ajax');
		break;
	case 20:
		require('imageedit.inc.php');
		images_album_editinfo('update','ajax');
		break;
	case 21:
		require('imageedit.inc.php');
		images_album_editinfo('update','ajax');
		break;
	case 22:
		require('websiteedit.inc.php');
		website_edit_toggleValues('update','ajax');
		break;
	case 23:
		require('websiteedit.inc.php');
		website_edit('update','ajax');
		break;
	case 24:
		require('useredit.inc.php');
		require('imageedit.inc.php');
		require('blogedit.inc.php');
		images_album_edit('albumcover');
		break;
	case 25:
		require('imageedit.inc.php');
		require('blogedit.inc.php');
		images_album_edit('usercover');
		break;
	case 26:
		//require("signinnout.inc.php");
		user_logout('php');
		redirects(0,'');
		break;
	case 27:
		require('imageedit.inc.php');
		require('useredit.inc.php');
		require('blogedit.inc.php');
		images_album_edit('usercover');
		break;
	case 28:
		require('blogedit.inc.php');
		blog_edit('insert','ajax');
		break;
	case 29:
		require('blogedit.inc.php');
		blog_edit('update','ajax');
		break;
	case 30:
		require('blogedit.inc.php');
		blog_edit_toggleValues('update','ajax');
		break;
	case 31:
		require('blogedit.inc.php');
		blog_edit_toggleValues('update','ajax');
		break;
	case 32:
		require('blogedit.inc.php');
		blog_edit('delete','ajax');
		break;
	case 33:
		require('imageedit.inc.php');
		images_album_edit('regular_blogpost');
		break;
	case 34:
		require('imageedit.inc.php');
		image_update_views_ajax('update','ajax');
		break;
	case 35:
		//require("signinnout.inc.php");
		$username = $_SESSION['user']['username'];
		user_logout('php');
		redirects(4,'?'.$username);
		break;
	case 36:
		email_contact_prepare_main('ajax');
		break;
	case 37:
		require('commentedit.inc.php');
		comment_edit('insert','ajax');		
		break;
	case 38:
		require('websiteedit.inc.php');
		website_edit_toggleValues('update','ajax');		
		break;
	case 39:
		require('imageedit.inc.php');
		images_album_edit('regular_coverpage');
		break;
	case 40:
		require('websiteedit.inc.php');
		website_edit('update','ajax');
		break;
	case 41:
		require('imageedit.inc.php');
		images_album_edit('wallpaper');
		break;
	case 42:
		require('imageedit.inc.php');
		images_album_edit('logo');
		break;
	default:
		//IMPORTANT TO HAVE NO ACTION
		return -1;
		break;
}//switch

//function with different headers for redirection purposes
function redirects($r_id,$flags)
{
	if(!preg_match("/^[0-9]([0-9]*)/",$r_id)){ $r_id = NULL; }
	else {}//do nothing. ALL OK
	
	switch($r_id)
	{
		case 0:
			header("Location: ./index.php".$flags);
			exit;
			break;
		case 1:
			header("Location: ./browsersettings.php".$flags);
			exit;
			break;
		case 2:
			header("Location: ./errorcontent.php".$flags);
			exit;
			break;
		case 3:
			header("Location: ./portfolioindex.php".$flags);
			exit;
			break;
		case 4:
			header("Location: ../portfolioindex.php".$flags);
			exit;
			break;
		case 100:
			header("Location: ../index.php".$flags);
			exit;
			break;
		case 200:
			header("Location: ../errorcontent.php".$flags);
			exit;
			break;
		default:
			//header("Location: ./index.php".$flags);
			//exit;
			break;
	}//switch
}//redirects($r_id,$flags)

?>