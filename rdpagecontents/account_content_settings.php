<?php
if(!isset($_GET['transmitter']) || ($_GET['transmitter']!='account.php') ){}
else
{
	session_start(); //start session
	//session_regenerate_id(true); //regenerate session id
	//regenerate session id if PHP version is lower thatn 5.1.0
	//if(!version_compare(phpversion(),"5.1.0",">=")){ setcookie( session_name(), session_id(), ini_get("session.cookie_lifetime"), "/" );}

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
		
	$csrfPasswordGenerator_containerPage = hash('sha256', 'accountsettings').CSRF_PASS_GEN;
	$page_filename_containerPage = 'accountsettings';

?>
<div>
<br />
	<ul id='account_settings_navigation'>
    	<li><span id='account_settings_navigation_user'>Profile Set-Up</span></li>
        <li><span id='account_settings_navigation_website'>Website Set-Up</span></li>
        <li><span id='account_settings_navigation_privacy'>Privacy Options</span></li>
        <li><span id='account_settings_navigation_resetpassword'>Reset Password</span></li>
        <li><span id='account_settings_navigation_images'>Images Options</span></li>
    </ul>
    
    <span class='section_content_subpage' id='section_content_subpage_settings'>
    	<? layout_get_accountsettings_userform($userData); ?>
    </span>
    
    <div id='eddie' class='displaynone'><?=$page_filename_containerPage?></div>
    <div id='clint' class='displaynone'><?=$csrfPasswordGenerator_containerPage?></div>
</div>
<? } ?>