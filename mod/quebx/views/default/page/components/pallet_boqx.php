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
$boqx_id = elgg_extract('boqx_id', $vars);
$entity = elgg_extract('entity', $vars, false);
$parent_cid = elgg_extract('cid', $vars);
$cid    = quebx_new_id('c');
$guid   = $entity->getGUID();
$title  = $entity->title;
$points = elgg_extract('points', $vars, -1);
$owner = $entity->getOwnerEntity();
$owner_name = $owner->name;
$owner_initials = quebx_initials($owner_name);
$aspect = elgg_extract('aspect', $vars, 'thing');
/*placeholders*/
$entity->blocked = true;
/**/
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
Switch ($aspect){
    case 'thing'     : $contents_aspect ='item' ; break;
    case 'receipt'   :
    case 'experience':
    case 'project'   :
    case 'issue'     : $contents_aspect =$aspect; break;
}
/*****/
$expander = elgg_format_element('button',['class'=> 'expander undraggable StoryPreviewItem__expander','data-guid'=> $guid,'data-cid'=> $cid,'aria-expanded' => 'false','tabindex'=> '-1','aria-label' => 'expander']);
$metadata = elgg_format_element('span',['class' => 'meta'],
                elgg_format_element('span', [], $points));
if ($points == -1){
    for ($i=0; $i<=3; $i++){$buttons .= elgg_format_element('button', ['class'=>"estimate__item estimate_$i", 'tabindex'=>'-1']);}
    $progress = elgg_format_element('span', ['class'=>'estimate'], $buttons);
}
else {
    $button   = elgg_format_element('label', ['data-aid'=>"StateButton", 'data-destination-state'=>"start", 'class'=>"state button start", 'tabindex'=>"-1"],'Start');
    $progress = elgg_format_element('span', ['class'=>'state'], $button);  
}
if ($entity->blocked) {
    $blocker      = elgg_format_element('div', ['class'=>"blocker",'data-aid'=>"StoryPreviewBlocker"]);
    $has_blockers = 'has_blockers_or_blocking';
}

$body = 
      elgg_format_element('header',['class'=>'preview','data-cid'=>$cid,'data-aid'=>'StoryPreviewItem__preview','tabindex'=>'0'],
          $expander.
          elgg_format_element('a',['class'=>['selector','undraggable'],'title'=>'Set this item on the shelf','data-cid'=>$cid,'tabindex'=>'-1']).
          $metadata.
          elgg_format_element('a',['class'=>['reveal','story','button'],'data-cid'=>$cid,'data-guid'=>$guid,'data-type'=>'item','tabindex'=>'-1'],
              elgg_format_element('span',['class'=>'locator','title'=>'Reveal this item'])).
          $progress.
          elgg_format_element('span',['class'=>['name','normal'],'data-cid'=>$cid],
              $name.
              $labels.
              elgg_format_element('span',['class'=>'StoryPreviewItemReviewList___2PqmkeBu'])).
          $blocker);

echo elgg_format_element('div', 
                        ['id'       => $cid,
                         'class'    => ['story model', $contents_aspect, 'has_tasks', 'draggable', 'story_'.$cid, 'feature', 'unscheduled', 'point_scale_linear', 'estimate_'.$points, 'is_estimatable', 'not_collapsed', $has_blockers],
                         'data-aid' => 'StoryPreviewItem',
                         'data-guid' => $guid,
                         'data-boqx' => $boqx_id,
                         'aria-describedby'=>'reorder-help',
                         'aria-label' => $title],
                        $body);