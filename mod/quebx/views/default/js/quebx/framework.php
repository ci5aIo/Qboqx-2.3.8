<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
elgg.provide('quebx.framework');

quebx.framework.init = function(){
$(document).ready(function(){

	/* Throws "Uncaught TypeError: $(...).infiniteScroll is not a function"
		$(".elgg-list").infiniteScroll({
		   path: ".elgg-after",
		   history: false,
		});
		*/
	/*	$(window).scroll(function () {
			$('a:visible.elgg-after').click();
		});*/
	/*	setInterval(function(){
			  if($("a.elgg-after").is(':visible')){
			    $('a:visible.elgg-after').click();
			  }
			},1000);*/
	$(document).on('click', 'span.elgg-child-menu-toggle', function(){
		$(this).parent().children('ul.elgg-child-menu').toggle();
		$(this).children('span.collapse').toggle();
		$(this).children('span.expand').toggle();
	});
	$(document).on( "click", "div#Family_tab", function(e) {
        $(this).next("div#Family_panel").slideToggle("slow");
    });
	$(document).on( "click", "div#Individual_tab", function(e) {
		$(this).next("#Individual_panel").slideToggle("slow");
		$(this).addClass("elgg-state-selected");
    });
	$(document).on( "click", "div#Acquisition_tab", function(e) {
		$(this).next("#Acquisition_panel").slideToggle("slow");
        $(this).addClass("elgg-state-selected");
    });
/*    $("#Gallery_tab").click(function(){
        var guid = $(this).data("guid");
        var folder_url = "<?php echo elgg_get_site_url(); ?>"+"market/edit_gallery/"+guid;
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_panel").load(folder_url);
        $("#Gallery_tab").addClass("elgg-state-selected");
    });
*/    $(document).on( "click", "div#Library_tab", function(e) {
        var guid = $(this).data("guid");
        var folder_url = "<?php echo elgg_get_site_url(); ?>"+"market/edit_library/"+guid;
        $(this).next("#Library_panel").slideToggle("slow");
        $(this).next("#Library_panel").load(folder_url);
        $(this).addClass("elgg-state-selected");
    });
    // From mod/market/views/default/forms/market/edit/gallery.php
    $("#edit_tab").click(function(){
        $("#edit_panel").slideToggle("slow");
        $("#edit_tab").toggleClass("elgg-state-selected");
        
        $("#add_panel").slideUp("slow");
        $("#add_tab").removeClass("elgg-state-selected");
    });
    $("#add_tab").click(function(){
        $("#add_panel").slideToggle("slow");
        $("#add_tab").toggleClass("elgg-state-selected");
        
        $("#edit_panel").slideUp("slow");
        $("#edit_tab").removeClass("elgg-state-selected");
    });
    $("#contents_add").click(function(){
        var button_value = $(this).html();
        $("#contents_panel").slideToggle("slow");
        if (button_value == '+'){$(this).html('-');}
        else                    {$(this).html('+');}
    });
    $("#components_add").click(function(){
        $("#components_panel").slideToggle("slow");
        $("#components_add").addClass("elgg-state-selected");
    });
    $("#accessories_add").click(function(){
        $("#accessories_panel").slideToggle("slow");
        $("#accessories_add").addClass("elgg-state-selected");
    });
    
    // @END From mod/market/views/default/forms/market/edit/gallery.php
    $("input[name=\'jot[aspect]\']").change(function() {
       if($(this).val() == "nothing") { 
            $("#expand_nothing_panel").show();
            $("#expand_instruction_panel").hide();
            $("#expand_observation_panel").hide();
            $("#expand_event_panel").hide();
            $("#expand_project_panel").hide();
          }
       if($(this).val() == "instruction") { 
            $("#expand_nothing_panel").hide(); 
            $("#expand_instruction_panel").show();
            $("#expand_observation_panel").hide();
            $("#expand_event_panel").hide();
            $("#expand_project_panel").hide();
          }
       if($(this).val() == "observation") { 
            $("#expand_nothing_panel").hide(); 
            $("#expand_instruction_panel").hide();
            $("#expand_observation_panel").show();
            $("#expand_event_panel").hide();
            $("#expand_project_panel").hide();
          }
       if($(this).val() == "event") { 
            $("#expand_nothing_panel").hide();
            $("#expand_instruction_panel").hide();
            $("#expand_observation_panel").hide();
            $("#expand_event_panel").show();
            $("#expand_project_panel").hide();
          }
       if($(this).val() == "project") { 
            $("#expand_nothing_panel").hide();
            $("#expand_instruction_panel").hide();
            $("#expand_observation_panel").hide();
            $("#expand_event_panel").hide();
            $("#expand_project_panel").show();
          }
    });
}); 
    $("#add_something_tab").click(function(){
        $("#add_experience_panel").slideUp("slow");
        $("#add_experience_tab").removeClass("elgg-state-selected");
        $("#add_something_panel").slideToggle("slow");
        $("#add_something_tab").addClass("elgg-state-selected");
        $("#add_receipt_panel").slideUp("slow");
        $("#add_receipt_tab").removeClass("elgg-state-selected");
    });
    $("#add_receipt_tab").click(function(){
        $("#add_something_panel").slideUp("slow");
        $("#add_something_tab").removeClass("elgg-state-selected");
        $("#add_experience_panel").slideUp("slow");
        $("#add_experience_tab").removeClass("elgg-state-selected");
        $("#add_receipt_panel").slideToggle("slow");
        $("#add_receipt_tab").addClass("elgg-state-selected");        
    });
	$("#add_experience_tab").click(function(){
	  $("#add_something_panel").slideUp("slow");
	  $("#add_something_tab").removeClass("elgg-state-selected");
	  $("#add_experience_panel").slideToggle("slow");
	  $("#add_experience_tab").addClass("elgg-state-selected");
	  $("#add_receipt_panel").slideUp("slow");
	  $("#add_receipt_tab").removeClass("elgg-state-selected");        
	});

	$("#experiences_add").click(function(){
        $("#experiences_panel").slideToggle("slow");
    });
    $("a.quebx-module-open").on("click", function(e){
        var panel           = $(this).attr('panel');
        var this_panel      = $("div[aspect=panel][panel="+panel+"]");
        var this_module     = $("div[aspect=module][panel="+panel+"]");
        var all_panels      = $("div[aspect=panel]");
        var all_modules     = $("div[aspect=module]");
        var panel_display   = this_panel.css("display"); 
        var module_display  = this_module.css("display");
        if (module_display != "block"){
            all_modules.slideUp("fast");
            this_module.css('marginLeft', '');
            this_panel.show();
            this_module.slideDown("slow");
            if (panel == "experience"){
                this_panel.css("min-height", "400px");
            }
        }
/*        if (module_display == "block"){
            if (panel_display != 'none'){
            	this_panel.slideUp("slow");
            }
        } 
        else {
        	$("div[aspect=jot]").hide();
        	this_panel.slideDown("slow");
        }*/
        /*********************/
/*		if ($(this).hasClass("quebx-module-collapsed")) {
			$(this).removeClass("quebx-module-collapsed")
		}
		else {
			$(this).addClass("quebx-module-collapsed")
		}*/
    });
/*    $("a.quebx-module-close").on("click", function(e){
        e.preventDefault();
        var panel = $(this).attr('panel');
        console.log('panel:'+panel);
        var this_module    = $("div[aspect=module][panel="+panel+"]");
        this_module.slideUp("slow");
    });
*/    
    $("a.quebx-panel-collapse-button").on("click", function(e){
    /*$("a[aspect=panel]").on("click", function(e){*/
        e.preventDefault();
        var panel = $(this).attr('panel');
        console.log('panel:'+panel);
        var this_panel     = $("div[aspect=panel][panel="+panel+"]");
        var this_module    = $("div[aspect=module][panel="+panel+"]"); 
        var panel_display  = this_panel.css("display"); 
        var module_display = $("div[aspect=module][panel="+panel+"]").css("display");
        this_module.css('marginLeft', '');
        $("div[aspect=module][panel="+panel+"]").show();
        if (panel_display == "block"){
            if (module_display != 'none'){
            	this_panel.slideUp("slow");
            }
        } 
        else {
        	$("div[aspect=panel]").hide();
        	this_panel.slideDown("slow");
        }
        /*********************/
		if ($(this).hasClass("quebx-panel-collapsed")) {
			$(this).removeClass("quebx-panel-collapsed");
			$(this).attr("title", "hide");
		}
		else {
			$(this).addClass("quebx-panel-collapsed");
			$(this).attr("title", "show");
		}
    });
/*    $(document).on("click", ".user_menu_tag", function(e){
        $(".user_menu_holder").slideToggle('slow');
    });
*/    $(document).on("click", "a.quebx-module-close", function(e){
        e.preventDefault();
        var panel = $(this).attr('panel');
        var aspect = $(this).attr('aspect');
//        var $module = $("div[aspect="+aspect+"][panel="+panel+"]");
        var this_panel  = $("div[aspect=panel][panel="+panel+"]");
        var this_module = $("div[aspect="+aspect+"][panel="+panel+"]");
        $(this_panel).fadeOut("slow");
        $(this_module).slideUp("slow");
/*        $module.animate({
        	marginLeft: parseInt($module.css('marginLeft'),10) == 0 ? $module.outerWidth() :0},500, function(){$module.hide()});*/
    });
    $(document).on( "click", "a[aspect=experience]", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        var element = $(this).data('element');
        if (element == 'qbox'){return true;} // Kill if the tab is presented in a qbox
        console.log('panel:'+panel);
        if ($("li[guid="+guid+"][panel="+panel+"][aspect=experience]").is(".elgg-state-selected")){
            $("li[guid="+guid+"][panel="+panel+"]").removeClass("elgg-state-selected");
            $("div[guid="+guid+"][panel="+panel+"]").hide();}
        else {
           $("li[guid="+guid+"][aspect=experience]").removeClass("elgg-state-selected");
           $("div[guid="+guid+"][aspect=attachments]").hide();
           $("li[guid="+guid+"][panel="+panel+"]").toggleClass("elgg-state-selected");
           $("div[guid="+guid+"][panel="+panel+"]").show();
           }
    });    
    $(document).on( "click", "a[aspect=media_input]", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=media_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=media_input]").hide();
        $("li[guid="+guid+"][panel="+panel+"][aspect=media_input]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][panel="+panel+"][aspect=media_input]").toggle();
    });
    $(document).on("click", "a[aspect=issue_input]", function(e) {
        e.preventDefault();
        var qid  = $(this).parent().data('qid'); 
        var panel = $(this).attr('panel');
        $("li[data-qid="+qid+"][aspect=issue_input]").removeClass("elgg-state-selected");
        $("div[data-qid="+qid+"][aspect=issue_input]").hide();
        $("li[data-qid="+qid+"][aspect=issue_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[data-qid="+qid+"][aspect=issue_input][panel="+panel+"]").toggle();
    });
    $(document).on("click", "a[aspect=observation_input]", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=observation_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=observation_input]").hide();
        $("li[guid="+guid+"][aspect=observation_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=observation_input][panel="+panel+"]").toggle();
    });
    $("input[name=\'jot[observation][state]\']").change(function() {
        var guid  = $(this).attr('guid'); 
    	if($(this).val() == 1) {var panel = 'observation_discoveries';}
    	if($(this).val() == 2) {var panel = 'observation_efforts';}
    	if($(this).val() == 3) {var panel = 'observation_request';}
    	if($(this).val() == 4) {var panel = 'observation_accept';}
    	if($(this).val() == 5) {var panel = 'observation_complete';}
        $("li[guid="+guid+"][aspect=observation_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=observation_input]").hide();
        $("li[guid="+guid+"][aspect=observation_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=observation_input][panel="+panel+"]").show();
    });
    $("input[name=\'jot[project][stage]\']").change(function() {
        var guid  = $(this).attr('guid'); 
    	if($(this).val() == 1) {var panel = 'project_planning';}
    	if($(this).val() == 2) {var panel = 'project_in_process';}
    	if($(this).val() == 3) {var panel = 'project_blocked';}
    	if($(this).val() == 4) {var panel = 'project_cancelled';}
    	if($(this).val() == 5) {var panel = 'project_complete';}
        $("li[guid="+guid+"][aspect=project_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=project_input]").hide();
        $("li[guid="+guid+"][aspect=project_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=project_input][panel="+panel+"]").show();
    });
    $("input[name=\'jot[event][stage]\']").change(function() {
        var guid  = $(this).attr('guid'); 
    	if($(this).val() == 1) {var panel = 'event_planning';}
    	if($(this).val() == 2) {var panel = 'event_in_process';}
    	if($(this).val() == 3) {var panel = 'event_postponed';}
    	if($(this).val() == 4) {var panel = 'event_cancelled';}
    	if($(this).val() == 5) {var panel = 'event_complete';}
        $("li[guid="+guid+"][aspect=event_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=event_input]").hide();
        $("li[guid="+guid+"][aspect=event_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=event_input][panel="+panel+"]").show();
    });
    $(document).on( "click", "a[aspect=project_input]", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=project_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=project_input]").hide();
        $("li[guid="+guid+"][aspect=project_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=project_input][panel="+panel+"]").toggle();
    });
    $(document).on( "click", "a[aspect=event_input]", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=event_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=event_input]").hide();
        $("li[guid="+guid+"][aspect=event_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=event_input][panel="+panel+"]").toggle();
    });
    $(document).on( "click", "a[aspect=instruction_input]", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=instruction_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=instruction_input]").hide();
        $("li[guid="+guid+"][aspect=instruction_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=instruction_input][panel="+panel+"]").toggle();
    });
    $(document).on("click", "a[aspect=observation_effort_input]", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=observation_effort_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=observation_effort_input]").hide();
        $("li[guid="+guid+"][aspect=observation_effort_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=observation_effort_input][panel="+panel+"]").toggle();
    });
    $(document).on("click", "a[do=show_comments]", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid');
        $("div[guid="+guid+"][panel=comments]").toggle();
    });
    
    $(document).on("click", "a.pallet-compartment", function(e) {
        e.preventDefault();
        var parent_cid = $(this).parents('ul').data('cid'),
            cid        = $(this).parent().data('cid');
        var qbox       = $('div.pallet-compartment[data-cid='+cid+']'),
            selected   = $(this).parent().hasClass('elgg-state-selected');
        console.log('cid: '+cid);
        console.log('parent_cid: '+parent_cid);
        console.log('qbox: ',qbox);
	   if(!selected){
		   $(this).parents('ul').children("li.pallet-compartment").removeClass('elgg-state-selected');
		   $(this).parent('li.pallet-compartment').addClass('elgg-state-selected');
    		$("div[data-parent-cid="+parent_cid+"]").hide();
    		qbox.show();
	   }/*// Don't toggle the compartment
	   else {
		   $(this).parent('li.pallet-compartment').removeClass('elgg-state-selected');
    		qbox.hide();
	   }*/
    });
    
    $(document).on("click", "a#properties_set", function(e) {
        e.preventDefault();
        var qid = $(this).data('qid');
        var jqid = $(this).data('jq-dropdown');
        var $properties =  $(this).parents('.qbox-body').find(jqid);
        var horizontalOffset = $(this).data('horizontal-offset');
        console.log('qid: '+qid);
        console.log('jqid:'+jqid);
/*        $(this)
            .parent("div")
            .children("div.receipt-item-properties")
            .slideToggle();
*/        
//        $("#"+qid).slideToggle();
        $properties.css('left', horizontalOffset+'px');
        $properties.slideToggle();
        
    });
