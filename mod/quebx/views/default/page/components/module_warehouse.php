<?php

/**
 * Elgg module element
 *
 * @uses $vars['type']         The type of module (main, info, popup, aside, etc.)
 * @uses $vars['title']        Optional title text (do not pass header with this option)
 * @uses $vars['header']       Optional HTML content of the header
 * @uses $vars['body']         HTML content of the body
 * @uses $vars['footer']       Optional HTML content of the footer
 * @uses $vars['class']        Optional additional class for module
 * @uses $vars['id']           Optional id for module
 * @uses $vars['show_inner']   Optional flag to leave out inner div (default: false)
 */
$type = elgg_extract('type', $vars, false);                                                        $display .= 'quebx/views/default/page/components/module_warehouse.php<br>';
$title = elgg_extract('title', $vars, '');
$body = elgg_extract('body', $vars, '');                                                           $display.='$body is '.strlen($body).' characters long<br>';
$footer = elgg_extract('footer', $vars, '');
$show_inner = elgg_extract('show_inner', $vars, false);
$handler = elgg_extract('handler', $vars);                                                         $display .= 'module_warehouse 20 $handler = '.$handler.'<br>';
$data_boqx = elgg_extract('data-boqx', $vars);
$cid     = elgg_extract('id', $vars);
$header = elgg_extract('header', $vars);
$guid  = elgg_extract('guid', $vars);

$body_class = ['elgg_body','items','panel_content'];
$attrs = ['id' => $cid,'class' => elgg_extract_class($vars),'data-guid'=>$guid, 'data-boqx'=>$data_boqx, 'data-contents'=>$handler];
//@EDIT - 2020-03-25 - SAJ - Removed the elgg-module class
//$attrs = ['id' => $cid,'class' => elgg_extract_class($vars, 'elgg-module'),'data-guid'=>$guid, 'data-boqx'=>$data_boqx];

if ($type){
	$attrs['class'][] = "elgg-module-$type";
	$attrs['class'][] = "q-module-$type";
}

$user_agent_styles = elgg_view('css/quebx/user_agent',['element' => $handler]);

if ($title)
	$header = elgg_format_element('h3', [], $title);

if ($header !== null) 
	$header = elgg_format_element('header', ['class' => ['tn-PanelHeader___c0XQCVI7','tn-PanelHeader--single___2ns28dRL'],'cid'=>$cid], $header);

$body = elgg_format_element('section', ['class'=>['items_container','tn-panel-items-container___1Fk42hjC'],'cid'=>$cid,'data-scrollable' => 'true'], 
            elgg_format_element('div', ['class' => $body_class],$body));

if ($footer)
	$footer = elgg_format_element('div', ['class' => 'elgg-foot'], $footer);

$contents = $header . $body . $footer;

if ($show_inner)
	$contents = elgg_format_element('div', ['class' => 'elgg-inner'], $contents);

$container = elgg_format_element('div', $attrs, $contents);
echo $user_agent_styles . $container;
//register_error($display);