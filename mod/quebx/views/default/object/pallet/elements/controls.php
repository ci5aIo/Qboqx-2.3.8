<?php
/**
 * Quebx pallet controls
 *
 * @uses $vars['pallet']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */
$entity      = elgg_extract('pallet', $vars);
$show_edit   = elgg_extract('show_edit', $vars, true);
$module_type = elgg_extract('module_type', $vars);

Switch($module_type){
    case 'warehouse':
	$add = array(
		'name' => 'add',
	    'title'=> 'Add',
		'class' => 'tn-AddButton___hGq7Vqlr'
	);
	$return[] = elgg_format_element('li',['class'=>'tn-PanelHeader__addArea___hw7L0xB'],
	                                     elgg_format_element('a', $add));
	
	if ($entity->canEdit()) {
		$close = array(
			'name' => 'close',
			'title' => 'Close '.$entity->getTitle(),
			'is_action' => true,
			'class' => 'elgg-menu-content elgg-widget-close-button elgg-widget-multiple tn-CloseButton___2wUVKGfh',
			'id' => "elgg-widget-close-button-$entity->guid",
			'data-elgg-widget-type' => $entity->handler
		);
		$return[] = elgg_format_element('li',['class'=>'elgg-menu-item-delete tn-PanelHeader__closeArea___37E1NbRU'],
	                                     elgg_format_element('a', $close, elgg_view_icon('delete-alt')));

		if ($show_edit) {
			$edit = array(
				'name'               => 'settings',
				'title'              => elgg_echo('widget:edit'),
				'href'               => elgg_get_site_url()."ajax/view/widget_manager/widgets/settings?guid=$entity->guid",
				'class'              => "elgg-menu-content elgg-widget-edit-button elgg-lightbox",
				'data-color-box-opts'=>'{"width": 750, "height": 500, "trapFocus": false}',
			);
			$return[] = elgg_format_element('li',['class'=>'elgg-menu-item-settings'],
	                                     elgg_format_element('a', $edit, elgg_view_icon('settings-alt')));
		}
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
