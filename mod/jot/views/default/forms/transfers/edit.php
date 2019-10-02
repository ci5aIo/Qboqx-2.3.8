<!--Form: jot\views\default\forms\transfers\edit.php-->
<?php
/**
 * transfers edit form body
 *
 * @package ElggPages	
 */

setlocale(LC_MONETARY, 'en_US');
$entity         = elgg_extract('entity'           , $vars);
$guid           = (int) elgg_extract('guid'       , $vars, 0);         $display       .= '$guid='.$guid.'<br>';
if ($entity && $guid == 0) 
    $guid       = $entity->getGUID();
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
$boqx_cid       = elgg_extract('boqx_cid'         , $vars);
//@TASK: Replace [transfer] with [$boqx_cid] 
$parent_cid     = elgg_extract('parent_cid'       , $vars, quebx_new_id ('c'));
$cid            = elgg_extract('cid'              , $vars, quebx_new_id ('c'));
$qid            = elgg_extract('qid'              , $vars, quebx_new_id ('q'));
$qid_n          = elgg_extract('qid_n'            , $vars, $qid.'_'.$n);
$presentation   = elgg_extract('presentation'     , $vars, 'full');                    $display       .= '$presentation='.$presentation.'<br>';
$perspective    = elgg_extract('perspective'      , $vars, 'edit');                    $display       .= '$perspective='.$perspective.'<br>';
$action         = elgg_extract('action'           , $vars, $perspective);              $display       .= '$action='.$action.'<br>';
$display_state  = elgg_extract('display_state'    , $vars, $perspective); //Options: add, edit, show
		                
$transfer_vars  = $vars;
$subtype        = 'transfer';
$exists         = true;
$now            = new DateTime();
$now->setTimezone(new DateTimeZone('America/Chicago'));
if (elgg_entity_exists($guid)){
     $jot  = get_entity($guid);
     $subtype = $jot->getSubtype();
	 $title   = $jot->title;}
else $exists  = false;
if (elgg_entity_exists($container_guid))
     $container = get_entity($container_guid);

$transfer_vars['subtype']      = $subtype;
$transfer_vars['presentation'] = $presentation;
$transfer_vars['transfer_guid']= $guid;
$transfer_vars['exists']       = $exists;
$transfer_vars['entity']       = $jot;
echo "<!--perspective=$perspective, presentation=$presentation, section=$section, snippet=$snippet-->";

