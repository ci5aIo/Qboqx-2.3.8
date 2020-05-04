<?php

$guid    = elgg_extract('guid', $vars);
$open_state = elgg_extract('open_state', $vars);
$boqx_id = elgg_extract('boqx_id', $vars, quebx_new_id('a'));
$entity = get_entity($guid);
switch($entity->getSubtype()){
    case 'market':
    case 'item':
        $aspect = 'thing';
        $list_items[]=['name'=>'details'    ,'handler'=>'market','aspect'=>'item'        ,'id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id];
        $list_items[]=['name'=>'contents'   ,'handler'=>'market','aspect'=>'contents'    ,'id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id,'count'=>quebx_count_pieces($guid, 'contents')];
        $list_items[]=['name'=>'accessories','handler'=>'market','aspect'=>'accessories' ,'id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id,'count'=>quebx_count_pieces($guid, 'accessories')];
        $list_items[]=['name'=>'components' ,'handler'=>'market','aspect'=>'components'  ,'id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id,'count'=>quebx_count_pieces($guid, 'components')];
        $list_items[]=['name'=>'issues'     ,'handler'=>'jot'   ,'aspect'=>'issues'      ,'id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id,'count'=>quebx_count_pieces($guid, 'issues')];
        $list_items[]=['name'=>'experiences','handler'=>'jot'   ,'aspect'=>'experiences' ,'id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id,'count'=>quebx_count_pieces($guid, 'experiences')];
        $list_items[]=['name'=>'gallery'                        ,'aspect'=>'gallery'     ,'id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id,'contents'=>'list','connection'=>'related'];
        $list_items[]=['name'=>'documents'                      ,'aspect'=>'documents'   ,'id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id,'contents'=>'list','connection'=>'related'];
        $list_items[]=['name'=>'transactions'                   ,'aspect'=>'transactions','id'=>quebx_new_id('c'),'cid'=>quebx_new_id('c'),'guid'=>$guid,'data-boqx'=>$boqx_id,'contents'=>'list','connection'=>'related'];        
        break;
    case 'experience':
        
        break;
    case 'issue':
        
         break;
    case 'receipt':
        
        break;
    default:
        break;    
}
foreach($list_items as $key=>$list_item){
    $list_item['slot']=$key+1;
    $list_item['action']='edit';
    $list_item['perspective']='edit';
    $list_item['open_class'] = $list_item['aspect']==$open_state?'open':'';
    $pallets[$key]   = quebx_get_pallet($list_item);
    $menu_item[$key] = elgg_format_element('li',['class'=>['item',$list_item['name'],'visible',$list_item['open_class']],'name'=>$list_item['name'],'count'=>$list_item['count'],'id'=>$list_item['name'].'_'.$guid,'cid'=>$list_item['cid'],'visible'=>'visible','data-boqx'=>$aid],
							elgg_format_element('button',['class'=>'pallet_toggle','data-boqx'=>$list_item['name'],'data-cid'=>$list_item['cid']],
								elgg_format_element('span',['class'=>'pallet_name'],$list_item['name'])).
							elgg_format_element('div',['class'=>'counter','aria-label'=>'count'],$list_item['count']));
//    $pallet[$key]    = quebx_get_pallet($list_item);
}
for ($key = 0; $key <= 8; $key++) {
    $list_item=$list_items[$key];
    $list_item['slot']=$key+1;
    $list_item['action']='edit';
    $list_item['perspective']='edit';
    $list_item['open_class'] = $list_item['aspect']==$open_state?'open':'';
    $pallets[$key]   = quebx_get_pallet($list_item);
/*    $menu_item[$key] = elgg_format_element('li',['class'=>['item',$list_item['name'],'visible',$list_item['open_class']],'name'=>$list_item['name'],'count'=>$list_item['count'],'id'=>$list_item['name'].'_'.$guid,'cid'=>$list_item['cid'],'visible'=>'visible','data-boqx'=>$aid],
							elgg_format_element('button',['class'=>'pallet_toggle','data-boqx'=>$list_item['name'],'data-cid'=>$list_item['cid']],
								elgg_format_element('span',['class'=>'pallet_name'],$list_item['name'])).
							elgg_format_element('div',['class'=>'counter','aria-label'=>'count'],$list_item['count']));
*/    $pallet[$key]    = quebx_get_pallet($list_item);
}
$menu_items = implode('',$menu_item);
$controlbar = elgg_format_element('aside',['class'=>"controlbar",'data-aspect'=>"qboqx"],
                   elgg_format_element('div',['class'=>"controlbar_wrapper"],
        			   elgg_format_element('div',['class'=>"controlbar-header"],				  
        				   elgg_format_element('div',['class'=>"name"],
        					   elgg_format_element('span',['class'=>"controlbar-title"],'Open Boqx')).
        			       elgg_format_element('div',['class'=>"controls"],
        					   elgg_format_element('span',['class'=>"button close",'title'=>'minimize boqx','data-action'=>'close']).
        			           elgg_format_element('span',['class'=>"button remove",'title'=>'close and remove boqx from shelf','data-action'=>'remove']))).
        			   elgg_format_element('section',['class'=>"control-content scrollable"],
        				    elgg_format_element('section',['class'=>"menu"],
        					    elgg_format_element('div',['class'=>"panels"],
        					        elgg_format_element('ul',['class'=>'items'],implode('',$menu_item))))).
        				 elgg_format_element('footer',['class'=>"controlbar-footer"],
        					 elgg_format_element('div',[],
        						 elgg_format_element('button',['class'=>"open_close",'title'=>"Expand or contract"],'&hellip;')))));
$slots = implode('',$pallet);
echo elgg_view_layout('open_boqx',['guid'=>$guid,'aspect'=>$aspect,'control_bar'=>$controlbar,'slots'=>$slots]);
