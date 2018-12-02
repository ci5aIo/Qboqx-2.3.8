<?php
$element = elgg_extract('element', $vars);
$guid    = $vars['guid'];
$cid     = elgg_extract('cid', $vars);
$service_cid = elgg_extract('service_cid', $vars);
$qid     = elgg_extract('qid', $vars);
$show_view_summary = elgg_extract('show_view_summary', $vars, true);

// if using third-party input views, make sure they initialize their scripts inline
// require(['third-party-input']);
// and not using elgg_require_js(), because currently those are not intialized on
// ajax requests
Switch ($element){
	case 'new_story':
		$delete_button = "<label class=remove-progress-marker>". 
		           	     elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
		              	 "</label>";
		$delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
		$expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'      => 'toggle_marker', 'data-cid'=>$cid,'tabindex'=> '-1',]);
		$story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
		$preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
		$form         = 'forms/experiences/edit';
		$view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
		$edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
			                                    'options'=> ['data-cid'=>$cid],
		                    			    	'content'=>$view_summary
		                    			    	. elgg_view($form,['section'=>'project_in_process', 'snippet'=>'marker', 'cid'=>$cid])]);
		$story_model .= elgg_view('output/div', ['class'  =>'rTableRow',
			                                    'options'=> ['data-cid'=>$cid],
		                                        'content'=>elgg_view('output/div', ['class'  =>'rTableCell',
		                                                                            'options'=>['style'=>'width:100%; padding: 0px 0px;vertical-align:top'],
		                                                                            'content'=> elgg_view('output/div',  ['class' =>'story model item pin',
		                                                                                                                 'options'=> ['data-cid'=>$cid],
		                                                                                                                 'content'=>$edit_details])])]);
			            		            		            	
		$form_body = str_replace('<<cid>>', $cid, $story_model);
	case 'new_discovery':
		$delete_button = "<label class=remove-progress-marker>". 
		           	     elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
		              	 "</label>";
		$delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
		$expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'      => 'toggle_marker', 'data-cid'=>$cid, 'data-qid'=>$qid,'tabindex'=> '-1',]);
		$story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
		$preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
		$form         = 'forms/experiences/edit';
		$view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
		$edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
			                                    'options'=> ['data-cid'=>$cid, 'data-qid'=>$qid],
		                    			    	'content'=>$view_summary
		                    			    	. elgg_view($form,['section'=>'issue_discovery', 'action'=>'add', 'snippet'=>'marker', 'cid'=>$cid, 'qid'=>$qid])]);
		$story_model .= elgg_view('output/div', ['class'  =>'rTableRow',
			                                    'options'=> ['data-cid'=>$cid, 'data-qid'=>$qid],
		                                        'content'=>elgg_view('output/div', ['class'  =>'rTableCell',
		                                                                            'options'=>['style'=>'width:100%; padding: 0px 0px;vertical-align:top'],
		                                                                            'content'=> elgg_view('output/div',  ['class' =>'story model item pin',
		                                                                                                                 'options'=> ['data-cid'=>$cid, 'data-qid'=>$qid],
		                                                                                                                 'content'=>$edit_details])])]);
			            		            		            	
		$form_body = str_replace('<<cid>>', $cid, $story_model);
	break;
	case 'new_effort':
		unset($view_summary);
		$delete_button = "<label class=remove-progress-marker>". 
		           	     elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
		              	 "</label>";
		$delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
		$expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'=> 'toggle_marker', 'data-cid'=>$cid, 'data-qid'=>$qid, 'tabindex'=> '-1',]);
		$story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
		$preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
		$form         = 'forms/experiences/edit';
		if ($show_view_summary){
			$view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
		}
		$hidden_fields= elgg_view('input/hidden', ['name'=>"jot[observation][effort][$cid][aspect]", 'value'=>'effort']);
		$edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
			                                    'options'=> ['data-cid'=>$cid, 'data-qid'=>$qid],
		                    			    	'content'=>$view_summary
		                    			    	. elgg_view($form,['section'=>'issue_resolve', 'action'=>'add', 'snippet'=>'marker', 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
		$story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
		                                          'options'=> ['data-cid'=>$cid, 'data-qid'=>$qid],
		                                          'content'=>$hidden_fields.$edit_details]);
			            		            		            	
		$form_body = str_replace('<<cid>>', $cid, $story_model);                                   $display.= 'form_elements 90 new_effort>$cid: '.$cid.'<br>form_elements  90 new_effort>$service_cid: '.$service_cid.'<br>';
//register_error($display);
		break;
	case 'new_thing':
