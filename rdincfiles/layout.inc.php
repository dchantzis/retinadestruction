<?php
if(isset($_GET['requesting']))
{
	if(!preg_match("/^[0-9]([0-9]*)/",$_GET['requesting'])){$_GET['requesting'] = NULL; }
	else{$get_type = $_GET['requesting']; }
	unset($_GET['requesting']);
	switch($get_type)
	{
		case 1:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			user_session_reset($_SESSION['user']['username'], $_SESSION['user']['email']);
			layout_get_accountsettings_userform($_SESSION['user']);
			break;
		case 2:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			layout_get_accountsettings_websiteform($_SESSION['user']);
			break;
		case 3:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/album.inc.php");
			require("../rdincfiles/image.inc.php");
			require("../rdincfiles/useredit.inc.php");

			$albumData = album_get('aid',$_GET['albumid']); unset($_GET['albumid']);
			layout_get_accountalbum_albumform($_SESSION['user'],$albumData);
			break;
		case 4:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			layout_get_accountsettings_privacyform($_SESSION['user']);
			break;
		case 5:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			layout_get_resetpasswordform($_SESSION['user']['uid']);
			break;
		case 6:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/useredit.inc.php");
			layout_get_uploadimagessettingsform($_SESSION['user']);
			break;
		case 7:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/blog.inc.php");
			require("../rdincfiles/image.inc.php");
			require("../rdincfiles/useredit.inc.php");

			$postData = blog_get('pid',$_GET['postid']); unset($_GET['postid']);
			layout_get_accountblog_blogform($_SESSION['user'],$postData);
			break;
		case 8:
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/blog.inc.php");
			require("../rdincfiles/image.inc.php");
			require("../rdincfiles/useredit.inc.php");

			contact_get_form('portfoliosite',$_GET['uid']);
			break;
		case '9':
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/blog.inc.php");
			require("../rdincfiles/image.inc.php");
			require("../rdincfiles/useredit.inc.php");

			contact_get_form('mainsite','');
			break;
		case '10':
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/blog.inc.php");
			require("../rdincfiles/image.inc.php");
			require("../rdincfiles/useredit.inc.php");

			layout_get_artistindex_contents();
			break;
		case '11':
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/blog.inc.php");
			require("../rdincfiles/image.inc.php");
			require("../rdincfiles/useredit.inc.php");

			layout_get_featuredartists_contents();
			break;
		case '12':
			session_start();
			require("../rdincfiles/rdconfig.inc.php");
			require("../rdincfiles/responses.inc.php");
			require("../rdincfiles/commonfunctions.inc.php");
			require("../rdincfiles/validate.class.inc.php");
			require("../rdincfiles/user.inc.php");
			require("../rdincfiles/blog.inc.php");
			require("../rdincfiles/image.inc.php");
			require("../rdincfiles/useredit.inc.php");

			layout_get_designeditor_settings($_GET['templateid']);
			break;
		default:
			//IMPORTANT TO HAVE NO ACTION
			break;
	}//switch
}//

function layout_get_header_main($adminloggedin,$rguserloggedin)
{
?>
	<div id='header' class='main_header'>

    	<span id='logo'><a href='./index.php' class='link_logo'>Retina Destruction</a></span>
        <!--<span id='search_form'><input type='text' /><span>search</span></span>-->
        <ul id='navigation_main'>
            <li><a id='link_introduction' href='#section_introduction' class='panel'>Introduction</a></li>
            <li><a id='link_artistsindex'href='#section_artistsindex' class='panel'>Artists Index</a></li>
            <li><a id='link_featuredartists'href='#section_featuredartists' class='panel'>Featured Artists</a></li>
            <li><a id='link_about'href='#section_about' class='panel'>About</a></li>
           	<li><a id='link_contact'href='#section_contact' class='panel'>Contact</a></li>
        </ul><!--navigation_main-->

        <span id='credential_options_placeholder'>
            <ul id='credential_options'>
                <? if( !($adminloggedin) && !($rguserloggedin) ){?>
                <li><a id='link_registration' href='#section_registration' class='panel'>register</a></li>
                <li>|</li>
                <li><a id='link_signin' href='#section_signin' class='panel'>sign-in</a></li>
                <? } else { ?>
                    <li><a id='link_username' href='./portfolioindex.php?<?=$_SESSION['user']['username']?>' class='' target="_blank"><?=$_SESSION['user']['username']?></a></li>
                    <li>|</li>
                    <li><a id='link_myaccount' href='./accountmanageindex.php' class=''>my account</a></li>
                    <li>|</li>
                    <li><a id='link_mywebsite' href='./portfolioindex.php?<?=$_SESSION['user']['username']?>' class='' target="_blank">my website</a></li>
                    <li>|</li>
                    <!--<li><span id='link_signout' class=''>sign-out</span></li>-->
                    <li><a id='link_signout' href='./rdincfiles/functionsmapping.inc.php?type=26' class=''>sign-out</a></li>
                <? } ?>
            </ul><!--credential_options-->
        </span><!--credential_options_placeholder-->
    </div><!--header-->
<?php
}//layout_get_header_main($adminloggedin,$rguserloggedin)

function layout_get_header_account($adminloggedin,$rguserloggedin)
{
?>
	<div id='header' class='account_header'>

    	<span id='logo'><a href='./index.php' class='link_logo'>Retina Destruction</a></span>
        <!--<span id='search_form'><input type='text' /><span>search</span></span>-->
        <ul id='navigation_account'>
            <li><a id='link_overview' href='#section_overview' class='panel'>Overview</a></li>
            <li><a id='link_settings'href='#section_settings' class='panel'>Settings</a></li>
            <li><a id='link_albums' href='#section_albums' class='panel'>Albums</a></li>
            <li><a id='link_blog' href='#section_blog' class='panel'>Blog</a></li>
            <!--
            <li><a id='link_about' href='#section_about' class='panel'>About</a></li>
            <li><a id='link_comments' href='#section_comments' class='panel'>Comments</a></li>
            -->
            <li><a id='link_coverpage' href='#section_coverpage' class='panel'>Cover Page</a></li>
            <li><a id='link_designeditor' href='#section_designeditor' class='panel'>Design Editor</a></li>
            <li><a id='link_instructions' href='#section_instructions' class='panel'>Instructions</a></li>
            <!--<li><a id='link_help'href='#' class=''>Help</a></li>-->
        </ul><!--navigation_main-->
        <span id='credential_options_placeholder'>
            <ul id='credential_options'>
                <li><a id='link_username' href='./portfolioindex.php?<?=$_SESSION['user']['username']?>' class='' target="_blank"><?=$_SESSION['user']['username']?></a></li>
                <li>|</li>
                <li><a id='link_myaccount' href='./accountmanageindex.php' class=''>my account</a></li>
                <li>|</li>
                <li><a id='link_mywebsite' href='./portfolioindex.php?<?=$_SESSION['user']['username']?>' class='' target="_blank">my website</a></li>
                <li>|</li>
                <!--<li><span id='link_signout' class=''>sign-out</span></li>-->
                <li><a id='link_signout' href='./rdincfiles/functionsmapping.inc.php?type=26' class=''>sign-out</a></li>
            </ul><!--credential_options-->
        </span><!--credential_options_placeholder-->
    </div><!--header-->
<?php
}//layout_get_header_account($adminloggedin,$rguserloggedin)


function layout_get_header_portfolio($adminloggedin,$rguserloggedin)
{
?>
	<div id='header' class='portfolio_header'>

    	<span id='logo'><a href='./index.php' class='link_logo'>Retina Destruction</a></span>
        <!--<span id='search_form'><input type='text' /><span>search</span></span>-->
        <span id='credential_options_placeholder'>
        	<ul id='credential_options'>
                 <?
				 	if(!$rguserloggedin){
				 ?>
                    <li><a id='link_registration' href='./index.php' class=''>register</a></li>
                    <li>|</li>
                    <li><a id='link_signin' href='./index.php' class=''>sign-in</a></li>
            	<? } else { ?>
                    <li><a id='link_username' href='./portfolioindex.php?<?=$_SESSION['user']['username']?>'><?=$_SESSION['user']['username']?></a></li>
                    <li>|</li>
                    <li><a id='link_myaccount' href='./accountmanageindex.php' class=''>my account</a></li>
                    <li>|</li>
                    <li><a id='link_mywebsite' href='./portfolioindex.php?<?=$_SESSION['user']['username']?>' class=''>my website</a></li>
                    <li>|</li>
                    <!--<li><span id='link_signout' class=''>sign-out</span></li>-->
                    <li><a id='link_signout' href='./rdincfiles/functionsmapping.inc.php?type=35' class=''>sign-out</a></li>
                <? } ?>
            </ul><!--credential_options-->
        </span><!--credential_options_placeholder-->
    </div><!--header-->
<?php
}//($adminloggedin,$rguserloggedin)

##############################
/*FOOTER*/
function layout_get_footer($adminloggedin,$rguserloggedin)
{
?>
<div id='footer'>
   <div class='clear'></div><div class='muchneededhight'></div>
</div><!--footer-->
<?
}//layout_get_footer($adminloggedin,$rguserloggedin)
/* END FOOTER */
##############################


##############################
/*REGISTER FORM*/
function layout_get_registerform($adminloggedin,$rguserloggedin)
{
?>
    <!--REGISTER FORM-->
    <span id='register_container' class=''>
        <span class="form_content" id='register_content'>
            <div class='messages' id='register_frm_messages'></div>

            <ul id='register_frm' class="frm">
                <li>
                <label for='register_username' class="">Username:</label>
                <input type="text" id='register_username' name='register_username' maxlength='50' class="text" />
                <span class='field_messages' id='register_username_message'></span>
				</li>

                <li>
                <label for='register_email' class="">E-mail:</label>
                <input type="text" id='register_email' name='register_email' maxlength='100' class="text" />
                <span class='field_messages' id='register_email_message'></span>
				</li>

                <li>
                <label for='register_password' class="">Password:</label>
                <input type="password" id='register_password' name='register_password' maxlength='25' class="text" />
                <span class='field_messages' id='register_password_message'></span>
				</li>

                <li class='register_terms_container'>
                <input type="hidden" id="register_terms" name="register_terms" class='displaynone' value="unchecked" />

                    <img id='register_terms_button' src='./rdlayout/images/unchecked.gif' />
                    I agree  with the <a id='link_terms' href='#section_terms' class='blacklink credential_togglers'>terms &amp; conditions</a>


                <span class='field_messages' id='register_terms_message'></span>
				</li>

                <li class='register_submit_container'>
                <span id='register_submit_placeholder' class='form_submit_placeholder'>
                <? if( !($adminloggedin) && !($rguserloggedin) ){?>
                    <span class="submit" id='register_submit'>submit</span>
                <? }?>
                </span>
                </li>
            </ul>
            <span class="" id='register_loader_placeholder'></span>

        </span><!--register_content-->
    </span><!--register_container-->
    <!--END REGISTER FORM-->
<?
}//layout_get_registerform()
/*END REGISTER FORM*/
##############################

##############################
/*LOGIN FORM*/
function layout_get_loginform($adminloggedin,$rguserloggedin)
{
?>
    <!--LOGIN FORM-->
    <span id='login_container' class="" >

        <span class="form_content" id='login_content'>
            <div class='messages' id='login_frm_messages'></div>

            <ul id='login_frm' class="frm">
            	<li>
                    <label for='login_email' class="">E-mail:</label>
                    <input type="text" class='text' id='login_email' name='login_email' maxlength='100' />
                    <span class='field_messages' id='login_email_message'></span>
                </li>
                <li>
                    <label for='login_password'>Password:</label>
                    <input type="password" class='text' id='login_password' name='login_password' maxlength='25' />
                    <span class='field_messages' id='register_password_message'></span>
				</li>

                <li class='forgotpassword_toggler_container'>
                	<span id='forgotpassword_toggler' class='blacklink credential_togglers' >Forgot your password?</span>
                </li>

                <li class='login_submit_container'>
                    <span id='login_submit_placeholder' class='form_submit_placeholder'>
                    <? if( !($adminloggedin) && !($rguserloggedin) ){?>
                        <span class="submit" id='login_submit'>Submit</span>
                    <? }?>
                    </span>
                </li>

            </ul>

            <span class='' id='login_loader_placeholder'></span>

        </span><!--login_content-->
    </span><!--login_container-->
    <!--END LOGIN FORM-->
<?
}//layout_get_loginform($adminloggedin,$rguserloggedin)
/*END LOGIN FORM*/
##############################

##############################
/*FORGOT PASSWORD FORM*/
function layout_get_forgotpasswordform($adminloggedin,$rguserloggedin)
{
?>
    <!--FORGOT PASSWORD FORM-->
    <span id='forgotpassword_container' class=''>
        <span class="form_content" id='forgotpassword_content'>
        	<div class='section_subtitle'>Type the e-mail address you specified when you registered. Instructions of how to reset your password would be sent there.</div>
            <div class='messages' id='forgotpassword_frm_messages'></div>

            <ul id='forgotpassword_frm' class="frm">

                <li>
                <label for='forgotpassword_email' class="">E-mail:</label>
                <input type="text" id='forgotpassword_email' name='forgotpassword_email' maxlength='100' class="text" />
                <span class='field_messages' id='forgotpassword_email_message'></span>
                </li>

                <li class='signin_toggler_container'>
                	<span id='signin_toggler' class='blacklink credential_togglers' >Sign-In</span>
                </li>

               <li class='forgotpassword_submit_container'>
                    <span id='forgotpassword_submit_placeholder' class='form_submit_placeholder'>
                    <? if( !($adminloggedin) && !($rguserloggedin) ){?>
                        <span class="submit" id='forgotpassword_submit'>Submit</span>
                    <? }?>
                    </span>
                </li>

            </ul>
            <span class="" id='forgotpassword_loader_placeholder'></span>

        </span><!--forgotpassword_icontent-->
    </span><!--forgotpassword_container-->
    <!--END FORGOT PASSWORD FORM-->
<?php
}//layout_get_forgotpasswordform()
/*END FORGOT PASSWORD FORM*/
##############################

function layout_get_resetpasswordform($sUserID)
{
?>
    <span id='resetpassword_container' class=''>

        <span class="form_content" id='resetpassword_content'>
            <div class='messages' id='resetpassword_frm_messages'></div>
            <span id='resetpassword_frm_loader' class='displaynone'></span>
                <ul id='resetpassword_frm' class="frm">
                <li>
                    <label for='resetpassword_password'>new password: </label>
                    <input type='password' id='resetpassword_password' name='resetpassword_password' class='text' value='' maxlength='25' size="" />
                    <span class='field_messages' id='resetpassword_password_message'></span>
                </li>
                <li>
                    <label for='resetpassword_repeatpassword'>repeat new password: </label>
                    <input type='password' id='resetpassword_repeatpassword' name='resetpassword_repeatpassword' class='text' value='' maxlength='25' size="" />
                    <span class='field_messages' id='resetpassword_repeatpassword_message'></span>
                </li>
                <li id='resetpassword_submit_placeholder'>
                    <input type='hidden' class='displaynone' name='repeatpassword_hash' id='repeatpassword_hash' value='<?=hash('sha256',$sUserID)?>'/>
                    <span class='submit' id='resetpassword_submit'>submit</span>
                </li>
            </ul>

            <span class="" id='resetpassword_loader_placeholder'></span>

		</span><!--resetpassword_content-->
    </span><!--resetpassword_container-->
<?
}//layout_get_resetpasswordform($sUserID)

