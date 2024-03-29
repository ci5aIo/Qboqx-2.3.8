<?php

/*namespace Quebx\Labels;*/
                                                                                                            $display = 'FROM: labels/views/devault/forms/labels/add.php<br>';
$user      = elgg_extract('user', $vars, elgg_get_logged_in_user_entity());
$item_guid = elgg_extract('friend', $vars);
$approve   = elgg_extract('approve', $vars);
$cid       = elgg_extract('cid', $vars);
$itemcollections = [];

if (elgg_entity_exists($item_guid)){
    $item            = get_entity($item_guid);
    $itemcollections = $item->gettags();
    if (!is_array($itemcollections)) $itemcollections = (array)$itemcollections;
}
if ($cid){
    $class_pick = 'BoqxLabelsPick--'.$cid;
    $class_show = 'BoqxLabelsShow--'.$cid;
}
else {
    $class_pick = 'BoqxLabelsPick__ThUG84u6';
    $class_show = 'BoqxLabelsShow__LFaMXDtw';
}

//get array of all label collections owned by the requesting user
$collections = get_user_label_collections($user->guid);                                                      //$display .= '25 $collections: '.print_r($collections, true).'<br>';

usort($collections, function($a, $b) {
	return strnatcmp(strtolower($a->string), strtolower($b->string));
});
$body = "<div class='row clearfix $class_show'>";
	foreach ($collections as $collection) {

		if (count($itemcollections)>0 && in_array($collection->string, $itemcollections)) {
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

if ($collections) {
	foreach ($collections as $collection) {                                                                  //$display .= '54 $collection: '.$collection->string.'<br>';
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
    	$label_options .= 
    	            elgg_format_element('div',['class'=>'SmartListSelector__child___zbvaMzth'],
    	                elgg_format_element('div', ['class'=>'LabelDropdownItem___3IFJX-oo',
    	                                            'data-scroll-id' => "LabelDropdownItem--c{$id}",
    	                                            'data-cid'=>"c{$id}",
    	                                            'data-aid' =>"LabelDropdownItem--{$collection->string}"],
    	                   elgg_format_element('label',['for'=>"rtag{$id}"],
    	                                               $collection->string
    	            )));                                                                                      //$display .= '74 $label_options: '.$label_options.'<br>';
	   }
	}
	$body = elgg_format_element('div', ['id'             =>'label_manager_labels_select',
	                                    'class'          =>"clearfix BoqxLabelsPick__ThUG84u6",
                                        'data-scrollable'=>"true"] 
	                                 , $label_options);                                                       
}
echo $body;                                                                                                    //$display .= '82 $body: '.print_r($body, true).'<br>';
//register_error($display);