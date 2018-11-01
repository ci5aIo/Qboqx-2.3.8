<!--View: market/views/default/forms/market/edit/library.php-->
<?php
$guid = $vars['guid'];
$entity = get_entity($guid);
$category = $entity->marketcategory;
$access_id = $entity['access_id']; 
$entity_owner = get_entity($entity->owner_guid);
$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
if($documents){
	foreach($documents as $document){
		$document_guids[]=$document->guid;
	}
}
//echo elgg_dump($documents);
?>
<style>
div.scrollimage {
    background-color: #E9EAED;
    overflow: auto;
    white-space: nowrap;
}
div.edit-panel {
	max-width:600px;
}
div.edit_library a {
    display: inline-block;
    text-align: center;
    padding: 4px;
    text-decoration: none;
}
div.add_to_library a {
    display: inline-block;
    text-align: center;
    padding: 4px;
    text-decoration: none;
}

span.hoverhelp {background: #F0F0EE;}

img.normal {
    height: auto;
}
img.big {
    height: 120px;
}
input.w100 {
    width: 100px;
}
input.w50 {
    width: 50px;
}
#add_panel_ignore_me{
	display: none;
}
#edit_tab, #add_tab {
    padding: 0px;
    text-align: left;
    background-color: #e5eecc;
    border: solid 1px #c3c3c3;
}

</style>
<?php
$edit_panel .= "<div id='edit_panel' class='rTable' style='width:100%'>
		<div class='rTableBody'>";

//$item_file_guids = $entity->files; 
if (!is_array($item_file_guids)){$item_file_guids = array($item_file_guids);}
foreach ($item_file_guids as $key=>$file_guid){                               $display .= '83 ['.$key.'] => '.$file_guid.'<br>';
	if ($file_guid == ''){                     
		unset($item_file_guids[$key]);
	}
}

if (empty($item_image_guids)){$item_image_guids[]=$entity->guid;              $display .= '89 empty $item_image_guids<br>';
	}

   $item_images = elgg_get_entities(array(
		'type' => 'object',
		'subtypes' => array('file', 'document'),
		'guids'   => $item_file_guids,
   		'limit'   => 0,
	));

   $thumbnails = "<div class='edit_library' style='margin:0 auto;'>";
   $default_file = $entity->default_file ?: $entity->guid;
   
				
	foreach ($item_file_guids as $key=>$file_guid){                      //$display .= 'Files<br>';
//	foreach ($item_files as $file){                      //$display .= 'Files<br>';
		
		$thumbnail = elgg_view('market/thumbnail', array('marketguid' => $file_guid,
														 'size'       => 'medium',
													));
		if ($file_guid == $default_file){
			$thumbnail   = "<span title='default image'>$thumbnail</span>";
			$input_checkbox = '';
			$input_radio = "<span title='default image'><input type='radio' checked='checked' name='item[default_file]' value='$image_guid'></span>";
		}
		else {
			$input_checkbox = elgg_view('input/checkbox', array('id'   => $file_guid,
					                                            'name' => 'unlink[]', 
										 					    'value'=> $file_guid,
					                                            'default' => false,
															));
			$input_checkbox = "<span title='remove file from this library'>".$input_checkbox."</span>";
			$input_radio    = "<span title='set as default file'><input type='radio' name='item[default_file]' value='$file_guid'></span>";
		}
		$input_files = elgg_view('input/hidden', array('name'=>'attach_guids[]', 'value' => $file_guid));
		//$thumbnail = "$input_checkbox.$input_radio.$thumbnail";
		$options = [
			'text' => $thumbnail, 
			'class' => 'elgg-lightbox',
		    'data-colorbox-opts' => json_encode(['width'=>500, 'height'=>525]),
            'href' => "market/viewimage/$file_guid",];
		if ($file_guid == $default_file){
	   		$options['style'] = 'background-color: #e5eecc';
	   		}		
		
		$thumbnail = elgg_view('output/url', $options);					
					
		$thumbnails .= $thumbnail.$input_files.$input_radio.$input_checkbox;
	}
$thumbnails = $thumbnails."</div>";

