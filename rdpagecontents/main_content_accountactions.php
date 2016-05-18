<?php
if(!isset($_GET['transmitter']) && ($_GET['transmitter']!='index.php') ){}
else
{	
	//session_start();
	//require("./common/functionsmapping.inc.php");
?>
<?php
	require('./rdincfiles/useredit.inc.php');
	//echo $_SESSION['FIRST_VISIT_TM'];
	unset($_GET['transmitter']);
	$currentPage_alias = 'accountactions';
	$currentPage_csrf = hash('sha256', $currentPage_alias).CSRF_PASS_GEN;

	/*
		if a user is logged in, then:
			1) He doesn't need to reset his password (the forgotpassword form is accessible only to users who are not logged in)
			2) He doesn't need to activate his account
		SO REDIRECT HIM TO THE HOME PAGE (index.php)
	*/
?>

<body class='accountactions'>

<?php layout_get_header_main($adminloggedin,$rguserloggedin); ?>

<div id="wrapper">
   
	<div id="mask">
    
<?php
	if(isset($_GET['hobbs']))
	{
		$urlCode = $_GET['hobbs']; unset($_GET['hobbs']);
		$actionCode = substr($urlCode,-5,-1);
		$actionCredentials = substr($urlCode,0,-5);
		switch($actionCode)
		{
			case 8543:
				echo "<div id='sections_container'>";
				echo "<div id='section_activateaccount' class='sections'> 
        				<div class='section_title_selected_activateaccount' id='section_accountactions_title'>Activate your account</div>";
						echo "<div class='section_content_selected_activateaccount' id='section_activateaccount_content'>";
						user_activatenewaccount($actionCredentials);
						echo "<div id='accountactiontype' class='displaynone'>activatenewaccount</div>";
						echo "</div>";
				echo "</div>";
				echo "</div>";
				break;
			case 2541:
				echo "<div id='sections_container'>";
				echo "<div id='section_resetpassword' class='sections'> 
        				<div class='section_title_selected_resetpassword' id='section_resetpassword_title'>Reset your password</div>";
						echo "<div class='section_content_selected_resetpassword' id='section_resetpassword_content'>";
						user_resetpassword_check($actionCredentials);
						echo "<div id='accountactiontype' class='displaynone'>resetpassword</div>";
						echo "</div>";
				echo "</div>";
				echo "</div>";
				break;
			default:
				//redirects(0,'');
				break;
		}//switch
	}//if
?>

</div><!--mask-->

</div><!--wrapper-->

<?php layout_get_footer($adminloggedin,$rguserloggedin); ?>

</body>
<div id='eddie' class='displaynone'><?=$currentPage_alias?></div>
<div id='clint' class='displaynone'><?=$currentPage_csrf?></div>

<?php
}//else
?>
