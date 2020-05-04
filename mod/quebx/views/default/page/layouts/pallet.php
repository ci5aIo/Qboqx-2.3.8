<?php
/**
 * Pallet object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 */

$presentation = elgg_extract('presentation', $vars, 'pallet');
$pallet      = elgg_extract('pallet', $vars);
$class       = elgg_extract('class', $vars);
$body_class  = elgg_extract('body_class', $vars, 'pallet draggable');
$module_type = elgg_extract('module_type', $vars);
$show_access = elgg_extract('show_access', $vars, true);
$pallet_id   = elgg_extract('pallet_id', $vars, "elgg-widget-$pallet->guid");

Switch($presentation){
    case 'open_boqx':
        $id     = elgg_extract('id', $vars, quebx_new_id('c'));
        $slot   = elgg_extract('slot', $vars);
        $cid    = quebx_new_id('c');
        $name   = elgg_extract('name', $vars);
        $pieces = elgg_extract('pieces', $vars);
        $content= elgg_extract('content', $vars);
        $aspect = elgg_extract('aspect', $vars,'');
        $open_class = elgg_extract('open_class', $vars,'');
        $module = elgg_format_element('div',['id'=>$id,'class'=>["pallet","visible",$aspect,$open_class],'data-contents'=>$aspect,'data-slot'=>$slot],
                	   elgg_format_element('div',['id'=>$cid,'class'=>['panelWrapper___mHr8fycU'],'data-boqx'=>$id],
//                       elgg_format_element('div',['id'=>$cid,'class'=>['boqx','container','droppable','panelWrapper___mHr8fycU','q-module','q-module-widget'],'data-boqx'=>$id],
                		  elgg_format_element('header',['class'=>"panelHeader___rp8gB85y",'cid'=>$cid],
                			  elgg_format_element('div',['class'=>['clearfix','panelHeader_inner___cNPfuQmy']],
                				  elgg_format_element('span',['class'=>['palletControls__counter',$name]],$pieces).
                				  elgg_format_element('h3',['class'=>"panelHeader_name___0zWOQSS8"],
                				      elgg_format_element('span',['class'=>'pallet_name'],$name)))).
                		  elgg_format_element('section',['class'=>['items_container','panelContainer_items__bRCDQNLi'],'cid'=>$cid,'data-scrollable'=>"true"],
                			  elgg_format_element('div',['class'=>"items"],
                				  elgg_format_element('div',['class'=>"boqx_stack"],
                					  $content)))));
        break;
    default:
        elgg_set_config("widget_show_access", $show_access);
        
        // @todo catch for disabled plugins
        $pallet_types = elgg_get_widget_types('all');
        
        $handler        = $pallet->handler;                       $display.= 'handler: '.$handler.'<br>';
        $pallet_context = $pallet->context;                       $display.= 'context: '.$pallet_context.'<br>';
        if (widget_manager_get_widget_setting($handler, "hide", $pallet_context)) {
        	return true;
        }
        
        $title = elgg_format_element('div', ["class" => "widget-manager-widget-title-link"], $pallet->title);
        
        $controls = elgg_view('object/widget/elements/controls', array(
        	'widget' => $pallet,
        	'show_edit' => $can_edit,
        ));
        
        $content = elgg_view('object/widget/elements/content', $vars);
        
        $pallet_instance = "elgg-widget-instance-$handler";
        $pallet_class = "pallet";
//@EDIT - 2020-03-20 - SAJ - Remove widget behaviors.  Replacing with slot and pallet behaviors
//        $pallet_class = "elgg-module elgg-module-widget";
        $pallet_header = "";
        
        if ($can_edit) {
        	$pallet_class .= " elgg-state-draggable $pallet_instance";
        } else {
        	$pallet_class .= " elgg-state-fixed $pallet_instance";
        }
        
        if ($pallet->widget_manager_custom_class) {
        	// optional custom class for this widget
        	$pallet_class .= " " . $pallet->widget_manager_custom_class;
        }
        
        if ($pallet->widget_manager_hide_header == "yes") {
        	if ($can_edit) {
        		$pallet_class .= " widget_manager_hide_header_admin";
        	} else {
        		$pallet_class .= " widget_manager_hide_header";
        	}
        }
        
        if ($pallet->widget_manager_disable_widget_content_style == "yes") {
        	$pallet_class .= " widget_manager_disable_widget_content_style";
        }
        if ($class)
            $pallet_class .= " $class";
/*        
        if (($pallet->widget_manager_hide_header != "yes") || $can_edit) {
        	$pallet_header = <<<HEADER
        		<div class="elgg-widget-handle clearfix"><h3 class="elgg-widget-title">$title</h3>
        		$controls
        		</div>
        HEADER;
        }
*/        
        $fixed_height = sanitize_int($pallet->widget_manager_fixed_height, false);
        
        $pallet_body_class = "elgg-widget-content $body_class";
        
        if ($pallet->widget_manager_collapse_disable !== "yes") {
        	$pallet_is_collapsed = false;
        	$pallet_is_open = true;
        	
        	if (elgg_is_logged_in()) {
        		$pallet_is_collapsed = widget_manager_check_collapsed_state($pallet->guid, "widget_state_collapsed");
        		$pallet_is_open = widget_manager_check_collapsed_state($pallet->guid, "widget_state_open");
        	}
        	if (($pallet->widget_manager_collapse_state === "closed" || $pallet_is_collapsed) && !$pallet_is_open) {
        	
        		$pallet_body_class .= " hidden";
        	}
        
        }
        
        $pallet_body = "<div class='" . $pallet_body_class . "'";
        if ($fixed_height) {
        	$pallet_body .= " style='height: " . $fixed_height . "px; overflow-y: auto;'";
        }
        
        $pallet_body .= " id='elgg-widget-content-" . $pallet->guid . "'>";
        $pallet_body .= $content;
        $pallet_body .= "</div>";
        $module_vars = ['class'  => $pallet_class,
                		'id'     => $pallet_id,
                		'header' => $pallet_header,];
        $module = elgg_view_module('widget', '', $pallet_body, $module_vars);
        
        $pallet_body = elgg_format_element('div', ['class'=>'tn-panel__loom'], $pallet_body);
        $module_vars['title'] = '';
        $module_vars['body']  = $pallet_body;
        $module_vars['module_type'] = $module_type;
        $module = elgg_view('page/components/module_warehouse', $module_vars);
}
echo $module;