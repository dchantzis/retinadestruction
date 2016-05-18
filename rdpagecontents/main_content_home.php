<?php
if(!isset($_GET['transmitter']) && ($_GET['transmitter']!='index.php') ){}
else
{
	require_once('./rdincfiles/validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('./rdincfiles/tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('./rdincfiles/tbdbase.pdo.class.inc.php');}
	
	$dbobj = new TBDBase(0); $validator = new Validate();
	
	$query = "SELECT count(*) as counter FROM user WHERE user.registrationstatus = 'complete' AND user.visibilitystatus='visible' AND user.accountstatus='active'; ";
	$dbVars = $dbobj->executeSelectQuery($query);
	if($dbVars['NUM_ROWS'] != 0){$currentUsersCounter = $dbVars['RESULT'][0]['counter'];}else{$currentUsersCounter = 0;}
	
	$query = "SELECT count(*) as counter FROM image; ";
	$dbVars = $dbobj->executeSelectQuery($query);
	if($dbVars['NUM_ROWS'] != 0){$currentImagesCounter = $dbVars['RESULT'][0]['counter'];}else{$currentImagesCounters = 0;}
?>
<body class='index'>

<?php layout_get_header_main($adminloggedin,$rguserloggedin); ?>

<div id="wrapper">
   
	<div id="mask"> 

    <div id='sections_container'>
    	
		<div id="section_introduction" class="sections"> 
       		<a id='section_anchor_top' name='top'></a><div id='top'></div>
			<a id='section_anchor_introduction' name="section_introduction"></a> 
            <div class='section_title' id='section_introduction_title'>Introduction</div>
			<div class="section_content" id='section_introduction_content'>
                 <span id='section_content_subpage_introduction' class='section_content_subpage'>
                
                   <div><span class='blackbackground'>ret&middot;i&middot;na</span> (n.) - <br /> A delicate, multilayered, light-sensitive membrane lining the inner eyeball,<br/> in which stimulation by light occurs, initiating the sensation of vision. </div>
                   <div><span class='blackbackground'>de&middot;struc&middot;tion</span> (n.) - <br /> The condition of having been destroyed.</div>         
                
				</span>
                <span>
				<p>
                	Retina Destruction is an online platform specifically designed to empower visual artists with an <span class='blackbackground2'>awesome</span> and complete online presence. 
               	</p>
                <p>
                	Artists have the power of a highly intuitive and elegant tool, to customize the contents and the design of their portfolio website, hosted in Retina Destruction.
                </p>
                <p>Retina Destruction transmits a call to all registered artists, to create highly mind-destructive and eye-blowing art.</p>
                <p><span class='blackbackground2'>Visual noise is highly anticipated and encouraged.</span></p>
                </span>
			</div> 
		</div><!--section_information-->
		
		
		<div id="section_artistsindex" class="sections"> 
			<a id='section_anchor_artistsindex' name="section_artistsindex"></a>
            <div class='section_title' id='section_artistsindex_title'>Artists Index</div>
			<div class="section_content" id='section_artistsindex_content'>
                <span class='section_subtitle'>
                    Retina Destruction is a constantly expanding community of visual artists. Currently it's hosting <span class='blackbackground2'><span class='red'><?=$currentUsersCounter?></span></span> artist websites, with a total of <span class='blackbackground2'><span class='red'><?=$currentImagesCounter?></span></span> images.
                </span>
                         
                <span id='section_content_page_artistsindex' class='section_content_page'></span>
                
            </div> 
		</div><!--section_artists_index-->


		<div id="section_featuredartists" class="sections"> 
			<a id='section_anchor_featuredartists' name="section_featuredartists"></a>
            <div class='section_title' id='section_featuredartists_title'>Featured Artists</div>
			<div class="section_content" id='section_featuredartists_content'>
                <span class='section_subtitle'>
                 Presenting the <span class='red'>5</span> most viewed artists of Retina Destruction.
                </span>
                         
                <span id='section_content_page_featuredartists' class='section_content_page'></span>
            </div> 
		</div><!--section_featured_artists-->
        
        
		<? if( !($adminloggedin) && !($rguserloggedin) ){?>
            <div id="section_registration" class="sections"> 
                <a id='section_anchor_registration' name="section_registration"></a>
                <div class='section_title' id='section_registration_title'>Registration</div>
                <div class="section_content" id='section_registration_content'>
                <?php layout_get_registerform($adminloggedin,$rguserloggedin); ?>
                </div> 
            </div><!--section_registration-->
    
            <div id="section_signin" class="sections"> 
                <a id='section_anchor_signin' name="section_signin"></a>
                <div class='section_title' id='section_signin_title'>Sign-In</div>
                <div class="section_content" id='section_signin_content'>
                <?php layout_get_loginform($adminloggedin,$rguserloggedin); ?>
                <?php layout_get_forgotpasswordform($adminloggedin,$rguserloggedin); ?>
                </div> 
            </div><!--section_signin-->
    	<? } ?>		
        
        


		<div id="section_about" class="sections"> 
			<a id='section_anchor_about' name="section_about"></a>
            <div class='section_title' id='section_about_title'>About</div>
			<div class="section_content" id='section_about_content'>              
              	<span>
              	<p>Retina Destruction is part of a final project submission for the <a href='http://ddm.caad.ed.ac.uk/postgradstudy/' title='MSc Design and Digital Media Website.' class='blacklink blackbackground2'>MSc in Design and Digital Media</a> at the <a href='http://www.ed.ac.uk/' title='University of Edinburgh Website.' class='blacklink blackbackground2'>University of Edinburgh</a> (2009-2010).</p>
                <p>This platform was concepted and implemented by <a href='mailto:chantzis.dimitrios@gmail.com' title='chantzis.dimitrios@gmail.com' class='blacklink blackbackground2'>Dimitrios Chantzis</a> (student number: s0972736) during the period of time June 2010 - August 2010.</p>
                
                <div id='universitylogocontainer'>
                	<img id='universitylogo' src='./rdlayout/images/UnivEdinburghLogo.jpg' width='85' height='85' alt='University of Edinburgh Logo' />
                </div>
                
                <div id='dimitrioschantziscontainer'>
                	<a href='http://www.dimitrioschantzis.com' title='Visit my website' class='dchlink'>www.dimitrioschantzis.com</a>
                    <br />
                    <img src='./rdlayout/images/inkstains08.png' class='inkstain' />
                </div>
                </span>                 
			</div> 
		</div><!--section_about-->
     	
        
		<div id="section_contact" class="sections"> 
			<a id='section_anchor_contract' name="section_contact"></a>
            <div class='section_title' id='section_contact_title'>Contact</div>
			<div class="section_content" id='section_contact_content'>
           		<span class='section_subtitle'>
                </span>				
                <span class='section_content_page' id='section_content_page_contact'></span>
			</div> 
		</div><!--section_contact-->

        <? if( !($adminloggedin) && !($rguserloggedin) ){?>
            <div id="section_terms" class="sections">
                <a id='section_anchor_terms' name="section_terms"></a>
                <div class='section_title' id='section_terms_title'>Terms of agreement</div>
                <div class="section_content" id='section_terms_content'>
                    <span class='section_content_page' id='section_content_page_terms'></span>
                </div>       
            </div><!--section_terms-->
        	<? } ?>
      
    </div><!--sections_container-->

	</div><!--mask-->

</div><!--wrapper-->

<?php layout_get_footer($adminloggedin,$rguserloggedin); ?>

<div id='loader_layer'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>

</body>
<div id='eddie' class='displaynone'>main</div>
<div id='clint' class='displaynone'><?=$csrfPasswordGenerator_containerPage?></div><!--clint-->
<? } ?>