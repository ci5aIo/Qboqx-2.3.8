<?php
use Elgg\Menu\Menu;

/**
 * Qboqx warehouse layout
 * 2019-06-20 - SAJ - Forked from Elgg widgets layout
 *
 * @uses $vars['content']          Optional display box at the top of layout
 * @uses $vars['num_columns']      Number of widget columns for this layout (3)
 * @uses $vars['show_add_pallets'] Display the add pallets button and panel (true)
 * @uses $vars['exact_match']      Widgets must match the current context (false)
 * @uses $vars['show_access']      Show the access control (true)
 * @uses $vars['owner_guid']       Widget owner GUID (optional, defaults to page owner GUID)
 */
//elgg_set_context('warehouse');
$num_columns = elgg_extract('num_columns', $vars, 3);
$show_add_pallets = elgg_extract('show_add_pallets', $vars, true);
$exact_match = elgg_extract('exact_match', $vars, false);
$show_access = elgg_extract('show_access', $vars, true);
$owner_guid = elgg_extract('owner_guid', $vars);
$aspect = elgg_extract('aspect', $vars);

$space_id = elgg_get_logged_in_user_guid();
$panel_items[]=['title'=>'My things'  , 'class'=>'my_things'              , 'name'=>'my things'  , 'handler'=>'market'      , 'visible'=>'true', 'count'=>$things_count, 'id'=>'market'.'_'.$space_id       , 'cid'=>quebx_new_id('c')];
$panel_items[]=['title'=>'Transfers'  , 'class'=>'backlog transfers'      , 'name'=>'transfers'  , 'handler'=>'transfer'    , 'visible'=>'true'                        , 'id'=>'transfer'.'_'.$space_id     , 'cid'=>quebx_new_id('c')];
$panel_items[]=['title'=>'Activity'   , 'class'=>'icebox activity'        , 'name'=>'activity'   , 'handler'=>'river_widget', 'visible'=>'true'                        , 'id'=>'river_widget'.'_'.$space_id , 'cid'=>quebx_new_id('c')];
$panel_items[]=['title'=>'Done'       , 'class'=>'done'                   , 'name'=>'done'       , 'handler'=>'done'        , 'visible'=>'false'                       , 'id'=>'done'.'_'.$space_id         , 'cid'=>quebx_new_id('c')];
$panel_items[]=['title'=>'Issues'     , 'class'=>'blockers issues'        , 'name'=>'issues'     , 'handler'=>'issue'       , 'visible'=>'false', 'count'=>1           , 'id'=>'issue'.'_'.$space_id        , 'cid'=>quebx_new_id('c')];
$panel_items[]=['title'=>'Collections', 'class'=>'epics collections'      , 'name'=>'collections', 'handler'=>'collection'  , 'visible'=>'false'                       , 'id'=>'collection'.'_'.$space_id   , 'cid'=>quebx_new_id('c')];
$panel_items[]=['title'=>'Labels'     , 'class'=>'labels'                 , 'name'=>'labels'     , 'handler'=>'labelcloud'  , 'visible'=>'true'                        , 'id'=>'label'.'_'.$space_id        , 'cid'=>quebx_new_id('c')];
$panel_items[]=['title'=>'history'    , 'class'=>'project_history history', 'name'=>'history'    , 'handler'=>'history'     , 'visible'=>'false'                       , 'id'=>'history'.'_'.$space_id      , 'cid'=>quebx_new_id('c')];

$page_owner = elgg_get_page_owner_entity();
if ($owner_guid) {
	$owner = get_entity($owner_guid);
} else {
	$owner = $page_owner;
}

if (!$owner) {
	elgg_log('Can not resolve owner entity for widget layout', 'ERROR');
	return;
}

// Underlying views and functions assume that the page owner is the owner of the widgets
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($owner->guid);
}

$context = elgg_get_context();
echo "<!--context: $context -->";

$widget_types = elgg_get_widget_types([
	'context' => $context,
	'exact' => $exact_match,
	'container' => $owner,
]);

//elgg_push_context($context);
elgg_push_context('warehouse');

$widgets = elgg_get_widgets($owner->guid, $context);
//elgg_dump($widgets);
if (elgg_can_edit_widget_layout($context)) {
	if ($show_add_pallets) {
		$content .= elgg_view('page/layouts/widgets/add_button');
	}
	$params = array(
		'widgets' => $widgets,
		'context' => $context,
		'exact_match' => $exact_match,
		'show_access' => $show_access,
	);
	$content .=  elgg_view('page/layouts/widgets/add_panel', $params);
}

