<?php
$content   = elgg_extract('content', $vars);
$qid       = elgg_extract('qid', $vars);
$max_width = elgg_extract('max_width', $vars);
$class     = 'jq-dropdown jq-dropdown-persistent';
$show_tip  = elgg_extract('show_tip', $vars, true);
if ($show_tip) 
   $class .= ' jq-dropdown-tip';

if (isset($vars['class'])) 
    $class .= " {$vars['class']}";

$body = elgg_view('output/div', ['content' => elgg_view('output/div',['content'=> $content,
                                                                      'class'  =>'jq-dropdown-panel',
                                                                      'options'=>['style'=>"overflow:visible;max-width:$max_width;padding:0"]]),
                                'class'    => $class,
                                'options'  => ['id'=>$qid]]);

echo $body;