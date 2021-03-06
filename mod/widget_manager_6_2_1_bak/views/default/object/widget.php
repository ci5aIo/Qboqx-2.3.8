<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 */

$widget = $vars['entity'];
if (!elgg_instanceof($widget, 'object', 'widget')) {
	return true;
}
//@EDIT 2019-06-22 - SAJ
$class = elgg_extract('class', $vars);
$body_class = elgg_extract('body_class', $vars, 'story model item draggable');
$module_type = elgg_extract('module_type', $vars);

$show_access = elgg_extract('show_access', $vars, true);
elgg_set_config("widget_show_access", $show_access);

// @todo catch for disabled plugins
$widget_types = elgg_get_widget_types('all');

$handler = $widget->handler;
$widget_context = $widget->context;

if (widget_manager_get_widget_setting($handler, "hide", $widget_context)) {
	return true;
}

$title = $widget->getTitle();

$widget_title_link = $widget->getURL();
if ($widget_title_link !== elgg_get_site_url()) {
	// only set usable widget titles
	$title = elgg_view("output/url", array("href" => $widget_title_link, "text" => $title, 'is_trusted' => true, "class" => "widget-manager-widget-title-link"));
}

$can_edit = $widget->canEdit();

$controls = elgg_view('object/widget/elements/controls', array(
	'widget' => $widget,
	'show_edit' => $can_edit,
    'module_type'=>$module_type,
));

$content = elgg_view('object/widget/elements/content', $vars);

$widget_id = "elgg-widget-$widget->guid";
$widget_instance = "elgg-widget-instance-$handler";
$widget_class = "elgg-module elgg-module-widget";
$widget_header = "";

if ($can_edit) {
	$widget_class .= " elgg-state-draggable $widget_instance";
} else {
	$widget_class .= " elgg-state-fixed $widget_instance";
}

if ($widget->widget_manager_custom_class) {
	// optional custom class for this widget
	$widget_class .= " " . $widget->widget_manager_custom_class;
}

if ($widget->widget_manager_hide_header == "yes") {
	if ($can_edit) {
		$widget_class .= " widget_manager_hide_header_admin";
	} else {
		$widget_class .= " widget_manager_hide_header";
	}
}

if ($widget->widget_manager_disable_widget_content_style == "yes") {
	$widget_class .= " widget_manager_disable_widget_content_style";
}
if ($class)
    $widget_class .= " $class";
/*
if (($widget->widget_manager_hide_header != "yes") || $can_edit) {
	$widget_header = <<<HEADER
		<div class="elgg-widget-handle clearfix"><h3 class="elgg-widget-title">$title</h3>
		$controls
		</div>
HEADER;
}*/
if (($widget->widget_manager_hide_header != "yes") || $can_edit) {
	$widget_header = elgg_format_element('div',['class'=>'elgg-widget-handle clearfix tn-PanelHeader__inner___3Nt0t86w tn-PanelHeader__inner--single___3Nq8VXGB'],
	                                           elgg_format_element('h3',['class'=>'elgg-widget-title tn-PanelHeader__name___2UfJ8ho9'],
	                                                                    $title).
	                                           $controls);
}
    
    
$fixed_height = sanitize_int($widget->widget_manager_fixed_height, false);

$widget_body_class = "elgg-widget-content $body_class";

if ($widget->widget_manager_collapse_disable !== "yes") {
	$widget_is_collapsed = false;
	$widget_is_open = true;
	
	if (elgg_is_logged_in()) {
		$widget_is_collapsed = widget_manager_check_collapsed_state($widget->guid, "widget_state_collapsed");
		$widget_is_open = widget_manager_check_collapsed_state($widget->guid, "widget_state_open");
	}
	if (($widget->widget_manager_collapse_state === "closed" || $widget_is_collapsed) && !$widget_is_open) {
	
		$widget_body_class .= " hidden";
	}

}

$widget_body = "<div class='" . $widget_body_class . "'";
if ($fixed_height) {
	$widget_body .= " style='height: " . $fixed_height . "px; overflow-y: auto;'";
}

$widget_body .= " id='elgg-widget-content-" . $widget->guid . "'>";
$widget_body .= $content;
$widget_body .= "</div>";
$module_vars = ['class'  => $widget_class,
        		'id'     => $widget_id,
        		'header' => $widget_header,];

Switch ($module_type){
    case 'warehouse':
        $widget_body = elgg_format_element('div', ['class'=>'tn-panel__loom'], $widget_body);
        $module_vars['title'] = '';
        $module_vars['body']  = $widget_body;
        $module_vars['module_type'] = $module_type;
        $module = elgg_view('page/components/module_warehouse', $module_vars);
        break;
    default:
        $module = elgg_view_module('widget', '', $widget_body, $module_vars);
}

echo $module;