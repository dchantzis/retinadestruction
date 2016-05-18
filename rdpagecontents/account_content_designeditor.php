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
	require("../rdincfiles/useredit.inc.php");
	
	if(isset($_SESSION['user']))
	{
		reset($_SESSION['user']); while (list($key, $val) = each ($_SESSION['user']))
			{ if(($val == NULL)||(strtolower($val) == 'null')){$userData[$key] = '';} else {$userData[$key] = $val;}}
	}//
	else
		{echo "<div class='loggedout_message'>You have been signed-out.<br/><br/>Please sign-in again.</div>"; exit;}
		
	$csrfPasswordGenerator_containerPage = hash('sha256', 'accountdesigneditor').CSRF_PASS_GEN;
	$page_filename_containerPage = 'accountdesigneditor';
?>

<script type="text/javascript" src="./rdscripts/jquery/jquery.autocomplete.js"></script>
<div class='section_content_page_designeditor_container'>

	 <span id='section_content_subpage_designeditor' class='section_content_subpage'>
     	
        <div id='designeditor_templates_container'><?php layout_get_designeditor_templates(); ?></div>
        <div id='designeditor_settings_container'><?php layout_get_designeditor_settings(0); ?></div>
        
    </span>
    <div id='loader_layer6' class='displaynone'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
  	<div class='clear'></div><div class='muchneededhight'></div>
    <div id='eddie' class='displaynone'><?=$page_filename_containerPage?></div>
    <div id='clint' class='displaynone'><?=$csrfPasswordGenerator_containerPage?></div>
</div>
<? } ?>