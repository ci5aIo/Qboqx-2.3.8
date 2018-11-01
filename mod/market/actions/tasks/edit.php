<?php
/**
 * Create or edit a task
 *
 * @package ElggPages
 */

$variables = elgg_get_config('tasks');
$input = array();
foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

// Get guids
$task_guid = (int)get_input('task_guid');
$container_guid = (int)get_input('container_guid');
$parent_guid = (int)get_input('parent_guid');	
// 10/31/2014 - added by scottj
	
	// Receive input from the Target form
	$element_type = get_input('element_type');
	$container = (int)get_input('container', 0);
	$relationship = $element_type;
//

elgg_make_sticky_form('task');

if (!$input['title']) {
	register_error(elgg_echo('tasks:error:no_title'));
	forward(REFERER);
}

if ($task_guid) {
	$task = get_entity($task_guid);
	if (!$task || !$task->canEdit()) {
		register_error(elgg_echo('tasks:error:no_save'));
		forward(REFERER);
	}
	$new_task = false;
} else {
	$task = new ElggObject();
	if ($parent_guid) {
		$task->subtype = 'task';
	} else {
		$task->subtype = 'task_top';
	}
	$new_task = true;
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$task->$name = $value;
		//echo "$task->$name = $value";
	}
}

// need to add check to make sure user can write to container
$task->container_guid = $container_guid;

if ($parent_guid) {
	$task->parent_guid = $parent_guid;
}

if ($task->save()) { 

	elgg_clear_sticky_form('task');

	// Now save description as an annotation
	$task->annotate('task', $task->description, $task->access_id);

	system_message(elgg_echo('tasks:saved'));
	
	if ($new_task) {
		
		elgg_create_river_item([
				'view'=>'river/object/jot/create',
				'action_type'=> 'create',
				'subject_guid' => elgg_get_logged_in_user_guid(),
				'object_guid' => $task->guid]);
	}

// 10/31/2014 - added by scottj

	// Extract the object into an array
	$element = get_entity($task->guid);
	
	// Create a relationship to the container, provided one is received
	if ($relationship) {
//	add_entity_relationship($element->guid, $relationship, $container_guid);
	add_entity_relationship($element->guid, $relationship, $container);
	}
//

	forward($task->getURL());
} else {
	register_error(elgg_echo('tasks:error:no_save'));
	forward(REFERER);
}