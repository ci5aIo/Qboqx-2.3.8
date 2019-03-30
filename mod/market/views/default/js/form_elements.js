/**
 * 
 */
define(function(require) {

   var $ = require('jquery');
   var Ajax = require('elgg/Ajax');

   $(document).on('click', '.trigger-element', function(e) {
       e.preventDefault();

       var ajax         = new Ajax();
       var element      = $(this).data("element");
       var entity       = $(this).attr("guid");
       var cid          = "c"+Math.floor((Math.random()*200)+1),
           service_cid  = "c"+Math.floor((Math.random()*200)+1);
       var qid          = $(this).data('qid');
       var this_element = this;
       var container    = $(this).parents('.inline-content-expand');
       var visible_container = $(container).children('.inline-visible');
       console.log('#colorbox.top: '+$('#colorbox').offset().top);
       console.log('container.top: '+$(container).offset().top);
       console.log('visible_container.top: '+$(visible_container).offset().top);
       console.log('visible_container.height: '+$(visible_container).height());
       console.log('cid: '+cid);
       console.log('service_cid: '+service_cid);
		
       ajax.view('partials/form_elements',{
    	   data: {
    		 element: element,
    		 guid: entity,
    		 cid: cid,
    		 service_cid: service_cid,
    		 qid: qid,
    	   },
       }).done(function(output) {
    	   if (element == 'new_service_effort'){
    		   $(this_element).parents('.container').find('.new_service_marker').before($(output));
    	   }
    	   else {
    		   $(this_element).parents('.container').find('.new_progress_marker').before($(output));
    	   }
       }).success(function(){
    	   $.colorbox.resize({
    		 innerHeight: ($(visible_container).offset().top + $(visible_container).height())
    	   });
       });
   });
	$(document).on('keydown', 'input.last_characteristic', function(e) { 
	    var keyCode = e.keyCode || e.which;
	    var ajax = new Ajax();
	    var field_type ='new_item_characteristic';
	    
	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_characteristic");
		  ajax.view('partials/form_elements',{
	    	   data: {
	    		 element: field_type
	    	   },
	       }).done(function(output) {
	    	   $('.new_characteristic').before($(output));
	       });
	    } 
	});
	$(document).on('keydown', 'input.last_feature', function(e) { 
	    var keyCode = e.keyCode || e.which;
	    var ajax = new Ajax();
	    var field_type ='new_item_feature';
	    
	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_feature");
		  ajax.view('partials/form_elements',{
	    	   data: {
	    		 element: field_type
	    	   },
	       }).done(function(output) {
	    	   $('.new_feature').before($(output));
	       });
	    } 
	});
	
	$(document).on('click', "#Gallery_tab", function(e){
        var guid       = $(this).data("guid");
        var field_type = 'gallery';
	    var ajax = new Ajax();
	    var state = $(this).data("state");
	    var panel_exists = $(this).next('#gallery_panel').length>0;
	    console.log(panel_exists);
	    if(panel_exists){
        	$("#gallery_panel").slideToggle("slow");
        	if ($(this).hasClass("selected")){
        		$(this).removeClass("selected");
        	}
        	else{
        		$(this).addClass("selected");
        	}
	    } 
	    else {
	        ajax.view('partials/form_elements',{
	        	data: {
	        		element: field_type,
	        		guid: guid,
	        	},
	        }).done(function(output){
	        	$('#Gallery_tab').after($(output));
	        	$("#gallery_panel").slideDown("slow");
	        	$("#Gallery_tab").addClass("selected");
	        });
	    }
	});
});