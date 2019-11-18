<?php
/**
 * A single element of a menu.
 *
 * @package Elgg.Core
 * @subpackage Navigation
 *
 * @uses $vars['item']       ElggMenuItem
 * @uses $vars['item_class'] Additional CSS class for the menu item
 */

$item = $vars['item'];
$context = elgg_get_context();                                                       $display .= 'context: '.$context.'<br>';

Switch ($context){
    case 'dashboard':
        $link_class = '_257Dx__projectNavTab';
        if ($item->getSelected())
            $link_class .= ' _2l-eS__projectNavTab--current';
        $children = $item->getChildren();
        if ($children) {
        	$item->addLinkClass($link_class);
        	$item->addLinkClass('elgg-menu-parent');
        }
        $menu_text = elgg_format_element('span', [], $item->getText()); 
        $menu_item = elgg_view_menu_item($item, ['text'=>$menu_text, 'class'=>$link_class]);
        if ($children) {
        	$menu_item .= elgg_view('navigation/menu/elements/section', array(
        		'items' => $children,
        		'class' => 'elgg-menu elgg-child-menu',
        	));
        }
        break;
    default:
        $link_class = 'elgg-menu-closed';
        if ($item->getSelected()) {
        	// @todo switch to addItemClass when that is implemented
        	//$item->setItemClass('elgg-state-selected');
        	$link_class = 'elgg-menu-opened';
        }
        
        $children = $item->getChildren();
        if ($children) {
        	$item->addLinkClass($link_class);
        	$item->addLinkClass('elgg-menu-parent');
        }
        
        $item_class = $item->getItemClass();
        if ($item->getSelected()) {
        	$item_class = "$item_class elgg-state-selected";
        }
        if (isset($vars['item_class']) && $vars['item_class']) {
        	$item_class .= ' ' . $vars['item_class'];
        }
        
        $menu_item .= "<li class=\"$item_class\">";
        $menu_item .= elgg_view_menu_item($item);
        if ($children) {
        	$menu_item .= elgg_view('navigation/menu/elements/section', array(
        		'items' => $children,
        		'class' => 'elgg-menu elgg-child-menu',
        	));
        }
        $menu_item .= '</li>';
}
echo $menu_item;
//register_error($display);