var showErrors = true;
// initialize the validation requests cache
var validateNsubmitCache = new Array();
var validateNsubmitServerAddress;
var tempStrArr = new Array();

var clint = null;
var documentClassName = null;
var conditionalFlag = null;
var urlString = null;
var loaderID = null;
var messages = null;
var responseStrArr = null;

var formValues = new Array();
var formValuesNames = new Array();
$loaderImg = 'loader3.gif';


function validateNsubmitMultipleValues(formID)
{
	validateNsubmitServerAddress = "./rdincfiles/functionsmapping.inc.php?type=";
	clint = null;
	containerPageName = $("#eddie").html();
	conditionalFlag = false;
	urlString = '';
	loaderID = '';
	messages = '';
	clint = $('#clint').html();

	formValues = new Array();
	formValuesNames = new Array();

	tempformID = formID.split('_');
	if(tempformID[0] == 'profileartisttag')
		{formID = 'profileartisttag'; tagID = parseInt(tempformID[2]); tagAction = tempformID[1];}
	if(tempformID[0] == 'profileartistgender')
		{formID = 'profileartistgender'; artistGender = tempformID[1];}
	if(tempformID[0] == 'profileartistvisibility')
		{formID = 'profileartistvisibility'; artistVisibilityStatus = tempformID[1];}
	if(tempformID[0] == 'profileartistnewsletter')
		{formID = 'profileartistnewsletter'; newsletterStatus = tempformID[1];}
	if(tempformID[0] == 'profileartistalbumcomments')
		{formID = 'profileartistalbumcomments'; albumcommentsStatus = tempformID[1];}
	if(tempformID[0] == 'profileartistblogpostcomments')
		{formID = 'profileartistblogpostcomments'; blogpostscommentsStatus = tempformID[1];}
	if(tempformID[0] == 'profileartistcommentnotifications')
		{formID = 'profileartistcommentnotifications'; commentnotificationsStatus = tempformID[1];}
	if(tempformID[0] == 'albumvisibility')
		{formID = 'albumvisibility'; albumID = tempformID[1]; albumVisibilityStatus = tempformID[2];}
	if(tempformID[0] == 'blogvisibility')
		{formID = 'blogvisibility'; postID = tempformID[1]; postVisibilityStatus = tempformID[2];}
	if(tempformID[0] == 'albumdelete')
		{formID = 'albumdelete'; albumID = tempformID[1];}
	if(tempformID[0] == 'blogdelete')
		{formID = 'blogdelete'; blogID = tempformID[1];}
	if(tempformID[1] == 'imageview')
	{
		if(tempformID[0] == 'album')
			{formID = 'albumimageview'; imageview = tempformID[2];}
		if(tempformID[0] == 'blog')
			{formID = 'blogimageview'; imageview = tempformID[2];}
	}

	if(tempformID[0] == 'imagecaption')
		{formID = 'imagecaption'; imageID = tempformID[1];}
	if(tempformID[0] == 'imagetags')
		{formID = 'imagetags'; imageID = tempformID[1];}
	if(tempformID[0] == 'uploadimagessettingsoption')
		{formID = 'uploadimagessettingsoption'; optionNumber = tempformID[1];}
	if(tempformID[0] == 'imageupdateviews')
		{formID = 'imageupdateviews'; imageID = tempformID[1]; views = tempformID[2];}
	if(tempformID[0] == 'coverpage')
		{formID = 'coverpage'; coverPageType = tempformID[1];}

	switch(formID)
	{
		case 'register':
			validateNsubmitServerAddress +="3";
			handleRegisterForm();
			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			clint += 'submitregister';

			$('#'+formID+'_username_message').html('');
			$('#'+formID+'_email_message').html('');
			$('#'+formID+'_password_message').html('');
			break;
		case 'login':
			validateNsubmitServerAddress +="7";
			handleLoginForm();
			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			clint += 'submitlogin';

			$('#'+formID+'_email_message').html('');
			$('#'+formID+'_password_message').html('');
			break;
		case 'forgotpassword':
			validateNsubmitServerAddress +="5";
			handleForgotPasswordForm();
			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			clint += 'submitforgotpassword';

			$('#'+formID+'_email_message').html('');
			break;
		case 'resetpassword':
			validateNsubmitServerAddress +="6";
			handleResetPasswordForm();
			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			clint += 'submitresetpassword';

			$('#'+formID+'_password_message').html('');
			$('#'+formID+'_repeatpassword_message').html('');
			break;
		case 'logout':
			validateNsubmitServerAddress +="8";
			clint += 'submitlogout';
			break;
		case 'user':
			clint += 'submitedituser';
			validateNsubmitServerAddress +="4";
			handleUpdateUserForm();
			break;
		case 'website':
			clint += 'submiteditwebsite';
			validateNsubmitServerAddress +="11";
			handleUpdateWebsiteForm();
			break;
		case 'profileartisttag':
			validateNsubmitServerAddress +="10";
			clint += 'submitprofileartistcategories';
			handleUpdateUserArtistCategoryForm(tagID,tagAction);

			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			break;
		case 'profileartistgender':
			validateNsubmitServerAddress +="10";
			clint += 'submitprofileartistgender';
			handleUpdateUserArtistGenderForm(artistGender);

			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			break;
		case 'profileartistvisibility':
			validateNsubmitServerAddress +="10";
			clint += 'submitprofileartistvisibilitystatus';
			handleUpdateUserArtistVisibilityForm(artistVisibilityStatus);

			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			break;
		case 'profileartistnewsletter':
			validateNsubmitServerAddress +="10";
			clint += 'submitprofileartistnewsletter';
			handleUpdateUserArtistNewsletterForm(newsletterStatus);

			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			break;
		case 'profileartistalbumcomments':
			validateNsubmitServerAddress +="10";
			clint += 'submitprofileartistalbumcomments';
			handleUpdateUserArtistAlbumCommentsForm(albumcommentsStatus);

			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			break;
		case 'profileartistblogpostcomments':
			validateNsubmitServerAddress +="10";
			clint += 'submitprofileartistblogpostcomments';
			handleUpdateUserArtistBlogCommentsForm(blogpostscommentsStatus);

			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			break;
		case 'profileartistcommentnotifications':
			validateNsubmitServerAddress +="10";
			clint += 'submitprofileartistcommentnotifications';
			handleUpdateUserArtistNotificationsForm(commentnotificationsStatus);

			loaderID = $('#'+formID+'_frm_loader');
			messages = $('#'+formID+'_frm_messages');
			break;
		case 'createalbum':
			validateNsubmitServerAddress +="12";
			clint += 'submitcreatealbum';
			handleCreateAlbumForm();
			break;
		case 'createpost':
			validateNsubmitServerAddress +="28";
			clint += 'submitcreatepost';
			handleCreatePostForm();
			break;
		case 'albumvisibility':
			validateNsubmitServerAddress +="13";
			clint += 'submitalbumvisibilitystatus';
			handleUpdateAlbumVisibilityStatusForm(albumID,albumVisibilityStatus);
			break;
		case 'blogvisibility':
			validateNsubmitServerAddress +="30";
			clint += 'submitpostvisibilitystatus';
			handleUpdatePostVisibilityStatusForm(postID,postVisibilityStatus);
			break;
		case 'albumdelete':
			validateNsubmitServerAddress +="14";
			clint += 'submitalbumdelete';
			handleDeleteAlbumForm(albumID);
			break;
		case 'blogdelete':
			validateNsubmitServerAddress +="32";
			clint += 'submitpostdelete';
			handleDeleteBlogForm(blogID);
			break;
		case 'album':
			validateNsubmitServerAddress +="15";
			clint += 'submiteditalbum';
			handleupdateAlbumForm();
			break;
		case 'blog':
			validateNsubmitServerAddress +="29";
			clint += 'submiteditblog';
			handleupdateBlogForm();
			break;
		case 'albumimagedelete':
			validateNsubmitServerAddress +="17";
			clint += 'submitalbumimagesdeletelist';
			handleAlbumImagesDeleteForm();
			break;
		case 'blogimagedelete':
			validateNsubmitServerAddress +="17";
			clint += 'submitpostimagesdeletelist';
			handleBlogImagesDeleteForm();
			break;
		case 'coverpageimagedelete':
			validateNsubmitServerAddress +="17";
			clint += 'submitcoverpageimagesdeletelist';
			handleCoverPageImagesDeleteForm();
			break;
		case 'albumimagereorder':
			validateNsubmitServerAddress +="18";
			clint += 'submitalbumimagesreorderlist';
			handleAlbumImagesReorderForm();
			break;
		case 'albumimageview':
			validateNsubmitServerAddress +="19";
			clint += 'submitalbumimageview';
			handleAlbumimageviewForm(imageview);
			break;
		case 'blogimageview':
			validateNsubmitServerAddress +="31";
			clint += 'submitpostimageview';
			handleBlogimageviewForm(imageview);
			break;
		case 'imagecaption':
			validateNsubmitServerAddress +="20";
			clint += 'submiteditimagecaption';
			handleImageCaptionForm(imageID);
			break;
		case 'imagetags':
			validateNsubmitServerAddress +="21";
			clint += 'submiteditimagetags';
			handleImageTagsForm(imageID);
			break;
		case 'uploadimagessettingsoption':
			validateNsubmitServerAddress +="22";
			clint += 'submituploadimagessettingsoption';
			handleImageSettingsOption(optionNumber);
			break;
		case 'uploadimagessettingswidth':
			validateNsubmitServerAddress +="23";
			clint += 'submituploadimageswidth';
			handleImageSettingsWidth();
			break;
		case 'imageupdateviews':
			validateNsubmitServerAddress += "34";
			clint += 'submitupdateimageviews';
			handleImageUpdateViews(imageID, views);
			break;
		case 'contact':
			validateNsubmitServerAddress += "36";
			clint += 'submitcontact';
			handleContactUserForm();
			break;
		case 'comment':
			validateNsubmitServerAddress += "37";
			clint += 'submitcommentwhatever';
			handleCommentAlbumForm();
			break;
		case 'coverpage':
			validateNsubmitServerAddress += "38";
			clint += 'submitcoverpage';
			handleCoverPageForm(coverPageType);
			break;
		case 'designeditor':
			validateNsubmitServerAddress += "40";
			clint += 'submitdesigneditor';
			handleDesignEditorForm();
			break;
		default:
			validateNsubmitServerAddress +="8";
			clint += 'submitlogout';
			break;
	}//switch
	//}//else

	// only continue if xmlHttp isn't void
	if (xmlHttp)
	{
		if(formID != 'logout' && formID != 'profileartisttag' && formID != 'profileartistgender' && formID != 'profileartistvisibility' && formID !='profileartistnewsletter' && formID != 'profileartistalbumcomments' && formID != 'profileartistblogpostcomments' && formID != 'profileartistcommentnotifications' && formID !='user' && formID !='website' && formID != 'createalbum' && formID !='albumvisibility' && formID !='albumdelete' && formID !='album' && formID !='albumimagedelete' && formID != 'albumimagereorder' && formID != 'uploadimagessettingswidth' && formID != 'createpost' && formID !='blog' && formID != 'blogvisibility' && formID != 'blogimageview' && formID != 'blogdelete' && formID !='blogimagedelete' && formID !='imageupdateviews' && formID !='coverpage' && formID !='uploadimagessettingsoption' && formID !='coverpageimagedelete' && formID != 'designeditor' && formID != 'imagecaption' && formID != 'imagetags')
		{
			for (var i=0; i<formValues.length; i++){
				if(formValues[i]!='')
					{conditionalFlag=true; $('#'+formID+'_'+formValuesNames[i]+'_message').html('');}
				else{$('#'+formID+'_'+formValuesNames[i]+'_message').html('<-'); conditionalFlag=false; break;}}
		}//if
		else{conditionalFlag=true;}
		// if we received non-null parameters, we add them to cache in the
		// form of the query string to be sent to the server for validation
		if(conditionalFlag)
		{
			// encode values for safely adding them to an HTTP request query string
			switch(formID)
			{
				case "register":
					if(formValues[2].length<8)
						{errormessage = errorMessages(formValuesNames[2],105);
						$('#'+formID+'_frm_messages').show(); $('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span> '+errormessage); return 1;}
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=register&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case "login":
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=login&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'logout':
					urlString +='clint='+clint+'&formid=logout&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case "forgotpassword":
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=forgotpassword&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case "resetpassword":
					for(i=0; i<2; i++)
					{
						if(formValues[i].length<8){
							errormessage = errorMessages(formValuesNames[i],105);
							$('#'+formID+'_frm_messages').show();
							$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span> '+errormessage);
							$('#'+formID+'_'+formValuesNames[i]+'_message').html('x');
							return 1;}
					}//for
					if(formValues[0]!=formValues[1])
					{
						errormessage = errorMessages(formValuesNames[0],113);
						$('#'+formID+'_frm_messages').show(); $('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span> '+errormessage);
						$('#'+formID+'_'+formValuesNames[0]+'_message').html('x');
						$('#'+formID+'_'+formValuesNames[1]+'_message').html('x');
						return 1;
					}//if
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=resetpassword&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case "user":
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=user&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case "userprivacy":
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=userprivacy&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case "profileartisttag":
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=profileartisttag&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case "profileartistgender":
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=profileartistgender&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case "profileartistvisibility":
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=profileartistvisibility&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'profileartistnewsletter':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=profileartistnewsletter&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'profileartistalbumcomments':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=profileartistalbumcomments&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'profileartistblogpostcomments':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=profileartistblogpostcomments&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'profileartistcommentnotifications':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=profileartistcommentnotifications&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'website':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=website&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'createalbum':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=createalbum&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break
				case 'createpost':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=createpost&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break
				case 'albumvisibility':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=albumvisibility&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'blogvisibility':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=blogvisibility&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'albumdelete':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=albumdelete&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'blogdelete':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=blogdelete&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'album':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=album&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'blog':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=blog&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'albumimagedelete':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=albumimagedelete&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'blogimagedelete':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=blogimagedelete&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'coverpageimagedelete':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=coverpageimagedelete&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'albumimagereorder':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=albumimagereorder&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'albumimageview':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=albumimageview&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'blogimageview':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=blogimageview&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'imagecaption':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=imagecaption&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'imagetags':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=imagetags&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'uploadimagessettingsoption':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=uploadimagessettingsoption&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'uploadimagessettingswidth':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=uploadimagessettingswidth&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'imageupdateviews':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=imageupdateviews&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'contact':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=contact&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'comment':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=comment&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'coverpage':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=coverpage&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				case 'designeditor':
					for (var i=0; i<formValues.length; i++){urlString +=formValuesNames[i]+'='+formValues[i]+'&';}//for
					urlString +='clint='+clint+'&formid=designeditor&pageid='+containerPageName;
					validateNsubmitCache.push(urlString);
					break;
				default:
					break;
			}//switch

			//alert(validateNsubmitServerAddress+' '+urlString);

		if(formID != 'logout' && formID != 'profileartisttag' && formID != 'profileartistgender' && formID != 'profileartistvisibility' && formID !='profileartistnewsletter' && formID != 'profileartistalbumcomments' && formID != 'profileartistblogpostcomments' && formID != 'profileartistcommentnotifications' && formID !='createalbum' && formID !='albumvisibility' && formID !='albumdelete' && formID !='albumimagereorder' && formID !='imagecaption' && formID !='imagetags' && formID !='createpost' && formID != 'blogvisibility' && formID !='blogimageview' && formID !='imageupdateviews' && formID !='comment' && formID != 'uploadimagessettingsoption' && formID !='coverpage')
		{
				$('#'+formID+'_frm_messages').hide();
				$('#'+formID+'_frm_messages').html('');
				if(formID == 'album' || formID == 'albumimagedelete' || formID == 'blog' || formID == 'blogdelete' )
					{$('#loader_layer2').show(); $('#loader_layer3').show();}
				else if(formID == 'contact')
					{$('#loader_layer5').show(); $("#maincontent").fadeTo(0,0.5);}
				else if(formID == 'designeditor')
					{$('#loader_layer7').show();}
				else
					{$('#wrapper').fadeTo(200,0.4,function(){$('#loader_layer').show();});}
			}//
			sendXMLRequestValidateNsubmit('multipleValues');
		}//if
		else{
			if(formID != 'logout'){
				$('#'+formID+'_frm_messages').show();
				$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span> please fill the required fields');
				$('#'+formID+'_loader_placeholder').html('');
				$('#'+formID+'_loader_placeholder').hide();
				$('#'+formID+'_frm').show();
			}//logout
		}//else
	}//if

}//validateNsubmitMultipleValues(formID)


function sendXMLRequestValidateNsubmit(type)
{
/*
A modified version of a function found in:

Darie, C., Brinzarea B., Chereches-Tosa, F. & Bucicia M., 2005, AJAX and PHP: Building Responsive Web Applications, Packt Publishing
*/

	// try to connect to the server
	try
	{
		// continue only if the XMLHttpRequest object isn't busy
		// and the cache is not empty
		if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0) && validateNsubmitCache.length > 0)
		{
			// get a new set of parameters from the cache
			var cacheEntry = validateNsubmitCache.shift();
			// make a server request to validate the extracted data
			xmlHttp.open("POST", validateNsubmitServerAddress, true);
			xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			if(type=='singleValue'){xmlHttp.onreadystatechange = handleRequestStateChangeValidateNSubmit;}
			else if(type=='multipleValues'){xmlHttp.onreadystatechange = handleRequestStateChangeValidateNSubmitMultipleValues;}
			xmlHttp.send(cacheEntry);
		}//if
	}//try
	catch (e)
	{
		// display an error when failing to connect to the server
		displayError(e.toString(), "submit");
	}//catch
}//sendXMLRequestValidateNsubmit(type)

function submitImageURL(imageID)
{
	validateNsubmitServerAddress = "./rdincfiles/image.inc.php?requesting=3&iid="+imageID;

	// only continue if xmlHttp isn't void
	if (xmlHttp)
	{
		urlString='';
		validateNsubmitCache.push(urlString);

		sendXMLRequestValidateNsubmit('multipleValues');
	}
}//submitImageURL

