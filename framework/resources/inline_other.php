<?php
/**
 * THIS HOOK HELPS BRING AN 'OTHER' TEXT OPTION UP TO THE 'OTHER' CHOICE OF A PREVIOUS RADIO/CHECKBOX QUESTION
 */
$term = '@INLINEOTHER';
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
.inlineOther {
    text-indent:initial; 
    margin-left:5px;
}
.inlineOther input {
    display:inline-block; 
    width:auto !important;
    margin-left:6px;
    vertical-align: initial;
}
</style>
<script type='text/javascript'>
$(document).ready(function() {
    var inline_other_fields = <?php print json_encode($startup_vars) ?>;
    console.log(inline_other_fields);
    $.each(inline_other_fields, function(field,params) {
        var altervals   = [];
        var affected    = params["params"].split("|");
        for(var i in affected){
            var temp = affected[i].split(",");
            altervals[temp[0]] = temp[1];
        }

        //for affected rows get radio/checks
        var tr          = $('tr[sq_id='+field+']');
        var inputs      = $('input[type!="hidden"]:not(:text)',tr).addClass("useHook",function(){
            //add context to parent of the input, to target css
            $(this).parent().addClass("inlineOther");

            //match input value/code to alterval key which is defined in the @INLINEOTHER=
            var inputval = $(this).attr("type") == "radio" ? $(this).val() : $(this).attr("code");
            if(altervals.hasOwnProperty(inputval)){
                var othertextrow = altervals[inputval];
                var othertextbox = $("tr[sq_id='"+othertextrow+"'] input[type!='hidden']");
                var otherlabel   = $("tr[sq_id='"+othertextrow+"'] .labelrc").not(".questionnum").text();

                //ON FOCUS CLICK THE OTHER RADIO
                othertextbox.addClass("custom_other").attr("placeholder",otherlabel).focus(function(){
                    if( $(this).closest(".inlineOther").find(".useHook:checked").length == 0 ){
                        $(this).parent().click();
                    }
                });
                $(this).parent().append(othertextbox);

                //REMOVE THE ORIGINAL OTHER ROW
                $("tr[sq_id='"+othertextrow+"']").attr("style","position:absolute; z-index:-1; visibility:hidden").hide();
            }
        });
    });
});
</script>
