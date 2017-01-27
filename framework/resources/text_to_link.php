<?php
/**
 *
 * THIS HOOK CHANGE A STRING TO a LINK 
 *
**/

$term = '@TO-LINK';

hook_log("Starting $term for project $project_id", "DEBUG");

///////////////////////////////
//  Enable hook_functions and hook_fields for this plugin (if not already done)
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
    .url_link_edit, .url_link_edit:link, .url_link_edit:hover {
      float:right; 
      font-size:smaller;
      text-decoration: none;
   }
</style>

<div class='url_link_template hidden'>
   <span class="url_link">
      <a href="" class="btn btn-link" role="button" target="">
         <span class="glyphicon glyphicon-share" style="padding-right:5px;text-indent:0;font-size:13px;" aria-hidden="true"></span>
         <span class="url_label"></span>
      </a>
   </span>
   <span stlye="display:inline-block;">
      <a hyperlink="javascript:;" class="url_link_edit">edit</a>
   </span>   
</div>




<script type='text/javascript'>

// Goals is to take text input and add hyperlink div and 'edit' div
function render_input_to_link(element, target="_BLANK") {
   // Take the input element
   element.addClass("url_link_input");
   // add a popover in case an invalid link
   element.attr('data-toggle', "popover");       
   element.attr('data-content', "Enter a valid url, e.g. https://www.google.com"); 
   element.attr('data-placement', "left");
   // Copy contents of template to the current question
   var copy = $('.url_link_template').contents().clone();
   $('.btn-link',copy).attr('target',target);
   element.after(copy);
   // Append placeholder information if there is no value   
   if (! element.attr("placeholder") ) element.attr("placeholder","Enter a valid url, e.g. https://www.google.com");


}

//Check if the value is a link
function validateUrl(webLink){
   var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.){1}([0-9A-Za-z]+\.)");
   return urlregex.test(webLink);
}


// Update the value of the link
function update_link(element, editMode = false) {
   var tr = element.parentsUntil('tr').parent();
   var val = element.val();
     
   if (val == "" || editMode ) {

      // link was deleted, so only show the input
      $('.url_link_input',tr).show();
      $('.url_link_edit', tr).hide();
      $('.url_link', tr).hide();
      if (editMode) $('.url_link_input',tr).focus().val('').val(val); // Set focus to the current input  
   
   } else {
       
      $('.url_link_input',tr).hide();
      $('.url_link a', tr).attr('href', val);       // Sets the hyperlink url
      $('.url_label', tr).text(encodeURI(val)); // Sets the label value
      $('.url_link_edit', tr).show();
      $('.url_link', tr).show();

   }
 

}
  
$(document).ready(function() {
   var affected_fields = <?php print json_encode($startup_vars) ?>;
   // Prepare each url input with extra HTML fields
   $.each(affected_fields, function(field,detail) {
      // Get the input
      var this_input = $('tr[sq_id='+field+'] input[type="text"]');
      // Set the target and render
      var target = ( detail.params == "self" ? '_self' : '_BLANK' );
      render_input_to_link(this_input, target);
      // Validate
      update_link(this_input);

   });

$( '.url_link_input' ).blur(function() {
      // Input was just left, convert to link.
   var val=  $(this).val();
      //Validate if the input is a valid URL
   var web =validateUrl(val.trim());
   if(!web  && val != ""){
      update_link($(this),true);
      $(this).popover('show');
   } else {
      update_link($(this));
      $(this).popover('hide');

   }
      //update_link($(this));   
   });


$( '.url_link_edit').click(function() {
   var tr = $(this).parentsUntil('tr').parent();
   var input = $('input', tr);
   update_link(input, true);

   });

});
</script>
