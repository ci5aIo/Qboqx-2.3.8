<?php

// Ensure that only logged-in users can see this page
elgg_gatekeeper();

// Set context and title
elgg_set_context('dashboard');
elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
$title = elgg_echo('space');

// wrap intro message in a div
$intro_message = elgg_view('dashboard/blurb');

$params = [
//	'content' => $intro_message,
	'num_pallets' => 5,
	'show_access' => false,
    'show_add_pallets' => false
];
$space = elgg_view_layout('space', $params);
/*
$body = elgg_view_layout('one_column', array(
	'title' => false,
	'content' => $warehouse
));*/
$body = $space;
$page_shell = 'qboqx';
echo elgg_view_page($title, $body, $page_shell, $vars);