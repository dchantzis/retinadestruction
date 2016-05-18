<?php
function findMaxAllowFileSize($fileType)
{
	$maxFileSize = NULL;
	
	switch($fileType)
	{
		case 'image':
			$maxFileSize = IMAGES_MAX_FILESIZE;
			break;
		case 'video':
			//$maxFileSize = VIDEO_MAX_FILESIZE;
			break;
		case 'audio':
			//$maxFileSize = AUDIO_MAX_FILESIZE;
			break;
		default:
			$maxFileSize = DEFAULT_MAX_FILESIZE;
			break;
	}//switch
	return $maxFileSize;
}//findMaxAllowFileSize($fileType)


function uploadToFileserver($fieldName, $uploadDirPath, $partialFileName, $fileType, $defineValsArr)
{
	$file = array();
	$fileType = strtolower($fileType);

	$imageTypeOption = $defineValsArr['imagetype'];
	switch($imageTypeOption)
	{
		case 'regular':
			$fullRezWidth = $defineValsArr['fullRezWidth'];
			$videoPlayerWidth = $defineValsArr['videoPlayerWidth'];
			$resizeOption = $defineValsArr['resizeOption'];
			break;
		case 'albumcover':
			$coverImageWidth = $defineValsArr['imagewidth'];
			$coverImageHeight = $defineValsArr['imageheight'];
			break;
		case 'usercover':
			$coverImageWidth = $defineValsArr['imagewidth'];
			$coverImageHeight = $defineValsArr['imageheight'];
			break;
		case 'wallpaper':
			$designImageWidth = $defineValsArr['imagewidth'];
			$designImageHeight = $defineValsArr['imageheight'];
			break;
		case 'logo':
			$designImageWidth = $defineValsArr['imagewidth'];
			$designImageHeight = $defineValsArr['imageheight'];
			break;
	}//switch

	if($_FILES[$fieldName]['size'] > 0)
	{
		$maxFileSize = findMaxAllowFileSize($fileType);
		if($_FILES[$fieldName]['size'] > $maxFileSize){ $file['error'] = 192; }
		else
		{
			if($fileType == 'image'){
				if(strtolower(PRESERVE_ORIGINAL_IMAGE_FILETYPE) == 'false')
					{$_FILES[$fieldName]['name'] = removeExtension($_FILES[$fieldName]['name']) . '.' . UPLOADED_IMAGES_FILETYPE;}
			}//if
			
			$file['filename'] = strtolower(str_replace(' ','',$_FILES[$fieldName]['name']));
			$file['tmpname'] = $_FILES[$fieldName]['tmp_name'];
			$file['filesize'] = $_FILES[$fieldName]['size'];
			$file['mimetype'] = $_FILES[$fieldName]['type'];
		
			$tempFieldNumber = explode('_',$fieldName);
			$tempFieldNumber = $tempFieldNumber[1];

			$generatedFileName = '';
			//$q=6; for($z=0;$z<7;$z++){$generatedFileName.=substr($_FILES[$fieldName]['name'],$q,2);$q+=9;}
				
			/*
			//if we want the name of the file to be: 'timestamp'+'filename', use the following
			$timestamp = date("Ymd") . date("Hisu");
			$file['fileurl'] = $timestamp . $partialFileName . strtolower($file['filename']);
			*/
			//if we want the name of the file to be: 'timestamp', use the following
			$explodedArr = explode('.',$file['filename']);
			$fileExtension = $explodedArr[count($explodedArr)-1];
			$timestamp = date("Ymd") . date("His").$generatedFileName . $tempFieldNumber;
			$file['fileurl'] = $timestamp . '.' . $fileExtension;
			$file['filename'] = $file['fileurl'];
		
			switch($fileType)
			{
				case "image":
					switch($imageTypeOption)
					{
						case 'regular':
						
							$uploadfilefullresolution = createDirectory($uploadDirPath,IMAGES_FOLDER_FULL_RESOLUTION) . '/' . $file['fileurl'];
							$uploadfilethumbnail = createDirectory($uploadDirPath,IMAGES_FOLDER_THUMBNAILS) . '/' . $file['fileurl'];
							$uploadfilelargethumbnail = createDirectory($uploadDirPath,IMAGES_FOLDER_LARGE_THUMBNAILS) . '/' . $file['fileurl'];
							
							$image = openImage($file['tmpname']);
							//create the full resolution version of the image
							$imagefullresolution = imageResize($image,'fullresolution',$defineValsArr);
							$uploadfilefullresolution = removeExtension($uploadfilefullresolution);
							//create the thumbnail version of the image
							$imagethumbnail = imageResize($image,'thumbnail',$defineValsArr);
							$uploadfilethumbnail = removeExtension($uploadfilethumbnail);
							//create the large thumbnail version of the image
							$imagelargethumbnail = imageResize($image,'largethumbnail',$defineValsArr);
							$uploadfilelargethumbnail = removeExtension($uploadfilelargethumbnail);
							
							if(strtolower(PRESERVE_ORIGINAL_IMAGE_FILETYPE) == 'true'){$tmp = findFileExtension($file['filename']);}//do nothing upload image as normal file
							elseif(strtolower(PRESERVE_ORIGINAL_IMAGE_FILETYPE) == 'false'){$tmp = ''; $file['fileurl'] = removeExtension($file['fileurl']).'.'.UPLOADED_IMAGES_FILETYPE; }//elseif
							
							if(uploadImage($imagefullresolution,$uploadfilefullresolution,$tmp)){}//ALL OK //do nothing
								else{$file['error']=110;}					
							if(uploadImage($imagethumbnail,$uploadfilethumbnail,$tmp)){}//ALL OK//do nothing
								else{$file['error']=110;}
							if(uploadImage($imagelargethumbnail,$uploadfilelargethumbnail,$tmp)){}//ALL OK//do nothing
								else{$file['error']=110;}
							
							imagedestroy($imagefullresolution);
							imagedestroy($imagethumbnail);
							imagedestroy($imagelargethumbnail);
							$file['imageorientation'] = $_SESSION['imageOrientation']; unset($_SESSION['imageOrientation']);
							return $file;
						
							break;
						case 'albumcover':
							$uploadfilefullresolution = $uploadDirPath.$file['fileurl'];
							
							$image = openImage($file['tmpname']);
							//create the full resolution version of the image
							$imagefullresolution = imageResize($image,'fullresolution',$defineValsArr);
							$uploadfilefullresolution = removeExtension($uploadfilefullresolution);
							
							if(strtolower(PRESERVE_ORIGINAL_IMAGE_FILETYPE) == 'true')
								{$tmp = findFileExtension($file['filename']);}//do nothing upload image as normal file
							elseif(strtolower(PRESERVE_ORIGINAL_IMAGE_FILETYPE) == 'false')
								{$tmp = ''; $file['fileurl'] = removeExtension($file['fileurl']).'.'.UPLOADED_IMAGES_FILETYPE; }//elseif
							
							if(uploadImage($imagefullresolution,$uploadfilefullresolution,$tmp)){}//ALL OK //do nothing
								else{$file['error']=110;}							
							imagedestroy($imagefullresolution);
							$file['imageorientation'] = $_SESSION['imageOrientation']; unset($_SESSION['imageOrientation']);
							return $file;
							
							break;
						case 'usercover':
							$uploadfilefullresolution = $uploadDirPath.$file['fileurl'];
							
							$image = openImage($file['tmpname']);
							//create the full resolution version of the image
							$imagefullresolution = imageResize($image,'fullresolution',$defineValsArr);
							$uploadfilefullresolution = removeExtension($uploadfilefullresolution);
							
							if(strtolower(PRESERVE_ORIGINAL_IMAGE_FILETYPE) == 'true')
								{$tmp = findFileExtension($file['filename']);}//do nothing upload image as normal file
							elseif(strtolower(PRESERVE_ORIGINAL_IMAGE_FILETYPE) == 'false')
								{$tmp = ''; $file['fileurl'] = removeExtension($file['fileurl']).'.'.UPLOADED_IMAGES_FILETYPE; }//elseif
							
							if(uploadImage($imagefullresolution,$uploadfilefullresolution,$tmp)){}//ALL OK //do nothing
								else{$file['error']=110;}							
							imagedestroy($imagefullresolution);
							$file['imageorientation'] = $_SESSION['imageOrientation']; unset($_SESSION['imageOrientation']);
							return $file;
							
							break;
						case 'wallpaper':
							$uploadfilefullresolution = $uploadDirPath.'wallpaper.png';
							$image = openImage($file['tmpname']);
							//create the full resolution version of the image
							$imagefullresolution = imageResize($image,'fullresolution',$defineValsArr);
							$uploadfilefullresolution = removeExtension($uploadfilefullresolution);
							$tmp = 'png'; $file['fileurl'] = removeExtension($file['fileurl']).'.'.'png';

							if(uploadImage($imagefullresolution,$uploadfilefullresolution,$tmp)){}//ALL OK //do nothing
								else{$file['error']=110;}							
							imagedestroy($imagefullresolution);
							return $file;
							
							break;
						case 'logo':
							$uploadfilefullresolution = $uploadDirPath.'logo.png';
							$image = openImage($file['tmpname']);
							//create the full resolution version of the image
							$imagefullresolution = imageResize($image,'fullresolution',$defineValsArr);
							$uploadfilefullresolution = removeExtension($uploadfilefullresolution);
							$tmp = 'png'; $file['fileurl'] = removeExtension($file['fileurl']).'.'.'png';

							if(uploadImage($imagefullresolution,$uploadfilefullresolution,$tmp)){}//ALL OK //do nothing
								else{$file['error']=110;}							
							imagedestroy($imagefullresolution);
							return $file;
							
							break;
							
						default:
							break;
					}//switch

					break;
				case "video":
					$uploadfile = $uploadDirPath . '/' . $file['fileurl'];
					if (move_uploaded_file($file['tmpname'], $uploadfile)) {} //ok! file uploaded successfully.
					else { $file['error']=110; }//else //error. file not uploaded.
					chmod($uploadfile,DEFAULT_UPLOAD_FILES_MASK);
					return $file; //array
					break;
				case "audio":
					$uploadfile = $uploadDirPath . '/' . $file['fileurl'];
					if (move_uploaded_file($file['tmpname'], $uploadfile)) {} //ok! file uploaded successfully.
					else { $file['error']=110; }//else //error. file not uploaded.
					chmod($uploadfile,DEFAULT_UPLOAD_FILES_MASK);
					return $file; //array
					break;
				default:
					//this is used to upload filetypes that are not images.
					$uploadfile = $uploadDirPath . '/' . $file['fileurl'];
					if (move_uploaded_file($file['tmpname'], $uploadfile)) {} //ok! file uploaded successfully.
					else { $file['error']=110; }//else //error. file not uploaded.
					chmod($uploadfile,DEFAULT_UPLOAD_FILES_MASK);
					return $file; //array
					break;
			}//switch

		}//if($_FILES[$field_name]['size'] > $maxFileSize)
	}//if($_FILES[$fieldName]['size'] > 0)
	else{ $file['error']=103; return $file; }
}//uploadToFileserver($fieldName, $uploadDirPath, $partialFileName, $fileType)

