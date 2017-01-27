<?php
/**
 * Created by PhpStorm.
 * User: alvaro1
 * Date: 12/9/16
 * Time: 3:43 PM
 */

/**
This is a hook utility function that Hide a field based on hte user name

Currently dropdowns randomization doesn't work...

@RANDOMORDER=99 would randomize the list but keep 99 at the bottom

Andrew Martin
Stanford University
 **/

$term = '@HIDDEN-FOR-USER';
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
error_log("Startup Vars in " . __FILE__);
error_log(print_r($startup_vars,true));
//error_log("Username $username");
error_log("USERID" . USERID);
//error_log("SUPERUSER" . SUPERUSER);
//global $username;
//error_log("Username in global: $username");
?>

<script type='text/javascript'>

//    $(document).ready(function() {

    var fields = <?php print json_encode($startup_vars) ?>;
    var user = <?php print json_encode(USERID) ?>;  //current user

    //console.log("Fields:",fields);
    console.log("Usuarios:",user);

    // Loop through each field_name
    $.each(fields, function(field,params) {

        // Get parent tr for table
        var tr = $('tr[sq_id="' + field + '"]');
        // console.log('tr');console.log(tr);
        //Get the user names
        var list_of_users_param =   params.params.split(",");

        //loop through each username in the action tag.
        $.each(list_of_users_param, function(index, optionKey) {
            if (optionKey==user) tr.remove() ; //if current user is in the list in the action tag
        });
    });
//});
</script>
