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
$show_navigation = elgg_extract('show_navigation', $vars, true);
$context = elgg_get_context();
elgg_push_context('widgets');

Switch ($context){
    case 'dashboard':
        $tag_name = 'header';
        $text    .= elgg_view('page/elements/header_logo', $vars);
        
        // drop-down login
        $text    .= elgg_view('core/account/login_dropdown');
        $attributes = ['class'=>'tc_page_header tc_page_header_version-ia tc_page_header-ia tc_page_header-expanded',
                       'data-aid'=>'PageHeader',
        ];
        $options  = [];
        $header   = elgg_format_element($tag_name, $attributes, $text, $options);
        break;
    default:
        $header .= elgg_view('page/elements/header_logo', $vars);
        
        // drop-down login
        $header .= elgg_view('core/account/login_dropdown');
    
        // insert site-wide navigation
        $header .= elgg_view_menu('site');
}

echo $header;