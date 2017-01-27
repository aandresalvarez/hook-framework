<?php
/**
 * Created by PhpStorm.
 * User: alvaro1
 * Date: 12/13/16
 * Time: 9:30 AM
 */
/**
The goal of this enhancement is to create some css into REDCap to have the possibilities for user to change column header rotation in matrix field.

@MATRIX-HEADER=45

 **/

$term = '@MATRIX-HEADER';
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
//error_log("USERID" . USERID);
//error_log("SUPERUSER" . SUPERUSER);
//global $username;
//error_log("Username in global: $username");
?>
/* class="matrix_first_col_hdr" */
<style>
    table.headermatrix th.row-header{
        width: 7.3%;

    }
    /*table.headermatrix td{
        width: 40px;
        border-top: 1px solid #dddddd;
        border-left: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        vertical-align: middle;
        text-align: center;*/
    }
     table.headermatrix th.rotate-45{
        height: 80px;
        width: 40px;
        min-width: 40px;
        max-width: 40px;
        position: relative;
        vertical-align: bottom;
        padding: 0;
        font-size: 12px;
        line-height: 0.8;
    }
    table.headermatrix th.rotate-45 > div{
        position: relative;
        top: 0px;
        left: 40px; /* 80 * tan(45) / 2 = 40 where 80 is the height on the cell and 45 is the transform angle*/
        height: 100%;
        -ms-transform:skew(-45deg,0deg);
        -moz-transform:skew(-45deg,0deg);
        -webkit-transform:skew(-45deg,0deg);
        -o-transform:skew(-45deg,0deg);
        transform:skew(-45deg,0deg);
        overflow: hidden;
        border-left: 1px solid #dddddd;
        border-right: 1px solid #dddddd;
        border-top: 1px solid #dddddd;
    }
    table.headermatrix th.rotate-45   span {
        -ms-transform:skew(45deg,0deg) rotate(315deg);
        -moz-transform:skew(45deg,0deg) rotate(315deg);
        -webkit-transform:skew(45deg,0deg) rotate(315deg);
        -o-transform:skew(45deg,0deg) rotate(315deg);
        transform:skew(45deg,0deg) rotate(315deg);
       /* position: absolute;*/
       bottom: 30px; /* 40 cos(45) = 28 with an additional 2px margin*/
       left: -25px; /* -25 Because it looked good, but there is probably a mathematical link here as well*/
        display: inline-block;
        width: 100%;
        width: 100px; /* 80 / cos(45) - 40 cos (45) = 85 where 80 is the height of the cell, 40 the width of the cell and 45 the transform angle*/
        height: 60px;
        text-align: justify;
       white-space: pre-wrap;  /*whether to display in one line or not*/

    }








</style>

<script type='text/javascript'>
    $(document).ready(function() {
        var colcount_fields = <?php print json_encode($startup_vars) ?>;
        console.log('colcount_fields::');console.log(colcount_fields);

        $.each(colcount_fields, function(field,params) {

            var tr         	= $('tr[sq_id='+field+']');
            var header      = tr.prev();
           // $(header).find('td').addClass('rotate1');

            var td          = header.children('td');
            var table       = td.children('table') ;
            var tbody       = table.children('tbody');
            var tr_matrix   = tbody.children('tr');


             //var td_matrix   = tr_matrix.children('td').css( "transform", "rotate(315deg)" );


            var td_matrix   = tr_matrix.children('td');//.addClass("rotate1");

           // var Text1 = td_matrix.replaceWith( '<th class="rotate-45"><div><span>x</span></div></th>' );
            var Text1 = td_matrix.wrapInner( '<th class="rotate-45"><div><span></span></div></th>' );

            console.log('::Texto::');console.log(Text1);




            //var labels= table.children('td').css( "border-style", "solid" );


            //var colwidth 	= Math.floor(100/params.params);
           // var inputs     	= $('td > span',tr).addClass("colCount").attr("style","width:"+colwidth+"%");



        });
    });
</script>



