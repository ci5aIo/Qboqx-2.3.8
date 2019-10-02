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
$aspect         =       get_input('aspect');                          $display .= '21 $aspect: '.$aspect.'<br>'; //goto eof;
$action         =       get_input('action');
$this_section   =       get_input('this_section');
// Receive jot data
$jot_input      =      get_input('jot');
$jot            =      get_input('jot');
$jot_snapshot   =      $jot['snapshot'];      unset($jot['snapshot']); 
$subtype        =      $jot['subtype'];
$guid           =      $guid   ?: $jot['guid'];                        $display .= '28 $guid: '.$guid.'<br>';
$aspect         =      $aspect ?: $jot['aspect'];                      $display .= '29 $aspect: '.$aspect.'<br>';
$now            =      new DateTime(null, new DateTimeZone('America/Chicago'));
$title          =      $jot['title']          ?: $subtype.'_'.$now;
$description    =      $jot['description'];
$container_guid =      $jot['container_guid'] ?: elgg_get_logged_in_user_guid(); $display.='33 $container_guid = '.$container_guid.'<br>';
$owner_guid     =      $jot['owner_guid']     ?: elgg_get_logged_in_user_guid(); $display.='34 $owner_guid = '.$owner_guid.'<br>';
$access_id      =      get_default_access();
$exists         =      elgg_entity_exists($guid);
$moment         =      $jot['moment']         ?: $now;                           $display .= '37 $moment = '.$moment->format('Y-m-d').'<br>';
$boqx_type      =      $jot['boqx'];
$boqx_has_title    =      false;

$containers           = array();
$aspects              = array();
//Extract boqx components
if (is_array($jot)){
    foreach($jot as $key=>$value){
        if (is_array($value) && !in_array($key,$containers)){
            // Identify the cid's
            $containers[] = $key;                                               $display .= '48 $key = '.$key.'<br>';
            continue;
        }
    }
}
$boqx                 = new ElggObject();
$boqx->container_guid = $container_guid;
$boqx->owner_guid     = $owner_guid;
$boqx->access_id      = $access_id;
$cid                  = $jot['cid'];
foreach($jot[$cid] as $key=>$value)
   $boqx->$key = $value;

//unset($jot[$cid]);

// Display the remaining containers
foreach($jot as $key=>$value){
	if (empty($value)) continue;                                             $display .= '63 jot['.$key.'] = '.$value.'<br>';
	if (is_array($value) && count($value)>0){
	    foreach($value as $key1=>$value1){
	        if (empty($value1)) continue;                                    $display .= '67 jot['.$key.']['.$key1.'] = '.$value1.'<br>';
		    if ($key == $cid) $boqx->$key1 = $value1;
    			if (is_array($value1) && count($value1)>0){
				foreach($value1 as $key2=>$value2){
				    if (empty($value2)) continue;                            $display .= '76 jot['.$key.']['.$key1.']['.$key2.'] = '.$value2.'<br>';
					if(is_array($value2) && count($value2)>0){
						foreach($value2 as $key3=>$value3){
						    if (empty($value3)) continue;                    $display .= '80 jot['.$key.']['.$key1.']['.$key2.']['.$key3.'] = '.$value3.'<br>';
							if (is_array($value3) && count($value3)>0){
								foreach($value3 as $key4=>$value4){
								    if (empty($value4)) continue;            $display .= '83 jot['.$key.']['.$key1.']['.$key2.']['.$key3.']['.$key4.'] = '.$value4.'<br>';
									if (is_array($value4) && count($value4)>0){
										foreach($value4 as $key5=>$value5){  
										    if (empty($value5)) continue;    $display .= '86 jot['.$key.']['.$key1.']['.$key2.']['.$key3.']['.$key4.']['.$key5.'] = '.$value5.'<br>';
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

if (!empty($boqx->title)) $boqx_has_title = true;
if ($boqx_has_title){
//            $boqx->save();
    $guid = 999999;
    $boqx->guid = $guid;
}
Switch ($boqx->aspect){
    case 'things'    : $contents_aspect ='item'       ; break;
    case 'receipts'  : $contents_aspect ='receipt'    ; break;
    case 'experience':
    case 'project'   :
    case 'issue'     : $contents_aspect =$boqx->aspect; break;
}
//Extract contents for only the selected aspect
foreach($containers as $container_id)
    if ($jot[$container_id]['boqx']==$cid && $jot[$container_id]['aspect']==$contents_aspect) 
        $contents[] = $jot[$container_id];
                                                 $display .= '112 $contents: '.print_r($contents, true).'<br>';
if (!empty($contents)){
    foreach($contents as $key=>$piece){
        unset($container_id);
        $container_id = $piece['cid'];
        foreach($jot as $key=>$value){
            if($value['boqx']==$container_id){
                $value['container_guid']=$container_id;
                $pieces[] = $value;
            }
        }
    }
}                                                       $display .= '124 $pieces: '.print_r($pieces, true).'<br>';

Switch ($boqx->aspect){
    case 'things':
        
        break;
    case 'receipts':
        
        break;
}

if (!empty($containers)){
    foreach ($containers as $cid1){                     $display .= '106 $cid1='.$cid1.'<br>';
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
    }                                              $display .= '120 $aspects: '.print_r($aspects, true).'<br>';
}                                                  $display .= '121 $items: '.print_r($items, true).'<br>';//$display.='107 $receipts: '.print_r($receipts, true);
goto eof;        
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
goto eof;        

eof:
register_error($display);
