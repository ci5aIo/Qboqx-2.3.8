<?php
/**
 * 
 */
$jot   = (object) elgg_extract('jot'              , $vars, false);
$guid           = elgg_extract('guid'             , $vars, false);
$task           = elgg_extract('task'             , $vars);
$title          = elgg_extract('title'            , $vars, false);
$action         = elgg_extract('action'           , $vars, false);
$parent_cid     = elgg_extract('parent_cid'       , $vars, false);  // if a parent_cid is provided, assume that: <div id=$cid data-boqx=$parent_cid
$cid            = elgg_extract('cid'              , $vars, quebx_new_id('c'));
$carton_id      = elgg_extract('carton_id'        , $vars);
$show_boqx      = elgg_extract('show_boqx'        , $vars, false);
$show_title     = elgg_extract('show_title'       , $vars, false);
$edit_title     = elgg_extract('edit_title'       , $vars, false);
$info_boqx      = elgg_extract('info_boqx'        , $vars);
$hidden_fields  = elgg_extract('hidden_fields'    , $vars);
$visible        = elgg_extract('visible'          , $vars, 'add');
$has_collapser  = elgg_extract('has_collapser'    , $vars) =='yes' ? true : false;
$presentation   = elgg_extract('presentation'     , $vars);
$presence       = elgg_extract('presence'         , $vars);
$fill_level     = elgg_extract('fill_level'       , $vars, 0);
$allow_delete   = elgg_extract('allow_delete'     , $vars, true);
$form_wrapper   = elgg_extract('form_wrapper'     , $vars, false);
$display_state  = elgg_extract('display_state'    , $vars);
$tally          = elgg_extract('tally'            , $vars);
$origin         = elgg_extract('origin'           , $vars);
/**/ 
$disabled       = $display_state == 'view';

$class_boqx[]     = 'envelope__NkIZUrK4';
$class_add[]      = 'envelopeWindow__3hpw9wdN';
$class_edit[]     = 'envelopeWindow__3hpw9wdN';
$class_show[]     = 'envelopeWindow__3hpw9wdN';
$class_qty        = 'TaskShow__qty_7lVp5tl4';
$class_title      = 'TaskShow__title___O4DM7q';
$class_total[]    = 'TaskShow__item_total__Dgd1dOSZ';
$class_items      = 'TaskShow__service_items___2wMiVig';
$class_time       = 'TaskShow__time___cvHQ72kV';
$class_delete     = 'IconButton___2y4Scyq6';      
$class_remove     = 'remove-item';
$add_visible      = $visible == 'add'  ? false  : 'display:none';
$show_visible     = $visible == 'show' ? false  : 'display:none';
$edit_visible     = $visible == 'edit' ? false  : 'display:none';
$contents_visible = $visible == 'contents' ? true  : false; 
if ($has_collapser) $collapser = elgg_format_element('a',['id'=>"collapser_$cid",'class'=>['autosaves','collapser',"collapser-$task",'CollapseEnvelope__z7DilsLc'],'data-cid'=>$cid,'tabindex'=>'-1']);
if ($guid == 0)      $guid = false;
if (elgg_entity_exists($guid)){
   $entity = get_entity($guid);
   $title  = $entity->title;
   $qty    = $entity->qty;
   $total  = $entity->total;
   $items  = $entity->items;
   $time   = $entity->time;
}
// $jot supercedes $entity
if ($jot){
//    $show_title = elgg_format_element('div',[],
//                      elgg_format_element('span',['class'=>$class_qty]  , $jot->qty).
//                      elgg_format_element('span',['class'=>$class_title], $jot->label));
   $qty   = $jot->qty;
   $title = $jot->title;
}
   
/**/
/*defaults*/
$add_label = 'Add something';
$boqx_aspect= $task;

echo "<!--origin=$origin, task=$task, action=$action, has_collapser=>$has_collapser, guid=$guid, presentation=$presentation, presence=$presence, parent_cid=$parent_cid, carton_id=$carton_id, cid=$cid, visible=$visible, fill_level=$fill_level -->";

