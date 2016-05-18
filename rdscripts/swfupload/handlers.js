function fileQueueError(file, errorCode, message) {
	try {
		//var imageName = "error.gif";
		var errorName = "";
		if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
			errorName = "You have attempted to queue too many files.";
		}

		if (errorName !== "") {
			alert(errorName);
			return;
		}

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			//imageName = "zerobyte.gif";
			alert('Error: The image you selected is empty (0 KB)');
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			//imageName = "toobig.gif";
			alert('Error: The image you selected is too big (Limit: 1.5 MB)');
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
		default:
			alert(message);
			break;
		}

		//addImage("images/" + imageName);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadProgress(file, bytesLoaded) {

	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);

		var progress = new FileProgress(file,  this.customSettings.upload_target);
		progress.setProgress(percent);
		if (percent === 100) {
			progress.setStatus("Creating thumbnail...");
			progress.toggleCancel(false, this);
		} else {
			progress.setStatus("Uploading...");
			progress.toggleCancel(true, this);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	try {
		var progress = new FileProgress(file,  this.customSettings.upload_target);

		if (serverData.substring(0, 7) === "FILEID:") {
			//addImage("thumbnail.php?id=" + serverData.substring(7));

			imageURL = submitImageURL(serverData.substring(7));
			//addImage(imageURL);
			//addImage("../../rdincfiles/image.inc.php?requesting=3&iid="+serverData.substring(7));
			
			progress.setStatus("Thumbnail Created.");
			progress.toggleCancel(false);
		} else {
			//addImage("images/error.gif");
			progress.setStatus("Error.");
			progress.toggleCancel(false);
			alert(serverData);

		}

	} catch (ex) {
		this.debug(ex);
	}
}

function uploadStart(file)
{
	var continue_with_upload = true;

	//DEACTIVATE 'reorder' and 'delete' buttons
	$('#albumimage_reorder').fadeTo(100,0.2);
	$('#albumimage_delete').fadeTo(100,0.2);
	$('#albumimage_reorder').unbind();
	$('#albumimage_delete').unbind();
	
	$('.refreshsection').fadeTo(100,0.2);
	$('.refreshsection').unbind();

	$('#albumimage_uploadprogress_container').fadeTo(0,1);
	$('#album_uploadprogress_container').fadeTo(0,1);

	return continue_with_upload;
}

function uploadComplete(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("All images received.");
			progress.toggleCancel(false);

			setTimeout('reset_uploadStatusDiv("albumimage_uploadprogress_container","spanButtonPlaceholderContainer")',500);
			
			//ACTIVATE 'reorder' and 'delete' buttons
			$('#albumimage_reorder').fadeTo(100,1);
			$('#albumimage_delete').fadeTo(100,1);
			
			$('#albumimage_reorder').click(function(){
				portfolio_images_reorder_event_initialize();
			});
			$('#albumimage_delete').click(function(){
				portfolio_images_delete_event_initialize();				
			});
			
		}
	} catch (ex) {
		this.debug(ex);
	}
}//uploadComplete(file)

function uploadCompleteCovers(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("Image received.");
			progress.toggleCancel(false);
		
			setTimeout('reset_uploadStatusDiv("album_uploadprogress_container","spanButtonPlaceholderContainerCover")',500);

			//ACTIVATE 'reorder' and 'delete' buttons
			$('#albumimage_reorder').fadeTo(100,1);
			$('#albumimage_delete').fadeTo(100,1);
			
			$('#albumimage_reorder').click(function(){
				portfolio_images_reorder_event_initialize();
			});
			$('#albumimage_delete').click(function(){
				portfolio_images_delete_event_initialize();				
			});
			
		}
	} catch (ex) {
		this.debug(ex);
	}
}//uploadCompleteCovers(file)

function uploadCompleteUserCovers(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("Image received.");
			progress.toggleCancel(false);
		
			setTimeout('reset_uploadStatusDiv("user_uploadprogress_container","spanButtonPlaceholderContainerCover")',500);
			
		}
	} catch (ex) {
		this.debug(ex);
	}
}//uploadCompleteUserCovers(file)

function uploadCompleteBlogImages(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("All images received.");
			progress.toggleCancel(false);
		
			setTimeout('reset_uploadStatusDiv("blog_uploadprogress_container","spanButtonPlaceholderContainerCover")',500);
			
		}
	} catch (ex) {
		this.debug(ex);
	}
}//uploadCompleteBlogImages(file)


function uploadCompleteCoverPageImages(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("All images received.");
			progress.toggleCancel(false);
		
			setTimeout('reset_uploadStatusDiv("coverpageimage_uploadprogress_container","spanButtonPlaceholderContainerCover")',500);
			
		}
	} catch (ex) {
		this.debug(ex);
	}
}//uploadCompleteCoverPageImages(file)

function uploadCompleteWallpaperImages(file)
{
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("Image received.");
			progress.toggleCancel(false);
		
			setTimeout('reset_uploadStatusDiv("wallpaperimage_uploadprogress_container","spanButtonPlaceholderContainerWallpaper")',500);
			
		}
	} catch (ex) {
		this.debug(ex);
	}
}//uploadCompleteWallpaperImages(file)


