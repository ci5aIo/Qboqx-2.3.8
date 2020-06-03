<?php
/***
 * @var $guid
 * @var $cid
 * @var $perspective
 */

$guid         = elgg_extract('guid', $vars);
$name         = elgg_extract('name', $vars);
$boqx_id      = elgg_extract('cid' , $vars);
$slot         = elgg_extract('slot', $vars);
$aspect       = elgg_extract('aspect', $vars);
$open_class   = elgg_extract('open_class', $vars,'');
$cid          = quebx_new_id('c');
$section      ='contents';
$presentation ='pallet';
$presence     ='item boqx';
$entity       = get_entity($guid);
$contents = elgg_get_entities([
        'type' => 'object',
//@EDIT 2020-05-06 - SAJ subtype 'market' replaced by 'q_item'
        'subtypes' => ['market', 'item','q_item', 'contents'],
        'joins'    => array('JOIN elgg_objects_entity e2 on e.guid = e2.guid'),
		'wheres' => array(
			"e.container_guid = $entity->guid",
            "NOT EXISTS (SELECT *
                         from elgg_entity_relationships s1
                         WHERE s1.relationship = 'component'
                           AND s1.guid_two = e.container_guid)"
		),
        'order_by' => 'e2.title',
        'limit' => false,
	]);
if (count($contents)>0) {
    $pieces = count($contents);
    $contents_count = 0;
    $contents   = elgg_get_entities(['type'=>'object', 'subtypes'=>ELGG_ENTITIES_ANY_VALUE, 'limit' => false,]);
    foreach ($contents as $content)
		$elements[] = ['guid'=> $content->guid,'container_guid'=>$content->container_guid,'title'=> $content->title];
	foreach ($elements as $element) {
	    ++$contents_count;
	    $id = $element['guid'];
	    $parent_id = $element['container_guid'];
	    $data[$id] = $element;
	    $index[$parent_id][] = $id;
	}
	$display_options = ['data'=>$data,'index'=>$index,'aspect'=>'contents', 'parent_id'=>$entity->guid,'ul_class'=>'hierarchy','collapsible'=>true,'collapse_level'=>1,'level'=>0,'links'=>false,'presentation'=>'contents','presence'=>$presence,'display'=>['expandable'=>true, 'selectable'=>false, 'show_owner'=>false, 'show_labels'=>false]];
	$item_contents = quebx_display_child_nodes_III($display_options);
}

echo elgg_view_layout('pallet',['presentation'=>'open_boqx','id'=>$boqx_id,'slot'=>$slot,'aspect'=>$aspect,'name'=>$name,'pieces'=>quebx_count_pieces($guid, $aspect),'content'=>$item_contents['contents'], 'open_class'=>$open_class]);