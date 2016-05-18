<?php
##############################
/* RESPONSES */
function handle_validationError($errorsVarsArr,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validationError($errorsVarsArr,$formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validationError($errorsVarsArr,$formID,$callType)
function handle_formSubmissionError($errorsVarsArr,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_formSubmissionError($errorsVarsArr,$formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validationError($errorCode,$formID,$callType)
function jsonresponse_validationError($errorsVarsArr,$formID)
{
	$response = array();
	$response['responsetype'] = 'validationerror';
	$response['result'] = $errorsVarsArr['errorCode'];
	$response['resultmessage'] = variousMessages($errorsVarsArr['errorCode'],$errorsVarsArr['errorFieldID']);
	$response['fieldid'] = $errorsVarsArr['errorFieldID'];
	$response['fieldvalue'] = $errorsVarsArr['errorFieldValue'];
	$response['formid'] = $formID;

	echo json_encode($response);
	exit;
}//jsonresponse_validationError()
function jsonresponse_formSubmissionError($errorCode,$formID)
{
	$response = array();
	$response['responsetype'] = 'formsubmitionerror';
	$response['result'] = $errorCode;
	$response['resultmessage'] = variousMessages($errorCode,'');
	$response['formid'] = $formID;	
	
	echo json_encode($response);
	exit;
}//jsonresponse_formSubmissionError($errorCode,$formID)

function handle_validateNsubmit_login($username,$userType,$formID, $callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_login($username,$userType,$formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//
function handle_validateNsubmit_loginError($username,$errorCode,$formID, $callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_loginError($username,$errorCode,$formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//
function handle_validateNsubmit_logout($formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_logout($formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//

function handle_validateNsubmit_albumimagesdelete($errorCode,$formID,$fielID,$fieldValue,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_albumimagesdelete($errorCode,$formID,$fielID,$fieldValue);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_albumimagesdelete($errorCode,$formID,$fielID,$fieldValue,$callType)

function jsonresponse_validateNsubmit_albumimagesdelete($errorCode,$formID,$fielID,$fieldValue)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['fieldid'] = $fieldID;
	$response['fieldvalue'] = $fieldValue;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_albumimagesdelete($errorCode,$formID,$fielID,$fieldValue)


function handle_validateNsubmit_albumimagesorder($errorCode,$formID,$fielID,$fieldValue,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_albumimagesorder($errorCode,$formID,$fielID,$fieldValue);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_albumimagesorder($errorCode,$formID,$fielID,$fieldValue,$callType)
function jsonresponse_validateNsubmit_albumimagesorder($errorCode,$formID,$fielID,$fieldValue)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['fieldid'] = $fieldID;
	$response['fieldvalue'] = $fieldValue;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_albumimagesorder($errorCode,$formID,$fielID,$fieldValue)

function handle_validateNsubmit_imageCaption($errorCode,$formID,$fieldID,$fieldValue,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_imageCaption($errorCode,$formID,$fieldID,$fieldValue);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_imageCaption($errorCode,$formID,$callType)

function jsonresponse_validateNsubmit_imageCaption($errorCode,$formID,$fieldID,$fieldValue)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	$response['fieldid'] = $fieldID;
	$response['fieldvalue'] = $fieldValue;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_imageCaption($errorCode,$formID,$fieldID,$fieldValue)

function handle_validateNsubmit_updateWebsiteDesign($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateWebsiteDesign($errorCode,$formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateWebsiteDesign($errorCode,$formID,$callType)

function jsonresponse_validateNsubmit_updateWebsiteDesign($errorCode,$formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateWebsiteDesign($errorCode,$formID)

function handle_validateNsubmit_imageupdateviews($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_imageupdateviews($errorCode,$formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_imageupdateviews($errorCode,$formID,$callType)

function jsonresponse_validateNsubmit_imageupdateviews($errorCode,$formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_imageupdateviews($errorCode,$formID)

function handle_validateNsubmit_comment($errorCode,$formID,$userID,$albumID,$postID,$currentCommentsCounter,$commentFor,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_comment($errorCode,$formID,$userID,$albumID,$postID,$currentCommentsCounter,$commentFor);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_comment($errorCode,$formID,$userID,$albumID,$postID,$callType)

function jsonresponse_validateNsubmit_comment($errorCode,$formID,$userID,$albumID,$postID,$currentCommentsCounter,$commentFor)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	$response['userid'] = $userID;
	$response['albumid'] = $albumID;
	$response['postid'] = $postID;
	$response['currentcommentscounter'] = $currentCommentsCounter;
	$response['commentfor'] = $commentFor;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_comment($errorCode,$formID,$userID,$albumID,$postID)

function handle_validateNsubmit_imageTags($errorCode,$formID,$fieldID,$fieldValue,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_imageTags($errorCode,$formID,$fieldID,$fieldValue);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_imageCaption($errorCode,$formID,$callType)

function jsonresponse_validateNsubmit_imageTags($errorCode,$formID,$fieldID,$fieldValue)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	$response['fieldid'] = $fieldID;
	$response['fieldvalue'] = $fieldValue;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_imageTags($errorCode,$formID,$fieldID,$fieldValue)



function jsonresponse_image_src_thumbnail($errorCode,$formID,$imageID,$imageOrientation,$imageURL)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['imageid'] = $imageID;
	$response['imageurl'] = $imageURL;
	$response['imageorientation'] = $imageOrientation;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//


function jsonresponse_validateNsubmit_login($username,$userType,$formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $userType;
	$response['username'] = $username;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_login($username,$userType,$formID)
function jsonresponse_validateNsubmit_loginError($username,$errorCode,$formID)
{
	$response = array();
	$response['responsetype'] = 'credentialserror';
	$response['result'] = $errorCode;
	$response['resultmessage'] = variousMessages($errorCode,'');
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_loginError($username,$errorCode,$formID)
function jsonresponse_validateNsubmit_logout($formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//validateNsubmitLogoutXMLresponse($formID)
function handle_validateNsubmit_registration($errorCode, $formID, $callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_registration($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_registration($errorCode, $formID, $callType)
function handle_validateNsubmit_registrationTESTING($errorCode, $formID,$activationURL, $callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_registrationTESTING($errorCode, $formID,$activationURL);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_registrationTESTING($errorCode, $formID,$activationURL, $callType)
function handle_validateNsubmit_forgotpassword($errorCode, $formID, $callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_forgotpassword($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_forgotpassword($errorCode, $formID, $callType)
function handle_validateNsubmit_forgotpasswordTESTING($errorCode,$formID,$activationURL, $callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_forgotpasswordTESTING($errorCode,$formID,$activationURL);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_forgotpasswordTESTING($errorCode, $formID,$activationURL, $callType)
function handle_validateNsubmit_resetpassword($errorCode, $formID, $callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_resetpassword($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_resetpassword($errorCode, $formID, $callType)
function handle_validateNsubmit_updateUser($errorCode, $formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateUser($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateUser($errorsVarsArr['errorCode'], $formID,$callType)

function handle_validateNsubmit_updateWebsite($errorCode, $formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateWebsite($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateWebsite($errorCode, $formID,$callType)

function handle_validateNsubmit_updateUserPrivacy($errorCode, $formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateUserPrivacy($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateUserPrivacy($errorCode, $formID,$callType)

function handle_validateNsubmit_updateUserArtistTags($errorCode, $formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateUserArtistTags($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateUserArtistTags($errorCode, $formID,$callType)

function handle_validateNsubmit_createAlbum($errorCode,$formID,$callType,$albumID)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_createAlbum($errorCode,$formID,$albumID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_createAlbum($errorsVarsArr['errorCode'],$formID,$callType,$insertID);

function handle_validateNsubmit_createPost($errorCode,$formID,$callType,$postID)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_createPost($errorCode,$formID,$postID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_createPost($errorCode,$formID,$callType,$albumID)


function handle_validateNsubmit_updateAlbumVisibility($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateAlbumVisibility($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateAlbumVisibility($errorCode, $formID,$callType)

function handle_validateNsubmit_updateBlogVisibility($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateBlogVisibility($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateAlbumVisibility($errorCode, $formID,$callType)

function handle_validateNsubmit_deleteAlbum($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_deleteAlbum($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_deleteAlbum($errorCode,$formID,$callType)


function handle_validateNsubmit_deletePost($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_deletePost($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_deletePost($errorCode,$formID,$callType)


function handle_validateNsubmit_updateAlbum($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateAlbum($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateAlbum($errorsVarsArr($errorCode,$formID,$callType)

function handle_validateNsubmit_updateBlog($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateBlog($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//handle_validateNsubmit_updateBlog($errorsVarsArr($errorCode,$formID,$callType)

function handle_validateNsubmit_updateImageSettingsOptions($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_updateImageSettingsOptions($errorCode, $formID);}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//($errorCode,$formID,$callType)

function jsonresponse_validateNsubmit_updateImageSettingsOptions($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateImageSettingsOptions($errorCode, $formID)

function jsonresponse_validateNsubmit_updateAlbum($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateAlbum($errorCode, $formID)

function jsonresponse_validateNsubmit_updateBlog($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateBlog($errorCode, $formID)

function jsonresponse_validateNsubmit_deleteAlbum($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_deleteAlbum($errorCode, $formID)

function jsonresponse_validateNsubmit_deletePost($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_deletePost($errorCode, $formID)


function jsonresponse_validateNsubmit_updateAlbumVisibility($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateAlbumVisibility($errorCode, $formID)

function jsonresponse_validateNsubmit_updateBlogVisibility($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateBlogVisibility($errorCode, $formID)


function jsonresponse_validateNsubmit_createAlbum($errorCode,$formID,$albumID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	$response['newaid'] = $albumID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_createAlbum($errorCode,$formID,$albumID)


function json_validateNsubmit_createPost($errorCode,$formID,$postID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	$response['newpid'] = $postID;
	
	echo json_encode($response);
	exit;
}//json_validateNsubmit_createPost($errorCode,$formID,$albumID)



function jsonresponse_validateNsubmit_registration($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_registration($errorCode, $formID)
function jsonresponse_validateNsubmit_registrationTESTING($errorCode, $formID,$activationURL)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	$response['activationurl'] = $activationURL;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_registrationTESTING($errorCode, $formID,$accountSettingsURL)

function jsonresponse_validateNsubmit_updateUser($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateUser($errorCode, $formID)

function jsonresponse_validateNsubmit_updateWebsite($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//handle_validateNsubmit_updateWebsite($errorCode, $formID)

function jsonresponse_validateNsubmit_updateUserPrivacy($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateUserPrivacy($errorCode, $formID)

function jsonresponse_validateNsubmit_updateUserArtistTags($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateUserArtistTags($errorCode, $formID)

function jsonresponse_validateNsubmit_forgotpassword($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//validateNsubmitForgotPasswordXMLresponse($errorsVarsArr['errorCode'], $formID);

function jsonresponse_validateNsubmit_forgotpasswordTESTING($errorCode, $formID,$activationURL)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	$response['activationurl'] = $activationURL;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_forgotpasswordTESTING($errorCode, $formID,$activationURL)
function jsonresponse_validateNsubmit_resetpassword($errorCode, $formID)
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_resetpassword($errorsVarsArr['errorCode'], $formID)



function handle_system_errors($errorCode,$formID,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_system_errors($errorCode,$formID);}
	else if($callType == 'php')
		{redirects(0,'');} //TO-BE IMPLEMENTED
}//handle_system_errors($errorCode,$callType)

function jsonresponse_system_errors($errorCode,$formID)
{
	$response = array();
	$response['responsetype'] = 'systemerror';
	$response['result'] = $errorCode;
	$response['resultmessage'] = variousMessages($errorsVarsArr['errorCode'],'');
	$response['formid'] = $formID;
	
	echo json_encode($response);
	exit;
}//jsonresponse_system_errors($formID)



function handle_validateNsubmit_contact($errorCode,$formID,$emailType,$callType)
{
	if($callType == 'ajax')
		{jsonresponse_validateNsubmit_contact($errorCode, $formID, $emailType); exit;}
	else if($callType == 'php')
		{} //TO-BE IMPLEMENTED
}//($errorCode,$formID,$callType)

function jsonresponse_validateNsubmit_contact($errorCode, $formID, $emailType )
{
	$response = array();
	$response['responsetype'] = 'routine';
	$response['result'] = $errorCode;
	$response['formid'] = $formID;
	$response['emailtype'] = $emailType;

	echo json_encode($response);
	exit;
}//jsonresponse_validateNsubmit_updateImageSettingsOptions($errorCode, $formID)

?>