function layout_get_accountsettings_userform($userData)
{
?>
    <span id='user_container'>

        <span class="form_content" id='user_content'>
            <span class='messages' id='user_frm_messages'>
            	<?php if(isset($_GET['userupdated'])){echo "<span class='highlight_color'>Your account has been updated successfully.</span>"; unset($_GET['userupdated']);}?>
            </span>

            <ul id='user_frm' class="frm">
                <li>
                	<label for='user_username' class="">Username:</label>
                    <span id='user_username' class='text'><?=$userData['username']?></span>
                </li>
                <li>
                	<label for='user_email' class="">E-mail:</label>
                    <span id='user_email' class='text'><?=$userData['email']?></span>
                </li>
            	<li>
                    <label for='user_name' class="">Name:</label>
                    <input type="text" class='text' id='user_name' name='user_name' maxlength='35' value='<?=$userData['name']?>' />
                    <span class='field_messages' id='user_name_message'></span>
                </li>
                <li>
                    <label for='user_description'>Description:</label>
                    <textarea  class="text" id='user_description' name='user_description' cols='' rows=''><?=$userData['description']?></textarea>
                   	<br />
                    <span id='user_charcountercontainer' class='charcountercontainers'>
                        <span class='counters' id='user_description_counter'><?=PROFILE_DESCRIPTION_MAX_LENGTH?> </span>
                        <span class='charcountertexts' id='user_charcountertext'>remaining characters</span>
                    </span>
                    <span class='field_messages' id='user_description_message'></span>
				</li>
                <li class='clear'></li>

       <li id='user_cover_frm'>
        	<label for='user_cover'>profile image: </label>
            <div id='user_uploader_container'>
            	<span id='spanButtonPlaceholderContainerCover'>
            		<span id="spanButtonPlaceholderCover"></span>
            	</span>
            </div>
            <div id='user_uploadprogress_container'>
        		<div id="divFileProgressContainerCover"></div>
            </div>
            <div id='user_uploadedcover_placeholder'>
            	<?php
                	if($userData['coverimage'] == DEFAULT_BLANK_USER_THUMBNAIL)
						{$coverImageSrc = $userData['coverimage'];}
					else
						{$coverImageSrc = './rdusers/'.$userData['username']."/".IMAGES_FOLDER_COVER_IMAGES."/".$userData['coverimage'];}
				?>
            	<img src='<?=$coverImageSrc?>' id='user_uploadedcover' class='thumbsimages' width='300' height='200' />
                <div id='usercover_dimensions'>(cover dimensions: <b>300x200</b>)</div>
            </div>
            <div class='clear'></div>
        </li>
        <li class='clear'></li>

                <li>
                	<label for=''>Artist Category:</label>
                    <?php user_get_artistcategories($userData['tags'])?>
                </li>
                <li class='clear'></li>
                <li>
                	<label for=''>Gender:</label>
                    <div id='profileartistgenders'>
                		<span id='profileartistgender_frm_messages' class='messages'></span>
						<span id='profileartistgender_frm' class=''>
                            <?php if($userData['gender'] == 'male'){$genderMaleFlag = 'selectedtag';}
								if($userData['gender'] == 'female'){$genderFemaleFlag = 'selectedtag';} ?>
                    		<span id='profileartistgender_male' class='profileartistgender <?=$genderMaleFlag?>' >male</span> / <span id='profileartistgender_female' class='profileartistgender <?=$genderFemaleFlag?>'>female</span>
                            <span class='field_messages' id='profileartistgender_message'></span>
                        </span>
                        <span class='' id='profileartistgender_loader_placeholder'></span>
                    </div>
                </li>
                <li class='clear'></li>
                <li>
                	<label for='user_facebook'>facebook account:</label>
                    <span class='url_prefix'>http://www.facebook.com/</span><input type="text" class='text3' id='user_facebook' name='user_facebook' maxlength='25' value='<?=$userData['facebook']?>' />
                    <span class='field_messages' id='user_facebook_message'></span>
                </li>
                <li class='clear'></li>
                <li>
                	<label for='user_myspace'>myspace account:</label>
                    <span class='url_prefix'>http://www.myspace.com/</span><input type="text" class='text3' id='user_myspace' name='user_myspace' maxlength='25' value='<?=$userData['myspace']?>' />
                    <span class='field_messages' id='user_myspace_message'></span>
                </li>
                <li class='clear'></li>
                <li>
                	<label for='user_twitter'>twitter feed:</label>
                    <span class='url_prefix'>http://www.twitter.com/</span><input type="text" class='text3' id='user_twitter' name='user_twitter' maxlength='25' value='<?=$userData['twitter']?>' />
                    <span class='field_messages' id='user_twitter_message'></span>
                </li>
                <li class='clear'></li>
                <li>
                	<label for='user_youtube'>youtube channel:</label>
                    <span class='url_prefix'>http://www.youtube.com/</span><input type="text" class='text3' id='user_youtube' name='user_youtube' maxlength='25' value='<?=$userData['youtube']?>' />
                    <span class='field_messages' id='user_youtube_message'></span>
                </li>
                <li class='clear'></li>
				<li>
                	<label for='user_vimeo'>vimeo account:</label>
                    <span class='url_prefix'>http://www.vimeo.com/</span><input type="text" class='text3' id='user_vimeo' name='user_vimeo' maxlength='25' value='<?=$userData['vimeo']?>' />
                    <span class='field_messages' id='user_vimeo_message'></span>
                </li>
                <li class='clear'></li>
<!--
                <li>
                	<label for=''>newsletter service:</label>
                    <div id='profileartistnewsletters'>
                		<span id='profileartistnewsletter_frm_messages' class='messages'></span>
						<span id='profileartistnewsletter_frm' class=''>
                        	<?php if($userData['newsletter'] == 'enabled'){$enabledFlag = 'selectedtag';}
								if($userData['newsletter'] == 'disabled'){$disabledFlag = 'selectedtag';} ?>
                    		<span id='profileartistnewsletter_enabled' class='profileartistnewsletter <?=$enabledFlag?>' >yes</span> / <span id='profileartistnewsletter_disabled' class='profileartistnewsletter <?=$disabledFlag?>'>no</span>
                            <div class='field_tip'>(people subscribed to your newsletter, will get notified for every blog post you create)</div>
                            <span class='field_messages' id='profileartistnewsletter_message'></span>
                        </span>
                        <span class='' id='profileartistnewsletter_loader_placeholder'></span>
                    </div>
                </li>
-->
                <li class='clear'></li>
                <li id='user_submit_placeholder' class='form_submit_placeholder'>
                    <span class="submit" id='user_submit'>Submit</span>
                </li>
            </ul>

            <span class='' id='user_loader_placeholder'></span>

        </span><!--login_content-->
    </span><!--login_container-->
    <div class='clear'></div><div class='muchneededhight'></div>

    <script language="javascript" type="text/javascript">
        username = $('#user_username').html();
        email = $('#user_email').html();

        swfuFunction_settings(username,email);
    </script>
<?
}//layout_get_accountsettings_userform($userData)

function layout_get_accountsettings_websiteform($userData)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	//require_once('emailfunctionsinc.php');

	$validator = new Validate();
	$dbVars = array();

	$dbobj = new TBDBase(1);

	$selectQuery = "SELECT * FROM website,user WHERE user.uid = website.uid AND user.username='".$userData['username']."' AND user.email='".$userData['email']."'; ";
	$dbVars = $dbobj->executeSelectQuery($selectQuery);

	for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
	{
		$websiteData['title'] = $dbVars['RESULT'][$i]['title'];
		$websiteData['urltitle'] = $dbVars['RESULT'][$i]['urltitle'];
	}

	unset($dbobj);
?>
    <span id='website_container' class="websiteform_container" >

        <span class="form_content" id='website_content'>
            <span class='messages' id='website_frm_messages'>
				<?php if(isset($_GET['websiteupdated'])){echo "<span class='highlight_color'>Your website settings have been updated successfully.</span>"; unset($_GET['websiteupdated']);}?>
            </span>

            <ul id='website_frm' class="frm">
                <li class='displaynone'><span id='website_username' class='displaynone'><?=$userData['username']?></span>
                <span id='website_email' class='displaynone'><?=$userData['email']?></span></li>

                <li>
                    <label for='website_title'>title:</label>
                    <input type="text" class='text' id='website_title' name='website_title' maxlength='25' value='<?=$websiteData['title']?>' />
                    <span class='field_messages' id='website_title_message'></span>
				</li>
                <li id='website_submit_placeholder' class='form_submit_placeholder'>
                    <span class="submit" id='website_submit'>Submit</span>
                </li>
                                <!--
                <li>
                    <label for='website_urltitle'>unique URL:</label>
                    <span id='urltitle_prefix' class='url_prefix'><?php echo SERVER_NAME ?><span class='highlight_color'><?=$userData['username']?></span>/</span><input type="text" class='text2' id='website_urltitle' name='website_urltitle' maxlength='25' value='<?=$websiteData['urltitle']?>' />
                    <span class='field_messages' id='website_urltitle_message'></span>
				</li>
                -->
            </ul>

            <span class='' id='website_loader_placeholder'></span>

        </span><!--login_content-->
    </span><!--login_container-->
<?
}//layout_get_accountsettings_websiteform($userData)


function layout_get_accountsettings_privacyform($userData)
{
?>
    <span id='accountsettings_container' id="privacyform_container" >

        <span class="form_content" id='privacy_container'>
            <span class='messages' id='privacy_frm_messages'></span>

            <ul id='privacy_frm' class="frm">
                <li class='displaynone'><span id='privacy_username' class='displaynone'><?=$userData['username']?></span>
                <span id='privacy_email' class='displaynone'><?=$userData['email']?></span></li>

                <li>
                	<label for=''>Allow people to comment on my albums:</label>
                    <div id='profileartistalbumcommentss'>
                		<span id='profileartistalbumcomments_frm_messages' class='messages'></span>
						<span id='profileartistalbumcomments_frm' class=''>
                        	<?php if($userData['albumcomments'] == 'enabled'){$enabledFlag1 = 'selectedtag';}
								if($userData['albumcomments'] == 'disabled'){$disabledFlag1 = 'selectedtag';} ?>
                    		<span id='profileartistalbumcomments_enabled' class='profileartistalbumcomments <?=$enabledFlag1?>' >yes</span> / <span id='profileartistalbumcomments_disabled' class='profileartistalbumcomments <?=$disabledFlag1?>'>no</span>
                            <span class='field_messages' id='profileartistalbumcomments_message'></span>
                        </span>
                        <span class='' id='profileartistalbumcomments_loader_placeholder'></span>
                    </div>
                </li>
				<li class='clear'></li>

                <li>
                	<label for=''>Allow people to comment on my blog posts:</label>
                    <div id='profileartistblogpostcommentss'>
                		<span id='profileartistblogpostcomments_frm_messages' class='messages'></span>
						<span id='profileartistblogpostcomments_frm' class=''>
                        	<?php if($userData['blogpostcomments'] == 'enabled'){$enabledFlag2 = 'selectedtag';}
								if($userData['blogpostcomments'] == 'disabled'){$disabledFlag2 = 'selectedtag';} ?>
                    		<span id='profileartistblogpostcomments_enabled' class='profileartistblogpostcomments <?=$enabledFlag2?>' >yes</span> / <span id='profileartistblogpostcomments_disabled' class='profileartistblogpostcomments <?=$disabledFlag2?>'>no</span>
                            <span class='field_messages' id='profileartistblogpostcomments_message'></span>
                        </span>
                        <span class='' id='profileartistblogpostcomments_loader_placeholder'></span>
                    </div>
                </li>
				<li class='clear'></li>

                <li>
                	<label for=''>Notify me via email for every new comment:</label>
                    <div id='profileartistcommentnotificationss'>
                		<span id='profileartistcommentnotifications_frm_messages' class='messages'></span>
						<span id='profileartistcommentnotifications_frm' class=''>
                        	<?php if($userData['commentnotifications'] == 'enabled'){$enabledFlag3 = 'selectedtag';}
								if($userData['commentnotifications'] == 'disabled'){$disabledFlag3 = 'selectedtag';} ?>
                    		<span id='profileartistcommentnotifications_enabled' class='profileartistcommentnotifications <?=$enabledFlag3?>' >yes</span> / <span id='profileartistcommentnotifications_disabled' class='profileartistcommentnotifications <?=$disabledFlag3?>'>no</span>
                            <span class='field_messages' id='profileartistcommentnotifications_message'></span>
                        </span>
                        <span class='' id='profileartistcommentnotifications_loader_placeholder'></span>
                    </div>
                </li>
				<li class='clear'></li>

                <li>
                	<label for=''>Your account is:</label>
                    <div id='profileartistvisibilitys'>
                		<span id='profileartistvisibility_frm_messages' class='messages'></span>
						<span id='profileartistvisibility_frm' class=''>
                        	<?php if($userData['visibilitystatus'] == 'visible'){$visibleFlag = 'selectedtag';}
								if($userData['visibilitystatus'] == 'invisible'){$invisibleFlag = 'selectedtag';} ?>
                    		<span id='profileartistvisibility_visible' class='profileartistvisibility <?=$visibleFlag?>' >visible</span> / <span id='profileartistvisibility_invisible' class='profileartistvisibility <?=$invisibleFlag?>'>invisible</span>
                            <span class='field_messages' id='profileartistvisibility_message'></span>
                        </span>
                        <span class='' id='profileartistvisibility_loader_placeholder'></span>
                    </div>
                </li>

                <li class='clear'></li>
            </ul>

            <span class='' id='privacy_loader_placeholder'></span>

        </span><!--login_content-->
    </span><!--login_container-->
<?
}//layout_get_accountsettings_privacyform($userData)

function layout_get_uploadimagessettingsform($userData)
{
?>
    <span id='uploadimagessettings_container' class=''>
        <div id='uploadimagessettingsform_instructions'>
        	These settings refer to the images that you upload.<br />
            Any changes to these settings will reflect your next image uploads.
        </div>
        <span class="form_content" id='uploadimagessettings_content'>
            	<span class='messages' id='uploadimagessettings_frm_messages'><?php if(isset($_GET['websiteupdated'])){echo "<span class='highlight_color'>Your image settings have been updated successfully.</span>"; unset($_GET['websiteupdated']);}?>
</span>
            	<span id='uploadimagessettings_frm_loader' class='displaynone'></span>

                <span id='settings_username' class='displaynone'><?=$userData['username']?></span>
                <span id='settings_email' class='displaynone'><?=$userData['email']?></span>

                <ul id='uploadimagessettings_frm' class="frm">
                <li>
                    <label for='uploadimagessettings_uploadimageswidth'>width: </label>
                    <input type='text' id='uploadimagessettings_uploadimageswidth' name='uploadimagessettings_uploadimageswidth' class='text7' value='<?=$userData['uploadimageswidth']?>' maxlength='4' size="" />px
                    <span class='field_messages' id='uploadimagessettings_uploadimageswidth_message'></span>
                    <div id='uploadimagessettings_uploadimageswidth_instructions'>
                    	Default value: <b>800px</b>. Max is: <b>1024px</b>, Min is: <b>350</b>
                    	<br />
                        Images smaller than the width value, <br />will not get resized.
                    </div>
                </li>
                <li class='clear'></li>
                <li>
                    <label for='uploadimagessettings_resizetype'>resize type: </label>
                    <div id='uploadimagessettingsoptions' class="frm">
                    	<span id='uploadimagessettingsoptions_frm_messages' class='messages'></span>
                        <span id='uploadimagessettingsoptions_frm' class=''>
							<?php
                                if($userData['uploadimagestype'] == '1'){$flagOption1 = 'selectedtag';}
                                else if($userData['uploadimagestype'] == '2'){$flagOption2 = 'selectedtag';}
                                else if($userData['uploadimagestype'] == '3'){$flagOption3 = 'selectedtag';}
                            ?>
                            <span id='uploadimagessettingsoption_option1' class='uploadimagessettingsoption <?=$flagOption1?>'>
                                Vertical images <b>width = <?=$userData['uploadimageswidth']?>px</b>,<br />
                                Horizontal images <b>width = <?=$userData['uploadimageswidth']?>px</b>
                            </span>
                            <br /><br />
                            <span id='uploadimagessettingsoption_option2' class='uploadimagessettingsoption <?=$flagOption2?>'>
                                Vertical images <b>height = <?=$userData['uploadimageswidth']?>px</b>,<br />
                                Horizontal images <b>height = <?=$userData['uploadimageswidth']?>px</b>
                            </span>
                            <br /><br />
                            <span id='uploadimagessettingsoption_option3' class='uploadimagessettingsoption <?=$flagOption3?>'>
                                Vertical images <b>width = <?=$userData['uploadimageswidth']?>px</b><br />
                                Horizontal images <b>height = <?=$userData['uploadimageswidth']?>px</b>
                            </span>
                    	</span>
                    </div>
                </li>
                <li class='clear'></li>
                <li>
                    <span class='submit' id='uploadimagessettings_submit'>submit</span>
                </li>
            </ul>

            <span class="" id='uploadimagessettings_loader_placeholder'></span>

		</span><!--resetpassword_content-->
    </span><!--resetpassword_container-->
<?
}//layout_get_uploadimagessettingsform($userData)


