<?php
$parent_cid  = elgg_extract('parent_cid', $vars);
$cid         = elgg_extract('cid', $vars);
$guid        = elgg_extract('guid', $vars, false);
$action      = elgg_extract('action', $vars, 'add');
$presentation= elgg_extract('presentation', $vars);
$presence    = elgg_extract('presence', $vars);
$buttons     = elgg_extract('buttons', $vars, false);
$form_method = elgg_extract('form_method', $vars, 'post');
if ($buttons && !is_array($buttons)) $buttons = (array) $buttons;

$guid           = $guid == 0 ? false : $guid;
$disabled       = $guid ? false : '';
$disabled_label = $guid ? false : ' (disabled)';
$cancel_class   = 'cancel-pallet';
$button_class   = ['persistence'];
$delete_class   = 'remove-card';
$submit_class   = ['autosaves','button','std','egg'];
if (elgg_entity_exists($guid)){
    $link       = elgg_get_site_url().get_entity($guid)->getSubtype().'/view/'.$guid;
    $cancel_class = 'cancel';
}
if ($buttons) 
    if ($buttons['delete'] && $buttons['delete_action'] == 'replace')
         $delete_class = 'replace-card';
switch ($form_method){
    case 'post': $submit_class[]='ThingsBundle__submit___q0kFhFBf'; break;
    case 'stuff': $submit_class[]='StuffEnvelope_6MIxIKaV'; break;
    default: break;
}
$cancel_effort = elgg_format_element('button',['type'=>'reset' ,'id'=>"pallet_submit_cancel_{$cid}",'class'=>['autosaves',$cancel_class,'clear'],'data-cid'=>$cid,'tabindex'=>'-1', 'data-boqx'=>$parent_cid, 'data-presence'=>$presence, 'dadta-presentation'=>$presentation, 'title'=>'Cancel changes'],'Cancel');
$add_effort    = elgg_format_element('button',['type'=>'submit','class'=>$submit_class,'data-aid'=>'addEffortButton','tabindex'=>'-1','data-parent-cid'=>$parent_cid,'data-cid'=>$cid],'Add');
$save_effort   = elgg_format_element('button',['type'=>'submit','class'=>$submit_class,'data-aid'=>'editEffortButton','tabindex'=>'-1','data-parent-cid'=>$parent_cid,'data-cid'=>$cid,'data-guid'=>$guid],'Save');
$maximize      = elgg_format_element('button',['type'=>'button','id'=>"story_maximize_{$cid}",'class'=>['autosaves','maximize','hoverable'],'tabindex'=>'-1','title'=>'Switch to a full view']);
$copy_link     = elgg_format_element('button',['type'=>'button','id'=>"story_copy_link_$cid",'class'=>['autosaves','clipboard_button','hoverable','link left_endcap'],'title'=>'Copy this link to the clipboard'.$disabled_label,'data-clipboard-text'=>$link,'tabindex'=>'-1','disabled'=>$disabled]);
$copy_id       = elgg_format_element('button',['type'=>'button','id'=>"story_copy_id_$cid",'class'=>['autosaves','clipboard_button','hoverable','id','use_click_to_copy'],'title'=>'Copy this ID to the clipboard'.$disabled_label,'data-clipboard-text'=>$guid,'tabindex'=>'-1','disabled'=>$disabled]);
$show_guid     = elgg_format_element('input' ,['type'=>'text'  ,'id'=>"story_copy_id_value_$cid",'class'=>['autosaves','id','text_value'],'readonly'=>'','value'=>$guid,'tabindex'=>'-1']);
$import        = elgg_format_element('button',['type'=>'button','id'=>"receipt_import_button_$cid",'class'=>['autosaves','import_receipt','hoverable','left_endcap'],'title'=>'Import receipt'.$disabled_label,'tabindex'=>'-1','disabled'=>$disabled]);
$clone         = elgg_format_element('button',['type'=>'button','id'=>"story_clone_button_$cid",'class'=>['autosaves','clone_story','hoverable','left_endcap'],'title'=>'Clone this boqx'.$disabled_label,'tabindex'=>'-1','disabled'=>$disabled]);
$history       = elgg_format_element('button',['type'=>'button','id'=>"story_history_button_$cid",'class'=>['autosaves','history','hoverable','capped'],'title'=>'View the history of this boqx'.$disabled_label,'tabindex'=>'-1', 'disabled'=>$disabled]);
$delete        = elgg_format_element('button',['type'=>'button','id'=>"story_delete_button_$cid",'class'=>['autosaves','delete','hoverable','right_endcap',$delete_class],'title'=>'Delete this boqx'.$disabled_label,'data-cid'=>$cid,'tabindex'=>'-1', 'disabled'=>$disabled]);
$action_button = $action == 'add' ? $add_effort : $save_effort;

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
    
    if (!$cancel_effort) $button_class[] = 'affirmative';
}

$nav_controls =
    elgg_format_element('nav',['class'=>'edit'],
    	elgg_format_element('section',['class'=>'controls'],
    		elgg_format_element('div',['class'=>$button_class,'style'=>'order:1'],
    			 $cancel_effort.
    			 $action_button).
            elgg_format_element('div',['class'=>'actions'],
        		elgg_format_element('div',['class'=>'bubble']).
        		$copy_link.
        		elgg_format_element('div',['class'=>'button_with_field'],
        			  $copy_id.
        			  $show_guid).
        		$import.
        		$clone.
        		$history.
        		$delete.
        		$maximize)));

echo $nav_controls;