<?php
/**
 *
 * THIS HOOK HELPS ACCESS ODD/EVEN OPTIONS IN A LIST (RADIO/CHECKBOX)
 *
 */

$term = '@ODDEVEN';
//hook_log("Starting $term for project $project_id", "DEBUG");

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
<style>
div.odd {
    background    : #f2f2f2 !important;
    border-radius : 5px !important;
    border-right:none;
}
</style>
<script type='text/javascript'>
$(document).ready(function() {
    var affected_fields = <?php print json_encode($startup_vars) ?>;
    $.each(affected_fields, function(field,params) {
        var elems = $('tr[sq_id='+field+']').find("input[type='radio'],input[type='checkbox']");
        elems.each(function(idx){
        	if(idx % 2 == 0){
	        	$(this).parent().addClass("odd");
	        }
        });
    });
});
</script>
