<?php
	###################################################################################
	header("Expires: Thu, 17 May 2001 10:17:17 GMT");    // Date in the past
  	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
	header ("Pragma: no-cache");                          // HTTP/1.0
	header ("Content-type: text/html; charset=utf-8");
	###################################################################################

	session_start(); //start session
	//session_regenerate_id(true); //regenerate session id
	//regenerate session id if PHP version is lower thatn 5.1.0
	//if(!version_compare(phpversion(),"5.1.0",">=")){ setcookie( session_name(), session_id(), ini_get("session.cookie_lifetime"), "/" );}

	if( (!isset($_SESSION['FIRST_VISIT_TM']))){ $_SESSION['FIRST_VISIT_TM'] = date("Y.m.d").".".date("H.i.s"); }

	require("./rdincfiles/functionsmapping.inc.php");

	//if( check_user_access('portfolioindex.php') ){redirects(0,'');};
	if(isset($_SESSION['user']))
		{reset($_SESSION['user']); while (list($key, $val) = each ($_SESSION['user']))
		{if(($val == NULL)||(strtolower($val) == 'null')){$_SESSION['user'][$key] = ' ';} else {$_SESSION['user'][$key] = $val;}}}

	//find the username of the user of this website
	reset($_GET);{$counter=0; while (list($key, $val) = each ($_GET)){$username = $key; if($counter == 0){break;} $counter++;}}
	$userData = user_get('username',$username);
	if($userData == NULL){redirects(0,'');}
	$websiteInfo = website_get('uid',$userData['uid']);
	$websiteDesign = layout_get_portfolio_website_design($websiteInfo,$userData);
	website_update_views($websiteInfo['views'], $websiteInfo['wid']);


	$adminloggedin = false; $rguserloggedin = false; $loggedInUsername = '';  $loggedin_usertype = validate_user_login();
	if($loggedin_usertype == 'administrator'){$adminloggedin = true; $loggedInUsername = $_SESSION['user']['username'];}
	if($loggedin_usertype == 'registereduser'){$rguserloggedin = true; $loggedInUsername = $_SESSION['user']['username'];}
	//!check_user_access('accountmanageindex.php');

	$csrfPasswordGenerator_containerPage = hash('sha256', 'portfolio').CSRF_PASS_GEN;
	$page_filename_containerPage = 'portfolio';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=strtoupper($userData['name']).": ".strtoupper($websiteInfo['title'])." - by RetinaDestruction"; ?></title>
<style type="text/css" media="screen">
	@import url(./rdlayout/css/portfoliolayout.inc.css);
	@import url(./rdlayout/css/iphone.inc.css) only screen and (max-device-width: 480px);
</style>
<?php
	layout_portfolio_website_design_generatecss($websiteDesign,$userData,$websiteInfo);
?>
<script type="text/javascript" src="./rdscripts/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="./rdscripts/jquery/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="./rdscripts/portfoliomain.inc.js"></script>
<script type="text/javascript" src="./rdscripts/commonajaxfunctions.js"></script>
<script type="text/javascript" src="./rdscripts/validatensubmit.inc.js"></script>
<script type="text/javascript" src="./rdscripts/commonfunctions.js"></script>
</head>

<body class='portfolio'>

	<?php layout_get_header_portfolio($adminloggedin,$rguserloggedin); ?>
        <div id="wrapper">
            <div id="content">
            <?php if($userData['visibilitystatus'] == 'visible'){ ?>
                <?php layout_get_portfolio_sidebar($userData,$websiteInfo,$websiteDesign);?>
                <?php layout_get_portfolio_maincontent($userData,$websiteInfo,$websiteDesign); ?>
             <?php } else { ?>
                <div id='portfolio_private_message' class='text_highlight text_highlight_bg' >We're sorry,<br/>This website is set to <i>private</i> by the user.</div>
            <?php } ?>
            <?php if($userData['registrationstatus'] == 'incomplete') { ?>
            	<div id='portfolio_private_message' class='text_highlight text_highlight_bg' >We're sorry,<br/>Website deactivated by user.</div>
            <?php } ?>
            </div><!--content-->
        </div>
        <!--wrapper-->
        <?php layout_get_footer($adminloggedin,$rguserloggedin);?>

	<div id='loader_layer' class='displaynone'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
    <div id='loader_layer2' class='displaynone'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
    <div id='loader_layer3' class='displaynone'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
    <div id='loader_layer4' class='displaynone'></div>

    <div id='layer_placeholder' class='displaynone'></div>

</body>
<div id='eddie' class='displaynone'>portfolio</div>
<div id='clint' class='displaynone'><?=$csrfPasswordGenerator_containerPage?></div>
<div id='steve' class='displaynone'><?=$userData['uid']?></div>
<div id='brandon' class='displaynone'><?=$userData['username']?></div>
</html>