function layout_get_accountalbum_albumform($userData,$albumData)
{
	if($albumData['views'] == 1){$albumData['views'] = "".$albumData['views']." view";}
	else{$albumData['views'] = "".$albumData['views']." views";}
?>
<div id='album_list_button' class='simple'><span class='blackbackground'>&lt;</span> Album List</div>
<span class="album_content" id='album_content'>
	<div class='titles'>Album information</div>
    <span class='messages' id='album_frm_messages'>
    	<?php if( isset($_GET['albumupdated']) && $_GET['albumupdated']==1){echo "<span class='highlight_color'>Album updated successfully.</span>"; unset($_GET['albumupdated']);}?>
    </span>
    <div class='clear'></div>
    <span id='album_frm_loader' class='displaynone'></span>
	<ul id='album_frm' class='frm'>
    	<li>
        	<span id='user_username' class='displaynone'><?=$userData['username']?></span>
        	<span id='user_email' class='displaynone'><?=$userData['email']?></span>
            <span id='album_aid' class='displaynone'><?=$albumData['aid']?></span>
        </li>

        <li class='staticfrmfields'>
        	Created: <span class='timestamp'><?=convertTimeStamp($albumData['creationtimestamp'],'reallyshortwithtime')?></span>,
            Updated: <span class='timestamp'><?=convertTimeStamp($albumData['lastupdatedtimestamp'],'reallyshortwithtime')?></span>,
            <?=$albumData['views']?>
        </li>
        <?php
			switch($albumData['type'])
			{
				case 'regular':
					//do nothing
					break;
				case 'blogpostsimagesalbum':
					echo "<li class='albumtype'>";
					echo "Blog images album";
					echo "</li>";
					break;
				case 'coverpageimagesalbum':
					echo "<li class='albumtype'>";
					echo "Cover page images album";
					echo "</li>";
					break;
				default:
					//do nothing
					break;
			}//switch
		?>
        <!--
        <li class='staticfrmfields2'>
        	<?php
			$visibilityClass = 'albumvisible';
			if($albumData['visibilitystatus'] == 'invisible'){$visibilityClass = 'albuminvisible';}
			?>
        	<span class='selectedtag'><span id='album_visibility_<?=$albumData['aid']?>' class='album_visibilitys <?=$visibilityClass?>'><?=$albumData['visibilitystatus']?></span></span>

        </li>
        -->
        <li class='clear'></li>
        <li>
        	<label for='album_name'>Title:</label>
            <input type='text' id='album_name' class='text4' value='<?=$albumData['name']?>' maxlength='25' />
            <span class='field_messages' id='album_name_message'></span>
        </li>
        <li class='clear'></li>
    	<li>
        	<label for='album_category'>Category:</label>
            <input type='text' id='album_category' class='text4' value='<?=$albumData['categoryname']?>' maxlength='15' />
            <span class='field_messages' id='album_category_message'></span>
        </li>
        <li class='clear'></li>
    	<li>
        	<label for='album_description'>Description:</label>
            <textarea  class="text5" id='album_description' name='album_description' cols='' rows=''><?=$albumData['description']?></textarea>
            <br />
            <span id='album_charcountercontainer' class='charcountercontainers'>
            	<span class='counters' id='album_description_counter'><?=ALBUM_DESCRIPTION_MAX_LENGTH?> </span>
                <span class='charcountertexts' id='album_charcountertext'>remaining characters</span>
            </span>
           	<span class='field_messages' id='album_description_message'></span>
        </li>

        <li class='clear'></li>

    	<li>
        	<label id='album_embeddedvideos_label' for='album_embeddedvideos'>Videos (add embed code from <b>youtube</b>, <b>vimeo</b>, etc):</label>
            <textarea  class="text5" id='album_embeddedvideos' name='album_embeddedvideos' cols='' rows=''><?=$albumData['embeddedvideos']?></textarea>
            <br />
           	<span class='field_messages' id='album_embeddedvideos_message'></span>
        </li>

        <li class='clear'></li>

		<li>
			<span class='submit' id='album_submit'>submit</span>
		</li>
        <li class='clear'><br/></li>

       	<li id='album_cover_frm'>
        	<label for='album_cover'>Cover: </label>
            <div id='album_uploader_container'>
            	<span id='spanButtonPlaceholderContainerCover'>
            		<span id="spanButtonPlaceholderCover"></span>
            	</span>
            </div>
            <div id='album_uploadprogress_container'>
        		<div id="divFileProgressContainerCover"></div>
            </div>
            <div class='clear'></div>
            <div id='album_uploadedcover_placeholder'>
            	<?php
                	if($albumData['coverimage'] == DEFAULT_BLANK_COVER_THUMBNAIL)
						{$coverImageSrc = $albumData['coverimage'];}
					else
						{$coverImageSrc = './rdusers/'.$userData['username']."/".IMAGES_FOLDER_COVER_IMAGES."/".$albumData['coverimage'];}
				?>
            	<img src='<?=$coverImageSrc?>' id='album_uploadedcover' class='thumbsimages' width='200' height='134' />
                <div id='albumcover_dimensions'>(cover dimensions: <b>200x137</b>)</div>
            </div>
            <div class='clear'></div>
        </li>

    </ul>

	<div class='seperetor clear'></div>

    <ul id='album_imageview_ul'>
        <li id='label_albumimageview'>
        <label for=''>Show album images as: </label>
        <div id='album_imageview'>
            <span id='album_imageview_frm_messages' class='messages'></span>
            <span id='album_imageview_frm' class=''>
                <?php
                    switch($albumData['imageview']){
                        case 'fullsize': $fullsize_flag = 'selectedtag'; break;
                        case 'thumbnails': $thumbnails_flag = 'selectedtag'; break;
                        case 'largethumbnails': $largethumbnails_flag = 'selectedtag'; break;
                    }//switch()
                ?>
                <span id='album_imageview_fullsize' class='album_imageviews <?=$fullsize_flag?>' >fullsize</span>
                <span id='album_imageview_thumbnails' class='album_imageviews <?=$thumbnails_flag?>'>thumbnails</span>
                <span id='album_imageview_largethumbnails' class='album_imageviews <?=$largethumbnails_flag?>'>large thumbnails</span>

                <span class='field_messages' id='profileartistcommentnotifications_message'></span>
            </span>
            <span class='' id='largethumbnails_flag_loader_placeholder'></span>
        </div>
    </li>
    </ul>

    <?php layout_get_accountalbum_imagesform($userData,$albumData); ?>

	<span class="" id='album_loader_placeholder'></span>
</span>

<?

}//layout_get_accountalbum_albumform($userData,$albumID)

function layout_get_accountalbum_imagesform($userData,$albumData)
{
$_SESSION["file_info"] = array();
?>
	<div class='titles'>Images</div>
	<ul id='albumimage_frm' class='frm'>
    	<li id='albumimage_options'>
        	<span id='albumimage_reset' class='albumimage_option'>edit images</span>
        	<span id='albumimage_add' class='albumimage_option selectedtag'>add</span>
            <span id='albumimage_reorder' class='albumimage_option'>reorder</span>
            <span id='albumimage_delete' class='albumimage_option'>delete</span>
        </li>
        <li class='clear'></li>
        <li id='albumimage_delete_container' class='displaynone'>
        	<span id='albumimage_delete_moretext'>Select images to delete</span>
        		(<span id='albumimage_delete_counter'>0</span>
            	<span id='albumimage_delete_text'>images selected</span>)
            <span id='albumimagedelete_frm'>
            	<span id='albumimage_delete_submit' class='submit'>delete</span>
                <span id='albumimages_deleteorder' class='displaynone'></span>
            </span>
        </li>
        <li class='clear'></li>
        <li id='albumimage_reorder_container' class='displaynone'>
        	<span id='albumimage_reorder_text'>Drag your images around to rearange their order.</span>
            <span id='albumimagereorder_frm'>
           	 <span id='albumimages_order' class='displaynone'></span>
        	</span>
        </li>
        <li class='clear'></li>
        <li	id='albumimage_uploader_container'>
            <div id='spanButtonPlaceholderContainer'>
            	<span id="spanButtonPlaceholder"></span>
            </div>
        </li>
        <li class='clear'></li>
        <li id='albumimage_uploadprogress_container'>
        	<div id="divFileProgressContainer">
            </div>
        </li>
    </ul>
    <div class='clear'></div>
    <? get_image_list($userData['uid'],$albumData['aid'],0,'thumbnails','edit')?>
   <div class='clear'></div>
   <div class='muchneededhight'></div>

	<script language="javascript" type="text/javascript">
        username = $('#user_username').html();
        email = $('#user_email').html();
        albumid = $('#album_aid').html();

        swfuFunction_album(username,email,albumid);
    </script>
<?
}//layout_get_accountalbum_imagesform()






