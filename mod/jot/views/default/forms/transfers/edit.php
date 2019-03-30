<!--Form: jot\views\default\forms\transfers\edit.php-->
<?php
/**
 * transfers edit form body
 *
 * @package ElggPages	
 */

setlocale(LC_MONETARY, 'en_US');
$transfer_guid  = (int) elgg_extract('guid'       , $vars, 0);         $display       .= '$transfer_guid='.$transfer_guid.'<br>';
$section        = elgg_extract('section'          , $vars, 'main');    $display       .= '$section='.$section.'<br>';
$snippet        = elgg_extract('snippet'          , $vars);
$aspect         = elgg_extract('aspect'           , $vars);            $display       .= '$aspect='.$aspect.'<br>';
$space          = elgg_extract('space'            , $vars);            $display       .= '$space= '.$space.'<br>$selected= '.$vars['selected'].'<br>';
$disable_save   = elgg_extract('disable_save'     , $vars, false);
$context        = elgg_extract('context'          , $vars);
$asset          = elgg_extract('asset'            , $vars);
$container_guid = elgg_extract('container_guid'   , $vars);
$referrer       = elgg_extract('referrer'         , $vars);            $display       .= '$referrer='.$referrer.'<br>';
$shelf          = elgg_extract('shelf'            , $vars);
$n              = elgg_extract('n'                , $vars, 1);
$boqx_cid       = elgg_extract('boqx_cid'         , $vars, false);
//@TASK: Replace [transfer] with [$boqx_cid] 
$parent_cid     = elgg_extract('parent_cid'       , $vars, quebx_new_cid ());
$cid            = elgg_extract('cid'              , $vars, quebx_new_cid ());
$qid            = elgg_extract('qid'              , $vars, $cid);
$qid_n          = elgg_extract('qid_n'            , $vars, $qid.'_'.$n);
$presentation   = elgg_extract('presentation'     , $vars, 'full');                    $display       .= '$presentation='.$presentation.'<br>';
$perspective    = elgg_extract('perspective'      , $vars, 'edit');                    $display       .= '$perspective='.$perspective.'<br>';
$action         = elgg_extract('action'           , $vars, $perspective);              $display       .= '$action='.$action.'<br>';

$transfer_vars  = $vars;
$subtype        = 'transfer';
$exists         = true;
$now            = new DateTime();
$now->setTimezone(new DateTimeZone('America/Chicago'));

if (elgg_entity_exists($transfer_guid)){
     $entity  = get_entity($transfer_guid);
     $subtype = $entity->getSubtype();}
else $exists  = false;
if (elgg_entity_exists($container_guid))
     $container = get_entity($container_guid);

$transfer_vars['subtype']      = $subtype;
$transfer_vars['presentation'] = $presentation;
$transfer_vars['transfer_guid']= $transfer_guid;
$transfer_vars['exists']       = $exists;
$transfer_vars['entity']       = $entity;
echo "<!--perspective=$perspective, section=$section, snippet=$snippet-->";
Switch ($perspective){
/****************************************
 * $perspective = 'add'                      *****************************************************************************
 ****************************************/
    case 'add':
    switch ($section){
        /****************************************
 *add********** $section = 'partials'       *****************************************************************
        ****************************************/
        case 'partials':
            switch ($snippet){
        /****************************************
 *add********** $section = 'partials' $snippet='new_receipt *****************************************************************
        ****************************************/
                case 'new_receipt':
                    unset($view_summary, $hidden_fields);
            		$delete_button = "<label class=remove-progress-marker>". 
            		           	     elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
            		              	 "</label>";
            		$delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
            		$expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'=> 'toggle_marker', 'parent_cid'=>$parent_cid, 'data-cid'=>$cid, 'data-qid'=>$qid, 'tabindex'=> '-1',]);
            		$story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
            		$preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
            		$form         = 'forms/experiences/edit';
            		if ($show_view_summary){
            			$view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
            		}
            		$hidden_fields= elgg_view('input/hidden', ['name'=>"jot[$parent_cid][aspect]", 'value'=>'transfer']);
            		$edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
            		                                        'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
            		                    			    	'content'=>$view_summary
            		                                       . elgg_view($form,['section'=>'things_bundle', 'action'=>'add', 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
            		$story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
            		                                          'options'=> ['data-parent-cid'=>$parent_cid, 'data-cid'=>$cid, 'data-qid'=>$qid],
            		                                          'content'=>$hidden_fields.$edit_details]);
            			            		            		            	
            		$form_body = str_replace('<<cid>>', $cid, $story_model);                                   $display.= 'form_elements 90 new_receipt>$cid: '.$cid.'<br>form_elements  90 new_receipt>$service_cid: '.$service_cid.'<br>';
                    break;
            }
            break;
        /****************************************
 *add********** $section = 'things_boqx'  *****************************************************************
        ****************************************/
            case 'things_boqx':
/*                $form_body = elgg_view('forms/experiences/edit',
                                ['action'      => 'add',
                                'section'     => 'boqx_contents',
                                'parent_cid'  => quebx_new_cid(),
                                ]);*/
                $hidden[] =['name'=>"jot[subtype]",
                            'value' => 'boqx'];
                $hidden[] =['name'=>"jot[aspect]",
                            'value' => 'transfer'];
                $hidden[] =['name'=>"jot[boqx]",
                            'value' => 'things'];
                $hidden[] =['name'=>"jot[cid]",
                            'value' => "$cid"];
                if (!empty($hidden)){                
                    foreach($hidden as $key=>$field){
                        $hidden_fields .= elgg_view('input/hidden', $field);}}
                $expander = elgg_view("output/url", [
                    'text'    => '',
                    'class'   => 'expander undraggable',
                    'id'      => 'toggle_marker',
                    'data-cid'=> $cid,
                    'tabindex'=> '-1',]);
                $maximize_button = "<a type='button' class='autosaves maximize hoverable' id='story_maximize_$cid' tabindex='-1' title='Switch to a full view'></a>";
                $view_id = '<<view_id>>';
                $url = elgg_get_site_url().'jot';
/*                $marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[$parent_cid][$cid][title]' placeholder='Give this effort a name'></textarea>";
                $marker_date_picker = elgg_view('input/date', ['name'  => "jot[$parent_cid][$cid][moment]", 'style'=>'width:75px; height: 20px;']);
                $marker_type_selector = elgg_view_field(['#type'    =>'select',
                    'name'    =>"jot[$parent_cid][$cid][type]",
                    'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
                ]);
                $owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
                $marker_work_order_no  = elgg_view_field(['#type'   => 'text',
                    'name'    => "jot[$parent_cid][$cid][wo_no]"]);
                foreach(explode(' ', $owner->name) as $name){$initials .= $name[0];}
                $marker_participant_link     = elgg_view('output/div',['class'=>'dropdown',
                    'content' =>elgg_view('output/url',['class'=>'selection',
                        'href' => "profile/{$owner->username}",
                        'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
                            elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>$initials])])])]);
                $add_story_button = elgg_view('output/url', array(
                    'title'    => 'Add service effort',
                    'data-element' => 'new_service_effort',
                    'data-cid'     => $cid,
                    'class'    => 'trigger-element add_marker',
                    'tabindex' => '-1'
                ));
*/                Switch ($snippet){
                    /****************************************
*add********** $section = 'things_boqx' $snippet = 'contents'  *****************************************************************
                     ****************************************/
                    case 'contents':
                        $parent_cid = $boqx_cid;                                                              $display .= '143 boqx_cid = '.$boqx_cid.'<br>143 $cid = '.$cid.'<br>143 $service_cid = '.$service_cid.'<br>';
                                                                                                       register_error($display);
                        unset($hidden, $hidden_fields);
/*                        $hidden[] =['name'=>"jot[cid]",
                                    'value' => "$cid"];
*/                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                         $boqx_contents_add = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'section'           => $section,
                            'snippet'           =>'contents_add',
                            'show_view_summary' => false,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'boqx_cid'          => $boqx_cid]);
                         $boqx_contents_show = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'section'           => $section,
                            'snippet'           =>'contents_show',
                            'show_view_summary' => false,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'boqx_cid'          => $boqx_cid]);
                         $boqx_contents_edit = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'section'           => $section,
                            'snippet'           =>'contents_edit',
                           'show_view_summary'  => false,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'boqx_cid'          => $boqx_cid]);
                         break;
                    case 'contents_add':
                        $form_body = "
                             <div tabindex='0' class='AddSubresourceButton___S1LFUcMd' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
                                 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
                                 <span class='AddSubresourceButton__message___2vsNCBXi'>Q: New Things</span>
                             </div>";
                        break;
                    case 'contents_show':
                        $form_body = "
                             <div class='EffortShow_haqOwGZY TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid=$cid style='display:none' draggable='true'>
                                 <span class='TaskShow__title___O4DM7q tracker_markup section-add-boqx_contents-marker'></span>
                                 <nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
                                     <button class='IconButton___4wjSqnXU IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
                                     $delete
                                     </button>
                                 </nav>
                                 <span class='TaskShow__description___qpuz67f tracker_markup'></span>
                                 <span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
                             </div>";
                        break;
                    case 'contents_edit':
                        $boqx_contents = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'section'           => $section,
                            'snippet'           =>'new_receipt',
                            'show_view_summary' => false,
                            'boqx_cid'          => $boqx_cid,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'service_cid'       => $service_cid]);   
                        $form_body = "
                             <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-parent-cid='$parent_cid' data-cid=$cid>
                                $boqx_contents
                             </div>";
                        break;
                /****************************************
 *add********** $section = 'things_boqx' $snippet = 'service_item'  *****************************************************************
                ****************************************/
