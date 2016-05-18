// JavaScript Document
//commonajaxfunctionsinc.js


// holds an instance of XMLHttpRequest
var xmlHttp = createXmlHttpRequestObject();

// creates an XMLHttpRequest instance
function createXmlHttpRequestObject()
{
/*
A modified version of a function found in: 

Darie, C., Brinzarea B., Chereches-Tosa, F. & Bucicia M., 2005, AJAX and PHP: Building Responsive Web Applications, Packt Publishing 
*/
	// will store the reference to the XMLHttpRequest object
	var xmlHttp;
	// this should work for all browsers except IE6 and older
	try
	{
		// try to create XMLHttpRequest object
		xmlHttp = new XMLHttpRequest();
	}
	catch(e)
	{
		// assume IE6 or older
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
										"MSXML2.XMLHTTP.5.0",
										"MSXML2.XMLHTTP.4.0",
										"MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP",
										"Microsoft.XMLHTTP");
		// try every id until one works
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
		{
			try
			{
				// try to create XMLHttpRequest object
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}
			catch (e) {} // ignore potential error
		}//for
	}//catch
	// return the created object or display an error message
	if (!xmlHttp)
		displayError("Error creating the XMLHttpRequest object.","");
	else
		return xmlHttp;
}//createXmlHttpRequestObject()

function handleRequestStateChangeValidateNSubmit(){handleRequestStateChange('validateNsubmit');}
function handleRequestStateChangeValidateNSubmitMultipleValues(){handleRequestStateChange('validateNsubmitMultipleValues');}
function handleRequestStateChangeValidateNSubmitLogin(){handleRequestStateChange('validateNsubmitLogin');}

// function that handles the HTTP response
function handleRequestStateChange($actionType)
{
/*
A modified version of a function found in: 

Darie, C., Brinzarea B., Chereches-Tosa, F. & Bucicia M., 2005, AJAX and PHP: Building Responsive Web Applications, Packt Publishing 
*/

	// when readyState is 4, we read the server response
	if (xmlHttp.readyState == 4)
	{
		// continue only if HTTP status is "OK"
		if (xmlHttp.status == 200)
		{
			try
			{
				// read and process the response from the server
				switch($actionType)
				{
					case "validateNsubmit":
						setTimeout('readResponseValidateNsubmit();',0);
						break;
					case "validateNsubmitLogin":
						setTimeout('readResponseValidateNsubmitLogin();',0);
						break;
					case "validateNsubmitMultipleValues":
						setTimeout('readResponseValidateNsubmitMultipleValues();',0);
						break;
					default:
						setTimeout('readResponseValidateNsubmitLogin();',0);
						break;
				}//switch
			}//try
			catch(e)
			{
				// display error message
				displayError(e.toString(),"");
			}//catch
		}//if
		else
		{
			// display error message
			displayError("There was a problem retrieving the data:\n" + xmlHttp.statusText,"");
		}//else
	}//if
}//handleRequestStateChange($actionType)


// function that displays an error message
function displayError($message, $purpose)
{
	switch($purpose)
	{
		case "validateNsubmit":
			// ignore errors if showErrors is false. showErrors initialized in validate.js
			if (showErrors)
			{
				// turn error displaying Off
				showErrors = false;
				// display error message
				alertmessages("Error","Error encountered: \n" + $message);
				// retry validation after 10 seconds
				setTimeout("validateNsubmit();", 5000);
			}//if
			break;
		case "suggest":
			// display error message, with more technical details if debugMode is true
  			alert("Error accessing the server! "+(debugMode ? "\n" + $message : ""));
			break;
		default:
			alertmessages("Error","Error encountered: \n" + $message);
			break;
	}//
}//displayError()

function databaseErrorReporter()
{
	var response = xmlHttp.responseText;
	if (response.indexOf("ERRNO") >= 0 || response.indexOf("error:") >= 0 || response.length == 0){throw(response.length == 0 ? "Server error." : response);}
	responseXml = xmlHttp.responseXML;
	xmlDoc = responseXml.documentElement;
	
	errorCode = xmlDoc.getElementsByTagName("errorcode")[0].firstChild.data;
	message = xmlDoc.getElementsByTagName("message")[0].firstChild.data;
	databaseError = xmlDoc.getElementsByTagName("databaseerror")[0].firstChild.data;
	
	if(databaseError=='null'){databaseError='';}else{databaseError='<br /><br />'+databaseError}
	
	alertmessages('Error',errorCode+': '+message+databaseError);
}//dbErrorReporter()

function xmlToArray(resultsXml)
{
	// initiate the resultsArray
	var resultsArray= new Array();  
	// loop through all the xml nodes retrieving the content  
	for(i=0;i<resultsXml.length;i++){resultsArray[i]=resultsXml.item(i).firstChild.data;}
	// return the node's content as an array
	return resultsArray;
}