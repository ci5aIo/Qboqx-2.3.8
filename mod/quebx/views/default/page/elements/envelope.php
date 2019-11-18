<?php
/**
 * 
 */

$guid           = elgg_extract('guid'             , $vars, false);
$task           = elgg_extract('task'             , $vars);
$title          = elgg_extract('title'            , $vars, false);
$action         = elgg_extract('action'           , $vars, false);
$parent_cid     = elgg_extract('parent_cid'       , $vars, false);  // if a parent_cid is provided, assume that: <div id=$cid data-boqx=$parent_cid
$cid            = elgg_extract('cid'              , $vars, quebx_new_id('c'));
$info_boqx       = elgg_extract('info_boqx'       , $vars);
$hidden_fields  = elgg_extract('hidden_fields'    , $vars);
$visible        = elgg_extract('visible'          , $vars, 'add');
$has_collapser  = elgg_extract('has_collapser'    , $vars) =='yes' ? true : false;
$presentation   = elgg_extract('presentation'     , $vars);
$presence       = elgg_extract('presence'         , $vars);
/**/ 
$delete         = elgg_format_element("span",['class'=>'remove-item'], 
                      elgg_format_element('a', ['title' =>'remove item'], 
                          elgg_view_icon('delete-alt')));
$add_visible    = $visible == 'add'  ? false  : 'display:none';
$show_visible   = $visible == 'show' ? false  : 'display:none';
$edit_visible   = $visible == 'edit' ? false  : 'display:none';
$contents_visible = $visible == 'contents' ? true  : false;
$class_add      = 'AddSubresourceButton___2PetQjcb';
if ($has_collapser) $collapser = elgg_format_element('a',['id'=>"collapser_$cid",'class'=>['autosaves','collapser',"collapser-$task",'CollapseEnvelope__z7DilsLc'],'data-cid'=>$cid,'tabindex'=>'-1']);
if ($guid == 0)      $guid = false;
if ($title)          $title = elgg_format_element('h4',[],$title);
/**/
/*defaults*/
$add_label = 'Add something';
$boqx_aspect= $task;
$class_boqx = ['ServiceEffort__26XCaBQk', $task];
$class_show = 'TaskShow___2LNLUMGe';
$class_edit = 'TaskEdit___1Xmiy6lz';

switch($task){
    case 'item':
        $add_label = 'Add an item';
        $class_boqx = ['Item__nhjb4ONn', $task];
        $class_add  = 'AddSubresourceButton___oKRbUbg6';
        $class_show = 'ItemShow_Btc471up';
        $class_edit = 'ItemEdit___7asBc1YY';
        break;
    case 'receipt_item':
        $add_label = 'Add an item';
        $class_boqx = ['Item__nhjb4ONn', $task];
        $class_add  = 'AddSubresourceButton___oKRbUbg6';
        $class_show = 'ItemShow_Btc471up';
        $class_edit = 'ItemEdit___7asBc1YY';
        break;
    case 'receipt':
        $add_label = 'Add a receipt';
        $class_boqx = ['ServiceEffort__26XCaBQk', $task];
        break;
    case 'issue':
        $add_label = 'Add an issue';
        $class_boqx = ['ServiceEffort__26XCaBQk', $task];
        break;
    case 'discoveries':
        /**/
        break;
    case 'discovery':
        $add_label = 'Add a discovery';
        $class_boqx = 'Effort__CPiu2C5N';
        $class_show = 'EffortShow_haqOwGZY';
        $class_edit = 'EffortEdit_fZJyC62e';
        break;
    case 'remedy':
        $add_label = 'Add an effort';
        $class_boqx = 'Effort__CPiu2C5N';
        $class_show = 'EffortShow_haqOwGZY';
        $class_edit = 'EffortEdit_fZJyC62e';
        break;
    case 'media':
        $add_label = 'Add media';
        break;
}
$add = 
    elgg_format_element('div',['class'=>$class_add,'tabindex'=>'0', 'data-aid'=>'TaskAdd','data-focus-id'=>'TaskAdd','data-cid'=>$cid,'style'=>$add_visible, 'data-guid'=>$guid],
        elgg_format_element('span',['class'=>'AddSubresourceButton__icon___h1-Z9ENT']).
        elgg_format_element('span',['class'=>'AddSubresourceButton__message___2vsNCBXi'],$add_label)
    );
$show =
    elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible,'draggable'=>true],
        elgg_format_element('input',['class'=>'TaskShow__checkbox___2BQ9bNAA','type'=>'checkbox','title'=>'mark task complete','data-aid'=>'toggle-complete','data-focus-id'=>"TaskShow__checkbox--$cid"]).
        elgg_format_element('div',['class'=>['TaskShow__description___3R_4oT7G','tracker_markup'], 'data-aid'=>'TaskDescription','tabindex'=>'0'],
            elgg_format_element('span',['class'=>'TaskShow__title___O4DM7q']).
            elgg_format_element('span',['class'=>'TaskShow__item_total__Dgd1dOSZ']).
            elgg_format_element('span',['class'=>'TaskShow__service_items___2wMiVig'])
         ).
         elgg_format_element('nav',['class'=>['TaskShow__actions___3dCdQMej','undefined','TaskShow__actions--unfocused___3SQSv294']],
             elgg_format_element('button',['class'=>['IconButton___2y4Scyq6','IconButton--small___3D375vVd'],'data-aid'=>'delete','aria-label'=>'Delete','data-cid'=>$cid],
                      $delete
             )
         )
      );
$edit = 
    elgg_format_element('div',['class'=>$class_edit,'data-aid'=>'TaskEdit','data-cid'=>$cid,'style'=>$edit_visible],
        $hidden_fields.
        $collapser.
    	$info_boqx);
if  ($contents_visible)
     $contents = $info_boqx;
else $contents = $add.$show.$edit;

if ($parent_cid) {
    $id = $cid;
    unset($cid);
}

$form_body =
    elgg_format_element('span',['class'=>['tally','bom'],'boqxes'=>1]).
    $title.
    elgg_format_element('div',['id'=>$id,'class'=>$class_boqx,'data-aspect'=>$boqx_aspect, 'data-cid'=>$cid, 'data-boqx'=>$parent_cid,'data-guid'=>$guid,'data-aid'=>$action, 'boqx-fill-level'=>'0','presentation'=>$presentation, 'presence'=>$presence],
    	$contents);

echo $form_body;