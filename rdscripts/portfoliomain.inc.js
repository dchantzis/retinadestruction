// JavaScript Document

var contactMessageMaxLength = 2500;
var commentMessageMaxLength = 1500;

$(document).ready(function() {
	var containerPageName = $("#eddie").html();
	
	initializeCategoriesTogglers();
});

function initializeCategoriesTogglers()
{
	$('.navicat_sections').hide();
	
	$('.navicat_names').click(function(){
		sectionID = $(this).attr('id')
		sectionID = sectionID.split('_');
		sectionName = sectionID[2];

		if($('#navicat_section_'+sectionName).is(':visible')){$('#navicat_section_'+sectionName).slideUp();}
		else{$('#navicat_section_'+sectionName).slideToggle(500);}
	});

	$('.navialbum_names').click(function(){
		albumElementID = $(this).attr('id')
		albumElementID = albumElementID.split('_');
		tagID = '';
		albumID = parseInt(albumElementID[2]);
		userID = parseInt($('#steve').html());
		username = $('#brandon').html();
		other = '';
		
		portfolio_content_load('portfolio_album',albumID,userID,tagID,other);
	});
	
	$('.portfolio_logo').click(function(){
		username = $('#brandon').html();
		window.location.replace("./portfolioindex.php?"+username);
	});
	
	if($('#portfolio_tagcloud').length!=0)
	{
		$('.portfolio_tags').click(function(){
			tagElementID = $(this).attr('id')
			tagElementID = tagElementID.split('_');
			albumID = '';
			tagID = parseInt(tagElementID[2]);
			userID = parseInt($('#steve').html());
			other = '';
			
			portfolio_content_load('portfolio_image_tag',albumID,userID,tagID,other);
		});
	}//if
	
	$('#navi_albums').click(function(){
		tagID = '';
		albumID = '';
		userID = parseInt($('#steve').html());
		username = $('#brandon').html();

		portfolio_content_load('portfolio_albums_list',albumID,userID,tagID,username);
	});
	
	$('#navi_blog').click(function(){
		tagID = '';
		albumID = '';
		userID = parseInt($('#steve').html());
		username = $('#brandon').html();
		other = '';
		
		portfolio_content_load('portfolio_blog',albumID,userID,tagID,other);
	});
	
	$('#navi_info').click(function(){
		tagID = '';
		albumID = '';
		userID = parseInt($('#steve').html());
		username = '';
		other = '';
		
		portfolio_content_load('portfolio_info',albumID,userID,tagID,other);
	});

	$('#navi_contact').click(function(){
		tagID = '';
		albumID = '';
		userID = parseInt($('#steve').html());
		username = '';
		postID = '';
		
		portfolio_content_load('portfolio_contact',albumID,userID,tagID,postID);
	});

}//function


function portfolio_content_load(requestKeyword,albumID,userID,tagID,other)
{
	window.scrollTo(0,0);
	switch(requestKeyword)
	{
		case 'portfolio_album':
			$('#loader_layer5').show();
			$("#maincontent").fadeTo(0,0.5);
			$("#maincontent").load("./rdincfiles/album.inc.php"+"?requesting=4&albumid="+albumID+"&userid="+userID, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
						$('#loader_layer5').hide();
						$("#maincontent").fadeTo(0,1);
						if($('#viewalbumimagespresentationtype').html() == 'fullsize')
						{
							$('#portfolio_album_images_get_more').unbind();
							$('.portfolio_tags').unbind();
							$('.display_albumcomments').unbind();
							
							$('#portfolio_album_images_get_more').click(function(){
								$('.pimages').removeClass('displaynone');
								$('#portfolio_album_images_get_more').remove();
							});
							
							$('.portfolio_tags').click(function(){
								tagElementID = $(this).attr('id')
								tagElementID = tagElementID.split('_');
								albumID = '';
								tagID = parseInt(tagElementID[2]);
								userID = parseInt($('#steve').html());
								other = '';
								
								portfolio_content_load('portfolio_image_tag',albumID,userID,tagID,other);		
							});
						}//
						else
						{
							portfolio_images_JDbox_initialize(0);
						}//
						
						$('.display_albumcomments').click(function(){
							
							tagElementID = $(this).attr('id')
							tagElementID = tagElementID.split('_');
							albumID = parseInt(tagElementID[2]);
							tagID = '';
							userID = parseInt($('#steve').html());
							postID = '';
							portfolio_content_load('portfolio_comments',albumID,userID,tagID,postID);
						});
							
				}//portfolio_images_JDbox_initialize();}
			});					
			break;
		case 'portfolio_image_tag':
			$('#loader_layer5').show();
			$("#maincontent").fadeTo(0,0.5);
			$("#maincontent").load("./rdincfiles/image.inc.php"+"?requesting=4&uid="+userID+"&tid="+tagID, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
					$('#loader_layer5').hide();
					$("#maincontent").fadeTo(0,1);
					portfolio_images_JDbox_initialize(0);
					
					$('.portfolio_tags').unbind();
					$('.portfolio_tags').click(function(){
						tagElementID = $(this).attr('id')
						tagElementID = tagElementID.split('_');
						albumID = '';
						tagID = parseInt(tagElementID[2]);
						userID = parseInt($('#steve').html());
						
						portfolio_content_load('portfolio_image_tag',albumID,userID,tagID,'');		
					});
					return 1;
				}//portfolio_images_JDbox_initialize();}
			});				
			break;
		case 'portfolio_blog':
			$('#loader_layer5').show();
			$("#maincontent").fadeTo(0,0.5);
			$("#maincontent").load("./rdincfiles/blog.inc.php"+"?requesting=2&uid="+userID+"&l=0&cp=1&blognavidir=latest", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
					$('#loader_layer5').hide();
					$("#maincontent").fadeTo(0,1);
					
					blog_initialize_elements();

				}//portfolio_images_JDbox_initialize();}
			});
			break;
		case 'portfolio_blog_tag':
			$('#loader_layer5').show();
			$("#maincontent").fadeTo(0,0.5);
			$("#maincontent").load("./rdincfiles/blog.inc.php"+"?requesting=2&uid="+userID+"&l=0&cp=1&blognavidir=latest&tid="+tagID+"&tname="+other, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
					$('#loader_layer5').hide();
					$("#maincontent").fadeTo(0,1);
					blog_initialize_elements();
					return 1;
				}//portfolio_images_JDbox_initialize();}
			});	
			break;
		case 'portfolio_blog_navigate':
			$('#loader_layer5').show();
			$("#maincontent").fadeTo(0,0.5);
			$("#maincontent").load("./rdincfiles/blog.inc.php"+"?requesting=2&uid="+userID+other+"&blognavidir=latest", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
					$('#loader_layer5').hide();
					$("#maincontent").fadeTo(0,1);
					blog_initialize_elements();

				}//portfolio_images_JDbox_initialize();}
			});
			break;
		case 'portfolio_info':
			$('#loader_layer5').show();
			$("#maincontent").fadeTo(0,0.5);
			$("#maincontent").load("./rdincfiles/user.inc.php"+"?requesting=1&uid="+userID, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
					$('#loader_layer5').hide();
					$("#maincontent").fadeTo(0,1);
				}//
			});	
			break;
		case 'portfolio_contact':
			$('#loader_layer5').show();
			$("#maincontent").fadeTo(0,0.5);
			if(other == 'emailsent'){parameter = '&emailsent=1';}else{parameter='';}
			$("#maincontent").load("./rdincfiles/layout.inc.php"+"?requesting=8&uid="+userID+parameter, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
					$('#loader_layer5').hide();
					$("#maincontent").fadeTo(0,1);
					portfolio_contact_initialize();
				}//
			});	
			break;
		case 'portfolio_comments':
			postID = other;
			comments_loadform_and_initialize_elements(albumID,userID,postID);
			break;
		case 'portfolio_albums_list':
			$('#loader_layer5').show();
			$("#maincontent").fadeTo(0,0.5);
			$("#maincontent").load("./rdincfiles/album.inc.php"+"?requesting=5&uid="+userID+"&username="+other, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{
					$('#loader_layer5').hide();
					$("#maincontent").fadeTo(0,1);
					
					$('.view_names').click(function(){
						albumElementID = $(this).attr('id')
						albumElementID = albumElementID.split('_');
						tagID = '';
						albumID = parseInt(albumElementID[2]);
						userID = parseInt($('#steve').html());
						username = $('#brandon').html();
						other = '';
						
						portfolio_content_load('portfolio_album',albumID,userID,tagID,other);
					});
					
				}//
			});
			break;
		default:
			//do nothing
			break;
	}//switch
}// portfolio_content_load(requestKeyword,albumID,userID,tagID,other)

function blog_initialize_elements()
{
	$('.navi_blog_navigate').unbind();
	$('.portfolio_tags').unbind();
	$('.postentry_tags').unbind();
	$('.blog_entry_comments').unbind();
	$('#portfolio_album_images_get_more').unbind();
	
	
	$('.navi_blog_navigate').click(function(){
		tagID = '';
		albumID = '';
		userID = parseInt($('#steve').html());
		username = $('#brandon').html();
		other = $(this).attr('rel');
		
		portfolio_content_load('portfolio_blog_navigate',albumID,userID,tagID,other);
	});
	
	$('.portfolio_tags').click(function(){
		tagElementID = $(this).attr('id')
		tagElementID = tagElementID.split('_');
		albumID = '';
		tagID = parseInt(tagElementID[2]);
		userID = parseInt($('#steve').html());
		
		portfolio_content_load('portfolio_image_tag',albumID,userID,tagID,'');		
	});
	
	
	$('.postentry_tags').click(function(){
		tagElementID = $(this).attr('id')
		tagElementName = $(this).html();
		tagElementID = tagElementID.split('_');
		albumID = '';
		tagID = parseInt(tagElementID[2]);
		userID = parseInt($('#steve').html());
		portfolio_content_load('portfolio_blog_tag',albumID,userID,tagID,tagElementName);		
	});
	
	$('.blog_entry_comments').click(function(){
		tagElementID = $(this).attr('id')
		tagElementID = tagElementID.split('_');
		postID = parseInt(tagElementID[3]);
		tagID = '';
		userID = parseInt($('#steve').html());
		albumID = '';
		portfolio_content_load('portfolio_comments',albumID,userID,tagID,postID);
	});
	
	$('#portfolio_album_images_get_more').click(function(){
		$('.pimages').removeClass('displaynone');
		$('#portfolio_album_images_get_more').remove();
	});
	$entriesOnDisplayID = $('#entriesOnDisplayID').html();$entriesOnDisplayID = $entriesOnDisplayID.split('::::');
	for($i=0; $i<(($entriesOnDisplayID.length)-1); $i++){portfolio_images_JDbox_initialize($entriesOnDisplayID[$i]);}

}//blog_initialize_elements()

function comments_loadform_and_initialize_elements(albumID,userID,postID)
{
	$('.blog_entry_embeddedvideos').hide();
	$('.display_albumembeddedvideos').hide();
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
						$('.blog_entry_embeddedvideos').show();
						$('.display_albumembeddedvideos').show();
						$('#loader_layer4').hide();
						$('#layer_placeholder').hide();
						$(document).unbind();
						$('#layer_placeholder').html('');
						
						return false;
					break;
					default: break;
				  }//
			});
			$('#comment_close').unbind();
			$('#comment_close').click(function(){
					  
				$('#layer_placeholder').fadeTo(500,0);
				$('#loader_layer4').fadeTo(500,0);
				$('.blog_entry_embeddedvideos').show();
				$('.display_albumembeddedvideos').show();
				$('#layer_placeholder').hide();
				$('#loader_layer4').hide();	
				$('#layer_placeholder').html('');
			});
		}//
	});
}//comments_loadform_and_initialize_elements(albumID,userID,postID)


