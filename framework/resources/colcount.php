<?php
	
/**

This is a hook that allows you to put the jumble of radio/checkboxes into orderly columns

You can define a hook as @COLCOUNT=4 and it will put 4 nice columns

**/
$term = '@COLCOUNT';

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
span.colCount input{
    position: absolute;
    left: 2px;
    top: 2px;
}
span.colCount{
	display: inline-block;
    vertical-align: top;
    padding: 5px 0 5px 21px;
    position: relative;
}
</style>
<script type='text/javascript'>
$(document).ready(function() {
	var colcount_fields = <?php print json_encode($startup_vars) ?>;

	$.each(colcount_fields, function(field,params) {
		var tr         	= $('tr[sq_id='+field+']');
		var colwidth 	= Math.floor(100/params.params);
		var inputs     	= $('td > span',tr).addClass("colCount").attr("style","width:"+colwidth+"%");
	});
});
</script>