//function that deletes files from fileserver
//$page_id --> page to redirect if something fails
function deleteFromFileserver($fileURL,$fileType,$profileUsername)
{
	$userDirectory = TBUSERS_DIR.''.$profileUsername;
	$dirHandle = @opendir($userDirectory);
	if(!$dirHandle){return 0;}
	
	if($fileURL != "" )
	{
		switch($fileType)
		{
			case 'image':
				if(file_exists($userDirectory."/portfolioimages/".$fileURL))
				{
					unlink ($userDirectory."/portfolioimages/".$fileURL);
					return $userDirectory;
				}else{return $userDirectory;}
				break;
			case 'video':
				if(file_exists($userDirectory."/video/".$fileURL))
				{
					unlink ($userDirectory."/video/".$fileURL);
					return $userDirectory;
				}else{return $userDirectory;}
				break;
			case 'audio':
				if(file_exists($userDirectory."/soundtrack/".$fileURL))
				{
					unlink ($userDirectory."/soundtrack/".$fileURL);
					return $userDirectory;
				}else{return $userDirectory;}
				break;
			default:
				break;
		}

	}//if
	else{return 0;}
}//deleteFromFileserver

//Upload a file to database
//$page_id --> page to redirect if something fails
function upload_to_database($field_name,$page_id)
{
	global $paper_upload_max_filesize; //set in sessioninitinc.php

	if($_FILES[$field_name]['size'] > 0)
	{
		if($_FILES[$field_name]['size'] > IMAGES_MAX_FILESIZE){ redirects($page_id,'?flg=109'); }//echo "error. file too large.";
		else
		{
			//OK!
			$file['filename'] = $_FILES[$field_name]['name'];
			$file['tmpname'] = $_FILES[$field_name]['tmp_name'];
			$file['filesize'] = $_FILES[$field_name]['size'];
			$file['filetype'] = $_FILES[$field_name]['type'];

			$fp = fopen($file['tmpname'], 'r');
			$file['filecontent'] = fread($fp, $file['filesize']);
			$file['filecontent'] = addslashes($file['filecontent']);
			fclose($fp);

			if(!get_magic_quotes_gpc()){ $file['filename'] = addslashes($file['filename']);}//if
			
			return $file; //array
		}//else		
	}//if
	else
	{
		//echo "no file was selected";
		Redirects($page_id,"?flg=103","");
	}//else
	
}//upload_to_database($field_name,$page_id)