function handleRegisterForm()
{

	formValuesNames[0] = 'username'; formValues[0] = $('#register_username').val();
	formValuesNames[1] = 'email'; formValues[1] = $('#register_email').val();
	formValuesNames[2] = 'password'; formValues[2] = $('#register_password').val();
	formValuesNames[3] = 'terms'; formValues[3] = $('#register_terms').val();
}//handleRegisterForm()

function handleLoginForm()
{
	formValuesNames[0] = 'email'; formValues[0] = $('#login_email').val();
	formValuesNames[1] = 'password'; formValues[1] = $('#login_password').val();
}//handleLoginForm()

function handleForgotPasswordForm()
{
	formValuesNames[0] = 'email'; formValues[0] = $('#forgotpassword_email').val();
}//handleForgotPasswordForm()

function handleResetPasswordForm()
{
	formValuesNames[0] = 'password'; formValues[0] = $('#resetpassword_password').val();
	formValuesNames[1] = 'repeatpassword'; formValues[1] = $('#resetpassword_repeatpassword').val();
	formValuesNames[2] = 'hash'; formValues[2] = $('#repeatpassword_hash').val();
}//handleResetPasswordForm()

function handleUpdateUserForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'name'; formValues[2] = $('#user_name').val();
	formValuesNames[3] = 'description'; formValues[3] = $('#user_description').val();
	formValuesNames[4] = 'facebook'; formValues[4] = $('#user_facebook').val();
	formValuesNames[5] = 'myspace'; formValues[5] = $('#user_myspace').val();
	formValuesNames[6] = 'youtube'; formValues[6] = $('#user_youtube').val();
	formValuesNames[7] = 'twitter'; formValues[7] = $('#user_twitter').val();
	formValuesNames[8] = 'vimeo'; formValues[8] = $('#user_vimeo').val();
}//handleUpdateUserForm()

function handleUpdateUserPrivacyForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#userprivacy_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#userprivacy_email').html();
	formValuesNames[2] = 'visibilitystatus'; formValues[2] = $('#userprivacy_visibilitystatus').val();
}//handleUpdateUserVisibilityForm()

function handleUpdateUserArtistCategoryForm(tagID,tagAction)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'tid'; formValues[2] = tagID;
	formValuesNames[3] = 'tagaction'; formValues[3] = tagAction;
}//handleUpdateUserArtistCategoryForm(tagID,tagAction)

function handleUpdateUserArtistGenderForm(artistGender)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'gender'; formValues[2] = artistGender;
}//handleUpdateUserArtistGenderForm(artistGender)

function handleUpdateUserArtistVisibilityForm(artistVisibilityStatus)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#privacy_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#privacy_email').html();
	formValuesNames[2] = 'visibilitystatus'; formValues[2] = artistVisibilityStatus;
}//handleUpdateUserArtistVisibilityForm(artistVisibilityStatus)

function handleCoverPageForm(coverPageType)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#steve').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#brandon').html();
	formValuesNames[2] = 'coverpage'; formValues[2] = coverPageType;
}//handleCoverPageForm(coverPageType)

function handleUpdateUserArtistNewsletterForm(newsletterStatus)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'newsletter'; formValues[2] = newsletterStatus;
}//handleUpdateUserArtistNewsletterForm(newsletterStatus)

function handleUpdateUserArtistAlbumCommentsForm(albumcommentsStatus)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#privacy_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#privacy_email').html();
	formValuesNames[2] = 'albumcomments'; formValues[2] = albumcommentsStatus;
}//handleUpdateUserArtistAlbumCommentsForm(albumcommentsStatus)

function handleUpdateUserArtistBlogCommentsForm(blogpostscommentsStatus)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#privacy_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#privacy_email').html();
	formValuesNames[2] = 'blogpostcomments'; formValues[2] = blogpostscommentsStatus;
}//handleUpdateUserArtistBlogCommentsForm(blogpostscommentsStatus)

function handleUpdateUserArtistNotificationsForm(commentnotificationsStatus)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#privacy_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#privacy_email').html();
	formValuesNames[2] = 'commentnotifications'; formValues[2] = commentnotificationsStatus;
}//handleUpdateUserArtistNotificationsForm(commentnotificationsStatus)

function handleUpdateWebsiteForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#website_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#website_email').html();
	formValuesNames[2] = 'title'; formValues[2] = $('#website_title').val();
	formValuesNames[3] = 'urltitle'; formValues[3] = $('#website_urltitle').val();
}//handleUpdateWebsiteForm()

function handleCreateAlbumForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
}//handleCreateAlbumForm()

function handleCreatePostForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
}//handleCreatePostForm()

function handleUpdateAlbumVisibilityStatusForm(albumID,albumVisibilityStatus)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'aid'; formValues[2] = albumID;
	formValuesNames[3] = 'visibilitystatus'; formValues[3] = albumVisibilityStatus;
}//handleUpdateAlbumVisibilityStatusForm(albumID,albumVisibilityStatus)

function handleUpdatePostVisibilityStatusForm(postID,postVisibilityStatus)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'pid'; formValues[2] = postID;
	formValuesNames[3] = 'visibilitystatus'; formValues[3] = postVisibilityStatus;
}//handleUpdateAlbumVisibilityStatusForm(postID,postVisibilityStatus)

function handleAlbumimageviewForm(imageview)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'aid'; formValues[2] = $('#album_aid').html();
	formValuesNames[3] = 'imageview'; formValues[3] = imageview;
}//handleAlbumimageviewForm(imageview)

function handleBlogimageviewForm(imageview)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'pid'; formValues[2] = $('#blog_pid').html();
	formValuesNames[3] = 'imageview'; formValues[3] = imageview;
}//handleAlbumimageviewForm(imageview)

function handleDeleteAlbumForm(albumID)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'aid'; formValues[2] = albumID;
}//handleDeleteAlbumForm(albumID)

function handleDeleteBlogForm(blogID)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'pid'; formValues[2] = blogID;
}//handleDeleteBlogForm(blogID)

function handleupdateAlbumForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'name'; formValues[2] = $('#album_name').val();
	formValuesNames[3] = 'description'; formValues[3] = $('#album_description').val();
	formValuesNames[4] = 'embeddedvideos'; formValues[4] = $('#album_embeddedvideos').val(); formValues[4] = encodeURIComponent(formValues[4]);

	formValuesNames[5] = 'category'; formValues[5] = $('#album_category').val();
	formValuesNames[6] = 'aid';  formValues[6] = $('#album_aid').html();
}//handleupdateAlbumForm(albumID)

function handleupdateBlogForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'headline'; formValues[2] = $('#blog_headline').val();
	formValuesNames[3] = 'body'; formValues[3] = $('#blog_body').val();
	formValuesNames[4] = 'embeddedvideos'; formValues[4] = $('#blog_embeddedvideos').val(); formValues[4] = encodeURIComponent(formValues[4]);

	formValuesNames[5] = 'category'; formValues[5] = $('#blog_category').val();
	formValuesNames[6] = 'pid';  formValues[6] = $('#blog_pid').html();
}//handleupdateBlogForm(blogID)

function handleAlbumImagesDeleteForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'aid'; formValues[2] = $('#album_aid').html();
	formValuesNames[3] = 'albumimagesdeleteorder'; formValues[3] = $('#albumimages_deleteorder').html();
}//handleAlbumImagesDeleteForm()

function handleBlogImagesDeleteForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'pid'; formValues[2] = $('#blog_pid').html();
	formValuesNames[3] = 'blogimagesdeleteorder'; formValues[3] = $('#blogimages_deleteorder').html();
}//handleAlbumImagesDeleteForm()

function handleCoverPageImagesDeleteForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'pid'; formValues[2] = $('#blog_pid').html();
	formValuesNames[3] = 'coverpageimagesdeleteorder'; formValues[3] = $('#coverpageimages_deleteorder').html();
}//handleCoverPageImagesDeleteForm

function handleAlbumImagesReorderForm()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#user_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#user_email').html();
	formValuesNames[2] = 'aid'; formValues[2] = $('#album_aid').html();
	formValuesNames[3] = 'albumimagesorder'; formValues[3] = $('#albumimages_order').html();
}//handleAlbumImagesReorderForm

function handleImageCaptionForm(imageID)
{
	userID = $('#portfolioimageusername_'+imageID).html();
	albumID = $('#portfolioimagealbum_'+imageID).html();

	formValuesNames[0] = 'username'; formValues[0] = $('#portfolioimageusername_'+imageID).html();
	formValuesNames[1] = 'aid'; formValues[1] = $('#portfolioimagealbum_'+imageID).html();
	formValuesNames[2] = 'iid'; formValues[2] = imageID;
	formValuesNames[3] = 'caption'; formValues[3] = $('#imagecaption_caption').val();
}//handleImageCaptionForm()

function handleImageTagsForm(imageID)
{
	userID = $('#portfolioimageusername_'+imageID).html();
	albumID = $('#portfolioimagealbum_'+imageID).html();

	formValuesNames[0] = 'username'; formValues[0] = $('#portfolioimageusername_'+imageID).html();
	formValuesNames[1] = 'aid'; formValues[1] = $('#portfolioimagealbum_'+imageID).html();
	formValuesNames[2] = 'iid'; formValues[2] = imageID;
	formValuesNames[3] = 'tags'; formValues[3] = $('#imagetags_tags').val();
}//handleImageTagsForm()

function handleImageSettingsOption(optionNumber)
{
	formValuesNames[0] = 'username'; formValues[0] = $('#settings_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#settings_email').html();
	formValuesNames[2] = 'uploadimagestype'; formValues[2] = optionNumber;
}//handleImageSettingsOption(optionNumber)

function handleImageSettingsWidth()
{
	formValuesNames[0] = 'username'; formValues[0] = $('#settings_username').html();
	formValuesNames[1] = 'email'; formValues[1] = $('#settings_email').html();
	formValuesNames[2] = 'uploadimageswidth'; formValues[2] = $('#uploadimagessettings_uploadimageswidth').val();
}//handleImageSettingsWidth

function handleImageUpdateViews(imageID, views)
{
	formValuesNames[0] = 'iid'; formValues[0] = parseInt(imageID);
	formValuesNames[1] = 'views'; formValues[1] = parseInt(views);
}//handleImageUpdateViews(imageID, views)

function handleCommentAlbumForm()
{
	commentName = $('#comment_name').val();
	commentEmail = $('#comment_email').val();
	commentReply = $('#comment_reply').val();
	commentFor = $('#commentfor').val();

	if( (commentName == '(your name)(required)') || (commentName == '(your name)') )
		{commentName = '';}
	if( (commentEmail == '(your email)(required)') || (commentEmail == '(your email)') )
		{commentEmail = '';}
	if( (commentReply == '(your comment)(required)') || (commentReply == '(your comment)') )
		{commentReply = '';}

	formValuesNames[0] = 'name'; formValues[0] = commentName;
	formValuesNames[1] = 'email'; formValues[1] = commentEmail;
	formValuesNames[2] = 'body'; formValues[2] = commentReply;
	formValuesNames[3] = 'uid'; formValues[3] = parseInt($('#steve').html());

	switch(commentFor)
	{
		case 'album':
			formValuesNames[4] = 'aid'; formValues[4] = parseInt($('#clark').html());
			formValuesNames[5] = 'pid'; formValues[5] = '0';
			break;
		case 'post':
			formValuesNames[4] = 'aid'; formValues[4] = '0';
			formValuesNames[5] = 'pid'; formValues[5] = parseInt($('#clark').html());
			break;
	}//switch

	formValuesNames[6] = 'commentfor'; formValues[6] = commentFor;
}//handleCommentAlbumForm()

function handleContactUserForm()
{
	contactName = $('#contact_name').val();
	contactEmail = $('#contact_email').val();
	contactRegarding = $('#contact_regarding').val();
	contactMessage = $('#contact_message').val();
	contactType = $('#contact_type').val();
	if( $('#contact_cc').attr('checked') == true ){cc = 'true'}
	else if( $('#contact_cc').attr('checked') == false ){cc = 'false'}

	if( (contactName == '(type your name)(required)') || (contactName == '(type your name)') )
		{contactName = '';}
	if( (contactEmail == '(type your email)(required)') || (contactEmail == '(type your email)') )
		{contactEmail = '';}
	if( (contactRegarding == '(regarding)(not required)') || (contactRegarding == '(regarding)') )
		{$('#contact_regarding').val('(regarding)(not required)');}
	if( (contactMessage == '(type your message)(required)') || (contactMessage == '(type your message)') )
		{contactMessage = '';}

	formValuesNames[0] = 'name'; formValues[0] = contactName;
	formValuesNames[1] = 'email'; formValues[1] = contactEmail;
	formValuesNames[2] = 'regarding'; formValues[2] = contactRegarding;
	formValuesNames[3] = 'message'; formValues[3] = contactMessage;
	formValuesNames[4] = 'uid'; formValues[4] = parseInt($('#steve').html());
	formValuesNames[5] = 'type'; formValues[5] = contactType;
	formValuesNames[6] = 'cc'; formValues[6] = cc;

}//handleContactUserForm()


function handleDesignEditorForm()
{
	formValuesNames[0] = 'wallpaper'; formValues[0] = $('#de_wallpaper').html();
	formValuesNames[1] = 'wallpapercolor'; formValues[1] = $('#de_wallpapercolor').html();
	formValuesNames[2] = 'logotext'; formValues[2] = $('#de_logotext').html();
	formValuesNames[3] = 'sidebarorientation'; formValues[3] = $('#de_sidebarorientation').html();
	formValuesNames[4] = 'texttextcolor'; formValues[4] = $('#de_texttextcolor').html();
	formValuesNames[5] = 'textlinkcolor'; formValues[5] = $('#de_textlinkcolor').html();
	formValuesNames[6] = 'linkbgcolor'; formValues[6] = $('#de_linkbgcolor').html();
	formValuesNames[7] = 'textlinkhovercolor'; formValues[7] = $('#de_textlinkhovercolor').html();
	formValuesNames[8] = 'textlinkhoverbgcolor'; formValues[8] = $('#de_textlinkhoverbgcolor').html();
	formValuesNames[9] = 'textnavilinksize'; formValues[9] = $('#de_textnavilinksize').html();
	formValuesNames[10] = 'sectionsbg'; formValues[10] = $('#de_sectionsbg').html();
	formValuesNames[11] = 'textfontfamily'; formValues[11] = $('#de_textfontfamily').html();
	formValuesNames[12] = 'texthighlightcolor'; formValues[12] = $('#de_texthighlightcolor').html();
	formValuesNames[13] = 'textbghighlightcolor'; formValues[13] = $('#de_textbghighlightcolor').html();
	formValuesNames[14] = 'wallpapertype'; formValues[14] = $('#de_wallpapertype').html();
	formValuesNames[15] = 'imagetagcloud'; formValues[15] = $('#de_imagetagcloud').html();
	formValuesNames[16] = 'sidebar_updated'; formValues[16] = $('#de_sidebar_updated').html();
	formValuesNames[17] = 'sidebar_copyright'; formValues[17] = $('#de_sidebar_copyright').html();
	formValuesNames[18] = 'sidebar_email'; formValues[18] = $('#de_sidebar_email').html();
	formValuesNames[19] = 'templateid'; formValues[19] = $('#templateid').html();
	formValuesNames[20] = 'uid'; formValues[20] = $('#uid').html();

}//handleDesignEditorForm()


