<?php
if(!isset($_GET['transmitter']) && ($_GET['transmitter']!='index') ){}
else
{	
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

<body class='index'>

<?php layout_get_header_main(); ?>

<div id="wrapper">

<?php
	if(isset($_GET['hobbs']))
	{
		$urlCode = $_GET['hobbs']; unset($_GET['hobbs']);
		$actionCode = substr($urlCode,-5,-1);
		$actionCredentials = substr($urlCode,0,-5);
		switch($actionCode)
		{
			case 8543:
				//activate new account
				echo "<div id='accountactions_layer' class='new_layer'>
						<div class='loaded_layer_wrapper'>
							<div id='accountactions_header' class='home_layers_headers'>
								<span class='blackbg'>activate</span><span class='greenbg'>your</span><span class='graybg'>account:</span>
							</div><!--accountactions_header-->
							<div id='accountactions_body'>";
							user_activatenewaccount($actionCredentials);
					
					echo "<div id='accountactiontype' class='displaynone'>activatenewaccount</div>";
				
						echo "</div><!--accountactions_body'-->";
						echo "<div class='home_layers_footer'></div><!--home_layers_footer-->";
						echo "</div><!--loaded_layer_wrapper-->";
				echo "</div><!--accountactions-->";
				break;
			case 2541:
				//reset password
				echo "<div id='accountactions_layer' class='new_layer'>
						<div class='loaded_layer_wrapper'>
							<div id='accountactions_header' class='home_layers_headers'>
								<span class='blackbg'>reset</span><span class='greenbg'>your</span><span class='graybg'>password:</span>
							</div><!--accountactions_header-->
							<div id='accountactions_body'>";
							user_resetpassword_check($actionCredentials);
							
					echo "<div id='accountactiontype' class='displaynone'>resetpassword</div>";
							
						echo "</div><!--accountactions_body'-->";
						echo "<div class='home_layers_footer'></div><!--home_layers_footer-->";
						echo "</div><!--loaded_layer_wrapper-->";
				echo "</div><!--accountactions-->";
				break;
			default:
				//redirects(0,'');
				break;
		}//switch
	}//if
?>



</div><!--wrapper-->

<?php layout_get_footer(); ?>

</body>
<div id='eddie' class='displaynone'><?=$currentPage_csrf?></div>
<div id='clint' class='displaynone'><?=$currentPage_alias?></div>

<script type="text/javascript" >
	//initializePageElements("accountactions");	
</script>
<?php
}//else
?>
