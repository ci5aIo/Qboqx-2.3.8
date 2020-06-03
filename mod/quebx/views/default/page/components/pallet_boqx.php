<?php
/**
 * Quebx pallet boqx pattern
 *
 * Common pattern where there is an image, icon, media object to the left
 * and a descriptive block of text to the right.
 * 
 * ---------------------------------------------------------------
 * |          |                                      |    alt    |
 * |  image   |               body                   |   image   |
 * |  block   |               block                  |   block   |
 * |          |                                      | (optional)|
 * ---------------------------------------------------------------
 *
 * @uses $vars['body']        HTML content of the body block
 * @uses $vars['image']       HTML content of the image block
 * @uses $vars['image_alt']   HTML content of the alternate image block
 * @uses $vars['class']       Optional additional class (or an array of classes) for media element
 * @uses $vars['id']          Optional id for the media element
 */
$boqx_id        = elgg_extract('boqx_id'          , $vars);
$boqx_class     = elgg_extract('boqx_class'       , $vars);
$guid           = elgg_extract('guid'             , $vars);
$entity         = elgg_extract('entity'           , $vars, get_entity($guid));
if (empty($entity)){ register_error('no entity provided'); goto eof;}
//$parent_cid     = elgg_extract('cid'              , $vars);
$cid            = elgg_extract('cid'              , $vars, quebx_new_id('c'));
$points         = elgg_extract('points'           , $vars, -1);
$aspect         = elgg_extract('aspect'           , $vars, 'thing');
$has_issues     = elgg_extract('has_issues'       , $vars);
$has_description= elgg_extract('has_description'  , $vars, false);
$has_attachments= elgg_extract('has_attachments'  , $vars, false);
$has_comments   = elgg_extract('has_comments'     , $vars, false);
$has_contents   = elgg_extract('has_contents'     , $vars, false);
$issues         = elgg_extract('issues'           , $vars, 0);
$icon           = elgg_extract('icon'             , $vars);
$edit_boqx      = elgg_extract('edit_boqx'        , $vars, false);
$edit_contents  = elgg_extract('edit_contents'    , $vars, false);
$hidden_fields  = elgg_extract('hidden_fields'    , $vars);
$visible        = elgg_extract('visible'          , $vars, 'add');
$has_collapser  = elgg_extract('has_collapser'    , $vars) =='yes' ? true : false;
$presentation   = elgg_extract('presentation'     , $vars);
$presence       = elgg_extract('presence'         , $vars);
$essence        = elgg_extract('essence'          , $vars);
$fill_level     = elgg_extract('fill_level'       , $vars, 0);
$task_aspect    = elgg_extract('task_aspect'      , $vars, false);
$child_toggle   = elgg_extract('child_toggle'     , $vars, false);
$state          = elgg_extract('state'            , $vars, false);
$expandable     = elgg_extract('expandable'       , $vars, true);
$selectable     = elgg_extract('selectable'       , $vars, true);
$show_owner     = elgg_extract('show_owner'       , $vars, true);
$show_labels    = elgg_extract('show_labels'      , $vars, true);
$form_wrapper   = elgg_extract('form_wrapper'     , $vars, false);
$mobility       = elgg_extract('mobility'         , $vars, 'draggable');
$handler        = elgg_extract('handler'          , $vars, false);
/*****/ 
$edit_visible   = $visible == 'edit' ? false  : 'display:none';
//$cid            = quebx_new_id('c');
$guid           = $guid ?: $entity->getGUID();
$title          = $entity->title;
$owner          = $entity->getOwnerEntity();
$owner_name     = $owner->name;
$owner_initials = quebx_initials($owner_name);
$has_blockers   = false;
$context        = elgg_get_context();
switch($fill_level){
    case 0: break;
    case 1:
    case 2:  $points = $fill_level;
    default: $points = 3;
}
$selected = $state == 'selected';

