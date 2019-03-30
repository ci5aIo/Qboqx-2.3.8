<?php

namespace hypeJunction\Gallery;

use ElggMenuItem;

/**
 * Bypass default access controls
 * - Allow users to add images to shared albums
 *
 * @param string  $hook   Equals 'container_permissions_check'
 * @param string  $type   Equals 'object'
 * @param boolean $return Current permission
 * @param array   $params Additional params
 * @return boolean Updated permission
 */
function container_permissions_check($hook, $type, $return, $params) {

	$container = elgg_extract('container', $params, false);
	$user = elgg_extract('user', $params, false);
	$subtype = elgg_extract('subtype', $params, false);

	if (!elgg_instanceof($container) || !elgg_instanceof($user, 'user') || !$subtype) {
		return $return;
	}

	switch ($container->getSubtype()) {

		case hjAlbum::SUBTYPE :

			switch ($subtype) {

				case hjAlbumImage::SUBTYPE :

					$owner = $container->getOwnerEntity();

					$permission = $container->permission;

					switch ($permission) {

						case 'friends' :
							return $owner->isFriendsWith($user->guid);

						case 'public' :
							return true;

						case 'group' :
							$group = $container->getContainerEntity();
							if (elgg_instanceof($group, 'group')) {
								return $group->isMember($user);
							}
							break;
					}

					return $return;
			}
			break;
	}

	return $return;
}

/**
 * Bypass default editing permissions
 * - Allow users to edit tags that have been added to photos they own
 *
 * @param string  $hook   Equals 'permissions_check'
 * @param string  $type   Equals 'object'
 * @param boolena $return Current permission
 * @param array   $params Additional params
 * @return boolean Updated permission
 */
function permissions_check($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);
	$user = elgg_extract('user', $params, false);

	if (!elgg_instanceof($entity, 'object') || !elgg_instanceof($user, 'user')) {
		return $return;
	}

	switch ($entity->getSubtype()) {

		default :
			return $return;

		case 'hjimagetag' :

			$image = $entity->getContainerEntity();
			if ($image->owner_guid == $user->guid) {
				return true;
			}
			return $return;
	}
}

/**
 * Filter access sql to display disabled entities
 *
 * @param string $hook    'get_sql'
 * @param string $type    'access'
 * @param array  $clauses Current clauses
 * @param array  $params  Additional params
 * @return array Filtered clauses
 */
function filter_access_sql($hook, $type, $clauses, $params) {

	if (!elgg_in_context('show_hidden_entities')) {
		return $clauses;
	}

	$table_alias = elgg_extract('table_alias', $params);
	$table_alias = ($table_alias) ? "{$table_alias}." : "";

	$and_clauses = elgg_extract('ands', $clauses, array());
	if (is_array($and_clauses)) {
		foreach ($and_clauses as $key => $clause) {
			if ($clause == "{$table_alias}enabled = 'yes'") {
				unset($clauses['ands'][$key]);
			}
		}
	}

	return $clauses;
}

