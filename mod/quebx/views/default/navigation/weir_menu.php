<?php
$aspects     = elgg_extract('aspects'    , $vars);                                   //elgg_dump($aspects, false);
$boqx_name   = elgg_extract('boqx_name'  , $vars);
$boqx_aspect = elgg_extract('boqx_aspect', $vars);
$boqx_value  = elgg_extract('boqx_value' , $vars);
$cid         = elgg_extract('cid'        , $vars);
$menu_class  = elgg_extract('menu_class' , $vars, 'weir_menu');
$menu_count  = elgg_extract('menu_level' , $vars, 1);
$boqx_class  = elgg_extract('boqx_class' , $vars, 'compartmentBoqx__m2HVyVRp');
$list_class  = elgg_extract('list_class' , $vars, 'pickList__q0EfbGIo');
$item_class  = elgg_extract('item_class' , $vars, 'pickItem__nCGQmMHP');
$link_class  = elgg_extract('link_class' , $vars, 'pickLink__p3nIV1Uh');
$crumb_class = elgg_extract('crumb_class', $vars, 'pickItem__ujGWJJw9');
$label_class = elgg_extract('label_class', $vars, 'pickLabel__sdRC4Kf9');

foreach ($aspects as $key=>$aspect){
	unset($anchor, $arrow, $list_item, $label_text, $attributes);
	$options    = ['encode_text'=>true];
	$aspect     = (object)$aspect;
	$label_text = elgg_format_element('span', ['class'=>$label_class], $aspect->name);
	$attributes = ['class'=>$item_class, 'data-value'=>$aspect->value, 'data-index'=>$key+1, 'data-aspect'=>$aspect->name];
//	if ($aspect->has_children) $attributes['class'] .= ' has_children'; 
	if ($aspect->has_children) {$arrow = elgg_format_element('a',['class'=>['menu_arrow','pickChildren__HBThno'], 'title'=>'Open']);}
	$anchor     = elgg_format_element('a', ['class'=>$link_class, 'id'=>$key.'_boqx_aspect_select_'.$cid], $label_text);
	$list_item  = elgg_format_element('li',$attributes, $anchor.$arrow);
	$list_items .= $list_item;
	}
$menu = elgg_format_element('div', ['class'=>$boqx_class, 'data-value'=>$boqx_value, 'data-boqx'=>$boqx_name, 'data-aspect'=>$boqx_aspect, 'data-level'=>$menu_count], 
             elgg_format_element('ul', ['class'=>$list_class], $list_items));
$breadcrumb = elgg_format_element('div', ['class'=>'weir_selections'],
                elgg_format_element('div',['class'=>'selections']).
                    elgg_format_element('a',['class'=>['selector',$crumb_class]],
                        elgg_format_element('span',['class'=>['fa','fa-window-close']])));
if ($menu_count == 1) $menu = elgg_format_element('div', ['class'=>$menu_class,'id'=>"selector_{$cid}"],$breadcrumb.$menu);

echo $menu;