function uploadImage($image,$uploadfile,$uploadtype)
{
	if($uploadtype == ''){$tmp = UPLOADED_IMAGES_FILETYPE;}
	else{$tmp = $uploadtype;}
	
	switch(strtolower($tmp))
	{
		case 'jpeg':
		case 'jpg':
			$uploadfile = $uploadfile.'.jpg';
			if(imagejpeg($image,$uploadfile,75)){return 1;}//image uploaded successfully
			else{return 0;}//error
			break;
		case 'gif':
			$uploadfile = $uploadfile.'.gif';
			if(imagegif($image,$uploadfile)){return 1;}//image uploaded successfully
			else{return 0;}//error
			break;
		case 'png':
			$uploadfile = $uploadfile.'.png';
			if(imagepng($image,$uploadfile,2,'PNG_ALL_FILTERS')){return 1;}//image uploaded successfully
			else{return 0;}//error
			break;
		case 'gd':
			$uploadfile = $uploadfile.'.gd';
			if(imagegd($image,$uploadfile)){return 1;}//image uploaded successfully
			else{return 0;}//error
			break;
		case 'gd2':
			$uploadfile = $uploadfile.'.gd2';
			if(imagegd2($image,$uploadfile)){return 1;}//image uploaded successfully
			else{return 0;}//error
			break;
		case 'wbmp':
			$uploadfile = $uploadfile.'.wbmp';
			if(imagewbmp($image,$uploadfile )){return 1;}//image uploaded successfully
			else{return 0;}//error
			break;
		default:
			return 0;
			break;
	}//switch
	chmod($uploadfile,DEFAULT_UPLOAD_FILES_MASK);
}//uploadImage()

