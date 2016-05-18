// JavaScript Document

var sectionsArr = new Array();
//var sectionsStateArr = new Array();
var sectionName_lastOpened = null;
var sectionTitleClass_lastOpened = null;
var sectionContentClass_lastOpened = null;

var contactMessageMaxLength = 2500;
var profileDescriptionMaxLength = 3000;
var albumDescriptionMaxLength = 3000;
var postMessageMaxLength = 3500;
var commentMessageMaxLength = 1500;

$(document).ready(function() {
	var containerPageName = $("#eddie").html();
	elements_page_initialize(containerPageName);

	if(containerPageName != 'accountactions'){resizePanel();}
    //resize all the items according to the new browser size  
    $(window).resize(function () {
        //call the resizePanel function  
       if(containerPageName != 'accountactions'){ resizePanel();  }
    });
}); 

function elements_page_initialize(pageName)
{
	switch(pageName)
	{
		case 'main':
			navigation_initialize_main();
			elements_common_initialize();
			//options_credential_initialize();
			break;
		case 'account':
			navigation_initialize_account();
			elements_common_initialize();
			//setTimeout('section_contentpages_account_defaultopen()',500);
			break;
		case 'accountactions':
			$('a.panel').click(function(){
				 window.location.replace("./index.php");
			});
			elements_common_initialize();
			section_contentpages_initialize_main('accountactions');
			break;
		case '':
			break;
		case 'browsersettings':
		/*
			if($('#disabletype').length!=0)
			{
				$disableType = $('#disabletype').html();
				switch($disableType)
				{
					case 'javascriptdisabled':
						window.location = "./index.php";
						break;
					case 'cookiesdisabled':
						//do nothing
						break;
					default:
						window.location = "./index.php";
						break;
				}//switch()
			}//if
			else
			{
				window.location = "./index.php";
			}//
		*/
			break;
		case 'user':
			break;
		default:
			//initializeCredentialOptions();
			break;
	}//switch
}//elements_page_initialize()

function elements_common_initialize()
{
	$('#logo').click(function(){ window.location.replace("./index.php"); });
	section_contentpages_initialize_main('signout');
}//elements_common_initialize()

function navigation_initialize_main()
{
	sectionsArr = new Array("introduction","about","contact","artistsindex","featuredartists","registration","signin","terms");
	//sectionsStateArr = new Array("closed","closed","closed","closed","closed","closed","closed","closed");

	$('#logo').click(function()
		{section_close_all('main'); resetVariables();});
	
    $('a.panel').click(function(){
        currentObj = $(this);
		section_open(currentObj,'main');
		//cancel the link default behavior  
        return false;  
    });
	$('div.section_title').click(function(){
		linkID = $(this).attr('id')
		linkID = linkID.split('_');
		sectionName = linkID[1];
		section_open($('#link_'+sectionName),'main');
		//cancel the link default behavior  
        return false;  
	});
}//navigation_initialize_main()


function navigation_initialize_account()
{
	sectionsArr = new Array("overview","albums","blog","settings","coverpage","designeditor","instructions");
	//sectionsStateArr = new Array();
	
	$('#logo').click(function()
		{section_close_all('account');});
	
    $('a.panel').click(function(){
        currentObj = $(this);
		section_open(currentObj,'account');
		//cancel the link default behavior  
        return false;  
    });
	$('div.section_title').click(function(){
		linkID = $(this).attr('id')
		linkID = linkID.split('_');
		sectionName = linkID[1];
		section_open($('#link_'+sectionName),'account');
		//cancel the link default behavior  
        return false;  
	});
	//$('link_help').click(function(){});
}//navigation_initialize_account()


function section_close_all(pageName)
{
	if(sectionName_lastOpened != null)
	{
		$('#section_'+sectionName_lastOpened+'_title').switchClass(sectionTitleClass_lastOpened,'section_title',500);
		$('#section_'+sectionName_lastOpened+'_content').switchClass(sectionContentClass_lastOpened,'section_content',500);
		$('a.panel').removeClass('selected');
		$('span.section_content_page').html('');
		resizePanel();
	}//if
	for(var i=0; i<sectionsArr.length; i++)
	{
		$('#section_'+sectionsArr[i]).fadeTo(500,1);
		//$('#section_'+sectionsArr[i]).hide();
	}//for
	$('#wrapper').scrollTo('#top', 500, function(){resizePanel();});
}//section_close_all()

function section_open(obj,pageName)
{
	linkID = obj.attr('id');
	linkID = linkID.split('_');
	sectionName = linkID[1];
	//default selected
	sectionTitleClass = 'section_title_selected';
	sectionContentClass = 'section_content_selected';
	
	sectionTitleClass += '_'+sectionName;
	sectionContentClass += '_'+sectionName;
	
	if(sectionName_lastOpened == sectionName){section_close_all(); sectionName_lastOpened = null; return 1; }
	
	if(sectionName_lastOpened != null)
	{
		$('#section_'+sectionName_lastOpened+'_title').switchClass(sectionTitleClass_lastOpened,'section_title',0,function(){resizePanel();});
		$('#section_'+sectionName_lastOpened+'_content').switchClass(sectionContentClass_lastOpened,'section_content',0,function(){resizePanel();});
		$('#section_content_page_'+sectionName_lastOpened).html('');
	}//if
	for(var i=0; i<sectionsArr.length; i++)
	{
		//$('#section_'+sectionsArr[i]).show();
		if(sectionsArr[i] == linkID[1]){selectedSectionPointer = i; continue;}
		$('#section_'+sectionsArr[i]).fadeTo(500, 0.1);
		//$('#section_'+sectionsArr[i]).hide();
	}//for
	
	$('#wrapper').scrollTo(obj.attr('href'), 500, function(){
		//$('#section_'+sectionName).show();
		$('#section_'+sectionName).fadeTo(500, 1);
		
		$('#section_'+sectionName+'_title').switchClass('section_title',sectionTitleClass,500);
		$('#section_'+sectionName+'_content').switchClass('section_content',sectionContentClass,500);
		//$('#section_'+sectionName+'_content').jScrollPane({showArrows:true});
		//reset and highlight the clicked link  
		$('a.panel').removeClass('selected');  
		obj.addClass('selected');
		resizePanel();

		sectionHeightClass = 'sectionheight500';
		if((400<height)&&(height<500)){sectionHeightClass = 'sectionheight450';}
		if((500<height)&&(height<600)){sectionHeightClass = 'sectionheight500';}
		if((600<height)&&(height<700)){sectionHeightClass = 'sectionheight550';}
		if((700<height)&&(height<800)){sectionHeightClass = 'sectionheight600';}
		if((800<height)&&(height<900)){sectionHeightClass = 'sectionheight650';}
		if((900<height)&&(height<1000)){sectionHeightClass = 'sectionheight700';}
		$('#section_'+sectionName+'_content').addClass(sectionHeightClass);


		sectionName_lastOpened = sectionName;
		sectionTitleClass_lastOpened = sectionTitleClass;
		sectionContentClass_lastOpened = sectionContentClass;

		section_contentpages_initialize(pageName,sectionName);
		//resizePanel();
	});//
}//section_open(obj)

function resizePanel() {  
    //get the browser width and height  
    width = $(window).width();  
    height = $(window).height();  

    //get the mask height: height * total of items  
    mask_height = height * $('.sections').length;  

    //set the dimension       
    $('#wrapper, .sections').css({width: width, height: height});  
    $('#mask').css({width: width, height: mask_height});  

    //if the item is displayed incorrectly, set it to the corrent pos
	//alert($('a.selected').attr('href'));
	if($('a.selected').attr('href') != null)
   		{$('#wrapper').scrollTo($('a.selected').attr('href'), 0);}
	else
		{$('#wrapper').scrollTo('#top', 0);}
}//resizePanel()