$attached_labels = $entity->tags ?: $entity->labels;
$item_labels = is_array($attached_labels) ? $attached_labels : (array) $attached_labels;
foreach ($item_labels as $item_label){
    $labels_post .= elgg_format_element('a', ['class'=>['std','label'], 'tabindex'=>'-1'], $item_label); 
}
$labels = elgg_format_element('span',['class'=>['labels','post'], 'data-cid'=>$cid], $labels_post);
if(!$show_labels) unset($labels);
$owner_label = elgg_format_element('span',['class'=>'parens'],
                elgg_format_element('a',['class'=>'owner', 'tabindex'=>'-1','title'=>$owner_name],$owner_initials));
if(!$show_owner) unset($owner_label);
$name = elgg_format_element('span',['class'=>'story_name'],
            elgg_format_element('span',['class'=>['tracker_markup','StoryPreviewItem__title'],'data-guid'=>$guid,'data-cid'=>$cid,'data-presentation'=>$presentation],$title).
            $owner_label);
//$expander_class[] = 'expander';
$expander_class[] = 'undraggable';
switch($presentation){
    case 'carton'  : $expander_class[] = 'cartonPreviewItem__expander'; break;
    case 'contents': $expander_class[] = 'contentsPreviewItem__expander'; break;
    default        : $expander_class[] = 'StoryPreviewItem__expander'; break; 
}

$selector_classes = ['selector','undraggable'];
$selector_title   = 'Set this item on the shelf';
if (shelf_item_is_on_shelf($guid)){
    $open_state = shelf_item_attr_value($guid, 'open_state');
    $selector_classes[]='selected';
    $close_boqx_classes[]='close_item';
    if($open_state != 'closed'){
        $selector_classes[]='open';
        $close_boqx_classes[]='visible';
    }
    $selector_title = 'Item is on the shelf';
}
$close_boqx     = elgg_format_element('div',['class'=>$close_boqx_classes],
                       elgg_format_element('ul',[],
                		    elgg_format_element('li',['class'=>["dropdown_item"], 'data-value'=>"close",'data-index'=>"1"],
                    			    elgg_format_element('a',['id'=>"contents_open_state_dropdown_$cid",'class'=>['pick','close-boqx']],
                    				    elgg_format_element('span',['class'=>"dropdown_label"],'close')))));
$selector                  = elgg_format_element('a',['class'=>$selector_classes,'open-state'=>$open_state, 'title'=>$selector_title,'data-cid'=>$cid,'tabindex'=>'-1']);
$menu_options['item']      =['contents','accessories','components'];
$menu_options['experience']=['items'];
$open_state_dropdown = elgg_format_element('div',['class'=>['dropdown','open-state'],'data-cid'=>$cid],
                            $selector.
                        	elgg_format_element('a',['id'=>"open_state_dropdown_{$cid}_arrow", 'class'=>['arrow', 'target'],'tabindex'=>'-1']).
                        	elgg_format_element('section',['class'=>['open-state','closed']],
                            	elgg_format_element('div',['class'=>'dropdown_menu'],
                            	     $close_boqx.
                            	     elgg_format_element('div',['class'=>'open_item'],'Open to pack:').
                            	     elgg_format_element('ul',[],
                            		    elgg_format_element('li',['class'=>["dropdown_item"], 'data-value'=>"contents",'data-index'=>"1"],
                            		        elgg_format_element('div',['class'=>'pick-slot'],
                                			    elgg_format_element('a',['id'=>"contents_open_state_dropdown_$cid",'class'=>['pick','open-contents']],
                                				    elgg_format_element('span',['class'=>"dropdown_label"],'contents')))).
                            			elgg_format_element('li',['class'=>["dropdown_item"],'data-value'=>"accessories",'data-index'=>"1"],
                            		        elgg_format_element('div',['class'=>'pick-slot'],
                                			    elgg_format_element('a',['id'=>"accessories_open_state_dropdown_$cid",'class'=>['pick','open-accessories']],
                                				    elgg_format_element('span',['class'=>"dropdown_label"],'accessories')))).
                            			elgg_format_element('li',['class'=>["dropdown_item"],'data-value'=>"components",'data-index'=>"1"],
                            		        elgg_format_element('div',['class'=>'pick-slot'],
                                			    elgg_format_element('a',['id'=>"components_open_state_dropdown_$cid",'class'=>['pick','open-components']],
                                				    elgg_format_element('span',['class'=>"dropdown_label"],'components'))))))));
