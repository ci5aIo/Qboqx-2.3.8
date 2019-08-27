<?php
$aspects    = elgg_extract('aspects'    , $vars);                                   //elgg_dump($aspects, false);
$this_aspect= elgg_extract('aspect'     , $vars);
$cid        = elgg_extract('cid'        , $vars);
$menu_class = elgg_extract('menu_class' , $vars, 'weir_menu');
$menu_count = elgg_extract('menu_level' , $vars, 1);
$boqx_class = elgg_extract('boqx_class' , $vars, 'compartmentBoqx__m2HVyVRp');
$list_class = elgg_extract('list_class' , $vars, 'pickList__q0EfbGIo');
$item_class = elgg_extract('item_class' , $vars, 'pickItem__nCGQmMHP');
$link_class = elgg_extract('link_class' , $vars, 'pickLink__p3nIV1Uh');
$label_class= elgg_extract('label_class', $vars, 'pickLabel__sdRC4Kf9');

foreach ($aspects as $key=>$aspect){
	    unset($anchor, $list_item, $label_text, $attributes);
	    $aspect = (object)$aspect;
	    $label_text = elgg_format_element('span', ['class'=>$label_class], $aspect->name);
	    $anchor = elgg_format_element('a', ['class'=>$link_class, 'id'=>$key.'_boqx_aspect_select_'.$cid], $label_text);
	    $attributes= ['class'=>$item_class, 'data-value'=>$aspect->value, 'data-index'=>$key+1, 'data-aspect'=>$aspect->name];
	    if ($aspect->has_children) $attributes['class'] .= ' has_children'; 
	    $list_item = elgg_format_element('li',$attributes, $anchor);
	    $list_items .= $list_item;
	}
$menu = elgg_format_element('div', ['class'=>$boqx_class,'data-boqx'=>$this_aspect, 'data-level'=>$menu_count], 
             elgg_format_element('ul', ['class'=>$list_class], $list_items));
$breadcrumb = elgg_format_element('div', ['class'=>'weir_selections'],
                elgg_format_element('div',['class'=>'selections']).
                elgg_format_element('a',['class'=>'selector'], 'Done'));
if ($menu_count == 1) $menu = elgg_format_element('div', ['class'=>$menu_class,'id'=>"selector_{$cid}"],$breadcrumb.$menu);

echo $menu;