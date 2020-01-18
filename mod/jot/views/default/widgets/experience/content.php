<?php
/**
 * 
 */
setlocale(LC_MONETARY, 'en_US');
$wwwroot = elgg_get_config('wwwroot');
//the page owner
$owner = get_user($vars['entity']->owner_guid);
$class = elgg_extract('class', $vars);
$context = elgg_get_context();
$module_type = elgg_extract('module_type', $vars);


//the number of experiences to display
$num = (int) $vars['entity']->num_display;
if (!isset($num)) {
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
unset($jot_options['order_by_metadata']);
//$jots = elgg_get_entities_from_metadata($jot_options);
$jots = elgg_get_entities($jot_options);
Switch ($module_type){
    case 'warehouse':
        if (is_array($jots) && sizeof($jots) > 0) {
       		foreach($jots as $jot) {
                echo elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'experience', 'boqx_id'=>$vars['boqx_id']]);
       		   }
        }}