/*	$(".add-progress-marker").on("click", function(e){"use strict"
	    e.preventDefault();
        var cid = "c"+Math.floor((Math.random()*200)+1);
		var line_item = $(".progress_marker_line_items").clone(true, true).contents();
 		var $input = $(line_item);
		$input.find("div.story").attr("data-cid", cid);
		$input.find("div.edit").attr("data-cid", cid);
		$input.find("span.count not_read_only").attr("data-cid", cid);
		$input.find("a.collapser").attr("id", "story_collapser_"+cid);
		$input.find("button.close").attr("id", "story_close_"+cid);
		$input.find("button.clipboard_button").attr("id", "story_copy_link_"+cid);
		$input.find("button.use_click_to_copy").attr("id", "story_copy_id_"+cid);
		$input.find("input.text_value").attr("id", "story_copy_id_value_"+cid);
		$input.find("button.clone_story").attr("id", "story_clone_button_"+cid);
		$input.find("button.history").attr("id", "story_history_button_"+cid);
		$input.find("button.delete").attr("id", "story_delete_button_"+cid);
		$input.find("textarea[data-aid=name]").attr("data-focus-id", "NameEdit--"+cid);
		$input.find("div.dropdown.story_type").children("input.honeypot").attr("id", "story_type_dropdown_"+cid+"_honeypot");
		$input.find("div.dropdown.story_type").children("a.selection.item_feature").attr("id","story_type_dropdown_"+cid);
		$input.find("div.dropdown.story_type").children("a.arrow.target").attr("id","story_type_dropdown_"+cid+"_arrow");
		
		$input.find("div.dropdown.story_estimate").children("input.honeypot").attr("id", "story_estimate_dropdown_"+cid+"_honeypot");
		$input.find("div.dropdown.story_estimate").children("a.selection").attr("id","story_estimate_dropdown_"+cid);
		$input.find("div.dropdown.story_estimate").children("a.arrow.target").attr("id","story_estimate_dropdown_"+cid+"_arrow");
		$input.find("div.dropdown.story_current_state").children("input.honeypot").attr("id", "story_current_state_dropdown_"+cid+"_honeypot");
		$input.find("div.dropdown.story_current_state").children("a.selection").attr("id","story_current_state_dropdown_"+cid);
		$input.find("div.dropdown.story_current_state").children("a.arrow.target").attr("id","story_current_state_dropdown_"+cid+"_arrow");
		$input.find("div.state.row").children("label.state.button").attr("id","story_state_button_"+cid+"_started");
		$input.find("div.state.row").children("input[aria-hidden=true]").attr("id","story_state_unscheduled_"+cid);
		$input.find("div.dropdown.story_scribe_id").children("input.honeypot").attr("id", "story_scribe_id_dropdown_"+cid+"_honeypot");
		$input.find("div.dropdown.story_scribe_id").children("a.selection").attr("id","story_scribe_id_dropdown_"+cid);
		$input.find("div.dropdown.story_scribe_id").children("a.arrow.target").attr("id","story_scribe_id_dropdown_"+cid+"_arrow");
		$input.find("div.story_participants").children("input.honeypot").attr("id", "story_participant_ids_"+cid+"_honeypot");
		$input.find("div.story_participants").children("a.add_participant").attr("id","add_participant_"+cid);
		$input.find("div.following.row").children("span.count").data("cid", cid);
		
		$input.find("div.dropdown_menu.search").children("div.search_item").children("input.search").attr("id", "story_type_dropdown_"+cid+"_search");
		$input.find("div.dropdown_menu.search").find("a.item_feature").attr("id", "feature_story_type_dropdown_"+cid);
		$input.find("div.dropdown_menu.search").find("a.item_bug").attr("id", "bug_story_type_dropdown_"+cid);
		$input.find("div.dropdown_menu.search").find("a.item_chore").attr("id", "chore_story_type_dropdown_"+cid);
		$input.find("div.dropdown_menu.search").find("a.item_release").attr("id", "release_story_type_dropdown_"+cid);
 		line_item = $input.outerHTML;
		$input.insertBefore(".new_progress_marker").outerHTML;
	});
*/    $(document).on("click", "a.expander", function(e) {
        e.preventDefault();
        var old_cid = $(this).attr('data-cid');
        var cid = "c"+Math.floor((Math.random()*200)+1);
        var container = $(this).parents('.story.model.item').parent();        
        console.log('old_cid: '.old_cid);
        $(this).parents("div.model").removeClass("draggable");
        //$(this).parents("div.model").addClass("pin");
        $(this).attr('data-cid', cid);
        $(this).parents('.rTableRow[data-cid='+old_cid+']').attr('data-cid', cid);
        $(container).attr('data-cid', cid);
        $(container).find("input[name=\'jot[observation][effort]["+old_cid+"][boqx]\']").attr('name', 'jot[observation][effort]['+cid+'][boqx]');-
        $('.rTableRow[data-cid='+cid+']').children('.rTableCell').attr('data-cid', cid);
        $('.rTableCell[data-cid='+cid+']').find('.story.model.item').attr('data-cid', cid);
        $('.story[data-cid='+cid+']').children('.story.model.item').attr('data-cid', cid);
        $('.details[data-cid='+old_cid+']').attr('data-cid', cid);
        $('.collapser[data-cid='+old_cid+']').attr('data-cid', cid);
        $('.collapser[data-cid='+cid+']').attr("id", "story_collapser_"+cid);
        $("button.close[data-cid="+old_cid+"]").attr("data-cid", cid);
        $("button.close[data-cid="+cid+"]").attr("id", "story_close_"+cid);
        $("button.close[data-cid="+cid+"]").html('Close');
        $("button.clipboard_button[data-cid="+old_cid+"]").attr("data-cid", cid);
        $("button.use_click_to_copy[data-cid="+cid+"]").attr("id", "story_copy_id_"+cid);
        $(this).parent("header.preview").next().find("textarea.NameEdit___2W_xAa_R").attr("data-focus-id", "NameEdit--"+cid);
        $(this).parent("header.preview").removeClass("expanded");
        $(this).parent("header.preview").addClass("collapsed");
        $(this).parent("header.preview").parentsUntil("div.story").parent("div.story").attr("data-cid", cid);
        $(this).parent("header.preview").next().find("div.edit").attr("data-cid", cid);
        $(this).parent("header.preview").next().find("span.count not_read_only").attr("data-cid", cid);
        $(this).parent("header.preview").next().find("input.text_value").attr("id", "story_copy_id_value_"+cid);
        $(this).parent("header.preview").next().find("button.clone_story").attr("id", "story_clone_button_"+cid);
        $(this).parent("header.preview").next().find("button.receipt_import").attr("id", "receipt_import_button_"+cid);
        $(this).parent("header.preview").next().find("button.history").attr("id", "story_history_button_"+cid);
        $(this).parent("header.preview").next().find("button.delete").attr("id", "story_delete_button_"+cid);
        $(this).parent("header.preview").next().find("div.dropdown.story_type").children("input.honeypot").attr("id", "story_type_dropdown_"+cid+"_honeypot");
        $(this).parent("header.preview").next().find("div.dropdown.story_type").children("a.selection.item_feature").attr("id","story_type_dropdown_"+cid);
        $(this).parent("header.preview").next().find("div.dropdown.story_type").children("a.arrow.target").attr("id","story_type_dropdown_"+cid+"_arrow");

		$(this).parent("header.preview").next().find("div.dropdown.story_estimate").children("input.honeypot").attr("id", "story_estimate_dropdown_"+cid+"_honeypot");
		$(this).parent("header.preview").next().find("div.dropdown.story_estimate").children("a.selection").attr("id","story_estimate_dropdown_"+cid);
		$(this).parent("header.preview").next().find("div.dropdown.story_estimate").children("a.arrow.target").attr("id","story_estimate_dropdown_"+cid+"_arrow");
		$(this).parent("header.preview").next().find("div.dropdown.story_current_state").children("input.honeypot").attr("id", "story_current_state_dropdown_"+cid+"_honeypot");
		$(this).parent("header.preview").next().find("div.dropdown.story_current_state").children("a.selection").attr("id","story_current_state_dropdown_"+cid);
		$(this).parent("header.preview").next().find("div.dropdown.story_current_state").children("a.arrow.target").attr("id","story_current_state_dropdown_"+cid+"_arrow");
		$(this).parent("header.preview").next().find("div.state.row").children("label.state.button").attr("id","story_state_button_"+cid+"_started");
		$(this).parent("header.preview").next().find("div.state.row").children("input[aria-hidden=true]").attr("id","story_state_unscheduled_"+cid);
		$(this).parent("header.preview").next().find("div.dropdown.story_scribe_id").children("input.honeypot").attr("id", "story_scribe_id_dropdown_"+cid+"_honeypot");
		$(this).parent("header.preview").next().find("div.dropdown.story_scribe_id").children("a.selection").attr("id","story_scribe_id_dropdown_"+cid);
		$(this).parent("header.preview").next().find("div.dropdown.story_scribe_id").children("a.arrow.target").attr("id","story_scribe_id_dropdown_"+cid+"_arrow");
		$(this).parent("header.preview").next().find("div.story_participants").children("input.honeypot").attr("id", "story_participant_ids_"+cid+"_honeypot");
		$(this).parent("header.preview").next().find("div.story_participants").children("a.add_participant").attr("id","add_participant_"+cid);
		$(this).parent("header.preview").next().find("div.following.row").children("span.count").data("cid", cid);
		
        $(this).parent("header.preview").next().find("div.dropdown_menu.search").children("div.search_item").children("input.search").attr("id", "story_type_dropdown_"+cid+"_search");
        $(this).parent("header.preview").next().find("div.dropdown_menu.search").find("a.item_feature").attr("id", "feature_story_type_dropdown_"+cid);
        $(this).parent("header.preview").next().find("div.dropdown_menu.search").find("a.item_bug").attr("id", "bug_story_type_dropdown_"+cid);
        $(this).parent("header.preview").next().find("div.dropdown_menu.search").find("a.item_chore").attr("id", "chore_story_type_dropdown_"+cid);
        $(this).parent("header.preview").next().find("div.dropdown_menu.search").find("a.item_release").attr("id", "release_story_type_dropdown_"+cid);        

		$(this).parent("header.preview").next().find("div.details").removeClass("collapsed");
        $(this).parent("header.preview").next().find("div.details").addClass("expanded");
		$(this).parent("header.preview").next(".details").removeClass("collapsed");
        $(this).parent("header.preview").next(".details").addClass("expanded");
        $(this).parents("div.rTableRow.story").addClass("pin");
        $(this).parents("div.rTableRow.story").css("cursor", "");
    });
    $(document).on("click", "button.close", function(e) {
        e.preventDefault();
        var cid  = $(this).attr("data-cid"),
            qid  = $(this).attr('data-qid'),
            egg  = $(this).hasClass('egg');
        var eggs = parseInt($('span.qbox-menu[data-qid='+qid+']').attr('eggs'), 10);
        //var n = $(this).attr("id").indexOf("story_close_");
        //var cid = $(this).attr("id").substring(n);
        var str = $(this).parents("section.edit").find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
        if (isNaN(eggs)){eggs = 0;}
        $(this).parents(".model[data-cid="+cid+"]").children("header.preview").removeClass("collapsed");
        $(this).parents(".model[data-cid="+cid+"]").children("header.preview").addClass("expanded");
        $(this).parents(".model[data-cid="+cid+"]").children("header.preview").find("span.story_name").html(str);
        $(this).parents(".rTableRow.story").removeClass("pin");
        $(this).parents(".rTableRow.story").css("cursor", "move");
        $(this).parents(".details[data-cid="+cid+"]").removeClass("expanded");
        $(this).parents(".details[data-cid="+cid+"]").addClass("collapsed");
        $('span.qbox-menu[data-qid='+qid+']').show();
        if (egg){
        	$('span.qbox-menu[data-qid='+qid+']').attr('eggs', ++eggs);
        	$(this).removeClass('egg');
        }
    });
    $(document).on('click', 'a.maximize',function(e){
       e.preventDefault();
       var $boqx = $(this).parents('.EffortEdit_fZJyC62e');
       $boqx.addClass('maximized');
       $(this).removeClass('maxmize');
       $(this).addClass('restore');
       $(this).attr('title', 'Restore view');
    });
    $(document).on('click', 'a.restore',function(e){
       e.preventDefault();
       var $boqx = $(this).parents('.EffortEdit_fZJyC62e');
       $boqx.removeClass('maximized');
       $(this).addClass('maxmize');
       $(this).removeClass('restore');
       $(this).attr('title', 'Switch to a full view');
    });
    $(document).on('click', 'span.qbox-section-remove', function(e){
        e.preventDefault();
        var qid = $(this).data('cid');
        $(this).parents('li').removeClass('elgg-state-selected');
        $(this).parent('div').remove();
        $('div#'+cid).remove();
    });
    $(document).on("click", "button.clipboard_button", function(e){     //Source: https://stackoverflow.com/questions/22581345/click-button-copy-to-clipboard-using-jquery
        e.preventDefault();
        var str = $(this).data("clipboard-text");
        var $temp = $("<input>");                             //Creates a temporary hidden text field.
        $("body").append($temp);                              //Copies the content of the element to that text field. 
        $temp.val(str).select();                              //Selects the content of the text field.
        document.execCommand("copy");                         //Executes the command 'copy'
        $temp.remove();                                       //Removes the temporary text field.
     });
    $(document).on("click", "a.collapser", function(e) {
          e.preventDefault();
          var cid = $(this).attr("data-cid");
          var str = $(this).parent().find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
          console.log('cid: '+cid);
          $(".model[data-cid="+cid+"]").children("header.preview").removeClass("collapsed");
          $(".model[data-cid="+cid+"]").children("header.preview").addClass("expanded");
          $(".model[data-cid="+cid+"]").children("header.preview").find("span.story_name").html(str);
          $(".rTableRow.story[data-cid="+cid+"]").removeClass("pin");
          $(".rTableRow.story[data-cid="+cid+"]").css("cursor", "move");
          $(".details[data-cid="+cid+"]").removeClass("expanded");
          $(".details[data-cid="+cid+"]").addClass("collapsed");
      });
    $(document).on("click", "a.collapser-effort", function(e) {
        e.preventDefault();
        var cid = $(this).attr("data-cid"),
            show = true;
        var name = $(this).parent().find("textarea[data-focus-id=NameEdit--"+cid+"]").val(),
            state       = $(this).parents('.Effort__CPiu2C5N').attr('data-aid');
        var $this_panel = $(this).parents('.Effort__CPiu2C5N').children('.EffortEdit_fZJyC62e');
        var $show_panel = $(this).parents('.Effort__CPiu2C5N').children('.EffortShow_haqOwGZY');
        var $add_panel  = $(this).parents('.Effort__CPiu2C5N').children('.AddSubresourceButton___S1LFUcMd');
        console.log('cid: '+cid);
        if(typeof name == 'undefined'){
            show = false;
        }
        else {
            if (name.length == 0)
                show = false;
        }
        $this_panel.removeClass('maximized');
        if (state == 'add' || state == 'edit'){
	        if (show){
	            $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+name+'</p>');
	            // remove vestigal spans
	            $show_panel.find('span.TaskShow__description___qpuz67f').remove();
	            $show_panel.find('span.TaskShow__service_items___2wMiVig').remove();
	            if (state == 'add'){                                       // hide delete button when state = add
	                $show_panel.find('button.IconButton___4wjSqnXU').hide();
	            }
	            $show_panel.show();
	            $this_panel.hide();
	        }
	        else {
	            $add_panel.show();
	            $this_panel.hide();
	        }
        }
        else{                                        // state == 'view'
            $show_panel.show();
            $this_panel.hide();
        }
    });
    $(document).on("click", '.elgg-input-checkbox.boqx-unpack', function(e){
        var scope = $(this).data('name').replace('unpack-',''),
            state = $(this).prop('checked'),
            cid   = $(this).data('cid'),
            rows,
            rows_checked = 0,
            items,
            el,
            item_is,
            item_isnt;
        items = $('input[type=checkbox][data-name=unpack-this][data-cid='+cid+']');
        rows  = $('a.new-item[data-cid='+cid+']').attr('data-rows');
        el    = $('input[type=checkbox][data-cid='+cid+'][data-name=unpack-all]');
        items.each(function(){
          if ($(this).prop('checked')) rows_checked++
        });
        if ($(this).hasClass('closed')){
          item_is   = 'closed';
          item_isnt = 'opened';
        }
        if ($(this).hasClass('opened')){
          item_is   = 'opened';
          item_isnt = 'closed';
        }
        console.log('state: '+state);
        console.log('cid: '+cid);
        console.log('item_is '+item_is);
        console.log('rows_checked: '+rows_checked);
        if (scope == 'all'){
            items.each(function(){
               $(this).prop('checked', -(state));
               $(this).removeClass(item_is);
               $(this).addClass(item_isnt);
            });
        }
        if (scope == 'this') {
            $(this).prop('checked', -(state));
            $(this).removeClass(item_is);
            $(this).addClass(item_isnt);
            el.removeClass(item_is);
            el.addClass(item_isnt);
            if (rows>1 && rows>rows_checked){
               el.prop('indeterminate', true);
            }
            if (rows==1 || rows==rows_checked || rows_checked==0){
               el.prop('indeterminate', false);
               el.prop('checked', -(state));
            }
        }
    });
    $(document).on("click", "a.collapser-service-item", function(e) {
        e.preventDefault();
        var cid = $(this).attr("data-cid"),
            show_service = true;
        var service_name = $(this).parent().find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
        var service_desc = $(this).parent().find("textarea[data-focus-id=ServiceEdit--"+cid+"]").val(),
            state        = $(this).parents('.Effort__CPiu2C5N').attr('data-aid');
/*	    if (typeof service_name == 'undefined')
	    	show_service = false;
*/		if (service_name.length==0)
			show_service = false;
        var $this_panel  = $(this).parent('.TaskEdit___1Xmiy6lz');
        var $show_panel  = $(this).parent('.TaskEdit___1Xmiy6lz').parents('.ServiceEffort__26XCaBQk').find('.TaskShow___2LNLUMGe');
        var $add_panel   = $(this).parent('.TaskEdit___1Xmiy6lz').parents('.ServiceEffort__26XCaBQk').find('.AddSubresourceButton___2PetQjcb');
        console.log('cid: '+cid);
        console.log('service_name: '+service_name);
        console.log('service_name.length: '+service_name.length);
        console.log('show_service: '+show_service);

        if (state == 'add' || state == 'edit'){
		    if (show_service){
	            $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+service_name+'</p>');
	            $show_panel.find('.TaskShow__description___qpuz67f').html('<p>'+service_desc+'</p>');
	            if (state == 'add'){                                       // hide delete button when state = add
	                $show_panel.find('button.IconButton___2y4Scyq6').hide();
	            }
	            $show_panel.show();
	            $this_panel.hide();
	        }
	        else {
	            $add_panel.show();
	            $this_panel.hide();
	        }
        }
        else{                                        // state == 'view'
            $show_panel.show();
            $this_panel.hide();
        }
    });
