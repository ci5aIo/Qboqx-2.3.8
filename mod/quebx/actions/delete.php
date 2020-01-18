<?php
/**
* Quebx object delete
* 
* @package ElggFile
*/

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!$entity instanceof ElggObject) {
	register_error('Delete failed');
}
/* @var ElggFile $entity */

if (!$entity->canEdit()) {
	register_error('Delete failed');
}

//$container = $entity->getContainerEntity();

if (!$entity->delete()) {
	register_error('Delete failed');
} else {
	system_message('Delete Succeeded');
}
/*
if (elgg_instanceof($container, 'group')) {
	forward("file/group/$container->guid/all");
} else {
	forward("file/owner/$container->username");
}
*/