<!-- Path: mod/quebx/views/default/resources/space.php -->
<?php

// Ensure that only logged-in users can see this page
elgg_gatekeeper();

// Set context and title
elgg_set_context('dashboard');
elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
$title            = elgg_echo('space');
$page_owner       = elgg_get_page_owner_entity();
$show_add_pallets = false;
$exact_match      = false;
$num_pallets      = 50;
$num_show         = 0; // 0 = all
$show_access      = false;
$aid              = quebx_new_id('a'); // area id

$context = elgg_get_context();

// wrap intro message in a div
$intro_message = elgg_view('dashboard/blurb');
$space_id = elgg_get_logged_in_user_guid();
$dbprefix = elgg_get_config('dbprefix');
$things_options = ['type'=>'object',
                   'subtype'=>'market', 
                   'owner_guid' => $owner_guid,
                   'wheres'    => ["NOT EXISTS (SELECT *
                                                FROM {$dbprefix}metadata md
                    	                        JOIN {$dbprefix}metastrings ms1 ON ms1.id = md.name_id
                    	                        JOIN {$dbprefix}metastrings ms2 ON ms2.id = md.value_id
                    	                        WHERE ms1.string = 'visibility_choices'
                    	                          AND ms2.string = 'hide_in_catalog'
                    	                          AND e.guid = md.entity_guid)"], 
                   'limit'=>0];
$things_count = count(elgg_get_entities($things_options));
$shelf_items_count = shelf_count_items ();

$options = ['type'       => 'object',
            'owner_guid' => $owner_guid, 
            'order_by_metadata' => ['name'=>'moment',
            		                'direction'=>DESC,
                                    'as'=>date],
            'order_by'=>'time_updated',
            'limit'      => 0,];
$jot_options = $options;
$jot_options['subtype'] = 'experience';
unset($jot_options['order_by_metadata']);
$experiences_count = count(elgg_get_entities_from_metadata($jot_options));
$jot_options = $options;
$jot_options['subtype'] = 'issue';
unset($jot_options['order_by_metadata']);
$issues_count = count(elgg_get_entities_from_metadata($jot_options));
$jot_options = $options;
$jot_options['subtype'] = 'transfer';
$transfers_count = count(elgg_get_entities_from_metadata($jot_options));