/******************************************************************************************************
 * 
 * Perspectives
 *
*******************************************************************************************************/
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
                $hidden_fields = elgg_extract('hidden_fields', $vars, false);
                $expander = elgg_view("output/url", [
                    'text'    => '',
                    'class'   => 'expander undraggable',
                    'id'      => 'toggle_marker',
                    'data-cid'=> $cid,
                    'tabindex'=> '-1',]);
                $maximize_button = "<button type='button' class='autosaves maximize hoverable' id='story_maximize_$cid' tabindex='-1' title='Switch to a full view'></a>";
                $url = elgg_get_site_url().'jot';
                Switch ($snippet){
                    /****************************************
*add********** $section = 'things_boqx' $snippet = 'contents'  *****************************************************************
                     ****************************************/
                    case 'contents':
                        $parent_cid = $boqx_cid;                                                              $display .= '143 boqx_cid = '.$boqx_cid.'<br>143 $cid = '.$cid.'<br>143 $service_cid = '.$service_cid.'<br>';
                                                                                                       //register_error($display);
                        unset($hidden, $hidden_fields);
/*                        $hidden[] =['name'=>"jot[cid]",
                                    'value' => "$cid"];
*/                      $hidden[] =['name'  => "jot[$cid][subtype]", 'value' => 'boqx'];
                        $hidden[] =['name'  => "jot[$cid][aspect]" , 'value' => 'transfer'];
                         if (!empty($hidden)){                
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
                    /****************************************
*add********** $section = 'things_boqx' $snippet = 'contents_add'  *****************************************************************
                     ****************************************/
                    case 'contents_add':
                        $experience_label = 'E';
                        $issue_label      = 'I';
                        $things_label     = 'T';
                        $receipts_label   = 'R';
                        $collection_label = 'C';
                        $project_label    = 'P';
                        $form_body = "
                             <div tabindex='0' class='TaskAdd_uZhkkYv8' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
                                 <div class='AddSubresourceButton___S1LFUcMd TaskAdd__labels_aytUtMoP'>
                                     <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
                                     <span class='AddSubresourceButton__message___2vsNCBXi'>New Q</span>
                                 </div>
                                 <nav class='TaskAdd__actions___q0FD6Iu9 undefined TaskAdd__actions--unfocused___mndgy2gB'>
                                     <button class='IconButton___Mix9C5NO IconButton--small___xfAQtIvt' data-aid='things' aria-label='Things' data-cid='$cid' title='New Things'>
                                        $things_label
                                     </button>
                                     <button class='IconButton___Mix9C5NO IconButton--small___xfAQtIvt' data-aid='receipts' aria-label='Receipts' data-cid='$cid' title='New Receipts'>
                                        $receipts_label
                                     </button>
                                     <button class='IconButton___Mix9C5NO IconButton--small___xfAQtIvt' data-aid='collection' aria-label='Collection' data-cid='$cid' title='New Collection'>
                                        $collection_label
                                     </button>
                                     <button class='IconButton___Mix9C5NO IconButton--small___xfAQtIvt' data-aid='experience' aria-label='Experience' data-cid='$cid' title='New Experience'>
                                        $experience_label
                                     </button>
                                     <button class='IconButton___Mix9C5NO IconButton--small___xfAQtIvt' data-aid='project' aria-label='Project' data-cid='$cid' title='New Project'>
                                        $project_label
                                     </button>
                                     <button class='IconButton___Mix9C5NO IconButton--small___xfAQtIvt' data-aid='issue' aria-label='Issue' data-cid='$cid' title='New Issue'>
                                        $issue_label
                                     </button>
                                 </nav>
                             </div>";
                        break;
                    /****************************************
*add********** $section = 'things_boqx' $snippet = 'contents_show'  *****************************************************************
                     ****************************************/
                    case 'contents_show':
                        $form_body = "
                             <div class='EffortShow_haqOwGZY TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid=$cid style='display:none' draggable='true'>
                                 <span class='TaskShow__title___O4DM7q tracker_markup section-add-boqx_contents-marker'></span>
                                 <nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
                                     <button class='IconButton___4wjSqnXU IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid' title='Delete'>
                                     $delete
                                     </button>
                                 </nav>
                                 <span class='TaskShow__description___qpuz67f tracker_markup'></span>
                                 <span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
                             </div>";
                        break;
                    /****************************************
*add********** $section = 'things_boqx' $snippet = 'contents_edit'  *****************************************************************
                     ****************************************/
                    case 'contents_edit':
                        $boqx_contents = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'presentation'      => $presentation,
                            'section'           => $section,
                            'snippet'           =>'new_receipt',
                            'show_view_summary' => false,
                            'boqx_cid'          => $boqx_cid,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'service_cid'       => $service_cid]);   
                        $form_body = "
                             <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-cid=$cid>
                                $hidden_fields
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
//                        $hidden[] =['name'  => "jot[$cid][subtype]", 'value' => 'boqx'];
//                        $hidden[] =['name'  => "jot[$cid][aspect]" , 'value' => 'transfer'];
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
                            $edit_details = elgg_view('output/div',['class'=>'model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
                                'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                'content'=>$view_summary
                                . elgg_view($form,['section'=> 'things_bundle', 'perspective'=>$perspective, 'presentation'=>$presentation, 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
                            $story_model .= elgg_view('output/div',  ['class' =>'model item pin',
                                                                      'options'=> ['parent_cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                                                      'content'=>$hidden_fields.$edit_details]);
                            
                            $form_body = str_replace('<<cid>>', $cid, $story_model);
                            break;
				/****************************************
*add********** $section = 'things_boqx' $snippet = 'pallet'  *****************************************************************
				 ****************************************/
                    case 'pallet':
                        unset($hidden, $hidden_fields);
                        $hidden[] =['name'=>"jot[cid]"          , 'value' => "$cid"];
                        $hidden[] =['name'=>"jot[$cid][subtype]", 'value' => 'boqx'];
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                        

                        break;
				/****************************************
*add********** $section = 'things_boqx' $snippet = default  *****************************************************************
				 ****************************************/
                    default:
                        unset($hidden, $hidden_fields);
                        $hidden[] =['name'=>"jot[boqx]"         , 'value' => "$parent_cid"];
                        $hidden[] =['name'=>"jot[cid]"          , 'value' => "$cid"];
                        $hidden[] =['name'=>"jot[$cid][subtype]", 'value' => 'boqx'];
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                        
                        $things_boqx = elgg_view('forms/transfers/edit',
                           ['section'     => 'things_boqx',
                            'perspective' => $perspective,
                            'snippet'     => 'contents',
                            'parent_cid'  => $cid,
                            'n'           => $n,
                          //initialize  boqx_cid
                            'boqx_cid'    => $cid,
                            'qid'         => $qid]);                                        $display .= '315 parent_cid = '.$parent_cid.'<br>315 $cid = '.$cid.'<br>';
                                                                                            //register_error($display);
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
                           'presentation'   => $presentation,
                           'section'        => 'boqx_contents_loose_things',
//                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
               $receipts_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $action,
                           'presentation'   => $presentation,
                           'section'        => 'boqx_contents_receipt',
//@todo: Move content to this section to facilitate the easier selection by other forms 
//                           'section'        => 'boqx_contents',
//                           'snippet'        => 'receipt',
//                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
               
               $experience_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $action,
                           'presentation'   => $presentation,
                           'section'        => 'boqx_contents_experience',
                    	   'guid'           => $guid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,));
               
                $books_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $action,
                           'presentation'   => $presentation,
                           'section'        => 'boqx_contents_books',
                    	   'guid'           => $guid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
                $contents_panel = "<section class='contents full'>
		                              $loose_things_panel
		                              $receipts_panel
		                              $books_panel
		                              $experience_panel
		                           </section>";
            	$delete_button = "<label class=remove-card>". 
                            	    elgg_view('output/url', array(
                            	        'title'    =>'remove effort card',
                            	        'class'    =>'remove-card',
                            	        'text'     => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$maximize_button = "<button type='button' class='autosaves maximize hoverable' id='story_maximize_$cid' tabindex='-1' title='Switch to a full view'></a>";
                $delete = elgg_view("output/span", ["class"=>"remove-card", "content"=>$delete_button]);
                $collapser = "<a class='collapser collapser-effort' id='effort_collapser_$cid' data-cid='$cid' tabindex='-1'></a>";
            	
            	$add_effort = "<button data-aid='addEffortButton' class='ThingsBundle__submit___q0kFhFBf autosaves button std egg' type='submit' tabindex='-1' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid'>Add</button>";
            	switch($presentation){
            	    case 'pallet':
            	        $cancel_effort = "<button class='autosaves cancel-pallet clear' type='reset' id='epic_submit_cancel_$cid' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid' tabindex='-1'>Cancel</button>";
            	        break;
            	    default:
            	        $cancel_effort = "<button class='autosaves cancel clear' type='reset' id='epic_submit_cancel_$cid' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid' tabindex='-1'>Cancel</button>";
            	        break;
            	}
            	
            	$url = elgg_get_site_url().'jot';
            	$marker_title         = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' style='margin: 8px;' name='jot[$cid][title]' data-name='title' placeholder='Boqx name'></textarea>";
            	$marker_date_picker   = elgg_view('input/date', ['name'  => "jot[$cid][moment]", 'placeholder' => $now->format('Y-m-d'), 'value' => $now->format('Y-m-d'), 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[$cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$boqx_value = 'root';
                $options['aspects']    = quebx_boqx_aspect_options($boqx_value);
                $options['boqx_value']  = $boqx_value;
                $options['boqx_aspect']= 'contents';
                $options['cid']        = $cid;
                $options['menu_level'] = 1;
                $options['boqx_class'] ='compartmentBoqx__m2HVyVRp';
                $options['list_class'] ='pickList__q0EfbGIo';
                $options['item_class'] ='pickItem__S1zeipik';
                $options['crumb_class']='pickItem__ujGWJJw9';
                $options['label_class']='pickLabel__sdRC4Kf9';
                $weir_menu             = elgg_view('navigation/weir_menu', $options);

                $marker_boqx_selector = "<div class='dropdown aspect' data-selector='boqx_aspect' data-cid='$cid'>  
                        <input aria-hidden='true' type='hidden' name='jot[$cid][aspect]' value='things'>
                      <a id='story_estimate_dropdown_$cid' class='selection item_0' tabindex='-1'><span>Things</span></a>
                      <a id='story_estimate_dropdown_{$cid}_arrow' class='arrow target' tabindex='-1'></a>
                      <section class='pickAspect__RFFo494j closed'>
                        $weir_menu
                    </section>
                  </div>";
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
            	$instructions = "<div class='bounding_box'>
                                    <div class = 'instructions'>
                                        Add things acquired from different places.  Bundle receipts from separate transactions and Q everything in one gesture.
                                    </div>
                                 </div>";            	
            	break;
        
               /****************************************
*add********** $section = 'boqx_contents'                *****************************************************************
               ****************************************/
             case 'boqx_contents':
/*				$expander = elgg_view("output/url", [
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
            	$marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[$parent_cid][$cid][title]' data-name='title' placeholder='Give this transfer a name'></textarea>";
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
*/				Switch ($snippet){
               /****************************************
*add********** $section = 'boqx_contents' $snippet='items' *****************************************************************
               ****************************************/
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
               /****************************************
*add********** $section = 'boqx_contents' $snippet='loose_things' *****************************************************************
               ****************************************/
				        case 'loose_things':
                        unset($hidden, $hidden_fields);
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
                                      'presentation'   => $presentation,
									  'section'     =>'boqx_contents', 
									  'snippet'     =>'single_thing',
									  'effort'      => $effort,      
									  'parent_cid'  => $parent_cid, 
									  'cid'         => $cid, 
									  'n'           => $n,]);
        				$first_line_properties = elgg_view($view,['section'=>'boqx_contents_receipt', 'perspective'=>$perspective, 'presentation'=>$presentation, 'snippet'=>'receipt_item_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
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
                                                                                        'data-presence' => 'panel',
                		                                                                'data-presentation'=> 'pallet'
																					   ]), 
                        								          'class'=>'receipt-input']);
                    	$loose_things_header_recd_qty = 'Rec\'d';
                        $loose_things_header_qty   = 'Qty';
                        $loose_things_header_item  = 'Thing';
        				$loose_things_line_items = "          
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
               /****************************************
*add********** $section = 'boqx_contents' $snippet='single_thing' *****************************************************************
               ****************************************/
                        case 'single_thing':
/*             			unset($hidden, $hidden_fields);
             			$hidden[] =['name'=>"jot[$parent_cid][$cid][$n][subtype]",
             			            'value' => 'boqx'];
             			$hidden[] =['name'=>"jot[$parent_cid][$cid][$n][aspect]",
                     			    'value' => 'thing'];
             			if (!empty($hidden)){
             			    foreach($hidden as $key=>$field){
             			        $hidden_fields .= elgg_view('input/hidden', $field);}}
		                $element = 'loose_item';
		                switch($presentation){
		                    case 'pallet':
		                        $horizontal_offset = '-90';
		                        $horizontal_offset_title = '-105';
		                        break;
		                    default:
		                        $horizontal_offset = '-15';
		                }
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
														                  'data-horizontal-offset'=>$horizontal_offset,
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
						$form_body ="
								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
                                    <div class='rTableCell'>$delete</div>
									<div class='rTableCell'>$hidden_fields".elgg_view('input/number',   ['name'=>"jot[$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$set_properties_button "
									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]", 'style'=>'width:95%', 'data-name'=>'title','data-jq-dropdown' => '#'.$cid.'_'.$n,'data-horizontal-offset'=>$horizontal_offset_title])."</div>
				                </div>";*/
						
				        /*$form_body = elgg_view($view,[ 
										  'perspective' => $perspective,
                                          'presentation'=> $presentation,
										  'section'     =>'boqx_contents_receipt', 
										  //'snippet'     =>'loose_things',
										  'snippet'     => 'receipt_item',
				                          'aspect'      => $aspect,
				                          'display_state'  => $display_state,
										  'effort'      => $effort,      
										  'parent_cid'  => $parent_cid, 
										  'n'           => $n,]);*/
                        $aspect        = elgg_extract('aspect', $vars, 'item');
                        $element = 'item';
		                $delete = elgg_format_element("span",['class'=>'remove-item'], elgg_format_element('a', ['title' =>'remove item'],
                                                                    	                                                elgg_view_icon('delete-alt')));
		                switch($presentation){
		                    case 'pallet':
                     			unset($hidden);
                     			unset($form_body, $disabled, $hidden, $hidden_fields, $id_value);
        		                $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
        		                $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => $aspect      , 'data-focus-id' => "Aspect--{$cid}"];
        		                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => '0'            , 'data-focus-id' => "FillLevel--{$cid}"];
        		                $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => "$n"           , 'data-focus-id' => "SortOrder--{$cid}"];
		                        //$qid_n = "{$cid}_{$n}";
		                        $collapser = elgg_format_element('a',['class'=>['collapser','collapser-item'], 'id'=>"item_collapser_{$cid}",'data-cid'=>"$cid", 'tabindex'=>'-1']);
		                        $id_value = $guid;
		                        $unpack_class='';
		                        $clone_class='';
		                        $delete_class='';
		                        switch($aspect){
		                            case 'item':
		                                $unpack_class    = 'selected';
		                                $unpack_toggle   = 1;
		                                $clone_disabled  = 'disabled';
		                                $delete_disabled = 'disabled';
		                                break;
		                            case 'receipt_item':
		                                $unpack_toggle   = 0;
		                                $clone_disabled  = 'disabled';
		                                $delete_disabled = 'disabled';		                                
		                                break;
		                        }
        		                $hidden[] =['name'=>"jot[$cid][unpack]"    , 'value' => $unpack_toggle , 'data-focus-id' => "Unpack--{$cid}"];
        		                if (!empty($hidden)){                
                                    foreach($hidden as $key=>$field){
                                        $hidden_fields .= elgg_view('input/hidden', $field);}}
		                        $add_item = "<button data-aid='addItemButton'  type='submit'  class='AddItem__submit___u7pvMd9T std egg' style='margin: 3px 3px 0 15px;order: 2;' data-boqx='$parent_cid' data-cid='$cid' data-presence='$presentation'>Add</button>";
		                        $horizontal_offset = '-140';
		                        $item_header_qty   = 'Quantity';
                                $item_header_tax   = 'Taxable';
                                $item_header_cost  = 'Item Cost';
                                $item_header_total = 'Total Cost';
                                $unpack_button = elgg_format_element('button',['class'=>['autosaves', 'unpack_item', 'hoverable', 'left_endcap', $unpack_class],
                                                                               'title'=>'Unpack this item'.$unpack_title_label,
                                                                               'id'=>"item_unpack_button_$cid",
                                                                               'data-aid'=>'Unpack',
                                                                               'tabindex'=>'-1',
                                                                                $unpack_disabled]);
                                $clone_button  = elgg_format_element('button',['class'=>['autosaves', 'clone_item', 'hoverable', 'left_endcap', $clone_class],
                                                                               'title'=>'Clone this item'.$clone_title_label,
                                                                               'id'=>"item_clone_button_$cid",
                                                                               'data-aid'=>'Clone',
                                                                               'tabindex'=>'-1',
                                                                                $clone_disabled]);
                                $delete_button  = elgg_format_element('button',['class'=>['autosaves', 'delete', 'hoverable', 'right_endcap', $delete_class],
                                                                                'title'=>'Delete this item'.$delete_title_label,
                                                                                'id'=>"item_delete_button_$cid",
                                                                                'data-aid'=>'Delete',
                                                                                'tabindex'=>'-1',
                                                                                 $delete_disabled]);
        		                $item_controls     = "<section class='story_or_epic_header'>
                                                            $collapser
                                                            <nav class='edit'>
		                                                          <section class='controls' style='width:100%'>
                                                                      <div class='persistence' style='order:1'>
		                                                                 $add_item
		                                                              </div>
		                                                              <div class='actions'>
		                                                                $unpack_button
		                                                                $clone_button
		                                                                $delete_button
		                                                              </div>
		                                                            </section>
		                                                      </nav>
		                            					    
		                            					</section>";
                                
		                        $style_add = $style_show = $style_edit = "style='display:none;'";
		                        $edit_properties_button = elgg_view('output/url', ['data-cid'            => $cid,
        														                   'class'               => "ItemEdit__showContainer",
		                                                                           'text'                => elgg_view_icon('settings-alt').'Item Details', 
        												                           'title'               => 'show properties',]);
		                        $item_title        = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[$cid][title]' placeholder='Item name'></textarea>";
//                    	        $item_description  = "<textarea name='jot[$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' placeholder='Describe the item'></textarea>";
                    	        $item_description  = "<div data-aid='Description' class='Description___3oUx83yQ'><h4 id='description$cid'>Description</h4><div class='DescriptionShow___3-QsNMNj DescriptionShow__placeholder___1NuiicbF' tabindex='0' data-aid='renderedDescription' data-focus-id='DescriptionShow--$cid'>Add a description</div>
                    	                                      <div class='DescriptionEdit___1FO6wKeX'>
                                                                <div class='markdownHelpContainer___32_mTqNL'>
                                                                    <div>
                                                                        <button class='DescriptionEdit__tab___7lFo9PZA'>Write</button>
                                                                        <button class='DescriptionEdit__tab___7lFo9PZA DescriptionEdit__tab--disabled___1x2-iUxr'>Preview</button>
                                                                    </div>
                                                                    <a href='/help/markdown' class='markdownHelp___2yFSQCip' target='_blank'>Formatting help</a>
                                                                </div>
                                                                <div data-aid='editor' class='textContainer___2EcYJKlD'>
                                                                    <div>
                                                                        <div class='DescriptionEdit__write___207LwO1n'>
                                                                            <div class='AutosizeTextarea___2iWScFt6'>
                                                                                <div class='AutosizeTextarea__container___31scfkZp'>
                                                                                    <textarea name='jot[$cid][description]' aria-labeledby='description$cid' data-aid='textarea' data-cid='$cid' data-focus-id='DescriptionEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' placeholder='Add a description'></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class='DescriptionEdit__preview_____Tr424N'>
                                                                            <span class='tracker_markup'><p>Preview your <a href='/help/markdown' target='_blank' rel='noopener' tabindex='-1'>Markdown formatted</a> text here.</p></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class='controls___2K44hJCR'>
                                                                        <div>
                                                                            <button class='SMkCk__Button QbMBD__Button--primary _3olWk__Button--small button__save___2XVnhUJI' data-aid='save' data-cid='$cid' type='button'>Add description</button>
                                                                            <button class='SMkCk__Button ibMWB__Button--open _3olWk__Button--small' data-aid='cancel' data-cid='$cid' type='button'>Cancel</button>
                                                                        </div>
                                                                    <div class='Controls__text___B_l9ri3_'>
                                                                        <button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='AddEmoji' aria-label='Add emoji to description'>
                                                                            <span class='' title='Add emoji to description' style='background: url(https://assets.pivotaltracker.com/next/assets/next/2b4b3f66-emoji-light.svg) center center no-repeat;'></span>
                                                                        </button>
                                                                    <div class='Dropdown Dropdown--left StoryTemplateDropdown___3ctobFmT'>
                                                                        <div class='Dropdown__content' data-aid='StoryTemplateDropdown'>
                                                                            <button class='SMkCk__Button ibMWB__Button--open StoryTemplate__button___6_DPoMAr _1m_u1__Button--short' type='button'>
                                                                                <img title='Select story template' class='_3iG1d__IconButton__icon' src='https://assets.pivotaltracker.com/next/assets/next/b202db4f-story-templates.svg'>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>";
                    	        if ($aspect == 'receipt_item'){$acquisition_details  = "<div class='row'>
                                                                                            <h2>Acquisition Details</h2>
                                                                            		    </div>
                                                                                        <div class='row'>
                                                                                            <div class='column_01'>
                                                                                                <label for='jot[$cid][qty]'>$item_header_qty</label>
                                                                                            </div>
                                                                                            <div class='column_02'>
                                                                                                ".elgg_view('input/number',   ['name'=>"jot[$cid][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class='row'>
                                                                                            <div class='column_01'>
                                                                                                <label for='jot[$cid][cost]'>$item_header_cost</label>
                                                                                                <div class='column_01b'>
                                                                                                    <label for='jot[$cid][taxable]'>$item_header_tax</label>".
                                                                                                    elgg_view('input/checkbox', ['name'=>"jot[$cid][taxable]",'value'=>1,'data-qid'=>$qid_n,'data-name'=>'taxable','default'=> false,])."
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class='column_02'>
                                                                                                ".elgg_view('input/text', [ 'name'      => "jot[$cid][cost]",
                                                            										 						'class'     => 'last_line_item',
                                                            											                    'data-qid'  => $qid_n,
                                                            											                    'data-name' => 'cost',
                                                            				    											'class'     => 'nString',
                                                            															   ])."
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class='row'>
                                                                                            <div class='column_01'>
                                                                                                <label>$item_header_total</label>
                                                                                            </div>
                                                                                            <div class='column_02'>
                                                                                                <span id='{$cid}_line_total'></span><span class='{$cid}_line_total_raw line_total_raw'></span>
                                                                                            </div>
                                                                                        </div>";}
                            	$value                 = elgg_get_site_entity()->guid;
                            	$boqx_name             = 'category';
                                $options['aspects']    = quebx_boqx_aspect_options($boqx_name,['root'=>$value]);//, 'order_by_metadata'=>['name'=>'name','direction' => 'ASC', 'as' => 'text']]);
                                $options['boqx_name']  = $boqx_name;
                                $options['boqx_aspect']= 'category';
                                $options['boqx_value'] = $value;
                                $options['cid']        = $cid;
                                $options['menu_level'] = 1;
                                $options['boqx_class'] ='compartmentBoqx__Cdil2TkU';
                                $options['list_class'] ='pickList__Upq66A3H';
                                $options['item_class'] ='pickItem__GaGSmQJ6';
                                $options['crumb_class']='pickItem__ehybudK0';
                                $options['label_class']='pickLabel__2JR0Zrcl';
                                $weir_menu             = elgg_view('navigation/weir_menu', $options);
                
                                $marker_boqx_selector = "<div class='dropdown category' data-selector='boqx_category' data-cid='$cid'>  
                                        <input aria-hidden='true' type='hidden' name='jot[$cid][category]'>
                                      <a id='item_category_dropdown_$cid' class='selection item_0' tabindex='-1'><span>Select ...</span></a>
                                      <a id='item_category_dropdown_{$cid}_arrow' class='arrow target' tabindex='-1'></a>
                                      <section class='pickCategory__VRYE6ZAO closed'>
                                        $weir_menu
                                    </section>
                                  </div>";                            	
                    	        $item_category     = elgg_format_element('div', ['class'=>['info_box','free']],
                    	                                         elgg_format_element('div', ['class'=>'info'],
                    	                                              elgg_format_element('div', ['class'=>'row'],
                    	                                                  "<em>Category</em>".
                    	                                                  $marker_boqx_selector)));
                    	        $category_picker = elgg_view('input/category', ['presentation'=>$presentation, 'perspective'=>$perspective, 'parent_cid'=>$cid, 'add_leaf' => false,'category_class'=>'BoqxCategoryPick__gNSC0Iny','item_class'=>'CategoryDropdownItem___qgkw9JYv', 'root_name_override'=>'blah', 'show_section_headers'=>false]);
                    	        $line_item_properties      = $item_description;
        						//$line_item_properties     .= elgg_view('forms/market/profile', ['presentation'=>$presentation]);
        						
        						$line_item_properties     .= $item_category;
                    	        $line_item_properties     .= elgg_view('forms/market/edit', ['presentation'=>$presentation, 'perspective'=>$perspective, 'cid'=>$cid]);    
                    	        switch ($display_state){case 'add':  unset($style_add);  break;
                    	                                case 'show': unset($style_show); break;
                    	                                case 'edit': $style_edit = "style='display:flex;'"; break;}
		                        $boqx_contents_add = "<div tabindex='0' class='AddSubresourceButton___oKRbUbg6' $style_add data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
                                                    				<span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
                                                    				<span class='AddSubresourceButton__message___2vsNCBXi'>Add an item</span>
                                                			  </div>";
                                $boqx_contents_show = "<div class='ItemShow_Btc471up' $style_show data-aid='TaskShow' data-cid='$cid' draggable='true'>
                    					<div>
                                            <nav class='ItemShow__actions__4AR4v8MP ItemShow__actions--unfocused___1df6Nh5x'>
                            					<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
                            						$delete
                            					</button>
                            				</nav>
                    					</div>
                                        <div class='ItemShow__line_gSpQE5Ao'>
                                            <span class='ItemShow__title__8tlRYJcP section-add-boqx_contents_marker1'></span>
                        					<span class='ItemShow__item_total__Dgd1dOSZ'></span>
                                        </div>
                        			</div>";
/*                                $boqx_contents_edit = "
                                    <div class='ItemEdit___7asBc1YY info_box free' $style_edit data-aid='ItemEdit' data-cid='$cid'>
                        			    $hidden_fields
                                        <div class='ItemEditValue'>
                            	    		$collapser
                                            <section class='ItemLedger__KY8DM3qs' data-cid='$cid'>
                                                <fieldset class='name'>
                                                	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
                                                        <div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 400px;'>
                                        					<div class='AutosizeTextarea__container___31scfkZp'>
                                        					    $item_title
                                        					</div>
                                        				</div>
                            							<button data-aid='addItemButton'  type='submit'  class='AddItem__submit___u7pvMd9T std egg' style='margin: 3px 3px 0 15px;order: 2;' data-boqx='$parent_cid' data-cid='$cid' data-presence='$presentation'>Add</button>
                                        			</div>
                                        		</fieldset>
                                                $acquisition_details
                                            </section>
                                        </div>
                                        <div class='ShowItemDetails__Uc3MWjrS'>
                                            <div class='ShowItemDetailsButton__qWXhMy9t' data-cid='$cid'>
                                                <h3>".elgg_view_icon('settings-alt')."Item Details</h3>
                                            </div>
                            				<section class='ItemEdit__descriptionContainer___Mr67pXjd ItemEditContainer__$cid' >
                                                <section class='item-properties item-properties__$cid'>
                                                 		$line_item_properties
                                                </section>
                            				</section>
                                        </div>
                        			</div>";*/
                                $boqx_contents_edit = "
                                    <div class='ItemEdit___7asBc1YY info_box free' $style_edit data-aid='ItemEdit' data-cid='$cid'>
                        			    $hidden_fields
                                        <div class='ItemEditValue'>
                            	    		$item_controls
                                            <section class='ItemLedger__KY8DM3qs' data-cid='$cid'>
                                                <fieldset class='name'>
                                                	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
                                                        <div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 400px;'>
                                        					<div class='AutosizeTextarea__container___31scfkZp'>
                                        					    $item_title
                                        					</div>
                                        				</div>
                                        			</div>
                                        		</fieldset>
                                                $acquisition_details
                                            </section>
                                        </div>
                                        <div class='ShowItemDetails__Uc3MWjrS'>
                                            <div class='ShowItemDetailsButton__qWXhMy9t' data-cid='$cid'>
                                                <h3>".elgg_view_icon('settings-alt')."Item Details</h3>
                                            </div>
                            				<section class='ItemEdit__descriptionContainer___Mr67pXjd ItemEditContainer__$cid' >
                                                <section class='item-properties item-properties__$cid'>
                                                 		$line_item_properties
                                                </section>
                            				</section>
                                        </div>
                        			</div>";
                            						
                                $single_thing = elgg_format_element('div',['class'=>['boqx-item','Item__nhjb4ONn'], 'id'=>"$cid", 'data-boqx'=>$parent_cid, 'data-aspect'=>$aspect, 'boqx-fill-level'=>'0'], $boqx_contents_add.$boqx_contents_show.$boqx_contents_edit);
		                        break;
		                    default:
                     			unset($hidden, $hidden_fields);
                                $hidden[] =['name'=>"jot[$parent_cid][$cid][$n][aspect]",
        		                            'value' => 'item',
        		                            'data-focus-id' => "Aspect--{$qid_n}"];
        		                $hidden[] =['name'=>"jot[$parent_cid][$cid][$n][fill_level]",
        		                            'value' => '0',
        		                            'data-focus-id' => "FillLevel--{$qid_n}"];
                				if (!empty($hidden)){                
                                    foreach($hidden as $key=>$field){
                                        $hidden_fields .= elgg_view('input/hidden', $field);}}
		                        $horizontal_offset = '-15';
        						$delete = elgg_view('output/url', ['title'=>'remove item',
        						                                   'class'=>'remove-item',
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
        														                  'data-horizontal-offset'=>$horizontal_offset,
        												                          'text'                => elgg_view_icon('settings-alt'), 
        												                          'title'               => 'set properties',
        						]);
        						$single_thing ="
        								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
                                            $hidden_fields
                                        	<div class='rTableCell'>$delete</div>
        									<div class='rTableCell'>$unpack_icon</div>
        									<div class='rTableCell'>".elgg_view('input/number',   ['name'=>"jot[$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
        									<div class='rTableCell'>$set_properties_button "
        									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]",'class'=>'rTableform90','id'=>'line_item_input','data-name'=>'title','data-jq-dropdown' => '#'.$cid.'_'.$n,'data-horizontal-offset'=>$horizontal_offset,])."</div>
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
		                }
             		    break;
               /****************************************
*add********** $section = 'boqx_contents' $snippet='receipt' *****************************************************************
               ****************************************/
                        case 'receipt':
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
                            break;
               /****************************************
*add********** $section = 'boqx_contents' $snippet='books' *****************************************************************
               ****************************************/
             		    case 'books':
                        unset($hidden, $hidden_fields);
                        $hidden[] =['name'=>"jot[$cid][boqx]",
                                    'value' => $parent_cid];
                        $hidden[] =['name'=>"jot[$cid][cid]",
                                    'value' => $cid];
                        $hidden[] =['name'=>"jot[$cid][aspect]",
                                    'value' => 'books'];
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                        $view = 'forms/transfers/edit';
        				$first_line = elgg_view($view,[ 
									  'perspective' => $perspective,
									  'section'     =>'boqx_contents', 
									  'snippet'     =>'single_book',
									  'effort'      => $effort,      
									  'parent_cid'  => $parent_cid, 
									  'cid'         => $cid, 
									  'n'           => $n,]);
        				$first_line_properties = elgg_view($view,['perspective'=>$perspective, 'section'=>'boqx_contents_books', 'snippet'=>'book_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
        				$books_header_selector = elgg_view('output/span', [
                		                                          'content'=> elgg_view('output/url', [
    		                                                                            'text' => '+',
																 					    'href' => '#',
																					    'class' => 'elgg-button-submit-element new-item',
																					    'data-element'=> 'new_book',
																					    'data-qid'    => $cid,
                                            					    				    'data-cid'      => $cid,
                                            	    								    'data-parent-cid'=>$parent_cid,
                                            					    				    'data-rows'     => 1,
                                            					    				    'data-last-row' => 1,
                                                                                        'data-presence' => 'panel'
																					   ]), 
                        								          'class'=>'receipt-input']);
                    	$books_header_recd_qty = 'Rec\'d';
                        $books_header_qty   = 'Qty';
                        $books_header_item  = 'Title';
        				$books_line_items = "          
                		    <div class='rTable line_items loose-line-items'>
                                <div class='rTableBody'>
                                    $hidden_fields
                					<div id='sortable'>
                						<div class='rTableRow pin'>
                			                <div class='rTableCell'>$books_header_selector</div>
                			    			<div class='rTableHead'>$books_header_qty</div>
                			    			<div class='rTableHead'>$books_header_item</div>
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
               /****************************************
*add********** $section = 'boqx_contents' $snippet='single_book' *****************************************************************
               ****************************************/
                        case 'single_book':
             			unset($hidden, $hidden_fields);
                        $hidden[] =['name'=>"jot[$cid][boqx]",
                                    'value' => $parent_cid];
                        $hidden[] =['name'=>"jot[$cid][cid]",
                                    'value' => $cid];
                        $hidden[] =['name'=>"jot[$cid][aspect]",
                                    'value' => 'book'];
             			if (!empty($hidden)){
             			    foreach($hidden as $key=>$field){
             			        $hidden_fields .= elgg_view('input/hidden', $field);}}
            	        switch($presentation){
		                    case 'pallet':
		                        $horizontal_offset = '-90';
		                        break;
		                    default:
		                        $horizontal_offset = '-15';
		                }
		                $element = 'book';
						$delete = elgg_view('output/url', ['title'=>'remove this book',
						                                   'class'=>'remove-book',
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
														                  'data-horizontal-offset'=>$horizontal_offset,
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
						$form_body ="
								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-boqx='$parent_cid' data-row-id='$n'>
                                    <div class='rTableCell'>$delete</div>
									<div class='rTableCell'>$hidden_fields".elgg_view('input/number',   ['name'=>"jot[$cid][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$set_properties_button "
									                         .elgg_view('input/text',     ['name'=>"jot[$cid][title]", 'style'=>'width:95%', 'data-name'=>'title','data-jq-dropdown' => '#'.$cid.'_'.$n,])."</div>
				                </div>";
             		    break;
               /****************************************
*add********** $section = 'boqx_contents' $snippet= default *****************************************************************
               ****************************************/
             		    default:
             		        
/*						$boqx_contents = elgg_view('forms/transfers/edit',[
	                                        'section'     => 'boqx_contents', 
                                            'action'      => $action, 
                                            'snippet'     => 'marker', 
                                            'parent_cid'  => $parent_cid, 
                                            'n'           => $n, 
                                            'cid'         => $cid, 
                                            'service_cid' => $service_cid, 
                                            'qid'         => $qid, 
                                            'guid'        => $guid]);
*/	                break;
				}
				break;
				/****************************************
*add********** $section = 'boqx_contents_loose_things'    *****************************************************************
                ****************************************/
            case 'boqx_contents_loose_things':
				unset($edit_things, $view_things);
            	$add_thing_button = elgg_view('output/url', [
	                                        'title'        => 'Add something',
	                		                'data-element' => 'new_item',
            								'data-qid'     => $qid,
            								'data-cid'     => $service_cid,
	                    					'class'        => 'trigger-element add_marker',
	                                        'tabindex'     => '-1' 
	                    					]);
			    $view = 'forms/transfers/edit';
				$add_things = elgg_view($view,[ 
										  'perspective' => $perspective,
                                          'presentation'=> $presentation,
										  'section'     =>'boqx_contents', 
										  //'snippet'     =>'loose_things',
										  'snippet'     => 'single_thing',
				                          'aspect'      => 'item',
				                          'display_state'=>'edit',
										  'effort'      => $effort,      
										  'parent_cid'  => $parent_cid, 
										  'n'           => $n,]);
				break;
				/****************************************
*add********** $section = 'boqx_contents_receipt'         *****************************************************************
				 ****************************************/
             case 'boqx_contents_receipt':
             	switch ($snippet){
				/****************************************
*add********** $section = 'boqx_contents_receipt' $snippet='marker1' *****************************************************************
				 ****************************************/
             	    case 'marker1':
             			unset($hidden, $hidden_fields);
             	        $unpack_icon = "<span title='Unpack all'>"
								           .elgg_view('input/checkbox', ['name'=>"jot[$cid][$n][unpack]",'value'=>1,'class'=>'boqx-unpack closed','data-cid'=>$cid,'data-name'=>'unpack-all','default'=> false,])
						              ."</span>";
                		switch($presentation){
                		    case 'pallet':
        		                $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
        		                $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => 'receipt'      , 'data-focus-id' => "Aspect--{$cid}"];
        		                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => '0'            , 'data-focus-id' => "FillLevel--{$cid}"];
                        		        
                        		$title_input             = elgg_view('input/text'  , ['name' => "jot[$cid][title]"             , 'class'=>'receipt-input'  , 'placeholder' => 'Receipt name', 'required'=>'']);
        		                $merchant = elgg_view('output/span', ['content'=>elgg_view('input/grouppicker', ['name' => "jot[$cid][merchant]",
                                                                                                                 'limit'=> 1,
                                                                                                                 'autocomplete'=>'on',
                                                                                    				            ]),
                                                                      'options' => ['data-focus-id'=> "MerchantAdd--$cid"],
                                		                              'class'=>'receipt-input',]);
                                $associate_label         = 'Sales Assoc.';
                                $asset_input             = elgg_view('input/text'  , ['name' => "jot[$cid][asset]"             , 'class'=>'receipt-input']);
                                $cashier                 = elgg_view('input/text'  , ['name' => "jot[$cid][cashier]"           , 'class'=>'receipt-input']);
                                $transaction_date_label  = 'Purchase Date';
                                $moment_label_receipt    = elgg_view('output/span' , ['content'=>'Date'                                                       , 'class'=>'receipt-input']);
                                $moment                  = elgg_view('input/date'  , ['name' => "jot[$cid][moment]"            , 'class'=>'receipt-input'   , 'value' => strtotime("now")]);
                                $actor_label             = 'Buyer';
                                $purchased_by            = elgg_view('input/text'  , ['name' => "jot[$cid][purchased_by]"      , 'class'=>'receipt-input']);
                                $order_no_label          = 'Order #';
                                $order_no                = elgg_view('input/text'  , ['name' => "jot[$cid][order_no]"          , 'class'=>'receipt-input']);
                                $preorder_label          = 'Ordered';
                                $preorder_options        =                           ['name'=>"jot[$cid][preorder_flag]"       , 'class'=>'preorder-flag'   ,'label_class'=>'receipt-input'];
                                $preorder_flag           = elgg_view('input/switchbox',$preorder_options);
                                $preorder_style          = 'visibility:hidden';
                                $delivery_date_label     = 'Scheduled date';
                                $delivery_date           = elgg_view('input/date'  , ['name'=>"jot[$cid][delivery_date]"       , 'class'=>'receipt-input'   , 'style'=>'width:100px;']);
                                $purchase_order_no_label = 'PO #';
                                $purchase_order_no       = elgg_view('input/text'  , ['name' => "jot[$cid][purchase_order_no]" , 'class'=>'receipt-input'   , 'style'=>'width:100px;']);
                                $invoice_no_label        = 'Invoice #';
                            	$invoice_no              = elgg_view('input/text'  , ['name' => "jot[$cid][invoice_no]"        , 'class'=>'receipt-input']);
                                $document_no             = elgg_view('input/text'  , ['name' => "jot[$cid][document_no]"       , 'class'=>'receipt-input']);
                                $transaction_no_label    = 'Receipt #';
                                $receipt_no              = elgg_view('input/text'  , ['name' => "jot[$cid][receipt_no]"        , 'class'=>'receipt-input']);
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
                                if (!empty($hidden)){                
                                    foreach($hidden as $key=>$field){
                                        $hidden_fields .= elgg_view('input/hidden', $field);}}
                        	    $delete_button = elgg_view('output/url', array(
                                            	        'title'=>'remove progress marker',
                                            	        'text' => elgg_view_icon('delete-alt'),
                                            	    ));
                            	$delete = elgg_view("output/span", ["content"=>$delete_button]);
                            	$service_marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[$cid][title]' placeholder='Receipt name'></textarea>";
                            	$service_marker_description = "<textarea name='jot[$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
                            	
                        		$shipping_cost  = elgg_view('input/text', ['name' => "jot[$cid][shipping_cost]", 'data-qid'=>"$cid", 'data-name'=>'shipping_cost', 'data-focus-id'=>"ShippingCost--{$cid}", 'class'=> 'nString']);
                        		$sales_tax      = elgg_view('input/text', ['name' => "jot[$cid][sales_tax]", 'data-qid'=>"$cid", 'data-name'=>'sales_tax', 'data-focus-id'=>"SalesTax--{$cid}", 'class'=> 'nString']);
                        		$sales_tax_rate = $transfer->sales_tax_rate;
                        		$total          = $item_exists ? $receipt_item_total
                        		                                 : money_format('%#10n', $transfer->total);
                        		if (!empty($sales_tax_rate)){
                        		    $sales_tax_rate_label = '('.number_format($sales_tax_rate*100, 0).'%)';
                        		}
                        		$line_items_footer = "
                                <div class='rTable'>
                                    <div class='rTableBody'>
                        				<div class='rTableRow pin'>
                            				<div class='rTableCell'></div>
                            				<div class='rTableCell'>Subtotal</div>
                            				<div class='rTableCell'><span id={$cid}_subtotal>$subtotal</span><span class='{$cid}_subtotal_raw subtotal_raw'>$transfer->subtotal</span></div>
                            			</div>
                            			<div class='rTableRow pin'>
                            				<div class='rTableCell'></div>
                            				<div class='rTableCell'>Shipping</div>
                            				<div class='rTableCell'>$shipping_cost</div>
                            			</div>
                            			<div class='rTableRow pin'>
                            				<div class='rTableCell'></div>
                            				<div class='rTableCell'>Sales Tax <span class='{$cid}_sales_tax_rate'></span></div>
                            				<div class='rTableCell'>$sales_tax</div>
                            			</div>
                            			<div class='rTableRow pin'>
                            				<div class='rTableCell'></div>
                            				<div class='rTableCell'>Total</div>
                            				<div class='rTableCell'><span id={$cid}_total>$total</span><span class='{$cid}_total_raw total_raw'></span></div>
                            			</div>
                                    </div>
                                </div>";                                                                                                               $display.='$presentation'.$presentation.'<br>';
        //Note: $qid receives the value of $cid
                        		//$first_line = elgg_view('forms/transfers/edit',['perspective'=>$perspective, 'presentation'=>$presentation, 'section'=>'boqx_contents_receipt', 'snippet'=>'receipt_item', 'display_state'=>'edit', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                        		$first_line = elgg_view('forms/transfers/edit',['perspective'=>$perspective, 'presentation'=>$presentation, 'section'=>'boqx_contents', 'snippet'=>'single_thing', 'aspect'=>'receipt_item', 'display_state'=>'edit', 'parent_cid'=>$cid]);
                        		$first_line_properties = elgg_view('forms/transfers/edit',['perspective'=>$perspective, 'presentation'=>$presentation, 'section'=>'boqx_contents_receipt', 'snippet'=>'receipt_item_properties', 'parent_cid'=>$cid]);
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
                        		
                        		
                                $line_items_header = $first_line;
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
                        					</div>
                                            <div class='rTableRow'>
                        						<div class='rTableCell'>$moment_label_receipt</div>
                        						<div class='rTableCell'>$moment</div>
                        					</div>
                        					<div class='rTableRow'>
                        						<div class='rTableCell'>Buyer</div>
                        						<div class='rTableCell'>$purchased_by</div>
                        					</div>
                                            <div class='rTableRow'>
                        						<div class='rTableCell' style='white-space:nowrap;'>Order #</div>
                        						<div class='rTableCell'>$order_no</div>
                        					</div>
                                            <div class='rTableRow'>
                        						<div class='rTableCell' style='white-space:nowrap;'>Invoice #</div>
                        						<div class='rTableCell'>$invoice_no</div>
                        					</div>
                        					<div class='rTableRow'>
                        						<div class='rTableCell' style='white-space:nowrap;'>Doc #</div>
                        						<div class='rTableCell'>$document_no</div>
                        					</div>
                                            <div class='rTableRow'>
                        						<div class='rTableCell' style='white-space:nowrap;'>Receipt #</div>
                        						<div class='rTableCell'>$receipt_no</div>
                        					</div>
                        				</div>
                        		</div>";
                                $boqx_contents_receipt_add = "<div tabindex='0' class='AddSubresourceButton___2PetQjcb' style='display:none' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
                        				 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
                        				 <span class='AddSubresourceButton__message___2vsNCBXi'>Add a receipt</span>
                        			</div>";
                                $boqx_contents_receipt_show = "<div class='TaskShow___2LNLUMGe ReceiptShow__HlXgL0Lm' data-aid='TaskShow' data-cid='$cid' style='display:none' draggable='true'>
                    					<div>
                                            <nav class='TaskShow__actions___3dCdQMej ReceiptShow__actions__DhD8Fs3C TaskShow__actions--unfocused___3SQSv294'>
                            					<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
                            						$delete
                            					</button>
                            				</nav>
                    					</div>
                                        <div>
                                            <span class='TaskShow__title___O4DM7q tracker_markup section-add-boqx_contents_receipt-marker1'></span>
                            				<span class='TaskShow__description___qpuz67f tracker_markup'></span>
                        					<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
                                        </div>
                        			</div>";
                                $boqx_contents_receipt_edit = "
                                    <div class='TaskEdit___1Xmiy6lz' data-aid='TaskEdit' data-cid='$cid'>
                                        $hidden_fields
                        			    <a class='collapser collapser-receipt' id='receipt_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
                        				<section class='TaskEdit__descriptionContainer___3NOvIiZo info_box'>
                                            <fieldset class='name'>
                                            	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
                        							<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 400px;'>
                                    					<div class='AutosizeTextarea__container___31scfkZp' style>
                                    					    $service_marker_title
                                    					</div>
                                    				</div>
                        							<button data-aid='addReceiptButton'  type='submit'  class='ReceiptAdd__submit___lS0kknw9 std egg' style='margin: 3px 3px 0 15px;order: 2;' data-cid='$cid' data-parent_cid='$parent_cid' data-presence='$presentation'>Add</button>
                                    			</div>
                                    		</fieldset>
                                            <section class='receipt-header'>
                                                $form_header
                                            </section>
                                            <section class='receipt-items'>
                                             	<div class='boqx-pallet' data-cid='$cid'>
                                             		<h5 style='padding:10px 0 0 10px;'>Items</h5>
                                             		$line_items_header
                                             	</div>
                                                $line_items_footer
                                            </section>
                        				</section>
                        			</div>";

                		        break;
				/****************************************
*add********** $section = 'boqx_contents_receipt' $snippet= default *****************************************************************
				 ****************************************/
                		    default:
                		        $hidden[] =['name'=>"jot[$cid][boqx]",
        		                            'value' => $parent_cid];
        		                $hidden[] =['name'=>"jot[$cid][cid]",
        		                            'value' => $cid];
                                $hidden[] =['name'=>"jot[$cid][aspect]",
        		                            'value' => 'receipt',
        		                            'data-focus-id' => "Aspect--{$cid}"];
        		                $hidden[] =['name'=>"jot[$cid][fill_level]",
        		                            'value' => '0',
        		                            'data-focus-id' => "FillLevel--{$cid}"];
        		                $title_input             = elgg_view('input/text'  , ['name' => "jot[$cid][title]"             , 'class'=>'receipt-input'  , 'placeholder' => 'Receipt name', 'required'=>'']);
        		                $merchant = elgg_view('output/span', ['content'=>elgg_view('input/grouppicker', ['name' => "jot[$cid][merchant]",
                                                                                                                 'limit'=> 1,
                                                                                                                 'autocomplete'=>'on',
                                                                                    				            ]),
                                                                      'options' => ['data-focus-id'=> "MerchantAdd--$cid"],
                                		                              'class'=>'receipt-input',]);
                                $associate_label         = 'Sales Assoc.';
                                $asset_input             = elgg_view('input/text'  , ['name' => "jot[$cid][asset]"             , 'class'=>'receipt-input']);
                                $cashier                 = elgg_view('input/text'  , ['name' => "jot[$cid][cashier]"           , 'class'=>'receipt-input']);
                                $transaction_date_label  = 'Purchase Date';
                                $moment_label_receipt    = elgg_view('output/span' , ['content'=>'Date'                                                       , 'class'=>'receipt-input']);
                                $moment                  = elgg_view('input/date'  , ['name' => "jot[$cid][moment]"            , 'class'=>'receipt-input'   , 'value' => strtotime("now")]);
                                $actor_label             = 'Buyer';
                                $purchased_by            = elgg_view('input/text'  , ['name' => "jot[$cid][purchased_by]"      , 'class'=>'receipt-input']);
                                $order_no_label          = 'Order #';
                                $order_no                = elgg_view('input/text'  , ['name' => "jot[$cid][order_no]"          , 'class'=>'receipt-input']);
                                $preorder_label          = 'Ordered';
                                $preorder_options        =                           ['name'=>"jot[$cid][preorder_flag]"       , 'class'=>'preorder-flag'   ,'label_class'=>'receipt-input'];
                                $preorder_flag           = elgg_view('input/switchbox',$preorder_options);
                                $preorder_style          = 'visibility:hidden';
                                $delivery_date_label     = 'Scheduled date';
                                $delivery_date           = elgg_view('input/date'  , ['name'=>"jot[$cid][delivery_date]"       , 'class'=>'receipt-input'   , 'style'=>'width:100px;']);
                                $purchase_order_no_label = 'PO #';
                                $purchase_order_no       = elgg_view('input/text'  , ['name' => "jot[$cid][purchase_order_no]" , 'class'=>'receipt-input'   , 'style'=>'width:100px;']);
                                $invoice_no_label        = 'Invoice #';
                            	$invoice_no              = elgg_view('input/text'  , ['name' => "jot[$cid][invoice_no]"        , 'class'=>'receipt-input']);
                                $document_no             = elgg_view('input/text'  , ['name' => "jot[$cid][document_no]"       , 'class'=>'receipt-input']);
                                $transaction_no_label    = 'Receipt #';
                                $receipt_no              = elgg_view('input/text'  , ['name' => "jot[$cid][receipt_no]"        , 'class'=>'receipt-input']);
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
                            	$service_marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[$cid][title]' placeholder='Receipt name'></textarea>";
                            	$service_marker_description = "<textarea name='jot[$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
                            	
                        		$shipping_cost  = elgg_view('input/text', ['name' => "jot[$cid][shipping_cost]", 'data-qid'=>"$cid", 'data-name'=>'shipping_cost', 'data-focus-id'=>"ShippingCost--{$cid}", 'class'=> 'nString']);
                        		$sales_tax      = elgg_view('input/text', ['name' => "jot[$cid][sales_tax]", 'data-qid'=>"$cid", 'data-name'=>'sales_tax', 'data-focus-id'=>"SalesTax--{$cid}", 'class'=> 'nString']);
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
                        		//$first_line = elgg_view('forms/transfers/edit',['perspective'=>$perspective, 'presentation'=>$presentation, 'section'=>'boqx_contents_receipt', 'snippet'=>'receipt_item', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                        		$first_line = elgg_view('forms/transfers/edit',['perspective'=>$perspective, 'presentation'=>$presentation, 'display_state'=>$perspective, 'section'=>'boqx_contents', 'snippet'=>'single_thing', 'aspect'=>'receipt_item', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                        		$first_line_properties = elgg_view('forms/transfers/edit',['perspective'=>$perspective, 'presentation'=>$presentation, 'section'=>'boqx_contents_receipt', 'snippet'=>'receipt_item_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
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
                        	    		<a class='collapser collapser-receipt-item' id='story_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
                        				<section class='TaskEdit__descriptionContainer___3NOvIiZo info_box'>
                                            <fieldset class='name'>
                                            	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
                        							<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 400px;'>
                                    					<div class='AutosizeTextarea__container___31scfkZp' style>
                                    					    $service_marker_title
                                    					</div>
                                    				</div>
                        							<button data-aid='addReceiptButton'  type='submit'  class='ReceiptAdd__submit___lS0kknw9 std egg' style='margin: 3px 3px 0 15px;order: 2;' data-cid='$cid' data-parent_cid='$parent_cid' data-presence='$presentation'>Add</button>
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
                		}
//Note: $qid receives the value of $cid
/*                		$form .="<div id='$qid' class='ServiceEffort__26XCaBQk boqx_contents_receipt-marker1' data-qid='$cid' data-parent-cid='$parent_cid' data-cid='$cid' data-guid='$guid'>
                			$boqx_contents_receipt_add
                			$boqx_contents_receipt_show
                			$boqx_contents_receipt_edit
                		</div>";
*/             			break;
				/****************************************
*add********** $section = 'boqx_contents_receipt' $snippet='service_item' 'receipt_item' *****************************************************************
				 ****************************************/
/*             		case 'service_item_xxx':
             		case 'receipt_item_xxx':
                        $aspect        = elgg_extract('aspect', $vars, 'receipt_item');
                        $element = 'receipt_item';
		                $delete = elgg_format_element("span",['class'=>'remove-receipt-item'], elgg_format_element('a', ['title' =>'remove receipt item'],
                                                                    	                                                elgg_view_icon('delete-alt')));
		                switch($presentation){
		                    case 'pallet':
                     			unset($hidden, $hidden_fields);
        		                $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
        		                $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => $aspect      , 'data-focus-id' => "Aspect--{$cid}"];
        		                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => '0'            , 'data-focus-id' => "FillLevel--{$cid}"];
        		                $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => "$n"           , 'data-focus-id' => "SortOrder--{$cid}"];
        		                if (!empty($hidden)){                
                                    foreach($hidden as $key=>$field){
                                        $hidden_fields .= elgg_view('input/hidden', $field);}}
		                        //$qid_n = "{$cid}_{$n}";
		                        $horizontal_offset = '-140';
		                        $receipt_item_header_qty   = 'Quantity';
                                $receipt_item_header_tax   = 'Taxable';
                                $receipt_item_header_cost  = 'Item Cost';
                                $receipt_item_header_total = 'Total Cost';
        		                
                                
		                        $style_add = $style_show = $style_edit = "style='display:none;'";
		                        $edit_properties_button = elgg_view('output/url', ['data-cid'            => $cid,
        														                   'class'               => "ItemEdit__showContainer",
		                                                                           'text'                => elgg_view_icon('settings-alt').'Item Details', 
        												                           'title'               => 'show properties',]);
		                        $receipt_item_title        = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[$cid][title]' placeholder='Item name'></textarea>";
//                    	        $receipt_item_description  = "<textarea name='jot[$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' placeholder='Describe the item'></textarea>";
                    	        $receipt_item_description  = "<div data-aid='Description' class='Description___3oUx83yQ'><h4 id='description$cid'>Description</h4><div class='DescriptionShow___3-QsNMNj DescriptionShow__placeholder___1NuiicbF' tabindex='0' data-aid='renderedDescription' data-focus-id='DescriptionShow--$cid'>Add a description</div>
                    	                                      <div class='DescriptionEdit___1FO6wKeX'>
                                                                <div class='markdownHelpContainer___32_mTqNL'>
                                                                    <div>
                                                                        <button class='DescriptionEdit__tab___7lFo9PZA'>Write</button>
                                                                        <button class='DescriptionEdit__tab___7lFo9PZA DescriptionEdit__tab--disabled___1x2-iUxr'>Preview</button>
                                                                    </div>
                                                                    <a href='/help/markdown' class='markdownHelp___2yFSQCip' target='_blank'>Formatting help</a>
                                                                </div>
                                                                <div data-aid='editor' class='textContainer___2EcYJKlD'>
                                                                    <div>
                                                                        <div class='DescriptionEdit__write___207LwO1n'>
                                                                            <div class='AutosizeTextarea___2iWScFt6'>
                                                                                <div class='AutosizeTextarea__container___31scfkZp'>
                                                                                    <textarea name='jot[$cid][description]' aria-labeledby='description$cid' data-aid='textarea' data-cid='$cid' data-focus-id='DescriptionEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' placeholder='Add a description'></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class='DescriptionEdit__preview_____Tr424N'>
                                                                            <span class='tracker_markup'><p>Preview your <a href='/help/markdown' target='_blank' rel='noopener' tabindex='-1'>Markdown formatted</a> text here.</p></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class='controls___2K44hJCR'>
                                                                        <div>
                                                                            <button class='SMkCk__Button QbMBD__Button--primary _3olWk__Button--small button__save___2XVnhUJI' data-aid='save' data-cid='$cid' type='button'>Add description</button>
                                                                            <button class='SMkCk__Button ibMWB__Button--open _3olWk__Button--small' data-aid='cancel' data-cid='$cid' type='button'>Cancel</button>
                                                                        </div>
                                                                    <div class='Controls__text___B_l9ri3_'>
                                                                        <button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='AddEmoji' aria-label='Add emoji to description'>
                                                                            <span class='' title='Add emoji to description' style='background: url(https://assets.pivotaltracker.com/next/assets/next/2b4b3f66-emoji-light.svg) center center no-repeat;'></span>
                                                                        </button>
                                                                    <div class='Dropdown Dropdown--left StoryTemplateDropdown___3ctobFmT'>
                                                                        <div class='Dropdown__content' data-aid='StoryTemplateDropdown'>
                                                                            <button class='SMkCk__Button ibMWB__Button--open StoryTemplate__button___6_DPoMAr _1m_u1__Button--short' type='button'>
                                                                                <img title='Select story template' class='_3iG1d__IconButton__icon' src='https://assets.pivotaltracker.com/next/assets/next/b202db4f-story-templates.svg'>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>";
                            	$value                 = elgg_get_site_entity()->guid;
                            	$boqx_name             = 'category';
                                $options['aspects']    = quebx_boqx_aspect_options($boqx_name,['root'=>$value]);//, 'order_by_metadata'=>['name'=>'name','direction' => 'ASC', 'as' => 'text']]);
                                $options['boqx_name']  = $boqx_name;
                                $options['boqx_aspect']= 'category';
                                $options['boqx_value'] = $value;
                                $options['cid']        = $cid;
                                $options['menu_level'] = 1;
                                $options['boqx_class'] ='compartmentBoqx__Cdil2TkU';
                                $options['list_class'] ='pickList__Upq66A3H';
                                $options['item_class'] ='pickItem__GaGSmQJ6';
                                $options['crumb_class']='pickItem__ehybudK0';
                                $options['label_class']='pickLabel__2JR0Zrcl';
                                $weir_menu             = elgg_view('navigation/weir_menu', $options);
                
                                $marker_boqx_selector = "<div class='dropdown aspect' data-selector='boqx_aspect' data-cid='$cid'>  
                                        <input aria-hidden='true' type='hidden' name='jot[$cid][aspect]'>
                                      <a id='story_estimate_dropdown_$cid' class='selection item_0' tabindex='-1'><span>Select ...</span></a>
                                      <a id='story_estimate_dropdown_{$cid}_arrow' class='arrow target' tabindex='-1'></a>
                                      <section class='pickAspect__VRYE6ZAO closed'>
                                        $weir_menu
                                    </section>
                                  </div>";                            	
                    	        $receipt_item_category     = elgg_format_element('div', ['class'=>['info_box','free']],
                    	                                         elgg_format_element('div', ['class'=>'info'],
                    	                                              elgg_format_element('div', ['class'=>'row'],
                    	                                                  "<em>Category</em>".
                    	                                                  $marker_boqx_selector)));
                    	        $category_picker = elgg_view('input/category', ['presentation'=>$presentation, 'perspective'=>$perspective, 'parent_cid'=>$cid, 'add_leaf' => false,'category_class'=>'BoqxCategoryPick__gNSC0Iny','item_class'=>'CategoryDropdownItem___qgkw9JYv', 'root_name_override'=>'blah', 'show_section_headers'=>false]);
                    	        $line_item_properties      = $receipt_item_description;
        						//$line_item_properties     .= elgg_view('forms/market/profile', ['presentation'=>$presentation]);
        						
        						$line_item_properties     .= $receipt_item_category;
                    	        $line_item_properties     .= elgg_view('forms/market/edit', ['presentation'=>$presentation, 'perspective'=>$perspective, 'parent_cid'=>$cid]);    
                    	        switch ($display_state){case 'add':  unset($style_add);  break;
                    	                                case 'show': unset($style_show); break;
                    	                                case 'edit': $style_edit = "style='display:flex;'"; break;}
		                        $boqx_contents_add = "<div tabindex='0' class='AddSubresourceButton___oKRbUbg6' $style_add data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
                                                    				<span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
                                                    				<span class='AddSubresourceButton__message___2vsNCBXi'>Add an item</span>
                                                			  </div>";
                                $boqx_contents_show = "<div class='ItemShow_Btc471up' $style_show data-aid='TaskShow' data-cid='$cid' draggable='true'>
                    					<div>
                                            <nav class='ItemShow__actions__4AR4v8MP ItemShow__actions--unfocused___1df6Nh5x'>
                            					<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
                            						$delete
                            					</button>
                            				</nav>
                    					</div>
                                        <div class='ItemShow__line_gSpQE5Ao'>
                                            <span class='ItemShow__title__8tlRYJcP section-add-boqx_contents_receipt-marker1'></span>
                        					<span class='ItemShow__item_total__Dgd1dOSZ'></span>
                                        </div>
                        			</div>";
                                $boqx_contents_edit = "
                                    <div class='ItemEdit___7asBc1YY info_box free' $style_edit data-aid='ItemEdit' data-cid='$cid'>
                        			    $hidden_fields
                                        <div class='ItemEditValue'>
                            	    		<a class='collapser collapser-receipt-item' id='receipt_item_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
                                            <section class='ItemLedger__KY8DM3qs' data-cid='$cid'>
                                                <fieldset class='name'>
                                                	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
                                                        <div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 400px;'>
                                        					<div class='AutosizeTextarea__container___31scfkZp'>
                                        					    $receipt_item_title
                                        					</div>
                                        				</div>
                            							<button data-aid='addReceiptButton'  type='submit'  class='ReceiptItem__submit___u7pvMd9T std egg' style='margin: 3px 3px 0 15px;order: 2;' data-parent_cid='$parent_cid' data-cid='$cid' data-presence='$presentation'>Add</button>
                                        			</div>
                                        		</fieldset>
                                                <div class='row'>
                                                    <h2>Acquisition Details</h2>
                                    		    </div>
                                                <div class='row'>
                                                    <div class='column_01'>
                                                        <label for='jot[$cid][qty]'>$receipt_item_header_qty</label>
                                                    </div>
                                                    <div class='column_02'>
                                                        ".elgg_view('input/number',   ['name'=>"jot[$cid][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='column_01'>
                                                        <label for='jot[$cid][cost]'>$receipt_item_header_cost</label>
                                                        <div class='column_01b'>
                                                            <label for='jot[$cid][taxable]'>$receipt_item_header_tax</label>".
                                                            elgg_view('input/checkbox', ['name'=>"jot[$cid][taxable]",'value'=>1,'data-qid'=>$qid_n,'data-name'=>'taxable','default'=> false,])."
                                                        </div>
                                                    </div>
                                                    <div class='column_02'>
                                                        ".elgg_view('input/text', [ 'name'      => "jot[$cid][cost]",
                    										 						'class'     => 'last_line_item',
                    											                    'data-qid'  => $qid_n,
                    											                    'data-name' => 'cost',
                    				    											'class'     => 'nString',
                    															   ])."
                                                    </div>
                                                </div>
                                                <div class='row'>
                                                    <div class='column_01'>
                                                        <label>$receipt_item_header_total</label>
                                                    </div>
                                                    <div class='column_02'>
                                                        <span id='{$cid}_line_total'></span><span class='{$cid}_line_total_raw line_total_raw'></span>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <div class='ShowItemDetails__Uc3MWjrS'>
                                            <div class='ShowItemDetailsButton__qWXhMy9t' data-cid='$cid'>
                                                <h3>".elgg_view_icon('settings-alt')."Item Details</h3>
                                            </div>
                            				<section class='ItemEdit__descriptionContainer___Mr67pXjd ItemEditContainer__$cid' >
                                                <section class='receipt-item-properties receipt-item-properties__$cid'>
                                                 		$line_item_properties
                                                </section>
                            				</section>
                                        </div>
                        			</div>";
                                $receipt_item_row = elgg_format_element('div',['class'=>['boqx-receipt-item','ReceiptItem__nhjb4ONn'], 'id'=>"$cid", 'boqx-fill-level'=>'0'], $boqx_contents_add.$boqx_contents_show.$boqx_contents_edit);
		                        break;
		                    default:
                     			unset($hidden, $hidden_fields);
                                $hidden[] =['name'=>"jot[$parent_cid][$cid][$n][aspect]",
        		                            'value' => 'receipt_item',
        		                            'data-focus-id' => "Aspect--{$qid_n}"];
        		                $hidden[] =['name'=>"jot[$parent_cid][$cid][$n][fill_level]",
        		                            'value' => '0',
        		                            'data-focus-id' => "FillLevel--{$qid_n}"];
                				if (!empty($hidden)){                
                                    foreach($hidden as $key=>$field){
                                        $hidden_fields .= elgg_view('input/hidden', $field);}}
		                        $horizontal_offset = '-15';
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
        														                  'data-horizontal-offset'=>$horizontal_offset,
        												                          'text'                => elgg_view_icon('settings-alt'), 
        												                          'title'               => 'set properties',
        						]);
        						$receipt_item_row ="
        								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
                                            $hidden_fields
                                        	<div class='rTableCell'>$delete</div>
        									<div class='rTableCell'>$unpack_icon</div>
        									<div class='rTableCell'>".elgg_view('input/number',   ['name'=>"jot[$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
        									<div class='rTableCell'>$set_properties_button "
        									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]",'class'=>'rTableform90','id'=>'line_item_input','data-name'=>'title','data-jq-dropdown' => '#'.$cid.'_'.$n,'data-horizontal-offset'=>$horizontal_offset,])."</div>
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
		                }
             			break;*/
				/****************************************
*add********** $section = 'boqx_contents_receipt' $snippet='service_item_properties' 'receipt_item_properties'***********
				 ****************************************/
             	    case 'service_item_properties':
					case 'receipt_item_properties':
					    $form_action = 'partials/jot_form_elements';
					    $body_vars   = ['element'        => 'properties_receipt_item',
	                                    'presentation'   => $presentation,
					                    'aspect'         => $aspect,
					                    'parent_cid'     => $parent_cid,
                                  	    'cid'            => $cid,
                                  	    'n'              => $n,
					                    'data_prefix'    => "jot[$cid][$n][",
					                   ];
					    $content = elgg_view($form_action,$body_vars);
             		    break;
				/****************************************
*add********** $section = 'boqx_contents_receipt' ***********
				 ****************************************/
					default:
					    $view = 'forms/transfers/edit';
						$add_receipt = elgg_view($view,[ 
												  'perspective' => $perspective,
	                                              'presentation'=> $presentation,
												  'section'     =>'boqx_contents_receipt', 
												  'snippet'     =>'marker1',
												  'effort'      => $effort,      
												  'parent_cid'  => $parent_cid, 
												  'cid'         => $service_cid, 
												  'n'           => $n,]);
             	}
             	break;
             	
				/****************************************
*add********** $section = 'boqx_contents_experience'    *****************************************************************
                ****************************************/
            case 'boqx_contents_experience':
			    $view = 'forms/experiences/edit';
			    $body_vars = ['title'           => 'New Experience',
            				  'qid'             => $qid,
            				  'cid'             => $parent_cid,
		                      'section'         => 'main_xxx',
						      'selected'        => true,
						      'presentation'    => $presentation,
				              'perspective'     => $perspective,
						      'action'          => $perspective,
				];
				$add_experience = elgg_view($view,$body_vars);
				break;
				/****************************************
*add********** $section = 'boqx_contents_books'    *****************************************************************
                ****************************************/
            case 'boqx_contents_books':
				unset($edit_things, $view_things);
				switch($snippet){
				/****************************************
*add********** $section = 'boqx_contents_books' $snippet='marker1'***********
				 ****************************************/
				    case 'marker1':
				    
				    break;
				/****************************************
*add********** $section = 'boqx_contents_books' $snippet='book_properties'***********
				 ****************************************/
             	    case 'book_properties':
				        $form_action = 'forms/market/profile';
					    $body_vars   = ['presentation'   => 'qboqx',
					                    'aspect'         => 'book',
					                    'selected'       => 'title',
	                                    'parent_cid'     => $parent_cid,
                                  	    'cid'            => $cid,
                                  	    'n'              => $n,
					                    'data_prefix'    => "jot[$cid][",
					                   ];
					    $content = elgg_view($form_action,$body_vars);
             		    break;
				/****************************************
*add********** $section = 'boqx_contents_books' $snippet = default ***********
				 ****************************************/
             		    default:
					    $view = 'forms/transfers/edit';
					    $add_book_button = elgg_view('output/url', [
    	                                          'title'       => 'Add a book',
    	                		                  'data-element'=> 'new_item',
                								  'data-qid'    => $qid,
                							 	  'data-cid'    => $service_cid,
    	                    					  'class'       => 'trigger-element add_marker',
    	                                          'tabindex'    => '-1']);
        				$add_books = elgg_view($view,[ 
        										  'perspective' => $perspective,
        										  'section'     =>'boqx_contents', 
        										  'snippet'     =>'books',
        										  'effort'      => $effort,      
        										  'parent_cid'  => $parent_cid, 
        										  'n'           => $n,]);        
						$add_book = elgg_view($view,[ 
												  'perspective' => $perspective,
												  'section'     =>'boqx_contents_book', 
												  'snippet'     =>'marker1',
												  'effort'      => $effort,      
												  'parent_cid'  => $parent_cid, 
												  'cid'         => $service_cid, 
												  'n'           => $n,]);
				}
				break;
				/****************************************
*add********** $section = 'emoji_bar ***********
				 ****************************************/
            case 'emoji_bar':
                $emoji_bar = elgg_view('elements/emoji_bar');
                break;
				/****************************************
*add********** $section = default ***********
				 ****************************************/
            default:
				    
				    break;
        }
    break;
/****************************************
 * $perspective = 'edit'                      *****************************************************************************
 ****************************************/
    case 'edit':
    switch ($section){
        /****************************************
 *edit********** $section = 'partials'       *****************************************************************
        ****************************************/
        case 'partials':
            switch ($snippet){
        /****************************************
 *edit********** $section = 'partials' $snippet='new_receipt *****************************************************************
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
            		                                       . elgg_view($form,['section'=>'things_bundle', 'perspective'=>$perspective, 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
            		$story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
            		                                          'options'=> ['data-parent-cid'=>$parent_cid, 'data-cid'=>$cid, 'data-qid'=>$qid],
            		                                          'content'=>$hidden_fields.$edit_details]);
            			            		            		            	
            		$form_body = str_replace('<<cid>>', $cid, $story_model);                                   $display.= 'form_elements 90 new_receipt>$cid: '.$cid.'<br>form_elements  90 new_receipt>$service_cid: '.$service_cid.'<br>';
                    break;
            }
            break;
        /****************************************
 *edit********** $section = 'things_boqx'  *****************************************************************
        ****************************************/
            case 'things_boqx':

                $hidden[] =['name'=>"jot[subtype]",
                            'value' => 'boqx'];
                $hidden[] =['name'=>"jot[cid]",
                            'value' => "$cid"];
                $hidden[] =['name'=>"jot[guid]",
                            'value' => "$guid"];
                if (!empty($hidden)){                
                    foreach($hidden as $key=>$field){
                        $hidden_fields .= elgg_view('input/hidden', $field);}}
                $expander = elgg_view("output/url", [
                    'text'    => '',
                    'class'   => 'expander undraggable',
                    'id'      => 'toggle_marker',
                    'data-guid'=> $guid,
                    'data-cid'=> $cid,
                    'tabindex'=> '-1',]);
                $maximize_button = "<a type='button' class='autosaves maximize hoverable' id='story_maximize_$cid' tabindex='-1' title='Switch to a full view'></a>";
                $view_id = '<<view_id>>';
                $url = elgg_get_site_url().'jot';
				$data_guid = "data-guid='$guid'";
                Switch ($snippet){
                    /****************************************
*edit********** $section = 'things_boqx' $snippet = 'contents'  *****************************************************************
                     ****************************************/
                    case 'contents':
                        $parent_cid = $boqx_cid;                                                              $display .= '143 boqx_cid = '.$boqx_cid.'<br>143 $cid = '.$cid.'<br>143 $service_cid = '.$service_cid.'<br>';
                                                                                                       //register_error($display);
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
							'guid'              => $guid,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'boqx_cid'          => $boqx_cid]);
                         $boqx_contents_edit = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'section'           => $section,
                            'snippet'           =>'contents_edit',
                           'show_view_summary'  => false,
							'guid'              => $guid,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'boqx_cid'          => $boqx_cid]);
                         $boqx_contents_show = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'section'           => $section,
                            'snippet'           =>'contents_show',
                            'show_view_summary' => false,
							'guid'              => $guid,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'boqx_cid'          => $boqx_cid]);
                         break;
                    /****************************************
*edit********** $section = 'things_boqx' $snippet = 'contents_add'  *****************************************************************
                     ****************************************/
                    case 'contents_add':
                        $form_body = "
                             <div tabindex='0' class='AddSubresourceButton___S1LFUcMd' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
                                 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
                                 <span class='AddSubresourceButton__message___2vsNCBXi'>$title</span>
                             </div>";
                        break;
                    /****************************************
*edit********** $section = 'things_boqx' $snippet = 'contents_show'  *****************************************************************
                     ****************************************/
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
                    /****************************************
*edit********** $section = 'things_boqx' $snippet = 'contents_edit'  *****************************************************************
                     ****************************************/
                    case 'contents_edit':
                        $boqx_contents = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'section'           => $section,
                            'snippet'           =>'edit_boqx',
                            'show_view_summary' => false,
							'guid'              => $guid,
                            'boqx_cid'          => $boqx_cid,	
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'service_cid'       => $service_cid]);   
                        $form_body = "
                             <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-cid=$cid>
                                $boqx_contents
                             </div>";
                        break;
						/****************************************
*edit********** $section = 'things_boqx' $snippet = 'edit_boqx'  *****************************************************************
				 ****************************************/
				    case 'edit_boqx':
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
                                . elgg_view($form,['section'=>'things_bundle', 'perspective'=>$perspective, 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
                            $story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
                                                                      'options'=> ['parent_cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                                                      'content'=>$hidden_fields.$edit_details]);
                            
                            $form_body = str_replace('<<cid>>', $cid, $story_model);
                            break;
				/****************************************
*edit********** $section = 'things_boqx' $snippet = default  *****************************************************************
				 ****************************************/
                    default:
                        $things_boqx = elgg_view('forms/transfers/edit',
                           ['section'     => 'things_boqx',
                            'perspective' => $perspective,
                            'snippet'     => 'contents',
//                            'parent_cid'  => $parent_cid,
                            'n'           => $n,
							'guid'        => $guid,
                            'cid'         => $cid,
                      //initialize $boqx_cid
                            'boqx_cid'    => $cid,
//                            'service_cid' => $service_cid,
                            'qid'         => $qid]);                                        $display .= '315 parent_cid = '.$parent_cid.'<br>315 $cid = '.$cid.'<br>';
                                                                                            //register_error($display);
                        break;
                }
                
                break;
				/****************************************
*edit********** $section = 'things_bundle'                *****************************************************************
				 ****************************************/
           case 'things_bundle':                                                             $display .= '320 things_bundle>$cid: '.$cid.'<br>588 things_bundle>$service_cid: '.$service_cid.'<br>';
               unset($form_body, $disabled, $hidden, $id_value);
               $id_value = $guid;
               $loose_things_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $action,
                           'section'        => 'boqx_contents_loose_things',
                    	   'guid'           => $guid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
               $receipts_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $action,
                           'section'        => 'boqx_contents_receipt',
                    	   'guid'           => $guid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
               
               $books_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $action,
                           'section'        => 'boqx_contents_books',
                    	   'guid'           => $guid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
                
                $contents_panel = "<section class='contents full'>
		                              $loose_things_panel
		                              $receipts_panel
		                              $books_panel
		                           </section>";
            	$delete_button = "<label class=remove-card>". 
                            	    elgg_view('output/url', array(
                            	        'title'    =>'remove effort card',
                            	        'class'    =>'remove-card',
                            	        'text'     => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$maximize_button = "<button type='button' class='autosaves maximize hoverable' id='story_maximize_$cid' tabindex='-1' title='Switch to a full view'></a>";
                $delete = elgg_view("output/span", ["class"=>"remove-card", "content"=>$delete_button]);
                $collapser = elgg_extract('collapser', $vars, "<a class='collapser collapser-effort' id='effort_collapser_$cid' data-cid='$cid' tabindex='-1'></a>");
            	
            	$edit_boqx = "<button data-aid='editEffortButton' class='ThingsBundle__submit___q0kFhFBf autosaves button std egg' type='submit' tabindex='-1' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid' data-guid='$guid'>Save</button>";
            	$cancel_effort = "<button class='autosaves cancel clear' type='reset' id='boqx_submit_cancel_$cid' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid' data-guid='$guid' tabindex='-1'>Cancel</button>";
            	$url = elgg_get_site_url().'jot';
            	$marker_title         = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' style='margin: 8px;' name='jot[$cid][title]' value='$title' data-name='title' placeholder='Boqx name'>$title</textarea>";
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
            	$owner_names           = explode(' ',$owner->name);
            	$owner_initials        = str_split($owner_names[0])[0].str_split($owner_names[1])[0];
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[$cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                     elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>$owner_initials])])])]);
            	$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',]);     	
            	break;
        
               /****************************************
*edit********** $section = 'boqx_contents'                *****************************************************************
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
                    	                                            'guid'              => $guid,
            														'parent_cid'        => $parent_cid,
		            			                                    'cid'               => $cid,
            			                                            'service_cid'       => $service_cid,
		            			                                    'qid'               => $qid,]);
            	$marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[$parent_cid][$cid][title]' data-name='title' placeholder='Give this transfer a name'></textarea>";
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
                    	                    'guid'         => $guid,
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
			        case 'loose_things':
			            unset($hidden, $hidden_fields, $options, $line_items);
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][subtype]",
                                    'value' => 'boqx'];
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][aspect]",
                                    'value' => 'loose things'];
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                        $options = ['type'           => 'object',
                                    'subtype'        => 'boqx',
                                    'container_guid' => $guid,];                                   $display .= '1444 $guid = '.$guid.'<br>';
                        $loose_things = elgg_get_entities($options);                               
                        $view = 'forms/transfers/edit';
                        if ($loose_things)
                            foreach($loose_things as $loose_thing){
                				$line_items .= elgg_view($view,[ 
        									  'perspective' => $perspective,
        									  'section'     =>'boqx_contents', 
        									  'snippet'     =>'single_thing',
        									  'effort'      => $effort,
                            	              'guid'        => $loose_thing->guid,      
        									  'parent_cid'  => $parent_cid, 
        									  'cid'         => $cid, 
        									  'n'           => $n,]);
                				$line_item_properties .= elgg_view($view,['section'=>'boqx_contents_receipt', 'perspective'=>$perspective, 'snippet'=>'receipt_item_properties', 'guid'=>$loose_thing->guid, 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                        }
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
        				$loose_things_line_items = "          
                		    <div class='rTable line_items loose-line-items'>
                                <div class='rTableBody'>
                                    $hidden_fields
                					<div id='sortable'>
                						<div class='rTableRow pin'>
                			                <div class='rTableCell'>$loose_things_header_selector</div>
                			    			<div class='rTableHead'>$loose_things_header_qty</div>
                			    			<div class='rTableHead'>$loose_things_header_item</div>
                			    		</div>
                			    		$line_items
                			    		<div id={$cid}_new_line_items class='new_line_items'></div>
                			    	</div>
                		    	</div>
                		    </div>
                    		<div id={$cid}_line_item_property_cards>
                    		      $line_item_properties
                            </div>";
                        break;
                    case 'single_thing':
             			unset($hidden, $hidden_fields);
             			$hidden[] =['name'=>"jot[$parent_cid][$cid][$n][subtype]",
             			            'value' => 'boqx'];
             			$hidden[] =['name'=>"jot[$parent_cid][$cid][$n][aspect]",
                     			    'value' => 'thing'];
             			$hidden[] =['name'=>"jot[$parent_cid][$cid][$n][guid]",
                     			    'value' => $guid];
             			if (!empty($hidden)){
             			    foreach($hidden as $key=>$field){
             			        $hidden_fields .= elgg_view('input/hidden', $field);}}
		                $element = 'loose_item';
						$delete = elgg_view('output/url', ['title'        => 'remove this thing',
						                                   'class'        => 'remove-loose-thing',
													       'data-element' => $element,
														   'data-guid'    => $guid,
														   'data-qid'     => $qid_n,
													       'style'        => 'cursor:pointer',
													       'text'         => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$set_properties_button = elgg_view('output/url', ['data-jq-dropdown'      => '#'.$cid.'_'.$n,
						                                                  'data-qid'              => $qid_n,
														                  'data-horizontal-offset'=> "-15",
							 					                          'text'                  => elgg_view_icon('settings-alt'), 
												                          'title'                 => 'set properties',
						]);
						$form_body ="
								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
                                    <div class='rTableCell'>$delete</div>
									<div class='rTableCell'>$hidden_fields".elgg_view('input/number',   ['name'=>"jot[$parent_cid][$cid][$n][qty]", 'value'=>$jot->qty, 'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$set_properties_button "
									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]", 'value'=>$title, 'style'=>'width:95%', 'data-name'=>'title','data-jq-dropdown' => '#'.$cid.'_'.$n,])."</div>
				                </div>";
             		    
             		    break;
					default:
						$boqx_contents = elgg_view('forms/transfers/edit',[
	                                        'section'     => 'boqx_contents', 
                                            'action'      => $action, 
                                            'snippet'     => 'marker', 
                                            'parent_cid'  => $parent_cid, 
                                            'n'           => $n,
											'guid'        => $guid,
                                            'cid'         => $cid, 
                                            'service_cid' => $service_cid, 
                                            'qid'         => $qid]);
	                break;
				}
				break;
				/****************************************
*edit********** $section = 'boqx_contents_loose_things'    *****************************************************************
                ****************************************/
            case 'boqx_contents_loose_things':
				unset($edit_things, $view_things);
            	$add_thing_button = elgg_view('output/url', [
	                                        'title'        => 'Add something',
	                		                'data-element' => 'new_item',
											'data-guid'    => $guid,
            								'data-qid'     => $qid,
            								'data-cid'     => $service_cid,
	                    					'class'        => 'trigger-element add_marker',
	                                        'tabindex'     => '-1' 
	                    					]);
			    $view = 'forms/transfers/edit';
				$edit_things = elgg_view($view,[ 
										  'perspective' => $perspective,
                                          'presentation'   => $presentation,
										  'section'     =>'boqx_contents', 
										  'snippet'     =>'loose_things',
										  'effort'      => $effort,
										  'guid'        => $guid,
										  'parent_cid'  => $parent_cid, 
										  'n'           => $n,]);
				break;
				/****************************************
*edit********** $section = 'boqx_contents_receipt'         *****************************************************************
				 ****************************************/
             case 'boqx_contents_receipt':
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
	                    					'class'    => 'trigger-element add_marker',
	                                        'tabindex' => '-1' 
	                    					));
             	switch ($snippet){
             		case 'marker1':
             			unset($hidden, $hidden_fields);
		                $unpack_icon = "<span title='Unpack all'>"
								           .elgg_view('input/checkbox', ['name'=>"jot[$parent_cid][$cid][$n][unpack]",'value'=>1,'class'=>'boqx-unpack closed','data-cid'=>$cid,'data-name'=>'unpack-all','default'=> false,])
						              ."</span>";
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][aspect]",
		                            'value' => 'receipt',
                                    'data-focus-id' => "Aspect--{$cid}"];
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][guid]",
                                    'value' => $guid,];
		                $hidden[] =['name'=>"jot[$parent_cid][$cid][fill_level]",
		                            'value' => 'empty',
		                            'data-focus-id' => "FillLevel--{$cid}"];
		                $title_input             = elgg_view('input/text'  , ['name' => "jot[$parent_cid][$cid][title]", 'value'=>$title, 'class'=>'receipt-input'  , 'placeholder' => 'Receipt name', 'required'=>'']);
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
                        $receipt_item_header_selector = elgg_view('output/span', ['content'=> elgg_view('output/url', ['text'            => '+',
                    																			 					   'href'            => '#',
                    																								   'class'           => 'elgg-button-submit-element new-item',
                    																								   'data-element'    => 'new_receipt_item',
                    																								   'data-guid'       => $guid,
                    																								   'data-qid'        => $cid,
                                                                            					    				   'data-cid'        => $cid,
                                                                            	    								   'data-parent-cid' => $parent_cid,
                                                                            					    				   'data-rows'       => 1,
                                                                            					    				   'data-last-row'   => 1,
                                                                                                                       'data-presence'   => 'panel'
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
                		$first_line = elgg_view('forms/transfers/edit',['section'=>'boqx_contents_receipt', 'perspective'=>$perspective, 'snippet'=>'receipt_item', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                		$first_line_properties = elgg_view('forms/transfers/edit',['section'=>'boqx_contents_receipt', 'perspective'=>$perspective, 'snippet'=>'receipt_item_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
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
                	    		<a class='collapser collapser-receipt-item' id='story_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
                				<section class='TaskEdit__descriptionContainer___3NOvIiZo info_box'>
                                    <fieldset class='name'>
                                    	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
                							<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 400px;'>
                            					<div class='AutosizeTextarea__container___31scfkZp' style>
                            					    $service_marker_title
                            					</div>
                            				</div>
                							<button data-aid='addReceiptButton'  type='submit'  class='ReceiptAdd__submit___lS0kknw9 std egg' style='margin: 3px 3px 0 15px;order: 2;' data-cid='$cid' data-parent_cid='$parent_cid' data-presence='$presentation'>Add</button>
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
									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]",'class'=>'rTableform90','id'=>'line_item_input','data-name'=>'title','data-jq-dropdown' => '#'.$cid.'_'.$n,])."</div>
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
             		    break;
					default:
					    $view = 'forms/transfers/edit';
						$add_receipt = elgg_view($view,[ 
												  'perspective' => $perspective,
												  'section'     =>'boqx_contents_receipt', 
												  'snippet'     =>'marker1',
												  'effort'      => $effort,      
												  'parent_cid'  => $parent_cid, 
												  'cid'         => $service_cid, 
												  'n'           => $n,]);
             	}
             	break;
        }
    break;
/****************************************
 * $perspective = 'view'                      *****************************************************************************
 ****************************************/
	case 'view':
		switch ($section){
        /****************************************
 *view********** $section = 'partials'       *****************************************************************
        ****************************************/
        case 'partials':
            switch ($snippet){
        /****************************************
 *view********** $section = 'partials' $snippet='view_receipt *****************************************************************
        ****************************************/
                case 'view_receipt':
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
            		                                       . elgg_view($form,['section'=>'things_bundle', 'perspective'=>$perspective, 'action'=>$action, 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
            		$story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
            		                                          'options'=> ['data-parent-cid'=>$parent_cid, 'data-cid'=>$cid, 'data-qid'=>$qid],
            		                                          'content'=>$hidden_fields.$edit_details]);
            			            		            		            	
            		$form_body = str_replace('<<cid>>', $cid, $story_model);                                   $display.= 'form_elements 90 new_receipt>$cid: '.$cid.'<br>form_elements  90 new_receipt>$service_cid: '.$service_cid.'<br>';
                    break;
            }
            break;
        /****************************************
 *view********** $section = 'things_boqx'  *****************************************************************
        ****************************************/
            case 'things_boqx':
                $expander = elgg_view("output/url", [
                    'text'    => '',
                    'class'   => 'expander undraggable',
                    'id'      => 'toggle_marker',
                    'data-cid'=> $cid,
                    'tabindex'=> '-1',]);
                $maximize_button = "<a type='button' class='autosaves maximize hoverable' id='story_maximize_$cid' tabindex='-1' title='Switch to a full view'></a>";
                $view_id = '<<view_id>>';
                $url = elgg_get_site_url().'jot';
				$data_guid = "data-guid='$guid'";
                Switch ($snippet){
                    /****************************************
*view********** $section = 'things_boqx' $snippet = 'contents'  *****************************************************************
                     ****************************************/
                    case 'contents':
                        $parent_cid = $boqx_cid;                                                       $display .= '1157 $guid = '.$guid.'<br>1157 $cid = '.$cid.'<br>143 $service_cid = '.$service_cid.'<br>';
                                                                                                       
                        unset($hidden, $hidden_fields);
                         $boqx_contents_show = elgg_view('forms/transfers/edit',[
                            'perspective'       => $perspective,
                            'presentation'      => $presentation,
                            'section'           => $section,
                            'snippet'           =>'contents_show',
                            'show_view_summary' => false,
                            'guid'              => $guid,
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'boqx_cid'          => $boqx_cid]);
                         break;
                    /****************************************
*view********** $section = 'things_boqx' $snippet = 'contents_show'  *****************************************************************
                     ****************************************/
                    case 'contents_show':
                        $form_body = "
                             <div class='BoqxShow__lsk3jlWE' data-aid='TaskShow' data-cid=$cid draggable='true'>
                                 <span class='TaskShow__title___O4DM7q tracker_markup section-add-boqx_contents-marker'>$title</span>
                                 <nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
                                     <button class='IconButton___4wjSqnXU IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
                                     $delete
                                     </button>
                                 </nav>
                                 <span class='TaskShow__description___qpuz67f tracker_markup'></span>
                                 <span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
                             </div>";
                        break;
                    /****************************************
*view********** $section = 'things_boqx' $snippet = 'contents_edit'  *****************************************************************
                     ****************************************/
                    case 'contents_edit':
                        $hidden[] =['name'=>"jot[subtype]", 'value' => 'boqx'];
                        $hidden[] =['name'=>"jot[cid]"    , 'value' => "$cid"];
                        $hidden[] =['name'=>"jot[guid]"   , 'value' => "$guid"];
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                        $view          = 'forms/transfers/edit';
                        $boqx_contents = elgg_view($view,[
                            'perspective'       => $perspective,
                            'section'           => $section,
                            'snippet'           =>'edit_boqx',
                            'show_view_summary' => false,
							'guid'              => $guid,
                            'boqx_cid'          => $boqx_cid,	
                            'parent_cid'        => $parent_cid,
                            'cid'               => $cid,
                            'service_cid'       => $service_cid]);   
                        $form_body = "
                                $hidden_fields
                             <div class='EffortEdit_fZJyC62e' data-aid='TaskEdit' data-parent-cid='$parent_cid' data-cid=$cid style='display:block;'>
                                $boqx_contents
                             </div>";
                        break;
				/****************************************
*view********** $section = 'things_boqx' $snippet = 'edit_boqx'  *****************************************************************
				 ****************************************/
				    case 'edit_boqx':
                        unset($hidden, $hidden_fields);
                        $view              = 'forms/transfers/edit';
                        $show_view_summary = elgg_extract('show_view_summary', $vars, true);
                        $delete_button     = "<label class=remove-progress-marker>".
                            elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
                            "</label>";
                            $delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
                            $expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'=> 'toggle_marker', 'data-cid'=>$cid, 'data-qid'=>$qid, 'tabindex'=> '-1',]);
                            $story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
                            $preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
                            $collapser    = "<a class='autosaves collapser-boqx' id='boqx_collapser_$cid' data-cid='$cid' tabindex='-1'></a>";
                            if ($show_view_summary){
                                $view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
                            }
                            $edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
                                'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                'content'=>$view_summary
                                . elgg_view($view,['section'=>'things_bundle', 'perspective'=>'edit', 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid, 'collapser'=>$collapser])]);
                            $story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
                                                                      'options'=> ['parent_cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                                                      'content'=>$hidden_fields.$edit_details]);
                            
                            $form_body = str_replace('<<cid>>', $cid, $story_model);
                            break;
				/****************************************
*view********** $section = 'things_boqx' $snippet = default  *****************************************************************
				 ****************************************/
                    default:
                        $things_boqx = elgg_view('forms/transfers/edit',
                           ['section'     => 'things_boqx',
                            'perspective' => $perspective,
                            'presentation'=> $presentation,
                            'snippet'     => 'contents',
                            'guid'        => $guid,
//                            'parent_cid'  => $parent_cid,
                            'n'           => $n,
                            'cid'         => $cid,
                      //initialize $boqx_cid
                            'boqx_cid'    => $cid,
                            'qid'         => $qid]);                                        $display .= '315 $guid = '.$guid.'<br>315 $cid = '.$cid.'<br>';
                                                                                            //register_error($display);
                        break;
                }
                
                break;
				/****************************************
*view********** $section = 'things_bundle'                *****************************************************************
				 ****************************************/
           case 'things_bundle':                                                             $display .= '320 things_bundle>$cid: '.$cid.'<br>588 things_bundle>$guid: '.$guid.'<br>';
               unset($form_body, $disabled, $hidden, $id_value);
			   $jot = get_entity($guid);
			   $id_value = $guid;
               $loose_things_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $perspective,
                           'section'        => 'boqx_contents_loose_things',
                           'guid'           => $guid,
//                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
               $receipts_panel  = elgg_view('forms/transfers/edit', array(
                           'perspective'    => $perspective,
                           'section'        => 'boqx_contents_receipt',
                           'guid'           => $guid,
//                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
                
                $contents_panel = "<section class='contents full'>
		                              $loose_things_panel
		                              $receipts_panel
		                              $books_panel
		                           </section>";    
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
                $collapser = "<a class='autosaves collapser-boqx' id='boqx_collapser_$cid' data-cid='$cid' tabindex='-1'></a>";
            	
            	$edit_boqx     = "<button data-aid='addEffortButton' class='ThingsBundle__submit___q0kFhFBf autosaves button std egg' type='submit' tabindex='-1' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid'>Edit</button>";
            	$cancel_effort = "<button class='autosaves cancel clear' type='reset' id='epic_submit_cancel_$cid' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid' tabindex='-1'>Cancel</button>";
           	
            	$url = elgg_get_site_url().'jot';
            	$marker_title         = $jot->title;
            	$marker_date_picker   = $jot->moment;
            	$marker_type_selector = $jot->type;
            	$marker_state_selector = $jot->state;
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = $jot->wo_no;
				$owner_names           = explode(' ',$owner->name);
				$owner_initials        = str_split($owner_names[0])[0].str_split($owner_names[1])[0];
            	$marker_participant_link = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>$owner_initials])])])]);
            	$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',]);
            	break;
        
               /****************************************
*view********** $section = 'boqx_contents'                *****************************************************************
               ****************************************/
             case 'boqx_contents':
