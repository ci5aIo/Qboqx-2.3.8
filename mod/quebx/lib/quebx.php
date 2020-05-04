<?php
/**
 * Adapted from Pages function library
 */

/**
 * Prepare the add/edit form variables
 * Experimental - Altered pages_prepare_form_vars() for QuebX
 *
 * @param ElggObject $page
 * @return array
 */

//namespace quebx;
 
 function quebx_prepare_form_vars($item = null, $parent_guid = 0) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'write_access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $item,
		'parent_guid' => $parent_guid,
	);

	if ($item) {
		foreach (array_keys($values) as $field) {
			if (isset($item->$field)) {
				$values[$field] = $item->$field;
			}
		}
	}

	if (elgg_is_sticky_form('quebx')) {
		$sticky_values = elgg_get_sticky_values('quebx');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('quebx');

	return $values;
}
/**
 * Get first level subcategories for a given container
 * 
 * @param int $container_guid Container GUID or an array of container GUIDs
 * @param array $params Additional parameters to be passed to the getter function
 * @return array Array of categories
 */
function quebx_get_subcategories($container_guid = null, $params = array()) {

	$defaults = array(
		'types' => 'object',
		'subtypes' => HYPECATEGORIES_SUBTYPE,
//		'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC', 'as' => 'integer'),
		'limit' => 9999
	);

	$params = array_merge($defaults, $params);

	if (!$container_guid) {
		$site = elgg_get_site_entity();
		$container_guid = $site->guid;
	}

	$params['container_guids'] = $container_guid;

	//return elgg_get_entities($params);
	return elgg_get_entities_from_metadata($params);
}
function quebx_get_object_by_title($subtype, $title)
{
    global $CONFIG;
    
    $query ="SELECT e.* from {$CONFIG->dbprefix}entities e ".
            "JOIN {$CONFIG->dbprefix}objects_entity o ON e.guid=o.guid ".
            "WHERE e.subtype={$subtype} ".
            "AND o.title=\"{$title}\" ".
            "LIMIT 0, 1";
    $row = get_data_row($query);
    if ($row)
        return new ElggObject($row);
    else
        return false;
}

function quebx_create_group($options){
    
    	$group = new ElggGroup($options['group_guid']); // load if present, if not create a new group
    	if ($options['group_guid'] && !$group->canEdit()) {
    		register_error(elgg_echo("groups:cantedit"));
    		return true;
    	}
    	
    	$group->name    = $options['group_name'];
    	$group->subtype = $options['group_type'];
    	$group->aspect  = $options['aspect'];
    	
        Switch ($options['aspect']) {
        	case 'support':
        		$relationship = 'support_group_of';
        		break;
        	case 'supplier':
        		$relationship = 'supplier_of';
        		break;
        }
    	
    	// Set group tool options
    	$tool_options = elgg_get_config('group_tool_options');
    	if ($tool_options) {
    		foreach ($tool_options as $group_option) {
    			$option_toggle_name = $group_option->name . "_enable";
    			$option_default = $group_option->default_on ? 'yes' : 'no';
    			$group->$option_toggle_name = $option_toggle_name ?: $option_default;
    		}
    	}
    	
    	// Group membership - should these be treated with same constants as access permissions?
    	$is_public_membership = ($options['membership']) == ACCESS_PUBLIC;
    	$group->membership = $is_public_membership ? ACCESS_PUBLIC : ACCESS_PRIVATE;
    	
    	$group->setContentAccessMode(get_input('content_access_mode'));
    	
    	$group->save();
        $group_guid = $group->getguid();
        $owner_guid = (int) $options['owner_guid'] ?: elgg_get_logged_in_user_guid();

		elgg_create_river_item(array(
			'view' => 'river/group/create',
			'action_type' => 'create',
			'subject_guid' => $owner_guid,
			'object_guid' => $group_guid,
		));
		return $group;
}
/* function quebx_buildTree
 * @param array $elements
 * @param array $options['parent_id_column_name', 'children_key_name', 'id_column_name'] 
 * @param int $parentId
 * @return array
 * 
 * The algorithm is pretty simple:
   1. Take the array of all elements and the id of the current parent (initially 0/nothing/null/whatever).
   2. Loop through all elements.
   3. If the parent_id of an element matches the current parent id you got in 1., the element is a 
      child of the parent. Put it in your list of current children (here: $branch).
   4. Call the function recursively with the id of the element you have just identified in 3., 
      i.e. find all children of that element, and add them as children element.
   5. Return your list of found children.
 * One execution of this function returns a list of elements which are children of the given parent id. 
   Call it with quebx_buildTree($myArray, 1), it will return a list of elements which have the parent id 1. 
 * Initially this function is called with the parent id being 0, so elements without parent id are returned, 
   which are root nodes. The function calls itself recursively to find children of children.
 * source: https://stackoverflow.com/a/8587437/476
 * 
 * @Note 2018-02-24 - SAJ - List option not working as expected
 */

