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

$num_columns = elgg_extract('num_columns', $vars, 3);
$show_add_pallets = elgg_extract('show_add_pallets', $vars, true);
$exact_match = elgg_extract('exact_match', $vars, false);
$show_access = elgg_extract('show_access', $vars, true);
$owner_guid = elgg_extract('owner_guid', $vars);

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

$widget_types = elgg_get_widget_types([
	'context' => $context,
	'exact' => $exact_match,
	'container' => $owner,
]);

elgg_push_context('widgets');

$widgets = elgg_get_widgets($owner->guid, $context);

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
/*
$content .= "<div class='qboqx-col-0'>"
            .elgg_view_menu('site',['class'=>'qboqx-menu-site-warehouse']) .
            "</div>";
*/
$widget_class = "elgg-col-1of{$num_columns}";
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
    unset($container_content);
	if (isset($widgets[$column_index])) {
		$column_widgets = $widgets[$column_index];
	} else {
		$column_widgets = array();
	}

	if (sizeof($column_widgets) > 0) {
		foreach ($column_widgets as $widget) {
			if (array_key_exists($widget->handler, $widget_types)) {
				$container_content .= elgg_view_entity($widget, ['show_access' => $show_access, 'class'=>'container droppable tn-panelWrapper___fTILOVmk', 'module_type'=>'warehouse']);
			}
		}
	}
	$panel_content .= elgg_format_element('div', ['class'=>"$widget_class elgg-widgets panel items_draggable visible",
	                                       'id'   =>"elgg-widget-col-$column_index",
	                                       'style'=>'width:375px;']
	                                    , $container_content);
}

elgg_pop_context();

//$content .= elgg_view('graphics/ajax_loader', array('id' => 'elgg-widget-loader'));

$body = "<article class='main'>
            <section class='panels' data-scrollable='true'>
            	<div id='view011' class='elgg-page-body table' style='width:1900px;'>
            	   $panel_content
            	</div>
            </section>
        </article>";

echo $body;

// Restore original page owner
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($page_owner->guid);
}