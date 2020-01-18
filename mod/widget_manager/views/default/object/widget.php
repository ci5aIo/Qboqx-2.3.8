<!-- views/default/object/widget.php  -->
<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 */

$widget      = elgg_extract('entity', $vars);
if (!elgg_instanceof($widget, 'object', 'widget')) {
	return true;
}
//@EDIT 2019-06-22 - SAJ
$class       = elgg_extract('class', $vars);
$body_class  = elgg_extract('body_class', $vars, 'full-pallet__stack');
$module_type = elgg_extract('module_type', $vars);
$parent_cid  = elgg_extract('parent_cid', $vars, quebx_new_id('c'));
$show_access = elgg_extract('show_access', $vars, true);
$this_slot   = elgg_extract('this_slot', $vars, $widget->column);
$contents_count = elgg_extract('contents_count', $vars, false);

if (!($widget instanceof WidgetManagerWidget)) {
	// need this for newly created widgets (elgg_create_widget returns ElggWidget)
	$widget = new \WidgetManagerWidget($widget->toObject());
	
	// need this to prevent memcache issue where object is stored in cache and is not a WidgetManagerWidget
	_elgg_invalidate_cache_for_entity($widget->guid);
	_elgg_invalidate_memcache_for_entity($widget->guid);
}
$cid           = quebx_new_id('c');
$empty_boqx_id = quebx_new_id('c');
$module_id     = quebx_new_id('c');

elgg_set_config('widget_show_access', $show_access);

$handler = $widget->handler;
if (widget_manager_get_widget_setting($handler, 'hide', $widget->context)) {
	return true;
}

$title = $widget->getTitle();

$widget_title_link = $widget->getURL();
if ($widget_title_link !== elgg_get_site_url()) {
	// only set usable widget titles
	$title = elgg_view('output/url', [
		'href' => $widget_title_link,
		'text' => $title,
		'is_trusted' => true,
		'class' => 'widget-manager-widget-title-link',
	]);
}

$can_edit = $widget->canEdit();

$widget_header = '';
if (($widget->widget_manager_hide_header !== 'yes') || $can_edit) {
	$controls = elgg_view('object/pallet/elements/controls', [
		'pallet' => $widget,
		'show_edit' => $can_edit,
//@EDIT 2019-06-22 - SAJ
	    'module_type'=>$module_type,
//@EDIT 2019-11-06 - SAJ
        'cid' => $module_id,
	    'target_boqx'=>$empty_boqx_id,
//@EDIT 2019-12-27 - SAJ
        'contents_count'=>$contents_count
	]);
		
//@EDIT 2019-06-22 - SAJ
	//$widget_header = "<div class='elgg-widget-handle clearfix'><h3 class='elgg-widget-title'>$title</h3>$controls</div>";
	$widget_header = elgg_format_element('div',['class'=>['elgg-widget-handle','clearfix','tn-PanelHeader__inner___3Nt0t86w','tn-PanelHeader__inner--single___3Nq8VXGB']],
                           elgg_format_element('h3',['class'=>['elgg-widget-title','tn-PanelHeader__name___2UfJ8ho9']],
                                $title).
                           $controls);
	$widget_header .= elgg_format_element('div',['class'=>'tn-PanelHeader__input__xCdUunkH'],
	                      elgg_format_element('div',['id'=>$empty_boqx_id,'class'=>'empty-boqx', 'data-boqx'=>$module_id],
	                          elgg_view('partials/jot_form_elements',['element'=>'empty boqx','handler'=>$handler,'perspective'=>'add','empty_boqx_id'=>$empty_boqx_id,'parent_cid'=>$module_id])));
}
$widget_body_vars = [
	'id'        => $cid,
    'data-boqx' => $module_id,
	'class'     => ['elgg-widget-content', $body_class],
    'data-guid' => $widget->guid,];

$fixed_height = sanitize_int($widget->widget_manager_fixed_height, false);
if ($fixed_height) {
	$widget_body_vars['style'] = "height: {$fixed_height}px; overflow-y: auto;";
}

if ($widget->showCollapsed()) {
	$widget_body_vars['class'][] = 'hidden';
}
$content_vars                   = $vars;
$content_vars['boqx_id']       = $cid;
//$content_vars['presence']      = 'envelope';
$content_vars['visible']       = 'show';
$content_vars['has_collapser'] ='yes';
$content_vars['action']        = 'show';
$content_vars['presentation']  = $module_type;
unset($content_vars ['title']);
$widget_body        = elgg_format_element('div', $widget_body_vars, elgg_view('object/widget/elements/content', $content_vars ));
$widget_class       = array_merge($widget->getClasses(), $class);                         //$display .= '$widget_class:'.print_r($widget_class,true);
$widget_module_vars = [
	'class'     => $widget_class,
	'id'        => $module_id,
	'data-boqx' => $parent_cid,
	'header'    => $widget_header];
		       
echo "<!-- module_type: $module_type -->";
//echo elgg_view_module('widget', '', $widget_body, $widget_module_vars);
Switch ($module_type){
    case 'warehouse':
// @EDIT - 2019-07-28 - SAJ - Make the pallet un-draggable
//        $rem_key = array_search('elgg-module-widget', $widget_module_vars['class']);
//        unset($widget_module_vars['class'][$rem_key]);
        $widget_body                       = elgg_format_element('div', ['class'=>'tn-pallet__stack'], $widget_body);
        $widget_module_vars['title']       = '';
        $widget_module_vars['body']        = $widget_body;
        $widget_module_vars['module_type'] = $module_type;
        $widget_module_vars['handler']     = $handler;
        $module = elgg_format_element('div',['id'=>$parent_cid,'class'=>['elgg-widgets','q-widgets','pallet','items_draggable','visible','ui-sortable'],'data-contents'=>$handler, 'data-slot'=>$this_slot],
                      elgg_view('page/components/module_warehouse', $widget_module_vars));
        break;
    default:
        $module = elgg_view_module('widget', '', $widget_body, $widget_module_vars);
}

echo $module;                                                                                  //register_error($display);