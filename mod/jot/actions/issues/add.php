<?php

// Make sure we're logged in
gatekeeper();

// Get input data
/*$variables = elgg_get_config('issues');
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
*/
// Get guids
$issue_guid = (int)get_input('issue_guid');
$container_guid = (int)get_input('container_guid');
$item_guid = (int)get_input('item_guid');

elgg_make_sticky_form('issue');
/*
$input['issue_guid'] = (int)get_input('issue_guid');
$input['container_guid'] = (int)get_input('container_guid');
$input['item_guid'] = get_input('item_guid');
$input['type'] = get_input('type');
$input['subtype'] = get_input('subtype');
$input['referrer'] = get_input('referrer');*/

$type = get_input('type');
$subtype = get_input('subtype');
$referrer = get_input('referrer');
$title = get_input('title');
$description = get_input('description');
$tags = get_input('tags');
$start_date = get_input('start_date');
$end_date = get_input('end_date');
$issue_type = get_input('issue_type');
$number = get_input('issue_number');
$status = get_input('status');
$assigned_to = get_input('assigned_to');
$aspect = get_input('aspect');
$referrer = get_input('referrer');
$access_id = get_input('access_id');
$write_access_id = get_input('write_access_id');
$container_guid = get_input('container_guid');
$guid = get_input('guid');
$entity = get_input('entity');

$item = get_entity($item_guid);
//$item = get_entity($input->item_guid);
$user = elgg_get_logged_in_user_entity();
$relationship = $subtype;
//$relationship = $input->subtype;

// Convert string of tags into a preformatted array
	$tagarray = string_to_tag_array($tags);
	
// Make sure the title / description aren't blank
if (empty($title) || empty($description)) {
/*
if ($issue_guid) {
	$issue = get_entity($issue_guid);
	if (!$issue || !$issue->canEdit()) {
		register_error(elgg_echo('jot:error:no_save'));
		forward(REFERER);
	}
	$new_issue = false;
} else {
	$issue = new ElggObject();
	$issue->subtype = 'issue';
	$new_issue = true;
}
	
if (!$title || !$description) {
*/
	register_error(elgg_echo("jot:blank"));
	forward(REFERER);

} else {
    	
	// save the issue
	
/*	if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$issue->$name = $value;
		//echo "$task->$name = $value";
	}
}
*/
//    $issue = issues_save_issue($title, $description, $status, $number, $asset, $tags, $access);
	// Initialise a new ElggObject
    $issue = new ElggObject();
	$new_issue = true;

	// Tell the system it's an issue
	$issue->subtype = $subtype;

	// Set its owner to the current user
	$issue->owner_guid = elgg_get_logged_in_user_guid();

	// Set its access_id
	$issue->access_id = $access;

	// Set its title and description
	$issue->title = $title;
	$issue->description = $description;
	$issue->type = $type;
	$issue->status = $status;
	$issue->issue_number = $number;
    $issue->asset = $item_guid;

	if (is_array($tagarray)) {
	  $issue->tags = $tagarray;
	}
	
	if (!$issue->save()) {
		register_error(elgg_echo("jot:error:no_save"));
		forward(REFERRER);
//		forward("mod/jot/add/issue");
	}

    elgg_clear_sticky_form('issue');

	// Now save description as an annotation
	$issue->annotate('issue', $description, $access);

		// Success message
	system_message(elgg_echo("jot:posted"));

	// Now see if we have a file icon
	if ((isset($_FILES['upload'])) && (substr_count($_FILES['upload']['type'],'image/'))) {

	  $prefix = "jot/".$issue->guid;
	  $filehandler = new ElggFile();
	  $filehandler->owner_guid = $issue->owner_guid;
	  $filehandler->setFilename($prefix . ".jpg");
	  $filehandler->open("write");
	  $filehandler->write(get_uploaded_file('upload'));
	  $filehandler->close();

	  $thumbtiny = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),25,25, true);
	  $thumbsmall = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),40,40, true);
	  $thumbmedium = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),153,153, true);
	  $thumblarge = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(),200,200, false);
	  if ($thumbtiny) {

	    $thumb = new ElggFile();
	    $thumb->owner_guid = $issue->owner_guid;
	    $thumb->setMimeType('image/jpeg');
	    $thumb->setFilename($prefix."tiny.jpg");
	    $thumb->open("write");
	    $thumb->write($thumbtiny);
	    $thumb->close();
	    $thumb->setFilename($prefix."small.jpg");
	    $thumb->open("write");
	    $thumb->write($thumbsmall);
	    $thumb->close();
	    $thumb->setFilename($prefix."medium.jpg");
	    $thumb->open("write");
	    $thumb->write($thumbmedium);
	    $thumb->close();
	    $thumb->setFilename($prefix."large.jpg");
	    $thumb->open("write");
	    $thumb->write($thumblarge);
	    $thumb->close();
	  }
	}

	// Extract the new issue into an array
	$element = get_entity($issue->guid);
	register_error(elgg_echo(elgg_dump($element)));
	
	// Create a relationship to the item, provided one exists
	if ($relationship) {
	// Create a relationship
	add_entity_relationship($element->guid, $relationship, $item_guid);
	}

	// Add to river
	
	elgg_create_river_item([
			'view'=>'river/object/jot/create',
			'action_type'=> 'create',
			'subject_guid' => $element->owner_guid,
			'object_guid' => $element->guid]);

    // Forward to the originating item
	forward($referrer);
}