function quebx_buildTree(array $elements, 
		                       $parentId = 0, 
		                       $return   = 'array',
		                       $level    = 1,
		                       $listing)
    {
    $branch = array();
    foreach ($elements as $element) {
    	if ($element['container_guid'] == $parentId) {
    		$list[$element['guid']] = $element['title'];
    		$children = quebx_buildTree($elements, $element['guid'], $return, ++$level);
		    if ($children) {
		    	$element['contents'] = $children;
		    	$list[$element['guid']]['contents'] = $children;
		    }
		    $level = 1;
		    $branch[] = $element;
		}
	}
    Switch ($return){
    	case 'list':                //@DRAFT 2018-02-24 - SAJ - List option not working as expected
    		return $list;
    	case 'array':
    		return $branch;
    }
    
}
/*
 *  $data, $index, $parent_id, $ul_class, $li_class, $collapsible, $collapse_level
 * 
 */
function quebx_display_child_nodes($options){
	$data           = $options['data'];
	$index          = $options['index'];
	$parent_id      = $options['parent_id'];
	$ul_class       = $options['ul_class'];
	$li_class       = $options['li_class'];
	$collapsible    = $options['collapsible'];
	$collapse_level = $options['collapse_level'];
	$level          = $options['level'];
	$links          = $options['links'];
	static $children;
	$toggle_children = elgg_view('output/url',['text'=>'+',
	            		                           'title'=>'expand',
	            		                           'class'=>'elgg-button-submit-element hierarchy-expand-button']);
	if (isset($ul_class)){$list_class = "class = $ul_class";}
	if ($collapsible && $level >= $collapse_level){
		$state = "data-state = 'collapsed'";}
	if (isset($li_class)){$list_item_class = "class = $li_class";}
    if (isset($index[$parent_id])) {
    	$children .= "<ul $list_class data-level=$level $state>";
        foreach ($index[$parent_id] as $id) {
        	unset($child_toggle, $has_children);
        	$has_children = count($index[$id])>0;
        	if ($collapsible && $has_children){
        		$child_toggle    = $toggle_children;}
        	if ($collapsible && !$has_children){
        		if (isset($li_class)){
        			$list_item_class = "class = $li_class node-no-children";}
        		else {$list_item_class = "class = node-no-children";}
        	}
        	$title    = $data[$id]['title'];
        	$item_name = $links ? 
        	             elgg_view('output/url',['text'=>$title,'href'=>get_entity_url($id)]) : 
        	             $title;
            $children .= "<li $list_item_class>$child_toggle $item_name";
            $options['parent_id'] = $id;
            $options['level']     = ++$level;
            quebx_display_child_nodes($options);
            $level = 0;
        }
        $children .= "</li></ul>";
    }
    return $children;
}
function quebx_display_child_nodes_II($options){
	$data           = $options['data'];
	$index          = $options['index'];
	$parent_id      = $options['parent_id'];
	$ul_class       = $options['ul_class'];
	$li_class       = $options['li_class'];
	$collapsible    = $options['collapsible'];
	$collapse_level = $options['collapse_level'];
	$level          = $options['level'];
	$links          = $options['links'];
	$presentation   = $options['presentation'];
	$presence       = $options['presence'];
	static $children;
	$toggle_children = elgg_view('output/url',['text'=>'+',
	            		                           'title'=>'expand',
	            		                           'class'=>'elgg-button-submit-element hierarchy-expand-button']);
	if (isset($ul_class)){$list_class = "class = $ul_class";}
	if ($collapsible && $level >= $collapse_level){
		$state = "data-state = 'collapsed'";}
    if (isset($index[$parent_id])) {
    	$children .= "<ul $list_class data-level=$level $state>";
        foreach ($index[$parent_id] as $id) {
        	unset($child_toggle, $has_children,$list_item_class);
        	if (isset($li_class)){
        	    $list_item_class[] = $li_class;
        	    $list_item_classes = $li_class;
        	}
        	$has_children           = count($index[$id])>0;
        	if ($collapsible && $has_children)
        		$child_toggle       = $toggle_children;
        	if ($collapsible && !$has_children){
        		$list_item_class[] = 'node-no-children';
        		$list_item_classes.= ' node-no-children';
        	}
    		else{
    		    $list_item_class[] = "node-children";
    		    $list_item_classes.= " node-children";
    		}
        	$title = $data[$id]['title'];
        	$link  = elgg_view('output/url',['text'=>$title,'href'=>get_entity_url($id)]);
        	$item  = $links ? $link  : $title;
//            $children .= elgg_format_element('li',['class'=>$list_item_class],$child_toggle.$item_name);
            $children .= "<li class=".$list_item_classes.">$child_toggle $item";
            $options['parent_id'] = $id;
            $options['level']     = ++$level;
            quebx_display_child_nodes_II($options);
            $level = 0;
        }
        $children .= "</li></ul>";
    }
    return $children;
}
function quebx_display_child_nodes_III($options){
	$data           = $options['data'];
	$index          = $options['index'];
	$parent_id      = $options['parent_id'];
	$parent_cid     = $options['parent_cid'];
	$ul_class       = $options['ul_class'];
	$li_class       = $options['li_class'];
	$collapsible    = $options['collapsible'];
	$collapse_level = $options['collapse_level'];
	$level          = $options['level'];
	$links          = $options['links'];
	$presentation   = $options['presentation'];
	$presence       = $options['presence'];
	$display        = (array) $options['display'];
	$boqx_id        = elgg_extract('cid', $options, quebx_new_id('c'));
	$aspect         = elgg_extract('aspect', $options);
	$fill_level     = elgg_extract('fill_level',$options, 0);
	static $children;
	if (isset($ul_class)){$list_class = "$ul_class";}
	if ($collapsible && $level >= $collapse_level){
		$state = "data-state = 'collapsed'";}
    if (isset($index[$parent_id])) {
    	$children .= "<ul id=$boqx_id class=$list_class data-boqx=$parent_cid data-level=$level $state>";
        foreach ($index[$parent_id] as $id) {
        	unset($child_toggle, $has_children,$list_item_class, $list_item_classes,$entity,$options['cid']);
        	$cid = quebx_new_id('c');
        	$child_id = quebx_new_id('c');
        	$edit_id =  quebx_new_id('c');
        	$essence = 'unrealized';
        	if (isset($li_class)){
        	    $list_item_class[] = $li_class;
        	    $list_item_classes = $li_class;
        	}
        	$has_children           = count($index[$id])>0;
        	if ($collapsible && $has_children)
        		$child_toggle       = elgg_format_element('span',['class'=>['contentsToggle','contentsExpand_Vs2YepGp'], 'title'=>'expand','data-boqx'=>$cid, 'data-target'=>$child_id],count($index[$id]));
        	if ($collapsible && $has_children){
    		    $list_item_class[] = "node-children";
    		    $list_item_classes.= " node-children";
        	}
//     		else{$list_item_class[] = 'node-no-children';
//         		$list_item_classes.= ' node-no-children';
//     		}
        	if(elgg_entity_exists($id)){
        	    $entity = get_entity($id);
        	    $subtype = $entity->getSubtype();
        	    $icon = elgg_view('market/thumbnail', ['marketguid' => $id, 'size' => 'tiny', 'class'=>'itemPreviewImage_ARIZlwto']);
            	$link  = elgg_view('output/url',['text'=>$title,'href'=>get_entity_url($id)]);
            	$display_options = array_merge($display,['entity'=>$entity,'aspect'=>$aspect,'boqx_id'=>$cid,'cid'=>$edit_id,'child_toggle'=>$child_toggle,'icon'=>$icon,'has_description'=>isset($entity->description),'has_attachments'=>count($attachments)>0,'has_contents'=>$has_children,'fill_level'=>$fill_level,'presentation'=>$presentation,'presence'=>$presence]);
            	if($has_children){
            	    $display_options['fill_level'] = count($index[$id]);
            	}
            	switch($subtype){
            	    case 'market':
            	    case 'item':  
            	        $display_options['task_aspect']= 'feature';
            	        $essence = 'realized';
            	        break;
            	}
            	$display_options['essence']=$essence;
            	$display_options['edit_contents'] = elgg_view('forms/market/edit',['perspective'=>'edit', 'section'=>'contents_edit_boqx','parent_cid'=>$edit_id,'guid'=>$id,'essence'=>$essence]);
            	$boqx  = elgg_view('page/components/pallet_boqx', $display_options);
        	}
        	$title = $data[$id]['title'];
        	$title = elgg_format_element('div',['class'=>'envelope__NkIZUrK4','data-aspect'=>"contents",'data-boqx'=>$cid,'data-guid'=>$id,'data-presentation'=>"contents"],
                    	 elgg_format_element('div',['class'=>"ContentsShow_iGfIgeuR",'data-aid'=>"TaskShow",'data-cid'=>$cid,'style'=>"display: flex;"],
                    		 elgg_format_element('div',['class'=>"TaskShow__description___3R_4oT7G",'data-aid'=>"TaskDescription",'tabindex'=>"0"],
                    			  elgg_format_element('span',['class'=>"TaskShow__title___O4DM7q"],
                    				  elgg_format_element('span',[],$data[$id]['title']))).
                             elgg_format_element('nav',['class'=>["ContentsShow__actions__UgfegvmW","undefined","ContentsShow__actions--unfocused__d6S4BCDN"]],
                    			 elgg_format_element('button',['class'=>["IconButton___5RN0PIS5","IconButton--small___3D375vVd"],'data-aid'=>"materialize",'aria-label'=>"Materialize",'data-cid'=>$cid],
                    				 elgg_format_element('span',['class'=>"materialize-item"],
                    					 elgg_format_element('a',['title'=>"materialize item"],
                    						 elgg_format_element('span',['class'=>["elgg-icon","fa","fa-external-link-square"]])))))));
/*        	switch($subtype){
        	    case 'market':
        	    case 'item':
        	        $present = $boqx;
        	        break;
        	    default:
        	        $present = $links ? $link : $title;
        	        break;
        	}
*/        	$item_name = $links ?  elgg_view('output/url',['text'=>$title,'href'=>get_entity_url($id)]) : $title;
        	$present = $boqx;
        	$item  = elgg_format_element('span',[],$present);
//        	$item  = elgg_format_element('span',[],$child_toggle.$present);
//        	$children .= elgg_format_element('li',['class'=>$list_item_class],$child_toggle.$item_name.$child_nodes);            
            $children .= "<li id='$cid' data-boqx=$boqx_id class=$list_item_classes>$item";
            $options['parent_id'] = $id;
            $options['parent_cid'] = $cid;
            $options['cid']       = $child_id;
            $options['level']     = ++$level;
            quebx_display_child_nodes_III($options);
            $level = 0;
        }
        $children .= "</li></ul>";
    }
    return ['contents'=>$children, 'children'=>0];
}
function quebx_new_cid (){
    return 'c'.mt_rand(1,99999);
}
function quebx_new_id ($prefix=''){
    return $prefix.mt_rand(1,99999);
}
function quebx_initials ($string){
    $words = explode(' ', $string);
    foreach($words as $word){
        $initials .= $word[0];
    }
    return $initials;
}
/**
 * Format a series of HTML elements
 * @receives         A series of identical tags identified as a single $tag_name and a series of attribute arrays 
 *
 * @param string     $tag_name   The common element tag name, e.g. "div".  Provides special treatment for "hidden" tags. 
 *                         
 * @param array      $attributes A series of element attribute arrays. Each array is passed individually to elgg_format_element().
 * 
 * @return string
 * @throws InvalidArgumentException
 */
