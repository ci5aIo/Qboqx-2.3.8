<?php
/**
 * QuebX pick elements action
 *
 * Used by: market\views\default\forms\market\pick.php
 *        : market\views\default\forms\market\groups.php
 *        :
 */

/* Pseudocode
 * Receive element_type.
 * Receive item_guid
 * if item_guid is empty
	* set item_guid to 0  
 * Receive container_guid
 * If container_guid is empty
 	* set container_guid to item_guid 
 * Receive selected groups
 * Determine type of container
 * If container is a transfer item
	 * Create new receipt item
	 * Create new link between receipt item and selected item
 * If container is a receipt item
	 * Delete existing link between receipt item and selected item
	 * Create new link between receipt item and selected item
 */

// Get variables
$element_type     =        get_input('element_type');
$item_guid        = (int)  get_input('item_guid' , 0);
$container_guid   = (int)  get_input('container_guid', $item_guid);
$group_type       =        get_input('group_type', 'support');
$group_subtype    =        get_input('group_subtype');
$access_id        = (int)  get_input("access_id");
$owner_guid       = (int)  get_input('owner_guid', elgg_get_logged_in_user_guid());
$selected_groups  =        get_input('selected_groups');
$show_on_timeline = (int)  get_input('show_on_timeline');
$timeline_label   =        get_input('timeline_label');
$add_cost_to_que  =        get_input('add_cost_to_que');
$que_contribution =        get_input('que_contribution', 'none');
$retain_line_label =       get_input('retain_line_label');
$link_type        =        get_input('link_type');
$distribute_freight =      get_input('distribute_freight', '0');
$unpack           =        get_input('unpack', '0');

$item             =        get_entity($item_guid);
$container        =        get_entity($container_guid);
if ($container_guid != 0) {
	$container_type   =    $container->getSubtype();
}
else {
	$container_type   =    get_input('container_type', 'transfer');
}
if ($item_guid != 0) {
	$item_type        =    $item->getSubtype();
}
if ($link_type   == 'pick link type ...'){
	unset($link_type);
}
$display  = '$retain_line_label: '.$retain_line_label.'<br>';
$display .= '$link_type: '.$link_type.'<br>';
$display .= '$item_guid: '.$item_guid.'<br>';
$display .= '$unpack: '   .$unpack   .'<br>';
elgg_make_sticky_form('jotForm');

Switch ($group_type) {
	case 'support':
		$relationship = 'support_group_of';
		break;
	case 'supplier':
		Switch ($group_subtype){
			case 'Merchant':
				$relationship = 'merchant_of';
				break;
		default:
			$relationship = 'supplier_of';
			break;
		}
	break;
}

if (($container_type == 'transfer' || $container_type == 'receipt_item') && $container_guid != $item_guid){	
	$relationship = 'receipt_item';
}

/*if ($existing_groups){
	$existing_groups = $existing_groups->getguid();
}*/
//$element_type   = $item->getSubtype();
// register_error('$group_subtype: '.$group_subtype);
//register_error('$item_guid: '.$item_guid);
//register_error('$item_type: '.$item_type);
// register_error('$container_guid: '.$container_guid);
// register_error('$container_type: '.$container_type);
// register_error('$element_type: '.$element_type);
// register_error('$relationship: '.$relationship);
// register_error('$relationship: '.$relationship);
// register_error('$show_on_timeline: '.$show_on_timeline);

$relationships = get_entity_relationships ($item_guid, $inverse_relationship=false);
	
/**
 * Updates the group associations of an item
 * @param type $item
 * @param type $selected_groups
 * @param type $existing_groups
 * @param type $relationship
 */
function groups_collections_update($item, $selected_groups, $existing_groups, $relationship) {
	$add_groups = array_diff($selected_groups    , $existing_groups);
	$rem_groups = array_diff($existing_groups, $selected_groups);
	
	$add_groups   = array_unique($add_groups);
	$add_groups   = array_values($add_groups);
	$rem_groups   = array_unique($rem_groups);
	$rem_groups   = array_values($rem_groups);
	$guid_two     = $item->getguid();
	
	foreach($add_groups as $group){
		$guid_one = $group;
		if(!check_entity_relationship($guid_one, $relationship, $guid_two)){
			add_entity_relationship($guid_one, $relationship, $guid_two);
		}		
	}
	foreach($rem_groups as $group){
		$guid_one = $group;
		remove_entity_relationship($guid_one, $relationship, $guid_two);
	}
}

