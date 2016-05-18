<?php

function errorHandler($errorCode,$validationType)
{
	$message = variousMessages($errorCode);
	
	$errArray['entryType']='error';
	$errArray['valueType']='-';
	$errArray['validationType']=$validationType;
	$errArray['errorCode']=$errorCode;
	$errArray['message']=$message;
	$errArray['query']='-';
	$errArray['dbError']='-';
	//editUsersActionLog('insert', $errArray);
	
	if($validationType=='ajax')
		{errorReportXMLresponse($errorCode,'',$message,'','','');}
	elseif($validationType=='php')
		{$_SESSION['ERR']['TYPE']='Security'; $_SESSION['ERR']['CODE']=$errorCode; $_SESSION['ERR']['MESSAGE']=$message; redirects(0,'');}
}//errorHandler($errorCode,$validationType)
function errorReportXMLresponse($errorCode,$dbError,$message,$fieldValue,$fieldID,$valueType)
{
	if($fieldValue==''){$fieldValue='null';}
	if($fieldID==''){$fieldID='null';}
	if($valueType==''){$valueType='null';}
	if($dbError==''){$dbError='null';}
	
	$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . '<response>';
	$response .= '<responsetype>'.'errorreporter'.'</responsetype>'
				.'<errorcode>'.$errorCode.'</errorcode>'
				.'<message>'.$message.'</message>'
				.'<databaseerror>'.$dbError.'</databaseerror>'
				.'<fieldid>'.$fieldID.'</fieldid>'
				.'<fieldvalue>'.$fieldValue.'</fieldvalue>'
				.'<result>1</result>'
				.'<valuetype>'.$valueType.'</valuetype>';
	$response .='</response>';
	
	// generate the response
	if(ob_get_length()) { ob_clean(); }
	header('Content-Type: text/xml');
	echo $response;
	exit;
}//errorReportXMLresponse($errorCode,$dbError,$message,$fieldValue,$fieldID,$valueType)
function checkCookiesAvailability($pageID)
{
	if($pageID != 'browsersettings')
	{
		if(!isset($_COOKIE['test']))
		{
			error_reporting (E_ALL ^ E_WARNING ^ E_NOTICE);
			setcookie ('test', 'test', time() + 60000);
			redirects(1,'?e='.hash('sha256', "cookies"));
		}//if
	}//if
	else{
		if(isset($_COOKIE['test'])){redirects(0,'');}//if
	}//else
}//checkCookiesAvailability($errVars)


function variousMessages($code,$fieldID)
{	
	if(!preg_match("/^[0-9]([0-9]*)/",$code)){ $code = NULL; $error = "";}
	else { 	$error = ""; }//do nothing. ALL OK
	
	$message = "";
	switch($code)
	{	
		case 101:
			$message = "Please type your ".$fieldID."";
			break;
		case 102:
			$message = "Please type a shorter ".$fieldID.".";
			break;
		case 103:
			$message = "Please type a valid ".$fieldID.".";
			break;
		case 104:
			$message = "This ".$fieldID." is already taken.";
			break;
		case 105:
			$message = "Account identified by this ".$fieldID." doesn't exist in the system.";
			break;
		case 106:
			$message = "You've already created a story with this ".$fieldID.".";
			break;
		case 107:
			$message = $error."Unaccepted file format.";
			break;
		case 108:
			$message = $error."Allowed file formats are not defined.";
			break;
		case 109:
			$message = $error."File exceeds the defined maximum filesize.";
			break;
		case 110:
			$message = $error."File not uploaded.";
			break;
		case 111:
			$message = $error."The user is an imposter.";
			break;
		case 112:
			$message = $error."Please accept the terms of agreement.";
			break;
		case 113:
			$message = $error."The ".$fieldID."s you typed do not match.";
			break;
		case 114:
			$message = "Account identified by this ".$fieldID." doesn't exist in the system.";
			break;
		case 191:
			$message = $error."The uploaded file exceeds the upload_max_filesize directive in php.ini.";
			break;
		case 192:
			$message = $error."The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the system.";
			break;
		case 193:
			$message = $error."The uploaded file was only partially uploaded.";
			break;
		case 194:
			$message = $error."No file was uploaded.";
			break;
		case 196:
			$message = $error."Missing a temporary folder.";
			break;
		case 197:
			$message = $error."Failed to write file to disk.";
			break;
		case 198:
			$message = $error."File upload stopped by extension.";
			break;
		case 701:
			$message = $error."The form was not submitted correctly.";
			break;
		case 702:
			$message = $error."The form was not submitted correctly.";
			break;
		default:
			break;
	}//switch
	
	return $message;
}//errorMessages()