/*				$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
							'data-cid'=> $cid,
                            'tabindex'=> '-1',]);
				$view_id = '<<view_id>>';
            	$url = elgg_get_site_url().'jot';
            	$boqx_contents = elgg_view('forms/transfers/edit',['perspective'       => $perspective,
            	                                                   'section'           => 'partials',
            	                                                   'snippet'           => 'view_receipt',
		            											   'show_view_summary' => false,
            													   'parent_cid'        => $parent_cid,
		            			                                   'cid'               => $cid,
            			                                           'service_cid'       => $service_cid,
		            			                                   'qid'               => $qid,]);
            	$marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[$parent_cid][$cid][title]' data-name='title' placeholder='Give this transfer a name'></textarea>";
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
*/				Switch ($snippet){
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
		            	$marker_description = "<textarea name='jot[$parent_cid][$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEt6+3aasz.dit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
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
			        case 'loose_things':
                        unset($hidden, $hidden_fields);
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][subtype]",
                                    'value' => 'boqx'];
                        $hidden[] =['name'=>"jot[$parent_cid][$cid][aspect]",
                                    'value' => 'loose things'];
                        if (!empty($hidden)){                
                            foreach($hidden as $key=>$field){
                                $hidden_fields .= elgg_view('input/hidden', $field);}}
                        $options = ['type'           => 'object',
                                    'subtype'        => 'boqx',
                                    'container_guid' => $guid,];                                   $display .= '1444 $guid = '.$guid.'<br>';
                        $loose_things = elgg_get_entities($options);                               
                        $view = 'forms/transfers/edit';
