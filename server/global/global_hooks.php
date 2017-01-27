<?php

/**
	
	This is the GLOBAL HOOKS Master File.
	
	It is included in EVERY hook event across your instance.
	You can use a variable called $hook_event to determine whether or not to take action on the call
	
	This file should be located in hooks/global/global_hooks.php
	
	For example:
	
	if ($hook_event == 'redcap_add_edit_records_page') {
		print "<div class='yellow'>A custom hook has been triggered for $hook_event.</div>";
	}
		
	You can use the same code for multiple events, such as:
	
	if ($hook_event == 'redcap_data_entry_form' || $hook_event == 'redcap_survey_page') {
		print "<div class='yellow'>Your entering data.</div>";
	}
**/

global $hook_functions;

// UNCOMMENT TO TEST
// THIS WILL ONLY WORK FOR HOOKS WHICH OCCUR AFTER THE HEADERS HAVE BEEN SENT
if ( defined('SUPER_USER') && SUPER_USER ) {
    if (headers_sent()) print "<div class='green'>We've caught a global hook event: <code>$hook_event</code> in <pre>" .
        __FILE__ . "</pre>Comment this statement out of " . __FILE__ . " at line " . __LINE__ . 
        " to hide or, even better just leave it in for certain developers...</div>";
    hook_log("SUPER USER " . USERID . " AT $hook_event" );
}

// THIS IS AN ARRY OF FILES TO INCLUDE AT THE END OF THE SCRIPT
$includes = array();

// START redcap_survey_pag  e_top
/*if ($hook_event == 'redcap_every_page_before_render') {

} */


// END redcap_survey_page_top

//if ($hook_event == 'redcap_data_entry_form_top' AND $project_id == 12) {
//    namespace Alvarospace;
//    {
//        function form_renderer($elements, $element_data=array(), $hideFields=array())
//        {
//            error_log("HELLO!!!");
//            return \form_renderer($elements, $element_data, $hideFields);
//        }
//    }
//}


// START redcap_data_entry_form OR redcap_survey_page
if ($hook_event == 'redcap_data_entry_form' || $hook_event == 'redcap_survey_page') {
    $includes[] = HOOK_PATH_RESOURCES."inputmatrix.php";
    $includes[] = HOOK_PATH_RESOURCES."none_of_the_above.php";
    $includes[] = HOOK_PATH_RESOURCES."random_order.php";
    $includes[] = HOOK_PATH_RESOURCES."shazam.php";
    $includes[] = HOOK_PATH_RESOURCES."shazam2.php";
    $includes[] = HOOK_PATH_RESOURCES."imagemap/imagemap.php";
    $includes[] = HOOK_PATH_RESOURCES."imageview.php";
    $includes[] = HOOK_PATH_RESOURCES."slider.php";
    $includes[] = HOOK_PATH_RESOURCES."colcount.php";
    $includes[] = HOOK_PATH_RESOURCES."inline_other.php";
    $includes[] = HOOK_PATH_RESOURCES."onerow_matrix.php";
    $includes[] = HOOK_PATH_RESOURCES."inputwidth.php";
    $includes[] = HOOK_PATH_RESOURCES."oddeven_rows.php";
   // $includes[] = HOOK_PATH_RESOURCES."hidden_for_user.php";
    //$includes[] = HOOK_PATH_RESOURCES."header_rotation.php";
    $includes[] = HOOK_PATH_RESOURCES."text_to_link.php";
} // END redcap_data_entry_form OR redcap_survey_page


// Enable the redcap_user_rights hook globally
if ($hook_event == 'redcap_user_rights') {
	$includes[] = HOOK_PATH_RESOURCES . "user_rights_default_roles.php";
} // END redcap_user_rights



// INCLUDE ALL OF THE RESOURCES SPECIFIED GLOBALLY
foreach($includes as $file) {
    if (file_exists($file)) {
        include_once $file;
    } else {
        hook_log("Unable to include $file in $hook_function context");
    }
}