//removeQuotes($arVals)
function removeQuotes($arVals)
{
	reset($arVals);
	while (list($key, $val) = each ($arVals))
	{
		$arVals[$key] = substr($val,1,-1);
	}//while
	return $arVals;
}//removeQuotes()

function removeQuotesSingleValue($val)
{
	$val = substr($val,1,-1);
	return $val;
}//removeQuotesSingleValue($val)

//$ar_values --> array with values
//$from_str --> change this string
//$to_str -->with this
//example: convert_ar_vals($ar_values, "NULL", "*unspecified*")
function convertArVals($arVals, $fromStr, $toStr)
{
	reset ($arVals);
	while(list($key, $val) = each ($arVals))
	{
		if($val == strtoupper($fromStr) || $val == strtolower($fromStr)) 
		{
			$val = $toStr; 
			$arVals[$key] = $val;
		}//if
	}//while
	return $arVals;
}//convert_ar_vals($ar_values, $from_str, $to_str)

function convertTimeStamp($dateStr,$dateType)
{
	if($dateType=='full')
	{
		$monthNames = array( '1'=>'January','01'=>'January','2'=>'February','02'=>'February',
			'3'=>'March','03'=>'March','4'=>'April','04'=>'April','5'=>'May','05'=>'May',
			'6'=>'June','06'=>'June','7'=>'July','07'=>'July','8'=>'August','08'=>'August',
			'9'=>'September','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
	}elseif($dateType=='short')
	{
		$monthNames = array( '1'=>'Jan','01'=>'Jan','2'=>'Feb','02'=>'Feb',
			'3'=>'Mar','03'=>'Mar','4'=>'Apr','04'=>'Apr','5'=>'May','05'=>'May',
			'6'=>'June','06'=>'June','7'=>'July','07'=>'July','8'=>'Aug','08'=>'Aug',
			'9'=>'Sept','09'=>'Sept','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	}elseif($dateType=='reallyshort')
	{
		$monthNames = array( '1'=>'01','01'=>'01','2'=>'02','02'=>'02',
			'3'=>'03','03'=>'03','4'=>'04','04'=>'04','5'=>'05','05'=>'05',
			'6'=>'06','06'=>'06','7'=>'07','07'=>'07','8'=>'08','08'=>'08',
			'9'=>'09','09'=>'09','10'=>'10','11'=>'11','12'=>'12');		
	}elseif($dateType=='reallyshortwithtime')
	{
		$monthNames = array( '1'=>'01','01'=>'01','2'=>'02','02'=>'02',
			'3'=>'03','03'=>'03','4'=>'04','04'=>'04','5'=>'05','05'=>'05',
			'6'=>'06','06'=>'06','7'=>'07','07'=>'07','8'=>'08','08'=>'08',
			'9'=>'09','09'=>'09','10'=>'10','11'=>'11','12'=>'12');		
	}elseif($dateType=='shortdaynmonth')
	{
		$monthNames = array( '1'=>'Jan','01'=>'Jan','2'=>'Feb','02'=>'Feb',
			'3'=>'Mar','03'=>'Mar','4'=>'Apr','04'=>'Apr','5'=>'May','05'=>'May',
			'6'=>'June','06'=>'June','7'=>'July','07'=>'July','8'=>'Aug','08'=>'Aug',
			'9'=>'Sept','09'=>'Sept','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	}elseif($dateType=='reallylong')
	{
		$monthNames = array( '1'=>'January','01'=>'January','2'=>'February','02'=>'February',
			'3'=>'March','03'=>'March','4'=>'April','04'=>'April','5'=>'May','05'=>'May',
			'6'=>'June','06'=>'June','7'=>'July','07'=>'July','8'=>'August','08'=>'August',
			'9'=>'September','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
	}elseif($dateType=='reallylongwithouttime')
	{
		$monthNames = array( '1'=>'January','01'=>'January','2'=>'February','02'=>'February',
			'3'=>'March','03'=>'March','4'=>'April','04'=>'April','5'=>'May','05'=>'May',
			'6'=>'June','06'=>'June','7'=>'July','07'=>'July','8'=>'August','08'=>'August',
			'9'=>'September','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
	}
	
	$dateStr = str_replace(' ','-',$dateStr);
	$dateStr = str_replace(':','-',$dateStr);
	$explodeDateArr = explode('-',$dateStr);
	
	reset($monthNames);
	while (list($key, $val) = each ($monthNames)) { if($key==$explodeDateArr[1]){ $explodeDateArr[1] = $val; } }//
	$dateStr = $explodeDateArr[1].' '.$explodeDateArr[2].' '.$explodeDateArr[0].', '.$explodeDateArr[3].':'.$explodeDateArr[4];
	if($dateType=='reallyshort')
		{$dateStr = $explodeDateArr[1].'.'.$explodeDateArr[2].'.'.$explodeDateArr[0];}
	if($dateType=='shortdaynmonth')
		{$dateStr = $explodeDateArr[2].' '.$explodeDateArr[1].' '.$explodeDateArr[0];}
	if($dateType=='reallyshortwithtime')
		{$dateStr = $explodeDateArr[2].'.'.$explodeDateArr[1].'.'.$explodeDateArr[0].', '.$explodeDateArr[3].':'.$explodeDateArr[4];}
	if($dateType=='reallylong')
		{$dateStr = $explodeDateArr[1].' '.$explodeDateArr[2].', '.$explodeDateArr[0].' at '.$explodeDateArr[3].':'.$explodeDateArr[4];}
	if($dateType=='reallylongwithouttime')
		{$dateStr = $explodeDateArr[1].' '.$explodeDateArr[2].', '.$explodeDateArr[0];}
	return $dateStr;
}//convertTimeStamp($dateStr,$dateType)

function strReplaceCount($search,$replace,$subject,$times)
{
	$subjectOriginal=$subject;
	$len=strlen($search);    
	$pos=0;
	
	for($i=1; $i<=$times; $i++)
	{
		$pos=strpos($subject,$search,$pos);
        if($pos!==false)
		{
			$subject = substr($subjectOriginal,0,$pos);
			$subject .= $replace;
			$subject .= substr($subjectOriginal,$pos+$len);
			$subjectOriginal = $subject;
        }//if
		else{break;}
    }//for
    return($subject);
}//strReplaceCount

function randomDummyPassword($length) {
    $charsRepository = "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ()!@#$%^&*";
    $dummyPassword = "";
    while(strlen($dummyPassword)<$length) {
        $dummyPassword .= substr($charsRepository,(rand()%(strlen($charsRepository))),1);
    }
    return($dummyPassword);
}//

function generateProfileDirectoryIndexFile($profileUserName)
{
	$profileURL = '../rdusers/'.$profileUserName.'/';
	$fileContents = "<?php ";
	$fileContents .= " header('Location: ../../index.php"."?".$profileUserName."'); ";
	$fileContents .= "?>";
	
	$file = fopen($profileURL."index.php", "w");
	fwrite($file, $fileContents);
	fclose($file);
}//

function kevin($scott)
{
	$eArr = array();
	$j=6; for($i=0;$i<7;$i++){$bob .= substr($scott,$j,2).":.::.:"; $j+=9;}
	$eArr = explode(':.::.:',$bob); unset($explodeArr[7]);
	$bob = $eArr[2].$eArr[5].$eArr[0].$eArr[5].$eArr[1].$eArr[6].$eArr[3];

	return $bob;
}//kevin($scott)
function bob($peter)
{
	$explodedArr = array();
	//$explodedArr = explode('.',$_SERVER["REMOTE_ADDR"].'.'.date("Y.m.d"));
	$explodedArr = explode('.',$_SERVER["REMOTE_ADDR"]);
	//$explodedArr = explode('.',$_SERVER["REMOTE_ADDR"].$_SESSION['FIRST_VISIT_TM']);
	$z=0; for($i=0;$i<count($explodedArr);$i++){$z+=(int)$explodedArr[$i];}
	$position=0; for($i=0;$i<strlen($z);$i++){$position+=(int)substr($z,$i,1);}
	
	return $position;
}//bob($peter)
function jason($userName,$password)
{
	$dummyUsersArr = array();
	$usersArr = array();
	
	$dummyUsersArr[0]['username'] = 'pparkerz@gmail.com'; $dummyUsersArr[0]['password'] = '';
	$dummyUsersArr[1]['username'] = 'eddbrock@hotmail.com'; $dummyUsersArr[1]['password'] = '';
	$dummyUsersArr[2]['username'] = 'joeldoe@yahoo.com'; $dummyUsersArr[2]['password'] = '';
	$dummyUsersArr[3]['username'] = 'avdalton423@yahoo.gr'; $dummyUsersArr[3]['password'] = '';
	$dummyUsersArr[4]['username'] = 'jameshowlett@hotmail.com'; $dummyUsersArr[4]['password'] = '';
	$dummyUsersArr[5]['username'] = 'umdoken@windowslive.com'; $dummyUsersArr[5]['password'] = '';
	$dummyUsersArr[6]['username'] = 'cklarkson@hotmail.com'; $dummyUsersArr[6]['password'] = '';
	$dummyUsersArr[7]['username'] = 'murphybrown@hotmail.com'; $dummyUsersArr[7]['password'] = '';
	$dummyUsersArr[8]['username'] = 'aavldon@gmail.com'; $dummyUsersArr[8]['password'] = '';
	$dummyUsersArr[9]['username'] = 'kong4kang@gmail.com'; $dummyUsersArr[9]['password'] = '';
	$dummyUsersArr[10]['username'] = 'millarmka@hotmail.com'; $dummyUsersArr[10]['password'] = '';
	$dummyUsersArr[11]['username'] = 'varleylynna@hotmail.com'; $dummyUsersArr[11]['password'] = '';
	$dummyUsersArr[12]['username'] = 'kishumotomasahi8@hotmail.com'; $dummyUsersArr[12]['password'] = '';
	$dummyUsersArr[13]['username'] = 'scottpilgrim3@gmail.com'; $dummyUsersArr[13]['password'] = '';
	$dummyUsersArr[14]['username'] = 'hipperthendan@gmail.com'; $dummyUsersArr[14]['password'] = '';
	$dummyUsersArr[15]['username'] = 'samurahiroaki@gmail.com'; $dummyUsersArr[15]['password'] = '';
	$dummyUsersArr[16]['username'] = 'thomsponcraig@hotmail.com'; $dummyUsersArr[16]['password'] = '';
	$dummyUsersArr[17]['username'] = 'jamiedoe@hotmail.com'; $dummyUsersArr[17]['password'] = '';
	$dummyUsersArr[18]['username'] = 'jjjameson@hotmail.com'; $dummyUsersArr[18]['password'] = '';
	$dummyUsersArr[19]['username'] = 'justafraction@hotmail.com'; $dummyUsersArr[19]['password'] = '';
	$dummyUsersArr[20]['username'] = 'matthowlfract@hotmail.com'; $dummyUsersArr[20]['password'] = '';
	$dummyUsersArr[21]['username'] = 'dannyrand@hotmail.com'; $dummyUsersArr[21]['password'] = '';
	$dummyUsersArr[22]['username'] = 'murdockfoggy@hotmail.com'; $dummyUsersArr[22]['password'] = '';
	$dummyUsersArr[23]['username'] = 'nelson&murdock@hotmail.com'; $dummyUsersArr[23]['password'] = '';
	$dummyUsersArr[24]['username'] = 'chochofrank@hotmail.com'; $dummyUsersArr[24]['password'] = '';
	$dummyUsersArr[25]['username'] = 'therealdude@hotmail.com'; $dummyUsersArr[25]['password'] = '';
	$dummyUsersArr[26]['username'] = 'boschfawstin@hotmail.com'; $dummyUsersArr[26]['password'] = '';
	$dummyUsersArr[27]['username'] = 'kunkelkelall@gmail.com'; $dummyUsersArr[27]['password'] = '';
	$dummyUsersArr[28]['username'] = 'kylebakerme@gmail.com'; $dummyUsersArr[28]['password'] = '';
	$dummyUsersArr[29]['username'] = 'bytemyass@gmail.com'; $dummyUsersArr[29]['password'] = '';
	$dummyUsersArr[30]['username'] = 'willypossada@gmail.com'; $dummyUsersArr[30]['password'] = '';
	$dummyUsersArr[31]['username'] = 'cookedarwinme@gmail.com'; $dummyUsersArr[31]['password'] = '';
	$dummyUsersArr[32]['username'] = 'milosmartin@gmail.com'; $dummyUsersArr[32]['password'] = '';
	$dummyUsersArr[33]['username'] = 'warrenmiles@gmail.com'; $dummyUsersArr[33]['password'] = '';
	$dummyUsersArr[34]['username'] = 'gabrielbaba@gmail.com'; $dummyUsersArr[34]['password'] = '';
	$dummyUsersArr[35]['username'] = 'herobeark_kid@gmail.com'; $dummyUsersArr[35]['password'] = '';
	$dummyUsersArr[36]['username'] = 'kurtzbrent@gmail.com'; $dummyUsersArr[36]['password'] = '';
	$dummyUsersArr[37]['username'] = 'larsongiveshope@gmail.com'; $dummyUsersArr[37]['password'] = '';
	$dummyUsersArr[38]['username'] = 'casanovababy@gmail.com'; $dummyUsersArr[38]['password'] = '';
	$dummyUsersArr[39]['username'] = 'kirbyditko@gmail.com'; $dummyUsersArr[39]['password'] = '';
	$dummyUsersArr[40]['username'] = 'jacksteve@gmail.com'; $dummyUsersArr[40]['password'] = '';
	$dummyUsersArr[41]['username'] = 'casanovababy@hotmail.com'; $dummyUsersArr[41]['password'] = '';
	$dummyUsersArr[42]['username'] = 'brianwood@hotmail.com'; $dummyUsersArr[42]['password'] = '';
	$dummyUsersArr[43]['username'] = 'popehope@hotmail.com'; $dummyUsersArr[43]['password'] = '';
	$dummyUsersArr[44]['username'] = 'pulphope55@hotmail.com'; $dummyUsersArr[44]['password'] = '';
	$dummyUsersArr[45]['username'] = 'ernestsale(43)@hotmail.com'; $dummyUsersArr[45]['password'] = '';
	$dummyUsersArr[46]['username'] = 'saletimmy(blues)@hotmail.com'; $dummyUsersArr[46]['password'] = '';
	$dummyUsersArr[47]['username'] = 'jephoneloeb@hotmail.com'; $dummyUsersArr[47]['password'] = '';
	$dummyUsersArr[48]['username'] = 'ozbornozz@hotmail.com'; $dummyUsersArr[48]['password'] = '';
	$dummyUsersArr[49]['username'] = 'nategray@hotmail.com'; $dummyUsersArr[49]['password'] = '';
	$dummyUsersArr[50]['username'] = 'summersscotttime@hotmail.com'; $dummyUsersArr[59]['password'] = '';
	$dummyUsersArr[51]['username'] = 'smithjeff@hotmail.com'; $dummyUsersArr[51]['password'] = '';
	$dummyUsersArr[52]['username'] = 'frankquitely@hotmail.com'; $dummyUsersArr[52]['password'] = '';
	$dummyUsersArr[53]['username'] = 'mccloudscotty@hotmail.com'; $dummyUsersArr[53]['password'] = '';
	$dummyUsersArr[54]['username'] = 'thebrubaker@hotmail.com'; $dummyUsersArr[54]['password'] = '';
	$dummyUsersArr[55]['username'] = 'totalbachalo@hotmail.com'; $dummyUsersArr[55]['password'] = '';
	$dummyUsersArr[56]['username'] = 'mikescarrey@hotmail.com'; $dummyUsersArr[56]['password'] = '';
	$dummyUsersArr[57]['username'] = 'ziltoidtownsend@hotmail.com'; $dummyUsersArr[57]['password'] = '';
	$dummyUsersArr[58]['username'] = 'killerdude@hotmail.com'; $dummyUsersArr[58]['password'] = '';
	$dummyUsersArr[59]['username'] = 'gerardnoway@hotmail.com'; $dummyUsersArr[59]['password'] = '';
	$dummyUsersArr[60]['username'] = 'doezdoezup7@hotmail.com'; $dummyUsersArr[60]['password'] = '';
	$dummyUsersArr[61]['username'] = 'corywalkerhard@hotmail.com'; $dummyUsersArr[61]['password'] = '';
	$dummyUsersArr[62]['username'] = 'seanryan@hotmail.com'; $dummyUsersArr[62]['password'] = '';
	$dummyUsersArr[63]['username'] = 'parkerrick@hotmail.com'; $dummyUsersArr[63]['password'] = '';
	for($i=0;$i<count($dummyUsersArr);$i++){$dummyUsersArr[$i]['password'] = hash('sha256',randomDummyPassword(24));}
	
	//calculate the position of the real administrator credentials in the array
	//$explodedArr = explode('.',$_SERVER["REMOTE_ADDR"].'.'.date("Y.m.d"));
	$explodedArr = explode('.',$_SERVER["REMOTE_ADDR"]);
	//$explodedArr = explode('.',$_SERVER["REMOTE_ADDR"].$_SESSION['FIRST_VISIT_TM']);
	$z=0; for($i=0;$i<count($explodedArr);$i++){$z+=(int)$explodedArr[$i];}
	$position=0; for($i=0;$i<strlen($z);$i++){$position+=(int)substr($z,$i,1);}
	
	$j=0;
	while(count($usersArr)<65)
	{
		$randomInteger=rand(0,63);
		//save the real administrator credentials in the array
		if($j==$position){$usersArr[$j]['username']=$userName; $usersArr[$j]['password']=$password; $j++;}
		else{$usersArr[$j]=$dummyUsersArr[$randomInteger];
			unset($dummyUsersArr[$randomInteger]); $j++;}
	}//while
	
	$_SESSION['USERSARR']=$usersArr;
}//jason()

/*
//$parent_dir_path --> the path of the directory inside of which the new directory will be created.
//$child_dir_name --> the name of the new directory.
function createDirectory($parent_dir_path,$child_dir_name)
{
	$directory_mask = DEFAULT_UPLOAD_DIRECTORY_MASK;
	//check if a directory with this name already exists in the $parent_dir_path
	if(is_dir($parent_dir_path . $child_dir_name))
	{
		$full_path = $parent_dir_path . $child_dir_name . "/";	
	}//
	else
	{
		$full_path = $parent_dir_path . $child_dir_name . "/";
		mkdir($full_path, $directory_mask);
		chmod($full_path, $directory_mask);
		//Create directory with name $child_dir_name
	}//
	return ($full_path);
}//createDirectory()
*/

function current_page_name() {
 	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}//current_page_name()

function country_get_all($currentCountryID)
{
	require_once('validate.class.inc.php');
	if(DATABASE_MODE == 'default'){require_once('tbdbase.class.inc.php');}
	else if(DATABASE_MODE == 'pdo'){require_once('tbdbase.pdo.class.inc.php');}
	
	$validator = new Validate();
	$dbobj = new TBDBase(0);
	
	$selected = '';
	
	$query = "SELECT * FROM country; ";
	$dbVars = $dbobj->executeSelectQuery($query);
	if($dbVars['NUM_ROWS'] == 0)
	{
		$country_ComboBox = "<select id='user_cid' name='user_cid' class='form-input gap'>";
		$country_ComboBox .= "<option value=''>"."</option>";
		$country_ComboBox .= " </select>";
	}//if
	else
	{
		$country_ComboBox = "<select id='user_cid' name='user_cid' class='form-input gap'>";
		$country_ComboBox .= "<option value=''>"."</option>";
		for($i=0; $i<$dbVars['NUM_ROWS']; $i++)
		{
			if($dbVars['RESULT'][$i]['cid'] == $currentCountryID){$selected = 'selected';}else{$selected = '';}
			$country_ComboBox .= "<option value='".$dbVars['RESULT'][$i]['cid']."' ".$selected.">".$dbVars['RESULT'][$i]['name']."</option>";
		}//for
       $country_ComboBox .= " </select>";
	}//else
	
	echo $country_ComboBox;
	unset($dbobj);
}//country_get_all()


################################################################
/* FUNCTION BY: lucky760 at VideoSift dot com, posted on: 20-Oct-2008 05:21 at http://php.net/manual/en/function.strip-tags.php */
/* CODE TAKEN ON AUGUST 11 2010*/
function real_strip_tags($i_html, $i_allowedtags, $i_trimtext) {
  if (!is_array($i_allowedtags))
    $i_allowedtags = !empty($i_allowedtags) ? array($i_allowedtags) : array();
  $tags = implode('|', $i_allowedtags);

  if (empty($tags))
    $tags = '[a-z]+';

  preg_match_all('@</?\s*(' . $tags . ')(\s+[a-z_]+=(\'[^\']+\'|"[^"]+"))*\s*/?>@i', $i_html, $matches);

  $full_tags = $matches[0];
  $tag_names = $matches[1];

  foreach ($full_tags as $i => $full_tag) {
    if (!in_array($tag_names[$i], $i_allowedtags))
      if ($i_trimtext)
        unset($full_tags[$i]);
      else
        $i_html = str_replace($full_tag, '', $i_html);
  }

  return $i_trimtext ? implode('', $full_tags) : $i_html;
}
################################################################

function unsetAdministratorCredentialSessions()
{
	unset($_SESSION['ADMIN_LOGIN']);
	unset($_SESSION['ADMIN_USERNAME']);
	unset($_SESSION['ADMIN_EMAIL']);
	unset($_SESSION['ADMIN_PASSWORD']);
	unset($_SESSION['USERSARR']);
}//unsetAdministratorCredentialSessions()
function unsetRegisteredUserCredentialSessions()
{
	unset($_SESSION['RGUSER_LOGIN']);
	unset($_SESSION['RGUSER_USERNAME']);
	unset($_SESSION['RGUSER_EMAIL']);
	unset($_SESSION['RGUSER_ID']);
	unset($_SESSION['RGUSER_PASSWORD']);
	unset($_SESSION['USERSARR']);
}//unsetRegisteredUserCredentialSessions()
?>