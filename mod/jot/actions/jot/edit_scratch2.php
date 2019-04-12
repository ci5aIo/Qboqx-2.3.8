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
$jot_input      =      get_input('jot');       //if(empty($jot_input)){unset($jot_input); $display .= 'empty $jot_input<br>';} $display .= '25 $jot_input[title]'.$jot_input['title'].'<br>';
$jot_snapshot   =      $jot_input['snapshot'];                       unset($jot_input['snapshot']); 
$subtype        =      $jot_input['subtype'];
$guid           =      $guid   ?: $jot_input['guid'];                        $display .= '28 $guid: '.$guid.'<br>';
$aspect         =      $aspect ?: $jot_input['aspect'];                      $display .= '29 $aspect: '.$aspect.'<br>';
$now            =      new DateTime(null, new DateTimeZone('America/Chicago'));
$title          =      $jot_input['title']          ?: $subtype.'_'.$now;
$description    =      $jot_input['description'];
$container_guid =      $jot_input['container_guid'] ?: elgg_get_logged_in_user_guid(); $display.='33 $container_guid = '.$container_guid.'<br>';
$owner_guid     =      $jot_input['owner_guid']     ?: elgg_get_logged_in_user_guid(); $display.='34 $owner_guid = '.$owner_guid.'<br>';
$access_id      =      get_default_access();
$exists         =      elgg_entity_exists($guid);
$moment         =      $jot_input['moment']         ?: $now;                           $display .= '37 $moment = '.$moment->format('Y-m-d').'<br>';
$boqx_type      =      $jot_input['boqx'];