/*        				if ($loose_things){
                                    foreach($loose_things as $loose_thing){
                        				$line_items .= elgg_view($view,[ 
                									  'perspective' => $perspective,
                									  'section'     =>'boqx_contents', 
                									  'snippet'     =>'single_thing',
                									  'effort'      => $effort,
                                    	              'guid'        => $loose_thing->guid,      
                									  'parent_cid'  => $parent_cid, 
                									  'cid'         => $cid, 
                									  'n'           => $n,]);
                        				$line_item_properties .= elgg_view($view,['section'=>'boqx_contents_receipt', 'perspective'=>$perspective, 'snippet'=>'receipt_item_properties', 'guid'=>$loose_thing->guid, 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                                }
        				}*/
                		$first_line = elgg_view($view,[ 
									  'perspective' => $perspective,
									  'section'     =>'boqx_contents', 
									  'snippet'     =>'single_thing',
									  'effort'      => $effort,      
									  'parent_cid'  => $parent_cid, 
									  'cid'         => $cid, 
									  'n'           => $n,]);
        				$first_line_properties = elgg_view($view,['section'=>'boqx_contents_receipt', 'perspective'=>'add', 'snippet'=>'receipt_item_properties', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'qid'=>$cid, 'qid_n'=>$cid.'_'.$n, 'n'=>$n]);
                		$loose_things_header_recd_qty = 'Rec\'d';
                        $loose_things_header_qty   = 'Qty';
                        $loose_things_header_item  = 'Thing';
        				$loose_things_line_items = "          
                		    <div class='rTable line_items loose-line-items'>
                                <div class='rTableBody'>
                					<div id='sortable'>
                						<div class='rTableRow pin'>
                                            <div class='rTableCell'></div>
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
		                $element = 'loose_item';
						$view_properties_button = elgg_view('output/url', ['data-jq-dropdown'       => '#'.$cid.'_'.$n,
						                                                   'data-qid'               => $qid_n,
														                   'data-horizontal-offset' => "-15",
												                           'text'                   => elgg_view_icon('settings-alt'), 
												                           'title'                  => 'view properties',
						]);
						$form_body ="
								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
                                    <div class='rTableCell'></div>
									<div class='rTableCell'>".elgg_view('input/number',   ['name'=>"jot[$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$view_properties_button "
									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]", 'style'=>'width:95%', 'data-name'=>'title','data-jq-dropdown' => '#'.$cid.'_'.$n,])."</div>
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
				break;
				/****************************************
*view********** $section = 'boqx_contents_loose_things'    *****************************************************************
                ****************************************/
            case 'boqx_contents_loose_things':
				unset($edit_things, $add_things);
			    $view = 'forms/transfers/edit';
				$view_things .= elgg_view($view,[ 
										  'perspective' => $perspective,
                                          'presentation'   => $presentation,
										  'section'     => 'boqx_contents', 
										  'snippet'     => 'loose_things',
										  'effort'      => $effort,
                                          'guid'        => $guid,
										  'parent_cid'  => $parent_cid, 
										  'n'           => $n,]);
				break;
				/****************************************
*view********** $section = 'boqx_contents_receipt'         *****************************************************************
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
		                            'value' => '0',
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
                	    		<a class='collapser collapser-receipt-item' id='story_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
                				<section class='TaskEdit__descriptionContainer___3NOvIiZo info_box'>
                                    <fieldset class='name'>
                                    	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
                							<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 400px;'>
                            					<div class='AutosizeTextarea__container___31scfkZp' style>
                            					    $service_marker_title
                            					</div>
                            				</div>
                							<button data-aid='addReceiptButton'  type='submit'  class='ReceiptAdd__submit___lS0kknw9 std egg' style='margin: 3px 3px 0 15px;order: 2;' data-cid='$cid' data-parent_cid='$parent_cid' data-presence='$presentation'>Add</button>
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
						$unpack_icon   = "<span title='Unpack this'>"
								            .elgg_view('input/checkbox', ['name'=>"jot[$parent_cid][$cid][$n][unpack]",'value'=>1, 'class'=>'boqx-unpack closed','data-cid'=>$cid,'data-name'=>'unpack-this','default'=> false,])
						                ."</span>";
						$view_properties_button = elgg_view('output/url', ['data-jq-dropdown'    => '#'.$cid.'_'.$n,
						                                                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'view properties',
						]);
						$receipt_item_row ="
								<div class='rTableRow $element ui-sortable-handle' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
									<div class='rTableCell'></div>
									<div class='rTableCell'>$unpack_icon</div>
									<div class='rTableCell'>".elgg_view('input/hidden',   ['name'=>"jot[$parent_cid][$cid][$n][aspect]", 'value' => 'receipt item','data-focus-id' => "Aspect--{$cid}"])
									                         .elgg_view('input/number',   ['name'=>"jot[$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$view_properties_button "
									                         .elgg_view('input/text',     ['name'=>"jot[$parent_cid][$cid][$n][title]",'class'=>'rTableform90','id'=>'line_item_input','data-name'=>'title','data-jq-dropdown' => '#'.$cid.'_'.$n,])."</div>
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
					case 'service_item_properties':
					case 'receipt_item_properties':
					    $form_action = 'partials/jot_form_elements';
					    $body_vars   = ['element'        => 'properties_receipt_item',
	                                    'presentation'   => 'qboqx',
										'guid'           => $guid,
					                    'parent_cid'     => $parent_cid,
                                  	    'cid'            => $cid,
                                  	    'n'              => $n,
					                   ];
					    $content = elgg_view($form_action,$body_vars);
					default:
						$view = 'forms/transfers/edit';
						$add_receipt = elgg_view($view,[ 
												  'perspective' => $perspective,
												  'section'     =>'boqx_contents_receipt', 
												  'snippet'     =>'marker1',
												  'effort'      => $effort,      
												  'parent_cid'  => $parent_cid, 
												  'cid'         => $service_cid, 
												  'n'           => $n,]);
				}
		}
		break;
/****************************************
 * $perspective = default                 *****************************************************************************
 ****************************************/
    default:
        Switch ($aspect){
        	case 'receipt':
        	case 'receive':
        		if ($jot->status == 'Received'){$transfer_vars['show_receive'] = false;}
        		else                             {$transfer_vars['show_receive'] = true;}                         $display .='42 $transfer_vars[show_receive] = '.$transfer_vars['show_receive'].'<br>'; 
        		$form_body = elgg_view("forms/transfers/elements/receipt", $transfer_vars);                       $display .= '44 $jot->status: '.$jot->status.'<br>';
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
            case 'trash':
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
                }                                                                    $display .= '86 $jot->status: '.$jot->status.'<br>';
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
	break;
}
/******************************************************************************************************
 * 
 * Shared sections
 *
*******************************************************************************************************/
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
            case 'pallet':
                $things_boqx = elgg_view('forms/transfers/edit',
                   ['section'     => 'things_boqx',
                    'perspective' => $perspective,
                    'presentation'=> 'pallet',
                    'snippet'     => 'contents_edit',
                    'n'           => $n,
                    'cid'         => $cid,
                    'boqx_cid'    => $parent_cid,
                    'qid'         => $qid,]);
                $counter     = elgg_format_element('span', ['class'=>'efforts-eggs', 'data-aid'=>'effortCounts', 'data-cid'=>$cid, 'data-qid'=>$qid, 'data-guid'=>$guid]);
