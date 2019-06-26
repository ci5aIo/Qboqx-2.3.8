<?php
/****************************************
 * Switch ...
 *  $element
 *      qbox-menu
 *      new_item_details
 *      properties
 *      properties_service_items
 *      new_receipt_item
 *      new_discovery_item
 *      new_service_item
 *      new_service_task
 *      new_remedy_item
 *      q
            $aspect
            	content
            	experience
            		$presentation
            			inline
            			lightbox
            			default
            	image
            	document
            	project
            	transfer
            		$perspective
            			add
            			list
            	return
            	replace
            	default
 *      qbox
        	$space
        		transfer
        		market
        		  $perspective
        		      pack
        		      edit
        		      view
        		      delete
        		default
 *      qbox_maximized
        	$perspective
        		edit
        		view
 *      Expand
 *      Things
 *      Documents
 *      Gallery
 *      Transfer
 *      Market
        	$perspective
        		edit
        		view
        		delete
 *      inline
        	$perspective
        		edit
        		view
 *      popup
        	$perspective
        		edit
        		view
        			$space
        				market
        				experience
        				transfer
        boqx
            $perspective
                add
                edit
                view
                    $presentation
                        list_boqx
                        default
 * 
 ******************************************/
$element      = elgg_extract('element', $vars);
$guid         = elgg_extract('guid', $vars);
$parent_cid   = elgg_extract('parent_cid', $vars);
$cid          = elgg_extract('cid', $vars);
$service_cid  = elgg_extract('service_cid', $vars);
$qid          = elgg_extract('qid', $vars);
$qid_n        = elgg_extract('qid_n', $vars);
$n            = elgg_extract('n', $vars);
$space        = elgg_extract('space', $vars);
$aspect       = elgg_extract('aspect', $vars);
$action       = elgg_extract('action', $vars);
$perspective  = elgg_extract('perspective', $vars);
$presence     = elgg_extract('presence', $vars);                // Current presentation of the form
$presentation = elgg_extract('presentation', $vars, $presence); // Desired presentation of the form
$context      = elgg_extract('context', $vars);
$section      = elgg_extract('section', $vars, false);
$compartment  = elgg_extract('compartment', $vars, 'Profile');
$form_class   = elgg_extract('form_class', $vars, 'inline-container');
$view_type    = elgg_extract('view_type', $vars, 'compact');
$message      = elgg_extract('message', $vars);
$data_prefix  = elgg_extract('data_prefix', $vars, "jot[$parent_cid][$cid][$n][");
if (elgg_entity_exists($guid)){
	$entity  = get_entity($guid);
	$subtype = $entity->getSubtype();
}
Switch ($element){
	case 'qbox-menu':
		$form_body = "<div class=qbox-section-remove><span title='Remove $section' class='elgg-icon fa elgg-icon-delete-alt fa-times-circle qbox-menu qbox-section-remove' data-cid=$cid></span></div>";
		break;
	case 'new_item_details':
		$item_metadata = str_replace('jot[', 
		                             'jot[snapshot][', 
		                              str_replace('item[', 
		                                          "line_item[$qid_n][", 
		                                          elgg_view('forms/market/edit/family', $vars)));
		$form_body .= elgg_view('output/div',['content'=>$item_metadata,
				                              'class'  => $element,
				                              'options'=> ['id'=>"{$qid_n}_$element"]]);
		break;
	case 'properties':
	    $form_body .="<div id='{$cid}_{$n}' class='jq-dropdown jq-dropdown-tip jq-dropdown-relative'>
                       <div class='jq-dropdown-panel'>".
                           elgg_view('forms/market/properties', [
	                                   'element_type'   =>'receipt_item',
	                                   'container_type' => 'transfer',
                                       'qid'            => $qid,
                                  	   'qid_n'          => $qid_n,
                                       'presence'       => $presence,
                                       'line_item_behavior_list_class'  => $qid_n."_line_item_behavior_list",
                                       'line_item_behavior_list_data'   =>['qid'=>$qid_n],
                                       'line_item_behavior_radio_class' => 'receipt-line-item-behavior',
                    	   ])."
                       </div>
                   </div>";
		break;
	case 'properties_receipt_item':
	case 'properties_loose_thing':
//	case 'properties_book':
/*	    $content = elgg_view('forms/market/properties', [
	                                   'element_type'   =>'receipt_item',
	                                   'container_type' => 'transfer',
                                       'qid'            => $qid,
                                  	   'qid_n'          => $qid_n,
                                       'presence'       => $presence,
                                       'line_item_behavior_list_class'  => $qid_n."_line_item_behavior_list",
                                       'line_item_behavior_list_data'   =>['qid'=>$qid_n],
                                       'line_item_behavior_radio_class' => 'receipt-line-item-behavior',
                    	   ]);
*/
	    $content = elgg_view('forms/market/profile',['presentation'=>'qboqx',
	                                                 'aspect'      => $aspect,
	                                                 'data_prefix' => $data_prefix,
	                                                 'guid'        => $guid,
	                                                 'parent_cid'  => $parent_cid,
	                                                 'cid'         => $cid,
	                                                 'n'           => $n]);
	    $form_body .="<div id='{$cid}_{$n}' class='jq-dropdown jq-dropdown-tip jq-dropdown-relative'>
                       <div class='jq-dropdown-panel' style='max-width:400px'>
                            $content
                       </div>
                   </div>";
		break;
	case 'properties_book':
	    $view      = 'forms/transfers/edit';
//	    $content   = elgg_view($view,['perspective'=>$perspective, 'section'=>'boqx_contents_receipt', 'snippet'=>'receipt_item_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
	    $content = elgg_view($view,['perspective'=>'add', 'section'=>'boqx_contents_books', 'snippet'=>'book_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
        				
	    $form_body = $content;
	    break;
	case 'properties_service_item':
//			    $form_body .="<div id={$cid}_{$n} class='qboqx-dropdown qboqx-dropdown-tip qboqx-dropdown-relative_xxx'>
			    $form_body .="<div id={$cid}_{$n} class='jq-dropdown jq-dropdown-tip jq-dropdown-relative'>
	                           <div class='qboqx-dropdown-panel'>".
                                   elgg_view('forms/market/properties', [
	    	                                   'element_type'   =>'service_item',
	    	                                   'container_type' => 'experience',
		                                       'qid'            => $qid,
                                          	   'qid_n'          => $qid_n,
                                          	   'cid'            => $cid,
                                          	   'parent_cid'     => $parent_cid,
                                          	   'n'              => $n,])."
                               </div>
                           </div>";
		break;
	case 'new_receipt':
//		$form_body = elgg_view('forms/transfers/edit',['perspective'=>'add', 'section'=>'boqx_contents_receipt', 'snippet'=>'marker1', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$qid, 'n'=>$n]);
	    $form_body = elgg_view('forms/transfers/edit',['perspective'=>'add', 'section'=>'boqx_contents_receipt', 'snippet'=>'marker1', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'n'=>$n]);
//		$form_body = elgg_view('forms/transfers/elements/receipt', ['presentation'=>'box_experimental', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'n'=>$n]);
		break;
	case 'new_loose_thing':
	    if ($presence == 'panel') {
	       $form_body = elgg_view('forms/transfers/edit',['perspective'=>'add', 'section'=>'boqx_contents', 'snippet'=>'single_thing', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$qid, 'qid_n'=>$qid_n, 'n'=>$n]);
	       break;
	    }
	    break;
	case 'new_book':
	    if ($presence == 'panel') {
	       $form_body = elgg_view('forms/transfers/edit',['perspective'=>'add', 'section'=>'boqx_contents', 'snippet'=>'single_book', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$qid, 'qid_n'=>$qid_n, 'n'=>$n]);
	       break;
	    }
	    break;
	case 'new_receipt_item':
	    if ($presence == 'panel') {
	        $form_body = elgg_view('forms/transfers/edit',['perspective'=>'add', 'section'=>'boqx_contents_receipt', 'snippet'=>'receipt_item', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$qid, 'qid_n'=>$qid_n, 'n'=>$n]);
	        break;
	    }
		$delete = elgg_view('output/url', ['title'=>'remove receipt item',
									       'class'=>'remove-node',
										   'data-qid' => $qid_n,
									       'style'=> 'cursor:pointer',
									       'text' => elgg_view_icon('delete-alt'),]);
		
		$set_properties_button = elgg_view('output/url', ['data-jq-dropdown'    => '#'.$qid_n,
										                  'data-qid'            => $qid_n,
										                  'data-horizontal-offset'=>"-15",
								                          'text'                => elgg_view_icon('settings-alt'), 
								                          'title'               => 'Detailed description',
		]);
		

		$form_body .="<div class='rTableRow receipt_item ui-sortable-handle' data-qid='$qid_n'>
						<div class='rTableCell'>$delete</div>
						<div class='rTableCell'></div>
						<div class='rTableCell'>".elgg_view('input/hidden', ['name' => "line_item[$qid_n][new_line]"]).
						                          elgg_view('input/number', ['name' => "line_item[$qid_n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1'])."</div>
						<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', array(
													'name'      => "line_item[$qid_n][title]",
								                    'class'     => 'rTableform90',
								                    'required'  => '',
						                            'id'        => 'line_item_input',
								                    'data-name' => 'title',
												))."
			            </div>
						<div class='rTableCell'>".elgg_view('input/checkbox', array(
													'name'      => "line_item[$qid_n][taxable]",
													'value'     => 1,
								                    'data-qid'  => $qid_n,
								                    'data-name' => 'taxable',
		        			                        'default'   => false,
												))."</div>
						<div class='rTableCell'>".elgg_view('input/text', array(
													'name'      => "line_item[$qid_n][cost]",
							 						'class'     => 'last_line_item',
								                    'data-qid'  => $qid_n,
								                    'data-name' => 'cost',
	    											'class'     => 'nString',
												))."</div>
						<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid}_line_total line_total_raw'></span></div>
                     </div>";
		//$form_body .= $property_cards;
        break; //($element_type == 'new_receipt_item')
	case 'new_discovery_item':
		$delete = elgg_view('output/url', ['title'=>'remove receipt item',
									       'class'=>'remove-node',
										   'data-qid' => $qid_n,
									       'style'=> 'cursor:pointer',
									       'text' => elgg_view_icon('delete-alt'),]);
		
		$set_properties_button = elgg_view('output/url', ['data-jq-dropdown'    => '#'.$qid_n,
										                  'data-qid'            => $qid_n,
										                  'data-horizontal-offset'=>"-15",
								                          'text'                => elgg_view_icon('settings-alt'), 
								                          'title'               => 'set properties',
		]);
		
		//refer to new_receipt_item above for updated method (e.g. 'name' => "line_item[$qid_n][taxable]") to capture new line items
		$form_body .= "
                    		<div class='rTableRow ui-sortable-handle' data-qid='$qid_n'>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>".quebx_view_icon('move')."</div>
                    			<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".elgg_view('input/date', array(
                    											'name' => 'jot[observation][discovery][date][]',
                    									))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][action][]',
                    							        		'class'=> 'rTableform',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][observation][]',
                    							        		'class'=> 'rTableform',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][discovery][]',
                    							        		'class'=> 'rTableform last_discovery',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
		//$form_body .= $property_cards;
		
       break; //($element_type == 'new_discovery_item')
	case 'new_service_item':                                                                       $display .= 'parent_cid: '.$parent_cid.'<br>$cid: '.$cid.'<br>$qid: '.$qid.'<br>';
		$form_body = elgg_view('forms/experiences/edit',['section'=>'issue_effort_service', 'action'=>'add', 'snippet'=>'service_item', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$qid, 'qid_n'=>$qid_n, 'n'=>$n]);
		break; 
	case 'new_service_task':                                                                       $display .= 'parent_cid: '.$parent_cid.'<br>';
		$form_body = elgg_view('forms/experiences/edit',['section'=>'issue_effort_service', 'action'=>'add', 'snippet'=>'marker1', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$qid, 'n'=>$n]);
		break;
	case 'new_remedy_item':                                                                       $display .= 'service_cid: '.$service_cid.'<br>$cid: '.$cid.'<br>$qid: '.$qid.'<br>';
		$form_body = elgg_view('forms/experiences/edit',['section'=>'issue_effort', 'action'=>'add', 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'n'=>$n]);
		break;
	case 'cancel_new_things':
         $boqx_contents_show = elgg_view('forms/transfers/edit',[
            'perspective'       => 'add',
            'section'           => 'things_boqx',
            'snippet'           => 'contents_show',
            'show_view_summary' => false,
            'parent_cid'        => $parent_cid,
            'cid'               => $cid]);
         $boqx_contents_edit = elgg_view('forms/transfers/edit',[
            'perspective'       => 'add',
            'section'           => 'things_boqx',
            'snippet'           => 'contents_edit',
            'show_view_summary' => false,
            'parent_cid'        => $parent_cid,
            'cid'               => $cid]);
	    $form_body  = $boqx_contents_show;
	    $form_body .= $boqx_contents_edit;
	    break;
	case 'q':
		switch($aspect){
			case 'content':
				$title     = 'Contents';
				$body_vars = ['title'         => $title,
						      'guid'          => $guid,
						      'container_guid'=> $guid,
						      'qid'           => $qid,
						      'selected'      => true,
						      'presentation'  =>'qbox',
						      'action'        => 'view',
						      'full_view'     => false,
						      'aspect'        => $aspect,
						      'perspective'   => $perspective];
				$form_body =  elgg_view('output/div', ['content' => elgg_view_entity($entity, $body_vars),
						                               'class'   => 'qbox-content-expand',
						                               'options' => ['id'=>$qid]]);
				$contents   = elgg_get_entities(['type'=>'object', 'subtypes'=>ELGG_ENTITIES_ANY_VALUE, 'limit' => false,]);
				foreach ($contents as $content){
					$elements[] = ['guid'           => $content->guid,
					               'container_guid' => $content->container_guid,
							       'title'          => $content->title];
				}
				$parent_id      = $guid;
				foreach ($elements as $element) {
				    $id = $element['guid'];
				    $parent_id = $element['container_guid'];
				    $data[$id] = $element;
				    $index[$parent_id][] = $id;
				}
				$options = ['data'           => $data, 
						    'index'          => $index, 
						    'parent_id'      => $guid, 
						    'ul_class'       => 'hierarchy', 
						    'collapsible'    => true,
						    'collapse_level' => 1,
						    'level'          => 0,
						    'links'          => true];
				$vars['title']        = $title;
				$vars['show_title']   = true;
	        	$vars['content']      = quebx_display_child_nodes($options);
	        	$vars['show_save']    = false;
	        	$vars['disable_save'] = true;
				$form_body =  elgg_view('output/div', ['content' => elgg_view_layout('qbox',$vars),
						                               'class'   => 'qbox-content-expand',
						                               'options' => ['id'=>$qid]]);
				
				break;
				
			case 'experience':
				$title                          = 'Experience';
                $expansion_objects              = elgg_get_entities(['type'=>'object', 'container_guid'=>$guid]);
                $expansion                      = $expansion_objects[0]; // There must be only one expansion of an experience
                if ($expansion) {
                	$selected                   = $expansion->aspect;
                }
/*				$tab_vars  = ['menu'            => 'q_expand',
                              'guid'            => $guid,
        		              'class'           => "qbox-$guid",
						      'qid_parent'      => $qid,
						      'selected'        => $selected,];
				$expand_tabs                    =  elgg_view('jot/menu', $tab_vars);
        		unset($tab_vars);
		        $forms_action                   =  'forms/experiences/edit';
				$body_vars = ['guid'            => $guid,
						      'container_guid'  => $guid,
						      'qid'             => $qid.'_1',
		                      'section'         => 'things',
						      'selected'        => true,
						      'style'           => 'display:none;',
						      'presentation'    => $presentation,
						      'action'          => 'add'];
				$thing_panel                    = elgg_view($forms_action, $body_vars);
*//*				$tab_vars  = ['subtype'         => 'experience',
                              'this_section'    => 'Expand',
                              'state'           => 'selected',
                              'action'          => $action,
                              'guid'            => $guid,
				              'qid'             => $qid,
				              'cid'             => $cid,
        		              'presentation'    => 'qbox',
						      'class'           => "qbox-$guid",
                              'ul_style'        => 'border-bottom: 0px solid #cccccc',
                              'ul_aspect'       => 'attachments',
							  'link_class'      => 'qbox-q qbox-menu',
	 			              'attachments'     => ['things'=>1, 'documents'=>0, 'gallery'=>0]];
//				if ($action == 'add') unset($tab_vars['guid']);
		        $tabs                           =  elgg_view('quebx/menu', $tab_vars);
		        $asset_title                    = get_entity($guid)->title;
*/		        $forms_action                   =  'experiences/edit';
				$form_vars = ['name'            => 'jotForm', 
		                      'enctype'         => 'multipart/form-data',
//		                      'action'          => 'action/jot/add/element',];
		                      'action'          => 'action/jot/edit_scratch',];
				$body_vars = ['title'           => 'New Experience',
						      'guid'            => $guid,
						      'container_guid'  => $guid,
            				  'qid'             => $qid,
            				  'cid'             => $cid,
		                      'section'         => 'main',
						      'selected'        => true,
						      'presentation'    => $presentation,
				              'perspective'     => $action,
						      'action'          => $action, 
//						      'tabs'            => $tabs,
//						      'expand_tabs'     => $expand_tabs,
//						      'preloaded_panels'=> $thing_panel
				];
				if($action=='add') unset($body_vars['guid']);
/*				$content = elgg_view('output/div', ['content' => elgg_view_form($action, $form_vars, $body_vars),
						                            'class'   => 'qbox-content-expand',
						                            'options' => ['id'=>$qid]]);*/
//				$content                        = elgg_view("forms/$forms_action", $body_vars);
				$content                        = elgg_view_form($forms_action, $form_vars, $body_vars);
				switch ($presentation){
					case 'inline':
					    $vars['content']        = $content;
					    $vars['show_save']      = false;
					    $vars['show_title']     = false;
					    $form_body              = elgg_view_layout($presentation, $vars);
					    break;
					case 'lightbox':
						$vars['action']         = $forms_action;
						$vars['body_vars']      = $body_vars;
						$vars['form_vars']      = $form_vars;
						$vars['perspective']    = 'add';
						$vars['show_save']      = false;
						$form_body              = elgg_view_layout($presentation, $vars);
						break;
					default:
						$form_body              = $content;
						break;
				}
		/* from 'Market' below
		        if ($context == 'widgets'){
		        	$vars['position'] = 'relative';
		        	$form_body = elgg_view_layout('inline', $vars);}
		        else {
		        	$form_body = "<div class = 'inline-content-expand' id=$qid>
						           		$content
						          </div>";
		        }*/
			break;
			case 'image':
				$title = 'Attach pictures';
				$forms_action = 'experiences/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element',];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'section' => 'gallery',
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add',
						      'title'   => $title,];
				$form_body = elgg_view('output/div', ['content' => elgg_view_form($forms_action, $form_vars, $body_vars),
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
			break;
			case 'document':
				$title = 'Attach documents';
				$forms_action = 'experiences/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element',];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'section' => 'documents',
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add',
						      'title'   => $title,];
				$form_body = elgg_view('output/div', ['content' => elgg_view_form($forms_action, $form_vars, $body_vars),
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
			break;
			case 'project':
				$forms_action = 'forms/experiences/edit';
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'section' => $aspect,
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add'];
				$form_body = elgg_view('output/div', ['content' => elgg_view($forms_action, $body_vars),
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
			break;
			case 'transfer':
				$form_action = 'transfers/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element_v2',
						      'id'      => $perspective.'_'.$aspect,];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
						      'space'   => $space,
		                      'aspect'  => $aspect,
						      'perspective'=>$perspective,
						      //'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => $perspective,
						      'context' => $context,
						      'title'   => $title,
						      'disable_save'=>true,];
				Switch ($perspective){
					case 'add':
						$body_vars['title'] = 'Transfer';
						$content   = elgg_view_form($form_action, $form_vars, $body_vars);
						break;
					case 'list':
						$body_vars['title']='Transfers';
						$transfers = elgg_get_entities_from_relationship(
							   ['type'                 => 'object',
								'relationship'         => 'transfer_receipt',
								'relationship_guid'    => $guid,
								'inverse_relationship' => true,]);
						$body_vars['list_type_toggle'] =  true;
						$body_vars['view_type']        =  'compact';
						$body_vars['perspective']      =  'list';
						$body_vars['show_title']       =  true;
							$list_items                =  elgg_view('output/div',['content'=>elgg_view_entity_list($transfers, $body_vars)]);
						$body_vars['content']          =  $list_items;
						$body_vars['show_save']        =  false;
						$body_vars['show_full_view']   =  false;
						$content                       =  elgg_view_layout('qbox', $body_vars);
						break;
				}
				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-container',
						                              'options' => ['id'=>$qid]]);
				break;
			case 'return':
				$title = 'Return items';
				$forms_action = 'transfers/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element_v2',];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
		                      'aspect'  => $aspect,
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add',
						      'title'   => $title,
						      'disable_save'=>true,];
				$content   = elgg_view_form($forms_action, $form_vars, $body_vars);
				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-container',
						                              'options' => ['id'=>$qid]]);
				
				break;
			case 'replace':
				$title = 'Replace with a different one';
				$forms_action = 'transfers/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element_v2',];
				$body_vars = $vars;
				$body_vars['container_guid'] = $guid;
				$body_vars['selected']       = true;
				$body_vars['presentation']   = 'qbox';
				$body_vars['action']         = 'add';
				$body_vars['title']          = $title;
				$body_vars['disable_save']   = true;
				$body_vars['show_full_view'] = false;
				$content   = elgg_view_form($forms_action, $form_vars, $body_vars);
				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-container',
						                              'options' => ['id'=>$qid]]);
				
				break;
			default:
				$title = 'Attach '.$aspect;
				$forms_action = 'experiences/edit';
				$form_vars = ['name'    => 'jotForm', 
		                      'enctype' => 'multipart/form-data',
		                      'action'  => 'action/jot/add/element_v2',];
				$body_vars = ['guid'    => $guid,
						      'container_guid'=> $guid,
						      'qid'     => $qid,
						      'space'   => $space,
		                      'aspect'  => $aspect,
						      'perspective'=>$perspective,
		                      'section' => $aspect,
						      'selected'=> true,
						      'presentation'=>'qbox',
						      'action'  => 'add',
						      'title'   => $title,];
				$content   = elgg_view_form($forms_action, $form_vars, $body_vars);