$panel_list_items[]=['title'=>'Things'     , 'class'=>'things'                 , 'name'=>'things'     , 'handler'=>'market'      , 'count'=>$things_count     , 'id'=>'market_'.$space_id       , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Community'  , 'class'=>'community'              , 'name'=>'community'  , 'handler'=>'community'   , 'count'=>$community_count  , 'id'=>'community_'.$space_id    , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Shelf'      , 'class'=>'shelf'                  , 'name'=>'shelf'      , 'handler'=>'shelf'       , 'count'=>$shelf_items_count, 'id'=>'shelf_'.$space_id        , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Boqxes'     , 'class'=>'boqxes'                 , 'name'=>'boqxes'     , 'handler'=>'boqx'                                     , 'id'=>'boqx_'.$space_id         , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Transfers'  , 'class'=>'backlog transfers'      , 'name'=>'transfers'  , 'handler'=>'transfer'    , 'count'=>$transfers_count  , 'id'=>'transfer_'.$space_id     , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Activity'   , 'class'=>'icebox activity'        , 'name'=>'activity'   , 'handler'=>'river_widget'                             , 'id'=>'river_widget_'.$space_id , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Experiences', 'class'=>'experiences'            , 'name'=>'experiences', 'handler'=>'experience'  , 'count'=>$experiences_count, 'id'=>'experience_'.$space_id   , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Done'       , 'class'=>'done'                   , 'name'=>'done'       , 'handler'=>'done'                                     , 'id'=>'done_'.$space_id         , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Issues'     , 'class'=>'blockers issues'        , 'name'=>'issues'     , 'handler'=>'issue'       , 'count'=>$issues_count     , 'id'=>'issue_'.$space_id        , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Collections', 'class'=>'epics collections'      , 'name'=>'collections', 'handler'=>'collection'                               , 'id'=>'collection_'.$space_id   , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'History'    , 'class'=>'project_history history', 'name'=>'history'    , 'handler'=>'history'                                  , 'id'=>'history_'.$space_id      , 'cid'=>quebx_new_id('c')];
$panel_list_items[]=['title'=>'Files'      , 'class'=>'filerepo'               , 'name'=>'files'      , 'handler'=>'filerepo'                                 , 'id'=>'files_'.$space_id        , 'cid'=>quebx_new_id('c')];

// Add unavailable pallets to the owner's que 
//$pallets = elgg_get_entities_from_private_settings([
$pallets = elgg_get_entities([
                'type' => 'object',
                'subtype' => 'widget',
                'owner_guid' => $page_owner->getGUID(),
                'private_setting_name' => 'context',
                'private_setting_value' => $context,
                'limit' => 0,]);
foreach($pallets as $pallet){
        $handlers[] = $pallet->handler;                                                                                  //$display .= print_r($widgets, true);$display .= print_r($handlers, true);$display .= print_r($active_handlers, true);register_error($display);
}
foreach($panel_list_items as $list_item){
    if(!in_array($list_item['handler'], $handlers)){
        $guid = elgg_create_widget($page_owner->getGUID(), $list_item['handler'], $context);
        if ($guid) {
            $pallet = get_entity($guid);
            $pallet->title = $list_item['title'];
            $pallet->column = 0;}}}    
$options = array(
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => $page_owner->getGUID(),
			'private_setting_name' => 'context',
			'private_setting_value' => $context,
			'limit' => 0,
		);
$widgets = elgg_get_entities_from_private_settings($options);
// realign active widgets into slots
$slot_contents=array();
for ($slot_index = 1; $slot_index <= $num_pallets; $slot_index++) {
    unset($this_slot);
    foreach($widgets as $key=>$widget){
        if($widget->slot == $slot_index) $this_slot[] = $widget;
    }
    if(count($this_slot)>0) $slot_contents = array_merge($slot_contents, $this_slot);
}
$slots = 0;
foreach($slot_contents as $key=>$widget){
    $widget->slot = ++$slots;                                // Set & save the slot attribute for the widget object
    foreach($panel_list_items as $key1=>$panel_item){
        if ($panel_item['handler'] == $widget->handler) {
            $panel_toggle = $panel_item;
            $panel_list_items[$key1]['visible'] = true;
            $contents_count = $panel_list_items[$key1]['count'];
        }
    }
	$cid                  = $panel_toggle['cid'];
    $container_contents[$slots] = elgg_view_entity($widget, ['show_access'    => $show_access,
	                                                 'class'          => ['boqx', 'container', 'droppable', 'tn-panelWrapper___fTILOVmk','q-module','q-module-widget'], 
	                                                 'module_type'    => 'warehouse',
                                                     'widget_id'      => $widget->handler,
                                                     'contents_count' => $contents_count,
	                                                 'parent_cid'     => $cid,
                                                     'this_slot'      => $widget->slot]);
}
if ($container_contents)
    $space_content = implode($container_contents,'');


$required_size = ($slots * 400) + 100;
$minimum_size = 1900;
$floor_size   = $required_size < $minimum_size ? $minimum_size : $required_size;

foreach($panel_list_items as $panel_item){
    unset($attributes, $text);
    $cid = elgg_extract('cid', $panel_item, quebx_new_id('c'));
    $panel_visibility = $panel_item['visible'] ? ' visible' : ' available';
    foreach($panel_item as $aspect=>$value){
        if ($aspect == 'title') continue;
        else $attributes[$aspect]=$value;}
    $attributes['class'] = 'item '.$panel_item['class'].$panel_visibility;
    $attributes['data-boqx'] = $aid;
    $text = elgg_format_element('button', 
                               ['class'              => 'pallet_toggle',
                                'data-pallet-visible'=> $panel_item['visible'],
                                'data-boqx'          => $panel_item['handler'],      
                                'data-cid'           => $cid
                               ],
                               elgg_format_element('span', ['class'=>'pallet_name'],$panel_item['name']));
    $panel_items[]=['attributes'=>$attributes,'text'=>$text];
}

if (elgg_is_logged_in()) {
    $sidebar_vars = $vars;
    $sidebar_vars['panel_items'] = $panel_items;
    $sidebar_vars['aspect']      = 'qboqx';
    $sidebar_vars['aid']         = $aid;
	$space_navigator = elgg_view('page/elements/space_navigator', $sidebar_vars);
}


$params = [
//	'content' => $intro_message,
    'floor_size'      => $floor_size, 
    'space_navigator' => $space_navigator,
    'space_content'   => $space_content
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