function section_contentpages_initialize(pageName,sectionName)
{
	switch(pageName)
	{
		case 'main':
			section_contentpages_initialize_main(sectionName);
			break;
		case 'account':
			section_contentpages_initialize_account(sectionName);
			break;
		default:
			break;
	}//pageName
}//section_contentpages_initialize(pageName,sectionName)
function section_contentpages_initialize_main(sectionName)
{
	switch(sectionName)
	{
		case 'registration':
			formID = 'register';
			resetVariables(formID);
			if($('#link_signout').length==0){
				
				$('#register_submit').unbind();
				$('#register_submit').click(function(event){ validateNsubmitMultipleValues('register'); });
				$('#register_username').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('register'); }});
				$('#register_email').keydown(function(event){if (event.which == 13) {validateNsubmitMultipleValues('register'); }});
				$('#register_password').keydown(function(event){if (event.which == 13) {validateNsubmitMultipleValues('register'); }});
				
				$('#'+formID+'_terms_button').click(function(){
					termsButtonVal = $('#'+formID+'_terms').val();
					if(termsButtonVal == 'checked')
					{
						$('#'+formID+'_terms_button').attr("src", './rdlayout/images/unchecked.gif');
						$('#'+formID+'_terms').val('unchecked');
					}else
					{
						$('#'+formID+'_terms_button').attr("src", './rdlayout/images/checked.gif');
						$('#'+formID+'_terms').val('checked');
					}//
				});
				
				$('#link_terms').click(function(){
					currentObj = $(this);
					section_open(currentObj,'main');
					//cancel the link default behavior  
					return false;  
				});
				
			}//if
			break;
		case 'signin':
			formID = 'login';
			resetVariables(formID);
			$('#login_container').show();
			$('#forgotpassword_container').hide();
			if($('#link_signout').length==0){
				$('#login_submit').unbind();
				$('#login_submit').click(function(event){validateNsubmitMultipleValues('login'); });
				$('#login_email').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('login'); }});
				$('#login_password').keydown(function(event){if (event.which == 13) {validateNsubmitMultipleValues('login'); }});
				
				$('#forgotpassword_toggler').click(function(event){
					section_contentpages_initialize_main('forgotpassword');
				});
				
			}//if
			break;
		case 'forgotpassword':
			formID = 'forgotpassword';
			resetVariables(formID);
			$('#login_container').hide();
			$('#forgotpassword_container').show();

			if($('#link_signout').length==0){
				$('#forgotpassword_submit').unbind();
				$('#forgotpassword_submit').click(function(event){validateNsubmitMultipleValues('forgotpassword'); });
				$('#forgotpassword_email').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('forgotpassword'); }});
				
				$('#signin_toggler').click(function(event){
					section_contentpages_initialize_main('signin');
				});
				
			}//if

			break;
		case 'terms':
			$("#section_content_page_"+sectionName).load("./rdpagecontents/main_content_"+sectionName+".php"+"?transmitter=index.php", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error')
				{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
			else
				{section_contentpages_account_initialize_listeners(sectionName);}//ALL OK
			});
			break;
		case 'signout':
		
			if($('#link_signout').length !=0 ){
				//$('#link_signout').click(function(event){validateNsubmitMultipleValues('logout'); });
			}//
			break;
		case 'accountactions':
			$('#resetpassword_submit').click(function(event){validateNsubmitMultipleValues('resetpassword'); });
			$('#resetpassword_password').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('resetpassword'); }});
			$('#resetpassword_repeatpassword').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('resetpassword'); }});
			break;
		case 'contact':
			
			$("#section_content_page_contact").load("./rdincfiles/layout.inc.php"+"?requesting=9", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
					mainsite_contact_initialize()
				}
			});
			break;
		case 'artistsindex':
			$('#loader_layer2').show();
			$("#section_content_page_artistsindex").load("./rdincfiles/layout.inc.php"+"?requesting=10", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{ 
					$('#loader_layer2').hide(); 
					
					$('.usercategories_tags').unbind();
					$('.usercategories_tags').click(function(event){
						$('.artistprofileli').removeClass('displaynone');
						obj = $(this);
						linkID = obj.attr('id');
						linkID = linkID.split('_');
						
						tagID = linkID[2];
						
						tagHelp = $('#taghelp').html();
						tagHelp = tagHelp.split('::');
						for($i=0; $i<tagHelp.length-1; $i++)
						{
							taggedHelpUsers_temp = tagHelp[$i].split('.');
							if(taggedHelpUsers_temp[0] == tagID)
							{
								taggedUsers_temp = taggedHelpUsers_temp[1].split(',');
								if(taggedUsers_temp.length == 1){$('#artistprofileli_0').removeClass('displaynone'); $('.artistprofileli').addClass('displaynone');}
								else
								{
									$('#artistprofileli_0').addClass('displaynone');
									$('.artistprofileli').addClass('displaynone');
									for($k=0; $k<taggedUsers_temp.length; $k++)
									{
										if($('#artistprofileli_'+taggedUsers_temp[$k]).length!=0)
											{$('#artistprofileli_'+taggedUsers_temp[$k]).removeClass('displaynone');}
									}//for
								}//else
							}//if
						}//for
					});
					
				}
			});	
			break;
			
		case 'featuredartists':
			$('#loader_layer2').show();
			$("#section_content_page_featuredartists").load("./rdincfiles/layout.inc.php"+"?requesting=11", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{ 
					$('#loader_layer2').hide(); 
				}
			});	
			break;
			
		default:
			//do nothing
			break;
	}//sectionName
}//section_contentpages_initialize_main(sectionName)
function section_contentpages_initialize_account(sectionName)
{
	$("#section_content_page_"+sectionName).load("./rdpagecontents/account_content_"+sectionName+".php"+"?transmitter=account.php", function(responseText, textStatus, XMLHttpRequest) {
		if(textStatus == 'error')
		{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
	else
		{section_contentpages_account_initialize_listeners(sectionName);}//ALL OK
	});
}//section_account_contentpages_initialize()


function section_contentpages_account_initialize_listeners(sectionName)
{
	window.scrollTo(0,0);
	switch(sectionName)
	{
		case 'overview':
			break;
		case 'settings':
		
			$('#account_settings_navigation_user').click(function(){
				$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=1", function(responseText, textStatus, XMLHttpRequest) {
					if(textStatus == 'error')
						{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
					else
						{section_contentpages_account_initialize_listeners('settings_user');}//ALL OK
				});
			});
			$('#account_settings_navigation_website').click(function(){
				$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=2", function(responseText, textStatus, XMLHttpRequest) {
					if(textStatus == 'error')
						{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
					else
						{section_contentpages_account_initialize_listeners('settings_website');}//ALL OK
				});
			});

			$('#account_settings_navigation_privacy').click(function(){
				$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=4", function(responseText, textStatus, XMLHttpRequest) {
					if(textStatus == 'error')
						{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
					else
						{section_contentpages_account_initialize_listeners('settings_privacy');}//ALL OK
				});
			});
			
			$('#account_settings_navigation_resetpassword').click(function(){
				$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=5", function(responseText, textStatus, XMLHttpRequest) {
					if(textStatus == 'error')
						{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
					else
						{section_contentpages_account_initialize_listeners('settings_resetpassword');}//ALL OK
				});
			});
			
			$('#account_settings_navigation_images').click(function(){
				$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=6", function(responseText, textStatus, XMLHttpRequest) {
					if(textStatus == 'error')
						{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
					else
						{section_contentpages_account_initialize_listeners('settings_images');}//ALL OK
				});
			});

			section_contentpages_account_initialize_listeners('settings_user');
			
			break;
		case 'settings_user':
		
			$('#user_submit').click(function(event){validateNsubmitMultipleValues('user'); });
			$('#user_name').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('user'); }});
			//$('#accountsettings_description').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('accountsettings'); }});	
			$('#user_description').keydown(function(event)
				{countCharsJQuery('#user_description','#user_description_counter',profileDescriptionMaxLength,event);});
			$('#user_facebook').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('user'); }});	
			$('#user_youtube').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('user'); }});
			$('#user_vimeo').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('user'); }});
			$('#user_myspace').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('user'); }});
			$('#user_twitter').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('user'); }});
			
			
			$('span.profileartisttags').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				artistTagID = linkID[1];
				
				classNames = $('#profileartisttag_'+artistTagID).attr('class');
				classNames = classNames.split(' ');
				
				tagAction = 'addtag';
				if(classNames[2] == 'selectedtag'){tagAction = 'removetag';}
				if(classNames[1] == 'selectedtag'){tagAction = 'removetag';}
				
				if(tagAction == 'addtag')
					{validateNsubmitMultipleValues('profileartisttag_addtag_'+artistTagID);}	
				else if(tagAction == 'removetag')
					{validateNsubmitMultipleValues('profileartisttag_removetag_'+artistTagID);}
			});
			
			$('span.profileartistgender').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				gender = linkID[1];
				
				classNames = $('#profileartistgender_'+gender).attr('class');
				classNames = classNames.split(' ');
				
				if(gender == 'male'){validateNsubmitMultipleValues('profileartistgender_'+gender);}
				else if(gender == 'female'){validateNsubmitMultipleValues('profileartistgender_'+gender);}
			});

			$('span.profileartistnewsletter').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				newsletterStatus = linkID[1];
				
				classNames = $('#profileartistnewsletter_'+newsletterStatus).attr('class');
				classNames = classNames.split(' ');

				if(newsletterStatus == 'enabled'){validateNsubmitMultipleValues('profileartistnewsletter_'+newsletterStatus);}
				else if(newsletterStatus == 'disabled'){validateNsubmitMultipleValues('profileartistnewsletter_'+newsletterStatus);}
			});

			break;
		case 'settings_website':
			$('#website_submit').click(function(event){validateNsubmitMultipleValues('website'); });
			$('#website_name').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('website'); }});
			$('#website_urltitle').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('website'); }});
			break;
		case 'settings_privacy':
			
			$('span.profileartistalbumcomments').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				commentsStatus = linkID[1];
				
				classNames = $('#profileartistalbumcomments_'+commentsStatus).attr('class');
				classNames = classNames.split(' ');

				if(commentsStatus == 'enabled'){validateNsubmitMultipleValues('profileartistalbumcomments_'+commentsStatus);}
				else if(commentsStatus == 'disabled'){validateNsubmitMultipleValues('profileartistalbumcomments_'+commentsStatus);}
			});
					
			$('span.profileartistblogpostcomments').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				commentsStatus = linkID[1];
				
				classNames = $('#profileartistblogpostcomments_'+commentsStatus).attr('class');
				classNames = classNames.split(' ');

				if(commentsStatus == 'enabled'){validateNsubmitMultipleValues('profileartistblogpostcomments_'+commentsStatus);}
				else if(commentsStatus == 'disabled'){validateNsubmitMultipleValues('profileartistblogpostcomments_'+commentsStatus);}
			});
								
			$('span.profileartistcommentnotifications').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				notificationsStatus = linkID[1];
				
				classNames = $('#profileartistcommentnotifications_'+notificationsStatus).attr('class');
				classNames = classNames.split(' ');

				if(notificationsStatus == 'enabled'){validateNsubmitMultipleValues('profileartistcommentnotifications_'+notificationsStatus);}
				else if(notificationsStatus == 'disabled'){validateNsubmitMultipleValues('profileartistcommentnotifications_'+notificationsStatus);}
			});
		
			$('span.profileartistvisibility').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				visibilityStatus = linkID[1];
				
				classNames = $('#profileartistvisibility_'+visibilityStatus).attr('class');
				classNames = classNames.split(' ');
				
				if(visibilityStatus == 'visible'){validateNsubmitMultipleValues('profileartistvisibility_'+visibilityStatus);}
				else if(visibilityStatus == 'invisible'){validateNsubmitMultipleValues('profileartistvisibility_'+visibilityStatus);}
			});
			break;
		case 'settings_resetpassword':
			$('#resetpassword_submit').click(function(event){validateNsubmitMultipleValues('resetpassword'); });
			$('#resetpassword_password').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('resetpassword'); }});
			$('#resetpassword_repeatpassword').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('resetpassword'); }});
			break;
		case 'coverpage':
			
			$('span.coverpagetype').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				coverPageType = linkID[1];
				
				switch(coverPageType)
				{
					case 'empty': validateNsubmitMultipleValues('coverpage_'+coverPageType); break;
					case 'blogsection': validateNsubmitMultipleValues('coverpage_'+coverPageType); break;
					case 'randomimage': validateNsubmitMultipleValues('coverpage_'+coverPageType); break;
				}//switch
			});
		
			portfolio_images_upload_listeners_initialize('coverpage');
			$('#coverpageimage_reset').click(function(){
				portfolio_images_upload_event_initialize('coverpage');
			});
			$('#coverpageimage_add').click(function(){												
				portfolio_images_upload_event_initialize('coverpage');
			});
			$('#coverpageimage_delete').click(function(){
				portfolio_images_delete_event_initialize('coverpage');				
			});
	
			break;
		case 'albums':
			$('#section_content_subpage_albums').fadeTo(0,0.9);
			$('#album_create_button').click(function(){validateNsubmitMultipleValues('createalbum');});
			$('li.albuminvisible').fadeTo(500,0.3);
			$('span.album_names').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				albumID = linkID[2];

				account_album_subpage_load_sections('album_page_edit',albumID,'');
				account_album_subpage_load_sections('album_page_view',albumID,'');
			});
			
			$('span.album_visibilitys').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				albumID = linkID[2];
				
				
				classNames = $('#album_visibility_'+albumID).attr('class');
				classNames = classNames.split(' ');
				
				visibilityStatus = classNames[1];
				
				if(visibilityStatus == 'albumvisible'){validateNsubmitMultipleValues('albumvisibility_'+albumID+'_'+visibilityStatus);}
				else if(visibilityStatus == 'albuminvisible'){validateNsubmitMultipleValues('albumvisibility_'+albumID+'_'+visibilityStatus);}		
			});
			
			$('span.album_deletes').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				albumID = linkID[2];
				
				$('span.album_container_deleteoptionss').addClass('displaynone');
				$('span.album_containers').removeClass('displaynone');
				
				$('#album_container_'+albumID).addClass('displaynone');
				$('#album_container_deleteoptions_'+albumID).removeClass('displaynone');
				$('#delete_album_yes_'+albumID).click(function(){validateNsubmitMultipleValues('albumdelete_'+albumID);});
				$('#delete_album_no_'+albumID).click(function(){
					$('#album_container_'+albumID).removeClass('displaynone');
					$('#album_container_deleteoptions_'+albumID).addClass('displaynone');
				});
			});
			
			$('.album_comments').click(function(){
				tagElementID = $(this).attr('id')
				tagElementID = tagElementID.split('_');
				albumID = parseInt(tagElementID[2]);
				userID = parseInt($('#steve').html());
				postID = '';

				comments_loadform_and_initialize_elements(albumID,userID,postID);
			});
			
			section_contentpages_account_initialize_listeners('album_demosubpage_albumlist');
			
			break;
		case 'album_subnavigation':
		
			$('#album_list_button').click(function(){
												   
				account_album_subpage_load_sections('album_list_edit','','');
				account_album_subpage_load_sections('album_list_view','','');
			});
			
			$('#album_description').keydown(function(event)
				{countCharsJQuery('#album_description','#album_description_counter',albumDescriptionMaxLength,event);});
			
			$('#album_submit').click(function(){validateNsubmitMultipleValues('album');});

			$('span.album_visibilitys').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				albumID = linkID[2];
				
				classNames = $('#album_visibility_'+albumID).attr('class');
				classNames = classNames.split(' ');
				
				visibilityStatus = classNames[1];
				
				if(visibilityStatus == 'albumvisible'){validateNsubmitMultipleValues('albumvisibility_'+albumID+'_'+visibilityStatus);}
				else if(visibilityStatus == 'albuminvisible'){validateNsubmitMultipleValues('albumvisibility_'+albumID+'_'+visibilityStatus);}		
			});


			$('span.album_imageviews').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				imageview = linkID[2];
				
				classNames = $('#album_imageview_'+imageview).attr('class');
				classNames = classNames.split(' ');


				if(imageview == 'fullsize'){validateNsubmitMultipleValues('album_imageview_'+imageview);}
				else if(imageview == 'thumbnails'){validateNsubmitMultipleValues('album_imageview_'+imageview);}
				else if(imageview == 'largethumbnails'){validateNsubmitMultipleValues('album_imageview_'+imageview);}
			});


			portfolio_images_upload_listeners_initialize();
			$('#albumimage_reset').click(function(){
				portfolio_images_upload_event_initialize('album');
			});
			$('#albumimage_add').click(function(){												
				portfolio_images_upload_event_initialize('album');
			});
			$('#albumimage_reorder').click(function(){
				portfolio_images_reorder_event_initialize();
			});
			$('#albumimage_delete').click(function(){
				portfolio_images_delete_event_initialize('album');				
			});

			var albumCategoryData = $('span.albumCategories').html().split("::"); $("#album_category").autocomplete(albumCategoryData); 
			break;
		case 'album_demosubpage_albumlist':
			$('span.view_names').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				albumID = linkID[2];

				account_album_subpage_load_sections('album_page_edit',albumID,'');
				account_album_subpage_load_sections('album_page_view',albumID,'');
			});

			$('.refreshsection').unbind();
			$('.refreshsection').click(function(){
				account_album_subpage_load_sections('album_list_view','','');
			});
			
			break;	
		case 'album_demosubpage_albumview':
			$('.refreshsection').unbind();
			$('.refreshsection').click(function(){
				albumID = $('#album_aid').html();
				account_album_subpage_load_sections('album_page_view',albumID,'');
			});
			break;
		case 'settings_images':
			$('#uploadimagessettings_submit').click(function(event){validateNsubmitMultipleValues('uploadimagessettingswidth'); });
			$('#uploadimagessettings_uploadimageswidth').keydown(function(event){if (event.which == 13) { validateNsubmitMultipleValues('uploadimagessettingswidth'); }});
			
			$('span.uploadimagessettingsoption').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				optionNumber = linkID[1];

				classNames = $('#uploadimagessettingsoption_'+optionNumber).attr('class');
				classNames = classNames.split(' ');

				if(optionNumber == 'option1'){validateNsubmitMultipleValues('uploadimagessettingsoption_'+optionNumber);}
				else if(optionNumber == 'option2'){validateNsubmitMultipleValues('uploadimagessettingsoption_'+optionNumber);}
				else if(optionNumber == 'option3'){validateNsubmitMultipleValues('uploadimagessettingsoption_'+optionNumber);}
			});
			
			break;
		case 'blog':
			$('#section_content_subpage_blog').fadeTo(0,0.9);
			$('#blog_create_button').click(function(){validateNsubmitMultipleValues('createpost');});
			$('li.bloginvisible').fadeTo(500,0.3);
			$('span.blog_headlines').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				blogID = linkID[2];
				
				account_blog_subpage_load_sections('blog_page_edit',blogID,'');
			});

			$('span.blog_visibilitys').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				blogID = linkID[2];

				
				classNames = $('#blog_visibility_'+blogID).attr('class');
				classNames = classNames.split(' ');

				visibilityStatus = classNames[1];
				
				if(visibilityStatus == 'blogvisible'){validateNsubmitMultipleValues('blogvisibility_'+blogID+'_'+visibilityStatus);}
				else if(visibilityStatus == 'bloginvisible'){validateNsubmitMultipleValues('blogvisibility_'+blogID+'_'+visibilityStatus);}		
			});
			
			$('span.blog_deletes').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				blogID = linkID[2];
				
				$('span.blog_container_deleteoptionss').addClass('displaynone');
				$('span.blog_containers').removeClass('displaynone');
				
				$('#blog_container_'+blogID).addClass('displaynone');
				$('#blog_container_deleteoptions_'+blogID).removeClass('displaynone');
				$('#delete_blog_yes_'+blogID).click(function(){validateNsubmitMultipleValues('blogdelete_'+blogID);});
				$('#delete_blog_no_'+blogID).click(function(){
					$('#blog_container_'+blogID).removeClass('displaynone');
					$('#blog_container_deleteoptions_'+blogID).addClass('displaynone');
				});
			});

			$('.blog_comments').click(function(){
				tagElementID = $(this).attr('id')
				tagElementID = tagElementID.split('_');
				postID = parseInt(tagElementID[2]);
				userID = parseInt($('#steve').html());
				albumID = '';

				comments_loadform_and_initialize_elements(albumID,userID,postID);
			});
			
			//section_contentpages_account_initialize_listeners('blog_bloglist');
			break;
		case 'blog_subnavigation':
			
			$('#blog_body').keydown(function(event)
				{countCharsJQuery('#blog_body','#blog_body_counter',albumDescriptionMaxLength,event);});
			
			$('#blog_submit').click(function(){validateNsubmitMultipleValues('blog');});

			$('span.blog_visibilitys').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				blogID = linkID[2];
				
				classNames = $('#blog_visibility_'+blogID).attr('class');
				classNames = classNames.split(' ');
				
				visibilityStatus = classNames[1];
				
				if(visibilityStatus == 'blogvisible'){validateNsubmitMultipleValues('blogvisibility_'+blogID+'_'+visibilityStatus);}
				else if(visibilityStatus == 'bloginvisible'){validateNsubmitMultipleValues('blogvisibility_'+blogID+'_'+visibilityStatus);}		
			});


			$('span.blog_imageviews').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				imageview = linkID[2];
				
				classNames = $('#blog_imageview_'+imageview).attr('class');
				classNames = classNames.split(' ');


				if(imageview == 'fullsize'){validateNsubmitMultipleValues('blog_imageview_'+imageview);}
				else if(imageview == 'thumbnails'){validateNsubmitMultipleValues('blog_imageview_'+imageview);}
				else if(imageview == 'largethumbnails'){validateNsubmitMultipleValues('blog_imageview_'+imageview);}
			});


			portfolio_images_upload_listeners_initialize();
			$('#blogimage_reset').click(function(){
				portfolio_images_upload_event_initialize('blog');
			});
			$('#blogimage_add').click(function(){												
				portfolio_images_upload_event_initialize('blog');
			});
			$('#blogimage_delete').click(function(){
				portfolio_images_delete_event_initialize('blog');				
			});

			var blogCategoryData = $('span.blogCategories').html().split("::"); $("#blog_category").autocomplete(blogCategoryData); 
			break;
		case 'designeditor':
			initialize_designeditor_form_elements();
			$('.templates').click(function(){
				obj = $(this);
				linkID = obj.attr('id');
				linkID = linkID.split('_');
				templateID = linkID[1];
				
				$('.templates').removeClass('selectedtag');
				$('#templates_'+templateID).addClass('selectedtag');
				
				$('#loader_layer6').show();
				$("#designeditor_settings_container").load("./rdincfiles/layout.inc.php"+"?requesting=12&templateid="+templateID, function(responseText, textStatus, XMLHttpRequest) {
					if(textStatus == 'error'){alert(XMLHttpRequest.status);} 
					else{$('#loader_layer6').hide(); initialize_designeditor_form_elements();}
				});
			});//click
			
			break;
		case 'instructions':

			break;
		default:
			break;
	}//sectionName
}//section_contentpages_account_initialize_listeners()


