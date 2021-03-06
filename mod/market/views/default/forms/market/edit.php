<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 *
 * Modified by Kevin Jardine for arckinteractive.com
 */

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}
$guid         = elgg_extract('guid', $vars, false);
$entity       = elgg_extract('entity', $vars, get_entity($guid));
$tags         = elgg_extract('markettags', $vars);
$body         = $vars['marketbody'];
$access_id    = $vars['access_id'];
$entity_guid  = $guid;
$asset_guid   = $guid;

/****/
$selected       = elgg_extract('selected'         , $vars, 'family');
$space          = elgg_extract('space'            , $vars);
$aspect         = elgg_extract('aspect'           , $vars);
$section        = elgg_extract('section'          , $vars, 'main');
$snippet        = elgg_extract('snippet'          , $vars);
$n              = elgg_extract('n'                , $vars, 1);
$boqx_guid      = elgg_extract('boqx_guid'        , $vars, false);
$cid            = elgg_extract('cid'              , $vars, quebx_new_id('c'));
$carton_id      = elgg_extract('carton_id'        , $vars, quebx_new_id('c'));
$envelope_id    = elgg_extract('envelope_id'      , $vars, quebx_new_id('c'));
$parent_cid     = elgg_extract('parent_cid'       , $vars, false);
$boqx_cid       = elgg_extract('boqx_cid'         , $vars);
$wrapper_cid    = elgg_extract('wrapper_cid'      , $vars);
$qid            = elgg_extract('qid'              , $vars, quebx_new_id('q'));
$presence       = elgg_extract('presence'         , $vars);
$presentation   = elgg_extract('presentation'     , $vars);
	if($presentation == 'nested')                   $vars['display_state'] = 'view';
$perspective    = elgg_extract('perspective'      , $vars, 'edit');
$action         = elgg_extract('action'           , $vars, $perspective);
$display_state  = elgg_extract('display_state'    , $vars, $perspective); //Options: add, show, view, edit
$display_class  = elgg_extract('display_class'    , $vars, false);
$fill_level     = elgg_extract('fill_level'       , $vars, 0);
$submit         = elgg_extract('submit'           , $vars, true);
$origin         = elgg_extract('origin'           , $vars);
$form_method    = elgg_extract('form_method'      , $vars, false);
$form_id        = elgg_extract('form_id'          , $vars['form_vars'], quebx_new_id('f'));

$unpacked       = elgg_entity_exists($guid);
$owner_guid     = elgg_get_logged_in_user_guid();

if ($entity){
    $tags  = elgg_extract('markettags', $vars, $entity->gettags());
    $owner = get_entity($entity->owner_guid);
    $subtype = $entity->getSubtype();
}

$disabled      = $display_state == 'view';
$family_values = item_prepare_form_vars(NULL,NULL,$entity, null, null);
$entity_values = item_prepare_form_vars(NULL,NULL,$entity, null, null);
$values        = array_merge($family_values, $entity_values);
foreach($values as $value=>$field){
//	$display .= $value.'=>'.$field.'<br>';
}
//$form .= $display;
echo "<!--origin=$origin, perspective=$perspective, section=$section, snippet=$snippet, presentation=$presentation, presence=$presence, display_state=$display_state, 'disabled'=>$disabled, submit=$submit, boqx_cid=>$boqx_cid, carton_id=>$carton_id -->";

