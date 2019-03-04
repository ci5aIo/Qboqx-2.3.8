<!--View: market/views/default/forms/market/edit/category.php-->
<?php
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}
$guid = elgg_extract('guid', $vars, false);
$qid  = elgg_extract('qid', $vars);
$presentation = elgg_extract('presentation', $vars);
if ($guid){
    $entity             = get_entity($guid);
    $category           = $entity->marketcategory;
    $selected_category  = $category;
    if ($entity->category){$category_guid = $entity->category;}
    if (elgg_instanceof(get_entity($category_guid), 'object', HYPECATEGORIES_SUBTYPE)){
    	$category       = get_entity($category_guid);
    	$root_name      = $category->title;
    	$hierarchy      = hypeJunction\Categories\get_hierarchy($category_guid, false, true);
    	$node           = array_shift($hierarchy);
    	$root_name      = $node['title'];
    //	$root_name = implode(':', $hierarchy[]['title']); // doesn't work
    	foreach($hierarchy as $node){
    		$root_name .=' : '.$node['title'];
    	}
    }
    $access_id          = $entity['access_id']; 
    $entity_owner       = get_entity($entity->owner_guid);
    $icon               = $entity->icon ?: $guid;
    $item_image_guids   = $entity->images;
    
}
else {
    $entity_owner = elgg_get_logged_in_user_entity();
}

/*********/
$subcategories = hypeJunction\Categories\get_subcategories($parent_category_guid);
/**********/
$num_items = 16;  // 0 = Unlimited
$options = array(
	'types' => 'object',
	'subtypes' => 'market',
	'limit' => $num_items,
	'full_view' => false,
	'pagination' => true,
	'list_type' => 'list', // options are list, gallery.  Seems to not allow other list types. See views.php in core.
	'list_type_toggle' => true,
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token)
);

// $options['metadata_name'] = "marketcategory";
// $options['metadata_value'] = $selected_category;
$content = elgg_list_entities_from_metadata($options);
// get related items
$individuals = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'market',
	'metadata_name_value_pairs' => array(
		'name' => 'family_token',
		'value' => $entity->family_token
	)
));

$components = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'component',
	'container_guid' => $entity->guid
));

$accessories = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => false,
	));

$groups = elgg_get_entities_from_relationship(array(
	'type'                 => 'group',
	'relationship'         => 'shared',
	'relationship_guid'    => $entity->guid,
	'inverse_relationship' => true,
	'limit'                => false,
	'callback'             => false // because we don't need the user entity, this makes the query a bit more efficient
));
$edit_panel .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>";
if ($item_image_guids){
    if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
    foreach ($item_image_guids as $key=>$image_guid){                               $display .= '83 ['.$key.'] => '.$image_guid.'<br>';
    	if ($image_guid == ''){                     
    		unset($item_image_guids[$key]);
    	}
    }
}
if (empty($item_image_guids) && $guid){$item_image_guids[]=$entity->guid;              $display .= '89 empty $item_image_guids<br>';
	}

   $item_images = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => array('image', 'hjalbumimage'),
		'guids'   => $item_image_guids,
   		'limit'   => 0,
	));
   //$thumbnails = "<div class='edit_gallery' style='margin:0 auto;'>";

   
