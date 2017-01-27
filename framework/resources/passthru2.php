<?php

/*
	This global hooks is intended to allow you to access custom hook/plugin code from outside of webauth

	To use, first define an appropriate entry in the $passthru2Files array where the key is the value you will
	pass into the survey url as __pasthru2=key and the value is the file path to the actual file to be inserted and returned
	
	e.g.  http://redcap.localhost.com/surveys/?s=9LRT3A8ND8&__passthru2=asdf
	$passthru2Files['asdf'] = HOOK_PATH_FRAMEWORK . "resources/asdf/listener.php";
*/


// The Custom PASSTHRU hook
// Check to see if any __passthru2 requests are present in the query string
if (isset($_GET['__passthru2']) && !empty($_GET['__passthru2']))
{
	// Since the survey page top hook starts AFTER the page rendering has begun, we need to
	// clear the page buffer and start over.  I hope this is stable and doesn't break
	//error_log("About to clean buffer!");
	//ob_clean();
	ob_end_clean();
	// Decode the query string value
	$passthru2Key = urldecode($_GET['__passthru2']);
	
	//error_log("Passthru Key is: $passthru2Key");
	
	
	// Set array of allowed passthru files.  
	// The Key is what is matched to the query string and the value is the file path
	$passthru2Files = array();
	
	// The stanford cat API endpoint
	// ADD YOUR ADDITONAL PASSTHRU OPTIONS HERE!
	$passthru2Files['stanford_cat_api'] = HOOK_PATH_FRAMEWORK . "resources/stanford_cat/listener.php";
	
	// A plugin-example
	//	$passthru2Files['my_plugin'] = dirname(APP_PATH_DOCROOT) . DS . "plugins/my_plugin/my_file.php";
	
	
	// Check if a valid passthru file
	if (isset($passthru2Files[$passthru2Key]))
	{
		$passthru2File = $passthru2Files[$passthru2Key];
		if (file_exists($passthru2File)) {
			// Include the file
			//error_log("Including $passthru2File");
			require_once $passthru2File;
			//error_log("Included $passthru2File");
		} else {
//			error_log("Including $passthru2File");
			error_log("Unable to located requested file: $passthru2File");
			print "Invalid parameter: __passthru2 - see server logs";
		}
	} else {
		hook_log("Requested passthru key ($passthru2Key) is not defined in " . __FILE__, "ERROR");
		print "Invalid configuration: __passthru2 - see server logs";
	}
	// Remove now since not needed
	unset($_GET['__passthru2']);
	exit();	
}

?>