<?php

/*
 *
 * Project Name:  Pearl
 *
 * @package: pearl
 * @author Clifton Barron - SocialApparatus
 * @license Commercial
 * @copyright Copyright (c) 2012, Clifton Barron
 *
 * @link http://socia.us
 *
 */
/*$header_elements[] =['text'       => elgg_view('page/elements/header_logo', $vars),
                    'attributes' => ['class'=> '']];*/


//$logo  = elgg_get_site_url().'mod/quebx/graphics/sW7Lr82V-logo.svg';
$show_site_menu = elgg_extract('show_site_menu', $vars, true);
$logo  = elgg_get_site_url().'mod/quebx/graphics/placeholder_logo.png';
$site = elgg_get_site_entity();
$site_name = $site->name;
$space_button = elgg_format_element('button',['aria-label'=>'Q:boqx  - Everything',
                                              'data-jq-dropdown'=>"#space_menu",
                                              'class'=>'tc_projects_dropdown_link tc_context_name'],
                                             "<span class='tc_context_name'><span class='raw_context_name'>$site_name</span></span>");
$space_menu = elgg_view('navigation/menu/space');

// drop-down login
//echo elgg_view('core/account/login_dropdown');
/*$header_elements[] = ['text'       => elgg_view('pearl/usermenu'),
                     'attributes' =>['class'=>'tc_pull_right tc_extra_wide']];*/
$shelf_items = '<li>fake item 1</li><li>fake item 2</li><li>fake item 3</li><li>fake item 4</li>';
$shelf_id    = quebx_new_id('c');
$shelf_items = elgg_view('object/shelf', ['perspective'=>'header', 'parent_cid'=>$shelf_id]);
$shelf_items_count = shelf_count_items ();
if (elgg_is_logged_in()) {
    $user_menu_options = elgg_view('pearl/loggedin');
	$user = elgg_get_logged_in_user_entity();
    $user_name = $user->name;
    $user_tag = $user_name;
} else {
    $user_menu_options = elgg_view('pearl/loggedout');
    $user_tag = "Login/Register";
}
$user_menu = 
        elgg_format_element('div',['class'=>'Dropdown tn_profile_dropdown'],
            elgg_format_element('div', ['class'=>'Dropdown__content',
                                        'style'=>'display:none;',],
                elgg_format_element ('div', ['class'=>'Dropdown__options'],
                                            $user_menu_options)).
                elgg_format_element ('button', ['class'=>'Dropdown__button SMkCk__Button _3jN8d__Button--header',
                                                'aria-label'=>'Profile Dropdown',
                                                'type'=>'button'], 
                                            $user_tag));
$bulk_actions =
    elgg_format_element('div',['class'=>"selectedStoriesControls", 'data-aid'=>"SelectedStoriesControls"],
		elgg_format_element('div',['class'=>"selectedStoriesControls__status"],
	    	elgg_format_element('a',['data-jq-dropdown'=>"#shelfShowItems", 'data-vertical-offset'=>"10"],
				elgg_format_element('span',['class'=>"selectedStoriesControls__counter"],$shelf_items_count).
				elgg_format_element('span',['class'=>"selectedStoriesControls__counterLabel"],'selected')).
			elgg_format_element('button',['class'=>["selectedStoriesControls__button","selectedStoriesControls__button--deselectAll"],
			                              'type'=>"button",
										  'title'=>"Drop all items off of the shelf",
										  'data-aid'=>"BulkDeselect"],
				elgg_format_element('span',[],'Drop all'))).
		elgg_format_element('div',['class'=>"selectedStoriesControls__actions"],
			elgg_format_element('a',['class'=>["selectedStoriesControls__button","selectedStoriesControls__button--label"],
			                         'data-aid'=>"BulkLabels",
									 'type'=>"button",
									 'title'=>"Add/remove labels to/from selected items"],
				elgg_format_element('span',[],'Label')).
			elgg_format_element('a',['class'=>["selectedStoriesControls__button","selectedStoriesControls__button--move", "move"],
			                         'data-aid'=>"MoveItems",
									 'type'=>"button",
									 'title'=>"Move selected items to another space"],
				elgg_format_element('span',[],'Move')).
			elgg_format_element('a',['class'=>["selectedStoriesControls__button","selectedStoriesControls__button--review"],
			                         'data-aid'=>"BulkReviews",
									 'type'=>"button",
									 'title'=>"Add/remove reviews to/from selected items"],
				elgg_format_element('span',[],'Review')).
			elgg_format_element('button',['class'=>["selectedStoriesControls__button","selectedStoriesControls__button--clone","clone"],
			                         'data-aid'=>"SelecteditemsControls__Clone",
									 'type'=>"button",
									 'title'=>"Clone selected items"],
				elgg_format_element('span',[],'Clone')).
			elgg_format_element('a',['class'=>["selectedStoriesControls__button","selectedStoriesControls__button--people"],],
				elgg_format_element('div',['class'=>"DropdownButton__icon___1qwu3upG",
				                           'data-aid'=>"bulkPeople",
										   'tabindex'=>"0",
										   'title'=>"Actions"],
								    'People')).
			elgg_format_element('button',['class'=>["selectedStoriesControls__button","selectedStoriesControls__button--csv","export_csv"],
			                         'data-aid'=>"SelectedStoriesControls__Clone",
									 'type'=>"button",
									 'title'=>"Export selected items to CSV"],
				elgg_format_element('span',[],'CSV')).
			elgg_format_element('button',['class'=>["selectedStoriesControls__button"," selectedStoriesControls__button--delete","delete"],
			                              'data-aid'=>'DeleteItems',
										  'type' =>"button",
										  'title'=>"Delete selected items"],
				elgg_format_element('span',[],'Delete')))).
			elgg_format_element('div',['id'=>'shelfShowItems', 'class'=>['selectedStoriesControls__list','jq-dropdown']],
		        elgg_format_element('div',[],
		            elgg_format_element('div',['class'=>'shelf-items'],
		                elgg_format_element('div',['class'=>'shelf-viewarea'],
		                    elgg_format_element('ul',['id'=>$shelf_id, 'class'=>['shelf-items-compartment','jq-dropdown-menu']],$shelf_items)))));