function layout_get_accountblog_blogform($userData,$postData)
{
	if($postData['views'] == 1){$postData['views'] = "".$postData['views']." view";}
	else{$postData['views'] = "".$postData['views']." views";}
?>
<span class="blog_content" id='blog_content'>
	<div class='titles'>Blog entry information</div>
    <span class='messages' id='blog_frm_messages'>
    	<?php if(isset($_GET['blogupdated']) && $_GET['blogupdated']==1){echo "<span class='highlight_color'>Blog entry updated successfully.</span>"; unset($_GET['blogupdated']);}?>
    </span>
    <div class='clear'></div>
    <span id='blog_frm_loader' class='displaynone'></span>

	<ul id='blog_frm' class='frm'>
    	<li>
        	<span id='user_username' class='displaynone'><?=$userData['username']?></span>
        	<span id='user_email' class='displaynone'><?=$userData['email']?></span>
            <span id='blog_pid' class='displaynone'><?=$postData['pid']?></span>
        </li>

        <li class='staticfrmfields'>
        	Created: <span class='timestamp'><?=convertTimeStamp($postData['creationtimestamp'],'reallyshortwithtime')?></span>,
            Updated: <span class='timestamp'><?=convertTimeStamp($postData['lastupdatedtimestamp'],'reallyshortwithtime')?></span>,
            <?=$postData['views']?>
        </li>

        <?php
    	if($postData['type'] == 'imagesupdate')
			{echo "<li><div class='imagesupdate_blog_entry_instructions'>This is an <i><b>images update</b></i> blog entry. <br />
			It's created automatically everytime you upload a new image in an album. <br />
			All images that were uploaded within 24 hours are grouped in the same blog entry.</div></li>";}
		?>

        <!--
        <li class='staticfrmfields2'>
        	<?php
			$visibilityClass = 'blogvisible';
			if($albumData['visibilitystatus'] == 'invisible'){$visibilityClass = 'bloginvisible';}
			?>
        	<span class='selectedtag'><span id='blog_visibility_<?=$albumData['pid']?>' class='blog_visibilitys <?=$visibilityClass?>'><?=$postData['visibilitystatus']?></span></span>

        </li>
        -->
        <li class='clear'></li>
        <li>
        	<label for='blog_headline'>Headline:</label>
            <input type='text' id='blog_headline' class='text4' value='<?=$postData['headline']?>' maxlength='25' />
            <span class='field_messages' id='blog_headline_message'></span>
        </li>
        <li class='clear'></li>
    	<li>
        	<label for='blog_category'>Tags:</label>
            <input type='text' id='blog_category' class='text4' value='<?=$postData['tags']?>' maxlength='40' />
            <span class='blog_category_instructions'>seperate tags with comma (,)</span>
            <span class='field_messages' id='blog_category_message'></span>
        </li>
        <li class='clear'></li>
    	<li>
        	<label for='blog_body'>Message:</label>
            <textarea  class="text5" id='blog_body' name='blog_body' cols='' rows=''><?=$postData['body']?></textarea>
            <br />
            <span id='blog_charcountercontainer' class='charcountercontainers'>
            	<span class='counters' id='blog_body_counter'><?=POST_MESSAGE_MAX_LENGTH?> </span>
                <span class='charcountertexts' id='blog_charcountertext'>remaining characters</span>
            </span>
           	<span class='field_messages' id='blog_body_message'></span>
        </li>
        <li class='clear'></li>
        <li>
        	<label id='blog_embeddedvideos_label' for='blog_embeddedvideos'>Videos (add embed code from <b>youtube</b>, <b>vimeo</b>, etc):</label>
            <textarea  class="text5" id='blog_embeddedvideos' name='blog_embeddedvideos' cols='' rows=''><?=$postData['embeddedvideos']?></textarea>
           	<span class='field_messages' id='blog_embeddedvideos_message'></span>
        </li>
        <li class='clear'></li>

		<li>
        	<br/>
			<span class='submit' id='blog_submit'>submit</span>
		</li>
        <li class='clear'><br/></li>

    </ul>

	<div class='seperetor clear'></div>

    <ul id='blog_imageview_ul'>
        <li id='label_blogimageview'>
        <label for=''>Show blog images as: </label>
        <div id='blog_imageview'>
            <span id='blog_imageview_frm_messages' class='messages'></span>
            <span id='blog_imageview_frm' class=''>
                <?php
                    switch($postData['imageview']){
                        case 'fullsize': $fullsize_flag = 'selectedtag'; break;
                        case 'thumbnails': $thumbnails_flag = 'selectedtag'; break;
                        case 'largethumbnails': $largethumbnails_flag = 'selectedtag'; break;
                    }//switch()
                ?>
                <span id='blog_imageview_fullsize' class='blog_imageviews <?=$fullsize_flag?>' >fullsize</span>
                <span id='blog_imageview_thumbnails' class='blog_imageviews <?=$thumbnails_flag?>'>thumbnails</span>
                <span id='blog_imageview_largethumbnails' class='blog_imageviews <?=$largethumbnails_flag?>'>large thumbnails</span>

                <span class='field_messages' id='profileartistcommentnotifications_message'></span>
            </span>
            <span class='' id='largethumbnails_flag_loader_placeholder'></span>
        </div>
    </li>
    </ul>

    <? layout_get_accountblog_imagesform($userData,$postData); ?>

	<span class="" id='blog_loader_placeholder'></span>
</span>

<?
}//layout_get_accountblog_blogform($userData,$postData)

function layout_get_accountblog_imagesform($userData,$postData)
{
$_SESSION["file_info"] = array();
?>
	<div class='titles'>Images</div>

<?php
	if($postData['type'] != 'imagesupdate')
	{
?>
	<ul id='blogimage_frm' class='frm'>
    	<li id='blogimage_options'>
        	<span id='blogimage_reset' class='blogimage_option'>edit images</span>
        	<span id='blogimage_add' class='blogimage_option selectedtag'>add</span>
            <span id='blogimage_delete' class='blogimage_option'>delete</span>
        </li>
        <li class='clear'></li>
        <li id='blogimage_delete_container' class='displaynone'>
        	<span id='blogimage_delete_moretext'>Select images to delete</span>
        		(<span id='blogimage_delete_counter'>0</span>
            	<span id='blogimage_delete_text'>images selected</span>)
            <span id='blogimagedelete_frm'>
            	<span id='blogimage_delete_submit' class='submit'>delete</span>
                <span id='blogimages_deleteorder' class='displaynone'></span>
            </span>
        </li>
        <li class='clear'></li>
        <li	id='blogimage_uploader_container'>
            <div id='spanButtonPlaceholderContainer'>
            	<span id="spanButtonPlaceholder"></span>
            </div>
        </li>
        <li class='clear'></li>
        <li id='blogimage_uploadprogress_container'>
        	<div id="divFileProgressContainer">
            </div>
        </li>
    </ul>
<?php
	}//if
?>
    <div class='clear'></div>
   <? get_image_list($userData['uid'],0,$postData['pid'],'thumbnails','edit');?>
   <div class='clear'></div>
   <div class='muchneededhight'></div>


	<script language="javascript" type="text/javascript">
        username = $('#user_username').html();
        email = $('#user_email').html();
        postid = $('#blog_pid').html();

        swfuFunction_blog(username,email,postid);
    </script>

<?
}//layout_get_accountblog_imagesform($userData,$postData)






function layout_get_accountcoverpage_imagesform($userData,$albumID)
{
$_SESSION["file_info"] = array();
?>
	<div class='titles'>Images</div>
	<ul id='coverpageimage_frm' class='frm'>
    	<li class='displaynone'>
            <span id='user_username' class=''><?=$userData['username']?></span>
        	<span id='user_email' class=''><?=$userData['email']?></span>
        </li>
    	<li id='coverpageimage_options'>
        	<span id='coverpageimage_reset' class='blogimage_option'>edit images</span>
        	<span id='coverpageimage_add' class='coverpageimage_options selectedtag'>add</span>
            <span id='coverpageimage_delete' class='coverpageimage_options'>delete</span>
        </li>
        <li class='clear'></li>
        <li id='coverpageimage_delete_container' class='displaynone'>
        	<span id='coverpageimage_delete_moretext'>Select images to delete</span>
        		(<span id='coverpageimages_delete_counter'>0</span>
            	<span id='coverpageimage_delete_text'>images selected</span>)
            <span id='coverpageimagedelete_frm'>
            	<span id='coverpageimage_delete_submit' class='submit'>delete</span>
                <span id='coverpageimages_deleteorder' class='displaynone'></span>
            </span>
        </li>

        <li class='clear'></li>
        <li	id='coverpageimage_uploader_container'>
            <div id='spanButtonPlaceholderContainer'>
            	<span id="spanButtonPlaceholder"></span>
            </div>
        </li>
        <li class='clear'></li>
        <li id='coverpageimage_uploadprogress_container'>
        	<div id="divFileProgressContainer">
            </div>
        </li>
    </ul>
    <div class='clear'></div>
   	<? get_image_list($userData['uid'],$albumID,0,'coverpage_thumbnails','edit'); ?>
   <div class='clear'></div>
   <div class='muchneededhight'></div>

	<script language="javascript" type="text/javascript">
        username = $('#user_username').html();
        email = $('#user_email').html();

        swfuFunction_coverpage(username,email);
    </script>

<?
}//layout_get_accountcoverpage_imagesform($userData,$albumID)







function layout_get_portfolio_website_design($websiteInfo,$userData)
{
//wallpaper:image:..::..::wallpapercolor:FFFFFF:..::..::logo:text_image:..::..::sidebarorientation:left:..::..::text:textcolor_5F5F5F:..::..::text:linkcolor_000000:..::..::text:linkbgcolor_FFFFFF:..::..::text:linkhovercolor_ff0000:..::..::text:linkhoverbgcolor_000000:..::..::text:navilinksize_small:..::..::sectionsbg:trans_white_light:..::..::text:fontfamily_Georgia,"Times New Roman",Times,serif:..::..::text:highlightcolor_ff0000:..::..::text:bghighlightcolor_FFFF00

	//get all the design parameters
	$design_form_options = explode(':..::..::',$websiteInfo['designformoptions']);
	if($design_form_options[(count($design_form_options)-1)] == ''){unset($design_form_options[(count($design_form_options)-1)]); }
	//GET THE WEBSITE BACKGROUND IMAGE
	$website_wallpaper = explode(':',$design_form_options[0]);
	if($website_wallpaper[1] == 'image'){$websiteDesign['wallpaper'] = $websiteInfo['wallpaper'];}
	else{$websiteDesign['wallpaper'] = '';}
	//GET IF THERE IS A WALLPAPER IMAGE OR NOT
	$website_wallpaper_status = explode(':',$design_form_options[14]);
	if($website_wallpaper_status[1] == 'image'){$websiteDesign['wallpapertype'] = 'image';}
	else{$websiteDesign['wallpapertype'] = 'empty';}
	//GET THE WEBSITE BACKGROUND COLOR
	$website_wallpaper = explode(':',$design_form_options[1]);
	$websiteDesign['bg_color'] = "#".$website_wallpaper[1];
	//GET THE WEBSITE LOGO, WEBSITE_TITLE, IMAGE OR USERNAME
	$website_logo = explode(':',$design_form_options[2]);
	$website_logo_type = explode('_',$website_logo[1]);
	if($website_logo_type[1] == 'image'){$websiteDesign['logo'] = $websiteInfo['logo'];}
		else if($website_logo_type[1] == 'websitename'){$websiteDesign['logo'] = 'websitename';}
		else if($website_logo_type[1] == 'username'){$websiteDesign['logo'] = 'username';}
	//GET THE SIDEBAR ORIENTATION, sidebarleft, sidebarright or sidebartop
	$website_sidebarorientation = explode(':',$design_form_options[3]);
	if($website_sidebarorientation[1] == 'left'){$websiteDesign['sidebarorientation'] = 'sidebar'.'left';}
		else if($website_sidebarorientation[1] == 'right'){$websiteDesign['sidebarorientation'] = 'sidebar'.'right';}
		else if($website_sidebarorientation[1] == 'top'){$websiteDesign['sidebarorientation'] = 'sidebar'.'top';}
	//GET THE GLOBAL WEBSITE TEXT COLOR
	$website_textcolor_global = explode(':',$design_form_options[4]);
	$website_textcolor_global_type = explode('_',$website_textcolor_global[1]);
	$websiteDesign['textcolor_global'] = '#'.$website_textcolor_global_type[1];
	//GET THE WEBSITE LINK COLOR
	$website_textcolor_link = explode(':',$design_form_options[5]);
	$website_textcolor_link_type = explode('_',$website_textcolor_link[1]);
	$websiteDesign['textcolor_link'] = '#'.$website_textcolor_link_type[1];
	//GET THE WEBSITE LINK BACKGROUND COLOR
	$website_textcolor_bglink = explode(':',$design_form_options[6]);
	$website_textcolor_bglink_type = explode('_',$website_textcolor_bglink[1]);
	$websiteDesign['textcolor_bglink'] = '#'.$website_textcolor_bglink_type[1];
	//GET THE WEBSITE LINK HOVER COLOR
	$website_textcolor_link_hover = explode(':',$design_form_options[7]);
	$website_textcolor_link_hover_type = explode('_',$website_textcolor_link_hover[1]);
	$websiteDesign['textcolor_link_hover'] = '#'.$website_textcolor_link_hover_type[1];
	//GET THE WEBSITE LINK HOVER BACKGROUND COLOR
	$website_textcolor_bglink_hover = explode(':',$design_form_options[8]);
	$website_textcolor_bglink_hover_type = explode('_',$website_textcolor_bglink_hover[1]);
	$websiteDesign['textcolor_bglink_hover'] = '#'.$website_textcolor_bglink_hover_type[1];
	//GET THE WEBSITE NAVIGATION LINKS FONT-SIZE
	$website_text_navisize = explode(':',$design_form_options[9]);
	$website_text_navisize_type = explode('_',$website_text_navisize[1]);
	$websiteDesign['text_navisize'] = 'navilinksize_'.$website_text_navisize_type[1];
	//GET THE WEBSITE SECTION BACKGROUND TYPE, LEVELS OF TRANSPARENCY OR COLOR
	$website_section_bg = explode(':',$design_form_options[10]);
	$website_section_bg_type = explode('_',$website_section_bg[1]);
	if($website_section_bg_type[0] == 'trans'){$websiteDesign['section_bg'] = $website_section_bg[1];}
	if($website_section_bg_type[1] == 'color'){$websiteDesign['section_bg'] = 'trans_color'; $websiteDesign['section_bg_color'] = '#'.$website_section_bg_type[2];}

	//GET THE WEBSITE FONT FAMILY
	$website_fontfamily = explode(':',$design_form_options[11]);
	$website_fontfamily_type = explode('_',$website_fontfamily[1]);
	$websiteDesign['font_family'] = $website_fontfamily_type[1];
	//GET THE GLOBAL TEXT HIGHLIGHT COLOR
	$website_text_global_highlight = explode(':',$design_form_options[12]);
	$website_text_global_highlight_type = explode('_',$website_text_global_highlight[1]);
	$websiteDesign['textcolor_global_highlight'] = "#".$website_text_global_highlight_type[1];
	//GET THE GLOBAL TEXT HIGHLIGHT BACKGROUND COLOR
	$website_text_global_bghighlight = explode(':',$design_form_options[13]);
	$website_text_global_bghighlight_type = explode('_',$website_text_global_bghighlight[1]);
	$websiteDesign['textcolor_global_bghighlight'] = "#".$website_text_global_bghighlight_type[1];

	//updated:0:.:.:copyright:0:.:.:email:1
	$website_sidebarinfo = explode(':.:.:',$websiteInfo["sidebarinfo"]);
	$sidebar_updated = explode(':',$website_sidebarinfo[0]); $websiteDesign['sidebar_updated'] = $sidebar_updated[1];
	$sidebar_copyright = explode(':',$website_sidebarinfo[1]); $websiteDesign['sidebar_copyright'] = $sidebar_copyright[1];
	$sidebar_email = explode(':',$website_sidebarinfo[2]); $websiteDesign['sidebar_email'] = $sidebar_email[1];

	return $websiteDesign;
}//layout_get_portfolio_website_design($websiteInfo)

//get the design information from the parameters, and create the portfolio website css
function layout_portfolio_website_design_generatecss($websiteDesign,$userData,$websiteInfo)
{
?>
<style type="text/css" media="screen">
* {
	margin: 0px;
	padding: 0px;
}
body
{
	background-color: <?=$websiteDesign['bg_color']?>;
	font-family: <?=$websiteDesign['font_family']?>;
	font-size: 80%;
	color: <?=$websiteDesign['textcolor_global']?>;

	<?php
	if($websiteDesign['wallpapertype'] == 'image'){
	?>
		background: <?=$websiteDesign['bg_color']?> url('./rdusers/<?=$userData['username']?>/designimages/<?=$websiteDesign['wallpaper']?>') fixed repeat-x top;
	<?php } ?>
}

a,
a:visited
{
	text-decoration: none;
	color: <?=$websiteDesign['textcolor_global']?>;
}
a:hover,
a:active,
a:focus
{
	text-decoration: underline;
}

#portfolio_logo_image
{
	padding: 0 0 0 5px;
}
#portfolio_logo_username
{
	font-size: 2.3em;
	font-weight: bold;
	/*padding: 7px 2px 3px 2px;*/
	text-transform: uppercase;
	text-align: center;
	color: <?=$websiteDesign['textcolor_global_highlight']?>;
	background-color: <?=$websiteDesign['textcolor_global_bghighlight']?>;
	cursor: pointer;
}

#portfolio_logo_website
{
	font-size: 2.2em;
	font-weight: bold;
	/*padding: 7px 2px 3px 2px;*/
	text-transform: uppercase;
	text-align: center;
	color: <?=$websiteDesign['textcolor_global_highlight']?>;
	background-color: <?=$websiteDesign['textcolor_global_bghighlight']?>;
	cursor: pointer;
}

#maincontent
{
	<?php
		if($websiteInfo['coverpage'] != 'empty')
			{echo "padding: 4px 4px 4px 2px;";}
	?>
	margin: 0px;
	width: 805px;
	padding: 0px 5px 0px 5px;
	height: 100%;
	<?php
	if($websiteDesign['sidebarorientation'] == 'sidebarleft'){echo 'float: left';}
	?>
}

.coverpageempty
{
	padding: 0;
}
<?php
if( ($websiteDesign['sidebarorientation'] == 'sidebarleft') || ($websiteDesign['sidebarorientation'] == 'sidebarright') )
{
?>
.portfolio #wrapper
{
	width: 1000px;
}
#portfolio_sidebar
{
	width: 160px;
	font-size: 0.8em;
	margin: 0px;
	padding: 0 5px 23px 5px;
	list-style-type: none;
	height: 100%;
	margin: 0 5px 13px 0;
<?php
	if($websiteDesign['sidebarorientation'] == 'sidebarleft'){echo 'float: left';}
	else if($websiteDesign['sidebarorientation'] == 'sidebarright'){echo 'float: right';}
?>
}
#portfolio_sidebar li
{
	margin: 5px 0 5px 0;
}

#website_navi
{
	margin: 0px;
	padding: 0px;
	list-style-type: none;
	font-weight: bold;
	color: <?=$websiteDesign['textcolor_link']?>;
	text-align: left;
}
#website_navi li
{
	margin: 5px 0 5px 0;
	border: 0px solid #000000;
<?php
	switch($websiteDesign['text_navisize'])
	{
		case 'navilinksize_tiny':
			echo "font-size: 1.1em;";
			break;
		case 'navilinksize_small':
			echo "font-size: 1.2em;";
			break;
		case 'navilinksize_medium':
			echo "font-size: 1.5em;";
			break;
		case 'navilinksize_large':
			echo "font-size: 1.8em;";
			break;
	}
?>
}

<?php
}else{
?>
.portfolio #wrapper
{
	width: 810px;
}
#portfolio_sidebar
{
	width: 810px;
	font-size: 0.8em;
	margin: 0px 0px 7px 0;
	padding: 0 3px 0 3px;
	list-style-type: none;
	height: 65px;
	<?php if($websiteDesign['logo'] == 'username' || $websiteDesign['logo']=='websitename') {echo 'height: 30px;';} ?>
}
#portfolio_sidebar li
{
	float: left;
	display: inline;
	margin: 0px;
}
#website_navi
{
	margin: 7px 0 7px 5px;
	padding: 0px;
	list-style-type: none;
	font-weight: bold;
	color: <?=$websiteDesign['textcolor_link']?>;
	text-align: right;
}
#website_navi li
{
	margin: 0 6px 0 6px;
	border: 0px solid #000000;
<?php
	switch($websiteDesign['text_navisize'])
	{
		case 'navilinksize_tiny':
			echo "font-size: 1.2em;";
			echo "margin: 14px 53px 14px 53px;";
			if($websiteDesign['logo'] == 'username' || $websiteDesign['logo']=='websitename')
				{echo "margin: 0px 33px 10px 33px;";}
			break;
		case 'navilinksize_small':
			echo "font-size: 1.5em;";
			if($websiteDesign['logo'] == 'username' || $websiteDesign['logo']=='websitename')
				{echo "margin: -4px 33px 8px 33px;";}else{echo "margin: 11px 33px 11px 33px;";}
			break;
		case 'navilinksize_medium':
			echo "font-size: 1.7em;";
			if($websiteDesign['logo'] == 'username' || $websiteDesign['logo']=='websitename')
				{echo "margin: -7px 23px 2px 23px;";}else{echo "margin: 7px 23px 7px 23px;";}
			break;
		case 'navilinksize_large':
			echo "font-size: 1.9em;";
			if($websiteDesign['logo'] == 'username' || $websiteDesign['logo']=='websitename')
				{echo "margin: -10px 6px -5px 6px;";}else{echo "margin: 0 6px 0 6px;";}
			break;
	}
?>
}
<?php
	if($websiteDesign['sidebar_updated'] || $websiteDesign['sidebar_copyright'] || $websiteDesign['sidebar_email'])
	{
?>

		#portfolio_sidebar
		{

			height: 75px;
			<?php if($websiteDesign['logo'] == 'username' || $websiteDesign['logo']=='websitename') {echo 'height: 47px;';} ?>
		}
		#portfolio_other_info
		{
			width: 100%;
			font-size: 1em;
			text-align: center;
		}
		#portfolio_updated,
		#sidebar_copyright,
		#sidebar_email
		{
			margin: 0 0 0 17px;
		}
<?

	}//if
}//
?>


#portfolio_sidebar .sidebar_seperator
{
	height: 10px;
	border-bottom: 1px solid #CCCCCC;
}

.navi_blog,
.navi_info,
.navi_albums,
.navi_contact,
.navicat_names,
.navialbum_names
{
	text-transform: uppercase;
	<?php if($websiteDesign['textcolor_bglink'] != '#'){?>
		background-color: <?=$websiteDesign['textcolor_bglink']?>;
	<?php }?>
	cursor: pointer;
}
.navi_blog:hover,
.navi_blog:focus,
.navi_info:hover,
.navi_info:focus,
.navi_contact:hover,
.navi_contact:focus,
.navicat_names:hover,
.navicat_names:focus,
.navialbum_names:hover,
.navialbum_names:focus,
.navi_albums:hover,
.navi_albums:focus
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != '#'){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}

.navialbum_names
{
	margin: 7px 0 7px 0;
<?php
	switch($websiteDesign['text_navisize'])
	{
		case 'navilinksize_tiny':
			echo "font-size: 0.9em;";
			break;
		case 'navilinksize_small':
			echo "font-size: 0.9em;";
			break;
		case 'navilinksize_medium':
			echo "font-size: 0.7em;";
			break;
		case 'navilinksize_large':
			echo "font-size: 0.6em;";
			break;
	}
?>
}

.navialbum_names_orphaned
{
<?php
	switch($websiteDesign['text_navisize'])
	{
		case 'navilinksize_tiny':
			echo "font-size: 1em;";
			break;
		case 'navilinksize_small':
			echo "font-size: 1.2em;";
			break;
		case 'navilinksize_medium':
			echo "font-size: 1.5em;";
			break;
		case 'navilinksize_large':
			echo "font-size: 1.8em;";
			break;
	}
?>
}

.blog_noentries
{
	font-size: 1.5em;
	font-weight: bold;
	margin: 27px 0 0 0;
	text-align: center;
}

.blog_navi_button_container
{
	width: 100%;
	border-top: 1px solid #F7F7F7;
	height: 17px;
	float: left;
}
#navi_blog_navigate_next
{
	float: right;
}
#navi_blog_navigate_previous
{
	float: left;
}
.navi_blog_navigate
{
<?php
	switch($websiteDesign['text_navisize'])
	{
		case 'navilinksize_tiny':
			echo "font-size: 1em;";
			break;
		case 'navilinksize_small':
			echo "font-size: 1.2em;";
			break;
		case 'navilinksize_medium':
			echo "font-size: 1.5em;";
			break;
		case 'navilinksize_large':
			echo "font-size: 1.8em;";
			break;
	}
?>
	font-weight: bold;
	color: <?=$websiteDesign['textcolor_link']?>;
	cursor:pointer;
	padding: 2px 3px 2px 3px;
}
.navi_blog_navigate:hover,
.navi_blog_navigate:active
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != '#'){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}

a.userSpecific,
a.userSpecific:visited
{
	color: <?=$websiteDesign['textcolor_link']?>;
	<?php if($websiteDesign['textcolor_bglink'] != '#'){?>
		background-color: <?=$websiteDesign['textcolor_bglink']?>;
	<?php }?>
	text-decoration: underline;
	padding: 2px 1px 2px 1px;
}
a.userSpecific:active
a.userSpecific:focus,
a.userSpecific:hover
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != '#'){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}

.navicat_sections
{
	margin: 0 0 0 5px;
}
.navicat_sections div
{
	margin: 7px 0 7px 0;
}

.display_albumname_big,
.display_tagname_big,
.display_blogname_big,
.display_infoname_big,
.display_contactname_big,
.display_albumsname_big
{
	/*
	background-color:#000000;
	color: #FFFFFF;
	*/
	padding: 2px 3px 2px 3px;
	font-size: 1.6em;
	font-weight: bold;
	font-style:italic;
	margin: 23px 0 13px 0;
	text-transform:capitalize;
}

#sidebar_imagetags_title
{
	font-size: 1.2em;
	font-weight: bold;
	text-transform:uppercase;
	padding: 2px 3px 2px 3px;
	width: 150px;
}

