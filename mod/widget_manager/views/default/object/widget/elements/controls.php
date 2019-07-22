<?php
/**
 * Elgg widget controls
 *
 * @uses $vars['widget']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */
$entity      = elgg_extract('widget', $vars);
$show_edit   = elgg_extract('show_edit', $vars, true);
$module_type = elgg_extract('module_type', $vars);

Switch($module_type){
    case 'warehouse':
        $widget = $entity;
	/* @var \ElggWidget $widget */
/*	$collapse = array(
		'name' => 'collapse',
		'text' => ' ',
		'href' => "#elgg-widget-content-$widget->guid",
		'class' => 'elgg-menu-content elgg-widget-collapse-button',
		'rel' => 'toggle'
	);
	$return[] = elgg_format_element('li',['class'=>'elgg-menu-item-collapse'],
	                                     elgg_format_element('a', $collapse, $collapse['text']));*/
	$add = array(
		'name' => 'add',
		'text' => ' ',
	    'title'=> 'Add',
		'class' => 'tn-AddButton___hGq7Vqlr',
		'rel' => 'toggle'
	);
	$return[] = elgg_format_element('li',['class'=>'tn-PanelHeader__addArea___hw7L0xB'],
	                                     elgg_format_element('a', $add, $add['text']));
	
	if ($widget->canEdit()) {
		$close = array(
			'name' => 'close',
			'text' => elgg_view_icon('delete-alt'),
			'title' => 'Close '.$widget->getTitle(),
			'is_action' => true,
			'class' => 'elgg-menu-content elgg-widget-close-button elgg-widget-multiple tn-CloseButton___2wUVKGfh',
			'id' => "elgg-widget-close-button-$widget->guid",
			'data-elgg-widget-type' => $widget->handler
		);
		$return[] = elgg_format_element('li',['class'=>'elgg-menu-item-delete tn-PanelHeader__closeArea___37E1NbRU'],
	                                     elgg_format_element('a', $close, $close['text']));

		if ($show_edit) {
			$edit = array(
				'name' => 'settings',
				'text' => elgg_view_icon('settings-alt'),
				'title' => elgg_echo('widget:edit'),
				'href' => "#widget-edit-$widget->guid",
				'class' => "elgg-menu-content elgg-widget-edit-button elgg-lightbox",
				'rel' => 'toggle'
			);
			$return[] = elgg_format_element('li',['class'=>'elgg-menu-item-settings'],
	                                     elgg_format_element('a', $edit, $edit['text']));
		}
/*		$add = ['title'=>'New Thing',
		        'tabindex'=>0,
		        'data-aid'=>'NewButton',
		        'class'=>'SomethingAddButton_zUHGSY4G',
		        'text' =>'New Q'];
		$return[] = elgg_format_element('li',['class'=>'elgg-menu-item-add tn-PanelHeader__action___3zvuQp6Z'],
	                                     elgg_format_element('a', $add, $add['text']));*/
	}
	foreach($return as $menu_item){
	    $menu_items .= $menu_item;
	}
    $controls = elgg_format_element('ul',['class'=>'elgg-menu elgg-menu-widget elgg-menu-hz elgg-menu-widget-default'],
                                         $menu_items);    
	echo $controls;
        break;
    default:
        echo elgg_view_menu('widget', array(
        	'entity' => $entity,
        	'show_edit' => $show_edit,
            'sort_by' => 'priority',
        	'class' => 'elgg-menu-hz',
        ));

}
