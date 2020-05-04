<?php
$parent_cid  = elgg_extract('parent_cid', $vars);
$cid         = elgg_extract('cid', $vars);
$wrapper_cid = elgg_extract('wrapper_cid', $vars, $parent_cid);
$guid        = elgg_extract('guid', $vars, false);
$action      = elgg_extract('action', $vars, 'add');
$presentation= elgg_extract('presentation', $vars);
$presence    = elgg_extract('presence', $vars);
$form_method = elgg_extract('form_method', $vars, 'post');
$form_id     = elgg_extract('form_id', $vars);
$buttons     = elgg_extract('buttons', $vars, false);
$display_state  = elgg_extract('display_state', $vars, $action);
$origin         = elgg_extract('origin'           , $vars);

if ($buttons && !is_array($buttons)) $buttons = (array) $buttons;
$cancel_class= elgg_extract('cancel_class', $buttons, 'cancel-pallet');
$delete_class= elgg_extract('delete_class', $buttons, 'remove-card');

$guid           = ($guid == 0) ? false : $guid;
$disabled       = $guid ? false : '';
$disabled_label = $guid ? false : ' (disabled)';
$button_class   = ['persistence'];
$submit_class   = ['button','std','egg'];
$close_class    = ['button','std'];
$nav_class      = $action;// $display_state;
$delete_title   = 'Delete this boqx'.$disabled_label;
if (elgg_entity_exists($guid)){
    $link       = elgg_get_site_url().get_entity($guid)->getSubtype().'/view/'.$guid;
    $cancel_class = 'cancel';
}
if ($buttons) 
    if ($buttons['delete'] && $buttons['delete_action'] == 'replace')
         $delete_class = 'trashEnvelope_0HziOPGx';
if($display_state == 'view'){
    $delete_class = 'removeNested_C7sxMc9H'; 
    $delete_title = 'Remove this boqx'.$disabled_label;
    $close_class[] = 'collapseBoqx_g9tELkBB';
}
switch ($form_method){
    case 'post': $submit_class[]='submitBundle_q0kFhFBf'; break;
    case 'pack': $submit_class[]='packBoqx_QQFvJSIR'; break;
    case 'stuff': $submit_class[]='StuffEnvelope_6MIxIKaV'; break;
    default: break;
}

echo "<!--origin=$origin, presentation=$presentation, presence=$presence, display_state=$display_state, 'disabled'=>$disabled, form_method=$form_method, parent_cid=>$parent_cid -->";
if($action == 'add'){
    $cancel_effort  = elgg_format_element('button',['type'=>'reset' ,'id'=>"pallet_submit_cancel_{$cid}",'class'=>[$cancel_class,'clear'],'data-cid'=>$cid,'tabindex'=>'-1', 'data-boqx'=>$wrapper_cid, 'data-presence'=>$presence, 'data-presentation'=>$presentation, 'title'=>'Cancel changes'],'Cancel');
    $submit_class[] = 'add';
}
if($action == 'edit')
    $submit_class[] = 'close';