if (is_array($jot_input)){
    $cid      = $jot_input['cid'];                                                    $display .= '62 $cid ='.$cid.'<br>';
    if (is_array($jot_input[$cid])){
        $jot_boqx = $jot_input[$cid];
        $subtype  = $jot_boqx['subtype'];
        $aspect   = $jot_boqx['aspect'];}
    foreach($jot_input as $key=>$value){                                         $display .= '63 jot['.$key.'] = '.$value.'<br>';
    	if (empty($value)) continue;
            $jot[$key] = $value;
    	if (is_array($value)){
    		foreach($value as $key1=>$value1){                                   $display .= '49 jot['.$key.']['.$key1.'] = '.$value1.'<br>';
    		
    		    if (empty($value1))      continue;
//    		    if ($value1 == 'aspect') 
    			if (is_array($value1)){
    				foreach($value1 as $key2=>$value2){                          $display .= '51 jot['.$key.']['.$key1.']['.$key2.'] = '.$value2.'<br>';
    					if(is_array($value2)){
    						foreach($value2 as $key3=>$value3){                  $display .= '53 jot['.$key.']['.$key1.']['.$key2.']['.$key3.'] = '.$value3.'<br>';
    							if (is_array($value3)){
    								foreach($value3 as $key4=>$value4){          $display .= '55 jot['.$key.']['.$key1.']['.$key2.']['.$key3.']['.$key4.'] = '.$value4.'<br>';
    									if (is_array($value4)){
    										foreach($value4 as $key5=>$value5){  $display .= '57 jot['.$key.']['.$key1.']['.$key2.']['.$key3.']['.$key4.']['.$key5.'] = '.$value5.'<br>';
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
}
//	$jot->save();
	$guid                = $jot->guid;
	
switch_subtype:
Switch ($subtype){
    case 'boqx':
        $jot                 = new ElggObject();
        $jot->subtype        = 'boqx';
        $jot->container_guid = $owner_guid;
        $jot->owner_guid     = $owner_guid;
        $jot->access_id      = $access_id;
        $sections = array();
        $aspects = array();
        //Extract boqx sections
        if (is_array($jot_boqx)){                             $display.='88 $jot_boqx: '.print_r($jot_boqx, true).'<br>';
            foreach($jot_boqx as $key=>$value){
                if (is_array($value) && !in_array($key,$sections)){
                    $sections[] = $key;                     $display .= '91 $key = '.$key.'<br>';
                    continue;
                }
                $jot->$key=$value;                           $display .= '94 $jot->'.$key.'='.$value.'<br>';   
            }
//            $jot->save();
        }                                                                        
        if (!empty($sections)){
            foreach ($sections as $cid1){                     $display .= '99 $cid1='.$cid1.'<br>';
                if (is_array($jot_boqx[$cid1]) && !in_array($jot_boqx[$cid1]['aspect'], $aspects))
                    $aspects[] = $jot_boqx[$cid1]['aspect'];
                if ($jot_boqx[$cid1]['aspect'] == 'loose things')
                    if (is_array($jot_boqx[$cid1])){
                        foreach ($jot_boqx[$cid1] as $key=>$value){
                            if (is_array($value))
                                $loose_things[]=$value;
                        }
                }
                if ($jot_boqx[$cid1]['aspect'] == 'receipt')
                    if (is_array($jot_boqx[$cid1])){
                        foreach ($jot_boqx[$cid1] as $key=>$value){
                            if (is_array($value))
                                $receipts[]=$value;
                        }
                }
            }
        }                                                  $display.='107 $loose_things: '.print_r($loose_things, true).'<br>';//$display.='107 $receipts: '.print_r($receipts, true);
        if (!empty($loose_things)){
            unset($line_items);
            foreach ($loose_things as $key=>$value){       $display .= '111 $loose_things['.$key.']=>'.$value.'<br>';
                if(is_array($value)){
                    $line_items[] = $value;                $display .= '112 $loose_things['.$key.']=>'.print_r($value, true).'<br>'; 
                }                                          $display .= '113 $line_items:'.print_r($line_items, true).'<br>';
            }
            if (!empty($line_items)){
                foreach($line_items as $key=>$item){       $display .= '116 $item:'.print_r($item, true).'<br>';
                    $loose_thing                 = new ElggObject();
                    $loose_thing->subtype        = 'boqx';
                    $loose_thing->container_guid = $jot->guid;
                    $loose_thing->owner_guid     = $owner_guid;
                    $loose_thing->access_id      = $access_id;
                    $loose_thing->sort_order     = $key+1;           $display .= '122 $loose_thing->sort_order = '.$loose_thing->sort_order.'<br>';
                    $loose_thing->title          = $item['title'];   $display .= '123 $loose_thing->title = '.$loose_thing->title.'<br>';
                    $loose_thing->qty            = $item['qty'];     $display .= '124 $loose_thing->qty = '.$loose_thing->qty.'<br>';
//                    $loose_thing->save();
                    $new_thing                 = new ElggObject();
                    foreach($item as $key1=>$value){                             $display .=  '137 $item['.$key1.'] = '.$value.'<br>';
                        //      Remove blank characteristics
                        if ($key1 == 'characteristic_names'){
                			foreach ($item[$key1] as $this_key=>$this_value){
                				if ($this_value == ''){                      
                					unset($item['characteristic_names'][$this_key]);
                					unset($item['characteristic_values'][$this_key]);
                					continue;
                				}
                			}
                		}
                        if ($key1 == 'this_characteristic_names'){
                			foreach ($item[$key1] as $this_key=>$this_value){
                				if ($this_value == ''){                      
                					unset($item['this_characteristic_names'][$this_key]);
                					unset($item['this_characteristic_values'][$this_key]);
                					continue;
                				}
                			}
                		}
                		if ($key1 == 'features' || $key1 == 'this_features'){
                			foreach ($item[$key1] as $this_key=>$this_value){
                				if ($this_value == ''){                     
                					unset($item[$key1][$this_key]);
                					continue;
                				}
                			}												
                		}
                        //if (!is_array($value))
                            $new_thing->$key1 = $value;                     $display .= '166 $new_thing->'.$key1.' = '.$new_thing->$key1.'<br>151 $value ='.print_r($value, true).'<br>';
                    }
                    $new_thing->subtype        = 'market';
                    $new_thing->container_guid = $owner_guid;
                    $new_thing->owner_guid     = $owner_guid;
                    $new_thing->access_id      = $access_id;               $display .= '171 $new_thing: '.print_r($new_thing, true).'<br>';
                    //                        $new_thing->save();
                    // connect the new thing to the 
                    $guid_one     = $loose_thing->guid;
                    $relationship = $loose_thing->subtype;
                    $guid_two     = $new_thing->guid;
//                        add_entity_relationship($guid_one, $relationship, $guid_two);
                }                                  // $display.='150 $loose_thing: '.print_r($loose_thing, true).'<br>150 $new_thing: '.print_r($new_thing, true);
            }
        }
goto eof;        
        break;

	default:
		
		break;
}

eof:
register_error($display);
