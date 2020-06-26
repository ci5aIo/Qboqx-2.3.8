<?php
/**
 * Pseudocode:
    * Receive 'jot' input
    * Determine subtype and aspect of input
    * Determine cid of input boqx
    * Cycle through input to extract contents
        * Identify loose things
        * Identify receipts
        * Extract loose things
        * Extract receipts
        * Create a new transfer object
        * Cycle through loose things
        * Create a new thing object for each loose thing
 */
// Get input data
$guid           = (int) get_input('guid'); if ($guid == 0 ) unset($guid);
$parent_guid    = (int) get_input('parent_guid');
$apply          =       get_input('apply');                           //$display .= '$apply: '.$apply.'<br>';goto eof;
$title          =       get_input('title');
$aspect         =       get_input('aspect');                                            $display .= '21 $aspect: '.$aspect.'<br>'; //goto eof;
$action         =       get_input('action');
$this_section   =       get_input('this_section');
// Receive jot data
$jot_input      =      get_input('jot');
$jot            =      get_input('jot');                                               //elgg_dump($jot);
$jot_snapshot   =      $jot['snapshot'];      unset($jot['snapshot']); 
$subtype        =      $jot['subtype'];
$guid           =      $guid   ?: $jot['guid'];                                         $display .= '28 $guid: '.$guid.'<br>';
$aspect         =      $aspect ?: $jot['aspect'];                                       $display .= '29 $aspect: '.$aspect.'<br>';
$now            =      new DateTime(null, new DateTimeZone('America/Chicago'));
$title          =      $jot['title']          ?: $subtype.'_'.$now->format('Y-m-d_H:i:s');;
$description    =      $jot['description'];
$container_guid =      $jot['container_guid'] ?: elgg_get_logged_in_user_guid();        $display.='33 $container_guid = '.$container_guid.'<br>';
$owner_guid     =      $jot['owner_guid']     ?: elgg_get_logged_in_user_guid();        $display.='34 $owner_guid = '.$owner_guid.'<br>';
$access_id      =      get_default_access();
$exists         =      elgg_entity_exists($guid);
// Set defaults
$moment         =      $jot['moment']         ?: $now;                                  $display .= '39 $moment = '.$moment->format('Y-m-d').'<br>';
$cid            =      $jot['cid'];                                                     $display .= '40 $cid = '.$cid.'<br>';                            //the root cid
$boqx_has_title =      false;

$containers        = array();
$boqxes            = array();
$aspects           = array();
$boqx_attributes   = array_fill_keys(['cid','boqx','aspect','fill_level','sort_order', 'unpack', 'blank_label','proximity'],'');
$entity_properties = array_fill_keys(['guid','container_guid','site_guid','owner_guid','type','subtype','access_id'],'');
$object_attributes = array_fill_keys(['guid','title','description'],'');
$access_controls   = ['ACCESS_PRIVATE'=>0,'ACCESS_LOGGED_IN'=>1,'ACCESS_PUBLIC'=>2,'ACCESS_FRIENDS'=>-2];
//Identify the boqxes
if (is_array($jot))
    foreach($jot as $key=>$value)
        if (is_array($value) && !empty($value['cid']) && !in_array($value['cid'],$boqxes))
            $boqxes[] = array_intersect_key($value, $boqx_attributes);                  
