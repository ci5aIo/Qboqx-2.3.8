<?php
/**
* Quebx object disable
* 
* @package ElggFile
*/

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (!$entity instanceof ElggObject) {
    $message = 'Move to trash not permitted';
	register_error($message);	
}
/* @var ElggFile $entity */

if (!$entity->canEdit()) {
    $message = 'Move to trash not permitted';
	register_error($message);
}

//$container = $entity->getContainerEntity();

if (!$entity->disable()) {
    $message = 'Move to trash failed';
	register_error($message);
} else {
    $message = 'Moved to trash';
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