<?php
/**
 * Qboqx widget add action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */
                                                                            $display .= 'q_widgets/add <br>';
$page_owner_guid = get_input('page_owner_guid');
$handler = get_input('handler');
$context = get_input('context');                                            $display .= '$context: '.$context.'<br>';
$show_access = (bool)get_input('show_access', true);
$column = get_input('column', 1);                                           $display .= '$column: '.$column.'<br>';
$default_widgets = get_input('default_widgets', 0);

elgg_set_page_owner_guid($page_owner_guid);

elgg_push_context($context);
if ($default_widgets) {
	elgg_push_context('default_widgets');
}
elgg_push_context('widgets');

// logged in user must be able to edit the layout to add a widget
$page_owner = elgg_get_page_owner_entity();                                 $display .= '$page_owner->guid'.$page_owner->guid.'<br>';
if ($page_owner && elgg_can_edit_widget_layout($context)) {
	$guid = elgg_create_widget($page_owner->getGUID(), $handler, $context);
	if ($guid) {
		$widget = get_entity($guid);

		// position the widget
		$widget->move($column, 0);

		// send widget html for insertion
		echo elgg_view_entity($widget, array('show_access' => $show_access));

		forward(REFERER);
	}	
}

register_error(elgg_echo('widgets:add:failure'));
register_error($display);
forward(REFERER);