function initialize_designeditor_form_elements()
{
	$('#wallpapercolor').ColorPicker({
		color: '#'+$('#wallpapercolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#wallpapercolor').val(hex);
			$('#colordemo_wallpapercolor').attr('style','color:#'+$('#wallpapercolor').val()+'; background-color:#'+$('#wallpapercolor').val());
			$('#de_wallpapercolor').html(hex);
		}
	});
	$('#wallpapercolor').blur(function(){
		color_temp_01 = $('#wallpapercolor').val();
		$('#colordemo_wallpapercolor').attr('style','color:#'+color_temp_01+'; background-color:#'+color_temp_01);
		$('#de_wallpapercolor').html(color_temp_01);
	});
	
	
	
	$('#sectionsbackgroundcolor').ColorPicker({
		color: '#'+$('#sectionsbackgroundcolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#sectionsbackgroundcolor').val(hex);
			$('#colordemo_sectionsbackgroundcolor').attr('style','color:#'+$('#sectionsbackgroundcolor').val()+'; background-color:#'+$('#sectionsbackgroundcolor').val());
			$('#de_sectionsbg').html('trans_color_'+hex);
		}
	});
	$('#sectionsbackgroundcolor').blur(function(){
		color_temp_02 = $('#sectionsbackgroundcolor').val();
		$('#colordemo_sectionsbackgroundcolor').attr('style','color:#'+color_temp_02+'; background-color:#'+color_temp_02);
		
		tempVar = $('#de_sectionsbg').html();
		tempVar = tempVar.split('_');
		
		if( (tempVar[0]+'_'+tempVar[1]+'_') == 'trans_color_')
			{$('#de_sectionsbg').html('trans_color_'+color_temp_02);}
	});




	$('#textcolor').ColorPicker({
		color: '#'+$('#textcolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#textcolor').val(hex);
			$('#colordemo_textcolor').attr('style','color:#'+$('#textcolor').val()+'; background-color:#'+$('#textcolor').val());
			$('#de_texttextcolor').html(hex);
		}
	});
	$('#textcolor').blur(function(){
		color_temp_03 = $('#textcolor').val();
		$('#colordemo_textcolor').attr('style','color:#'+color_temp_03+'; background-color:#'+color_temp_03);
		$('#de_texttextcolor').html(color_temp_03);
	});





	$('#highlightedtextcolor').ColorPicker({
		color: '#'+$('#highlightedtextcolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#highlightedtextcolor').val(hex);
			$('#colordemo_highlightedtextcolor').attr('style','color:#'+$('#highlightedtextcolor').val()+'; background-color:#'+$('#highlightedtextcolor').val());
			$('#de_texthighlightcolor').html(hex);
		}
	});
	$('#highlightedtextcolor').blur(function(){
		color_temp_04 = $('#highlightedtextcolor').val();
		$('#colordemo_highlightedtextcolor').attr('style','color:#'+color_temp_04+'; background-color:#'+color_temp_04);
		$('#de_texthighlightcolor').html(color_temp_04);
	});




	$('#highlightedtextbackgroundcolor').ColorPicker({
		color: '#'+$('#highlightedtextbackgroundcolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#highlightedtextbackgroundcolor').val(hex);
			$('#colordemo_highlightedtextbackgroundcolor').attr('style','color:#'+$('#highlightedtextbackgroundcolor').val()+'; background-color:#'+$('#highlightedtextbackgroundcolor').val());
			$('#de_textbghighlightcolor').html(hex);
		}
	});
	$('#highlightedtextbackgroundcolor').blur(function(){
		color_temp_05 = $('#highlightedtextbackgroundcolor').val();
		$('#colordemo_highlightedtextbackgroundcolor').attr('style','color:#'+color_temp_05+'; background-color:#'+color_temp_05);
		$('#de_textbghighlightcolor').html(color_temp_05);
	});



	$('#linkscolor').ColorPicker({
		color: '#'+$('#linkscolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#linkscolor').val(hex);
			$('#colordemo_linkscolor').attr('style','color:#'+$('#linkscolor').val()+'; background-color:#'+$('#linkscolor').val());
			$('#de_textlinkcolor').html(hex);
		}
	});
	$('#linkscolor').blur(function(){
		color_temp_06 = $('#linkscolor').val();
		$('#colordemo_linkscolor').attr('style','color:#'+color_temp_06+'; background-color:#'+color_temp_06);
		$('#de_textlinkcolor').html(color_temp_06);
	});
	
	
	
	$('#linksbackgroundcolor').ColorPicker({
		color: '#'+$('#linksbackgroundcolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#linksbackgroundcolor').val(hex);
			$('#colordemo_linksbackgroundcolor').attr('style','color:#'+$('#linksbackgroundcolor').val()+'; background-color:#'+$('#linksbackgroundcolor').val());
			$('#de_linkbgcolor').html(hex);
		}
	});
	$('#linksbackgroundcolor').blur(function(){
		color_temp_07 = $('#linksbackgroundcolor').val();
		$('#colordemo_linksbackgroundcolor').attr('style','color:#'+color_temp_07+'; background-color:#'+color_temp_07);
		$('#de_linkbgcolor').html(color_temp_07);
	});



	$('#linkshovercolor').ColorPicker({
		color: '#'+$('#linkshovercolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#linkshovercolor').val(hex);
			$('#colordemo_linkshovercolor').attr('style','color:#'+$('#linkshovercolor').val()+'; background-color:#'+$('#linkshovercolor').val());
			$('#de_textlinkhovercolor').html(hex);
		}
	});
	$('#linkshovercolor').blur(function(){
		color_temp_08 = $('#linkshovercolor').val();
		$('#colordemo_linkshovercolor').attr('style','color:#'+color_temp_08+'; background-color:#'+color_temp_08);
		$('#de_textlinkhovercolor').html(color_temp_08);
	});



	$('#linkshoverbackgroundcolor').ColorPicker({
		color: '#'+$('#linkshoverbackgroundcolor').val(),
		onShow: function (colpkr) {$(colpkr).fadeIn(500); return false;},
		onHide: function (colpkr) {$(colpkr).fadeOut(500);return false;},
		onChange: function (hsb, hex, rgb) {
			$('#linkshoverbackgroundcolor').val(hex);
			$('#colordemo_linkshoverbackgroundcolor').attr('style','color:#'+$('#linkshoverbackgroundcolor').val()+'; background-color:#'+$('#linkshoverbackgroundcolor').val());
			$('#de_textlinkhoverbgcolor').html(hex);
		}
	});
	$('#linkshoverbackgroundcolor').blur(function(){
		color_temp_09 = $('#linkshoverbackgroundcolor').val();
		$('#colordemo_linkshoverbackgroundcolor').attr('style','color:#'+color_temp_09+'; background-color:#'+color_temp_09);
		$('#de_textlinkhoverbgcolor').html(color_temp_09);
	});

	
	$('.sidebarorientation').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_01 = linkID[1];
		
		$('.sidebarorientation').removeClass('selectedtag');
		$('#sidebarorientation_'+elementID_01).addClass('selectedtag');
		
		switch(elementID_01)
		{
			case 'sidebarleft':
				elementID_01 = 'left';
				break;
			case 'sidebarright':
				elementID_01 = 'right';
				break;
			case 'sidebartop':
				elementID_01 = 'top';
				break;
		}//elementID
		
		$('#de_sidebarorientation').html(elementID_01);
	});
	
	$('.wallpapertype').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_10 = linkID[1];

		$('.wallpapertype').removeClass('selectedtag');
		$('#wallpapertype_'+elementID_10).addClass('selectedtag');
		$('#de_wallpapertype').html(elementID_10);
		
		if(elementID_10 == 'image'){$('#wallpaper_li').show(); $('#wallpaperimage_uploadprogress_container').html('<div id="divFileProgressContainer"></div>');}
		else{$('#wallpaper_li').hide();}	
	});
	
	$('.logotype').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_02 = linkID[1];

		$('.logotype').removeClass('selectedtag');
		$('#logotype_'+elementID_02).addClass('selectedtag');
		$('#de_logotext').html(elementID_02);
		
		if(elementID_02 == 'image'){$('#logoimage_li').show();}
		else{$('#logoimage_li').hide();}
	});
	
	$('.sectionsbackground').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_03 = linkID[1];

		$('.sectionsbackground').removeClass('selectedtag');
		$('#sectionsbackground_'+elementID_03).addClass('selectedtag');
		
		switch(elementID_03)
		{
			case 'transparent100':
				elementID_03 = 'trans_100';
				//$('#sectionsbackgroundcolor').val('FFFFFF');
				$('#sectionsbackgroundcolor_li').hide();
				
				break;
			case 'transparentlightwhite':
				elementID_03 = 'trans_white_light';
				//$('#sectionsbackgroundcolor').val('FFFFFF');
				$('#sectionsbackgroundcolor_li').hide();
				
				break;
			case 'transparentheavywhite':
				elementID_03 = 'trans_white_heavy';
				//$('#sectionsbackgroundcolor').val('FFFFFF');
				$('#sectionsbackgroundcolor_li').hide();
				
				break;
			case 'transparentlightblack':
				elementID_03 = 'trans_black_light';
				//$('#sectionsbackgroundcolor').val('FFFFFF');
				$('#sectionsbackgroundcolor_li').hide();
				
				break;
			case 'transparentheavyblack':
				elementID_03 = 'trans_black_heavy';
				//$('#sectionsbackgroundcolor').val('FFFFFF');
				$('#sectionsbackgroundcolor_li').hide();
				
				break;
			case 'transparentcolor':
				elementID_03 = 'trans_color_'+$('#sectionsbackgroundcolor').val();
				$('#sectionsbackgroundcolor').val($('#sectionsbackgroundcolor').val());
				$('#colordemo_sectionsbackgroundcolor').attr('style','color:#'+$('#sectionsbackgroundcolor').val()+'; background-color:#'+$('#sectionsbackgroundcolor').val());
				$('#sectionsbackgroundcolor_li').show();
				
				break;
		}//switch()
		
		$('#de_sectionsbg').html(elementID_03);
		
	});

	$('.textfont').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_04 = linkID[1];

		$('.textfont').removeClass('selectedtag');
		$('#textfont_'+elementID_04).addClass('selectedtag');
		
		switch(elementID_04)
		{
			case 'couriernew':
				elementID_04 = '"Courier New", Courier, monospace';
				break;
			case 'helvetica':
				elementID_04 = 'Helvetica';
				break;
			case 'georgia':
				elementID_04 = 'Georgia, "Times New Roman", Times, serif';
				break;
			case 'verdana':
				elementID_04 = 'Verdana, Arial, Helvetica, sans-serif';
				break;
			case 'geneva':
				elementID_04 = 'Geneva, Arial, Helvetica, sans-serif';
				break;
		}//switch()
		
		$('#de_textfontfamily').html(elementID_04);		
		
	});
	
	$('.navigationlinkssize').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_05 = linkID[1];

		$('.navigationlinkssize').removeClass('selectedtag');
		$('#navigationlinkssize_'+elementID_05).addClass('selectedtag');
		
		$('#de_textnavilinksize').html(elementID_05);	
	});
	
	$('.addimagestagcloud').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_06 = linkID[1];

		$('.addimagestagcloud').removeClass('selectedtag');
		$('#addimagestagcloud_'+elementID_06).addClass('selectedtag');
		
		$('#de_imagetagcloud').html(elementID_06);
	});
	
	$('.addemail').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_07 = linkID[1];
		
		$('.addemail').removeClass('selectedtag');
		$('#addemail_'+elementID_07).addClass('selectedtag');
		
		if(elementID_07 == 'yes'){elementID_07 = 1;}
		else{elementID_07 = 0;}
		$('#de_sidebar_email').html(elementID_07);
	});

	$('.addupdated').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_08 = linkID[1];

		$('.addupdated').removeClass('selectedtag');
		$('#addupdated_'+elementID_08).addClass('selectedtag');
		
		if(elementID_08 == 'yes'){elementID_08 = 1;}
		else{elementID_08 = 0;}
		$('#de_sidebar_updated').html(elementID_08);
	});

	$('.addcopyright').click(function(){
		obj = $(this);
		linkID = obj.attr('id');
		linkID = linkID.split('_');
		elementID_09 = linkID[1];

		$('.addcopyright').removeClass('selectedtag');
		$('#addcopyright_'+elementID_09).addClass('selectedtag');
		
		if(elementID_09 == 'yes'){elementID_09 = 1;}
		else{elementID_09 = 0;}
		$('#de_sidebar_copyright').html(elementID_09);
	});
	
	$('#designeditor_submit').unbind();
	$('#designeditor_submit').click(function(){
		validateNsubmitMultipleValues('designeditor');
	});


}//initialize_designeditor_form_elements()