/*                    case 'service_item':                                                                      $display.= '215 $cid: '.$cid.'<br>';
                    case 'receipt_item':
                        unset($hidden, $hidden_fields);
                        $hidden["jot[$parent_cid][$cid][$n][aspect]"] = 'service item';
                        if (!empty($hidden)){
                            foreach($hidden as $field=>$value){
                                $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
//                                $element = 'service_item';
                        $element = 'receipt_item';
                        $delete = elgg_view('output/url', ['title'=>'remove service item',
                            //'class'=>'remove-service-item',
                            'class'=>'remove-receipt-item',
                            'data-element'=>$element,
                            'data-qid' => $qid_n,
                            'style'=> 'cursor:pointer',
                            'text' => elgg_view_icon('delete-alt'),]);
                        $action_button = elgg_view('input/button', [
                            'class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
                            'data-aid'   => 'delete',
                            'aria-label' => 'Delete',
                            'data-cid'   => $cid]);
                        $set_properties_button = elgg_view('output/url', [
                            'data-qboqx-dropdown'    => '#'.$cid.'_'.$n,
                            'data-qid'            => $qid_n,
                            'data-horizontal-offset'=>"-15",
                            'text'                => elgg_view_icon('settings-alt'),
                            'title'               => 'Detailed description',
                        ]);
                        $form_body .= $hidden_fields;
                        $form_body .="
                            <div class='rTableRow $element ui-sortable-handle TaskEdit___1Xmiy6lz' data-qid=$qid_n data-guid=$guid>
                                <div class='rTableCell'>$delete</div>
                                <div class='rTableCell'>".elgg_view('input/hidden', ['name' => "jot[$qid_n][new_line]"]).
                                elgg_view('input/number', ['name' => "jot[$qid_n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1'])."
                                </div>
                                <div class='rTableCell'>$set_properties_button ".elgg_view('input/text', [
                                    'name'      => "jot[$qid_n][title]",
                                    'class'     => 'rTableform90',
                                    'id'        => 'line_item_input',
                                    'data-name' => 'title',
                                ])."
    				            </div>
    							<div class='rTableCell'>".elgg_view('input/checkbox', [
    							    'name'      => "jot[$qid_n][taxable]",
    							    'value'     => 1,
    							    'data-qid'  => $qid_n,
    							    'data-name' => 'taxable',
    							    'default'   => false,
    							])."
                                </div>
    							<div class='rTableCell'>".elgg_view('input/text', [
    							    'name'      => "jot[$qid_n][cost]",
    							    'class'     => 'last_line_item',
    							    'data-qid'  => $qid_n,
    							    'data-name' => 'cost',
    							    'class'     => 'nString',
    							])."
    							</div>
    							<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total_raw line_total_raw'></span></div>
    						</div>";
    						break;
*/				/****************************************
*add********** $section = 'things_boqx' $snippet = 'new_receipt'  *****************************************************************
				 ****************************************/
				    case 'new_receipt':
                        unset($hidden, $hidden_fields);
                        $hidden[] =['name'  => "jot[$cid][subtype]",
                                    'value' => 'boqx'];
                        $hidden[] =['name'  => "jot[$cid][aspect]",
                                    'value' => 'transfer'];
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                        $show_view_summary = elgg_extract('show_view_summary', $vars, true);
                        $delete_button = "<label class=remove-progress-marker>".
                            elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
                            "</label>";
                            $delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
                            $expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'=> 'toggle_marker', 'data-cid'=>$cid, 'data-qid'=>$qid, 'tabindex'=> '-1',]);
                            $story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
                            $preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
                            $form         = 'forms/transfers/edit';
                            if ($show_view_summary){
                                $view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
                            }
                            $edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
                                'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                'content'=>$view_summary
                                . elgg_view($form,['section'=>'things_bundle', 'perspective'=>'add', 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
                            $story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
                                                                      'options'=> ['parent_cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                                                      'content'=>$hidden_fields.$edit_details]);
                            
                            $form_body = str_replace('<<cid>>', $cid, $story_model);
                            break;
				/****************************************
*add********** $section = 'things_boqx' $snippet = default  *****************************************************************
				 ****************************************/
                    default:
                        $things_boqx = elgg_view('forms/transfers/edit',
                           ['section'     => 'things_boqx',
                            'perspective' => $perspective,
                            'snippet'     => 'contents',
//                            'parent_cid'  => $parent_cid,
                            'n'           => $n,
                            'cid'         => $cid,
                      //initialize $boqx_cid
                            'boqx_cid'    => $cid,
//                            'service_cid' => $service_cid,
                            'qid'         => $qid]);                                        $display .= '315 parent_cid = '.$parent_cid.'<br>315 $cid = '.$cid.'<br>';
                                                                                            register_error($display);
                        break;
                }
                
                break;
				/****************************************
*add********** $section = 'things_bundle'                *****************************************************************
				 ****************************************/
           case 'things_bundle':                                                             $display .= '320 things_bundle>$cid: '.$cid.'<br>588 things_bundle>$service_cid: '.$service_cid.'<br>';
               unset($form_body, $disabled, $hidden);
               $disabled = 'disabled';
               $loose_things_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $action,
                           'section'        => 'boqx_contents_loose_things',
//                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
               $receipts_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $action,
                           'section'        => 'boqx_contents_receipt',
//                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
                    
            	$delete_button = "<label class=remove-card>". 
                            	    elgg_view('output/url', array(
                            	        'title'    =>'remove effort card',
                            	        'class'    =>'remove-card',
                            	        'text'     => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$maximize_button = "<a type='button' class='autosaves maximize hoverable' id='story_maximize_$cid' tabindex='-1' title='Switch to a full view'></a>";
                $delete = elgg_view("output/span", ["class"=>"remove-card", "content"=>$delete_button]);
            	
            	$add_effort = "<button data-aid='addEffortButton' class='ThingsBundle__submit___q0kFhFBf autosaves button std egg' type='submit' tabindex='-1' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid'>Add</button>";
            	$cancel_effort = "<button class='autosaves cancel clear' type='reset' id='epic_submit_cancel_$cid' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid' tabindex='-1'>Cancel</button>";
           	
            	$url = elgg_get_site_url().'jot';
            	$marker_title         = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' style='margin: 8px;' name='jot[$cid][title]' placeholder='Boqx name'></textarea>";
            	$marker_date_picker   = elgg_view('input/date', ['name'  => "jot[$cid][moment]", 'placeholder' => $now->format('Y-m-d'), 'value' => $now->format('Y-m-d'), 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[$cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$marker_state_selector = elgg_view_field(['#type'   =>'select',
            			                                  'name'   =>"jot[$cid][state]",
            			                                  'options_values' =>['ordered'=>'Ordered','delivered'=>'Delivered','rejected'=>'Rejected','accepted'=>'Accepted']
            			                                 ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[$cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',]);
            	
            	break;
        
               /****************************************
*add********** $section = 'boqx_contents'                *****************************************************************
               ****************************************/
             case 'boqx_contents':
				$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
							'data-cid'=> $cid,
                            'tabindex'=> '-1',]);
				$view_id = '<<view_id>>';
            	$url = elgg_get_site_url().'jot';
            	$boqx_contents = elgg_view('forms/transfers/edit',[  'action'            => $action,
            	                                                    'section'           => 'partials',
            	                                                    'snippet'           =>'new_receipt',
		            											    'show_view_summary' => false,
            														'parent_cid'        => $parent_cid,
		            			                                    'cid'               => $cid,
            			                                            'service_cid'       => $service_cid,
		            			                                    'qid'               => $qid,]);
            	$marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[$parent_cid][$cid][title]' placeholder='Give this transfer a name'></textarea>";
            	$marker_date_picker = elgg_view('input/date', ['name'  => "jot[$parent_cid][$cid][moment]", 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[$parent_cid][$cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[$parent_cid][$cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$add_story_button = elgg_view('output/url', array(
	                                        'title'    => 'Add service effort',
	                		                'data-element' => 'new_service_effort',
            								'data-qid'     => $qid,
            								'data-cid'     => $cid,
	                    				    //'text'     => '+',
	                    					//'href'     => '#',
	                    					//'class'    => 'add-progress-marker addButton___3-z3g3BH',
	                    					'class'    => 'trigger-element add_marker',
	                                        'tabindex' => '-1' 
	                    					));
				Switch ($snippet){
				    case 'items':
						$service_item_header_selector = elgg_view('output/span', ['content'=>
					    								elgg_view('output/url', [
									    				    'text'          => '+',
									    					'href'          => '#',
															'class'         => 'elgg-button-submit-element new-item',
															'data-element'  => 'new_receipt_item',
									    					'data-qid'      => $qid,
									    					'data-guid'     => $guid,
									    					'data-cid'      => $cid,
									    					'data-rows'     => 0,
									    					'data-last-row' => 0,
                                                            'data-presence' => 'panel'
									    					]), 
					    								'class'=>'effort-input']);
					    $service_item_header_qty   = 'Qty';
					    $service_item_header_item  = 'Item';
					    $service_item_header_tax   = 'tax';
					    $service_item_header_cost  = 'Cost';
					    $service_item_header_total = 'Total';
					    $delete_button = elgg_view('output/url', array(
		                            	        'title'=>'remove remedy effort',
		                            	        'text' => elgg_view_icon('delete-alt'),
		                            	    ));
		            	$delete = elgg_view("output/span", ["content"=>$delete_button]);
		            	$marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[$parent_cid][$cid][title]' placeholder='Service name'></textarea>";
		            	$marker_description = "<textarea name='jot[$parent_cid][$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
		                $form_body = "          
						    <div class='rTable line_items service-line-items'>
								<div class='rTableBody'>
									<div id='sortable'>
										<div class='rTableRow pin'>
							                <div class='rTableCell'>$service_item_header_selector</div>
							    			<div class='rTableHead'>$service_item_header_qty</div>
							    			<div class='rTableHead'>$service_item_header_item</div>
							    			<div class='rTableHead'>$service_item_header_tax</div>
							    			<div class='rTableHead'>$service_item_header_cost</div>
							    			<div class='rTableHead'>$service_item_header_total</div>
							    		</div>
							    		<div id={$cid}_new_line_items class='new_line_items'></div>
							    	</div>
						    	</div>
						    </div>
				    		<div id={$cid}_line_item_property_cards></div>";
				        break;
/*					case 'marker':                                                                                             $display .= '434 boqx_contents>marker $service_cid: '.$service_cid.'<br>';
            			unset($hidden, $hidden_fields);
            			$line_items_header = elgg_view('forms/experiences/edit',['section'     => 'boqx_contents', 
                			                                                     'action'      => $action, 
                			                                                     'snippet'     => 'items', 
                			                                                     'parent_cid'  => $parent_cid, 
                			                                                     'n'           => $n, 
                			                                                     'cid'         => $cid, 
                			                                                     'service_cid' => $service_cid, 
                			                                                     'qid'         => $qid, 
                			                                                     'guid'        => $guid]);
	            	    $boqx_contents_add ="
								<div tabindex='0' class='AddSubresourceButton___S1LFUcMd' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
									 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
									 <span class='AddSubresourceButton__message___2vsNCBXi'>Add an effort</span>
								</div>";
						$boqx_contents_show = "
							<div class='EffortShow_haqOwGZY TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid=$cid style='display:none' draggable='true'>
								<span class='TaskShow__title___O4DM7q tracker_markup section-add-boqx_contents-marker'></span>
								<nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
									<button class='IconButton___4wjSqnXU IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
										$delete
									</button>
								</nav>
								<span class='TaskShow__description___qpuz67f tracker_markup'></span>
								<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
							</div>";
						$boqx_contents_edit = "
	                        <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-parent-cid='$parent_cid' data-cid=$cid>
					    		$boqx_contents
							</div>";
						break;
*//*					case 'service_item':                                                                      $display.= '3990 $cid: '.$cid.'<br>';
					case 'receipt_item':
            			unset($hidden, $hidden_fields);
		                $hidden["jot[$parent_cid][$cid][$n][aspect]"] = 'service item';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						//$element = 'service_item';
						$element = 'receipt_item';
						$delete = elgg_view('output/url', ['title'=>'remove service item',
//													       'class'=>'remove-service-item',
						                                   'class'=>'remove-receipt-item',
													       'data-element'=>$element,
														   'data-qid' => $qid_n,
													       'style'=> 'cursor:pointer',
													       'text' => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$set_properties_button = elgg_view('output/url', ['data-qboqx-dropdown'    => '#'.$cid.'_'.$n,
						                                                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
							            	    $form_body .= $hidden_fields;
						$form_body .="
								<div class='rTableRow $element ui-sortable-handle TaskEdit___1Xmiy6lz' data-qid=$qid_n data-guid=$guid>
									<div class='rTableCell'>$delete</div>
									<div class='rTableCell'>".elgg_view('input/hidden', ['name' => "jot[$qid_n][new_line]"]).
									                          elgg_view('input/number', ['name' => "jot[$qid_n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1'])."
                                    </div>
									<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', [
																'name'      => "jot[$qid_n][title]",
											                    'class'     => 'rTableform90',
									                            'id'        => 'line_item_input',
											                    'data-name' => 'title',
															])."
						            </div>
									<div class='rTableCell'>".elgg_view('input/checkbox', [
																'name'      => "jot[$qid_n][taxable]",
																'value'     => 1,
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'taxable',
					        			                        'default'   => false,
															])."
                                    </div>
									<div class='rTableCell'>".elgg_view('input/text', [
																'name'      => "jot[$qid_n][cost]",
										 						'class'     => 'last_line_item',
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'cost',
				    											'class'     => 'nString',
															])."
                                    </div>
									<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total_raw line_total_raw'></span></div>
				                </div>";
						break;
*//*					case 'new_receipt':
						$show_view_summary = elgg_extract('show_view_summary', $vars, true);
						$delete_button = "<label class=remove-progress-marker>". 
		           	                     	elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
						              	 "</label>";
						$delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
						$expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'=> 'toggle_marker', 'data-cid'=>$cid, 'data-qid'=>$qid, 'tabindex'=> '-1',]);
						$story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
						$preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
						$form         = 'forms/experiences/edit';
						if ($show_view_summary){
							$view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
						}
						$hidden_fields= elgg_view('input/hidden', ['name'=>"jot[$cid][aspect]", 'value'=>'effort']);
						$edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
						                                        'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
						                    			    	'content'=>$view_summary
						                    			    	. elgg_view($form,['section'=>'things_bundle', 'action'=>'add', 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
						$story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
                                    						      'options'=> ['parent_cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
						                                          'content'=>$hidden_fields.$edit_details]);
							            		            		            	
						$form_body = str_replace('<<cid>>', $cid, $story_model);
						break;
*/			        case 'loose_things':
                        unset($hidden);
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][subtype]",
                                    'value' => 'boqx'];
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][aspect]",
                                    'value' => 'loose things'];
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                        $view = 'forms/transfers/edit';
        				$first_line = elgg_view($view,[ 
									  'perspective' => 'add',
									  'section'     =>'boqx_contents', 
									  'snippet'     =>'single_thing',
									  'effort'      => $effort,      
									  'parent_cid'  => $parent_cid, 
									  'cid'         => $cid, 
									  'n'           => $n,]);
        				$first_line_properties = elgg_view($view,['section'=>'boqx_contents_receipt', 'perspective'=>'add', 'snippet'=>'receipt_item_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                		$loose_things_header_selector = elgg_view('output/span', [
                		                                          'content'=> elgg_view('output/url', [
    		                                                                            'text' => '+',
																 					    'href' => '#',
																					    'class' => 'elgg-button-submit-element new-item',
																					    'data-element'=> 'new_loose_thing',
																					    'data-qid'    => $cid,
                                            					    				    'data-cid'      => $cid,
                                            	    								    'data-parent-cid'=>$parent_cid,
                                            					    				    'data-rows'     => 1,
                                            					    				    'data-last-row' => 1,
                                                                                        'data-presence' => 'panel'
																					   ]), 
                        								          'class'=>'receipt-input']);
                    	$loose_things_header_recd_qty = 'Rec\'d';
                        $loose_things_header_qty   = 'Qty';
                        $loose_things_header_item  = 'Thing';
        				$line_items = "          
                		    <div class='rTable line_items loose-line-items'>
                                <div class='rTableBody'>
                                    $hidden_fields
                					<div id='sortable'>
                						<div class='rTableRow pin'>
                			                <div class='rTableCell'>$loose_things_header_selector</div>
                			    			<div class='rTableHead'>$loose_things_header_qty</div>
                			    			<div class='rTableHead'>$loose_things_header_item</div>
                			    		</div>
                                        $first_line
                			    		<div id={$cid}_new_line_items class='new_line_items'></div>
                			    	</div>
                		    	</div>
                		    </div>
                    		<div id={$cid}_line_item_property_cards>
                                $first_line_properties 
                            </div>";
                        break;
                    case 'single_thing':
             			unset($hidden, $hidden_fields);
		                $element = 'loose_item';
						$delete = elgg_view('output/url', ['title'=>'remove this thing',
						                                   'class'=>'remove-loose-thing',
													       'data-element'=>$element,
														   'data-qid' => $qid_n,
													       'style'=> 'cursor:pointer',
													       'text' => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$set_properties_button = elgg_view('output/url', ['data-jq-dropdown'    => '#'.$cid.'_'.$n,
						                                                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
						$form_body ="
								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
									<div class='rTableCell'>$delete</div>
									<div class='rTableCell'>".elgg_view('input/hidden',   ['name'=>"jot[$parent_cid][$cid][$n][aspect]", 'value' => 'thing','data-focus-id' => "Aspect--{$cid}"])
									                         .elgg_view('input/number',   ['name'=>"jot[$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$set_properties_button "
									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]", 'style'=>'width:95%', 'data-name'=>'title',])."</div>
				                </div>";
             		    
             		    break;
					default:
						$boqx_contents = elgg_view('forms/transfers/edit',[
	                                        'section'     => 'boqx_contents', 
                                            'action'      => $action, 
                                            'snippet'     => 'marker', 
                                            'parent_cid'  => $parent_cid, 
                                            'n'           => $n, 
                                            'cid'         => $cid, 
                                            'service_cid' => $service_cid, 
                                            'qid'         => $qid, 
                                            'guid'        => $guid]);
	                break;
				}
				/****************************************
*add********** $section = 'boqx_contents_receipt'         *****************************************************************
				 ****************************************/
             case 'boqx_contents_receipt':
             	switch ($snippet){
             		case 'marker1':
             			unset($hidden, $hidden_fields);
		                $unpack_icon = "<span title='Unpack all'>"
								           .elgg_view('input/checkbox', ['name'=>"jot[$parent_cid][$cid][$n][unpack]",'value'=>1,'class'=>'boqx-unpack closed','data-cid'=>$cid,'data-name'=>'unpack-all','default'=> false,])
						              ."</span>";
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][aspect]",
		                            'value' => 'receipt',
		                            'data-focus-id' => "Aspect--{$cid}"];
		                $hidden[] =['name'=>"jot[$parent_cid][$cid][fill_level]",
		                            'value' => 'empty',
		                            'data-focus-id' => "FillLevel--{$cid}"];
		                $title_input             = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][title]"             , 'class'=>'receipt-input'  , 'placeholder' => 'Receipt name', 'required'=>'']);
		                $merchant = elgg_view('output/span', ['content'=>elgg_view('input/grouppicker', ['name' => "jot[$parent_cid][$cid][merchant]",
                                                                                                         'limit'=> 1,
                                                                                                         'autocomplete'=>'on',
                                                                            				            ]),
                                                              'options' => ['data-focus-id'=> "MerchantAdd--$cid"],
                        		                              'class'=>'receipt-input',]);
                        $associate_label         = 'Sales Assoc.';
                        $asset_input             = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][asset]"             , 'class'=>'receipt-input']);
                        $cashier                 = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][cashier]"           , 'class'=>'receipt-input']);
                        $transaction_date_label  = 'Purchase Date';
                        $moment_label_receipt    = elgg_view('output/span' , ['content'=>'Date'                                                       , 'class'=>'receipt-input']);
                        $moment                  = elgg_view('input/date'  , ['name' => "jot[$parent_cid][$cid][moment]"            , 'class'=>'receipt-input'   , 'value' => strtotime("now")]);
                        $actor_label             = 'Buyer';
                        $purchased_by            = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][purchased_by]"      , 'class'=>'receipt-input']);
                        $order_no_label          = 'Order #';
                        $order_no                = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][order_no]"          , 'class'=>'receipt-input']);
                        $preorder_label          = 'Ordered';
                        $preorder_options        =                           ['name'=>"jot[$parent_cid][$cid][preorder_flag]"       , 'class'=>'preorder-flag'   ,'label_class'=>'receipt-input'];
                        $preorder_flag           = elgg_view('input/switchbox',$preorder_options);
                        $preorder_style          = 'visibility:hidden';
                        $delivery_date_label     = 'Scheduled date';
                        $delivery_date           = elgg_view('input/date'  , ['name'=>"jot[$parent_cid][$cid][delivery_date]"       , 'class'=>'receipt-input'   , 'style'=>'width:100px;']);
                        $purchase_order_no_label = 'PO #';
                        $purchase_order_no       = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][purchase_order_no]" , 'class'=>'receipt-input'   , 'style'=>'width:100px;']);
                        $invoice_no_label        = 'Invoice #';
                    	$invoice_no              = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][invoice_no]"        , 'class'=>'receipt-input']);
                        $document_no             = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][document_no]"       , 'class'=>'receipt-input']);
                        $transaction_no_label    = 'Receipt #';
                        $receipt_no              = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][receipt_no]"        , 'class'=>'receipt-input']);
//Note: $qid receives the value of $cid
                        $receipt_item_header_selector = elgg_view('output/span', ['content'=> elgg_view('output/url', ['text' => '+',
                    																			 					   'href' => '#',
                    																								   'class' => 'elgg-button-submit-element new-item',
                    																								   'data-element'=> 'new_receipt_item',
                    																								   'data-qid'    => $cid,
                                                                            					    				   'data-cid'      => $cid,
                                                                            	    								   'data-parent-cid'=>$parent_cid,
                                                                            					    				   'data-rows'     => 1,
                                                                            					    				   'data-last-row' => 1,
                                                                                                                       'data-presence' => 'panel'
                    																								  ]), 
                        								'class'=>'receipt-input']);
                    	$receipt_item_header_recd_qty = 'Rec\'d';
                        $receipt_item_header_qty   = 'Qty';
                        $receipt_item_header_item  = 'Item';
                        $receipt_item_header_tax   = 'tax';
                        $receipt_item_header_cost  = 'Cost';
                        $receipt_item_header_total = 'Total';
                        
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                	    $delete_button = elgg_view('output/url', array(
                                    	        'title'=>'remove progress marker',
                                    	        'text' => elgg_view_icon('delete-alt'),
                                    	    ));
                    	$delete = elgg_view("output/span", ["content"=>$delete_button]);
                    	$service_marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[$parent_cid][$cid][title]' placeholder='Receipt name'></textarea>";
                    	$service_marker_description = "<textarea name='jot[$parent_cid][$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
                    	
                		$shipping_cost  = elgg_view('input/text', ['name' => "jot[$parent_cid][$cid][shipping_cost]", 'data-qid'=>"$cid", 'data-name'=>'shipping_cost', 'data-focus-id'=>"ShippingCost--{$cid}", 'class'=> 'nString']);
                		$sales_tax      = elgg_view('input/text', ['name' => "jot[$parent_cid][$cid][sales_tax]", 'data-qid'=>"$cid", 'data-name'=>'sales_tax', 'data-focus-id'=>"SalesTax--{$cid}", 'class'=> 'nString']);
                		$sales_tax_rate = $transfer->sales_tax_rate;
                		$total          = $item_exists ? $receipt_item_total
                		                                 : money_format('%#10n', $transfer->total);
                		if (!empty($sales_tax_rate)){
                		    $sales_tax_rate_label = '('.number_format($sales_tax_rate*100, 0).'%)';
                		}
                		
                		$line_items_footer = "<div class='rTableRow pin'>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'>Subtotal</div>
                				<div class='rTableCell'><span id={$cid}_subtotal>$subtotal</span><span class='{$cid}_subtotal_raw subtotal_raw'>$transfer->subtotal</span></div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'>Shipping</div>
                				<div class='rTableCell'>$shipping_cost</div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'>Sales Tax <span class='{$cid}_sales_tax_rate'></span></div>
                				<div class='rTableCell'>$sales_tax</div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'></div>
                				<div class='rTableCell'>Total</div>
                				<div class='rTableCell'><span id={$cid}_total>$total</span><span class='{$cid}_total_raw total_raw'></span></div>
                			</div>";
//Note: $qid receives the value of $cid
                		$first_line = elgg_view('forms/transfers/edit',['section'=>'boqx_contents_receipt', 'perspective'=>'add', 'snippet'=>'receipt_item', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                		$first_line_properties = elgg_view('forms/transfers/edit',['section'=>'boqx_contents_receipt', 'perspective'=>'add', 'snippet'=>'receipt_item_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                		$preorder_input .= "<div class='rTable pre-order'>
                                         <div class='rTableBody'>
                							<div class='rTableRow'>
                								<div class='rTableCell'                                                                                             > {$preorder_flag}</div>
                								<div class='rTableCell'                                                                                             > {$preorder_label}</div>
                								<div class='rTableCell jot-preorder jot-delivery-date jot-delivery-date-label'         style = '$preorder_style'    > $delivery_date_label</div>
                								<div class='rTableCell jot-preorder jot-delivery-date jot-delivery-date-field'         style = '$preorder_style'    > {$delivery_date}</div>
                								<div class='rTableCell jot-preorder jot-purchase-order-no jot-purchase-order-no-label' style = '$preorder_style'    > $purchase_order_no_label</div>
                								<div class='rTableCell jot-preorder jot-purchase-order-no jot-purchase-order-no-field' style = '$preorder_style'    > {$purchase_order_no}</div>
                							</div>
                					 	 </div>
                					  </div>";
                        $line_items_header= "          
                		    <div class='rTable line_items receipt-line-items'>
                                <div class='rTableBody'>
                					<div id='sortable'>
                						<div class='rTableRow pin'>
                			                <div class='rTableCell'>$receipt_item_header_selector</div>
                			    			<div class='rTableCell'>$unpack_icon</div>
                			    			<div class='rTableHead'>$receipt_item_header_qty</div>
                			    			<div class='rTableHead'>$receipt_item_header_item</div>
                			    			<div class='rTableHead'>$receipt_item_header_tax</div>
                			    			<div class='rTableHead'>$receipt_item_header_cost</div>
                			    			<div class='rTableHead'>$receipt_item_header_total</div>
                			    		</div>
                                        $first_line
                			    		<div id={$cid}_new_line_items class='new_line_items'></div>
                                        $line_items_footer
                			    	</div>
                		    	</div>
                		    </div>
                    		<div id={$cid}_line_item_property_cards>
                                $first_line_properties 
                            </div>";
                        $form_header = "
                	    	<div class='rTable'>
                				<div class='rTableBody'>
                					<div class='rTableRow'>
                						<div class='rTableCell' style='width:14%'>Merchant</div>
                						<div class='rTableCell' style='width:86%;padding-right:0'>$merchant</div>
                					</div>
                				</div>
                			</div>";
                		$form_header .= "<div id='header_2' class='rTable'>
                				<div class='rTableBody'>
                					<div class='rTableRow'>
                						<div class='rTableCell'>Associate</div>
                						<div class='rTableCell'>$cashier</div>
                						<div class='rTableCell'>$moment_label_receipt</div>
                						<div class='rTableCell'>$moment</div>
                						<div class='rTableCell'></div>
                						<div class='rTableCell'></div>
                					</div>
                					<div class='rTableRow'>
                						<div class='rTableCell'>Buyer</div>
                						<div class='rTableCell'>$purchased_by</div>
                						<div class='rTableCell' style='white-space:nowrap;'>Order #</div>
                						<div class='rTableCell'>$order_no</div>
                						<div class='rTableCell' style='white-space:nowrap;'>Invoice #</div>
                						<div class='rTableCell'>$invoice_no</div>
                					</div>
                					<div class='rTableRow'>
                						<div class='rTableCell'></div>
                						<div class='rTableCell'></div>
                						<div class='rTableCell' style='white-space:nowrap;'>Doc #</div>
                						<div class='rTableCell'>$document_no</div>
                						<div class='rTableCell' style='white-space:nowrap;'>Receipt #</div>
                						<div class='rTableCell'>$receipt_no</div>
                					</div>
                				</div>
                		</div>";
                        $boqx_contents_receipt_add = "<div tabindex='0' class='AddSubresourceButton___2PetQjcb' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
                				 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
                				 <span class='AddSubresourceButton__message___2vsNCBXi'>Add a receipt</span>
                			</div>";
                        $boqx_contents_receipt_show = "<div class='TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid='$cid' style='display:none' draggable='true'>
            					<span class='TaskShow__title___O4DM7q tracker_markup section-add-boqx_contents_receipt-marker1'></span>
                				<nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
                					<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
                						$delete
                					</button>
                				</nav>
            					<span class='TaskShow__description___qpuz67f tracker_markup'></span>
            					<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
                			</div>";
                        $boqx_contents_receipt_edit = "
                            <div class='TaskEdit___1Xmiy6lz' style='display:none' data-aid='TaskEdit' data-cid='$cid'>
                			    $hidden_fields
                	    		<a class='autosaves collapser-receipt-item' id='story_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
                				<section class='TaskEdit__descriptionContainer___3NOvIiZo'>
                                    <fieldset class='name'>
                                    	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
                							<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 400px;'>
                            					<div class='AutosizeTextarea__container___31scfkZp' style>
                            					    $service_marker_title
                            					</div>
                            				</div>
                							<button data-aid='addReceiptButton'  type='submit'  class='ReceiptAdd__submit___lS0kknw9 std egg' style='margin: 3px 3px 0 15px;order: 2;' data-cid='$cid' data-parent_cid='$parent_cid'>Add
                							</button>
                            			</div>
                            		</fieldset>
                                    <section class='receipt-header'>
                                        $form_header
                                    </section>
                                    <section class='receipt-items'>
                                     	<div>
                                     		<h5 style='padding:10px 0 0 10px;'>Received Items</h5>
                                     		$line_items_header
                                     	</div>
                                    </section>
                				</section>
                			</div>";
//Note: $qid receives the value of $cid
/*                		$form .="<div id='$qid' class='ServiceEffort__26XCaBQk boqx_contents_receipt-marker1' data-qid='$cid' data-parent-cid='$parent_cid' data-cid='$cid' data-guid='$guid'>
                			$boqx_contents_receipt_add
                			$boqx_contents_receipt_show
                			$boqx_contents_receipt_edit
                		</div>";
*/             			break;
             		case 'service_item':
             		case 'receipt_item':
             			unset($hidden, $hidden_fields);
		                $element = 'receipt_item';
						$delete = elgg_view('output/url', ['title'=>'remove receipt item',
						                                   'class'=>'remove-receipt-item',
													       'data-element'=>$element,
														   'data-qid' => $qid_n,
													       'style'=> 'cursor:pointer',
													       'text' => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$unpack_icon   = "<span title='Unpack this'>"
								            .elgg_view('input/checkbox', ['name'=>"jot[$parent_cid][$cid][$n][unpack]",'value'=>1, 'class'=>'boqx-unpack closed','data-cid'=>$cid,'data-name'=>'unpack-this','default'=> false,])
						                ."</span>";
						$set_properties_button = elgg_view('output/url', ['data-jq-dropdown'    => '#'.$cid.'_'.$n,
						                                                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
						$receipt_item_row ="
								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
									<div class='rTableCell'>$delete</div>
									<div class='rTableCell'>$unpack_icon</div>
									<div class='rTableCell'>".elgg_view('input/hidden',   ['name'=>"jot[$parent_cid][$cid][$n][aspect]", 'value' => 'receipt item','data-focus-id' => "Aspect--{$cid}"])
									                         .elgg_view('input/number',   ['name'=>"jot[$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$set_properties_button "
									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]",'class'=>'rTableform90','id'=>'line_item_input','data-name'=>'title',])."</div>
									<div class='rTableCell'>".elgg_view('input/checkbox', ['name'=>"jot[$parent_cid][$cid][$n][taxable]",'value'=>1,'data-qid'=>$qid_n,'data-name'=>'taxable','default'=> false,])."</div>
									<div class='rTableCell'>".elgg_view('input/text', array(
																'name'      => "jot[$parent_cid][$cid][$n][cost]",
										 						'class'     => 'last_line_item',
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'cost',
				    											'class'     => 'nString',
															))."</div>
									<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total_raw line_total_raw'></span></div>
				                </div>";
             			break;
             		case 'receipt_item_properties':
             		    break;
             	}
             	break;
        }
    break;
/****************************************
 * $perspective = 'edit'                      *****************************************************************************
 ****************************************/
    case 'edit':
    default:
        Switch ($aspect){
        	case 'receipt':
        	case 'receive':
        		if ($entity->status == 'Received'){$transfer_vars['show_receive'] = false;}
        		else                             {$transfer_vars['show_receive'] = true;}                         $display .='42 $transfer_vars[show_receive] = '.$transfer_vars['show_receive'].'<br>'; 
        		$form_body = elgg_view("forms/transfers/elements/receipt", $transfer_vars);                       $display .= '44 $entity->status: '.$entity->status.'<br>';
            break;
        	case 'return':
            	$content = elgg_view('output/div',['content'=> elgg_view("forms/transfers/elements/return", $transfer_vars),
            	                                     'options'=> ['id'=>$qid.'-body']]);
                $transfer_vars['show_title']=true;
                if ($presentation == 'qbox'){
                	$transfer_vars['content']      = $content;
                	$transfer_vars['disable_save'] = $disable_save;
        			$form_body = elgg_view('jot/display/qbox',$transfer_vars);
                }
                else {
                	$form_body = $content;	
                }
            break;
        	case 'replace':
        		
        		break;
        /*    case 'ownership':
        		$form_body = elgg_view("forms/transfers/elements/ownership", $transfer_vars);
            break;
            case 'donate':
            	$form_body = elgg_view("forms/transfers/elements/donate", $transfer_vars);
            break;
            case 'loan':
            case 'sell':
            	$form_body = elgg_view('output/div',['content'=> $aspect,
            	                                      'options'=> ['id'=>$qid]]);
            break;
        */    case 'trash':
            	$transfer_vars['selected']=$aspect;
            	$content = elgg_view('output/div',['content'=> elgg_view('forms/transfers/elements/transfer', $transfer_vars),
            	                                   'options'=> ['id'=>$qid.'-body']]);
        
                if ($presentation == 'qbox'){
                	$transfer_vars['content']      = $content;
                	$transfer_vars['disable_save'] = $disable_save;
                	if ($context == 'widgets'){$transfer_vars['position'] = 'relative';}
        			$form_body = elgg_view_layout('qbox',$transfer_vars);
                }
                else {
                	$form_body = $content;	
                }                                                                    $display .= '86 $entity->status: '.$entity->status.'<br>';
            	break;
            default:
            	$transfer_vars['space']    = $space;
            	$transfer_vars['aspect']   = $aspect;
            	if ($aspect != 'transfer'){$transfer_vars['selected'] = $aspect;} // Sets the tab to the value of the selection
                $content = elgg_view('output/div',['content'=> elgg_view("forms/transfers/elements/transfer", $transfer_vars),
            	                                   'options'=> ['id'=>$qid]]);
        		$transfer_vars['show_title']        = true;
                if ($presentation == 'qbox'){
                	$transfer_vars['content']      = $content;
                	$transfer_vars['disable_save'] = $disable_save;
                	if ($context == 'widgets'){$transfer_vars['position'] = 'relative';}
        			$form_body = elgg_view_layout('qbox',$transfer_vars);
                }
                else {
                	$form_body = $content;	
                }
                
            	break;
        }
}

Switch ($section){
/****************************************
$section = 'things_boqx'              *****************************************************************
****************************************/
    case 'things_boqx':
        Switch ($snippet){
            case 'contents':                                                                                        $display .= '1055 boqx_contents>contents $service_cid: '.$service_cid.'<br>';
            $form_body .= "<div class='Effort__CPiu2C5N section-things_boqx-contents' data-qid='$qid' data-parent-cid='$parent_cid' data-cid='$cid' data-guid='$guid' data-aid='$perspective'>
                              $hidden_fields
                              $boqx_contents_add
                              $boqx_contents_show
                              $boqx_contents_edit
                          </div>";
                break;
            case 'service_item':
            case 'receipt_item':                                                                                  $display.= '1063 $cid: '.$cid.'<br>';
            
                break;
            case 'list_efforts':
            case 'view_effort':
                $form_body .= str_replace('__cid__', $cid, $story_model);
                break;
            default:
                if (!$things_boqx)
                    break;
                $form_body .= "<div data-aid='Efforts' class='things-boqx-default' action ='$perspective'>
                                    $hidden_fields
                                    <span class='efforts-eggs' data-aid='effortCounts' data-cid='$cid' data-qid='$qid'></span>
                                    $things_boqx
                               </div>";                                                                          $display .= '1076 $things_boqx-default ... <br>1076 $cid: '.$cid.'<br>1076 $service_cid: '.$service_cid;
                break;
        }                                                                                         //register_error($display);
        break;
        
/****************************************
$section = 'things_bundle'              *****************************************************************
****************************************/
            case 'things_bundle':
			    switch($snippet){
            		case 'marker':
            			if ($disabled      == 'disabled'){$disabled_label      = ' (disabled)';}
            			if ($disabled_view == 'disabled'){$disabled_view_label = ' (disabled)';}
            			$form_body .= "	   	<div class='$perspective details things_bundle-marker expanded' data-cid='$cid' data-guid='$guid' data-qid='$qid' action='$action'>
		    					                   <section class='edit' data-aid='StoryDetailsEdit' tabindex='-1'>
		                                              <section class='model_details'>
		                                                  <section class='story_or_epic_header'>
		                                                    <a class='autosaves collapser-effort' id='effort_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
		                            					    <fieldset class='name'>
		                            					        <div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='display:flex'>
		                            					            <div class='AutosizeTextarea__container___31scfkZp'>
		                            					               $marker_title
		                            					            </div>
		                            					        </div>
		                            					     </fieldset>
		                            					     $maximize_button
		                            					  </section>
                                                        <section class='model_instructions'>
                                                                <div class='bounding_box'>
                                                                     <div class = 'instructions'>
                                                                        Add things acquired from different places.  Bundle receipts from separate transactions and Q: everything in one gesture.
                                                                    </div>
                                                                </div>
                                                            </section>
		                            					  <aside>
		                                                    <div class='wrapper'>
		                                                      <nav class='edit'>
		                                                          <section class='controls'>
                                                                      <div class='persistence use_click_to_copy' style='order:1'>
		                                                                 $cancel_effort
		                                                                 $add_effort
		                                                              </div>
		                                                              <div class='actions'>
		                                                                  <div class='bubble'></div>
		                                                                  <button type='button' id='story_copy_link_$cid' title='Copy this effort&#39;s link to your clipboard".$disabled_label."' data-clipboard-text='$url/view/$id_value' class='autosaves clipboard_button hoverable link left_endcap' tabindex='-1' $disabled></button>
		                                                                  <div class='button_with_field'>
			                                                                  <button type='button' id ='story_copy_id_$cid' title='Copy this effort&#39;s ID to your clipboard".$disabled_label."' data-clipboard-text='$id_value' class='autosaves clipboard_button hoverable id use_click_to_copy' tabindex='-1' $disabled></button>
			                                                                  <input type='text' id='story_copy_id_value_$cid' readonly='' class='autosaves id text_value' value='$id_value' tabindex='-1'>
		                                                                </div>
		                                                                <button type='button' id='receipt_import_button_$cid' title='Import receipt (disabled)' class='autosaves import_receipt hoverable left_endcap' tabindex='-1' disabled></button>
		                                                                <button type='button' id='story_clone_button_$cid' title='Clone this effort".$disabled_view_label."' class='autosaves clone_story hoverable left_endcap' tabindex='-1' $disabled $disabled_view></button>
		                                                                <button type='button' id='story_history_button_$cid' title='View the history of this effort".$disabled_view_label."' class='autosaves history hoverable capped' tabindex='-1' $disabled $disabled_view></button>
		                                                                <button type='button' id='story_delete_button_$cid' title='Delete this effort".$disabled_view_label."' class='autosaves delete hoverable right_endcap remove-card' data-qid=$qid tabindex='-1'$disabled $disabled_view></button>
		                                                              </div>
		                                                            </section>
		                                                      </nav>
		                                                      <div class='story info_box' style='display:block'><!-- hidden -->
		                                                        <div class='info'>
			                                                        <div class='date row'>
			                                                          <em>Date</em>
			                                                          <div class='dropdown story_date'>
			                                                            <input aria-hidden='true' type='text' id='story_date_dropdown_{$cid}_honeypot' tabindex='-1' class='honeypot'>
			                                                            $marker_date_picker
			                                                          </div>
			                                                        </div>
			                                                       <div class='state row'>
			                                                          <em>State</em>
			                                                          <div class='dropdown story_current_state disabled'>
			                                                            <input aria-hidden='true' type='text' id='story_current_state_dropdown_{$cid}_honeypot' tabindex='-1' class='honeypot'>
			                                                            $marker_state_selector
			                                                        </div>
			                                                    </div>
		                                                        <div class='requester row'>
		                                                          <em>Receiving Group</em>
		                                                          <div class='dropdown story_org_id'>
		                                                            <input aria-hidden='true' type='text' id='story_scribe_id_dropdown_{$cid}_honeypot' tabindex='0' class='honeypot'>
		                                                            <a id='effort_org_id_dropdown_$cid' class='selection item_2936271' tabindex='-1'><span><div class='name'>??</div></span></a>
		                                                          <section>
		                                                            <div class='dropdown_menu search'>
		                                                                  <div class='search_item'><input aria-label='search' type='text' id='effort_org_id_dropdown_{$cid}_search' class='search'></div>
		                                                              <ul>
		                                                                  <li class='no_search_results hidden'>No results match.</li>
		                                                                  <li data-value='2936271' data-index='1' class='dropdown_item selected'><a class='item_2936271 ' id='2936271_effort_org_id_dropdown_$cid' href='#'><span><span class='dropdown_label'>??</span><span class='dropdown_description'>??</span></span></a></li>
		                                                              </ul>
		                                                            </div>
		                                                          </section>
		                                                        </div>
		                                                      </div>
		                                                        <div class='participant row'>
		                                                          <em>Receiver</em>
		                                                          <div class='story_participants'>
    		                                                          <input aria-hidden='true' type='text' id='story_participant_ids_{$cid}_honeypot' tabindex='0' class='honeypot'>
<!--    		                                                          <a id='add_participant_$cid' class='add_participant selected' tabindex='-1'></a> -->
    		                                                          $marker_participant_link
		                                                        </div>
		                                                  </div>
		                                                  <div class='mini attachments'></div>
		                                                </div>
		                                              </aside>
		                                        	</section>
		                                        	<section class='description full'>
		                                        	    <div data-reactroot='' data-aid='Description' class='Description___Dljkfzd5 things_bundle-marker'>
		                                        	        $loose_things_panel
		                                        	    </div>
		                                        	</section>
		                                        	<section class='description full'>
		                                        	    <div data-reactroot='' data-aid='Description' class='Description___Dljkfzd5 things_bundle-marker'>
		                                        	        $receipts_panel
		                                        	    </div>
		                                        	</section>
		                                        	<section class='media full'>
		                                        	    <div data-reactroot='' class='Activity___2ZLT4Ekd'>
		                                        	        <div class='GLOBAL__activity comment CommentEdit___3nWNXIac CommentEdit--new___3PcQfnGf' tabindex='-1' data-aid='comment-new'>
		                                        	        </div>
		                                        	     </div>
		                                        	 </section>
		    					                 </section>
		    					               </div>";
            			break;
            		case 'new_receipt':
            			$content   = elgg_view('forms/transfers/edit',[ 'action'            => $action,
                	                                                    'section'           => 'partials',
                	                                                    'snippet'           =>'new_receipt',
    		            											    'show_view_summary' => false,
    							    		                            'guid'              => $guid,
                														'parent_cid'        => $parent_cid,
    		            			                                    'cid'               => $cid,
                			                                            'service_cid'       => $service_cid,
    		            			                                    'qid'               => $qid,]);
            			$form_body .="<div class='TaskEdit___1Xmiy6lz things_bundle-default' data-qid='$qid' data-cid='$cid' data-guid='$guid'>
							<div tabindex='0' class='AddSubresourceButton___2PetQjcb' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
								 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
								 <span class='AddSubresourceButton__message___2vsNCBXi'>Add a remedy</span>
							</div>
							<div class='TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid=$cid style='display:none' draggable='true'>
									<span class='TaskShow__title___O4DM7q tracker_markup section-things_bundle'></span>
    								<nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
    									<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
    										$delete
    									</button>
    								</nav>
									<span class='TaskShow__description___qpuz67f tracker_markup'></span>
									<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
								</div>
							</div>
	                        <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-parent-cid='$parent_cid' data-cid=$cid>
	                        	$content
							</div>
						</div>";
            			break;
            		default:
            			unset($hidden, $hidden_fields);                                                          $display .= '6138 $efforts # '.count($efforts).'<br>';
            			//$hidden['jot[boqx]'] = 'effort';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
		                    $new_receipt     = elgg_view('forms/transfers/edit',
    		                    ['action'      => $perspective,
    		                     'section'     => 'boqx_contents',
    		                     //'snippet'=>'new_receipt',
    		                     'qid'         => $qid,
    		                     'parent_cid'  => $cid,
    		                     'service_cid' => $service_cid,
    		                     'guid'        => $guid,
    		                     'efforts'     => $efforts,
    		                    ]);
		            	$form_body     .= $new_receipt;
		            	
		                break;
				}                                                                                                   //register_error($display);
				break;

/****************************************
$section = 'boqx_contents'                *****************************************************************
 * Provides a floating 'Add effort' section to the Issue>Remedies panel
****************************************/
            case 'boqx_contents':
				Switch ($snippet){
					case 'marker':                                                                             $display .= '4471 boqx_contents>marker $service_cid: '.$service_cid.'<br>';
						$form_body .= "
							<div class='Effort__CPiu2C5N section-boqx_contents-marker' data-qid='$qid' data-parent-cid='$parent_cid' data-cid='$cid' data-guid='$guid' data-aid='$action'>
    							$hidden_fields
    						    $boqx_contents_add
    						    $boqx_contents_show
    						    $boqx_contents_edit
							</div>";
						break;
					case 'service_item':                                                                      $display.= '4479 $cid: '.$cid.'<br>';
					case 'receipt_item':

						break;
					case 'list_efforts':
					case 'view_effort':
						$form_body .= str_replace('__cid__', $cid, $story_model);
						break;
					case 'loose_things':
					    $form_body = $line_items;
					    break;
					case 'single_thing':
						break;
					default:
						$form_body .= "<div data-aid='Efforts' class='boqx_contents-default' action ='$action'>
                							$hidden_fields
                							<span class='efforts-eggs' data-aid='effortCounts' data-cid='$parent_cid' data-qid='$qid'></span>
                							$boqx_contents
                						</div>";                                                                          $display .= '4491 boqx_contents-default ... <br>4050 $cid: '.$cid.'<br>4050 $service_cid: '.$service_cid;
	                break;
				}                                                                                         //register_error($display);
	                                            	                                                          
				break;
/****************************************
$section = 'boqx_contents_loose_things'    *****************************************************************
****************************************/
            case 'boqx_contents_loose_things':
            	$add_thing_button = elgg_view('output/url', [
	                                        'title'        => 'Add something',
	                		                'data-element' => 'new_item',
            								'data-qid'     => $qid,
            								'data-cid'     => $service_cid,
	                    					'class'        => 'trigger-element add_marker',
	                                        'tabindex'     => '-1' 
	                    					]);
			    $view = 'forms/transfers/edit';
				$add_things .= elgg_view($view,[ 
										  'perspective' => 'add',
										  'section'     =>'boqx_contents', 
										  'snippet'     =>'loose_things',
										  'effort'      => $effort,      
										  'parent_cid'  => $parent_cid, 
										  'n'           => $n,]);
				$form_body .= "<div data-aid='Things' class='boqx-contents-just-things boqx_contents_loose_things-default' data-cid='$parent_cid'>
                                   $hidden_fields
    							   <span class='things-count' data-aid='thingCounts' data-cid='$parent_cid'><h4>Things</h4></span>
    						       $add_things
    						   </div>";
				break;
/****************************************
$section = 'boqx_contents_receipt'        *****************************************************************
****************************************/
            case 'boqx_contents_receipt':                                                                 $display .= '4500 boqx_contents_receipt>$service_cid: '.$service_cid.'<br>';
				$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
							'data-cid'=> $service_cid,
                            'tabindex'=> '-1',]);
            	$url = elgg_get_site_url().'jot';                                                        $display .= '4512 $effort->guid: '.$effort->guid.'<br>';
            	$marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$service_cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[$parent_cid][$service_cid][title]' placeholder='Give this effort a name'></textarea>";
            	$marker_date_picker = elgg_view('input/date', ['name'  => "jot[$parent_cid][$service_cid][moment]", 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[$parent_cid][$service_cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[$parent_cid][$service_cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$add_story_button = elgg_view('output/url', array(
	                                        'title'    => 'Add service effort',
	                		                'data-element' => 'new_service_effort',
            								'data-qid'     => $qid,
            								'data-cid'     => $service_cid,
	                    				    //'text'     => '+',
	                    					//'href'     => '#',
	                    					//'class'    => 'add-progress-marker addButton___3-z3g3BH',
	                    					'class'    => 'trigger-element add_marker',
	                                        'tabindex' => '-1' 
	                    					));
				Switch ($snippet){
					case 'marker1':
						$form_body .="<div id='$qid' class='ServiceEffort__26XCaBQk boqx_contents_receipt-marker1' data-qid='$qid' data-parent-cid='$parent_cid' data-cid='$cid' boqx-fill-level='empty'>
            							$boqx_contents_receipt_add
            							$boqx_contents_receipt_show
            							$boqx_contents_receipt_edit
            						</div>";
			            break;
					case 'service_item':                                                                      $display.= '4648 $cid: '.$cid.'<br>';
					case 'receipt_item':
	            	    $form_body .= $hidden_fields;
						$form_body .= $receipt_item_row;
						break;
					case 'service_item_properties':
					case 'receipt_item_properties':
					    $form_action = 'partials/jot_form_elements';
					    $body_vars   = ['element'        => 'properties_receipt_item',
	                                    'presentation'   => 'qboqx',
					                    'parent_cid'     => $parent_cid,
                                  	    'cid'            => $cid,
                                  	    'n'              => $n,
					                    'data_prefix'    => "jot[$parent_cid][$cid][$n][",
					                   ];
					    $content = elgg_view($form_action,$body_vars);
                        $form_body = $content;
						break;
					default:
						if ($action == 'add' || $action == 'edit'){
						    $view = 'forms/transfers/edit';
							$add_receipt = elgg_view($view,[ 
													  'perspective' => 'add',
													  'section'     =>'boqx_contents_receipt', 
													  'snippet'     =>'marker1',
													  'effort'      => $effort,      
													  'parent_cid'  => $parent_cid, 
													  'cid'         => $service_cid, 
													  'n'           => $n,]);
						}
						$form_body .= "<div data-aid='Tasks' class='boqx-contents-receipt boqx_contents_receipt-default' data-cid='$parent_cid'>
                                           $hidden_fields
            							   <span class='receipts-count' data-aid='taskCounts' data-cid='$parent_cid'><h4>Receipts ...</h4></span>
            						       $add_receipt
            						   </div>";                                              						$display .= '4739 boqx_contents_receipt-default ... <br>4739 $cid: '.$cid.'<br>4739 $service_cid: '.$service_cid.'<br>4739 $effort->guid: '.$effort->guid;
						break;
				}               
	                                            	                                                          
				break;
/****************************************
 $section = default                        *****************************************************************
 ****************************************/
    default:
}
echo $form_body;
//register_error($display);