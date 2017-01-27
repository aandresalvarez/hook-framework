<?php

/*
 * This script hides the Project Dashboard feature from the Project Home for users that do not have design rights
 *
 * Since there are no ids or classes to specifically select the Project Dashboard I hide a group of divs and then
 * show them.
 *
 * By using CSS first and then the javascript second, it prevents a 'flicker' of showing the dashboard for a second
 * before it is removed from the page.
 *
 */

// Get the current user's rights
$user_rights = REDCap::getUserRights(USERID);
$user_rights = $user_rights[USERID];

// Right now we are hiding the dashboard if a user doesn't have design rights.  You could also do thinks like:
if ($user_rights['design'] == 1) {
    // keep it visible
    $hide_project_dashboard = true;
} else {
    // Hide Dashboard
    $hide_project_dashboard = true;
}

if ($hide_project_dashboard) {
    ?>
    <style>
        /* Hide a bunch of stuff, including the Project Dashboard*/
        #center {
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s linear;
        }

        /* Add it back after cleaning out the Project Dashboard */
        #center.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
    <script>
        $(document).ready(function() {
            // Remove the project dashboard form the page
            $('div.chklisthdr:contains("Project Dashboard")').parent().remove();
            // Redisplay the rest of the page
            $('#center').addClass('show');
        });
    </script>
    <?php
};

?>