function portfolio_images_delete_event_initialize(typeID) // 'album' or 'blog'
{
	$('#'+typeID+'image_add').removeClass('selectedtag');
	$('#'+typeID+'image_reorder').removeClass('selectedtag');
	$('#'+typeID+'image_delete').addClass('selectedtag');
	
	$('#editimagesinstructions').addClass('displaynone');
	$('#'+typeID+'image_uploader_container').addClass('displaynone');
	$('#'+typeID+'image_delete_container').removeClass('displaynone');
	$('#'+typeID+'image_reorder_container').addClass('displaynone');
	
	$('#'+typeID+'images_deleteorder').html('');
	portfolio_images_upload_listeners_remove();
	portfolio_images_delete_listeners_remove();
	
	if(typeID == 'album'){portfolio_images_reorder_listeners_remove();}
	
	portfolio_images_delete_listeners_initialize(typeID);

	$('#'+typeID+'image_delete_submit').click(function(){
		if($('#'+typeID+'images_deleteorder').html() != ''){validateNsubmitMultipleValues(typeID+'imagedelete');}
		else{alert('Please select at least one image to delete');}
	});	
}//portfolio_images_delete_event_initialize(typeID)


function portfolio_images_upload_event_initialize(typeID)
{
	$('#'+typeID+'image_add').addClass('selectedtag');
	$('#'+typeID+'image_reorder').removeClass('selectedtag');
	$('#'+typeID+'image_delete').removeClass('selectedtag');
	
	$('#editimagesinstructions').removeClass('displaynone');
	$('#'+typeID+'image_uploader_container').removeClass('displaynone');
	$('#'+typeID+'image_delete_container').addClass('displaynone');
	$('#'+typeID+'image_reorder_container').addClass('displaynone');
	
	portfolio_images_upload_listeners_remove();
	portfolio_images_delete_listeners_remove();
	if(typeID == 'album'){portfolio_images_reorder_listeners_remove();}
	
	portfolio_images_upload_listeners_initialize();
}//portfolio_images_upload_event_initialize(typeID)



