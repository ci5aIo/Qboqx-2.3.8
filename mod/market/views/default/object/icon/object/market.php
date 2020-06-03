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

// $marketguid = $vars['marketguid'];
// $entity     = get_entity($marketguid);
$entity     = $vars['entity'];
//@EDIT 2020-05-06 - SAJ subtype 'market' replaced by 'q_item'
if ((elgg_instanceof($entity, 'object', 'market') || elgg_instanceof($entity, 'object', 'q_item')) && !empty($entity->icon)){
    $marketguid = $entity->icon;
}
else {
    $marketguid = $entity->getGUID();
}

$size =  $vars['size'];
$class = $vars['class'];
$tu = $vars['tu'];

echo "<img src='" . elgg_get_site_url() . "mod/market/thumbnail.php?marketguid=$marketguid&size=$size&tu=$tu' class='elgg-photo $class'>";