/*                $form_body .= "<div data-aid='Efforts' class='things-boqx-default' action ='$perspective'>
                                    $hidden_fields
                                    <span class='efforts-eggs' data-aid='effortCounts' $data_guid data-cid='$cid' data-qid='$qid'></span>
                                    $things_boqx
                               </div>";                                                                          $display .= '1076 $things_boqx-default ... <br>1076 $cid: '.$cid.'<br>1076 $service_cid: '.$service_cid;
*/                $form_body .= $hidden_fields.elgg_format_element('div',['id'=>$cid, 'class'=>'Effort__ATAgsAWL things-boqx-default', 'data-aid'=>'Efforts', 'action'=>$perspective],$counter.$things_boqx);
                break;
            default:
                if (!$things_boqx)
                    break;
                $form_body .= "$hidden_fields
                               <div data-aid='Efforts' class='things-boqx-default' action ='$perspective'>
                                    <span class='efforts-eggs' data-aid='effortCounts' $data_guid data-cid='$cid' data-qid='$qid'></span>
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
            			if ($disabled == 'disabled') $disabled_label = ' (disabled)';
/*            			$label_card = elgg_view_layout('qbox',['content'=>elgg_view_module('info', null, elgg_view('forms/labels/add',['cid'=>$cid])),
            			                                       'show_save'=>false,
            			                                       'show_full_view'=>false,
            			                                       'qid'=>"BoqxLabelsCard__{$cid}",]);
*/            			$label_card = elgg_view_module('labels', null, elgg_view('forms/labels/add',['cid'=>$cid]));
//		            	$label_card = elgg_view('forms/labels/add',['cid'=>$cid]);
                        $close_icon= elgg_view_icon('window-close',['title'=>'Close']);
                        $close_button = "<a id='qboxClose' class='labelBoqxClose' data-cid='$cid' data-perspective='$perspective'>
                                            $close_icon
                                        </a>";
            			$form_body .= "	   	<div class='$perspective details things_bundle-marker expanded' data-cid='$cid' data-guid='$guid' data-qid='$qid' action='$action'>
		    					                   <section class='edit' data-aid='StoryDetailsEdit' tabindex='-1'>
		                                              <section class='model_details'>
		                                                <section class='story_or_epic_header'>
                                                            $collapser
                                                            <nav class='edit'>
		                                                          <section class='controls'>
                                                                      <div class='persistence use_click_to_copy' style='order:1'>
		                                                                 $cancel_effort
		                                                                 $add_effort
		                                                                 $edit_boqx
		                                                              </div>
		                                                              <div class='actions'>
		                                                                  <div class='bubble'></div>
		                                                                  <button type='button' id='story_copy_link_$cid' title='Copy this link to the clipboard' data-clipboard-text='$url/view/$id_value' class='autosaves clipboard_button hoverable link left_endcap' tabindex='-1' $disabled></button>
		                                                                  <div class='button_with_field'>
			                                                                  <button type='button' id ='story_copy_id_$cid' title='Copy this ID to the clipboard' data-clipboard-text='$id_value' class='autosaves clipboard_button hoverable id use_click_to_copy' tabindex='-1' $disabled></button>
			                                                                  <input type='text' id='story_copy_id_value_$cid' readonly='' class='autosaves id text_value' value='$id_value' tabindex='-1'>
		                                                                </div>
		                                                                <button type='button' id='receipt_import_button_$cid' title='Import receipt (disabled)' class='autosaves import_receipt hoverable left_endcap' tabindex='-1' disabled></button>
		                                                                <button type='button' id='story_clone_button_$cid' title='Clone this boqx".$disabled_view_label."' class='autosaves clone_story hoverable left_endcap' tabindex='-1' $disabled></button>
		                                                                <button type='button' id='story_history_button_$cid' title='View the history of this boqx".$disabled_view_label."' class='autosaves history hoverable capped' tabindex='-1' $disabled></button>
		                                                                <button type='button' id='story_delete_button_$cid' title='Delete this boqx".$disabled_view_label."' class='autosaves delete hoverable right_endcap remove-card' data-qid=$qid tabindex='-1'$disabled></button>
                                                                        $maximize_button
		                                                              </div>
		                                                            </section>
		                                                      </nav>
		                            					    
		                            					</section>