/*				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
*/				$form_body = elgg_view('output/div', ['content' => $content,
						                              'class'   => 'qbox-content-expand',
						                              'options' => ['id'=>$qid]]);
			break;
		}
	break;
	case 'qbox':
		Switch ($space){
 			case 'transfer':
 				if ($space==$subtype){                                  // This is an existing transfer
					if ($perspective == 'delete'){
	 					$entity->delete();
	 					continue;
	 				}                               	 				// View or edit a transfer
					$body_vars = ['guid'          =>$guid,
							      //'container_guid'=>$guid,
							      'qid'           =>$qid,
							      'qid_n'         =>$qid_n,
								  'cid'           =>$cid,
							      'space'         =>$space,
			                      'aspect'        =>$aspect,
							      'asset'         =>$entity->asset,
							      'perspective'   =>$perspective,
						          'context'       =>$context,
							      'selected'      =>true,
							      'presentation'  =>'qbox',
							      'view_type'     =>'compact',
							      'disable_save'  =>$perspective == 'view',
							      'show_title'    =>true,
							      'let_edit'      =>$perspective == 'view',
							      'let_view'      =>$perspective == 'edit',];
					//$form_body  = elgg_view('output/div', ['options' => ['id'=>'form-messages']]);
					//$form_result_msg = elgg_view('output/div',['options'=>['id'=>"form-messages"]]); 
					$form_body .= elgg_view('output/div', ['content' => //$form_result_msg.
							                                            elgg_view_entity($entity, $body_vars),
							                              'class'    => 'qbox-content-expand',
							                              'options'  => ['id'=>$qid]]);
 				}
 				else {                                                 // Add new transfer
 					$view      = "transfers/edit";
 					$form_vars = ['action'  => 'action/jot/edit_v4'];
//  					$body_vars = ['element_type'   => 'item',
// 			                      'panel'         => 'receipts'];
                    $body_vars = ['title'         => "Add a $aspect",
			                      'action'        => 'add',
			                      'do'            => $perspective.'_'.$aspect,
			                      'qid'           => $qid,
							      'qid_n'         => $qid_n,
								  'cid'           => $cid,
							      'space'         => $space,
			                      'aspect'        => $aspect,
							      'perspective'   => $perspective,
						          'context'       => $context,
							      'owner_guid'     => elgg_get_logged_in_user_guid(),
			                      'container_guid' => elgg_get_logged_in_user_guid(),
			                      'selected'      => true,
							      'presentation'  => 'qbox',
							      'view_type'     => 'compact',
							      'disable_save'  => false,
							      'show_title'    => true,
							      'let_edit'      => false,
							      'let_view'      => false];
 					$form_body =  elgg_view('output/div', ['content' => elgg_view_form($view, $form_vars, $body_vars),
							                               'class'   => 'qbox-content-expand',
							                               'options' => ['id'=>$qid]]);
 					//$display .= '$space:'.$space.'<br>';
 					$display .= '<hr>'.print_r($body_vars, true);
 				}
				break;
 			case 'market':
 				switch ($perspective){
 					case 'pack':
 						$forms_action    = 'forms/jot/edit';
 						$body_vars = ['guid'         => $guid,
 								      'aspect'       => $aspect,
 								      'perspective'  => $perspective,
 								      'presentation' => 'drop_box'
 								     ];
 						$content = elgg_view($forms_action, $body_vars);
 						
 						break;
 					case 'edit':
						$forms_action    =  'forms/market/edit';
						$body_vars = ['guid'    => $guid];
						$content   = elgg_view($forms_action, $body_vars);
				 		$vars['content']      = $content;
				        $vars['disable_save'] = $disable_save;
				        if ($context == 'widgets'){$vars['position'] = 'relative';}
						break;
 					case 'view':
 						$body_vars = ['entity' =>$entity,
 						              'guid'=>$guid,
								      'full_view'=>false,
								      'this_section'=>'Profile',
								      'view_type'=>'inline',
								      'perspective'=>$perspective];
						$content   = elgg_view_entity($entity, $body_vars);
				 		$vars['content']      = $content;
				 		$vars['show_save']    = false;
				        if ($context == 'widgets'){$vars['position'] = 'relative';}
						break;
 					case 'delete':
 					    $entity->delete();
 					    break;
 				}
				$content = elgg_view_layout('qbox',$vars);
		        $form_body = elgg_view('output/div', ['content' => $content,
						                              'class'    => 'qbox-content-expand',
						                              'options'  => ['id'=>$qid]]);
				break;
			default:
				$forms_action    =  'forms/experiences/edit';
				$body_vars = ['action'        => $action,
		                      'section'       => $section,
						      'guid'          => $guid,
						      'container_guid'=> $guid,
						      'qid'           => $qid,
							  'cid'           => $cid,
						      'service_cid'   => $service_cid,
						      'selected'      => true,
						      'presentation'  => $presentation,];
				$form_body =  elgg_view($forms_action, $body_vars);
				break;		
		}
	break;
	case 'qbox-maximized':
		switch ($perspective){
 			case 'edit':
				$forms_action    =  'forms/market/edit';
				$body_vars = ['guid'    => $guid];
				$content   = elgg_view($forms_action, $body_vars);
		 		$vars['content']      = $content;
		        $vars['disable_save'] = $disable_save;
		        if ($context == 'widgets'){$vars['position'] = 'relative';}
				break;
 			case 'view':
 				$body_vars = ['entity' =>$entity,
 						      'guid'=>$guid,
 						      'qid'=>$qid,
							  'full_view'=>false,
							  'this_section'=>'Profile',
							  'view_type'=>'inline',
							  'perspective'=>$perspective];
				$content   = elgg_view_entity($entity, $body_vars);
		 		$vars['content']      = $content;
		 		$vars['show_save']    = false;
		 		$vars['let_edit']     = true;
		        if ($context == 'widgets'){$vars['position'] = 'relative';}
				break;
 		}
		$form_body = elgg_view_layout('maximized',$vars);
		break;
	case 'Expand':
		$forms_action    =  'forms/experiences/edit';
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid,
                      'section' => 'expand',
				      'selected'=> true,
				      'presentation'=>'qbox_experience',
				      'action'  => 'add'];
		$form_body =  elgg_view($forms_action, $body_vars);
	break;
	case 'Things':
		
	case 'Documents':