if ($element_type != 'item'){
	// remove empty elements
	$selected_groups = array_diff($selected_groups , array("0"));
	
	$existing_groups = elgg_get_entities_from_relationship(array(
			'relationship'         => $relationship,
			'relationship_guid'    => $item_guid,
			'inverse_relationship' => true,
			'relationship_join_on' => 'guid',
	));
	
	if ($existing_groups){
		foreach($existing_groups as $group){
			$existing_groups[] = $group->getguid();
		}
		if ($item_type == 'transfer'){
			// Give the transfer merchant value the merchant name in case merchant link is detached.
			$item->merchant = $group->name;
		}
	}
	
	groups_collections_update($item, $selected_groups, $existing_groups, $relationship);
	
	if ($group_subtype == 'Merchant'){
		// Make Merchant a supplier for the item being purchased
		$existing_groups = elgg_get_entities_from_relationship(array(
				'relationship'         => 'supplier_of',
				'relationship_guid'    => $container_guid,
				'inverse_relationship' => false,
				//'inverse_relationship' => true,
				'relationship_join_on' => 'guid',
		));
		
		if ($existing_groups){
			foreach($existing_groups as $group){
				$existing_groups[] = $group->getguid();
			}
			if ($item_type == 'transfer'){
				// Give the transfer merchant value the merchant name in case merchant link is detached.
				$item->merchant = $group->name;
//			register_error('$item_type == transfer');
			}
				
		}
		
		groups_collections_update($container, $selected_groups, $existing_groups, 'supplier_of');
	
	}
}
// $element_type = 'item'
else { 

/* selectors on edit.php
	$receipt_items = elgg_get_entities_from_relationship(array(
			'type'                 => 'object',
			'relationship'         => 'receipt_item',
			'relationship_guid'    => $transfer_guid,
			'inverse_relationship' => true,
			'limit'                => false,
			'order_by_metadata'    => array('name' => 'sort_order',
					'direction' => ASC,
					'as' => 'integer'),
	));
        $linked_item = elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'relationship' => 'receipt_item',
			'relationship_guid' => $item->guid,
			'inverse_relationship' => true,
			'limit' => false,
		));
*/	
	
	if ($container_type == 'transfer'){ // Pick started from an unsaved line item 
		// Count the existing line items to determine sort order value
		$receipt_items = elgg_get_entities_from_relationship(array(
				'type'                 => 'object',
				'relationship'         => 'receipt_item',
				'relationship_guid'    => $container_guid,
				'inverse_relationship' => true,
				'limit'                => false,
		));
		$line_items = count($receipt_items);
		
		// Define a new line item
		$line_item                    = new ElggObject();
		$line_item->subtype           = $relationship;
		$line_item->qty               = 1;
		$line_item->sort_order        = $line_items + 1;

		// Save the new line item
		$line_item->save();
		
		$guid_one   = $item_guid;            // item
		$guid_two   = $line_item->getGUID(); // receipt item
		$guid_three = $container_guid;       // transfer item
	}
	if ($container_type == 'receipt_item'){ // Pick started from a saved line item
		$line_item                   = get_entity($container_guid);
		$guid_one                    = $item_guid;         // item 
		$guid_two                    = $container_guid;	// receipt item
		$linked_items                = elgg_get_entities_from_relationship(array(
											'type'                 => 'object',
											'relationship'         => 'receipt_item',
											'relationship_guid'    => $container_guid,
											'inverse_relationship' => true,
											'limit'                => false,
		));
		foreach ($linked_items as $linked_item){
			if ($linked_item['guid'] != $item_guid){
				remove_entity_relationship ($linked_item['guid'], $relationship, $guid_two);
			}
		}
		
	}
// 		register_error('$guid_one: '.$guid_one);
// 		register_error('$relationship: '.$relationship);
// 		register_error('$guid_two: '.$guid_two);
// 		register_error('$guid_three: '.$guid_three);
	
		// Populate common attributes
		$line_item->title             = $item->title;
		$line_item->timeline_label    = $timeline_label;
		$line_item->show_on_timeline  = $show_on_timeline;
		$line_item->retain_line_label = $retain_line_label;
		$line_item->add_cost_to_que   = $add_cost_to_que;
		$line_item->que_contribution  = $que_contribution;
		$line_item->distribute_freight=	$distribute_freight;
		$line_item->unpack            =	$unpack;
		$line_item->link_type         = $link_type;
		$line_item->item_guid         = $item_guid;

		// Save the line item
		$line_item->save();

		// Create relationships
		if(check_entity_relationship($guid_one, $relationship, $guid_two) == false){
			add_entity_relationship($guid_one, $relationship, $guid_two);
		}
		if(check_entity_relationship($guid_two, $relationship, $guid_three) == false && !empty($guid_three)){
			add_entity_relationship($guid_two, $relationship, $guid_three);
		}
}
// forward(REFERER);
 register_error($display);