#portfolio_tagcloud
{
	margin: 0 0 0 0px;
	padding: 0px;
	list-style-type: none;
	text-transform: capitalize;

<?php
	if( ($websiteDesign['sidebarorientation'] == 'sidebarleft') || ($websiteDesign['sidebarorientation'] == 'sidebarright') )
	{
		echo "width: 160px;";
	}else
	{
		echo "margin: 0 0 77px 0";
	}
?>
}
#portfolio_tagcloud li
{
	font-size: 1em;
	display: inline;
	float: left;
	line-height: 20px;
	margin:0px;
	padding: 0px;
}
#portfolio_tagcloud li a
{
	color: <?=$websiteDesign['textcolor_link']?>;
	<?php if($websiteDesign['textcolor_bglink'] != '#'){?>
		background-color: <?=$websiteDesign['textcolor_bglink']?>;
	<?php }?>
	padding: 2px;
	text-decoration: none;
}
#portfolio_tagcloud li a:hover,
#portfolio_tagcloud li a:active,
#portfolio_tagcloud li a:focus
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != ''){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}

#portfolio_tagcloud .smallestTag{font-size:0.9em; font-weight: bold;}
#portfolio_tagcloud .smallTag{font-size:1.5em; font-weight: bold;}
#portfolio_tagcloud .mediumTag{font-size:2em;}
#portfolio_tagcloud .largeTag{font-size:2.8em;}
#portfolio_tagcloud .largestTag{font-size:3.2em;}


#portfolio_album_images_get_more_container
{
	text-align: center;
	margin: -27px 0 17px 0;
}
#portfolio_album_images_get_more_container span
{
	font-size: 1.5em;
	font-weight: bold;
	text-transform:lowercase;
	cursor: pointer;
}
#portfolio_album_images_get_more_container span:hover,
#portfolio_album_images_get_more_container span:focus
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != ''){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}

.trans_color
{
	background-color: <?=$websiteDesign['section_bg_color']?>;
}
.trans_100
{
}
.trans_white_light
{
	background-color: #FFFFFF;
	background: transparent url('./rdlayout/images/bglinewhite90.png');
	background-repeat: repeat;
}

.trans_white_heavy
{
	background-color: #FFFFFF;
	background: transparent url('./rdlayout/images/bglinewhite98.png');
	background-repeat: repeat;
}

.trans_black_light
{
	background-color: #000000;
	background: transparent url('./rdlayout/images/bglineblack60.png');
	background-repeat: repeat;
}

.trans_black_heavy
{
	background-color: #000000;
	background: transparent url('./rdlayout/images/bglineblack85.png');
	background-repeat: repeat;
}

.text_highlight
{
	color: <?=$websiteDesign['textcolor_global_highlight']?>;
	font-weight: bold;
	padding: 2px 3px 2px 3px;
}
.text_highlight_bg
{
<?php
	if($websiteDesign['textcolor_global_bghighlight'] != '')
		{echo "background-color: ".$websiteDesign['textcolor_global_bghighlight'].";";}
?>
}
.text_highlight_sidebar
{
	color: <?=$websiteDesign['textcolor_global_highlight']?>;
	<?php
	if($websiteDesign['textcolor_global_bghighlight'] != '')
		{echo "background-color: ".$websiteDesign['textcolor_global_bghighlight'].";";}
	?>
	padding: 1px 2px 1px 2px;
}
.navilinksize_tiny
{
	font-size: 1em;
}
.navilinksize_small
{
	font-size: 1.2em;
}
.navilinksize_medium
{
	font-size: 1.5em;
}
.navilinksize_large
{
	font-size: 1.8em;
}


.portfolio_tags,
.postentry_tags
{
	cursor: pointer;
}
.portfolio_tags:hover,
.portfolio_tags:focus,
.postentry_tags:hover,
.postentry_tags:focus
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != ''){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}
.display_albuminfo #display_albumcomments span:hover,
.display_albuminfo #display_albumcomments span:focus
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != ''){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}
#loader_layer5
{
	display: none;
	font-weight: bold;
	font-size: 1.2em;
	color: #454545;

	position:absolute;


	<?php
	switch($websiteDesign['sidebarorientation'])
	{
		case 'sidebarleft':
			echo 'top: 23px;';
			echo 'margin: 0 0 0 175px;';
			break;
		case 'sidebarright':
			echo 'top: 23px;';
			echo 'margin: 0 0 0 0px;';
			break;
		case 'sidebartop':
			if($websiteDesign['sidebar_updated'] || $websiteDesign['sidebar_copyright'] || $websiteDesign['sidebar_email'])
				{echo 'top: 76px;';}
			else
				{echo 'top: 76px;';}
			break;
		default:
			echo 'margin: 0 0 0 175px;';
			break;
	}
	?>

	width: 810px;
	height: 100%;

	padding: 3px;
	background-color: #FFFFFF;
	background: transparent url('./rdlayout/images/bglinewhite90.png');
	background-repeat: repeat;
	z-index: +7;
}

.display_albuminfo .display_albumcomments span:hover,
.display_albuminfo .display_albumcomments span:focus
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != ''){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}

.blog_entry_comments span:hover,
.blog_entry_comments span:focus
{
	color: <?=$websiteDesign['textcolor_link_hover']?>;
	<?php if($websiteDesign['textcolor_bglink_hover'] != ''){?>
		background-color: <?=$websiteDesign['textcolor_bglink_hover']?>;
	<?php }?>
}

</style>
<?
}//layout_portfolio_website_design_generatecss($websiteDesign)


function layout_get_portfolio_sidebar($userData,$websiteInfo,$websiteDesign)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbVars = array();

	$dbobj = new TBDBase(0);

	$sidebarOrientation = explode('_',$websiteInfo['sidebarorientation']);
	if( ($sidebarOrientation[0] == 'top'))
	{
		###################### SIDEBAR: TOP ##########################
		//i don't need the album categories, or the album names for this layout

		//get the last updated date of the website
		$query_updated = "SELECT lastupdatedtimestamp FROM posts ORDER BY lastupdatedtimestamp DESC; ";
		$dbVars_updated = $dbobj->executeSelectQuery($query_updated);
		if($dbVars_updated['NUM_ROWS'] != 0){$websiteUpdated = convertTimeStamp($dbVars_updated['RESULT'][0]['lastupdatedtimestamp'],'reallyshort');}
		else{$websiteUpdated = '00.00.0000';}

		//count how many blog entries the user has
		$query_postscount = "SELECT count(*) AS counter FROM posts WHERE uid='".$userData['uid']."' AND visibilitystatus = 'visible'; ";
		$dbVars_postscount = $dbobj->executeSelectQuery($query_postscount);
		$postsCount = $dbVars_postscount['RESULT'][0]['counter'];

		//get all the users' album categories
		$query_albumtags = "SELECT * FROM tag WHERE uid='".$userData['uid']."' AND type='album'; ";
		$dbVars_albumtags = $dbobj->executeSelectQuery($query_albumtags);

		if($dbVars_albumtags['NUM_ROWS'] != 0)
		{
			for($i=0; $i<$dbVars_albumtags['NUM_ROWS']; $i++)
			{
				$albumTags_tempID = $dbVars_albumtags['RESULT'][$i]['tid'];
				$albumTags[$albumTags_tempID]['name'] =  $dbVars_albumtags['RESULT'][$i]['name'];
				$albumTags[$albumTags_tempID]['albumcount'] =  0;
			}//for()
		}//
		else{}//no album categories

		unset($dbobj);
		//get all the users' albums
		$albumData = album_get('uid',$userData['uid']);
		//get the tag cloud
		$tagCloud = layout_get_portfolio_images_tagcloud($userData);


		$explodedArr = explode('@',$userData['email'],2);
		$explodedArr[0] = explode('.',$explodedArr[0],10);
		$explodedArr[1] = explode('.',$explodedArr[1],10);
		$counter=0;
		reset($explodedArr[0]);
		while (list($key, $val) = each ($explodedArr[0])){
			$counter++;
			if(count($explodedArr[0])>1){if($counter==count($explodedArr[0])){$emailAddress.=$val;} else{$emailAddress.=$val." <span class='text_highlight_sidebar'>dot</span> ";}}
			else{$emailAddress.=$val;}
		}//while
		$emailAddress.=" <span class='text_highlight_sidebar'>at</span> ";
		$counter=0;
		reset($explodedArr[1]);
		while (list($key, $val) = each ($explodedArr[1])){
			$counter++;
			if(count($explodedArr[1])>1){if($counter==count($explodedArr[1])){$emailAddress.=$val;}else{$emailAddress.=$val." <span class='text_highlight text_highlight_bg'>dot</span> ";}}
			else{$emailAddress.=$val;}
		}//while

			echo "<ul id='portfolio_sidebar' class='".$websiteDesign['section_bg']."'>";
			if($websiteDesign['logo'] == 'logo.png')
				{echo "<li id='portfolio_logo_image' class='portfolio_logo'><img src='./rdusers/".$userData['username']."/designimages/".$websiteDesign['logo']."' id='' class='' /></li>";}
			else if($websiteDesign['logo'] == 'username')
				{echo "<li id='portfolio_logo_username' class='portfolio_logo'>".$userData['username']."</li>";}
			else if($websiteDesign['logo'] == 'websitename')
				{echo "<li id='portfolio_logo_website' class='portfolio_logo'>".$websiteInfo['title']."</li>";}
			echo "<li>";
			echo "<ul id='website_navi' class='".$websiteDesign['text_navisize']."'>";

				if($userData['username'] != 'cillinfarrell')
				{
					if($postsCount != 0 )
						{echo "<li class='navis'><div id='navi_blog' class='navi_blog'>news</div></li>";}
					if(count($albumData) != 0)
						{echo "<li class='navis'><div id='navi_albums'><span class='navi_albums'>albums</span></div></li>";}
				}
				else{
					if(count($albumData) != 0)
						{echo "<li class='navis'><div id='navi_albums'><span class='navi_albums'>portfolio</span></div></li>";}
				}

				echo "<li class='navis'><div id='navi_info'><span class='navi_info'>about</span></div></li>";
				echo "<li class='navis'><div id='navi_contact' class='navi_contact'>contact</div></li>";
			echo "</ul>";
			echo "</li>";

			echo "<li id='portfolio_other_info'>";
			if($websiteDesign['sidebar_updated'])
				{echo "<span id='portfolio_updated'>UPDATED: <span class='text_highlight_sidebar'>".$websiteUpdated."</span></span>";}
			if($websiteDesign['sidebar_copyright'])
				{echo "<li id='sidebar_copyright'>All images&copy; <span class='text_highlight_sidebar'>1999-2010</span></li>";}
			if($websiteDesign['sidebar_email'])
				{echo "<span id='sidebar_email'>"."<a href='mailto:".$userData['email']."' class='emailme'>".$emailAddress."</a></span>";}
			echo "</li>";
		echo "</ul>";

			if($websiteInfo['imagetagcloud'] == 'yes')
			{
				//echo "<div id='sidebar_imagetags_title' class='text_highlight text_highlight_bg'>Image Tags</div>";
				echo $tagCloud;
			}
	}//
	else
	{
		###################### SIDEBAR: LEFT/ RIGHT ##########################
		//get the last updated date of the website
		$query_updated = "SELECT lastupdatedtimestamp FROM posts ORDER BY lastupdatedtimestamp DESC; ";
		$dbVars_updated = $dbobj->executeSelectQuery($query_updated);
		if($dbVars_updated['NUM_ROWS'] != 0){$websiteUpdated = convertTimeStamp($dbVars_updated['RESULT'][0]['lastupdatedtimestamp'],'reallyshort');}
		else{$websiteUpdated = '00.00.0000';}

		//count how many blog entries the user has
		$query_postscount = "SELECT count(*) AS counter FROM posts WHERE uid='".$userData['uid']."' AND visibilitystatus = 'visible'; ";
		$dbVars_postscount = $dbobj->executeSelectQuery($query_postscount);
		$postsCount = $dbVars_postscount['RESULT'][0]['counter'];


		//get all the users' album categories
		$query_albumtags = "SELECT * FROM tag WHERE uid='".$userData['uid']."' AND type='album'; ";
		$dbVars_albumtags = $dbobj->executeSelectQuery($query_albumtags);

		if($dbVars_albumtags['NUM_ROWS'] != 0)
		{
			for($i=0; $i<$dbVars_albumtags['NUM_ROWS']; $i++)
			{
				$albumTags_tempID = $dbVars_albumtags['RESULT'][$i]['tid'];
				$albumTags[$albumTags_tempID]['name'] =  $dbVars_albumtags['RESULT'][$i]['name'];
				$albumTags[$albumTags_tempID]['albumcount'] =  0;
			}//for()
		}//
		else{}//no album categories

		unset($dbobj);
		//get all the users' albums
		$albumData = album_get('uid',$userData['uid']);
		//get the tag cloud
		$tagCloud = layout_get_portfolio_images_tagcloud($userData);

		//find how many albums each category has
		for($k=0; $k<count($albumData); $k++)
		{
			if($albumData[$k]['visibilitystatus'] == 'visible'){$albumTags[$albumData[$k]['tid']]['albumcount']++;}
		}//

		$explodedArr = explode('@',$userData['email'],2);
		$explodedArr[0] = explode('.',$explodedArr[0],10);
		$explodedArr[1] = explode('.',$explodedArr[1],10);
		$counter=0;
		reset($explodedArr[0]);
		while (list($key, $val) = each ($explodedArr[0])){
			$counter++;
			if(count($explodedArr[0])>1){if($counter==count($explodedArr[0])){$emailAddress.=$val;} else{$emailAddress.=$val." <span class='text_highlight_sidebar'>dot</span> ";}}
			else{$emailAddress.=$val;}
		}//while
		$emailAddress.=" <span class='text_highlight_sidebar'>at</span> ";
		$counter=0;
		reset($explodedArr[1]);
		while (list($key, $val) = each ($explodedArr[1])){
			$counter++;
			if(count($explodedArr[1])>1){if($counter==count($explodedArr[1])){$emailAddress.=$val;}else{$emailAddress.=$val." <span class='text_highlight_sidebar'>dot</span> ";}}
			else{$emailAddress.=$val;}
		}//while

		echo "<ul id='portfolio_sidebar' class='".$websiteDesign['section_bg']."'>";
			if($websiteDesign['logo'] == 'logo.png')
				{echo "<li id='portfolio_logo_image' class='portfolio_logo'><img src='./rdusers/".$userData['username']."/designimages/".$websiteDesign['logo']."' id='' class='' /></li>";}
			else if($websiteDesign['logo'] == 'username')
				{echo "<li id='portfolio_logo_username' class='portfolio_logo'>".$userData['username']."</li>";}
			else if($websiteDesign['logo'] == 'websitename')
				{echo "<li id='portfolio_logo_website' class='portfolio_logo'>".$websiteInfo['title']."</li>";}
			if($websiteDesign['sidebar_updated'])
				{echo "<li id='portfolio_updated'>UPDATED: <span class='text_highlight_sidebar'>".$websiteUpdated."</span></li>";}
			//if($websiteDesign['sidebar_copyright'])
			if($userData['username'] == 'jamesdoe')
				{echo "<li id='sidebar_copyright'>All images&copy; <span class='text_highlight_sidebar'>1999-2010</span></li>";}
			if($websiteDesign['sidebar_email'])
				{echo "<li id='sidebar_email'>"."<a href='mailto:".$userData['email']."' class='emailme'>".$emailAddress."</a></li>";}
			if($websiteDesign['sidebar_updated'] || $websiteDesign['sidebar_copyright'] || $websiteDesign['sidebar_email'])
				{echo "<li class='sidebar_seperator'></li>";}

			echo "<li>";
			echo "<ul id='website_navi' class='".$websiteDesign['text_navisize']."'>";

				if($postsCount != 0 )
					{echo "<li class='navis'><div id='navi_blog'><span class='navi_blog'>news</span></div></li>";}

				if(count($albumData) != 0)
				{
					if(count($albumTags) !=0 )
					{
						reset($albumTags);
						while (list($key, $val) = each ($albumTags))
						{
							if($albumTags[$key]['albumcount'] != 0)
							{
							echo "<li class='navis'>";
								echo "<span class='navicat_names' id='navicat_name_".str_replace(' ','',$albumTags[$key]['name'])."'>".$albumTags[$key]['name']."</span>";
								echo "<div class='navicat_sections' id='navicat_section_".str_replace(' ','',$albumTags[$key]['name'])."'>";
								for($j=0; $j<count($albumData); $j++)
								{
									if($albumData[$j]['visibilitystatus'] == 'visible')
									{
										if($albumData[$j]['tid'] == $key){
											echo "<div><span id='navialbum_name_".$albumData[$j]['aid']."' class='navialbum_names'>".$albumData[$j]['name']."</span></div>";
										}//if
									}//if
								}//for
								echo "</div>";
							echo "</li>";
							}//if
						}//while
					}//if

					//display all the albums that are not categorized
					reset($albumData);
					while (list($key, $val) = each ($albumData))
					{
						if($albumData[$key]['categoryname'] == '')
						{
							if($albumData[$key]['visibilitystatus'] == 'visible')
							{
								echo "<div><span id='navialbum_name_".$albumData[$key]['aid']."' class='navialbum_names navialbum_names_orphaned'>".$albumData[$key]['name']."</span></div>";
							}//if
						}
					}//while

				}//if
				echo "<li class='navis'><div id='navi_info'><span class='navi_info'>about</span></div></li>";
				echo "<li class='navis'><div id='navi_contact'><span class='navi_contact'>contact</span></div></li>";
			echo "</ul>";
			echo "</li>";

			if($websiteInfo['imagetagcloud'] == 'yes')
				{if($tagCloud != ''){echo "<li class='sidebar_seperator'></li>";  echo "<div id='sidebar_imagetags_title' class='text_highlight text_highlight_bg'>Image Tags</div>"; echo $tagCloud;}}

		echo "</ul>";

	}//else

}//layout_get_portfolio_sidebar($userData)

