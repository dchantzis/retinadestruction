<?php

	###################################################################################
	header("Expires: Thu, 17 May 2001 10:17:17 GMT");    // Date in the past
  	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
	header ("Pragma: no-cache");                          // HTTP/1.0
	header ("Content-type: text/html; charset=utf-8");
	###################################################################################
	
	session_start(); //start session
	/*
	session_regenerate_id(true); //regenerate session id
	//regenerate session id if PHP version is lower thatn 5.1.0
	if(!version_compare(phpversion(),"5.1.0",">=")){ setcookie( session_name(), session_id(), ini_get("session.cookie_lifetime"), "/" );}
	*/
	if( (!isset($_SESSION['FIRST_VISIT_TM']))){ $_SESSION['FIRST_VISIT_TM'] = date("Y.m.d").".".date("H.i.s"); }
	

	require("./rdincfiles/functionsmapping.inc.php");
	######################################################
	######################################################
	
	/* PLACE THIS ****ONLY**** IN THE SITE INDEX PAGE*/
	//CHECK IF PAGE EXITSTS

	$page_filename_toload = check_browseraddressGET_page_exists();
	if($page_filename_toload != NULL){}//page found
	else if($page_filename_toload == NULL)// IF THE PAGE IS NOT FOUND, THAT MEANS THAT IT COULD BE A USERNAME
	{
		$page_filename_toload = 'main_content_home.php';
		if(count($_GET)==0){$page_filename_toload = 'main_content_home.php';}//
		else
		{
			reset($_GET); $counter=0;
			while (list($key_get, $val_get) = each ($_GET))
			{
				if($counter==0){$search_username = $key_get;} //IN THIS CASE, THE SYSTEM ONLY ACKNOWLEDGES THE FIRST PARAMETER TO BE A USERNAME
				//if($counter==1){$search_story = $key_get;}	//IN THIS CASE, THE SYSTEM ONLY ACKNOWLEDGES THE SECOND PARAMETER TO BE A STORYNAME	
				if($counter==1){break;}//I WANT THE OUTER WHILE TO RUN ONLY TWICE. WE ARE SEARCHING FOR ONLY THE TWO FIRST $_GET PARAMETERS
				$counter++;
			}//outer while
		}//else
		//CHECK IF THE USERNAME EXISTS IN THE SYSTEM
		unset($_GET);

		if(isset($search_username))
			{if(check_username_exists($search_username,1)){$page_filename_toload = 'portfolioindex.php'; $_GET['search_username'] = $search_username;}}
		//if(isset($search_story))
			//{if(check_story_exists($search_story,0)){$page_filename_toload = 'story.php'; $_GET['search_username'] = $search_username; $_GET['search_story'] = $search_story;}}
	}//
	#####################################################
	#####################################################
	
	$csrfPasswordGenerator_containerPage = hash('sha256', 'main').CSRF_PASS_GEN;
	$page_filename_containerPage = 'index';
	
	//checkCookiesAvailability('');

	//if(check_user_access('index.php')){redirects(0,'');}

	$adminloggedin = false; $rguserloggedin = false; $loggedInUsername = '';  $loggedin_usertype = validate_user_login();
	if($loggedin_usertype == 'administrator'){$adminloggedin = true; $loggedInUsername = $_SESSION['user']['username'];}
	if($loggedin_usertype == 'registereduser'){$rguserloggedin = true; $loggedInUsername = $_SESSION['user']['username'];}
	
	/*
	if( !($adminloggedin) && !($rguserloggedin) ){
		reset($_SESSION['user']); while (list($key, $val) = each ($_SESSION['user']))
		{ if(($val == NULL)||(strtolower($val) == 'null')){$userData[$key] = ' ';} else {$userData[$key] = $val;}}}
	*/
	/*
	session_unset();
	// Clear the session cookie
	unset($_COOKIE[session_name()]);
	// Destroy session data
	session_destroy();
	unset($rguserloggedin);
	unset($adminloggedin);
	*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Retina Destruction: Artists Community</title>
<style type="text/css" media="screen"> 
	@import url(./rdlayout/css/allstyles.inc.css);
</style>
<script type="text/javascript" src="./rdscripts/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="./rdscripts/jquery/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="./rdscripts/jquery/jquery.scrollTo.js"></script>
<script type="text/javascript" src="./rdscripts/commonajaxfunctions.js"></script>
<script type="text/javascript" src="./rdscripts/validatensubmit.inc.js"></script>
<script type="text/javascript" src="./rdscripts/main.inc.js"></script>
<script type="text/javascript" src="./rdscripts/commonfunctions.js"></script>





</head>

<?php
#################################
#################################
	if(check_user_access($page_filename_toload)) //TRUE IS FOR "NO ACCESS ALLOWED"
		{$page_filename_toload = 'main_content_home.php';}//FOR SUCH CASES, DEFAULT PAGE TO LOAD IS THE HOMEPAGE
	if($page_filename_toload == 'portfolioindex.php'){redirects(3,'?'.$search_username);}
	
	$_GET['transmitter'] = 'index.php';
	//if($page_filename_toload == 'accountactions.php'){}
	if($page_filename_toload == 'portfolioindex.php'){require_once('./'.$page_filename_toload);}
	else 
		{require_once('./rdpagecontents/'.$page_filename_toload);}
#################################
#################################
?>

<div id='loader_layer'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
<div id='loader_layer2'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
<div id='loader_layer3'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
<div id='loader_layer4'></div>

</html>