function readResponseValidateNsubmitMultipleValues()
{

	$('#wrapper').fadeTo(200,1,function(){
		$('#loader_layer').hide();
		$('#loader_layer2').hide();
		$('#loader_layer3').hide();
		$('#loader_layer7').hide();
	});

	// retrieve the server's response
	var formID = '';
	var loaderID = '';
	var messages = '';

	try
		{var responseObj = eval("(" + xmlHttp.responseText + ")");}
	catch(e)
		{
			try
				{var responseObj = eval("(" + xmlHttp.responseText + ")");}
			catch(e)
				{alert('Expected JSON response, FAILED: '+e.toString()); return 0;}//{return 0;}//
		}//catch


	formID = responseObj.formid;
	responsetype = responseObj.responsetype;

	if(formID != null)
	{
		tempformID = formID.split('_');

		if(tempformID[0] == 'profileartisttag')
			{formID = 'profileartisttag'; tagID = parseInt(tempformID[2]); tagAction = tempformID[1];}
		if(tempformID[0] == 'profileartistgender')
			{formID = 'profileartistgender'; artistGender = tempformID[1];}
		if(tempformID[0] == 'profileartistvisibility')
			{formID = 'profileartistvisibility'; artistVisibilityStatus = tempformID[1];}
		if(tempformID[0] == 'profileartistnewsletter')
			{formID = 'profileartistnewsletter'; artistNewsletterStatus = tempformID[1];}
		if(tempformID[0] == 'profileartistalbumcomments')
			{formID = 'profileartistalbumcomments'; artistAlbumCommentsStatus = tempformID[1];}
		if(tempformID[0] == 'profileartistblogpostcomments')
			{formID = 'profileartistblogpostcomments'; artistBlogPostCommentsStatus = tempformID[1];}
		if(tempformID[0] == 'profileartistcommentnotifications')
			{formID = 'profileartistcommentnotifications'; artistCommentNotificationsStatus = tempformID[1];}
		if(tempformID[0] == 'albumvisibility')
			{formID = 'albumvisibility'; albumID = tempformID[1]; albumVisibilityStatus = tempformID[2];}
		if(tempformID[0] == 'blogvisibility')
			{formID = 'blogvisibility'; postID = tempformID[1]; postVisibilityStatus = tempformID[2];}
		if(tempformID[0] == 'albumdelete')
			{formID = 'albumdelete'; albumID = tempformID[1];}
		if(tempformID[0] == 'blogdelete')
			{formID = 'blogdelete'; postID = tempformID[1];}
		if(tempformID[0] == 'albumimagereorder')
			{formID = 'albumimagereorder';}
		if(tempformID[0] == 'album')
			{formID = 'album'; albumID = tempformID[1];}
		if(tempformID[0] == 'blog')
			{formID = 'blog'; postID = tempformID[1];}
		if(tempformID[1] == 'imageview')
		{
			if(tempformID[0] == 'album')
				{formID = 'albumimageview'; albumID = tempformID[1]; albumimageview = tempformID[3];}
			if(tempformID[0] == 'blog')
				{formID = 'blogimageview'; postID = tempformID[1]; postimageview = tempformID[3];}
		}
		if(tempformID[0] == 'imagecaption')
			{formID = 'imagecaption'; imageID = tempformID[1];}
		if(tempformID[0] == 'imagetags')
			{formID = 'imagetags'; imageID = tempformID[1];}
		if(tempformID[0] == 'uploadimagessettingsoption')
			{formID = 'uploadimagessettingsoption'; optionNumber = tempformID[1];}
		if(tempformID[0] == 'coverpage')
			{formID = 'coverpage'; coverPageType = tempformID[1];}
	}//
	if(responsetype!='routine')
	{
		switch(responsetype)
		{
			case 'formsubmitionerror':
				formSubmitionErrorReporter(formID,'json');
				break;
			case 'validationerror':
				validationErrorReporter(formID,'json');
				break;
			case 'databaseerror':
				databaseErrorReporter(formID,'json');
				break;
			case 'credentialserror':
				credentialserror(formID,'json');
				break;
			case 'systemerror':
				systemerror(formID,'json');
				break;
			default:
				break;
		}//switch
		return 0;
	}//if
	else{}//OK

	switch(formID)
	{
		case 'register':
			var result = responseObj.result;

			registrationCompleteMessage = '<div id="registration_complete">You are now registered in RetinaDestruction!<br /><br />';
			registrationCompleteMessage += '<div class="highlight_color">Your account is currently inactive. An activation link has been sent to your email address.</div></div>';
			$('#'+formID+'_frm_messages').html(registrationCompleteMessage);
			$('#'+formID+'_frm_messages').show();
			$('#'+formID+'_loader_placeholder').html();
			$('#'+formID+'_loader_placeholder').hide();
			$('#'+formID+'_frm').hide();

			var activationURL = responseObj.activationurl;
			if(activationURL != null){alert("For testing purposes, the mail function that sends your activation link, is deactivated. Copy-paste to the browser address bar the following: "+activationURL);}
			break;
		case 'login':
			var result = responseObj.result;

			switch(result){
				case 'administrator':
					break;
				case 'registereduser':
					$('#login_frm_messages').html('User Identified. Redirecting to account page');
					$('#login_frm_messages').show();
				 	window.location.replace("./accountmanageindex.php");
					break;
			}//inner switch
			break;
		case 'logout':
			//alert('logout');
			switch($("#eddie").html())
			{
				case 'account':
					window.location.replace("./index.php");
					break;
				default:
					window.location.replace("./index.php");
					break;
			}//switch(document.body.className)


			break;
		case 'forgotpassword':
			var result = responseObj.result;

			requestPasswordMessage = '<div id="forgotpassword_complete">Directions to reset your password have been sent to your email account.</div>';
			$('#'+formID+'_frm_messages').html(requestPasswordMessage);
			$('#'+formID+'_frm_messages').show();
			$('#'+formID+'_loader_placeholder').html('');
			$('#'+formID+'_loader_placeholder').hide();

			var resetPasswordURL = responseObj.activationurl;
			if(resetPasswordURL != null){alert("For testing purposes, the mail function that sends the reset password link, is deactivated. Copy-paste to the browser address bar the following: "+resetPasswordURL);}

			break;
		case 'resetpassword':
			var result = responseObj.result;

			resetPasswordMessage = '<div id="resetpassword_complete">Your password has been reseted! <br /><br /><a class="blacklink home_links" href="./index.php" class="whitelink">Return to home page.</a></div>';
			$('#'+formID+'_frm_messages').html(resetPasswordMessage);
			$('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_loader').hide();
			$('#resetpassword_frm').hide();

			break;
		case 'user':
			var result = responseObj.result;

			$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=1&userupdated=1", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error')
					{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
				else
					{window.scroll(0,0); section_contentpages_account_initialize_listeners('settings_user');}//ALL OK
			});

			break;
		case 'website':
			var result = responseObj.result;

			$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=2&websiteupdated=1", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error')
					{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
				else
					{window.scroll(0,0); section_contentpages_account_initialize_listeners('settings_website');}//ALL OK
			});

			break;
		case 'profileartisttag':

			switch(tagAction)
			{
				case 'addtag':
					$('#profileartisttag_'+tagID).addClass('selectedtag');
					break;
				case 'removetag':
					$('#profileartisttag_'+tagID).removeClass('selectedtag');
					break;
				default:
					$('#profileartisttag_'+tagID).removeClass('selectedtag');
					break;
			}//switch
			break;
		case "profileartistgender":
			switch(artistGender)
			{
				case 'male':
					$('#profileartistgender_male').addClass('selectedtag');
					$('#profileartistgender_female').removeClass('selectedtag');
					break;
				case 'female':
					$('#profileartistgender_female').addClass('selectedtag');
					$('#profileartistgender_male').removeClass('selectedtag');
					break;
			}//switch
			break;
		case "profileartistvisibility":
			switch(artistVisibilityStatus)
			{
				case 'visible':
					$('#profileartistvisibility_visible').addClass('selectedtag');
					$('#profileartistvisibility_invisible').removeClass('selectedtag');
					break;
				case 'invisible':
					$('#profileartistvisibility_invisible').addClass('selectedtag');
					$('#profileartistvisibility_visible').removeClass('selectedtag');
					break;
			}//switch
			break;
		case 'profileartistnewsletter':
			switch(artistNewsletterStatus)
			{
				case 'enabled':
					$('#profileartistnewsletter_enabled').addClass('selectedtag');
					$('#profileartistnewsletter_disabled').removeClass('selectedtag');
					break;
				case 'disabled':
					$('#profileartistnewsletter_enabled').removeClass('selectedtag');
					$('#profileartistnewsletter_disabled').addClass('selectedtag');
					break;
			}//switch
			break;
		case 'profileartistalbumcomments':
			switch(artistAlbumCommentsStatus)
			{
				case 'enabled':
					$('#profileartistalbumcomments_enabled').addClass('selectedtag');
					$('#profileartistalbumcomments_disabled').removeClass('selectedtag');
					break;
				case 'disabled':
					$('#profileartistalbumcomments_enabled').removeClass('selectedtag');
					$('#profileartistalbumcomments_disabled').addClass('selectedtag');
					break;
			}//switch
			break;
		case 'profileartistblogpostcomments':
			switch(artistBlogPostCommentsStatus)
			{
				case 'enabled':
					$('#profileartistblogpostcomments_enabled').addClass('selectedtag');
					$('#profileartistblogpostcomments_disabled').removeClass('selectedtag');
					break;
				case 'disabled':
					$('#profileartistblogpostcomments_enabled').removeClass('selectedtag');
					$('#profileartistblogpostcomments_disabled').addClass('selectedtag');
					break;
			}//switch
			break;
		case 'profileartistcommentnotifications':
			switch(artistCommentNotificationsStatus)
			{
				case 'enabled':
					$('#profileartistcommentnotifications_enabled').addClass('selectedtag');
					$('#profileartistcommentnotifications_disabled').removeClass('selectedtag');
					break;
				case 'disabled':
					$('#profileartistcommentnotifications_enabled').removeClass('selectedtag');
					$('#profileartistcommentnotifications_disabled').addClass('selectedtag');
					break;
			}//switch
			break;
		case 'createalbum':
			var result = responseObj.result;
			var newAlbumID = responseObj.newaid;

			$("#account_albums_navigation_container").load("./rdincfiles/album.inc.php"+"?requesting=1&newaid="+newAlbumID, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{ section_contentpages_account_initialize_listeners('albums');}
			});

			$("#section_content_subpage_albums").load("./rdincfiles/album.inc.php"+"?requesting=2&newaid="+newAlbumID, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{section_contentpages_account_initialize_listeners('album_demosubpage_albumlist');}
			});

			break;
		case 'createpost':
			var result = responseObj.result;
			var newPostID = responseObj.newaid;

			$("#account_blog_navigation_container").load("./rdincfiles/blog.inc.php"+"?requesting=1&newpid="+newPostID, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{ section_contentpages_account_initialize_listeners('blog');}
			});

			account_blog_subpage_load_sections('blog_page_edit',newPostID,'');
			break;
		case 'albumvisibility':
			if(albumVisibilityStatus == 'visible'){
				albumVisibilityStatus = 'albumvisible';

				$('#album_visibility_'+albumID).removeClass('albuminvisible');
				$('#album_visibility_'+albumID).addClass('albumvisible');
				$('#album_visibility_'+albumID).html('visible');

				$('#account_album_'+albumID).removeClass('albuminvisible');
				$('#account_album_'+albumID).addClass('albumvisible');
				$('#account_album_'+albumID).fadeTo(500,1);
			}//
			else{
				albumVisibilityStatus = 'albuminvisible';

				$('#album_visibility_'+albumID).addClass('albuminvisible');
				$('#album_visibility_'+albumID).removeClass('albumvisible');
				$('#album_visibility_'+albumID).html('invisible');

				$('#account_album_'+albumID).removeClass('albumvisible');
				$('#account_album_'+albumID).addClass('albuminvisible');
				$('#account_album_'+albumID).fadeTo(500,0.3);
			}//
			break;
		case 'blogvisibility':
			if(postVisibilityStatus == 'visible'){
				postVisibilityStatus = 'albumvisible';

				$('#blog_visibility_'+postID).removeClass('bloginvisible');
				$('#blog_visibility_'+postID).addClass('blogvisible');
				$('#blog_visibility_'+postID).html('visible');

				$('#account_blog_'+postID).removeClass('bloginvisible');
				$('#account_blog_'+postID).addClass('blogvisible');
				$('#account_blog_'+postID).fadeTo(500,1);
			}//
			else{
				postVisibilityStatus = 'albuminvisible';

				$('#blog_visibility_'+postID).addClass('bloginvisible');
				$('#blog_visibility_'+postID).removeClass('blogvisible');
				$('#blog_visibility_'+postID).html('invisible');

				$('#account_blog_'+postID).removeClass('blogvisible');
				$('#account_blog_'+postID).addClass('bloginvisible');
				$('#account_blog_'+postID).fadeTo(500,0.3);
			}//
			break;
		case 'albumdelete':
				$('#account_album_'+albumID).fadeTo(500,0,function(){$('#account_album_'+albumID).remove();});
				account_album_subpage_load_sections('album_list_view','','');
			break;
		case 'blogdelete':
				$('#account_blog_'+postID).fadeTo(500,0,function(){$('#account_blog_'+postID).remove();});
				account_blog_subpage_load_sections('blog_list_view','','');
				$('#section_content_subpage_blog').html('');
			break;
		case 'album':

			account_album_subpage_load_sections('album_page_edit',albumID,'1');
			account_album_subpage_load_sections('album_page_view',albumID,'');

			albumCategoryData = $('span.albumCategories').html().split("::"); $("#album_category").autocomplete(albumCategoryData);
			break;
		case 'blog':
			account_blog_subpage_load_sections('blog_list_edit',postID,'');
			account_blog_subpage_load_sections('blog_page_edit',postID,'1');

			blogCategoryData = $('span.blogCategories').html().split("::"); $("#blog_category").autocomplete(blogCategoryData);
			break;
		case 'imagesrc':
			result = responseObj.result;
			imageURL = responseObj.imageurl;
			imageID = responseObj.imageid;
			imageOrientation = responseObj.imageorientation;

			liElement = "<li id='portfolioimageli_"+imageID+"' class='ul_portfolio_images_thumbnails' rel='"+imageID+"'></li>";
			imgElement = "<img src='"+imageURL+"' id='portfolioimage_"+imageID+"' height='75' width='75' class='pi_thumbs' />";
			spanElements = "<span id='portfolioimageorientation_"+imageID+"' class='displaynone'>"+imageOrientation+"</span><span id='portfolioimagetags_"+imageID+"' class='portfolioimagetags displaynone'></span><span id='portfolioimagecaption_"+imageID+"' class='displaynone'></span><span id='portfolioimageuploaded_"+imageID+"' class='displaynone'></span>";

			$('#thumbnails').append(liElement);
			$('#portfolioimageli_'+imageID).html(imgElement);
			$('#portfolioimageli_'+imageID).append(spanElements);

			$('.refreshsection').fadeTo(100,1);
			$('.refreshsection').click(function(){
				account_album_subpage_load_sections('album_page_view','','');
			});

			//portfolio_images_upload_listeners_remove();
			//portfolio_images_upload_listeners_initialize();

			return imageURL;
			break;
		case 'albumcoverimagesrc':
			result = responseObj.result;
			imageURL = responseObj.imageurl;
			imageID = responseObj.imageid;
			imageOrientation = responseObj.imageorientation;

			$('#album_uploadedcover').attr('src',imageURL);

			$('.refreshsection').fadeTo(100,1);
			$('.refreshsection').click(function(){
				account_album_subpage_load_sections('album_page_view','','');
			});

			return imageURL;
			break;
		case 'usercoverimagesrc':
			result = responseObj.result;
			imageURL = responseObj.imageurl;
			imageID = responseObj.imageid;
			imageOrientation = responseObj.imageorientation;

			$('#user_uploadedcover').attr('src',imageURL);

			return imageURL;
			break;
		case 'albumimagedelete':
			result = responseObj.result;
			fieldID = responseObj.fieldid;
			fieldValue = responseObj.fieldvalue;

			deletedPortfolioImages = fieldValue.split(':..::..:');

			for (var i=0; i<deletedPortfolioImages.length; i++){
				tempImageToDeleteID = deletedPortfolioImages[i];
				if($('#portfolioimageli_'+tempImageToDeleteID)){$('#portfolioimageli_'+tempImageToDeleteID).remove();}}//for

			albumID = $('#album_aid').html();
			account_album_subpage_load_sections('album_page_view',albumID,'');
			account_album_subpage_load_sections('album_page_edit',albumID,'');

			//portfolio_images_upload_event_initialize();
			break;
		case 'blogimagedelete':
			result = responseObj.result;
			fieldID = responseObj.fieldid;
			fieldValue = responseObj.fieldvalue;

			deletedPortfolioImages = fieldValue.split(':..::..:');

			for (var i=0; i<deletedPortfolioImages.length; i++){
				tempImageToDeleteID = deletedPortfolioImages[i];
				if($('#portfolioimageli_'+tempImageToDeleteID)){$('#portfolioimageli_'+tempImageToDeleteID).remove();}}//for

			blogID = $('#blog_pid').html();
			account_album_subpage_load_sections('album_page_edit',blogID,'');

			//portfolio_images_upload_event_initialize();
			break;
		case 'coverpageimagedelete':
			result = responseObj.result;
			fieldID = responseObj.fieldid;
			fieldValue = responseObj.fieldvalue;

			deletedPortfolioImages = fieldValue.split(':..::..:');

			for (var i=0; i<deletedPortfolioImages.length; i++){
				tempImageToDeleteID = deletedPortfolioImages[i];
				if($('#portfolioimageli_'+tempImageToDeleteID)){$('#portfolioimageli_'+tempImageToDeleteID).remove();}}//for

			section_contentpages_initialize_account('coverpage');

			//portfolio_images_upload_event_initialize();
			break;
		case 'albumimagereorder':
			result = responseObj.result;
			fieldID = responseObj.fieldid;
			imagesOrder = responseObj.fieldvalue;

			$('.albumportfolioimagesorder').html(imagesOrder);
			break;
		case 'albumimageview':
			$('span.album_imageviews').removeClass('selectedtag');
			$('#album_imageview_'+albumimageview).addClass('selectedtag');

			albumID = $('#album_aid').html();
			account_album_subpage_load_sections('album_page_view',albumID,'');
			break;
		case 'blogimageview':
			$('span.blog_imageviews').removeClass('selectedtag');
			$('#blog_imageview_'+postimageview).addClass('selectedtag');

			//postID = $('#blog_pid').html();
			//account_album_subpage_load_sections('album_page_view',albumID,'');
			break;
		case 'imagecaption':
			result = responseObj.result;
			fieldID = responseObj.fieldid;
			imageCaption = responseObj.fieldvalue;

			albumID = $('#portfolioimagealbum_'+imageID).html();
			$('#portfolioimagecaption_'+imageID).html(imageCaption);

			$('#imagecaption_frm_messages').html('Image caption saved successfully!');
		break;
		case 'imagetags':
			result = responseObj.result;
			fieldID = responseObj.fieldid;
			imageTags = responseObj.fieldvalue;

			albumID = $('#portfolioimagealbum_'+imageID).html();
			$('#portfolioimagetags_'+imageID).html(imageTags);

			$('#imagetags_frm_messages').html('Image tags saved successfully!');
			break;
		case 'uploadimagessettingsoption':
			/*
			$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=6&websiteupdated=1", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error')
					{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
				else
					{section_contentpages_account_initialize_listeners('settings_images');}//ALL OK
			});
			*/
			$('span.uploadimagessettingsoption').removeClass('selectedtag');
			$('#uploadimagessettingsoption_'+optionNumber).addClass('selectedtag');
			break;
		case 'coverpage':
			$('span.coverpagetype').removeClass('selectedtag');
			$('#coverpage_'+coverPageType).addClass('selectedtag');
			break;
		case 'uploadimagessettingswidth':
			$("#section_content_subpage_settings").load("./rdincfiles/layout.inc.php"+"?requesting=6&websiteupdated=1", function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error')
					{alert(XMLHttpRequest.status);} //THIS ERROR HANDLING WILL IMPLEMENTED IN THE FUTURE
				else
					{section_contentpages_account_initialize_listeners('settings_images');}//ALL OK
			});
			break;
		case 'imageupdateviews':
			//no response;
			break;
		case 'contact':
			emailType = responseObj.emailtype;

			if(emailType != 'mainsite')
				{portfolio_content_load('portfolio_contact','',userID,'','emailsent');}
			else
				{$('#contact_frm_messages').html("<span class='highlight_color'>Your message has been sent successfully.</span>"); $('#contact_frm_messages').show();}
			break;
		case 'comment':
			result = responseObj.result;
			albumID = responseObj.albumid;
			userID = responseObj.userid;
			tagID = '';
			postID = responseObj.postid;
			currentCommentsCounter = responseObj.currentcommentscounter;
			commentFor = responseObj.commentfor;

			if(commentFor == 'album')
			{
				if($('#accounter_'+albumID).length !=0 )
					{$('#accounter_'+albumID).html(currentCommentsCounter);}
				albumID = responseObj.albumid;
				postID = '';
			}
			if(commentFor == 'post')
			{
				if($('#pccounter_'+postID).length !=0 )
					{$('#pccounter_'+postID).html(currentCommentsCounter);}
				postID = responseObj.postid;
				albumID = '';
			}

			comments_loadform_and_initialize_elements(albumID,userID,postID);
		case 'designeditor':
			window.scroll(0,0);
			templateID = 0;
			$("#designeditor_settings_container").load("./rdincfiles/layout.inc.php"+"?requesting=12&templateid="+templateID, function(responseText, textStatus, XMLHttpRequest) {
				if(textStatus == 'error'){alert(XMLHttpRequest.status);}
				else{$('#loader_layer6').hide(); $('#maincontent').scrollTo(0,0); initialize_designeditor_form_elements(); $('#designeditor_frm_messages').html('Your website design has been changed successfully!');}
			});



			break;
		default:
			break;
	}//formID

	sendXMLRequestValidateNsubmit('multipleValues');

}//readResponseValidateNsubmitMultipleValues()


