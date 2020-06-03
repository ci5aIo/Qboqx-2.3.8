<?php
/**
 * Elgg widget move action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */
/*
$column = get_input('column', 1);
$position = get_input('position');
$widget = get_entity(get_input('widget_guid'));
if ($widget) {
	$layout_owner_guid = $widget->getContainerGUID();
	elgg_set_page_owner_guid($layout_owner_guid);
	if (elgg_can_edit_widget_layout($widget->context)) {
		$widget->move($column, $position);
		forward(REFERER);
	}
}

register_error(elgg_echo('widgets:move:failure'));
forward(REFERER);

$column = get_input('column', 1);
$position = get_input('position');
$widget = get_entity(get_input('widget_guid'));
if ($widget) {
	$layout_owner_guid = $widget->getContainerGUID();
	elgg_set_page_owner_guid($layout_owner_guid);
	if (elgg_can_edit_widget_layout($widget->context)) {
		$widget->move($column, $position);
		forward(REFERER);
	}
}

register_error('actions/pallets/move.php');
forward(REFERER);
*/
/**
 * Qboqx pallet move action
 *
 * @package Qboqx.Core
 * @subpackage Pallets.Management
 */
// $action = get_input('action');
// $boqx   = get_input('moving_boqx_id');
$column = get_input('column', 1);
$guid     = get_input('guid');
//elgg_dump('mod/quebx/actions/pallets/move.php boqx:'.$boqx.' action:'.$action.' column:'.$column.' guid:'.$guid, false);
$pallet = get_entity($guid);
if ($pallet) {
	$layout_owner_guid = $pallet->getContainerGUID();
	elgg_set_page_owner_guid($layout_owner_guid);
    set_private_setting($guid, 'column', $column);
    set_private_setting($guid, 'slot', $column);
}