// Display boqx contents
foreach($jot as $key=>$value){
	if (empty($value)) continue;                                                        $display .= '57 jot['.$key.'] = '.$value.'<br>';
	if (is_array($value) && count($value)>0){
	    foreach($value as $key1=>$value1){
	        if (empty($value1)) continue;                                               $display .= '60 jot['.$key.']['.$key1.'] = '.$value1.'<br>';
    			if (is_array($value1) && count($value1)>0){
				foreach($value1 as $key2=>$value2){
				    if (empty($value2)) continue;                                       $display .= '63 jot['.$key.']['.$key1.']['.$key2.'] = '.$value2.'<br>';
					if(is_array($value2) && count($value2)>0){
						foreach($value2 as $key3=>$value3){
						    if (empty($value3)) continue;                               $display .= '66 jot['.$key.']['.$key1.']['.$key2.']['.$key3.'] = '.$value3.'<br>';
							if (is_array($value3) && count($value3)>0){
								foreach($value3 as $key4=>$value4){
								    if (empty($value4)) continue;                       $display .= '69 jot['.$key.']['.$key1.']['.$key2.']['.$key3.']['.$key4.'] = '.$value4.'<br>';
									if (is_array($value4) && count($value4)>0){
										foreach($value4 as $key5=>$value5){  
										    if (empty($value5)) continue;               $display .= '72 jot['.$key.']['.$key1.']['.$key2.']['.$key3.']['.$key4.']['.$key5.'] = '.$value5.'<br>';
}}}}}}}}}}}
//                                                                                        $display .= '$boqx_attributes: '.print_r($boqx_attributes, true);$display .= '$boqxes: '.print_r($boqxes, true);
                                                                                        $display .= '$boqxes: '.print_r($boqxes, true);
										    
/* Boqxes without labels are 'Invisible' and do not become objects. 
 * Boqxes accumulate under their labels
 * 
 * The contents of invisible boqxes live in the outermost visible boqx.  
 */

$boqx = (object) $jot[$cid];
    
Switch ($boqx->aspect){
    case 'things'    : $contents_aspect ='qim'     ; break;
    case 'receipts'  : $contents_aspect ='receipt'    ; break; 
    case 'receipt'   :
    case 'experience':
    case 'project'   :
    case 'issue'     : $contents_aspect =$boqx->aspect; break;
    default          : $contents_aspect =$boqx->aspect; break;
}

foreach($boqxes as $key=>$element){
    unset($container_id);
    $container_id=$element['cid'];                                                                 $display .= '96 $container_id = '.$container_id.'<br>';
// Since we're here, let's clean up some Dimensions ...
    if(is_array($jot[$container_id]['characteristic_names'])){
        foreach($jot[$container_id]['characteristic_names'] as $key1=>$value)
    		if ($value == ''){
    			unset($jot[$container_id]['characteristic_names'][$key1]);
    			unset($jot[$container_id]['characteristic_values'][$key1]);}
    	if(count($jot[$container_id]['characteristic_names'])==0){
            unset($jot[$container_id]['characteristic_names'], $jot[$container_id]['characteristic_values']);}}
    if(is_array($jot[$container_id]['this_characteristic_names'])){
        foreach($jot[$container_id]['this_characteristic_names'] as $key1=>$value)
			if ($value == ''){
				unset($jot[$container_id]['this_characteristic_names'][$key1]);
				unset($jot[$container_id]['this_characteristic_values'][$key1]);}
		if(count($jot[$container_id]['this_characteristic_names'])==0){
            unset($jot[$container_id]['this_characteristic_names'], $jot[$container_id]['this_characteristic_values']);}}
    if(is_array($jot[$container_id]['features'])){
        foreach($jot[$container_id]['features'] as $key1=>$value)
			if ($value == '')
				unset($jot[$container_id]['features'][$key1]);
        if(count($jot[$container_id]['features'])==0)
           unset($jot[$container_id]['features']);}
    if(is_array($jot[$container_id]['this_features'])){
        foreach($jot[$container_id]['this_features'] as $key1=>$value)
			if ($value == '')
				unset($jot[$container_id]['this_features'][$key1]);
        if(count($jot[$container_id]['this_features'])==0)
           unset($jot[$container_id]['this_features']);}
    if(is_array($jot[$container_id]['labels'])){
        foreach($jot[$container_id]['labels'] as $key1=>$value){
			if ($value == ''){
				unset($jot[$container_id]['labels'][$key1]);}}
        if(count($jot[$container_id]['labels'])==0)
           unset($jot[$container_id]['labels']);}
    if(is_array($jot[$container_id]['images'])){
        $jot[$container_id]['images'] = array_unique($jot[$container_id]['images']);
        foreach($jot[$container_id]['images'] as $key1=>$value){
			if ($value == ''){
				unset($jot[$container_id]['images'][$key1]);}}
        if(count($jot[$container_id]['images'])==0)
           unset($jot[$container_id]['images']);}
    if(is_array($jot[$container_id]['actions'])){
        foreach($jot[$container_id]['actions'] as $key1=>$value){
			if ($value == ''){
				unset($jot[$container_id]['actions'][$key1]);}}
        if(count($jot[$container_id]['actions'])==0)
           unset($jot[$container_id]['actions']);}
    if(is_array($jot[$container_id]['merchant'])){
        $merchant_id = $jot[$container_id]['merchant'][0];
        if(elgg_entity_exists($merchant_id)){
            $jot[$container_id]['merchant_guid'] = $merchant_id;
            $jot[$container_id]['merchant'] = get_entity($merchant_id)->title;}
        else unset($jot[$container_id]['merchant']);}
        
/**M*E*A*T**/
    $aspects[$key]  = $jot[$container_id];
}                                                                                                       $display .= '144 $aspects: '.print_r($aspects,true);
//goto eof;        