/*    $(document).on("click", "a.collapser-receipt-item", function(e) {
        e.preventDefault();
        var cid = $(this).attr("data-cid"),
            show_service = true;
        var service_name = $(this).parent().find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
        var $merchant_add = $(this).parents('#'+cid).find("[data-focus-id=MerchantAdd--"+cid+"]");
        var service_desc = $(this).parent().find("textarea[data-focus-id=ServiceEdit--"+cid+"]").val(),
            state        = $(this).parents('.Effort__CPiu2C5N').attr('data-aid');
        if (service_name.length==0)
               show_service = false;
        var $this_panel  = $(this).parent('.TaskEdit___1Xmiy6lz');
        var $show_panel  = $(this).parent('.TaskEdit___1Xmiy6lz').parents('.ServiceEffort__26XCaBQk').find('.TaskShow___2LNLUMGe');
        var $add_panel   = $(this).parent('.TaskEdit___1Xmiy6lz').parents('.ServiceEffort__26XCaBQk').find('.AddSubresourceButton___2PetQjcb'),
            merchant     = $this_panel.find('h3.elgg-listing-summary-title').text() || $this_panel.find('input.elgg-input-group-picker').val(),
            receipt_total = $("span#"+cid+"_total_raw").text();
        var $merchant_name = $merchant_add.find('.elgg-autocomplete-item').attr('title'),
            merchant_name;
        if (typeof $merchant_name != 'undefined')
                    merchant_name  = $merchant_name.text();
        else       $merchant_name  = $merchant_add.find('input:text.ui-autocomplete-input');
        if (typeof $merchant_name != 'undefined')
                    merchant_name  = $merchant_name.val();
        console.log('cid: '+cid);
        console.log('$merchant_add = ',$merchant_add);
        console.log('merchant_name: '+merchant_name);
        console.log('receipt_total: '+receipt_total);
        console.log('service_name: '+service_name);
        console.log('service_name.length: '+service_name.length);
        console.log('show_service: '+show_service);

        if (state == 'add' || state == 'edit'){
              if (show_service){
                 $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+service_name+'</p>');
                 $show_panel.find('.TaskShow__description___qpuz67f').html('<p>'+service_desc+'</p>');
                 $show_panel.find('.TaskShow__service_items___2wMiVig').html('<p>'+receipt_total+'</p>');
                 if (state == 'add'){                                       // hide delete button when state = add
                     $show_panel.find('button.IconButton___2y4Scyq6').hide();
                 }
                 $show_panel.show();
                 $this_panel.hide();
             }
             else {
                 $add_panel.show();
                 $this_panel.hide();
             }
        }
        else{                                        // state == 'view'
            $show_panel.show();
            $this_panel.hide();
        }
    });
*/
    $(document).on("click", "a.collapser-receipt-item", function(e) {
        e.preventDefault();
        var cid = $(this).attr("data-cid");
        var $boqx = $(document).find('#'+cid);
        var fill_level = $boqx.attr('boqx-fill-level'),
            $this_panel  = $boqx.children('.TaskEdit___1Xmiy6lz');
        var $show_panel  = $boqx.children('.TaskShow___2LNLUMGe');
        var $add_panel   = $boqx.children('.AddSubresourceButton___2PetQjcb');
        if (fill_level == 'full'){
           $show_panel.show();
           $this_panel.hide();
        }
        else {
           $add_panel.show();
           $this_panel.hide();
         }
        
    });
    $(document).on("click", ".DescriptionShow___3-QsNMNj", function(e) {
        e.preventDefault();
        var edit = $(this).next(".DescriptionEdit___1FO6wKeX");
        var textarea = edit.find(".AutosizeTextarea__container___31scfkZp").children('textarea');
        var cid = edit.parents('.edit.details').data('cid');
        autosize($(textarea));
        $(edit).show();
        $(this).hide();
        $(textarea).focus();
    });
    $(document).on("click", ".ServiceShow___3-QsNMNj", function(e) {
        e.preventDefault();
        var edit = $(this).next(".ServiceEdit___1FO6wKeX");
        var textarea = edit.find(".AutosizeTextarea__container___31scfkZp").children('textarea');
        var cid = edit.parents('.edit.details').data('cid');
        autosize($(textarea));
        $(edit).show();
        $(this).hide();
        $(textarea).focus();
    });
    $(document).on("click", ".TaskShow___2LNLUMGe", function(e) {
        e.preventDefault();
        var edit = $(this).next(".TaskEdit___1Xmiy6lz");
        $(edit).show();
        $(this).hide();        
    });
    $(document).on("click", ".EffortShow_haqOwGZY", function(e) {
        e.preventDefault();
        var edit = $(this).next(".EffortEdit_fZJyC62e");
        $(edit).show();
        $(this).hide();        
    });
    $(document).on('click', '.AutosizeTextarea__container___31scfkZp', function(){
        var textarea = $(this).children('textarea');
        autosize($(textarea));
        $(textarea).focus();
    });
    $(document).on('click', '.AddSubresourceButton___2PetQjcb, .AddSubresourceButton___S1LFUcMd', function(){
        var $cid = $(this).data('cid');
        $(this).hide();
        $('.TaskEdit___1Xmiy6lz[data-cid = '+$cid+']').show();
        $('.EffortEdit_fZJyC62e[data-cid = '+$cid+']').show();
    });
    $(document).on("click", "button[data-aid=cancel]", function (e) {
        e.preventDefault();
    });
    $(".edit_experience").hide();

    $(document).on("click", "a.done", function(e){
        e.preventDefault();
        // Do nothing else.
    });

	$(document).on("click", "a.hierarchy-expand-button", function(e){
		e.preventDefault();
		$(this).parent().children("ul.hierarchy").attr("data-state", "expanded");
		$(this).removeClass('hierarchy-expand-button');
		$(this).addClass('hierarchy-collapse-button');
		$(this).text('-');
		$(this).attr('title','collapse');
	});

	$(document).on("click", "a.hierarchy-collapse-button", function(e){
		e.preventDefault();
		$(this).parent().children("ul.hierarchy").attr("data-state", "collapsed");
		$(this).removeClass("hierarchy-collapse-button");
		$(this).addClass("hierarchy-expand-button");
		$(this).text('+');
		$(this).attr('title','expand');
	});  
    $(document).on( "click", "button#qboxClose", function(e) {
        e.preventDefault();
        var qid         = $(this).data('qid'),
            cid         = $(this).data('cid'),
            master_boqx = $(this).parents('.jq-dropdown.qboqx'),
            qbox;
        var master_id   = $(master_boqx).attr('id');
        console.log('master_id: '+master_id);
        if (typeof qid != 'undefined'){
            qbox        = $('div.qbox-content-expand#'+qid);
            if (typeof qbox != 'undefined') { 
    	  		qbox.remove();
    	  		return true;
            }
            else {qbox      = $('div.jq-dropdown#'+qid);}
            if (typeof qbox != 'undefined'){
            	qbox.remove();
            	return true;
            }
        }
        if (typeof cid != 'undefined') {
            console.log('cid: '+cid);
            qbox      = $('div.qboqx-dropdown#'+cid);
        	if (typeof qbox != 'undefined'){
            	    qbox.remove();
        	}
        }
	    $('div.jq-dropdown#'+master_id).show();
        console.log('done');
    });
    $(document).on('click', 'button#qboxMinimize', function(e){
        e.preventDefault();
        var contentWidth = $(this).parents('.qbox').find('div#qboxLoadedContent').width();
        $(this).parents('.qbox').css('min-height', '0');
        $(this).parents('.qbox').find('div#qboxLoadedContent').css('width', contentWidth);
        $(this).parents(".qbox").find('div.qbox-body').hide();
        $(this).children('span').removeClass('fa-window-minimize');
        $(this).children('span').addClass('fa-window-restore');
        $(this).children('span').attr('title', 'Restore');
        $(this).attr('id', 'qboxRestore');
    });
    $(document).on('click', 'button#qboxRestore', function(e){
        e.preventDefault();
        $(this).parents(".qbox").find('div.qbox-body').show();
        $(this).children('span').removeClass('fa-window-restore');
        $(this).children('span').addClass('fa-window-minimize');
        $(this).children('span').attr('title', 'Minimize');
        $(this).attr('id', 'qboxMinimize');
    }); 
    $(document).on( "click", "button.inlineClose", function(e) {
        e.preventDefault();
        var qid         = $(this).data('qid'),
            cid         = $(this).data('cid'),
            perspective = $(this).data('perspective');
        console.log('qid: '+qid);
        console.log('cid: '+cid);
        console.log('perspective: '+perspective);
        if (typeof qid != 'undefined'){
    	  	$("div.inline-content-expand[data-qid='"+qid+"']").remove();
    	    $('div.inline-container#'+qid).remove();
    	    $('div.jq-dropdown#'+qid).remove();
            return true;}
        else {$(this).parents('.inline-content-expand').remove();
             return true;}
	    if (perspective == 'add' && typeof cid != 'undefined'){
	    	$('div.inline-content-expand[data-cid='+cid+']').remove();
	    }	    
    });
    $(document).on('click', 'button.inlineMinimize', function(e){
        e.preventDefault();
        var contentWidth = $(this).parents('.qbox').find('div#inlineLoadedContent').width();
        $(this).parents('.inline').css('min-height', '0');
        $(this).parents('.inline').find('div.inlineLoadedContent').css('width', contentWidth);
        $(this).parents(".inline").find('div.inline-body').hide();
        $(this).children('span').removeClass('fa-window-minimize');
        $(this).children('span').addClass('fa-window-restore');
        $(this).children('span').attr('title', 'Restore');
        $(this).attr('id', 'inlineRestore');
    });
    $(document).on('click', 'button#inlineRestore', function(e){
        e.preventDefault();
        $(this).parents(".inline").find('div.inline-body').show();
        $(this).children('span').removeClass('fa-window-restore');
        $(this).children('span').addClass('fa-window-minimize');
        $(this).children('span').attr('title', 'Minimize');
        $(this).attr('id', 'inlineMinimize');
    }); 
    $(document).on( "click", "button#maximizedClose", function(e) {
        e.preventDefault();
        var qid         = $(this).data('qid');
	  	$('div.maximized-content-expand#'+qid).remove();
	    $('div.maximized-container#'+qid).remove();
    });
    $(document).on('click', 'button#maximizedMinimize', function(e){
        e.preventDefault();
        var contentWidth = $(this).parents('.qbox').find('div#maximizedLoadedContent').width();
        $(this).parents('.maximized').css('min-height', '0');
        $(this).parents('.maximized').find('div#maximizedLoadedContent').css('width', contentWidth);
        $(this).parents(".maximized").find('div.maximized-body').hide();
        $(this).children('span').removeClass('fa-window-minimize');
        $(this).children('span').addClass('fa-window-restore');
        $(this).children('span').attr('title', 'Restore');
        $(this).attr('id', 'maximizedRestore');
    });
    $(document).on('click', 'button#maximizedRestore', function(e){
        e.preventDefault();
        $(this).parents(".maximized").find('div.maximized-body').show();
        $(this).children('span').removeClass('fa-window-restore');
        $(this).children('span').addClass('fa-window-minimize');
        $(this).children('span').attr('title', 'Minimize');
        $(this).attr('id', 'maximizedMinimize');
    }); 
    $(document).on('click', 'button.IconButton___2y4Scyq6', function(e){
        e.preventDefault();
        var cid        = $(this).data('cid'),
            parent_cid = $(this).data('parent-cid');
        var eggs = parseInt($('span.tasks-count[data-cid='+parent_cid+']').attr('eggs'), 10);
        console.log('button.IconButton___2y4Scyq6.cid: '+cid);
 	   $('span.tasks-count[data-cid='+parent_cid+']').attr('eggs', eggs-1);
        $('div.ServiceEffort__26XCaBQk[data-cid='+cid+']').remove();
    }); 
    $(document).on('click', 'button.IconButton___4wjSqnXU', function(e){
        e.preventDefault();
        var cid        = $(this).data('cid'),
            parent_cid = $(this).data('parent-cid');
        var eggs = parseInt($('span.tasks-count[data-cid='+parent_cid+']').attr('eggs'), 10);
        console.log('button.IconButton___4wjSqnXU.cid: '+cid);
 		$('span.tasks-count[data-cid='+parent_cid+']').attr('eggs', eggs-1);
        $('div.Effort__CPiu2C5N[data-cid='+cid+']').remove();
    });
    $(document).on('click', '.IconButton___23o4ips', function(e) {
        e.preventDefault();
        var item_guid  = $(this).data('item-guid'),
           qid         = $(this).data('qid'),
           element     = $(this).data('element')
           aspect      = $(this).data('aspect'),
           section     = $(this).data('section'),
           perspective = $(this).data('perspective');
        var element = $(this).data('aid');
        var item_count = $("."+aspect+"-"+section+"-count[data-qid='"+qid+"']").attr('data-count');
        
        console.log('aspect: '+aspect);
        console.log('section: '+section);
        console.log('qid: '+qid);
        console.log('item_count string: '+"."+aspect+"-"+section+"-count[data-qid='"+qid+"']");
        console.log('item_count: '+item_count);
		   
        $(this).parents('li[data-guid='+item_guid+']').remove();
	    $("."+aspect+"-"+section+"-count[data-qid='"+qid+"']").attr('data-count', --item_count);
	    if (item_count > 0){$("."+aspect+"-"+section+"-count[data-qid='"+qid+"']").html(" ("+item_count+")");}
	    else               {$("."+aspect+"-"+section+"-count[data-qid='"+qid+"']").html("");}
    });
    $(".qbox").resizable({
        handles: 's, se',minHeight: 57
    });

    $(document).on("click", "input.jq-dropdown-menu-option", function() {
        var guid = $(this).val();
        var name = $(this).parent().find('a.recipient').text();
//        var name = $(this).data('display-name');
        console.log('guid: '+guid);
        var qbox = $(this).parents('div.qbox');
        var aspect = $(qbox).find('input#q-aspect').val();
        var save   = $(qbox).find('button#qboxSave'); 
        var status = $(this).data('status') 
        if (name   == ''){name   = null;}
        if (aspect == ''){aspect = null;}
        if (status == 'new_user'){
            name = $(this).next("input:text.recipient_input").val(); 
            if (name == ''){name = null;}
            guid = name;
        }
        console.log('name: '+name);
        if (status == 'new_user' && name != null){
            $(this).parents('div.rTableRow').children('span.new_user_email').show();
            $(this).parents('div.rTableRow').find('input.elgg-input-email').focus();}
        $(qbox).find('span.qbox-recipient').html(name);
        $(qbox).find('input#recipient').attr('value', guid);
        if (name==null){
		             $(save).prop('disabled', true);
				 	 $(save).css('cursor','unset');

				 	 if (aspect != null){$(save).children('span.fa-check').attr('title','Please select a recipient to save');}
				 	 else               {$(save).children('span.fa-check').attr('title','Please select a recipient and an action to save');}
		}
		else{
			if (status != 'new_user'){
				$(this).parents('div.rTableRow').children('span.new_user_email').hide();}
            if(aspect != null){
				$(save).prop('disabled', false);
			    $(save).css('cursor','pointer');
				$(save).children('span.fa-check').attr('title','Save');
			}
			else {$(save).children('span.fa-check').attr('title','Please select an action to save');}
		}
    });
    $(document).on("focus", "input:text.recipient_input", function(){
        var qbox = $(this).parents('div.qbox');
        var save = $(qbox).find('button#qboxSave');
        var name = $(this).val();
        var aspect = $(qbox).find('input#q-aspect').val();
        if (name == '')  {name   = null;}
        if (aspect == ''){aspect = null;}
        $(this).prev('input:radio').prop('checked', true);
        if (name==null){
		             $(save).prop('disabled', true);
				 	 $(save).css('cursor','unset');
				 	 if (aspect != null){$(save).children('span.fa-check').attr('title','Please select a recipient to save');}
				 	 else               {$(save).children('span.fa-check').attr('title','Please select a recipient and an action to save');}
		}
        else {
        	$(qbox).find('span.new_user_email').show();
        	//$(this).parents('div.rTableRow').children('span.new_user_email').show();
            if(aspect==null){$(save).prop('disabled', true);
					 	     $(save).css('cursor','unset');
					 	     $(save).children('span.fa-check').attr('title','Please select an action to save');}
		 	else            {$(save).removeAttr('disabled');
	                         $(save).children('span.fa-check').attr('title','Save');}
        }
    });
    $(document).on('blur', 'textarea.AutosizeTextarea__textarea___1LL2IPEy', function(){
        contents = $(this).val();
        this_container = $(this).parents('.DescriptionEdit___1FO6wKeX');
        show_container =  $(this_container).parent().children('.DescriptionShow___3-QsNMNj');
        $(show_container).text(contents)
        $(show_container).show();
        $(this_container).hide();
        
    });
    $(document).on("blur", "input:text.recipient_input", function(){
        var name = $(this).val();
        var qbox = $(this).parents('div.qbox');
        var save = $(qbox).find('button#qboxSave');
        var aspect = $(qbox).find('input#q-aspect').val();
        $(qbox).find('span.qbox-recipient').html(name);
        if (name == '')  {name   = null;}
        if (aspect == ''){aspect = null;}
        $(qbox).find('input#recipient').attr('value', name);
        if (name==null){
                     $(save).prop('disabled', true);
    			 	 $(save).css('cursor','unset');
 			         $(this).parents('div.rTableRow').children('span.new_user_email').hide();
     			 	 if (aspect != null){$(save).children('span.fa-check').attr('title','Please select a recipient to save');}
				 	 else               {$(save).children('span.fa-check').attr('title','Please select a recipient and an action to save');}
		}
        else{
            $(this).parents('div.rTableRow').children('span.new_user_email').show();
            $(this).parents('div.rTableRow').find('input.elgg-input-email').focus();
            if(aspect==null){$(save).prop('disabled', true);
					 	   $(save).css('cursor','unset');
					 	   $(save).children('span.fa-check').attr('title','Please select an action to save');}
			else          {$(save).removeAttr('disabled');
				           $(save).children('span.fa-check').attr('title','Save');}
			}
    });
    $(document).on('click', 'a.qbox-q2', function(e) {
        e.preventDefault();
        var selected = $(this).parents('li.qbox-q2').hasClass('elgg-state-selected');
  	    var aspect   = $(this).attr('aspect');
        var qbox     = $(this).parents('div.qbox');
        var name     = $(qbox).find('input#recipient').attr('value');
        var save     = $(qbox).find('button#qboxSave');
        var qid      = $(this).parents('ul').attr('qid');
        var qid_n    = $(this).data('qid');
        var $this_panel = $(this).parents('div#'+qid).find('div.option-panel#'+qid_n);
        var $all_panels = $(this).parents('div#'+qid).find('div.option-panel');
	    if(!selected){
		   $(this).parents('ul').find("li.qbox-q2").removeClass('elgg-state-selected');
		   $(this).parents('li.qbox-q2').addClass('elgg-state-selected');
		   
	    }
	    else {
		   $(this).parents('li.qbox-q2').removeClass('elgg-state-selected');
		   aspect = null;
	    }
        $(qbox).find('input#q-aspect').attr('value', aspect);
        $(qbox).find('.recipientRow').show();
        if (name=='') {$(save).children('span.fa-check').attr('title','Please select a recipient');}
        else          {if(aspect==null){$(save).prop('disabled', true);
							 	      $(save).css('cursor','unset');
								 	  $(save).children('span.fa-check').attr('title','Please select an action');}
					   else          {$(save).prop('disabled', false);
							          $(save).css('cursor','pointer');
							          $(save).children('span.fa-check').attr('title','Save');}
					  }
		if (aspect == 'donate'){$(qbox).find('input[name=\'jot[transfer][donate][stay_connected]\']').prop('required', true);
		                        $(qbox).find('input[name=\'jot[transfer][recipient]\']').prop('required', true);}
		else                   {$(qbox).find('input[name=\'jot[transfer][donate][stay_connected]\']').removeAttr('required');}
		if (aspect == 'trash') {$(qbox).find('input[name=\'jot[transfer][recipient]\']').removeAttr('required');
		                        $(qbox).find('.recipientRow').hide();}
		$all_panels.hide();
		$this_panel.show();
    });
    $(document).on('click', 'a.qbox-q3', function(e) {
        e.preventDefault();
        var selected = $(this).parents('li.qbox-q3').hasClass('elgg-state-selected');
  	    var aspect   = $(this).attr('aspect');
        var qbox_tabs = $(this).parents('.quebx-tabs');
        var qbox     = $(qbox_tabs).next('.qbox-details');
        var save     = $(qbox).find('button#qboxSave');
        var qid      = $(qbox).attr('qid');
        var qid_n    = $(this).data('qid');
        var $this_panel = $(qbox).find('div.option-panel#'+qid_n);
        var $all_panels = $(qbox).find('div.option-panel');
	    if(!selected){
		   $(qbox_tabs).find("li.qbox-q3").removeClass('elgg-state-selected');
		   $(this).parents('li.qbox-q3').addClass('elgg-state-selected');
		   
	    }
	    else {
		   $(this).parents('li.qbox-q3').removeClass('elgg-state-selected');
		   aspect = null;
	    }
		$all_panels.hide();
		$this_panel.show();
    });
   $(document).on('change','input[name="jot[transfer][donate][delivery]"]', function(e){
	   var $delivery_address = $(this).parents('ul').parent().next('div.inset');
	   if ($(this).val()=='ship'){
		   $delivery_address.show();
		   $delivery_address.find('input[name="jot[transfer][donate][recipient_street_1]"]').attr('required', true);
		   $delivery_address.find('input[name="jot[transfer][donate][recipient_city]"]').attr('required', true);
		   $delivery_address.find('input[name="jot[transfer][donate][recipient_state]"]').attr('required', true);
		   $delivery_address.find('input[name="jot[transfer][donate][recipient_postal_code]"]').attr('required', true);
	   }
	   else {
		   $(this).parents('ul').parent().next('div.inset').hide();
		   $delivery_address.find('input[name="jot[transfer][donate][recipient_street_1]"]').removeAttr('required');
		   $delivery_address.find('input[name="jot[transfer][donate][recipient_city]"]').removeAttr('required');
		   $delivery_address.find('input[name="jot[transfer][donate][recipient_state]"]').removeAttr('required');
		   $delivery_address.find('input[name="jot[transfer][donate][recipient_postal_code]"]').removeAttr('required');
	   }
   });
   $(document).on('change','input[name="jot[transfer][donate][tax_deductible]"]', function(e){
	   if($(this).prop("checked")){
		   $(this).next('div.donate-value').attr('style', 'visibility:visible;');
	   }
	   else {$(this).next('div.donate-value').attr('style', 'visibility:hidden;');
	   }
   });/*
   $(document).on('click', 'a.transfer-view', function(e){
	   e.preventDefault();
	   var hOffset = $(this).data('horizontal-offset');
	   var vOffset = $(this).data('vertical-offset');
	   var find_id = $(this).data('jq-dropdown');
	   var menu    = $(this).parent().find(find_id);
	   menu.css('left', hOffset);
	   menu.css('top', vOffset);
	   menu.parent().show();
   });*/
   $(document).on('change', '.preorder-flag', function(e){
	   if($(this).prop("checked"))
		    {$(this).parents('.rTableRow').find('div.jot-preorder').attr('style', 'visibility:visible;');}
	   else {$(this).parents('.rTableRow').find('div.jot-preorder').attr('style', 'visibility:hidden;');}
   });
   $(document).on('change', 'input[data-name=received]', function(e){
	   if ($(this).prop('checked'))
			   {var qty = $(this).parents('div.receive_item').find('input[data-name=qty]').attr('value');
			    $(this).parents('div.receive_item').find('input[data-name=recd_qty]').val(qty);
			    $(this).parents('div.receive_item').find('input[data-name=recd_qty]').prop('readonly', true);
			    $(this).parents('div.receive_item').find('input[data-name=bo_qty]').prop('readonly', true);
			    $(this).parents('div.receive_item').find('input[data-name=bo_delivery_date]').prop('readonly', true);
			   }
	   else    {$(this).parents('div.receive_item').find('input[data-name=recd_qty]').prop('readonly', false);
 		        $(this).parents('div.receive_item').find('input[data-name=bo_qty]').prop('readonly', false);
		        $(this).parents('div.receive_item').find('input[data-name=bo_delivery_date]').prop('readonly', false);}
   });
   /**Calculate values for receipts and service items**/
   $(document).on('change', "input[data-name='qty'], input[data-name='cost']",function(e){
	    var qid_n      = $(this).data('qid');
        var $qbox      = $(this).parents('div.qbox')
         if (isNaN($qbox))
            $qbox      = $(this).parents('div.TaskEdit___1Xmiy6lz');
        var $line_item = $(this).parents('.rTableRow.receipt_item, .rTableRow.service_item');
	    var qid        = $qbox.attr('id'),
            cid        = $line_item.data('cid');
         if (typeof qid == 'undefined')
            qid        = $qbox.data('cid');
        var $this_form = $(this).parents("#"+cid);
        var $this_row  = $(this).parents('.rTableRow');
	    var $qty       = $line_item.find("input[data-name='qty'][data-qid="+qid_n+"]");
	    var $cost      = $line_item.find("input[data-name='cost'][data-qid="+qid_n+"]");
	    var $line_total= $line_item.find("span#"+qid_n+"_line_total");
	    var $line_total_raw = $line_item.find("span."+qid_n+"_line_total_raw.line_total_raw");
        var $sales_tax = $qbox.find("input[name='jot[sales_tax]'][data-qid="+qid+"]");
	    var $shipping  = $qbox.find("input[name='jot[shipping_cost]'][data-qid="+qid+"]");
	    var $subtotal  = $qbox.find("span#"+qid+"_subtotal");                                                                                       
	    var $subtotal_raw  = $qbox.find("span."+qid+"_subtotal");
	    var $total     = $qbox.find("span#"+qid+"_total");
	    var $total_raw = $qbox.find("span."+qid+"_total_raw");
	    var qty        = parseFloat($qty.val());
	    var cost       = parseFloat($cost.val());
	    var shipping   = parseFloat($shipping.val());
	    var sales_tax  = parseFloat($sales_tax.val());
	    var subtotal;
	    var total;                                                       	    console.log('qty = '+qty); console.log('cost = '+cost); console.log('qid = '+qid); console.log('qid_n = '+qid_n);console.log('$qbox = ',$qbox);
    	var subtotal = 0;
		var line_total = parseFloat(qty*cost)
    	$line_total.text(addCommas(line_total.toFixed(2)));
    	$line_total_raw.text(line_total);
    	$qbox.find("span.line_total_raw").each(function(){
			var value = $(this).text();                     				console.log('value: '+value);
			if(!isNaN(value) && value.length>0)
				{subtotal += parseFloat(value);}
		   });
	   $subtotal.text(moneyFormat(subtotal));
	   $subtotal_raw.text(subtotal);
	   if(isNaN(shipping)  || shipping.length  == 0){shipping  = 0;}
	   if(isNaN(sales_tax) || sales_tax.length == 0){sales_tax = 0;}
	   total = subtotal+shipping+sales_tax;
	   $total.text(moneyFormat(total)); 
	   $total_raw.text(total);                                            console.log('total = '+total);
          /*calculate tax rate when taxable checkbox is pre-checked on a row for which a user enters a cost value**/
         if ($line_item.find("input[data-name='taxable']").prop("checked")){
             var taxableSum = 0;
             var taxableItems = 0;
             var salesTax   = $this_form.find("input[data-name='sales_tax'][data-qid="+qid+"]").val();
             $this_form.find("[data-name='taxable']:checked"). each(function(e){
                   qid_n = $(this).data('qid');
                   cost  = $(this).parents('.rTableRow').find("span."+qid_n+"_line_total_raw").html();
                   taxableSum += parseFloat(cost);
                   taxableItems ++;
             });
             if (parseFloat(salesTax) > 0 && taxableSum > 0){
                   var taxRate = parseFloat(salesTax)/taxableSum
                   var taxRateLabel = percentFormat(taxRate, 2);
                   $this_form.find('span.'+cid+'_sales_tax_rate').text(' (' + taxRateLabel + ')');
             }
         }
	});
   $(document).on('change', "input[name='jot[shipping_cost]'], input[name='jot[sales_tax]']",function(e){
	    var qid        = $(this).data('qid');
	    var $sales_tax = $(this).parents('.rTableBody').find("input[name='jot[sales_tax]'][data-qid="+qid+"]");
	    var $shipping  = $(this).parents('.rTableBody').find("input[name='jot[shipping_cost]'][data-qid="+qid+"]");
	    var $subtotal  = $(this).parents('.rTableBody').find("span#"+qid+"_subtotal");
	    var $subtotal_raw  = $(this).parents('.rTableBody').find("span."+qid+"_subtotal");
	    var $total     = $(this).parents('.rTableBody').find("span#"+qid+"_total");
	    var $total_raw = $(this).parents('.rTableBody').find("span."+qid+"_total_raw");
	    var sales_tax  = parseFloat($sales_tax.val());
	    var shipping   = parseFloat($shipping.val());
	    var subtotal   = parseFloat($subtotal_raw.text());
		if(isNaN(subtotal)  || subtotal.length  == 0){subtotal  = 0;}
		if(isNaN(shipping)  || shipping.length  == 0){shipping  = 0;}
		if(isNaN(sales_tax) || sales_tax.length == 0){sales_tax = 0;}
		$total.text(moneyFormat(subtotal+shipping+sales_tax)); 
		$total_raw.text(subtotal+shipping+sales_tax);
   });
   $(document).on('change', "input[data-name='shipping_cost'], input[data-name='sales_tax']",function(e){
         var qid        = $(this).data('qid');
         var cid        = qid;
         var $this_form = $(this).parents("#"+qid);
         var $sales_tax = $this_form.find("input[data-name='sales_tax'][data-qid="+qid+"]");
         var $shipping  = $this_form.find("input[data-name='shipping_cost'][data-qid="+qid+"]");
         var $subtotal  = $this_form.find("span#"+qid+"_subtotal");
         var $subtotal_raw  = $this_form.find("span."+qid+"_subtotal_raw");
         var $total     = $this_form.find("span#"+qid+"_total");
         var $total_raw = $this_form.find("span."+qid+"_total_raw");
         var sales_tax  = parseFloat($sales_tax.val());
         var shipping   = parseFloat($shipping.val());
         var subtotal   = parseFloat($subtotal_raw.text());
          if(isNaN(subtotal)  || subtotal.length  == 0){subtotal  = 0;}
          if(isNaN(shipping)  || shipping.length  == 0){shipping  = 0;}
          if(isNaN(sales_tax) || sales_tax.length == 0){sales_tax = 0;}
          $total.text(moneyFormat(subtotal+shipping+sales_tax)); 
          $total_raw.text(subtotal+shipping+sales_tax);
   });
   $(document).on('change', "input[data-name='taxable'], input[data-name='sales_tax']", function(e){
// D R A F T - Calculate the sales tax percentage
         var cid        = $(this).parents('.TaskEdit___1Xmiy6lz').data('cid');
         var $this_form = $(this).parents("#"+cid);
         var salesTax   = $this_form.find("input[data-name='sales_tax'][data-qid="+cid+"]").val();
         var taxableSum = 0;
         var taxableItems = 0;
         $this_form.find("[data-name='taxable']:checked"). each(function(e){
               var qid_n = $(this).data('qid');
               var cost  = $(this).parents('.rTableRow').find("span."+qid_n+"_line_total_raw").html();
               taxableSum += parseFloat(cost);
               taxableItems ++;
         });
         if (parseFloat(salesTax) > 0 && taxableSum > 0){
               var taxRate = parseFloat(salesTax)/taxableSum
               var taxRateLabel = percentFormat(taxRate, 2);
               $this_form.find('span.'+cid+'_sales_tax_rate').text(' (' + taxRateLabel + ')');
         }
         if (taxableItems == 0){
               $this_form.find('span.'+cid+'_sales_tax_rate').text('');
         }
         console.log('cid: '+cid+' salesTax: '+salesTax +' taxRate: '+taxRate+' taxRateLabel: '+taxRateLabel+' taxableItems: '+taxableItems);
         
         
   });
	$(document).on('change', 'select.properties-item-type', function(e){
		var item_type = $(this).find(':selected').val();
		console.log('item_type: '+item_type);
		$(this).parents('.rTableBody').find('div.properties-input-selector').css('display', '')
		$(this).parents('.rTableBody').find('div.item-'+item_type).css('display', 'table-row');
	});

	$(document).on( "click", ".boqx-label", function(e) {
		var $this = $(this),
		    state;
	    if ($this.parent('span').hasClass('boqx-items-expanded')) state = 'expanded';
	    if ($this.parent('span').hasClass('boqx-items-collapsed')) state = 'collapsed';
		if (state == 'expanded'){
			$this.parent('span').removeClass('boqx-items-expanded');
			$this.parent('span').addClass('boqx-items-collapsed');
			$this.parent('span').attr('title', 'Open boqx'); 
		}
		if (state == 'collapsed'){
			$this.parent('span').removeClass('boqx-items-collapsed');
			$this.parent('span').addClass('boqx-items-expanded');
			$this.parent('span').attr('title', 'Close boqx'); 
		}
		$this.parent('span').prev(".quebx-list-boqx-viewarea").slideToggle("slow");
    });