/******************************************************************************************************
 * 
 * Perspectives
 *
*******************************************************************************************************/
Switch ($perspective){
/****************************************
 * $perspective = 'add'                      *****************************************************************************
 ****************************************/
    case 'add':
        switch ($section){
				/****************************************
*add********** $section = 'main'                          *****************************************************************
				 ****************************************/
            case 'main':
                $tabs[]=['title'=>'Family'     , 'aspect'=>'family'       , 'section'=>'us' , 'note'=>'Common characteristics'               , 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_1", 'selected'=>$selected == 'family'];
                $tabs[]=['title'=>'Individual' , 'aspect'=>'individual'   , 'section'=>'this' , 'note'=>'Characteristics unique to this item', 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_2", 'selected'=>$selected == 'individual'];
                $tabs[]=['title'=>'Receipt'    , 'aspect'=>'receipt'      , 'section'=>'get' , 'note'=>'Acquisition'                         , 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_3", 'selected'=>$selected == 'receipt'];
                $tabs[]=['title'=>'Gallery'    , 'aspect'=>'gallery'      , 'section'=>'pics', 'note'=>'Pictures'                            , 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_4", 'selected'=>$selected == 'gallery'];
                $tabs[]=['title'=>'Library'    , 'aspect'=>'library'      , 'section'=>'docs', 'note'=>'Documents'                           , 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_5", 'selected'=>$selected == 'library'];
                $panels[]=['aspect'=>'family'    , 'class'=>'option-panel family-option-panel'    , 'content'=> elgg_view("forms/market/edit/family"     , $vars)];
                $panels[]=['aspect'=>'individual', 'class'=>'option-panel individual-option-panel', 'content'=> elgg_view("forms/market/edit/individual" , $vars)];
                $panels[]=['aspect'=>'receipt'   , 'class'=>'option-panel receipt-option-panel'   , 'content'=> elgg_view("forms/market/edit/acquisition", $vars)];
                $panels[]=['aspect'=>'gallery'   , 'class'=>'option-panel gallery-option-panel'   , 'content'=> elgg_view("forms/market/edit/gallery"    , $vars)];
                $panels[]=['aspect'=>'library'   , 'class'=>'option-panel library-option-panel'   , 'content'=> elgg_view("forms/market/edit/library"    , $vars)];
        
                switch ($presentation){
				/****************************************
*add********** $section = 'main' $presentation = 'pallet, envelope' *****************************************************************
				 ****************************************/
                    case 'envelope':
                    case 'pallet':
//                        if(!elgg_entity_exists($guid)) break;
                        unset($tabs, $panels);
                        $selected = elgg_extract('selected', $vars, 'item');
                     	$tabs[]   = ['title'=>'Item'       , 'aspect'=>'item'         , 'section'=>'us'  , 'note'=>'Characteristics, Contents, Components, Accessories', 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'family'];
//                         $tabs[]   = ['title'=>'Experiences', 'aspect'=>'experiences'  , 'section'=>'this', 'note'=>'Experiences, Issues, Instructions, Insights', 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'individual'];
//                         $tabs[]   = ['title'=>'Inventory'  , 'aspect'=>'inventory'    , 'section'=>'docs', 'note'=>'Supplies, Pieces, Parts, Receipts'   , 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'library'];
//                         $tabs[]   = ['title'=>'Media'      , 'aspect'=>'media'        , 'section'=>'docs', 'note'=>'Documents, Pictures, Video'          , 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'library'];
                        foreach($tabs as $key=>$tab){
                            unset($content);
                            $content = elgg_view("forms/market/edit", array_merge($vars, ['section'=>$tab['aspect'], 'parent_cid'=>$parent_cid, 'guid'=>$guid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]));
                            $panels[] = ['aspect'=>$tab['aspect'], 'id'=>$tab['data-qid']    , 'class'=>"option-panel ".$tab['aspect']."-option-panel"    , 'content'=> $content];
                            $tabs[$key]['selected'] = $tab['aspect'] == $selected;
                        }
                        unset($tabs);
                        break;
				/****************************************
*add********** $section = 'main' $presentation = 'open_boqx' *****************************************************************
				 ****************************************/
                    case 'open_boqx':
//                        if(!elgg_entity_exists($guid)) break;
                        unset($tabs, $panels);
                        $selected = elgg_extract('selected', $vars, 'item');
                     	$tabs[]   = ['title'=>'Item'       , 'aspect'=>'item'         , 'section'=>'us'  , 'note'=>'Characteristics, Contents, Components, Accessories', 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'family'];
                        foreach($tabs as $key=>$tab){
                            unset($content);
                            $content = elgg_view("forms/market/edit", array_merge($vars, ['section'=>$tab['aspect'], 'parent_cid'=>$parent_cid, 'guid'=>$guid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]));
                            $panels[] = ['aspect'=>$tab['aspect'], 'id'=>$tab['data-qid']    , 'class'=>"option-panel ".$tab['aspect']."-option-panel"    , 'content'=> $content];
                            $tabs[$key]['selected'] = $tab['aspect'] == $selected;
                        }
                        break;
                    default:
                        break;
                }
                $nav['tabs']  = $tabs;
                $nav['space'] = $space;
                $nav['qid']   = $qid;
                $nav['class'] ='quebx-tabs';
                $navigation   = elgg_view('navigation/tabs_slide', $nav);
                foreach($panels as $key=>$panel){
                	$is_selected = $selected == $panel['aspect'];
                	$class       = (array) $panel['class'];
            	    if ($is_selected) {
            			$class[] = 'qbox-state-selected';
            		}
                	$detail .= elgg_format_element('div',['id'=>$panel['id'], 'class'=>$class, 'parent_cid'=>$parent_cid], $panel['content']);
                }
                $details = elgg_format_element('div',['class'=>"qbox-details"], $detail);
                $form    = $navigation.$details;
                
                break;
				/****************************************
*add********** $section = 'profile'                       *****************************************************************
				 ****************************************/
            case 'profile':
                
                break;
				/****************************************
*add********** $section = 'tabs'                          *****************************************************************
				 ****************************************/
            case 'tabs':
                
                break;
				/****************************************
*add********** $section = 'category'                      *****************************************************************
				 ****************************************/
            case 'category':
                
                break;
                /****************************************
*add********** $section = 'item_xxx'                       *****************************************************************
				 ****************************************/
            case 'item_xxx':
                unset($hidden_fields);
                $hidden_fields = quebx_format_elements('hidden', $hidden);
                switch ($snippet){
                    case 'characteristics':
                        
                        break;
                    default:
                        $manufacturers         = elgg_get_entities(['type'=>'group','subtype'=>'merchant']);
        				foreach($manufacturers as $manufacturer)
        					$manufacturer_guids[] = $manufacturer->getGUID();
        				
                       	$pick = elgg_view('output/url', array(
                        		'text' => '[pick]',
                        		'class' => 'elgg-lightbox',
                        		'data-colorbox-opts' => '{"width":600, "height":525}',
                       			'href' => "pick_test/family_characteristics/" . $entity->guid));
                       	$pick_menu = "<span title='Select family characteristics'>$pick</span>";
                       	if($entity->characteristic_names) $characteristic_names = is_array($entity->characteristic_names) ? $entity->characteristic_names : (array) $entity->characteristic_names;
                        if($entity->characteristic_values) $characteristic_values = is_array($entity->characteristic_values) ? $entity->characteristic_values : (array) $entity->characteristic_values;
                        if($entity->features) $features = is_array($entity->features) ? $entity->features : (array) $entity->features;
                        if($entity->this_characteristic_names) $this_characteristic_names = is_array($entity->this_characteristic_names) ? $entity->this_characteristic_names : (array) $entity->this_characteristic_names;
                        if($entity->this_characteristic_values) $this_characteristic_values = is_array($entity->this_characteristic_values) ? $entity->this_characteristic_values : (array) $entity->this_characteristic_values;
                        if($entity->this_features) $this_features = is_array($entity->this_features) ? $entity->this_features : (array) $entity->this_features;
                        unset($characteristics, $last_class);
                        if($characteristic_names)
                            foreach($characteristic_names as $key=>$name)
                                $characteristics .= elgg_format_element('div',['class'=>'rTableRow'],
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_names][]", 'value'=>$name, 'placeholder'=>'Characteristic','disabled'=>$disabled])).
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_values][]", 'value'=>$characteristic_values[$key], 'placeholder'=>'Value','disabled'=>$disabled])));
                        if($this_characteristic_names)
                            foreach($this_characteristic_names as $key=>$name)
                                $characteristics .= elgg_format_element('div',['class'=>'rTableRow'],
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_names][]", 'value'=>$name, 'placeholder'=>'Characteristic','disabled'=>$disabled])).
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_values][]", 'value'=>$this_characteristic_values[$key], 'placeholder'=>'Value','disabled'=>$disabled])));
                        if($display_state != 'view')
                            $characteristics .= elgg_format_element('div',['class'=>'rTableRow'],
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_names][]", 'placeholder'=>'Characteristic'])).
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_values][]", 'placeholder'=>'Value','class' => 'last_characteristic',])));
                        unset($all_features, $last_class);
                        if($features)
                            foreach ($features as  $key=>$name)
                                $all_features .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                      elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:100%'],
                                                          elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][features][]", 'value'=>$name, 'placeholder'=>'Feature','disabled'=>$disabled])));
                        if($this_features)
                            foreach ($this_features as  $key=>$name)
                                $all_features .= elgg_format_element('div',['class'=>'rTableRow'],
                                                      elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:100%'],
                                                          elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][features][]", 'value'=>$name, 'placeholder'=>'Feature','disabled'=>$disabled])));
                        if($display_state != 'view')
                            $all_features .= elgg_format_element('div',['class'=>'rTableRow'],
                                				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:100%'],
                                		            elgg_view('input/text', ['name'=> "jot[$cid][features][]",'class'=> 'last_feature','placeholder'=>'Feature'])));
                        
                        $content =
                            elgg_format_element('div',['class'=>'rTable','style'=>'width:100%;margin-top:10px;'],
                                elgg_format_element('div',['id'=>$cid.'_characteristics','class'=>['rTableBody','itemCharacteristics_Dcl702bW'],'data-cid'=>$cid],
                                    elgg_format_element('div',['class'=>'rTableRow'],
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>Manufacturer</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_view('input/grouppicker', array('name' => "jot[$cid][manufacturer]", 'value' => $entity->manufacturer, 'placeholder'=>'Manufacturer','disabled'=>$disabled)))).
                                    elgg_format_element('div',['class'=>'rTableRow'],
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>Brand</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_format_element('input',['type'=>'text','name' => "jot[$cid][brand]", 'value' => $entity->brand, 'placeholder'=>'Brand','disabled'=>$disabled]))).
                                    elgg_format_element('div',['class'=>'rTableRow'],
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>Model #</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_format_element('input',['type'=>'text','name' => "jot[$cid][model_no]", 'value' => $entity->model_no, 'placeholder'=>'Model #','disabled'=>$disabled]))).
                        			elgg_format_element('div',['class'=>'rTableRow'],
                                        elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>Part #</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_format_element('input',['type'=>'text','name' => "jot[$cid][part_no]", 'value' => $entity->part_no, 'placeholder'=>'Part #','disabled'=>$disabled]))).
                        			elgg_format_element('div',['class'=>'rTableRow'],
                                        elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>SKU</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_format_element('input',['type'=>'text','name' => "jot[$cid][sku]", 'value' => $entity->sku, 'placeholder'=>'SKU','disabled'=>$disabled]))).
                                    $characteristics.
                                    elgg_format_element('div',['class'=>'new_characteristic','data-cid'=>$cid]))).
                            elgg_view('page/elements/envelope',['task'=>'characteristic','action'=>$perspective, 'guid'=>$guid, 'parent_cid'=>$parent_cid,'carton_id'=>$carton_id, 'cid'=>$cid,'visible'=>'add','has_collapser'=>'no', 'hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'presence'=>$presence]).
                            elgg_format_element('div',['class'=>'rTable','style'=>'width:100%'],
        		               elgg_format_element('div',['class'=>['rTableBody','itemFeatures_LU71nPs4'],'id'=>$cid.'_features','data-cid'=>$cid],
                                   elgg_format_element('div',['class'=>['rTableRow','pin']],
                                       elgg_format_element('div',['class'=>'rTableCell'],'<b>Features</b>')).
        		                   $all_features.
                                   elgg_format_element('div',['class'=>'new_feature','data-cid'=>$cid]))).
                            elgg_view('page/elements/envelope',['task'=>'feature','action'=>$perspective, 'guid'=>$guid, 'parent_cid'=>$parent_cid,'carton_id'=>$carton_id, 'cid'=>$cid,'visible'=>'add','has_collapser'=>'no', 'hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'presence'=>$presence]);
                }
                
                $form = $hidden_fields.$content;
                break;
                /****************************************
*add********** $section = 'item'                       *****************************************************************
				 ****************************************/
            case 'item':
				unset($hidden_fields);
				$characteristics       = [];
				$features              = [];
				$characteristic_fields = [];
				$feature_fields        = [];
                $hidden_fields         = quebx_format_elements('hidden', $hidden);
				$class_delete          = 'IconButton___kmh1IhBB';      
				$class_remove          = 'remove-item';

				$pick                  = elgg_view('output/url', ['text' => '[pick]','class' => 'elgg-lightbox','data-colorbox-opts' => '{"width":600, "height":525}','href' => "pick_test/family_characteristics/" . $entity->guid]);
				$pick_menu             = "<span title='Select family characteristics'>$pick</span>";
				$delete         	   = elgg_format_element('button',['class'=>[$class_delete,'IconButton--small___3D375vVd'],'data-aid'=>'delete','aria-label'=>'Delete','data-cid'=>$cid],
											  elgg_format_element("span",['class'=>$class_remove],
												  elgg_format_element('a', ['title' =>'remove item'], 
													  elgg_view_icon('delete-alt'))));   

				if($entity->characteristic_names)       $characteristic_names       = is_array($entity->characteristic_names)       ? $entity->characteristic_names       : (array) $entity->characteristic_names;
				if($entity->characteristic_values)      $characteristic_values      = is_array($entity->characteristic_values)      ? $entity->characteristic_values      : (array) $entity->characteristic_values;
				if($entity->features)                   $features                   = is_array($entity->features)                   ? $entity->features                   : (array) $entity->features;
				if($entity->this_characteristic_names)  $this_characteristic_names  = is_array($entity->this_characteristic_names)  ? $entity->this_characteristic_names  : (array) $entity->this_characteristic_names;
				if($entity->this_characteristic_values) $this_characteristic_values = is_array($entity->this_characteristic_values) ? $entity->this_characteristic_values : (array) $entity->this_characteristic_values;
				if($entity->this_features)              $this_features              = is_array($entity->this_features)              ? $entity->this_features              : (array) $entity->this_features;
				$attribute[] = 'manufacturer';
				$attribute[] = 'brand';
				$attribute[] = 'model_no';
				$attribute[] = 'part_no';
				$attribute[] = 'sku';
				
				if($characteristic_names)
					foreach($characteristic_names as $key=>$name){
						if(in_array_recursive($name,$characteristic_fields) || in_array_recursive($name,$attribute)) continue;
						$characteristic_fields[] = ['name'=>$name, 'type'=>'text','value'=>$characteristic_values[$key]];
					}
				if($this_characteristic_names)
					foreach($this_characteristic_names as $key=>$name){
						if(in_array_recursive($name,$characteristic_fields) || in_array_recursive($name,$attribute)) continue;
						$characteristic_fields[] = ['name'=>$name, 'type'=>'text','value'=>$this_characteristic_values[$key]];
					}
				if($features)
					foreach ($features as  $key=>$name){
						if(in_array_recursive($name,$feature_fields)) continue;
						$feature_fields[] = ['type'=>'text','value'=>$name];
					}
				if($this_features)
					foreach ($this_features as  $key=>$name){
						if(in_array_recursive($name,$feature_fields)) continue;
						$feature_fields[] = ['type'=>'text','value'=>$name];
					}
						
				$attributes[0] = ['name'=>$attribute[0],'value'=>$entity->manufacturer,'type'=>'text'       ,'title'=>'Manufacturer','placeholder'=>'Manufacturer'];
				$attributes[1] = ['name'=>$attribute[1],'value'=>$entity->brand       ,'type'=>'text'       ,'title'=>'Brand'       ,'placeholder'=>'Brand'];
				$attributes[2] = ['name'=>$attribute[2],'value'=>$entity->model_no    ,'type'=>'text'       ,'title'=>'Model #'     ,'placeholder'=>'Model #'];
				$attributes[3] = ['name'=>$attribute[3],'value'=>$entity->part_no     ,'type'=>'text'       ,'title'=>'Part #'      ,'placeholder'=>'Part #'];
				$attributes[4] = ['name'=>$attribute[4],'value'=>$entity->sku         ,'type'=>'text'       ,'title'=>'SKU'         ,'placeholder'=>'SKU'];
				
				foreach($attributes as $field){
					$placeholder = $field['type'] != 'number' ? $field['title'] : false;
					$rows[] = elgg_format_element('div',['class'=>'rTableRow'],
								   elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],$field['title']).
								   elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
									    elgg_format_element('input', ['type'=>$field['type'], 'name' => "jot[$cid][{$field['name']}]", 'value' => $field['value'], 'placeholder'=>$placeholder,'disabled'=>$disabled])));
				}
				if($characteristic_fields)
					foreach($characteristic_fields as $key=>$field)
						$rows[] = elgg_format_element('div',['class'=>['rTableRow','has_value']],
									   elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],
											elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_names][]", 'value'=>$field['name'], 'placeholder'=>'Characteristic'])).
									   elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
											elgg_format_element('input', ['type'=>$field['type'], 'name'=>"jot[$cid][characteristic_values][]", 'value'=>$field['value'],'disabled'=>$disabled])).
									   elgg_format_element('nav',['class'=>['Row__actions__Yhon73Vo','undefined','TaskShow__actions--unfocused___3SQSv294']],$delete));
				$rows[] = elgg_format_element('div',['class'=>'rTableRow'],
							   elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],
									elgg_format_element('input', ['type'=>'text', 'name'=>"jot[$cid][characteristic_names][]", 'placeholder'=>'Characteristic'])).
							   elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
									elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_values][]", 'placeholder'=>'Value','class' => 'last_characteristic'])));
				$rows[] = elgg_format_element('div',['class'=>'new_characteristic','data-cid'=>$cid]);
				if($feature_fields)
					foreach($feature_fields as $key=>$field)
						$feature_rows[] = elgg_format_element('div',['class'=>['rTableRow','has_value']],
        									   elgg_format_element('input', ['type'=>$field['type'], 'name'=>"jot[$cid][features][]", 'value'=>$field['value'],'disabled'=>$disabled]).
        						               elgg_format_element('nav',['class'=>['Row__actions__Yhon73Vo','undefined','TaskShow__actions--unfocused___3SQSv294']],$delete));
				$feature_rows[] = elgg_format_element('div',['class'=>'rTableRow'],
                            		  elgg_format_element('div',['class'=>'rTableCell','style'=>'width:100%'],
							              elgg_format_element('input', ['type'=>'text', 'name'=>"jot[$cid][features][]",'class'=> 'last_feature','placeholder'=>'Feature'])));
				$feature_rows[] = elgg_format_element('div',['class'=>'new_feature','data-cid'=>$cid]);
				$content = elgg_format_element('div',['class'=>'rTable','style'=>'width:100%;margin-top:10px;'],
							   elgg_format_element('div',['class'=>['rTableBody','itemCharacteristics_Dcl702bW'],'data-cid'=>$cid],
								   implode('',$rows))).
                            elgg_view('page/elements/envelope',['task'=>'characteristic','action'=>$perspective, 'guid'=>$guid, 'parent_cid'=>$parent_cid,'carton_id'=>$carton_id, 'cid'=>$cid,'visible'=>'add','has_collapser'=>'no', 'hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'presence'=>$presence]).
							elgg_format_element('div',['class'=>'rTable','style'=>'width:100%'],
        		               elgg_format_element('div',['class'=>['rTableBody','itemFeatures_LU71nPs4'],'id'=>$cid.'_features','data-cid'=>$cid],
                                   elgg_format_element('div',['class'=>['rTableRow','pin']],
                                       elgg_format_element('div',['class'=>'rTableCell'],'<b>Features</b>')).
        		                   implode('',$feature_rows))).
                            elgg_view('page/elements/envelope',['task'=>'feature','action'=>$perspective, 'guid'=>$guid, 'parent_cid'=>$parent_cid,'carton_id'=>$carton_id, 'cid'=>$cid,'visible'=>'add','has_collapser'=>'no', 'hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'presence'=>$presence]);
				
        		$form = $hidden_fields.$content;
        		break;
                /****************************************
*add********** $section = 'individual'                   *****************************************************************
				 ****************************************/
            case 'individual':
                unset($hidden, $hidden_fields);
                if (!empty($hidden)){                
                    foreach($hidden as $key=>$field){
                        $hidden_fields .= elgg_view('input/hidden', $field);}}
                switch ($snippet){
                    case 'characteristics':
                        
                        break;
                    default:
                       	$pick = elgg_view('output/url', array(
                        		'text' => '[pick]',
                        		'class' => 'elgg-lightbox',
                        		'data-colorbox-opts' => '{"width":600, "height":525}',
                       			'href' => "pick_test/individual_characteristics/" . $entity->guid));
                       	$pick_menu = "<span title='Select individual characteristics'>$pick</span>";
                       	
                        if($entity->this_characteristic_names) $characteristic_names = is_array($entity->this_characteristic_names) ? $entity->this_characteristic_names : (array) $entity->this_characteristic_names;
                        if($entity->this_characteristic_values) $characteristic_values = is_array($entity->this_characteristic_values) ? $entity->this_characteristic_values : (array) $entity->this_characteristic_values;
                        if($entity->this_features) $features = is_array($entity->this_features) ? $entity->this_features : (array) $entity->this_features;
                        
                        $content = elgg_format_element("div",['class'=>'rTable', 'style'=>'width:100%;margin-top: 10px;'],
                                        elgg_format_element('div',['class'=>'rTableBody'],
                                            elgg_format_element('div',['class'=>'rTableRow'],
                                                elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:50%;padding:0px 5px'],
                                                    elgg_format_element('b',[],'Measure useage in')).
                                                elgg_format_element('div',['class'=>'rTableCell','style'=>'width:50%;padding:0px 5px'],
                                                    elgg_format_element('input',['type'=>'select','name'=>'item[frequency_units]', 'options'=>['hours', 'days', 'weeks', 'months', 'years', 'miles', 'cycles'],'value' => $entity->frequency_units,]))).
                                            elgg_format_element('div',['class'=>'rTableRow'],
                                                elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:50%;padding:0px 5px'],
                                                    elgg_format_element('b',[],'Access')).
                                                elgg_format_element('div',['class'=>'rTableCell','style'=>'width:50%;padding:0px 5px'],
                                                    elgg_format_element('input',['type'=>'access', 'name' => "jot[$cid][access_id]", 'value' => $entity->access_id]))).
                                            elgg_format_element('div',['class'=>'rTableRow'],
                                                elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:50%;padding:0px 5px'],
                                                    elgg_format_element('b',[],'Visibility')).
                                                elgg_format_element('div',['class'=>'rTableCell','style'=>'width:50%;padding:0px 5px'],
                                                    elgg_format_element('input',['type'=>'quebx_radio', 'name'=>"jot[$cid][visibility]", 'value' => $entity->visibility, 'align'=>'horizontal','default'=>'show','disabled'=>false, 'options'=>['Show in catalog'=>'show', 'Hide from Catalog'=>'hide'],])))));
                        
                        $content .= "<div class='rTable' style='width:100%;margin-top: 10px;'>
                        		<div class='rTableBody' id='individual_characteristics'>
                        			<div class='rTableRow pin'>
                        				<div class='rTableCell'><b>Individual Characteristics</b></div>
                        				<div class='rTableCell'>$pick_menu</div>
                        			</div>";
                        if($characteristic_names)
                            foreach($characteristic_names as $key=>$name){
                                if (($key+1) == count($characteristic_names)) $last_class = 'last_characteristic';
                                $content .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:218px'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][this_characteristic_names][]", 'value'=>$name, 'placeholder'=>'Characteristic'])).
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:420px'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][this_characteristic_values][]", 'value'=>$characteristic_values[$key], 'placeholder'=>'Value', 'class'=>$last_class])));
                            }
                        else
                            $content .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:218px'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][this_characteristic_names][]", 'placeholder'=>'Characteristic'])).
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:420px'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][this_characteristic_values][]", 'placeholder'=>'Value','class' => 'last_characteristic',])));
                        $content .= '<div class="new_individual_characteristic1"></div>';
                        $content .= "</div>
                        			</div>";
                        $content .="
                        	<div class='rTable' style='width:100%'>
                        		<div class='rTableBody' id='individual_features'>
                                    <div class='rTableRow pin'>
                                        <div class='rTableCell'><b>Individual Features</b></div>
                                    </div>";
                        if($features){
                            if (($key+1) == count($features)) $last_class = 'last_feature';
                            foreach ($features as  $key=>$name)
                                $content .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:630px'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][this_features][]", 'value'=>$name, 'placeholder'=>'Feature', 'class'=>$last_class])));
                            }
                        else 
                            $content .="
                            			<div class='rTableRow' style='cursor:move'>
                            				<div class='rTableCell' style='width:630px'>".
                            			      elgg_view('input/text', array(
                            						'name'       => "jot[$cid][this_features][]",
                            			      		'class'      => 'last_feature',
                            			      		'placeholder'=>'Feature',
                            					))."
                            		        </div>
                            			</div>";
                        
                        $content .= "<div class='new_individual_feature1'></div>";
                        $content .= "</div>
                        			</div>";
                }
                
                $form = $hidden_fields.$content;
                break;
				/****************************************
*add********** $section = 'media'                      *****************************************************************
				 ****************************************/
            case 'media':
                unset($hidden, $hidden_fields);
                if($guid && $guid!=0)
                    $action = 'edit';
                $owner           = get_entity($jot->participant_id ?: $owner_guid);
            	$owner_names     = explode(' ',$owner->name);
            	$owner_initials  = strToUpper(str_split($owner_names[0])[0]).strToUpper(str_split($owner_names[1])[0]);
                $media_info_boqx = 
					elgg_format_element('ol',['class'=>['comments','all_activity'],'data-aid'=>'comments']).
					elgg_format_element('div',['class'=>['GLOBAL__activity','comment','CommentEdit___3nWNXIac','CommentEdit--new___3PcQfnGf'],'tabindex'=>'-1','data-aid'=>'comment-new'],
						elgg_format_element('div',['class'=>'CommentEdit__commentBox___21QXi4py'],
							elgg_format_element('div',['class'=>'CommentEdit__textContainer___2V0EKFmS'],
								elgg_format_element('div',['data-aid'=>'CommentGutter','class'=>'CommentGutter___1wlvO_PP'],
									elgg_format_element('div',['data-aid'=>'Avatar','class'=>'_2mOpl__Avatar'],$owner_initials)).
								 elgg_format_element('div',['class'=>'CommentEdit__textEditor___3L0zZts-','data-aid'=>'CommentV2TextEditor'],
									 elgg_format_element('div',['class'=>'MentionableTextArea___1zoYeUDA'],
										 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
											 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
												 elgg_view('input/dropzone', ['name'=>"jot[$cid][files]",'default-message'=>'<strong>Drop files here</strong><br /><span>or click to select from your computer</span>','max'=>25,'accept'=>'image/*, video/*, application/vnd.*, application/pdf, text/plain','multiple'=>true,'style'=>'padding:0;','container_guid'=>$owner_guid,]))))))));
				$media_input = elgg_view('page/elements/envelope',['task'=>'media', 'action'=>$action, 'guid'=>$guid,'parent_cid'=>$cid,'info_boqx'=>$media_info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>'add', 'title'=>'Media','has_collapser'=>'yes','origin'=>'forms/market/edit>'.$perspective.'>'.$section.'>'.$snippet]);
				$form        = elgg_format_element('section',['class'=>'ItemMedia__Nyaa0xmV'],
									elgg_format_element('div',['class'=>'Activity___2ZLT4Ekd','data-cid'=>$cid],
									    $media_input));
                break;
				/****************************************
*add********** $section = 'gallery'                      *****************************************************************
				 ****************************************/
            case 'gallery':
                unset($hidden, $hidden_fields);
                $gallery = elgg_view('forms/market/edit/gallery',$vars);
                $form = $hidden_fields.$gallery;
                break;
				/****************************************
*add********** $section = 'library'                      *****************************************************************
				 ****************************************/
            case 'library':
                unset($hidden, $hidden_fields);
                
                $form = $hidden_fields.'library';
                break;
				/****************************************
*add********** $section = 'maintenance'                      *****************************************************************
				 ****************************************/
            case 'maintenance':
                unset($hidden, $hidden_fields);
                
                $form = $hidden_fields.'Maintenance';
                break;
				/****************************************
*add********** $section = 'inventory'                      *****************************************************************
				 ****************************************/
            case 'inventory':
                unset($hidden, $hidden_fields);
                
                $form = $hidden_fields.'Inventory';
                break;
				/****************************************
*add********** $section = 'experiences'                      *****************************************************************
				 ****************************************/
            case 'experiences':
                unset($hidden, $hidden_fields);
                
                $form = $hidden_fields.'Experiences';
                break;
				/****************************************
*add********** $section = 'contents'                      *****************************************************************
				 ****************************************/
            case 'contents':
                unset($hidden, $hidden_fields);
				$task   = 'thing';
				$aspect = 'contents';
				$params = array_merge($vars,['boqx_id'     => $parent_cid,
											 'boqx_guid'   => $boqx_guid,
				                             //'guid'         => 0,
											 //'parent_cid'   => $cid ?: quebx_new_id('c'),
											 'parent_cid'   => $parent_cid,
				                             'cid'          => $cid,
											 'fill_level'   => '0',
//											 'perspective'  => 'edit',
//				                             'section'      => 'item edit',
				                             'section'      => 'contents add',
											 'action'       => 'add',
											 'form_method'  => 'pack',
											 'presentation' => 'pallet',
											 'proximity'    => 'in',
											 'aspect'       => $aspect,
											 'display_state'=> 'edit',
				                             'cancel_class' => 'cancelReplace__mw0ODp0p',
											 'origin'       => 'forms/market/edit>'.$perspective.'>'.$section]);
				unset($params['guid']);
				$info_boqx = elgg_view("forms/market/edit",$params);
				$form      = elgg_view('page/elements/envelope',['class'=>'contentsAdd_P1C3VSjT', 'task'=>$task,'action'=>$action,'has_collapser'=>false, 'guid'=>$guid, 'presentation'=>$presentation, 'presence'=>$presence, 'parent_cid'=>$parent_cid,'envelope_id'=>$envelope_id,'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields, 'info_boqx'=>$info_boqx, 'visible'=>$visible,'origin'=>'forms/market/edit>'.$perspective.'>'.$section.'>'.$snippet]);
                    
                break;
               /****************************************
*add********** $section = 'contents add'                       *****************************************************************
               ****************************************/
            case 'contents add':
                unset($form_body, $hidden, $hidden_fields);
                $proximity = elgg_extract('proximity', $vars, 'boqx');
                $form_method = $presence == 'empty boqx' ? 'stuff' : 'post';
                $form_method = elgg_extract('form_method', $vars);
//                 if($origin != 'forms/market/edit>edit>single_thing' && $origin != 'forms/market/edit>add>contents'){
//                     unset($form_id);
    				$hidden[] =['name'=>"jot[cid]"                , 'value' => $parent_cid];
    				$hidden[] =['name'=>"jot[$parent_cid][aspect]", 'value' => 'things'];
    				$hidden[] =['name'=>"jot[$parent_cid][boqx]"  , 'value' => $boqx_cid ?: '...'];
    				$hidden[] =['name'=>"jot[$parent_cid][guid]"  , 'value' => $boqx_guid];
    				$hidden[] =['name'=>"jot[$parent_cid][cid]"   , 'value' => $parent_cid];
//                }
                $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
                $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
				if($guid)
					$hidden[] =['name'=>"jot[$cid][guid]"  , 'value' => $guid];
                $hidden[] =['name'=>"jot[$cid][proximity]" , 'value' => $proximity];
                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => $aspect    , 'data-focus-id' => "Aspect--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][contents]"  , 'value' => 'qim'      , 'data-focus-id' => "Contents--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => $fill_level, 'data-focus-id' => "FillLevel--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => "$n"       , 'data-focus-id' => "SortOrder--{$cid}"];
                //$qid_n = "{$cid}_{$n}";
                $collapser          = elgg_format_element('a',['class'=>'collapser-item', 'id'=>"item_collapser_{$cid}",'data-cid'=>"$cid", 'tabindex'=>'-1', 'style'=>'margin-top:0;']);
                $view               = 'forms/transfers/edit';
                $unpack_class       ='';
                $unpack_title_label = 'Unpack this item';
                $unpack_label       = 'Unpack item';
                $clone_class        ='';
                $delete_class       ='';
                $cancel_class       = elgg_extract('cancel_class', $vars, 'cancel-pallet');
                if ($presence == 'empty boqx') $cancel_class='cancelReplace__mw0ODp0p';                
                switch($aspect){
                    case 'item':
                    case 'q_item':
                    case 'qim':
                        $unpack_class    = 'selected';
                        $unpack_toggle   = 1;
                        $unpack_tag_toggle = 'display:inline;';
                        $clone_disabled  = 'disabled';
                        $clone_title_label = ' (disabled)';
                        $delete_disabled = 'disabled';
                        $delete_title_label = ' (disabled)';
                        break;
                    case 'receipt_item':
                        $unpack_toggle   = 0;
                        $unpack_tag_toggle = 'display:none;';
                        $clone_disabled  = 'disabled';
                        $clone_title_label = ' (disabled)';
                        $delete_disabled = 'disabled';
                        $delete_title_label = ' (disabled)';		                                
                        break;
                }
                if ($unpacked){
                        $unpack_disabled = 'disabled';
                        $unpack_title_label = 'Item unpacked';
                        unset($unpack_label);
                }
                $style_add = $style_show = $style_edit = "style='display:none;'";
                switch ($display_state){case 'add':  unset($style_add);  break;
    	                                case 'show': unset($style_show); break;
										case 'view':
    	                                case 'edit': $style_edit = "style='display:flex;'"; break;}                        
                $hidden[] =['name'=>"jot[$cid][unpack]"    , 'value' => $unpack_toggle , 'data-focus-id' => "Unpack--{$cid}"];
                $hidden_fields = quebx_format_elements('hidden', $hidden);
                if($display_state == 'view')
					unset($hidden_fields);
                $unpack_button = elgg_format_element('button',['class'=>['autosaves', 'unpack_item', 'hoverable', 'left_endcap', $unpack_class],
                                                               'title'=>$unpack_title_label,
                                                               'id'=>"item_unpack_button_$cid",
                                                               'data-aid'=>'Unpack',
                                                               'tabindex'=>'-1',
                                                                'disabled'=>$unpack_disabled]);
                $clone_button  = elgg_format_element('button',['class'=>['autosaves', 'clone_item', 'hoverable', 'left_endcap', $clone_class],
                                                               'title'=>'Clone this item'.$clone_title_label,
                                                               'id'=>"item_clone_button_$cid",
                                                               'data-aid'=>'Clone',
                                                               'tabindex'=>'-1',
                                                                'disabled'=>$clone_disabled]);
                $delete_button  = elgg_format_element('button',['class'=>['autosaves', 'delete', 'hoverable', 'right_endcap', $delete_class],
                                                                'title'=>'Delete this item'.$delete_title_label,
                                                                'id'=>"item_delete_button_$cid",
                                                                'data-aid'=>'Delete',
                                                                'tabindex'=>'-1',
                                                                 'disabled'=>$delete_disabled]);
                if ($aspect == 'receipt_item'){
            	            $section_vars = $vars;
            	            $section_vars['section'] = 'acquisition details';
            	            $acquisition_details  = elgg_view('forms/market/edit',$section_vars);
            	        }
            	$labels_maker = elgg_view('forms/transfers/edit',[ 
										  'perspective'   => $perspective,
										  'section'       =>'labels_maker',
            	                          'display_state' => $display_state,
            	                          'parent_cid'    => $parent_cid,
										  'cid'           => $cid,
            	                          'entity'        => $entity]);
				$fieldset    = elgg_format_element('fieldset',['class'=>'name'],
									 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6','style'=>'display: flex;'],
										 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'flex-basis: 320px;'],
											 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
												 elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_{$cid}"],'data-aid'=>'name','value'=>$entity->title, 'tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'name'=>"jot[$cid][title]",'placeholder'=>'Item name', 'disabled'=>$disabled],
													  $entity->title)))));
                $boqx_header = elgg_format_element('section',['class'=>'boqx_header'],
                                     $collapser.
									 /**/
									 $fieldset);
                $nav_controls             = elgg_view('navigation/controls',['form_method'=>$form_method,'form_id'=>$form_id,'parent_cid'=>$parent_cid,'wrapper_cid'=>$wrapper_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$action,'presentation'=>$presentation,'presence'=>$presence, 'display_state'=>$display_state,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'delete_action'=>'disable','maximize'=>true,'cancel'=>true,'cancel_class'=>$cancel_class,'action'=>true,'submit'=>$submit],'forms/market/edit>'.$perspective.'>'.$section]);
                $item_details_class       = ['ItemEdit__descriptionContainer___Mr67pXjd',"ItemEditContainer__$cid"];
                $inventory_details_class  = ['InventoryEdit_descriptionContainer__JCesEC6l',"InventoryEditContainer__$cid"];
                if($presence == 'empty boqx'){
                    $item_details_class[] = 'open';
                }
				$options                  = ['perspective'=>'edit', 'section'=>'item details','presentation'=>$presentation,'presence'=>$presence, 'display_state'=>$display_state, 'cid'=>$cid];
				if($guid) $options['guid']= $guid;
/*                $item_details             = elgg_format_element('div',['class'=>'ShowItemDetails__Uc3MWjrS'],
                    							elgg_format_element('div',['class'=>'ShowItemDetailsButton__qWXhMy9t','data-cid'=>$cid],
                    								elgg_format_element('h3',[],elgg_view_icon('settings-alt').' Item Details')).
                    							elgg_format_element('section',['class'=>$item_details_class],
                    								elgg_format_element('section',['class'=>['item-properties',"item-properties__$cid"]],
                    								    elgg_view('forms/market/edit', $options))));
*/				$options['section']       = 'item inventory';
                $item_inventory           = elgg_format_element('div',['class'=>'ShowInventoryDetails__Suh7f1o7'],
                    							elgg_format_element('div',['class'=>'ShowInventoryDetailsButton__7OEGZ2m3','data-cid'=>$cid],
                    								elgg_format_element('h3',[],elgg_view_icon('settings-alt').' Inventory')).
                    							elgg_format_element('section',['class'=>$inventory_details_class],
                    								elgg_format_element('section',['class'=>['item-properties',"item-properties__$cid"]],
                    								    elgg_view('forms/market/edit', $options))));                
                $form_vars                  = elgg_extract('form_vars', $vars,['id'=>$form_id,'ajax'=>true,'enctype'=>'multipart/form-data','action'=>'action/jot/edit_pallet']);
				if($display_state == 'view') unset($hidden_fields);
    	        $form_vars['body']        = $hidden_fields.
        				                    $boqx_header.
        									elgg_format_element('section',['class'=>'ItemLedger__KY8DM3qs'               ,'data-cid'=>$cid],$nav_controls.$acquisition_details.$item_details.$item_inventory).
        									elgg_format_element('section',['class'=>'ItemLabels__HqGmyX2Y'               ,'data-cid'=>$cid],$labels_maker).
        									elgg_format_element('section',['class'=>['ItemMedia__Nyaa0xmV','cache']      ,'data-cid'=>$cid]).
        									elgg_format_element('section',['class'=>['ItemContents__aXLIZva0','cache']   ,'data-cid'=>$cid]).
        									elgg_format_element('section',['class'=>['ItemIssues__3d5EmH6b','cache']     ,'data-cid'=>$cid]).
        									elgg_format_element('section',['class'=>['ItemExperiences__yVf0QCHi','cache'],'data-cid'=>$cid]);
				
                if($display_state == 'view')
                     $boqx_contents_edit = $form_vars['body'];
                else $boqx_contents_edit = elgg_view_form('',$form_vars);
				$form                    = $boqx_contents_edit;
				break;
				/****************************************
*add********** $section = 'accessory'                      *****************************************************************
				 ****************************************/
            case 'accessory':
                unset($hidden, $hidden_fields);
                
                $form = $hidden_fields.'Accessory';
                break;
				/****************************************
*add********** $section = 'component'                      *****************************************************************
				 ****************************************/
            case 'component':
                unset($hidden, $hidden_fields);
                
                $form = $hidden_fields.'Component';
                break;
               /****************************************
*add********** $section = 'single_thing'                       *****************************************************************
               ****************************************/
            case 'single_thing':                                                                  $display.= "guid=$guid, perspective=$perspective, section=$section, snippet=$snippet, presentation=$presentation, presence=$presence, submit=$submit";
                $form_action            = elgg_extract('form_action', $vars);
                $form_vars              = ['name'=>$section, 'enctype'=>'multipart/form-data','action'=>"action/$form_action"];
                $section_vars           = array_merge($vars,['form_vars'=> $form_vars,'parent_cid'=> $cid,'perspective'=>'edit']);
                unset($section_vars['cid']);
                $form_version           = 'market/edit';
                $form                   = elgg_format_element('div',['id'=>$cid, 'class'=>['Effort__ATAgsAWL'],'data-boqx'=>$parent_cid, 'action'=>$perspective],
                                              elgg_format_element('span',['class'=>'efforts-eggs','data-aid'=>'effortCounts','data-cid'=>$cid,'data-guid'=>$guid]).
                                              elgg_view('forms/'.$form_version, $section_vars));
//                                              elgg_view_form($form_version, $form_vars, $section_vars));
                break;
               /****************************************
*add********** $section = default                       *****************************************************************
               ****************************************/
                default:
                $form = elgg_view('forms/market/edit',['perspective'=>$perspective, 'presentation'=>$presentation, 'presence'=>$presence, 'section'=>'main', 'display_state'=>$display_state, 'cid'=>$cid, 'entity'=>$entity]);
                break;            
        }
        break;
/****************************************
 * $perspective = 'edit'                  *****************************************************************************
 ****************************************/
    case 'edit':
        Switch ($section){
               /****************************************
*edit********** $section = 'acquisition details'         *****************************************************************
               ****************************************/
            case 'acquisition details':
                $item_header_qty   = 'Quantity';
                $item_header_tax   = 'Taxable';
                $item_header_cost  = 'Item Cost';
                $item_header_total = 'Total Cost';
                $form =  "<div class='row'>
                            <h4>Acquisition Details</h4>
            		    </div>
                        <div class='row'>
                            <div class='column_01'>
                                <label for='jot[$cid][qty]'>$item_header_qty</label>
                            </div>
                            <div class='column_02'>
                                ".elgg_view('input/number',   ['name'=>"jot[$cid][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."
                            </div>
                        </div>
                        <div class='row'>
                            <div class='column_01'>
                                <label for='jot[$cid][cost]'>$item_header_cost</label>
                                <div class='column_01b'>
                                    <label for='jot[$cid][taxable]'>$item_header_tax</label>".
                                    elgg_view('input/checkbox', ['name'=>"jot[$cid][taxable]",'value'=>1,'data-qid'=>$qid_n,'data-name'=>'taxable','default'=> false,])."
                                </div>
                            </div>
                            <div class='column_02'>
                                ".elgg_view('input/text', [ 'name'      => "jot[$cid][cost]",                                                                                                            
									 						'class'     => 'last_line_item',
										                    'data-qid'  => $qid_n,
										                    'data-name' => 'cost',
			    											'class'     => 'nString',
														   ])."
                            </div>
                        </div>
                        <div class='row'>
                            <div class='column_01'>
                                <label>$item_header_total</label>
                            </div>
                            <div class='column_02'>
                                <span id='{$cid}_line_total'></span><span class='{$cid}_line_total_raw line_total_raw'></span>
                            </div>
                        </div>";
                break;
               /****************************************
*edit********** $section = 'item details'               *****************************************************************
               ****************************************/
            case 'item details':
                $nav_controls         = elgg_view('navigation/controls',['form_method'=>'post', 'parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$perspective,'presentation'=>$presentation,'display_state'=>$display_state,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'maximize'=>true,'cancel'=>true,'action'=>true]]);
                $item_description     = elgg_view('input/description',['cid'=>$cid,'value'=>$entity->description,'display_state'=>$display_state]);
                $ancestry             = hypeJunction\Categories\get_hierarchy($entity->category, true, false);
                //$value                 = elgg_get_site_entity()->guid;
                $value                 = end($ancestry);
            	$boqx_name             = 'category';
                $options['aspects']    = quebx_boqx_aspect_options($boqx_name,['root'=>$value]);//, 'order_by_metadata'=>['name'=>'name','direction' => 'ASC', 'as' => 'text']]);
                $options['boqx_name']  = $boqx_name;
                $options['boqx_aspect']= 'category';
                $options['boqx_value'] = $value;
                $options['item_value'] = $entity->category;
                $options['ancestry']   = $ancestry;
                $options['cid']        = $cid;
                $options['menu_level'] = 1;
                $options['boqx_class'] ='compartmentBoqx__Cdil2TkU';
                $options['list_class'] ='pickList__Upq66A3H';
                $options['item_class'] ='pickItem__GaGSmQJ6';
                $options['crumb_class']='pickItem__ehybudK0';
                $options['label_class']='pickLabel__2JR0Zrcl';
                $weir_menu             = elgg_view('navigation/weir_menu', $options);
                $category_name         = get_entity($entity->category)->title ?: ($disabled ? '(No category)' : 'Select ...');
                $boqx_header = elgg_format_element('section',['class'=>'boqx_header'],
                                     elgg_format_element('fieldset',['class'=>'name'],
                                         elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6','style'=>'display: flex;'],
                                             elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'flex-basis: 100%;'],
                                                 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
                                                     elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_{$cid}"],'data-aid'=>'name','value'=>$entity->title, 'tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'name'=>"jot[$cid][title]",'placeholder'=>'Item name','disabled'=>$disabled],
                                                          $entity->title))))));                
				$boqx_selector = $disabled ?
				                 elgg_format_element('div',['class'=>['dropdown','category'],'data-selector'=>'boqx_category','data-cid'=>'$cid'],
                                     elgg_format_element('input',['type'=>'hidden','aria-hidden'=>'true','name'=>"jot[$cid][category]",'disabled'=>$disabled]).
                                     elgg_format_element('div',['id'=>"item_category_dropdown_$cid",'class'=>'selection item_0','tabindex'=>'-1'],
		                                 elgg_format_element('span',[],$category_name)))
								 :
				                 elgg_format_element('div',['class'=>['dropdown','category'],'data-selector'=>'boqx_category','data-cid'=>'$cid'],
                                     elgg_format_element('input',['type'=>'hidden','aria-hidden'=>'true','name'=>"jot[$cid][category]",'disabled'=>$disabled]).
                                     elgg_format_element('div',['id'=>"item_category_dropdown_$cid",'class'=>'selection item_0','tabindex'=>'-1'],
		                                 elgg_format_element('span',[],$category_name)).
                                     elgg_format_element('a',['id'=>"item_category_dropdown_{$cid}_arrow",'class'=>['arrow','target'],'tabindex'=>'-1']).
                                     elgg_format_element('section',['class'=>'pickCategory__VRYE6ZAO closed'],
                                         $weir_menu));
                           	
    	        $item_category     = elgg_format_element('div', ['class'=>['info_box']],
										 elgg_format_element('div', ['class'=>'info'],
											  elgg_format_element('div', ['class'=>'row'],
												  "<em>Category</em>".
												  $boqx_selector)));
    	        if($presentation=='pallet' || $presentation=='envelope' || $presentation == 'contents')   unset($nav_controls, $boqx_header);
    	        $form      = $boqx_header
    	                    .$item_description
    	                    .$nav_controls
    	                    .$item_category
    	                    .elgg_view('forms/market/edit', ['perspective'=>'add', 'presentation'=>$presentation, 'presence'=>$presence, 'display_state'=>$display_state, 'cid'=>$cid, 'guid'=>$guid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                break;
               /****************************************
*edit********** $section = 'item inventory'               *****************************************************************
               ****************************************/
            case 'item inventory':
                $inventory_details_class  = ['InventoryEdit_descriptionContainer__JCesEC6l',"InventoryEditContainer__$cid"];
                $nav_controls  = elgg_view('navigation/controls',['form_method'=>'post', 'parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$perspective,'presentation'=>$presentation,'display_state'=>$display_state,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'maximize'=>true,'cancel'=>true,'action'=>true]]);
                $boqx_header   = elgg_format_element('section',['class'=>'boqx_header'],
                                     elgg_format_element('fieldset',['class'=>'name'],
                                         elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6','style'=>'display: flex;'],
                                             elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'flex-basis: 100%;'],
                                                 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
                                                     elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_{$cid}"],'data-aid'=>'name','value'=>$entity->title, 'tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'name'=>"jot[$cid][title]",'placeholder'=>'Item name','disabled'=>$disabled],
                                                          $entity->title))))));
                $info_boqx     = elgg_view('forms/transfers/edit', ['perspective'=>'add','section'=>'boqx_contents','snippet'=>'inventory','presentation'=>$presentation, 'presence'=>$presence, 'perspective'=>'add', 'display_state'=>$display_state, 'cid'=>$cid, 'guid'=>$guid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
				if($presentation=='pallet' || $presentation=='envelope'){
				    unset($nav_controls, $boqx_header);
				    //wrap info_boqx in a cloak when presented within an expandable section 
				    $info_boqx     = elgg_format_element('section',['class'=>$inventory_details_class],
                    				  elgg_format_element('section',['class'=>['item-properties',"item-properties__$cid"]],
                    					   $info_boqx));
				}
				elseif ($presentation == 'contents')
				    unset($nav_controls, $boqx_header);
				else 
				    $info_boqx = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>'qim','pieces'=>$info_boqx,'title'=>'Inventory']);
				$form      = $boqx_header
    	                    .$nav_controls
    	                    .$info_boqx;
                break;
               /****************************************
*edit********** $section = 'item edit'                       *****************************************************************
               ****************************************/
            case 'item edit':
                unset($form_body, $hidden, $hidden_fields, $id_value);
                $proximity = elgg_extract('proximity', $vars, 'boqx');
                $form_method = $presence == 'empty boqx' ? 'stuff' : 'post';
                $form_method = elgg_extract('form_method', $vars);
// @EDIT 2020-06-23 - SAJ - Remove condition until the purpose is determined.
//                if($origin != 'forms/market/edit>edit>single_thing'){
//                    unset($form_id);
    				$hidden[] =['name'=>"jot[cid]"                , 'value' => $parent_cid];
    				$hidden[] =['name'=>"jot[$parent_cid][aspect]", 'value' => 'things'];
    				$hidden[] =['name'=>"jot[$parent_cid][boqx]"  , 'value' => $boqx_cid ?: '...'];
    				$hidden[] =['name'=>"jot[$parent_cid][guid]"  , 'value' => $boqx_guid];
    				$hidden[] =['name'=>"jot[$parent_cid][cid]"   , 'value' => $parent_cid];
//                }
                $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
                $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                $hidden[] =['name'=>"jot[$cid][guid]"      , 'value' => $guid];
                $hidden[] =['name'=>"jot[$cid][proximity]" , 'value' => $proximity];
                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => $aspect    , 'data-focus-id' => "Aspect--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][contents]"  , 'value' => 'qim'      , 'data-focus-id' => "Contents--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => $fill_level, 'data-focus-id' => "FillLevel--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => "$n"       , 'data-focus-id' => "SortOrder--{$cid}"];
                //$qid_n = "{$cid}_{$n}";
                $collapser          = elgg_format_element('a',['class'=>'collapser-item', 'id'=>"item_collapser_{$cid}",'data-cid'=>"$cid", 'tabindex'=>'-1', 'style'=>'margin-top:0;']);
                $view               = 'forms/transfers/edit';
                $id_value           = $guid;
                $unpack_class       ='';
                $unpack_title_label = 'Unpack this item';
                $unpack_label       = 'Unpack item';
                $clone_class        ='';
                $delete_class       ='';
                $cancel_class       = elgg_extract('cancel_class', $vars, 'cancel-pallet');
                if ($presence == 'empty boqx') $cancel_class='cancelReplace__mw0ODp0p';                
                switch($aspect){
                    case 'item':
                    case 'q_item':
                    case 'qim':
                        $unpack_class    = 'selected';
                        $unpack_toggle   = 1;
                        $unpack_tag_toggle = 'display:inline;';
                        $clone_disabled  = 'disabled';
                        $clone_title_label = ' (disabled)';
                        $delete_disabled = 'disabled';
                        $delete_title_label = ' (disabled)';
                        break;
                    case 'receipt_item':
                        $unpack_toggle   = 0;
                        $unpack_tag_toggle = 'display:none;';
                        $clone_disabled  = 'disabled';
                        $clone_title_label = ' (disabled)';
                        $delete_disabled = 'disabled';
                        $delete_title_label = ' (disabled)';		                                
                        break;
                }
                if ($unpacked){
                        $unpack_disabled = 'disabled';
                        $unpack_title_label = 'Item unpacked';
                        unset($unpack_label);
                }
                $style_add = $style_show = $style_edit = "style='display:none;'";
                switch ($display_state){case 'add':  unset($style_add);  break;
    	                                case 'show': unset($style_show); break;
										case 'view':
    	                                case 'edit': $style_edit = "style='display:flex;'"; break;}                        
                $hidden[] =['name'=>"jot[$cid][unpack]"    , 'value' => $unpack_toggle , 'data-focus-id' => "Unpack--{$cid}"];
                $hidden_fields = quebx_format_elements('hidden', $hidden);
                if($display_state == 'view')
					unset($hidden_fields);
                $unpack_button = elgg_format_element('button',['class'=>['autosaves', 'unpack_item', 'hoverable', 'left_endcap', $unpack_class],
                                                               'title'=>$unpack_title_label,
                                                               'id'=>"item_unpack_button_$cid",
                                                               'data-aid'=>'Unpack',
                                                               'tabindex'=>'-1',
                                                                'disabled'=>$unpack_disabled]);
                $clone_button  = elgg_format_element('button',['class'=>['autosaves', 'clone_item', 'hoverable', 'left_endcap', $clone_class],
                                                               'title'=>'Clone this item'.$clone_title_label,
                                                               'id'=>"item_clone_button_$cid",
                                                               'data-aid'=>'Clone',
                                                               'tabindex'=>'-1',
                                                                'disabled'=>$clone_disabled]);
                $delete_button  = elgg_format_element('button',['class'=>['autosaves', 'delete', 'hoverable', 'right_endcap', $delete_class],
                                                                'title'=>'Delete this item'.$delete_title_label,
                                                                'id'=>"item_delete_button_$cid",
                                                                'data-aid'=>'Delete',
                                                                'tabindex'=>'-1',
                                                                 'disabled'=>$delete_disabled]);
                if ($aspect == 'receipt_item'){
            	            $section_vars = $vars;
            	            $section_vars['section'] = 'acquisition details';
            	            $acquisition_details  = elgg_view('forms/market/edit',$section_vars);
            	        }
            	$labels_maker = elgg_view('forms/transfers/edit',[ 
										  'perspective'   => $perspective,
										  'section'       =>'labels_maker',
            	                          'display_state' => $display_state,
            	                          'parent_cid'    => $parent_cid,
										  'cid'           => $cid,
            	                          'entity'        => $entity]);
				$fieldset    = elgg_format_element('fieldset',['class'=>'name'],
									 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6','style'=>'display: flex;'],
										 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'flex-basis: 320px;'],
											 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
												 elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_{$cid}"],'data-aid'=>'name','value'=>$entity->title, 'tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'name'=>"jot[$cid][title]",'placeholder'=>'Item name', 'disabled'=>$disabled],
													  $entity->title)))));
                $boqx_header = elgg_format_element('section',['class'=>'boqx_header'],
                                     $collapser.
									 /**/
									 $fieldset);
                $nav_controls             = elgg_view('navigation/controls',['form_method'=>$form_method,'form_id'=>$form_id,'parent_cid'=>$parent_cid,'wrapper_cid'=>$wrapper_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$action,'presentation'=>$presentation,'presence'=>$presence, 'display_state'=>$display_state,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'delete_action'=>'disable','maximize'=>true,'cancel'=>true,'cancel_class'=>$cancel_class,'action'=>true,'submit'=>$submit],'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
//                $nav_controls             = elgg_view('navigation/controls',['form_method'=>'post','parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$perspective,'presentation'=>$presentation, 'display_state'=>$display_state,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'maximize'=>true,'cancel'=>true,'cancel_class'=>'cancel-pallet','action'=>true,'submit'=>$submit]]);
                $item_details_class       = ['ItemEdit__descriptionContainer___Mr67pXjd',"ItemEditContainer__$cid"];
                $inventory_details_class  = ['InventoryEdit_descriptionContainer__JCesEC6l',"InventoryEditContainer__$cid"];
                if($presence == 'empty boqx'){
                    $item_details_class[] = 'open';
//                     $info_boqx            = $boqx_header;
//                     $task                 = 'thing';
//                     $boqx_header          = elgg_view('page/elements/envelope',['task'=>$task,'action'=>$action,'has_collapser'=>false, 'guid'=>$guid, 'presentation'=>$presentation, 'presence'=>$presence, 'parent_cid'=>$parent_cid,'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields, 'info_boqx'=>$info_boqx, 'visible'=>$visible]);
                }
                $item_details             = elgg_format_element('div',['class'=>'ShowItemDetails__Uc3MWjrS'],
                    							elgg_format_element('div',['class'=>'ShowItemDetailsButton__qWXhMy9t','data-cid'=>$cid],
                    								elgg_format_element('h3',[],elgg_view_icon('settings-alt').' Item Details')).
                    							elgg_format_element('section',['class'=>$item_details_class],
                    								elgg_format_element('section',['class'=>['item-properties',"item-properties__$cid"]],
                    								    elgg_view('forms/market/edit', ['section'=>'item details','presentation'=>$presentation,'presence'=>$presence, 'perspective'=>$perspective, 'display_state'=>$display_state, 'cid'=>$cid,'guid'=>$guid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]))));                
                $item_inventory             = elgg_format_element('div',['class'=>'ShowInventoryDetails__Suh7f1o7'],
                    							elgg_format_element('div',['class'=>'ShowInventoryDetailsButton__7OEGZ2m3','data-cid'=>$cid],
                    								elgg_format_element('h3',[],elgg_view_icon('settings-alt').' Inventory')).
                    							elgg_view('forms/market/edit', ['section'=>'item inventory','presentation'=>$presentation,'presence'=>$presence, 'perspective'=>$perspective, 'display_state'=>$display_state, 'cid'=>$cid,'guid'=>$guid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]));                
                $form_vars                  = elgg_extract('form_vars', $vars,['id'=>$form_id,'ajax'=>true,'enctype'=>'multipart/form-data','action'=>'action/jot/edit_pallet']);
				if($display_state == 'view') unset($hidden_fields);
    	        $form_vars['body']        = $hidden_fields.
        				                    $boqx_header.
        									elgg_format_element('section',['class'=>'ItemLedger__KY8DM3qs'               ,'data-cid'=>$cid],$nav_controls.$acquisition_details.$item_details.$item_inventory).
        									elgg_format_element('section',['class'=>'ItemLabels__HqGmyX2Y'               ,'data-cid'=>$cid],$labels_maker).
        									elgg_format_element('section',['class'=>['ItemMedia__Nyaa0xmV','cache']      ,'data-cid'=>$cid]).
        									elgg_format_element('section',['class'=>['ItemContents__aXLIZva0','cache']   ,'data-cid'=>$cid]).
        									elgg_format_element('section',['class'=>['ItemIssues__3d5EmH6b','cache']     ,'data-cid'=>$cid]).
        									elgg_format_element('section',['class'=>['ItemExperiences__yVf0QCHi','cache'],'data-cid'=>$cid]);
				
                if($display_state == 'view')
                     $boqx_contents_edit = $form_vars['body'];
                else $boqx_contents_edit = elgg_view_form('',$form_vars);
				$form                    = $boqx_contents_edit;
                break;
            /****************************************
*edit********** $section = 'item aspect'        *****************************************************************
             * Receives the item guid and breaks out each individual 
             ****************************************/
                case 'item aspect':
                $item_aspects = elgg_extract('item_aspects', $vars);
                                
                if($item_aspects){
                    foreach($item_aspects as $item_aspect){
                        unset($aspect_vars);
                        $aspect_vars = array_merge($vars,['action'=>'add','perspective'=>'save','section'=>$item_aspect->section,'guid'=>$item_aspect->guid,'container_guid'=>$guid, 'visible'=>'show']);
                        unset($aspect_vars['cid']);
                        $aspects[]   =  elgg_view('forms/experiences/edit',$aspect_vars);
                    }
                }
                // Add a blank card
                unset($aspect_vars);
                $aspect_vars                   = $vars;
                $aspect_vars['action']         = 'add';
                $aspect_vars['perspective']    = 'add';
                $aspect_vars['section']        =  $item_aspect->section;
                $aspect_vars['guid']           = 0;
                $aspect_vars['container_guid'] = $guid;
                $aspects[]                     = elgg_view('forms/experiences/edit',$aspect_vars); 
                $form                          = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>'discoveries','pieces'=>$aspects,'title'=>'xxxxx'] );
                
                break;
				/****************************************
*edit********** $section = 'contents'                      *****************************************************************
				 ****************************************/
            case 'contents':
                $action = 'add';
                $pieces = 0;
                if($entity){
                    $guid = $entity->getGUID();
                    $contents = quebx_count_pieces($guid, 'contents','contents');
                    $tally    = quebx_count_pieces($guid, 'contents','count');
//                    	$contents = elgg_get_entities([
//                         'type' => 'object',
// //@EDIT 2020-05-06 - SAJ subtype 'market' changed to 'q_item'
//                    	    'subtypes' => array('q_item', 'item', 'contents'),
// //                   	  'subtypes' => array('market', 'item', 'contents'),
//                         'joins'    => array('JOIN elgg_objects_entity e2 on e.guid = e2.guid'),
//         				'wheres' => array(
//         					"e.container_guid = $guid",
//                             "NOT EXISTS (SELECT *
//                                          from elgg_entity_relationships s1
//                                          WHERE s1.relationship = 'component'
//                                            AND s1.guid_two = e.container_guid)"
//         				),
//                         'order_by' => 'e2.title',
//                         'limit' => false,
//         			]);
                }
                $item_contents['contents'] = elgg_view('forms/market/edit',['perspective'=>'add','section'=>'contents', 'boqx_guid'=>$guid,'parent_cid'=>$envelope_id,'carton_id'=>$carton_id]);