function portfolio_images_reorder_event_initialize()
{
	$('#albumimage_add').removeClass('selectedtag');
	$('#albumimage_reorder').addClass('selectedtag');
	$('#albumimage_delete').removeClass('selectedtag');
	
	$('#editimagesinstructions').addClass('displaynone');
	$('#albumimage_uploader_container').addClass('displaynone');
	$('#albumimage_delete_container').addClass('displaynone');
	$('#albumimage_reorder_container').removeClass('displaynone');
	
	portfolio_images_upload_listeners_remove();
	portfolio_images_delete_listeners_remove();
	portfolio_images_reorder_listeners_remove();
	
	portfolio_images_reorder_listeners_initialize();
}

function portfolio_images_delete_listeners_remove()
{
	$('img.pi_thumbs').removeClass('selectedtodelete');
	$('#albumimages_deleteorder').html("");
	$('#albumimage_delete_counter').html('0');
	$('img.pi_thumbs').unbind();
	$('#albumimage_delete_submit').unbind();
}

function portfolio_images_delete_listeners_initialize(typeID)
{
	$('img.pi_thumbs').click(function(){
		deleteImagesNumber = $('#'+typeID+'image_delete_counter').html();
		deleteImagesNumber = parseInt(deleteImagesNumber);
		deleteImagesOrder = $('#'+typeID+'images_deleteorder').html();					   
		deleteImagesOrderNew = '';
		
		obj = $(this);
		imageID = obj.attr('id');
		imageID = imageID.split('_');
		imageID = imageID[1];
		elementID = '#portfolioimage_'+imageID;
		
		className = $(elementID).attr("class");
		className = className.split(' ');
		
		if(className[1] == 'selectedtodelete')
		{
			$(elementID).removeClass('selectedtodelete');
			$('#'+typeID+'image_delete_counter').html(deleteImagesNumber-1);
			$('#'+typeID+'image_delete_last_counter').html(deleteImagesNumber-1);
			
				tempImagesDeleteArr = deleteImagesOrder.split(':..::..:');
				for(k=0; k<tempImagesDeleteArr.length; k++)
				{
					if(imageID == tempImagesDeleteArr[k]) //remove this id from the array
						{tempImagesDeleteArr[k] = '';}
				}//for
				for(k=0; k<tempImagesDeleteArr.length; k++)
				{
					if(tempImagesDeleteArr[k] == ''){continue;}
					deleteImagesOrderNew = deleteImagesOrderNew + tempImagesDeleteArr[k] + ':..::..:';
				}//for
				$('#'+typeID+'images_deleteorder').html(deleteImagesOrderNew);
		}
		else
		{
			$(elementID).addClass('selectedtodelete');
			
			 $('#'+typeID+'image_delete_counter').html(deleteImagesNumber+1);
			 //$('#albumimage_delete_last_counter').html(deleteImagesNumber+1);
			 
			 deleteImagesOrderNew = deleteImagesOrder + imageID + ':..::..:';
			 $('#'+typeID+'images_deleteorder').html(deleteImagesOrderNew);
		}
	});
}//portfolio_images_delete_listeners_initialize()

