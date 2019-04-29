<?php
$entity    = elgg_extract('entity', $vars);
$container = get_entity($entity->container_guid);
$guid      = $entity->getGUID();

$metadata  = elgg_view_menu('entity', [
	'entity' => $entity,
	'handler' => 'market',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',]);
if (!empty($entity->icon)){$icon_guid = $entity->icon;}              
else                    {$icon_guid = $entity->guid;}
$image = elgg_view('quebx/thumbnail', ['guid' => $icon_guid, 'size' => 'medium', 'tu' => $tu]);
$market_img = elgg_view('output/url', array(
	'href' => "market/view/$owner->username",
	'text' => $image,
));
$loose_things = elgg_get_entities_from_metadata([
	'type'           => 'object',
    'subtype'        => 'boqx',
	'container_guid' => $guid,
//	'metadata_name_value_pairs' => ['aspect' => 'loose things']
    
]);                register_error(print_r($loose_things, true));

$params = [
		'entity'     => $entity,
        'contents'   => $loose_things, //[$loose_things, $receipts],
		'metadata'   => $metadata,
		'title'      => elgg_view('output/url', ['data-guid'        => $guid,
				                                 'data-qid'         => "q{$guid}",
				                                 'data-qid_n'       => "q{$guid}_0",
				                                 'data-element'     => 'boqx',
				                                 'data-space'       => $entity->getSubtype(),
				                                 'data-perspective' => 'view',
				                                 'data-presentation'=> 'inline',
				                                 'data-context'     => elgg_get_context(),
				                                 'text'             => $entity->title,
				                                 'class'            => 'do']),
		'show_activity'  => true,
        'show_contents'  => true,
	    'show_comments'  => false,
	    'include_dropbox'=> false];
$vars['body_class']      = 'quebx-menu-q';

$params = array_merge($vars, $params);
$list_body = elgg_view('page/elements/list', $params);
	
$list_body = str_replace("elgg-body", "elgg-body elgg-menu-q", $list_body);
$content  .= elgg_view_image_block($market_img, $list_body, $vars);
		
//if ($container->guid == elgg_get_logged_in_user_guid()) 
//    echo elgg_view_entity($entity, ['item_view'=>'object/default']);
echo $content;












eof: