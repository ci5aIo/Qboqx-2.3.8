<?php
$inventory   = elgg_extract('inventory'  , $vars);
$item        = elgg_extract('entity'     , $vars);
$perspective = elgg_extract('perspective', $vars, 'page');
$state       = elgg_extract('state'      , $vars);
$guid        = elgg_extract('guid'       , $vars);
$parent_cid  = elgg_extract('parent_cid' , $vars);
if(elgg_entity_exists($guid))
    $item = get_entity($guid);
$cid         = quebx_new_id('c');
$aspect      = $item->aspect ?: 'thing';                                                                     $display .= '11 $aspect = '.$aspect.'<br>';

if ($item){
	$element_type = 'receipt item';
    $title = $item->title;
    $content_item = elgg_view('output/url', [
        		'text' =>  $title,
        		'href' =>  $item->getURL(),
        		'class'=> 'rTableform90',]);
    $item_options = ['name'    => 'item[selected][]',
					    'value'   => $item->guid,
    			        'default' => false,];
    $item_check = elgg_view('input/checkbox', $item_options);
    $qty = elgg_view('input/number', [
				'name'  => 'item[quantity][]',
	            'value' => $vars['quantity'],
	        	'max'   => $inventory,]);
}        
Switch ($perspective){
	case 'sidebar':
		if (!empty($item->icon)){$icon_guid = $item->icon;}              
		else {$icon_guid = $item->guid;}
		$icon = elgg_view('market/thumbnail', ['marketguid' => $item->guid, 'size' => 'tiny', 'item_guid'=>$item->guid]);
//		$icon = elgg_view('market/thumbnail', ['marketguid' => $icon_guid, 'size' => 'tiny', 'item_guid'=>$item->guid]);
		$image_vars=['item_guid'=>$item->guid];
		$this_item = elgg_view_image_block($icon, $item->title, $image_vars);//, $vars);
		$content = "<li class='quebx-shelf-item' data-perspective=$perspective id='quebx-shelf-item-$item->guid' data-container-guid='$item->container_guid' >
						<div class='ShelfShow__elw1jufs'>
    						$this_item
    						<nav class='ShelfShow__actions___oosero4fs undefined ShelfShow__actions--unfocused___234slkj65'>
    							<button class='IconButton___0po345dx IconButton--small___ew4pds0kd' data-aid='delete' aria-label='Delete' data-item-guid='$item->guid'>
    								<span><a title='remove from shelf'><span class='elgg-icon fa elgg-icon-delete-alt fa-times-circle'></span></a></span>
    							</button>
    						</nav>
                        </div>
					</li>";
		break;
	case 'header':
	    $content = 
	       elgg_format_element('li',['class'=>'shelf-viewer','data-perspective'=>$perspective,'id'=>"quebx-shelf-item-$item->guid",'data-container-guid'=>$item->container_guid],
	           elgg_format_element('div',['id'=>$cid,'class'=>['story','model','item','feature','unscheduled'], 'data-boqx'=>$parent_cid, 'data-guid'=>$item->guid],
	               elgg_format_element('div',['class'=>['shelfview'], 'data-cid'=>$cid],
	                   elgg_format_element('span',['class'=>['name','normal']],
	                       elgg_format_element('span',[],
	                           elgg_format_element('span',[],$item->title))))));
	    break;
	case 'space_sidebar':
	    $content = elgg_view('page/components/pallet_boqx', ['entity'=>$item,'guid'=>$guid,'state'=>$state,'aspect'=>$aspect,'boqx_id'=>$parent_cid, 'boqx_class'=>'quebx-shelf-item']);
	    break;
	case 'page':
	default:
		$content = "<div class='rTableRow'>
						<div class='rTableCell' style='width:0'>$item_check</div>
						<div class='rTableCell' style='width:5%'>$qty</div>
						<div class='rTableCell' style='width:95%'>$content_item</div>
							".elgg_view('input/hidden', array('name' => 'item[guid][]',
															  'value' => $item->guid,
															))."
					</div>";
	break;
}
echo $content;
//register_error($display);