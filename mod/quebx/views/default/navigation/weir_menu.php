<?php
$aspects     = elgg_extract('aspects'    , $vars);                                   //elgg_dump($aspects, false);
$boqx_name   = elgg_extract('boqx_name'  , $vars);
$boqx_aspect = elgg_extract('boqx_aspect', $vars);
$boqx_value  = elgg_extract('boqx_value' , $vars);
$item_value  = elgg_extract('item_value' , $vars);
$ancestry    = elgg_extract('ancestry'   , $vars);
$cid         = elgg_extract('cid'        , $vars);
$menu_class  = elgg_extract('menu_class' , $vars, 'weir_menu');
$menu_count  = elgg_extract('menu_level' , $vars, 1);
$boqx_class  = elgg_extract('boqx_class' , $vars, 'compartmentBoqx__m2HVyVRp');
$list_class  = elgg_extract('list_class' , $vars, 'pickList__q0EfbGIo');
$item_class  = elgg_extract('item_class' , $vars, 'pickItem__nCGQmMHP');
$link_class  = elgg_extract('link_class' , $vars, 'pickLink__p3nIV1Uh');
$crumb_class = elgg_extract('crumb_class', $vars, 'pickItem__ujGWJJw9');
$label_class = elgg_extract('label_class', $vars, 'pickLabel__sdRC4Kf9');

if($boqx_class == 'compartmentBoqx__Cdil2TkU') {$li_class = 'pickedItem__R8VF5oDQ'; $a_class = 'pickedLink__1yKII8tz';}
else                                           {$li_class = 'pickedItem__Dows8rhn'; $a_class = 'pickedLink__elUW0FxF';} 
       

foreach ($aspects as $key=>$aspect){
	unset($anchor, $arrow, $list_item, $list_item_class, $label_text, $attributes);
	$list_item_class[] = $item_class;
	$options    = ['encode_text'=>true];
	$aspect     = (object)$aspect;
	$label_text = elgg_format_element('span', ['class'=>$label_class], $aspect->name);
	if ($item_value == $aspect->value) $list_item_class[] = 'selected'; 
	$attributes = ['class'=>$list_item_class, 'data-value'=>$aspect->value, 'data-index'=>$key+1, 'data-aspect'=>$aspect->name];
//	if ($aspect->has_children) $attributes['class'] .= ' has_children'; 
	if ($aspect->has_children) {$arrow = elgg_format_element('a',['class'=>['menu_arrow','pickChildren__HBThno'], 'title'=>'Open']);}
	$anchor     = elgg_format_element('a', ['class'=>$link_class, 'id'=>$key.'_boqx_aspect_select_'.$cid], $label_text);
	$list_item  = elgg_format_element('li',$attributes, $anchor.$arrow);
	$list_items .= $list_item;
	}
if ($ancestry){
    $lineage = array_reverse($ancestry);
    foreach($lineage as $key=>$guid){                                                       $display .= '38 $guid = '.$guid.'<br>';
        $ancestor = get_entity($guid);
        $data_boqx = $lineage[$key + 1];
        $label = $ancestor->title;        
        $trail = elgg_format_element('ul', ['class'=>'pickedList__XR2j7lQP'],
                        elgg_format_element('li',['class'=>$li_class, 'data-value'=>$lineage[$key], 'data-aspect'=>$boqx_aspect,'data-boqx'=>$data_boqx,'data-level'=>$menu_count],
                            elgg_format_element('a',['class'=>$a_class],
                                elgg_format_element('span',['class'=>'pickedLabel__uNI8tKTa'],$label)).
                            $trail));
    }
}

$menu = elgg_format_element('div', ['class'=>$boqx_class, 'data-value'=>$boqx_value, 'data-boqx'=>$boqx_name, 'data-aspect'=>$boqx_aspect, 'data-level'=>$menu_count], 
             elgg_format_element('ul', ['class'=>$list_class], $list_items));
$breadcrumb = elgg_format_element('div', ['class'=>'weir_selections'],
                elgg_format_element('div',['class'=>'selections'], $trail).
                    elgg_format_element('a',['class'=>['selector',$crumb_class]],
                        elgg_format_element('span',['class'=>['fa','fa-window-close']])));
if ($menu_count == 1) $menu = elgg_format_element('div', ['class'=>$menu_class,'id'=>"selector_{$cid}"],$breadcrumb.$menu);

echo $menu;
register_error($display);