<?php

// Ensure that only logged-in users can see this page
elgg_gatekeeper();

// Set context and title
elgg_set_context('dashboard');
elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
$title = elgg_echo('warehouse');

// wrap intro message in a div
$intro_message = elgg_view('dashboard/blurb');

$params = [
//	'content' => $intro_message,
	'num_columns' => 5,
	'show_access' => false,
    'show_add_pallets' => false
];
$warehouse = elgg_view_layout('warehouse', $params);
/*
$body = elgg_view_layout('one_column', array(
	'title' => false,
	'content' => $warehouse
));*/
$body = $warehouse;
$page_shell = 'boqx';
$vars['panel_items']=$panel_items;
echo elgg_view_page($title, $body, $page_shell, $vars);