//@EDIT 2020-06-11 - SAJ - replacing the hierarchy with nested boqxes
/*                 if (count($contents)>0) {
                    if($presentation == 'carton')
                        $ul_id = $envelope_id;
                    else $ul_id = $parent_cid;
                    $action = 'edit';
//                    $pieces = count($contents);
                    $tally  = quebx_count_pieces($guid, 'contents','count');
                    $contents   = elgg_get_entities(['type'=>'object', 'subtypes'=>ELGG_ENTITIES_ANY_VALUE, 'limit' => false,]);
                    foreach ($contents as $content)
            			$elements[] = ['guid'=> $content->guid,'container_guid'=>$content->container_guid,'title'=> $content->title];
            		foreach ($elements as $element) {
            		    $id = $element['guid'];
            		    $parent_id = $element['container_guid'];
            		    $data[$id] = $element;
            		    $index[$parent_id][] = $id;
            		}
            		$display_options = ['data'=>$data,'index'=>$index,'aspect'=>'contents', 'parent_id'=>$entity->guid,'parent_cid'=>$ul_id,'ul_class'=>'hierarchy','collapsible'=>true,'collapse_level'=>1,'level'=>0,'links'=>false,'presentation'=>'contents','presence'=>$presence];
            		$item_contents = quebx_display_child_nodes_III($display_options);
                }*/
                if($tally>0){
                    foreach($contents as $content){
                        $boqx[] = elgg_view('forms/market/edit',['perspective'=>'edit','section'=>'contents_single_piece','parent_cid'=>$envelope_id,'guid'=>$content->getGUID(),'presentation'=>'contents']); 
                    }
                    $item_contents['contents'] .= implode('',$boqx);
                }
                switch($presentation){
                    case 'carton':
                        $info_boqx    = $item_contents['contents'];
                        $window_title = $tally==1 ? 'Item' : 'Items';
                        $show_title   = elgg_format_element('span',['class'=>'TaskShow__qty_7lVp5tl4'], $tally).
                                           elgg_format_element('span',['class'=>'TaskShow__title___O4DM7q'], $window_title);
                        if($tally>0 || $display_state == 'view')
                           $visible   = 'show';
                        if($display_state == 'view'){
                            $visible  = 'show';
                        }
                        $carton_contents = elgg_view('page/elements/envelope',['class'=>'item_drop', 'task'=>'contents', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'cid'=>$envelope_id, 'carton_id'=>$carton_id, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>'contents','presence'=>$presence,'display_state'=>$display_state,'visible'=>$visible,'title'=>$title,'show_title'=>$show_title,'has_collapser'=>'yes','allow_delete'=>false,'tally'=>$tally,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                        $form            = elgg_view_layout('carton',['cid'=>$parent_cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$carton_contents,'title'=>'Contents'] );
                        break;
                    default:
                        $form = $item_contents['contents'];
                        break;
                }
                break;
				/****************************************
*edit********** $section = 'contents_single_piece'          *****************************************************************
				 ****************************************/
            case 'contents_single_piece':
                /**
                 * Show:
                 *   Quantity
                 *   Replenish
                 *   Remove
                 *   Materialize
                 *   Move
                 *   Transfer
                 * 
                 */
                $essence          = elgg_extract('essence', $vars, 'unrealized');
                $edit_id          =  quebx_new_id('c');
//	            $guid             = $entity->getGUID();
//                $content = $entity;
                $tally            = quebx_count_pieces($guid, 'contents','count');
                $display_options  = [];
                if ($tally > 0)
                    $child_toggle = elgg_format_element('span',['class'=>['contentsToggle','contentsExpand_Vs2YepGp'], 'title'=>'expand','data-boqx'=>$parent_cid, 'data-target'=>$child_id],$tally);
                $display_options  = array_merge($display_options, ['guid'=>$guid,'aspect'=>'contents','boqx_id'=>$parent_cid,'cid'=>$cid,'carton_id'=>$carton_id,'child_toggle'=>$child_toggle,'icon'=>$icon,'has_description'=>isset($entity->description),'has_attachments'=>count($attachments)>0,'has_contents'=>$has_children,'fill_level'=>$tally,'presentation'=>'contents','presence'=>$presence,'mobility'=>'stationary']);
                $form_version     = 'market/edit';
                $form_vars        = elgg_extract('form_vars', $vars,['id'=>$form_id,'ajax'=>true,'enctype' => 'multipart/form-data', 'action' => 'action/jot/edit_pallet']);
                $body_vars        = [];
                switch($entity->getSubtype()){
            	    case 'market':
            	    case 'item':
            	    case 'qim':
            	        $display_options['task_aspect']= 'feature';
            	        $essence  = 'realized';
            	        break;
            	}
            	$display_options['essence']   = $essence;
                /********* Item *********
                *************************/
    	        $section_vars                = array_merge($vars,['section'=>'piece','parent_cid'=>$cid,'cid'=>$edit_id,'guid'=>$guid,'wrapper_cid'=> $boqx_cid,'form_id'=>$form_id,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
    	        $boqx_edit                   = elgg_view('forms/'.$form_version,$section_vars);
    	        
                /******** Media ******
                 ************************/
    	        $section_vars                = array_merge($vars,['section'=>'media','guid'=>$guid,'parent_cid'=>$parent_cid,'cid'=>$cid,'wrapper_cid'=> $boqx_cid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
		        $item_media                  = elgg_view('forms/'.$form_version,$section_vars); 
                
                /******** Contents ******
                 ************************/
                $section_vars                = array_merge($vars,['section'=>'contents','presentation'=>'carton','presence'=>'contents','parent_cid'=>$edit_id,'form_wrapper'=> ['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars],'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                unset($section_vars['cid']);
    	        if($presence == 'empty boqx')
                    unset($section_vars['form_wrapper'],$section_vars['carton_id']);
                $item_contents               = elgg_format_element('section',['class'=>'ItemContents__aXLIZva0','data-cid'=>$edit_id],
                                                   elgg_view('forms/'.$form_version,$section_vars));
                
                /**** Assembled Item  ***
                 ************************/           
//             	if ($unpacked) unset($boqx_details_add,$boqx_details_show);
/*                $boqx_details_edit          = elgg_view('forms/market/edit',[  'perspective'=> 'edit',
                                                        					   'section'=> 'single_thing',
                                                        					   'form_method'=> 'pack',
                                                        					   'parent_cid'=> $parent_cid,
                                                        					   'guid'=> $guid,
                                                        					   'aspect'=> 'contents',
                                                        					   'presentation'=> 'pallet',
                                                        					   'presence'=> 'pallet']);
*/             	$boqx_details_edit           = elgg_format_element('section',['class'=>['ItemEdit___7asBc1YY','info_box','free'],'style'=>$style_edit,'data-aid'=>'ItemEdit','data-cid'=>$edit_id],
         	                                       elgg_format_element('div',['class'=>'ItemEditValue'],/*$hidden_fields.*/$boqx_edit.$item_inventory.$item_media.$item_contents));
        	    $class                       = ['Item__nhjb4ONn','boqx-item'];
            	$class[]                     = 'collapsed';
                $display_options['edit_boqx']= elgg_format_element('div',['id'=>$edit_id,'class'=>$class,'data-guid'=>$guid,'data-boqx'=>$cid,'data-carton'=>$carton_id,'data-aspect'=>$aspect,'data-aid'=>$action,'data-presentation'=>$presentation,'data-presence'=>$presence,'boqx-fill-level'=>'0'], 
                                                   $boqx_details_add.$boqx_details_show.$boqx_details_edit);

               $form = elgg_view('page/components/pallet_boqx', $display_options);
                
                break;
				/****************************************
*edit********** $section = 'piece'                  *****************************************************************
				 ****************************************/
                case 'piece':
    				$hidden[] =['name'=>"jot[cid]"                , 'value' => $parent_cid];
    				$hidden[] =['name'=>"jot[$parent_cid][aspect]", 'value' => 'things'];
    				$hidden[] =['name'=>"jot[$parent_cid][boqx]"  , 'value' => $boqx_cid ?: '...'];
    				$hidden[] =['name'=>"jot[$parent_cid][guid]"  , 'value' => $boqx_guid];
    				$hidden[] =['name'=>"jot[$parent_cid][cid]"   , 'value' => $parent_cid];
                    $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
                    $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                    $hidden[] =['name'=>"jot[$cid][guid]"      , 'value' => $guid];
                    $hidden[] =['name'=>"jot[$cid][proximity]" , 'value' => $proximity];
                    $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => $aspect    , 'data-focus-id' => "Aspect--{$cid}"];
                    $hidden[] =['name'=>"jot[$cid][contents]"  , 'value' => 'qim'      , 'data-focus-id' => "Contents--{$cid}"];
                    $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => $fill_level, 'data-focus-id' => "FillLevel--{$cid}"];
                    $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => "$n"       , 'data-focus-id' => "SortOrder--{$cid}"];
                    $hidden_fields = quebx_format_elements('hidden',$hidden);
                    $essence       = elgg_extract('essence', $vars, 'unrealized');
                    $form_method   = 'pack carton';
					$form_vars     = elgg_extract('form_vars', $vars,['id'=>$form_id,'ajax'=>true,'enctype' => 'multipart/form-data', 'action' => 'action/jot/edit_pallet']);
                    $boqx_header   = elgg_format_element('section',['class'=>'boqx_header'],
    					                 elgg_format_element('a',['class'=>'collapser-item', 'id'=>"item_collapser_{$cid}",'data-cid'=>"$cid", 'tabindex'=>'-1', 'style'=>'margin-top:0;']).  
                                         elgg_format_element('fieldset',['class'=>'name'],
                                             elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6','style'=>'display: flex;'],
                                                 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'flex-basis: 100%;'],
                                                     elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
                                                         elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_{$cid}"],'data-aid'=>'name','value'=>$entity->title, 'tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'name'=>"jot[$cid][title]",'placeholder'=>'Item name','disabled'=>$disabled],
                                                              $entity->title))))));
                    $nav_controls  = elgg_view('navigation/controls',['form_method'=>$form_method,'form_id'=>$form_id,'parent_cid'=>$parent_cid,'wrapper_cid'=>$wrapper_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$action,'presentation'=>$presentation,'presence'=>$presence, 'display_state'=>$display_state,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>($essence=='unrealized'),'unpack'=>true,'delete_action'=>'disable','maximize'=>false,'cancel'=>false,'cancel_class'=>$cancel_class,'action'=>true,'submit'=>$submit],'forms/market/edit>'.$perspective.'>'.$section]);
                    /********* Item *********
                    *************************/
        	        $item_details_class       = ['ItemEdit__descriptionContainer___Mr67pXjd',"ItemEditContainer__$cid"];
/*                    $item_details             = elgg_format_element('div',['class'=>'ShowItemDetails__Uc3MWjrS'],
                        							elgg_format_element('div',['class'=>'ShowItemDetailsButton__qWXhMy9t','data-cid'=>$cid],
                        								elgg_format_element('h3',[],elgg_view_icon('settings-alt').' Item Details')).
                        							elgg_format_element('section',['class'=>$item_details_class],
                        								elgg_format_element('section',['class'=>['item-properties',"item-properties__$cid"]],
                        								    elgg_view('forms/market/edit', ['section'=>'item details','presentation'=>$presentation,'presence'=>$presence, 'perspective'=>$perspective, 'display_state'=>$display_state, 'cid'=>$cid, 'guid'=>$guid]))));                
*/                
                    /******* Inventory*******
                    *************************/
        	        $section_vars  = array_merge($vars,['section'=>'item inventory','presentation'=>$presentation,'presence'=>$presence, 'display_state'=>$display_state, 'cid'=>$cid, 'guid'=>$guid]);
        	        $item_inventory= elgg_view('forms/market/edit',$section_vars);
                    /******** Labels ********
                    *************************/
        	        $labels_maker = elgg_view('forms/transfers/edit',array_merge($vars,['section'=>'labels_maker']));            	        
					
        	        $form_vars['body']        = $hidden_fields.
            				                    $boqx_header.
            									elgg_format_element('section',['class'=>'ItemLedger__KY8DM3qs'               ,'data-cid'=>$cid],$nav_controls.$item_details.$item_inventory).
            									elgg_format_element('section',['class'=>'ItemLabels__HqGmyX2Y'               ,'data-cid'=>$cid],$labels_maker).
            									elgg_format_element('section',['class'=>['ItemMedia__Nyaa0xmV','cache']      ,'data-cid'=>$cid]).
            									elgg_format_element('section',['class'=>['ItemContents__aXLIZva0','cache']   ,'data-cid'=>$cid]).
            									elgg_format_element('section',['class'=>['ItemIssues__3d5EmH6b','cache']     ,'data-cid'=>$cid]).
            									elgg_format_element('section',['class'=>['ItemExperiences__yVf0QCHi','cache'],'data-cid'=>$cid]);
        	        $form                     = elgg_view_form('',$form_vars);
                break;
               /****************************************
*edit********** $section = 'media'                       *****************************************************************
               ****************************************/
            case 'media':
                $default_album   = elgg_get_entities_from_metadata(['type'=>'object', 'subtype'=>'hjalbum','owner_guid'=>$owner->guid,'limit'=>1,'metadata values metadata_name_value_pairs'=>['name'=>'default']]);
            	$media_class     = 'media_drop';
            	$media_count     = 0;
    	        $carton_id       = quebx_new_id('c');
//    	        $form_id         = quebx_new_id('f');
                $form_vars       = ['enctype' => 'multipart/form-data', 'action' => 'action/shelf/upload','id'=>$form_id];
                $submit_class[]  ='dropZone__uNpSdLP4';
                $submit_button   = elgg_format_element('button',['type'=>'submit','form'=>$form_id,'class'=>$submit_class,'data-aid'=>'addEffortButton' ,'data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-presence'=>$presence,'value'=>'Add'],'Add');
                $body_vars       = ['submit_button'=>$submit_button];
                $image_guids     = [];
                if($guid && $guid!=0){
                    $action = 'edit';
                    $entity        = get_entity($guid);
                    $image_guids   = (array)$entity->images;
                    $image_guids[] = $entity->icon;                                                          //echo "<!-- icon: {$entity->icon}-->";
                    $image_guids   = array_unique(array_filter($image_guids));                               //echo "<!-- images: ".elgg_dump($image_guids)." -->";
                    $media_count   = count($image_guids);                                                         
                    $hidden[] = ['name'=>"jot[$cid][guid]",'value'=>$guid];
    	        }
                $media_output  = elgg_view('market/display/gallery',['entity'=>$entity,'image_guids'=>$image_guids, 'boqx'=>$cid,'carton_id'=>$carton_id,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                $media_thumb   = elgg_view('market/display/gallery',['entity'=>$entity,'image_guids'=>$image_guids,'section'=>'thumbnail_show','size'=>'tiny','origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
//    	        $media_output  = elgg_view('market/display/gallery',['entity'=>$entity,'image_guids'=>$image_guids, 'boqx'=>$parent_cid,'carton_id'=>$carton_id,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                $media_input[] = elgg_view('page/elements/envelope',['task'=>'media','action'=>$action,'guid'=>$guid,'carton_id'=>$carton_id,'parent_cid'=>$cid,'info_boqx'=>$media_output,'presentation'=>$presentation, 'presence'=>$presence,'fill_level'=>$media_count,'jot'=>['qty'=>$media_count,'title'=>$media_count==1?'piece':'pieces'],'visible'=>'edit','has_collapser'=>'yes','allow_delete'=>false,'origin'=>'forms/market/edit>'.$perspective.'>'.$section.'>'.$snippet]);
    	        $hidden[] = ['name'=>"container_guid" ,'value'=>$default_album[0]->guid];   // expected input for action/self/upload 
    	        if($presentation == 'pallet')                                               // this is a stand-alone media boqx 
    	           $hidden[] = ['name'=>"jot[boqx]"   ,'value'=>$cid];
    	        $hidden[] = ['name'=>"jot[$cid][cid]" ,'value'=>$cid];
    	        $hidden[] = ['name'=>"jot[$cid][boqx]",'value'=>$parent_cid];
    	        $hidden_fields = quebx_format_elements('hidden',$hidden);
    	        $owner           = get_entity($entity->participant_id ?: $owner_guid);
            	$owner_names     = explode(' ',$owner->name);
            	$owner_initials  = strToUpper(str_split($owner_names[0])[0]).strToUpper(str_split($owner_names[1])[0]);
            	$media_info_boqx = 
					elgg_format_element('ol',['class'=>['comments','all_activity'],'data-aid'=>'comments']).
					elgg_format_element('div',['class'=>['GLOBAL__activity','comment','CommentEdit___3nWNXIac','CommentEdit--new___3PcQfnGf'],'tabindex'=>'-1','data-aid'=>'comment-new'],
						elgg_format_element('div',['class'=>'CommentEdit__commentBox___21QXi4py'],
							elgg_format_element('div',['class'=>'CommentEdit__textContainer___2V0EKFmS'],
								elgg_format_element('div',['data-aid'=>'CommentGutter','class'=>'CommentGutter___1wlvO_PP'],
									elgg_format_element('div',['data-aid'=>'Avatar','class'=>'_2mOpl__Avatar'],$owner_initials)).
								 elgg_format_element('div',['class'=>'CommentEdit__textEditor___3L0zZts-','data-aid'=>'CommentV2TextEditor'],
									 elgg_format_element('div',['class'=>'MentionableTextArea___1zoYeUDA'],
										 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
											 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
												 elgg_view('input/gallery/filedrop', ['container_guid'=>$default_album[0]->getGUID(), 'default-message'=>'<strong>Drop files here</strong><br /><span>or click to select from your computer</span>','max'=>25,'accept'=>'image/*, video/*, application/vnd.*, application/pdf, text/plain','multiple'=>true,'compact'=>true,'style'=>'padding:0;']))))))));
				$media_input[] = elgg_format_element('section',['class'=>['ItemMedia__Nyaa0xmV',$media_class],'data-boqx'=>$cid,'data-cid'=>$cid,'data-carton'=>$carton_id,'data-presence'=>$presence],
//				$media_input[] = elgg_format_element('section',['class'=>['ItemMedia__Nyaa0xmV',$media_class],'data-boqx'=>$parent_cid,'data-cid'=>$cid,'data-carton'=>$carton_id,'data-presence'=>$presence],
									elgg_view('page/elements/envelope',['task'=>'media','action'=>$action,'guid'=>$guid,'parent_cid'=>$cid,'hidden_fields'=>$hidden_fields,'form_wrapper'=> ['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars],'info_boqx'=>$media_info_boqx,'show_boqx'=>$media_thumb,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>'add', 'has_collapser'=>'yes','fill_level'=>'open','origin'=>'forms/market/edit>'.$perspective.'>'.$section.'>'.$snippet]));
				$form          = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>'media','pieces'=>$media_input,'title'=>'Media','tally'=>$pieces]);
                break;
               /****************************************
*edit********** $section = 'single_thing'                       *****************************************************************
               ****************************************/
            case 'single_thing':                                                                  $display.= "guid=$guid, perspective=$perspective, section=$section, snippet=$snippet, presentation=$presentation, presence=$presence, submit=$submit";
                $hidden_fields         = elgg_extract('hidden', $vars,'');
                unset($vars['hidden']);
                switch($presentation){
                    case 'open_boqx':
            	        $section_vars  = array_merge($vars,['cid'=> $cid,'section'=>'item details']);
            	        $form_version  = 'market/edit';
            	        $form_vars     = ['name'=>'marketForm','enctype'=>'multipart/form-data','action'=>'action/jot/edit_pallet'];
                    	$form          = elgg_view_form($form_version,$form_vars,$section_vars);                        
                        break;
                    case 'envelope':
                    case 'empty boqx':
                    case 'nested':
                    case 'pallet':
                    case 'carton':
                        $element = 'item';
                        $delete = elgg_format_element("span",['class'=>'remove-item'], elgg_format_element('a', ['title' =>'remove item'],
                                                                    	                                                elgg_view_icon('delete-alt')));
                        $horizontal_offset = '-140';
                        
                        
                        $edit_properties_button = elgg_view('output/url', ['data-cid'            => $cid,
        												                   'class'               => "ItemEdit__showContainer",
                                                                           'text'                => elgg_view_icon('settings-alt').'Item Details', 
        										                           'title'               => 'show properties',]);
            	        $style_add = $style_show = $style_edit = "display:none;";
//            	        $style_add = $style_show = $style_edit = "style='display:none;'";
                        switch ($display_state){case 'add':  unset($style_add);  break;
            	                                case 'show': unset($style_show); break;
            	                                case 'view': echo '<!-- disabled='.$disabled.'-->';
            	                                case 'edit': $style_edit = "display:flex;"; break;}
                        $boqx_details_add = elgg_format_element('div',['tabindex'=>'0','class'=>'AddSubresourceButton___oKRbUbg6','style'=>$style_add,'data-aid'=>'TaskAdd','data-focus-id'=>'TaskAdd','data-cid'=>$cid,'data-guid'=>$guid],
                                            	 elgg_format_element('span',['class'=>'AddSubresourceButton__icon___h1-Z9ENT']).
                                            	 elgg_format_element('span',['class'=>'AddSubresourceButton__message___2vsNCBXi'],'Add an item'));
                        $boqx_details_show =elgg_format_element('div',['class'=>'ItemShow_Btc471up','style'=>$style_show,'data-aid'=>'TaskShow','data-cid'=>$cid],
                                                 elgg_format_element('div',['class'=>'ItemShow__line_gSpQE5Ao'],
                                                     elgg_format_element('span',['class'=>['ItemShow__title__8tlRYJcP']]).
                                					 elgg_format_element('span',['class'=>'ItemShow__item_total__Dgd1dOSZ'])).
            					                 elgg_format_element('div',[],
                                                     elgg_format_element('nav',['class'=>['ItemShow__actions__4AR4v8MP','ItemShow__actions--unfocused___1df6Nh5x']],
                                    					 elgg_format_element('button',['class'=>['IconButton___2y4Scyq6','IconButton--small___3D375vVd'],'data-aid'=>'delete','aria-label'=>'Delete','data-cid'=>$cid],$delete))));
            	        /********* Item *********
                        *************************/
                        $form_version                = 'market/edit';
//             	        $form_vars                   = elgg_extract('form_vars', $vars,              //$form_vars might be received from add>single_thing
//													   ['name' => 'marketForm', 
//														'enctype' => 'multipart/form-data', 
//														'action' => 'action/jot/edit_pallet']);
//                     	$boqx_details_edit = elgg_view_form($form_version,$form_vars,$section_vars);
            	        $section_vars                = array_merge($vars,['section'=>'item edit','parent_cid'=> $parent_cid,'cid'=>$cid,'carton_id'=>$carton_id,'wrapper_cid'=> $boqx_cid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
            	        $boqx_edit                   = elgg_view('forms/'.$form_version,$section_vars);
                        
                        /******** Media ******
                         ************************/
                        $form_version                = 'market/edit';
            	        $section_vars                = array_merge($vars,['section'=>'media','parent_cid'=>$parent_cid,'cid'=>$cid,'wrapper_cid'=> $boqx_cid,'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
				        $item_media                  = elgg_view('forms/'.$form_version,$section_vars); 
//                        unset($carton_id);
                        
                        /******** Contents ******
                         ************************/

                        $form_version                = 'market/edit';
                        $form_vars                   = ['enctype' => 'multipart/form-data', 'action' => 'action/jot/edit_pallet'];
                        $body_vars                   = [];
                        $section_vars                = array_merge($vars,['section'=>'contents','presentation'=>'carton','presence'=>'contents','parent_cid'=>$cid,'form_wrapper'=> ['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars],'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                        unset($section_vars['cid']);
            	        if($presence == 'empty boqx')
                            unset($section_vars['form_wrapper'],$section_vars['carton_id']);
                        $item_contents               = elgg_format_element('section',['class'=>'ItemContents__aXLIZva0','data-cid'=>$cid],
                                                           elgg_view('forms/'.$form_version,$section_vars));
                        
                        /******** Attachments ******
                         ************************/

                        $form_version                = 'market/edit';
                        $form_vars                   = ['enctype' => 'multipart/form-data', 'action' => 'action/jot/edit_pallet'];
                        $body_vars                   = [];
                        $section_vars                = array_merge($vars,['section'=>'attachments','presentation'=>'carton','presence'=>'contents','parent_cid'=>$cid,'form_wrapper'=> ['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars],'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                        unset($section_vars['cid']);
            	        if($presence == 'empty boqx')
                            unset($section_vars['form_wrapper'],$section_vars['carton_id']);
                        $item_attachments_xxx           = elgg_format_element('section',['class'=>'ItemContents__aXLIZva0','data-cid'=>$cid],
                                                           elgg_view('forms/'.$form_version,$section_vars));
                        
                        /******** Issues ********
                         ************************/
                        
                        $form_version                = 'experiences/edit';
                        $form_vars                   = ['enctype' => 'multipart/form-data', 'action' => 'action/jot/edit_pallet'];
                        $body_vars                   = [];
                        $section_vars                = array_merge($vars,['action'=> $perspective,'section'=> 'issues','aspect'=>'issues','presentation'=>'carton','presence'=>'contents','container_guid'=>$guid,'cid'=>$cid,'form_wrapper'=> ['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars],'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                        if($presence == 'empty boqx')
                            unset($section_vars['form_wrapper']);
                        unset($section_vars['perspective'],$section_vars['parent_cid'],$section_vars['carton_id'], $section_vars['form_method']);
//                        $section_vars                = array_merge($section_vars, ['form_method' => 'post']);
                        $item_issues                 = elgg_format_element('section',['class'=>'ItemIssues__3d5EmH6b','data-cid'=>$cid],
                                                           elgg_view('forms/'.$form_version,$section_vars));
                        
                        /****** Experiences ******
                         ************************/
                        
                        $form_version                = 'experiences/edit';
                        $form_vars                   = ['enctype' => 'multipart/form-data', 'action' => 'action/jot/edit_pallet'];
                        $body_vars                   = [];
                        $section_vars                = array_merge($vars,['action'=> $perspective,'section'=>'experiences','aspect'=>'experiences','presentation'=>'carton','presence'=>'contents','parent_cid'=>$cid,'form_wrapper'=> ['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars],'origin'=>'forms/market/edit>'.$perspective.'>'.$section]);
                        if($presence == 'empty boqx')
                            unset($section_vars['form_wrapper']);
                        unset($section_vars['perspective'],$section_vars['cid'],$section_vars['carton_id'], $section_vars['form_method']);
                        $section_vars                = array_merge($section_vars, ['form_method' => 'post','origin'=> 'forms/market/edit>'.$perspective.'>'.$section]);
                        $item_experiences            = elgg_format_element('section',['class'=>'ItemExperiences__yVf0QCHi','data-cid'=>$cid,'data-guid'=>$section_vars['guid']],
                                                           elgg_view('forms/'.$form_version,$section_vars));
                        
                        /**** Assembled Item  ***
                         ************************/
                    
                    	if ($unpacked) unset($boqx_details_add,$boqx_details_show);
                    	$boqx_details_edit           = elgg_format_element('section',['class'=>['ItemEdit___7asBc1YY','info_box','free'],'style'=>$style_edit,'data-aid'=>'ItemEdit','data-cid'=>$cid],
                	                                       elgg_format_element('div',['class'=>'ItemEditValue'],/*$hidden_fields.*/$boqx_edit.$item_media.$item_contents.$item_attachments.$item_issues.$item_experiences));
                	    $class                       = ['Item__nhjb4ONn','boqx-item'];
                    	if ($display_class)
                    	    $class[]                 = $display_class;
                        $single_thing                = elgg_format_element('div',['id'=>$cid,'class'=>$class,'data-guid'=>$guid,'data-boqx'=>$boqx_cid,'data-envelope'=>$envelope_id,'data-aspect'=>$aspect,'data-aid'=>$action,'data-presentation'=>$presentation,'data-presence'=>$presence,'boqx-fill-level'=>'0'], 
                                                                                  $boqx_details_add.$boqx_details_show.$boqx_details_edit);
        		                        
                       $form = $single_thing;
                       break;
                }
                break;
               /****************************************
*edit********** $section = default                       *****************************************************************
               ****************************************/
            default:
                $cid = elgg_extract('cid', $vars);
                if ($guid && !$entity)
                   $entity = get_entity($guid);
                if ($entity && !$guid)
                    $guid   = $entity->getGUID();
                $title  = $entity->title;
        	    $owner_name = $owner->name;
        	    $owner_initials = quebx_initials($owner_name);
        	    $requester_name = $owner_name;
        	    $requester_initials = $owner_initials;
                $parent_cid = elgg_extract('cid', $vars);
                $url = elgg_get_site_url().'market';
                $id_value = $guid;
                $form = "<div class='model $cid'>
                        	<div>
                        		<div id='view8059' data-scrollable='true' class='edit details'>
                        			<section class='edit' data-aid='StoryDetailsEdit' aria-expanded='true' tabindex='-1'>
                        			  <section class='model_details'>
                        				<form action='#' onsubmit='tracker.preventDefault.apply(this,arguments)' class='story model'>
                        				  <section class='story_or_epic_header'>
                        					<div class='autosaves collapser story_collapser_$cid' tabindex='0' aria-expanded='true' aria-label='$title' data-cid='$cid'></div>
                        					<fieldset class='name'>
                                                <div class='AutosizeTextarea___2iWScFt6'>
                                                    <div class='AutosizeTextarea__container___31scfkZp'>
                                                        <textarea aria-label='story title' data-aid='name' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy NameEdit___2W_xAa_R' name='jot[title]'>$entity->title</textarea>
                                                    </div>
                                                </div>
                                          </fieldset>
                        				  <a href='/story/show/$guid' type='button' class='autosaves maximize hoverable' id='story_maximize_$cid' tabindex='-1' title='Switch to a full page view of this item'></a>
                        				  </section>
                        				  <aside>
                        					<div class='wrapper'>
                        					  <nav class='edit'>
                                                <section class='controls'>
                        						  <div class='persistence use_click_to_copy'>
                        							<button class='autosaves cancel clear' type='reset' id='epic_submit_cancel_$cid' data-cid='$cid' tabindex='-1'>Cancel</button>
                        							<button class='autosaves button std close' type='submit' id='story_close_$cid' tabindex='-1'>Close</button>
                        						  </div>
                                                  <div class='actions'>
                                                      <div class='bubble'></div>
                                                      <button type='button' id='story_copy_link_$cid' title='Copy this link to the clipboard' data-clipboard-text='$url/view/$id_value' class='autosaves clipboard_button hoverable link left_endcap' tabindex='-1' $disabled></button>
                                                      <div class='button_with_field'>
                                                          <button type='button' id ='story_copy_id_$cid' title='Copy this ID to the clipboard' data-clipboard-text='$id_value' class='autosaves clipboard_button hoverable id use_click_to_copy' tabindex='-1' $disabled></button>
                                                          <input type='text' id='story_copy_id_value_$cid' readonly='' class='autosaves id text_value' value='$id_value' tabindex='-1'>
                                                    </div>
                                                    <button type='button' id='receipt_import_button_$cid' title='Import receipt (disabled)' class='autosaves import_receipt hoverable left_endcap' tabindex='-1' disabled></button>
                                                    <button type='button' id='story_clone_button_$cid' title='Clone this thing".$disabled_view_label."' class='autosaves clone_story hoverable left_endcap' tabindex='-1' $disabled></button>
                                                    <button type='button' id='story_history_button_$cid' title='View the history of this thing".$disabled_view_label."' class='autosaves history hoverable capped' tabindex='-1' $disabled></button>
                                                    <button type='button' id='story_delete_button_$cid' title='Delete this thing".$disabled_view_label."' class='autosaves delete hoverable right_endcap remove-card' data-qid=$qid tabindex='-1'$disabled></button>
                                                  </div>
        						               </section>
                        				    </nav>
                        					  <div class='info_box_wrapper'>
                        						<div class='story state_box'>
                        						  <div class='state row'><div class='StoryState___2vkCAl9L' data-aid='StoryState'><em>State</em><div class='Dropdown StoryState__dropdown___3GU-2fu0 StoryState__dropdown--disabled___179oZpFv'><div class='Dropdown__content' data-aid='StoryState__dropdown'><button class='SMkCk__Button _3INnV__Button--default Dropdown__button StoryState__dropdownButton___LdR9Y07L undefined _3Xvsn__Button--disabled' disabled='' tabindex='0' type='button'><span class='StoryState__dropdown--label___3qsLBfq3' data-aid='StoryState__dropdown--label'>Unscheduled <img src='//assets.pivotaltracker.com/next/assets/next/aa0730f7-arrow-light.svg' alt=''></span></button></div></div><span class='state'><label data-aid='StateButton' data-destination-state='start' class='state button start' tabindex='-1'>Start</label></span></div></div>
                        						  <div class='reviews'><div class='Reviews___3RL2ODu6' data-aid='Reviews'><div class='Reviews__controls___2HDGtk0b'><div class='Reviews__label___3eZCCaQO'>Reviews</div><div class='Dropdown'><div class='Dropdown__content' data-aid='Reviews__addReview'><button class='SMkCk__Button _3INnV__Button--default Dropdown__button Reviews__addReview___2qS8cLCf' aria-label='Reviews' type='button'><span class='Reviews__addReview--plus___1RlRoYng'>+</span><span>&nbsp;add review</span></button></div></div></div><div></div></div></div>
                        						 </div>
                        
                        						<div class='story info_box'>
                        						  <div class='info'><div class='type row'>
                        			  <em>Story Type</em>
                        			  <div class='dropdown story_type'>
                        			  
                        				<input aria-hidden='true' type='hidden' name='story[story_type]' value='feature'>
                        			  
                        			  <input aria-hidden='true' type='text' id='story_type_dropdown_".$cid."_honeypot' tabindex='0' class='honeypot'>
                
                        			  <a id='story_type_dropdown_$cid' class='selection item_feature' tabindex='-1'><span>feature</span></a>
                        
                        			  
                        				<a id='story_type_dropdown_".$cid."_arrow' class='arrow target' tabindex='-1'></a>
                        			  
                        
                        			  <section>
                        				<div class='dropdown_menu search'>
                        				  
                        					
                        					  <div class='search_item'><input aria-label='search' type='text' id='story_type_dropdown_".$cid_search."' class='search'></div>
                        					
                        				  
                        
                        				  <ul>
                        					
                        					  <li class='no_search_results hidden'>No results match.</li>
                        					
                        					
                        					  <li data-value='feature' data-index='1' class='dropdown_item selected'><a class='item_feature ' id='feature_story_type_dropdown_$cid' href='#'><span class='dropdown_label'>feature</span></a></li>
                        					
                        					  <li data-value='bug' data-index='2' class='dropdown_item'><a class='item_bug ' id='bug_story_type_dropdown_$cid' href='#'><span class='dropdown_label'>bug</span></a></li>
                        					
                        					  <li data-value='chore' data-index='3' class='dropdown_item'><a class='item_chore ' id='chore_story_type_dropdown_$cid' href='#'><span class='dropdown_label'>chore</span></a></li>
                        					
                        					  <li data-value='release' data-index='4' class='dropdown_item'><a class='item_release ' id='release_story_type_dropdown_$cid' href='#'><span class='dropdown_label'>release</span></a></li>
                        					
                        				  </ul>
                        				</div>
                        			  </section>
                        			</div>
                        
                        			</div>
                        
                        
                        
                        			<div class='estimate row'>
                        			  <em>Points</em>
                        			  <div class='dropdown story_estimate'>
                        			  
                        				<input aria-hidden='true' type='hidden' name='story[estimate]' value='0' data-type='number'>
                        			  
                        			  <input aria-hidden='true' type='text' id='story_estimate_dropdown_".$cid."_honeypot' tabindex='0' class='honeypot'>
                        			  <a id='story_estimate_dropdown_$cid' class='selection item_0' tabindex='-1'><span>0 points</span></a>
                        
                        			  
                        				<a id='story_estimate_dropdown_".$cid."_arrow' class='arrow target' tabindex='-1'></a>
                        			  
                        
                        			  <section>
                        				<div class='dropdown_menu search'>
                        				  
                        					
                        					  <div class='search_item'><input aria-label='search' type='text' id='story_estimate_dropdown_".$cid."_search' class='search'></div>
                        					
                        				  
                        
                        				  <ul>
                        					
                        					  <li class='no_search_results hidden'>No results match.</li>
                        					
                        					
                        					  <li data-value='-1' data-index='1' class='dropdown_item'><a class='item_-1 ' id='-1_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>unestimated</span></a></li>
                        					
                        					  <li data-value='0' data-index='2' class='dropdown_item selected'><a class='item_0 ' id='0_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>0 points</span></a></li>
                        					
                        					  <li data-value='1' data-index='3' class='dropdown_item'><a class='item_1 ' id='1_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>1 point</span></a></li>
                        					
                        					  <li data-value='2' data-index='4' class='dropdown_item'><a class='item_2 ' id='2_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>2 points</span></a></li>
                        					
                        					  <li data-value='3' data-index='5' class='dropdown_item'><a class='item_3 ' id='3_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>3 points</span></a></li>
                        					
                        				  </ul>
                        				</div>
                        			  </section>
                        			</div>
                        
                        			</div>
                        
                        
                        			<div class='requester row'>
                        			  <em>Requester</em>
                        			  <div class='dropdown story_requested_by_id'>
                        			  
                        				<input aria-hidden='true' type='hidden' name='story[requested_by_id]' value='2936271' data-type='number'>
                        			  
                        			  <input aria-hidden='true' type='text' id='story_requested_by_id_dropdown_".$cid."_honeypot' tabindex='0' class='honeypot'>
                        			  <a id='story_requested_by_id_dropdown_$cid' class='selection item_2936271' tabindex='-1'><span><div class='name hbsAvatarName'>$owner_name</div>
                        				<span class='selectable_owner_row_element hbsAvatar__container requester_link' data-person-id='2936271' tabindex='-1'><div class='hbsAvatar hbsAvatar__hasHoverCard' data-person-id='2936271'><span><span class='hbsAvatar__initials'>$owner_initials</span></span></div></span></span></a>
                        
                        			  
                        				<a id='story_requested_by_id_dropdown_".$cid_arrow."' class='arrow target' tabindex='-1'></a>
                        			  
                        
                        			  <section>
                        				<div class='dropdown_menu search'>
                        				  
                        					
                        					  <div class='search_item'><input aria-label='search' type='text' id='story_requested_by_id_dropdown_".$cid."_search' class='search'></div>
                        					
                        				  
                        
                        				  <ul>
                        					
                        					  <li class='no_search_results hidden'>No results match.</li>
                        					
                        					
                        					  <li data-value='2936271' data-index='1' class='dropdown_item selected'><a class='item_2936271 ' id='2936271_story_requested_by_id_dropdown_$cid' href='#'><span class='dropdown_description'>$requester_initials</span><span class='dropdown_label'>$requester_name</span></a></li>
                        					
                        				  </ul>
                        				</div>
                        			  </section>
                        			</div>
                        
                        			</div>
                        
                        			<div class='owner row'>
                        			  <em>Owners</em>
                        			  <div class='story_owners'>
                        			  <input aria-hidden='true' type='text' id='story_owner_ids_".$cid."_honeypot' tabindex='0' class='honeypot'>
                        			  <a id='add_owner_$cid' class='selectable_owner_row_element add_owner has_owners' tabindex='-1'>
                        				
                        			  </a>
                        			  
                        				<span class='selectable_owner_row_element hbsAvatar__container owner_link selected' data-person-id='2936271' tabindex='-1'><span class='wrapper hbsAvatarName'><span class='name'>Scott Jenkins</span></span><div class='hbsAvatar hbsAvatar__hasHoverCard' data-person-id='2936271'><span><span class='hbsAvatar__initials'>SJ</span></span></div></span>
                        			  
                        			</div>
                        
                        			</div>
                        			</div>
                        						  <div class='integration_wrapper'>
                        			  
                        
                        			</div>
                        						  <div class='followers_wrapper'><div class='following row' role='group' aria-label='followers'>
                        			  
                        				<em>Follow this story</em>
                        			  
                        			  <input type='hidden' name='story[following]' value='0'>
                        			  
                        				<input type='checkbox' id='$cid_following' aria-label='follow this story' checked='checked' disabled='true' class='autosaves std value' name='story[following]' value='on'>
                        			  
                        			  <span class='count not_read_only' data-cid='$cid'>1 follower</span>
                        			</div>
                        			</div>
                        						  
                        							<div class='row timestamp_wrapper'>
                        							  <div class='timestamp'>
                        							    <div class='timestamps clickable'>
                        								  <div class='saving timestamp_row'><span>Saving...</span></div>
                        								  <div class='updated_at timestamp_row'>Updated: <span data-millis='1560859061000'>18 Jun 2019, 6:57am</span></div>
                        								  <div class='requested_at timestamp_row'>Requested: <span data-millis='1498652166000'>28 Jun 2017, 7:16am</span></div>
                        								</div>
                        							  </div>
                        							</div>						  
                        						</div>
                        					  </div>
                        
                        
                        					  <div class='mini attachments'></div>
                        					</div>
                        				  </aside>
                        				</form>
                        			  </section>
                        			  <section class='blockers full'><div><div data-aid='Blockers'><h4>Blockers</h4><div class='BlockerShow___1hFt8_I1' data-aid='BlockerShow'><button class='BlockerShow__toggleButton___2GFmLA4H' data-aid='BlockerShow__toggleButton' data-focus-id='BlockerShow__toggleButton--c137' title='Resolve this blocker'></button><div tabindex='0' class='BlockerShow__description___3LsV-EfY' data-aid='BlockerShow__description'><span class='tracker_markup'><p>dddddd</p></span></div><div class='BlockerShow__controls___2bTZp0xk'><button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='BlockerShow__resolveButton' aria-label='Resolve blocker'><span class='iconClassName' title='Resolve blocker' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/5431490a-checkMark-thin.svg&quot;) center center no-repeat;'></span></button><button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='BlockerShow__deleteButton' aria-label='Delete blocker'><span class='' title='Delete blocker' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/6f796a82-trashcan.svg&quot;) center center no-repeat;'></span></button></div></div><div tabindex='0' class='AddSubresourceButton___2PetQjcb' data-aid='BlockerAdd' data-focus-id='BlockerAdd'><span class='AddSubresourceButton__icon___h1-Z9ENT'></span><span class='AddSubresourceButton__message___2vsNCBXi'>Add blocker or impediment</span></div></div></div></section>
                        			  <section class='blocking full'></section>
                        			  <section class='description full'><div data-aid='Description' class='Description___3oUx83yQ'><h4 id='description$cid'>Description</h4><div class='DescriptionShow___3-QsNMNj DescriptionShow__placeholder___1NuiicbF' tabindex='0' data-aid='renderedDescription' data-focus-id='DescriptionShow--$cid'>Add a description</div></div></section>
                        			  <section class='labels_container full'>
                        				<div id='story_labels_$cid' class='labels'><div class='StoryLabelsMaker___Lw8q4VmA'><h4>Labels</h4><div class='StoryLabelsMaker__container___2B23m_z1'><div data-aid='StoryLabelsMaker__contentContainer' class='StoryLabelsMaker__contentContainer___3CvJ07iU'><div class='Label___mHNHD3zD' data-aid='Label--c105--$cid' data-focus-id='Label--c105--$cid' tabindex='-1'><div class='Label__Name___mTDXx408' data-aid='Label__Name'>juxtaposition</div><div class='Label__RemoveButton___2fQtutmR' data-aid='Label__RemoveButton'></div></div><div class='Label___mHNHD3zD' data-aid='Label--c108--$cid' data-focus-id='Label--c108--$cid' tabindex='-1'><div class='Label__Name___mTDXx408' data-aid='Label__Name'>mechanical</div><div class='Label__RemoveButton___2fQtutmR' data-aid='Label__RemoveButton'></div></div><div class='Label___mHNHD3zD' data-aid='Label--c114--$cid' data-focus-id='Label--c114--$cid' tabindex='-1'><div class='Label__Name___mTDXx408' data-aid='Label__Name'>sophisticated</div><div class='Label__RemoveButton___2fQtutmR' data-aid='Label__RemoveButton'></div></div><div class='Label___mHNHD3zD' data-aid='Label--c120--$cid' data-focus-id='Label--c120--$cid' tabindex='-1'><div class='Label__Name___mTDXx408' data-aid='Label__Name'>wonder</div><div class='Label__RemoveButton___2fQtutmR' data-aid='Label__RemoveButton'></div></div><div class='LabelsSearch___2V7bl828' data-aid='LabelsSearch'><div class='tn-text-input___1CFr3eiU LabelsSearch__container___kJAdoNya'><div><input autocomplete='off' class='tn-text-input__field___3gLo07Il tn-text-input__field--medium___v3Ex3B7Z LabelsSearch__input___3BARDmFr' type='text' placeholder='' data-aid='LabelsSearch__input' data-focus-id='LabelsSearch--$cid' aria-label='Search for an existing label or type a new label' value=''></div></div></div></div><a class='StoryLabelsMaker__arrow___OjD5Om2A' data-aid='StoryLabelsMaker__arrow'></a></div></div></div>
                        			  </section>
                        			  <section class='code full' data-aid='code'><div data-aid='Code' class='Code___3pLWnu1D'><h4 class='Code__heading___2LJTrLuO'><a href='/help/articles/github_integration' target='_blank' class='Code__menuHelp___3NHpSmo9'>Code</a></h4><input data-aid='GitHubAttach__input' aria-label='GitHub Paste Link' class='GitHubAttach__input___3-hGhNzg' type='text' placeholder='Paste link to pull request or branch...' value=''></div></section>
                        			  <section class='tasks full'><div><div data-aid='Tasks'><span class='tasks_count' data-aid='taskCounts'><h4>Tasks (0/1)</h4></span><div><div class='TaskShow___2LNLUMGe' data-aid='TaskShow' draggable='true'><input type='checkbox' title='mark task complete' data-aid='toggle-complete' data-focus-id='TaskShow__checkbox--c136' class='TaskShow__checkbox___2BQ9bNAA'><div tabindex='0' class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription'><span class='tracker_markup'><p>jjj</p></span></div><nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'><button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete'><span class='' data-click-aid='delete' title='Delete' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/6f796a82-trashcan.svg&quot;) center center no-repeat;'></span></button></nav></div><div tabindex='0' class='AddSubresourceButton___2PetQjcb' data-aid='TaskAdd' data-focus-id='TaskAdd'><span class='AddSubresourceButton__icon___h1-Z9ENT'></span><span class='AddSubresourceButton__message___2vsNCBXi'>Add a task</span></div></div></div></div></section>
                        			  <section class='activity full'><div><div class='Activity___2ZLT4Ekd Activity--sequential___snOLHrxL'><div class='Activity__header___2pU2Tw9L'><h4 class='Activity__title___2uuNQeA8 tn-comments__activity'>Activity</h4><div class='ToggleComment__Container___eOafaqW5'><span class='ToggleHeading___1K1l1zUE'>Sort by</span><span data-aid='ToggleComment' class='ToggleComment___yucMHq3w' role='button'><span data-aid='ToggleStatus' class='ToggleStatus___34uUfSHP' tabindex='0'>Oldest to newest</span></span></div></div><ol class='comments all_activity' data-aid='comments'><li class='item___3FqFqgaA'><div class='GLOBAL__activity comment CommentEdit___3nWNXIac CommentEdit--new___3PcQfnGf' tabindex='-1' data-aid='comment-new'><div class='CommentEdit__writePreview-bar___1aXEb92m'><div><button class='CommentEdit__tab___qUF4n2tB' data-aid='WriteComment'>Write</button><button class='CommentEdit__tab___qUF4n2tB CommentEdit__tab--disabled___2C0MLjfb' data-aid='PreviewComment'>Preview</button></div><a href='/help/markdown' class='CommentEdit__markdown_help___lvuA4kSr' target='_blank' tabindex='0' title='Markdown help' data-focus-id='FormattingHelp__link--$cid'>Formatting help</a></div><div class='CommentEdit__commentBox___21QXi4py'><div class='CommentEdit__textContainer___2V0EKFmS'><div data-aid='CommentGutter' class='CommentGutter___1wlvO_PP'><div><div data-aid='Avatar' class='Avatar Avatar--initials'>SJ</div></div></div><div class='CommentEdit__preview___2yY8VPnu'><span class='tracker_markup'><p>Preview your <a href='/help/markdown' target='_blank' rel='noopener' tabindex='-1'>Markdown formatted</a> text here.</p></span></div><div class='CommentEdit__textEditor___3L0zZts-' data-aid='CommentV2TextEditor'><div class='MentionableTextArea___1zoYeUDA'><div class='AutosizeTextarea___2iWScFt6'><div class='AutosizeTextarea__container___31scfkZp'><textarea id='comment-edit-$cid' aria-label='Comment' data-aid='Comment__textarea' data-focus-id='CommentEdit__textarea--$cid' class='AutosizeTextarea__textarea___1LL2IPEy tracker_markup MentionableTextArea__textarea___2WDXl0X6 CommentEdit__textarea___2Rzdgkej' placeholder='Add a comment or paste an image'></textarea></div><div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt tracker_markup MentionableTextArea__textarea___2WDXl0X6 CommentEdit__textarea___2Rzdgkej'><span></span><span>w</span></div></div></div></div></div><div class='CommentEdit__action-bar___3dyLnEWb'><div class='CommentEdit__button-group___2ytpiQPa'><button class='SMkCk__Button QbMBD__Button--primary _3olWk__Button--small undefined _3Xvsn__Button--disabled' disabled='' data-aid='comment-submit' type='button'>Post comment</button></div><div class=''><span class='CommentEditToolbar__container___3LKaxfw8' data-aid='CommentEditToolbar__container'><div class='CommentEditToolbar__action___3t8pcxD7'><button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='add-mention' aria-label='Mention person in comment'><span class='' title='Mention person in comment' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/8846f168-mention.svg&quot;) center center no-repeat;'></span></button></div><div class='CommentEditToolbar__action___3t8pcxD7'><a class=''><div data-aid='attachmentDropdownButton' tabindex='0' title='Add attachment to comment' class='DropdownButton__icon___1qwu3upG CommentEditToolbar__attachmentIcon___48kfJPfH' aria-label='Add attachment'></div></a><input data-aid='CommentEditToolbar__fileInput' type='file' title='Attach file from your computer' name='file' multiple='' tabindex='-1' style='display: none;'></div><div class='CommentEditToolbar__action___3t8pcxD7'><button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='add-emoji' aria-label='Add emoji to comment'><span class='' title='Add emoji to comment' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/2b4b3f66-emoji-light.svg&quot;) center center no-repeat;'></span></button></div></span></div></div></div></div></li></ol></div></div></section>
                        			</section>
                        		</div>
                        	</div>
                        </div>
                        ";
                        
                    break;
                }
        break;
/****************************************
 * $perspective = 'view'                      *****************************************************************************
 ****************************************/
    case 'view':
        switch ($section){
				/****************************************
*view********** $section = 'main'                          *****************************************************************
				 ****************************************/
            case 'main':
                $tabs[]=['title'=>'Family'     , 'aspect'=>'family'       , 'section'=>'us' , 'note'=>'Common characteristics'               , 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_1", 'selected'=>$selected == 'family'];
                $tabs[]=['title'=>'Individual' , 'aspect'=>'individual'   , 'section'=>'this' , 'note'=>'Characteristics unique to this item', 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_2", 'selected'=>$selected == 'individual'];
                $tabs[]=['title'=>'Receipt'    , 'aspect'=>'receipt'      , 'section'=>'get' , 'note'=>'Acquisition'                         , 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_3", 'selected'=>$selected == 'receipt'];
                $tabs[]=['title'=>'Gallery'    , 'aspect'=>'gallery'      , 'section'=>'pics', 'note'=>'Pictures'                            , 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_4", 'selected'=>$selected == 'gallery'];
                $tabs[]=['title'=>'Library'    , 'aspect'=>'library'      , 'section'=>'docs', 'note'=>'Documents'                           , 'class'=>'qbox-q3', 'data-qid'=>"{$qid}_5", 'selected'=>$selected == 'library'];
                $panels[]=['aspect'=>'family'    , 'class'=>'option-panel family-option-panel'    , 'content'=> elgg_view("forms/market/edit/family"     , $vars)];
                $panels[]=['aspect'=>'individual', 'class'=>'option-panel individual-option-panel', 'content'=> elgg_view("forms/market/edit/individual" , $vars)];
                $panels[]=['aspect'=>'receipt'   , 'class'=>'option-panel receipt-option-panel'   , 'content'=> elgg_view("forms/market/edit/acquisition", $vars)];
                $panels[]=['aspect'=>'gallery'   , 'class'=>'option-panel gallery-option-panel'   , 'content'=> elgg_view("forms/market/edit/gallery"    , $vars)];
                $panels[]=['aspect'=>'library'   , 'class'=>'option-panel library-option-panel'   , 'content'=> elgg_view("forms/market/edit/library"    , $vars)];
        
                switch ($presentation){
				/****************************************
*view********** $section = 'main' $presentation = 'nested' *****************************************************************
				 ****************************************/
                    case 'nested':
				/****************************************
*view********** $section = 'main' $presentation = 'pallet' *****************************************************************
				 ****************************************/
                    case 'pallet':
//                        if(!elgg_entity_exists($guid)) break;
                        unset($tabs, $panels);
                        $selected = elgg_extract('selected', $vars, 'item');
                     	$tabs[]   = ['title'=>'Item'       , 'aspect'=>'item'         , 'section'=>'us'  , 'note'=>'Characteristics, Contents, Components, Accessories', 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'family'];
//                         $tabs[]   = ['title'=>'Experiences', 'aspect'=>'experiences'  , 'section'=>'this', 'note'=>'Experiences, Issues, Instructions, Insights', 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'individual'];
//                         $tabs[]   = ['title'=>'Inventory'  , 'aspect'=>'inventory'    , 'section'=>'docs', 'note'=>'Supplies, Pieces, Parts, Receipts'   , 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'library'];
//                         $tabs[]   = ['title'=>'Media'      , 'aspect'=>'media'        , 'section'=>'docs', 'note'=>'Documents, Pictures, Video'          , 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'library'];
                        foreach($tabs as $key=>$tab){
                            unset($content);
                            $content = elgg_view("forms/market/edit", array_merge($vars, ['section'=>$tab['aspect'], 'parent_cid'=>$parent_cid, 'entity'=>$entity]));
                            $panels[] = ['aspect'=>$tab['aspect'], 'id'=>$tab['data-qid']    , 'class'=>"option-panel ".$tab['aspect']."-option-panel"    , 'content'=> $content];
                            $tabs[$key]['selected'] = $tab['aspect'] == $selected;
                        }
                        unset($tabs);
                        break;
				/****************************************
*view********** $section = 'main' $presentation = 'open_boqx' *****************************************************************
				 ****************************************/
                    case 'open_boqx':
//                        if(!elgg_entity_exists($guid)) break;
                        unset($tabs, $panels);
                        $selected = elgg_extract('selected', $vars, 'item');
                     	$tabs[]   = ['title'=>'Item'       , 'aspect'=>'item'         , 'section'=>'us'  , 'note'=>'Characteristics, Contents, Components, Accessories', 'class'=>['qbox-q3'], 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'family'];
                        foreach($tabs as $key=>$tab){
                            unset($content);
                            $content = elgg_view("forms/market/edit", array_merge($vars, ['section'=>$tab['aspect'], 'parent_cid'=>$parent_cid, 'entity'=>$entity]));
                            $panels[] = ['aspect'=>$tab['aspect'], 'id'=>$tab['data-qid']    , 'class'=>"option-panel ".$tab['aspect']."-option-panel"    , 'content'=> $content];
                            $tabs[$key]['selected'] = $tab['aspect'] == $selected;
                        }
                        break;
                    default:
                        break;
                }
                $nav['tabs']  = $tabs;
                $nav['space'] = $space;
                $nav['qid']   = $qid;
                $nav['class'] ='quebx-tabs';
                $navigation   = elgg_view('navigation/tabs_slide', $nav);
                foreach($panels as $key=>$panel){
                	$is_selected = $selected == $panel['aspect'];
                	$class       = (array) $panel['class'];
            	    if ($is_selected) {
            			$class[] = 'qbox-state-selected';
            		}
                	$detail .= elgg_format_element('div',['id'=>$panel['id'], 'class'=>$class, 'parent_cid'=>$parent_cid], $panel['content']);
                }
                $details = elgg_format_element('div',['class'=>"qbox-details"], $detail);
                $form    = $navigation.$details;
                
                break;
                /****************************************
*view********** $section = 'item'                       *****************************************************************
				 ****************************************/
            case 'item':
                unset($hidden, $hidden_fields);
                if (!empty($hidden)){                
                    foreach($hidden as $key=>$field){
                        $hidden_fields .= elgg_view('input/hidden', $field);}}
                switch ($snippet){
                    case 'characteristics':
                        
                        break;
                    default:
                        
                       	$pick = elgg_view('output/url', array(
                        		'text' => '[pick]',
                        		'class' => 'elgg-lightbox',
                        		'data-colorbox-opts' => '{"width":600, "height":525}',
                       			'href' => "pick_test/family_characteristics/" . $entity->guid));
                       	$pick_menu = "<span title='Select family characteristics'>$pick</span>";
                       	
                        $components = elgg_get_entities_from_relationship([
                        	'type' => 'object',
                        	'relationship' => 'component',
                        	'relationship_guid' => $entity->guid,
                            'inverse_relationship' => true,
                        	'limit' => false,
                        ]);
                        $accessories = elgg_get_entities_from_relationship([
                        	'type' => 'object',
                        	'relationship' => 'accessory',
                        	'relationship_guid' => $entity->guid,
                            'inverse_relationship' => true,
                        	'limit' => false,
                        ]);
                        if($entity->characteristic_names) $characteristic_names = is_array($entity->characteristic_names) ? $entity->characteristic_names : (array) $entity->characteristic_names;
                        if($entity->characteristic_values) $characteristic_values = is_array($entity->characteristic_values) ? $entity->characteristic_values : (array) $entity->characteristic_values;
                        if($entity->features) $features = is_array($entity->features) ? $entity->features : (array) $entity->features;
                        if($entity->this_characteristic_names) $this_characteristic_names = is_array($entity->this_characteristic_names) ? $entity->this_characteristic_names : (array) $entity->this_characteristic_names;
                        if($entity->this_characteristic_values) $this_characteristic_values = is_array($entity->this_characteristic_values) ? $entity->this_characteristic_values : (array) $entity->this_characteristic_values;
                        if($entity->this_features) $this_features = is_array($entity->this_features) ? $entity->this_features : (array) $entity->this_features;
                        unset($characteristics, $last_class);
                        if($characteristic_names)
                            foreach($characteristic_names as $key=>$name)
                                $characteristics .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'value'=>$name, 'placeholder'=>'Characteristic','disabled'=>true])).
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'value'=>$characteristic_values[$key], 'placeholder'=>'Value','disabled'=>true])));
                        if($this_characteristic_names)
                            foreach($this_characteristic_names as $key=>$name)
                                $characteristics .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'value'=>$name, 'placeholder'=>'Characteristic','disabled'=>true])).
                                                        elgg_format_element('div',['class'=>'rTableCell'],
                                                            elgg_format_element('input',['type'=>'text', 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'value'=>$this_characteristic_values[$key], 'placeholder'=>'Value','disabled'=>true])));
                        unset($all_features, $last_class);
                        if($features)
                            foreach ($features as  $key=>$name)
                                $all_features .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                      elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:100%'],
                                                          elgg_format_element('input',['type'=>'text', 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'value'=>$name, 'placeholder'=>'Feature','disabled'=>true])));
                        if($this_features)
                            foreach ($this_features as  $key=>$name)
                                $all_features .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                      elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:100%'],
                                                          elgg_format_element('input',['type'=>'text', 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'value'=>$name, 'placeholder'=>'Feature','disabled'=>true])));
                        $content =
                            elgg_format_element('div',['class'=>'rTable','style'=>'width:100%;margin-top:10px;'],
                                elgg_format_element('div',['id'=>$cid.'_characteristics','class'=>'rTableBody'],
                                    elgg_format_element('div',['class'=>'rTableRow'],
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>Manufacturer</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_view('input/text', array('value' => $entity->manufacturer, 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'placeholder'=>'Manufacturer','disabled'=>true)))).
                                    elgg_format_element('div',['class'=>'rTableRow'],
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>Brand</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_view('input/text', array('value' => $entity->brand, 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'placeholder'=>'Brand','disabled'=>true)))).
                                    elgg_format_element('div',['class'=>'rTableRow'],
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>Model #</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_view('input/text', array('value' => $entity->model_no, 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'placeholder'=>'Model #','disabled'=>true)))).
                        			elgg_format_element('div',['class'=>'rTableRow'],
                                        elgg_format_element('div',['class'=>'rTableCell','style'=>'width:30%;padding:0px 5px'],'<b>Part #</b>').
                        				elgg_format_element('div',['class'=>'rTableCell','style'=>'width:70%;padding:0px 5px'],
                        				    elgg_view('input/text', array('value' => $entity->part_no, 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'placeholder'=>'Part #','disabled'=>true)))).
                                    $characteristics.
                                    elgg_format_element('div',['class'=>'new_characteristic']))).
                            elgg_format_element('div',['class'=>'rTable','style'=>'width:100%'],
        		               elgg_format_element('div',['class'=>'rTableBody','id'=>$cid.'_features'],
                                   elgg_format_element('div',['class'=>['rTableRow','pin']],
                                       elgg_format_element('div',['class'=>'rTableCell'],'<b>Features</b>')).
        		                   $all_features));
                }
                
                $form = $hidden_fields.$content;
                break;
               /****************************************
*view********** $section = 'item details'               *****************************************************************
               ****************************************/
            case 'item details':
                $nav_controls         = elgg_view('navigation/controls',['form_method'=>'nested', 'parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$perspective,'presentation'=>$presentation,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'maximize'=>true,'cancel'=>true,'action'=>true]]);
                $item_description     = elgg_view('output/description',['cid'=>$cid,'value'=>$entity->description]);
            	$category_name         = get_entity($entity->category)->title ?: '(No category)';
                $boqx_header = elgg_format_element('section',['class'=>'boqx_header'],
                                     elgg_format_element('fieldset',['class'=>'name'],
                                         elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6','style'=>'display: flex;'],
                                             elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'flex-basis: 100%;'],
                                                 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
                                                     elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_{$cid}"], 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'data-aid'=>'name','value'=>$entity->title, 'tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'name'=>"jot[$cid][title]",'placeholder'=>'Item name','disabled'=>true],
                                                          $entity->title))))));
                

                $boqx_selector = "
                  <div class='dropdown category' data-selector='boqx_category' data-cid='$cid'>  
                      <div id='item_category_dropdown_$cid' class='selection item_0' tabindex='-1'><span>$category_name</span></a>
                  </div>";                            	
    	        $item_category     = elgg_format_element('div', ['class'=>['info_box']],
    	                                         elgg_format_element('div', ['class'=>'info'],
    	                                              elgg_format_element('div', ['class'=>'row'],
    	                                                  "<em>Category</em>".
    	                                                  $boqx_selector)));
    	        if($presentation=='pallet') unset($nav_controls, $boqx_header);
                $form      = $boqx_header;
                $form     .= $item_description;
    	        $form     .= $nav_controls;
				$form     .= $item_category;
				$form     .= elgg_view('forms/market/edit', ['perspective'=>$perspective, 'section'=>'main', 'presentation'=>$presentation, 'cid'=>$cid, 'entity'=>$entity]);
                break;

               /****************************************
*view********** $section = 'item view'                       *****************************************************************
               ****************************************/
            case 'item view':
                unset($form_body, $disabled, $hidden, $hidden_fields, $id_value);
                if($presence == 'nested'){
				// nested items are view-only, but the container is read-write
					$hidden[] =['name'=>"jot[$cid][boqx]"         , 'value' => $parent_cid];
					$hidden[] =['name'=>"jot[$cid][cid]"          , 'value' => $cid];
					$hidden[] =['name'=>"jot[$cid][guid]"         , 'value' => $guid];
                }
                $collapser = elgg_format_element('a',['class'=>'collapser-item', 'id'=>"item_collapser_{$cid}",'data-cid'=>"$cid", 'tabindex'=>'-1', 'style'=>'margin-top:0;']);
                $view = 'forms/transfers/edit';
                $edit_item = elgg_format_element('button',['class'=>['CloseItem__submit__oz8vFV9a','autosaves','std','button','egg'],'type'=>'button','id'=>"boqx_close_$cid", 'data-boqx'=>$parent_cid, 'data-cid'=>$cid, 'tabindex'=>'-1', 'data-presence'=>$presentation,'title'=>'Close'],'Close');
                                  
                $id_value = $guid;
                $unpack_class='';
                $unpack_title_label = 'Unpack this item';
                $unpack_label    = 'Unpack item';
                $clone_class='';
                $delete_class='';
                switch($aspect){
                    case 'item':
                        $unpack_class    = 'selected';
                        $unpack_toggle   = 1;
                        $unpack_tag_toggle = 'display:inline;';
                        $clone_disabled  = 'disabled';
                        $clone_title_label = ' (disabled)';
                        $delete_disabled = 'disabled';
                        $delete_title_label = ' (disabled)';
                        break;
                    case 'receipt_item':
                        $unpack_toggle   = 0;
                        $unpack_tag_toggle = 'display:none;';
                        $clone_disabled  = 'disabled';
                        $clone_title_label = ' (disabled)';
                        $delete_disabled = 'disabled';
                        $delete_title_label = ' (disabled)';		                                
                        break;
                }
                if ($unpacked){
                        $unpack_disabled = 'disabled';
                        $unpack_title_label = 'Item unpacked';
                        unset($unpack_label);
                }
                $style_add = $style_show = $style_edit = "style='display:none;'";
                switch ($display_state){case 'add':  unset($style_add);  break;
    	                                case 'show': unset($style_show); break;
    	                                case 'edit': $style_edit = "style='display:flex;'"; break;}                        
                
                $unpack_button = elgg_format_element('button',['class'=>['autosaves', 'unpack_item', 'hoverable', 'left_endcap', $unpack_class],
                                                               'title'=>$unpack_title_label,
                                                               'id'=>"item_unpack_button_$cid",
                                                               'data-aid'=>'Unpack',
                                                               'tabindex'=>'-1',
                                                                'disabled'=>$unpack_disabled]);
                $clone_button  = elgg_format_element('button',['class'=>['autosaves', 'clone_item', 'hoverable', 'left_endcap', $clone_class],
                                                               'title'=>'Clone this item'.$clone_title_label,
                                                               'id'=>"item_clone_button_$cid",
                                                               'data-aid'=>'Clone',
                                                               'tabindex'=>'-1',
                                                                'disabled'=>$clone_disabled]);
                $delete_button  = elgg_format_element('button',['class'=>['autosaves', 'delete', 'hoverable', 'right_endcap', $delete_class],
                                                                'title'=>'Delete this item'.$delete_title_label,
                                                                'id'=>"item_delete_button_$cid",
                                                                'data-aid'=>'Delete',
                                                                'tabindex'=>'-1',
                                                                 'disabled'=>$delete_disabled]);
                if ($aspect == 'receipt_item'){
            	            $section_vars = $vars;
            	            $section_vars['section'] = 'acquisition details';
            	            $acquisition_details  = elgg_view('forms/market/edit',$section_vars);
            	        }
            	$labels_maker = elgg_view('forms/transfers/edit',[ 
										  'perspective' => $perspective,
										  'section'     =>'labels_maker',
										  'cid'         => $cid,
            	                          'entity'      => $entity]);     			                
                $boqx_header = elgg_format_element('section',['class'=>'boqx_header'],
                                     $collapser.
                                     elgg_format_element('fieldset',['class'=>'name'],
                                         elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6','style'=>'display: flex;'],
                                             elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'flex-basis: 320px;'],
                                                 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
                                                     elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_{$cid}"], 'style'=>"color:#666;border-bottom:1px solid #9e9e9e;", 'data-aid'=>'name','value'=>$entity->title, 'tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'name'=>"jot[$cid][title]",'placeholder'=>'Item name', 'disabled'=>true],
                                                          $entity->title))))));
