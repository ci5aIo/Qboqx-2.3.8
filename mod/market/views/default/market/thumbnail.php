<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$img_guid        = $vars['marketguid'];
$options['guid'] = $img_guid; 
$entity          = get_entity($img_guid);
if (elgg_instanceof($entity, 'object', 'market') && !empty($entity->icon)){
    $options['guid'] = $entity->icon;
}
else $options['src'] = elgg_get_site_url().'mod/quebx/graphics/noimagetiny.png';
$size =  $vars['size'];
$class = $vars['class'];
$tu = $vars['tu'];

$options['size']  = $size;
$options['class'] = "elgg-photo $class";
$content = elgg_view('output/image',$options);
//$content = elgg_view('output/image',['guid'=>$img_guid, 'size'=>$size,'class'=>"elgg-photo $class"]);
//$content = elgg_view('output/image',['src'=>elgg_get_site_url() . "gallery/icon/$marketguid/$size",'class'=>"elgg-photo $class"]);

//echo "<img src='" . elgg_get_site_url() . "mod/market/thumbnail.php?marketguid=$marketguid&size=$size&tu=$tu' class='elgg-photo $class'>";
//echo "<img src='" . elgg_get_site_url() . "gallery/icon/$marketguid/$size' class='elgg-photo $class'>";
echo $content;
