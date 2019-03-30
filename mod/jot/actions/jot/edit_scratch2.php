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

if (empty($aspect)){
	$aspect              = 'boqx';
	$jot_input['aspect'] = $aspect;
}
if ($aspect == 'nothing'){
    $aspect  = $subtype;}                                                    $display .= '45 $aspect: '.$aspect.'<br>';

if ($exists){// get the jot
	$jot = get_entity($guid);                                                    $display .= '61 $jot->guid = '.$jot->guid.'<br>';
}
else {       // create a new jot
    $jot                 = new ElggObject();
    $jot->subtype        = $subtype;
    $jot->container_guid = $owner_guid;
    $jot->owner_guid     = $owner_guid;
	$jot->access_id      = $access_id;
    $jot->title          = $title;
	$jot->description    = $description;
    $jot->aspect         = $aspect;
    $jot->moment         = $moment;
}
foreach($jot_input as $key=>$value){                                         $display .= '47 jot['.$key.'] = '.$value.'<br>';
	if (is_array($value)){
		foreach($value as $key1=>$value1){                                   $display .= '49 jot['.$key.']['.$key1.'] = '.$value1.'<br>';
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
	if (empty($value)){continue;}
        $jot[$key] = $value;
}

//	$jot->save();
	$guid                = $jot->guid;

switch_subtype:
Switch ($subtype){
    case 'transfer':
        Switch ($aspect){
    /****************************************
     * $subtype = 'transfer'
     * $aspect = 'boqx'                   *****************************************************************************
     ****************************************/
            case 'boqx':

                break;
        }
goto eof;        
        break;
	case 'observation':
	/****************************************
         * $aspect = 'observation'               *****************************************************************
     ****************************************/
            $jot->state                      = $observation['state'];
//            $jot->save();
            $new_observation                 = new ElggObject();
            $new_observation->subtype        = 'observation';
            $new_observation->aspect         = $observation['aspect'];
            $new_observation->container_guid = $guid;
            $new_observation->owner_guid     = $owner_guid;
			$new_observation->access_id      = $access_id;
			$new_observation->title          = $title.' - '.$observation['aspect'];
			$new_observation->description    = 'Container for the '.$observation['aspect'];
//			$new_observation->save();
            unset($existing_guids);
            $discoveries_existing = elgg_get_entities_from_metadata(['type'                      => 'object',
		                                                             'subtype'                   => 'observation',
		                                                             'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'discovery'),
		                                                             'container_guid'            => $guid,
		                                                             'limit'                     => 0,
		                                                            ]);//                   $display .= '102 '.print_r($discoveries_existing, false).'<br>';
            $efforts_existing     = elgg_get_entities_from_metadata(['type'                      => 'object',
		                                                             'subtype'                   => 'observation',
		                                                             'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'effort'),
		                                                             'container_guid'            => $guid,
		                                                             'limit'                     => 0,
		                                                            ]);//                   $display .= '108 '.print_r($discoveries_existing, false).'<br>';
            $discoveries = $observation['discovery'];
            $efforts     = array_values($observation['effort']);// reassign cid keys to be sequential
            $requests     = $observation['request'];
            if (!empty($discoveries_existing)){
                foreach($discoveries_existing as $existing){                // $display .= '113 $discoveries_existing->guid: '.$existing->guid.'<br>';
                    $discoveries_existing_guids[] = $existing->guid;
                }
            }
            if (!empty($efforts_existing)){
                foreach($efforts_existing as $existing){                     //$display .= '118 $efforts_existing->guid: '.$existing->guid.'<br>';
                    $efforts_existing_guids[] = $existing->guid;
                }
            }
            if (!empty($discoveries)){
            	foreach($discoveries as $key=>$value){
            		//create discovery set entity
            		if (is_array($value)){
            			foreach($value as $key1=>$value1){
            				//unset discovery entity
            				//create discovery entity
            				//connect discovery entity to discovery set entity
            				//save discovery entity
            			}
            		}
            	}
            }
            if (!empty($efforts)){
            	foreach($efforts as $key=>$effort){                                         //$display .= '141 $effort['.$key.'] = '.$effort.'<br>';
            		if (is_array($effort)){
            			if (empty($effort['title'])){unset($efforts[$key]); continue;}
	            		//create effort entity
	            		$new_effort                 = new ElggObject();
	            		$new_effort->subtype        = 'effort';
					    $new_effort->container_guid = $new_observation->guid;
					    $new_effort->owner_guid     = $owner_guid;
						$new_effort->access_id      = $access_id;
						$new_effort->title          = $effort['title'];
						$new_effort->description    = $effort['description'];
						$new_effort->aspect         = $effort['aspect'];
						$new_effort->effort_type    = $effort['type'];
						$new_effort->state          = $effort['state'];
						$new_effort->moment         = $effort['moment'] ?: $moment;         $display .= '155 $new_effort['.$key.']->save(): '.$new_effort->title.'<br>';
						$new_effort->sort_order     = $key;
//						$new_effort->save();
            			foreach($effort as $key1=>$task){                                  //$display .= '157 $effort['.$key.']['.$key1.'] = '.$tasks.'<br>';
            				if (is_array($task)){
	            				if (empty($task['title'])){unset($effort[$key1]); continue;}
	            				//unset task entity variable
	            				//create task entity
	            				$new_task                 = new ElggObject();
			            		$new_task->subtype        = 'task';
							    $new_task->owner_guid     = $owner_guid;
							    $new_task->container_guid = $new_effort->guid;
								$new_task->access_id      = $access_id;
								$new_task->title          = $task['title'];
								$new_task->description    = $task['description'];          $display .= '168 $new_task['.$key1.']->save(): '.$new_task->title.'<br>';
								$new_task->sort_order     = $key1;
	            				//save effort entity
//	            				$new_task->save();
            					// reassign keys to be sequential.  Keys originally assigned by system, but might have been resorted by the user.
            					$task = array_values($task);
            					foreach($task as $key2=>$item){                           //$display .= '173 $tasks['.$key1.']['.$key2.'] = '.$item.'<br>';
            						if (is_array($item)){
            							if (empty($item['title']) || 
            							    empty($item['qty'])   || 
            								      $item['qty']== 0){unset($task[$key2]); continue;}
	            						$new_item                 = new ElggObject();
			            		        $new_item->subtype        = 'item';
									    $new_item->owner_guid     = $owner_guid;
										$new_item->container_guid = $new_task->guid;
										$new_item->access_id      = $access_id;
										$new_item->title          = $item['title'];
										$new_item->description    = $item['description'];
										$new_item->aspect         = $item['aspect'];
										$new_item->qty            = $item['qty'];
										$new_item->title          = $item['title'];
										$new_item->aspect         = $item['aspect'];
										$new_item->cost           = $item['cost'] ?: 0;
										$new_item->sku            = $item['sku'];
										$new_item->item_type      = $item['item_type'];
										$new_item->replaces       = $item['replaces'];
										$new_item->sort_order     = $key2;
										$new_item->guid           = $item['guid'];          $display .= '193 $new_item['.$key2.']->save(): '.$new_item->title.'<br>';
//	            						$new_item->save();
            						}
            					}
            				}
            			}
            		}
            	}
            }
		break;
	default:
		
		break;
}

eof:
register_error($display);