function quebx_format_elements($tag_name, array $attributes = []){
    if (!is_string($tag_name) || $tag_name === '') {
		throw new \InvalidArgumentException('$tag_name is required');
	}
	if($tag_name == 'hidden'){
	    $tag_name = 'input';
	    $tag_type = 'hidden';
	}
    if(is_array($attributes))
        foreach($attributes as $key=>$attrs){
            $attrs['#tag_name'] = $tag_name;
            $attrs['type']      = $tag_type;
            $elements .= elgg_format_element($attrs);
        }
    else throw new \InvalidArgumentException('$attributes must be an array');
    return $elements;
}
function quebx_count_pieces($guid=false, $aspect){
    if($guid==false)
        return false;
    $page_owner     = elgg_get_page_owner_entity();
    $dbprefix       = elgg_get_config('dbprefix');
    $options = ['type'       => 'object',
                'owner_guid' => $page_owner->guid, 
                'order_by_metadata' => ['name'=>'moment',
                		                'direction'=>DESC,
                                        'as'=>date],
                'order_by'=>'time_updated',
                'limit'      => 0,];
    
    switch ($aspect){
        case 'things':
            $jot_options = $options;
            unset($jot_options['order_by_metadata']);
            $jot_options['subtype']='market';
            $jot_options['wheres'] = ["NOT EXISTS (SELECT *
                                                   FROM {$dbprefix}metadata md
                        	                       JOIN {$dbprefix}metastrings ms1 ON ms1.id = md.name_id
                        	                       JOIN {$dbprefix}metastrings ms2 ON ms2.id = md.value_id
                        	                       WHERE ms1.string = 'visibility_choices'
                        	                         AND ms2.string = 'hide_in_catalog'
                        	                         AND e.guid = md.entity_guid)"];
            return count(elgg_get_entities($jot_options));
            break;
        case 'experiences':
            $jot_options = $options;
            $jot_options['subtype'] = 'experience';
            $jot_options['wheres']  = $guid ? ["e.container_guid = $guid"] : '';
            unset($jot_options['order_by_metadata']);
            return count(elgg_get_entities_from_metadata($jot_options));
            break;
        case 'issues':
            $jot_options = ['type' => 'object',
                            'subtypes'=>'issue',
                        	'relationship' => 'on',
                        	'relationship_guid' => $guid,
                            'inverse_relationship' => true,
                        	'limit' => false,];
            return count(elgg_get_entities_from_relationship($jot_options));
            break;
        case 'transfers':
            $jot_options = $options;
            $jot_options['subtype'] = 'transfer';
            return count(elgg_get_entities_from_metadata($jot_options));
            break;
        case 'contents':
            $jot_options = $options;
            unset($jot_options['order_by_metadata']);
            $jot_options['subtypes'] = ['market', 'item', 'contents'];
            $jot_options['joins']    = ["JOIN {$dbprefix}objects_entity e2 on e.guid = e2.guid"];
            $jot_options['wheres'][] = $guid ? "e.container_guid = $guid" :'';
            $jot_options['wheres'][] = "NOT EXISTS (SELECT *
                                                     from {$dbprefix}entity_relationships s1
                                                     WHERE s1.relationship = 'component'
                                                       AND s1.guid_two = e.container_guid)";
            $jot_options['order_by']= 'e2.title';
            return count(elgg_get_entities($jot_options));
            break;
        case 'accessories':
            $jot_options = ['type' => 'object',
                        	'relationship' => 'accessory',
                        	'relationship_guid' => $guid,
                            'inverse_relationship' => true,
                        	'limit' => false,];
            return count(elgg_get_entities_from_relationship($jot_options));
            break;
        case 'components':
            $jot_options = ['type' => 'object',
                        	'relationship' => 'component',
                        	'relationship_guid' => $guid,
                            'inverse_relationship' => true,
                        	'limit' => false,];
            return count(elgg_get_entities_from_relationship($jot_options));
            break;
    }
    return false;
}