function layout_get_portfolio_maincontent($userData,$websiteInfo,$websiteDesign)
{
	if($websiteInfo['coverpage'] == 'empty'){$mainContentEmptyClass = 'coverpageempty';}

	echo "<div id='maincontent' class='".$websiteDesign['section_bg']." '>";
		website_coverpage($userData,$websiteInfo);
	echo "</div>";
	echo "<div id='loader_layer5' class='displaynone'> Loading... <img class='loader_img' src='./rdlayout/images/loader.gif' /></div>";
}//layout_get_portfolio_maincontent($userData,$websiteInfo,$websiteDesign)


function layout_get_portfolio_images_tagcloud($userData)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$query = NULL;

	$tagsArr = array();
	$imagesArr = array();
	$tempExplodeArr = array();
	$orphanTagsArr = array();
	$minValue=0;
	$maxValue=0;

	$query01 = "SELECT * FROM tag WHERE uid='".$userData['uid']."' AND type='image'; ";
	$query02 = "SELECT image.iid, image.caption, image.tags FROM image, album WHERE image.aid=album.aid AND album.visibilitystatus='visible' AND image.uid='".$userData['uid']."'; ";

	$dbVars01 = $dbobj->executeSelectQuery($query01);
	$dbVars02 = $dbobj->executeSelectQuery($query02);

	if($dbVars01['NUM_ROWS'] != 0)
	{
		for($i=0; $i<$dbVars01['NUM_ROWS']; $i++)
		{
			$tagID = $dbVars01['RESULT'][$i]['tid'];
			$tagsArr[$tagID]['name'] = $dbVars01['RESULT'][$i]['name'];
			$tagsArr[$tagID]['imagesno'] = 0;
		}//for

		if($dbVars02['NUM_ROWS'] != 0)
		{
			for($j=0; $j<$dbVars02['NUM_ROWS']; $j++)
			{
				$imageID = $dbVars02['RESULT'][$j]['iid'];
				$imagesArr[$imageID]['caption'] = $dbVars02['RESULT'][$j]['caption'];
				$imagesArr[$imageID]['tags'] = $dbVars02['RESULT'][$j]['tags'];
			}//for

			reset($imagesArr);
			while (list($imagekey, $imageval) = each ($imagesArr))
			{
				$tempExplodeArr = explode('::..::',$imagesArr[$imagekey]['tags']);
				for($i=0; $i<count($tempExplodeArr); $i++)
				{
					if($tempExplodeArr[$i]==''){continue;}
					$tagsArr[$tempExplodeArr[$i]]['imagesno']++;

					$maxValue = $tagsArr[$tempExplodeArr[$i]]['imagesno'];
					$minValue = $tagsArr[$tempExplodeArr[$i]]['imagesno'];
				}//for
			}//while

			//remove from array all tags that have no images attached to them
			reset($tagsArr);
			while (list($key, $val) = each ($tagsArr))
				{if($tagsArr[$key]['imagesno']==0||!isset($tagsArr[$key]['name'])){$orphanTagsArr[$key]=$tagsArr[$key]; unset($tagsArr[$key]);}}//while

			//find the min and max values of tag occurances in images
			reset($tagsArr);
			while (list($key, $val) = each ($tagsArr))
				{if($tagsArr[$key]['imagesno']>$maxValue){$maxValue=$tagsArr[$key]['imagesno'];}
				if($tagsArr[$key]['imagesno']<$minValue){$minValue=$tagsArr[$key]['imagesno'];}}//while

			//difference between max and min
			$difference = $maxValue-$minValue;
			$distribution = $difference/3;

			unset($dbobj);

			//if(count($orphanTagsArr)==0){cleanOrphanImageTags($orphanTagsArr);}

			//display tags
			$tagsUL = '';
			//$tagsUL .= "<div id='sidebar_imagetags_title' class='text_highlight text_highlight_bg'>Image Tags</div>";
			$tagsUL .= "<ul id='portfolio_tagcloud'>";
			reset($tagsArr);
			while (list($key, $val) = each ($tagsArr))
			{
				$liClass='smallTag';
				if($tagsArr[$key]['imagesno']==$minValue){$liClass='smallestTag';}
				else if($tagsArr[$key]['imagesno']==$maxValue){$liClass='largestTag';}
				else if($tagsArr[$key]['imagesno']>($minValue+($distribution*2))){$liClass='largeTag';}
				else if($tagsArr[$key]['imagesno']>($minValue+$distribution)){$liClass='mediumTag';}

				$tagsUL.="<li class='".$liClass."'>"."<a href='javascript:;' class='portfolio_tags' id='portfolio_tag_".$key."'>".$tagsArr[$key]['name']."</a>"."</li>";
			}//while
			$tagsUL.="</ul>";
			return $tagsUL;
		}
	}
	else{return '';}
}//layout_get_portfolio_images_tagcloud($userData)


function contact_get_form($type,$userID)
{
	switch($type)
	{
		case 'portfoliosite':
			$userData = user_get('uid',$userID);
?>
        <div id='display_contact'>
			<span id='display_contactname' class='text_highlight text_highlight_bg'>contact</span>
        </div>
        <div class='display_contactname_big text_highlight text_highlight_bg'>contact me</div>

  	<div id='contact_body'>

        <div id='contacttext'>
            You can contact me at
            <span class='email'>
                <b><a href='mailto:<?=$userData['email']?>' title='e-mail me' class='userSpecific'><?=$userData['email']?></a></b>
            </span>
            , or by using the form below.
        </div>

        <div id='contactfrm_container'>
        	<div id='contact_frm_messages' class='messages'>
            <?php if(isset($_GET['emailsent'])){echo "<span class='highlight_color'>Your message has been sent successfully.</span>"; unset($_GET['websiteupdated']);}?>
            </div>


            <div id='contact_frm_loader' class='visibilityhidden'></div>
            <ul id='contact_frm'>
                <li id='contact_failed' class='hidden'></li>
                <li>
                	<input class='text' type='text' name='contact_name' id='contact_name' maxlength='40' value='(type your name)(required)' title='(type your name)(required)' />
                	<span class='field_messages' id='contact_name_message'></span>
                </li>
                <li>
                	<input class='text' type='text' name='contact_email' id='contact_email' maxlength='70' value='(type your email)(required)' title='(type your email)(required)' />
                	<span class='field_messages' id='contact_email_message'></span>
                </li>
                <li>
                	<input class='text' type='text' name='contact_regarding' id='contact_regarding' maxlength='100' value='(regarding)(not required)' title='(regarding)(not required)' />
                    <span class='field_messages' id='contact_regarding_message'></span>
               	</li>
                <li>
                	<textarea class='text' name='contact_message' id='contact_message' cols='' rows='' title='(type your message)(required)'>(type your message)(required)</textarea>
                    <input type='hidden' class='displaynone' id='contact_type' value='portfoliosite' />
                	<span class='field_messages' id='contact_message_message'></span>
                </li>
                <li class='charcounters'><span class='counters' id='scounter'><?=CONTACT_MESSAGE_MAX_LENGTH?></span> remaining characters</li>
                <li><input type='checkbox' name='contact_cc' id='contact_cc' value='off' /> Send CC to self (not required)</li>
                <li><div class="submit" id='contact_submit'>submit</div></li>
            </ul>
        </div><!--contactusfrm_container-->

		<div id='sentemailanchor'></div>
        <span id='sender_loader' class='hidden'></span>

    </div><!--contactus_body-->
	<div class='clear'></div>
	<div class='clear'></div><div class='muchneededhight'></div>
<?
		break;
	case 'mainsite':
?>
  	<div id='contact_body'>

        <div id='contacttext'>
            You can contact us at
            <span class='email'>
                <b><a href='mailto:<?=DEFAULT_EMAIL?>' title='e-mail us' class='blacklink'><?=DEFAULT_EMAIL?></a></b>
            </span>
            , or by using the form below.
        </div>

        <div id='contactfrm_container'>
        	<div id='contact_frm_messages' class='messages'>
            <?php if(isset($_GET['emailsent'])){echo "<span class='highlight_color'>Your message has been sent successfully.</span>"; unset($_GET['websiteupdated']);}?>
            </div>
            <div id='contact_frm_loader' class='visibilityhidden'></div>
            <ul id='contact_frm'>
                <li id='contact_failed' class='hidden'></li>
                <li>
                	<input class='text' type='text' name='contact_name' id='contact_name' maxlength='40' value='(type your name)(required)' title='(type your name)(required)' />
                	<span class='field_messages' id='contact_name_message'></span>
                </li>
                <li>
                	<input class='text' type='text' name='contact_email' id='contact_email' maxlength='70' value='(type your email)(required)' title='(type your email)(required)' />
                	<span class='field_messages' id='contact_email_message'></span>
                </li>
                <li>
                	<input class='text' type='text' name='contact_regarding' id='contact_regarding' maxlength='100' value='(regarding)(not required)' title='(regarding)(not required)' />
                    <span class='field_messages' id='contact_regarding_message'></span>
               	</li>
                <li>
                	<textarea class='text' name='contact_message' id='contact_message' cols='' rows='' title='(type your message)(required)'>(type your message)(required)</textarea>
                    <input type='hidden' class='displaynone' id='contact_type' value='mainsite' />
                	<span class='field_messages' id='contact_message_message'></span>
                </li>
                <li class='charcounters'><span class='counters' id='scounter'><?=CONTACT_MESSAGE_MAX_LENGTH?></span> remaining characters</li>
                <li><input type='checkbox' name='contact_cc' id='contact_cc' value='off' /> Send CC to self (not required)</li>
                <li><div class="submit" id='contact_submit'>submit</div></li>
            </ul>
        </div><!--contactusfrm_container-->

		<div id='sentemailanchor'></div>
        <span id='sender_loader' class='hidden'></span>

    </div><!--contactus_body-->
	<div class='clear'></div>
	<div class='clear'></div><div class='muchneededhight'></div>
<?
		break;
	}//switch
}//contact_get_form($type,$userID)



function layout_get_artistindex_contents()
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$query = NULL;

	$tagsArr = array();
	$imagesArr = array();
	$tempExplodeArr = array();
	$orphanTagsArr = array();
	$minValue=0;
	$maxValue=0;

	$query01 = "SELECT * FROM tag WHERE type='profile'; ";
	$dbVars01 = $dbobj->executeSelectQuery($query01);

	$queryAvatar = "SELECT iid, fileurl FROM image WHERE imagetype='usercover'; ";
	$dbVarsAvatar = $dbobj->executeSelectQuery($queryAvatar);
	unset($dbobj);

	$userData = user_get('all',$tokenValue);

	if($dbVars01['NUM_ROWS'] != 0)
	{
		for($i=0; $i<$dbVars01['NUM_ROWS']; $i++)
		{
			$tagID = $dbVars01['RESULT'][$i]['tid'];
			$tagsArr[$tagID]['name'] = $dbVars01['RESULT'][$i]['name'];
			$tagsArr[$tagID]['usersno'] = 0;
			$tagsArr[$tagID]['taggedusers'] = '';
		}//for

		if(count($userData) != 0)
		{
			reset($userData);
			while (list($key, $val) = each ($userData))
			{
				$tagNames_temp = '';
				$tempExplodeArr = explode(':..::..:',$userData[$key]['tags']);
				for($i=0; $i<count($tempExplodeArr); $i++)
				{
					if($tempExplodeArr[$i]==''){continue;}
					else{
						$tagsArr[$tempExplodeArr[$i]]['usersno']++;

						$maxValue = $tagsArr[$tempExplodeArr[$i]]['usersno'];
						$minValue = $tagsArr[$tempExplodeArr[$i]]['usersno'];

						$tagNames_temp .= $tagsArr[$tempExplodeArr[$i]]['name'].", ";
					}//else
				}//for
				$userData[$key]['tags'] = $tagNames_temp; unset($tagNames_temp);
			}//while

			//remove from array all tags that have no images attached to them
			/*
			reset($tagsArr);
			while (list($key, $val) = each ($tagsArr))
				{if($tagsArr[$key]['usersno']==0||!isset($tagsArr[$key]['name'])){$orphanTagsArr[$key]=$tagsArr[$key]; unset($tagsArr[$key]);}}//while
			*/

			//find the min and max values of tag occurances in images
			reset($tagsArr);
			while (list($key, $val) = each ($tagsArr))
				{if($tagsArr[$key]['usersno']>$maxValue){$maxValue=$tagsArr[$key]['usersno'];}
				if($tagsArr[$key]['usersno']<$minValue){$minValue=$tagsArr[$key]['usersno'];}}//while

			//difference between max and min
			$difference = $maxValue-$minValue;
			$distribution = $difference/3;

			unset($dbobj);

			//if(count($orphanTagsArr)==0){cleanOrphanImageTags($orphanTagsArr);}

			//display tags
			$tagsUL="<ul id='usercategories_tagcloud'>";
			$tagsUL.="<li class='largestTag'>"."<a href='javascript:;' class='usercategories_tags' id='usercategories_tag_000'>all</a>"."</li>";
			reset($tagsArr);
			while (list($key, $val) = each ($tagsArr))
			{
				$liClass='smallTag';
				if($tagsArr[$key]['usersno']==$minValue){$liClass='smallestTag';}
				else if($tagsArr[$key]['usersno']==$maxValue){$liClass='largestTag';}
				else if($tagsArr[$key]['usersno']>($minValue+($distribution*2))){$liClass='largeTag';}
				else if($tagsArr[$key]['usersno']>($minValue+$distribution)){$liClass='mediumTag';}

				$tagsUL.="<li class='".$liClass."'>"."<a href='javascript:;' class='usercategories_tags' id='usercategories_tag_".$key."'>".$tagsArr[$key]['name']."</a>"."</li>";
			}//while
			$tagsUL.="</ul>";
		}//
		echo $tagsUL;
	}
	else{$tagsUL = '';}

	if(count($userData) != 0)
	{
		if($dbVarsAvatar['NUM_ROWS']!=0)
		{
			for($k=0; $k<$dbVarsAvatar['NUM_ROWS']; $k++)
			{$avatarData[$dbVarsAvatar['RESULT'][$k]['iid']] = $dbVarsAvatar['RESULT'][$k]['fileurl'];}
		}//if

		reset($userData);
		echo "<ul id='artistindex_users'>";
		while (list($key, $val) = each ($userData))
		{
			switch($counter%2)
			{
				case 0: $liClass = "oddhighlight"; break;
				case 1: $liClass = 'evenhighlight'; break;
				default: $liClass = ""; break;
			}//for

			$userTags = $userData[$key]['tags'];
			$userTagIDs = $userData[$key]['tids'];
			$userTagsHTML = '';
			$usetTagIDsHTML = '';
			if($userTags != '')
			{
				$userTagsNames = explode(',',$userTags); unset($userTagsNames[count($userTagsNames)-1]);
				$userTagIDs = explode(':..::..:',$userTagIDs);
				for($f=0; $f<count($userTagsNames); $f++)
				{
					$userTagsHTML .= "<a href='javascript:;' class='usercategories_tags' id='usercategories_tag_".$userTagIDs[$f]."'>".$userTagsNames[$f]."</a>";
					$usetTagIDsHTML .= $userTagIDs[$f];

					$tagsArr[$userTagIDs[$f]]['taggedusers'] .= $userData[$key]['uid'].",";
					if($f != (count($userTagsNames)-1) ){$userTagsHTML .= ", "; $usetTagIDsHTML .= ".";}

				}//
				$userTags = $userTagsHTML;
			}//if
			else{$userTags = '~';}

			if($userData[$key]['avatar'] != 0)
				{$avatarHTML = './rdusers/'.$userData[$key]['username'].'/coverimages/'.$avatarData[$userData[$key]['avatar']];}
			else
				{$avatarHTML = './rdlayout/images/defaultuser.png';}

			if($userData[$key]['name'] != '')
				{$artistName = $userData[$key]['name'];}
			else
				{$artistName = $userData[$key]['username'];}

			if($userData[$key]['title'] != ''){$userData[$key]['title'] = "[".$userData[$key]['title']."]";}
			else{$userData[$key]['title'] = "";}

			echo "<li class='artistprofileli' id='artistprofileli_".$userData[$key]['uid']."' class='' alt='".$usetTagIDsHTML."'>";
				echo "<img src='".$avatarHTML."' class='artistprofile_picture thumbsimages' alt='Profile picture' title='Profile picture' width='200' height='133' />";
				echo "<div class='artistprofilecontainers'>";
					echo "<span id='artistprofilelink_".$userData[$key]['uid']."' class='artistprofile_name'>"
						."<a href='".SERVER_NAME."?".$userData[$key]['username']."'>".$artistName."</a>";
					echo "</span>";
					echo "<div class='artistprofile_title'>".$userData[$key]['title']."</div>";
					echo "<div class='artistprofile_views'>(".$userData[$key]['views']." <b>views</b>)</div>";
					echo "<div class='artistprofile_joined'>"."<b>Joined on:</b> ".convertTimeStamp($userData[$key]['registrationtimestamp'],'reallylongwithouttime')."</div>";
					echo "<div class='artistprofile_tags'>"."<b>Filed under:</b> ".$userTags."</div>";
				echo "</div>";
				echo "<div class='clear'></div>";
			echo "</li>";

			$counter++;
		}//while
		echo "<li id='artistprofileli_0' class='displaynone' alt=''>No artists in this category</li>";
		reset($tagsArr);
		$tagHelpHTML = '';
		while (list($key, $val) = each ($tagsArr)){$tagHelpHTML .= $key.".".$tagsArr[$key]['taggedusers']."::";}
		echo "<li id='taghelp' class='displaynone'>".$tagHelpHTML."</div>";
		echo "<li class='clear'></div><div class='muchneededhight'></li>";
		echo "</ul>";
	}//
	echo "<div class='clear'></div><div class='muchneededhight'></div>";
}//layout_get_artistindex_contents()


function layout_get_featuredartists_contents()
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$query = NULL;

	$tagsArr = array();
	$imagesArr = array();
	$tempExplodeArr = array();
	$orphanTagsArr = array();
	$minValue=0;
	$maxValue=0;

	$query01 = "SELECT * FROM tag WHERE type='profile'; ";
	$dbVars01 = $dbobj->executeSelectQuery($query01);

	$queryAvatar = "SELECT iid, fileurl FROM image WHERE imagetype='usercover'; ";
	$dbVarsAvatar = $dbobj->executeSelectQuery($queryAvatar);
	unset($dbobj);

	$userData = user_get('featured',$tokenValue);

	if($dbVars01['NUM_ROWS'] != 0)
	{
		for($i=0; $i<$dbVars01['NUM_ROWS']; $i++)
		{
			$tagID = $dbVars01['RESULT'][$i]['tid'];
			$tagsArr[$tagID]['name'] = $dbVars01['RESULT'][$i]['name'];
			$tagsArr[$tagID]['usersno'] = 0;
			$tagsArr[$tagID]['taggedusers'] = '';
		}//for

		if(count($userData) != 0)
		{
			reset($userData);
			while (list($key, $val) = each ($userData))
			{
				$tagNames_temp = '';
				$tempExplodeArr = explode(':..::..:',$userData[$key]['tags']);
				for($i=0; $i<count($tempExplodeArr); $i++)
				{
					if($tempExplodeArr[$i]==''){continue;}
					else{
						$tagsArr[$tempExplodeArr[$i]]['usersno']++;

						$maxValue = $tagsArr[$tempExplodeArr[$i]]['usersno'];
						$minValue = $tagsArr[$tempExplodeArr[$i]]['usersno'];

						$tagNames_temp .= $tagsArr[$tempExplodeArr[$i]]['name'].", ";
					}//else
				}//for
				$userData[$key]['tags'] = $tagNames_temp; unset($tagNames_temp);
			}//while
		}//
	}
	else{$tagsUL = '';}

	if(count($userData) != 0)
	{
		if($dbVarsAvatar['NUM_ROWS']!=0)
		{
			for($k=0; $k<$dbVarsAvatar['NUM_ROWS']; $k++)
			{$avatarData[$dbVarsAvatar['RESULT'][$k]['iid']] = $dbVarsAvatar['RESULT'][$k]['fileurl'];}
		}//if

		reset($userData);
		echo "<ul id='artistindex_users'>";
		while (list($key, $val) = each ($userData))
		{
			switch($counter%2)
			{
				case 0: $liClass = "oddhighlight"; break;
				case 1: $liClass = 'evenhighlight'; break;
				default: $liClass = ""; break;
			}//for

			$userTags = $userData[$key]['tags'];
			$userTagIDs = $userData[$key]['tids'];
			$userTagsHTML = '';
			$usetTagIDsHTML = '';
			if($userTags != '')
			{
				$userTagsNames = explode(',',$userTags); unset($userTagsNames[count($userTagsNames)-1]);
				$userTagIDs = explode(':..::..:',$userTagIDs);
				for($f=0; $f<count($userTagsNames); $f++)
				{
					$userTagsHTML .= "<a href='javascript:;' class='usercategories_tags' id='usercategories_tag_".$userTagIDs[$f]."'>".$userTagsNames[$f]."</a>";
					$usetTagIDsHTML .= $userTagIDs[$f];

					$tagsArr[$userTagIDs[$f]]['taggedusers'] .= $userData[$key]['uid'].",";
					if($f != (count($userTagsNames)-1) ){$userTagsHTML .= ", "; $usetTagIDsHTML .= ".";}

				}//
				$userTags = $userTagsHTML;
			}//if
			else{$userTags = '~';}

			if($userData[$key]['avatar'] != 0)
				{$avatarHTML = './rdusers/'.$userData[$key]['username'].'/coverimages/'.$avatarData[$userData[$key]['avatar']];}
			else
				{$avatarHTML = './rdlayout/images/defaultuser.png';}

			if($userData[$key]['name'] != '')
				{$artistName = $userData[$key]['name'];}
			else
				{$artistName = $userData[$key]['username'];}

			if($userData[$key]['title'] != ''){$userData[$key]['title'] = "[".$userData[$key]['title']."]";}
			else{$userData[$key]['title'] = "";}

			echo "<li class='artistprofileli' id='artistprofileli_".$userData[$key]['uid']."' class='' alt='".$usetTagIDsHTML."'>";
				echo "<img src='".$avatarHTML."' class='artistprofile_picture thumbsimages' alt='Profile picture' title='Profile picture' width='180' height='120' />";
				echo "<div class='artistprofilecontainers'>";
					echo "<span id='artistprofilelink_".$userData[$key]['uid']."' class='artistprofile_name'>"
						."<a href='".SERVER_NAME."?".$userData[$key]['username']."'>".$artistName."</a>";
					echo "</span>";
					echo "<div class='artistprofile_title'>".$userData[$key]['title']."</div>";
					echo "<div class='artistprofile_description'>".substr(nl2br($userData[$key]['description']),0,160)."..."."</div>";
					echo "<div class='artistprofile_views'>(".$userData[$key]['views']." <b>views</b>)</div>";
					echo "<div class='artistprofile_joined'>"."<b>Joined on:</b> ".convertTimeStamp($userData[$key]['registrationtimestamp'],'reallylongwithouttime')."</div>";
					echo "<div class='artistprofile_tags'>"."<b>Filed under:</b> ".$userTags."</div>";
				echo "</div>";
				echo "<div class='clear'></div>";
			echo "</li>";

			$counter++;
		}//while

		reset($tagsArr);
		$tagHelpHTML = '';
		while (list($key, $val) = each ($tagsArr)){$tagHelpHTML .= $key.".".$tagsArr[$key]['taggedusers']."::";}
		echo "<li id='taghelp' class='displaynone'>".$tagHelpHTML."</div>";
		//echo "<li class='clear'></div><div class='muchneededhight'></li>";
		echo "</ul>";
	}//
	echo "<div class='clear'></div><div class='muchneededhight'></div>";
}//layout_get_featuredartists_contents()


function layout_get_designeditor_templates()
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbobj = new TBDBase(0);
	$dbVars = array();
	$userData = $_SESSION['user'];

	$query = 'SELECT * FROM template;';
	$dbVars = $dbobj->executeSelectQuery($query);
	if($dbVars['NUM_ROWS'] != 0)
	{
		echo "<div class='designeditor_container_title'>templates:</div><span> Select one and customize it from the settings below.</span>";
		echo "<ul id='designeditor_templates'>";
		echo "<li class='templates selectedtag' id='templates_0'>"."CURRENT"."</li>";
		for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
		{
			$templateData['tid'] = $dbVars['RESULT'][$i]['tid'];
			$templateData['name'] = $dbVars['RESULT'][$i]['name'];
			$templateData['designcsscode'] = $dbVars['RESULT'][$i]['designcsscode'];
			$templateData['designformoptions'] = $dbVars['RESULT'][$i]['designformoptions'];

			if($templateData['name'] == 'default'){echo "<li class='templates' id='templates_".$templateData['tid']."'>".$templateData['name']."</li>";}
			else{echo "<li class='templates' id='templates_".$templateData['tid']."'>".$templateData['name']."</li>";}

		}//for
		echo "</ul>";
	}//if
	unset($dbobj);
}//layout_get_designeditor_templates()


