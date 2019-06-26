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
$site = elgg_get_site_entity();
$site_name = $site->name;
$text = "<div>
     <button aria-label='Q:boqx  - Everything' class='tc_projects_dropdown_link tc_context_name'>
          <span class='tc_context_name'>
               <span class='raw_context_name'>$site_name</span>
          </span>
     </button>
</div>";
$header_elements[] =['text'       => $text,
                    'attributes' => ['class'=> '']];

// drop-down login
//echo elgg_view('core/account/login_dropdown');
/*$header_elements[] = ['text'       => elgg_view('pearl/usermenu'),
                     'attributes' =>['class'=>'tc_pull_right tc_extra_wide']];*/
$header_elements[] = ['text'       => "<div class='Dropdown tn_profile_dropdown'>
                                        <div class='Dropdown__content' data-aid='ProfileDropdown'>
                                            <button class='SMkCk__Button _3jN8d__Button--header Dropdown__button' aria-label='Profile Dropdown' type='button'>scottjenkins</button>
                                        </div>
                                       </div>",
                     'attributes' =>['class'=>'tc_pull_right tc_extra_wide']];


// insert site-wide navigation
//echo elgg_view_menu('site');

$header_elements[] = ['text'  => elgg_format_element('div',['class'=>'menu next search_bar_container',
                                                            'id'   => 'view61']
                                                          , elgg_view('search/header')),
                     'attributes' => ['class'=>'tc_pull_right']];
foreach ($header_elements as $key=>$element){
    $element = (object) $element;
    $header_list_items .= elgg_format_element('li', $element->attributes
                                                  , $element->text);    
}
$header_list = elgg_format_element('ul'    ,[]
                                           ,$header_list_items);
$header_menu .= elgg_view_menu('site');
$page_header = elgg_format_element('header',['class'=>'tc_page_header tc_page_header_version-ia tc_page_header-ia tc_page_header-expanded']
                                           ,$header_list.$header_menu);


echo $page_header;