/**
 * 
 */
/*
define(['elgg', 'jquery', 'quebx/widgets'], function(elgg, $, q_widgets) {

	$(document).on('click', '.space #sidebar_area li.available', quebx.space.q_widgets.add);
});*/
define(function(require) {

   var $      = require('jquery');
   var moment = require('moment_js');           //require Moment.js
   var Ajax   = require('elgg/Ajax');

   $(document).on('click', 'a.new-item', function(e) {
       e.preventDefault();

       var ajax = new Ajax();
       var $this = $(this),
           presence = $(this).data('presence'),
           presentation = $(this).data('presentation');
       var $receipt_card = $('div.qbox#'+qid);
       var element = $(this).attr("data-element");
       var guid     = $(this).attr("guid") || $(this).attr("data-guid");
       var qid      = $(this).attr("data-qid");
       var cid      = $(this).attr("data-cid") || "c"+Math.floor((Math.random()*999)+1);
       var parent_cid = $(this).parents('.edit.details').attr('data-cid');
	   if (typeof parent_cid == 'undefined')
	       parent_cid = $(this).attr('data-parent-cid');
       var rows     = +($(this).attr("data-rows")),
           lastRow  = +($(this).attr("data-last-row")),
           new_line_items = qid+'_new_line_items',
           new_property_cards = qid+'_line_item_property_cards';
       console.log('rows from data: '+ rows);
       console.log('parent_cid: '+parent_cid);
       console.log('cid: '+cid);
       if (rows == 0){
    	   rows = $(this).parents('div.rTableBody').find('.rTableRow.receipt_item').length;
       }
       var n          = (rows + 1);
       var x          = (lastRow + 1);
       var qid_n;
       if (typeof qid != 'undefined')
    	   qid_n      = qid+'_'+x;
       else qid_n     = cid+'_'+x;
	   new_line_items   = cid+'_new_line_items'
	   new_property_cards = cid+'_line_item_property_cards'
       switch (element){
	       case 'new_receipt_item':
	    	   property_element = 'properties_receipt_item';
	       break;
	       case 'new_service_item':
	    	   property_element = 'properties_service_item';
	    	   break;
	       case 'new_loose_thing':
	    	   property_element = 'properties_loose_thing';
	       break;
	       case 'new_book':
	    	   property_element = 'properties_book';
	    	   break;
	   }
       console.log('rows: '+rows);
       console.log('qid: '+qid);
       console.log('qid_n: '+qid_n);
       console.log('guid: '+guid);
       
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element: element,
//    		 guid: guid,
    		 qid_n: qid_n,
    		 qid: qid,
    		 cid: cid,
    		 parent_cid: parent_cid,
    		 n: x,
    		 presence: presence,
    		 presentation: presentation
    	   },
       }).done(function(output) {
    	   $('div#'+new_line_items).before($(output));
       });

       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element: property_element,
//    		 guid: guid,
    		 qid_n: qid_n,
    		 qid: qid,
    		 cid: cid,
    		 parent_cid: parent_cid,
    		 n: x,
    		 presence: presence,
    		 presentation: presentation
    	   },
       }).done(function(property_card) {
    	   $('div#'+new_property_cards).append($(property_card));
       }).success(function(){
    	   $this.attr("data-rows", n);
	       $this.attr("data-last-row", x);
       });
	   
   });
    $(document).on('click', '.model section.edit nav.edit .cancel', function(e){
    	e.preventDefault();
       var cid         = $(this).data('cid'),
           parent_cid  = $(this).data('parent-cid');
       var $pallet     = $(".Effort__CPiu2C5N[data-cid='"+cid+"']"),
           ajax        = new Ajax(),
           new_cid     = "c"+Math.floor((Math.random()*999)+1);
       var guid        = $pallet.attr('data-guid');
       var $this_panel = $pallet.children('.EffortEdit_fZJyC62e');
       var $show_panel = $pallet.children('.BoqxShow__lsk3jlWE');
       var boqx_exists = guid > 0,
           pallet_exists = $pallet.length > 0;
       console.log('click:cancel');
       console.log('cid = '+cid);
       if (boqx_exists){
    	   $show_panel.show();
    	   $this_panel.parents('form').remove();
       }
       else if (pallet_exists) {
           $this_panel.remove();
           $pallet.children(".AddSubresourceButton___S1LFUcMd").show();
           $pallet.children(".EffortShow_haqOwGZY").remove();
	       ajax.view('partials/jot_form_elements',{
	    	   data: {
	    		   element: 'cancel_new_things',
	    		   cid: cid,
	    		   parent_cid: parent_cid
	    	   },
	       }).done(function(output) {
	    	   $pallet.append($(output));
	       });
       }
    });
	$(document).on('click', '.remove-card', function(e){
		   e.preventDefault();
	      var cid        = $(this).data('cid');
	      var container  = $('#'+cid);
	      var action     = $(container).data('aid'),
		      boqx_id    = $(container).data('boqx'),
		      guid       = $(container).data('guid'),
		      aspect     = $(container).data('aspect');
	      var boqx       = $('#'+boqx_id),
	          boqx_aspect= $('#'+boqx_id).data('aspect');
		  var eggs       = 0;
			// remove the card
			$(container).remove();
			$('span.efforts-eggs[data-qid='+qid+']').attr('eggs', eggs-1);
            
		});
	$(document).on('click', '.replace-card, .IconButton___2y4Scyq6, .trashEnvelope_0HziOPGx', function(e){
		  e.preventDefault();
	      var ajax       = new Ajax(),
	          cid        = $(this).data('cid');
	      var envelope   = $('#'+cid);
	      var action     = $(envelope).data('aid'),
	          perspective= $(envelope).data('aid'), 
		      boqx_id    = $(envelope).data('boqx'),
		      carton_id  = $(envelope).data('carton'),
		      guid       = $(envelope).data('guid'),
		      aspect     = $(envelope).data('aspect'),
		      presence   = $(envelope).data('presence'),
		      presentation = $(envelope).data('presentation');                    console.log('164 guid = '+guid);
	      var boqx       = $('#'+boqx_id),
	          boqx_aspect= $('#'+boqx_id).data('aspect'),
	          carton     = $('#'+carton_id);
		  var carton_aspect= $(carton).data('aspect'),
		      cards       = 0,
              empty_cards = 0,
              view,
              snippet,
              carton_total=0,
			  envelope_cid,
			  envelope_total;
		  switch (carton_aspect){
			  case 'discoveries': 
			 	 view = 'experiences';
			 	 section = 'issue_discovery';
			 	 snippet = 'discovery';
			 	 break;
			  case 'remedies': 
			 	 view = 'experiences';
			 	 section = 'issue_remedy';
			 	 snippet = 'remedy';
			 	 break;
			  case 'issues':
				 view = 'experiences';
				 section = 'issue';
				 break;
			  case 'parts':
				 view = 'transfers';
				 section = 'boqx_contents';
				 snippet = 'single_part';
				 break;
			  case 'item' : 
				 view = 'market'; 
				 break;
    	   }
		  $(envelope).remove();
		  ajax.action('quebx/delete', {
			data:{
				guid: guid
			}
		  }).done(function () {
			//alert('deleted: '+guid);
		  });                                                                                       console.log('carton_id = '+carton_id);
		  $('[data-carton='+carton_id+']').each(function(){
			  cards++;
			  if ($(this).attr('boqx-fill-level') == 0)
				  empty_cards++;
			  envelope_cid = $(this).attr('id');                                                      console.log('envelope_cid = '+envelope_cid);
			  envelope_total = $('#'+envelope_cid+'_line_total_raw').text();                          console.log('envelope_total = '+envelope_total);
			  if(!isNaN(envelope_total) && envelope_total.length>0)
				  carton_total += parseFloat(envelope_total);
			});                                                                                       console.log('carton_total = '+carton_total);
			$('#'+boqx_id+'_total').text(moneyFormat(carton_total));
			$('#'+boqx_id+'_total_raw').text(carton_total);
			// add an empty card if needed
           if (empty_cards == 0){
        	   ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'conveyor',
		    		   view : view,
		    		   action: action,
		    		   perspective: perspective,
		    		   section: section,
		    		   snippet: snippet,
		    		   parent_cid: boqx_id,
		    		   carton_id: carton_id,
		    		   guid: guid,
		    		   aspect: aspect,
		    		   presentation: presentation,
		    		   presence: presence,
		    	   },
		       }).done(function(output) {
		    	   $(carton).append($(output));
		       }).success(function(){
					// remove the card
					$(envelope).remove();
			        $('#'+boqx_id).children('.tally').attr('boqxes', cards--);
		       });
           }
           else {
			// remove the card
			$('#'+boqx_id).children('.tally').attr('boqxes', cards--);
           }                                                             console.log('boqx_id: '+boqx_id);console.log('carton_id: '+carton_id);console.log('action: '+action);console.log('perspective: '+perspective);console.log('aspect: '+aspect);console.log('empty_cards: '+empty_cards);
            
		});
   $(document).on('click', '.TaskEdit__submit___3m10BkLZ', function(e){
       e.preventDefault();
       var cid          = $(this).attr("data-cid"),
       	   parent_cid   = $(this).parents('.edit.details').attr('data-cid'),
           qid          = $(this).attr("data-qid"),
           state        = $(this).attr('data-aid').replace('TaskButton',''),
           egg          = $(this).hasClass('egg');
       var service_name = $(this).parents('.TaskEdit__descriptionContainer___3NOvIiZo').find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
       var service_desc = $(this).parents('.TaskEdit__descriptionContainer___3NOvIiZo').find("textarea[data-focus-id=ServiceEdit--"+cid+"]").val();
       var service_items= $(this).parents('.TaskEdit__descriptionContainer___3NOvIiZo').find("a.new-item").attr('data-rows'),
           show_service = true,
           show_desc    = true;
       if (typeof service_name == 'undefined')
    	   show_service = false;
       if (typeof service_desc == 'undefined')
           show_desc    = false;
       var this_element = $(this),
   	       $this_panel  = $(this).parents('.TaskEdit___1Xmiy6lz'),
           $container   = $(this).parents('.ServiceEffort__26XCaBQk').parent(),
           $show_panel  = $(this).parents('.ServiceEffort__26XCaBQk').find('.TaskShow___2LNLUMGe'),
           $add_panel   = $(this).parents('.ServiceEffort__26XCaBQk').find('.AddSubresourceButton___2PetQjcb'),
           ajax         = new Ajax(),
           new_cid      = "c"+Math.floor((Math.random()*999)+1),
           property_element = 'properties_service_item';
       var eggs = parseInt($('span.tasks-count[data-cid='+parent_cid+']').attr('eggs'), 10);
       if (isNaN(eggs)){eggs = 0;}
       console.log('cid: '+cid);
       console.log('parent_cid: '+parent_cid);
       console.log('service_name: '+service_name);
       console.log('state: '+state);
       if (show_service){
    	   if (!show_desc){service_desc='Describe the service';}
           $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+service_name+'</p>');
           $show_panel.find('.TaskShow__description___qpuz67f').html('<p>'+service_desc+'</p>');
           $show_panel.show();
           $show_panel.find('button.IconButton___2y4Scyq6').show();
           $this_panel.hide();
           if (state=='add'){
        	   $(this).attr('data-aid', 'saveTaskButton');
        	   $(this).html('Save');
	           ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'new_service_task',
		    		   cid: new_cid,
		    		   parent_cid: parent_cid,
		    		   qid: qid
		    	   },
		       }).done(function(output) {
		    	   $container.append($(output));
		       }).success(function(){
		    	   if (egg){
	                 	$('span.tasks-count[data-cid='+parent_cid+']').attr('eggs', ++eggs);
	                 	$(this_element).removeClass('egg');
	                 }
		       });
           }
       }
       else {
           $add_panel.show();
           $this_panel.hide();
       }
   });
   $(document).on('click', '.ReceiptEdit__submit___6pelZHn', function(e){
       e.preventDefault();
       var cid          = $(this).attr("data-cid"),
       	   parent_cid   = $(this).parents('.edit.details').attr('data-cid'),
           qid          = $(this).attr("data-qid"),
           state        = $(this).attr('data-aid').replace('ReceiptButton',''),
           egg          = $(this).hasClass('egg');
       var service_name = $(this).parents('.TaskEdit__descriptionContainer___3NOvIiZo').find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
       var service_desc = $(this).parents('.TaskEdit__descriptionContainer___3NOvIiZo').find("textarea[data-focus-id=ServiceEdit--"+cid+"]").val();
       var service_items= $(this).parents('.TaskEdit__descriptionContainer___3NOvIiZo').find("a.new-item").attr('data-rows'),
           show_service = true,
           show_desc    = true;
       if (typeof service_name == 'undefined')
    	   show_service = false;
       if (typeof service_desc == 'undefined')
           show_desc    = false;
       var this_element = $(this),
   	       $this_panel  = $(this).parents('.TaskEdit___1Xmiy6lz'),
           $container   = $(this).parents('.ServiceEffort__26XCaBQk').parent(),
           $show_panel  = $(this).parents('.ServiceEffort__26XCaBQk').find('.TaskShow___2LNLUMGe'),
           $add_panel   = $(this).parents('.ServiceEffort__26XCaBQk').find('.AddSubresourceButton___2PetQjcb'),
           ajax         = new Ajax(),
           new_cid      = "c"+Math.floor((Math.random()*999)+1),
           property_element = 'properties_service_item';
       var eggs = parseInt($('span.receipts-count[data-cid='+parent_cid+']').attr('eggs'), 10);
       if (isNaN(eggs)){eggs = 0;}
       console.log('cid: '+cid);
       console.log('parent_cid: '+parent_cid);
       console.log('service_name: '+service_name);
       console.log('state: '+state);
       if (show_service){
    	   if (!show_desc){service_desc='Describe the service';}
           $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+service_name+'</p>');
           $show_panel.find('.TaskShow__description___qpuz67f').html('<p>'+service_desc+'</p>');
           $show_panel.show();
           $show_panel.find('button.IconButton___2y4Scyq6').show();
           $this_panel.hide();
           if (state=='add'){
        	   $(this).attr('data-aid', 'saveReceiptButton');
        	   $(this).html('Save');
	           ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'new_receipt',
		    		   cid: new_cid,
		    		   parent_cid: parent_cid,
		    		   qid: qid
		    	   },
		       }).done(function(output) {
		    	   $container.append($(output));
		       }).success(function(){
		    	   if (egg){
	                 	$('span.receipts-count[data-cid='+parent_cid+']').attr('eggs', ++eggs);
	                 	$(this_element).removeClass('egg');
	                 }
		       });
           }
       }
       else {
           $add_panel.show();
           $this_panel.hide();
       }
   });
   $(document).on('click', '.ReceiptAdd__submit___lS0kknw9', function(e){
       e.preventDefault();
       var cid          = $(this).attr("data-cid"),
       	   parent_cid   = $(this).parents('.add.details').attr('data-cid'),
           qid          = $(this).attr("data-qid"),
           state        = $(this).attr('data-aid').replace('ReceiptButton',''),
           presentation = $(this).data('presence'),
           egg          = $(this).hasClass('egg');
       var service_items= $(this).parents('.TaskEdit__descriptionContainer___3NOvIiZo').find("a.new-item").attr('data-rows'),
           show_merchant= true,
           show_receipt = true;
       var this_element = $(this),
   	       $this_panel  = $(this).parents('.TaskEdit___1Xmiy6lz'),
   	       $boqx        = $(this).parents('.ServiceEffort__26XCaBQk');
       var $pallet      = $boqx.parent(),
           $show_panel  = $boqx.find('.TaskShow___2LNLUMGe'),
           $add_panel   = $boqx.find('.AddSubresourceButton___2PetQjcb'),
           ajax         = new Ajax(),
           new_cid      = "c"+Math.floor((Math.random()*999)+1),
           property_element = 'properties_service_item',
           points       = 0;
       var eggs         = parseInt($('span.receipts-count[data-cid='+parent_cid+']').attr('eggs'), 10);
       var receipt_name = $this_panel.find("textarea[data-focus-id=NameEdit--"+cid+"]").val() || '[no receipt name]',
//           merchant     = $this_panel.find('h3.elgg-listing-summary-title').text() || $this_panel.find('input.elgg-input-group-picker').val(),
           merchant     = $this_panel.find('ul.elgg-group-picker-list').children('li').find('.elgg-body').text() || $this_panel.find('input.elgg-input-group-picker').val() || '[no merchant selected]',
           receipt_total= $this_panel.find('#'+cid+'_total').text() || '$0.00';
           receipt_total_raw= $this_panel.find('.'+cid+'_total_raw').text();
         if (typeof receipt_name  != 'undefined' && receipt_name.length  > 0) points++;
         if (typeof merchant      != 'undefined' && merchant.length      > 0) points++;
         if (typeof receipt_total != 'undefined' && receipt_total.length > 0 && parseFloat(receipt_total_raw)>0) points++;
/*       if (typeof receipt_name  != 'undefined' && receipt_name.length  > 0 &&
    	   typeof merchant      != 'undefined' && merchant.length      > 0 &&
    	   typeof receipt_total != 'undefined' && receipt_total.length > 0 && parseFloat(receipt_total_raw)>0 )
    	   show_receipt = true;
       if (typeof merchant == 'undefined')
           show_merchant    = false;
*/       if (isNaN(eggs)){eggs = 0;}
       console.log('cid: '+cid);
       console.log('parent_cid: '+parent_cid);
       console.log('receipt_name: '+receipt_name);
       console.log('state: '+state);
       console.log('merchant: '+merchant);
       console.log('receipt_total: '+receipt_total);
       console.log('points: '+points);
       if (show_receipt){
    	   //if (!show_merchant){merchant='No merchant selected';}
           $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+receipt_name+'</p>');
           $show_panel.find('.TaskShow__description___qpuz67f').html('<p>'+merchant+'</p>');
           $show_panel.find('.TaskShow__service_items___2wMiVig').html('<p>'+receipt_total+'</p>');
           $show_panel.show();
           $show_panel.find('button.IconButton___2y4Scyq6').show();
    	   $boqx.attr('boqx-fill-level', points);
    	   $boqx.find('input[data-focus-id = "FillLevel--'+cid+'"]').val(points);
           $this_panel.hide();
           if (state=='add'){
        	   $(this).attr('data-aid', 'saveReceiptButton');
        	   $(this).html('Save');
	           ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'new_receipt',
		    		   cid: new_cid,
		    		   parent_cid: parent_cid,
		    		   presentation: presentation,
		    		   qid: qid
		    	   },
		       }).done(function(output) {
		    	   $pallet.append($(output));
		       }).success(function(){
		    	   if (egg){
	                 	$('span.receipts-count[data-cid='+parent_cid+']').attr('eggs', ++eggs);
	                 	$(this_element).removeClass('egg');
	                 }
		       });
           }
       }
       else {
           $add_panel.show();
           $this_panel.hide();
           $boqx.attr('boqx-fill-level', '0');
       }
   });
   $(document).on('click', '.AddItem__submit___u7pvMd9T', function(e){
       e.preventDefault();
       var cid           = $(this).data('cid'),
           parent_cid    = $(this).data('boqx'),
       	   qid           = $(this).attr("data-qid"),
           state         = $(this).attr('data-aid').replace('ItemButton',''),
           egg           = $(this).hasClass('egg');
       var show_receipt  = true;
       var this_element  = $(this),
   	       $panel   = $('[data-aid=TaskEdit][data-cid='+cid+']'),
   	       $slot         = $('#'+cid);
   	       $boqx         = $('#'+parent_cid);
       var $pallet       = $('.boqx-pallet[data-cid='+parent_cid+']'),
           $show_panel   = $('[data-aid=TaskShow][data-cid='+cid+']'),
           $add_panel    = $('[data-aid=TaskAdd][data-cid='+cid+']'),
           ajax          = new Ajax(),
           property_element = 'properties_service_item',
           aspect        = $slot.data('aspect'),
           fill_level,
           points        = 0;
       var eggs          = parseInt($('span.item-count[data-cid='+parent_cid+']').attr('eggs'), 10);
       var item_name     = $("input[data-focus-id=NameEdit--"+cid+"]").val() || '[no item name]',
           item_total    = $('#'+cid+'_line_total').text() || '$0.00';
           item_total_raw= $('.'+cid+'_line_total_raw').text();
       if (typeof item_name  != 'undefined' && item_name.length  > 0) points++;
       if (typeof item_total != 'undefined' && item_total.length > 0 && parseFloat(item_total_raw)>0) points++;
       if (aspect == 'item' && points>= 1) fill_level = 'full'; // an item only needs a title to be complete
       else                                     fill_level = points;
       if (isNaN(eggs)){eggs = 0;}                                                                                 console.log('aspect: '+aspect);console.log('cid: '+cid);console.log('parent_cid: '+parent_cid);console.log('receipt_name: '+item_name);console.log('state: '+state);console.log('item_total: '+item_total);console.log('points: '+points);console.log('$boqx: ',$boqx);console.log('$pallet: ',$pallet);
       if (show_receipt){
    	   $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+item_name+'</p>');
           if (aspect == 'receipt_item')
        	   $show_panel.find('.TaskShow__item_total__Dgd1dOSZ').html('<p>'+item_total+'</p>');
           $show_panel.show();
           $show_panel.find('button.IconButton___2y4Scyq6').show();
    	   $slot.attr('boqx-fill-level', fill_level);
    	   $slot.find('input[data-focus-id="FillLevel--'+cid+'"]').val(fill_level);
           $panel.hide();
           if (state=='add'){
        	   $(this).attr('data-aid', 'saveItemButton');
        	   $(this).html('Save');
	           ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'new_item',
		    		   parent_cid: parent_cid,
		    		   aspect: aspect,
		    		   presence: 'panel',
		    		   presentation: 'pallet'
		    	   },
		       }).done(function(output) {
		    	   $pallet.append($(output));
		       }).success(function(){
		    	   if (egg){
	                 	$('span.item-count[data-cid='+parent_cid+']').attr('eggs', ++eggs);
	                 	$(this_element).removeClass('egg');
	                 }
		       });
           }
       }
       else {
           $add_panel.show();
           $panel.hide();
           $slot.attr('boqx-fill-level', fill_level);
       }
   });
   $(document).on('click', '.BoqxShow__lsk3jlWE', function(e){
	   e.preventDefault();
	   var $this        = $(this),
	       cid          = $(this).data('cid'),
           $container   = $(this).parents('.Effort__CPiu2C5N'),
           ajax         = new Ajax();
	   var guid         = $container.data('guid'),
           parent_cid   = $container.data('parent-cid'),
           perspective  = $container.data('aid');
	   var $list_boqx_edit = $('.EffortEdit_fZJyC62e[data-cid='+cid+']'),
	   	   list_boqx_edit_exists = true;
	   if ($list_boqx_edit.length == 0)
		   list_boqx_edit_exists = false;
	   console.log('cid: '+cid);
	   console.log('guid: '+guid);
	   console.log('$container: ',$container);
	   console.log('$list_boqx_edit: ',$list_boqx_edit);
	   console.log('typeof $list_boqx_edit: ',typeof $list_boqx_edit);
	   console.log('list_boqx_edit_exists: '+list_boqx_edit_exists);
	   console.log('perspective: '+perspective);
	   if (list_boqx_edit_exists){
		   $this.hide();
		   $list_boqx_edit.show();
	   }
	   else {
		   ajax.view('partials/jot_form_elements',{
	    	   data: {
	    		   element     : 'boqx',
	    		   perspective : perspective,
	    		   presentation: 'list_boqx_edit',
	    		   section     : 'things_boqx',
	    		   snippet     : 'contents_edit',
	    		   parent_cid  : parent_cid,
	    		   cid         : cid,
	    		   guid        : guid
	    	   	},			   
			   }).done(function(output) {
			    	   $container.append($(output));
			   }).success(function(){
					   $this.hide();
			   });
	   }
   });
   // Remedies>Effort
   $(document).on('click', '.EffortEdit__submit___CfUzEM7s', function(e){
       e.preventDefault();
       var cid          = $(this).data('cid'),
           qid          = $(this).data('qid'),
           parent_cid   = $(this).data('parent-cid');
           state        = $(this).attr('data-aid').replace('EffortButton',''), // 'data-aid changes. use attr() to retrieve.
           show_effort  = true,
           egg          = $(this).hasClass('egg');
       var this_element = $(this),
       	   $container   = $(this).parents('.Effort__CPiu2C5N').parent(),
           $this_panel = $(this).parents('.Effort__CPiu2C5N').children('.EffortEdit_fZJyC62e'),
           $show_panel = $(this).parents('.Effort__CPiu2C5N').children('.EffortShow_haqOwGZY'),
           $add_panel  = $(this).parents('.Effort__CPiu2C5N').children('.AddSubresourceButton___S1LFUcMd');
       var effort_name = $this_panel.find("textarea[data-focus-id=NameEdit--"+cid+"]").val();
       var ajax         = new Ajax(),
           new_cid      = "c"+Math.floor((Math.random()*999)+1),
           service_cid  = "c"+Math.floor((Math.random()*999)+1),
           property_element = 'properties_service_item';
       var eggs = parseInt($('span.efforts-eggs[data-cid='+parent_cid+']').attr('eggs'), 10);
       if (isNaN(eggs)){eggs = 0;}
       console.log('cid: '+cid);
       console.log('service_cid'+service_cid);
       console.log('effort_name: '+effort_name);
       console.log('state: '+state);
       if(typeof effort_name == 'undefined'){
           show_effort = false;
       }
       else {
           if (effort_name.length == 0)
               show_effort = false;
       }
       if (show_effort){
           $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+effort_name+'</p>');
           // remove vestigal spans
           $show_panel.find('span.TaskShow__description___qpuz67f').remove();
           $show_panel.find('span.TaskShow__service_items___2wMiVig').remove();
           $show_panel.show();
           $show_panel.find('button.IconButton___4wjSqnXU').show(); //restore delete button hidden if/when state = add
           $this_panel.hide();
           if (state=='add'){
        	   $(this).attr('data-aid', 'saveEffortButton');
        	   $container.attr('data-aid', 'edit');
        	   $container.find('#story_delete_button_'+cid).removeAttr('disabled');
        	   $(this).html('Save');
	           ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'new_remedy_item',
		    		   parent_cid: parent_cid,
		    		   cid: new_cid,
		    		   service_cid: service_cid,
		    		   qid: qid
		    	   },
		       }).done(function(output) {
		    	   $container.append($(output));
		       }).success(function(){
//		    	   $('span.qbox-menu[data-qid='+qid+']').show();
	               if (egg){
	                 	$('span.efforts-eggs[data-cid='+parent_cid+']').attr('eggs', ++eggs);
	                 	$(this_element).removeClass('egg');
	                 }
		       });
           }
       }
       else {
           $add_panel.show();
           $this_panel.hide();
       }
   });
   $(document).on('click', '.ThingsBundle__submit___q0kFhFBf', function(e){
	  // block action during testing 
	   return
	   e.preventDefault();
	  var ajax         = new Ajax(),
	      cid          = $(this).data('cid'),
	      boqx_id      = $(this).data('boqx'),
	      guid         = $(this).data('guid'),
	      aspect       = $(this).data('aspect');
	  var form         = $('#'+cid).children('form');
	  if (typeof form != 'undefined'){
		  var formData     = ajax.objectify(form),//$(form).serialize(),
		      action       = $(form).attr('action'),
			  method       = $(form).attr('method'),
		      title        = $(form).find('textarea[data-focus-id="NameEdit--'+cid+'"]').val(),
			  edit_boqx    = $('.EffortEdit_fZJyC62e[data-cid='+cid+']'),
			  add_boqx     = $('.AddSubresourceButton___S1LFUcMd[data-cid='+cid+']'),
			  show_boqx    = $('.BoqxShow__lsk3jlWE[data-cid='+cid+']'),
			  boqx_exists  = guid > 0;
		  console.log('action: '+action);
		  console.log('method: '+method);
		  console.log('formData: ',formData);
		  $(form).trigger('reset');
		  $.ajax({
			    type: 'POST',
			    url: action,
			    data: formData
			}).done(function(response) {
	//			alert('done');
			}).success(function() {
		      $(edit_boqx).remove();
			  if (boqx_exists){
				$(show_boqx).find('span.TaskShow__title___O4DM7q').text(title);
				$(show_boqx).show();}
			  else
				$(add_boqx).show();
			}).fail(function() {
	//			alert('failed');
			});
	  }
   });
