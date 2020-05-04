<?php
$guid = elgg_extract('guid', $vars);
$aspect = elgg_extract('aspect', $vars);
$boqx_id = elgg_extract('boqx_id', $vars, quebx_new_id('a'));
$controlbar = elgg_extract('control_bar', $vars);
$slots = elgg_extract('slots', $vars);
$entity = get_entity($guid);

$layout = elgg_format_element('section',['class'=>["open-boqx",$aspect]],
              elgg_format_element('div',['id'=>$boqx_id,'class'=>"boqx-control-area"],
                  $controlbar).
        	  elgg_format_element('article',['class'=>"boqx-load-area",'data-aspect'=>""],
        	      elgg_format_element('section',['class'=>"boqx-load-heading"],
        			  elgg_format_element('div',['class'=>"name"],
        				  elgg_format_element('h2',['class'=>"boqx-title"],$entity->title))).
        		  elgg_format_element('section',['class'=>"floor",'data-scrollable'=>"true"],
        			  elgg_format_element('div',['id'=>"view111",'class'=>"slots",'style'=>"width:1900px;"],
        				  $slots))));
echo $layout;