$edit_panel .=  "<div class='rTableRow'>
				<div class='rTableCell' style='padding:0px 5px'>$thumbnails</div>
			</div>";

$edit_panel .=  "		</div>
	</div>";

//$form_body .= $edit_panel;
/*********/
	$add_panel .= "<div class='add_to_library' style='margin:0 10px;'>
        		<p>&nbsp;</p>
        		<h3>New Documents</h3>";
/*
 * Forked from mod\jot\views\default\forms\jot\attach2.php
 */

        $access_id      = elgg_extract('access_id', $vars, ACCESS_PUBLIC);
        $element_type   = get_input('element_type');
        $container_guid = $guid;
        $submit_label   = elgg_echo('attach');
        $owner          = elgg_get_page_owner_entity();
        $owner_guid     = $owner->guid;
        if (!$owner_guid) {
        	$owner_guid = elgg_get_logged_in_user_guid();
        }
        $input_form = elgg_view('input/dropzone', array('name' => 'attach_guids',
        									'max' => 25,
        									'multiple' => true,
        		                            'style' => 'padding:0;',
        									'container_guid' => $container_guid,
        									'subtype' => 'file',));
        
        $files = elgg_get_entities(array('type'=>'object','subtype'=>'file', 'owner_guid' => $owner_guid, 'limit'=>0 ));
        
        $add_panel .= elgg_view('input/hidden', array('name' => 'element_type'  , 'value' => $element_type));
        $add_panel .= elgg_view('input/hidden', array('name' => 'access_id'     , 'value' => $access_id));
        $add_panel .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
        $add_panel .= elgg_view('input/hidden', array('name' => 'owner_guid'    , 'value' => $owner_guid));
        
        $add_panel .= $input_form;
        $add_panel .= '</div>';
/*********/
$collections = elgg_get_entities(array('type'=>'object','subtype'=>'file', 'owner_guid' => $entity_owner->guid, 'limit'=>0 ));

if ($collections) {
	usort($collections, function($a, $b) {
	//	return strnatcmp(strtolower($a->string), strtolower($b->string));
		return strnatcmp(strtolower($a->title), strtolower($b->title));
	});
	$body = '<div class="row clearfix">
        		<h3>Linked Documents</h3>';
		foreach ($collections as $key=>$collection) {
			if (empty($collection->title)){
				unset($collections[$key]);
				continue;
			}
			if (in_array($collection->guid, $document_guids)) {
				$current_labels = array(
					'id' => 'rdoc_' . $collection->guid,
					'name' => 'attach_guids[]',
					'value' => $collection->guid,
					'checked' => 'checked',
					'default' => false,
				);
		
	//			$current_labels['checked'] = 'checked';
				$body .= "<div class=\"elgg-col elgg-col-1of2\">";
				$body .= elgg_view('input/checkbox', $current_labels);
	//			$body .= "<label for=\"rtag{$collection->id}\">" . $collection->string . "</label>";
				$body .= "<label for=\"rdoc_$collection->guid\">" . $collection->title . "</label>";
				$body .= "</div>";
			}
		}
		
	$body .= "</div>";
}
$body .= $add_panel;
if ($collections) {
	$body .= '<div class="row clearfix">
        		<h3>Unlinked Documents</h3>';
	foreach ($collections as $collection) {

		//if (!in_array($collection->string, $itemcollections)) {
		if (!in_array($collection->guid, $document_guids)) {
		$checkbox_options = array(
			'id' => 'rdoc_' . $collection->guid,
			'name' => 'attach_guids[]',
			//'value' => $collection->string,
			'value' => $collection->guid,
			'default' => false
		);

//		if (in_array($collection->string, $itemcollections)) {
//			$checkbox_options['checked'] = 'checked';
//		}
		
		$body .= "<div class=\"elgg-col elgg-col-1of2\">";
		$body .= elgg_view('input/checkbox', $checkbox_options);
		//$body .= "<label for=\"rtag{$collection->id}\">" . $collection->string . "</label>";
		$body .= "<label for=\"rdoc_$collection->guid\">" . $collection->title . "</label>";
		$body .= "</div>";
	}
	}
	$body .= '</div><br>';
}
$form_body .= $body;

echo $form_body;
//echo $display;