$maximize      = elgg_format_element('button',['type'=>'button','id'=>"story_maximize_{$cid}"     ,'class'=>['maximize','hoverable']                                 ,'title'=>'Switch to a full view'                                                    ,'data-cid'=>$cid ,'tabindex'=>'-1']);
$copy_link     = elgg_format_element('button',['type'=>'button','id'=>"story_copy_link_$cid"      ,'class'=>['clipboard_button','hoverable','link left_endcap']      ,'title'=>'Copy this link to the clipboard'.$disabled_label,'data-clipboard-text'=>$link               ,'tabindex'=>'-1','disabled'=>$disabled]);
$copy_id       = elgg_format_element('button',['type'=>'button','id'=>"story_copy_id_$cid"        ,'class'=>['clipboard_button','hoverable','id','use_click_to_copy'],'title'=>'Copy this ID to the clipboard'.$disabled_label,'data-clipboard-text'=>$guid                 ,'tabindex'=>'-1','disabled'=>$disabled]);
$show_guid     = elgg_format_element('input' ,['type'=>'text'  ,'id'=>"story_copy_id_value_$cid"  ,'class'=>['id','text_value'],'readonly'=>'','value'=>$guid,'tabindex'=>'-1']);
$import        = elgg_format_element('button',['type'=>'button','id'=>"receipt_import_button_$cid",'class'=>['import_receipt','hoverable','left_endcap']              ,'title'=>'Import receipt'.$disabled_label                                                            ,'tabindex'=>'-1','disabled'=>$disabled]);
$clone         = elgg_format_element('button',['type'=>'button','id'=>"story_clone_button_$cid"   ,'class'=>['clone_story','hoverable','left_endcap']                 ,'title'=>'Clone this boqx'.$disabled_label                                                           ,'tabindex'=>'-1','disabled'=>$disabled]);
$history       = elgg_format_element('button',['type'=>'button','id'=>"story_history_button_$cid" ,'class'=>['history','hoverable','capped']                          ,'title'=>'View the history of this boqx'.$disabled_label                                             ,'tabindex'=>'-1', 'disabled'=>$disabled]);
$delete        = elgg_format_element('button',['type'=>'button','id'=>"story_delete_button_$cid"  ,'class'=>['delete','hoverable','right_endcap',$delete_class]       ,'title'=>$delete_title,'data-aid'=>'delete'                                         ,'data-cid'=>$cid,'tabindex'=>'-1', 'disabled'=>$disabled]);
$add_effort    = elgg_format_element('button',['type'=>'submit','form'=>$form_id                  ,'class'=>$submit_class                                                                    ,'data-aid'=>'addEffortButton','data-parent-cid'=>$parent_cid ,'data-cid'=>$cid,'value'=>'Add'],'Add');
$close_effort  = elgg_format_element('button',['type'=>'submit','form'=>$form_id                  ,'class'=>$submit_class                                                                    ,'data-aid'=>'editEffortButton','data-parent-cid'=>$parent_cid,'data-cid'=>$cid,'data-guid'=>$guid,'value'=>'Close'],'Close');
$close_boqx    = elgg_format_element('button',[                                                    'class'=>$close_class                                                                     ,'data-aid'=>'closeBoqxButton','data-parent-cid'=>$parent_cid ,'data-cid'=>$cid,'data-guid'=>$guid],'Close');
$action_button = ($action == 'add') ? $add_effort : (($display_state == 'view') ? $close_boqx : $close_effort);
$submit        = elgg_format_element('div',['class'=>$button_class,'style'=>'order:1'],$cancel_effort.$action_button);

if($buttons){
    $cancel_effort = $buttons['cancel']    ? $cancel_effort : false;
    $action_button = $buttons['action']    ? $action_button : false;
    $maximize      = $buttons['maximize']  ? $maximize      : false;
    $copy_link     = $buttons['copy_link'] ? $copy_link     : false;
    $copy_id       = $buttons['copy_id']   ? $copy_id       : false;
    $show_guid     = $buttons['show_guid'] ? $show_guid     : false;
    $import        = $buttons['import']    ? $import        : false;
    $clone         = $buttons['clone']     ? $clone         : false;
    $history       = $buttons['history']   ? $history       : false;
    $delete        = $buttons['delete']    ? $delete        : false;
    $submit        = elgg_extract('submit',$buttons,true) ? $submit : false;
    
    if (!$cancel_effort) $button_class[] = 'affirmative';
}

$nav_controls =
    elgg_format_element('nav',['class'=>$nav_class],
    	elgg_format_element('section',['class'=>'controls'],
    		$submit.
            elgg_format_element('div',['class'=>'actions'],
        		$maximize.
        		elgg_format_element('div',['class'=>'bubble']).
        		$copy_link.
        		elgg_format_element('div',['class'=>'button_with_field'],
        			  $copy_id.
        			  $show_guid).
        		$import.
        		$clone.
        		$history.
        		$delete)));

echo $nav_controls;