if($entity->getSubtype()=='market'||$entity->getSubtype()=='item')
     $pick_check = $open_state_dropdown;
else $pick_check = $selector;
if(!$selectable) unset($pick_check);
if ($points  == -1 && $point_scale == 'point_scale_linear'){
    for ($i=0; $i<=3; $i++){$buttons .= elgg_format_element('button', ['class'=>"estimate__item estimate_$i", 'tabindex'=>'-1']);}
    $progress = elgg_format_element('span', ['class'=>'estimate'], $buttons);
}
Switch ($aspect){
    case 'thing'     : 
    case 'item'      : unset($estimate,$schedule,$has_tasks,$task_aspect,$estimatable,$point_scale);
                       $this_aspect ='item';
                       $points      = "-1";
                       $meta_span   = $icon;
    break;
    case 'photo_boqx': 
        break;
    case 'file'      :
    case 'receipt'   :
    case 'transfer'  :
    case 'experience':
    case 'project'   :
    case 'issue'     :$this_aspect     = $aspect;
                       $estimate        = 'estimate_'.$points;
                       $meta_span       = elgg_format_element('span', [], $points);
                       $schedule        = 'unscheduled';
                       $has_tasks       = true;
                       $task_aspect     = 'feature';
                       $estimatable     = true;
                       $point_scale     = 'point_scale_linear';
    break;
    case 'contents'  : unset($reveal, $selector, $progress,$has_tasks);
                       $this_aspect     = $aspect;
                       $estimate        = 'estimate_'.$points;
                       if($child_toggle)
                            $meta_span  = $child_toggle;
                       else $meta_span  = elgg_format_element('span', [], $points);
                       $estimatable     = true;
                       $point_scale     = 'point_scale_linear';
                       if($fill_level == 1)
                            $meta_title = "contains $fill_level item";
                      if($fill_level > 1)
                            $meta_title = "contains $fill_level items";
    break;
    case 'accessory'  : unset($reveal, $selector, $progress,$has_tasks);
                       $this_aspect     = $aspect;
                       $estimate        = 'estimate_'.$points;
                       if($child_toggle)
                            $meta_span  = $child_toggle;
                       else $meta_span  = elgg_format_element('span', [], $points);
                       $estimatable     = true;
                       $point_scale     = 'point_scale_linear';
                       if($fill_level == 1)
                            $meta_title = "contains $fill_level item";
                      if($fill_level > 1)
                            $meta_title = "contains $fill_level items";
    break;
}
if ($has_collapser) $collapser = elgg_format_element('a',['id'=>"collapser_$cid",'class'=>['autosaves','collapser',"collapser-$task",'CollapseEnvelope__z7DilsLc'],'data-cid'=>$cid,'tabindex'=>'-1']);
//if ($edit_boqx)     $edit      = elgg_format_element('div',['class'=>$class_edit,'data-aid'=>'TaskEdit','data-cid'=>$cid,'style'=>$edit_visible],$hidden_fields.$collapser.$edit_boqx);

// if ($form_wrapper){
//     $form_wrapper['form_vars']['body']= $edit_boqx;
//     $edit = elgg_view_form($form_wrapper['action'],$form_wrapper['form_vars'],$form_wrapper['body_vars']); 
// }
// else
    $edit = $edit_boqx;

