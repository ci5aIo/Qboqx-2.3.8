<?php

//$quebxguid = $vars['itemguid'];
$guid      = $vars['guid'];
$entity    = get_entity($guid);
$size      = $vars['size'];
$class     = $vars['class'];
$tu        = $vars['tu'];

//echo "<img src='" . elgg_get_site_url() . "mod/quebx/thumbnail.php?itemguid={$quebxguid}&size={$size}&ut={$tu}' class='elgg-photo $class'>";

if (elgg_instanceof($entity, 'object', 'market') && !empty($entity->icon)){
    $guid = $entity->icon;
    $options['guid'] = $guid;
}
else $options['src'] = elgg_get_site_url().'mod/quebx/graphics/noimagetiny.png';

$options['size']  = $size;
$options['class'] = "elgg-photo $class";

$content = elgg_view('output/image',$options);

echo $content;