// DRAFT	    
	    unset($view_summary);
	    $delete_button = "<label class=remove-progress-marker>".
	   	    elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
	   	    "</label>";
	   	    $delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
	   	    $expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'=> 'toggle_marker', 'data-cid'=>$cid, 'data-qid'=>$qid, 'tabindex'=> '-1',]);
	   	    $story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
	   	    $preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
	   	    $form         = 'forms/experiences/edit';
	   	    if ($show_view_summary){
	   	        $view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
	   	    }
	   	    $hidden_fields= elgg_view('input/hidden', ['name'=>"jot[observation][effort][$cid][aspect]", 'value'=>'effort']);
	   	    $edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
	   	        'options'=> ['data-cid'=>$cid, 'data-qid'=>$qid],
	   	        'content'=>$view_summary
	   	        . elgg_view($form,['section'=>'issue_resolve', 'action'=>'add', 'snippet'=>'marker', 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
	   	    $story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
	   	        'options'=> ['data-cid'=>$cid, 'data-qid'=>$qid],
	   	        'content'=>$hidden_fields.$edit_details]);
	   	    
	   	    $form_body = str_replace('<<cid>>', $cid, $story_model);                                   $display.= 'form_elements 90 new_effort>$cid: '.$cid.'<br>form_elements  90 new_effort>$service_cid: '.$service_cid.'<br>';
	   	    
	case 'new_service_effort':
		$delete_button = "<label class=remove-progress-marker>". 
		           	     elgg_view('output/url', ['title'=>'remove progress marker','class'=>'remove-progress-marker','text' => elgg_view_icon('delete-alt'), 'data-qid'=>$qid,]).
		              	 "</label>";
		$delete       = elgg_view("output/span", ["class"  =>"remove-progress-marker", "content"=>$delete_button]);
		$expander     = elgg_view("output/url",  ['text'   => '','class' => 'expander undraggable','id'=> 'toggle_marker', 'data-cid'=>$cid, 'tabindex'=> '-1',]);
		$story_span   = elgg_view('output/span', ['content'=>'dig?','class'=>'story_name']);
		$preview      = elgg_view('output/span', ['content'=>$story_span,'class'=>'name tracker_markup']);
		$form         = 'forms/experiences/edit';
		if ($show_view_summary){
			$view_summary = elgg_view('output/header', ['content'=>$expander.$preview.$delete, 'class'=>'preview collapsed']);
		}
		$edit_details = elgg_view('output/div',
				                 ['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
			                      'options'=> ['data-cid'=>$cid],
		                    	  'content'=>$view_summary
		                    	 . elgg_view($form,
		                    	 		    ['section'=>'issue_effort_service', 
		                    	 		     'action'=>'add', 
		                    	 		     'snippet'=>'marker', 
		                    	 		     'cid'=>$cid, 
		                    	 		     'qid'=>$qid])]);
		$story_model .= elgg_view('output/div', ['class'  =>'rTableRow',
			                                    'options'=> ['data-cid'=>$cid],
		                                        'content'=>elgg_view('output/div', 
		                                        					['class'  =>'rTableCell',
		                                                             'options'=>['style'=>'width:100%; padding: 0px 0px;vertical-align:top'],
		                                                             'content'=> elgg_view('output/div',  
		                                                             		              ['class' =>'story model item pin',
		                                                                                   'options'=> ['data-cid'=>$cid],
		                                                                                   'content'=>$edit_details])])]);
			            		            		            	
		$form_body = str_replace('<<cid>>', $cid, $story_model);
	break;
	case 'new_item_characteristic':
		$form_body = "
            <div class='rTableRow'>
				<div class='rTableCell' style='width:250px'>".
				    elgg_view('input/text', array(
					'name'  => 'item[characteristic_names][]',
					'placeholder'=>'Characteristic'
				))."</div>
				<div class='rTableCell' style='width:460px'>".
				elgg_view('input/text', array(
					'name'  => 'item[characteristic_values][]',
					'placeholder'=> 'Value',
 					'class' => 'last_characteristic',
				))."</div>
			</div>";
	break;
	case 'new_item_feature':
		$form_body = "
        <div class='rTableRow'>
				<div class='rTableCell' style='width:90%'>".
			      elgg_view('input/text', array(
						'name' => 'item[features][]',
						'placeholder'=>'Feature',
			      		'class' => 'last_feature',
					))."
		        </div>
			</div>";
	break;
	case 'gallery':
		$entity          = get_entity($guid);
		$body_vars       = array('entity'=>$entity, 'guid'=>$guid,);
		$gallery_content = "forms/market/edit/gallery";
		$form_body       = "<div id='gallery_panel' class='elgg-head'>".elgg_view($gallery_content, $body_vars)."</div>";
	break;
	default:
	break;
}


echo $form_body;
//register_error($display);