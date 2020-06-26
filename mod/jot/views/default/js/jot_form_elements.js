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
    $(document).on('click', 'section.controls .cancelReplace__mw0ODp0p', function(e){
/* used in:
 	* forms/market/edit>edit>item edit>empty boqx
 	* forms/experiences/edit>add>issue_discovery
 	* forms/experiences/edit>add>issue_remedy>marker
 * functions related to:
 	*  on("click", ".packBoqx_QQFvJSIR" ...
 */
    	e.preventDefault();
        var $this             = $(this),
            ajax              = new Ajax(),
            cid               = $(this).data('cid'),
            show_service      = true;
	    var boqx,
	        parent_cid        = $('#'+cid).data('boqx'),
            envelope_id       = $('#'+cid).data('envelope'),
            presence          = $('#'+cid).data('presence');
	    switch (presence){
	    	case 'empty boqx' : boqx = $('#'+envelope_id); break;
    		default           : boqx = $('#'+cid);         break;}            
    	var carton_id         = $(boqx).data('carton'),
		    guid              = $(boqx).data('guid'),
		    aspect            = $(boqx).data('aspect'),
		    presentation      = $(boqx).data('presentation'),
            state             = $(boqx).data('aid'),
		    perspective       = 'add',
		    action            = 'add',
            display_state     = 'add';
		var carton            = $('#'+carton_id);
		var carton_aspect     = $(carton).data('aspect'),
		    boqxes            = 0,
		    filled_boqxes     = 0,
			empty_boqxes      = 0,
			filled_fields     = 0,
			handler,
			snippet,
            section,
            points            = 0;
       console.log('on click .cancelReplace__mw0ODp0p');
       console.log('parent_cid = '+parent_cid);
       console.log('carton_id = '+carton_id);
       $(boqx).remove();
       $('[data-carton='+carton_id+']').each(function(){
		    boqxes++;
			if ($(this).attr('boqx-fill-level') == 0){                                                console.log('boqx: '+$(this).attr('id'));
				empty_boqxes++;}
			if ($(this).attr('boqx-fill-level')>0)
				filled_boqxes++;
		});	                                                                                         console.log('empty_boqxes: '+empty_boqxes);console.log('filled_boqxes: '+filled_boqxes);console.log('state: '+state);
		switch (carton_aspect){
		  case 'item' :        handler = 'market';      section = 'single_thing';    perspective = 'edit';      if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'discoveries':  handler = 'experiences'; section = 'issue_discovery'; snippet = 'discovery';     if(filled_boqxes==1) carton_show_title = 'discovery'; else carton_show_title = 'discoveries'; break;
		  case 'remedies':     handler = 'experiences'; section = 'issue_remedy';    snippet = 'remedy';        if(filled_boqxes==1) carton_show_title = 'remedy';    else carton_show_title = 'remedies';    break;
		  case 'issues':       handler = 'experiences'; section = 'issue';                                      if(filled_boqxes==1) carton_show_title = 'issue';     else carton_show_title = 'issues';      break;
		  case 'parts':        handler = 'transfers';   section = 'boqx_contents';   snippet = 'single_part';   if(filled_boqxes==1) carton_show_title = 'part';      else carton_show_title = 'parts';       break;
		  case 'efforts':      handler = 'transfers';   section = 'boqx_contents';   snippet = 'single_effort'; if(filled_boqxes==1) carton_show_title = 'effort';    else carton_show_title = 'efforts';     break;
		  case 'qim' :         handler = 'transfers';   section = 'boqx_contents';   snippet = 'single_thing';  if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'receipt_item': handler = 'transfers';   section = 'boqx_contents';   snippet = 'single_item';   if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'receipts':     handler = 'transfers';   section = 'thing_receipt';   snippet = 'receipt';       if(filled_boqxes==1) carton_show_title = 'receipt';   else carton_show_title = 'receipts';    break;
	   }
		// add an empty card if needed
	   if (empty_boqxes == 0){
			if ($('.delete[data-cid='+cid+']').length > 0){
			    $('.delete[data-cid='+cid+']').removeAttr('disabled');
			    del_title = $('.delete[data-cid='+cid+']').attr('title');
				$('.delete[data-cid='+cid+']').attr('title',del_title.replace('(disabled)',''));
			}                                                                             console.log('handler: '+handler);console.log('section: '+section);console.log('snippet: '+snippet);console.log('parent_cid: '+parent_cid);console.log('carton_aspect: '+carton_aspect);console.log('carton_id: '+carton_id);
		   ajax.view('partials/jot_form_elements',{
			   data: {
				   element      : 'conveyor',
				   view         : handler,
				   action       : action,
				   perspective  : perspective,
				   display_state: display_state,
				   section      : section,
				   snippet      : snippet,
				   form_method  : 'pack',
                   parent_cid   : parent_cid,
				   carton_id    : carton_id,
				   guid         : guid,
				   aspect       : aspect,
				   presentation : presentation,
				   presence     : presence,
				   boqxes       : boqxes,
			   },
		   }).done(function(output) {
			   $(carton).append($(output));
		   }).success(function(){
				
		   });
	   }
    });
    $(document).on('click', 'section.controls .cancelReplace__TFF3oQiV', function(e){
/* used in 
 	* forms/experiences/edit>add>issue 
 */    	
    	e.preventDefault();
       var cid               = $(this).data('cid'),
           boqx_id           = $(this).data('boqx'),
           presentation      = $(this).data('presentation'),
           presence          = $(this).data('presence'),
           ajax              = new Ajax();
       var envelope          = $("#"+cid),
           handler           = $("#"+cid).data('aspect');
       var carton_id         = $(envelope).data('carton');
       var carton            = $("#"+carton_id);
       var outer_envelope_id = $(carton).data('envelope');
       var tally             = $(".envelope__NkIZUrK4[data-boqx="+carton_id+"]").length
//       var $this_panel = $('.envelopeWindow__3hpw9wdN.EffortEdit_fZJyC62e[data-cid='+cid+']');
//       var $add_panel  = $('.envelopeWindow__3hpw9wdN.AddSubresourceButton___2PetQjcb[data-cid='+cid+']')
       console.log('on click .cancelReplace__TFF3oQiV');
       console.log('handler = '+handler);
       console.log('boqx_id = '+boqx_id);
       console.log('carton_id = '+carton_id);
       console.log('tally = '+tally);
//       $this_panel.toggle();
//       $add_panel.toggle();
    	ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : 'empty boqx',
    		 handler       : handler,
    		 perspective   : 'add',
    		 presentation  : presentation,
    		 presence      : presence,
    		 parent_cid    : boqx_id,
    		 carton_id     : carton_id
    	   },
	       }).done(function(output) {
               $(envelope).remove();
               if (presence == 'contents') carton.prepend($(output));
               else              	       carton.append($(output));
		       tally   = $(".envelope__NkIZUrK4[data-boqx="+carton_id+"]").length;
		       $(carton).children('.tally').attr('boqxes', tally);
		       $('.media_drop').droppable({
					  	accept: '.boqx.file, .boqx.media',
					    tolerance: "touch",
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
			    $('.item_drop').droppable({
					    accept: '.boqx.item',
					    tolerance: "touch",
					    greedy:true,
					    scope: 'things',
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
	       });
    });
	$(document).on('click', '.disableItem_WCeQcYKQ', function(e){
	   e.preventDefault();
       var ajax          = new Ajax(),
	       cid           = $(this).data('cid'),
           boqx_id       = $(this).data('parent-cid')
           button_action = $(this).data('aid');
       var envelope      = $('#'+cid),
           boqx          = $('#'+boqx_id);
       var  guid         = $(boqx).data('guid');                    console.log('guid = '+guid);
       if (button_action == 'disable'){
         $(boqx).remove();
         if(typeof(guid) != 'undefined')
              ajax.action('quebx/disable', {
                 data:{
                      guid: guid
                 }
               }).done(function () {
               });
       }  
    });
	$(document).on('click','.IconButton___kmh1IhBB',function(e){
		  e.preventDefault();
	      var ajax          = new Ajax(),
	          this_row      = $(this).closest('.rTableRow'),
	          this_table    = $(this).closest('rTable');
	      this_row.remove();    
    });
	$(document).on('click', '.replace-card, .IconButton___2y4Scyq6, .trashEnvelope_0HziOPGx', function(e){
		  e.preventDefault();
	      var ajax          = new Ajax(),
	          cid           = $(this).data('cid'),
	          button_action = $(this).data('aid'),
              show_service  = true;
	      var envelope      = $('#'+cid);
	      var action        = $(envelope).data('aid'),
	          perspective   = $(envelope).data('aid'), 
		      boqx_id       = $(envelope).data('boqx'),
		      carton_id     = $(envelope).data('carton'),
		      guid          = $(envelope).data('guid'),
		      aspect        = $(envelope).data('aspect'),
		      presence      = $(envelope).data('presence'),
		      presentation  = $(envelope).data('presentation');                    console.log('guid = '+guid);
	      var boqx          = $('#'+boqx_id),
	          boqx_aspect   = $('#'+boqx_id).data('aspect'),
	          carton        = $('#'+carton_id);
		  var carton_aspect = $(carton).data('aspect'),
		      carton_envelope_id = $(carton).data('envelope'),
		      boqxes        = 0,
		      filled_boqxes = 0,
              empty_boqxes  = 0,
              carton_total  = 0,
              view,
              snippet,
			  envelope_cid,
			  envelope_total;
		  var carton_envelope     = $('#'+carton_envelope_id),
			  add_panel           = $('[data-aid=TaskAdd][data-cid='+cid+']'),
			  show_panel          = $('[data-aid=TaskShow][data-cid='+cid+']'),
			  edit_panel          = $('[data-aid=TaskEdit][data-cid='+cid+']'),
			  carton_add_panel    = $('[data-aid=TaskAdd][data-cid='+carton_envelope_id+']'),
			  carton_show_panel   = $('[data-aid=TaskShow][data-cid='+carton_envelope_id+']'),
			  carton_edit_panel   = $('[data-aid=TaskEdit][data-cid='+carton_envelope_id+']');
		  var service_qty         = $('input#'+cid+'_line_qty').val(),
		      service_name        = $('input#'+cid+'_line_title').val(),
		      service_desc        = $('input#'+cid+'_line_description').val(),
		      service_cost        = $('input#'+cid+'_line_cost').val(),
		      service_total       = $('input#'+cid+'_line_total').val(),
		      service_total_raw   = $('input#'+cid+'_line_total_raw').val();
		  var $sales_tax      = $("input[data-name='sales_tax'][data-cid="+boqx_id+"]"),
              $shipping       = $("input[data-name='shipping_cost'][data-cid="+boqx_id+"]"),
              $subtotal       = $("span#"+boqx_id+"_subtotal"),
              $subtotal_raw   = $("span#"+boqx_id+"_subtotal_raw"),
              $total          = $("span#"+boqx_id+"_total"),
              $total_raw      = $("span#"+boqx_id+"_total_raw");
	      var sales_tax       = parseFloat($sales_tax.val()),
              shipping        = parseFloat($shipping.val()),
              subtotal        = 0,
		      total;
            if (button_action == 'delete'){
			  $(envelope).remove();
			  if(typeof(guid) != 'undefined')
				  ajax.action('quebx/delete', {
					data:{
						guid: guid
					}
				  }).done(function () {
				  });
		    }                                                                                          console.log('carton_id = '+carton_id);
		  switch (carton_aspect){
		  case 'discoveries':  view = 'experiences'; section = 'issue_discovery'; snippet = 'discovery';     if(filled_boqxes==1) carton_show_title = 'discovery'; else carton_show_title = 'discoveries'; break;
		  case 'remedies':     view = 'experiences'; section = 'issue_remedy';    snippet = 'remedy';        if(filled_boqxes==1) carton_show_title = 'remedy';    else carton_show_title = 'remedies';    break;
		  case 'issues':       view = 'experiences'; section = 'issue';                                      if(filled_boqxes==1) carton_show_title = 'issue';     else carton_show_title = 'issues';      break;
		  case 'parts':        view = 'transfers';   section = 'boqx_contents';   snippet = 'single_part';   if(filled_boqxes==1) carton_show_title = 'part';      else carton_show_title = 'parts';       break;
		  case 'efforts':      view = 'transfers';   section = 'boqx_contents';   snippet = 'single_effort'; if(filled_boqxes==1) carton_show_title = 'effort';    else carton_show_title = 'efforts';     break;
		  case 'item' :        view = 'market';                                                              if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'receipt_item': view = 'transfers';   section = 'boqx_contents';   snippet = 'single_item';   if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'receipts':     view = 'transfers';   section = 'thing_receipt';   snippet = 'receipt';       if(filled_boqxes==1) carton_show_title = 'receipt';   else carton_show_title = 'receipts';    break;
	   }			
		    $('[data-carton='+carton_id+']').each(function(){
			  boqxes++;
			  if ($(this).attr('boqx-fill-level') == 0)
				  empty_boqxes++;
			  if ($(this).attr('boqx-fill-level')>0)
				  filled_boqxes++;
			  envelope_cid = $(this).attr('id');                                                      console.log('envelope_cid = '+envelope_cid);
			  envelope_total = $('#'+envelope_cid+'_line_total_raw').text();                          console.log('envelope_total = '+envelope_total);
			  if(!isNaN(envelope_total) && envelope_total.length>0)
				  carton_total += parseFloat(envelope_total);
			});                                                                                       console.log('carton_total = '+carton_total);
            $(carton).attr('boqxes',filled_boqxes);
			$(carton_envelope).attr('boqx-fill-level', filled_boqxes);
			$('#'+boqx_id+'_total').text(moneyFormat(carton_total));
			$('#'+boqx_id+'_total_raw').text(carton_total);
			
	        $(show_panel).find('.TaskShow__qty_7lVp5tl4').html(service_qty);
	        $(show_panel).find('.TaskShow__title___O4DM7q').html(service_name);
	        $(show_panel).find('.TaskShow__description___qpuz67f').html(service_desc);
	        $(show_panel).find('.TaskShow__item_total__Dgd1dOSZ').html(service_total);
		
			$(carton_show_panel).find('.TaskShow__qty_7lVp5tl4').html(filled_boqxes);
			$(carton_show_panel).find('.TaskShow__title___O4DM7q').html(carton_show_title);
			$(carton_show_panel).find('.TaskShow__item_total__Dgd1dOSZ').html(moneyFormat(carton_total));

			if (empty_boqxes == 0){
			// add an empty card if needed
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
			        $('#'+boqx_id).children('.tally').attr('boqxes', boqxes--);
		       });
           }
           else {
			// remove the card
			$('#'+boqx_id).children('.tally').attr('boqxes', boqxes--);
           }                                                             console.log('boqx_id: '+boqx_id);console.log('carton_id: '+carton_id);console.log('action: '+action);console.log('perspective: '+perspective);console.log('aspect: '+aspect);console.log('empty_boqxes: '+empty_boqxes);
		   //Recalculate
			$(boqx).find("span.line_total_raw").each(function(){
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
		});	
	$(document).on('click', '.IconButton___SUFDHCSY', function(e){
		/**
		 * Used in:
		 	* Receipts 
		 **/
		  e.preventDefault();
	      var ajax          = new Ajax(),
	          cid           = $(this).data('cid'),
	          button_action = $(this).data('aid');
	      var envelope      = $('#'+cid);
	      var action        = $(envelope).data('aid'),
	          perspective   = $(envelope).data('aid'), 
		      boqx_id       = $(envelope).data('boqx'),
		      carton_id     = $(envelope).data('carton'),
		      guid          = $(envelope).data('guid'),
		      aspect        = $(envelope).data('aspect'),
		      presence      = $(envelope).data('presence'),
		      presentation  = $(envelope).data('presentation');                    console.log('guid = '+guid);
	      var boqx         = $('#'+boqx_id),
		      add_panel     = $('[data-aid=TaskAdd][data-cid='+cid+']'),
			  show_panel    = $('[data-aid=TaskShow][data-cid='+cid+']'),
			  edit_panel    = $('[data-aid=TaskEdit][data-cid='+cid+']'),
			  carton        = $('#'+carton_id);
		  var carton_aspect = $(carton).data('aspect'),
		      boqxes        = 0,
		      filled_boqxes = 0,
              empty_boqxes  = 0,
              view,
              snippet;
		  var service_name = $("[data-focus-id=NameEdit--"+cid+"]").val(),
		      service_desc = $("[data-focus-id=ServiceEdit--"+cid+"]").val(),
		      service_qty  = $("#"+cid+"_line_qty").val(),
		      service_cost = $("#"+cid+"_line_cost").val(),
		      service_time = $("#"+cid+"_hours").val(),
		      service_total= $('#'+cid+"_line_total").html(),
		      service_total_raw = $('.'+cid+"_line_total_raw").html(),
		      carton_total = 0,
		  	  carton_show_title,
		      envelope_cid,
		      envelope_total;
		  var $sales_tax      = $("input[data-name='sales_tax'][data-cid="+boqx_id+"]"),
              $shipping       = $("input[data-name='shipping_cost'][data-cid="+boqx_id+"]"),
              $subtotal       = $("span#"+boqx_id+"_subtotal"),
              $subtotal_raw   = $("span#"+boqx_id+"_subtotal_raw"),
              $total          = $("span#"+boqx_id+"_total"),
              $total_raw      = $("span#"+boqx_id+"_total_raw");
	      var sales_tax       = parseFloat($sales_tax.val()),
              shipping        = parseFloat($shipping.val()),
              subtotal        = 0,
		      total;
              

		  switch (carton_aspect){
			  case 'receipts':      view = 'transfers';   section = 'thing_receipt';   snippet = 'receipt';     break;
    	   }
		  if (button_action == 'delete'){
			  $(envelope).remove();
			  if(typeof(guid) != 'undefined')
				  ajax.action('quebx/delete', {
					data:{
						guid: guid
					}
				  }).done(function () {
				  });
		  }
		  
			if (typeof service_name  != 'undefined' && service_name.length  > 0) points++; console.log('service_name: '+service_name);
			if (typeof service_desc  != 'undefined' && service_desc.length  > 0) points++; console.log('service_desc: '+service_desc);
			if (typeof service_qty   != 'undefined' && service_qty.length   > 0) points++; console.log('service_qty: '+service_qty);
			if (typeof service_cost  != 'undefined' && service_cost.length  > 0) points++; console.log('service_cost: '+service_cost);
			if (typeof service_time  != 'undefined' && service_time.length  > 0) points++; console.log('service_time: '+service_time);
			if (typeof service_total != 'undefined' && service_total.length > 0 && parseFloat(service_total_raw)>0) points++; console.log('service_total: '+service_total);
		  $('[data-carton='+carton_id+']').each(function(){
			  boqxes++;
			  if ($(this).attr('boqx-fill-level') == 0)
				  empty_boqxes++;
			  if ($(this).attr('boqx-fill-level')>0)
				  filled_boqxes++;
			});
            $(carton).attr('boqxes',filled_boqxes);
			$(show_panel).find('.TaskShow__qty_7lVp5tl4').html(service_qty);
			$(show_panel).find('.TaskShow__title___O4DM7q').html(service_name);
			$(show_panel).find('.TaskShow__description___qpuz67f').html(service_desc);
			$(show_panel).find('.TaskShow__item_total__Dgd1dOSZ').html(service_total);
			$(show_panel).find('.TaskShow__time___cvHQ72kV').html(service_time);
			
			// add an empty boqx if needed
           if (empty_boqxes == 0){
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
		       });
           }                                                             console.log('boqx_id: '+boqx_id);console.log('carton_id: '+carton_id);console.log('action: '+action);console.log('perspective: '+perspective);console.log('aspect: '+aspect);console.log('empty_boqxes: '+empty_boqxes);
		   //Recalculate
			$(boqx).find("span.line_total_raw").each(function(){
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
   $(document).on('click', '.submitBundle_q0kFhFBf, .packCarton_GAz9q0NX', function(e){ 
// Temporarily disable for testing
//	   return;
/* submitBundle_q0kFhFBf used in:
 	* forms/transfers/edit  > add  > things_bundle
 	* forms/transfers/edit  > edit > things_bundle
 	* forms/transfers/edit  > view > things_bundle
 	* forms/transfers/edit  > add  > thing_receipts  > (not) empty boqx
 	* forms/transfers/edit  > edit > thing_receipts  > (not) empty boqx
 * packCarton_GAz9q0NX used in:
 	* forms/experiences/edit  > add  > single_experience
 	* forms/experiences/edit  > add  > issue
 */
	   e.preventDefault(); 
	  var ajax         = new Ajax(),
	      $this        = $(this),
	      cid          = $(this).data('cid'),
	      boqx_id      = $(this).data('parent-cid'),                          // where presentation == 'pallet'
	      pallet_id    = $(this).data('parent-cid'),                          // where presentation == 'empty boqx'
	      form_id      = $(this).attr('form'),
	      guid         = $(this).data('guid'),
	      response;
	  var this_boqx    = $('#'+cid),
	      boqx         = $('#'+boqx_id),
	      form         = $('#'+form_id);
	  var carton_id    = $(boqx).data('carton'),
	      cache,
		  processed    = [],
	      update       = [],
	      create       = [];
	  var carton       = $('#'+carton_id);
	  var carton_aspect= $(carton).data('aspect'),
		  title        = $('[data-focus-id="NameEdit--'+cid+'"]').val(),
		  edit_boqx    = $('.EffortEdit_fZJyC62e[data-cid='+cid+']'),
		  add_boqx     = $('.AddSubresourceButton___S1LFUcMd[data-cid='+cid+']'),
		  show_boqx    = $('.EffortShow_haqOwGZY[data-cid='+cid+'], .BoqxShow__lsk3jlWE[data-cid='+cid+']'),
		  header_preview = $('header.preview[data-cid='+boqx_id+']'), 
		  preview_titles = $(document).find('.StoryPreviewItem__title[data-guid='+guid+']'),
		  boqx_exists  = $(boqx).length > 0; 
	  var header       = $('header[cid='+pallet_id+']');
//		  $(form).trigger('reset');
	  if($(this).hasClass('submitBundle_q0kFhFBf'))
		  response = 'submit bundle';
	  if ($(this).hasClass('packCarton_GAz9q0NX'))
		  response = 'pack carton';                                                       console.log('response: '+response);
	  if (boqx_exists && response == 'submit bundle'){
		  $(show_boqx).show();
	      $(this_boqx).remove();
		  $(header_preview).removeClass('collapsed');
    	  $(show_boqx).find('span.TaskShow__title___O4DM7q').text(title);
    	  $('span.StoryPreviewItem__title[data-cid='+boqx_id+']').text(title);
	  }
	  else if (response == 'submit bundle')
		$(add_boqx).show();
	  
	  if (boqx_exists && response == 'pack carton'){
		  $(this_boqx).addClass('collapsed');
	      $(header_preview).removeClass('collapsed');
	      $(header_preview).find('.contentsPreviewItem__collapser').removeClass('.contentsPreviewItem__collapser').addClass('.contentsPreviewItem__expander');
	      $(preview_titles).text(title);
	  }
	  switch(carton_aspect){
	  case 'qim':
		  cache = $('.BoqxThings__Ei7CMCSo.cache[data-cid='+boqx_id+']');
		  break;
	  case 'receipts':
		  cache = $('.BoqxReceipts__PPI3dHHq.cache[data-cid='+boqx_id+']');
		  break;	  
	  }
	  
	  $(header).removeClass('open');
      if (typeof form != 'undefined'){
		  var formData     = $(form).serialize(),   //ajax.objectify(form),//From elgg documentation: When setting data, use ajax.objectify($form) instead of $form.serialize(). Doing so allows the ajax_request_data plugin hook to fire and other plugins to alter/piggyback on the request.
		      action       = $(form).attr('action'),
			  method       = $(form).attr('method');                                           console.log('action: '+action);console.log('method: '+method);
		  $('body>input[type=file]').remove();      //hidden input fields added (inexplicably) by dropzone
//		  return;
	  	  $.ajax({
			    method: method,
			    url: action,
			    data: formData
			}).done(function(json) {
				$.each(json.output, function(index,value){
					processed.push(value);
					if(value['ui_response'] == 'update' && ('guid' in value))
						update.push(value);
					if(value['ui_response'] == 'create' && ('guid' in value))
						create.push(value);
				});
				$.each(create, function(index,value){                                           //console.log('create.'+index + ": " + value );
					$.each(value, function(index1, value1){                 					//console.log('create.'+index1 + ": " + value1 );
					});
					var contents  = value['contents'],
					    guid      = value['guid'],
					    aspect    = value['aspect'];
					var stack  = $('.slot[data-contents='+contents+']').children('.pallet');
					ajax.view('partials/jot_form_elements',{
			    	   data: {
			    		   element : 'create boqx',
			    		   guid    : guid,
			    		   handler : contents
			    	   },
					}).done(function(output){
						$(stack).prepend($(output));
					});
					// 
					ajax.view('partials/jot_form_elements',{
			    	   data: {
			    		   element : 'pack boqx',
			    		   guid    : guid,
			    		   boqx_id : boqx_id,
			    		   cid     : cid,
			    		   aspect  : carton_aspect
			    	   },
					}).done(function(output){
						$(cache).append($(output));
					});
				});
				$.each(update, function(index,value){                                           //console.log('update.'+index + ": " + value );
					$.each(value, function(index1, value1){                 					//console.log('update.'+index1 + ": " + value1 );
					});
				});
			}).success(function(result) {
			}).fail(function() {
				alert('failed');
			});
	  	  if (response == 'submit bundle'){
	  		  $(form).remove();
	  		  $(this_boqx).remove();
	  	  }
	  }
   });
    $(document).on("click", ".StuffEnvelope_6MIxIKaV", function(e) {
/* used in:
 	* forms/experiences/edit> add  > issue_discovery
 	* forms/experiences/edit> add  > issue_remedy    > marker
 	* forms/transfers/edit  > add  > thing_receipt   > marker
 	* forms/transfers/edit  > add  > thing_receipts  > empty boqx
 	* forms/transfers/edit  > add  > boqx_contents   > single_part
 	* forms/transfers/edit  > add  > boqx_contents   > single_effort
 	* forms/transfers/edit  > add  > boqx_contents   > single_item
 	* forms/transfers/edit  > edit > thing_receipts  > empty boqx
 	*/ 
        e.preventDefault();
        var $this        = $(this),
            ajax         = new Ajax(),
            cid          = $(this).data('cid'),
            show_service = true;
	    var envelope     = $('#'+cid);
        var boqx_id      = $(envelope).data('boqx'),
		    carton_id    = $(envelope).data('carton'),
		    guid         = $(envelope).data('guid'),
		    aspect       = $(envelope).data('aspect'),
		    presence     = $(envelope).data('presence'),
		    presentation = $(envelope).data('presentation'),
		    perspective       = 'add',
		    action            = 'add',
//            state        = $(this).attr('data-aid').replace('EffortButton',''),
            state        = $(envelope).data('aid');
		var boqx         = $('#'+boqx_id),
		    boqx_aspect  = $('#'+boqx_id).data('aspect'),
			carton       = $('#'+carton_id);
		var carton_aspect= $(carton).data('aspect'),
		    carton_envelope_id = $(carton).data('envelope') || $(carton).data('boqx'),
		    boqxes        = 0,
		    filled_boqxes = 0,
			empty_boqxes  = 0,
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
		    carton_total = 0,
			carton_show_title,
		    envelope_cid,
		    envelope_total;
		var carton_envelope     = $('#'+carton_envelope_id),
			add_panel           = $('[data-aid=TaskAdd][data-cid='+cid+']'),
			show_panel          = $('[data-aid=TaskShow][data-cid='+cid+']'),
			edit_panel          = $('[data-aid=TaskEdit][data-cid='+cid+']'),
			carton_add_panel    = $('[data-aid=TaskAdd][data-cid='+carton_envelope_id+']'),
			carton_show_panel   = $('[data-aid=TaskShow][data-cid='+carton_envelope_id+']'),
			carton_edit_panel   = $('[data-aid=TaskEdit][data-cid='+carton_envelope_id+']');                                   console.log('carton_id: '+carton_id);console.log('carton_envelope_id: '+carton_envelope_id);console.log('carton_aspect: '+carton_aspect);console.log('state: '+state);console.log('presence: '+presence);console.log('presentation: '+presentation);

		if (typeof service_name  != 'undefined' && service_name.length  > 0) points++; console.log('service_name: '+service_name);
		if (typeof service_desc  != 'undefined' && service_desc.length  > 0) points++; console.log('service_desc: '+service_desc);
		if (typeof service_qty   != 'undefined' && service_qty.length   > 0) points++; console.log('service_qty: '+service_qty);
		if (typeof service_cost  != 'undefined' && service_cost.length  > 0) points++; console.log('service_cost: '+service_cost);
		if (typeof service_time  != 'undefined' && service_time.length  > 0) points++; console.log('service_time: '+service_time);
		if (typeof service_total != 'undefined' && service_total.length > 0 && parseFloat(service_total_raw)>0) points++;      console.log('service_total: '+service_total);
        
        if (typeof service_name == 'undefined')                  show_service = false;
        else if (service_name.length==0)         if (points > 0) service_name = '(missing name)';
                                                 else         	 show_service = false;
        
		$(envelope).attr('boqx-fill-level', points);
		
    	$('input[data-focus-id = "FillLevel--'+cid+'"]').val(points);

		$('[data-carton='+carton_id+']').each(function(){
			boqxes++;
			if ($(this).attr('boqx-fill-level') == 0){                                                console.log('boqx: '+$(this).attr('data-cid'));
				empty_boqxes++;}
			if ($(this).attr('boqx-fill-level')>0)
				filled_boqxes++;
			  envelope_cid = $(this).attr('id');                                                      console.log('envelope_cid = '+envelope_cid);
			  envelope_total = $('#'+envelope_cid+'_line_total_raw').text();                          console.log('envelope_total = '+envelope_total);
			  if(!isNaN(envelope_total) && envelope_total.length>0)
				  carton_total += parseFloat(envelope_total);                                         
		});                                                                                           console.log('empty_boqxes: '+empty_boqxes);console.log('filled_boqxes: '+filled_boqxes);
		switch (carton_aspect){
		  case 'discoveries':  view = 'experiences'; section = 'issue_discovery'; snippet = 'discovery';     if(filled_boqxes==1) carton_show_title = 'discovery'; else carton_show_title = 'discoveries'; break;
		  case 'remedies':     view = 'experiences'; section = 'issue_remedy';    snippet = 'remedy';        if(filled_boqxes==1) carton_show_title = 'remedy';    else carton_show_title = 'remedies';    break;
		  case 'issues':       view = 'experiences'; section = 'issue';                                      if(filled_boqxes==1) carton_show_title = 'issue';     else carton_show_title = 'issues';      break;
		  case 'parts':        view = 'transfers';   section = 'boqx_contents';   snippet = 'single_part';   if(filled_boqxes==1) carton_show_title = 'part';      else carton_show_title = 'parts';       break;
		  case 'efforts':      view = 'transfers';   section = 'boqx_contents';   snippet = 'single_effort'; if(filled_boqxes==1) carton_show_title = 'effort';    else carton_show_title = 'efforts';     break;
		  case 'item' :        view = 'market';                                                              if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'receipt_item': view = 'transfers';   section = 'boqx_contents';   snippet = 'single_item';   if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'receipts':     view = 'transfers';   section = 'thing_receipt';   snippet = 'receipt';       if(filled_boqxes==1) carton_show_title = 'receipt';   else carton_show_title = 'receipts';    break;
	   }
        $(carton).attr('boqxes',filled_boqxes);
        $(carton_envelope).attr('boqx-fill-level', filled_boqxes);
	    $(show_panel).find('.TaskShow__qty_7lVp5tl4').html(service_qty);
        $(show_panel).find('.TaskShow__title___O4DM7q').html(service_name);
        $(show_panel).find('.TaskShow__description___qpuz67f').html(service_desc);
        $(show_panel).find('.TaskShow__item_total__Dgd1dOSZ').html(service_total);
        $(show_panel).find('.TaskShow__time___cvHQ72kV').html(service_time);
		
		$(carton_show_panel).find('.TaskShow__qty_7lVp5tl4').html(filled_boqxes);
		$(carton_show_panel).find('.TaskShow__title___O4DM7q').html(carton_show_title);
		$(carton_show_panel).find('.TaskShow__item_total__Dgd1dOSZ').html(moneyFormat(carton_total));
		$(carton_show_panel).find('.TaskShow__time___cvHQ72kV').html(service_time);
		
        if (state == 'add' || state == 'edit'){
              if (show_service){
                 $(show_panel).show();
                 $(edit_panel).hide();
                 $(envelope).attr('data-aid', 'show');
             }
             else {
                 $(add_panel).show();
                 $(edit_panel).hide();
                 $(envelope).attr('data-aid', 'add');
             }
             if(points > 0){
	    	     $this.attr('data-aid', 'saveEffortButton');
	    	     $this.html('Close').removeClass('add').addClass('close');
	    	     $('nav .controls .cancelReplace__TFF3oQiV[data-cid='+cid+']').remove();
	    	     $('nav .controls .cancelReplace__mw0ODp0p[data-cid='+cid+']').remove();
	    	     $('nav .controls .closeEnvelope_1kZzzgcR[data-cid='+cid+']').remove();
	    	     $this.closest('nav').attr('class','edit');
	    	     var del_title;
	    	     if ($('.delete[data-cid='+cid+']').length > 0){
	    	    	 $('.delete[data-cid='+cid+']').removeAttr('disabled');
	    	    	 del_title = $('.delete[data-cid='+cid+']').attr('title');
	    	    	 $('.delete[data-cid='+cid+']').attr('title',del_title.replace('(disabled)',''));
	    	     }
             }
		   // add an empty card if needed
           if (empty_boqxes == 0){                                                                                   console.log('view: '+view);console.log('section: '+section);console.log('snippet: '+snippet);console.log('boqx_id: '+boqx_id);console.log('carton_aspect: '+carton_aspect);console.log('carton_id: '+carton_id);
	           ajax.view('partials/jot_form_elements',{
		    	   data: {
		    		   element: 'conveyor',
		    		   view         : view,
		    		   action       : action,
		    		   perspective  : perspective,
		    		   section      : section,
		    		   snippet      : snippet,
					   form_method  : 'stuff',
		    		   parent_cid   : boqx_id,
		    		   carton_id    : carton_id,
		    		   guid         : guid,
		    		   aspect       : aspect,
		    		   presentation : presentation,
		    		   presence     : presence,
		    		   boqxes       : boqxes,
		    	   },
		       }).done(function(output) {
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
    $(document).on("click", ".packBoqx_QQFvJSIR", function(e) {
        e.preventDefault();
        var $this             = $(this),
            ajax              = new Ajax(),
            cid               = $(this).data('cid'),
            show_service      = true;
	    var boqx              = $('#'+cid),
            parent_cid        = $('#'+cid).data('boqx');
    	var carton_id         = $(boqx).data('carton'),
		    guid              = $(boqx).data('guid'),
		    aspect            = $(boqx).data('aspect'),
		    presence          = $(boqx).data('presence'),
		    presentation      = $(boqx).data('presentation'),
		    perspective       = 'add',
		    action            = 'add',
//            state        = $(this).attr('data-aid').replace('EffortButton',''),
            state             = $(boqx).data('aid');
		var carton            = $('#'+carton_id);
		var carton_aspect     = $(carton).data('aspect'),
		    boqxes            = 0,
		    filled_boqxes     = 0,
			empty_boqxes      = 0,
			handler,
			snippet,
            section,
            points            = 0;
		var service_name      = $("[data-focus-id=NameEdit--"+cid+"]").val(),
		    service_desc      = $("[data-focus-id=ServiceEdit--"+cid+"]").val(),
		    service_qty       = $("#"+cid+"_line_qty").val(),
		    service_cost      = $("#"+cid+"_line_cost").val(),
		    service_time      = $("#"+cid+"_hours").val(),
		    service_total     = $('#'+cid+"_line_total").html(),
		    service_total_raw = $('.'+cid+"_line_total_raw").html(),
		    carton_total      = 0,
			carton_show_title;
		var add_panel         = $('[data-aid=TaskAdd][data-cid='+cid+']'),
			show_panel        = $('[data-aid=TaskShow][data-cid='+cid+']'),
			edit_panel        = $('[data-aid=TaskEdit][data-cid='+cid+']');
		if (edit_panel.length == 0)
			edit_panel        = $('[data-aid=ItemEdit][data-cid='+cid+']');                   		console.log('cid: '+cid);console.log('carton_id: '+carton_id);console.log('carton_aspect: '+carton_aspect);console.log('state: '+state);console.log('presence: '+presence);console.log('presentation: '+presentation);

		if (typeof service_name  != 'undefined' && service_name.length  > 0)                                    points++; console.log('service_name: '+service_name);
		if (typeof service_desc  != 'undefined' && service_desc.length  > 0)                                    points++; console.log('service_desc: '+service_desc);
		if (typeof service_qty   != 'undefined' && service_qty.length   > 0)                                    points++; console.log('service_qty: '+service_qty);
		if (typeof service_cost  != 'undefined' && service_cost.length  > 0)                                    points++; console.log('service_cost: '+service_cost);
		if (typeof service_time  != 'undefined' && service_time.length  > 0)                                    points++; console.log('service_time: '+service_time);
		if (typeof service_total != 'undefined' && service_total.length > 0 && parseFloat(service_total_raw)>0) points++; console.log('service_total: '+service_total);
        
        if (typeof service_name == 'undefined')                  show_service = false;
        else if (service_name.length==0)         if (points > 0) service_name = '(missing name)';
                                                 else         	 show_service = false;
    	$('#'+cid).attr('boqx-fill-level',points);                                                  console.log('points: '+points);
        $('[data-carton='+carton_id+']').each(function(){
			boqxes++;
			if ($(this).attr('boqx-fill-level') == 0){                                              console.log('boqx: '+$(this).attr('id'));
				empty_boqxes++;}
			if ($(this).attr('boqx-fill-level')>0)
				filled_boqxes++;
		});	                                                                                        console.log('empty_boqxes: '+empty_boqxes);console.log('filled_boqxes: '+filled_boqxes);console.log('state: '+state);
		switch (carton_aspect){
		  case 'discoveries':  handler = 'experiences'; section = 'issue_discovery'; snippet = 'discovery';     if(filled_boqxes==1) carton_show_title = 'discovery'; else carton_show_title = 'discoveries'; break;
		  case 'remedies':     handler = 'experiences'; section = 'issue_remedy';    snippet = 'remedy';        if(filled_boqxes==1) carton_show_title = 'remedy';    else carton_show_title = 'remedies';    break;
		  case 'issues':       handler = 'experiences'; section = 'issue';                                      if(filled_boqxes==1) carton_show_title = 'issue';     else carton_show_title = 'issues';      break;
		  case 'parts':        handler = 'transfers';   section = 'boqx_contents';   snippet = 'single_part';   if(filled_boqxes==1) carton_show_title = 'part';      else carton_show_title = 'parts';       break;
		  case 'efforts':      handler = 'transfers';   section = 'boqx_contents';   snippet = 'single_effort'; if(filled_boqxes==1) carton_show_title = 'effort';    else carton_show_title = 'efforts';     break;
		  case 'item' :        handler = 'market';      section = 'single_thing';    perspective = 'edit';      if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'receipt_item': handler = 'transfers';   section = 'boqx_contents';   snippet = 'single_item';   if(filled_boqxes==1) carton_show_title = 'item';      else carton_show_title = 'items';       break;
		  case 'receipts':     handler = 'transfers';   section = 'thing_receipt';   snippet = 'receipt';       if(filled_boqxes==1) carton_show_title = 'receipt';   else carton_show_title = 'receipts';    break;
	   }
        $(show_panel).find('.ItemShow__title__8tlRYJcP').html(service_name);
        $(show_panel).find('.ItemShow__item_total__Dgd1dOSZ').html(service_total);

		if (state == 'add' || state == 'edit'){
			  if (show_service){
				 $(show_panel).show();
				 $(edit_panel).hide();
			 }
			 else {
				 $(add_panel).show();
				 $(edit_panel).hide();
			 }
		   // add an empty card if needed
		   if (empty_boqxes == 0){
				 $this.attr('data-aid', 'saveEffortButton');
				 $this.html('Close').removeClass('add').addClass('close');
				 $('nav .controls .cancelReplace__mw0ODp0p[data-cid='+cid+']').remove();
				 $this.closest('nav').attr('class','edit');
				 var del_title;
				 if ($('.delete[data-cid='+cid+']').length > 0){
					 $('.delete[data-cid='+cid+']').removeAttr('disabled');
					 del_title = $('.delete[data-cid='+cid+']').attr('title');
					 $('.delete[data-cid='+cid+']').attr('title',del_title.replace('(disabled)',''));
				 }                                                                                     console.log('handler: '+handler);console.log('section: '+section);console.log('snippet: '+snippet);console.log('parent_cid: '+parent_cid);console.log('carton_aspect: '+carton_aspect);console.log('carton_id: '+carton_id);
			   ajax.view('partials/jot_form_elements',{
				   data: {
					   element      : 'conveyor',
					   view         : handler,
					   action       : action,
					   perspective  : perspective,
					   section      : section,
					   snippet      : snippet,
					   form_method  : 'pack',
					   parent_cid   : parent_cid,
					   carton_id    : carton_id,
					   guid         : guid,
					   aspect       : aspect,
					   presentation : presentation,
					   presence     : presence,
					   boqxes       : boqxes,
				   },
			   }).done(function(output) {                          console.log('carton: ',carton);
				   $(carton).append($(output));
			   }).success(function(){
					
			   });
		   }
		}
		else{                                        // state == 'view'
			$(show_panel).show();
			$(edit_panel).hide();
		}			
       /****************************************
* packBoqx_QQFvJSIR . boqx is envelope *****************************************************************
       ****************************************/
	    if ($(boqx).hasClass('envelope__NkIZUrK4')){
			var envelope        = boqx,
		    	carton_envelope_id = $(carton).data('envelope');
			var carton_envelope = $('#'+carton_envelope_id),
			    envelope_cid,
			    envelope_total;
			var edit_panel          = $('[data-aid=TaskEdit][data-cid='+cid+']'),
			    carton_add_panel    = $('[data-aid=TaskAdd][data-cid='+carton_envelope_id+']'),
				carton_show_panel   = $('[data-aid=TaskShow][data-cid='+carton_envelope_id+']'),
				carton_edit_panel   = $('[data-aid=TaskEdit][data-cid='+carton_envelope_id+']');        console.log('carton_envelope_id: '+carton_envelope_id);
			$(envelope).attr('boqx-fill-level', points);			
			$('[data-carton='+carton_id+']').each(function(){
				envelope_cid = $(this).attr('id');                                                      console.log('envelope_cid = '+envelope_cid);
				envelope_total = $('#'+envelope_cid+'_line_total_raw').text();                          console.log('envelope_total = '+envelope_total);
				if(!isNaN(envelope_total) && envelope_total.length>0)
				   carton_total += parseFloat(envelope_total);                                          console.log('carton_total =' +carton_total);
			});                                                                                         console.log('empty_boqxes: '+empty_boqxes);console.log('filled_boqxes: '+filled_boqxes);
	        $(carton_envelope).attr('boqx-fill-level', filled_boqxes);

	        $(show_panel).find('.TaskShow__qty_7lVp5tl4').html(service_qty);
	        $(show_panel).find('.TaskShow__title___O4DM7q').html(service_name);
	        $(show_panel).find('.TaskShow__description___qpuz67f').html(service_desc);
	        $(show_panel).find('.TaskShow__item_total__Dgd1dOSZ').html(service_total);
	        $(show_panel).find('.TaskShow__time___cvHQ72kV').html(service_time);

			$(carton_show_panel).find('.TaskShow__qty_7lVp5tl4').html(filled_boqxes);
			$(carton_show_panel).find('.TaskShow__title___O4DM7q').html(carton_show_title);
			$(carton_show_panel).find('.TaskShow__item_total__Dgd1dOSZ').html(moneyFormat(carton_total));
			$(carton_show_panel).find('.TaskShow__time___cvHQ72kV').html(service_time);
			
	        if (state == 'add' || state == 'edit'){
	              if (show_service){
	                 $(envelope).attr('data-aid', 'show');
	             }
	             else {
	                 $(envelope).attr('data-aid', 'add');
	             }
	        }
	        else{                                        // state == 'view'
	            $(envelope).attr('boqx-fill-level', '0');
	            $(envelope).attr('data-aid', 'show');
	        }
	    }
    });
    $(document).on('click', '.dropZone__uNpSdLP4', function(e){
/* used in:
 	* forms/market/edit  > edit  > single_thing
 */
	   e.preventDefault(); 
	  var ajax         = new Ajax(),
	      $this        = $(this),
	      cid          = $(this).data('cid'),
	      boqx_id      = $(this).data('parent-cid'),                          // where presentation == 'pallet'
	      form_id      = $(this).attr('form'),
	      guid         = $(this).data('guid'),
	      aspect       = $(this).data('aspect');
	  var envelope     = $('#'+cid),
	      boqx         = $('#'+boqx_id),                                     
	      form         = $('#'+form_id),
		  carton_id    = $('#'+cid).data('carton');                          
	  var carton       = $('#'+carton_id),
	      media_boqx   = $('.mediaBoqx_fnBMgIOE[data-carton='+carton_id+']')
	      processed    = [];
	  var contents     = $(carton).data('aspect');                         console.log('contents: '+contents);console.log('boqx_id: '+boqx_id);console.log('carton_id: '+carton_id);
      if (typeof form != 'undefined'){
		  var formData = $(form).serialize(),
		      action   = $(form).attr('action'),
			  method   = $(form).attr('method');                             console.log('action: '+action);console.log('method: '+method);
//		  return;
	  	  $.ajax({                                                //process the form data
			    method: method,                                   //method used to process the form data
			    url: action,                                      //url provided as the form action
			    data: formData                                    //data provided as the form data
			}).done(function(json) {                              //receive the data returned by the action
				$.each(json.output, function(index,value){        //process the data returned by the action
					processed.push(value);                 console.log('processed['+index+']='+value);                        //push each value onto the end of the array 'processed'
				});
				ajax.view('partials/jot_form_elements',{          //send the processed data to the view 'jot_form_elements'
		    	   data: {
		    		   element    : 'show images',                   //send the element 'show images' to identify the element needed for the switch
		    		   guids      : processed,                       //send the processed data as 'guids'
		    		   handler    : contents,                         //send the carton contents tag as 'handler'
		    		   boqx_id    : boqx_id,
		    		   carton_id  : carton_id
		    	   },
				}).done(function(output){                         //receive the output from the view
					$(media_boqx).append($(output));              //append the output from the view to the end of the media_boqx
				});
			}).success(function(result) {
			}).fail(function() {
				alert('failed');
			});
          $(form).remove();
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
   $(document).on('click', '.action-item', function(e){
	  e.preventDefault();
	  var ajax         = new Ajax(),
//	      boqx_id      = $(this).data('boqx'),
	      cid          = $(this).data('cid'),
	      action       = $(this).data('aid'),
	      action_item  = $(this);
	  var item         = $('#'+cid),
	      item_guid    = $('#'+cid).data('guid'),
	      boqx_id      = $('#'+cid).data('cid'),
	      carton_id    = $('#'+cid).data('carton');
	  var boqx         = $('#'+boqx_id),               //$('.boqx#'+boqx_id),
	      guid         = $('#'+boqx_id).data('guid');  //$('.boqx#'+boqx_id).data('guid');                             	  console.log('carton_id: '+carton_id);console.log('boqx_id: '+boqx_id);console.log('item_guid: '+item_guid);console.log('guid: '+guid);
	  var carton       = $('#'+carton_id),
		  media_envelope = $('.envelope__NkIZUrK4[data-carton='+carton_id+']');
	  var media_cache  = $('.ItemMedia__Nyaa0xmV.cache[data-cid='+boqx_id+']');
	  var pieces       = $('.media-item[data-carton='+carton_id+']').length,
		  show_label   = $(media_envelope).find('.TaskShow__title___O4DM7q').text();
	  
	  switch(action){
	  	case 'set_default':
	  		var selected = $(item).hasClass('selected'),
	  		    meta     = $('.boqx#'+boqx_id+' .meta'),
	  		    icon     = $('.boqx#'+boqx_id+' .itemPreviewImage_ARIZlwto'),
	  		    size     = 'tiny';
	  			
	  		if(!selected){
	  			$('#'+carton_id+' .media-item').removeClass('selected');
	  			ajax.action("jot/action", {
		           data: {guid_one : item_guid,
		                  guid_two : guid,
		                  action   : action
		           },
	  			}).done(function(json){
	  				$(item).addClass('selected');
	  				if(icon.length == 0){                                           // icon does not exist
			  			ajax.view('partials/jot_form_elements',{                    // create single image
				    	   data: {
				    		 element: 'show thumbnail image',
				    		 guid: item_guid,
				    		 size: size
				    	   },
				       }).done(function(output) {             console.log('output: '+output);
				    	   $(meta).append($(output));
					   });   
			  		}
			  		else {                                                         // icon exists
			  			$(icon).attr('guid',item_guid).attr('src',"http://qboqx.smarternetwork.com/gallery/icon/"+item_guid+"/"+size);    //replace icon
			  		}
		        });
	  		}
	  		break;
	  	case 'detach':
	  		ajax.action("jot/action", {
	           data: {guid_one : item_guid,
	                  guid_two : guid,
	                  action   : action
	           },
	  		}).done(function(output, statusText, jqXHR){
	              $(item).remove();
 				  pieces     = $('.media-item[data-carton='+carton_id+']').length;
				  if(pieces == 1) show_label = 'piece'; else show_label = 'pieces';
				  $(media_envelope).attr('boqx-fill-level',pieces);
				  $(media_envelope).find('.TaskShow__qty_7lVp5tl4').text(pieces);
				  $(media_envelope).find('.TaskShow__title___O4DM7q').text(show_label);
				  $(media_cache).find('input[data-guid='+item_guid+']').remove();
	        });
	  		break;
	  }
	  
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
   $(document).on('click', '.pallet .elgg-item-object-hjalbum a, .pallet .gallery-photostream a', function(e){  //interrupt all link behaviors related to the Gallery and Photostream pallets
	  e.preventDefault();
	  var href = $(this).attr('href');                     console.log('href = '+href);
	  var link_segments = href.split("/");
	  var segments = link_segments.length;
	  if($(this).hasClass('gallery-album-icon') || $(this).parent().hasClass('elgg-listing-summary-title')){          console.log('album = '+link_segments[segments-1]);console.log('album_guid = '+link_segments[segments-2]);
	  }
	  else {                                                console.log('album_guid = '+link_segments[segments-1]);console.log('action = '+link_segments[segments-2]);
	  }
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
      e.preventDefault();
	  var ajax        = new Ajax();
	  var guid        = $(this).data('guid'),
	      cid         = $(this).data('cid'),
          presentation= $(this).data('presentation'),
          presence    = $(this).data('presence'),
          slot        = $(this).closest('.slot');
//@EDIT - 2020-03-20 - SAJ - Replacing pallet behaviors with slot behaviors
//	  var pallet      = $(this).closest('.pallet');
      var item_boqx   = $(this).closest('#'+cid);
      var boqx_id     = $(item_boqx).data('boqx'),
          isDraggable = $(item_boqx).is(".ui-draggable");
      var boqx        = $(this).closest('#'+boqx_id);
	  var $boqx_show  = $(item_boqx).children('.Item__nhjb4ONn[data-boqx='+cid+']'),
          $boqx_preview = $(this).closest('header.preview[data-cid='+cid+']');
      var $boqx_exists = $boqx_show.length>0;
      if($(item_boqx).hasClass('clone'))                                                  //bail if item_boqx is a clone
    	  return false;
      if($(this).parents('.open-boqx').length>0)
    	  presence    = 'open_boqx';
      if(presence=='open_boqx' && !slot.hasClass('maximized')){
    	  slot.addClass('maximized').attr('open-boqx',cid);
    	  item_boqx.find('button.maximize').removeClass('maxmize').addClass('restore').attr('title', 'Restore view');  
      }
      if($(this).parents('boqx-carton').length>0)
    	  presence = 'carton';
      if(isDraggable)
    	  item_boqx.draggable("disable");
//      $('.boqx .preview').draggable('enable').draggable('option',{cursor:'move'});
      if($boqx_exists){
    	  $boqx_show.removeClass('collapsed');
    	  $boqx_preview.addClass('collapsed');
      }
      else {
    	  ajax.view('partials/jot_form_elements',{
			  data: {
					  element      : 'show boqx',
					  guid         : guid,
					  cid          : cid,
					  presentation : presentation,
// when presence == 'carton', cancel/close buttons are not available
					  presence  : presence
				  },
			  }).done(function(output){
				  $(item_boqx).append($(output));
				  
				  var media        = $('.boqx.file, .boqx.media'),
			          item         = $('.boqx.item'),
				      media_drop   = $('.media_drop'),
				      item_drop    = $('.item_drop');
				  
				  $('.dropboqx.things').droppable({
				    	greedy: true,
				        accept: '.boqx',
				    	tolerance: "touch",
				        scope: 'things',
					    classes:{
				           'ui-droppable-active': "box-state-highlight"} 
					});
				  media_drop.droppable({
					  	accept: media,
					    tolerance: "touch",
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
				  item_drop.droppable({
					    accept: item,
					    tolerance: "touch",
					    greedy:true,
					    scope: 'things',
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
/*   $(document).on('drop', '.dropboqx', function(e, ui) {
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
*/     
    $(document).on('drop', '.dropboqx',function(event,ui){
        console.log('+framework.dropboqx.on(drop)');
        var ajax      = new Ajax(),
            boqx      = $(this),
            cid       = $(this).data('cid'),
            opening   = $(this).closest('.window-opening'),
            liner     = $(this).closest('.liner'),
            dropped_item = ui.helper,
            boqx_guid = $(this).parents('.boqx').data('guid'),
            dropboqx  = this;
        var item_guid = dropped_item.data("guid"),
            item_id   = dropped_item.attr('id'),
            boqx_id   = $('#'+cid).data('boqx'),
            aspect    = boqx.data('aspect'),
            section   = boqx.data('section');
        var envelope_id = $(liner).data('cid');
        var envelope  = $('#'+envelope_id),
            carton_id = $('#'+envelope_id).data('carton');
        var carton    = $('#'+carton_id);
        var tally     = parseInt($(carton).children('.tally').attr('boqxes'));
        var new_id    = "c"+Math.floor((Math.random()*200)+1);
        var item      = $('#'+item_id).clone();
        var isConnected = liner.children('.boqx[data-guid='+item_guid+']').length > 0;
        var presence  = $(envelope).data('presence');
        if (boqx_guid)                                    //there is a boqx (that's not empty).  pack the dropped thing into it.
	        elgg.action("shelf/pack", {
	           data: {guid     : item_guid,
	                  boqx_guid: boqx_guid,
	                  aspect   : aspect,
	                  section  : section
	           		},
           success: function(e){
              if(!isConnected){
            	  ajax.view('forms/experiences/edit',{              //build the item boqx 
            		  data: {
            			  guid        : item_guid,
            			  parent_cid  : envelope_id,
            			  boqx_id     : boqx_id,
            			  action      : 'add',
            			  section     : 'thing',
            			  presentation: 'carton',
            			  presence    : 'pallet'
            		  }
            	  }).done(function(output) {
            		 $(opening).after($(output));                 //set the item boqx into the window-opening 
	                 $('.boqx.item').draggable({                 //make the item boqx draggable
	                    scope: 'things',
	                 });
	                 tally = $(dropboqx).closest('.liner').children('.boqx').length;              //increment the tally
	                 $(carton).find('.tally').attr('boqxes', tally);
	                 $(envelope).find('.TaskShow__qty_7lVp5tl4').text(tally);
            	  });
               }
           }
	    });
        else 
        	ajax.view('forms/experiences/edit',{              //build the item boqx 
    		  data: {
    			  guid        : item_guid,
    			  parent_cid  : envelope_id,
            	  boqx_id     : boqx_id,
    			  action      : 'add',
    			  section     : 'thing',
    			  presentation: 'nested',
    			  presence    : 'pallet'
    		  }
    	  }).done(function(output) {
    		 $(opening).after($(output));                //set the item boqx into the window-opening 
             $('.boqx.item').draggable({                 //make the item boqx draggable
                scope: 'things',
             });
             tally = $(dropboqx).closest('.liner').children('.boqx').length;              //increment the tally
             $(carton).find('.tally').attr('boqxes', tally);
             $(envelope).find('.TaskShow__qty_7lVp5tl4').text(tally);
             $(dropped_item).remove();
    	  });
//        quebx.shelf_tools.pack_item(item_guid, boqx_guid, boqx, item, aspect, container_guid);
    });    
	$(function() {
	  var ajax         = new Ajax(),
          media        = $('.boqx.file, .boqx.media'),
          item         = $('.boqx.item'),
	      media_drop   = $('.media_drop'),
	      item_drop    = $('.item_drop');

	  media_drop.droppable({
	    accept: media,
	    tolerance: "touch",
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
	  item_drop.droppable({
		    accept: item,
		    tolerance: "touch",
		    greedy:true,
		    scope: 'things',
		    over: function(event, ui) {
		      $(this).addClass('ui-droppable-hover');
		    },
		    out: function() {
		      $(this).removeClass('ui-droppable-hover');
		    },
		    drop: function(event, ui) {
		      
		      $(this).removeClass('ui-droppable-hover');
		    }
	  });
	  $(document).on('dropover', '.media_drop', function(e, ui){
	      $(media_drop).addClass('ready-to-receive');
	  });
	  $(document).on('dropout', '.media_drop', function(e, ui){
	      $(media_drop).removeClass('ready-to-receive');
	  });
	  $(document).on('drop', '.media_drop', function(e,ui){
	      var media      = ui.draggable,                                //the dragged boqx
	          boqx_id    = $(this).data('boqx'),                        //the cid of the drop boqx
	          cid        = $(this).data('cid'),
	          carton_id  = $(this).data('carton'),
	          presence   = $(this).data('presence');
	      var boqx       = $('#'+boqx_id);
		  var boqx_guid  = $(boqx).data('guid');                        //the guid of the receiving object
	      var guid       = $(media).children('a.gallery-popup').data('guid'),
	          hidden_fields;       
//	      var carton_id  = $(this).closest('.boqx-carton').attr('id');
		  var carton     = $('#'+carton_id),
		      media_boqx = $('.mediaBoqx_fnBMgIOE[data-carton="'+carton_id+'"]'),
		      media_envelope = $('.envelope__NkIZUrK4[data-carton="'+carton_id+'"]'),
		      media_cache= $('.ItemMedia__Nyaa0xmV.cache[data-cid='+cid+']');
		  var contents   = $(carton).data('aspect'),
		      pieces     = $('.media-item[data-carton="'+carton_id+'"]').length,
		      show_label = $(media_envelope).find('.TaskShow__title___O4DM7q').text();                               console.log('guid: '+guid);console.log('boqx_guid: '+boqx_guid);console.log('boqx_id: '+boqx_id);console.log('carton_id: '+carton_id);

	      if (media.hasClass('media')){
	    	  ajax.action('shelf/pack',{
			      data: {boqx_guid: boqx_guid,
			      	     guid     : guid,
			      	     cid      : cid,
			    	     aspect   : 'media',
			    	     action   : 'add',
			    	     presence : presence
			    	    }	    		  
	    	  }).done(function(content, message, forward_url, status_code){
		    		if(typeof content != 'undefined' && typeof content != 'string'){  
	    		      guid = $.parseJSON(content['guid']);                                                          console.log('guid: '+guid);console.log('guid returned: '+guid);
	    		      hidden_fields = $.parseJSON(content['hidden_fields']); console.log('hidden_fields: '+hidden_fields);
//	    		      guid = $.parseJSON(content);                   console.log('guid: '+guid);
		    		  ajax.view('partials/jot_form_elements',{          //send the processed data to the view 'jot_form_elements'
				         data: {element    : 'show single image',       //identify the element needed for the switch
				        	 	guid       : guid,                      //the guid
				        	 	cid        : cid,
				        	 	boqx_id    : boqx_id,
				        	 	carton_id  : carton_id,
				        	 	presence   : presence,
				        	 	size       : 'medium'
				    	      	},
						}).done(function(output){
							$(media_boqx).append($(output));            //append the output from the view to the end of the media_boqx
							pieces     = $('.media-item[data-carton="'+carton_id+'"]').length;
							if(pieces == 1) show_label = 'piece'; else show_label = 'pieces';
							$(media_envelope).attr('boqx-fill-level',pieces);
							$(media_envelope).find('.TaskShow__qty_7lVp5tl4').text(pieces);
							$(media_envelope).find('.TaskShow__title___O4DM7q').text(show_label);
							$(media_cache).append(hidden_fields);
						});
		    		}
	    	  });
	      }
		  if (media.hasClass('file')){
	      }
	  });
	  $(document).on('drop', '.item_drop', function(e,ui){
		  e.stopPropagation();                                        //prevent the event from propagating to ancestor boqxes
	      var item      = ui.draggable,                               //the dropped item
	          boqx_id    = $(this).data('boqx'),                      //the cid of the receiving boqx
	          cid        = $(this).attr('id'),
	          carton_id  = $(this).data('carton'),
	          presence   = $(this).data('presence');
	      var boqx       = $('#'+boqx_id);
		  var boqx_guid  = $(boqx).data('guid'),                      //the guid of the receiving object
	          guid       = $(item).data('guid'),                      //the guid of the dragged item
	          hidden_fields;       
//	      var carton_id  = $(this).closest('.boqx-carton').attr('id');
		  var carton        = $('#'+carton_id),
		      item_boqx     = $('.contentsAdd_P1C3VSjT[data-carton="'+carton_id+'"]'),
		      item_envelope = $('.envelope__NkIZUrK4[data-carton="'+carton_id+'"]'),
		      item_cache    = $('.ItemContents__aXLIZva0.cache[data-cid='+boqx_id+']'),
		      pieces        = $('.boqx.contents[data-boqx="'+boqx_id+'"]').length;
		  var contents      = $(carton).data('aspect'),
		      action        = $(item_envelope).attr('data-aid'),
		      show_label    = $(item_envelope).find('.TaskShow__title___O4DM7q').text();                               console.log('guid: '+guid);console.log('boqx_guid: '+boqx_guid);console.log('boqx_id: '+boqx_id);console.log('carton_id: '+carton_id);
	      if (item.hasClass('item')){
	    	  ajax.action('shelf/pack',{
			      data: {boqx_guid: boqx_guid,
			      	     guid     : guid,
			      	     cid      : cid,
			    	     aspect   : 'contents',
			    	     action   : 'pack',
			    	     presence : presence
			    	    }	    		  
	    	  }).done(function(content, message, forward_url, status_code){
		    		if(typeof content != 'undefined' && typeof content != 'string'){  
	    		      guid = $.parseJSON(content['guid']);                   console.log('guid: '+guid);
	    		      hidden_fields = $.parseJSON(content['hidden_fields']); console.log('hidden_fields: '+hidden_fields);
//	    		      guid = $.parseJSON(content);                   console.log('guid: '+guid);
		    		  ajax.view('partials/jot_form_elements',{          //send the processed data to the view 'jot_form_elements'
				         data: {element       : 'conveyor',
				        	 	view          : 'market',
                                section       : 'contents_single_piece',
								guid          : guid,
								parent_cid    : cid,
                                display_class : 'collapsed'
				    	      	},
						}).done(function(output){
							$(output).find('.item_drop').droppable({
									accept: '.boqx.item',
									tolerance: "touch",
									greedy:true,
									scope: 'things',
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
							$(item_boqx).after($(output));            //append the output from the view to the end of the item_boqx
							pieces     = $('.boqx.contents[data-boqx="'+cid+'"]').length;
							if(pieces == 1) show_label = 'piece'; else show_label = 'pieces';
							$(item_envelope).attr('boqx-fill-level',pieces);
							$(item_envelope).find('.TaskShow__qty_7lVp5tl4').text(pieces);
							$(item_envelope).find('.TaskShow__title___O4DM7q').text(show_label);
							$(carton).children('.tally').attr('boqxes',pieces);
							$(item_cache).append(hidden_fields);
							if(action == 'add' && pieces>0){
								$(item_envelope).children('.AddSubresourceButton___2PetQjcb').hide();
								$(item_envelope).children('.TaskShow___2LNLUMGe').show();
								$(item_envelope).attr('data-aid','show');
							}
						});
		    		}
	    	  });
	      }
	  });
	});
    $(document).on('click', '.panels .items .pallet_toggle', function(e){
          e.preventDefault();
          var ajax    = new Ajax(),
              cid     = $(this).data('cid'),
              boqx    = $(this).data('boqx'),
              handler = $(this).parent().attr('handler'),
              visible = $(this).parent().hasClass('visible'),
              slots,
              this_toggle = $(this),
              materialized,
              available = false;
          var min_size = 1900,
              required_size,
              floor_size;
          var slot,
              pallet;
          var last_slot = $('.slots').children('.slot').size();
//@EDIT - 2020-03-20 - SAJ - Replacing pallet behaviors with slot behaviors
//          var last_slot = $('.slots').children('.pallet').size();
          if (boqx == 'shelf') pallet = $('.tc_page_bulk_header');
          else                 pallet = $('#'+cid);
          materialized = pallet.length>0;
          if (visible && materialized){
        	  slot = pallet.parent();
              slot.removeClass('visible');
              $(this).parent().removeClass('visible');
              if (boqx == 'shelf') {
            	  $('.tc_page_nav_header').addClass('visible');
            	  $('.tc_pull_right').addClass('visible');
              }
	    	  slots         = $('.slots').children('.slot').size();
//@EDIT - 2020-03-20 - SAJ - Replacing pallet behaviors with slot behaviors
//	    	  slots         = $('.slots').children('.pallet').size();
	          required_size = (slots*400) + 100;
	          floor_size    = required_size < min_size ? min_size : required_size;
			  $('.slots').attr('data-slots', slots);
			  $('.slots').css('width',floor_size);
			  return;
          }
          if (!visible && materialized) {
        	  slot = pallet.parent();
              slot.addClass('visible');
              $(this).parent().addClass('visible');
              if (boqx == 'shelf') {
            	  $('.tc_page_nav_header').removeClass('visible');
            	  $('.tc_pull_right').removeClass('visible');
              }
	    	  slots         = $('.slots').children('.slot').size();
//@EDIT - 2020-03-20 - SAJ - Replacing pallet behaviors with slot behaviors
//	    	  slots         = $('.slots').children('.pallet').size();
	          required_size = (slots*400) + 100;
	          floor_size    = required_size < min_size ? min_size : required_size;
			  $('.slots').attr('data-slots', slots);
			  $('.slots').css('width',floor_size);
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
                 this_toggle.parent().addClass('visible');
		         $('.slots').append($(output)).focus();
	      	     slots         = $('.slots').children('.slot').size();
//@EDIT - 2020-03-20 - SAJ - Replacing pallet behaviors with slot behaviors
//		    	 slots         = $('.slots').children('.pallet').size();
		         required_size = (slots*400) + 100;
		         floor_size    = required_size < min_size ? min_size : required_size;
				 $('.slots').attr('data-slots', slots);
				 $('.slots').css('width',floor_size);
				 $('.slot').droppable({                                                                          //make the new slot droppable
				    accept: ".pallet",
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
				 $(".pallet").draggable({                                                                        //@EDIT - 2020-05-18 - SAJ - Make the new pallet draggable
				    containment: slots,
				    helper: "clone",
				    handle: '.tn-PanelHeader__inner___3Nt0t86w',
				    axis: "x",
				    stack: ".pallet",
				    zIndex: 100,
	                scroll: true,
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
       var ajax     = new Ajax(),
		   slots    = $('.slots'),
		   slots_rev= $('.slot').get().reverse(),
    	   slot     = $(this).parents('.slot'),
		   slot_id  = $(this).attr('id'),
		   slot_no  = $(this).parents('.slot').data('slot'),
	       pallet   = $(this).parents('.pallet'),
		   pallet_guid = $(this).parents('.pallet').data('guid');                    console.log('pallet_guid: '+pallet_guid);
	   var contents = $(pallet).data('contents'),
	       cloned   = $(pallet).hasClass('cloned'),
	       is_clone = $(pallet).hasClass('clone'),
		   clone,
		   this_slot,
		   clone_slot_no,
		   new_slot_no;
       var cid      = slot.attr('id');
       var last_slot = $(slots).children('.slot').size(),
           handler  = slot.data('contents'),
		   cloned_pallet = $('.pallet.cloned[data-contents='+contents+']');
       var min_size = 1900,
           required_size,
		   floor_size;
	   
	   if(cloned || is_clone){                                                       //treat cloned pallets differently
		   clone         = $('.pallet.clone[data-contents='+contents+']');           //identify the pallet clone
		   clone_slot_no = $(clone).parent('.slot').data('slot');                    //identify the slot occupied by the pallet clone
		   $(cloned_pallet).find('.palletControl_VHr65Izd[data-aid=ClonePallet]').removeClass('disabled');
		   $(cloned_pallet).removeClass('cloned');
		   $(clone).parent('.slot').remove();                                        //remove pallet clone slot
		   slots_rev= $('.slot').get().reverse();
		   $(slots_rev).each(function(){                                              //move each rightward pallet slot left
				this_slot   = $(this).attr('data-slot');
				pallet_guid = $(this).children('.pallet').data('guid');
				new_slot_no = parseInt(this_slot)-1;                                 //calculate the new slot number
				if(this_slot > clone_slot_no){
					elgg.action("pallets/move",{                                      //record the new slot of the moved pallet in the database
						data: {guid  : pallet_guid,
							   column: new_slot_no
						}
					});
					$(this).attr('data-slot',new_slot_no);                           //renumber the slot
				}
				else return false;				   
		   });
		   if(cloned){                                                               //determine whether the pallet is a cloned pallet
		       slot_no  = $(this).parents('.slot').attr('data-slot');                //determine the current slot number after removing pallet clone
	           $(slot).remove();                                                     //remove pallet slot
			   slots_rev= $('.slot').get().reverse();
			   $(slots_rev).each(function(){                                          //move each rightward pallet left
					this_slot   = $(this).attr('data-slot'),
					pallet_guid = $(this).children('.pallet').data('guid');
				new_slot_no = parseInt(this_slot)-1;                                 //calculate the new slot number
				if(this_slot > slot_no){
					elgg.action("pallets/move",{                                      //record the new slot of the moved pallet in the database
						data: {guid  : pallet_guid,
							   column: new_slot_no
						}
					});
					$(this).attr('data-slot',new_slot_no);                           //renumber the slot
				}
				else return false;				   
			   });
		   };
		   last_slot  = $('.slots').children('.slot').size();
		   required_size = ((last_slot)*400) + 100
		   floor_size = required_size < min_size ? min_size : required_size;
		   slots.attr('data-slots', last_slot);
		   slots.css('width',floor_size);
	   }
	   
	   if(!cloned && !is_clone)
		   elgg.action("pallets/remove", {
			   data: {guid     : pallet_guid
					},
			   success: function(e){
				   slot.remove();
				   slots_rev= $('.slot').get().reverse();
				   $(slots_rev).each(function(){
						this_slot   = $(this).attr('data-slot');
						pallet_guid = $(this).children('.pallet').attr('data-guid');
						var new_slot_no = parseInt(this_slot)-1;
						if(this_slot > slot_no){
							elgg.action("pallets/move",{
								data: {guid  : pallet_guid,
									   column: new_slot_no
								}
							});
							$(this).attr('data-slot',new_slot_no);
						}
						else return false;				   
					   });
				   last_slot     = $('.slots').children('.slot').size();
				   required_size = ((last_slot)*400) + 100
				   floor_size    = required_size < min_size ? min_size : required_size;
				   slots.attr('data-slots', last_slot);
				   slots.css('width',floor_size);
//				   $('li[cid='+cid+']').removeClass('visible');
			   }
			});
/*       ajax.view('partials/jot_form_elements',{
    	   data: {
    		 element       : 'pallet',
    		 handler       : handler,
    		 perspective   : 'remove'
    	   },
		   }).done(function(){
			   slot.remove();
			   slots.attr('data-slots', last_slot-1);
			   slots.css('width',floor_size);
		       $('li[cid='+cid+']').removeClass('visible');
	       });*/
    });
     $(document).on('click', '.tn-AddButton___hGq7Vqlr', function(e){
    	e.preventDefault();
    	var slot  = $(this).parents('.slot');
//@EDIT - 2020-03-20 - SAJ - Replacing pallet behaviors with slot behaviors
//  	var pallet  = $(this).parents('.pallet');
    	var target  = $(this).data('target-boqx'),
    	    ajax    = new Ajax();
    	var handler = slot.data('contents'),
    	    cid     = slot.data('cid'),
    	    stack   = $(this).parents('.tn-PanelHeader___c0XQCVI7')//pallet.find('.tn-PanelHeader__input__xCdUunkH')//pallet.find('.tn-pallet__stack')
    	    ;
    	var empty_boqx = $('#'+target),
    	    liner = $('[data-boqx='+target+']');
    	var liner_exists = liner.length > 0;
    	//console.log('items_container: ',items_container);
    	if (liner_exists){
    		//empty_boqx.show();
    		liner.addClass('open').removeClass('collapsed');
    		//stack.closest('header.tn-PanelHeader___c0XQCVI7').addClass('open');
    		stack.addClass('open');
    	}
    	else
	    	ajax.view('partials/jot_form_elements',{
	    	   data: {
	    		 element       : 'empty boqx',
	    		 handler       : handler,
	    		 perspective   : 'add',
	    		 presentation  : 'pallet',
	    		 presence      : 'empty boqx',
	    		 empty_boqx_id : target
	    	   },
		       }).done(function(output) {
		    	   //stack.prepend($(output));
		    	   //stack.find('.empty-boqx').show();
		    	   //stack.closest('header.tn-PanelHeader___c0XQCVI7').addClass('open');
		    	   empty_boqx.append($(output));
		    	   $('[data-boqx='+target+']').addClass('open').removeClass('collapsed');
    		       stack.addClass('open');
    		      
				  var media        = $('.boqx.file, .boqx.media'),
			          item         = $('.boqx.item'),
				      media_drop   = $('.media_drop'),
				      item_drop    = $('.item_drop');
				  
				  $('.dropboqx.things').droppable({
				    	greedy: true,
				        accept: '.boqx',
				    	tolerance: "touch",
				        scope: 'things',
					    classes:{
				           'ui-droppable-active': "box-state-highlight"} 
				  });
				  media_drop.droppable({
					  	accept: media,
					    tolerance: "touch",
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
				  item_drop.droppable({
					    accept: item,
					    tolerance: "touch",
					    greedy:true,
					    scope: 'things',
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
		       });
     });
     $(document).on('click','.palletActions_D8x8EuHj', function(e){
    	 $(this).next('.tn-DropdownButton_3N5I43GN').removeClass('collapsed');
     });
     $(document).on('click','.tc_scrim',function(e){
    	 $(	'.tn-DropdownButton_3N5I43GN').addClass('collapsed'); 
     });
     $(document).on('click','.palletControl_VHr65Izd',function(e){
    	 var aid = $(this).data('aid');
    	 switch(aid){
    	 	case 'ClonePallet':
		        var pallet = $(this).parents('.pallet');
    	 		if($(pallet).hasClass('cloned')||$(pallet).hasClass('clone'))                                  //stop if this pallet has been cloned or is a clone
		        	break;
    	 		var slot   = $(this).parents('.slot'),
    	 		    slot_id= $(this).attr('id'),
    	 		    slot_no= parseInt($(this).parents('.slot').data('slot'));
    	 		var slots  = $('.slot').get().reverse(),
    	 		    new_slot = $(slot).clone(),
    	 		    new_slot_id = "c"+Math.floor((Math.random()*99999)+1),
    	 		    this_slot;
		        var min_size = 1900,
		            required_size,
		            floor_size;
		        var pallet,
		            pallet_guid,
		            slot_count;
    	 		$(slots).each(function(){
    	 			pallet_guid = $(this).children('.pallet').data('guid');
    	 			this_slot = parseInt($(this).attr('data-slot'));
    	 			new_slot_no = this_slot + 1;                                 //calculate the new slot number
    	 			if(this_slot > slot_no){
    	 				elgg.action("pallets/move",{                                      //record the new slot of the moved pallet in the database
							data: {guid  : pallet_guid,
								   column: new_slot_no
							}
						});
						$(this).attr('data-slot',new_slot_no);                           //renumber the slot
    	 			}
    	 			else return false;
    	 		});
    	 		slot_count = $('.slots').children('.slot').size();
    	 		required_size = ((parseInt(slot_count)+1)*400) + 100;
	            floor_size    = required_size < min_size ? min_size : required_size;
			    $('.slots').attr('data-slots', parseInt(slot_count)+1);
			    $('.slots').css('width',floor_size);
			    $(slot).children('.pallet').addClass('cloned');                                                  //identify this pallet as having been cloned
			    $(new_slot).children('.pallet').addClass('clone');                                               //identify new pallet as a clone
			    $(new_slot).attr('id',new_slot_id);                                                              //give the new slot a new id.
			    $(new_slot).children('.pallet').attr('data-boqx',new_slot_id);                                   //give the new pallet the new id as its boqx.
			    $(new_slot).find('.empty-boqx').children('.Effort__ATAgsAWL.open').remove()                      //'sterilize' the clone
			    $(new_slot).find('header.tn-PanelHeader___c0XQCVI7').removeClass('open');                        //forceably close the header
			    $(new_slot).find('.boqx').addClass('clone');                                                     //identify each cloned boqx as a clone
    	 		$(new_slot).attr('data-slot',slot_no+1).insertAfter(slot);                                       //give the clone a slot number one greater than this slot. insert the clone after this slot
    	 		$(new_slot).find('.Item__nhjb4ONn').addClass('collapsed');                                       //collapse any open boqx
    	 		$(new_slot).find('header.preview').removeClass('collapsed');                                     //expand all previews
    	 		$(new_slot).droppable({                                                                          //make the new slot droppable
				    accept: ".pallet",
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
    	 		$(".pallet").draggable({                                                                         //make the clone pallet draggable
				    containment: slots,
				    helper: "clone",
				    handle: '.tn-PanelHeader__inner___3Nt0t86w',
				    axis: "x",
				    stack: ".pallet",
				    zIndex: 100,
	                scroll: true,
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
			    $('.boqx').draggable({                                                                           //register all cloned boqxes with the DOM as draggable
			   	    refreshPositions: true,
			        revert:"invalid",
			        snap:true,
			        disabled: false,
			        cursor: "default",
			        cursorAt: { left: 160, top: 28 },
			        zIndex: 1050,
			        appendTo: "#root",
//			        snap:'.dropboqx',
			        snapMode: 'inner',
			        classes: {'ui-draggable':'dragging'},
			        helper: function(event){
			          icon         = $(this).closest('.boqx').clone().addClass('dragging_item');
			          avatar       = icon;
			          return avatar;
			       }
			    });
			    $('.boqx.item').draggable({                                                                       //register all cloned item boqxes with the DOM as draggable
			       scope: 'things',
			    });
    	 		break;
    	 }
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
          $('.boqx[data-guid='+guid+'] .preview .selector').removeClass('selected').removeClass('open').attr('open-state', 'closed');
          selected_count = selected_counter - 1;
          $('.boqx[data-guid='+guid+'] .dropdown .close_item').removeClass('visible');
    	  open_state = 'closed';
    	  $('.boqx[data-guid='+guid+']').removeClass('open');
          $('.boqx[data-guid='+guid+'] .selector').removeClass('open');
          if($('#root').attr('open-boqx') == guid){
        	  $('#root').removeClass('boqx-open').removeAttr('open-boqx').removeAttr('open-state');
        	  $('#root .closed-boqx').removeClass('compressed');
        	  $('#root .open-boqx').remove();
        	  $('#root .main.space').removeClass('compressed');
          }
          if (selected_count <= 0){
               $('.tc_page_bulk_header').removeClass('visible');
               $('.tc_pull_right').hide();
               $('.tc_page_nav_header').show();
          }
	      ajax.view('partials/shelf_form_elements',{
	    	   data: {
	    		 element    : element,
	    		 guid       : guid,
	    		 perspective: perspective
	    	   },
	      }).done(function() {
	    	   $('.commandArea .boqx[data-guid='+guid+']').remove();
	    	   $('.shelf-items-compartment .shelf-viewer#quebx-shelf-item-'+guid).remove();
	      });
       }
       else{
          $(this).addClass('selected');
          if (selected_counter <= 0){
               $('.tc_page_bulk_header').addClass('visible');
               $('.tc_pull_right').show();
               $('.tc_page_nav_header').hide();
          }
          selected_count = selected_counter + 1;
          // add to JSON shelf
	      ajax.view('partials/shelf_form_elements',{
	    	   data: {
	    		 element    : element,
	    		 guid       : guid,
	    		 perspective: perspective
	    	   },
	      }).done(function(output) {
	    	   $('.shelf-items-compartment').append($(output));
	      });
	      // show in sidebar
	      ajax.view('partials/shelf_form_elements',{
	    	   data: {
	    		 element    : 'load_sidebar',
	    		 guid       : guid,
	    		 perspective: 'space_sidebar'
	    	   },
	      }).done(function(output) {
	    	   $('.commandArea .pallet .full-pallet__stack').append($(output));
	      });
      }
      if (selected_count == 0)
    	  selected_count = '';
      if (selected_count == 1)
          $('.selectedStoriesControls__counterLabel').html('item selected');
      else
          $('.selectedStoriesControls__counterLabel').html('items selected');
      $('.selectedStoriesControls__counter').html(selected_count);
      $('.Shelf__toggle___pGbKiuvT .count').html(selected_count);
      $(shelf_selector).attr('count', selected_count);
      $(shelf_selector).find('.counter').html(selected_count);
    });
    $(document).on('click', ".dropdown.open-state li.dropdown_item", function(e){
       e.preventDefault();
       var cid              = $(this).parents('.dropdown').data('cid'),
           shelf_selector   = $('li.shelf');
       var guid             = $('#'+cid).data('guid'),
           element,
           perspective      = 'header',
           pick_state       = $(this).closest('li.dropdown_item').data('value');
       var open_state       = pick_state,
           selected         = $('.selector[data-cid='+cid+']').hasClass('selected'),
           selected_count   = 0,
           selected_counter = parseFloat($('.selectedStoriesControls__counter').text()),
           ajax             = new Ajax();
       console.log('cid = '+cid);
       console.log('pick_state = '+pick_state);
       element = 'open';
/*
.boqx.open.open-contents .preview {border: 2px solid #2dde8e;}
.boqx.open.open-accessories .preview {border: 2px solid #cebd24;}
.boqx.open.open-components .preview {border: 2px solid #9163db;}
*/
       switch (pick_state){
	       case 'close': 
	    	   $('.boqx[data-guid='+guid+'] .dropdown .close_item').removeClass('visible');
	    	   open_state = 'closed';
	    	   $('.boqx[data-guid='+guid+']').removeClass('open').removeAttr('open-state');
	           $('.boqx[data-guid='+guid+'] .selector').removeClass('open');
	           $('#root').removeClass('boqx-open').removeAttr('open-boqx').removeAttr('open-state');
	           $('#root .closed-boqx').removeClass('compressed');
	           $('#root .open-boqx').remove();
	    	   break;
	       default:
		       // add to JSON shelf
		       ajax.view('partials/shelf_form_elements',{
		    	   data: {
		    		 element    : element,
		    		 guid       : guid,
		    		 perspective: perspective,
		    		 open_state : open_state
		    	   },
		       }).done(function(output) {
			       $('.shelf-items-compartment').append($(output));
			   }).success(function() {
		           selected_count = selected_counter + 1;
		           if (selected_count == 0)
			    	   selected_count = '';
			       if (selected_count == 1)
			           $('.selectedStoriesControls__counterLabel').html('item selected');
			       else
			           $('.selectedStoriesControls__counterLabel').html('items selected');
			       $('.selectedStoriesControls__counter').html(selected_count);
			       $('.Shelf__toggle___pGbKiuvT .count').html(selected_count);
			       $('.palletControls__counter').html(selected_count);
			       $(shelf_selector).attr('count', selected_count);
			       $(shelf_selector).find('.counter').html(selected_count);		   
		       });
			   // show in sidebar
			   ajax.view('partials/shelf_form_elements',{
			      data: {
			    	 element    : 'load_sidebar',
			    	 guid       : guid,
			    	 perspective: 'space_sidebar'
			       },
			    }).done(function(output) {
			       $('.commandArea .pallet .full-pallet__stack').append($(output));
			    });
			   // open on shelf
			   ajax.view('partials/shelf_form_elements',{
			      data: {
			    	 element    : 'load_shelf',
			    	 guid       : guid,
		    		 open_state : open_state
			       },
			    }).done(function(output) {
		    	   $('.boqx').removeClass('open').removeAttr('open-state');
		    	   $('.boqx[data-guid='+guid+']').addClass('open').attr('open-state',pick_state);
		    	   $('.boqx[data-guid='+guid+'] .dropdown .close_item').addClass('visible');
		           $('.boqx[data-guid='+guid+'] .selector').addClass('open');
		           $('#root').addClass('boqx-open').attr('open-boqx', guid).attr('open-state', pick_state);
		           $('#root .closed-boqx').addClass('compressed');
			    	$('.boqx[data-guid='+guid+'] .selector').attr('open-state', open_state).addClass('selected');
				    $('.page_header_container section.open-boqx').remove();
			       $('.page_header_container').append($(output));
			    });		       
		  	   break;
		       $('.dropdown[data-cid='+cid+']').removeClass('above_scrim');
		       $('.dropdown[data-cid='+cid+'] section.open-state').addClass('closed');
		  }

       
      
    });
    $(document).on('click',".slot[data-contents='shelf'] .preview .name",function(e){
//@EDIT - 2020-03-20 - SAJ - Replacing pallet behaviors with slot behaviors
//    $(document).on('click',".pallet[data-contents='shelf'] .preview .name",function(e){
	    e.preventDefault();
       var cid              = $(this).data('cid');
       var guid             = $('#'+cid).data('guid'),
           perspective      = 'header';
       var open_state       = 'boqx',
           ajax             = new Ajax();
       console.log('cid = '+cid);
       // add to JSON shelf
       ajax.view('partials/shelf_form_elements',{
    	   data: {
    		 element    : 'open',
    		 guid       : guid,
    		 perspective: perspective,
    		 open_state : open_state
    	   },
       });
	   // open on shelf
	   ajax.view('partials/shelf_form_elements',{
	      data: {
	    	 element    : 'load_shelf',
	    	 guid       : guid,
    		 open_state : open_state
	       },
	    }).done(function(output) {
    	   $('.boqx').removeClass('open').removeAttr('open-state');
    	   $('.boqx[data-guid='+guid+']').addClass('open').attr('open-state',open_state);
    	   $('.boqx[data-guid='+guid+'] .dropdown .close_item').addClass('visible');
           $('.boqx[data-guid='+guid+'] .selector').addClass('open');
           $('#root').addClass('boqx-open').attr('open-boqx', guid).attr('open-state', open_state);
           $('#root .closed-boqx').addClass('compressed');
	    	$('.boqx[data-guid='+guid+'] .selector').attr('open-state', open_state).addClass('selected');
		    $('.page_header_container section.open-boqx').remove();
	       $('.page_header_container').append($(output));
	    });
       
       $('.dropdown[data-cid='+cid+']').removeClass('above_scrim');
       $('.dropdown[data-cid='+cid+'] section.open-state').addClass('closed');
    });
     $(document).on('click','.controlbar-header .controls .button',function(e){
    	 var action = $(this).data('action'),
    	     guid = $('#root').attr('open-boqx'),
    	     ajax = new Ajax(),
    	     message,
    	     count;
         $(document).find('#root.boqx-open .open-boqx').fadeOut('fast',function(){
              $(this).remove();
              $(document).find('.page_header_container .closed-boqx.compressed').removeClass('compressed');
              $(document).find('section.main.compressed').removeClass('compressed');
	          if(action == 'remove'){
		     	   $('.commandArea .boqx[data-guid='+guid+']').remove();
		    	   $('.shelf-items-compartment .shelf-viewer#quebx-shelf-item-'+guid).remove();
		    	   $('.boqx[data-guid='+guid+'] .selector').removeClass('selected');
		      }});
         ajax.action('shelf/do', {
			data:{
				guid: guid,
				action: action
			},success: function (result) {
				if(result.output){
					message = result.output.message;
					count   = result.output.count;
				}
	        }
         });
         console.log('count: '+count);
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