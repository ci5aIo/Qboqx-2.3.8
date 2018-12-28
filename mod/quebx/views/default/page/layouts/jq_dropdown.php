<?php
$content   = elgg_extract('content', $vars);
$qid       = elgg_extract('qid', $vars);
$max_width = elgg_extract('max_width', $vars);

$body = elgg_view('output/div', ['content' => elgg_view('output/div',['content'=> $content,
                                                                      'class'  =>'jq-dropdown-panel',
                                                                      'options'=>['style'=>"overflow:visible;max-width:$max_width;padding:0"]]),
                                'class'    => 'jq-dropdown jq-dropdown-tip',
                                'options'  => ['id'=>$qid]]);

echo $body;