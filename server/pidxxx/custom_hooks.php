<?php

/**
 *
 * This is a PROJECT HOOK File.
 *
 * It is included for every hook in this project that matches the parent folder's name
 *
 *
 */

/**
  You can use a variable called $hook_event to determine whether or not to take action on the call

  To see what parameters are present in your function, check the Hook Function documentation
  - redcap_add_edit_records_page ($project_id, $instrument, $event_id)
  - redcap_data_entry_form($project_id, $record, $instrument, $event_id, $group_id)
  - redcap_data_entry_form_top($project_id, $record, $instrument, $event_id, $group_id)
  - redcap_every_page_before_render($project_id)
  - redcap_every_page_top($project_id)
  - redcap_project_home_page($project_id)
  - redcap_save_record($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id)
  - redcap_survey_complete($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id)
  - redcap_survey_page($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id)
  - redcap_survey_page_top($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id)
  - redcap_user_rights($project_id)

	For example:
	if ($hook_event == 'redcap_add_edit_records_page') {
		print "<div class='yellow'>A custom hook has been triggered for $hook_event in project $project_id.</div>";
	}
	
	You can use the same code for multiple events, such as:
	if ($hook_event == 'redcap_data_entry_form' || $hook_event == 'redcap_survey_page') {
		print "<div class='yellow'>Your entering data on project $project_id.</div>";
	}
**/

// YOU SHOULD COMMENT THESE OUT ONCE YOU ARE SURE YOUR HOOK IS WORKING:
//print "<div class='yellow'>We've caught a project-specific hook: <code>$hook_event</code> in project $project_id with <pre>" . __FILE__ . "</pre></div>";
if (headers_sent()) print "<script>if (window.console) { console.log('Just Fired ' + $hook_event); }</script>";