/*	$(document).on('keydown', 'input.last_characteristic', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_characteristic");
		  var html = $('.characteristics').html();
		  $(html).insertBefore('.new_characteristic');
	    } 
	});*/
/*	$(document).on('keydown', 'input.last_feature', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_feature");
		  $(this).trigger({type: 'keypress', which: 9, keyCode: 9}); // supposed to send the TAB keystroke, but doesn't work ... yet.
		  var html = $('.features').html();
		  $(html).insertBefore('.new_feature');
	    } 
	});*/
    $( "#family_characteristics" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#family_features" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});
    $( "#item_characteristics" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#item_features" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
	// clone line item node
	$('.clone-line-item-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.line_item').html();
		$(html).insertBefore('.new_line_item');
	});

	// Add input fields when TAB pressed.  Source: http://jsbin.com/amoci/123/edit
	$(document).on('keydown', 'input.last_line_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_line_item");
		  var html = $('.receipt_line_items').html();
		  $(html).insertBefore('.new_line_items');
	    } 
	});

	//Add input fields when TAB pressed.  Source: http://jsbin.com/amoci/123/edit
	$(document).on('keydown', 'input.last_step_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_step_item");
		  $(this).trigger({type: 'keypress', which: 9, keyCode: 9}); // supposed to send the TAB keystroke, but doesn't work ... yet.
		  var step = $('.step_item').clone(true, true).contents();
		  $(step).insertBefore('.new_step_item');
	    } 
	});
	$(document).on('keydown', 'textarea.last_step_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_step_item");
		  $(this).trigger({type: 'keypress', which: 9, keyCode: 9}); // supposed to send the TAB keystroke, but doesn't work ... yet.
		  var step = $('.step_item').clone().contents();
		  $(step).insertBefore('.new_step_item');
	    } 
	});
	$(document).on('keydown', 'textarea.last_discovery', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_discovery");
		  $(this).trigger({type: 'keypress', which: 9, keyCode: 9}); // supposed to send the TAB keystroke, but doesn't work ... yet.
		  var discovery = $('.discovery').clone().contents();
		  $(discovery).insertBefore('.new_discovery');
	    } 
	});
	$(document).on('keydown', 'input.last_milestone_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_milestone_item");
		  var milestone = $('.milestone_item').clone().contents();
		  $(milestone).insertBefore('.new_milestone_item');
	    } 
	});
	$(document).on('keydown', 'input.last_schedule_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_schedule_item");
		  var schedule = $('.schedule_item').clone().contents();
		  $(schedule).insertBefore('.new_schedule_item');
	    } 
	});
	$(document).on('keydown', 'input.last_other_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_other_item");
		  var html = $('.other_item').html();
		  $(html).insertBefore('.new_other_item');
	    } 
	});
	$(document).on('keydown', 'input.last_material_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_material_item");
		  var material = $('.material_item').clone().contents();
		  $(material).insertBefore('.new_material_item');
	    } 
	});
	$(document).on('keydown', 'input.last_content_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass('last_content_item');
		  var html = $('.content_item').html();
		  $(html).insertBefore('.new_content_item');
	    } 
	});
	$(document).on('keydown', 'input.last_component_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass('last_component_item');
		  var html = $('.component_item').html();
		  $(html).insertBefore('.new_component_item');
	    } 
	});
	$(document).on('keydown', 'input.last_accessory_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass('last_accessory_item');
		  var html = $('.accessory_item').html();
		  $(html).insertBefore('.new_accessory_item');
	    } 
	});		
