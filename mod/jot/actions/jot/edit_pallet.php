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
    case 'things'    : $contents_aspect ='q_item'     ; break;
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
goto eof;        

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
//return elgg_ok_response(json_encode($response), '');
/*********************************************/
goto eof;
// The above^ replaces the below ...
// Retain for reference
/*********************************************/

//extract the pieces from each contents container
if (!empty($contents)){
    $n=0;
    foreach($contents as $key=>$piece){
        if(array_key_exists('sort_order',$piece))
            $contents[$key]['sort_order']= ++$n;
        unset($container_id);
        $container_id = $piece['cid'];
        $nn=0;
        foreach($jot as $key1=>$value)
            if($value['boqx']==$container_id && !empty($value['title'])){
                if(array_key_exists('sort_order', $value)){
                    $value['sort_order'] = ++$nn;
                    $contents[$key]['pieces'] = $nn;
                }
                $pieces[] = $value;}}}                  $display .= '175 $boqx: '.print_r($boqx, true).'<br>';
                                                        $display .= '176 $boqxes: '.print_r($boqxes, true).'<br>';
                                                        $display .= '177 $contents: '.print_r($contents, true).'<br>';
                                                        $display .= '178 $pieces: '.print_r($pieces, true).'<br>';
/* Extract existing entities (has guid)
 * Create new entities (boqxes and contents) 
   * Place boqxes within their parents
 * Unpack indicated items
 * Link items to their boxes 
 */
