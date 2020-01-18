<?php
$cid       = elgg_extract('cid', $var, quebx_new_id('c'));
$carton_id = elgg_extract('carton_id', $vars, quebx_new_id('c'));
$aspect    = elgg_extract('aspect', $vars,false);
$title     = elgg_extract('title', $vars);
$pieces    = elgg_extract('pieces', $vars, false);
$footer    = elgg_extract('footer', $vars);
$wrapper   = elgg_extract('wrapper_class', $vars, false);
$class     = elgg_extract_class($vars, false);
/*
if($pieces && !is_array($pieces))
    $pieces = (array) $pieces;
*/
if ($title)
    $title = elgg_format_element('h4',[],$title);
$tally     = elgg_format_element('span',['class'=>['tally','bom'],'boqxes'=>count($pieces)]);
/*
if (count($pieces) > 0)
    foreach($pieces as $piece)
    $content .= $piece;
*/    
if (is_array(($pieces)) && count($pieces)>0)
     $content = implode('', $pieces);
else $content = $pieces;
    
if($class && !is_array($class))
    $class = (array) $class;
$class[] = 'boqx-carton';

if($wrapper)
    $form_body = elgg_format_element('div',['class'=>$wrapper], 
                    elgg_format_element('div',['id'=>$carton_id, 'class'=>$class, 'data-boqx'=>$cid, 'data-aspect'=>$aspect],
                        $title.
                        $tally.
                        $content).
                     $footer);
else 
    $form_body = elgg_format_element('div',['id'=>$carton_id, 'class'=>$class, 'data-boqx'=>$cid, 'data-aspect'=>$aspect],
                    $title.
                    $tally.
                    $content.
                    $footer);

echo $form_body;