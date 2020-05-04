<?php
$boqx_id       = elgg_extract('cid', $vars);
$heading       = elgg_extract('heading', $vars, false);
$metadata_name = elgg_extract('metadata_name', $vars, 'description');
$value         = elgg_extract('value', $vars, false);
$html_tag      = elgg_extract('html_tag', $vars,'div');
$placeholder   = $value ? elgg_format_element('span',['class'=>'tracker_markup'],elgg_format_element('p',[],$value)) : "(No $metadata_name)";
$cid           = quebx_new_id('c');
if($heading) 
    $show_heading = elgg_format_element('h4',[],$heading);

$description  = elgg_format_element($html_tag,['id'=>$cid, 'data-boqx'=>$boqx_id, 'class'=>'Description___3oUx83yQ_xxx','data-aid'=>$heading],
                     $show_heading.
                     elgg_format_element('div',['class'=>['DescriptionShow___3-QsNMNj','DescriptionShow__placeholder___1NuiicbF'],'data-cid'=>$cid, 'tabindex'=>'0','data-aid'=>'renderedDescription','data-focus-id'=>"DescriptionShow--$cid"],$placeholder));
echo $description;