function layout_get_designeditor_settings($templateID)
{
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$dbobj = new TBDBase(0);
	$dbVars = array();
	$userData = $_SESSION['user'];

	$templateID = $templateID;
	$templateID = (get_magic_quotes_gpc()) ? $templateID : addslashes($templateID);
	$templateID = htmlentities($templateID, ENT_QUOTES, "UTF-8");
	$templateID = trim($templateID);

	$query00 = "SELECT * FROM website WHERE uid='".$userData['uid']."';";
	$dbVars00 = $dbobj->executeSelectQuery($query00);
	$websiteInfo["sidebarinfo"] = $dbVars00['RESULT'][0]['sidebarinfo'];
	$websiteInfo["sidebarorientation"] = $dbVars00['RESULT'][0]['sidebarorientation'];
	$websiteInfo["imagetagcloud"] = $dbVars00['RESULT'][0]['imagetagcloud'];

	if($templateID == '0'){
		$templateData['tid'] = '0';
		$templateData['name'] = 'current';

		$templateData['designcsscode'] = $dbVars00['RESULT'][0]['designcsscode']; //it gets the design information from the user DB entry
		$templateData["designformoptions"] = $dbVars00['RESULT'][0]['designformoptions']; //it gets the design information from the user DB entry
	}
	else
	{
		$query = "SELECT * FROM template WHERE tid='".$templateID."';";
		$dbVars = $dbobj->executeSelectQuery($query);

		$templateData['tid'] = $dbVars['RESULT'][0]['tid'];
		$templateData['name'] = $dbVars['RESULT'][0]['name'];
		$templateData['designcsscode'] = $dbVars['RESULT'][0]['designcsscode'];
		$templateData['designformoptions'] = $dbVars['RESULT'][0]['designformoptions'];

		$websiteInfo["sidebarinfo"] = $dbVars['RESULT'][0]['sidebarinfo'];
		$websiteInfo["sidebarorientation"] = $dbVars['RESULT'][0]['sidebarorientation'];
		$websiteInfo["imagetagcloud"] = $dbVars['RESULT'][0]['imagetagcloud'];

	}//else

	//get all the design parameters
	$design_form_options = explode(':..::..::',$templateData['designformoptions']);
	if($design_form_options[(count($design_form_options)-1)] == ''){unset($design_form_options[(count($design_form_options)-1)]); }
	//GET THE WEBSITE BACKGROUND IMAGE
	$website_wallpaper = explode(':',$design_form_options[0]);
	if($website_wallpaper[1] == 'image'){$websiteDesign['wallpaper'] = 'image';}
	else{$websiteDesign['wallpaper'] = '';}

	//GET IF THERE IS A WALLPAPER IMAGE OR NOT
	$website_wallpaper_status = explode(':',$design_form_options[14]);
	if($website_wallpaper_status[1] == 'image'){$websiteDesign['wallpapertype'] = 'image';}
	else{$websiteDesign['wallpapertype'] = 'empty';}

	//GET THE WEBSITE BACKGROUND COLOR
	$website_wallpaper = explode(':',$design_form_options[1]);
	$websiteDesign['bg_color'] = "".$website_wallpaper[1];
	//GET THE WEBSITE LOGO, WEBSITE_TITLE, IMAGE OR USERNAME
	$website_logo = explode(':',$design_form_options[2]);
	$website_logo_type = explode('_',$website_logo[1]);
	if($website_logo_type[1] == 'image'){$websiteDesign['logo'] = 'image';}
		else if($website_logo_type[1] == 'websitename'){$websiteDesign['logo'] = 'websitename';}
		else if($website_logo_type[1] == 'username'){$websiteDesign['logo'] = 'username';}
	//GET THE SIDEBAR ORIENTATION, sidebarleft, sidebarright or sidebartop
	$website_sidebarorientation = explode(':',$design_form_options[3]);
	if($website_sidebarorientation[1] == 'left'){$websiteDesign['sidebarorientation'] = 'sidebar'.'left';}
		else if($website_sidebarorientation[1] == 'right'){$websiteDesign['sidebarorientation'] = 'sidebar'.'right';}
		else if($website_sidebarorientation[1] == 'top'){$websiteDesign['sidebarorientation'] = 'sidebar'.'top';}
	//GET THE GLOBAL WEBSITE TEXT COLOR
	$website_textcolor_global = explode(':',$design_form_options[4]);
	$website_textcolor_global_type = explode('_',$website_textcolor_global[1]);
	$websiteDesign['textcolor_global'] = ''.$website_textcolor_global_type[1];
	//GET THE WEBSITE LINK COLOR
	$website_textcolor_link = explode(':',$design_form_options[5]);
	$website_textcolor_link_type = explode('_',$website_textcolor_link[1]);
	$websiteDesign['textcolor_link'] = ''.$website_textcolor_link_type[1];
	//GET THE WEBSITE LINK BACKGROUND COLOR
	$website_textcolor_bglink = explode(':',$design_form_options[6]);
	$website_textcolor_bglink_type = explode('_',$website_textcolor_bglink[1]);
	$websiteDesign['textcolor_bglink'] = ''.$website_textcolor_bglink_type[1];
	//GET THE WEBSITE LINK HOVER COLOR
	$website_textcolor_link_hover = explode(':',$design_form_options[7]);
	$website_textcolor_link_hover_type = explode('_',$website_textcolor_link_hover[1]);
	$websiteDesign['textcolor_link_hover'] = ''.$website_textcolor_link_hover_type[1];
	//GET THE WEBSITE LINK HOVER BACKGROUND COLOR
	$website_textcolor_bglink_hover = explode(':',$design_form_options[8]);
	$website_textcolor_bglink_hover_type = explode('_',$website_textcolor_bglink_hover[1]);
	$websiteDesign['textcolor_bglink_hover'] = ''.$website_textcolor_bglink_hover_type[1];
	//GET THE WEBSITE NAVIGATION LINKS FONT-SIZE
	$website_text_navisize = explode(':',$design_form_options[9]);
	$website_text_navisize_type = explode('_',$website_text_navisize[1]);
	$websiteDesign['text_navisize'] = 'navilinksize_'.$website_text_navisize_type[1];
	//GET THE WEBSITE SECTION BACKGROUND TYPE, LEVELS OF TRANSPARENCY OR COLOR
	$website_section_bg = explode(':',$design_form_options[10]);
	$website_section_bg_type = explode('_',$website_section_bg[1]);
	if($website_section_bg_type[0] == 'trans'){$websiteDesign['section_bg'] = $website_section_bg[1];}
	if($website_section_bg_type[1] == 'color'){$websiteDesign['section_bg'] = 'trans_color'; $websiteDesign['section_bg_color'] = $website_section_bg_type[2];}


	//GET THE WEBSITE FONT FAMILY
	$website_fontfamily = explode(':',$design_form_options[11]);
	$website_fontfamily_type = explode('_',$website_fontfamily[1]);
	$websiteDesign['font_family'] = $website_fontfamily_type[1];
	//GET THE GLOBAL TEXT HIGHLIGHT COLOR
	$website_text_global_highlight = explode(':',$design_form_options[12]);
	$website_text_global_highlight_type = explode('_',$website_text_global_highlight[1]);
	$websiteDesign['textcolor_global_highlight'] = "".$website_text_global_highlight_type[1];
	//GET THE GLOBAL TEXT HIGHLIGHT BACKGROUND COLOR
	$website_text_global_bghighlight = explode(':',$design_form_options[13]);
	$website_text_global_bghighlight_type = explode('_',$website_text_global_bghighlight[1]);
	$websiteDesign['textcolor_global_bghighlight'] = "".$website_text_global_bghighlight_type[1];

	//updated:0:.:.:copyright:0:.:.:email:1
	$website_sidebarinfo = explode(':.:.:',$websiteInfo["sidebarinfo"]);
	$sidebar_updated = explode(':',$website_sidebarinfo[0]); $websiteDesign['sidebar_updated'] = $sidebar_updated[1];
	$sidebar_copyright = explode(':',$website_sidebarinfo[1]); $websiteDesign['sidebar_copyright'] = $sidebar_copyright[1];
	$sidebar_email = explode(':',$website_sidebarinfo[2]); $websiteDesign['sidebar_email'] = $sidebar_email[1];

	//echo $templateData['designformoptions']."<hr>";
	//print_r($websiteDesign)."<hr>";

?>
    <div class='designeditor_container_title'>settings:</div><span></span>
    <ul id='designeditor_settings'>

    	<li class='messages' id='designeditor_frm_messages'></li>
        <li class='clear'></li>
       	<li class='displaynone'>
            <span id='user_username' class=''><?=$userData['username']?></span>
        	<span id='user_email' class=''><?=$userData['email']?></span>
        </li>
        <li>
            <label for=''>sidebar orientation: </label>
            <div id='sidebarorientation_options'>
            <?php
				switch($websiteDesign['sidebarorientation'])
				{
					case 'sidebarleft':
						$sidebarLeft = 'selectedtag';
						$websiteDesign['sidebarorientation'] = 'left';
						break;
					case 'sidebarright':
						$sidebarRight = 'selectedtag';
						$websiteDesign['sidebarorientation'] = 'right';
						break;
					case 'sidebartop':
						$sidebarTop = 'selectedtag';
						$websiteDesign['sidebarorientation'] = 'top';
						break;
				}//
			?>
                <span class='sidebarorientation <?=$sidebarLeft?>' id='sidebarorientation_left'>left</span> /
                <span class='sidebarorientation <?=$sidebarRight?>' id='sidebarorientation_right'>right</span> /
                <span class='sidebarorientation <?=$sidebarTop?>' id='sidebarorientation_top'>top</span>
            </div>
        </li>
        <li class='clear'></li>

        <li class='odd'>
        	<label for=''>wallpaper: </label>
            <?php
				switch($websiteDesign['wallpapertype'])
				{
					case 'empty':
						$wallpaperTypeEmpty = 'selectedtag';
						break;
					case 'image':
						$wallpaperTypeImage = 'selectedtag';
						break;
				}//switch()
			?>
            <span class='wallpapertype <?=$wallpaperTypeEmpty?>' id='wallpapertype_empty'>empty</span> /
            <span class='wallpapertype <?=$wallpaperTypeImage?>' id='wallpapertype_image'>image</span>
        </li>

		<? if($websiteDesign['wallpapertype'] == 'empty'){$wallpaperImageClass = 'displaynone';} ?>
        <li id='wallpaper_li' class='<?=$wallpaperImageClass?> odd'>
            <label for=''>wallpaper image: </label>

            <span id='wallpaperimage_uploader_container'>
                <div id='spanButtonPlaceholderContainerWallpaper'><span id="spanButtonPlaceholderWallpaper"></span></div>
            </span>
			<br />
            <span id='wallpaperimage_uploadprogress_container'>
            	<div id="divFileProgressContainerWallpaper"></div>
            </span>

        </li>
        <li class='clear'></li>
        <li class='odd'>
            <label for=''>wallpaper color: </label>
            #<input class='text8' id='wallpapercolor' type='text' maxlength="6" value='<?=$websiteDesign['bg_color']?>' />
            <div class='designeditor_colordemo' id='colordemo_wallpapercolor' style="background-color:#<?=$websiteDesign['bg_color']?>;color:#<?=$websiteDesign['bg_color']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>
        <li class=''>
            <label for=''>logo type: </label>
            <div id='logotype_options'>
            <?php
				switch($websiteDesign['logo'])
				{
					case 'image':
						$logoImage = 'selectedtag';
						break;
					case 'username':
						$logoUsername = 'selectedtag';
						break;
					case 'websitename':
						$logoWebsiteName = 'selectedtag';
						break;
				}//switch()
			?>
            	<span class='logotype <?=$logoImage?>' id='logotype_image'>image</span> /
                <span class='logotype <?=$logoUsername?>' id='logotype_username'>username</span> /
                <span class='logotype <?=$logoWebsiteName?>' id='logotype_websitename'>website title</span>
            </div>
        </li>
        <li class='clear'></li>
        <? if($websiteDesign['logo'] != 'image'){$logoImageliClass = 'displaynone';} ?>
        <li id='logoimage_li' class='<?=$logoImageliClass?> odd'>
            <label for=''>logo image: </label>

            <span id='logoimage_uploader_container'>
                <div id='spanButtonPlaceholderContainerLogo'><span id="spanButtonPlaceholderLogo"></span> </div>
            </span>
			<br />
            <span id='logoimage_uploadprogress_container'>
            	<div id="divFileProgressContainerLogo"></div>
            </span>

        </li>
        <li class='clear'></li>

        <li class=''>
            <label for=''>sections background: </label>
            <div id='sectionsbackground_options'>
            	<?php
					switch($websiteDesign['section_bg'])
					{
						case 'trans_100':
							$trans100 = 'selectedtag';
							break;
						case 'trans_white_light':
							$transWhiteLight = 'selectedtag';
							break;
						case 'trans_white_heavy':
							$transWhiteHeavy = 'selectedtag';
							break;
						case 'trans_black_light':
							$transBlackLight = 'selectedtag';
							break;
						case 'trans_black_heavy':
							$transBlackHeavy = 'selectedtag';
							break;
						default:
							$transColor = 'selectedtag';
							break;
					}//switch()
				?>
                <span class='sectionsbackground <?=$trans100?>' id='sectionsbackground_transparent100'>transparent 100%</span>
                <span class='sectionsbackground <?=$transWhiteLight?>' id='sectionsbackground_transparentlightwhite'>transparent light white</span>
                <span class='sectionsbackground <?=$transWhiteHeavy?>' id='sectionsbackground_transparentheavywhite'>transparent heavy white</span>
                <span class='sectionsbackground <?=$transBlackLight?>' id='sectionsbackground_transparentlightblack'>transparent light black</span>
                <span class='sectionsbackground <?=$transBlackHeavy?>' id='sectionsbackground_transparentheavyblack'>transparent heavy black</span>
                <span class='sectionsbackground <?=$transColor?>' id='sectionsbackground_transparentcolor'>color</span>
            </div>
        </li>
        <li class='clear'></li>

        <? if($transColor != 'selectedtag') { $sectionbackgroundColorClass = 'displaynone'; } ?>
        <li id='sectionsbackgroundcolor_li' class='<?=$sectionbackgroundColorClass?> odd'>
            <label for=''>sections background color:</label>
            <?php
				$websiteDesignSection_bg_temp = explode('_',$websiteDesign['section_bg']);
				if($websiteDesignSection_bg_temp[2] == '100' || $websiteDesignSection_bg_temp[2]== 'light' || $websiteDesignSection_bg_temp[2]== 'heavy'){$websiteDesignSection_bg_temp[2] = '';}
			?>
            #<input class='text8' id='sectionsbackgroundcolor' type='text' maxlength="6" value='<?=$websiteDesign['section_bg_color']?>' />
            <div class='designeditor_colordemo' id='colordemo_sectionsbackgroundcolor' style="background-color:#<?=$websiteDesign['section_bg_color']?>;color:#<?=$websiteDesign['section_bg_color']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>


        <li>
            <label for=''>text font:</label>
            <?php

			switch($websiteDesign['font_family'])
			{
				case '"Courier New", Courier, monospace':
					$fontCourier = 'selectedtag';
					break;
				case 'Helvetica':
					$fontHelvetica = 'selectedtag';
					break;
				case 'Georgia, "Times New Roman", Times, serif':
					$fontGeorgia = 'selectedtag';
					break;
				case 'Verdana, Arial, Helvetica, sans-serif':
					$fontVerdana = 'selectedtag';
					break;
				case 'Geneva, Arial, Helvetica, sans-serif':
					$fontGeneva = 'selectedtag';
					break;
			}//switch()
			?>
            <div id='textfont_options'>
                <span class='textfont <?=$fontCourier?>' id='textfont_couriernew'>Courier New</span>
                <span class='textfont <?=$fontHelvetica?>' id='textfont_helvetica'>Helvetica</span>
                <span class='textfont <?=$fontGeorgia?>' id='textfont_georgia'>Georgia</span>
                <span class='textfont <?=$fontVerdana?>' id='textfont_verdana'>Verdana</span>
                <span class='textfont <?=$fontGeneva?>' id='textfont_geneva'>Geneva</span>
            </div>
        </li>
        <li class='odd'>
            <label for=''>text color: </label>
            #<input class='text8' id='textcolor' type='text' maxlength="6" value='<?=$websiteDesign['textcolor_global']?>' />
            <div class='designeditor_colordemo' id='colordemo_textcolor' style="background-color:#<?=$websiteDesign['textcolor_global']?>;color:#<?=$websiteDesign['textcolor_global']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>
        <li>
            <label for=''>highlighted text color: </label>
            #<input class='text8' id='highlightedtextcolor' type='text' maxlength="6" value='<?=$websiteDesign['textcolor_global_highlight']?>' />
            <div class='designeditor_colordemo' id='colordemo_highlightedtextcolor' style="background-color:#<?=$websiteDesign['textcolor_global_highlight']?>;color:#<?=$websiteDesign['textcolor_global_highlight']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>
        <li class='odd'>
            <label for=''>highlighted text background color: </label>
            #<input class='text8' id='highlightedtextbackgroundcolor' type='text' maxlength="6" value='<?=$websiteDesign['textcolor_global_bghighlight']?>' />
            <div class='designeditor_colordemo' id='colordemo_highlightedtextbackgroundcolor' style="background-color:#<?=$websiteDesign['textcolor_global_bghighlight']?>;color:#<?=$websiteDesign['textcolor_global_bghighlight']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>
        <li>
            <label for=''>links color: </label>
            #<input class='text8' id='linkscolor' type='text' maxlength="6" value='<?=$websiteDesign['textcolor_link']?>' />
            <div class='designeditor_colordemo' id='colordemo_linkscolor' style="background-color:#<?=$websiteDesign['textcolor_link']?>;color:#<?=$websiteDesign['textcolor_link']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>
        <li class='odd'>
            <label for=''>links background color: </label>
            #<input class='text8' id='linksbackgroundcolor' type='text' maxlength="6" value='<?=$websiteDesign['textcolor_bglink']?>' />
            <div class='designeditor_colordemo' id='colordemo_linksbackgroundcolor' style="background-color:#<?=$websiteDesign['textcolor_bglink']?>;color:#<?=$websiteDesign['textcolor_bglink']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>
        <li>
            <label for=''>links hover color: </label>
            #<input class='text8' id='linkshovercolor' type='text' maxlength="6" value='<?=$websiteDesign['textcolor_link_hover']?>' />
            <div class='designeditor_colordemo' id='colordemo_linkshovercolor' style="background-color:#<?=$websiteDesign['textcolor_link_hover']?>;color:#<?=$websiteDesign['textcolor_link_hover']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>
        <li class='odd'>
            <label for=''>links hover background color: </label>
            #<input class='text8' id='linkshoverbackgroundcolor' type='text' maxlength="6" value='<?=$websiteDesign['textcolor_bglink_hover']?>' />
            <div class='designeditor_colordemo' id='colordemo_linkshoverbackgroundcolor' style="background-color:#<?=$websiteDesign['textcolor_bglink_hover']?>;color:#<?=$websiteDesign['textcolor_bglink_hover']?>" >-</div>(empty for no color)
        </li>
        <li class='clear'></li>
        <li>
            <label for=''>navigation links size: </label>
            <div id='navigationlinkssize_options'>
            <?php
				switch($websiteDesign['text_navisize'])
				{
					case 'navilinksize_tiny':
						$naviSizeTiny = 'selectedtag';
						break;
					case 'navilinksize_small':
						$naviSizeSmall = 'selectedtag';
						break;
					case 'navilinksize_medium':
						$naviSizeMedium = 'selectedtag';
						break;
					case 'navilinksize_large':
						$naviSizeLarge = 'selectedtag';
						break;
				}//switch()
			?>
            	<span class='navigationlinkssize navilinksize_tiny <?=$naviSizeTiny?>' id='navigationlinkssize_tiny'>tiny</span> /
                <span class='navigationlinkssize navilinksize_small <?=$naviSizeSmall?>' id='navigationlinkssize_small'>small</span> /
                <span class='navigationlinkssize navilinksize_medium <?=$naviSizeMedium?>' id='navigationlinkssize_medium'>medium</span> /
                <span class='navigationlinkssize navilinksize_large <?=$naviSizeLarge?>' id='navigationlinkssize_large'>large</span>
            </div>
        </li>
        <li class='clear'></li>

        <li class='odd'>
            <label for=''>Add an images tag cloud on the sidebar: </label>
            <div id='addimagestagcloud_options'>
            	<?php
					if($websiteInfo["imagetagcloud"] == 'yes'){$addImageTagCloudYes = 'selectedtag';}
					else if($websiteInfo["imagetagcloud"] == 'no'){$addImageTagCloudNo = 'selectedtag';}
				?>
                <span class='addimagestagcloud <?=$addImageTagCloudYes?>' id='addimagestagcloud_yes'>yes</span> /
                <span class='addimagestagcloud <?=$addImageTagCloudNo?>' id='addimagestagcloud_no'>no</span>
            </div>
        </li>
        <li class='clear'></li>

        <li>
            <label for=''>Add your email on the sidebar: </label>
            <div id='addemail_options'>
           		<?php
				if($websiteDesign["sidebar_email"] == '1'){$addEmailYes = 'selectedtag';}
				else{$addEmailNo = 'selectedtag';}
				?>
                <span class='addemail <?=$addEmailYes?>' id='addemail_yes'>yes</span> /
                <span class='addemail <?=$addEmailNo?>' id='addemail_no'>no</span>
            </div>
        </li>
        <li class='clear'></li>
        <li class='odd'>
            <label for=''>Add a "last updated" date on the sidebar: </label>
            <div id='addupdated_options'>
            	<?php
				if($websiteDesign["sidebar_updated"] == '1'){$addUpdatedYes = 'selectedtag';}
				else{$addUpdatedNo = 'selectedtag';}
				?>
                <span class='addupdated <?=$addUpdatedYes?>' id='addupdated_yes'>yes</span> /
                <span class='addupdated <?=$addUpdatedNo?>' id='addupdated_no'>no</span>
            </div>
        </li>
        <li class='clear'></li>
        <li class='displaynone'>
            <label for=''>Add a copyright notification: </label>
            <div id='addcopyright_options'>
            	<?php
				if($websiteDesign["sidebar_copyright"] == '1'){$addCopyrightYes = 'selectedtag';}
				else{$addCopyrightNo = 'selectedtag';}
				?>
                <span class='addcopyright <?=$addCopyrightYes?>' id='addcopyright_yes'>yes</span> /
                <span class='addcopyright <?=$addCopyrightNo?>' id='addcopyright_no'>no</span>
            </div>
        </li>

        <li class='displaynone'>
        	<span id='de_wallpapertype'><?=$websiteDesign['wallpapertype']?></span>
        	<span id='de_wallpaper'><?=$websiteDesign['wallpaper']?></span>
            <span id='de_wallpapercolor'><?=$websiteDesign['bg_color']?></span>
            <span id='de_logotext'><?=$websiteDesign['logo']?></span>
            <span id='de_sidebarorientation'><?=$websiteDesign['sidebarorientation']?></span>
            <span id='de_texttextcolor'><?=$websiteDesign['textcolor_global']?></span>
            <span id='de_textlinkcolor'><?=$websiteDesign['textcolor_link']?></span>
            <span id='de_linkbgcolor'><?=$websiteDesign['textcolor_bglink']?></span>
            <span id='de_textlinkhovercolor'><?=$websiteDesign['textcolor_link_hover']?></span>
            <span id='de_textlinkhoverbgcolor'><?=$websiteDesign['textcolor_bglink_hover']?></span>

            <?php $websiteDesign['text_navisize'] = explode('_',$websiteDesign['text_navisize']); ?>
            <span id='de_textnavilinksize'><?=$websiteDesign['text_navisize'][1]?></span>

            <? if($websiteDesign['section_bg'] == 'trans_color'){$websiteDesign['section_bg'] = $websiteDesign['section_bg'].'_'.$websiteDesign['section_bg_color'];} ?>
            <span id='de_sectionsbg'><?=$websiteDesign['section_bg']?></span>
            <span id='de_textfontfamily'><?=$websiteDesign['font_family']?></span>
            <span id='de_texthighlightcolor'><?=$websiteDesign['textcolor_global_highlight']?></span>
            <span id='de_textbghighlightcolor'><?=$websiteDesign['textcolor_global_bghighlight']?></span>
            <span id='de_imagetagcloud'><?=$websiteInfo["imagetagcloud"]?></span>
            <span id='de_sidebar_updated'><?=$websiteDesign['sidebar_updated']?></span>
            <span id='de_sidebar_copyright'><?=$websiteDesign['sidebar_copyright']?></span>
            <span id='de_sidebar_email'><?=$websiteDesign['sidebar_email']?></span>
            <span id='templateid'><?=$templateData['tid']?></span>
            <span id='uid'><?=$userData['uid']?></span>
        </li>
        <li class='clear'></li>
        <li>
            <span class='submit' id='designeditor_submit'>commit changes</span>
        </li>
    </ul>
<?
	unset($dbobj);
?>
	<script language="javascript" type="text/javascript">
        username = $('#user_username').html();
        email = $('#user_email').html();

        swfuFunction_wallpaper(username,email);
		swfuFunction_logo(username,email);
    </script>
<?
}//layout_get_designeditor_settings($templateID)

?>