if (isset($vars['content'])) {
	$content .=  $vars['content'];
}
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
    unset($container_content, $cid);
    $cid = quebx_new_id('c');
	if (isset($widgets[$column_index])) {
//        elgg_dump($widgets[$column_index]);        	    
		$column_widgets = $widgets[$column_index];
	} else {
		$column_widgets = array();
	}

	if (sizeof($column_widgets) > 0) {
		unset($panel_id);
		foreach ($column_widgets as $widget) {
			    unset($panel_id);
			    $panel_id = 'panel_'.$widget->handler.'_'.$space_id;
//			if (array_key_exists($widget->handler, $widget_types)) {
			    $container_content .= elgg_view_entity($widget, ['show_access' => $show_access, 
				                                                 'class'       =>'container droppable tn-panelWrapper___fTILOVmk', 
				                                                 'module_type' =>'warehouse',
			                                                     'widget_id'   => $panel_id,
				                                                 'cid'         => $cid]);
//			}
		}
	}
	$panel_content .= elgg_format_element('div', ['class'=>"elgg-widgets panel items_draggable visible",
        	                                      'id'   => $panel_id,
        	                                      'cid'  => $cid,
        	                                      'style'=>'width:375px;']
        	                                    , $container_content);
}
/****Experimental****/
/*unset($panel_content, $column_widgets);
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
    unset($panels, $container_content, $cid);
    $cid = quebx_new_id('c');
	if (isset($widgets[$column_index])) {
//        elgg_dump($widgets[$column_index]);        	    
		$column_widgets = $widgets[$column_index];
	} else {
		$column_widgets = array();
	}

	if (sizeof($column_widgets) > 0) {
		foreach ($column_widgets as $widget) {
			    unset($panel_id);
			    $panel_id = 'panel_'.$widget->handler.'_'.$space_id;
//			if (array_key_exists($widget->handler, $widget_types)) {
			    ;
//			}
	$panels .= elgg_format_element('div', ['class'=>"elgg-widgets panel items_draggable visible",
        	                                      'id'   => $panel_id,
        	                                      'cid'  => $cid,
        	                                      'style'=>'width:375px;']
        	                                    , $container_content .= elgg_view_entity($widget, ['show_access' => $show_access, 
				                                                 'class'       =>'container droppable tn-panelWrapper___fTILOVmk', 
				                                                 'module_type' =>'warehouse',
			                                                     'widget_id'   => $panel_id,
				                                                 'cid'         => $cid]));
				}
	}
}
$panel_content .= $panels;
*/
/*for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	if (isset($widgets[$column_index])) {
//        elgg_dump($widgets[$column_index]);        	    
		$column_widgets[] = $widgets[$column_index];
	}
}
//unset($panel_content);
//elgg_dump($column_widgets);
foreach($column_widgets as $widget){
    $cid = quebx_new_id('c');
//    elgg_dump($widget);
    $panel_content .= elgg_format_element('div', ['class'=>"elgg-widgets panel items_draggable visible",
        	                                      'id'   => 'panel_'.$widget->handler.'_'.$space_id,
        	                                      'cid'  => $cid,
        	                                      'style'=>'width:375px;']
        	                                   , elgg_view_entity($widget, ['show_access' => $show_access, 
                        	                                                'class'       =>'container droppable tn-panelWrapper___fTILOVmk', 
                        	                                                'module_type' =>'warehouse',
                        	                                                'cid'         => $cid]));
}
*//*
unset($panel_content);
foreach($panel_items as $panel){
    unset($container_content);
    $panel = (object) $panel;
    if ($panel->visible == 'false') continue;
    $panel_id = 'panel_'.$widget->handler.'_'.$space_id;
    $container_content = elgg_view_layout('pallet', ['pallet'=>$panel, 
                                                     'show_access' => $show_access, 
                                                     'class'       =>'container droppable tn-panelWrapper___fTILOVmk', 
                                                     'module_type' =>'warehouse',
                                                     'pallet_id'   => $panel_id,
                                                     'cid'         => $cid]);
    $panel_content .= elgg_format_element('div', ['class'=>"elgg-widgets panel items_draggable visible",
            	                                      'id'   => $panel_id,
            	                                      'cid'  => $cid,
            	                                      'style'=>'width:375px;']
            	                                    , $container_content);
}
*/
elgg_pop_context();

//$content .= elgg_view('graphics/ajax_loader', array('id' => 'elgg-widget-loader'));
if (elgg_is_logged_in()) {
    $sidebar_vars = $vars;
    $sidebar_vars['panel_items'] = $panel_items;
    $sidebar_vars['aspect']      = 'qboqx';
	$sidebar_area = elgg_view('page/elements/sidebar_area', $sidebar_vars);
}

$body = "<article class='main' data-aspect='$aspect'>
            <section class='panels' data-scrollable='true'>
            	<div id='view011' class='elgg-page-body table' style='width:1900px;'>
            	   $panel_content
            	</div>
            </section>
        </article>";

echo $sidebar_area.$body;

// Restore original page owner
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($page_owner->guid);
}