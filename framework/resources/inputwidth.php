<?php
/**
 *
 * THIS HOOK OVERRIDES THE WIDTH OF AN INPUT ELEMENT - CAN BE USEFUL FOR CUSTOM SCRIPTS LIKE INPUT MATRIX/SHAZAM..
 *
**/

$term = '@INPUTWIDTH';

hook_log("Starting $term for project $project_id", "DEBUG");

///////////////////////////////
//	Enable hook_functions and hook_fields for this plugin (if not already done)
if (!isset($hook_functions)) {
	$file = HOOK_PATH_FRAMEWORK . 'resources/init_hook_functions.php';
	if (file_exists($file)) {
		include_once $file;
		
		// Verify it has been loaded
		if (!isset($hook_functions)) { hook_log("ERROR: Unable to load required init_hook_functions."); return; }
	} else {
		hook_log ("ERROR: In Hooks - unable to include required file $file while in " . __FILE__);
	}
}

// See if the term defined in this hook is used on this page
if (!isset($hook_functions[$term])) {
	hook_log ("Skipping $term on $instrument of $project_id - not used.", "DEBUG");
	return;
}
//////////////////////////////


$startup_vars = $hook_functions[$term];
?>
<script type='text/javascript'>
$(document).ready(function() {
    var affected_fields = <?php print json_encode($startup_vars) ?>;
    $.each(affected_fields, function(field,params) {
    	var fixed_width = params.params;
        $('tr[sq_id='+field+'] input[type="text"]').width(fixed_width);
        
    });
});
</script>
