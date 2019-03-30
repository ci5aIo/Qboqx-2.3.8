/**
 * 
 */

define(function(require) {

   var $      = require('jquery');
   var moment = require('moment_js');           //require Moment.js
   var Ajax   = require('elgg/Ajax');

   $(document).on('click', '.IconButton___0po345dx', function(e) {
       e.preventDefault();

       var ajax = new Ajax();
       var $this = $(this);
       var shelf_item = $(this).data('item-guid');
       var element = $(this).data('aid');
       var item_count = $('.shelf-item-count').attr('data-count');
       
       ajax.view('partials/shelf_form_elements',{
    	   data: {
    		 element: element,
    		 guid: shelf_item
    	   },
       }).done(function(output) {
    	   $('li#quebx-shelf-item-'+shelf_item).remove();
       }).success(function(){
    	   $('.shelf-item-count').attr('data-count', --item_count);
    	   $('.shelf-item-count').html(item_count);
       });	   
   });
   $(document).on('click', 'a.shelf-load', function(e) {
       e.preventDefault();

       var ajax = new Ajax();
       var item_guid = $(this).data('guid');
       var element = $(this).data('element');
       var item_count = $('.shelf-item-count').attr('data-count');
       
       ajax.view('partials/shelf_form_elements',{
    	   data: {
    		 element: element,
    		 guid: item_guid
    	   },
       }).done(function(output) {
    	   $('#shelf_list_items')
	    	   .find('.shelf-items-compartment')
				    .append($(output));
       }).success(function(){
    	   $('#quebx-shelf-item-'+item_guid).draggable({
    	   	    refreshPositions: true,
    	        revert:false,
    	        cursor: "move",
    	        cursorAt: { left: 50, top: 15 },
    	        zIndex: 1050,
    	        helper: function(event){
    	        	item = $('#quebx-shelf-item-'+item_guid);
    	            icon = item.find('div.elgg-image').html();
    	        	return $("<div class='elgg-image' data-item-guid='"+item_guid+"'>"+icon+"</div>" );
    	        }
    	    });
    	   $('.shelf-item-count').attr('data-count', ++item_count);
    	   $('.shelf-item-count').html(item_count);
       });
   });
});