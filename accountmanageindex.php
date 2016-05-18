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
	
	if( !check_user_access('accountmanageindex.php') ){redirects(0,'');};
	
	if(isset($_SESSION['user']))
		{reset($_SESSION['user']); while (list($key, $val) = each ($_SESSION['user']))
		{if(($val == NULL)||(strtolower($val) == 'null')){$userData[$key] = ' ';} else {$userData[$key] = $val;}}}
	
//!check_user_access('accountmanageindex.php');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Retina Destruction: Artists Community</title>
<style type="text/css" media="screen"> 
	@import url(./rdlayout/css/allstyles.inc.css);
	@import url(./rdscripts/colorpicker/css/colorpicker.css);
</style>

<script type="text/javascript" src="./rdscripts/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="./rdscripts/jquery/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="./rdscripts/jquery/jquery.scrollTo.js"></script>
<script type="text/javascript" src="./rdscripts/commonajaxfunctions.js"></script>
<script type="text/javascript" src="./rdscripts/validatensubmit.inc.js"></script>
<script type="text/javascript" src="./rdscripts/main.inc.js"></script>
<script type="text/javascript" src="./rdscripts/commonfunctions.js"></script>


<script type="text/javascript" src="./rdscripts/swfupload/swfupload.js"></script>
<script type="text/javascript" src="./rdscripts/swfupload/handlers.js"></script>
<script type="text/javascript" src="./rdscripts/swfobjectsini.js"></script>
<script type="text/javascript" src="./rdscripts/colorpicker/colorpicker.js"></script>


</head>

<body class='account'>

<?php layout_get_header_account($adminloggedin,$rguserloggedin); ?>

<div id="wrapper">
   
	<div id="mask"> 

    <div id='sections_container'>
    	
		<div id="section_overview" class="sections"> 
       		<a id='section_anchor_top' name='top'></a><div id='top'></div>
			<a id='section_anchor_overview' name="section_overview"></a> 
            <div class='section_title' id='section_overview_title'>0. Overview</div>
			<div class="section_content" id='section_overview_content'>
            	<span class='section_subtitle'>   
				A global view to your profile and your website settings, as well as your statistics.
                </span>
                <span class='section_content_page' id='section_content_page_overview'></span>
			</div> 
		</div><!--section_information-->

		<div id="section_settings" class="sections"> 
			<a id='section_anchor_settings' name="section_settings"></a>
            <div class='section_title' id='section_settings_title'>1. Settings</div>
			<div class="section_content" id='section_settings_content'>
            	<span class='section_subtitle'>
				Edit your account settings.
                </span>
                <span class='section_content_page' id='section_content_page_settings'></span>
            </div> 
		</div><!--section_artists_index-->

		<div id="section_albums" class="sections"> 
			<a id='section_anchor_albums' name="section_albums"></a> 
            <div class='section_title' id='section_albums_title'>2. Albums</div>
			<div class="section_content" id='section_albums_content'>
           		<span class='section_subtitle'>
				The cornerstone of your portfolio website! Here you can create and edit your albums and all their included images. Moreover you can read comments by your website visitors, and reply to them.
                </span>
                <span class='section_content_page' id='section_content_page_albums'></span>
			</div> 
		</div><!--section_instructions-->
		
		<div id="section_blog" class="sections"> 
			<a id='section_anchor_blog' name="section_blog"></a>
            <div class='section_title' id='section_blog_title'>3. Blog</div>
			<div class="section_content" id='section_blog_content'>
            	<span class='section_subtitle'>
				This is your personal news service. Create and edit your news posts, read comments left by your website visitors to each of your news posts, as well as reply.
                </span>
                <span class='section_content_page' id='section_content_page_blog'></span>
			</div> 
		</div><!--section_about--> 	
        
   		<div id="section_coverpage" class="sections"> 
			<a id='section_anchor_coverpage' name="section_coverpage"></a>
            <div class='section_title' id='section_coverpage_title'>4. Cover Page</div>
			<div class="section_content" id='section_coverpage_content'>
				<span class='section_subtitle'>
                Customize the cover page of your portfolio website.
                </span>
                <span class='section_content_page' id='section_content_page_coverpage'></span>
            </div> 
		</div><!--section_registration-->
        
		<div id="section_designeditor" class="sections"> 
			<a id='section_anchor_designeditor' name="section_designeditor"></a>
            <div class='section_title' id='section_designeditor_title'>5. Design Editor</div>
			<div class="section_content" id='section_designeditor_content'>
            	<span class='section_subtitle'>
				Set the look and feel of your website by selecting one of the predefined templates and customizing it easily through the guide.
                </span>
                <span class='section_content_page' id='section_content_page_designeditor'></span>
            </div> 
		</div><!--section_featured_artists-->

		<div id="section_instructions" class="sections"> 
			<a id='section_anchor_instruction' name="section_instructions"></a> 
            <div class='section_title' id='section_instructions_title'>Instructions</div>
			<div class="section_content" id='section_instructions_content'>
            	<span class='section_subtitle'>
				Get directions on how to explore Retina Destruction's full potential
                </span>
                <span class='section_content_page' id='section_content_page_instructions'></span>
            </div> 
		</div><!--section_instructions-->

    </div><!--sections_container-->

	</div><!--mask-->

</div><!--wrapper-->

<?php layout_get_footer($adminloggedin,$rguserloggedin); ?>

<div id='loader_layer'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
<div id='loader_layer2'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
<div id='loader_layer3'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
<div id='loader_layer7'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>
<div id='loader_layer4'></div>
<div id='layer_placeholder' class='displaynone'></div>

</body>
<div id='eddie' class='displaynone'>account</div>
<div id='clint' class='displaynone'></div>
<div id='steve' class='displaynone'><?=$userData['uid']?></div>
<div id='brandon' class='displaynone'><?=$userData['username']?></div>
</html>