// Iterate over $aspects to provide guid values to new relationships and entity containers
for ($x = 0; $x <= 3; $x++) {                                                                           $display .= 'Loop: '.$x.'<br>';
    foreach($aspects as $key=>$element){
        unset($container);
        // separate the boqx attributes from the element
        $this_boqx     = array_intersect_key($element, $boqx_attributes);                              // boqx attributes
        $this_element  = array_diff_key($element, $boqx_attributes);                                   // boqx contents
        $container     = $aspects[array_search($this_boqx['boqx'], array_column($aspects, 'cid'))];    // boqx container
        $proximity     = $this_boqx['proximity'];                                                      // relationship between the boqx and the container
        if    (elgg_entity_exists($this_element['guid'])){                                             // test for existence
           $this_entity                  = get_entity($this_element['guid']);                          // existing entity
           $aspects[$key]['ui_response'] = 'update';
        }
        elseif(!empty($this_element['title'])){
           $this_entity             = new ElggObject();                                                // new entity
           $this_entity->subtype    = $this_boqx['aspect'];                                            // entity subtype = boqx aspect
           $this_entity->owner_guid = $owner_guid;                                                     // entity owner
           $this_entity->access_id  = 'ACCESS_LOGGED_IN';
           $aspects[$key]['ui_response'] = 'create';
        }
        else continue;                                                                                 // return to the top of the loop if there is neither an existing entity nor a title value.  This means that the boqx is invisible.
        foreach($this_element as $element_attribute=>$element_value)                                   // cycle through the boqx contents
            $this_entity->$element_attribute = $element_value;                                         // store values for each attribute.  Saves automagically if the entity exists.
        if($proximity =='in' && !empty($container['guid']))
              $this_entity->container_guid = $container['guid'];                                       // assign the container to the entity if the proximity is 'in'
        $this_entity->save();                                                                          // save explicitly 
        $aspects[$key][$element]['guid'] = $this_entity->getGUID();                                    // store the entity guid in the current aspect array
        if(($proximity =='on' || $proximity =='with') && !empty($container['guid']))                   // test for proximity and the existence of the container
             if(!check_entity_relationship($this_entity->getGUID(), $proximity, $container['guid'])){       // test for an existing relationship
                 add_entity_relationship($this_entity->getGUID(),   $proximity, $container['guid']);        // associate the boqx with the container if the container exists and the relationship does not exist
        }
        $aspects[$key]['guid'] = $this_entity->getGUID();                                                   // store the entity guid in the 
    }
    $response = $aspects;
}                                                                                                      // $display .= '$aspects: '.print_r($aspects,true);
return elgg_ok_response(json_encode($response), '');

eof:
register_error($display);