<!--                                                    <section class='model_instructions'>
                                                            $instructions
                                                        </section>
-->		                            					<aside>
		                                                    <div class='wrapper'>
		                                                      <div class='info_box' style='display:block'>
		                                                        <div class='info'>
                                                                    <fieldset class='name'>
            		                            					    <div class='name row'>
                                                                        <div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='display:flex'>
            		                            					         <div class='AutosizeTextarea__container___31scfkZp'>
            		                            					            $marker_title
            		                            					         </div>
            		                            					    </div>
        		                            					     </fieldset>
			                                                       <div class='date row'>
			                                                         <em>Date</em>
			                                                         <div class='dropdown story_date'>
			                                                            <input aria-hidden='true' type='text' id='story_date_dropdown_$cid_honeypot' tabindex='-1' class='honeypot'>
			                                                            $marker_date_picker
			                                                         </div>
			                                                       </div>
    		                                                       <div class='requester row closed'>
    		                                                         <em>Receiving Group</em>
    		                                                         <div class='dropdown story_org_id'>
    		                                                            <input aria-hidden='true' type='text' id='story_scribe_id_dropdown_$cid_honeypot' tabindex='0' class='honeypot'>
    		                                                            <a id='effort_org_id_dropdown_$cid' class='selection item_2936271' tabindex='-1'><span><div class='name'>??</div></span></a>
    		                                                         </div>
    		                                                       </div>
    		                                                       <div class='participant row closed'>
    		                                                         <em>Receiver</em>
    		                                                         <div class='story_participants'>
        		                                                          <input aria-hidden='true' type='text' id='story_participant_ids_$cid_honeypot' tabindex='0' class='honeypot'>
    <!--    		                                                          <a id='add_participant_$cid' class='add_participant selected' tabindex='-1'></a> -->
        		                                                          $marker_participant_link
    		                                                         </div>
			                                                       </div>
    		                                                       <div class='contents row'>
    		                                                          <em>Contents</em>
        		                                                      <div class='dropdown'>
                                                                          <div class='contents-selectors'>
        		                                                            $marker_boqx_selector
        		                                                          </div>
                                                                      </div>
    		                                                       </div>
    		                                                       <div class='labels row'>
    		                                                          <section class='labels_container full'>
                                                                        <div id='story_labels_$cid' class='labels'>
                                                                            <div class='StoryLabelsMaker___Lw8q4VmA'>
                                                                                <h4>Labels</h4>
                                                                                <div class='StoryLabelsMaker__container___2B23m_z1'>
                                                                                    <div data-aid='StoryLabelsMaker__contentContainer' class='StoryLabelsMaker__contentContainer___3CvJ07iU'>
                                                                                        <div class='LabelsSearch___2V7bl828' data-aid='LabelsSearch'>
                                                                                            <div class='tn-text-input___1CFr3eiU LabelsSearch__container___kJAdoNya'>
                                                                                                <div>
                                                                                                    <input autocomplete='off' data-cid='$cid' class='tn-text-input__field___3gLo07Il tn-text-input__field--medium___v3Ex3B7Z LabelsSearch__input___3BARDmFr' type='text' placeholder='new label' data-aid='LabelsSearch__input' data-focus-id='LabelsSearch--$cid' aria-label='Search for an existing label or type a new label' value=''></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <a class='StoryLabelsMaker__arrow___OjD5Om2A' data-aid='StoryLabelsMaker__arrow' data-jq-dropdown='#BoqxLabelsCard__{$cid}' data-horizontal-offset='-260' data-vertical-offset='50'></a>
                                                                                    </div>
                                                                                    <div class='BoqxLabelsPicker__Vof1oGNB' data-cid='$cid'>
                                                                                        <div id = 'BoqxLabelsCard__{$cid}' class='qbox-dropdown qbox-dropdown-tip_xxx qbox-dropdown-relative'>
						                                                                    <div id='qboxContent'>
                                                                                                <div class='qbox-dropdown-panel' style='max-width:450px;display:flex;justify-content:flex-end;flex-flow:column nowrap;'>
                                                                                                    <div style='text-align:right;'>
                                                                                                        $close_button
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        $label_card
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                      </section>
    		                                                       </div>
		                                                        </div>
                                                              </div>
                                                            </div>
		                                                </aside>
		                                        	</section>
                                                    $contents_panel
		    					                 </section>
		    					               </div>";
