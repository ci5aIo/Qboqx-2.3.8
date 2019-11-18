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
$perspective    = elgg_extract('perspective'      , $vars, 'edit');
$section        = elgg_extract('section'          , $vars, 'main');
$snippet        = elgg_extract('snippet'          , $vars);
$presentation   = elgg_extract('presentation'     , $vars);
$n              = elgg_extract('n'                , $vars, 1);
$boqx_guid      = elgg_extract('boqx_guid'        , $vars, false);
$cid            = elgg_extract('cid'              , $vars, quebx_new_id('c'));
$parent_cid     = elgg_extract('parent_cid'       , $vars, false);
$qid            = elgg_extract('qid'              , $vars, quebx_new_id('q'));
$display_state  = elgg_extract('display_state'    , $vars, $perspective); //Options: add, edit, show
$fill_level     = elgg_extract('fill_level'       , $vars, 0);
$unpacked       = $guid>0;

if ($entity){
    $tags  = elgg_extract('markettags', $vars, $entity->gettags());
    $owner = get_entity($entity->owner_guid);
}
    

$family_values = item_prepare_form_vars(NULL,NULL,$entity, null, null);
$entity_values = item_prepare_form_vars(NULL,NULL,$entity, null, null);
$values        = array_merge($family_values, $entity_values);
foreach($values as $value=>$field){
//	$display .= $value.'=>'.$field.'<br>';
}
//$form .= $display;
echo "<!--perspective=$perspective, section=$section, snippet=$snippet, presentation=$presentation-->";

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
*add********** $section = 'main' $presentation = 'pallet' *****************************************************************
				 ****************************************/
                    case 'pallet':
                        unset($tabs, $panels);
                    	$tabs[]=['title'=>'Family'     , 'aspect'=>'family'       , 'section'=>'us'  , 'note'=>'Common characteristics'              , 'class'=>'qbox-q3', 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'family'];
                        $tabs[]=['title'=>'Individual' , 'aspect'=>'individual'   , 'section'=>'this', 'note'=>'Characteristics unique to this item' , 'class'=>'qbox-q3', 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'individual'];
                        $tabs[]=['title'=>'Gallery'    , 'aspect'=>'gallery'      , 'section'=>'pics', 'note'=>'Pictures'                            , 'class'=>'qbox-q3', 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'gallery'];
                        $tabs[]=['title'=>'Library'    , 'aspect'=>'library'      , 'section'=>'docs', 'note'=>'Documents'                           , 'class'=>'qbox-q3', 'data-qid'=>quebx_new_id('q'), 'selected'=>$selected == 'library'];
                        foreach($tabs as $key=>$tab){
                            unset($content);
                            $content = elgg_view("forms/market/edit", array_merge($vars, ['section'=>$tab['aspect'], 'parent_cid'=>$parent_cid, 'entity'=>$entity]));                                     $display.="$tab[aspect] = ".$tab['aspect'].'<br>';
                            $panels[] = ['aspect'=>$tab['aspect'], 'id'=>$tab['data-qid']    , 'class'=>"option-panel ".$tab['aspect']."-option-panel"    , 'content'=> $content];
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
                	$class       = $panel['class'];
            	    if ($is_selected) {
            			$class .= ' qbox-state-selected';
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
*add********** $section = 'family'                       *****************************************************************
				 ****************************************/
            case 'family':
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
                       	
                        if($entity->characteristic_names) $characteristic_names = is_array($entity->characteristic_names) ? $entity->characteristic_names : (array) $entity->characteristic_names;
                        if($entity->characteristic_values) $characteristic_values = is_array($entity->characteristic_values) ? $entity->characteristic_values : (array) $entity->characteristic_values;
                        if($entity->features) $features = is_array($entity->features) ? $entity->features : (array) $entity->features;
                        
                        $family_content = "
                        <div class='rTable' style='width:100%;margin-top:10px;'>
                    		<div class='rTableBody'>
                                <div class='rTableRow'>
                    				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Manufacturer</b></div>
                    				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => "jot[$cid][manufacturer]", 'value' => $entity->manufacturer, 'placeholder'=>'Manufacturer'))."</div>
                    		    </div>
                                <div class='rTableRow'>
                    				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Brand</b></div>
                    				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => "jot[$cid][brand]", 'value' => $entity->brand, 'placeholder'=>'Brand',))."</div>
                    		    </div>
                                <div class='rTableRow'>
                    				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Model #</b></div>
                    				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => "jot[$cid][model_no]", 'value' => $entity->model_no, 'placeholder'=>'Model #',))."</div>
                                </div>
                    			<div class='rTableRow'>
                                    <div class='rTableCell' style='width:20%;padding:0px 5px'><b>Part #</b></div>
                    				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => "jot[$cid][part_no]", 'value' => $entity->part_no, 'placeholder'=>'Part #',))."</div>
                    			</div>
                    		</div>
                    	</div>";
                        
                        $family_content .= "<div class='rTable' style='width:100%'>
                        		<div class='rTableBody' id='family_characteristics'>
                        			<div class='rTableRow pin'>
                        				<div class='rTableCell'><b>Family Characteristics</b></div>
                        				<div class='rTableCell'>$pick_menu</div>
                        			</div>";
                        if($characteristic_names)
                            foreach($characteristic_names as $key=>$name){
                                if (($key+1) == count($characteristic_names)) $last_class = 'last_characteristic';
                                $family_content .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:33%'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_names][]", 'value'=>$name, 'placeholder'=>'Characteristic'])).
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:66%'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_values][]", 'value'=>$characteristic_values[$key], 'placeholder'=>'Value', 'class'=>$last_class])));
                            }
                        else
                            $family_content .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:33%'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_names][]", 'placeholder'=>'Characteristic'])).
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:66%'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][characteristic_values][]", 'placeholder'=>'Value','class' => 'last_characteristic',])));
                        $family_content .= '<div class="new_characteristic"></div>';
                        $family_content .= "</div>
                        			</div>";
                        $family_content .="
                        	<div class='rTable' style='width:100%'>
                        		<div class='rTableBody' id='family_features'>
                                    <div class='rTableRow pin'>
                                        <div class='rTableCell'><b>Family Features</b></div>
                                    </div>";
                        if($features){
                            if (($key+1) == count($features)) $last_class = 'last_feature';
                            foreach ($features as  $key=>$name)
                                $family_content .= elgg_format_element('div',['class'=>'rTableRow', 'style'=>'cursor:move;'],
                                                        elgg_format_element('div',['class'=>'rTableCell', 'style'=>'width:100%'],
                                                            elgg_format_element('input',['type'=>'text', 'name'=>"jot[$cid][features][]", 'value'=>$name, 'placeholder'=>'Feature', 'class'=>$last_class])));
                            }
                        else 
                            $family_content .="
                            			<div class='rTableRow' style='cursor:move'>
                            				<div class='rTableCell' style='width:100%'>".
                            			      elgg_view('input/text', array(
                            						'name'       => "jot[$cid][features][]",
                            			      		'class'      => 'last_feature',
                            			      		'placeholder'=>'Feature',
                            					))."
                            		        </div>
                            			</div>";
                        
                        $family_content .= "<div class='new_feature'></div>";
                        $family_content .= "</div>
                        			</div>";
                }
                
                $form = $hidden_fields.$family_content;
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
*add********** $section = 'gallery'                      *****************************************************************
				 ****************************************/
            case 'gallery':
                unset($hidden, $hidden_fields);
                
                $form = $hidden_fields.'gallery';
                break;
				/****************************************
*add********** $section = 'library'                      *****************************************************************
				 ****************************************/
            case 'library':
                unset($hidden, $hidden_fields);
                
                $form = $hidden_fields.'library';
                break;
            default:
                $form = elgg_view('forms/market/edit',['perspective'=>$perspective, 'presentation'=>$presentation, 'section'=>'main', 'cid'=>$cid, 'entity'=>$entity]);
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
                $item_description     = elgg_view('input/description',['cid'=>$cid]);
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
                $category_name         = get_entity($entity->category)->title ?: 'Select ...';
                

                $boqx_selector = "
                  <div class='dropdown category' data-selector='boqx_category' data-cid='$cid'>  
                      <input aria-hidden='true' type='hidden' name='jot[$cid][category]'>
                      <a id='item_category_dropdown_$cid' class='selection item_0' tabindex='-1'><span>$category_name</span></a>
                      <a id='item_category_dropdown_{$cid}_arrow' class='arrow target' tabindex='-1'></a>
                      <section class='pickCategory__VRYE6ZAO closed'>
                         $weir_menu
                      </section>
                  </div>";                            	
    	        $item_category     = elgg_format_element('div', ['class'=>['info_box','free']],
    	                                         elgg_format_element('div', ['class'=>'info'],
    	                                              elgg_format_element('div', ['class'=>'row'],
    	                                                  "<em>Category</em>".
    	                                                  $boqx_selector)));
    	        $category_picker = elgg_view('input/category', ['presentation'=>$presentation, 'perspective'=>$perspective, 'parent_cid'=>$cid, 'add_leaf' => false,'category_class'=>'BoqxCategoryPick__gNSC0Iny','item_class'=>'CategoryDropdownItem___qgkw9JYv', 'root_name_override'=>'blah', 'show_section_headers'=>false]);
    	        $form      = $item_description;				
				$form     .= $item_category;
				$form     .= elgg_view('forms/market/edit', ['presentation'=>$presentation, 'perspective'=>'add', 'cid'=>$cid, 'entity'=>$entity]);
                break;
               /****************************************
*edit********** $section = 'item edit'                       *****************************************************************
               ****************************************/
            case 'item edit':
                unset($form_body, $disabled, $hidden, $hidden_fields, $id_value);
                $hidden[] =['name'=>"jot[cid]"             , 'value' => $parent_cid];
                $hidden[] =['name'=>"jot[$parent_cid][aspect]", 'value' => 'things'];
                $hidden[] =['name'=>"jot[$parent_cid][cid]", 'value' => $parent_cid];
                $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
                $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                $hidden[] =['name'=>"jot[$cid][guid]"      , 'value' => $guid];
                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => $aspect    , 'data-focus-id' => "Aspect--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => $fill_level, 'data-focus-id' => "FillLevel--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => "$n"       , 'data-focus-id' => "SortOrder--{$cid}"];
                //$qid_n = "{$cid}_{$n}";
                $collapser = elgg_format_element('a',['class'=>'collapser-item', 'id'=>"item_collapser_{$cid}",'data-cid'=>"$cid", 'tabindex'=>'-1', 'style'=>'margin-top:0;']);
                $view = 'forms/transfers/edit';
                $edit_item = elgg_format_element('button',['class'=>['AddItem__submit___u7pvMd9T','std','egg'], 'type'=>'submit', 'data-aid'=>'addItemButton', 'style'=>'margin: 3px 3px 0 15px;order: 2;', 'data-boqx'=>$parent_cid, 'data-cid'=>$cid, 'data-presence'=>$presentation],'Add');
                $edit_item = elgg_format_element('button',['class'=>['autosaves','cancel','clear'],'type'=>'reset','id'=>"submit_cancel_$cid", 'data-boqx'=>$parent_cid, 'data-cid'=>$cid, 'tabindex'=>'-1', 'data-presence'=>$presentation, 'title'=>'Cancel changes'],'Cancel').
                             elgg_format_element('button',['class'=>['CloseItem__submit__oz8vFV9a','autosaves','std','button','egg'],'type'=>'submit','id'=>"boqx_close_$cid", 'data-boqx'=>$parent_cid, 'data-cid'=>$cid, 'tabindex'=>'-1', 'data-presence'=>$presentation,'title'=>'Close and save changes'],'Close');
                                  
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
                $hidden[] =['name'=>"jot[$cid][unpack]"    , 'value' => $unpack_toggle , 'data-focus-id' => "Unpack--{$cid}"];
                if (!empty($hidden)){                
                    foreach($hidden as $key=>$field){
                        $hidden_fields .= elgg_view('input/hidden', $field);}}
                
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
                                                     elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_{$cid}"],'data-aid'=>'name','value'=>$entity->title, 'tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'name'=>"jot[$cid][title]",'placeholder'=>'Item name'],
                                                          $entity->title))))));
                $line_item_properties     = elgg_view('forms/market/edit', ['presentation'=>$presentation, 'perspective'=>$perspective, 'section'=>'item details', 'cid'=>$cid, 'entity'=>$entity]);
                $nav_controls  = elgg_view('navigation/controls',['form_method'=>'post', 'parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$perspective,'presentation'=>$presentation,'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'maximize'=>true,'cancel'=>true,'action'=>true]]);
                $item_controls = "
                            <nav class='edit'>
                                <section class='controls'>
                                    <div class='persistence' style='order:1'>
                                       $edit_item
                                    </div>
                                    <div class='actions'>
                                      $unpack_button
                                      $clone_button
                                      $delete_button
                                    </div>
                                    <section class='toggleTags'>
                                        <span class='toggleUnpackTag' style='$unpack_tag_toggle'>$unpack_label</span>
                                    </section>
                                  </section>
                            </nav>";
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
                $issue_body_vars['parent_cid'] = $cid; 
                unset($issue_body_vars['cid']);
                $issue_body_vars['action'] = 'add';
                $issue_body_vars['section']='issue';
                $issue_body_vars['presentation']='pallet';
                $issue_body_vars['presence']='item boqx';
                $item_issues = elgg_view('forms/experiences/edit',$issue_body_vars);
    	        $boqx_contents_edit = "
                    <section class='ItemEdit___7asBc1YY info_box free' $style_edit data-aid='ItemEdit' data-cid='$cid'>
        			  <div class='ItemEditValue'>
						$hidden_fields
						$boqx_header
						<section class='ItemLedger__KY8DM3qs' data-cid='$cid'>
							$nav_controls
							$acquisition_details
                            $item_details
						</section>
                        <section class='ItemLabels__HqGmyX2Y' data-cid='$cid'>
							$labels_maker                            
                        </section>
                        <section class='ItemIssues__3d5EmH6b' data-cid='$cid'>
                            $item_issues
                        </section>
                      </div>
        			</div>";
    	        $form = $boqx_contents_edit;
                break;
               /****************************************
*edit********** $section = 'xxx'                       *****************************************************************
               ****************************************/
            case 'xxx':
                
                break;
               /****************************************
*edit********** $section = 'single_thing'                       *****************************************************************
               ****************************************/
            case 'single_thing':
                $element = 'item';
                $delete = elgg_format_element("span",['class'=>'remove-item'], elgg_format_element('a', ['title' =>'remove item'],
                                                            	                                                elgg_view_icon('delete-alt')));
                $horizontal_offset = '-140';
                
                
                $edit_properties_button = elgg_view('output/url', ['data-cid'            => $cid,
												                   'class'               => "ItemEdit__showContainer",
                                                                   'text'                => elgg_view_icon('settings-alt').'Item Details', 
										                           'title'               => 'show properties',]);
    	        
    	        
            	
    	        $style_add = $style_show = $style_edit = "style='display:none;'";
                switch ($display_state){case 'add':  unset($style_add);  break;
    	                                case 'show': unset($style_show); break;
    	                                case 'edit': $style_edit = "style='display:flex;'"; break;}