//@EDIT - 2020-03-30 - SAJ - temporarily override $perspective, revert from 'view' to 'edit'
//                $line_item_properties     = elgg_view('forms/market/edit', ['presentation'=>$presentation, 'perspective'=>'edit', 'section'=>'item details', 'cid'=>$cid, 'entity'=>$entity]);
                $line_item_properties     = elgg_view('forms/market/edit', ['presentation'=>$presentation, 'perspective'=>$perspective, 'section'=>'item details', 'cid'=>$cid, 'entity'=>$entity]);
                $nav_controls  = elgg_view('navigation/controls',['form_method'=>'post', 'parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>'edit','presentation'=>$presentation,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'maximize'=>true,'cancel'=>true,'action'=>true,'submit'=>$submit]]);
//                $nav_controls  = elgg_view('navigation/controls',['form_method'=>'post', 'parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$perspective,'presentation'=>$presentation,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'maximize'=>true,'cancel'=>true,'action'=>true,'submit'=>$submit]]);
                $item_details = "
                        <div class='ShowItemDetails__Uc3MWjrS'>
							<div class='ShowItemDetailsButton__qWXhMy9t' data-cid='$cid'>
								<h3>".elgg_view_icon('settings-alt')." Item Details</h3>
							</div>
							<section class='ItemEdit__descriptionContainer___Mr67pXjd ItemEditContainer__$cid' >
								<section class='item-properties item-properties__$cid'>
										$line_item_properties
								</section>
							</section>
						</div>";
                $issue_body_vars = $vars;
                $issue_body_vars['cid'] = $cid; 
//                unset($issue_body_vars['cid']);
//@EDIT - 2020-03-30 - SAJ - temporarily override $perspective, revert from 'view' to 'edit'
                $issue_body_vars['action'] = 'edit';
//                $issue_body_vars['action'] = $perspective;
                $issue_body_vars['section']='issues';
                $issue_body_vars['presentation']='carton';
                $issue_body_vars['presence']='item boqx';
                $form_vars = ['enctype' => 'multipart/form-data', 
        					  'action' => 'action/jot/edit_pallet'];
                $body_vars =[];
                $issue_body_vars['form_wrapper']=['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars];
                $item_issues = elgg_view('forms/experiences/edit',$issue_body_vars);
                
                $contents_body_vars = $vars;
                $contents_body_vars['cid']          = $cid;
//@EDIT - 2020-03-30 - SAJ - temporarily override $perspective, revert from 'view' to 'edit'
                $contents_body_vars['action']       = 'edit';
//                $contents_body_vars['action']       = $perspective;
                $contents_body_vars['section']      ='contents';
                $contents_body_vars['presentation'] ='carton';
                $contents_body_vars['presence']     ='item boqx';
                $item_contents = elgg_view('forms/market/edit',$contents_body_vars);
				$form_vars = ['name' => 'marketForm', 
							  'enctype' => 'multipart/form-data', 
							  'action' => 'action/jot/edit_pallet'];
    	        $form_vars['body'] = $hidden_fields.
				                     $boqx_header.
									 elgg_format_element('section',['class'=>'ItemLedger__KY8DM3qs','data-cid'=>$cid],$nav_controls.$acquisition_details.$item_details).
									 elgg_format_element('section',['class'=>'ItemLabels__HqGmyX2Y','data-cid'=>$cid],$labels_maker);
				
                if($presentation == 'nested')
                     $boqx_contents_edit = $form_vars['body'];
                else $boqx_contents_edit = elgg_view_form('',$form_vars);
				$form = $boqx_contents_edit;
                break;
               /****************************************
*view********** $section = 'single_thing'                       *****************************************************************
               ****************************************/
            case 'single_thing':                                                                  $display.= "guid=$guid, perspective=$perspective, section=$section, snippet=$snippet, presentation=$presentation, presence=$presence, submit=$submit";
                
                switch($presentation){
                    case 'open_boqx':
            	        $section_vars           = $vars;
            	        $section_vars['cid']    = $cid;
            	        $section_vars['section']='item details';
            	        $form_version           = 'market/edit';
            	        $form_vars = ['name' => 'marketForm', 
        						      'enctype' => 'multipart/form-data', 
        						      'action' => 'action/jot/edit_pallet'];
                    	$form = elgg_view_form($form_version,$form_vars,$section_vars);
                        
                        break;
                    case 'nested':
                    case 'pallet':
                    case 'carton':
                        $element = 'item';
                        $delete = elgg_format_element("span",['class'=>'remove-item'], elgg_format_element('a', ['title' =>'remove item'],
                                                                    	                                                elgg_view_icon('delete-alt')));
                        $horizontal_offset = '-140';
                        $edit_properties_button = elgg_view('output/url', ['data-cid'            => $cid,
        												                   'class'               => "ItemEdit__showContainer",
                                                                           'text'                => elgg_view_icon('settings-alt').'Item Details', 
        										                           'title'               => 'show properties',]);
            	        $style_add = $style_show = $style_edit = "display:none;";
                        switch ($display_state){case 'add':  unset($style_add);  break;
            	                                case 'show': unset($style_show); break;
            	                                case 'view':  break;
            	                                case 'edit': $style_edit = "display:flex;"; break;}
                        $boqx_details_add = elgg_format_element('div',['tabindex'=>'0','class'=>'AddSubresourceButton___oKRbUbg6','style'=>$style_add,'data-aid'=>'TaskAdd','data-focus-id'=>'TaskAdd','data-cid'=>$cid,'data-guid'=>$guid],
                                            	 elgg_format_element('span',['class'=>'AddSubresourceButton__icon___h1-Z9ENT']).
                                            	 elgg_format_element('span',['class'=>'AddSubresourceButton__message___2vsNCBXi'],'Add an item'));
                        $boqx_details_show =elgg_format_element('div',['class'=>'ItemShow_Btc471up','style'=>$style_show,'data-aid'=>'TaskShow','data-cid'=>$cid],
            					                 elgg_format_element('div',[],
                                                     elgg_format_element('nav',['class'=>['ItemShow__actions__4AR4v8MP','ItemShow__actions--unfocused___1df6Nh5x']],
                                    					 elgg_format_element('button',['class'=>['IconButton___2y4Scyq6','IconButton--small___3D375vVd'],'data-aid'=>'delete','aria-label'=>'Delete','data-cid'=>$cid],$delete))).
                                                 elgg_format_element('div',['class'=>'ItemShow__line_gSpQE5Ao'],
                                                     elgg_format_element('span',['class'=>['ItemShow__title__8tlRYJcP']]).
                                					 elgg_format_element('span',['class'=>'ItemShow__item_total__Dgd1dOSZ'])));
            	        $section_vars           = $vars;
            	        $section_vars['cid']    = $cid;
//            	        $section_vars['perspective'] = 'edit';               //temporary change
                        $section_vars['section']= 'item view';
//            	        $section_vars['section']= 'item edit';
            	        $form_version           = 'market/edit';
                        $boqx_edit              = elgg_view('forms/'.$form_version,$section_vars);
						
                        $section_vars                = $vars;
                        $section_vars['cid']         = $cid;
                        $section_vars['perspective'] = 'edit';               //temporary change
                        $section_vars['section']     ='contents';
                        $section_vars['presentation']='carton';
                        $section_vars['presence']    ='item boqx';
                        $form_version                = 'market/edit';
                        $form_vars                   = ['enctype' => 'multipart/form-data', 
                					                    'action' => 'action/jot/edit_pallet'];
                        $body_vars =[];
                        $section_vars['form_wrapper']=['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars];
                        $item_contents               = elgg_format_element('section',['class'=>'ItemContents__aXLIZva0','data-cid'=>$cid],
                                                           elgg_view('forms/'.$form_version,$section_vars));
                        
                        $section_vars                = $vars;
                        $section_vars['cid']         = $cid; 
                        $section_vars['action']      = 'edit';               //temporary change
                        $section_vars['section']     = 'issues';
                        $section_vars['presentation']= 'pallet'; //$presentation;// 'carton';
                        $section_vars['presence']    = 'item boqx';
                        $section_vars['form_wrapper']= ['action'=>'','form_vars'=>$form_vars,'body_vars'=>$body_vars];
                        $form_version                = 'experiences/edit';
                        $form_vars                   = ['enctype' => 'multipart/form-data', 
                					                    'action' => 'action/jot/edit_pallet'];
                        $body_vars =[];
                        $item_issues                 = elgg_format_element('section',['class'=>'ItemIssues__3d5EmH6b','data-cid'=>$cid],
                                                           elgg_view('forms/'.$form_version,$section_vars));
                            
                	   $boqx_details_edit            = elgg_format_element('section',['class'=>['ItemEdit___7asBc1YY','info_box','free'],'style'=>$style_edit,'data-aid'=>'ItemEdit','data-cid'=>$cid],
                	                                       elgg_format_element('div',['class'=>'ItemEditValue'],$boqx_edit.$item_contents.$item_issues));
                    
                    	if ($unpacked) unset($boqx_details_add,$boqx_details_show);
                    	$class = ['Item__nhjb4ONn','boqx-item'];
                    	if ($display_class)
                    	    $class[] = $display_class;
                        $single_thing = elgg_format_element('div',['id'=>$cid, 'class'=>$class, 'data-boqx'=>$parent_cid, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'boqx-fill-level'=>'0'], $boqx_details_add.$boqx_details_show.$boqx_details_edit);
        		                        
                       $form = $single_thing;
                       break;
                }
                break;
        }
    break;
    default:
        elgg_set_context('edit_item');
        //$form .= elgg_dump($entity);
        
        	$receipts = elgg_get_entities_from_relationship(array(
        		'type'                 => 'object',
        		'relationship'         => 'transfer_receipt',
        		'relationship_guid'    => $entity_guid,
        		'inverse_relationship' => true,
        		'limit'                => false,
        	));
        	$receipt_items = elgg_get_entities_from_relationship(array(
        		'type'                 => 'object',
        		'relationship'         => 'receipt_item',
        		'relationship_guid'    => $entity_guid,
        		'inverse_relationship' => false,
        		'limit'                => false,
        		// Receipt items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
        		'order_by_metadata'    => array('name' => 'sort_order', 
        				                        'direction' => ASC, 
        				                        'as' => 'integer'),
        	));
        $contents = elgg_get_entities(array(
        				'type' => 'object',
        				'subtypes' => ['market','q_item'],
        				'wheres' => array(
        					"e.container_guid = $entity_guid",
        				),
        			));
        $characteristics = elgg_get_entities_from_relationship(array(
        	'type' => 'object',
        	'relationship' => 'characteristic',
        	'relationship_guid' => $entity_guid,
            'inverse_relationship' => true,
        	'limit' => false,
        ));
        $add_characteristic = elgg_view_form('jot/add/element', array("action" => "action/jot/add/element?element_type=characteristic&guid=$entity_guid&asset=$asset"));
        
        if ($vars['entity']) {
          $form .= elgg_view('input/hidden',array('name'=>'parent_guid','value'=>$vars['entity']->parent_guid));
        }
        //$form .= elgg_dump($vars);
        //$form .= elgg_dump($vars['entity']);
        
        $form .= "<b>Family</b>";
        $form .= "<div class='rTable' style='width:100%'>
        		<div class='rTableBody'>
        			<div class='rTableRow'>
        				<div class='rTableCell' style='width:20%;padding:0px 5px'>Title</div>
        				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[title]', 'value' => $entity->title,))."</div>
        			</div>
        			<div class='rTableRow'>
        				<div class='rTableCell' style='width:20%;padding:5px 5px 0px 5px'>Manufacturer</div>
        				<div class='rTableCell' style='width:80%;padding:5px 5px 0px 5px'>".elgg_view('input/text', array('name' => 'item[manufacturer]', 'value' => $entity->manufacturer,))."</div>
        			</div>
        			<div class='rTableRow'>
        				<div class='rTableCell'>Model #</div>
        				<div class='rTableCell'>
        			       <div class='rTable' style='width:100%'>
        						<div class='rTableBody'>
        						   	<div class='rTableRow'>
        								<div class='rTableCell' style='padding:0px 0px'>".elgg_view('input/text', array('name' => 'item[model_no]', 'value' => $entity->model_no,))."</div>
        								<div class='rTableCell' style='padding:0px 5px'>Part #</div>
        								<div class='rTableCell' style='padding:0px 0px'>".elgg_view('input/text', array('name' => 'item[part_no]', 'value' => $entity->part_no,))."</div>
        							</div>
        						</div>
        					</div>
        				</div>
        			</div>
        			<div class='rTableRow'>
        				<div class='rTableCell' style='width:20%;padding:0px 5px;vertical-align:top;'>Description</div>
        				<div class='rTableCell' style='width:80%;padding:0px 5px'>";
        
        if ($allowhtml != 'yes') {
        	$form .= "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
/*        	$form .= <<<HTML
        <textarea name='marketbody' class='mceNoEditor' rows='8' cols='40'
          onKeyDown='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars}'
          onKeyUp='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars})'>{$body}</textarea><br />
        HTML;
        	$form .= "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
        } else {
        	$form .= elgg_view("input/longtext", array("name" => "marketbody", "value" => $body));
        }
        */
        $form .="			</div>
        			</div>
        			<div class='rTableRow'>
        				<div class='rTableCell' style='width:20%;padding:0px 5px'>Category</div>
        				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('market/marketcategories',$vars)."</div>
        			</div>
        			<div class='rTableRow'>
        				<div class='rTableCell' style='width:20%;padding:0px 5px'>Owner</div>
        				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('output/url', array('text' => $owner->name,'href' =>  'profile/'.$owner->username))."</div>
        			</div>";
        $form .= "		<div class='rTableRow'>
        				<div class='rTableCell' style='width:20%;padding:0px 5px;vertical-align:top;'></div>
        				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('market/thumbnail', array('marketguid' => $entity->guid, 'size' => 'large', 'tu' => $entity->time_updated))."</div>
        			</div>";
        $form .= "		</div>
        	</div>";
        
        $form .= "<b>Family Characteristics</b><br>";
        
        
        // Characteristics clone
        // Taken from mod\market\views\default\forms\market\edit\car\profile.php 
        $form .= '<div class="characteristics">
        		<div>'. 
        	elgg_view('input/text', array(
        		'name' => 'item[component_names][]',
        		'style' => 'width: 25%;'
        	)).
        	elgg_view('input/text', array(
        		'name' => 'item[component_values][]',
        		'style' => 'width: 65%;'
        	)).
            '<a href="#" class="remove-node">remove</a>
        	</div>
        </div>'; // end of Characteristics clone
        
        
        
        
        $form .= "<b>This Item</b>";
        	$form .= "<div class='rTable' style='width:100%'>
        			<div class='rTableBody'>
        				<div class='rTableRow'>
        					<div class='rTableCell' style='width:10%'>
        						<span title='New line item'>".
        							elgg_view('output/url', array(
        							    'text' => '+',
        								'href' => '#',
        								'class' => 'elgg-button-submit-element clone-line-item-action' // unique class for jquery
        								))."
        						</span>
        					</div>
        					<div class='rTableHead' style='width:5%'>Qty</div>
        					<div class='rTableHead' style='width:60%'>Receipt</div>
        					<div class='rTableHead' style='width:5%'>tax?</div>
        					<div class='rTableHead' style='width:10%'>Cost</div>
        					<div class='rTableHead' style='width:10%'>Total</div>
        				</div>
        				";
        	// Populate existing receipt items
        	$n=0;
        	$display .= '';
        	foreach($receipt_items as $item){
        		$n = $n+1;
        		$element_type = 'receipt item';
        		if ($item->canEdit()) {
        			$delete = elgg_view("output/url",array(
        		    	'href' => "action/jot/delete?guid=$item->guid&container_guid=$transfer_guid",
        		    	'text' => elgg_view_icon('arrow-left'),
        		    	'confirm' => sprintf('Remove receipt item?'),
        		    	'encode_text' => false,
        		    ));
        			$select = elgg_view('input/checkbox', array('name'    => 'do_me',
        														'value'   => $item->guid,
        								        			    'default' => false,
        								        			   ));
        			$title = $item->title;
        	        $linked_item = elgg_get_entities_from_relationship(array(
        				'type'                 => 'object',
        				'relationship'         => 'receipt_item',
        				'relationship_guid'    => $item->getGUID(),
        				'inverse_relationship' => true,
        				'limit'                => 1,
        //	        	'limit' => false,
        			));
        	        $linked_receipt = elgg_get_entities_from_relationship(array(
        				'type'                 => 'object',
        				'relationship'         => 'transfer_receipt',
        				'relationship_guid'    => $item->getGUID(),
        				'inverse_relationship' => false,
        	        	'limit'                => 1,
        			));
        	
        	        if (!empty($linked_item[0]) && ($item->retain_line_label == 'no')){
        /*				$detach = elgg_view("output/url",array(
        			    	'href' => "action/jot/detach?element_type=receipt_item&&guid=".$linked_item[0]->getGUID()."&container_guid=$item->getGUID()",
        			    	'text' => elgg_view_icon('unlink'),
        			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), 'linked item'),
        			    	'encode_text' => false,
        			    ));
        */
        /*				$link         = "<span class='hoverinfo'>".
        								      elgg_view('output/url', array('text' => $linked_receipt[0]->title,'href' =>  "jot/view/$linked_receipt[0]->guid"))."
        					 	             <span style='width:150px;'>".
        						 	             elgg_view('market/display/hoverinfo', array('i'=>$linked_receipt[0]))."
        			 	                     </span>
        					 	          </span>";
        */
        //				$line_item = $link;
        	        	$line_item .= elgg_view('output/url', array(
        //	        			'text' =>  $linked_receipt[0]->title,
        	        			'text' =>  $linked_item[0]->title,
        //	        			'href' =>  "market/view/".$linked_receipt[0]->getGUID()."/".$linked_receipt[0]->title."/Inventory",
        	        			'href' =>  "market/view/".$linked_item[0]->getGUID()."/".$linked_item[0]->title."/Inventory",
        		        		'class'=> 'rTableform90',
        		        ));
        		        $line_item .= elgg_view('input/hidden', array(
        		        		'name' => 'item[receipt_item][title][]',
        		        		'value' => $title,
        		        ));	         
        	        } else {
        	        	$line_item = elgg_view('input/text', array(
        	        			'name' => 'item[receipt_item][title][]',
        	        			'value' => $title,
        	        			'class'=> 'rTableform90',
        	        	));        	
        	        }
        	$display .= '$line_item: '.$line_item.'<br>';
        	$display .= 'item_guid: '.$item->item_guid.'<br>';
        /*	        if ($item->taxable == 1){
        	        	$tax_options = array('name'    => 'item[receipt_item][taxable][]',
        							         'checked' => 'checked',
        							         'value'   => 1,
        	        			             'default' => false,
        	        			            );
        	        } else {
        	        	$tax_options = array('name'    => 'item[receipt_item][taxable][]',
        							         'value'   => 1,
        	        			             'default' => false,
        								    );
        	        }
        	        $tax_check = elgg_view('input/checkbox', $tax_options);
        */	        
        	        $pick = elgg_view('output/url', array(
        	        		'text' => elgg_view_icon('settings-alt'),
        	        		'class' => 'elgg-lightbox',
        	        		'data-colorbox-opts' => '{"width":500, "height":525}',
        	        		'href' => "market/pick/item/" . $item->getGUID()));
        	        $pick_menu = "<span title='Set line item properties'>$pick</span>";
        	        $item_total = '';
        	        if (!empty($item->total) && $item->sort_order == 1){
        	        	$item_total = money_format('%#10n', $item->total);
        	        }
        	        else {
        	        	$item_total = number_format($item->total, 2);
        	        }
        		}
        	$form .="		<div class='rTableRow'>
        					<div class='rTableCell' style='width:10%'>{$delete}{$select}</div>
        					<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
        																	'name' => 'item[receipt_item][qty][]',
        																	'value' => $item->qty,
        																))."</div>
        					<div class='rTableCell' style='width:60%'>$pick_menu $line_item</div>
        					<div class='rTableCell' style='width:5%'>$tax_check</div>
        					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
        																	'name' => 'item[receipt_item][cost][]',
        																	'value' => $item->cost,
        																))."</div>
        					<div class='rTableCell' style='width:10%;text-align:right'>$item_total</div>
        					".elgg_view('input/hidden', array(
        																	'name' => 'item[receipt_item][guid][]',
        																	'value' => $item->guid,
        																))."
        				</div>";
        	}
        	// Populate blank lines
        	for ($i = $n+1; $i <= 1; $i++) {
        	
        	        if ($exists){
        		        $pick = elgg_view('output/url', array(
        		        		'text' => elgg_view_icon('settings-alt'),
        		        		'class' => 'elgg-lightbox',
        		        		'data-colorbox-opts' => '{"width":500, "height":525}',
        		        		'href' => "market/pick/item/" . $transfer_guid));
        		        $pick_menu = "<span title='Set line item properties'>$pick</span>";
        	        	    }
        		    else {
        		        $pick = elgg_view('output/url', array(
        		        		'text' => elgg_view_icon('settings-alt')));
        		        $pick_menu = "<span title='Save form before setting line item properties'>$pick</span>";
        		    }
        				//'href' => "market/pick?element_type=item&container_guid=" . $transfer_guid));
        	$form .="		<div class='rTableRow'>
        					<div class='rTableCell' style='width:10%'><a href='#' class='remove-node'>[X]</a></div>
        					<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
        												'name' => 'item[receipt_item][qty][]',
        											))."</div>
        					<div class='rTableCell' style='width:60%'>".$pick_menu.' '.elgg_view('input/text', array(
        												'name' => 'item[receipt_item][title][]',
        							                    'class'=> 'rTableform90',
        											))."</div>
        					<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
        												'name' => 'item[receipt_item][taxable][]',
        												'value'=> 1,
        	        			                        'default' => false,
        											))."</div>
        					<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
        												'name' => 'item[receipt_item][cost][]',
        											))."</div>
        					<div class='rTableCell' style='width:10%'></div>
        				</div>";
        	}
        
        	$form .="	<div class='new_line_item'></div>
        		</div>
        	</div>";
        /*
        
        $marketcategories = elgg_view('market/marketcategories',$vars);
        if (!empty($marketcategories)) {
        	$form .= "<p><label>Category: </label>$marketcategories</p>";
        }
        
        $form .= "<p><label>Model #:</label>".
             elgg_view('input/text', array('name' => 'model_no','value' => $model_no)).
             "</p>";
        */
        $form .= "<p><label>Serial #:</label>".
             elgg_view('input/text', array('name' => 'serial_no','value' => $serial_no)).
             "</p>";
        /*
        $form .= "<p><label>" . elgg_echo("market:text") . "<br>";
        if ($allowhtml != 'yes') {
        	$form .= "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
        	$form .= <<<HTML
        <textarea name='marketbody' class='mceNoEditor' rows='8' cols='40'
          onKeyDown='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars}'
          onKeyUp='textCounter(document.marketForm.marketbody,"market-remLen1",{$numchars})'>{$body}</textarea><br />
        HTML;
        	$form .= "<div class='market_characters_remaining'><span id='market-remLen1' class='market_charleft'>{$numchars}</span> " . elgg_echo("market:charleft") . "</div>";
        } else {
        	$form .= elgg_view("input/longtext", array("name" => "marketbody", "value" => $body));
        }
        $form .= "</label></p>";
        */
        $form .= "<p><label>" . 
              elgg_echo("market:tags")."
        	  </label>". $tags."<br>";
        
        	$url = elgg_get_site_url() . "labels/$asset_guid";
        	$url = elgg_add_action_tokens_to_url($url);
        $form .= elgg_view('output/url', array(
                          "href" => $url,
                           "text" => "add label",
                           "class" => "elgg-lightbox"
                ));
        
        // characteristics
        // Taken from mod\jot\views\default\jot\display\observation\details.php`
        	$form .= '<table width = 100%><tr>
        	        <td colspan=2><b>Characteristics</b>&nbsp;
        		        <span class="hoverhelp">[?]
        		        <span style="width:500px;"><p></span>
        		        </span>
        	        </td>
        	      </tr>
        	      <tr>
        	        <td colspan=2>'.$add_characteristic.'
        	        </td>
        	      </tr>';
        
        if ($characteristics) {
        foreach ($characteristics as $i) {
        			$element_type = 'characteristic';
        			if ($i->canEdit() && $entity->state <= 3) {
        				$delete = elgg_view("output/url",array(
        			    	'href' => "action/jot/delete?guid=$i->guid&container_guid=$entity_guid&tab=$section",
        			    	'text' => elgg_view_icon('delete'),
        			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
        			    	'encode_text' => false,
        			    ));
        			}
        	$form .= '<tr class="highlight">
        	        <td>'.$i->title.'</td>
        	        <td>'.$delete.'
        	      </tr>';
            }
        }
        else {
        	$form .= '<tr>
        	        <td>Enter new characteristic and click [add!]</td>
        	        <td>
        	      </tr>';	
             }	
        $form .= '</table><br>';
        
        $form .= "<p><label>" . elgg_echo('access') . "&nbsp;<small><small>" . elgg_echo("market:access:help") . "</small></small><br />";
        $form .= elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
        $form .= "</label></p>";
        $form .=
        "<div class='elgg-foot'>".
             elgg_view('input/hidden', array(
        		'name' => 'guid',
        		'value' => $entity_guid,
        	)).
           elgg_view('input/hidden', array(
        		'name' => 'asset',
        		'value' => $vars['asset'],
        	)).
           elgg_view('input/hidden', array(
        		'name' => 'container_guid',
        		'value' => $entity->getContainerGUID(),
        	)).
           elgg_view('input/hidden', array(
        		'name' => 'parent_guid',
        		'value' => $vars['parent_guid'],
        	)).
           elgg_view('input/hidden', array(
        	     'name' => 'aspect', 
        	     'value' => $aspect
        	)).
           elgg_view('input/hidden', array(
        	     'name' => 'item_type', 
        	     'value' => 'receipt_item',
        	)).
        	elgg_view('input/hidden', array(
        	'name' => 'referrer',
        	'value' => $referrer,
        	)).
        	
           elgg_view('input/submit', array('value' => elgg_echo('save'), 'name' => 'submit')).
           elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply')).
        '<a href='.elgg_get_site_url() . $referrer.' class="cancel_button">Cancel</a>
        </div>';
        
        $form .=
        "<div id='line_store' style='visibility: hidden; display:inline-block;'>
        	<div class='line_item'>
        		<div class='rTableRow'>
        				<div class='rTableCell' style='width:10%'><a href='#' class='remove-node'>[X]</a></div>
        				<div class='rTableCell' style='width:5%'>".elgg_view('input/text', array(
        											'name' => 'item[receipt_item][qty][]',
        										))."</div>
        				<div class='rTableCell' style='width:60%'>".$pick_menu.' '.elgg_view('input/text', array(
        											'name' => 'item[receipt_item][title][]',
        						                    'class'=> 'rTableform90',
        										))."</div>
        				<div class='rTableCell' style='width:5%'>".elgg_view('input/checkbox', array(
        											'name' => 'item[receipt_item][taxable][]',
        											'value'=> 1,
                			                        'default' => false,
        										))."</div>
        				<div class='rTableCell' style='width:10%'>".elgg_view('input/text', array(
        											'name' => 'item[receipt_item][cost][]',
        										))."</div>
        				<div class='rTableCell' style='width:10%'></div>
        			</div>
        	</div>
        </div>";

    }
}
echo $form;
//register_error($display);