<?phpelgg_load_library('elgg:quebx:navigation');$page_owner = elgg_get_page_owner_entity();$show_tally = elgg_extract('show_tally', $vars, true);$show_empty = elgg_extract('show_empty', $vars, false);$vars['name'] = preg_replace('/[^a-z0-9\-]/i', '-', $vars['name']);$headers = elgg_extract('show_section_headers', $vars, false);$menu    = elgg_extract('menu', $vars);$selected = elgg_extract('selected', $vars);$selected_owner = elgg_extract('owner', $vars);$list_type      = elgg_extract('list_type', $vars);$class = "elgg-menu elgg-menu-{$vars['name']}";if (isset($vars['class'])) {	$class .= " {$vars['class']}";}foreach ($menu as $section => $menu_item) {    if (!show_empty){        $children = quebx_get_occupied_subcategories_v2(get_entity($menu_item->guid), $selected_owner);    }    $children = quebx_get_occupied_subcategories_v2($menu_item['guid'], $selected_owner);	echo elgg_view('navigation/flyout/categories/section', array(		'items' =>$children,//		'class' => "$class elgg-menu-{$vars['name']}-$section",		'section' => $menu_item,		'selected'=>$selected,	    'selected_owner' => $selected_owner,		'name' => $vars['name'],		'collapse'=> $vars['collapse'],		'show_section_headers' => $headers,		'show_toggle'=> $vars['show_toggle'],		'show_tally'=>$show_tally,		'show_empty'=>$show_empty,	    'list_type' => $list_type,	));}