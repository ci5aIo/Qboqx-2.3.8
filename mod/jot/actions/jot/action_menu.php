<?php
$guid_one           =           (int) get_input('guid_one', false);         //subject
$guid_two           =           (int) get_input('guid_two', false);         //target
$relationship       =                 get_input('relationship', false);
$action             =                 get_input('action');
$size               = elgg_strtolower(get_input('size','master'));

$subject            =                 get_entity($guid_one);
$target             =                 get_entity($guid_two);

$item_image_guids   =                 $target->images;                                  //get the target guids

if (!is_array($item_image_guids))                                                       //test to see if the target guids are an array
    $item_image_guids = (array)$item_image_guids;                                       //make the target guids an array
$item_image_guids     = array_unique(array_filter($item_image_guids));                  //make sure the target guid array has only uniqe values

switch($action){
    case 'set_default':
        if(elgg_entity_exists($guid_two)){
            $target->icon = $guid_one;
            $target->save();
            return elgg_ok_response(json_encode(['guid' =>$guid_one]), '');}
        break;
    case 'attach':
    	if($relationship)
        	if (!check_entity_relationship ($guid_one, $relationship, $guid_two))
        	 	add_entity_relationship($guid_one, $relationship, $guid_two);
        //Confirm that the image exists
        if       ($subject->mimetype == 'image/png')    $filename = "icons/" . $guid_one . $size . ".png";
        else if ($subject->mimetype == 'image/gif') 	$filename = "icons/" . $guid_one . $size . ".gif";
        else                                          	$filename = "icons/" . $guid_one . $size . ".jpg";
        
        $filehandler              = new ElggFile();
        $filehandler->owner_guid  = $subject->owner_guid;
        $filehandler->setFilename($filename);
        $filehandler->open('read');
        $etag                     = md5($filehandler->icontime . $size);
        $contents                 = $filehandler->grabFile();
        $filehandler->close();
        
        if ($contents){                                                                    //the subject image file exists
            $item_image_guids[]   = $guid_one;                                             //push the subject guid to the target guid array
            $item_image_guids     = array_unique(array_filter($item_image_guids));         //make sure the target guids are unique
            $target->images       = $item_image_guids;                                     //save the guids to the target object
        }
        return elgg_ok_response(json_encode($guid_one), '');
        break;    
    case 'detach':
    	if (check_entity_relationship ($guid_one, $relationship, $guid_two))
    	 	remove_entity_relationship($guid_one, $relationship, $guid_two);
    	if (check_entity_relationship ($guid_two, $relationship, $guid_one))
    		remove_entity_relationship($guid_two, $relationship, $guid_one);    	
    	if($guid_one && $subject->getSubtype() == 'hjalbumimage'){
            unset($item_image_guids[array_search($guid_one,$item_image_guids)]);
            $target->images       = $item_image_guids;
    	}
        break;    
}