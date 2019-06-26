<!--View: market/views/default/forms/market/edit/individual.php  -->
<?php
$guid = elgg_extract('guid', $vars, false);
$entity = get_entity($guid);

if (empty($entity->frequency_units)){
	$frequency_units_value = 'miles';
}
else {
	$frequency_units_value = $entity->frequency_units;
}
/*
 * @uses string $vars['name']     The name of the input fields
 *                                (Forced to an array by appending [])
 * @uses array  $vars['options']  An array of strings representing the
 *                                label => option for the each checkbox field
 * @uses string $vars['default']  The default value to send if nothing is checked.
 *                                Optional, defaults to 0. Set to FALSE for no default.
 * @uses bool   $vars['disabled'] Make all input elements disabled. Optional.
 * @uses string $vars['value']    The current value. Single value or array. Optional.
 * @uses string $vars['class']    Additional class of the list. Optional.
 * @uses string $vars['align']    'horizontal' or 'vertical' Default: 'vertical'
 *
 */
$current_visibility_choices = $entity->visibility_choices;
if (elgg_entity_exists($guid)) $visibility_value = $entity->visibility_choices;
else                           $visibility_value = 'show';

$visibility_choices = array(
	'align'    => 'horizontal',
	'value'    => $visibility_value,
	'default'  => 'show',
	'disabled' => false,
//    'label'   => 'Hide from catalog',
	'options'  => ['Show in catalog'=>'show', 'Hide from Catalog'=>'hide'],
	'name'     => 'item[visibility_choices]',
    'style'    => 'font-weight:normal',
);

$visibility = elgg_view('input/radio', $visibility_choices);

/*
$individual_content .= "
	<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
					<div class='rTableCell' style='width:250px'><label>Vehicle Identification Number (VIN)</label>&nbsp;</div>
					<div class='rTableCell' style='width:400px'>".elgg_view('input/text', array('name' => 'item[vin]','value' => $entity->vin))."			    </div>
			</div>
		</div>
	</div>";
*/
$individual_content .= "<div class='rTable' style='width:100%'>
		<div id='item_characteristics' class='rTableBody'>
			<div class='rTableRow pin'>
					<div class='rTableCell' style='width:250px'><label>Individual Characteristics</label>&nbsp;</div>
					<div class='rTableCell' style='width:460px'></div>
					<div class='rTableCell' style='width:200px'><span class='hoverhelp'>".
												elgg_view('output/url', array(
													'text' => '[add characteristic]',
													'href' => '#',
													'class' => 'clone-individual-characteristic-action1' // unique class for jquery
												))."
												<span style='width:200px;'>Add another characteristic.</span>
												</span>
			    </div>
			</div>
		</div>
	</div>";

$individual_content .="<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:250px'>".
			      elgg_view('input/text', array(
					'name' => 'item[this_characteristic_names][]',
					'value' => $name,
				))."</div>
				<div class='rTableCell' style='width:460px'>".
				elgg_view('input/text', array(
					'name' => 'item[this_characteristic_values][]',
					'value' => $values[$key],
				))."</div>
				<div class='rTableCell' style='width:200px'>
	            </div>
			</div>";


$names = $entity->this_characteristic_names;
if ($names && !is_array($names)) {
	$names = array($names);
}

$values = $entity->this_characteristic_values;
if ($values && !is_array($values)) {
	$values = array($values);
}

// iterate throught the names/values to populate what we have
if (isset($names)){
    foreach ($names as $key => $name) {
    	if ($name === ''){
    		continue;
    	}
    $individual_content .="<div class='rTableRow'>
    				<div class='rTableCell' style='width:250px'>$name".
    			      elgg_view('input/hidden', array(
    					'name' => 'item[this_characteristic_names][]',
    					'value' => $name,
    			      	 'placeholder'=>'Characteristic',
    				))."</div>
    				<div class='rTableCell' style='width:460px'>".
    				elgg_view('input/text', array(
    					'name' => 'item[this_characteristic_values][]',
    					'value' => $values[$key],
    					'placeholder'=>'Value',
    				))."</div>
    				<div class='rTableCell' style='width:200px'>
                        <a href='#' class='remove-node'>remove</a>
    		        </div>
    			</div>";
    }
}
$individual_content .= '<div class="new_individual_characteristic1"></div>';
$individual_content .= "</div>
		</div>";

$individual_content .= "<div class='rTable' style='width:100%'>
		<div id='item_features' class='rTableBody'>
			<div class='rTableRow pin'>
				<div class='rTableCell' style='width:250px'><b>Individual Features (options)</b>&nbsp;
				</div>
				<div class='rTableCell' style='width:460px'></div>
				<div class='rTableCell' style='width:200px'><span class='hoverhelp'>".
											elgg_view('output/url', array(
												'text' => '[add feature]',
												'href' => '#',
												'class' => 'clone-individual-feature-action1' // unique class for jquery
											))."
											<span style='width:200px;'>Add another feature.</span>
											</span>
		         </div>
			</div>
		</div>
	</div>";
