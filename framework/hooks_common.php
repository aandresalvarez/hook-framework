<?php
		
/**
	This file contains common functions used by the redcap_hooks and redcap hook functions
	
	The hook_log function is intended to help you debug your hooks and is a work-in-progress...
	
	TODO: Allow you to specify a default file to log to instead of the php error log
	TODO: Make sure it actually works :-)  I haven't had much time to play with this
	TODO: Maybe try wrapping hook code in try/catch so errors are easier to debug...
	
	Each project can have a debug level for its hooks:
		0 = only errors are logged (production),
		1 = error and info statements are logged
		2 = all statements are logged
		3 = all statements are logged to error file AND screen
	
	Andy Martin
	Stanford University

**/

// Set the base hook folder to be one level higher than this file
define('HOOK_PATH_ROOT', dirname(__DIR__).DS);
define('HOOK_PATH_FRAMEWORK', dirname(__FILE__).DS);
define('HOOK_PATH_RESOURCES', dirname(__FILE__).DS."resources".DS);
define('HOOK_PATH_SERVER', HOOK_PATH_ROOT . "server" . DS);

define('HOOK_DEBUG_LEVEL', 2);  // 0 = ERROR, 1 = ALL BUT DEBUG, 2 = ALL
define('HOOK_DEBUG_LOG', NULL);  // A valid path to a log file or null for the default error log

/**
 *
 * Returns an array of paths to be included for the hook
 *
 * This works be including a global_hooks.php file or a hook_name.php file in either the
 * global folder or a pidxxxx folder.
 *
 **/
function get_hook_include_files($function, $project_id = null) {
	$paths = array();
	
	// GLOBAL SINGLE HOOK FILE
	$script = HOOK_PATH_SERVER."global".DS."global_hooks.php";
	if (file_exists($script)) $paths[] = $script;
	
	// GLOBAL HOOKS PER-FILE
	$script = HOOK_PATH_SERVER."global".DS.$function.".php";
	if (file_exists($script)) $paths[] = $script;
	
	// PROJECT-SPECIFIED HOOKS IN ONE FILE
	$script = HOOK_PATH_SERVER."pid".$project_id.DS."custom_hooks.php";
	if (file_exists($script)) $paths[] = $script;
	
	// PROJECT-SPECIFIC HOOKS PER-FILE (PREVIOUS VERSION)
	$script = HOOK_PATH_SERVER."pid".$project_id.DS.$function.".php";
	if (file_exists($script)) $paths[] = $script;

	// SCAN PROJECT LOG FOR USER-DEFINED HOOK MODULES
	// get something like:  "auto_continue_logic" : "enabled"
	//                      "hook_folder" : "my_research_project";
	// $script = HOOK_PATH_FRAMEWORK."resources".DS.$function.".php";
	// $params = xxx;
	// if (file_exists($script))) $paths[] = $script;

	return $paths;
}




// Logging function for all hook activity
/*
	The message parameter can be an object/array/string
	Type can be: ERROR, INFO, DEBUG
*/

function hook_log($message, $type = 'INFO', $prefix = '') {
	global $project_id;

    $log_this = (HOOK_DEBUG_LEVEL == 2) OR (HOOK_DEBUG_LEVEL == 1 AND $type != 'DEBUG') OR ($type == 'ERROR');
    if ($log_this) {
        // Get calling file using php backtrace to help label where the log entry is coming from
        $bt = debug_backtrace();

        // This is a hackish way to get the most relevant information from the backtrace
        $calling_file = $bt[0]['file'];
        $calling_line = $bt[0]['line'];
        $calling_function = isset($bt[3]['function']) ? $bt[3]['function'] :
            (isset($bt[2]['function']) ? $bt[2]['function'] :
                (isset($bt[1]['function']) ? $bt[1]['function'] :
                    (isset($bt[0]['function']) ? $bt[0]['function'] : "")));

        // Convert arrays/objects into string for logging
        if (is_array($message) OR is_object($message)) {
            $msg = print_r($message, true);
        } elseif (is_string($message) || is_numeric($message)) {
            $msg = $message;
        } elseif (is_bool($message)) {
            $msg = ($message ? "true" : "false");
        } elseif (is_null($message)) {
            $msg = "NULL";
        } else {
            $msg = "(unknown): " . print_r($message, true);
        }

        // Prepend prefix
        if ($prefix) $msg = "[$prefix] " . $msg;

        // Build log row
        $message = array(
            empty($project_id) ? "-" : $project_id,
            basename($calling_file, '.php'),
            $calling_line,
            $calling_function,
            $type,
            $msg
        );

        // Output to plugin log or error_log
        if (!empty(HOOK_DEBUG_LOG) && file_exists(HOOK_DEBUG_LOG)) {
            // Add the date
            array_unshift($message, date('Y-m-d H:i:s'));

            // Write to the plugin_log
            file_put_contents(
                HOOK_DEBUG_LOG,
                implode("\t", $message) . "\n",
                FILE_APPEND
            );
        } else {
            error_log(implode("\t", $message));
        }
    }
}