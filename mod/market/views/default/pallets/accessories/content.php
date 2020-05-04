<?php
/***
 * @var $guid
 * @var $name
 * @var $cid
 * @var $slot
 * @var $aspect
 */

$guid         = elgg_extract('guid', $vars);
$name         = elgg_extract('name', $vars);
$boqx_id      = elgg_extract('cid' , $vars);
$slot         = elgg_extract('slot', $vars);
$aspect       = elgg_extract('aspect', $vars);
$open_class   = elgg_extract('open_class', $vars,'');

$contents = elgg_get_entities_from_relationship([
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $guid,
    'inverse_relationship' => true,
	'limit' => false,
]);
	
foreach($contents as $entity) {
    $icon_guid = $entity->icon ?: $entity->guid;
    $icon = elgg_view('market/thumbnail', array('marketguid' => $entity->guid, 'size' => 'tiny', 'class'=>'itemPreviewImage_ARIZlwto'));
    $content .= elgg_view('page/components/pallet_boqx', ['entity'=>$entity,'aspect'=>'accessory','boqx_id'=>$boqx_id,'icon'=>$icon,'has_description'=>isset($entity->description)]);
  }

echo elgg_view_layout('pallet',['presentation'=>'open_boqx','id'=>$boqx_id,'slot'=>$slot,'aspect'=>$aspect,'name'=>$name,'pieces'=>quebx_count_pieces($guid, $aspect),'content'=>$content, 'open_class'=>$open_class]);