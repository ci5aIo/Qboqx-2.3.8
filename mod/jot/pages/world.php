<?php
/**
 * List all places
 *
 * @package Elggplaces
 */

$title = elgg_echo('jots:all');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('places'));

elgg_register_title_button();

elgg_load_library('elgg:market');
/************/
	$dbprefix = elgg_get_config('dbprefix');
	$jot_options = [
		'type' => 'object',
    	'subtypes' => ['jot', 'experience', 'observation'],
		'owner_guid' => $owner->guid, 
		'order_by_metadata' => ['name'=>'moment',
				                'direction'=>DESC,
	                            'as'=>date],
		'order_by'=>'time_updated',
		'limit' => $num,];
	$jots = elgg_get_entities_from_metadata($jot_options);
	foreach ($jots as $key=>$jot){
		if ($jot->aspect == 'receive'){
			unset ($jots[$key]);
		}
	}
	
	$vars['list_type_toggle'] = true;
	$vars['view_type']        = 'compact';
	$vars['perspective']      = 'list';
	$list_header = elgg_view('jot/display/transfer/ledger_header');
	$list_items  = elgg_view_entity_list($jots, $vars);
	$list_items  = $list_header.elgg_view('output/div',['content'=>$list_items, 'options'=>['style'=>'max-height:800px;overflow:auto;']]);
	$list_items .="<div class=\"contentWrapper\"><a href=\"" . $wwwroot . "jot/home/" . $owner->username . "\">" . elgg_echo("jot:widget:viewall") . "</a></div>";
/***************/

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtypes' => ['jot', 'experience', 'observation'],
	'full_view' => false,
	'no_results' => elgg_echo('places:none'),
));

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $list_items,
	'title' => $title,
	'sidebar' => elgg_view('places/sidebar'),
));

echo elgg_view_page($title, $body);
