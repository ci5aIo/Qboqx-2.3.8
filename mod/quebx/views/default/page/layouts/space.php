<!--  Path: mod/quebx/views/default/page/layouts/space.php -->
<?php
/**
 * Qboqx warehouse layout
 * 2019-06-20 - SAJ - Forked from Elgg widgets layout
 *
 * @uses $vars['content']          Optional display box at the top of layout
 * @uses $vars['num_pallets']      Number of widget columns for this layout (3)
 * @uses $vars['show_add_pallets'] Display the add pallets button and panel (true)
 * @uses $vars['exact_match']      Widgets must match the current context (false)
 * @uses $vars['show_access']      Show the access control (true)
 * @uses $vars['owner_guid']       Widget owner GUID (optional, defaults to page owner GUID)
 */
//elgg_set_context('warehouse');
/*
$aid              = elgg_extract('aid',$vars);
$num_pallets      = elgg_extract('num_pallets', $vars, 3);
$show_add_pallets = elgg_extract('show_add_pallets', $vars, true);
$exact_match      = elgg_extract('exact_match', $vars, false);
$show_access      = elgg_extract('show_access', $vars, true);
$owner_guid       = elgg_extract('owner_guid', $vars);
$aspect           = elgg_extract('aspect', $vars);*/
$space_navigator     = elgg_extract('space_navigator', $vars);
$space_content    = elgg_extract('space_content', $vars);
$floor_size      = elgg_extract('floor_size', $vars, 1900);

$page_owner       = elgg_get_page_owner_entity();

if ($owner_guid)
	$owner = get_entity($owner_guid);
else
	$owner = $page_owner;

if (!$owner) {
	elgg_log('Can not resolve owner entity for widget layout', 'ERROR');
	return;
}

// Underlying views and functions assume that the page owner is the owner of the widgets
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($owner->guid);
}
//elgg_set_context('warehouse');
$context = elgg_get_context();
echo "<!--context: $context -->";

$widget_types = elgg_get_widget_types([
	'context' => $context,
	'exact' => $exact_match,
	'container' => $owner,
]);

//elgg_push_context($context);
elgg_push_context('warehouse');

/****Experimental****/
/*unset($space_content, $column_widgets);
for ($column_index = 1; $column_index <= $num_pallets; $column_index++) {
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
$space_content .= $panels;
*/
/*for ($column_index = 1; $column_index <= $num_pallets; $column_index++) {
	if (isset($widgets[$column_index])) {
//        elgg_dump($widgets[$column_index]);        	    
		$column_widgets[] = $widgets[$column_index];
	}
}
//unset($space_content);
//elgg_dump($column_widgets);
foreach($column_widgets as $widget){
    $cid = quebx_new_id('c');
//    elgg_dump($widget);
    $space_content .= elgg_format_element('div', ['class'=>"elgg-widgets panel items_draggable visible",
        	                                      'id'   => 'panel_'.$widget->handler.'_'.$space_id,
        	                                      'cid'  => $cid,
        	                                      'style'=>'width:375px;']
        	                                   , elgg_view_entity($widget, ['show_access' => $show_access, 
                        	                                                'class'       =>'container droppable tn-panelWrapper___fTILOVmk', 
                        	                                                'module_type' =>'warehouse',
                        	                                                'cid'         => $cid]));
}
*//*
unset($space_content);
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
    $space_content .= elgg_format_element('div', ['class'=>"elgg-widgets panel items_draggable visible",
            	                                      'id'   => $panel_id,
            	                                      'cid'  => $cid,
            	                                      'style'=>'width:375px;']
            	                                    , $container_content);
}
*/
elgg_pop_context();

//$content .= elgg_view('graphics/ajax_loader', array('id' => 'elgg-widget-loader'));

$body = "<article class='main' data-aspect='$aspect'>
            <section class='floor' data-scrollable='true'>
            	<div id='view011' class='slots' style='width:{$floor_size}px;'>
            	   $space_content
            	</div>
            </section>
        </article>";

echo $space_navigator.$body;

// Restore original page owner
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($page_owner->guid);
}
//register_error($display);