/*   $(document).on('click', '.StuffEnvelope_6MIxIKaV', function(e){
       e.preventDefault();
       var ajax          = new Ajax(),
           cid           = $(this).data('cid'),
           show_service = true;
       var envelope      = $('#'+cid);
       var parent_cid    = $(envelope).data('boqx'),
       	   aspect        = $(envelope).data('aspect'),
           qid           = $(this).attr("data-qid"),
           state         = $(this).attr('data-aid').replace('ItemButton','');
       var show_receipt  = true;
       var this_element  = $(this),
   	       $panel        = $('[data-aid=TaskEdit][data-cid='+cid+']');
       var pallet       = $('.boqx-pallet[data-cid='+parent_cid+']'),
           $show_panel   = $('[data-aid=TaskShow][data-cid='+cid+']'),
           $add_panel    = $('[data-aid=TaskAdd][data-cid='+cid+']'),
           fill_level,
           points        = 0;
       var bom           = $(pallet).children('span.tally');
       var boqxes        = parseInt($(bom).attr('boqxes'), 10);
       var item_name     = $("input[data-focus-id=NameEdit--"+cid+"]").val() || '[no item name]',
           item_total    = $('#'+cid+'_line_total').text() || '$0.00';
           item_total_raw= $('.'+cid+'_line_total_raw').text();
           
       if (typeof service_name == 'undefined')
           show_service = false;
        else if (service_name.length==0)
                 show_service = false;                                                         console.log('show_service = '+show_service);
        
       if (typeof item_name  != 'undefined' && item_name.length  > 0) points++;
       if (typeof item_total != 'undefined' && item_total.length > 0 && parseFloat(item_total_raw)>0) points++;
       if (aspect == 'item' && points>= 1) fill_level = 'full'; // an item only needs a title to be complete
       else                                fill_level = points;
       if (isNaN(boqxes)){boqxes = 0;}                                                                                 console.log('aspect: '+aspect);console.log('cid: '+cid);console.log('parent_cid: '+parent_cid);console.log('receipt_name: '+item_name);console.log('state: '+state);console.log('item_total: '+item_total);console.log('points: '+points);console.log('$boqx: ',$boqx);console.log('$pallet: ',$pallet);
       if (show_service){
    	   $show_panel.find('.TaskShow__title___O4DM7q').html('<p>'+item_name+'</p>');
           if (aspect == 'receipt_item')
        	   $show_panel.find('.TaskShow__item_total__Dgd1dOSZ').html('<p>'+item_total+'</p>');
           $show_panel.show();
           $show_panel.find('button.IconButton___2y4Scyq6').show();
    	   $(envelope).attr('boqx-fill-level', fill_level);
    	   $(envelope).find('input[data-focus-id="FillLevel--'+cid+'"]').val(fill_level);
           $panel.hide();
           if (state=='add'){
        	   $(this).attr('data-aid', 'saveItemButton');
        	   $(this).html('Save');
	           ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'conveyor',
		    		   parent_cid: parent_cid,
		    		   aspect: aspect,
		    		   presence: 'panel',
		    		   presentation: 'pallet'
		    	   },
		       }).done(function(output) {
		    	   $(pallet).append($(output));
		       }).success(function(){
		    	   $(bom).attr('boqxes', ++boqxes);
		       });
           }
       }
       else {
           $add_panel.show();
           $panel.hide();
           $(envelope).attr('boqx-fill-level', fill_level);
       }
   });*/
    $(document).on("click", ".StuffEnvelope_6MIxIKaV", function(e) {
        e.preventDefault();
        var ajax         = new Ajax(),
            cid          = $(this).data('cid'),
            show_service = true;
	    var envelope     = $('#'+cid);
        var boqx_id      = $(envelope).data('boqx'),
		    carton_id    = $(envelope).data('carton'),
		    guid         = $(envelope).data('guid'),
		    aspect       = $(envelope).data('aspect'),
		    presence     = $(envelope).data('presence'),
		    presentation = $(envelope).data('presentation'),
            state        = $(this).attr('data-aid').replace('EffortButton','');
		var boqx         = $('#'+boqx_id),
		    boqx_aspect  = $('#'+boqx_id).data('aspect'),
			carton       = $('#'+carton_id);
		var carton_aspect= $(carton).data('aspect'),
		    cards        = 0,
			empty_cards  = 0,
			view,
			snippet,
            section,
            points       = 0;
		var service_name = $("[data-focus-id=NameEdit--"+cid+"]").val(),
		    service_desc = $("[data-focus-id=ServiceEdit--"+cid+"]").val(),
		    service_qty  = $("#"+cid+"_line_qty").val(),
		    service_cost = $("#"+cid+"_line_cost").val(),
		    service_time = $("#"+cid+"_hours").val(),
		    service_total= $('#'+cid+"_line_total").html(),
		    service_total_raw = $('.'+cid+"_line_total_raw").html(),
		    carton_total,
		    envelope_cid,
		    envelope_total,
			add_panel    = $('[data-aid=TaskAdd][data-cid='+cid+']'),
			show_panel   = $('[data-aid=TaskShow][data-cid='+cid+']'),
			edit_panel   = $('[data-aid=TaskEdit][data-cid='+cid+']');
        var state        = $(envelope).data('aid');                                   console.log('pallet_aspect: '+carton_aspect);console.log('state: '+state);console.log('presence: '+presence);console.log('presentation: '+presentation);

		if (typeof service_name  != 'undefined' && service_name.length  > 0) points++; console.log('service_name: '+service_name);
		if (typeof service_desc  != 'undefined' && service_desc.length  > 0) points++; console.log('service_desc: '+service_desc);
		if (typeof service_qty   != 'undefined' && service_qty.length   > 0) points++; console.log('service_qty: '+service_qty);
		if (typeof service_cost  != 'undefined' && service_cost.length  > 0) points++; console.log('service_cost: '+service_cost);
		if (typeof service_time  != 'undefined' && service_time.length  > 0) points++; console.log('service_time: '+service_time);
		if (typeof service_total != 'undefined' && service_total.length > 0 && parseFloat(service_total_raw)>0) points++;
			 
	    switch (carton_aspect){
		  case 'discoveries': 
		 	 view = 'experiences';
		 	 section = 'issue_discovery';
		 	 snippet = 'discovery';
		 	 break;
		  case 'remedies': 
		 	 view = 'experiences';
		 	 section = 'issue_remedy';
		 	 snippet = 'remedy';
		 	 break;
		  case 'issues':
			 view = 'experiences';
			 section = 'issue';
			 break;
		  case 'parts':
			  view = 'transfers';
			  section = 'boqx_contents';
			  snippet = 'single_part';
			  break;
		  case 'efforts':
			  view = 'transfers';
			  section = 'boqx_contents';
			  snippet = 'single_effort';
			  break;
		  case 'item' : 
			 view = 'market'; 
			 break;
		  case 'receipts':
			 break;
	   }
        
        if (typeof service_name == 'undefined')
           show_service = false;
        else if (service_name.length==0)
                 show_service = false;
        
		$(envelope).attr('boqx-fill-level', points);
    	$('input[data-focus-id = "FillLevel--'+cid+'"]').val(points);

		$('[data-carton='+carton_id+']').each(function(){
			cards++;
			if ($(this).attr('boqx-fill-level') == 0)
				empty_cards++;
		});

        if (state == 'add' || state == 'edit'){
              if (show_service){
                 $(show_panel).find('.TaskShow__qty_7lVp5tl4').html(service_qty);
                 $(show_panel).find('.TaskShow__title___O4DM7q').html(service_name);
                 $(show_panel).find('.TaskShow__description___qpuz67f').html(service_desc);
                 $(show_panel).find('.TaskShow__item_total__Dgd1dOSZ').html(service_total);
                 $(show_panel).find('.TaskShow__time___cvHQ72kV').html(service_time);
                 $(show_panel).show();
                 $(edit_panel).hide();
                 $(envelope).attr('data-aid', 'show');
             }
             else {
                 $(add_panel).show();
                 $(edit_panel).hide();
                 $(envelope).attr('data-aid', 'add');
             }
		   // add an empty card if needed
           if (empty_cards == 0){
        	     $(this).attr('data-aid', 'saveEffortButton');
        	     $(this).html('Save');        	     
        	     var del_title;
        	     if ($('.delete[data-cid='+cid+']').length > 0){
        	    	 $('.delete[data-cid='+cid+']').removeAttr('disabled');
        	    	 del_title = $('.delete[data-cid='+cid+']').attr('title');
        	    	 $('.delete[data-cid='+cid+']').attr('title',del_title.replace('(disabled)',''));
        	     }                                             console.log('view: '+view);console.log('section: '+section);console.log('snippet: '+snippet);console.log('boqx_id: '+boqx_id);console.log('carton_aspect: '+carton_aspect);console.log('carton_id: '+carton_id);
	           ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'conveyor',
		    		   view : view,
		    		   action: 'add',
		    		   perspective: 'add',
		    		   section: section,
		    		   snippet: snippet,
		    		   parent_cid: boqx_id,
		    		   carton_id: carton_id,
		    		   guid: guid,
		    		   aspect: aspect,
		    		   presentation: presentation,
		    		   presence: presence,
		    		   cards: cards,
		    	   },
		       }).done(function(output) {                          //console.log('carton: ',carton);console.log('output: ',output);
		    	   $(carton).append($(output));
		       }).success(function(){
					
		       });
           }
        }
        else{                                        // state == 'view'
            $(show_panel).show();
            $(edit_panel).hide();
            $(envelope).attr('boqx-fill-level', '0');
            $(envelope).attr('data-aid', 'show');
        }
    });  
   $(document).on('click', '.CloseItem__submit__oz8vFV9a', function(e){
	  e.preventDefault();
	  var ajax         = new Ajax(),
	      form         = $(this).parents('form'),
	      cid          = $(this).data('cid'),
	      boqx_id      = $(this).data('boqx');
	  var formData     = ajax.objectify(form), //$(form).serialize(),
	      action       = $(form).attr('action'),
		  method       = $(form).attr('method'),
	      title        = $('.NameEdit___Mak_'+cid).val(),
		  item_boqx    = $('.Item__nhjb4ONn#'+cid);
	  var item_guid    = $(item_boqx).data('guid'),
	      pallet_boqx  = $('#'+boqx_id);
	  var boqx_exists  = pallet_boqx.length > 0,
	      view_boqx    = $('.preview[data-cid='+boqx_id+']');
	  var labels       = '',
	      labels_post  = $('.labels.post[data-cid='+boqx_id+']');
	  $('.Label__Name___mTDXx408[data-cid='+cid+']').each(function(){
		 labels  += "<a class='std label' tabindex='-1'>"+$(this).text()+"</a>";
	  });
	  console.log('labels = '+labels);
	  console.log('labels_post = ',labels_post);
	  console.log('view_boqx = ',view_boqx);
	  $(form).trigger('reset');
	  $.ajax({
		    type: 'POST',
		    url: action,
		    data: formData
		}).done(function(response) {
//			alert('done');
		}).success(function() {
		    $(item_boqx).remove();
			$(view_boqx).find('span.StoryPreviewItem__title').text(title);
			$(labels_post).html(labels);
			$(view_boqx).removeClass('collapsed');		  
		}).fail(function() {
//			alert('failed');
		});		
   });
    $(document).on('click', '.SMkCk__Button', function(e){
    e.preventDefault();
     var ajax        = new Ajax(),
	     action      = $(this).data('aid'),
         cid         = $(this).data('cid'),
         boqx        = $(this).parents('.ItemEdit___7asBc1YY');
     var description = $('[data-focus-id=DescriptionEdit--'+cid+']').val();
     console.log('boqx: ',boqx);
     console.log('description: '+description);
     switch(action){
          case 'cancel':
              //boqx.find('.AutosizeTextarea__container___31scfkZp textarea.tracker_markup').val('');
              //boqx.find('.DescriptionShow___3-QsNMNj').html('');
              break;
          case 'save':
              $('[data-focus-id=DescriptionShow--'+cid+']').html('<span class="tracker_markup"><p>'+description+'</p></span>');
              $('[data-focus-id=Description--'+cid+']').val(description);
              break;
     }
      $('.DescriptionEdit___1FO6wKeX[data-cid='+cid+']').hide();
      $('.DescriptionShow___3-QsNMNj[data-cid='+cid+']').show();
    });
   $(document).on('click', 'a.jot-q', function(e) {
       e.preventDefault();
       var this_element = this;
       var ajax       = new Ajax(),
           element    = $(this).data("element"),
           guid       = $(this).data("guid"),
           qid        = $(this).data("qid"),
           space      = $(this).data("space"),
           perspective= $(this).data("perspective"),
           presentation= $(this).data("presentation"),
           action     = $(this).data("action");
       if (perspective == 'add' || action == 'add'){
    	   qid        = "q"+Math.floor((Math.random()*999)+1);                 // allow multiple forms for new jots
    	   cid        = "c"+Math.floor((Math.random()*999)+1);                 // allow multiple forms for new jots
       }
	   var qbox_exists = $('div.qbox-content-expand#'+qid).length>0;
	   if (!qbox_exists){
		   qbox_exists = $('div.qbox-container#'+qid).length>0;
	   }
	   var selected   = $(this).parent('li.elgg-state-selected').length>0;
	   var aspect     = $(this).data('aspect');
	   var context = $(this).data("context");
	   console.log('element: '+element);
	   console.log('guid: '+guid);
	   console.log('qid: '+qid);
	   console.log('space: '+space);
	   console.log('aspect: '+aspect);
	   console.log('perspective: '+perspective);
	   console.log('qbox_exists:'+qbox_exists);
	   console.log('selected: '+selected);
	   console.log('context: '+context);
	   
	   if(context=='ajax'){
		   $(this).parents(".rTableCell").find(".qbox-content-expand").hide();
		   $(this).parents("ul.elgg-htabs").find("li.elgg-state-selected").removeClass("elgg-state-selected");
		   if (!selected){
			   $(this).parent("li").addClass('elgg-state-selected');
			   $(this).parents('div.quebx-menu-q-expand').children('input#expand').attr('value',aspect);}
		   else{
			   $('div.qbox-content-expand#'+qid).hide()
			   $(this).parents('div.quebx-menu-q-expand').children('input#expand').attr('value','nothing');}
	   }
   
	   if(qbox_exists){
    	if (!selected){
        	   $(this).parents('div.quebx-menu-q').children('div.qbox-content-expand').hide();
        	   $(this).parents('div.quebx-menu-q').children('div.qbox-container').hide();
        	   $('div.qbox-content-expand#'+qid).show();
        	   $('div.qbox-container#'+qid).show();
     		   $('div.qbox#'+qid).addClass('qbox-visible');
     		   $('div.qbox.qbox-visible#'+qid).show();}
	    } 
	    else {
	    	if (perspective != 'add'){
	    		$(this).parents('div.quebx-menu-q').children('div.qbox-content-expand').hide();
	    		$(this).parents('div.quebx-menu-q').children('div.qbox-container').hide();}
 		   ajax.view('partials/jot_form_elements',{
	    	   data: {
	    		 element: element,
	    		 guid: guid,
	    		 qid: qid,
	    		 cid: cid,
	    		 space: space,
	    		 aspect: aspect,
	    		 action: action,
	    		 perspective: perspective,
	    		 presentation: presentation,
	    		 context: context
	    	   },
	       }).done(function(output) {
	    	   switch (context){
		    	   case "view_item":
		    		   console.log('context: '+context);
		    		   $.colorbox({html:$(output), top:"5px", overlayClose:false, escKey:false, reposition:false});
		    		   //Doesn't work.  Overridden in lightbox.css.  Not sure of the ramifications.
		    		   //$('#cboxOverlay').removeClass('overflow');
		    		   //$.colorbox.resize();
		    		   break;
		    	   case "market":
		    		   console.log('context: '+context);
		    		   //$(this_element).parents('.quebx-menu-q').append($(output));
		    		   $(this_element).parents('.elgg-item-object-market').append($(output));
		    		   //$('li#elgg-object-'+guid).prepend($(output));
		    		   break;
		    	   case "ajax":
		    		   console.log('context: '+context);
//		    		   $(this_element).parents(".menu-q-expand-content").append($(output));
		    		   $('div.qbox-'+guid+'.qbox-details').append($(output));
		    		   break;
		    	   case "widgets":
		    		   console.log('context: '+context);
		    		   $(this_element).parents("tbody").children("tr.odd-row").children("td.trans").append($(output));
		    		   break;
		    	   case 'quebx':
		    		   console.log('context: '+context);
		    		   $("div.elgg-main.elgg-body").children(".elgg-head.clearfix").append($(output));
		    		   break;
	    	   }
	       }).success(function(){
	    	   console.log('+jot_form_elements.a.jot-q>success');
	    	   if (element == 'q' && aspect == 'experience'){
		    	   $('.dropboqx').droppable({
		      	    	greedy: true,
		      	    	accept: '.quebx-shelf-item',
		      	    	tolerance: "touch",
		      	     	hoverClass: "box-state-highlight",
	//	      	    	create: function(event, ui){
	//	      	    		alert ('Dropboqx Created!')
	//	      	    	},
		   	   	    });
	    	   }
	       });
	    };
   });
   $(document).on('click', 'a.qbox-q', function(e) {
       e.preventDefault();
       var ajax       = new Ajax();
       var guid       = $(this).attr('guid');
       var card_qid   = $(this).parents('ul').attr('qid');
           cid        = $(this).data('cid');
           service_cid= "c"+Math.floor((Math.random()*999)+1);
       var qbox       = $('div[data-cid='+cid+']'),
           qbox_exists;
       var section_remove = $(this).parent().children('.qbox-section-remove'),
           section_remove_exists = true;
	   var selected   = $(this).parent('li.qbox-q').hasClass('elgg-state-selected');
	   var aspect     = $(this).attr('aspect'),
	       action     = $(this).attr('action');
	   var panel      = $(this).parent().attr('panel');
	   var element    = $(this).data('element');
	   var section    = $(this).data('section');
	   var this_element = $(this);
	   if (typeof cid == 'undefined')
		   cid        = "c"+Math.floor((Math.random()*999)+1);
       qbox_exists= qbox.length>0;
       if (typeof qbox == 'undefined')
	       qbox_exists = false;
       if (typeof section_remove == 'undefined')
    	   section_remove_exists= false;
	   console.log('element: '+element);
	   console.log('selected: '+selected);
	   console.log('aspect: '+aspect);
	   console.log('guid: '+guid);
	   console.log('cid: '+cid);
	   console.log('service_cid: '+service_cid);
	   console.log('section: '+section);
	   console.log('qbox: '+qbox); 
	   console.log('qbox.length: '+qbox.length);
	   console.log('qbox_exists '+qbox_exists);
	   if(!selected){
		   $(this).parents('ul').find("li.qbox-q").removeClass('elgg-state-selected');
	       $(this).parents('.rTableCell').find('div[aspect=attachments]').hide();
	       $(this).parents('.rTableCell').find('div.menu-q-expand-content').hide();
		   $(this).parent('li.qbox-q').addClass('elgg-state-selected');
	   }
	   else {
		   $(this).parent('li.qbox-q').removeClass('elgg-state-selected');
	   }
	   $('div.experience-panel[guid='+guid+']').hide();
	   /*
	       $(document).on("click","li.qbox-q", function(e) {
	           var section = $(this).children('.qbox-q').data('section');
	           $(this).parent().find("li.qbox-q").removeClass('elgg-state-selected');
	       	$(this).parents('.rTableCell').find('.menu-q-expand-content').hide();
	           if (section == 'expand...'){   
	           }
	       });
	   */
	   
	   // for Experience
	   if (section == 'expand...'){
		   if(qbox_exists){
			   qbox.show();}
		   if(!selected){
	    	   $(this).parents('.rTableCell').find('.menu-q-expand-content').show();
	       }
	       else {
	    	   $(this).parents('.rTableCell').find('.menu-q-expand-content').hide();
//@NOTE Not sure whether to set the value of the expand aspect to 'nothing' if one closes the Expand tab because the expand-content remains active when the Expand tab closes.
			   //$(this).parents('.rTableCell').find('div.quebx-menu-q-expand').children('input#expand').attr('value','nothing');
	       }
		   return true;
	   }
	   // for Transfer
	   $(this).parents('ul[aspect=transfer]').next('div.qbox-details').children('div').hide();
	   
	   if(qbox_exists){
	    	if (!selected){
	    		qbox.show();}
	    	else {
	    		qbox.hide();}
	    } 
	    else {
		   ajax.view('partials/jot_form_elements',{
	    	   data: {
	    		 element: element,
	    		 guid: guid,
	    		 cid: cid,
	    		 service_cid: service_cid,
	    		 aspect: aspect,
	    		 action: action,
	    		 section: section
	    	   },
	       }).done(function(output) {
	    	   $("div.qbox-details[data-qid='"+card_qid+"']").append($(output));
	    	   $(this_element).colorbox.resize();
			   $('#cboxLoadedContent').css('overflow', 'visible');
		   });   
	    }
	   ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element: 'qbox-menu',
    		 guid: guid,
    		 cid: cid,
    		 service_cid: service_cid,
    		 aspect: aspect,
    		 section: section
    	   },
       }).done(function(output) {
	       $(this_element).parent().prepend($(output));
       }).success(function(){
    	   console.log('+jot_form_elements.a.qbox-q>success');
       });
   });
   $(document).on('click', 'div.AddSomethingButton___k1dvTuKc', function(e){
	   var ajax       = new Ajax(),
           element    = $(this).data('element'),
           this_element = $(this),
           guid       = $(this).data('guid'),
           qid        = $(this).data('qid'),
//           cid        = $(this).data('cid'),
           cid        = "c"+Math.floor((Math.random()*999)+1);
           parent_cid = $(this).parents('div[panel="Things"]').attr('parent-cid'),
           aspect     = $(this).data('aspect'),
           action     = $(this).data('action'),
           perspective= $(this).data('perspective'),
           presence   = $(this).data('presence'),
           section    = $(this).data('section'),
           position   = $(this).data('position'),
           anchored   = $(this).data('anchored'),
           message    = $(this).find('.AddSomethingButton__message___2vsNCBXi').html();
	   var hoffset_container,
	       voffset_container,
	       hoffset,
	       voffset
	   if (anchored == 'anchored'){
		   hoffset = 0;
		   voffset = 0;
	   }
	   else {
	       hoffset_container = $(this_element).parents('div.qboqx-dropdown').css('left') || false,
           voffset_container = $(this_element).parents('div.qboqx-dropdown').css('top') || false;
           hoffset    = hoffset_container || $(this_element).offset().left + parseInt($(this).attr('data-horizontal-offset') || 0, 10),
           voffset    = voffset_container || $(this_element).offset().top + $(this_element).outerHeight() + parseInt($(this_element).attr('data-vertical-offset') || -100, 10);
	   }
       qbox_exists    = $('div.qboqx-dropdown#'+cid).length>0;

       console.log('cid: ' + cid);
       console.log('parent_cid: ' + parent_cid);
	   console.log('perspective: ' + perspective);
	   console.log('message: ' + message);

	   if(qbox_exists){
		   $('div.qboqx-dropdown#'+cid).show();
		   $('div.qboqx-dropdown#'+cid).css('left',hoffset);
		   $('div.qboqx-dropdown#'+cid).css('top',voffset);
			return true;
	   }
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element    : element,
    		 guid       : guid,
    		 qid        : qid,
    		 cid        : cid,
    		 aspect     : aspect,
    		 action     : action,
    		 perspective: perspective,
    		 presence   : presence,
    		 section    : section,
    		 message    : message
    	   },
       }).done(function(output) {
//    	   $('.qboqx#'+qid).append($(output));
    	   if (presence == 'popup'){
	    	   $('.qboqx#'+qid).find('.jotboqx').append($(output));
    	   }
    	   if (presence == 'qbox_experience'){
//    		   $('body').append($(output));
    		   $('.inline-content-expand[data-qid='+qid+']').find('.inlineLoadedContent').append($(output));
    	   }
		   $('div.qboqx-dropdown#'+cid).show();
		   $('div.qboqx-dropdown#'+cid).css('left',hoffset);
		   $('div.qboqx-dropdown#'+cid).css('top',voffset);
	   });   
	});
   $(document).on('click', 'button.do[data-perspective=save]', function(e){
	   e.preventDefault();
	   // sources:  http://blog.teamtreehouse.com/create-ajax-contact-form; https://stackoverflow.com/questions/16616250/form-submit-with-ajax-passing-form-data-to-php-without-page-refresh
	   var qid          = $(this).data('qid'),
	       guid         = $(this).data('guid');
	   var qid_n        = qid+'_0',
	       transfer     = $(this).parents('div#'+qid).prev('table[data-qid='+qid+']'),
	       item         = $(this).parents('li#elgg-object-'+guid),
	       form         = $(this).parents('form'),
	       formMessages = $('#form-messages'),
	       space       = $(this).parents('div#'+qid).data('space'),
	       aspect       = $(this).parents('div#'+qid).data('aspect'),
	       context      = $(this).parents('div#'+qid).data('perspective');
	   var formData                = ajax.objectify(form),// $(form).serialize(),
	       title                   = $(form).find('input[name=\'jot[title]\']').val(),
	       total                   = $(form).find('span.'+qid+'_total').text(),
	       to                      = $(form).find('input[name=\'jot[purchased_by]\']').val(),
	       shown_moment            = moment($(transfer).find('span.moment').html(), "YYYY-MM-DD"),
	       this_moment             = $(form).find("input[name='jot["+space+"][moment]']").attr('value'),
	       received_moment         = moment($(form).find('input[name=\'jot['+space+'][received_moment]\']').val()),
	       this_moment_display     = moment(this_moment).format("MM/DD/YYYY"),
	       received_moment_display = moment(received_moment).format("MM/DD/YYYY"),
	       shown_moment_display    = moment(shown_moment).format("MM/DD/YYYY"),
	       latest_moment           = moment($(transfer).find('span.latest-moment').text());
	   if ($(title).length == 0){
		   title                   = $(form).find('input[name=\'item[title]\']').val();
	   }
	   console.log('moment received:'+this_moment);
	   console.log('moment displayed:'+this_moment_display);
	   console.log('shown moment received: '+shown_moment);
	   console.log('shown moment displayed:'+shown_moment_display);
	   console.log('total:'+moneyFormat(total));
	   console.log('qid: '+qid);
	   console.log('context: '+context);
	   console.log('aspect: '+aspect);
	   console.log('title: '+title);
	   $.ajax({
		    type: 'POST',
		    url: $(form).attr('action'),
		    data: formData
		}).done(function(response) {
			    // Make sure that the formMessages div has the 'success' class.
			    $(formMessages).removeClass('error');
			    $(formMessages).addClass('success');
	
			    // Set the message text.
			    $(formMessages).text(response);
			switch (context){
				case 'edit':
					switch (aspect){
					case 'receipt':
						$(transfer).find('span.moment').text(this_moment_display);
			 		    if (received_moment>latest_moment || latest_moment==null){
			 		    	$(transfer).find('span.latest-moment').text(received_moment_display);}
			 		    $(transfer).find('a.do.title').text(title);
			 		    $(transfer).find('.total').text(moneyFormat(total));
			 		    $(transfer).find('.to').text(to);
			 		    $(transfer).find('.to').attr('title', to);
					  	break;
					case 'donate':
						status = 'donate'; 
						status_tag = 'D'; 
						status_msg = 'Donated';
						$(transfer).find('span.moment').text(this_moment_display);
						break;
					case 'trash':
						var status;
						var status_tag;
						var status_msg;
						switch (true){
							case $(form).find("input[name='jot[transfer][trash][disposition]'][value=recover]").prop("checked"):
								status = 'recover'; 
								status_tag = 'R'; 
								status_msg = 'Recovered';
							break;
							case $(form).find("input[name='jot[transfer][trash][disposition]'][value=trash]").prop("checked"):
								status = 'trash'; 
								status_tag = 'T'; 
								status_msg = 'Trash';
								$(transfer).find('span.moment').text(this_moment_display);
							break;
							case $(form).find("input[name='jot[transfer][trash][disposition]'][value=remove]").prop("checked"):
								status = 'remove'; 
								status_tag = 'R'; 
								status_msg = 'Removed';
								if (shown_moment < this_moment){
									$(transfer).find('span.latest-moment').text(shown_moment_display);}
							break;
							default:
								status='none found';
							break;
						}
						console.log('status: '+status);
						console.log('status_tag: '+status_tag);
						$(transfer).find('td.status').html(status_tag);
						$(transfer).find('td.status').attr('title', status_msg);
						break;
					case 'item':
						console.log('title: '+title);
						$(item).find('a.do[data-qid='+qid+'][data-perspective=view]').text(title);
						break;
					default:
						$(item).find('a.do[data-qid='+qid_n+'][data-perspective=view]').text(title);
						break;
					}
					$('div.qbox-content-expand#'+qid).remove();
					$('div.inline-content-expand#'+qid).parents('form').remove();
				  	$('div.inline-content-expand#'+qid).remove();
				    $('div.inline-container#'+qid).remove();
		 		    break;
				case 'add':
					$('div.qbox-container#'+qid).remove();
					break;
			}
		}).fail(function(data) {
		    // Make sure that the formMessages div has the 'error' class.
		    $(formMessages).removeClass('success');
		    $(formMessages).addClass('error');

		    // Set the message text.
		    if (data.responseText !== '') {
		        $(formMessages).text(data.responseText);
		    } else {
		        $(formMessages).text('Oops! An error occured and your message could not be sent.');
		    }
		});
		
	   function moneyFormat(nStr) {
		    nStr = parseFloat(nStr).toFixed(2).toString();
		    x = nStr.split('.');
		    x1 = x[0];
		    x2 = x.length > 1 ? '.' + x[1] : '';
		    var rgx = /(\d+)(\d{3})/;
		    while (rgx.test(x1)) {
		            x1 = x1.replace(rgx, '$1' + ',' + '$2');
		    }
		    return '$'+ x1 + x2;
		}
   });
   $(document).on('click', 'a.do, button.do', function(e){
	   e.preventDefault();
       var ajax       = new Ajax(),
           this_element = this,
           element      = $(this).data('element'),
           guid         = $(this).data('guid'),
           qid          = $(this).data('qid'),
           qid_n        = $(this).data('qid_n'),
           space        = $(this).data('space'),
           context      = $(this).data('context'),
           aspect       = $(this).attr('data-aspect'),
           perspective  = $(this).data('perspective'),
           presentation = $(this).data('presentation'),
           presence     = $(this).data('presence'),
           view_type    = $(this).data('view_type'),
           compartment  = $(this).data('compartment') || null,
           form_class   = 'inline-container';
	          
       var maximized_container = $(this).parents('.qbox-maximized');
       var full_view_container = $(this).parents('.quebx-item-body');
	   var qbox_compartment_exists = $('div.inline-compartment#'+qid_n).length>0;
       var qbox_compartment_visible = $('div.inline-compartment-visible').length>0; // A qbox compartment is visible.
              
       console.log('guid: '+guid);
	   console.log('qid: '+qid);
	   console.log('qid_n: '+qid_n);
	   console.log('perspective: '+perspective);
	   console.log('presentation: '+presentation);
	   console.log('presence: '+presence);
	   console.log('space: '+space);
	   console.log('aspect: '+aspect);
	   console.log('element: '+element);
	   console.log('compartment:'+compartment);
	   console.log('context: '+context);
	   console.log('qbox_compartment_exists: '+qbox_compartment_exists);
	   console.log('view_type: '+view_type);
	   console.log('action: '+action);
	   switch (context){
	   		case 'market':
	   			var this_container = $(this).parents('div.quebx-menu-q');
	   			break;
	   		case "inline":
	   			var this_container = $(this).parents('div.inline#'+qid).find('.inline-container').data('qid', qid);
	   			break;
	   }
	   switch (perspective){
	   //perspective == 'add'
	   		case 'add':
	   			ajax.view('partials/jot_form_elements',{
			    	   data: {
			    		 element: element,
			    		 guid: guid,
			    		 qid: qid,
			    		 qid_n: qid_n,
			    		 space: space,
			    		 aspect: aspect,
			    		 perspective: perspective,
			    		 presentation: presentation,
			    		 context: context,
			             compartment: compartment
			    	   },
			       }).done(function(output) {
			    	   //$('table.ledger-'+guid).after($(output));
			    	   switch (context){
				    	   case "widgets":
				    		   console.log('context: '+context);
				    		   $('table.ledger-'+guid).after($(output));
					    	   break;
			    	   }});
	   			break;
	   //perspective == 'delete'
	   		case 'delete':
	   			switch (element){
	   				case 'market':
	   					$('li#elgg-object-'+guid).remove();
	   					break;
	   				default:
					   $(this).parents('table.ledger').parent('li').remove();
	   			}
			   ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		 element: element,
		    		 perspective: perspective,
		    		 guid: guid,
		    		 qid: qid,
		    		 space: space,
		    		 aspect: aspect
		    	   },
		       })
			   break;
	   //perspective == 'view'
	   		case 'edit':
	   		case 'view':
			   switch (element){
			   	   case 'boqx':
			   		   ajax.view('partials/jot_form_elements',{
					    	   data: {
					    		 element: element,
					    		 guid: guid,
					    		 qid: qid,
					    		 qid_n: qid_n,
					    		 space: space,
					    		 aspect: aspect,
					    		 action: perspective,
					    		 perspective: perspective,
					    		 presentation: presentation,
					    		 context: context,
					             compartment: compartment,
					             view_type: view_type
					    	   },
					       }).done(function(output) {
					    	   //$('table.ledger-'+guid).after($(output));
					    	   switch (context){
						    	   case "widgets":
						    		   console.log('context: '+context);
						    		   $('table.ledger-'+guid).after($(output));
							    	   break;
						    	   case "market":
					    		      $(this_container).append($(output));
						    		   break;
						    	   case "inline":
									   $(this_container).append($(output));
									   break;
								   case "maximized":
						    		   $(maximized_container).prepend($(output));
						    		   break;
							   };
					       });
			   		   break;
		   //perspective == 'view'; element == 'qbox';
				   case 'qbox':
		   //perspective == 'view'; element == 'market';
				   case 'market':
					   var qbox_exists = $('div#'+qid+'[data-perspective='+perspective+']').length>0;
				       var qbox_visible = $('div.qbox-visible#'+qid).length>0; // Another qbox is visible.
				       if (!qbox_visible){
				    	   var qbox_visible = $('div.inline-visible#'+qid).length>0; // Another qbox is visible.
				       }
					   console.log('qbox_exists: '+qbox_exists);
					   console.log('qbox_visible: '+qbox_visible);
	//				   if (context == 'market'){
					   if(qbox_visible){
						   console.log('visible perspective: '+$('div.qbox-visible#'+qid).data('perspective'));
						   if($('div.qbox-visible#'+qid).data('perspective') == perspective ||
							  $('div.inline-visible#'+qid).data('perspective') == perspective){
							   return true;                                    // Do nothing.  This qbox is visible.
						   }
						   $('div.qbox-content-expand#'+qid).remove();
						   $('div.inline-content-expand#'+qid).remove();
					   }
					   if(qbox_exists){
							    $('div#'+qid+'[data-perspective='+perspective+']').parents('div.qbox-content-expand#'+qid).show();
						   		$('div#'+qid+'[data-perspective='+perspective+']').show();
						   		$('div#'+qid+'[data-perspective='+perspective+']').addClass('qbox-visible');
						   		return true;
						   }
	//				   }
						   ajax.view('partials/jot_form_elements',{
					    	   data: {
					    		 element: element,
					    		 guid: guid,
					    		 qid: qid,
					    		 qid_n: qid_n,
					    		 space: space,
					    		 aspect: aspect,
					    		 action: perspective,
					    		 perspective: perspective,
					    		 presentation: presentation,
					    		 context: context,
					             compartment: compartment,
					             view_type: view_type
					    	   },
					       }).done(function(output) {
					    	   //$('table.ledger-'+guid).after($(output));
					    	   switch (context){
						    	   case "widgets":
						    		   console.log('context: '+context);
						    		   $('table.ledger-'+guid).after($(output));
							    	   break;
						    	   case "market":
					    		      $(this_container).append($(output));
						    		   break;
						    	   case "inline":
									   $(this_container).append($(output));
									   break;
								   case "maximized":
						    		   $(maximized_container).prepend($(output));
						    		   break;
							   };
					       });
						   break;
		   //perspective == 'view'; element == 'popup';
				   case "popup":
					   var hoffset_container = $(this_element).parents('div.jq-dropdown').css('left') || false,
			               voffset_container = $(this_element).parents('div.jq-dropdown').css('top') || false;
				       var hoffset = hoffset_container || $(this_element).offset().left + parseInt($(this).attr('data-horizontal-offset') || 0, 10),
				           voffset = voffset_container || $(this_element).offset().top + $(this_element).outerHeight() + parseInt($(this_element).attr('data-vertical-offset') || 0, 10);
				       qbox_exists = $('div.jq-dropdown#'+qid).length>0;
					   
				       $(this_element).parents('div.jq-dropdown').hide();
						   
					   if(qbox_exists){
						   $('div.jq-dropdown#'+qid).show();
							return true;
					   }
					   ajax.view('partials/jot_form_elements',{
				    	   data: {
				    		 element: element,
				    		 guid: guid,
				    		 qid: qid,
				    		 qid_n: qid_n,
				    		 space: space,
				    		 aspect: aspect,
				    		 perspective: perspective,
				    		 presentation: presentation,
				    		 context: context,
	//			             compartment: compartment,
				             view_type: view_type
				    	   },
				       }).done(function(output) {
						   $('body').append($(output));
						   $('div.jq-dropdown#'+qid).show();
						   $('div.jq-dropdown#'+qid).css('left',hoffset);
						   $('div.jq-dropdown#'+qid).css('top',voffset);
				       });
					   break;
				//perspective == 'view'; element == 'compartment';
				   case 'compartment':
					   if(qbox_compartment_visible && context == 'inline'){
						   if($('div.inline-compartment-visible#'+qid_n).data('perspective') == perspective){
							   return true;                                    // Do nothing.
						   }
						   $('div.inline-compartment-visible').removeClass('inline-compartment-visible');
					   }
					   if(qbox_compartment_exists && context == 'inline'){
						    $('div.inline-content-expand#'+qid).show();
					   		$('div#'+qid_n+'[data-perspective='+perspective+']').addClass('inline-compartment-visible');
					   		$(this).parents('ul').find('li').removeClass('elgg-state-selected');
					   		$(this).parent('li').addClass('elgg-state-selected');
					   		return true;
					   }
					   
					   break;
				   }
				   
				   break;
		   //perspective == 'edit';
		   case 'edit':
			   switch (element){
				   case 'qbox':
				//perspective == 'edit'; element == 'qbox';
//					   break;
				   case 'market':
//					   	var qbox = $('div#'+qid);
					   	var qbox = $('div#'+qid+'[data-perspective='+perspective+']'),
					   	    this_boqx = $('div.inline-content-expand[data-qid='+qid+']');
				//perspective == 'edit'; element == 'market';
					   if (typeof qbox != 'undefined'){
						   var qbox_exists = qbox.length>0;
						   var qbox_visible = $('div.qbox-visible#'+qid).length>0; // Another qbox is visible.
					   }
				       if (!qbox_visible){
				    	   var qbox_visible = $('div.inline-visible#'+qid).length>0; // Another qbox is visible.
				       }
					   console.log('qbox_exists: '+qbox_exists);
					   console.log('qbox_visible: '+qbox_visible);
					   if(qbox_visible){
						   console.log('visible perspective: '+$('div.qbox-visible#'+qid).data('perspective'));
						   console.log('visible perspective: '+$('div.inline-visible#'+qid).data('perspective'));
						   if($('div.qbox-visible#'+qid).data('perspective') == perspective ||
							  $('div.inline-visible#'+qid).data('perspective') == perspective){
							   return true;                                    // Do nothing.  This qbox is visible.
						   }
						   $('div.qbox-content-expand#'+qid).remove();
						   $('div.inline-content-expand#'+qid).remove();
						   this_boqx.remove();
					   }
					   if(qbox_exists){
						    qbox.parents('div.qbox-content-expand#'+qid).show();
						    qbox.show();
						    qbox.addClass('qbox-visible');
					   		return true;                                      // Show and do nothing more.
					   }
//					   qbox.remove();
					   ajax.view('partials/jot_form_elements',{
				    	   data: {
				    		 element: element,
				    		 guid: guid,
				    		 qid: qid,
				    		 qid_n: qid_n,
				    		 space: space,
				    		 aspect: aspect,
				    		 perspective: perspective,
				    		 presentation: presentation,
				    		 context: context,
				             compartment: compartment,
				             form_class: form_class
				    	   },
				       }).done(function(output) {
				    	   //$('table.ledger-'+guid).after($(output));
				    	   switch (context){
				    	    //perspective == 'edit'; element == 'qbox' || 'market'; context == 'widgets';
					    	   case "widgets":
					    		   console.log('context: '+context);
					    		   $('table.ledger-'+guid).after($(output));
					    		   if (aspect=='receive'){
									   this_element = $('div.qbox#'+qid);
									   var toggle_button = $(this_element).find('a.do[data-qid='+qid+']');
									   $(toggle_button).attr('data-aspect', 'receipt');
									   $(this_element).find('#qboxTitle').text('Receive');
									   $(toggle_button).children('span').removeClass('elgg-icon-sign-in fa-sign-in');
									   $(toggle_button).children('span').addClass('elgg-icon-sign-out fa-sign-out');
									   $(toggle_button).children('span').attr('title', 'Receipt');
									   $(this_element).find('input[name=action]').attr('value','receive');
									   $(this_element).find('.receive-output').show();
									   $(this_element).find('.receive-input').show();
									   $(this_element).find('.receive-line-items').show();
									   $(this_element).find('.receipt-input').hide();
									   $(this_element).find('.receipt-line-items').hide();
									   $(this_element).find('.message-stamp').hide();
									   break;
					    		   }
						    	   break;
						    //perspective == 'edit'; element == 'qbox' || 'market'; context == 'market';
					    	   case "market":
					    		   console.log('context: '+context);
				    		      $(this_container).append($(output));
					    		   break;
						    //perspective == 'edit'; element == 'qbox' || 'market'; context == 'inline';
					    	   case "inline":
								   $(this_container).append($(output));
								   break;
						    //perspective == 'edit'; element == 'qbox' || 'market'; context == 'maximized';
							   case "maximized":
					    		   $(maximized_container).prepend($(output));
					    		   break;
						    //perspective == 'edit'; element == 'qbox' || 'market'; context == 'view_item';
							   case 'view_item':
								   $(full_view_container).append($(output));								   
								   break;
						   };
				       });
					   break;
				//perspective == 'edit'; element == 'popup';
				   case 'popup':
					   var action   = perspective,
					       selected = true
					       $this    = $(this);
					   var hoffset_container = $(this_element).parents('div.jq-dropdown').css('left') || false,
			               voffset_container = $(this_element).parents('div.jq-dropdown').css('top') || false;
				       var hoffset = hoffset_container || $(this_element).offset().left + parseInt($(this).attr('data-horizontal-offset') || 0, 10),
				           voffset = voffset_container || $(this_element).offset().top + $(this_element).outerHeight() + parseInt($(this_element).attr('data-vertical-offset') || 0, 10);
				       qbox_exists = $('div.jq-dropdown#'+qid).length>0;
					   
					   //$(this_element).parents('div.jq-dropdown').hide();
					   console.log('perspective: '+perspective);
/*				       if(qbox_exists){
						   $('div.jq-dropdown#'+qid).show();
							return true;
					   }
*/					   ajax.view('partials/jot_form_elements',{
				    	   data: {
				    		 element: element,
				    		 guid: guid,
				    		 qid: qid,
				    		 qid_n: qid_n,
				    		 action: action,
				    		 space: space,
				    		 aspect: aspect,
				    		 perspective: perspective,
				    		 presentation: presentation,
				    		 presence: presence,
				    		 context: context,
				             compartment: compartment
				    	   },
				       }).done(function(output) {
				    	   if (presence == element){        //The element originated from a popup
				    		   $this.
					    	   parents('.elgg-layout.elgg-layout-default').
						    	  html($(output));
				    	   }
				    	   else {
							   $('body').append($(output));
							   $('div.jq-dropdown#'+qid).show();
							   $('div.jq-dropdown#'+qid).css('left',hoffset);
							   $('div.jq-dropdown#'+qid).css('top',voffset);
				    	   }
				       });
					   
					   break;
				//perspective == 'edit'; element == 'phase';
				   case 'phase':
					   switch (aspect){
					//perspective == 'edit'; element == 'phase'; aspect == 'receive';
					   case 'receive':
						   $(this).attr('data-aspect', 'receipt');
						   $(this).parents('.qbox').find('#qboxTitle').text('Receive');
						   $(this).children('span').removeClass('elgg-icon-sign-in fa-sign-in');
						   $(this).children('span').addClass('elgg-icon-sign-out fa-sign-out');
						   $(this).children('span').attr('title', 'Receipt');
						   $(this).parents('form').find('input[name=action]').attr('value','receive');
						   $(this).parents('form').find('.receive-output').show();
						   $(this).parents('form').find('.receive-input').show();
						   $(this).parents('form').find('.receive-line-items').show();
						   $(this).parents('form').find('.receipt-input').hide();
						   $(this).parents('form').find('.receipt-line-items').hide();
						   $(this).parents('form').find('.message-stamp').hide();
						   break;
					//perspective == 'edit'; element == 'phase'; aspect == 'receipt';
					   case 'receipt':
						   $(this).attr('data-aspect', 'receive');
						   $(this).parents('.qbox').find('#qboxTitle').text('Receipt');
						   $(this).children('span').removeClass('elgg-icon-sign-out fa-sign-out');
						   $(this).children('span').addClass('elgg-icon-sign-in fa-sign-in');
						   $(this).children('span').attr('title', 'Receive');
						   $(this).parents('form').find('input[name=action]').attr('value','receipt');
						   $(this).parents('form').find('.receipt-input').show();
						   $(this).parents('form').find('.receipt-line-items').show();
						   $(this).parents('form').find('.message-stamp').show();
						   $(this).parents('form').find('.receive-output').hide();
						   $(this).parents('form').find('.receive-input').hide();
						   $(this).parents('form').find('.receive-line-items').hide();
						   break;
					   }
					   break;
				//perspective == 'edit'; element == 'experience';
				   case 'experience':
					   var presentation = 'popup',
					       action = 'edit',
					       selected = true;
					   var $this = $(this);
					   //Switch element to 'popup'
					   element = 'popup';
					   console.log('experience > element: '+element);
					   console.log('experience > perspective: '+perspective);
					   console.log('experience > guid: '+guid);
					   console.log('experience > qid: '+qid);
					   console.log('experience > presentation: '+presentation);
					   console.log('experience > action: '+action);
					   console.log('experience > space: '+space);
					   ajax.view('partials/jot_form_elements',{
				    	   data: {
				    		 element: element,
				    		 guid: guid,
				    		 qid: qid,
				    		 perspective: perspective,
				    		 presentation: presentation,
				             action: action,
				             space: space,
				             selected: selected,
				    	   },
				       }).done(function(output) {
				    	   $this.
					    	   parents('.elgg-layout.elgg-layout-default').
						    	  html($(output));
				       }).success(function(){
				    	   $('.dropboqx').droppable({
				    	    	accept: '.quebx-shelf-item',
				    	    	tolerance: "touch",
				    	    });
				       });
					   break;
			   }
			   break;
	   }
   });
   $(document).on('click', '.StoryPreviewItem__expander, .StoryPreviewItem__title', function(e){   console.log('StoryPreviewItem__expander');
	  //alert('works');
	  var ajax        = new Ajax();
	  var guid        = $(this).data('guid'),
	      cid         = $(this).data('cid');
      var item_boqx   = $('#'+cid);
      var boqx_id     = $(item_boqx).data('boqx');
      var boqx        = $('#'+boqx_id);
	  var $boqx_show  = $('[data-boqx='+cid+']'),
          $boqx_preview = $('header.preview[data-cid='+cid+']');
      var $boqx_exists = $boqx_show.length>0;
      var add_panel   = $('[data-aid=TaskAdd][data-cid='+cid+']'),
          show_panel  = $('[data-aid=TaskShow][data-cid='+cid+']'),
          edit_panel  = $('[data-aid=TaskEdit][data-cid='+cid+']');
      if($boqx_exists){
    	  $boqx_show.removeClass('collapsed');
    	  $boqx_preview.addClass('collapsed');
      }
      else {
    	  ajax.view('partials/jot_form_elements',{
			  data: {
					  element   : 'show boqx',
					  guid      : guid,
					  cid       : cid
				  },
			  }).done(function(output){
				  $(item_boqx).append($(output));
			  }).success(function(){
				 $boqx_preview.addClass("collapsed");
		  });
      }
   });
   $(document).on('click', 'button.estimate__item', function(e){
	  e.preventDefault(); 
   });
   $(document).on('change', 'input.receipt-line-item-behavior', function(e){
	   var ajax       = new Ajax();
       var element
       var guid       = $(this).data('guid');
       var qid_n      = $(this).data('qid');
	   var space      = 'receipt_line_item';
	   var aspect     = 'behavior';
	   var perspective= 'add';
	   var behavior   = $(this).val(); 
	   var new_item_details_exists = $('div#'+qid_n+'_new_item_details').length>0;
       console.log('qid_n: '+qid_n);
	   console.log('perspective: '+perspective);
	   console.log('space: '+space);
	   console.log('aspect: '+aspect);
	   console.log('behavior: '+behavior);
       if (behavior == 'create'){
    	element      = 'new_item_details';
 	   console.log('element: '+element);
 	   	   $(this).parent().addClass('new-item-details-visible');
	 	   if(new_item_details_exists){
		   		$('div#'+qid_n+'_new_item_details').show();
		   }
		   else {
		   ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		 element: element,
		    		 guid: guid,
		    		 qid_n: qid_n,
		    		 space: space,
		    		 aspect: aspect,
		    		 perspective: perspective,
		    		 behavior: behavior
		    	   },
		       }).done(function(output) {
		    	   $('ul.'+qid_n+'_line_item_behavior_list').find('input[value="create"]').parents('li').append($(output));
		       });
	   	   }
       }
       else {
    	   $('div#'+qid_n+'_new_item_details').hide();
    	   $(this).parents('ul').find('input[value="create"]').parent('label').removeClass('new-item-details-visible');
       }
   });
   $(document).on('drop', '.dropboqx', function(e, ui) {
       e.preventDefault();

       var ajax        = new Ajax(),
           item        = ui.draggable,
           qid         = $(this).data('qid'),
           element     = $(this).data('element')
           aspect      = $(this).data('aspect'),
           section     = $(this).data('section'),
           perspective = $(this).data('perspective');
      var  destination = $('.ThingsPallet__23erasdeR[data-qid="'+qid+'"]'),
		   item_guid   = item.attr("id").split("-").pop(),
		   item_count  = $("."+aspect+"-"+section+"-count[data-qid='"+qid+"']").attr('data-count');
      
      console.log('aspect: '+aspect);
      console.log('section: '+section);
      console.log('qid: '+qid);
      console.log('item_count string: '+"."+aspect+"-"+section+"-count[data-qid='"+qid+"']");
      console.log('item_count: '+item_count);
       
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : element,
    		 aspect        : aspect,
    		 perspective   : perspective,
    		 guid          : item_guid,
    		 qid           : qid,
    		 action        : 'add',
    		 section       : 'things_used',
    		 snippet       : 'things_used_view',
    		 container_type: 'experience',
    	   },
       }).done(function(output) {
    	   destination.append($(output));
       });
		   $("."+aspect+"-"+section+"-count[data-qid='"+qid+"']").attr('data-count', ++item_count);
		   $("."+aspect+"-"+section+"-count[data-qid='"+qid+"']").html(" ("+item_count+")");
   });
     $(document).on('click', '.panels .items .pallet_toggle', function(e){
          e.preventDefault();
          var ajax    = new Ajax(),
              cid     = $(this).data('cid'),
              boqx    = $(this).data('boqx'),
              handler = $(this).parent().attr('handler'),
              visible = $(this).parent().hasClass('visible'),
              slots   = $('.slots'),
              this_toggle = $(this),
              materialized,
              available = false;
          var pallet,
              last_slot = $(slots).children('.pallet').size();
          var min_size = 1900,
              required_size = (last_slot+1)*400;
          var floor_size = required_size < min_size ? min_size : required_size;
          if (boqx == 'shelf') pallet = $('.tc_page_bulk_header');
          else                 pallet = $('#'+cid);
          materialized = pallet.length>0;
          if (visible && materialized){ 
              pallet.removeClass('visible');
              $(this).parent().removeClass('visible');
              if (boqx == 'shelf') {
            	  $('.tc_page_nav_header').addClass('visible');
            	  $('.tc_pull_right').addClass('visible');
              }
              return;
          }
          if (!visible && materialized) {
              pallet.addClass('visible');
              $(this).parent().addClass('visible');
              if (boqx == 'shelf') {
            	  $('.tc_page_nav_header').removeClass('visible');
            	  $('.tc_pull_right').removeClass('visible');
              }
              return;
          }
          if(!available){
    		  ajax.view('partials/jot_form_elements',{
    			  data: {
    				  element   : 'pallet',
    				  handler   : handler,
    				  cid       : cid,
    				  last_slot : last_slot
    			  },
    		  }).done(function(output){
    			 $(output).addClass('visible');
    			 slots.append($(output));
    			 slots.attr('data-slots', last_slot+1);
    			 slots.css('width',floor_size);
                 this_toggle.parent().addClass('visible');
    		  }).success(function(response){
/*    	        var open_slots = $('.slots .pallet[data-contents=open]');
				var slot = open_slots[0];
				var slot_id = $(slot).attr('id');
				console.log('slot id: '+slot_id);
				$(slot).append(response);
				$(slot).attr('data-contents',boqx);
				$('li[cid='+cid+']').attr('cid',slot_id);
				$('button[data-cid='+cid+']').attr('data-cid',slot_id);*/
    			 
    		  });
        	  
/*        	var dataString = 'add=pallet&cid='+cid+'&boqx='+boqx,
        	    form       = $(this).parents('form');
        	var open_slots = $('.slots .pallet[data-contents=open]');
        	console.log('open slots: ', open_slots);
        	ajax.action('quebx/add',{
			    data: {add:'pallet',
			    	   cid: cid,
			    	   boqx: boqx,
			    	   hander: boqx,
			    	   context: 'warehouse',
			    	   show_access: true,
			    	   column: 1,
			    	   default_widgets: 0
			    	  }
			  }).done(function(response) {
			  }).success(function(response) {
				console.log('response: ',response);
				var slot = open_slots[0];
				var slot_id = $(slot).attr('id');
				console.log('slot id: '+slot_id);
				$(slot).append(response);
				$(slot).attr('data-contents',boqx);
				$('li[cid='+cid+']').attr('cid',slot_id);
				$('button[data-cid='+cid+']').attr('data-cid',slot_id);
			  }).fail(function() {
				 alert('failed');
			  });*/
          }
     });
    $(document).on('click', 'a.tn-CloseButton___2wUVKGfh', function(e){
       e.preventDefault();
       var pallet   = $(this).parents('.pallet'),
           slots    = $('.slots'),
    	   ajax     = new Ajax();
       var cid      = pallet.attr('id'),
           last_slot = $(slots).children('.pallet').size(),
           handler  = pallet.data('contents');
       var min_size = 1900,
           required_size = ((last_slot-1)*400) + 100;
       var floor_size = required_size < min_size ? min_size : required_size;
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : 'pallet',
    		 handler       : handler,
    		 perspective   : 'remove'
    	   },
		   }).done(function(){
			   pallet.remove();
			   slots.attr('data-slots', last_slot-1);
			   slots.css('width',floor_size);
		       $('li[cid='+cid+']').removeClass('visible');
	       });
    });
     $(document).on('click', '.tn-AddButton___hGq7Vqlr', function(e){
    	e.preventDefault();
    	var pallet  = $(this).parents('.pallet'),
    	    target  = $(this).data('target-boqx'),
    	    ajax    = new Ajax();
    	var handler = pallet.data('contents'),
    	    cid     = pallet.data('cid'),
    	    stack   = $(this).parents('.tn-PanelHeader___c0XQCVI7')//pallet.find('.tn-PanelHeader__input__xCdUunkH')//pallet.find('.tn-pallet__stack')
    	    ;
    	var empty_boqx = $('#'+target),
    	    liner = $('[data-boqx='+target+']');
    	var liner_exists = liner.length > 0;
    	//console.log('items_container: ',items_container);
    	if (liner_exists){
    		//empty_boqx.show();
    		liner.addClass('open');
    		//stack.closest('header.tn-PanelHeader___c0XQCVI7').addClass('open');
    		stack.addClass('open');
    	}
    	else
	    	ajax.view('partials/jot_form_elements',{
	    	   data: {
	    		 element       : 'empty boqx',
	    		 handler       : handler,
	    		 perspective   : 'add',
	    		 empty_boqx_id : target
	    	   },
		       }).done(function(output) {
		    	   //stack.prepend($(output));
		    	   //stack.find('.empty-boqx').show();
		    	   //stack.closest('header.tn-PanelHeader___c0XQCVI7').addClass('open');
		    	   empty_boqx.append($(output));
		    	   $('[data-boqx='+target+']').addClass('open');
    		       stack.addClass('open');
		       });
     });
	   //Add a new label when one presses the 'comma' key
	     $(document).on('keydown', 'input.LabelsSearch__input___3BARDmFr', function(e) { 
	         var keyCode = e.keyCode || e.which; 
	
	         if (keyCode == 188) { 
	           e.preventDefault(); 
	           var label = $(this).val(),
	               cid   = $(this).data('cid');
	           var label_container = $(this).parents('.StoryLabelsMaker__contentContainer___3CvJ07iU'),
	               label_badge = "<div class='Label___mHNHD3zD' tabindex='-1'><div class='Label__Name___mTDXx408' data-cid="+cid+">"+label+"</div><div class='Label__RemoveButton___2fQtutmR'></div><input type='hidden' name='jot["+cid+"][labels][]' value='"+label+"'></div>",
				   $selector = $('.BoqxLabelsPicker__Vof1oGNB[data-cid='+cid+']');
			   var $items = $selector.find('.SmartListSelector__child___zbvaMzth');
	           $(this).val('');
	           $(label_container).prepend(label_badge);
               $items.each(function () {
					if ($(this).text().toUpperCase() == label.toUpperCase()){
						$(this).addClass('label-selected');
					}
				});
	         } 
	     });
    $(document).on('click', '.dropdown_item.has_children', function(e){
    	var value = $(this).data('value'),
    	    cid   = $(this).parents('.dropdown').data('cid');
    	    compartment = $(this).closest('div'),
    	    ajax   = new Ajax();
    	ajax.view('partials/jot_form_elements',{
	    	   data: {
	    		 element       : 'dropdown_menu',
	    		 cid           : cid,
	    		 value         : value,
	    	   },
		       }).done(function(output) {
		    	   compartment.append($(output));
		       });
    });
    $(document).on("click", ".pickChildren__HBThno", function(e) {
       var value  = $(this).parent().data("value"),
           label  = $(this).parent().data('aspect'),
           aspect = $(this).parent().data('aspect'),
           cid    = $(this).parents('.dropdown').data('cid'),
           $menu  = $(this).parents('.weir_menu'),
           $boqx  = $(this).parents('.compartmentBoqx__m2HVyVRp, .compartmentBoqx__Cdil2TkU'),
           ajax   = new Ajax();
       var boqx_value    = $boqx.data('value'),
           boqx_aspect  = $boqx.data('aspect'),
           menu_level   = $boqx.data('level'),
           li_class,
           a_class;
       var $selections  = $menu.children('.weir_selections').children('.selections'),
            selector    = $menu.children('.weir_selections').children('.selector');
       
       if($boqx.hasClass('compartmentBoqx__Cdil2TkU')) {li_class = 'pickedItem__R8VF5oDQ'; a_class = 'pickedLink__1yKII8tz';}
       else                                            {li_class = 'pickedItem__Dows8rhn'; a_class = 'pickedLink__elUW0FxF';} 
       var crumb        = "<a class='"+a_class+"'><span class='pickedLabel__uNI8tKTa'>"+label+"</span></a>";
       var crumb_level  = $selections.find('ul').length;
       $(this).parents(".dropdown").children("input").attr("value", value);
       $(this).parents(".dropdown").children("a.selection").children("span").text(label);
       console.log('value: '+value);
       console.log('aspect: '+aspect);
       console.log('boqx_value: '+boqx_value);
       console.log('boqx_aspect: '+boqx_aspect);
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : 'weir_menu',
    		 flow          : 'down',
    		 cid           : cid,
    		 value         : value,
    		 aspect        : aspect,
    		 boqx_value    : boqx_value,
    		 boqx_aspect   : boqx_aspect,
    		 menu_level    : menu_level+1,
    	   },
	       }).done(function(output) {
	    	   $menu.children('.compartmentBoqx__m2HVyVRp, .compartmentBoqx__Cdil2TkU').remove();
	    	   $menu.append($(output));
	       }).success(function(){
	    	   $boqx.attr('data-value', value);
	    	   if (crumb_level > 0)
		    	   var last_label = $selections.find('ul')[crumb_level-1];
		       if (typeof last_label == 'undefined') $selections.html("<ul class='pickedList__XR2j7lQP'><li class='"+li_class+"' data-value='"+value+"' data-aspect='"+boqx_aspect+"' data-boqx='"+boqx_value+"' data-level='"+menu_level+"'>"+crumb+"</li></ul>")
		       else                                  $(last_label).children('li').append("<ul class='pickedList__XR2j7lQP'><li class='"+li_class+"' data-value='"+value+"' data-aspect='"+boqx_aspect+"' data-boqx='"+boqx_value+"' data-level='"+menu_level+"'>"+crumb+"</li></ul>");
		       $(selector).attr('data-value', value);
	       });
    });
    $(document).on("click", "li.pickedItem__Dows8rhn", function(e) {
       var value        = $(this).data("value"),
           boqx_aspect  = $(this).data('aspect'),
           boqx_value   = $(this).data('boqx'),
           crumb_level  = $(this).data('level'),
           cid          = $(this).parents('.dropdown').data('cid'),
           $menu        = $(this).parents('.weir_menu'),
           $pickSection = $(this).parents('.pickAspect__RFFo494j'),
           $pickedList  = $(this).closest('ul.pickedList__XR2j7lQP'), 
           ajax         = new Ajax();
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : 'weir_menu',
    		 flow          : 'up',
    		 cid           : cid,
    		 boqx_value    : boqx_value,
    		 boqx_aspect   : boqx_aspect,
    		 value         : value,
    		 menu_level    : crumb_level,
    	   },
	       }).done(function(output) {
	    	   if (crumb_level > 1){
		    	   $menu.children('.compartmentBoqx__m2HVyVRp').remove();
		    	   $menu.append($(output));
	    	   }
	    	   else {
	    		   $menu.remove();
	    		   $pickSection.append($(output));
	    	   }
	       }).success(function() {
	    	   $pickedList.remove();
	       });
    });
    $(document).on("click", "a.pickedLink__1yKII8tz", function(e) {
       var value        = $(this).parent('.pickedItem__R8VF5oDQ').data("value"),
           boqx_aspect  = $(this).parent('.pickedItem__R8VF5oDQ').data('aspect'),
           boqx_value   = $(this).parent('.pickedItem__R8VF5oDQ').data('boqx'),
           crumb_level  = $(this).parent('.pickedItem__R8VF5oDQ').data('level'),
           cid          = $(this).parents('.dropdown').data('cid'),
           $menu        = $(this).parents('.weir_menu'),
           $pickSection = $(this).parents('.pickCategory__VRYE6ZAO'),
           $pickedList  = $(this).closest('ul.pickedList__XR2j7lQP'), 
           ajax         = new Ajax();
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : 'weir_menu',
    		 flow          : 'up',
    		 cid           : cid,
    		 boqx_value    : boqx_value,
    		 boqx_aspect   : boqx_aspect,
    		 value         : value,
    		 menu_level    : crumb_level,
    	   },
	       }).done(function(output) {
	    	   if (crumb_level > 1){
		    	   $menu.children('.compartmentBoqx__Cdil2TkU').remove();
		    	   $menu.append($(output));
	    	   }
	    	   else {
	    		   $menu.remove();
	    		   $pickSection.append($(output));
	    	   }
	       }).success(function() {
	    	   $pickedList.remove();
	       });
    });
    $(document).on("click", "li.pickItem__GaGSmQJ6.has_children", function(e) {
       var value = $(this).data("value"),
           label = $(this).data('aspect'),
           aspect= $(this).data('aspect'),
           cid   = $(this).parents('.dropdown').data('cid'),
           $menu = $(this).parents('.weir_menu'),
           $boqx  = $(this).parents('.compartmentBoqx__Cdil2TkU'),
           ajax  = new Ajax();
       var boqx_name    = $boqx.data('boqx'),
           boqx_aspect  = $boqx.data('aspect'),
           boqx_value   = $boqx.data('value'),
           menu_level   = $boqx.data('level');
       var $selections  = $menu.children('.weir_selections').children('.selections'),
            selector    = $menu.children('.weir_selections').children('.selector');
       var crumb        = "<a class='pickedLink__7M19RCBV'><span class='pickedLabel__Pc5ckRQZ'>"+label+"</span></a>";
       var crumb_level  = $selections.find('ul').length;
       $(this).parents(".dropdown").children("input").attr("value", value);
       $(this).parents(".dropdown").children("a.selection").children("span").text(label);
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : 'weir_menu',
    		 cid           : cid,
    		 boqx_name     : aspect,
    		 boqx_aspect   : boqx_aspect,
    		 boqx_value    : boqx_value,
    		 aspect        : aspect,
    		 value         : value,
    		 menu_level    : menu_level+1,
    	   },
	       }).done(function(output) {
	    	   $menu.children('.compartmentBoqx__Cdil2TkU').remove();
	    	   $menu.append($(output));
	       }).success(function(){
	    	   if (crumb_level > 0)
		    	   var last_label = $selections.find('ul')[crumb_level-1];
		       if (typeof last_label == 'undefined') $selections.html("<ul class='pickedList__3rgp3Mtb'><li class='pickedItem__BWylRYY7' data-value='"+boqx_value+"' data-aspect='"+boqx_aspect+"' data-boqx='"+boqx_name+"' data-level='"+menu_level+"'>"+crumb+"</li></ul>")
		       else                                  $(last_label).children('li').append("<ul class='pickedList__3rgp3Mtb'><li class='pickedItem__BWylRYY7' data-value='"+boqx_value+"' data-aspect='"+boqx_aspect+"' data-boqx='"+boqx_name+"' data-level='"+menu_level+"'>"+crumb+"</li></ul>");
		       $(selector).attr('data-value', value);
	       });
    });
    $(document).on("click", ".pickedLink__7M19RCBV", function(e) {
       var $list_item   = $(this).parent('.pickedItem__BWylRYY7'),
            label       = $(this).children('.pickedLabel__Pc5ckRQZ').text();
       var value        = $list_item.data("value"),
       	   boqx_aspect  = $list_item.data('aspect'),
           boqx_name    = $list_item.data('boqx'),
           pickedList   = $list_item.parent('.pickedList__3rgp3Mtb'),
           crumb_level  = $list_item.data('level');
           cid          = $list_item.parents('.dropdown').data('cid'),
           $menu        = $list_item.parents('.weir_menu'),
           $pickSection = $list_item.parents('.pickCategory__VRYE6ZAO'),
           $pickedList  = $list_item.parent('ul'), 
           ajax         = new Ajax();
       $(this).parents(".dropdown").children("input").attr("value", value);
       $(this).parents(".dropdown").children("a.selection").children("span").text(label);
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : 'weir_menu',
    		 cid           : cid,
    		 boqx_name     : boqx_name,
    		 boqx_aspect   : boqx_aspect,
    		 value         : value,
    		 menu_level    : crumb_level,
    	   },
	       }).done(function(output) {
	    	   if (crumb_level > 1){
		    	   $menu.children('.compartmentBoqx__Cdil2TkU').remove();
		    	   $menu.append($(output));
	    	   }
	    	   else {
	    		   $menu.remove();
	    		   $pickSection.append($(output));
	    	   }
	       }).success(function() {
	    	   $pickedList.remove();
	       });
    });
    $(document).on('click', '.preview .selector', function(e){
       e.preventDefault();
       var cid              = $(this).data('cid'),
           shelf_selector   = $('li.shelf');
       var guid             = $('#'+cid).data('guid'),
           element,
           perspective      = 'header';
       var selected         = $(this).hasClass('selected'),
           selected_count   = 0,
           selected_counter = parseFloat($('.selectedStoriesControls__counter').text()),
           ajax             = new Ajax();
       console.log('selected_counter = '+selected_counter);
       element = selected ? 'delete' : 'load';
       
       if (selected){
          $(this).removeClass('selected');
          selected_count = selected_counter - 1;
          if (selected_count <= 0){
               $('.tc_page_bulk_header').removeClass('visible');
               $('.tc_pull_right').hide();
               $('.tc_page_nav_header').show();
          }
          $('.shelf-items-compartment .shelf-viewer#quebx-shelf-item-'+guid).remove();
       }
       else{
          $(this).addClass('selected');
          if (selected_counter <= 0){
               $('.tc_page_bulk_header').addClass('visible');
               $('.tc_pull_right').show();
               $('.tc_page_nav_header').hide();
          }
          selected_count = selected_counter + 1;
	      ajax.view('partials/shelf_form_elements',{
	    	   data: {
	    		 element    : element,
	    		 guid       : guid,
	    		 perspective: perspective
	    	   },
	      }).done(function(output) {
	    	   $('.shelf-items-compartment').append($(output));
	      });
      }
      if (selected_count == 0)
    	  selected_count = '';
      if (selected_count == 1)
          $('.selectedStoriesControls__counterLabel').html('item selected');
      else
          $('.selectedStoriesControls__counterLabel').html('items selected');
      $('.selectedStoriesControls__counter').html(selected_count);
      $(shelf_selector).attr('count', selected_count);
      $(shelf_selector).find('.counter').html(selected_count);
    });
         
   function moneyFormat(nStr) {
	    nStr = parseFloat(nStr).toFixed(2).toString();
        return '$' + addCommas(nStr);
	}
    function percentFormat(nStr, precision){
        nStr = parseFloat(nStr*100).toFixed(precision).toString();
        return nStr+'%';
    } 