function uploadCompleteLogoImages(file)
{
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("Image received.");
			progress.toggleCancel(false);
		
			setTimeout('reset_uploadStatusDiv("logoimage_uploadprogress_container","spanButtonPlaceholderContainerLogo")',500);
			
		}
	} catch (ex) {
		this.debug(ex);
	}
}//uploadCompleteLogoImages(file)


function uploadError(file, errorCode, message) {
	var imageName =  "error.gif";
	var progress;
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Cancelled");
				progress.toggleCancel(false);
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Stopped");
				progress.toggleCancel(true);
			}
			catch (ex2) {
				this.debug(ex2);
			}
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			alert('Error: You tryied to upload too many images all at once');
			break;
		default:
			alert(message);
			break;
		}

		//addImage("images/" + imageName);

	} catch (ex3) {
		this.debug(ex3);
	}

}


function addImage(src) {
	var newImg = document.createElement("img");
	newImg.style.margin = "5px";

	document.getElementById("thumbnails").appendChild(newImg);
	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} else {
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);
	};
	newImg.src = src;
}

function fadeIn(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 30;	// 15 fps


	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}



/* ******************************************
 *	FileProgress Object
 *	Control object for displaying file info
 * ****************************************** */

function FileProgress(file, targetID) {
	this.fileProgressID = "divFileProgress";

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";

		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "visible";
		progressCancel.appendChild(document.createTextNode(" "));

		var progressText = document.createElement("span");
		progressText.className = "progressName";
		
		someText = "Image: "+file.name;
		progressText.appendChild(document.createTextNode(someText));


		var progressBar = document.createElement("span");
		progressBar.className = "progressBarInProgress";

		var progressStatus = document.createElement("span");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
		fadeIn(this.fileProgressWrapper, 0);

	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		someText = "Image: "+file.name;
		this.fileProgressElement.childNodes[1].firstChild.nodeValue = someText;
	}

	this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progressContainer blue";
	this.fileProgressElement.childNodes[3].className = "progressBarComplete";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer red";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[2].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfuploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfuploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};


function reset_uploadStatusDiv(statusContainerID,uploadFormID)
{
	switch(statusContainerID)
	{
		case 'albumimage_uploadprogress_container':		
			//$('#'+statusContainerID).fadeTo(500,0);
			$('#'+statusContainerID).html('<div id="divFileProgressContainer"></div>');
			
			albumID = $('#album_aid').html();
			account_album_subpage_load_sections('album_page_view',albumID,'');
			account_album_subpage_load_sections('album_page_edit',albumID,'');
			
			//$('#'+uploadFormID).show();
			break;
		case 'album_uploadprogress_container':
			//$('#'+statusContainerID).fadeTo(500,0);
			$('#'+statusContainerID).html('<div id="divFileProgressContainerCover"></div>');
			
			albumID = $('#album_aid').html();
			//account_album_subpage_load_sections('album_page_view',albumID,'');
			//account_album_subpage_load_sections('album_page_edit',albumID,'');
			break;
		case 'user_uploadprogress_container':
			//$('#'+statusContainerID).fadeTo(500,0);
			$('#'+statusContainerID).html('<div id="divFileProgressContainerCover"></div>');
	
			//account_album_subpage_load_sections('album_page_view',albumID,'');
			//account_album_subpage_load_sections('album_page_edit',albumID,'');	
			break;
		case 'blog_uploadprogress_container':
			//$('#'+statusContainerID).fadeTo(500,0);
			$('#'+statusContainerID).html('<div id="divFileProgressContainer"></div>');
			
			blogID = $('#blog_pid').html();
			account_blog_subpage_load_sections('blog_page_edit',blogID,'');
			break;
		case 'coverpageimage_uploadprogress_container':
			//$('#'+statusContainerID).fadeTo(500,0);
			$('#'+statusContainerID).html('<div id="divFileProgressContainer"></div>');
			
			section_contentpages_initialize_account('coverpage');
			break;
		case 'wallpaperimage_uploadprogress_container':
			//$('#'+statusContainerID).fadeTo(500,0);
			$('#'+statusContainerID).html('<div id="divFileProgressContainerWallpaper">Your website has a wallpaper image!</div>');
			break;
		case 'logoimage_uploadprogress_container':
			//$('#'+statusContainerID).fadeTo(500,0);
			$('#'+statusContainerID).html('<div id="divFileProgressContainerLogo">Your website has a logo!</div>');
		
			break;
	}//switch
	/*
	if(statusContainerID == 'albumimage_uploadprogress_container')
	{

	}//
	else if(statusContainerID == 'album_uploadprogress_container')
	{
		
	}//
	else if(statusContainerID == 'user_uploadprogress_container')
	{
	
	}//
	else if(statusContainerID == 'blog_uploadprogress_container')
	{

	}//
	else if(statusContainerID == 'coverpageimage_uploadprogress_container')
	{
	}//
	*/
}