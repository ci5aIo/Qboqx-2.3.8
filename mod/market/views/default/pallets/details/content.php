<?php
/***
 * @var $guid
 * @var $name
 * @var $cid
 * @var $slot
 * @var $aspect
 */

$guid         = elgg_extract('guid', $vars);
$name         = elgg_extract('name', $vars);
$boqx_id      = elgg_extract('cid' , $vars);
$slot         = elgg_extract('slot', $vars);
$aspect       = elgg_extract('aspect', $vars);
$open_class   = elgg_extract('open_class', $vars,'');
$entity       = get_entity($guid);

$params['guid']        = $guid;
$params['entity']      = $entity;
$params['parent_cid']  = $boqx_id;
$params['fill_level']  = 'full';
$params['perspective'] = 'edit';
$params['section']     = 'single_thing';
$params['presentation']= 'open_boqx';
$params['aspect']      = 'item';
$params['display_state']='edit';

$content = elgg_view('forms/market/edit',$params);
//$content = 'Details';
echo elgg_view_layout('pallet',['presentation'=>'open_boqx','id'=>$boqx_id,'slot'=>$slot,'aspect'=>$aspect,'name'=>$name,'content'=>$content, 'open_class'=>$open_class]);