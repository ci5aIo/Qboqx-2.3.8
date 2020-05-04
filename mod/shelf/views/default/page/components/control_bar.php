<?php

$menu_items = elgg_view('page/components/menu_items',$vars);
$control_bar = elgg_format_element('aside',['class'=>"controlbar",'data-aspect'=>"qboqx"],
                   elgg_format_element('div',['class'=>"controlbar_wrapper"],
                       elgg_format_element('div',['class'=>"controlbar_header"],                      
                            elgg_format_element('div',['class'=>"name"],
                                 elgg_format_element('span',['class'=>"controlbar-title"],'Open Boqx'))).
                       elgg_format_element('section',['class'=>"control-content scrollable"],
                             elgg_format_element('section',['class'=>"menu"],
                                  elgg_format_element('div',['class'=>"panels"],$menu_items))).
                          elgg_format_element('footer',['class'=>"controlbar_footer"],
                               elgg_format_element('div',[],
                                    elgg_format_element('button',['class'=>"open_close",'title'=>"Expand or contract"],'&hellip;')))));
                                    
echo $control_bar;