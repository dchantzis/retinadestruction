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

	
	if(DATABASE_MODE == 'default'){require_once('../rdincfiles/tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('../rdincfiles/tbdbase.pdo.class.inc.php');}
	$dbobj = new TBDBase(0);
		
	$query = "SELECT coverpage FROM website WHERE uid='".$userData['uid']."'; ";
	$dbVars = $dbobj->executeSelectQuery($query);
	$coverPageType = $dbVars['RESULT'][0]['coverpage'];
	
	$queryA = "SELECT aid FROM album WHERE uid='".$userData['uid']."' AND type='coverpageimagesalbum'; ";
	$dbVars_A = $dbobj->executeSelectQuery($queryA);
	if($dbVars_A['NUM_ROWS'] !=0 ){$albumID_coverpageimages = $dbVars_A['RESULT'][0]['aid'];}
	else{$albumID_coverpageimages = 0;}
	unset($dbobj);
?>
<div>
<br />
    <span class='section_content_subpage' id='section_content_subpage_coverpage_container'>
    	
		<div class='coverpage_instructions'>
            <b>Cover page</b> is the page that appears when your website is loaded. In other words, it's the <b>home page</b> of your portfolio wesite.
            <br /><br />
            The cover page can be: <span class='blackbackground'>1.</span> empty, <span class='blackbackground'>2.</span> The blog section of your website, <span class='blackbackground'>3.</span> A randomly loaded selected image.
        </div>
        
        <div id='form_container' class="coverpage_content" >
        	
            <label for=''>Cover page is:</label>
            <div id='coverpagetypes'>
                <span id='coverpage_frm_messages' class='messages'></span>
                <span id='coverpage_frm' class=''>
                    <?php 
                        if($coverPageType == 'empty'){$emptyFlag = 'selectedtag';}
                        if($coverPageType == 'blogsection'){$blogSectionFlag = 'selectedtag';}
                        if($coverPageType == 'randomimage'){$randomImageFlag = 'selectedtag';}
                    ?>
                    <span id='coverpage_empty' class='coverpagetype <?=$emptyFlag?>' >empty</span> / 
                    <span id='coverpage_blogsection' class='coverpagetype <?=$blogSectionFlag?>'>blog section</span> / 
                    <span id='coverpage_randomimage' class='coverpagetype <?=$randomImageFlag?>'>random image</span>
                    <span class='field_messages' id='coverpage_message'></span>
                </span>
                <span class='' id='coverpage_placeholder'></span>
            </div> 
            
        </div>
        
        <div id='coverpageimage_container'>
			<? layout_get_accountcoverpage_imagesform($userData,$albumID_coverpageimages); ?>
            
            <span class="" id='coverpage_loader_placeholder'></span>
        </div>
        
        
    </span>
    
    <div id='eddie' class='displaynone'><?=$page_filename_containerPage?></div>
    <div id='clint' class='displaynone'><?=$csrfPasswordGenerator_containerPage?></div>
</div>
<? } ?>