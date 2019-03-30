<?php
use Doctrine\DBAL\Types\ObjectType;

/**
 * Elgg jot Plugin
 * @package jot
 * @TODO: Check whether a jot has relatives.  Do not delete if conditions are not met. See design for conditions.  
 */

 
// Make sure we're logged in (send us to the front page if not)
if (!elgg_is_logged_in()) forward();

// Get input data
$guid           = (int) get_input('guid');                                                 $display .= '13 $guid: '.$guid.'<br>';
$container_guid = (int) get_input('container_guid');
$tab            = get_input('tab');
$delete_contents = get_input('delete_contents');                                           $display .= '$delete_contents: '.$delete_contents.'<br>';
$jot            = get_entity($guid);
$recursion_level = 4;
if (!$container_guid){
	$container_guid = $jot->container_guid;
}
$container      = get_entity($container_guid);
$item_guid      = $container_guid;
$item           = get_entity($item_guid);
$subtype        = $jot->getSubtype();
if ($container){
	$container_subtype = $container->getSubtype();
}
if ($container){
	switch($container->getType()){
	case 'object': $referrer       = "jot/view/$container_guid";       break;
	case 'user':   $referrer       = "profile/$container->username";   break;
	case 'group':  $referrer       = "groups/profile/$container_guid"; break;
	}
//	$referrer       = "jot/$container_subtype/$container_guid";
}
if ($tab){
	$referrer  .= "/$tab";
}
if ($subtype == 'receipt_item'){ // Stay put when deleting a receipt item
	$referrer = null;
}
// register_error('$referrer: '.$referrer);
// register_error('$guid: '.$guid);
// register_error('$container_guid: '.$container_guid);

$jot_guids[] = $guid;                                                                $display .= '43 $jot_guids: '.print_r($jot_guids, true);
$guids[]     = $guid;
for ($i=0; $i<$recursion_level; $i++){
	if ($guids){
		unset($object_guids);
		foreach($guids as $level_guid){
			$level_objects = elgg_get_entities(['type'=>'object',
					                            'container_guid'=>$level_guid,
												'limit'=>0]);
			if ($level_objects){
				foreach($level_objects as $level_object){
					$object_guids[] = $level_object->guid;
				}
			}
		}
		unset($guids);
		if (is_array($object_guids)){
			$guids = $object_guids;
			$jot_guids=array_merge($jot_guids, $object_guids);
		}
	}
}                                                                                     $display .= '61 $jot_guids: '.print_r($jot_guids, true);
//goto eof;
if ($jot_guids){
// Delete children first
$jot_guids = array_reverse($jot_guids);
	foreach($jot_guids as $key=>$this_guid){
		$jot = get_entity($this_guid);
		// Make sure we actually have permission to edit
		if ($jot->canEdit()){
			// Get owning user
			$owner = get_entity($jot->getOwnerGUID());
			// Delete the jot
				$rowsaffected += $jot->delete();
		}
		if ($rowsaffected > 0) {
			// Success message
			system_message(elgg_echo("jot:deleted"));
			} else {
			register_error(elgg_echo("jot:notdeleted"));
			}
	}
}
// Forward to the referring page
forward($referrer);
		
eof:
//register_error($display);