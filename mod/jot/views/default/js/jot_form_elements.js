/**
 * 
 */

define(function(require) {

   var $      = require('jquery');
   var moment = require('moment_js');           //require Moment.js
   var Ajax   = require('elgg/Ajax');

   $(document).on('click', 'a.new-item', function(e) {
       e.preventDefault();

       var ajax = new Ajax();
       var $this = $(this),
           presence = $(this).data('presence');
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
	    	   cid              = cid;
	    	   parent_cid       = parent_cid;
	    	   new_line_items   = cid+'_new_line_items'
	    	   new_property_cards = cid+'_line_item_property_cards'
	    	   break;
	       case 'new_loose_thing':
	    	   property_element = 'properties_loose_thing';
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
    		 presence: presence
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
    		 presence: presence
    	   },
       }).done(function(property_card) {
    	   $('div#'+new_property_cards).append($(property_card));
       }).success(function(){
    	   $this.attr("data-rows", n);
	       $this.attr("data-last-row", x);
       });
	   
   });
    $(document).on('click', '.model section.edit nav.edit .cancel', function(e){
       var cid        = $(this).data('cid'),
           parent_cid = $(this).data('parent-cid');
       var $pallet      = $(".Effort__CPiu2C5N[data-cid='"+cid+"']"),
           ajax       = new Ajax(),
           new_cid    = "c"+Math.floor((Math.random()*999)+1);
       console.log('click:cancel');
       console.log('cid = '+cid);
       $pallet.children(".EffortShow_haqOwGZY").remove();
       $pallet.children(".EffortEdit_fZJyC62e").remove();
       $pallet.children(".AddSubresourceButton___S1LFUcMd").show();
       
       ajax.view('partials/jot_form_elements',{
    	   data: {
    		   element: 'cancel_new_things',
    		   cid: cid,
    		   parent_cid: parent_cid
    	   },
       }).done(function(output) {
    	   $pallet.append($(output));
       });
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
           property_element = 'properties_service_item'
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
           if (points > 0 && points < 3){
        	   $boqx.attr('boqx-fill-level', 'partial');
        	   $boqx.find('input[data-focus-id = "FillLevel--'+cid+'"]').val('partial');
           }
           if (points >= 3){
        	   $boqx.attr('boqx-fill-level', 'full');
        	   $boqx.find('input[data-focus-id="FillLevel--'+cid+'"]').val('full');
           }
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
           $boqx.attr('boqx-fill-level', 'empty');
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
   $(document).on('click', '.ThingsBundle__submit___q0kFhFBf_xxx', function(e){
	  e.preventDefault();
	  var ajax         = new Ajax(),
	      form         = $(this).parents('form');
	  var formData     = $(form).serialize(),
	      action       = $(form).attr('action'),
		  method       = $(form).attr('method'),
		  edit_boqx    = $(form).find('.EffortEdit_fZJyC62e'),
		  add_boqx     = $(form).find('.AddSubresourceButton___S1LFUcMd');
	  console.log('action: '+action);
	  console.log('method: '+method);
	  console.log('formData: ',formData);
	  $(form).trigger('reset');
	  $(edit_boqx).hide();
	  $(add_boqx).show();
	  $.ajax({
		    type: 'POST',
		    url: action,
		    data: formData
		}).done(function(response) {
//			alert('done');
		}).fail(function(data) {
//			alert('failed');
		});		
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
	   var formData                = $(form).serialize(),
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
			   $(this).parents('table.ledger').parent('li').remove();
			   ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		 element: element,
		    		 guid: guid,
		    		 qid: qid,
		    		 space: space,
		    		 aspect: aspect,
		    		 perspective: perspective
		    	   },
		       })
			   break;
	   //perspective == 'view'
		   case 'view':
			   switch (element){
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
					   	var qbox = $('div#'+qid+'[data-perspective='+perspective+']');
				//perspective == 'edit'; element == 'qbox';
				   case 'market':
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
						   if($('div.qbox-visible#'+qid).data('perspective') == perspective ||
							  $('div.inline-visible#'+qid).data('perspective') == perspective){
							   return true;                                    // Do nothing.  This qbox is visible.
						   }
						   $('div.qbox-content-expand#'+qid).remove();
						   $('div.inline-content-expand#'+qid).remove();
					   }
					   if(qbox_exists){
						    qbox.parents('div.qbox-content-expand#'+qid).show();
						    qbox.show();
						    qbox.addClass('qbox-visible');
					   		return true;                                      // Show and do nothing more.
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
});

function filterList() {
//Source: https://www.w3schools.com/howto/howto_js_filter_lists.asp
	    // Declare variables
	    var input, filter, ul, li, a, i;
	    input = document.getElementById('qbox_filter');
	    filter = input.value.toUpperCase();
//	    ul = document.getElementsByClassName('ul.jq-dropdown-menu-option');
	    ul = document.getElementById("selections");
	    li = ul.getElementsByTagName('li');

	    // Loop through all list items, and hide those who don't match the search query
	    for (i = 0; i < li.length; i++) {
	        a = li[i].getElementsByTagName("a")[0];
	        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
	            li[i].style.display = "";
	        } else {
	            li[i].style.display = "none";
	        }
	    }
	}