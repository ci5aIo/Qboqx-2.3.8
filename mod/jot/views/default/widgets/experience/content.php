<?php
/**
 * 
 */
setlocale(LC_MONETARY, 'en_US');
$wwwroot = elgg_get_config('wwwroot');
//the page owner
$owner = get_user($vars['entity']->owner_guid);

//the number of experiences to display
$num = (int) $vars['entity']->num_display;
if (!$num) {
	$num = 0;
}		
elgg_load_library('elgg:market');
$dbprefix = elgg_get_config('dbprefix');
$jot_options = [
	'type' => 'object',
    'subtype'=>'experience', 
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

echo $list_items;