function portfolio_comment_initialize()
{
	$('#comment_name').unbind();
	$('#comment_email').unbind();
	$('#comment_reply').unbind();
	$('#comment_submit').unbind();

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

function portfolio_contact_initialize()
{
	$('#contact_name').unbind();
	$('#contact_email').unbind();
	$('#contact_regarding').unbind();
	$('#contact_message').unbind();
	$('#contact_submit').unbind();
	
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
			
	$('#contact_submit').click(function(){validateNsubmitMultipleValues('contact');});

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


}//portfolio_contact_initialize()




function portfolio_images_JDbox_initialize(imageContainerID)
{
	$('img.pi_thumbs_view').click(function(){
		
		obj = $(this);
		imageID = obj.attr('id');
		imageID = imageID.split('_');
		imageID = imageID[1];
		imageSrc = $('#portfolioimage_'+imageID).attr('src');
	
		if(imageContainerID !=0 ){viewAlbumImagesPresentationType = $('#viewalbumimagespresentationtype_'+imageContainerID).html();}
		else {viewAlbumImagesPresentationType = $('#viewalbumimagespresentationtype').html();}

		switch(viewAlbumImagesPresentationType)
		{
			case 'largethumbnails':
				if(imageSrc == imageSrc.split('largethumbnails')){imageSrc = imageSrc.split('thumbnails');}
				else{imageSrc = imageSrc.split('largethumbnails');}
				imageSrc = imageSrc[0]+'fullresolution'+imageSrc[1];				
				JDbox_initialize(imageID,imageSrc,'view',imageContainerID);
				break;
			case 'thumbnails':
				imageSrc = imageSrc.split('thumbnails');
				imageSrc = imageSrc[0]+'fullresolution'+imageSrc[1];
				JDbox_initialize(imageID,imageSrc,'view',imageContainerID);
				break;
			default:
				break;
		}//switch

	});
}//portfolio_images_JDbox_initialize()

function JDbox_initialize(imageID_jdbox,imageSrc,presentationMode,imageContainerID)
{
/*
here reset the search and suggest for the image tags
*/
	
		$('.blog_entry_embeddedvideos').hide();
		$('.display_albumembeddedvideos').hide();
		if($('#display_albumembeddedvideos').length != 0 ){$('#display_albumembeddedvideos').hide();}
	
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
		
		$('.blog_entry_embeddedvideos').show();
		$('.display_albumembeddedvideos').show();
		if($('#display_albumembeddedvideos').length != 0 ){$('#display_albumembeddedvideos').show();}
	});
	
	$('#jdbox_image_newwindow').click(function(){
		window_open_new(imageSrc);
	});

	if(currentGalleryImagesNumber != 1)
	{
		$('#jdbox_image_prev').click(function(){
			$(document).unbind();
			$('#jdbox_image_container').remove();
			JDbox_navigate_images(imageID_jdbox,presentationMode,'previous',imageContainerID);
		});
		
		$('#jdbox_image_next').click(function(){
			$(document).unbind();
			$('#jdbox_image_container').remove();
			JDbox_navigate_images(imageID_jdbox,presentationMode,'next',imageContainerID);
		});
	}//
	
	
	$('.portfolio_tags').click(function(){
		tagElementID = $(this).attr('id')
		tagElementID = tagElementID.split('_');
		albumID = '';
		tagID = parseInt(tagElementID[2]);
		userID = parseInt($('#steve').html());
		other = '';
		$('#loader_layer4').hide();
		$('#layer_placeholder').hide();
		$('#jdbox_image_container').remove();
		portfolio_content_load('portfolio_image_tag',albumID,userID,tagID,other);
	});
		
	
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
				JDbox_navigate_images(imageID_jdbox,presentationMode,'previous',imageContainerID);
				return false;
			}//if
		break;
		case (39):
			$(document).unbind();
			if(currentGalleryImagesNumber != 1){
				$('#jdbox_image_container').remove();
				JDbox_navigate_images(imageID_jdbox,presentationMode,'next',imageContainerID);
				return false;
			}//if
		break;
		case (27):
			$('#loader_layer4').hide();
			$('#layer_placeholder').hide();
			$(document).unbind();
			$('#jdbox_image_container').remove();
			
			$('.blog_entry_embeddedvideos').show();
			$('.display_albumembeddedvideos').show();
			if($('#display_albumembeddedvideos').length != 0 ){$('#display_albumembeddedvideos').show();}
			return false;
		break;
		default: break;
	  }//
	});
	
	
}//JDbox_initialize(imageID,imageSrc,presentationMode)

function JDbox_navigate_images(imageID,presentationMode,navigationDirection,imageContainerID)
{
	albumPortfolioImagesOrder = $('.albumportfolioimagesorder').html();
	albumPortfolioImagesOrder = albumPortfolioImagesOrder.split(',');
	currentGalleryImagesNumber = albumPortfolioImagesOrder.length-1;
	
	imageSrc = $('#portfolioimage_'+imageID).attr('src');
	imageSrc = imageSrc.split('thumbnails');
	imageSrc = imageSrc[0]+'fullresolution'+imageSrc[1];
	
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
	
	if(imageContainerID !=0 ){viewAlbumImagesPresentationType = $('#viewalbumimagespresentationtype_'+imageContainerID).html();}
	else {viewAlbumImagesPresentationType = $('#viewalbumimagespresentationtype').html();}

	switch(viewAlbumImagesPresentationType)
	{
		case 'largethumbnails':
			if(newImageSrc == newImageSrc.split('largethumbnails')){newImageSrc = newImageSrc.split('thumbnails');}
			else{newImageSrc = newImageSrc.split('largethumbnails');}
			break;
		case 'thumbnails':
			newImageSrc = newImageSrc.split('thumbnails');
			break;
		default:
			break;
	}//switch
	
	newImageSrc = newImageSrc[0]+'fullresolution'+newImageSrc[1];	
	JDbox_initialize(newImageID,newImageSrc,presentationMode,imageContainerID);
	
}//JDbox_navigate_images(imageID,presentationMode,navigationDirection)

function window_open_new(fileURL)
{
	window.open(fileURL ,"_blank","fullscreen=no,status=no,toolbar=no,menubar=no,resizable=yes,scrollbars=yes");
}//window_open_new(fileURL)