//		$forms_action    = 'forms/experiences/edit';
		$title     = 'Attach documents';
		$forms_action    = 'experiences/edit';
		$form_vars = ['name'    => 'jotForm',
                      'enctype' => 'multipart/form-data',
                      'action'  => 'action/jot/add/element', 
					  'class'   => $form_class];
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid,
                      'section' => 'documents',
				      'selected'=> true,
				      'presentation'=>'inline',
				      'action'  => 'add',
				      'title'   => $title,];
		$content = elgg_view('output/div', ['content' => elgg_view_form($forms_action, $form_vars, $body_vars),
				                            'class'   => 'inline-content-expand',
				                            'options' => ['id'=>$qid]]);
        $form_body = $content;
		
		break;
	case 'Gallery':
		$title     = 'Attach pictures';
		$forms_action    = 'experiences/edit';
		//$forms_action    = 'forms/experiences/edit';
		$form_vars = ['name'    => 'jotForm',
                      'enctype' => 'multipart/form-data',
                      'action'  => 'action/jot/add/element', 
					  'class'   => $form_class];
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid,
                      'section' => 'gallery',
				      'selected'=> true,
				      'presentation'=>'inline',
				      'action'  => 'add',
				      'title'   => $title,];
//		$form_body =  elgg_view($forms_action, $body_vars);
		$content = elgg_view('output/div', ['content' => elgg_view_form($forms_action, $form_vars, $body_vars),
				                            'class'   => 'inline-content-expand',
				                            'options' => ['id'=>$qid]]);
        $form_body = $content;
		
	break;
	case 'transfer':
		$forms_action    =  'forms/transfers/edit';
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid,
                      'aspect' => $aspect,
				      'selected'=> true,
				      'presentation'=>'qbox_transfer',
				      'action'  => 'add'];
		
		$form_body = elgg_view($forms_action, $body_vars);
		break;
	case 'market':
		switch ($perspective){
		    case 'add':
		    case 'edit':
//				$forms_action    =  'forms/market/profile';
//				$body_vars = ['guid'    => $guid];
 				if ($context == 'market'){
 					$presentation = 'inline'; // otherwise be what is passed in
 				}
				$form_version = 'market/profile';
				$form_vars = ['name' => 'marketForm', 
						      'enctype' => 'multipart/form-data', 
						      'action' => 'action/market/edit', 
						      'class'=>$form_class];
				$body_vars = ['guid'        => $guid,
						      'qid'         => $qid,
						      'qid_n'       => $qid_n,
				              'cid'         => $cid,
						      'space'       => $space,
						      'aspect'      => $aspect,
						      'perspective' => $perspective,
						      'context'     => $context,
        				      'presentation'=> 'inline',//$presentation,
        				      'view_type'   => 'inline',];
				if ($perspective == 'add') unset($body_vars['guid']);
				$content = elgg_view_form($form_version, $form_vars, $body_vars);				  
//				$content   = elgg_view('forms/'.$form_version, $body_vars);
                switch ($context){
                    case 'widgets':
    		        	$vars['content']        = $content;
    			 		$vars['show_save']      = false;                   //Upper Save icon lands outside of the form and won't work.
    			 		$vars['show_full_view'] = false;
    			        $vars['position']       = 'relative';
    		        	$form_body = elgg_view_layout('inline', $vars);
    		        	break;
 			        default:
 			            switch ($presentation){
                 			case 'popup':
                 			    $vars['content']        = $content;
                 			    $vars['show_save']      = false;                   //Upper Save icon lands outside of the form and won't work.
                 			    $vars['show_full_view'] = false;
                 			    $vars['show_title']     = true;
                 			    $vars['show_tip']       = false;
                 			    $vars['position']       = 'relative';
                 			    $vars['presentation']   = $presentation;
                 			    $vars['anchored']       = true;
                 			    $form_body = elgg_view_layout('qbox', $vars);
//                 			    $form_body = elgg_view_layout('inline', $vars);
//                  			    $form_body .="<div id=$cid class='qboqx-dropdown qboqx-dropdown-tip qboqx-dropdown-relative'>
//                                  			    <div class='qboqx-dropdown-panel'>
//                                                     $content
//                                                </div>
//                                              </div>";
                 			    break;
                 			default: 
                 			    if (isset($cid)){$data_cid = "data-cid='$cid'";}
                 			    if (isset($qid)){$data_qid = "data-qid='$qid'";}
        		        	    $form_body = "<div class = 'qbox-container inline-content-expand' $data_qid $data_cid>
            						           		$content
            						          </div>";
         			            }
    				break;
		          }
				break;
 			case 'view':
 					unset($compartments);
				    $compartments[] = 'Profile';
				    $compartments[] = 'Summary';
				    $compartments[] = 'Details';
				    $compartments[] = 'Maintenance';
				    $compartments[] = 'Inventory';
				    $compartments[] = 'Management';
				    $compartments[] = 'Accounting';
				    $compartments[] = 'Gallery';
				    $compartments[] = 'Timeline';
		 		$this_compartment   = $compartments[0]; 
 				$body_vars            = [//'entity'      =>$entity,
			 						     'guid'        =>$guid,
			 						     'qid'         =>$qid,
 						                 'qid_n'       => $vars['qid_n'],
										 'full_view'   => false,
 						                 'preload_compartments'=>true,
										 'compartment' => $this_compartment,
 						                 'compartments'=> $compartments,
										 'view_type'   => 'inline',
 						                 'element'     => $element,
										 'perspective' => $perspective,
 				                         'presentation'=> $presentation,
 				                         'presence'    => $presentation,
 				];
				$content              = elgg_view_entity($entity, $body_vars);
		 		$vars['content']      = $content;
		 		$vars['show_save']    = false;
		 		$vars['let_edit']     = true;
		 		$vars['show_title']   = false;
		 		$vars['show_full_view']=false;
				$data                 = ['guid'=>$guid,'qid'=>$qid,'element'=>'compartment','space'=>$space,'perspective'=>$perspective,'compartment'=>$compartment, 'context'=>'inline', 'presentation'=>$presentation];
				$vars['tabs']         = elgg_view('items/menu', ['guid' =>$guid, 'this_section' => $this_compartment,'link_class'=>'do quebx-htabs-compact', 'data'=>$data, 'compartments'=>$sections]);
		        if ($context == 'widgets'){
		        	$vars['position'] = 'relative';}
				$form_body = elgg_view_layout('inline', $vars);
				break;
 			case 'attach':
 			    $form                     = 'forms/experiences/edit';
 			    switch($aspect){
 			        case 'experience':
 			            $vars['entity']   = get_entity($guid);
 			            $vars['data-qid'] = $qid;
		                $form_body       .= elgg_view($form, $vars);
 			            break;
 			    }
 			    break;
 			case 'delete':
 			    $entity->delete();
 			    break;
 		}
		break;
	case 'inline':
		switch ($perspective){
 			case 'edit':
				$forms_action    =  'forms/market/edit';
				$body_vars = ['guid'    => $guid];
				$content   = elgg_view($forms_action, $body_vars);
		 		$vars['content']      = $content;
		 		$vars['show_save']    = false;                   //Upper Save icon lands outside of the form and won't work.
//		 		$vars['disable_save'] = $disable_save;
		        if ($context == 'widgets'){$vars['position'] = 'relative';}
				break;
 			case 'view':
 				$this_section         = $section ?: 'Profile'; 
 				$body_vars            = ['entity' =>$entity,
			 						     'guid'=>$guid,
			 						     'qid'=>$qid,
 						                 'qid_n'=>$vars['qid_n'],
										 'full_view'=>false,
										 'compartment'=>$compartment,
										 'view_type'=>'inline',							  
										 'perspective'=>$perspective,
 				                         'presentation'=>$presentation];
				$content              = elgg_view_entity($entity, $body_vars);
		 		$vars['content']      = $content;
		 		$vars['show_save']    = false;
		 		$vars['let_edit']     = true;
		 		$vars['show_title']   = true;
				$data                 = ['guid'=>$guid,'qid'=>$qid,'element'=>'compartment','space'=>$space,'perspective'=>$perspective,'compartment'=>$compartment, 'context'=>'inline', 'presentation'=>$presentation];
				$vars['tabs']         = elgg_view('items/menu', ['guid' =>$guid, 'this_section' => $this_section,'link_class'=>'do quebx-htabs-compact', 'data'=>$data]);
		        if ($context == 'widgets'){
		        	$vars['position'] = 'relative';}
				break;
 		}
		$form_body = elgg_view_layout('inline', $vars);
		break;
	case 'popup':
		switch ($perspective){
		    case 'edit':
			    $max_width = '500px';
			    $n            = 0;
			    $forms_action = 'experiences/edit';
			    $form_vars = ['name'    => 'jotForm',
			        'enctype' => 'multipart/form-data',
			        'action'  => 'action/jot/edit_scratch',];
			    $container_guid = $entity->container_guid;
			    $tab_vars     = ['menu'         => 'q_expand',
            			         'guid'         => $guid,
            			         'class'        => "qbox-$guid",
            			         'qid_parent'   => $qid,];
			    $expand_tabs  = elgg_view('jot/menu', $tab_vars);
/*			        $preloaded_panels .= elgg_view($forms_action, [
			            'guid'          => $guid,
			            'container_guid'=> $guid,
			            'qid'           => $qid.'_'.$i,
			            'section'       => $section,
			            'style'         => 'display:none;',
			            'presentation'  => $presentation,
			            'action'        => $perspective]);
*/			    $tab_vars     = ['subtype'      => 'experience',
            			         'this_section' => 'Expand',
            			         'state'        => 'selected',
            			         'action'       => $perspective,
            			         'guid'         => $guid,
			                     'cid_array'    => $cid_array,
            			         'presentation' => 'qbox',
            			         'class'        => "qbox-$guid",
            			         'ul_style'     => 'border-bottom: 0px solid #cccccc',
            			         'ul_aspect'    => 'attachments',
            			         'link_class'   => 'qbox-q qbox-menu',
            			         'attachments'  => ['things'=>1]];
			    $tabs          =  elgg_view('quebx/menu', $tab_vars);
			    $body_vars     = ['guid'          => $guid,
                			    //            				    'container_guid'=> $container_guid,
                			      'qid'           => $qid,
                			      'qid_n'         => $qid_n,
                			      'cid'           => $cid,
                			      'space'         => $space,
                			      'aspect'        => $aspect,
                			      'section'       => 'main',
                			        //            				    'tabs'          => $tabs,
                			    //            				    'expand_tabs'   => $expand_tabs,
                			      'selected'      => true,
                			      'presence'      => $presence,
                			      'presentation'  => $presentation,
                			      'action'        => $action,
                			      'perspective'   => $perspective,
                			      'context'       => $context,
                			      'view_type'     => $view_type,
                			        //				                'preloaded_panels'=>$preloaded_panels,
                			    //				                'preload_panels'=> $preload_panels,
                			    ]; 
			    $form_body     = elgg_view_form($forms_action, $form_vars, $body_vars);
//			    $form_body     = elgg_view('forms/'.$forms_action, $body_vars);
				                                                                           $display .= '916 $perspective: '.$perspective.'<br>916 $presence: '.$presence.'<br>';
register_error($display);
			    break;
			case 'edit_xxx':
			case 'view':
				$max_width = $max_width ?: '500px';
				Switch ($space){
				    case 'market':
				    case 'experience':
				            $qid_n = $qid."_01";
				            $cid   = quebx_new_cid ();//'c'.mt_rand(1,999);
				    case 'transfer':
    				        $close_icon= elgg_view_icon('window-close',['title'=>'Close']);
    				        $close_button = "<button class='inlineClose inline-controls-button' type='button' data-qid='$qid' data-perspective='$perspective'>
                    				            $close_icon
                    				        </button>";
    				        
		 					$body_vars = ['guid'          =>$guid,
									      //'container_guid'=>$guid,
									      'qid'           => $qid,
									      'qid_n'         => $qid_n,
		 					              'cid'           => $cid,
									      'space'         => $space,
					                      'aspect'        => $aspect,
									      'asset'         => $entity->asset,
									      'perspective'   => $perspective,
									      'element'       => $element,
									      'presentation'  => 'popup',
									      'presence'      => $presence,
									      'action'        => $action,
								          'context'       => $context,
									      'selected'      => true,
									      'view_type'     => $view_type,
									      'disable_save'  => $perspective == 'view',
									      'show_title'    => true,
									      'let_edit'      => $perspective == $action,
									      'let_view'      => $perspective == $action,];
							//$form_body  = elgg_view('output/div', ['options' => ['id'=>'form-messages']]);
// 		 					if ($perspective == 'edit'){
// 		 					    $form_body = elgg_view_entity($entity, $body_vars);
// 		 					}
// 		 					else {
// 		 					    $form_body = elgg_view('output/div', ['content' => elgg_view('output/div',['content'=> "<div class='inline-controls'>
//                                                                                     		 					            $close_button
//                                                                                     		 					        </div>"
//                                                                                     		 					        .elgg_view_entity($entity, $body_vars),
//     									                                                                    'class'  =>'jq-dropdown-panel',
//     									                                                                    'options'=>['style'=>"overflow:visible;max-width:$max_width;"]]),
//     									                              'class'    => 'jq-dropdown jq-dropdown-tip',
//     									                              'options'  => ['id'=>$qid]]);
		 					    $form_body = elgg_view_layout('jq_dropdown', ['content'   => elgg_view_entity($entity, $body_vars),
		 					                                                  'qid'       => $qid,
		 					                                                  'class'     => 'qboqx',
		 					                                                  'show_tip'  => false,
                                                                              'max_width' => $max_width]);
//		 					}
						break;
				}
				break;
		}
		break;
	case 'boqx':
	    switch ($perspective){
	        case 'add':
	        case 'edit':
	        case 'view':
			    $view = 'forms/transfers/edit';
			    $params = $vars;
	            Switch($presentation){
	                case 'list_boqx_edit':
			            $view = 'transfers/edit';
                        $form_vars = ['name'    => 'jotForm', 
                                      'enctype' => 'multipart/form-data',
                                      'action'  => 'jot/edit_scratch2'];
                        $body_vars = [
                            'perspective'       => $perspective,
                            'display'           => 'block',
                            'section'           => $section,
                            'snippet'           =>'contents_edit',
                            'show_view_summary' => false,
							'guid'              => $guid,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid];
                        $form_body = elgg_view_form($view, $form_vars, $body_vars);
                        
/*                         $form_body = elgg_view($view,[
                            'perspective'       => $perspective,
                            'display'           => 'block',
                            'section'           => $section,
                            'snippet'           =>'contents_edit',
                            'show_view_summary' => false,
							'guid'              => $guid,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid]);*/
	                    break;
	                default:
                        $params['section'] = 'things_boqx';
                        $params['perspective']=$perspective;
                        $params['content']  = elgg_view($view, $params);
                        
        //				$form_body = elgg_view_layout('inline', $params);
        				$form_body = elgg_view($view, $params);
	            }
	            break;
	    }
	default:
	break;
}


echo $form_body;
//echo $display;