$individual_content .="<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[this_features][]',
						'value' => $feature,
					))."
		        </div>
				<div class='rTableCell'>
				</div>
			</div>";
$individual_content .= "<div class='new_individual_feature1'></div>";

$features = $entity->this_features;
if ($features && !is_array($features)) {
	$features = array($features);
}
if ($features){
    foreach ($features as $feature) {
    	if ($feature === '') {
    		// don't show empty values
    		continue;
    	}
    $individual_content .= "<div class='rTableRow'>
    				<div class='rTableCell' style='width:90%'>$feature".
    			      elgg_view('input/hidden', array(
    						'name' => 'item[this_features][]',
    						'value' => $feature,
    			      		 'placeholder'=>'Feature',
    					))."</div>
    				<div class='rTableCell'>
                        <a href='#' class='remove-node'>remove</a>
    				</div>
    			</div>";
    }
}
$individual_content .= "</div>
		</div>";

$guids = array();
if ($groups){
    foreach ($groups as $e) {
    	$guids[] = $e->guid;
    }
}
if (count($individuals)>$value) {
   $value = count($individuals);
};
	$url = elgg_get_site_url() . "labels/$guid";
//	$url = elgg_add_action_tokens_to_url($url);
$label_input =  elgg_view('output/url', array(
                  "href" => $url,
                   "text" => "add label",
                   "class" => "elgg-lightbox"
        ));
if (elgg_entity_exists($guid))
    $labels = $entity->gettags();
$individual_content .= "<div class='rTable' style='width:100%'>
		<div class='rTableBody'>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'><b>Measure useage in</b></div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/select', array('options' => array('hours', 'days', 'weeks', 'months', 'years', 'miles', 'cycles'),'value' => $frequency_units_value,'name' => 'item[frequency_units]'))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Access</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/access', array('name' => 'item[access_id]','value' => $access_id))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Visibility</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>$visibility</div>
			</div>
			<!-- <snip 01> -->
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>".elgg_echo("market:tags")."&nbsp
					<span class='hoverhelp'>[?]
					    <span style='width:500px;'>Label items to place them into quebs (little collections).  Separate labels with commas.</span>
				    </span>
				</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view("input/tags", array("name" => "item[tags]","value" => $labels,'placeholder'=>'Queb Labels',))
				                                                           .elgg_view("input/hidden", array("name" => "jot[tags]","value" => $labels))."
                                                                            $label_input
                </div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Warranty</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/text', array('name' => 'item[warranty]','value' => $entity->warranty,  'placeholder'=>'Warranty',))
				                                                           .elgg_view('input/hidden', array('name' => 'jot[warranty]','value' => $entity->warranty))."</div>
			</div>
			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Lifecycle Type</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>
					<div class='rTable' style='width:100%'>
						<div class='rTableBody'>
							<div class='rTableRow'>
								<div class='rTableCell' style='width:50%;padding:0px 0px'>".elgg_view('input/radio', array('name' => 'item[lifecycle]',
																										   'align' => 'horizontal',
																											'id' => 'lifecycle',
																											'options' => array('Depleting' => '1', 'Depreciating' => '2'),
																											'default' => 2,
																											'value' => $entity->lifecycle
																											))."</div>
								<div class='rTableCell' style='width:20%;padding:0px 0px'>Qty on Hand
									<span class='hoverhelp'>[?]
										<span style='width:500px;'><p>How many identical ' . elgg_echo($entity->title) . ' items are available?</p><p>Click [add +] to clone this item or to increase the quantity on hand.</span>
									</span>
								</div>
								<div class='rTableCell' style='width:5%;padding:0px 0px'>".elgg_view('input/number', array(
                                                                            			'name'  => 'item[qty]',
								                                                        'value' => $entity->qty,
                                                                            		))."</div>
								<div class='rTableCell' style='width:25%;padding:0px 0px'>
									<span class='hoverhelp'>".elgg_view('output/url', array(
											'text' => '[add +]',
								            'href' =>  'market/inventory/'.$guid.'/'.$category.$next_level
										))."
										<span style='width:500px;'>Clone this item or increase the quantity on hand.</span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>";

$individual_content .= "</div>
</div>";
echo $individual_content;


/*****************************/
/*<snip 01>
 			<div class='rTableRow'>
				<div class='rTableCell' style='width:20%;padding:0px 5px'>Group Visibility
					<span class='hoverhelp'>[?]
						<span style='width:500px;'>Select one or more groups to feature this item.  Begin typing to show matching groups.</span>
					</span>
				</div>
				<div class='rTableCell' style='width:80%;padding:0px 5px'>".elgg_view('input/tokeninput', array('value' => $guids,
																												'name' => 'groups',
																												'callback' => 'group_picker_callback', // this is a php function that searches groups in given criteria - see start.php
																												'query' => array('guid' => $entity->guid), // some information we need to send to the callback function
																												'multiple' => true,
																											))."</div>
			</div>

 */