$header_elements[] = ['text'     => "<a href='/dashboard' aria-label='Dashboard' class='tc_header_item tc_header_logo' style='margin-right: 8px;'><img class='headerLogo__image' src='$logo' alt='que logo'></a>",
                      'attributes'=>['class'=>['tc_page_nav_header','visible']]];
/*$header_elements[] = ['text'     => "<a href='/dashboard' aria-label='Dashboard'style='margin-right: 8px;'><span class='tc_header_item tc_header_logo'>&nbsp;</span></a>",
                      'attributes'=>['class'=>['tc_page_nav_header','visible']]];*/
$header_elements[] = ['text'       => elgg_format_element('div',[],$space_button.$space_menu),
                      'attributes' => ['class'=> ['tc_page_nav_header','visible']]];
$header_elements[] = ['text'       => $bulk_actions,
                      'attributes' => ['class'=>'tc_page_bulk_header']];
$header_elements[] = ['text'       => $user_menu,
                      'attributes' =>['class'=>['tc_pull_right tc_extra_wide','visible']]];
$header_elements[] = ['text'       => "<div class='Dropdown _nCX4ioru__HelpDropdown__indicator--newHeader'>
                                        <div class='Dropdown__content' style='display:none;'>
                                           <div class='Dropdown__options'>
                                             ... content ...
                                           </div>                             
                                        </div>
                                        <button class='SMkCk__Button _3jN8d__Button--header Dropdown__button' type='button'>Help</button>
                                      </div>",
                     'attributes' =>['class'=>['tc_pull_right','tc_extra_wide','visible']]];
$header_elements[] = ['text'      => "<div class='Dropdown _1WzQ9__ProductUpdatesDropdown__indicator--newHeader'>
                                        <div class='Dropdown__content' style='display:none;' data-aid='ProductUpdatesDropdown__indicator--newHeader'>
                                           <div class='Dropdown__options'>
                                             ... content ...
                                           </div></div><button class='SMkCk__Button _3jN8d__Button--header Dropdown__button' type='button'>What's New</button></div>",
                     'attributes' =>['class'=>['tc_pull_right','tc_extra_wide','visible']]];
$header_elements[] = ['text'      => elgg_format_element('div',['id'=>'view61','class'=>['menu','next','search_bar_container']], elgg_view('search/header')),
                     'attributes' => ['class'=>['tc_pull_right','visible']]];
$header_elements[] = ['text'      => elgg_format_element('div',['class'=>'_3weZY__NotificationsBell'],
                                         elgg_format_element('div',['class'=>'Dropdown _3pEKc__NotificationsBell__dropdownContainer'],
                                             elgg_format_element('div',['class'=>'Dropdown__content _1Npm___NotificationsBell__dropdownContent'],
                                                 elgg_format_element('button',['class'=>['SMkCk__Button _3INnV__Button--default','Dropdown__button _2Oy9G__NotificationsBell__button'],'aria-label'=>'0 unread notifications','type'=>'button'])))),
                     'attributes' => ['class'=>['tc_pull_right','visible']]];
foreach ($header_elements as $key=>$element){
    $element = (object) $element;
    $header_list_items .= elgg_format_element('li', $element->attributes
                                                  , $element->text);    
}
$header_list = elgg_format_element('ul'    ,[]
                                           ,$header_list_items);
$header_class = ['tc_page_header','tc_page_header_version-ia','tc_page_header-ia'];
if($show_site_menu){
    $site_menu = elgg_view_menu('site');
    $header_class[]= 'tc_page_header-expanded';
}
$page_header = elgg_format_element('header',['class'=>$header_class],$header_list.$site_menu);


echo $page_header;