<?php
$cid       = elgg_extract('cid'          , $vars, quebx_new_id('c'));
$carton_id = elgg_extract('carton_id'    , $vars, quebx_new_id('c'));
$envelope_id = elgg_extract('envelope_id', $vars);
$aspect    = elgg_extract('aspect'       , $vars, false);
$title     = elgg_extract('title'        , $vars, '');
$pieces    = elgg_extract('pieces'       , $vars, false);
$footer    = elgg_extract('footer'       , $vars);
$wrapper   = elgg_extract('wrapper_class', $vars, false);
$class     = elgg_extract_class($vars, false);
$tally_count = elgg_extract('tally'      , $vars, 0);                        $display .= 'page/layouts/carton $cid:'. $cid.'<br>';
/*
if($pieces && !is_array($pieces))
    $pieces = (array) $pieces;
*/
if ($title && $title!= '')
    $title = elgg_format_element('h4',[],$title);
$tally     = elgg_format_element('span',['class'=>['tally','bom'],'boqxes'=>$tally_count]);

if (is_array(($pieces)) && count($pieces)>0)
     $content = implode('', $pieces);
else $content = $pieces;
    
if($class && !is_array($class))
    $class = (array) $class;
$class[] = 'boqx-carton';

if($wrapper)
    $form_body = elgg_format_element('div',['class'=>$wrapper], 
                    elgg_format_element('div',['id'=>$carton_id, 'class'=>$class, 'data-boqx'=>$cid, 'data-envelope'=>$envelope_id, 'data-aspect'=>$aspect],
                        $title.
                        $tally.
                        $content).
                     $footer);
else 
    $form_body = elgg_format_element('div',['id'=>$carton_id, 'class'=>$class, 'data-boqx'=>$cid, 'data-envelope'=>$envelope_id, 'data-aspect'=>$aspect],
                    $title.
                    $tally.
                    $content.
                    $footer);
echo '<!-- layout=carton, $tally='.$tally_count.'-->';
echo $form_body;
//register_error($display);