/** * Update entity menus * * @param string $hook   Equals 'register' * @param string $type   Equals 'menu:entity' * @param array  $return Current menu array * @param array  $params Additional params * @return array Updated menu */
function entity_menu_setup($hook, $type, $return, $params) {	$entity = elgg_extract('entity', $params, false);
	if (!elgg_instanceof($entity)) {		return $return;	}
	$items = array();
	switch ($entity->getSubtype()) {
		default :			return $return;		case hjAlbum::SUBTYPE :			// Add images			if ($entity->canWriteToContainer(0, 'object', hjAlbumImage::SUBTYPE)) {				$items['upload'] = array(					'text' => elgg_echo('gallery:upload'),					'title' => elgg_echo('gallery:upload'),					'href' => "gallery/upload/$entity->guid",					'link_class' => 'elgg-button-edit-entity',					'data-guid' => $entity->guid,					'priority' => 400				);			}
			// Manage album			if ($entity->canWriteToContainer(0, 'object', hjAlbumImage::SUBTYPE)) {				$items['manage'] = array(					'text' => elgg_echo('gallery:manage:album'),					'title' => elgg_echo('gallery:manage:album'),					'href' => "gallery/manage/$entity->guid",					'priority' => 400				);			}
			// Edit and/or Delete			if ($entity->canEdit()) {				$items['edit'] = array(					'text' => elgg_view_icon('docedit'),					'title' => elgg_echo('edit'),					'href' => $entity->getURL('edit'),					'link_class' => 'elgg-button-edit-entity',					'data-guid' => $entity->guid,					'priority' => 995				);				$items['delete'] = array(					'text' => elgg_view_icon('delete'),					'title' => elgg_echo('delete'),					'href' => $entity->getURL('delete'),					'link_class' => 'elgg-button-delete-entity',					'data-guid' => $entity->guid,					'priority' => 1000				);			}
			break;		case hjAlbumImage::SUBTYPE :			if (elgg_in_context('gallery-manage')) {				$return = array();				// Use this image as avatar				$items['makeavatar'] = (HYPEGALLERY_AVATARS && elgg_is_logged_in()) ? array(					'text' => elgg_echo('gallery:image:makeavatar'),					'title' => elgg_echo('gallery:image:makeavatar'),					'href' => "action/gallery/makeavatar?e=$entity->guid",					'is_action' => true,					'priority' => 100,						) : NULL;			} else {				if (elgg_is_logged_in()) {					// Download if allowed					$items['download'] = (HYPEGALLERY_DOWNLOADS && (elgg_is_logged_in() || HYPEGALLERY_PUBLIC_DOWNLOADS)) ? array(						'text' => elgg_echo('gallery:image:download'),						'title' => elgg_echo('gallery:image:download'),						'href' => $entity->getURL('download'),						'priority' => 150,						) : NULL;
					// Edit					$items['edit'] = array(						'text' => elgg_view_icon('docedit'),						'title' => elgg_echo('edit'),						'href' => $entity->getURL('edit'),						//'link_class' => 'elgg-button-edit-entity',						'data-guid' => $entity->guid,						'priority' => 995					);
					// Delete					$items['delete'] = array(						'text' => elgg_view_icon('delete'),						'title' => elgg_echo('delete'),						'href' => $entity->getURL('delete'),						//'link_class' => 'elgg-button-gallery-delete',						'data-guid' => $entity->guid,						'priority' => 1000					);				}			}			break;	}

	if ($items) {		foreach ($items as $name => $item) {			foreach ($return as $key => $val) {				if (!$val instanceof ElggMenuItem) {					unset($return[$key]);				}				if ($val instanceof ElggMenuItem && $val->getName() == $name) {					unset($return[$key]);				}			}			$item['name'] = $name;			$return[$name] = ElggMenuItem::factory($item);		}	}	return array_filter($return);}
/** * Album/image manage menu items * * @param string $hook   Equals 'register' * @param string $type   Equals 'menu:album:manage' * @param array  $return Current menu * @param array  $params Additional params * @return array Filtered menu*/
function manage_album_image_menu_setup($hook, $type, $return, $params) {	$entity = elgg_extract('entity', $params, false);	if (!elgg_instanceof($entity, 'object', hjAlbumImage::SUBTYPE)) {		return $return;	}	$items = array();
	// Is item is pending approval?	if (!$entity->isEnabled() && $entity->disable_reason == 'pending_approval' && $entity->getContainerEntity()->canEdit()) {
		// Approve		$items['approve'] = array(			'text' => '<i class="gallery-icon-approve"></i><span>' . elgg_echo('gallery:approve') . '</span>',			'title' => elgg_echo('gallery:approve'),			'href' => "action/gallery/approve/image?guid=$entity->guid",			'is_action' => true,			'link_class' => 'elgg-button-gallery-approve',			'data-guid' => $entity->guid,			'priority' => 990		);
		// Delete		$items['delete'] = array(			'text' => '<i class="gallery-icon-delete"></i><span>' . elgg_echo('delete') . '</span>',			'title' => elgg_echo('delete'),			'href' => $entity->getURL('delete'),			'link_class' => 'elgg-button-gallery-delete',			'data-guid' => $entity->guid,			'priority' => 1000		);	}
	if ($entity->canEdit()) {		// Edit		//$items['edit'] = array(		//	'text' => '<i class="gallery-icon-edit"></i><span>' . elgg_echo('edit') . '</span>',		//	'title' => elgg_echo('edit'),		//	'href' => $entity->getURL('edit'),		//	'link_class' => 'elgg-button-edit-entity',		//	'data-guid' => $entity->guid,		//	'priority' => 995		//);
		// Delete		$items['delete'] = array(			'text' => '<i class="gallery-icon-delete"></i><span>' . elgg_echo('delete') . '</span>',			'title' => elgg_echo('delete'),			'href' => $entity->getURL('delete'),			'link_class' => 'elgg-button-gallery-delete',			'data-guid' => $entity->guid,			'priority' => 1000		);
		// Crop		$items['cropper'] = array(			'text' => '<i class="gallery-icon-cropper"></i><span>' . elgg_echo('gallery:image:cropper') . '</span>',			'title' => elgg_echo('gallery:image:cropper'),			'href' => "gallery/thumb/$entity->guid",			'data-guid' => $entity->guid,			'link_class' => 'elgg-button-gallery-cropper',			'priority' => 990,		);	}
	$container = $entity->getContainerEntity();	if ($container && $container->canEdit()) {
		// Reorder drag handle		$items['drag'] = array(			'text' => '<i class="gallery-icon-drag"></i><span>' . elgg_echo('gallery:image:reorder') . '</span>',			'title' => elgg_echo('gallery:image:reorder'),			'href' => "#elgg-object-$entity->guid",			'link_class' => 'elgg-button-gallery-drag',			'priority' => 10,			'section' => 'drag'		);
		// Reorder input		$items['position'] = array(			'text' => elgg_view('input/text', array(				'name' => "files[$entity->guid][priority]",				'value' => $entity->priority			)),			'title' => elgg_echo('gallery:image:priority'),			'href' => false,			'link_class' => '',			'priority' => 20,			'section' => 'drag'		);
		// Make is image an album cover		$items['makecover'] = array(			'text' => '<i class="gallery-icon-makecover"></i><span>' . elgg_echo('gallery:image:makecover') . '</span>',			'title' => elgg_echo('gallery:image:makecover'),			'href' => "action/gallery/makecover?e=$entity->guid",			'is_action' => true,			'link_class' => 'elgg-button-gallery-makecover',			'item_class' => ($entity->getContainerEntity()->cover == $entity->guid) ? 'hidden' : '',			'data-guid' => $entity->guid,			'priority' => 980,		);	}
	if ($items) {		foreach ($items as $name => $item) {			foreach ($return as $key => $val) {				if (!$val instanceof ElggMenuItem) {					unset($return[$key]);				}				if ($val instanceof ElggMenuItem && $val->getName() == $name) {					unset($return[$key]);				}			}			$item['name'] = $name;			$return[$name] = ElggMenuItem::factory($item);		}	}	return array_filter($return);}
/** * Add gallery related items to owner block menu * * @param string $hook   Equals 'register' * @param string $type   Equals 'menu:owner_block' * @param array  $return Current menu items * @param array  $params Additional params * @return array Updated menu */
function owner_block_menu_setup($hook, $type, $return, $params) {	$entity = elgg_extract('entity', $params);	if (HYPEGALLERY_GROUP_ALBUMS && elgg_instanceof($entity, 'group') && $entity->albums_enable !== 'no') {		$return[] = ElggMenuItem::factory(array(					'name' => 'group:albums',					'text' => elgg_echo('gallery:albums:groups'),					'href' => "gallery/group/$entity->guid"		));	} else if (elgg_instanceof($entity, 'user')) {		$return[] = ElggMenuItem::factory(array(					'name' => 'user:albums',					'text' => elgg_echo('gallery:albums'),					'href' => "gallery/dashboard/owner/$entity->username"		));	}	return $return;}
/** * Icon size config * * @param string $hook   Equals 'entity:icon:sizes' * @param string $type   Equals 'object' * @param array  $return Current config * @param array  $params Additional params * @return array Updated config */
function entity_icon_sizes($hook, $type, $return, $params) {	$entity = elgg_extract('entity', $params);	if (!$entity instanceof hjAlbumImage) {		return;	}	$gallery_config = elgg_get_config('gallery_icon_sizes');	return (is_array($return)) ? array_merge($return, $gallery_config) : $gallery_config;}