$boqx_visible = true;
// Materialize the boqx
// confirm that the boqx is not empty
if (!empty($contents)){
    // retrieve the boqx if it exists
    if(elgg_entity_exists($boqx->guid)){
       $boqx_entity = get_entity($boqx->guid);
       // test to confirm that the retrieved boqx is a 'boqx' subtype
       if ($boqx_entity->getSubtype() == 'boqx')
           // merge the received boqx into the retrieved boqx
           foreach($boqx as $key=>$value)
               $boqx_entity->$key = $value;
    }
    // if the boqx does not exist
    // test to confirm that the received contents can be realized (has a label or has a blank label)
    elseif(!empty($boqx->labels) || $boqx->blank_label == true){
       // create a new boqx
       $boqx_entity                 = new ElggObject();
       $boqx_entity->container_guid = $container_guid;
       $boqx_entity->owner_guid     = $owner_guid;
       $boqx_entity->access_id      = $access_id;
       // merge the new boqx into the received boqx
       foreach($boqx as $key=>$value)
            $boqx_entity->$key = $value;
    }
    else $boqx_visible = false;
    if ($boqx_visible){                                                                                               $display .= '207 $boqx_visible = true <br>';
        // find the key for the current boqx
        $boqxes_key = array_search($boqx_entity->cid, array_column($boqxes, 'cid'));
        foreach($boqx_attributes as $key=>$attribute){
            // scan the current boqx for attributes
            if(array_key_exists($attribute, $boqx_entity)){
                // store attributes for the current boqx
                $boqxes[$boqxes_key][$attribute] = $boqx_entity->$attribute;
            }
        }
        // remove housekeeping attributes from the current boqx
        unset($boqx_entity->boqx, $boqx_entity->cid);
        $boqx = $boqx_entity;
                // save the boqx
    //            $boqx->save();
                // capture the guid attribute
    //            $boqxes[$boqxes_key]['guid'] = $boqx->getGUID();
    }
}
// Fill the boqx
Switch ($boqx->aspect){
    case 'things':
        // a 'thing' does not have contents and is not associated with an invisible boqx 
        // confirm that the boqx is not empty
        if(empty($contents)) break;
        // materialize each piece
        foreach($contents as $key=>$piece){
            unset($piece_guid, $piece_entity, $piece_obj, $piece_boqx);
            $piece_guid = $piece['guid'];
            // retrieve the thing if it exists
            if (elgg_entity_exists($piece_guid)){
                $piece_entity = get_entity($piece_guid);                                                        $display .= '221 $piece_entity[labels] = '.print_r($piece_entity['labels'], true).'<br>221 $piece_entity = '.print_r($piece_entity, true).'<br>221 $piece[labels] = '.print_r($piece['labels'], true).'<br>';
                // determine whether attribute sets have been removed from the received piece
/*                $attr_set = elgg_get_metadata(['guid'=>$piece_guid, 'metadata_name'=>'labels']);
                if ($attr_set && count($piece['labels'])==0){
                    elgg_delete_metadata(['guid'=>$piece_guid, 'metadata_name'=>'labels']);
                    unset($piece_entity['labels']);
                }
                $attr_set = elgg_get_metadata(['guid'=>$piece_guid, 'metadata_name'=>'characteristic_names']);  $display .= '228 $attr_set = '.print_r($attr_set, true).'<br>228 count($piece[characteristic_names]): '.count($piece['characteristic_names']).'<br>';
                if ($attr_set && count($piece['characteristic_names'])==0){
                    elgg_delete_metadata(['guid'=>$piece_guid, 'metadata_name'=>'characteristic_names']);
                    elgg_delete_metadata(['guid'=>$piece_guid, 'metadata_name'=>'characteristic_values']);
                    unset($piece_entity['characteristic_names'], $piece_entity['characteristic_values']);
                }
                $attr_set = elgg_get_metadata(['guid'=>$piece_guid, 'metadata_name'=>'features']);
                if ($attr_set && count($piece['features'])==0){
                    elgg_delete_metadata(['guid'=>$piece_guid, 'metadata_name'=>'features']);
                    unset($piece_entity['features']);
                }
                $attr_set = elgg_get_metadata(['guid'=>$piece_guid, 'metadata_name'=>'this_characteristic_names']);
                if ($attr_set && count($piece['this_characteristic_names'])==0){
                    elgg_delete_metadata(['guid'=>$piece_guid, 'metadata_name'=>'this_characteristic_names']);
                    elgg_delete_metadata(['guid'=>$piece_guid, 'metadata_name'=>'this_characteristic_values']);
                    unset($piece_entity['this_characteristic_names'], $piece_entity['this_characteristic_values']);
                }
                $attr_set = elgg_get_metadata(['guid'=>$piece_guid, 'metadata_name'=>'this_features']);
                if ($attr_set && count($piece['this_features'])==0){
                    elgg_delete_metadata(['guid'=>$piece_guid, 'metadata_name'=>'this_features']);
                    unset($piece_entity['this_features']);
                }
*/               
            }
            // if the piece does not exist
            else {
               // create a new piece
               $piece_entity                 = new ElggObject();
               $piece_entity->container_guid = $container_guid;
               $piece_entity->owner_guid     = $owner_guid;
               $piece_entity->access_id      = $access_id;
               $piece_entity->subtype        = 'market';
            }                                                                                                           $display .= '259 $piece_entity = '.print_r($piece_entity, true).'<br>';
            // merge the new piece into the received piece
            foreach($piece as $key=>$value){                                                                            $display .= '261 $piece['.$key.'] = '; if(is_array($value)) $display .= print_r($value, true).'<br>'; else $display .= $value.'<br>';
                $piece_entity->$key = $value;                                                                           $display .= '261 $piece_entity->'.$key.' = '; if(is_array($value)) $display .= print_r($piece_entity->$key, true).'<br>'; else $display .= $piece_entity->$key.'<br>';
            }
            $boqxes_key = array_search($piece_entity->cid, array_column($boqxes, 'cid'));
            // determine whether the outer boqx exists
            if ($boqx_visible){
                // determine whether the spot exists
                $spot_key = array_search($piece_entity->cid, array_column($boqxes, 'cid'));
                //if (elgg_entity_exists())
                // create a boqx to hold the piece in place
                $piece_boqx                 = new ElggObject();
                $piece_boqx->subtype        = 'boqx';
                $piece_boqx->title          = 'boqx';
                $piece_boqx->container_guid = $boqx->getGUID();
                $piece_boqx->owner_guid     = $owner_guid;
                $piece_boqx->access_id      = $access_id;
                // save the new piece_boqx
                $piece_boqx->save();
                // capture the guid attribute
                $boqxes[$boqxes_key]['guid'] = $piece_boqx->getGUID();
                // extract the boqx attributes from the piece to the piece boqx
                foreach($boqx_attributes as $key=>$attribute){
                    // scan the current boqx for attributes
                    if(array_key_exists($attribute, $piece_entity)){
                        // store attributes for the current boqx
                        $piece_boqx->$attribute = $piece_entity->$attribute;
                    }
                }
            }
            // extract the boqx attributes from the piece to the piece boqx
            foreach($boqx_attributes as $key=>$attribute){
                // scan the current boqx for attributes
                if(array_key_exists($attribute, $piece_entity)){
                    // store attributes for the current boqx
                    $boqxes[$boqxes_key][$attribute] = $piece_entity->$attribute;
                    // remove housekeeping attributes from the piece itself
                    unset($piece_entity->$attribute);
                }
            }                                                                                                            $display .= '300 $piece_boqx = '.print_r($piece_boqx, true).'<br>';
            $piece = $piece_entity;                                                                                      $display .= '301 $piece = '.print_r($piece, true).'<br>';
            $piece->save();
            if ($piece_boqx && !check_entity_relationship($piece_boqx->getGUID(), 'contains', $piece->getGUID()))
                 add_entity_relationship($piece_boqx->getGUID(), 'contains', $piece->getGUID());
            
        }
        break;
    case 'receipts':

        foreach($contents as $key=>$piece){
            $n = (int) $piece['pieces'];
            for ($i = 1; $i <= $n; $i++) {
                unset($piece_aspect);
                $piece_aspect = $piece['aspect'];
            }
        }
        break;
}