function openImage($file) {
	# JPEG:
	$image = @imagecreatefromjpeg($file);
	if ($image !== false) { return $image; }
	# GIF:
	$image = @imagecreatefromgif($file);
	if ($image !== false) { return $image; }
	# PNG:
	$image = @imagecreatefrompng($file);
	if ($image !== false) { return $image; }
	# GD File:
	$image = @imagecreatefromgd($file);
	if ($image !== false) { return $image; }
	# GD2 File:
	$image = @imagecreatefromgd2($file);
	if ($image !== false) { return $image; }
	# WBMP:
	$image = @imagecreatefromwbmp($file);
	if ($image !== false) { return $image; }
	# Try and load from string:
	$image = @imagecreatefromstring(file_get_contents($file));
	if ($image !== false) { return $image; }
	return false;
}//openImage()

//findFileExtension()
function findFileExtension($filename)
{
	$filename = strtolower($filename) ;
	$file_extension = split("[/\\.]", $filename) ;
	$n = count($file_extension)-1;
	$file_extension = $file_extension[$n];
	return $file_extension;
}//findFileExtension()

function removeExtension($str)
{ 
     $temp = strrchr($str, '.'); 
     if($temp != false) { $str = substr($str, 0, -strlen($temp));}
     return $str;
}//removeExtension()