function resetVariables(formID)
{
	$('#'+formID+'_frm_messages').html(''); $('#'+formID+'_frm_messages').hide();
	$('#'+formID+'_loader_placeholder').html(''); $('#'+formID+'_loader_placeholder').hide();
	$('#'+formID+'_frm').show();
	switch(formID)
	{
		case 'register':
			if( $('#'+formID+'_loader').length == 0 )
			{
				$('#'+formID+'_username').val('');
				$('#'+formID+'_username'+'_message').html('');
				$('#'+formID+'_email').val('');
				$('#'+formID+'_email'+'_message').html('');
				$('#'+formID+'_password').val('');
				$('#'+formID+'_email'+'_message').html('');
				$('#register_terms').val('0');
				$('#register_terms_button').removeClass("selected");
				$('#'+formID+'_terms'+'_message').html('');
			}//
			break;
		case 'login':
			if( $('#'+formID+'_loader').length == 0 )
			{
				$('#'+formID+'_email').val('');
				$('#'+formID+'_email'+'_message').html('');
				$('#'+formID+'_password').val('');
				$('#'+formID+'_password'+'_message').html('');
				$('#'+'login_alert').html('');
			}//if
			break;
		case 'forgotpassword':
			if( $('#'+formID+'_loader').length == 0 )
			{
				$('#'+formID+'_email').val('');
				$('#'+formID+'_email'+'_message').html('');
			}//if
			break;
		case 'resetpassword':
			if( $('#'+formID+'_loader').length == 0 )
			{
				$('#'+formID+'_password').val('');
				$('#'+formID+'_password'+'_message').html('');
				$('#'+formID+'_repeatpassword').val('');
				$('#'+formID+'_repeatpassword'+'_message').html('');
			}//if
			break;
		case 'profileartisttag':
			break;
		default:
			break;
	}//switch
}//resetVariables()

