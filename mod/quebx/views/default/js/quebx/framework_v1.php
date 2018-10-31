<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
elgg.provide('quebx.framework');

quebx.framework.init = $(document).ready(function(){
$(document).ready(function(){
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
/*    $("#add_something_tab").click(function(){
        $("#add_experience_panel").slideUp("slow");
        $("#add_experience_tab").removeClass("elgg-state-selected");
        $("#add_something_panel").slideToggle("slow");
        $("#add_something_tab").addClass("elgg-state-selected");
        $("#add_receipt_panel").slideUp("slow");
        $("#add_receipt_tab").removeClass("elgg-state-selected");
    });/*
/*    $("#add_experience_tab").click(function(){
//        $("#add_something_panel").fadeOut("slow");
	  $("#add_something_panel").slideUp("slow");
	  $("#add_something_tab").removeClass("elgg-state-selected");
//        $("#add_experience_panel").fadeToggle("slow");
	  $("#add_experience_panel").slideToggle("slow");
	  $("#add_experience_tab").addClass("elgg-state-selected");
//        $("#add_receipt_panel").fadeOut("slow");
	  $("#add_receipt_panel").slideUp("slow");
	  $("#add_receipt_tab").removeClass("elgg-state-selected");        
    });*/
/*    $("#add_receipt_tab").click(function(){
        $("#add_something_panel").slideUp("slow");
        $("#add_something_tab").removeClass("elgg-state-selected");
        $("#add_experience_panel").slideUp("slow");
        $("#add_experience_tab").removeClass("elgg-state-selected");
        $("#add_receipt_panel").slideToggle("slow");
        $("#add_receipt_tab").addClass("elgg-state-selected");        
    });*/
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
    $("a.quebx-module-close").on("click", function(e){
        var panel = $(this).attr('panel');
        var this_module    = $("div[aspect=module][panel="+panel+"]");
        this_module.slideUp("slow");
    });
    $("a[aspect=panel]").on("click", function(e){
        var panel = $(this).attr('panel');
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
    $("a.quebx-module-close-button").on("click", function(e){
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
    $("a[aspect=experience]").on( "click", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        if ($("li[guid="+guid+"][panel="+panel+"][aspect=attachments]").is(".elgg-state-selected")){
            $("li[guid="+guid+"][panel="+panel+"]").removeClass("elgg-state-selected");
            $("div[guid="+guid+"][panel="+panel+"]").hide();}
        else {
           $("li[guid="+guid+"][aspect=attachments]").removeClass("elgg-state-selected");
           $("div[guid="+guid+"][aspect=attachments]").hide();
           $("li[guid="+guid+"][panel="+panel+"]").toggleClass("elgg-state-selected");
           $("div[guid="+guid+"][panel="+panel+"]").show();
           }
    });    
    $("a[aspect=media_input]").on( "click", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=media_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=media_input]").hide();
        $("li[guid="+guid+"][panel="+panel+"][aspect=media_input]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][panel="+panel+"][aspect=media_input]").toggle();
    });
    $("a[aspect=observation_input]").on( "click", function(e) {
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
    $("a[aspect=project_input]").on( "click", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=project_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=project_input]").hide();
        $("li[guid="+guid+"][aspect=project_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=project_input][panel="+panel+"]").toggle();
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
    $("a[aspect=event_input]").on( "click", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=event_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=event_input]").hide();
        $("li[guid="+guid+"][aspect=event_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=event_input][panel="+panel+"]").toggle();
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
    $("a[aspect=instruction_input]").on( "click", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=instruction_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=instruction_input]").hide();
        $("li[guid="+guid+"][aspect=instruction_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=instruction_input][panel="+panel+"]").toggle();
    });
    $("a[aspect=observation_effort_input]").on( "click", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid'); 
        var panel = $(this).attr('panel');
        $("li[guid="+guid+"][aspect=observation_effort_input]").removeClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=observation_effort_input]").hide();
        $("li[guid="+guid+"][aspect=observation_effort_input][panel="+panel+"]").toggleClass("elgg-state-selected");
        $("div[guid="+guid+"][aspect=observation_effort_input][panel="+panel+"]").toggle();
    });
    $("a[do=show_comments]").on( "click", function(e) {
        e.preventDefault();
        var guid  = $(this).attr('guid');
        $("div[guid="+guid+"][panel=comments]").toggle();
    });
    $("a[id=properties_set]").on("click", function(e) {
        e.preventDefault();
        $(this)
            .parent("div")
            .children("div.receipt-item-properties")
            .slideToggle();
    });
    $("a.expander").on("click", function(e) {
//    $("a[id=toggle_marker]").on("click", function(e) {
        e.preventDefault();
        var cid = "c"+Math.floor((Math.random()*200)+1);
        $(this).parents("div.model").removeClass("draggable");
        //$(this).parents("div.model").addClass("pin");
        $(this).parent("header.preview").removeClass("expanded");
        $(this).parent("header.preview").addClass("collapsed");
//        $(this).parent("header.preview").next("div.story").removeClass("collapsed");
//        $(this).parent("header.preview").next("div.story").addClass("expanded");
//        $(this).parent("header.preview").next("div.story").data("cid", cid); // doesn't work(??)
//        $(this).parent("header.preview").next("div.story").attr("data-cid", cid);
        $(this).parent("header.preview").parents("div.story").attr("data-cid", cid);
        $(this).parent("header.preview").next().find("div.edit").attr("data-cid", cid);
        $(this).parent("header.preview").next().find("span.count not_read_only").attr("data-cid", cid);
        $(this).parent("header.preview").next().find("a.collapser").attr("id", "story_collapser_"+cid);
        $(this).parent("header.preview").next().find("textarea[data-aid=name]").attr("data-focus-id", "NameEdit--"+cid);
		$(this).parent("header.preview").next().find("div.details").removeClass("collapsed");
        $(this).parent("header.preview").next().find("div.details").addClass("expanded");
        $(this).parents("div.rTableRow.story").addClass("pin");
        $(this).parents("div.rTableRow.story").css("cursor", "");
        $(this).parents("div.rTableBody").find("div.details").removeClass("expanded");
        $(this).parents("div.rTableBody").find("div.details").addClass("collapsed");
    });
    $("a.collapser").on("click", function(e) {
          e.preventDefault();
          var cid = $(this).parents("div.story").attr("data-cid");
          //var did = $(this).parents("div.tn-panel__loom").children("div.story:last").data("id");
          // use of "did" not implemented
          var str = $(this).parent().find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
          //var str = $(this).parent().find("textarea[name=story[name]]").val();
          //$(this).parents("div.model").removeClass("pin");
          //$(this).parents("div.model").addClass("draggable");
          $(this).parents("div.model").children("header.preview").removeClass("collapsed");
          $(this).parents("div.model").children("header.preview").addClass("expanded");
          //$(this).parents("div.story").removeClass("expanded");
          //$(this).parents("div.story").addClass("collapsed");
          //$(this).parents("div.model").children("header.preview").children("span.name").children("span.story_name").html(str);
          $(this).parents("div.model").children("header.preview").find("span.story_name").html(str);
          $(this).parents("div.rTableRow.story").removeClass("pin");
          $(this).parents("div.rTableRow.story").css("cursor", "move");
          $(this).parents("div.details").removeClass("expanded");
          $(this).parents("div.details").addClass("collapsed");
//          $(this).parents("div.story").addClass("collapsed");
      });
    $("div[data-focus-id=DescriptionShow--c1058]").on("click", function(e) {
        e.preventDefault();
        var edit = $(this).next("div.DescriptionEdit___1FO6wKeX");
        $(edit).show();
        $(this).hide();
        
    });
    $("button[data-aid=cancel]").on("click", function(e) {
        e.preventDefault();
        
    });

    $(".edit_experience").hide();

	});

quebx.framework.dunno = $(document).ready(function() {
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
	$('.add-step-item').live('click', function(e){
		$('#step_button_label').hide();
		var step = $('.step_item').clone().contents();
		$(step).insertBefore('.new_step_item');
	});
    $('.add-schedule-item').live('click', function(e){
		$('#interval_button_label').hide();
		var schedule = $('.schedule_item').clone().contents();
		$(schedule).insertBefore('.new_schedule_item');
	});
    $('.add-other-item').live('click', function(e){
		//$('#other_button_label').hide();
		var html = $('.other_item').html();
		$(html).insertBefore('.new_other_item');
	});
    $('.add-material-item').live('click', function(e){
		$('#interval_button_label').hide();
		var material = $('.material_item').clone().contents();
		$(material).insertBefore('.new_material_item');
	});
    $('.add-tool-item').live('click', function(e){
		$('#interval_button_label').hide();
		var tool = $('.tool_item').clone().contents();
		$(tool).insertBefore('.new_tool_item');
	});
	$('.add-effort').live('click', function(e){
		$('#effort_button_label').hide();
		var effort = $('.resolve_effort').clone().contents();
		$(effort).insertBefore('.new_effort');
	});
	$('.add-milestone-item').live('click', function(e){
		$('#milestone_button_label').hide();
		var milestone = $('.milestone_item').clone().contents();
		$(milestone).insertBefore('.new_milestone_item');
	});
	$(".clone-discovery-action").live("click", function(e){
		e.preventDefault();
		// clone the node
		var discovery = $(".discovery").clone().contents();
		$(discovery).insertBefore(".new_discovery");
	});
	$(".clone-receipt-action").live("click", function(e){"use strict"
	    e.preventDefault();
		// clone the node
		var line_item = $(".receipt_line_items").clone(true, true).contents();
		$(line_item).insertBefore(".new_line_items");
	}); 
	$(".add-progress-marker").on("click", function(e){"use strict"
	    e.preventDefault();
        var cid = "c"+Math.floor((Math.random()*200)+1);
        //var did = $(this).parents("div.tn-panel__loom").children("div.story:last").data("id");
        // use of "did" not implemented
		// clone the node
		var line_item = $(".progress_marker_line_items").clone(true, true).contents();
 		var $input = $(line_item);
		$input.find("div.story").attr("data-cid", cid);
		$input.find("div.edit").attr("data-cid", cid);
		$input.find("span.count not_read_only").attr("data-cid", cid);
		$input.find("a.collapser").attr("id", "story_collapser_"+cid);
		$input.find("textarea[data-aid=name]").attr("data-focus-id", "NameEdit--"+cid);
 		line_item = $input.outerHTML;
// 		$(this).parents("div.tn.panel__loom").children("div.story").removeClass("expanded");
// 		$(this).parents("div.tn.panel__loom").children("div.story").addClass("collapsed");
		$input.insertBefore(".new_line_items").outerHTML;
	});
	// remove a node
	$('.remove-node').live('click', function(e){
		e.preventDefault();
		// remove the node
		$(this).parents('div').parents('div').eq(0).remove();
	});
	$('.remove-receipt-node').live('click', function(e){
		e.preventDefault();
		// remove the node
		$(this).parents('div.receipt-item').eq(0).remove();
	});
	$('.remove-progress-marker').on('click', function(e){
		e.preventDefault();
		// remove the node
		$(this).parents('div.rTableRow').eq(0).remove();
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
	});
	
/*  @TODO Figure out how to set this up correctly.
quebx.framework.facebook = $(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1390736364334124';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
*/
	   
elgg.register_hook_handler('init', 'system', quebx.framework.init);
elgg.register_hook_handler('success', 'quebx:framework', quebx.framework.init, 500);
elgg.register_hook_handler('success', 'quebx:framework', quebx.framework.dunno);
elgg.register_hook_handler('success', 'quebx:framework', quebx.framework.facebook);


<?php if (FALSE) : ?></script><?php endif; ?>