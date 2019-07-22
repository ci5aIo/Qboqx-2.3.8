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

$entity = elgg_extract('entity', $vars, false);
$parent_cid = elgg_extract('cid', $vars);
$cid    = quebx_new_id('c');
$guid   = $entity->getGUID();
$title  = $entity->title;
$points = elgg_extract('points', $vars, -1);
$owner = $entity->getOwnerEntity();
$owner_name = $owner->name;
$owner_initials = quebx_initials($owner_name);
/*placeholders*/
$entity->blocked = true;
$item_labels[] = 'juxtaposition';
$item_labels[] = 'mechanical';
$item_labels[] = 'sophisticated';
$item_labels[] = 'wonder';
foreach ($item_labels as $item_label){
    $labels_post .= elgg_format_element('a', ['class'=>'std label', 'tabindex'=>'-1'], $item_label); 
}
if (count($item_labels > 0)){
    $labels = elgg_format_element('span',['class'=>'labels post'], $labels_post);
}
/*****/
$expander = elgg_format_element('button',
                               ['class'         => 'expander undraggable',
                                'data-aid'      => 'StoryPreviewItem__expander',
                                'data-guid'     => $guid,
                                'data-cid'      => $cid,
                                'aria-expanded' => 'false',
                                'tabindex'      => '-1',
                                'aria-label'    => 'expander']);
$metadata = elgg_format_element('span',
                               ['class'         => 'meta'],
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

$body = "
      <header tabindex='0' 
              data-aid='StoryPreviewItem__preview' 
              class='preview'
              data-cid='$cid'>
              $expander
              <a class='selector undraggable' 
                 title='Select this story for bulk actions' 
                 tabindex='-1'></a>
              $metadata
              <a class='reveal story button' 
                 data-cid='$cid' 
                 data-guid='$guid' 
                 data-type='item'
                 tabindex='-1'>
                 <span class='locator' 
                       title='Reveal this story'></span>
              </a>
              $progress
              <span class='name normal'>
                   <span class='story_name'>
                        <span class='tracker_markup' 
                              data-aid='StoryPreviewItem__title'>$title</span>
                        <span class='parens'>
                             <a class='owner' 
                                tabindex='-1' 
                                title='$owner_name'>$owner_initials</a>
                        </span>
                   </span>
                   $labels
                   <span class='StoryPreviewItemReviewList___2PqmkeBu'></span>
              </span>
              $blocker
      </header>";

echo elgg_format_element('div', 
                        ['class'    => "story model item has_tasks draggable story_$guid $cid feature unscheduled point_scale_linear estimate_$points is_estimatable not_collapsed $has_blockers",
                         'data-aid' => 'StoryPreviewItem',
                         'data-cid' => $cid,
                         'data-id'  => $guid,
                         'aria-describedby'=>'reorder-help',
                         'aria-label' => $title],
                        $body);