switch($task){
    case 'item':
        unset($class_time);
        $add_label    = 'Add an item';
        $class_boqx[] = 'Item__nhjb4ONn';
        $class_add[]  = 'AddSubresourceButton___oKRbUbg6';
        $class_show[] = 'ItemShow_Btc471up';
        $class_edit[] = 'ItemEdit___7asBc1YY';
        break;
    case 'things':
        unset($class_time);
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner = true;
        break;
    case 'thing':
        unset($class_time);
        $add_label    = 'Add an item';
        $class_boqx[] = 'Effort__CPiu2C5N';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'EffortShow_haqOwGZY';
        $class_edit[] = 'EffortEdit_fZJyC62e';
        break;
    case 'receipt_item':
        unset($class_time);
        $add_label    = 'Add an item';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
//         $class_boqx[] = 'Item__nhjb4ONn';
//         $class_add[]  = 'AddSubresourceButton___oKRbUbg6';
//         $class_show[] = 'ItemShow_Btc471up';
//         $class_edit[] = 'ItemEdit___7asBc1YY';
        break;
    case 'receipt':
        unset($class_time);
        $add_label    = 'Add a receipt';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_delete = 'IconButton___SUFDHCSY';
        break;
    case 'experiences':
        unset($class_time);
        $add_label    = 'Add experiences';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner        = true;
        break;
    case 'experience':
        unset($class_time);
        $add_label    = 'Add an experience';
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
    case 'issues':
        unset($class_time);
        $add_label    = 'Add issues';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner        = true;
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
    case 'items':
        unset($class_time);
        $add_label    = 'Add items';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner = true;
        break;
    case 'receipt_items':
        unset($class_time);
        $add_label    = 'Add items';
        $class_total[]= $cid.'_total'; 
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner = true;
        break;
    case 'parts':
        unset($class_time);
        $add_label    = 'Add parts';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner = true;
        break;
    case 'efforts':
        $add_label    = 'Add time';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner = true;
        break;
    case 'contents':
        unset($class_time);
        $add_label    = 'Add contents';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner = true;
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
    case 'characteristic':
        unset($class_time);
        $add_label    = 'New characteristic';
        $class_boqx[] = 'ServiceEffort__SW0QbAto';
        $class_add[]  = 'AddSubresourceButton___xI4azKNIW';
        break;
    case 'feature':
        unset($class_time);
        $add_label    = 'New feature';
        $class_boqx[] = 'ServiceEffort__SW0QbAto';
        $class_add[]  = 'AddSubresourceButton___cWgofWCr';
        break;
    default:
        $show_boqx    = $show_boqx ?: 'Details';
        $class_boqx[] = 'ServiceEffort__26XCaBQk';
        $class_add[]  = 'AddSubresourceButton___2PetQjcb';
        $class_show[] = 'TaskShow___2LNLUMGe';
        $class_edit[] = 'TaskEdit___1Xmiy6lz';
        $class_edit[] = 'linedEnvelope';
        $liner = true;
        break;
}
if($disabled && $tally == 0)
    $class_show[] = 'disabled';

$add = 
    elgg_format_element('div',['class'=>$class_add,'tabindex'=>'0', 'data-aid'=>'TaskAdd','data-focus-id'=>'TaskAdd','data-cid'=>$cid,'style'=>$add_visible, 'data-guid'=>$guid],
        elgg_format_element('span',['class'=>'AddSubresourceButton__icon___h1-Z9ENT']).
        elgg_format_element('span',['class'=>'AddSubresourceButton__message___2vsNCBXi'],$add_label)
    );

if($show_title)
     $show_title_span = elgg_format_element('span',[]                     , $show_title);
else $show_title_span = elgg_format_element('span',['class'=>$class_qty]  , $qty).
                        elgg_format_element('span',['class'=>$class_title], $title).
                        elgg_format_element('span',['class'=>$class_total], $total).
                        elgg_format_element('span',['class'=>$class_items], $items).
                        elgg_format_element('span',['class'=>$class_time] , $time);
                       
$info_boqx = is_array($info_boqx) ? implode('',$info_boqx) : $info_boqx; 
if ($edit_title)
    $info_boqx = elgg_format_element('h4',[], $edit_title).$info_boqx;

if($allow_delete)
$delete         = elgg_format_element('button',['class'=>[$class_delete,'IconButton--small___3D375vVd'],'data-aid'=>'delete','aria-label'=>'Delete','data-cid'=>$cid],
                      elgg_format_element("span",['class'=>$class_remove],
                          elgg_format_element('a', ['title' =>'remove item'], 
                              elgg_view_icon('delete-alt'))));   
$show = $show_boqx ?
    // if true
    elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible],$show_boqx)
    :
    // if false
    elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible],
        elgg_format_element('input',['class'=>'TaskShow__checkbox___2BQ9bNAA','type'=>'checkbox','title'=>'mark task complete','data-aid'=>'toggle-complete','data-focus-id'=>"TaskShow__checkbox--$cid"]).
        elgg_format_element('div',['class'=>['TaskShow__description___3R_4oT7G','tracker_markup_xxx'], 'data-aid'=>'TaskDescription','tabindex'=>'0'],$show_title_span).
         elgg_format_element('nav',['class'=>['TaskShow__actions___3dCdQMej','undefined','TaskShow__actions--unfocused___3SQSv294']],$delete));

// if (strlen($show_boqx)>0)
//     $show = elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible],$show_boqx);
// else 
//     $show = elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible],
//                 elgg_format_element('input',['class'=>'TaskShow__checkbox___2BQ9bNAA','type'=>'checkbox','title'=>'mark task complete','data-aid'=>'toggle-complete','data-focus-id'=>"TaskShow__checkbox--$cid"]).
//                 elgg_format_element('div',['class'=>['TaskShow__description___3R_4oT7G','tracker_markup_xxx'], 'data-aid'=>'TaskDescription','tabindex'=>'0'],$show_title_span).
//                 elgg_format_element('nav',['class'=>['TaskShow__actions___3dCdQMej','undefined','TaskShow__actions--unfocused___3SQSv294']],$delete));

// $show = elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible], $show_boqx);
// $show = $show_boqx;
// $show = elgg_format_element('div',['class'=>$class_show,'data-aid'=>'TaskShow','data-cid'=>$cid,'style'=>$show_visible], $show);

/*if ($form_wrapper){
    $form_wrapper['form_vars']['body']= $hidden_fields.$collapser.$info_boqx;
    $edit_boqx = elgg_view_form($form_wrapper['action'],$form_wrapper['form_vars'],$form_wrapper['body_vars']); 
}
else $edit_boqx = $hidden_fields.$collapser.$info_boqx;
    
$edit = elgg_format_element('div',['class'=>$class_edit,'data-aid'=>'TaskEdit','data-cid'=>$cid,'style'=>$edit_visible],$edit_boqx);*/
if($liner)
    $info_boqx = elgg_format_element('div',['class'=>'liner','data-cid'=>$cid],$info_boqx);
if ($form_wrapper){
    $submit_button = $form_wrapper['body_vars']['submit_button'];
    $form_wrapper['form_vars']['body']= $hidden_fields.$collapser.$info_boqx.$submit_button;
    $edit = elgg_format_element('div',['class'=>$class_edit,'data-aid'=>'TaskEdit','data-cid'=>$cid,'style'=>$edit_visible],
                elgg_view_form($form_wrapper['action'],$form_wrapper['form_vars'],$form_wrapper['body_vars'])); 
}
else $edit = elgg_format_element('div',['class'=>$class_edit,'data-aid'=>'TaskEdit','data-cid'=>$cid,'style'=>$edit_visible],$hidden_fields.$collapser.$info_boqx);

if($task=='characteristic'||$task=='feature')
    unset($show, $edit);

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