/*	$(document).on('keydown', 'input.commentarea', function(e) { 
	    var keyCode = e.keyCode || e.which; 
	    if (keyCode == 13) { 
	      e.preventDefault(); 
	      this.form.submit();
	    } 
	});
	$(document).ready(function(event) {
	    $('.commentarea').keydown(function(event) {
	        if (event.keyCode == 13) {
	            this.form.submit();
	            return false;
	         }
	    });
	});*/
	// Add input field when [+] pressed
	$(document).on('click', '.add-step-item', function(e){
		$('#step_button_label').hide();
		var step = $('.step_item').clone().contents();
		$(step).insertBefore('.new_step_item');
	});
    $(document).on('click', '.add-schedule-item', function(e){
		$('#interval_button_label').hide();
		var schedule = $('.schedule_item').clone().contents();
		$(schedule).insertBefore('.new_schedule_item');
	});
    $(document).on('click', '.add-other-item', function(e){
		//$('#other_button_label').hide();
		var html = $('.other_item').html();
		$(html).insertBefore('.new_other_item');
	});
    $('.add-material-item').on('click', function(e){
		$('#interval_button_label').hide();
		var material = $('.material_item').clone().contents();
		$(material).insertBefore('.new_material_item');
	});
    $(document).on('click', '.add-tool-item', function(e){
		$('#interval_button_label').hide();
		var tool = $('.tool_item').clone().contents();
		$(tool).insertBefore('.new_tool_item');
	});
	$(document).on('click', '.add-effort', function(e){
		$('#effort_button_label').hide();
		var effort = $('.resolve_effort').clone().contents();
		$(effort).insertBefore('.new_effort');
	});
	$(document).on('click', '.add-milestone-item', function(e){
		$('#milestone_button_label').hide();
		var milestone = $('.milestone_item').clone().contents();
		$(milestone).insertBefore('.new_milestone_item');
	});
	$(document).on("click", ".clone-discovery-action-xxx", function(e){
		e.preventDefault();
		// clone the node
		var discovery = $(".discovery").clone().contents();
		$(discovery).insertBefore(".new_discovery");
	});
/*	$(document).on("click", ".clone-receipt-action", function(e){"use strict"
	    e.preventDefault();
		// clone the node
		var line_item = $(".receipt_line_items").clone(true, true).contents();
		$(line_item).insertBefore(".new_line_items");
	}); 
*/	// remove a node
	$(document).on('click', '.remove-node', function(e){
		e.preventDefault();
		// remove the node
		var property_card = $(this).parents('div.rTableRow').data('qid'),
		    element_type  = $(this).data('element');
		var is_receipt_item = true;
	    if (typeof property_card == 'undefined')
	    	is_receipt_item = false;
		console.log('property_card:'+property_card);
		$this = $(this);
		var qid_n = $(this).parents('div.receipt_item').data('qid');
		
		if (is_receipt_item){
			var $qbox      = $this.parents('div.qbox');
			var qid        = $qbox.attr('id');                                 console.log('qid: '+qid);
			var $sales_tax = $qbox.find("input[name='jot[sales_tax]'][data-qid="+qid+"]");
		    var $shipping  = $qbox.find("input[name='jot[shipping_cost]'][data-qid="+qid+"]");
		    var $subtotal  = $qbox.find("span#"+qid+"_subtotal");
		    var $subtotal_raw  = $qbox.find("span."+qid+"_subtotal");
		    var $total     = $qbox.find("span#"+qid+"_total");
		    var $total_raw = $qbox.find("span."+qid+"_total_raw");
		    var shipping   = parseFloat($shipping.val());
		    var sales_tax  = parseFloat($sales_tax.val());
		    var subtotal   = 0;
		    var total      = 0;
		    
			$('div#'+property_card).remove();
			$("span."+qid+"_line_total").each(function(){
				var value = $(this).text();                     				console.log('value: '+value);
				var qid_x = $(this).parents('div.receipt_item').data('qid');
				if(!isNaN(value) && 
					value.length>0 && 
					qid_x != qid_n)
					{subtotal += parseFloat(value);}
			   });
		   $subtotal.text(moneyFormat(subtotal));
		   $subtotal_raw.text(subtotal);
		   if(isNaN(shipping)  || shipping.length  == 0){shipping  = 0;}
		   if(isNaN(sales_tax) || sales_tax.length == 0){sales_tax = 0;}
		   total = subtotal+shipping+sales_tax;
		   $total.text(moneyFormat(total)); 
		   $total_raw.text(total);                                            console.log('total = '+total);
	   }
		$this.parents('div.rTableRow').eq(0).remove();
		
	});
	$(document).on('click', '.remove-service-item', function(e){
		e.preventDefault();
		// remove the node
		var element_type  = $(this).data('element'),
		    qid_n         = $(this).data('qid');
	    $service_item_add = $(this).parents('.rTable.service-line-items').find('a.new-item');
	     item_rows = $service_item_add.attr('data-rows');
	    $service_item_add.attr('data-rows', item_rows-1);
		$('.rTableRow.service_item[data-qid='+qid_n+']').remove();
	    $('.ServiceEffort__26XCaBQk[data-qid='+qid_n+']').remove();
	    $('div.jq-dropdown#'+qid_n).remove();
		
	});
    $(document).on('click', '.remove-loose-thing', function(e){
          e.preventDefault();
          // remove the node
         var $qbox         = $(this).parents('div.TaskEdit___1Xmiy6lz'),
             element_type  = $(this).data('element'),
             qid_n         = $(this).data('qid'),
             $loose_item_add = $(this).parents('.rTable.loose-line-items').find('a.new-item');
         var $line_item    = $(this).parents('.rTableRow.loose_item');
         var qid           = $qbox.attr('id'),
             cid           = $line_item.data('cid');
         if (typeof qid == 'undefined')
            qid            = $qbox.data('cid');
//         var item_rows     = $loose_item_add.attr('data-rows');
//         console.log('$loose_item_add: ',$loose_item_add);
//         $lose_item_add.attr('data-rows', item_rows-1);
         $('.rTableRow.loose_item[data-qid='+qid_n+']').remove();
         $('div.jq-dropdown#'+qid_n).remove();
          
    });
    $(document).on('click', '.remove-receipt-item', function(e){
          e.preventDefault();
          // remove the node
         var $qbox         = $(this).parents('div.TaskEdit___1Xmiy6lz'),
             element_type  = $(this).data('element'),
             qid_n         = $(this).data('qid'),
         $receipt_item_add = $(this).parents('.rTable.receipt-line-items').find('a.new-item');
         var $line_item    = $(this).parents('.rTableRow.receipt_item');
         var qid           = $qbox.attr('id'),
            cid            = $line_item.data('cid');
         if (typeof qid == 'undefined')
            qid            = $qbox.data('cid');
         var item_rows     = $receipt_item_add.attr('data-rows');
         var $qty          = $(this).parents('.rTableRow.receipt_item').find("input[data-name='qty'][data-qid="+qid_n+"]");
         var $cost         = $(this).parents('.rTableRow.receipt_item').find("input[data-name='cost'][data-qid="+qid_n+"]");
         var $line_total   = $(this).parents('.rTableRow.receipt_item').find("span#"+qid_n+"_line_total");
         var $line_total_raw = $(this).parents('.rTableRow.receipt_item').find("span."+qid_n+"_line_total.line_total_raw");
         var $sales_tax      = $qbox.find("input[name='jot[sales_tax]'][data-qid="+qid+"]");
         var $shipping       = $qbox.find("input[name='jot[shipping_cost]'][data-qid="+qid+"]");
         var $subtotal       = $qbox.find("span#"+qid+"_subtotal");                                                                                       
         var $subtotal_raw   = $qbox.find("span."+qid+"_subtotal");
         var $total          = $qbox.find("span#"+qid+"_total");
         var $total_raw      = $qbox.find("span."+qid+"_total_raw");
         var subtotal        = 0;
         var line_total      = parseFloat(qty*cost);
         
         var qty        = parseFloat($qty.val());
         var cost       = parseFloat($cost.val());
         var shipping   = parseFloat($shipping.val());
         var sales_tax  = parseFloat($sales_tax.val());
         var subtotal;
         var total;                                    
         $receipt_item_add.attr('data-rows', item_rows-1);
         $('.rTableRow.receipt_item[data-qid='+qid_n+']').remove();
         $('div.jq-dropdown#'+qid_n).remove();
         $line_total.text(addCommas(line_total.toFixed(2)));
         $line_total_raw.text(line_total);
         $qbox.find("span.line_total_raw").each(function(){
                   var value = $(this).text();                                      console.log('value: '+value);
                   if(!isNaN(value) && value.length>0)
                        {subtotal += parseFloat(value);}
                 });
         $subtotal.text(moneyFormat(subtotal));
         $subtotal_raw.text(subtotal);
         if(isNaN(shipping)  || shipping.length  == 0){shipping  = 0;}
         if(isNaN(sales_tax) || sales_tax.length == 0){sales_tax = 0;}
         total = subtotal+shipping+sales_tax;
         $total.text(moneyFormat(total)); 
         $total_raw.text(total);   
     });
     
	   function moneyFormat(nStr) {
		    nStr = parseFloat(nStr).toFixed(2).toString();
            return '$' + addCommas(nStr);
		}
        function percentFormat(nStr, precision){
            nStr = parseFloat(nStr*100).toFixed(precision).toString();
            return nStr+'%';
        } 
	// Source: https://www.codeproject.com/Questions/1103675/How-to-set-thousand-saprator-in-javascript-or-jque
	   function addCommas(nStr) {
		    nStr += '';
		    x = nStr.split('.');
		    x1 = x[0];
		    x2 = x.length > 1 ? '.' + x[1] : '';
		    var rgx = /(\d+)(\d{3})/;
		    while (rgx.test(x1)) {
		            x1 = x1.replace(rgx, '$1' + ',' + '$2');
		    }
		    return x1 + x2;
		}

	$(document).on('click', '.remove-receipt-node', function(e){
		e.preventDefault();
		// remove the node
		$(this).parents('div.receipt-item').eq(0).remove();
	});
	$(document).on('click', '.remove-progress-marker', function(e){
		e.preventDefault();
       var container    = $('.inline-content-expand'),
	       qid  = $(this).attr('data-qid');
	   var eggs = parseInt($('span.qbox-menu[data-qid='+qid+']').attr('eggs'), 10);
       var visible_container = $(container).children('.inline-visible');
		// remove the node
		$(this).parents('.rTableRow').eq(0).remove();
		$.colorbox.resize({
	  		innerHeight: ($(visible_container).offset().top + $(visible_container).height())
	  	});
	  	
		$('span.qbox-menu[data-qid='+qid+']').attr('eggs', eggs-1);
	  	if (eggs <= 1){
	  		$('span.qbox-menu[data-qid='+qid+']').hide();
	  	}
	});
	$(document).on('click', '.remove-card', function(e){
		   e.preventDefault();
	      var container  = $(this).parents('.Effort__CPiu2C5N'),
		       qid       = $(this).attr('data-qid'),
		       $container = $(this).parents('.Effort__CPiu2C5N').parent();
	      var action = $container.attr('data-aid');
		  var eggs = parseInt($('span.efforts-eggs[data-qid='+qid+']').attr('eggs'), 10);
			// remove the card
			$(container).remove();		  	
			$('span.efforts-eggs[data-qid='+qid+']').attr('eggs', eggs-1);
		});
    $( "#sortable" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#sortable_item" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)",
	    axis: "y",
	    containment: "parent"
	});	
    $( "#sortable_interval" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#sortable_discovery" ).sortable({
    	revert: false,
	    items: "> div:not(.pin)",
	    axis: "y",
	    containment: "parent"
	});	
    $( "#sortable_project_milestones" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});		
    $("#sortable_observation_resolve").sortable({
	});		
    $("#sortable_instruction_material").sortable({
	});
    $("#sortable_instruction_tool").sortable({
	});	
    $("#sortable_instruction_step").sortable({
	});
    $( "#draggable" ).draggable({
        revert: false,
        connectToSortable: "#sortable",
      });
    $( "#draggable_image" ).draggable({
        revert: true,
        connectToSortable: "#instruction_step_image",
      });
    $('.quebx-shelf-item').draggable({
   	    refreshPositions: true,
        revert:false,
        cursor: "move",
        cursorAt: { left: 50, top: 15 },
        zIndex: 1050,
        helper: function(event){
        	item       = $(this);
        	item_guid  = item.find('div.elgg-image').data('item-guid');
        	item_title = item.find('div.elgg-body').html(); 
            icon       = item.find('div.elgg-image').html();
        	return $("<div class='elgg-image' data-item-guid='"+item_guid+"'>"+icon+"</div>");
        }
    });
    $('.qbox-open').droppable({
     	accept: '.quebx-shelf-item',
     	tolerance: "touch",
//     	hoverClass: "box-state-highlight"
    });
    $('.dropboqx').droppable({
    	greedy: true,
    	accept: '.quebx-shelf-item',
    	tolerance: "touch",
     	hoverClass: "box-state-highlight",
	});
/*    $('.dropboqx').droppable({
    	accept: '.quebx-shelf-item',
    	tolerance: "touch",
    	 activate:  function(event, ui){
    		 $( this )
    	        .find( ".dropboqx-dropspot" )
    	        .html( "started moving " );
    	     },
      	 deactivate: function( event, ui ) {
      	      $( this )
      	        .find( ".dropboqx-dropspot" )
      	        .html( "stopped  " );
      	     }
    });
*/    $(document).on('dropactivate','.dropboqx', function( event, ui ) {
    	console.log('+framework.dropboqx.on(dropactivate)');
    	  	//boqx = $(this);
        	boqx_div = $('.dropboqx');
//        	item = ui.draggable;
//        	boqx_div.addClass( "box-state-highlight" );
//             boqx_div.css('border-bottom','thin dashed red;');
  	     });
    $(document).on('dropdeactivate', '.dropboqx',function( event, ui ) {
    	console.log('+framework.dropboqx.on(dropdeactivate)');
    	  $( this )
          .find( ".dropboqx-dropspot" )
          .html( "Add something from the shelf " );
	     });
/*    $('.qbox-open').on('dropactivate',function( event, ui ) {
    	console.log('+framework.qbox-open.on(dropactivate)');
    	  $( this )
  	        .find( ".dropboqx-dropspot" )
  	        .html( "started moving " );
  	     });
    $('.qbox-open').on('dropdeactivate',function( event, ui ) {
    	console.log('+framework.qbox-open.on(dropdeactivate)');
	      $( this )
	        .find( ".dropboqx-dropspot" )
	        .html( "stopped " );
	     });
*/    $(document).on('click', 'a.elgg-after', function(e){
        $('.qbox-open').droppable({
        	accept: '.quebx-shelf-item',
        	tolerance: "touch",
        });
    });
    $(document).on( "dropover", '.dropboqx', function( event, ui ) {
    	console.log('+framework.dropboqx.on(dropover)');
    	var boqx = $(this),
        	boqx_div = boqx.parents('.things_add_pallet_boqx'),
        	item = ui.draggable;
    	var item_title = item.find('.elgg-body').html();
//    	item_title = item.attr('id');
        $( boqx )
          .find( ".dropboqx-dropspot" )
          .html( "Add: <br>"+item_title );
//     	boqx_div.addClass( "box-state-highlight" );
//         boqx_div.css('border-bottom','thin dashed red;');
    });
    $(document).on( "dropover", '.dropboqx', function( event, ui ) {
    	console.log('+framework.dropboqx.on(dropover)');
    	var boqx = $(this),
        	boqx_div = boqx.parents('.things_add_pallet_boqx'),
        	item = ui.draggable;
    	var item_title = item.find('.elgg-body').html();
//    	item_title = item.attr('id');
        $( boqx )
          .find( ".dropboqx-dropspot" )
          .html( "Add: <br>"+item_title );
//     	boqx_div.addClass( "box-state-highlight" );
//         boqx_div.css('border-bottom','thin dashed red;');
    });
    $( '.qbox-open').on( "dropover", function( event, ui ) {
    	boqx = $(this);
    	boqx_li = boqx.parent('li');
    	item = ui.draggable;
    	pallet_id = boqx.data('dropbox');
    	pallet = boqx.next(pallet_id);
    	dropbox = boqx.find('.dropbox');
    	boqx_guid = boqx.data('guid');
    	container_guid = item.attr('data-container-guid');  //use .attr() instead of .data() because container_guid changes on drop
    	item_guid = item.attr("id").split("-").pop();
    	if (boqx_guid != item_guid && pallet.is(':hidden')){
//             boqx_li.addClass( "box-state-highlight" );
//             boqx_li.css('border-bottom','thin dashed red;');
			console.log('+framework.qbox-open.on(dropover)');
        	console.log('item_guid: '+item_guid);
        	console.log('container_guid: '+container_guid);
        	console.log('box_guid: '+boqx_guid);
        	console.log('pallet_id: '+pallet_id);
        	console.log('pallet is hidden: '+pallet.is(':hidden'));
        //	dropbox.show();
            boqx.Dropbox('open');
        //    dropbox.find('.qbox-drop').droppable('enable');
    	}
    });
    $( '.qbox-open').on( "drop", function( event, ui ) {
    	boqx = $(this);
    	boqx_li = boqx.parent('li');
    	dropbox = boqx.find('.dropbox');
        boqx_guid = boqx.data('guid');
        boqx_li.removeClass( "box-state-highlight");
        boqx_li.css('border-bottom','');
	});
    $( '.qbox-open').on( "dropout", function( event, ui ) {
    	boqx = $(this);
    	boqx_li = boqx.parent('li');
    	dropbox = boqx.find('.dropbox');
        boqx_guid = boqx.data('guid');
        boqx_li.removeClass( "box-state-highlight");
        boqx_li.css('border-bottom','');
    //    box.removeClass( "box-state-received" );
    //	  box.css("border", "");
    //    dropbox.find('.qbox-drop').droppable('disable');
    //    dropbox.hide();
	});
    $('.qbox-pallet_xxx').droppable({
    	accept: '.quebx-shelf-item',
        //greedy: true,
    });
    $('.qbox-pallet_xxx').on('dropover', function(event, ui){
        $(this).children('.qbox-drop').droppable('enable');
    });
    $('.qbox-pallet_xxx').on('dropout', function(event, ui){
        $(this).children('.qbox-drop').droppable('disable');
    });
    $('.qbox-drop').droppable({
        accept: '.quebx-shelf-item',
        tolerance: "pointer",
        //greedy: true,
        //disabled:true,
    });
    $('.qbox-drop_xxx').on('dropover', function (event, ui) {
        boqx = $(this);
    	boqx_li = boqx.parent('li');
        item = ui.draggable;
        boqx_guid = boqx.parent('.qbox-pallet').data("boqx-guid");
    	item_guid = item.attr("id").split("-").pop();
    	console.log('+framework.qbox-drop.on(dropover)');
        console.log('boqx_guid: '+boqx_guid);
        console.log('item_guid: '+item_guid);
        //boqx.addClass('ui-droppable-hover');
    });
    $('.qbox-drop, .dropboqx').on('drop', function(event, ui){
        /**
         * Used to select:
           * Content of an item
           * Accessory for an item
           * Component of an item
           * Thing for an experience (proposed)
        **/
        boqx = $(this);
        item = ui.draggable;
        boqx_guid = boqx.parent('.qbox-pallet').data('boqx-guid');
//    	item_guid = item.data("item-guid");
    	item_guid = item.attr("id").split("-").pop();
    	container_guid = item.attr('data-container-guid');  //use .attr() instead of .data() because container_guid changes on drop
    	aspect = boqx.data('aspect'); 
        boqx_li.removeClass( "box-state-highlight");
        boqx_li.css('border-bottom','');
    	console.log('+framework.qbox-drop.on(drop)');
    	console.log('item_guid: '+item_guid);
    	console.log('container_guid: '+container_guid);
    	console.log('boqx_guid: '+boqx_guid);
    	console.log('aspect: '+aspect);
    	quebx.shelf_tools.pack_item(item_guid, boqx_guid, boqx, item, aspect, container_guid);
    });
    $('.qbox-drop_xxx').on('dropout', function (event, ui){
        boqx = $(this);
        //boqx.removeClass("ui-droppable-hover");
    });
    // from market/edit/profile
	// clone characteristics node
	$('.clone-characteristic-action1').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.characteristics1').html();
		$(html).insertBefore('.new_characteristic1');
	});
	
	// clone characteristics node
	$('.clone-characteristic-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.characteristics').html();
		$(html).insertBefore('.new_characteristic');
	});

	// clone features node
	$('.clone-feature-action').on('click', function(e){
		e.preventDefault();
		// clone the node
		var html = $('.features').html();
		$(html).insertBefore('.new_feature');
	});
	// clone characteristics node
	$('.clone-individual-characteristic-action1').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.individual_characteristics1').html();
		$(html).insertBefore('.new_individual_characteristic1');
	});

	// clone characteristics node
	$('.clone-individual-characteristic-action').on('click', function(e){
		e.preventDefault();
		
		// clone the node
		var html = $('.individual_characteristics').html();
		$(html).insertBefore('.new_individual_characteristic');
	});	

	// clone individual features node
	$('.clone-individual-feature-action1').on('click', function(e){
		e.preventDefault();
		// clone the node
		var html = $('.individual_features1').html();
		$(html).insertBefore('.new_individual_feature1');
	});
	$('.Description___3oUx83yQ').on('click', function(){
		$(this).next('.DescriptionEdit___1FO6wKeX').show();
		$(this).hide();
	});
};	

/*  @TODO Figure out how to set this up correctly.
quebx.framework.facebook = $(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1390736364334124';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
*/
/*
quebx.framework.ajax = 
	define(function(require) {
	
	   var $ = require('jquery');
	   var Ajax = require('elgg/Ajax');
	   
	   $(document).on('click', '.trigger-element', function(e) {
	       e.preventDefault();
	       var ajax = new Ajax();
	       ajax.view('partials/form_elements').done(function(output) {
	          $('.new_datefield').append($(output));
	       });       
	   });
	});
//};
*/
elgg.register_hook_handler('init', 'system', quebx.framework.init);
<?php if (FALSE) : ?></script><?php endif; ?>