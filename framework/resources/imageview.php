<?php
	
/**
	This is a hook utility function that allows the file that was uploaded previously to be displayed as an inline image

	Lee Ann Yasukawa
	Stanford University
**/

$term = '@IMAGEVIEW';

///////////////////////////////
//	Enable hook_functions and hook_fields for this plugin (if not already done)
if (!isset($hook_functions)) {
	$file = HOOK_PATH_FRAMEWORK . 'resources/init_hook_functions.php';
	if (file_exists($file)) {
		include_once $file;
		
		// Verify it has been loaded
		if (!isset($hook_functions)) { hook_log("ERROR: Unable to load required init_hook_functions."); return; }
	} else {
		hook_log ("ERROR: In Hooks - unable to include required file $file while in " . __FILE__, "ERROR");
	}
}

// See if the term defined in this hook is used on this page
if (!isset($hook_functions[$term])) {
	//hook_log ("Skipping $term on $instrument of $project_id - not used.", "DEBUG");
	return;
}
//////////////////////////////

// Get the edoc storage option
global $edoc_storage_option;
if ($edoc_storage_option != 0) {
	hook_log("This hook only supports local edoc storage at the moment.  You're turn to make it better - see data_entry/file_download.php :-)", "ERROR");
	return;
}


// Verify edocs folder exists
if (!is_dir(EDOC_PATH)) {
	hook_log("Unable to view the local edoc folder: " . EDOC_PATH, "ERROR");
	return;
}



# Step 1 - Create array of fields which need to have the uploaded file displayed inline.
$startup_vars = array();
foreach($hook_functions[$term] as $field => $details) {
	// These are the fields with the @IMAGEVIEW tag requested
	
	// An array to store parameters
	$js_params = array();
	
	// Does $field have an image attached?
	$results = REDCap::getData('json',array($record),array($field), $event_id);
	$results = json_decode($results, true);
	$doc_id = isset($results[0][$field]) ? $results[0][$field] : NULL;
	
	if (!$doc_id) {
		hook_log("No file attached to $field - skipping", "DEBUG");
		continue;
	}
	
	// Query to get the filename
	$sql = sprintf("select rm.* from redcap_edocs_metadata rm
		where rm.doc_id = '%u' and rm.project_id = '%u';", 
		intVal($doc_id), 
		intVal($project_id)
	);
	$q = db_query($sql);
	if (!db_num_rows($q)) {
		hook_log("Unable to find edoc database entry for doc $doc_id", "ERROR");
		continue;
	}
	$this_file = db_fetch_array($q);
	$js_params['doc_name'] = $this_file['doc_name'];
	$js_params['file_extension'] = $this_file['file_extension'];
	
	// Make sure the file exists
	$local_file = EDOC_PATH . $this_file['stored_name'];
	if (!file_exists($local_file)) {
		hook_log("Unable to find edoc file: $local_file for doc $doc_id","ERROR");
		continue;
	}
		
	// Security check - is the attached file actually an image
	if (function_exists('getimagesize')) {
		$is_image = getimagesize($local_file) ? true : false;
		if ($is_image) {
			//hook_log("$local_file verified as image type");
		} else {
			hook_log("$local_file is not an image - skipping", "DEBUG");
			continue;
		}
	} else {
		hook_log ("Not able to do security check of image type - getimagesize function not supported");
	}
	
	// Get the contents of the actual file
	$b64 = base64_encode(file_get_contents($local_file));
	
	// Add the file source as a base64 encoded image and metadata to the startup vars array
	$js_params['src'] = "data:" . $this_file['mime_type'] . ";base64,$b64";
	$js_params['field_name'] = $field;
	
	// Check for user-supplied json parameters (passed in as $detauls) contain any valid json
	// such as @IMAGEVIEW={"hideLinks":true,"hideTitle":true}
	if (isset($details['params'])) {
		$user_params = json_decode($details['params'],true);
		if ($user_params) $js_params = array_merge($js_params, $user_params);
	}
	
	$startup_vars[] = $js_params;
}

?>

<script type='text/javascript'>
$(document).ready(function() {
	
	var displayFields = <?php print json_encode($startup_vars); ?>;
	//console.log(displayFields);
	
	// Loop through each field_name
	$(displayFields).each(function(i, params) {
		//console.log('i: ' + i);
		//console.log(params.field_name);
	
		// Get parent tr for table
		var tr = $('tr[sq_id="' + params.field_name + '"]');
		
		// Get the hyperlink
		var a = $('a[name="' + params.field_name + '"]', tr);
		
		// Determine the width of the parent td
		var td_width = a.closest('td').width();
		
		// Create a new image element
		var img = $('<img>').attr('src', params.src).css('max-width',td_width + 'px').css({"margin-left":"auto","margin-right":"auto","display":"block"});
		
		if (params.hideTitle == true) {
			// no title
		} else {
			// Get the hyperlink text to use as a title for the image
			var title = a.text();
			img.attr('title',title)
		}
		
		// Hide edoc-link if param hideLinks = true
		if (params.hideLinks == true) {
			$('span.edoc-link', tr).hide();
			a.attr('href','javascript:return false;');
		}
		
		// Replace the hyperlink text with the image
		a.text('').append(img);
	});	
	
});
</script>