function addCommas(nStr) {
// Source: https://www.codeproject.com/Questions/1103675/How-to-set-thousand-saprator-in-javascript-or-jque
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

   $(document).on('click.dropdown_menu', hide_dropdown);
});

function filterList(cid, list_id) {
//Source: https://www.w3schools.com/howto/howto_js_filter_lists.asp
	    var input, filter, ul, list, label, i;
	    input = document.getElementById('filter_'+cid);
	    filter = input.value.toUpperCase();
	    ul = document.getElementById(list_id);
	    list = ul.getElementsByTagName('li');

// Loop through all list items, and hide those who don't match the search query
	    for (i = 0; i < list.length; i++) {
	        label = list[i].getElementsByClassName('dropdown_label')[0];
	        if (label.innerHTML.toUpperCase().indexOf(filter) > -1) {
	            list[i].style.display = "";
	        } else {
	            list[i].style.display = "none";
	        }
	    }
	}

function hide_dropdown(event) {

    // In some cases we don't hide them
    var targetGroup = event ? $(event.target).parents().addBack() : null;

    // Are we clicking anywhere in a dropdown?
    if (targetGroup && (targetGroup.is('.dropdown'))) {
        // Is it a dropdown menu?
        if (targetGroup.is('.pickList__NRi0PbnO')) {
            // Did we click on an option? If so close it.
            if (!targetGroup.is('A')) return;
        } else {
            // Nope, it's a panel. Leave it open.
            return;
        }
    }

    // Trigger the event early, so that it might be prevented on the visible popups
    var hideEvent = jQuery.Event("hide");
    var $property_card =  $(document).find('.dropdown section.pick-boqx:visible').parent();
    $(document).find('.dropdown section.pick-boqx').each(function () {
        $(this).addClass('closed');
    });

    if(!hideEvent.isDefaultPrevented()) {
        // Hide any pick-boqxes that may be showing
        $(document).find('.dropdown section.pick-boqx').each(function () {
            $(this).addClass('closed');
        });

        $(document).find('.dropdown section.pick-boqx').addClass('closed');
    }
}
function empty( val ) {
        if (val === undefined)
        return true;
    if (typeof (val) == 'function' || typeof (val) == 'number' || typeof (val) == 'boolean' || Object.prototype.toString.call(val) === '[object Date]')
        return false;
    if (val == null || val.length === 0)        // null or 0 length array
        return true;
    if (typeof (val) == "object") {
        // empty object
        var r = true;
        for (var f in val)
            r = false;
        return r;
    }
}