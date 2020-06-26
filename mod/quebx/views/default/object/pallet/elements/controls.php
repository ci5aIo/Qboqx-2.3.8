<?php
/**
 * Quebx pallet controls
 *
 * @uses $vars['pallet']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */
$entity      = elgg_extract('pallet', $vars);
$show_edit   = elgg_extract('show_edit', $vars, true);
$show_add    = elgg_extract('show_add', $vars, true);
$module_type = elgg_extract('module_type', $vars);
$cid         = elgg_extract('cid', $vars);
$parent_cid  = elgg_extract('parent_cid', $vars);
$target_boqx = elgg_extract('target_boqx', $vars);
$contents_count = elgg_extract('contents_count', $vars, false);
$title       = elgg_extract('title', $vars);
$control_options = (array) elgg_extract('control_options', $vars);

Switch($module_type){
    case 'warehouse':
    	$menu_item['add'] = elgg_format_element('li',['class'=>'tn-PanelHeader__addArea___hw7L0xB'],
    	                        elgg_format_element('a', ['class'=>'tn-AddButton___hGq7Vqlr','name' => 'add','title'=> 'Add','data-cid'=>$cid,'data-target-boqx'=> $target_boqx]));
    	if($contents_count)
    	   $menu_item['counter'] = elgg_format_element('li',['class'=>'palletControls__counter'],$contents_count);
        
    //     $menu_item[] = elgg_format_element('li',['class'=>['elgg-menu-item-menu','tn-PanelHeader__menu_4ITCRyXi']],
    // 	                                     elgg_format_element('a', [], elgg_view_icon('ellipsis-v')));
/*        $menu_item['actions'] = elgg_format_element('li',['class'=>['tn-PanelHeader__menu_4ITCRyXi']],
                                	elgg_format_element('button',['class'=>["MuiButtonBase-root","MuiIconButton-root","MuiIconButton-sizeSmall"],'tabindex'=>"0",'type'=>"button",'title'=>"Actions"],
                                        elgg_format_element('span',['class'=>"MuiIconButton-label"],
                                          elgg_format_element('a', ['class'=>'palletActions_D8x8EuHj'], elgg_view_icon('ellipsis-v')).
                                          elgg_format_element('div',['class'=>["tn-DropdownButton_3N5I43GN",'collapsed']],
                                              elgg_format_element('div',[],
                                                  elgg_format_element('div',['class'=>"tc_scrim"]).
                                                  elgg_format_element('ul',['class'=>"_3Sa3d__DropdownMenu__menuList"],
                                                      elgg_format_element('li',['class'=>"w39lj__DropdownMenuOption"],
                                                          elgg_format_element('span',[],'Select all')).
                                                      elgg_format_element('li',['class'=>"w39lj__DropdownMenuOption",'data-aid'=>"ClonePallet"],
                                                          elgg_format_element('span',[],'Clone panel'))))))));
*/        
        $menu_item['clone'] = elgg_format_element('li',['class'=>['tn-PanelHeader__menu_4ITCRyXi']],
                                  elgg_format_element('span',['title'=>'Clone Pallet'],
                                      elgg_format_element('a', ['class'=>'palletControl_VHr65Izd','data-aid'=>"ClonePallet"], elgg_view_icon('clone'))));
    	   
    	if ($entity->canEdit()) {
    		$menu_item['close'] = elgg_format_element('li',['class'=>'tn-PanelHeader__closeArea___37E1NbRU'],
    	                              elgg_format_element('a', ['class' => ['elgg-menu-content','elgg-widget-close-button','elgg-widget-multiple','tn-CloseButton___2wUVKGfh'],'name' => 'close','title' => 'Close '.$entity->getTitle(),'is_action' => true,'data-elgg-widget-type' => $entity->handler], elgg_view_icon('delete-alt')));
    		if ($show_edit) {
    			$edit = array(
    				'name'               => 'settings',
    				'title'              => elgg_echo('widget:edit'),
    				'href'               => elgg_get_site_url()."ajax/view/widget_manager/widgets/settings?guid=$entity->guid",
    				'class'              => "elgg-menu-content elgg-widget-edit-button elgg-lightbox",
    				'data-color-box-opts'=>'{"width": 750, "height": 500, "trapFocus": false}',
    			);
    		 $menu_item['settings'] = elgg_format_element('li',['class'=>['tn-PanelHeader__settings__Lm1ErqzE']],
    	                                  elgg_format_element('a', $edit, elgg_view_icon('settings-alt')));
    		}
    	}
        $controls = elgg_format_element('ul',['class'=>'elgg-menu elgg-menu-widget elgg-menu-hz elgg-menu-widget-default palletControls'],
                                             implode('',$menu_item));
//    	echo $controls;
//************************************************************************************************************************************
        unset($menu_item);
		$menu_item['add'] = elgg_format_element('div',['class'=>['tn-PanelHeader__addArea___hw7L0xB','controlIcon']],
    	                        elgg_format_element('a', ['class'=>'tn-AddButton___hGq7Vqlr','name' => 'add','title'=> 'Add','data-cid'=>$cid,'data-target-boqx'=> $target_boqx]));
    	if($contents_count)
    	   $menu_item['counter'] = elgg_format_element('div',['class'=>'palletControls__counter'],$contents_count);
	    $menu_item['title'] = elgg_format_element('h3',['class'=>['tn-PanelHeader__name___2UfJ8ho9']],
                                    $title);
		$menu_item['marquis'] = elgg_format_element('div',['class'=>'tn-PanelHeader_marquis_eYsgHffY'],
		                            $menu_item['counter'].$menu_item['title']);
		if(in_array('clone', $control_options)){
            $menu_item['clone'] = elgg_format_element('div',['class'=>['tn-PanelHeader__menu_4ITCRyXi','controlIcon']],
                                      elgg_format_element('span',['title'=>'Clone Pallet'],
                                          elgg_format_element('a', ['class'=>'palletControl_VHr65Izd','data-aid'=>"ClonePallet"], elgg_view_icon('clone'))));
            $menu_item['clone_badge'] = elgg_format_element('div',['class'=>'tn-PanelHeader_badge'],
                                             elgg_format_element('span',['class'=>'panelBadge'],'clone'));
		}
    	   
    	if ($entity->canEdit()) {
    		$menu_item['close'] = elgg_format_element('div',['class'=>['tn-PanelHeader__closeArea___37E1NbRU','controlIcon']],
    	                              elgg_format_element('a', ['class' => ['elgg-menu-content','elgg-widget-close-button','elgg-widget-multiple','tn-CloseButton___2wUVKGfh'],'name' => 'close','title' => 'Close '.$entity->getTitle(),'is_action' => true,'data-elgg-widget-type' => $entity->handler], elgg_view_icon('delete-alt')));
    		if ($show_edit) {
    			$edit = array(
    				'name'               => 'settings',
    				'title'              => elgg_echo('widget:edit'),
    				'href'               => elgg_get_site_url()."ajax/view/widget_manager/widgets/settings?guid=$entity->guid",
    				'class'              => "elgg-menu-content elgg-widget-edit-button elgg-lightbox",
    				'data-color-box-opts'=>'{"width": 750, "height": 500, "trapFocus": false}',
    			);
    		 $menu_item['settings'] = elgg_format_element('div',['class'=>['tn-PanelHeader__settings__Lm1ErqzE','controlIcon']],
    	                                  elgg_format_element('a', $edit, elgg_view_icon('settings-alt')));
    		}
    	}
        $controls = elgg_format_element('div',['class'=>'palletControls'],
                        elgg_format_element('div',['class'=>'tn-PalletControl_xDoxa2dR leftSide'],
						     $menu_item['clone_badge'].
                             $menu_item['add'].
							 $menu_item['marquis']).
                        elgg_format_element('div',['class'=>'tn-PalletControl_xDoxa2dR rightSide'],
						     $menu_item['settings'].
							 $menu_item['clone'].
							 $menu_item['close']));
    	echo $controls;
        
        break;
    case 'space_sidebar':
        if($contents_count) $return[] = elgg_format_element('li',['class'=>'palletControls__counter'],$contents_count);
    	
		$close = ['name'                  => 'close',
    			  'title'                 => 'Close '.$entity->title,
    			  'is_action'             => true,
    			  'class'                 => 'elgg-menu-content elgg-widget-close-button elgg-widget-multiple tn-CloseButton___2wUVKGfh',
    			  'id'                    => "elgg-widget-close-button-$entity->guid",
    			  'data-elgg-widget-type' => $entity->handler];
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
    	foreach($return as $menu_item){
    	    $menu_items .= $menu_item;
    	}
        $controls = elgg_format_element('ul',['class'=>'elgg-menu elgg-menu-widget elgg-menu-hz elgg-menu-widget-default palletControls'],
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
