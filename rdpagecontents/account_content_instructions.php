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


<div class='section_content_page_instructions_container'>

        The user's account page features a number of sections, each including settings and options to provide help on customizing your website.
        <br /><br />
        For any questions regarding the following, please e-mail us at <a href='mailto:<?php echo DEFAULT_EMAIL; ?>' class='blacklink'>mail@retinadestruction.com</a>
        <ul class='outer_ul'>
            <li>
                <span class='blackbackground'>Section 0: Overview</span>
                <span class='section_description'>
                    This section gives a general view of your account, your statistics regarding your profile and your website.
                    The information there, can be altered from the <b>"settings"</b> section.
                </span>
            </li>
            <li>
                <span class='blackbackground'>Section 1: Settings</span>
                 <span class='section_description'>
                    A section collecting all the settings and options to help you:
                    <ul class='inner_ul'>
                        <li>Edit your profile and website.</li>
                        <li>Change your privacy options.</li>
                        <li>Reset your password.</li>
                        <li>Define the way your images are uploaded in your website.</li>
                    </ul>
                </span>
            </li>
            <li>
                <span class='blackbackground'>Section 2: Albums</span>
                 <span class='section_description'>
                    Providing a full set of options about the following matters:
                    <ul class='inner_ul'>
                        <li>Create, update and delete albums.</li>
                        <li>Add description for each album, and embed videos from sources such as youtube.com and vimeo.com</li>
                        <li>Sort your albums in categories. Albums that belong in the same category are grouped together at your website. <b>Albums not belonging in any category, are presented as separate pages.</b></li>
                        <li>Upload covers for your albums.</li>
                        <li>Edit the visibility of each album (invisible albums, are not accessed by your website visitors),</li>
                        <li>View all the comments regarding each album, and respond options.</li>
                        <li>Select how the images are presented in the album from three available options: fullsize, thumbnails and large thumbnails. Every album can have a different option selected.</li>
                        <li>Upload, re-order and delete album images.</li>
                        <li>Add captions and tags to your images. Visitors can search all images under the same tag.</li>
                        <li>Set the way your images are presented for each album.</li>
                        <li><b>NOTE:</b> For every image that is upload to your website website, a new blog entry is automatically created that includes that image. All images uploaded on the same date, are included in the same blog entry. This is an automatic action, these  blog entries are completely customizable and by default invisible to the website visitors.
                        </li>

                    </ul>
                </span>
            </li>
            <li>
                <span class='blackbackground'>Section 3: Blog</span>
                 <span class='section_description'>
                    Includes options that are very similar with the album options.
                    <ul class='inner_ul'>
                        <li>Create, update and delete blog entries.</li>
                        <li>Add a message for each blog entry, and embed videos from sources such youtube.com and vimeo.com</li>
                        <li>Sort your blog entries with tags (keywords). Visitors can search all blog entries under the same tag.</li>

                        <li>View all the comments regarding each blog entry, and respond to them.</li>
                    	<li>Select how the images are presented in a blog entry. Every entry can have a different option selected.</li>
                        <li>Edit the visibility of each entry.</li>

                        <li>Upload, re-order and delete album images.</li>
                        <li>Add captions and tags to your images. Visitors can search all images under the same tag.</li>
                        <li>Set the way your images are presented for each blog entry.</li>
                        <li><b>NOTE:</b>Every image that is upload inside a blog entry, is saved in a specific album that is created automatically.
                        This album is by default invisible to your website visitors.
                        </li>
                    </ul>
                </span>
            </li>
            <li>
                <span class='blackbackground'>Section 4: Cover Page</span>
                 <span class='section_description'>
                    <b>Cover page</b> is the page that appears when your website is loaded. In other words, it's the <b>home page</b> of your portfolio wesite.
                    <br /><br />
                    The cover page can be: <span class='blackbackground'>1.</span> empty, <span class='blackbackground'>2.</span> The blog section of your website, <span class='blackbackground'>3.</span> A randomly loaded selected image.
                    <br /><br />
                    <b>NOTE:</b>Images that are upload to be used as random cover images, are saved in a specific album that is created automatically.
                    This album is by default invisible to your website visitors.
                </span>
            </li>
            <li>
                <span class='blackbackground'>Section 5: Design Editor</span>
                 <span class='section_description'>
                    A most elaborate section. It contains all the necessary settings to change the "look and feel" of your website.
                    There are 4 templates which are actually predefined values for all the design settings.
                    From there, you can change each setting to fit your individual website presentation needs.
                </span>
            </li>
        </ul>
    <div class='muchneededhight'></div>
    <div id='eddie' class='displaynone'><?=$page_filename_containerPage?></div>
    <div id='clint' class='displaynone'><?=$csrfPasswordGenerator_containerPage?></div>
</div>

<? } ?>