/*    	        $boqx_options      = [	'type'                 => 'object',
                                    	'subtype'              => 'boqx',
                                    	'relationship'         => 'contains',
                                    	'relationship_guid'    => $guid,
                                    	'inverse_relationship' => true,
                                    	'limit'                => false];
    	        $boqx              = elgg_get_entities_from_relationship($boqx_options);*/
                $boqx_contents_add = "<div tabindex='0' class='AddSubresourceButton___oKRbUbg6' $style_add data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
                                    				<span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
                                    				<span class='AddSubresourceButton__message___2vsNCBXi'>Add an item</span>
                                			  </div>";
                $boqx_contents_show = "<div class='ItemShow_Btc471up' $style_show data-aid='TaskShow' data-cid='$cid' draggable='true'>
    					<div>
                            <nav class='ItemShow__actions__4AR4v8MP ItemShow__actions--unfocused___1df6Nh5x'>
            					<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
            						$delete
            					</button>
            				</nav>
    					</div>
                        <div class='ItemShow__line_gSpQE5Ao'>
                            <span class='ItemShow__title__8tlRYJcP section-add-boqx_contents_marker1'></span>
        					<span class='ItemShow__item_total__Dgd1dOSZ'></span>
                        </div>
        			</div>";
                
    	        $section_vars           = $vars;
    	        $section_vars['cid']    = $cid;
    	        $section_vars['section']='item edit';
    	        $form_version           = 'market/edit';
    	        $form_vars = ['name' => 'marketForm', 
						      'enctype' => 'multipart/form-data', 
						      'action' => 'action/jot/edit_pallet'];
            	$boqx_contents_edit = elgg_view_form($form_version,$form_vars,$section_vars);
            	if ($unpacked) unset($boqx_contents_add,$boqx_contents_show);
                $single_thing = elgg_format_element('div',['id'=>$cid, 'class'=>['Item__nhjb4ONn','boqx-item'], 'data-boqx'=>$parent_cid, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'boqx-fill-level'=>'0'], $boqx_contents_add.$boqx_contents_show.$boqx_contents_edit);
		                        
               $form = $single_thing;
                
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
        				'subtypes' => array('market'),
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