function credentialserror(formID,responseFormat)
{

	$('#'+formID+'_frm_loader').html('');
	$('#'+formID+'_frm_loader').hide();
	$('#'+formID+'_frm_messages').show();
	$('#'+formID+'_frm_messages').html('<div class="highlight_color">Please register to create an account</div>');
	$('#'+formID+'_email').val(''); $('#'+formID+'_email'+'_message').html('');
	$('#'+formID+'_password').val(''); $('#'+formID+'_password'+'_message').html('');
	$('#'+formID+'_frm').show();
}//credentialserror(formID,responseFormat)



function formSubmitionErrorReporter(formID,responseFormat)
{
	if(responseFormat == 'xml'){
		var response = xmlHttp.responseText;
		if (response.indexOf("ERRNO") >= 0 || response.indexOf("error:") >= 0 || response.length == 0){throw(response.length == 0 ? "Server error." : response);}
		responseXml = xmlHttp.responseXML;
		xmlDoc = responseXml.documentElement;
		errorCode = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
	}
	else if(responseFormat == 'json')
	{
		var responseObj = JSON.parse(xmlHttp.responseText);

		formID = responseObj.formid;
		errorMessage = responseObj.resultmessage;
	}//

	switch(formID)
	{
		case 'register':
			//alert('Error: 'errorCode+' Form was not submitted correctly.');
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'login':
			//alert('Error: 'errorCode+' Form was not submitted correctly.');
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'forgotpassword':
			//alert('Error: 'errorCode+' Form was not submitted correctly.');
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case "reseatpassword":
			//alert('Error: 'errorCode+' Form was not submitted correctly.');
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case "user":
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case "userprivacy":
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'profileartisttag':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'album':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'blog':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'imagecaption':
			//$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'imagetags':
			//$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'uploadimagessettingswidth':
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'contact':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'comment':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		default:
			break;
	}//switch
}//formSubmitionErrorReporter(formID,responseFormat)

function validationErrorReporter(formID,responseFormat)
{
	if(responseFormat == 'xml'){
		var response = xmlHttp.responseText;
		if (response.indexOf("ERRNO") >= 0 || response.indexOf("error:") >= 0 || response.length == 0){throw(response.length == 0 ? "Server error." : response);}
		responseXml = xmlHttp.responseXML;
		xmlDoc = responseXml.documentElement;

		errorCode = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
		errorMessage = xmlDoc.getElementsByTagName("resultmessage")[0].firstChild.data;
		errorFieldID = xmlDoc.getElementsByTagName("fieldid")[0].firstChild.data;
		errorFieldValue = xmlDoc.getElementsByTagName("fieldvalue")[0].firstChild.data;
	}
	else if(responseFormat == 'json')
	{
		var responseObj = JSON.parse(xmlHttp.responseText);

		formID = responseObj.formid;
		errorMessage = responseObj.resultmessage;
		errorFieldID = responseObj.fieldid;
		errorFieldValue = responseObj.fieldvalue;
	}//

	switch(formID)
	{
		case 'register':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();

			$('#'+formID+'_'+errorFieldID+'_message').html('<-');
			$('#'+formID+'_'+errorFieldID+'_message').show();
			break;
		case 'login':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();

			$('#'+formID+'_'+errorFieldID+'_message').html('<-');
			$('#'+formID+'_'+errorFieldID+'_message').show();
			break;
		case 'forgotpassword':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();

			$('#'+formID+'_'+errorFieldID+'_message').html('<-');
			$('#'+formID+'_'+errorFieldID+'_message').show();
			break;
		case 'resetpassword':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();

			$('#'+formID+'_'+errorFieldID+'_message').html('<-');
			$('#'+formID+'_'+errorFieldID+'_message').show();
			break;
		case 'user':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();

			$('#'+formID+'_'+errorFieldID+'_message').html('<-');
			$('#'+formID+'_'+errorFieldID+'_message').show();
			break;
		case "userprivacy":
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();

			$('#'+formID+'_'+errorFieldID+'_message').html('<-');
			$('#'+formID+'_'+errorFieldID+'_message').show();
			break;
		case 'album':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'blog':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'imagecaption':
			//$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'imagetags':
			//$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'uploadimagessettingswidth':
			//$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'contact':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		case 'comment':
			$('#'+formID+'_frm_loader').hide(); $('#'+formID+'_frm_loader').html('');
			$('#'+formID+'_frm_messages').html('<span class="highlight_color">Error:</span>'+errorMessage); $('#'+formID+'_frm_messages').show();
			$('#'+formID+'_frm').show();
			break;
		default:
			break;
	}//switch
}//validationErrorReporter(formID,responseFormat)


function systemerror(formID,responseFormat)
{
	if(responseFormat == 'xml'){
		var response = xmlHttp.responseText;
		if (response.indexOf("ERRNO") >= 0 || response.indexOf("error:") >= 0 || response.length == 0){throw(response.length == 0 ? "Server error." : response);}
		responseXml = xmlHttp.responseXML;
		xmlDoc = responseXml.documentElement;

		errorCode = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
		errorMessage = xmlDoc.getElementsByTagName("resultmessage")[0].firstChild.data;
	}
	else if(responseFormat == 'json')
	{
		var responseObj = JSON.parse(xmlHttp.responseText);

		formID = responseObj.formid;
		errorCode = responseObj.result;
		errorMessage = responseObj.resultmessage;
	}//

	window.location.replace("./error.php?e="+errorCode);

}//systemerror(formID,responseFormat)

function databaseErrorReporter(formID,responseFormat)
{
	alert('database error! Not implemented yet');
	if(responseFormat == 'xml'){}
	else if(responseFormat == 'json'){}

	switch(formID)
	{
		case 'register':
			break;
		case 'login':
			break;
		case 'forgotpassword':
			break;
		case 'resetpassword':
			break;
		default:
			break;
	}//switch
}//databaseErrorReporter(formID,responseFormat)


function errorMessages(fieldID,errorCode)
{
	var $errVals = new Array();
	$errVals[101] = "Please type a "+fieldID+".";
	$errVals[102] = "Please type a shorter "+fieldID+".";
	$errVals[103] = "Please type a valid "+fieldID+".";
	$errVals[104] = "This "+fieldID+" already exists in the system.";

	$errVals[105] = "Your password must be at least 8 characters long."
	$errVals[112] = "Please accept the terms of agreement.";
	$errVals[113] = "The "+fieldID+"s you typed do not match.";

	return $errVals[errorCode];
}//errorMessages(element, fieldID, errorCode)
