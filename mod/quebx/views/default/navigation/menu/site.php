<?php
/**
 * Site navigation menu
 *
 * @uses $vars['menu']['default']
 * @uses $vars['menu']['more']
 */

$default_items = elgg_extract('default', $vars['menu'], array());
$more_items = elgg_extract('more', $vars['menu'], array());
$class = elgg_extract('class', $vars);
$context = elgg_get_context();

Switch ($context){
    case 'dashboard':
        foreach ($default_items as $menu_item) {
            if ($menu_item->getText() == 'Things') $menu_item->setSelected();
        	$site_navigation .= elgg_view('navigation/menu/elements/item', ['item' => $menu_item]);
        }
        
        if ($more_items) {
        	$more = elgg_echo('more');
        	$site_navigation .= elgg_format_element('a',['href'=>'#','class'=>'_257Dx__projectNavTab', 'data-aid'=>'navTab-more']
        	                                           ,$more);
        	$site_navigation .= elgg_view('navigation/menu/elements/section',
                                    	 ['class' => 'elgg-menu elgg-menu-site elgg-menu-site-more', 
                                    	  'items' => $more_items,]);
        }
        $site_navigation .= elgg_format_element('div', ['class'=>"_3-iOd__projectNav__toggleWrapper", 'data-aid'=>"collapseButton"]
                                                     , elgg_format_element('button',['class'=>"_GPfz__projectNav__toggle",'aria-label'=>"collapse header"])); 
        
        $site_navigation = elgg_format_element('nav',['class'=>'_199kd__projectNavExpanded'],$site_navigation);
    break;
    default:
        $site_navigation .= "<ul class='elgg-menu elgg-menu-site elgg-menu-site-default clearfix $class'>";
        foreach ($default_items as $menu_item) {
        	$site_navigation .= elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
        }
        
        if ($more_items) {
        	$site_navigation .= '<li class="elgg-more">';
        
        	$more = elgg_echo('more');
        	$site_navigation .= "<a href=\"#\">$more</a>";
        	
        	$site_navigation .= elgg_view('navigation/menu/elements/section', array(
        		'class' => 'elgg-menu elgg-menu-site elgg-menu-site-more', 
        		'items' => $more_items,
        	));
        	
        	$site_navigation .= '</li>';
        }
        $site_navigation .= '</ul>';
}
echo $site_navigation;