/*                                                                                        
*/        		                                                            
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
						$new_receipt     = elgg_view('forms/transfers/edit',
							['action'      => $perspective,
							 'section'     => 'boqx_contents',
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
					    $form_body = $loose_things_line_items;
					    break;
					case 'single_thing':
					    $form_body = $single_thing;
					case 'single_book':
					case 'book':
					case 'comic_book':
						break;
					case 'books':
					    $form_body = $books_line_items;
					    
					    break;
					case 'receipt':
					    $form_body .= "<div class='boqx-contents receipts closed' data-aid='Receipts' data-cid='$parent_cid'>
                                         <div data-aid='Tasks'>
                                               $hidden_fields
                							   <span class='receipts-count' data-aid='taskCounts' data-cid='$parent_cid'><h4>Receipts</h4></span>
                						       $add_receipt
    										   $edit_receipt
    										   $view_receipt
            						     </div>
                                      </div>";   
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
				$counter    = elgg_format_element('span', ['class'=>'item-count', 'data-aid'=>'itemCount', 'data-cid'=>$parent_cid], '<h4>Things</h4>');
				$form_body .= elgg_format_element('div', ['class'=>['boqx-pallet', 'things'], 'data-aid'=>'items', 'data-cid'=>$parent_cid],$hidden_fields.$counter.$add_things.$edit_things.$view_things);
				break;
/****************************************
$section = 'boqx_contents_receipt'        *****************************************************************
****************************************/
            case 'boqx_contents_receipt':
				Switch ($snippet){
					case 'marker1':
						$form_body .="<div class='ServiceEffort__26XCaBQk boqx_contents_receipt-marker1' data-qid='$qid' data-parent-cid='$parent_cid' data-cid='$cid' boqx-fill-level='0'>
            							$boqx_contents_receipt_add
            							$boqx_contents_receipt_show
            							$boqx_contents_receipt_edit
            						</div>";
			            break;
/****************************************
$section = 'boqx_contents_receipt' $snippet = 'service_item' 'receipt item'  *****************************************************************
****************************************/
			        case 'service_item':                                                                      $display.= '4648 $cid: '.$cid.'<br>';
					case 'receipt_item':
	            	    $form_body .= $receipt_item_row;
						break;
/****************************************
$section = 'boqx_contents_receipt' $snippet = 'service_item_properties' 'receipt item_properties'  *******************************************
****************************************/
					case 'service_item_properties':
					case 'receipt_item_properties':
                        $form_body = $content;
						break;
/****************************************
$section = 'boqx_contents_receipt' *******************************************
****************************************/
						default:
						$form_body .= "<div class='AcquistionEffort__bNMQfidM boqx-pallet receipts closed' data-cid='$parent_cid' data-aid='Receipts'>
                                         <div data-aid='Tasks'>
                                               $hidden_fields
                							   <span class='receipts-count' data-aid='receiptCounts' data-cid='$parent_cid'><h4>Receipts</h4></span>
                						       $add_receipt
    										   $edit_receipt
    										   $view_receipt
            						     </div>
                                      </div>";                                              						$display .= '4739 boqx_contents_receipt-default ... <br>4739 $cid: '.$cid.'<br>4739 $service_cid: '.$service_cid.'<br>4739 $effort->guid: '.$effort->guid;
						break;
				}               
	                                            	                                                          
				break;
/****************************************
$section = 'boqx_contents_experience'    *****************************************************************
****************************************/
            case 'boqx_contents_experience':
				$form_body .= "<div class='boqx-pallet experience closed' data-aid='Experience' data-cid='$parent_cid'>
                                  <div>
                                       $hidden_fields
        							   <span class='things-count' data-aid='thingCounts' data-cid='$parent_cid'><h4>Experience</h4></span>
        						       $add_experience
    								   $edit_experience
    								   $view_experience
    						      </div>
                             </div>";
				break;
/****************************************
$section = 'boqx_contents_books'         *****************************************************************
****************************************/
			case 'boqx_contents_books':
			    switch($snippet){
			    case 'book_properties':
                 $form_body .="<div id='{$cid}_{$n}' class='jq-dropdown jq-dropdown-tip jq-dropdown-relative'>
                                    <div class='jq-dropdown-panel' style='max-width:400px'>
                                       $content
                                    </div>
                               </div>";
			        break;
			    default:
				$form_body .= "<div class='boqx-pallet book_collection closed' data-aid='Book Collection' data-cid='$parent_cid'>
                                   <div data-aid='Books'>
                                       $hidden_fields
        							   <span class='things-count' data-aid='thingCounts' data-cid='$parent_cid'><h4>Book Collection</h4></span>
        						       $add_books
    								   $edit_books
    								   $view_books
    							   </div>
    						   </div>";
				}
			    break;
/****************************************
$section = 'boqx_contents_comic_books'         *****************************************************************
****************************************/
			case 'boqx_contents_comic_books':
			    switch($snippet){
			    case 'book_properties':
			        $form_body = $content;
			        break;
				}
				$form_body .= "<div data-aid='ComicBooks' class='boqx-contents-comic_collection boqx_contents_Comic_collection-default' data-cid='$parent_cid'>
                                   $hidden_fields
    							   <span class='things-count' data-aid='thingCounts' data-cid='$parent_cid'><h4>Comic Book Collection</h4></span>
    						       $add_comics
								   $edit_comics
								   $view_comics
    						   </div>";
			    break;
/****************************************
 $section = default                        *****************************************************************
 ****************************************/
    default:
}
echo $form_body;
//register_error($display);