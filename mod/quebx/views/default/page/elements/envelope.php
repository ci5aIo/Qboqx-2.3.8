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
$carton_id      = elgg_extract('carton_id'        , $vars);
$show_boqx      = elgg_extract('show_boqx'        , $vars, false);
$show_title     = elgg_extract('show_title'       , $vars, false);
$info_boqx      = elgg_extract('info_boqx'        , $vars);
$hidden_fields  = elgg_extract('hidden_fields'    , $vars);
$visible        = elgg_extract('visible'          , $vars, 'add');
$has_collapser  = elgg_extract('has_collapser'    , $vars) =='yes' ? true : false;
$presentation   = elgg_extract('presentation'     , $vars);
$presence       = elgg_extract('presence'         , $vars);
$fill_level     = elgg_extract('fill_level'       , $vars, 0);
$allow_delete   = elgg_extract('allow_delete'     , $vars, true);
/**/ 
if($allow_delete)
$delete         = elgg_format_element('button',['class'=>['IconButton___2y4Scyq6','IconButton--small___3D375vVd'],'data-aid'=>'delete','aria-label'=>'Delete','data-cid'=>$cid],
                      elgg_format_element("span",['class'=>'remove-item'], 
                          elgg_format_element('a', ['title' =>'remove item'], 
                              elgg_view_icon('delete-alt'))));
$class_boqx[]     = 'envelope__NkIZUrK4';
$class_add[]      = 'envelopeWindow__3hpw9wdN';
$class_edit[]     = 'envelopeWindow__3hpw9wdN';
$class_show[]     = 'envelopeWindow__3hpw9wdN';
$class_qty        = 'TaskShow__qty_7lVp5tl4';
$class_title      = 'TaskShow__title___O4DM7q';
$class_total      = 'TaskShow__item_total__Dgd1dOSZ';
$class_items      = 'TaskShow__service_items___2wMiVig';
$class_time       = 'TaskShow__time___cvHQ72kV';
$add_visible      = $visible == 'add'  ? false  : 'display:none';
$show_visible     = $visible == 'show' ? false  : 'display:none';
$edit_visible     = $visible == 'edit' ? false  : 'display:none';
$contents_visible = $visible == 'contents' ? true  : false;
if ($has_collapser) $collapser = elgg_format_element('a',['id'=>"collapser_$cid",'class'=>['autosaves','collapser',"collapser-$task",'CollapseEnvelope__z7DilsLc'],'data-cid'=>$cid,'tabindex'=>'-1']);
if ($guid == 0)      $guid = false;
if (elgg_entity_exists($guid))
   $entity = get_entity($guid);
/**/
/*defaults*/
$add_label = 'Add something';
$boqx_aspect= $task;

switch($task){
    case 'item':
        unset($class_time);
        $add_label    = 'Add an item';
        $class_boqx[] = 'Item__nhjb4ONn';
        $class_add[]  = 'AddSubresourceButton___oKRbUbg6';
        $class_show[] = 'ItemShow_Btc471up';
        $class_edit[] = 'ItemEdit___7asBc1YY';
        break;
    case 'receipt_item':
        unset($class_time);
        $add_label    = 'Add an item';
        $class_boqx[] = 'Item__nhjb4ONn';
        $class_add[]  = 'AddSubresourceButton___oKRbUbg6';
        $class_show[] = 'ItemShow_Btc471up';
        $class_edit[] = 'ItemEdit___7asBc1YY';
        break;
    case 'receipt':
        unset($class_time);
        $add_label    = 'Add a receipt';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        break;
    case 'issue':
        unset($class_time);
        $add_label    = 'Add an issue';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        break;
    case 'discoveries':
        unset($class_time);
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        break;
    case 'discovery':
        unset($class_time);
        $add_label    = 'Add a discovery';
        $class_boqx[] = 'Effort__CPiu2C5N';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'EffortShow_haqOwGZY';
        $class_edit[] = 'EffortEdit_fZJyC62e';
        break;
    case 'remedies':
        unset($class_time);
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        break;
    case 'remedy':
        unset($class_time);
        $add_label    = 'Add a remedy';
        $class_boqx[] = 'Effort__CPiu2C5N';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'EffortShow_haqOwGZY';
        $class_edit[] = 'EffortEdit_fZJyC62e';
        break;
    case 'parts':
        unset($class_time);
        $add_label    = 'Add parts';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz linedEnvelope';
        break;
    case 'efforts':
        $add_label    = 'Add time';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz linedEnvelope';
        break;
    case 'contents':
        unset($class_time);
        $add_label    = 'Add contents';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz linedEnvelope';
        break;
    case 'effort':
        unset($class_qty,$class_total,$class_items);
        $add_label    = 'Add time';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        break;
    case 'media':
        unset($class_time);
        $add_label    = 'Add media';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        break;
    case 'part':
        unset($class_time);
        $add_label    = 'Add part';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        break;
}
$add = 
    elgg_format_element('div',['class'=>$class_add,'tabindex'=>'0', 'data-aid'=>'TaskAdd','data-focus-id'=>'TaskAdd','data-cid'=>$cid,'style'=>$add_visible, 'data-guid'=>$guid],
        elgg_format_element('span',['class'=>'AddSubresourceButton__icon___h1-Z9ENT']).
        elgg_format_element('span',['class'=>'AddSubresourceButton__message___2vsNCBXi'],$add_label)
    );
if($show_title)
     $show_title_span = elgg_format_element('span',[],$show_title);
else $show_title_span = elgg_format_element('span',['class'=>$class_qty],$entity->qty).
                        elgg_format_element('span',['class'=>$class_title],$entity->title).
                        elgg_format_element('span',['class'=>$class_total],$entity->total).
                        elgg_format_element('span',['class'=>$class_items],$entity->items).
                        elgg_format_element('span',['class'=>$class_time], $entity->time);
    
$show = $show_boqx ?
    // if true
    elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible,'draggable'=>true],$show_boqx)
    :
    // if false
    elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible,'draggable'=>true],
        elgg_format_element('input',['class'=>'TaskShow__checkbox___2BQ9bNAA','type'=>'checkbox','title'=>'mark task complete','data-aid'=>'toggle-complete','data-focus-id'=>"TaskShow__checkbox--$cid"]).
        elgg_format_element('div',['class'=>['TaskShow__description___3R_4oT7G','tracker_markup_xxx'], 'data-aid'=>'TaskDescription','tabindex'=>'0'],$show_title_span).
         elgg_format_element('nav',['class'=>['TaskShow__actions___3dCdQMej','undefined','TaskShow__actions--unfocused___3SQSv294']],$delete));
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
    elgg_format_element('div',['id'=>$id,'class'=>$class_boqx,'data-aspect'=>$boqx_aspect, 'data-cid'=>$cid, 'data-boqx'=>$parent_cid,'data-carton'=>$carton_id, 'data-guid'=>$guid,'data-aid'=>$action, 'boqx-fill-level'=>$fill_level,'data-presentation'=>$presentation, 'data-presence'=>$presence],
    	$contents);

echo $form_body;