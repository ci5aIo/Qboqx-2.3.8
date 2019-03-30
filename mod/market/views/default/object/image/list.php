<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);
$guid = $entity->guid;
echo '<br>market\views\default\object\image\list.php';

$subtitle = elgg_view('object/image/meta', array(
	'entity' => $entity,
	'full_view' => true
		));

$metadata = elgg_view_menu('entity', array(
	'entity' => $entity,
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority'
		));

if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {

	$summary = elgg_view('object/image/gallery', array(
		'size' => '800x200',
		'full_view' => true,
		'entity' => $entity
	));

	$limit = get_input('limit', 20);
	$offset = get_input("offset-images-$entity->guid", 0);

	$options = array(
		'types' => 'object',
		'subtypes' => array('image'),
		'container_guids' => $entity->guid,
		'limit' => $limit,
		'offset' => $offset,
		'count' => true,
		'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC', 'as' => 'integer'),
		'list_type' => get_input('list_type', 'gallery'),
		'gallery_class' => 'gallery-photostream',
		'full_view' => false,
		'pagination' => true,
		'offset_key' => "offset-images-$entity->guid",
	);


	$body = elgg_list_entities_from_metadata($options);
	$comments = elgg_view_comments($entity);

	echo '<div class="gallery-full">';
	echo "$summary$body$comments";
	echo '</div>';
} else {

	$icon = elgg_view('market/thumbnail', array('marketguid' => $guid,
												'size'       => 'medium',
											));
//	$icon = elgg_view_entity_icon($entity, 'medium');

	$params = array(
		'entity' => $entity,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => elgg_get_excerpt(strip_tags($entity->description))
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($icon, $list_body);
}