goto eof;        

if (!empty($containers)){
    //initialize $aspects, unsetting previous instance of the array that was populated above
    unset($aspects);
    foreach ($containers as $cid1){                     $display .= '187 $cid1='.$cid1.'<br>';
        if (is_array($jot[$cid1]) && !in_array($jot[$cid1]['aspect'], $aspects[$cid1]))
            $aspects[$cid1] = $jot[$cid1]['aspect'];
        if ($jot[$cid1]['aspect'] == 'item')
            if (is_array($jot[$cid1]))
                foreach ($jot[$cid1] as $key=>$value)
                    if (is_array($value))
                        $items[]=$value;                                    
        if ($jot[$cid1]['aspect'] == 'receipt')
            if (is_array($jot[$cid1]))
                foreach ($jot[$cid1] as $key=>$value)
                    if (is_array($value))
                        $receipts[]=$value;
    }                                              $display .= '200 $aspects: '.print_r($aspects, true).'<br>';
}                                                  $display .= '201 $items: '.print_r($items, true).'<br>';//$display.='107 $receipts: '.print_r($receipts, true);
if (!empty($items)){
    unset($line_items);
    foreach ($items as $key=>$value){       $display .= '128 $items['.$key.']=>'.$value.'<br>';
        if(is_array($value) && !empty($value['title'])){
            $line_items[] = $value;                $display .= '130 $items['.$key.']=>'.print_r($value, true).'<br>'; 
        }                                          $display .= '131 $line_items:'.print_r($line_items, true).'<br>';
    }
    if (!empty($line_items)){
        foreach($line_items as $key=>$line_item_Mz78qqFc){       $display .= '116 $line_item:'.print_r($line_item, true).'<br>';
            $line_item                 = new ElggObject();
            $line_item->subtype        = 'boqx';
            $line_item->container_guid = $boqx->guid;
            $line_item->owner_guid     = $owner_guid;
            $line_item->access_id      = $access_id;
            $line_item->title          = $line_item_Mz78qqFc['title'];   $display .= '140 $line_item->title = '.$line_item->title.'<br>';
            $line_item->sort_order     = $key+1;           $display .= '142 $line_item->sort_order = '.$line_item->sort_order.'<br>';
            $line_item->qty            = $line_item_Mz78qqFc['qty'];     $display .= '143 $line_item->qty = '.$line_item->qty.'<br>';
            if ($boqx_has_title){
//                        $line_item->save();
            }
            foreach($line_item_Mz78qqFc as $key1=>$value){                             $display .=  '147 $line_item['.$key1.'] = '.$value.'<br>';
                //      Remove blank characteristics
                if ($key1 == 'characteristic_names'){
        			foreach ($value as $this_key=>$this_value){
        				if ($this_value == ''){                      
        					unset($line_item_Mz78qqFc['characteristic_names'][$this_key]);
        					unset($line_item_Mz78qqFc['characteristic_values'][$this_key]);
        				}
        			}
        		}
                if ($key1 == 'this_characteristic_names'){
        			foreach ($value as $this_key=>$this_value){
        				if ($this_value == ''){                      
        					unset($line_item_Mz78qqFc['this_characteristic_names'][$this_key]);
        					unset($line_item_Mz78qqFc['this_characteristic_values'][$this_key]);
        				}
        			}
        		}
        		if ($key1 == 'features' || $key1 == 'this_features'){
        			foreach ($value as $this_key=>$this_value){
        				if ($this_value == ''){                     
        					unset($line_item_Mz78qqFc[$key1][$this_key]);
        				}
        			}												
        		}
                //if (!is_array($value))
            }
            $new_thing                 = new ElggObject();
            foreach($line_item_Mz78qqFc as $key1=>$value){
               $new_thing->$key1       = $value;                   $display .= '166 $new_thing->'.$key1.' = '.$new_thing->$key1.'<br>151 $value ='.print_r($value, true).'<br>';
            }
            $new_thing->subtype        = 'market';
            $new_thing->container_guid = $owner_guid;
            $new_thing->owner_guid     = $owner_guid;
            $new_thing->access_id      = $access_id;               $display .= '171 $new_thing: '.print_r($new_thing, true).'<br>';
//                    $new_thing->save();
                    // connect the new item to the line item as long as the boqx exists
            if (elgg_entity_exists($boqx->guid)){
                $guid_one     = $item->guid;
                $relationship = $item->subtype;
                $guid_two     = $new_thing->guid;
                add_entity_relationship($guid_one, $relationship, $guid_two);
            }
        }                                  // $display.='150 $loose_thing: '.print_r($loose_thing, true).'<br>150 $new_thing: '.print_r($new_thing, true);
    }
}

eof:
//register_error($display);
