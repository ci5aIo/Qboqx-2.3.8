<?php
$cid       = elgg_extract('cid', $var, quebx_new_id('c'));
$carton_id = elgg_extract('carton_id', $vars, quebx_new_id('c'));
$aspect    = elgg_extract('aspect', $vars,false);
$title     = elgg_extract('title', $vars);
$pieces    = elgg_extract('pieces', $vars);
$footer    = elgg_extract('footer', $vars);
if(!is_array($pieces))
    $pieces = (array) $pieces;
$boqxes = count($pieces);

if ($title) $title = elgg_format_element('h4',[],$title).
                     elgg_format_element('span',['class'=>['tally','bom'],'boqxes'=>$boqxes]);
else        $title = elgg_format_element('span',['class'=>['tally','bom'],'boqxes'=>$boqxes]);

if ($boqxes > 0)
    foreach($pieces as $piece)
    $content .= $piece;

$form_body = elgg_format_element('div',['id'=>$carton_id, 'class'=>'boqx-carton', 'data-boqx'=>$cid, 'data-aspect'=>$aspect],
                $title.
                $content.
                $footer);

echo $form_body;