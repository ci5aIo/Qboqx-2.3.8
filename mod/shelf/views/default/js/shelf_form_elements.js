/**
 * 
 */

define(function(require) {

   var $      = require('jquery');
   var moment = require('moment_js');           //require Moment.js
   var Ajax   = require('elgg/Ajax');
	
	$(document).ready(function() {
	  var boqx  = $(".pallet"),
	      slot  = $('.slot'),
	      slots = $(".slots");
	
	  boqx.draggable({
	    containment: slots,
	    helper: "clone",
	    handle: '.tn-PanelHeader__inner___3Nt0t86w',
	    axis: "x",
	    stack: ".pallet",
	    zIndex: 100,
	    start: function() {
	       $(this).css({
	         opacity: .5
	       });
	    },
	    stop: function() {
	      $(this).css({
	        opacity: 1
	      });
	    }
	  });
	
	  slot.droppable({
	    accept: boqx,
	    tolerance: "pointer",
	    over: function(event, ui) {
	      $(this).addClass('ui-droppable-hover');
	    },
	    out: function() {
	      $(this).removeClass('ui-droppable-hover');
	    },
	    drop: function() {
	      $(this).removeClass('ui-droppable-hover');
	    }
	  });

	  $(document).on('dropover', '.slot', function(e, ui){
	      var moving_boqx    = ui.draggable;                                    //the dragged boqx
	      var moving_boqx_id = moving_boqx.attr('data-boqx'),                   //the cid of the slot that the moving_box is leaving
		      slot_id        = $(this).attr('id');                              //the cid of the slot_boqx
		          
	      if (moving_boqx.hasClass('pallet') && moving_boqx_id != slot_id){         //the dragged boqx is a pallet and it is over a new slot
		      var slot_boqx      = $(this).children('.pallet'),                     //the displaced pallet
		          to_slot_no     = $(this).data('slot');                            //the number of this slot 
		      var moving_boqx_guid= moving_boqx.data('guid'),                       //the guid of the dragged pallet
		          slot_boqx_guid = slot_boqx.data('guid');                          //the guid of the displaced pallet.  stored in the database
		      var from_slot      = $('#'+moving_boqx_id);                           //the slot that the moving_boqx is leaving
		      var from_slot_no   = $(from_slot).data('slot'),                       //the number of the slot the the moving_boqx is leaving.  stored in the database
		          moving_boqx_is_pallet = $(moving_boqx).hasClass('pallet');
		      $(slot_boqx).attr('data-boqx',moving_boqx_id);                        //give the displaced pallet the boqx cid of the slot that the moving_boqx is leaving
		      $(moving_boqx).attr('data-boqx',slot_id);                             //give the dragged pallet the boqx cid of this slot
		      $(from_slot).prepend(slot_boqx);                                      //move the pallet in the this slot to the slot that the moving_boqx is leaving
		      elgg.action('pallets/move', {                                         //store the new slot number for the displaced pallet in the database
		    	 data: {
		    		 column: from_slot_no,
		    		 guid: slot_boqx_guid
		    	 } 
		      });
	      }
	  });

	$(document).on('drop', '.slot', function(e,ui){
	      var moving_boqx           = ui.draggable;                                 //the dragged pallet
	      if (moving_boqx.hasClass('pallet')){
	          var slot_boqx      = $(this).children('.pallet'),                     //the displaced pallet
		          slot_id        = $(this).attr('id'),                              //the cid of the slot_boqx
		          to_slot_no     = $(this).data('slot');                            //the number of this slot 
		      var moving_boqx_id = moving_boqx.attr('data-boqx'),                   //the cid of the slot that the moving_box is leaving
		          moving_boqx_guid= moving_boqx.data('guid'),                       //the guid of the dragged pallet
		          slot_boqx_guid = slot_boqx.data('guid');                          //the guid of the displaced pallet.  stored in the database
		      var from_slot      = $('#'+moving_boqx_id);                           //the slot that the moving_boqx is leaving
		      var from_slot_no   = $(from_slot).data('slot');                       //the number of the slot the the moving_boqx is leaving.  stored in the database
		      $(moving_boqx).attr('data-boqx',slot_id);
		      $(moving_boqx).removeAttr('style');
		      $(slot_boqx).removeAttr('style');
		      $(this).prepend(moving_boqx);
		      elgg.action('pallets/move', {
		    	 data: {
		    		 column: to_slot_no,                                            //store the new slot number for the dragged pallet in the database
		    		 guid: moving_boqx_guid
		    	 } 
		      });
//		      elgg.action('pallets/move', {                                         //store the new slot number for the displaced pallet in the database
//		    	 data: {
//		    		 column: from_slot_no,
//		    		 guid: slot_boqx_guid,
//		    		 action: 'move displaced pallet',
//		    		 moving_boqx_id: moving_boqx_id
//		    	 } 
//		      });
	      }
	  });
	});
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