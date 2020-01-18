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
$entity         = elgg_extract('entity'           , $vars, false);
//$parent_cid     = elgg_extract('cid'              , $vars);
$cid            = elgg_extract('cid'              , $vars, quebx_new_id('c'));
$points         = elgg_extract('points'           , $vars, -1);
$aspect         = elgg_extract('aspect'           , $vars, 'thing');
$blocked        = elgg_extract('has_issues'       , $vars);
$has_description= elgg_extract('has_description'  , $vars, false);
$has_attachments= elgg_extract('has_attachments'  , $vars, false);
$has_comments   = elgg_extract('has_comments'     , $vars, false);
$issues         = elgg_extract('issues'           , $vars, 0);
$icon           = elgg_extract('icon'             , $vars);
$presence       = elgg_extract('presence'         , $vars);
$edit_boqx      = elgg_extract('edit_boqx'        , $vars, false);
$hidden_fields  = elgg_extract('hidden_fields'    , $vars);
$visible        = elgg_extract('visible'          , $vars, 'add');
$has_collapser  = elgg_extract('has_collapser'    , $vars) =='yes' ? true : false;
$presentation   = elgg_extract('presentation'     , $vars);
$presence       = elgg_extract('presence'         , $vars);
$fill_level     = elgg_extract('fill_level'       , $vars, 0);
/*****/
$edit_visible   = $visible == 'edit' ? false  : 'display:none';
//$cid            = quebx_new_id('c');
$guid           = $entity->getGUID();
$title          = $entity->title;
$owner          = $entity->getOwnerEntity();
$owner_name     = $owner->name;
$owner_initials = quebx_initials($owner_name);
$has_blockers   = false;
$context        = elgg_get_context();

$attached_labels = $entity->tags ?: $entity->labels;
$item_labels = is_array($attached_labels) ? $attached_labels : (array) $attached_labels;
foreach ($item_labels as $item_label){
    $labels_post .= elgg_format_element('a', ['class'=>['std','label'], 'tabindex'=>'-1'], $item_label); 
}
$labels = elgg_format_element('span',['class'=>['labels','post'], 'data-cid'=>$cid], $labels_post);
$name = elgg_format_element('span',['class'=>'story_name'],
            elgg_format_element('span',['class'=>['tracker_markup','StoryPreviewItem__title'],'data-guid'=>$guid,'data-cid'=>$cid],$title).
            elgg_format_element('span',['class'=>'parens'],
                elgg_format_element('a',['class'=>'owner', 'tabindex'=>'-1','title'=>$owner_name],$owner_initials)));
$expander_class = ['expander','undraggable'];
if ($presentation == 'carton')
     $expander_class[] = 'cartonPreviewItem__expander';
else $expander_class[] = 'StoryPreviewItem__expander';
Switch ($aspect){
    case 'thing'     : $this_aspect ='item';
                       $points      = "-1";
                       $estimate    = false;
                       $meta_span   = $icon;
                       $schedule    = false;
                       $has_tasks   = false;
                       $task_aspect = false;
                       $estimatable = false;
                       $point_scale = false;
                       
    break;
    case 'receipt'   :
    case 'transfer'  :
    case 'experience':
    case 'project'   :
    case 'issue'     : $this_aspect = $aspect;
                       $estimate        = 'estimate_'.$points;
                       $meta_span       = elgg_format_element('span', [], $points);
                       $schedule        = 'unscheduled';
                       $has_tasks       = true;
                       $task_aspect     = 'feature';
                       $estimatable     = true;
                       $point_scale     = 'point_scale_linear';
    break;
}
if ($has_collapser) $collapser = elgg_format_element('a',['id'=>"collapser_$cid",'class'=>['autosaves','collapser',"collapser-$task",'CollapseEnvelope__z7DilsLc'],'data-cid'=>$cid,'tabindex'=>'-1']);
//if ($edit_boqx)     $edit      = elgg_format_element('div',['class'=>$class_edit,'data-aid'=>'TaskEdit','data-cid'=>$cid,'style'=>$edit_visible],$hidden_fields.$collapser.$edit_boqx);
$edit = $edit_boqx;

/*****/
$expander = elgg_format_element('button',['class'=>$expander_class,'data-guid'=> $guid,'data-cid'=> $cid,'aria-expanded' => 'false','tabindex'=> '-1','aria-label' => 'expander']);
$metadata = elgg_format_element('span',['class' => 'meta'],$meta_span);
if ($points  == -1 && $point_scale == 'point_scale_linear'){
    for ($i=0; $i<=3; $i++){$buttons .= elgg_format_element('button', ['class'=>"estimate__item estimate_$i", 'tabindex'=>'-1']);}
    $progress = elgg_format_element('span', ['class'=>'estimate'], $buttons);
}
if ($points  >= 0 && $point_scale == 'point_scale_linear'){
    $button   = elgg_format_element('label', ['data-aid'=>"StateButton", 'data-destination-state'=>"start", 'class'=>"state button start", 'tabindex'=>"-1"],'Start');
    $progress = elgg_format_element('span', ['class'=>'state'], $button);  
}
if ($blocked) {
    if ($issues > 1) $issues_title = "$issues issues";
    if ($issues <= 1) $issues_title = "1 issue";
    $blocker      = elgg_format_element('div', ['class'=>"blocked",'data-aid'=>"StoryPreviewBlocked"],
                         elgg_format_element('span',['title'=>$issues_title]));
    $has_blockers = true;
}
if ($aspect == 'issue') $blocker = elgg_format_element('div', ['class'=>"blocker",'data-aid'=>"StoryPreviewBlocker"]);
if (shelf_item_is_on_shelf($guid))
     $selector = elgg_format_element('a',['class'=>['selector','undraggable', 'selected'],'title'=>'Item is on the shelf','data-cid'=>$cid,'tabindex'=>'-1']);
else $selector = elgg_format_element('a',['class'=>['selector','undraggable'],'title'=>'Set this item on the shelf','data-cid'=>$cid,'tabindex'=>'-1']);

$body = 
      elgg_format_element('header',['class'=>'preview','data-cid'=>$cid,'data-aid'=>'StoryPreviewItem__preview','tabindex'=>'0'],
          $expander.
          $selector.
          $metadata.
          elgg_format_element('a',['class'=>['reveal','story','button'],'data-cid'=>$cid,'data-guid'=>$guid,'data-type'=>'item','tabindex'=>'-1'],
              elgg_format_element('span',['class'=>'locator','title'=>'Reveal this item'])).
          $progress.
          elgg_format_element('span',['class'=>['name','normal'],'data-cid'=>$cid],
              $name.
              $labels.
              elgg_format_element('span',['class'=>'StoryPreviewItemReviewList___2PqmkeBu'])).
          $blocker).
          $edit;

echo elgg_format_element('div', 
                        ['id'       => $cid,
                         'class'    => ['boqx', 
                                        $this_aspect, 
                                        $has_tasks?'has_tasks':false, 
                                        $has_description?'description':false, 
                                        $has_attachments?'attachments':false,
                                        $has_comments?'comments':false, 'draggable',$task_aspect, $schedule, $point_scale, $estimate, $estimatable?'is_estimatable':false, 'not_collapsed', $has_blockers?'has_blockers_or_blocking':false],
                         'data-aid' => 'StoryPreviewItem',
                         'data-guid' => $guid,
                         'data-boqx' => $boqx_id,
                         'data-issues'=> $issues,
                         'aria-describedby'=>'reorder-help',
                         'aria-label' => $title],
                        $body);