function portfolio_images_reorder_listeners_initialize()
{
	var setSelector = "#thumbnails";
	
	$(setSelector).sortable({
		axis: "",
		cursor: "move",
		update: function() {
			var sort_order = '';
			$('#thumbnails li').each(function(index) { sort_order = sort_order +  $(this).attr('rel')  + ':..::..:';});
			$('#albumimages_order').html(sort_order);
			validateNsubmitMultipleValues('albumimagereorder');
		}
	});
	
	$('img.pi_thumbs').addClass('selectedtoreorder');
}//portfolio_images_reorder_listeners_initialize()


function portfolio_images_reorder_listeners_remove()
{
	var setSelector = "#thumbnails";
	$(setSelector).bind( "sortremove", function(event, ui) {});
	$('img.pi_thumbs').removeClass('selectedtoreorder');
	$('#albumimages_order').html('');
}//portfolio_images_reorder_listeners_remove()


function section_contentpages_account_defaultopen()
{
	sectionName = 'overview';
	section_open($('#link_'+sectionName),'account');
}//

function window_open_new(fileURL)
{
	window.open(fileURL ,"_blank","fullscreen=no,status=no,toolbar=no,menubar=no,resizable=yes,scrollbars=yes");
}//window_open_new(fileURL)


function account_album_subpage_load_sections(requestKeyword,albumID,albumUpdated)
{
	switch(requestKeyword)
	{
		case 'album_list_edit':
			$('#loader_layer2').show();
			$("#account_albums_navigation_container").load("./rdincfiles/album.inc.php"+"?requesting=1", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{ $('#loader_layer2').hide(); section_contentpages_account_initialize_listeners('albums');}
			});						
			break;
		case 'album_list_view':
			$('#loader_layer3').show();
			$("#section_content_subpage_albums").load("./rdincfiles/album.inc.php"+"?requesting=2", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{$('#loader_layer3').hide(); section_contentpages_account_initialize_listeners('album_demosubpage_albumlist');}
			});
			break;
		case 'album_page_edit':
			$('#loader_layer2').show();
			$("#account_albums_navigation_container").load("./rdincfiles/layout.inc.php"+"?requesting=3&albumid="+albumID+"&albumupdated="+albumUpdated, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);} 
				else{ $('#loader_layer2').hide(); section_contentpages_account_initialize_listeners('album_subnavigation'); }
			});
			break;
		case 'album_page_view':
			$('#loader_layer3').show();
			$("#section_content_subpage_albums").load("./rdincfiles/album.inc.php"+"?requesting=3&albumid="+albumID, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{$('#loader_layer3').hide(); section_contentpages_account_initialize_listeners('album_demosubpage_albumview'); portfolio_images_JDbox_initialize();}
			});
			break;
	}//switch
}//account_album_subpage_load_sections(requestKeyword,albumID,albumUpdated)


function account_blog_subpage_load_sections(requestKeyword,blogID,blogUpdated)
{
	switch(requestKeyword)
	{
		case 'blog_page_edit':
			$('#loader_layer3').show();
	
			if(blogUpdated != ""){blogUpdated = "&blogupdated="+blogUpdated;}
			else{blogUpdated = "";}
			
			$("#section_content_subpage_blog").load("./rdincfiles/layout.inc.php"+"?requesting=7&postid="+blogID+blogUpdated, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);} 
				else{ $('#loader_layer3').hide(); section_contentpages_account_initialize_listeners('blog_subnavigation'); }
			});
		break;
		case 'blog_list_edit':
			$('#loader_layer2').show();
			$("#account_blog_navigation_container").load("./rdincfiles/blog.inc.php"+"?requesting=1", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{ $('#loader_layer2').hide(); section_contentpages_account_initialize_listeners('blog');}
			});						
		break;
	}//
}//account_blog_subpage_load_sections(requestKeyword,blogID,blogUpdated)



function portfolio_images_upload_listeners_initialize()
{
	$('img.pi_thumbs').click(function(){
		obj = $(this);
		imageID = obj.attr('id');
		imageID = imageID.split('_');
		imageID = imageID[1];
		imageSrc = $('#portfolioimage_'+imageID).attr('src');
		
		imageSrc = imageSrc.split('thumbnails');
		imageSrc = imageSrc[0]+'fullresolution'+imageSrc[1];
		
		JDbox_initialize(imageID,imageSrc,'edit');
		//window_open_new(imageSrc);
	});
}//	portfolio_images_upload_listeners_initialize();

function portfolio_images_upload_listeners_remove()
{
	$('img.pi_thumbs').unbind();
}//portfolio_images_upload_listeners_remove()

function portfolio_images_JDbox_initialize()
{
	$('img.pi_thumbs_view').click(function(){
		obj = $(this);
		imageID = obj.attr('id');
		imageID = imageID.split('_');
		imageID = imageID[1];
		imageSrc = $('#portfolioimage_'+imageID).attr('src');

		switch($('#viewalbumimagespresentationtype').html())
		{
			case 'largethumbnails':
				//this check is not a mistake. The page that contains these thumbnails, also contains a demo listing of the images
				//were they have the same HTML DOM ids and class names.
				if(imageSrc == imageSrc.split('largethumbnails')){imageSrc = imageSrc.split('thumbnails');}
				else{imageSrc = imageSrc.split('largethumbnails');}
				break;
			case 'thumbnails':
				imageSrc = imageSrc.split('thumbnails');
				break;
			case 'fullsize':
				imageSrc = imageSrc.split('thumbnails');
				break;
			default:
				break;
		}//switch
		imageSrc = imageSrc[0]+'fullresolution'+imageSrc[1];				
		JDbox_initialize(imageID,imageSrc,'view');
	});
}//portfolio_images_JDbox_initialize()

function JDbox_initialize(imageID_jdbox,imageSrc,presentationMode)
{
/*
here reset the search and suggest for the image tags
*/
		$('#display_albumembeddedvideos').hide();

		albumPortfolioImagesOrder = $('.albumportfolioimagesorder').html();
		albumPortfolioImagesOrder = albumPortfolioImagesOrder.split(',');
		currentGalleryImagesNumber = albumPortfolioImagesOrder.length-1;

		userID = $('#portfolioimageusername_'+imageID_jdbox).html();
		albumID = $('#portfolioimagealbum_'+imageID_jdbox).html();

		imageOrientation = $('#portfolioimageorientation_'+imageID_jdbox).html();
		imageCaption = $('#portfolioimagecaption_'+imageID_jdbox).html();
		imageUploadTimestamp = $('#portfolioimageuploaded_'+imageID_jdbox).html();
		imageViews = parseInt($('#portfolioimageviews_'+imageID_jdbox).html()); validateNsubmitMultipleValues('imageupdateviews_'+imageID_jdbox+"_"+imageViews); imageViews++;		
		imageTags = $('#portfolioimagetags_'+imageID_jdbox).html();
		
		for(i=0; i<currentGalleryImagesNumber; i++)
			{if(albumPortfolioImagesOrder[i]==imageID_jdbox){ currentGalleryImagePointer = i+1;}}//for

		switch(presentationMode)
		{
			case 'edit':
				JDboxContent = '';
				JDboxContent += "<div id='jdbox_image_container'>";
					JDboxContent += "<img id='jdbox_image_img' src='"+imageSrc+"' class='orientation_"+imageOrientation+"' />";
					JDboxContent += "<div id='jdbox_image_newwindow'>Open image in new window</div>";
					JDboxContent += "<div id='jdbox_image_prev' class=''>&lt;prev</div>";
					JDboxContent += "<div id='jdbox_image_next' class=''>next&gt;</div>";
						
					JDboxContent += "<div id='imagetags_frm'><span id='imagetags_instructions'>seperate tags with a comma (,)</span><span class='messages' id='imagetags_frm_messages'></span><div class='clear'></div><label for='imagetags_tags'>Image Tags:</label><input type='text' id='imagetags_tags' class='text6' value='"+imageTags+"' maxlength='120' /><div class='clear'></div><span class='submit' id='imagetags_submit'>submit</span></div>";
					JDboxContent += "<div id='imagecaption_frm'><span class='messages' id='imagecaption_frm_messages'></span><div class='clear'></div><label for='imagecaption_caption'>Image Caption:</label><input type='text' id='imagecaption_caption' class='text6' value='"+imageCaption+"' maxlength='250' /><div class='clear'></div><span class='submit' id='imagecaption_submit'>submit</span></div>";
				
					JDboxContent += "<div id='jdbox_image_views'>("+imageViews+" views)</div>";
					JDboxContent += "<div id='jdbox_image_uploadedtimestamp'>Uploaded on <b>"+imageUploadTimestamp+"</b></div>";
					JDboxContent += "<div id='jdbox_image_close' class=''>close</div>";
				JDboxContent += "</div>";
				break;
			case 'view':
				JDboxContent = '';
				JDboxContent += "<div id='jdbox_image_container'>";
					JDboxContent += "<img id='jdbox_image_img' src='"+imageSrc+"' class='orientation_"+imageOrientation+"' />";
					JDboxContent += "<div id='jdbox_image_newwindow'>Open image in new window</div>";
					JDboxContent += "<div id='jdbox_image_prev' class=''>&lt;prev</div>";
					JDboxContent += "<div id='jdbox_image_next' class=''>next&gt;</div>";
					
					if(imageTags != '')
						{JDboxContent += "<div id='jdbox_image_tags'>Filed under: <b>"+imageTags+"</b></div>";}
					
					if(imageCaption != '')
						{JDboxContent += "<div id='jdbox_image_caption'>"+imageCaption+"</div>";}
					JDboxContent += "<div id='jdbox_image_views'>("+imageViews+" views)</div>";
					JDboxContent += "<div id='jdbox_image_uploadedtimestamp'>Uploaded on <b>"+imageUploadTimestamp+"</b></div>";
					JDboxContent += "<div id='jdbox_image_close' class=''>close</div>";
					//JDboxContent += '<a id="facebooksharebutton" rel="nofollow" href="http://www.facebook.com/share.php?u='+imageSrc+'" onclick="return fbs_click()" target="_blank" class="fb_share_link">Share on Facebook</a>';
				JDboxContent += "</div>";
				break;
		}//switch

	
	$('#layer_placeholder').html(JDboxContent);
	
	if(imageOrientation == 'vertical'){
		$('#jdbox_image_img').addClass('verticalimg');
	}
	else if(imageOrientation == 'horizontal'){
		$('#jdbox_image_img').addClass('horizontalimg');
	}
	
	$('#loader_layer4').fadeTo(500,1);
	$('#layer_placeholder').fadeTo(500,1);
	
	$('#jdbox_image_close').click(function(){
		$('#loader_layer4').hide();
		$('#layer_placeholder').hide();
		$(document).unbind();
		$('#jdbox_image_container').remove();

		$('#display_albumembeddedvideos').show();
	});
	
	$('#jdbox_image_newwindow').click(function(){
		window_open_new(imageSrc);
	});

	if(currentGalleryImagesNumber != 1)
	{
		$('#jdbox_image_prev').click(function(){
			$(document).unbind();
			$('#jdbox_image_container').remove();
			JDbox_navigate_images(imageID_jdbox,presentationMode,'previous');
		});
		
		$('#jdbox_image_next').click(function(){
			$(document).unbind();
			$('#jdbox_image_container').remove();
			JDbox_navigate_images(imageID_jdbox,presentationMode,'next');
		});
	}//

	if(presentationMode == 'edit')
	{
		$('#imagecaption_submit').click(function(){validateNsubmitMultipleValues('imagecaption_'+imageID_jdbox);});
		$('#imagecaption_caption').keydown(function(event){if (event.which == 13) {validateNsubmitMultipleValues('imagecaption_'+imageID_jdbox); }});
		
		$('#imagetags_submit').click(function(){validateNsubmitMultipleValues('imagetags_'+imageID_jdbox);});
		$('#imagetags_tags').keydown(function(event){if (event.which == 13) {validateNsubmitMultipleValues('imagetags_'+imageID_jdbox); }});
		
		var imageTagData = $('div.imagesalltagnames').html().split(","); $("#imagetags_tags").autocomplete(imageTagData); 
	}//

	$(document).keypress(function(e) {
	  switch(e.keyCode) { 
		case (37):
			$(document).unbind();
			if(currentGalleryImagesNumber != 1){
				$('#jdbox_image_container').remove();
				JDbox_navigate_images(imageID_jdbox,presentationMode,'previous');
				return false;
			}//if
		break;
		case (39):
			$(document).unbind();
			if(currentGalleryImagesNumber != 1){
				$('#jdbox_image_container').remove();
				JDbox_navigate_images(imageID_jdbox,presentationMode,'next');
				return false;
			}//if
		break;
		case (27):
			$('#loader_layer4').hide();
			$('#layer_placeholder').hide();
			$(document).unbind();
			$('#jdbox_image_container').remove();
			
			$('#display_albumembeddedvideos').show();
			return false;
		break;
		default: break;
	  }//
	});
	
	
}//JDbox_initialize(imageID,imageSrc,presentationMode)

function JDbox_navigate_images(imageID,presentationMode,navigationDirection)
{
	albumPortfolioImagesOrder = $('.albumportfolioimagesorder').html();
	albumPortfolioImagesOrder = albumPortfolioImagesOrder.split(',');
	currentGalleryImagesNumber = albumPortfolioImagesOrder.length-1;
																		   
	
	/*
	imageSrc = $('#portfolioimage_'+imageID).attr('src');
	imageSrc = imageSrc.split('thumbnails');
	imageSrc = imageSrc[0]+'fullresolution'+imageSrc[1];
	*/

	
	for(i=0; i<currentGalleryImagesNumber; i++)
	{
		if(albumPortfolioImagesOrder[i]==imageID)
		{
			//found!!!
			if(i == 0)
			{
				previousImagePointer = currentGalleryImagesNumber-1; //the last image			
				nextImagePointer = i+1;
			}//
			else if(i == currentGalleryImagesNumber-1)//the last image
			{
				previousImagePointer = i-1;
				nextImagePointer = 0;
			}//
			else
			{
				previousImagePointer = i-1;
				nextImagePointer = i+1;
			}//
		}//

	}//for
	
	previousImageID = albumPortfolioImagesOrder[previousImagePointer];
	nextImageID = albumPortfolioImagesOrder[nextImagePointer];
	if(navigationDirection == 'next')
	{
		newImagePointer = nextImagePointer;
		newImageID = nextImageID;
	}
	if(navigationDirection == 'previous')
	{
		newImagePointer = previousImagePointer;
		newImageID = previousImageID;
	}
	
	
	newImageSrc = $('#portfolioimage_'+newImageID).attr('src');
	switch($('#viewalbumimagespresentationtype').html())
	{
		case 'largethumbnails':
			if(newImageSrc == newImageSrc.split('largethumbnails')){newImageSrc = newImageSrc.split('thumbnails');}
			else{newImageSrc = newImageSrc.split('largethumbnails');}
			break;
		case 'thumbnails':
			newImageSrc = newImageSrc.split('thumbnails');
			break;
		case 'fullsize':
			newImageSrc = newImageSrc.split('thumbnails');
			break;
		default:
			newImageSrc = newImageSrc.split('thumbnails');
			break;
	}//switch
	
	newImageSrc = newImageSrc[0]+'fullresolution'+newImageSrc[1];
	JDbox_initialize(newImageID,newImageSrc,presentationMode);
	
}//JDbox_navigate_images(imageID,presentationMode,navigationDirection)




function mainsite_contact_initialize()
{
	$('#contact_name').click(function(){
		if($('#contact_name').val() != '(type your name)(required)'){}
		else{$('#contact_name').val('');}
	
	});

	$('#contact_email').click(function(){
		if($('#contact_email').val() != '(type your email)(required)'){}
		else{$('#contact_email').val('');}
	});

	$('#contact_regarding').click(function(){
		if($('#contact_regarding').val() != '(regarding)(not required)'){}
		else{$('#contact_regarding').val('');}
	});

	$('#contact_message').click(function(){
		if($('#contact_message').val() != '(type your message)(required)'){}
		else{$('#contact_message').val('');}
	});
	
	$('#contact_message').keydown(function(event)
		{countCharsJQuery('#contact_message','#scounter',contactMessageMaxLength,event);});
			
	$('#contact_submit').click(function(){	
		validateNsubmitMultipleValues('contact');
	});

	$('#contact_name').blur(function(){
		if($('#contact_name').val() == ''){$('#contact_name').val('(type your name)(required)');}
	});
	$('#contact_email').blur(function(){
		if($('#contact_email').val() == ''){$('#contact_email').val('(type your email)(required)');}
	});
	$('#contact_regarding').blur(function(){
		if($('#contact_regarding').val() == ''){$('#contact_regarding').val('(regarding)(not required)');}
	});
	$('#contact_message').blur(function(){
		if($('#contact_message').val() == ''){$('#contact_message').val('(type your message)(required)');}
	});

	
}//mainsite_contact_initialize()



function comments_loadform_and_initialize_elements(albumID,userID,postID)
{
	if(albumID != ''){commentfor = 'album';}
	else if(postID != ''){commentfor = 'post';}
	$("#layer_placeholder").load("./rdincfiles/comment.inc.php"+"?requesting=1&uid="+userID+"&aid="+albumID+"&pid="+postID+"&commentfor="+commentfor, function(responseText, textStatus, XMLHttpRequest) {
		if(textStatus == 'error'){alert(XMLHttpRequest.status);}
		else{
			$('#layer_placeholder').fadeTo(500,1);
			$('#loader_layer4').fadeTo(500,1);
			
			portfolio_comment_initialize();
			
			$(document).keypress(function(e) {
				  switch(e.keyCode) {
					case (27):
						$('#loader_layer4').hide();
						$('#layer_placeholder').hide();
						$(document).unbind();
						$('#layer_placeholder').html('');
						return false;
					break;
					default: break;
				  }//
			});
			$('#comment_close').click(function(){
				$('#layer_placeholder').fadeTo(500,0);
				$('#loader_layer4').fadeTo(500,0);
				$('#layer_placeholder').hide();
				$('#loader_layer4').hide();	
				$('#layer_placeholder').html('');
			});
		}//
	});
}//comments_loadform_and_initialize_elements(albumID,userID,postID)

function portfolio_comment_initialize()
{
	$('#comment_name').click(function(){
		if($('#comment_name').val() != '(your name)(required)'){}
		else{$('#comment_name').val('');}
	});

	$('#comment_email').click(function(){
		if($('#comment_email').val() != '(your email)(required)'){}
		else{$('#comment_email').val('');}
	});
	
	$('#comment_reply').click(function(){
		if($('#comment_reply').val() != '(your comment)(required)'){}
		else{$('#comment_reply').val('');}
	});
	$('#comment_reply').keydown(function(event)
		{countCharsJQuery('#comment_reply','#ccounter',commentMessageMaxLength,event);});
			
	$('#comment_submit').click(function(){
		validateNsubmitMultipleValues('comment');
	});

	$('#comment_name').blur(function(){
		if($('#comment_name').val() == ''){$('#comment_name').val('(your name)(required)');}
	});
	$('#comment_email').blur(function(){
		if($('#comment_email').val() == ''){$('#comment_email').val('(your email)(required)');}
	});
	$('#comment_reply').blur(function(){
		if($('#comment_reply').val() == ''){$('#comment_reply').val('(your comment)(required)');}
	});
}//portfolio_comment_initialize()