/*****/
$expander = elgg_format_element('button',['class'=>$expander_class,'data-guid'=> $guid,'data-cid'=> $cid,'data-presentation'=>$presentation,'data-presence'=>$presence,'aria-expanded' => 'false','tabindex'=> '-1','aria-label' => 'expander']);
$metadata = elgg_format_element('span',['class'=>'meta','title'=>$meta_title],$meta_span);
if(!$expandable) unset($expander);
/*
if ($points  >= 0 && $point_scale == 'point_scale_linear'){
    $button   = elgg_format_element('label', ['data-aid'=>"StateButton", 'data-destination-state'=>"start", 'class'=>"state button start", 'tabindex'=>"-1"],'Start');
    $progress = elgg_format_element('span', ['class'=>'state'], $button);  
}**/
if ($has_issues) {
    if ($issues > 1) $issues_title = "$issues issues";
    if ($issues <= 1) $issues_title = "1 issue";
//    $has_blockers = true;
}
if($presentation == 'nested'){
    $remove = elgg_format_element('nav',['class'=>'ItemShow__actions__e6e6wwhJ'],
                  elgg_format_element('button',['class'=>['IconButton__a3w2LGYY','IconButton--small___3D375vVd'],'data-aid'=>'remove','aria-label'=>'Remove','data-cid'=>$cid],
                      elgg_format_element("span",['class'=>'remove-item'], 
                          elgg_format_element('a', ['title' =>'remove item'], 
                              elgg_view_icon('delete-alt')))));
}

$reveal = elgg_format_element('a',['class'=>['reveal',$this_aspect,'button'],'data-cid'=>$cid,'data-guid'=>$guid,'data-type'=>$this_aspect,'data-handler'=>$handler, 'tabindex'=>'-1'],
              elgg_format_element('span',['class'=>'locator','title'=>'Reveal this item']));

if ($aspect == 'issue') $issue = elgg_format_element('div', ['class'=>"blocker",'data-aid'=>"StoryPreviewBlocker"]);
else                    $issue = elgg_format_element('div', ['class'=>"blocked",'data-aid'=>"StoryPreviewBlocked"],
                                     elgg_format_element('span',['title'=>$issues_title]));
$body = 
      elgg_format_element('header',['class'=>'preview','data-cid'=>$cid,'data-aid'=>'StoryPreviewItem__preview','tabindex'=>'0'],
          $expander.
          $pick_check.
          $metadata.
          $reveal.
          $remove.
          $progress.
          elgg_format_element('span',['class'=>['name','normal'],'data-cid'=>$cid],
              $name.
              $labels.
              elgg_format_element('span',['class'=>'StoryPreviewItemReviewList___2PqmkeBu'])).
          $issue.
          $edit_contents).
          $edit;
                     $class[]='boqx';
if($this_aspect)     $class[]=$this_aspect;
if($presentation)    $class[]=$presentation;
if($has_tasks)       $class[]='has_tasks';
if($has_description) $class[]='description'; 
if($has_attachments) $class[]='attachments';
if($has_comments)    $class[]='comments';
if($selected)        $class[]='selected';
if($task_aspect)     $class[]=$task_aspect;
if($schedule)        $class[]=$schedule;
if($point_scale)     $class[]=$point_scale;
if($estimate)        $class[]=$estimate; 
if($estimatable)     $class[]='is_estimatable'; 
                     $class[]='not_collapsed';
if($has_issues)      $class[]='has_issues';
if($has_contents)    $class[]='has_contents';
if($boqx_class)      $class[] = $boqx_class;
if (shelf_item_is_on_shelf($guid) && $open_state != 'closed'){
                     $class[]='open';
                     $open_attr=$open_state;
}
echo elgg_format_element('div', ['id'               => $cid,
                                 'class'            => $class,
                                 'data-aid'         => 'StoryPreviewItem',
                                 'data-guid'        => $guid,
                                 'data-aspect'      => $this_aspect,
                                 'data-presence'    => $presence,
                                 'data-essence'     => $essence,
                                 'data-presentation'=> $presentation,
                                 'data-mobility'    => $mobility,
                                 'data-boqx'        => $boqx_id,
                                 'open-state'       => $open_attr,
                                 'data-issues'      => $issues,
                                 'aria-describedby' => $presentation,
                                 'aria-label'       => $title],
                                $hidden_fields.
                                $body);
eof: