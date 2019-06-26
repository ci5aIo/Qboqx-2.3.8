<?php

/*namespace Quebx\Labels;*/

$user      = elgg_extract('user', $vars, elgg_get_logged_in_user_entity());
$item_guid = elgg_extract('friend', $vars);
$approve   = elgg_extract('approve', $vars);
$cid       = elgg_extract('cid', $vars);

if (elgg_entity_exists($item_guid)){
    $item            = get_entity($item_guid);
    $itemcollections = $item->gettags();
}
if ($cid){
    $class_pick = 'BoqxLabelsPick--'.$cid;
    $class_show = 'BoqxLabelsShow--'.$cid;
}
else {
    $class_pick = 'BoqxLabelsPick__ThUG84u6';
    $class_show = 'BoqxLabelsShow__LFaMXDtw';
}
/*
$options = array(
	'tag_name'  => 'tags',
	'item_guid' => $item_guid,
);

$tag_data = get_labels($options);
*/
//get array of all label collections owned by the requesting user
$collections = get_user_label_collections($user->guid);

usort($collections, function($a, $b) {
	return strnatcmp(strtolower($a->string), strtolower($b->string));
});
if (count($itemcollections)>0){
$body = "<div class='row clearfix $class_show'>";
	foreach ($collections as $collection) {

		if (in_array($collection->string, $itemcollections)) {
			$current_labels = array(
				'id' => 'rtag' . $collection->id,
				'name' => 'existing_rtag[]',
				'value' => $collection->string,
				'checked' => 'checked',
				'default' => false,
		        'class'  => 'LabelDropdownItem-checkbox'
			);
	
//			$current_labels['checked'] = 'checked';
			$body .= "<div class=\"elgg-col elgg-col-1of3\">"
    			        .elgg_view('input/checkbox', $current_labels)
    			        ."<label for=\"rtag{$collection->id}\">" . $collection->string . "</label>
                    </div>";
		}
	}
$body .= '</div>';
}
/*$body .= elgg_view('output/longtext', array('value' => elgg_echo('labels:form:instructions'),
                                        	'class' => 'elgg-subtext'));*/	
/*$body .= 'New Labels: ';
$body .= elgg_view('input/text', array(
	'name' => 'rtags',
	'id' => 'labels_rtags',
    'placeholder'=> 'Separate, new labels, with commas'
//	'value' => implode(', ', $itemcollections),
		));
*/

if ($collections) {
	$body .= "<div class='row clearfix $class_pick'>";
	foreach ($collections as $collection) {
	    unset($checkbox_options, $id);
	    $id=quebx_new_id();
		if (!in_array($collection->string, $itemcollections)) {
		$checkbox_options = [
    			'id' => 'rtag' . $collection->id,
    			'name' => 'existing_rtag[]',
    			'value' => $collection->string,
    			'default' => false,
		        'class'  => 'LabelDropdownItem-checkbox'
			    
		];

//		if (in_array($collection->string, $itemcollections)) {
//			$checkbox_options['checked'] = 'checked';
//		}
		
		$body .= "<div class=\"elgg-col elgg-col-1of3\">
                    <div class='SmartListSelector__child___zbvaMzth'>
                        <div class='LabelDropdownItem___3IFJX-oo' data-scroll-id = 'LabelDropdownItem--c{$id}' data-cid='c{$id}' data-aid ='LabelDropdownItem--{$collection->string}'>"
//    			        .elgg_view('input/checkbox', $checkbox_options)
    			        ."<label for=\"rtag{$id}\">" . $collection->string . "</label>
                        </div>
                    </div>
                  </div>";
	}
	}
	$body .= '</div><br>';
}
echo $body;