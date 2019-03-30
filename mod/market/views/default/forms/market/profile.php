<!-- View: market/views/default/forms/market/profile.php -->
<?php

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}
$presentation = elgg_extract('presentation', $vars, 'full');                         $display .= '10 $presentation: '.$presentation.'<br>';
$perspective  = elgg_extract('perspective', $vars, 'edit');		
$qid          = elgg_extract('qid', $vars);
$cid          = elgg_extract('cid', $vars);                                          $display .= '13 $cid: '.$cid.'<br>13 isset($cid): '.isset($cid).'<br>';
$space        = elgg_extract('space',$vars);
$context      = elgg_extract('context', $vars);

$guid         = $vars['guid'];                                                        $display .= '17 $guid: '.$guid.'<br>';
$lineage_level= $vars['h'];
$entity       = get_entity($guid);
$category     = get_entity($entity->category);
$entity_owner = get_entity($entity->owner_guid);
$record_stage = $entity->record_stage;                                                $display  .= '22 $record_stage:'.$record_stage.'<br>';

//if (elgg_instanceof($entity, 'object','market')) {
  // must always supply $guid $h (current hierarchy) and current $level
if ($guid)          $hidden['guid']               = $guid;
if ($qid)           $hidden['qid']                = $qid;
if ($lineage_level) $hidden['h']                  = $lineage_level;
if ($record_stage)  $hidden['item[record_stage]'] = $record_stage;
  $hidden['level']     = 'family';
  $hidden['item[family]']     = 'family';
  
  $this_level = '/car';
  $next_level = '/family'; //probably not the right way to reference the next level.
//}

$category_content    = "forms/market/edit/category";
$family_content      = "forms/market/edit/family";        $family_header = 'Item Family';
$individual_content  = "forms/market/edit/individual";    $individual_header = 'Item';    
$acquisition_content = "forms/market/edit/acquisition";
$gallery_content     = "forms/market/edit/gallery";
$library_content     = "forms/market/edit/library";
  
$hierarchy           = hypeJunction\Categories\get_hierarchy($category->guid, true, true);
foreach(array_reverse($hierarchy) as $key=>$cat_guid){
    $this_category = get_entity($cat_guid);
    if (elgg_view_exists("forms/market/edit/".strtolower($this_category->title)."/category")){
        $category_content    = "forms/market/edit/".strtolower($this_category->title)."/category";
    }
    if (elgg_view_exists("forms/market/edit/".strtolower($this_category->title)."/family")){
        $family_content    = "forms/market/edit/".strtolower($this_category->title)."/family";
        if (substr($this_category->title, -1) == 's'){
            $family_header = substr($this_category->title, 0, -1)." Family";
        }
        else {
            $family_header     = "$this_category->title Family";
        }
    }
    if (elgg_view_exists("forms/market/edit/".strtolower($this_category->title)."/individual")){
        $individual_content    = "forms/market/edit/".strtolower($this_category->title)."/individual";
        $individual_header     = 'Individual';
    }
    if (elgg_view_exists("forms/market/edit/".strtolower($this_category->title)."/acquisition")){
        $acquisition_content    = "forms/market/edit/".strtolower($this_category->title)."/acquisition";
    }
}  

$title = $entity['title'];                                                                                   $display .= '67 $title: '.$title.'<br>';
$body = $entity['description'];
$tags = $entity->tags;
$access_id = $entity['access_id']; 
	
$selected_category = $category->title;

if (!empty($hidden)){
    foreach($hidden as $field=>$value){
        $content .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
        
$content .= "<div id='Category_panel' class='elgg-head' style='margin:5px'>".elgg_view($category_content, $vars)."</div>";

	$selected = 'family';
	$tabs[]=['title'=>'Family'     , 'aspect'=>'family'       , 'section'=>'us' , 'note'=>'Common characteristics'               , 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_1", 'selected'=>$selected == 'family'];
    $tabs[]=['title'=>'Individual' , 'aspect'=>'individual'   , 'section'=>'this' , 'note'=>'Characteristics unique to this item', 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_2", 'selected'=>$selected == 'individual'];
    $tabs[]=['title'=>'Receipt'    , 'aspect'=>'receipt'      , 'section'=>'get' , 'note'=>'Acquisition'                         , 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_3", 'selected'=>$selected == 'receipt'];
    $tabs[]=['title'=>'Gallery'    , 'aspect'=>'gallery'      , 'section'=>'pics', 'note'=>'Pictures'                            , 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_4", 'selected'=>$selected == 'gallery'];
    $tabs[]=['title'=>'Library'    , 'aspect'=>'library'      , 'section'=>'docs', 'note'=>'Documents'                           , 'class'=>'qbox-q3', 'guid'=>$guid,'data-qid'=>"q{$guid}_5", 'selected'=>$selected == 'library'];
    $panels[]=['aspect'=>'family'    , 'class'=>'option-panel family-option-panel'    , 'content'=> elgg_view($family_content, $vars)];
    $panels[]=['aspect'=>'individual', 'class'=>'option-panel individual-option-panel', 'content'=> elgg_view($individual_content, $vars)];
    $panels[]=['aspect'=>'receipt'   , 'class'=>'option-panel receipt-option-panel'   , 'content'=> elgg_view($acquisition_content, $vars)];
    $panels[]=['aspect'=>'gallery'   , 'class'=>'option-panel gallery-option-panel'   , 'content'=> elgg_view($gallery_content, $vars)];
    $panels[]=['aspect'=>'library'   , 'class'=>'option-panel library-option-panel'   , 'content'=> elgg_view($library_content, $vars)];

    $nav['tabs']  = $tabs;
    $nav['space'] = $space;
    $nav['qid']   = $qid;
    $nav['class'] ='quebx-tabs';
    $navigation   = elgg_view('navigation/tabs_slide', $nav);
    foreach($panels as $key=>$panel){
    	$n = $key+1;
    	$is_selected = $selected == $panel['aspect'];
    	$class       = $panel['class'];
	    if ($is_selected) {
			$class .= ' qbox-state-selected';
		}
    	$detail .= elgg_view('output/div',['content'=>$panel['content'], 'class'=>$class, 'options'=>['id'=>"q{$guid}_{$n}"]]);
    }
    $details = elgg_view('output/div',['content'=>$detail, 'class'=>"qbox-details qbox-$guid"]);
    
$content .= $navigation.$details;
$content .= '<br><br>
             <div class="elgg-foot">';
if ($presentation == 'full'||$presentation == 'full_view'){
	$content .= elgg_view('input/submit', array('value' => 'Save'));
}
if ($presentation == 'full'){
	$content .= elgg_view('input/submit', array('value' => 'Apply', 'name' => 'apply'));
}
$content .= '</div>';

switch ($presentation){
	case 'full':
		$form_body = $content;
		break;
	case 'qboqx':
	    unset($tabs[2],    // Remove 'Receipt' tab
	          $panels[2],  // Remove 'Receipt' panel
	          $detail, $details,$nav,$navigation, $class, $content
	         );
	    
	    $parent_cid   = elgg_extract('parent_cid', $vars);
	    $cid          = elgg_extract('cid', $vars);
	    $n            = elgg_extract('n', $vars);
	    $data_prefix  = elgg_extract('data_prefix', $vars, "jot[$parent_cid][$cid][$n][");
	    $nav['tabs']  = $tabs;
	    $nav['class'] = 'quebx-tabs';
        $nav['space'] = $space;
        $nav['qid']   = $qid;
        $navigation   = elgg_view('navigation/tabs_slide', $nav);
        foreach($panels as $key=>$panel){
        	$n = $key+1;
        	$is_selected = $selected == $panel['aspect'];
        	$class       = $panel['class'];
    	    if ($is_selected) {
    			$class .= ' qbox-state-selected';
    		}
        	$detail .= elgg_view('output/div',['content'=>$panel['content'], 'class'=>$class, 'options'=>['id'=>"q{$guid}_{$n}"]]);
        }
        $body_vars = $vars;
        $header  = elgg_view($category_content, $body_vars);
        $details = elgg_view('output/div',['content'=>$detail, 'class'=>"qbox-details qbox-$guid"]);
        
        $content = "<div id='Category_panel' class='elgg-head' style='margin:5px'>$header</div>";
        $content .= $navigation.$details;
        $content   = str_replace('jot[', $data_prefix, $content);
        $form_body = str_replace('item[', $data_prefix, $content);		
		break;
	case 'popup':
	   
	case 'inline':
/*		$save_icon  = elgg_view_icon('save',['title'=>'Save', 'name'=>'apply']);
		$close_icon = elgg_view_icon('window-close',['title'=>'Close']);
		if (isset($cid)){$data_cid = "data-cid='$cid'";}                                                           $display .= '128 $data_cid: '.$data_cid.'<br>';
		$save_button = "<button  class='do' type='submit' id='qboxSave' data-guid=$guid data-qid=$qid value='Save' data-perspective='save'
                                 style='right:16px'> $save_icon
						</button>";
		$close_button = "<button type='button' id='inlineClose' data-qid='$qid' $data_cid data-perspective='$perspective'>
							$close_icon
						</button>";
        if ($presentation == 'popup') unset($close_button, $save_button);
		$form_body = "<div id='$qid' class='inline inline-visible' $pos_style role='data entry' tabindex='-1' data-space='$space' data-perspective='$perspective' data-context = '$context' 'data-aspect'='item'>
								<div id='inlineLoadedContent'>
										<div class='elgg-body inline-body'>
											<div class='elgg-layout elgg-layout-default clearfix'>
		                                        $content
		                                    </div>
    									</div>
		                            $close_button
									$save_button
								</div>
						</div>";
*///		$layout_vars = array_merge(
	//	    $vars,
        $layout_vars = 
		    ['content'       => $content,
	         'show_full'     => false,
	         'show_save'     => false,
	         'show_edit'     => false,
	         'show_close'    => true,
	         'show_title'    => false,
	         'space'         => $space,
	         'perspective'   => $perspective,
	         'context'       => $context,
//	         'qid'           => $qid,
	         'cid'           => $cid,
		    ];
		
		$form_body = elgg_view_layout('inline',$layout_vars);
				
		break;
	case 'full_view':
		$close_icon= elgg_view_icon('window-close',['title'=>'Close']);
		$close_button = "<button type='button' id='inlineClose' data-qid=$qid data-perspective='$perspective'>
							$close_icon
						</button>";
		$form_body = "<div id=$qid class='inline inline-visible' $pos_style role='data entry' tabindex='-1' data-space='$space' data-perspective='$perspective' data-context = '$context'>
								<div id='inlineLoadedContent'>
										<div class='elgg-body inline-body'>
											<div class='elgg-layout elgg-layout-default clearfix'>
		                                        $content
		                                    </div>
		                                </div>
									</div>
		                            $close_button
								</div>
						</div>";
		break;
}
	
echo $form_body;
//register_error( $display);