<?php
/** * Item sections pages menu * * @uses $vars['section'] */$selected  = $vars['this_section'];$title     = $vars['title'];$this_guid = $vars['guid'];$task      = get_entity($this_guid);$user_guid = elgg_get_logged_in_user_guid(); if (empty($selected)) {	 $selected = 'Summary';}if (empty($title)) {	 $title = $task->title;}if ($user_guid == $task->owner_guid){$manage = true;}//set the url$url = $task->getURL();

$sections = array();if ($manage){    $sections[] = 'Profile';    $sections[] = 'Summary';    $sections[] = 'Details';    $sections[] = 'Maintenance';    $sections[] = 'Inventory';    $sections[] = 'Management';    $sections[] = 'Accounting';    $sections[] = 'Gallery';//    $sections[] = 'Reports';    $sections[] = 'Timeline';}else {    //$sections[] = 'Profile';}$tabs = array();foreach ($sections as $section) {	$tabs[] = array(		'title' => elgg_echo("$section"),	    'url'   => "$url/$section",		'selected' => $section == $selected,	);}
echo elgg_view('navigation/tabs', array('tabs' => $tabs));