//if (count($item_image_guids)>0){				
if (count($item_image_guids)>0){
   foreach ($item_image_guids as $key=>$image_guid){                      //$display .= 'Images<br>';
//	foreach ($item_images as $image){                      //$display .= 'Images<br>';
		
		$thumbnail = elgg_view('market/thumbnail', array('marketguid' => $image_guid,
														 'size'       => 'medium',
													));
		if ($image_guid == $icon){
			$thumbnail   = "<span title='default image'>$thumbnail</span>";
			$input_checkbox = '';
			$input_radio = "<span title='default image'><input type='radio' checked='checked' name='item[icon]' value='$image_guid'></span>";
		}
		else {
			$input_checkbox = elgg_view('input/checkbox', array('id'   => $image_guid,
					                                            'name' => 'unlink[]', 
										 					    'value'=> $image_guid,
					                                            'default' => false,
															));
			$input_checkbox = "<span title='remove image from this gallery'>".$input_checkbox."</span>";
			$input_radio    = "<span title='set as default image'><input type='radio' name='item[icon]' value='$image_guid'></span>";
		}
		$input_images = elgg_view('input/hidden', array('name'=>'item[images][]', 'value' => $image_guid));
		//$thumbnail = "$input_checkbox.$input_radio.$thumbnail";
		$options = array(
			'text' => $thumbnail, 
			'class' => 'elgg-lightbox',
			'data-colorbox-opts' => json_encode(['width'=>500, 'height'=>525]),
		    'href' => "market/viewimage/$image_guid");
		if ($image_guid == $icon){
	   		$options['style'] = 'background-color: #e5eecc';
	   		}		
		
		$thumbnail = elgg_view('output/url', $options);					
					
		//$thumbnails .= $thumbnail.$input_images.$input_radio.$input_checkbox;
		$thumbnails .= "<li>{$thumbnail}{$input_images}{$input_radio}{$input_checkbox}</li>";
		
	}
    //$thumbnails = $thumbnails."</div>";
    $thumbnails = "<ul class='edit_gallery'>$thumbnails</ul>";
}
$edit_panel .=  "<div class='rTableRow'>
				<div class='rTableCell' style='padding:0px 5px'>$thumbnails</div>
			</div>";

$edit_panel .=  "		</div>
	</div>";
$title = elgg_view('input/text', array('name' => 'item[title]', 'value' => $entity->title, 'placeholder'=>'Title',)).elgg_view('input/hidden', array('name' => 'jot[title]', 'value' => $entity->title));
if ($allowhtml != 'yes') {
	$description  = "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	$description .= <<<HTML
<textarea name='marketbody' class='mceNoEditor' rows='8' cols='40'
  onKeyDown='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars}'
  onKeyUp='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars})'>{$entity['description']}</textarea><br />
HTML;
	$description .= "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
} else {
	$description   = elgg_view("input/longtext", array("name" => "item[description]", "value" => $entity['description'],  'placeholder'=>'Short Description',));
    $description  .= elgg_view("input/hidden",   array("name" => "jot[description]",  "value" => $entity['description']));
}

	$thumbnail = elgg_view('market/thumbnail', array('marketguid' => $entity->icon,
													   'size'       => 'medium',
													   'tu'         => $entity->time_updated,
													));
	
if ($cid){$data_cid      = "data-cid='$cid'";}
if ($qid){$data_qid      = "data-qid='$qid'";}
if ($guid)      {$data_guid     = "data-guid='$guid'";
    if (!($qid)) $data_qid = "data-qid = 'q{$guid}'";
}
$first_row = "<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Title</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>
					<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='display: flex;margin-bottom: 0;'>
						<div class='AutosizeTextarea__container___31scfkZp' style='flex-basis: 500px;'>
							$title
						</div>
						<div style='margin: 3px 3px 0 15px;float: right;order:2;'>
							<button class='autosaves button std do' $data_guid $data_qid $data_cid type='submit' tabindex='-1' data-perspective='save'>Save</button>
						</div>
					</div>
				</div>
			</div>";
if ($presentation == 'qboqx') unset($first_row);
$category_panel .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			$first_row
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;vertical-align:top;'>Description</div>
				<div class='rTableCell' style='width:80%;'>$description</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Category</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/category', array('value'=>array($entity->category), 'root_name_override' =>$root_name, 'add_leaf' => true,))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Owner</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('output/url', array('text' => $entity_owner->name,'href' =>  'profile/'.$entity_owner->username))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>
					<div id='edit_panel' class='elgg-head edit-panel'>$edit_panel</div>
				</div>
			</div>
		</div>
	</div>";

echo $category_panel;
register_error($display);
eof: