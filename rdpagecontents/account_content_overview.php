<?php
if(!isset($_GET['transmitter']) || ($_GET['transmitter']!='account.php') ){}
else
{
	session_start(); //start session
	/*
	session_regenerate_id(true); //regenerate session id
	//regenerate session id if PHP version is lower thatn 5.1.0
	if(!version_compare(phpversion(),"5.1.0",">=")){ setcookie( session_name(), session_id(), ini_get("session.cookie_lifetime"), "/" );}
	*/
	unset($_GET['transmitter']);
	
	require("../rdincfiles/functionsmapping.inc.php");
	
	if(isset($_SESSION['user']))
	{
		reset($_SESSION['user']); while (list($key, $val) = each ($_SESSION['user']))
			{ if(($val == NULL)||(strtolower($val) == 'null')){$userData[$key] = '';} else {$userData[$key] = $val;}}
	}//
	else
		{echo "<div class='loggedout_message'>You have been signed-out.<br/><br/>Please sign-in again.</div>"; exit;}
		
	$csrfPasswordGenerator_containerPage = hash('sha256', 'accountoverview').CSRF_PASS_GEN;
	$page_filename_containerPage = 'accountoverview';
?>


<div class='section_content_page_overview_container'>

	<? get_user_overview('overview',$userData['uid']); ?>
    
    <div id='eddie' class='displaynone'><?=$page_filename_containerPage?></div>
    <div id='clint' class='displaynone'><?=$csrfPasswordGenerator_containerPage?></div>
</div>

<? } ?>