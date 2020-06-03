<?php
/**
* Quebx object delete
* 
* @package ElggFile
*/

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!$entity instanceof ElggObject) {
    $message = 'Delete failed';
	register_error($message);	
}
/* @var ElggFile $entity */

if (!$entity->canEdit()) {
    $message = 'Delete failed';
	register_error($message);
}

//$container = $entity->getContainerEntity();

if (!$entity->delete()) {
    $message = 'Delete failed';
	register_error($message);
} else {
    $message = 'Delete Succeeded';
	system_message($message);
}
return $message;
/*
if (elgg_instanceof($container, 'group')) {
	forward("file/group/$container->guid/all");
} else {
	forward("file/owner/$container->username");
}
*/