function imageResize($image,$resize_type,$defineValsArr)
{
	$width = imagesx($image);
	$height = imagesy($image);
	$imageOrientation = 'vertical'; //default value

	$imageTypeOption = $defineValsArr['imagetype'];
	switch($imageTypeOption)
	{
		case 'regular':
			$fullRezWidth = $defineValsArr['fullRezWidth'];
			$videoPlayerWidth = $defineValsArr['videoPlayerWidth'];
			$resizeOption = $defineValsArr['resizeOption'];
			
			if($resize_type == 'fullresolution')
			{
				if(IMAGE_FULL_RESOLUTION_RESIZE == 'false')
				{
					if($width > $height){$val = imagesx($image);}
					else{$val = imagesy($image);}
				}
				else{$val=floatval($fullRezWidth);}
			}
			elseif($resize_type == 'thumbnail'){$val=floatval(IMAGES_FOLDER_THUMBNAILS_PIXELS);}
			elseif($resize_type == 'largethumbnail'){$val=floatval(IMAGES_FOLDER_LARGE_THUMBNAILS_PIXELS);}
			
			
			if($resize_type == 'fullresolution')
			{
				if(IMAGE_FULL_RESOLUTION_RESIZE == 'false')
				{
					if($width > $height) {$imageOrientation = 'horizontal'; $new_width = $val; $new_height = $height * ($new_width/$width); }//horizontal image
					else { $imageOrientation = 'vertical'; $new_height = $val; $new_width = $width * ($new_height/$height); }//vertical image
				}//if(IMAGE_FULL_RESOLUTION_RESIZE == 'false')
				else //RESIZE THE DAMN IMAGE
				{
					//if($width>=$val)
					if(($height>$fullRezWidth))
					{
						switch($resizeOption)
						{
							case '1':
								//THE WIDTH OF BOTH HORIZONTAL AND VERTICAL IMAGES IS GOING TO BE THE VALUE OF IMAGES_FULL_RESOLUTION_PIXELS
								$new_width = $val; $new_height = $height * ($new_width/$width);
								break;
							case '2':
								//THE HEIGHT OF BOTH HORIZONTAL AND VERTICAL IMAGES IS GOING TO BE THE VALUE OF IMAGES_FULL_RESOLUTION_PIXELS
								$new_height = $val; $new_width = $width * ($new_height/$height);
								break;
							case '3':
								//IF THE IMAGE IS HORIZONTAL, THEN THE HEIGHT IS GOING TO BE THE VALUE OF IMAGES_FULL_RESOLUTION_PIXELS
								//IF THE IMAGE IS VERTICAL, THEN THE WIDTH IS GOING TO BE THE VALUE OF IMAGES_FULL_RESOLUTION_PIXELS
								if($width > $height) //HORIZONTAL IMAGE
									{ $imageOrientation = 'horizontal'; $new_height = $val; $new_width = $width * ($new_height/$height); }
								else //VERTICAL IMAGE
									{ $imageOrientation = 'vertical'; $new_width = $val; $new_height = $height * ($new_width/$width); }
								break;
							default:
								//IF THE IMAGE IS HORIZONTAL, THEN THE HEIGHT IS GOING TO BE THE VALUE OF IMAGES_FULL_RESOLUTION_PIXELS
								//IF THE IMAGE IS VERTICAL, THEN THE WIDTH IS GOING TO BE THE VALUE OF IMAGES_FULL_RESOLUTION_PIXELS
								if($width > $height) //HORIZONTAL IMAGE
									{ $imageOrientation = 'horizontal'; $new_height = $val; $new_width = $width * ($new_height/$height); }//HORIZONTAL IMAGE
								else //VERTICAL IMAGE
									 { $imageOrientation = 'vertical'; $new_width = $val; $new_height = $height * ($new_width/$width); }//VERTICAL IMAGE
								break;
						}//switch(FULL_RESOLUTION_IMAGES_RESIZE_OPTION)
					}//if($width>=$val)
					else
					{
						if($width > $height) {$imageOrientation = 'horizontal';}
						else {$imageOrientation = 'vertical';}
							
						if($width>=$videoPlayerWidth)
						{
							$val = $videoPlayerWidth;
							$new_width = $val; $new_height = $height * ($new_width/$width);
						}//if($width>=VIDEO_PLAYER_PIXELS_WIDTH)
						else
						{
							//if the original image is smaller, then don't resize it upwards
							$new_width = $width; $new_height = $height * ($new_width/$width);
						}//else
					}//else
				}//else RESIZE THE DAMN IMAGE
				
				// Resample
				$_SESSION['imageOrientation'] = $imageOrientation;
				$image_resized = imagecreatetruecolor($new_width, $new_height);
				imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			}//if($resize_type == 'fullresolution')
			else
			{
				if(THUMBNAILS_TYPE == 'normal')
				{
					if($width > $height) { $new_width = $val; $new_height = $height * ($new_width/$width); }//horizontal image
					else { $new_height = $val; $new_width = $width * ($new_height/$height); }//vertical image
					// Resample
					$image_resized = imagecreatetruecolor($new_width, $new_height);
					imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				}//if(THUMBNAILS_TYPE == 'normal')
				elseif(THUMBNAILS_TYPE == 'square')
				{
					if($width>$height){ $new_width = ceil(($width-$height)/2); $width=$height; }
					else{ $new_height = ceil(($height-$width)/2); $height=$width;}
					// Resample
					$image_resized = imagecreatetruecolor($val, $val);
					imagecopyresampled($image_resized, $image, 0, 0, $new_width, $new_height, $val, $val, $width, $height);
				}//elseif(THUMBNAILS_TYPE == 'square')
			}//else if($resize_type != 'fullresolution')
		
			return $image_resized;
			break;
		case 'albumcover':
			
			$coverImageWidth = $defineValsArr['imagewidth'];
			$coverImageHeight = $defineValsArr['imageheight'];
						
			$val=floatval(300 * 0.7);
			$new_width = $val; $new_height = $height * ($new_width/$width);
			// RESIZE
			$image_resized = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			//CROP
			$cropWidth = $coverImageWidth * 0.9;
			$cropHeight = $coverImageHeight * 0.9;
			
			$x = ($new_width - $cropWidth) / 2.3;
			$y = ($new_height - $cropHeight) / 2.3;
			
			$image_cropped = imagecreatetruecolor($coverImageWidth, $coverImageHeight);
			imagecopyresampled($image_cropped, $image_resized, 0, 0, $x, $y, $coverImageWidth, $coverImageHeight, $cropWidth, $cropHeight);
			
			$imageOrientation = 'horizontal';
			$_SESSION['imageOrientation'] = $imageOrientation;
			return $image_cropped;
			break;
		case 'usercover':
			$coverImageWidth = IMAGES_AVATARS_PIXELS_WIDTH;
			$coverImageHeight = IMAGES_AVATARS_PIXELS_HEIGHT;
			
			if($width > $height)		
				$val=floatval(300 * 1);
			else
				{$val=floatval(300 * 1);}
			$new_width = $val; $new_height = $height * ($new_width/$width);
			// RESIZE
			$image_resized = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			//CROP
			$cropWidth = $coverImageWidth * 0.9;
			$cropHeight = $coverImageHeight * 0.9;

			$x = ($new_width - $cropWidth) / 2.3;
			$y = ($new_height - $cropHeight) / 2.3;
			
			$image_cropped = imagecreatetruecolor($coverImageWidth, $coverImageHeight);
			imagecopyresampled($image_cropped, $image_resized, 0, 0, $x, $y, $coverImageWidth, $coverImageHeight, $cropWidth, $cropHeight);
			
			$imageOrientation = 'horizontal';
			$_SESSION['imageOrientation'] = $imageOrientation;
			return $image_cropped;
			break;
		case 'wallpaper':
			$designImageWidth = $defineValsArr['imagewidth'];
			$designImageHeight = $defineValsArr['imageheight'];
			
			$val=floatval($designImageWidth);
			/*
			if($width > $height) {$imageOrientation = 'horizontal'; $new_width = $val; $new_height = $height * ($new_width/$width); }//horizontal image
			else { $imageOrientation = 'vertical'; $new_height = $val; $new_width = $width * ($new_height/$height); }//vertical image
			*/
			$new_width = $width; $new_height = $height * ($new_width/$width);
			
			$image_resized = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			return $image_resized;
			break;
		case 'logo':
			$designImageWidth = $defineValsArr['imagewidth'];
			$designImageHeight = $defineValsArr['imageheight'];
	
			$val=floatval($designImageWidth);
			$imageOrientation = 'horizontal'; $new_width = $val; $new_height = $height * ($new_width/$width);
			
			$image_resized = imagecreatetruecolor($new_width, $new_height);
			//$white = imagecolorallocate($image_resized, 0, 0, 0);
			//imagecolortransparent($image_resized, $white);
			imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			return $image_resized;

			break;
	}//switch


}//imageResize()

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

function fullDeleteDirAndContents($dirname)
{
	if (!file_exists($dirname)) {return false;}
	if (is_file($dirname)) {return unlink($dirname);}

	$dir = dir($dirname);
	while (false !== $entry = $dir->read()) {
		if ($entry == '.' || $entry == '..') {continue;}
		fullDeleteDirAndContents("$dirname/$entry");
	}//while
	
	$dir->close();
	return rmdir($dirname);
}//fullDeleteDirAndContents($path)
?>