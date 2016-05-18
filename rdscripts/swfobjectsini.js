	var swfu_1; //album images
	var swfu_2; //album cover
	var swfu_3; //profile cover
	var swfu_4; //blog post images
	var swfu_5; //cover page images
	var swfu_6; //wallpaper image
	var swfu_7; //logo image
	
	var albumImageUploadCounter = 0;
	function swfuFunction_album(username,email,albumid)
	{
		swfu_1 = new SWFUpload({
		// Backend Settings
		upload_url: "./rdincfiles/functionsmapping.inc.php?type=16&username="+username+"&email="+email+"&albumid="+albumid,
		post_params: {"PHPSESSID": "<?=session_id();?>"},

		// File Upload Settings
		file_size_limit : "2 MB",	// 2MB
		file_types : "*.jpg;*.gif;*.png",
		file_types_description: "Web Image Files",
		file_upload_limit : "0",

		// Event Handler Settings - these functions as defined in Handlers.js
		//  The handlers are not part of SWFUpload but are part of my website and control how
		//  my website reacts to the SWFUpload events.
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,

		// Button Settings
		button_image_url : "", //"images/SmallSpyGlassWithTransperancy_17x18.png",
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 300,
		button_height: 27,
		button_text : '<span class="albumimages_button">Click to upload image(s) (max: 2MB)</span>',
		button_text_style : '.albumimages_button { font-size: 17px; color: #454545; cursor: pointer; font-weight: bold;}',
		button_text_top_padding: 2,
		button_text_left_padding: 3,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		// Flash Settings
		flash_url : "./rdscripts/swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainer"
		},
		
		// Debug Settings
		debug: false
		});
	
	
		swfu_2 = new SWFUpload({
		// Backend settings
		upload_url: "./rdincfiles/functionsmapping.inc.php?type=24&username="+username+"&email="+email+"&albumid="+albumid,
		post_params: {"PHPSESSID": "<?=session_id();?>"},

		// Flash file settings
		file_size_limit : "2 MB",
		file_types : "*.jpg;*.gif;*.png",
		file_types_description: "Web Image Files",
		file_upload_limit : "0",
		file_queue_limit : "1",

		// Event handler settings
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		
		upload_start_handler : uploadStart,	// I could do some client/JavaScript validation here, but I don't need to.
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadCompleteCovers,

		// Button Settings
		button_image_url : "",
		button_placeholder_id : "spanButtonPlaceholderContainerCover",
		button_width: 300,
		button_height: 27,
		button_text : '<span class="albumcovers_button">Click to upload image (max: 2MB)</span>',
		button_text_style : '.albumcovers_button {font-size: 17px; color: #454545; cursor: pointer; font-weight: bold;}',
		button_text_top_padding: 2,
		button_text_left_padding: 3,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		// Flash Settings
		flash_url : "./rdscripts/swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainerCover"
		},
		
		// Debug settings
		debug: false
		});	

	}//swfuFunction_album(username,email,albumid)
	
	
	function swfuFunction_settings(username,email)
	{
	
		swfu_3 = new SWFUpload({
		// Backend settings
		upload_url: "./rdincfiles/functionsmapping.inc.php?type=27&username="+username+"&email="+email,
		post_params: {"PHPSESSID": "<?=session_id();?>"},

		// Flash file settings
		file_size_limit : "2 MB",
		file_types : "*.jpg;*.gif;*.png",
		file_types_description: "Web Image Files",
		file_upload_limit : "0",
		file_queue_limit : "1",

		// Event handler settings
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		
		upload_start_handler : uploadStart,	// I could do some client/JavaScript validation here, but I don't need to.
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadCompleteUserCovers,

		// Button Settings
		button_image_url : "",
		button_placeholder_id : "spanButtonPlaceholderContainerCover",
		button_width: 300,
		button_height: 27,
		button_text : '<span class="usercovers_button">Click to upload image (max: 2MB)</span>',
		button_text_style : '.usercovers_button {font-size: 17px; color: #454545; cursor: pointer; font-weight: bold;}',
		button_text_top_padding: 2,
		button_text_left_padding: 3,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		// Flash Settings
		flash_url : "./rdscripts/swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainerCover"
		},
		
		// Debug settings
		debug: false
		});	
	
	}//swfuFunction_settings(username,email)
	
	
	function swfuFunction_blog(username,email,postID)
	{
		swfu_4 = new SWFUpload({
		// Backend Settings
		upload_url: "./rdincfiles/functionsmapping.inc.php?type=33&username="+username+"&email="+email+"&postid="+postID,
		post_params: {"PHPSESSID": "<?=session_id();?>"},

		// File Upload Settings
		file_size_limit : "2 MB",	// 2MB
		file_types : "*.jpg;*.gif;*.png",
		file_types_description: "Web Image Files",
		file_upload_limit : "0",
		
		// Event Handler Settings - these functions as defined in Handlers.js
		//  The handlers are not part of SWFUpload but are part of my website and control how
		//  my website reacts to the SWFUpload events.
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadCompleteBlogImages,

		// Button Settings
		button_image_url : "", //"images/SmallSpyGlassWithTransperancy_17x18.png",
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 300,
		button_height: 27,
		button_text : '<span class="albumimages_button">Click to upload image(s) (max: 2MB)</span>',
		button_text_style : '.albumimages_button {font-size: 17px; color: #454545; cursor: pointer; font-weight: bold;}',
		button_text_top_padding: 2,
		button_text_left_padding: 3,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		// Flash Settings
		flash_url : "./rdscripts/swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainer"
		},
		
		// Debug Settings
		debug: false
		});
	}//swfuFunction_blog(username,email,postID)
	
	
	
	function swfuFunction_coverpage(username,email)
	{
		swfu_5 = new SWFUpload({
		// Backend Settings
		upload_url: "./rdincfiles/functionsmapping.inc.php?type=39&username="+username+"&email="+email,
		post_params: {"PHPSESSID": "<?=session_id();?>"},

		// File Upload Settings
		file_size_limit : "2 MB",	// 2MB
		file_types : "*.jpg;*.gif;*.png",
		file_types_description: "Web Image Files",
		file_upload_limit : "0",


		// Event Handler Settings - these functions as defined in Handlers.js
		//  The handlers are not part of SWFUpload but are part of my website and control how
		//  my website reacts to the SWFUpload events.
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadCompleteCoverPageImages,

		// Button Settings
		button_image_url : "", //"images/SmallSpyGlassWithTransperancy_17x18.png",
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 300,
		button_height: 27,
		button_text : '<span class="albumimages_button">Click to upload image(s) (max: 2MB)</span>',
		button_text_style : '.albumimages_button {font-size: 17px; color: #454545; cursor: pointer;}',
		button_text_top_padding: 2,
		button_text_left_padding: 3,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		// Flash Settings
		flash_url : "./rdscripts/swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainer"
		},
		
		// Debug Settings
		debug: false
		});
	}//swfuFunction_coverpage(username,email,postID)
	
	
	function swfuFunction_wallpaper(username,email)
	{
		swfu_6 = new SWFUpload({
		// Backend Settings
		upload_url: "./rdincfiles/functionsmapping.inc.php?type=41&username="+username+"&email="+email,
		post_params: {"PHPSESSID": "<?=session_id();?>"},

		// File Upload Settings
		file_size_limit : "1 MB",	// 2MB
		file_types : "*.jpg;*.gif;*.png",
		file_types_description: "Web Image Files",
		file_upload_limit : "0",
		file_queue_limit : "1",
		
		// Event Handler Settings - these functions as defined in Handlers.js
		//  The handlers are not part of SWFUpload but are part of my website and control how
		//  my website reacts to the SWFUpload events.
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadCompleteWallpaperImages,

		// Button Settings
		button_image_url : "", //"images/SmallSpyGlassWithTransperancy_17x18.png",
		button_placeholder_id : "spanButtonPlaceholderWallpaper",
		button_width: 300,
		button_height: 27,
		button_text : '<span class="wallpaperimages_button">Click to upload image (max: 1MB)</span>',
		button_text_style : '.wallpaperimages_button {font-size: 17px; color: #454545; cursor: pointer; font-weight: bold;}',
		button_text_top_padding: 2,
		button_text_left_padding: 3,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		
		// Flash Settings
		flash_url : "./rdscripts/swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainerWallpaper"
		},
		
		// Debug Settings
		debug: false
		});
	}//swfuFunction_wallpaper(username,email,postID)	
	
	
	function swfuFunction_logo(username,email)
	{
		swfu_7 = new SWFUpload({
		// Backend Settings
		upload_url: "./rdincfiles/functionsmapping.inc.php?type=42&username="+username+"&email="+email,
		post_params: {"PHPSESSID": "<?=session_id();?>"},

		// File Upload Settings
		file_size_limit : "1 MB",	// 1MB
		file_types : "*.jpg;*.gif;*.png",
		file_types_description: "Web Image Files",
		file_upload_limit : "0",
		file_queue_limit : "1",

		// Event Handler Settings - these functions as defined in Handlers.js
		//  The handlers are not part of SWFUpload but are part of my website and control how
		//  my website reacts to the SWFUpload events.
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadCompleteLogoImages,

		// Button Settings
		button_image_url : "", //"images/SmallSpyGlassWithTransperancy_17x18.png",
		button_placeholder_id : "spanButtonPlaceholderLogo",
		button_width: 300,
		button_height: 27,
		button_text : '<span class="logoimages_button">Click to upload image (max: 1MB)</span>',
		button_text_style : '.logoimages_button {font-size: 17px; color: #454545; cursor: pointer; font-weight: bold;}',
		button_text_top_padding: 2,
		button_text_left_padding: 3,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,

		
		// Flash Settings
		flash_url : "./rdscripts/swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainerLogo"
		},
		
		// Debug Settings
		debug: false
		});
	}//swfuFunction_logo(username,email,postID)