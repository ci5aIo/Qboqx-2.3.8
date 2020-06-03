<!-- Form: jot/views/default/forms/experiences/edit.php -->
<?php
/**
 * experiences edit form body
 *
 * @package ElggPages	
 */
//elgg_use_library('elgg:quebx');
// Receive input
$guid        = elgg_extract('guid'             , $vars, get_input('guid'));
$owner_guid  = elgg_extract('owner_guid'       , $vars,                      // extract from $vars 
		                     get_input('owner_guid',                         // extract from form input
		                     elgg_get_logged_in_user_guid()));               // default to logged in user
$container_guid = elgg_extract('container_guid', $vars, get_input('container_guid'));
$section     = elgg_extract('section'          , $vars, 'main');             $display .= '15 $section: '.$section.'<br>';
$snippet     = elgg_extract('snippet'          , $vars);
$selected    = elgg_extract('selected'         , $vars, false);
$action      = elgg_extract('action'           , $vars);                      $display .= '18 $action: '.$action.'<br>';
$perspective = elgg_extract('perspective'      , $vars);                      $display .= '19 $perspective: '.$perspective.'<br>';
$tabs        = elgg_extract('tabs'             , $vars);
$expand_tabs = elgg_extract('expand_tabs'      , $vars, false);
$tabs_expand = elgg_extract('tabs_expand'      , $vars);
$presence    = elgg_extract('presence'         , $vars);                      $display .= '23 $presence: '.$presence.'<br>';
$presentation = elgg_extract('presentation'    , $vars, $presence ?: 'qbox_experience');           $display .= '24 $presentation: '.$presentation.'<br>';
	if($presentation == 'nested')                $vars['display_state'] = 'view';
$display_state= elgg_extract('display_state'   , $vars);
$qid         = elgg_extract('qid'              , $vars, quebx_new_id ('q'));
$n           = elgg_extract('n'                , $vars, '1');
$qid_n       = elgg_extract('qid_n'            , $vars, $qid.'_'.$n);
$parent_cid  = elgg_extract('parent_cid'       , $vars, false);
$boqx_id     = elgg_extract('boqx_id'          , $vars, $parent_cid);
$cid         = elgg_extract('cid'              , $vars, quebx_new_id ('c'));
$carton_id   = elgg_extract('carton_id'        , $vars);
$service_cid = elgg_extract('service_cid'      , $vars);
$aspect      = elgg_extract('aspect'           , $vars, false);
$title       = elgg_extract('title'            , $vars, false);
$visible     = elgg_extract('visible'          , $vars, 'add');
$fill_level  = elgg_extract('fill_level'       , $vars, 0);
$form_wrapper= elgg_extract('form_wrapper'     , $vars, false);
$title       = elgg_extract('title'            , $vars, false);
$origin      = elgg_extract('origin'           , $vars);
$form_method = elgg_extract('form_method'      , $vars, false);
$form_id     = elgg_extract('id'               , $form_wrapper['form_vars'], quebx_new_id('f'));

switch($presentation){
    case 'carton':    $form_method = 'pack carton';          break;
    case 'open_boqx': $form_method = 'open_boqx';            break;
    default         : $form_method = $form_method ?: 'post'; break;
}

$disabled    = $display_state == 'view';
$guid        = $guid==0 ? '' : $guid;
$entity      = get_entity($guid);                                             
$now         = new DateTime();
$now->setTimezone(new DateTimeZone('America/Chicago'));
if (elgg_entity_exists($guid)){
    $jot     = $entity;
    $subtype = $entity->getSubtype();
//    $aspect  = $jot->aspect ?: 'experience';
}                                                                             $display .= '53 $action: '.$action.'<br>53 $aspect: '.$aspect.'<br>53 $section: '.$section.'<br>';
$container = get_entity($container_guid);
echo "<!--origin=$origin, action=$action, section=$section, snippet=$snippet, aspect=$aspect, perspective=$perspective, presence=$presence, presentation=$presentation, display_state=$display_state, container_guid=$container_guid, guid=$guid, form_method=$form_method, parent_cid=$parent_cid, cid=$cid -->";

if ($selected){
      $style = 'display: block';} 
else{ $style = 'display: none';}  

// Define variables
//$form_body   .= '<br>guid:'.$guid.'<br>section: '.$section.'<br>selected:'.$selected;
																				$display .= '40 $cid: '.$cid.'<br>40 $service_cid: '.$service_cid.'<br>40 $snippet: '.$snippet.'<br>';
elgg_load_library('elgg:quebx');
$pick_date  = elgg_view_field(['#type' => 'date',]);
$album      = quebx_get_object_by_title(get_subtype_id('object', 'hjalbum'), 'Default');
$drop_media = elgg_view('input/dropzone', [
		'name' => 'jot[files]',
		'default-message'=> '<strong>Drop images videos or documents here</strong><br /><span>or click to select from your computer</span>',
		'max' => 25,
		'accept'=> 'image/*, video/*, application/vnd.*, application/pdf, text/plain',
		'multiple' => true,
		'style' => 'padding:0;',
		'container_guid' => $owner_guid,
        ]);
Switch ($action){
    case 'add':
        Switch ($section){
            /****************************************
*add********** $section = 'main_xxx'                 *****************************************************************
             ****************************************/
            case 'main_xxx':
                unset($form_body, $hidden, $buttons, $preloaded_panels);
                Switch ($presentation){
                    case 'pallet': $width_class = ''; break;
                    default:       $width_class = 'width:510px;'; break;
                }
                $title              = elgg_extract('title', $vars, 'Experiences');
                $title_field        = elgg_view("input/text"    , ["name" => "jot[$cid][title]"         , 'placeholder' => 'Give your experience a name', 'id'=>'title', 'data-parsley-required'=>'true']);
                $description_field  = elgg_view("input/longtext", ["name" => "jot[$cid][description]"   , 'placeholder' => 'Describe the experience ...', 'style' => "word-wrap: break-word; $width_class height: 52px; margin-left: 0px; margin-right: 0px;", 'id' => $qid.'_description']);
                $moment_field       = elgg_view('input/date'    , ['name' => 'jot[$cid][moment]'        , 'placeholder' => $now->format('Y-m-d')        , 'style' => 'width:150px']);
            	$expansion          = ['section' => 'issue', 'visibility' => 'display:none', 'subtypes'=>['observation'], 'title'=>'Issue'];
            	
                break;
            /****************************************
*add********** $section = 'main'                    *****************************************************************
             ****************************************/
            case 'main':
                unset($form_body, $hidden, $buttons, $preloaded_panels);
                $title              = elgg_extract('title', $vars, 'Experiences');
                $title_field        = elgg_view("input/text"    , ["name" => "jot[title]"         , 'placeholder' => 'Give your experience a name', 'id'=>'title', 'data-parsley-required'=>'true']);
                $description_field  = elgg_view("input/longtext", ["name" => "jot[description]"   , 'placeholder' => 'Describe the experience ...', 'style' => 'word-wrap: break-word; width:520px; height: 52px; margin-left: 0px; margin-right: 0px;', 'id' => $qid.'_description']);
                $moment_field       = elgg_view('input/date'    , ['name' => 'jot[moment]'        , 'placeholder' => $now->format('Y-m-d')        , 'style' => 'width:150px']);
//            	$buttons            = elgg_view('input/submit',	 array('value' => elgg_echo('save'), "class" => 'elgg-button-submit-element'));
            	$buttons            = "<button class='autosaves button std do_xxx' data-qid='$qid' type='submit' tabindex='-1' data-perspective='save'>Save</button>";
/*            	$forms_action       = 'forms/experiences/edit';
            	$view_sections[]    = ['section' => 'things'            , 'visibility' => 'display:none'];
            	$view_sections[]    = ['section' => 'documents'         , 'visibility' => 'display:none'];
            	$view_sections[]    = ['section' => 'gallery'           , 'visibility' => 'display:none'];
            	$view_sections[]    = ['section' => 'issue'             , 'visibility' => 'display:none', 'subtypes'=>['observation'], 'title'=>'Issue'];
            	foreach ($view_sections as $i=>$view_section) {
            	    unset($qid_n, $child_cid);
            	    $qid_n        = $qid.'_'.$i;
            	    $child_cid    = 'c'.mt_rand(1,999);
            	    $cid_array[$i] = $child_cid;
            	    $view_sections[$i]['cid'] = $child_cid;
            	    $body_vars = ['container_guid'=> $guid,
                            	  'qid'           => $qid_n,
                            	  'cid'           => $child_cid,
                            	  'parent_cid'    => $cid,
                            	  'section'       => $view_section['section'],
                            	  'visibility'    => $view_section['visibility'],
                            	  'presentation'  => 'qbox_experience',
                            	  'action'        => $action];
            	    $preloaded_panels            .= elgg_view($forms_action, $body_vars);
//            	    $preload_panels [] = $body_vars;
            	}
            	$vars['preloaded_panels'] = $preloaded_panels;
//            	$vars['preload_panels']   = $preload_panels;
*/
                // loading styles inline to raise their priority
                $hidden['jot[owner_guid]']     = $owner_guid;
            	$hidden['jot[container_guid]'] = $container_guid;
            	$hidden['jot[subtype]']        = $aspect;
            	$expansion                     = ['section' => 'issue', 'visibility' => 'display:none', 'subtypes'=>['observation'], 'title'=>'Issue'];
            break;
            /****************************************
*add********** $section = 'experiences'                   *****************************************************************
             ****************************************/
            case 'experiences':
                $carton_id  = quebx_new_id('c');
                $experience_vars = $vars;
                $experience_vars['section']    ='single_experience';
                $experience_vars['parent_cid'] = $cid;
                $experience_vars['carton_id']  = $carton_id;
                $experience_vars['title']='experiences';
                unset($experience_vars['cid']);
                $item_experience = elgg_view('forms/experiences/edit',$experience_vars);
                $form_body = elgg_format_element('div',['id'=>$carton_id, 'class'=>'boqx-carton', 'data-boqx'=>$cid, 'data-aspect'=>'experiences'],$item_experience);
            break;
            /****************************************
*add********** $section = 'single_experience'     *****************************************************************
             ****************************************/
            case 'experience':
            case 'single_experience':
                unset($input_tabs, $form_body, $hidden, $hidden_fields);
                $preview_id   = quebx_new_id('c');
                $hidden[]     = ['name'=>"jot[$cid][boqx]"      , 'value' => $preview_id];
                $hidden[]     = ['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                if($guid && $guid!=0){
                    $hidden[] = ['name'=>"jot[$cid][guid]"  , 'value' => $guid];
                    $action   = 'edit';
                }
                $hidden[] =['name'=>"jot[$cid][proximity]" , 'value' => 'on'];
                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => 'experience', 'data-focus-id' => "Aspect--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][contents]"  , 'value' => 'experience', 'data-focus-id' => "Contents--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => $fill_level, 'data-focus-id' => "FillLevel--{$cid}"];
                $hidden[]      = ['name'=>"jot[$cid][sort_order]", 'value' => '1'    , 'data-focus-id' => "SortOrder--{$cid}"];
                $hidden_fields = quebx_format_elements('hidden',$hidden);
                $nav_controls_options = ['form_id'=>$form_id, 'form_method'=>$form_method,'aspect'=>$aspect,'guid'=>$guid,'parent_cid'=>$preview_id,'cid'=>$cid,'action'=>$action,'presentation'=>$presentation,'presence'=>$presence, 'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'delete_action'=>'replace','maximize'=>true,'cancel'=>true,'action'=>true]];
                if($presentation == 'open_boqx')
                    $nav_controls_options['buttons']['maximize'] = true;
                $nav_controls = elgg_view('navigation/controls',$nav_controls_options);
                if($presentation == 'pallet') $collapser = elgg_format_element('a',['class'=>'collapser-item'              , 'data-cid'=>$cid, 'tabindex'=>'-1', 'style'=>'margin-top:10px;']);
                if($presence == 'empty boqx') $collapser = elgg_format_element('a',['class'=>'tn-Panel_collapser__oHRdb3eq', 'data-cid'=>$cid, 'tabindex'=>'-1', 'style'=>'margin-top:10px;']);
                if($action == 'edit')         $collapser = elgg_format_element('a',['class'=>'cartonPreviewItem__collapser', 'data-cid'=>$cid, 'tabindex'=>'-1', 'style'=>'margin-top:10px;']);
                $things_cid           = quebx_new_id('c');
                $media_cid            = quebx_new_id('c');
                $discoveries_cid      = quebx_new_id('c');
                $remedies_cid         = quebx_new_id('c');
                $description          = elgg_view('input/description',['cid'=>$cid,'value'=>$jot->description]);
                $marker_title         = elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2','NameEdit___2W_xAa_R'],'data-aid'=>'name','tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'style'=>'padding-top: 0;margin: 8px;','name'=>"jot[$cid][title]",'value'=>$jot->title, 'placeholder'=>'experience name']);
                $service_marker_title = elgg_format_element("textarea",['data-aid'=>'name','tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_$cid"], 'style'=>'padding-top: 0px;margin: 8px;','name'=>"jot[$cid][title]",'value'=>$jot->title,'placeholder'=>'experience name']);
                $labels_maker         = elgg_view('forms/transfers/edit',['perspective' => $perspective,'presentation'=> $presentation,'section'=>'labels_maker','cid'=> $cid]);
                $things               = elgg_view('forms/experiences/edit',['action'=>$action,'section'=>'things','aspect'=>'item','title'=>'Things','snippet'=>'','guid'=>$guid,'parent_cid'=>$cid, 'carton_id'=>$things_cid,'perspective' => $perspective,'presentation'=>'carton','presence'=>$presence]);
		        $media                = elgg_view('forms/market/edit',array_merge($vars,['perspective'=>'edit','section'=>'media','parent_cid'=> $preview_id,'cid'=>$cid,'wrapper_cid'=> $boqx_cid,'origin'=>'forms/experiences/edit>'.$action.'>'.$section]));
		        $info_boqx            = elgg_format_element('section',['class'=>['TaskEdit__description','Container___3NOvIiZo','info_box'],'presentation'=>$presentation, 'presence'=>$presence],
                                            elgg_format_element('section',['class'=>'model_details', 'style'=>'padding:0 10px;'],
                                                elgg_format_element('section',['class'=>'story_or_epic_header', 'style'=>'display:flex'],
                                                    $collapser.
                                                    elgg_format_element('fieldset',['class'=>'name'],
                                                        elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'display: flex;'],
                                                            elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp','style'=>'flex-basis: 300px;'],
                                                                $marker_title)))).
                                                elgg_format_element('aside',[],
                                                    elgg_format_element('div',['class'=>'wrapper'],
                                                        $nav_controls))).
                                            $description.
                                            $labels_maker.
                                            elgg_format_element('section',['class'=>'TaskInfo__SCPW4PCD'],
                                                $things).
                                            elgg_format_element('section',['class'=>'TaskInfo__SCPW4PCD'],
                                                $media));

                break;

            /****************************************
*add********** $section = 'things'                 *****************************************************************
             ****************************************/
            case 'things':
                unset($hidden, $hidden_fields);
                $hidden[]                     = ['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
                $hidden[]                     = ['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                if($guid)
                    $hidden[]                 = ['name'=>"jot[$cid][guid]"      , 'value' => $guid];
                $hidden[]                     = ['name'=>"jot[$cid][proximity]" , 'value' => 'boqx'];
                $hidden[]                     = ['name'=>"jot[$cid][aspect]"    , 'value' => $aspect    , 'data-focus-id' => "Aspect--{$cid}"];
                $hidden[]                     = ['name'=>"jot[$cid][contents]"  , 'value' => 'market'   , 'data-focus-id' => "Contents--{$cid}"];
                $hidden[]                     = ['name'=>"jot[$cid][fill_level]", 'value' => $fill_level, 'data-focus-id' => "FillLevel--{$cid}"];
                $hidden_fields                = quebx_format_elements('hidden', $hidden);
                $things_used_vars             = ['action'=>$action, 'section'=>'things_used', 'perspective'=>$perspective, 'presentation'=>$presentation, 'presence'=>$presence, 'data-qid'=>$div_id, 'parent_cid'=>$parent_cid, 'cid' => $cid, 'qid'=> $qid];
                $things_used_vars['snippet']  = 'things_used_add';
                $things_used_add              = elgg_view('forms/experiences/edit', $things_used_vars);
                $things_used_vars['snippet']  = 'dropboqx';
                $things_used_dropboqx         = elgg_view('forms/experiences/edit', $things_used_vars);
                $things_used_content          = elgg_format_element('div', ['class'=>'things_add_pallet'],$things_used_add.$things_used_dropboqx);

                $carton_id                    = quebx_new_id('c');
                $title                        = $presentation == 'open_boqx' ? '' :'things';
                unset($thing_vars);
                $thing_vars                   = $vars;
                unset($thing_vars['cid'],$thing_vars['entity'],$thing_vars['guid']);
                $item_things[]                = $things_used_dropboqx;
                $pieces                       = 0;

                switch($presentation){
                    case 'carton':
                        if($pieces==1) $label = "Item";
                        if($pieces>1)  $label = "Items";
                        if($pieces>0)  $visible="show";
                        $carton_contents      = elgg_view('page/elements/envelope',['task'=>'things', 'action'=>$action, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>implode('',$item_things),'presentation'=>'contents', 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title, 'jot'=>['label'=>$label,'qty'=>$pieces],'show_title'=>false, 'has_collapser'=>'yes','allow_delete'=>false]);
                        $things               = elgg_view_layout('carton',['cid'=>$parent_cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$carton_contents,'title'=>$title,'tally'=>$pieces] );
                        break;
                    default:
                        $things               = implode('',$item_things);
                        break;
                 }
                 break;
/*            case 'things_v1':
//use the 'edit' action for things
                unset($form_body, $things, $hidden);                                                                        $display .= '104 $container_guid: '.$container_guid.'<br>104 $qid: '.$qid.'<br>';
                $quebx_assets = (array) elgg_extract('assets', $vars);                                                      $display .= '105 $quebx_assets: '.print_r($quebx_assets, true).'<br>';
                if (elgg_instanceof($container, 'object', 'market') || elgg_instanceof($container, 'object', 'item')){
                    $quebx_assets[]     = $container_guid;
                }
                $form          = 'forms/experiences/edit';
                $remove_button = "<a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
                
                if($presentation == 'qbox_experience' || 
                   $presentation == 'popup'){
                     $div_id = elgg_extract('qid', $vars);
                     $style  = elgg_extract('style', $vars, $style);}
                else                       {$div_id = 'Things_panel';}
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $things .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $things_used_vars            = ['action'=>$action, 'perspective'=>$perspective, 'presentation'=>$presentation, 'presence'=>$presence, 'section'=>'things_used', 'data-qid'=>$div_id, 'cid' => $cid, 'qid'=> $qid];
                $things_used_vars['things'] = array_unique($quebx_assets);
                $things_used                 = elgg_view($form, $things_used_vars);
                unset($things_used_vars['things']);
                $things_used_vars['snippet'] = 'things_used_add';
                $things_used_add             = elgg_view($form, $things_used_vars);
                $things_used_vars['snippet'] = 'things_used_dropboqx';
                $things_used_dropboqx        = elgg_view($form, $things_used_vars);
                $things_used_content         = elgg_view('output/div', ['content'=> $things_used, 'class'=>'things_used_pallet']).
                                               elgg_view('output/div', ['content'=> $things_used_add.$things_used_dropboqx, 'class'=>'things_add_pallet']);
                $things .= elgg_view('output/div',['content'=>$things_used_content,
                                                   'class'  => 'elgg-head experience-panel',
                                                   'options'=> ['panel'      => 'Things',
                                                                'aspect'     => 'attachments',
                                                                'data-qid'   => $div_id,
                                                                'parent-cid' => $parent_cid,
                                                                'data-cid'   => $cid,
                                                       'style'      => $vars['visibility']]]);
                break;*/
                /****************************************
 *add********** $section = 'things_used'                 *****************************************************************
                ****************************************/
            case 'things_used':
				    unset($things, $things_used, $things_used_add, $things_used_show, $things_used_edit, $things_used_vars, $hidden);
                $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => "$n"       , 'data-focus-id' => "SortOrder--{$cid}"];
                
                $form         = 'forms/experiences/edit';
                $things = elgg_extract('things', $vars);
				Switch ($presence){
				    case 'inline':
				    case 'popup':
						$anchor = 'anchored';
					break;
					case 'qbox_experience':
						$anchor = 'free';
					break;
				};
                Switch ($snippet){
                    case 'things_used_add':
                        $cid         = quebx_new_cid ();//'c'.rand(1, 999);
                        $things_used = "<div tabindex='0'  
                                            class='AddSomethingButton___k1dvTuKc things_add_pallet_boqx' 
                                            data-aid='ThingAdd' 
                                            data-focus-id='ThingAdd' 
                                            data-element = 'market'
                                            data-cid='$cid' 
                                            data-qid='$qid'
                                            data-aspect = 'thing'
                                            data-action = '$action'
                                            data-section = '$section'
                                            data-perspective = '$perspective'
                                            data-presence    = '$presentation'
                                            data-position    = 'relative'
                                            data-anchored    = $anchor
                                            >
                                            <span class='AddSomethingButton__icon___h1-Z9ENT'></span>
                                            <span class='AddSomethingButton__message___2vsNCBXi'>Add something new</span>
                                        </div>";
                        break;
                    case 'things_used_view':
                        $entity         = elgg_extract('entity', $vars);
                        $container_guid = elgg_extract('container_guid', $vars);
                        $things_used    = elgg_view('input/assetpicker/item', ['entity'        => $entity,
                                                                               'container_guid'=> $container_guid,
                                                                               'input_name'    => 'jot[assets]',
                                                                               'snapshot'      => true,
                                                                               'container_type'=> 'experience',
                                                                               'aspect'        => $aspect,
                                                                               'section'       => 'things',
                                                                               'qid'           => $qid,
                                                                              ]);
                        break;
                    case 'things_used_dropboqx':
                                                      $dropboqx_class = 'things_add_pallet_boqx';
                        if($presentation == 'carton') $dropboqx_class = 'carton_boqx';
                        $things_used = elgg_format_element('div',['class'=>$dropboqx_class],
                                           elgg_format_element('div',['class'=>['dropboqx','things'],'data-qid'=>$qid,'data-element'=>'market','data-aspect'=>'experience','data-section'=>'things','data-perspective'=>'attach'],
                                              elgg_format_element('div',['class'=>'dropboqx-panel'],
                                                  elgg_format_element('div',['class'=>'dropboqx-pallet','data-boqx-guid'=>$guid],
                                                       elgg_format_element('span',['title'=>'Drag something from Things or from the shelf and drop it here'],
                                                           elgg_format_element('div',['class'=>'dropboqx-dropspot','label'=>'Add Things']))))));
                        break;
                    case 'dropboqx':
                        $dropboqx_class = 'things_add_pallet_boqx';
                        $things_used = elgg_format_element('div',['class'=>'window-opening'],
                                           elgg_format_element('div',['class'=>['dropboqx','things'],'data-cid'=>$cid, 'data-qid'=>$qid,'data-element'=>'market','data-aspect'=>'experience','data-section'=>'things','data-perspective'=>'attach'],
                                              elgg_format_element('div',['class'=>'dropboqx-panel'],
                                                  elgg_format_element('div',['class'=>'dropboqx-pallet','data-boqx-guid'=>$guid],
                                                       elgg_format_element('span',['title'=>'Drag something from Things or from the shelf and drop it here'],
                                                           elgg_format_element('div',['class'=>'dropboqx-dropspot','label'=>'Add Things']))))));
                        break;
                    default:
                        if (!is_array($things)){$things = (array)$things;}
                        $things_used_vars        = $vars;
                        $things_used_vars['cid'] = $cid;
                        $things_used_vars['qid'] = $qid;
                        foreach ($things as $key=>$thing){
                            if (elgg_entity_exists($thing)){$entity = get_entity($thing);}
                            elseif(is_object($thing))      {$entity = $thing;}
                            $container_guid = $entity->getGUID();
                            
                            $things_used_vars['snippet']        = 'things_used_view';
                            $things_used_vars['entity']         = $entity;
                            $things_used_vars['container_guid'] = $container_guid;
                            $things_used_view                  .= elgg_view($form, $things_used_vars);
                            
                        }
/*                        $things_used ="<div class='ServiceEffort__26XCaBQk issue_effort_service-marker1' data-qid='$qid' data-parent-cid='$parent_cid' data-cid='$cid' data-guid='$guid'>
                                            $things_used_view
                                       </div>";
*/                                            
                        $things_used ="<ul class='ThingsPallet__23erasdeR' data-qid='$qid' data-cid='$cid' data-guid='$guid'>
                                            $things_used_view
                                      </ul>";
                        break;
                }
                break;
                
                /****************************************
*add********** $section = 'thing_add'                 *****************************************************************
                 ****************************************/
            case 'thing_add':
                $form_version = 'market/profile';
                $form_class   = elgg_extract('form_class', $vars, 'inline-container');
                $form_vars = ['name'        => 'marketForm',
                              'enctype'     => 'multipart/form-data',
                              'action'      => 'action/market/edit',
                              'class'       => $form_class];
                $body_vars = ['qid'         => $cid,               // Note switching of qid and cid.  Allows for closing of the form.
                              'space'       => $space,
                              'aspect'      => $aspect,
                              'perspective' => $perspective,
                              'context'     => $context,
                              'presentation'=> 'inline'];
                $content = elgg_view_form($form_version, $form_vars, $body_vars);
                $vars['content']        = $content;
                $vars['show_save']      = false;                   //Upper Save icon lands outside of the form and won't work.
                $vars['show_full_view'] = false;
                $vars['position']       = 'relative';
                $form_body = "<div class = 'qbox-container inline-content-expand' id='$qid'>"
                                .elgg_view_layout('inline', $vars)
                            ."</div>";
                break;
                /****************************************
*add********** $section = 'thing'                 *****************************************************************
                 ****************************************/
            case 'thing':
                unset($hidden);
                $hidden[]              = ['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];//$boqx_id]; // 
                $hidden[]              = ['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                if($guid)
                    $hidden[]          = ['name'=>"jot[$cid][guid]"      , 'value' => $guid];
                $hidden[]              = ['name'=>"jot[$cid][proximity]" , 'value' => 'boqx'];
                $hidden[]              = ['name'=>"jot[$cid][aspect]"    , 'value' => $aspect , 'data-focus-id' => "Aspect--{$cid}"];
                $hidden[]              = ['name'=>"jot[$cid][contents]"  , 'value' => 'market', 'data-focus-id' => "Contents--{$cid}"];
                if($presentation != 'nested'){
                    $params['hidden']      = quebx_format_elements('hidden', $hidden);
                    $params['guid']        = $guid;
                    $params['boqx_cid']    = $parent_cid;
            	    $params['parent_cid']  = $cid ?: quebx_new_id('c');
            	    $params['fill_level']  = 'full';
                    $params['perspective'] = 'edit';
            	    $params['section']     = 'single_thing';
            	    $params['presentation']= $presentation;//'pallet';
            	    $params['presence']    = $presentation;
            	    $params['aspect']      = 'item';
            	    $params['display_state']='edit';
            	    //$params['display_class']='collapsed';
                    
                    $info_boqx = elgg_view("forms/market/edit",$params);
                }
                break;
            /****************************************
*add********** $section = 'documents'                 *****************************************************************
             ****************************************/
            case 'documents':
                unset($form_body, $hidden);
                if ($presentation == 'qbox'){
                	$hidden['jot[container_guid]'] = $container_guid;
	            	$hidden['jot[guid]']           = $guid;
	            	$hidden['jot[aspect]']         = 'documents';
	                $documents = elgg_get_entities_from_relationship(array(
						'type' => 'object',
						'relationship' => 'document',
						'relationship_guid' => $guid,
						'inverse_relationship' => true,
						'limit' => false,
						));
	                foreach ($documents as $document){                               //$display .= '489 ['.$key.'] => '.$image_guid.'<br>';
	                	$item_document_guids[]=$document->getGUID();
	                }
                }
            break;
            /****************************************
*add********** $section = 'gallery'                 *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
            if ($presentation == 'qbox'){
            	$hidden['jot[container_guid]'] = $container_guid;
            	$hidden['jot[guid]']           = $guid;
            	$hidden['jot[aspect]']         = 'pictures';
                $item_image_guids = $entity->images;  
                if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
                foreach ($item_image_guids as $key=>$image_guid){                               //$display .= '489 ['.$key.'] => '.$image_guid.'<br>';
                	if ($image_guid == ''){                     
                		unset($item_image_guids[$key]);
                		continue;
                	}
                }
            }
            break;
            /****************************************
*add********** $section = 'media'                 *****************************************************************
             ****************************************/
            case 'media':
                $action = elgg_entity_exists($guid) ? 'edit' : 'add';
                $drop_media = elgg_view('input/dropzone',  ['name' => "jot[$cid][media]",
                                                    		'default-message'=> '<strong>Drop files here</strong><br /><span>or click to select from your computer</span>',
                                                    		'max' => 25,
                                                    		'accept'=> ['image/*', 'video/*', 'audio/*', 'application/vnd.*', 'application/pdf', 'application/zip', 'application/x-7z-compressed', 'text/plain'],
                                                    		'multiple' => true,
                                                    		'style' => 'padding:0;',
                                                    		'container_guid' => $jot->participant_id ?: $owner_guid,
                                                           ]);
                $media_info_boqx = elgg_format_element('ol',['class'=>['comments','all_activity'],'data-aid'=>'comments']).
                						elgg_format_element('div',['class'=>['GLOBAL__activity','comment','CommentEdit___3nWNXIac','CommentEdit--new___3PcQfnGf'],'tabindex'=>'-1','data-aid'=>'comment-new'],
                							elgg_format_element('div',['class'=>'CommentEdit__commentBox___21QXi4py'],
                								elgg_format_element('div',['class'=>'CommentEdit__textContainer___2V0EKFmS'],
                									elgg_format_element('div',['data-aid'=>'CommentGutter','class'=>'CommentGutter___1wlvO_PP'],
                										elgg_format_element('div',['data-aid'=>'Avatar','class'=>'_2mOpl__Avatar'],'SJ')).
                									 elgg_format_element('div',['class'=>'CommentEdit__textEditor___3L0zZts-','data-aid'=>'CommentV2TextEditor'],
                										 elgg_format_element('div',['class'=>'MentionableTextArea___1zoYeUDA'],
                											 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
                												 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
                													 $drop_media)))))));
                $media_input = elgg_view('page/elements/envelope',['task'=>'media', 'action'=>$action, 'guid'=>$guid,'parent_cid'=>$cid,'info_boqx'=>$media_info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>'add', 'title'=>'Media','has_collapser'=>'yes']);
                $form_body = elgg_format_element('section',['class'=>['media','full']],
                            	elgg_format_element('div',['class'=>'Activity___2ZLT4Ekd'],
                            		elgg_format_element('h4',[],'Media').
                            		$media_input
                            		));
                break;
            /****************************************
*add********** $section = 'expand'                  *****************************************************************
             ****************************************/
            case 'expand':
                unset($form_body, $hidden);
                Switch ($presentation){
                	case 'qbox_experience':
                	case 'qbox':
                		
                	break;
                	default:
		                $nothing_panel     = NULL;
		                $instruction_panel = elgg_view('forms/experiences/edit', array(
		                                               'action'         => $action,
		                                               'section'        => 'instruction',
		                                               'guid'           => $guid,));
		                $observation_panel = elgg_view('forms/experiences/edit', array(
		                                               'action'         => $action,
		                                               'section'        => 'observation',
		                                               'guid'           => $guid,));
		                $event_panel = elgg_view('forms/experiences/edit', array(
		                                               'action'         => $action,
		                                               'section'        => 'event',
		                                               'guid'           => $guid,));
		                $project_panel = elgg_view('forms/experiences/edit', array(
		                                               'action'         => $action,
		                                               'section'        => 'project',
		                                               'guid'           => $guid,
                    		                           'presentation'   => $presentation,));
		                $issue_panel = elgg_view('forms/experiences/edit', [
		                                               'action'         => $action,
		                                               'section'        => 'issue',
		                                               'guid'           => $guid]);
	                break;
                }
                break;
            /****************************************
*add********** $section = 'instruction'             *****************************************************************
             ****************************************/
                case 'instruction':
                    unset($input_tabs, $form_body, $hidden);
                    $material_panel .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => $action,
                                                 'section'        => 'instruction_material',
                                                 'guid'           => $guid,));
                    $tools_panel    .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => $action,
                                                 'section'        => 'instruction_tools',
                                                 'guid'           => $guid,));
                    $steps_panel    .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => $action,
                                                 'section'        => 'instruction_steps',
                                                 'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'event'                 *****************************************************************
             ****************************************/
                case 'event':
                    unset($stage_selector, $stage_params, $form_body, $hidden, $input_tabs);
                    $stage_params = array("name"    => "jot[event][stage]",
                                          "align"   => "horizontal",
                    					  "value"   => $jot->state ?: 1,
                                          "guid"    => $guid,
                    					  "options2" => array(
                    					                   array("label" =>"Planning",
                    					                         "option" => 1,
                    					                         "title" => "Initial planning stage"),
                    					                   array("label" => "In Process",
                    					                         "option" => 2,
                    					                         "title" => "Event is active and under way"),
                    					                   array("label" => "Postponed",
                    					                         "option" => 3,
                    					                         "title" => "Event will be rescheduled"),
                    					                   array("label" => "Cancelled",
                    					                         "option" => 4,
                    					                         "title" => "Event is cancelled and will not continue"),
                    					                   array("label" => "Complete",
                    					                         "option" => 5,
                    					                         "title" => "Event is complete.")
                    					                   ),
                    				      "default" => 1,
                    					 );
                    $stage_selector = elgg_view('input/quebx_radio', $stage_params);
                    
                    $input_tabs[] = array('title'=>'Planning'  , 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'event_planning'  , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'In Process', 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_in_process', 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Postponed' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_postponed' , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Cancelled' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_cancelled' , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Complete'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_complete'  , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'event_input', 'tabs'=>$input_tabs));
                    
                    $planning_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_planning',
                                           'guid'           => $guid,));
                    $in_process_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_in_process',
                                           'guid'           => $guid,
                    ));
                    $cancelled_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_cancelled',
                                           'guid'           => $guid,));
                    $postponed_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_postponed',
                                           'guid'           => $guid,));
                    $complete_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_complete',
                                           'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'project'                 *****************************************************************
             ****************************************/
                case 'project':
                    unset($stage_selector, $stage_params, $form_body, $hidden);
                    $stage_params = array("name"    => "jot[project][stage]",
                                          'align'   => 'horizontal',
                    					  "value"   => 1,
                                          "guid"    => $guid,
                    					  "options2" => array(
                    					                   array("label" =>"Planning",
                    					                         "option" => 1,
                    					                         "title" => "Initial project setup"),
                    					                   array("label" => "In Process",
                    					                         "option" => 2,
                    					                         "title" => "Project is active and under way"),
                    					                   array("label" => "Blocked",
                    					                         "option" => 3,
                    					                         "title" => "Project is impeded"),
                    					                   array("label" => "Cancelled",
                    					                         "option" => 4,
                    					                         "title" => "Project is incomplete and will not continue"),
                    					                   array("label" => "Complete",
                    					                         "option" => 5,
                    					                         "title" => "Project is complete.")
                    					                   ),
                    				      'default' => 1,
                    					 );
                    $stage_selector = elgg_view('input/quebx_radio', $stage_params);
                    
                    $input_tabs[] = array('title'=>'Planning'  , 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'project_planning'  , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'In Process', 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_in_process', 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Blocked'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_blocked'   , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Cancelled' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_cancelled' , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Complete'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_complete'  , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'project_input', 'tabs'=>$input_tabs));
                    
                    $milestones_panel = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_milestones',
                                                  'guid'           => $guid,));
                    $in_process_panel = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_in_process',
                                                  'guid'           => $guid,
                    		                      'presentation'   => $presentation,));
                    $blocked_panel    = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_blocked',
                                                  'guid'           => $guid,));
                    $cancelled_panel  = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_cancelled',
                                                  'guid'           => $guid,));
                    $complete_panel   = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_complete',
                                                  'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'observation'                 *****************************************************************
             ****************************************/
                case 'observation':                                                                 $display .= '229 $action: '.$action.'<br>229 $aspect: '.$aspect.'<br>229 $section: '.$section.'<br>';
                    unset($state_params, $state_selector, $form_body, $hidden);
                    $input_tabs[] = array('title'=>'Discovery', 'selected' => $state == 1, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_discoveries', 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs[] = array('title'=>'Resolve'  , 'selected' => $state == 2, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_efforts'    , 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs[] = array('title'=>'Request'  , 'selected' => $state == 3, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_request'     , 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs[] = array('title'=>'Accept'   , 'selected' => $state == 4, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_accept'     , 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs[] = array('title'=>'Complete' , 'selected' => $state == 5, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_complete'   , 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'observation_input', 'tabs'=>$input_tabs));
	                    
                    $discoveries_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_discoveries',
                                                   'guid'           => $guid,));
                    $resolve_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_resolve',
                                                   'guid'           => $guid,
                    							   'action'         => $action,));
                    $assign_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_request',
                                                   'guid'           => $guid,));
                    $accept_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_accept',
                                                   'guid'           => $guid,));
                    $complete_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_complete',
                                                   'guid'           => $guid,));
                break;
            /****************************************
*add********** $section = 'observation_discoveries'                 *****************************************************************
             ****************************************/
                case 'observation_discoveries':
                    unset($form_body, $hidden, $last_class);
                break;
            /****************************************
*add********** $section = 'observation_resolve'                 *****************************************************************
             ****************************************/
                case 'observation_resolve':
                    unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'observation_request'                 *****************************************************************
             ****************************************/
                case 'observation_request':
                    unset($form_body, $hidden, $form_items);
                    $next_request_number = 'RITM#####';
                    $request_selector    = 'placeholder';
                    $form_items .= "
                            <div class='rTableRow'>
                        		<div class='rTableCell' style='width:30%;padding:0px'>Request Number   		</div>
                        		<div class='rTableCell' style='width:30%;padding:0px'>$next_request_number".
                        	      elgg_view('input/hidden', array(
                        			'name'        => 'jot[observation][request][number][]',
                        	        'value'       => $next_request_number,))."                               </div>
                        		<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    		</div>";
                    $form_items .= "
                    	   <div class='rTableRow'>
                        		<div class='rTableCell' style='width:30%;padding:0px'>Request Type           </div>
                        		<div class='rTableCell' style='width:30%;padding:0px'>$request_selector      </div>
                        		<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    	   </div>";
                break;
            /****************************************
*add********** $section = 'observation_accept'                    *****************************************************************
             ****************************************/
            case 'observation_accept':
                unset($form_body, $hidden, $form_items);
                $form_items .= "
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                    	    <div class='rTableRow'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                    		      elgg_view('input/longtext', array(
                    				'name'        => 'jot[observation][accept][description][]',
                    	      	    'placeholder' => 'Acceptance notes',
                    			))."
                    			</div>
                   			</div>
                		</div>
                    </div>";
                break;
            /****************************************
*add********** $section = 'observation_complete'                 *****************************************************************
             ****************************************/
            case 'observation_complete':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'instruction_material'     *****************************************************************
             ****************************************/
            case 'instruction_material':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                break;
            /****************************************
*add********** $section = 'instruction_tools'     *****************************************************************
             ****************************************/
            case 'instruction_tools':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                $tool    = elgg_view('input/text', array('name'=> 'jot[instruction][tool][title][]', 'class'=> 'last_tool_item','placeholder' => 'Tool Name',));
                break;
            /****************************************
*add********** $section = 'instruction_steps'        *****************************************************************
             ****************************************/
            case 'instruction_steps':
                unset($form_body, $hidden);
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                break;
            /****************************************
*add********** $section = 'project_milestones'       *****************************************************************
             ****************************************/
            case 'project_milestones':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'project_in_process'              *****************************************************************
             ****************************************/
            case 'project_in_process':
				if ($presentation == 'qbox'){
            		$form_body .= '$presentation: '.$presentation;
//            		break;
            	}
                unset($form_body, $hidden);
                $set_properties_button = elgg_view('output/url', array(
                           'id'    => 'properties_set',
                           'text'  => elgg_view_icon('settings-alt'), 
                           'title' => 'set properties',
                           'style' => 'cursor:pointer;',
                ));
            	$delete_button = "<label class=remove-progress-marker>". 
                            	    elgg_view('output/url', array(
                            	        'title'=>'remove progress marker',
                            	        'class'=>'remove-progress-marker',
                            	        'text' => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$delete = elgg_view("output/span", array("class"=>"remove-progress-marker", "content"=>$delete_button));
                $expander = elgg_view("output/url", array(
                            'text'    => '',
//                            'href'    => '#',
                            'class'   => 'expander undraggable',
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',
                          ));
                $collapser = elgg_view("output/url", array(
                            'text'    => '',
//                            'href'    => '#',
                            'class'   => 'collapser autosaves',
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',
                          ));
                break;
            /****************************************
*add********** $section = 'project_blocked'              *****************************************************************
             ****************************************/
            case 'project_blocked':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'project_cancelled'              *****************************************************************
             ****************************************/
            case 'project_cancelled':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'project_complete'              *****************************************************************
             ****************************************/
            case 'project_complete':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'event_planning'                 *****************************************************************
             ****************************************/
            case 'event_planning':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                $interval_add_button = "<a title='add interval' class='elgg-button-submit-element add-schedule-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $interval_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-schedule-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                $start_date  = elgg_view('input/date', array('name'=> 'jot[event][schedule][start_date][]', 'placeholder'=>'start date'));
                $end_date    = elgg_view('input/date', array('name'=> 'jot[event][schedule][end_date][]', 'placeholder'=>'end date'));
                $activity    = elgg_view('input/text', array('name'=> 'jot[event][schedule][title][]', 'class'=> 'last_schedule_item','placeholder' => 'Activity Name',));
                break;
            /****************************************
*add********** $section = 'event_in_process'              *****************************************************************
             ****************************************/
            case 'event_in_process':
                unset($form_body, $hidden); 
                break;
            /****************************************
*add********** $section = 'event_postponed'         *****************************************************************
             ****************************************/
            case 'event_postponed':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'event_cancelled'              *****************************************************************
             ****************************************/
            case 'event_cancelled':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'event_complete'              *****************************************************************
             ****************************************/
            case 'event_complete':
                unset($form_body, $hidden);
                break;
            /****************************************
*add********** $section = 'transfer'                 *****************************************************************
             ****************************************/
            case 'transfer':
                unset($form_body, $hidden);
                if ($presentation == 'qbox'){
                	$hidden['jot[container_guid]'] = $container_guid;
	            	$hidden['jot[guid]']           = $guid;
	            	$hidden['jot[aspect]']         = 'documents';
	                $documents = elgg_get_entities_from_relationship(array(
						'type' => 'object',
						'relationship' => 'document',
						'relationship_guid' => $guid,
						'inverse_relationship' => true,
						'limit' => false,
						));
	                foreach ($documents as $document){                               //$display .= '489 ['.$key.'] => '.$image_guid.'<br>';
	                	$item_document_guids[]=$document->getGUID();
	                }
                }
                	
            break;
            
            
            /****************************************
*add********** $section = 'issues'                   *****************************************************************
             ****************************************/
                case 'issues':
                $carton_id  = quebx_new_id('c');
                $issue_vars = array_merge($vars,['section'=>'issue','parent_cid'=>$cid,'carton_id'=>$carton_id,'title'=>'Issues']);
                unset($issue_vars['cid']);
                $item_issue = elgg_view('forms/experiences/edit',$issue_vars);
                $form_body = elgg_format_element('div',['id'=>$carton_id, 'class'=>'boqx-carton', 'data-boqx'=>$cid, 'data-aspect'=>'issues'],$item_issue);
                //$media_input = elgg_view('page/elements/envelope',['task'=>'media', 'action'=>$action, 'guid'=>$guid,'parent_cid'=>$cid,'info_boqx'=>$media_info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>'add', 'title'=>'Media','has_collapser'=>'yes']);
                
                break;
            /****************************************
*add********** $section = 'issue'                   *****************************************************************
             ****************************************/
                case 'issue':
                    unset($input_tabs, $form_body, $hidden, $hidden_fields);
                    $discoveries_cid = quebx_new_id('c');
                    $remedies_cid    = quebx_new_id('c');
                    $preview_id      = $presence == 'pallet' ? $parent_cid : quebx_new_id('c');  //The $preview_id provides a cid for the envelope when the $parent_cid refers to the associated boqx.
                    $proximity       = $presence == 'pallet' ? 'boqx'      : 'on'; 
                    if($container_guid){
                        $hidden[]    = ['name'=>"jot[cid]"                , 'value' => $parent_cid];
                        $hidden[]    = ['name'=>"jot[$parent_cid][cid]"   , 'value' => $parent_cid];
                        $hidden[]    = ['name'=>"jot[$parent_cid][guid]"  , 'value' => $container_guid];
                    }
                    else if($guid){
                        $hidden[]    = ['name'=>"jot[cid]"            , 'value' => $cid];
                        $hidden[]    = ['name'=>"jot[$cid][cid]"      , 'value' => $cid];
                    }                           
                    $hidden[]        = ['name'=>"jot[$cid][boqx]"     , 'value' => $parent_cid];
//                    $hidden[]        = ['name'=>"jot[$cid][boqx]"     , 'value' => $preview_id];
                    $hidden[]        = ['name'=>"jot[$cid][cid]"      , 'value' => $cid];
                    if($guid && $guid!=0){
                        $hidden[]    = ['name'=>"jot[$cid][guid]"     , 'value' => $guid];
                        $action      = 'edit';
                    }
//                    else $guid = 0;
                    $hidden[]       = ['name'=>"jot[$cid][proximity]" , 'value' => $proximity];
                    $hidden[]       = ['name'=>"jot[$cid][aspect]"    , 'value' => 'issue', 'data-focus-id' => "Aspect--{$cid}"];
                    $hidden[]       = ['name'=>"jot[$cid][contents]"  , 'value' => 'experience', 'data-focus-id' => "Contents--{$cid}"];
                    $hidden[]       = ['name'=>"jot[$cid][fill_level]", 'value' => $fill_level, 'data-focus-id' => "FillLevel--{$cid}"];
                    $hidden[]       = ['name'=>"jot[$cid][sort_order]", 'value' => '1'    , 'data-focus-id' => "SortOrder--{$cid}"];
//                    $nav_controls_options = ['form_id'=>$form_id, 'form_method'=>$form_method,'aspect'=>$aspect,'guid'=>$guid,'parent_cid'=>$parent_cid,'cid'=>$cid,'action'=>$action,'presentation'=>$presentation,'presence'=>$presence,'display_state'=>$display_state, 'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'delete_action'=>'replace','maximize'=>false,'cancel'=>false,'action'=>true,'cancel_class'=>'cancelReplace__TFF3oQiV'],'origin'=>'forms/experiences/edit>'.$action.'>'.$section];
                    $nav_controls_options = ['form_id'=>$form_id, 'form_method'=>$form_method,'aspect'=>$aspect,'guid'=>$guid,'parent_cid'=>$preview_id,'cid'=>$cid,'action'=>$action,'presentation'=>$presentation,'presence'=>$presence,'display_state'=>$display_state, 'buttons'=>['copy_link'=>true,'copy_id'=>true,'show_guid'=>true,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'delete_action'=>'replace','maximize'=>false,'cancel'=>false,'action'=>true,'cancel_class'=>'cancelReplace__TFF3oQiV'],'origin'=>'forms/experiences/edit>'.$action.'>'.$section];
                    if($presentation == 'open_boqx')
                        $nav_controls_options['buttons']['maximize'] = true;
                    $nav_controls = elgg_view('navigation/controls',$nav_controls_options);
                    if($presentation == 'pallet') $collapser = elgg_format_element('a',['class'=>'collapser-item', 'data-cid'=>"$cid", 'tabindex'=>'-1', 'style'=>'margin-top:10px;']);
                    if($presentation == 'carton') $collapser = elgg_format_element('a',['class'=>'CollapseEnvelope__z7DilsLc', 'data-cid'=>"$cid", 'tabindex'=>'-1', 'style'=>'margin-top:10px;']);
                    if($action == 'edit')         $collapser = elgg_format_element('a',['class'=>'cartonPreviewItem__collapser', 'data-cid'=>"$cid", 'tabindex'=>'-1', 'style'=>'margin-top:10px;']);
                    $description          = elgg_view('input/description',['cid'=>$cid,'value'=>$jot->description]);
                    $marker_title         = elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2','NameEdit___2W_xAa_R'],'data-aid'=>'name','tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'style'=>'padding-top: 0;margin: 8px;','name'=>"jot[$cid][title]",'value'=>$jot->title, 'placeholder'=>'Issue name','display_state'=>$display_state]);
                    $service_marker_title = elgg_format_element("textarea",['data-aid'=>'name','tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'class'=>['AutosizeTextarea__textarea___1LL2IPEy2',"NameEdit___Mak_$cid"], 'style'=>'padding-top: 0px;margin: 8px;','name'=>"jot[$cid][title]",'value'=>$jot->title,'placeholder'=>'Issue name','display_state'=>$display_state]);
                    $labels_maker         = elgg_view('forms/transfers/edit',['perspective' => $perspective,'presentation'=> $presentation,'section'=>'labels_maker','cid'=> $cid]);
                    $discoveries          = elgg_view('forms/experiences/edit',['action'=>$action,'section'=>'issue_discoveries','aspect'=>'discovery','title'=>'Discoveries','snippet'=>'discovery','guid'=>$guid,'parent_cid'=>$cid, 'carton_id'=>$discoveries_cid,'perspective' => $perspective,'presentation'=>$presentation,'presence'=>$presence,'display_state'=>$display_state]);
                    $remedies             = elgg_view('forms/experiences/edit',['action'=>$action,'section'=>'issue_remedies'   ,'aspect'=>'remedy'   ,'title'=>'Remedies'   ,'snippet'=>'remedy'   ,'guid'=>$guid,'parent_cid'=>$cid, 'carton_id'=>$remedies_cid   ,'perspective' => $perspective,'presentation'=>$presentation,'presence'=>$presence,'display_state'=>$display_state]);
                    $info_boqx            = elgg_format_element('section',['class'=>['TaskEdit__description','Container___3NOvIiZo','info_box'],'presentation'=>$presentation, 'presence'=>$presence],
                                                elgg_format_element('section',['class'=>'model_details', 'style'=>'padding:0 10px;'],
                                                    elgg_format_element('section',['class'=>'story_or_epic_header', 'style'=>'display:flex'],
                                                        $collapser.
                                                        elgg_format_element('fieldset',['class'=>'name'],
                                                            elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'display: flex;'],
                                                                elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp','style'=>'flex-basis: 300px;','disabled'=>$disabled],
                                                                    $marker_title)))).
                                                    elgg_format_element('aside',[],
                                                        elgg_format_element('div',['class'=>'wrapper'],
                                                            $nav_controls))).
                                                $description.
                                                $labels_maker.
                                                elgg_format_element('section',['class'=>'TaskHeader__X4GG1Xeg'],
                                                    $form_header).
                                                elgg_format_element('section',['class'=>'TaskInfo__SCPW4PCD'],
                                                    $discoveries).
                                                elgg_format_element('section',['class'=>'TaskInfo__SCPW4PCD'],
                                                    $remedies));

                    break;
                /****************************************
*add********** $section = 'issue_discoveries'              *****************************************************************
             * Pass-through - Assumes single discovery.  Used to provide compatibility with action = 'edit'.
             ****************************************/
            case 'issue_discoveries':
                    if(empty($carton_id))
                        $carton_id               = quebx_new_id('c');
                    $discoveries_vars = $vars;
                    $discoveries_vars['section'] = 'issue_discovery';
                    $issue_discoveries[]         = elgg_view('forms/experiences/edit',$discoveries_vars);
                    $form_body                   = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>'discoveries','pieces'=>$issue_discoveries,'title'=>'Discoveries'] );
                
                    break;
                /****************************************
*add********** $section = 'issue_discovery'              *****************************************************************
             * Do not send a $cid value.  Receives $cid as parent_cid, generates new $cid for this envelope
             ****************************************/
            case 'issue_discovery':
				if ($presentation == 'qbox'){
            		$form_body .= '$presentation: '.$presentation;
//            		break;
            	}
                unset($form_body, $disabled, $id_value, $hidden, $hidden_fields);
                $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
                if($guid && elgg_entity_exists($guid))
                    $hidden[] =['name'=>"jot[$cid][guid]"  , 'value' => $guid];
                $hidden[] =['name'=>"jot[$cid][proximity]" , 'value' => 'in'];
                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => 'discovery', 'data-focus-id' => "Aspect--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => '0'        , 'data-focus-id' => "FillLevel--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => '1'        , 'data-focus-id' => "SortOrder--{$cid}"];
                $title = elgg_extract('title', $vars, false);
                $set_properties_button = elgg_view('output/url', array(
                           'id'    => 'properties_set',
                           'text'  => elgg_view_icon('settings-alt'), 
                           'title' => 'set properties',
                           'style' => 'cursor:pointer;',
                ));
            	$delete_button = "<label class=remove-progress-marker>". 
                            	    elgg_view('output/url', array(
                            	        'title'=>'remove progress marker',
                            	        'class'=>'remove-progress-marker',
                            	        'text' => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$drop_media = elgg_view('input/dropzone', [
            		'name' => "jot[$cid][media]",
            		'default-message'=> '<strong>Drop images videos or documents here</strong><br /><span>or click to select from your computer</span>',
            		'max' => 25,
            		'accept'=> 'image/*, video/*, application/vnd.*, application/pdf, text/plain',
            		'multiple' => true,
            		'style' => 'padding:0;',
            		'container_guid' => $jot->participant_id ?: $owner_guid,
                    ]);
            	$delete = elgg_view("output/span", array("class"=>"remove-progress-marker", "content"=>$delete_button));
            	$url = elgg_get_site_url().'jot';
//            	$marker_title         = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' style='padding-top: 0;margin: 8px;' name='jot[observation][discovery][$cid][title]' placeholder='Give this discovery a name'></textarea>";
            	$marker_title         = elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2','NameEdit___2W_xAa_R'],'data-aid'=>'name','tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'style'=>'padding-top: 0;margin: 8px;','name'=>"jot[$cid][title]",'value'=>$jot->title,'placeholder'=>'Give this discovery a name','disabled'=>$disabled]);
            	$marker_date_picker   = elgg_format_element('date',['name'=>"jot[$cid][moment]",'value'=>$jot->moment, 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[$cid][type]",
            	                                         'value'   => $jot->type,
            			                                 'options_values' =>['quip'=>'Quip', 'milestone'=>'Milestone', 'check_in'=>'Check in']
            	                                         ]);
            	$owner                   = get_entity($jot->participant_id ?: $owner_guid);
            	$owner_names             = explode(' ',$owner->name);
            	$owner_initials          = strToUpper(str_split($owner_names[0])[0]).strToUpper(str_split($owner_names[1])[0]);
            	
            	$marker_participant_link = elgg_view('output/div',['class'=>'dropdown', 
            			                                           'content' =>elgg_view('output/url',['class'=>'selection',
						            		                       'href' => "profile/{$owner->username}",
						            		                       'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            		                                                                     elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>$owner_initials])])])]);
            	$nav_controls = elgg_view('navigation/controls',['form_method'=>'stuff','aspect'=>$aspect, 'parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$perspective,'presentation'=>$presentation,'presence'=>$presence,'display_state'=>$display_state, 'buttons'=>['copy_link'=>false,'copy_id'=>false,'show_guid'=>false,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'delete_action'=>'replace','maximize'=>false,'cancel'=>false,'cancel_class'=>'cancelReplace__mw0ODp0p','action'=>true]]);
/*            	$cancel_effort = elgg_format_element('button',['type'=>'reset' ,'id'=>"pallet_submit_cancel_{$cid}",'class'=>['autosaves',$cancel_class,'clear'],'data-cid'=>$cid,'tabindex'=>'-1', 'data-boqx'=>$parent_cid, 'data-presence'=>$presentation, 'title'=>'Cancel changes'],'Cancel');
                $add_effort    = elgg_format_element('button',['type'=>'submit','class'=>['submitBundle_q0kFhFBf','autosaves','button','std','egg'],'data-aid'=>'addEffortButton','tabindex'=>'-1','data-parent-cid'=>$parent_cid,'data-cid'=>$cid],'Add');
                $save_effort   = elgg_format_element('button',['type'=>'submit','class'=>['submitBundle_q0kFhFBf','autosaves','button','std','egg'],'data-aid'=>'editEffortButton','tabindex'=>'-1','data-parent-cid'=>$parent_cid,'data-cid'=>$cid,'data-guid'=>$guid],'Save');
                $action_button = $state == 'add' ? $add_effort : $save_effort;
                $do            = elgg_format_element('div',['class'=>['persistence','use_click_to_copy'],'style'=>'order:1'],$cancel_effort.$action_button);
*/                
/*                $media_info_boqx = 
					elgg_format_element('ol',['class'=>['comments','all_activity'],'data-aid'=>'comments']).
					elgg_format_element('div',['class'=>['GLOBAL__activity','comment','CommentEdit___3nWNXIac','CommentEdit--new___3PcQfnGf'],'tabindex'=>'-1','data-aid'=>'comment-new'],
						elgg_format_element('div',['class'=>'CommentEdit__commentBox___21QXi4py'],
							elgg_format_element('div',['class'=>'CommentEdit__textContainer___2V0EKFmS'],
								elgg_format_element('div',['data-aid'=>'CommentGutter','class'=>'CommentGutter___1wlvO_PP'],
									elgg_format_element('div',['data-aid'=>'Avatar','class'=>'_2mOpl__Avatar'],'SJ')).
								 elgg_format_element('div',['class'=>'CommentEdit__textEditor___3L0zZts-','data-aid'=>'CommentV2TextEditor'],
									 elgg_format_element('div',['class'=>'MentionableTextArea___1zoYeUDA'],
										 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
											 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
												 $drop_media)))))));
                    
                $media_input = elgg_view('page/elements/envelope',['task'=>'media', 'action'=>$action, 'guid'=>$guid,'parent_cid'=>$cid,'info_boqx'=>$media_info_boqx,'presentation'=>$presentation, 'presence'=>$presence,'display_state'=>$display_state, 'visible'=>'add', 'title'=>'Media','has_collapser'=>'yes']);
*/              $media_vars            = array_merge($vars,['perspective'=>'edit','section'=>'media','parent_cid'=> $cid,'wrapper_cid'=> $boqx_cid,'origin'=>'forms/experiences/edit>'.$action.'>'.$section]);
            	unset($media_vars['cid']);
            	$media_input           = elgg_view('forms/market/edit',$media_vars); 
					
                switch($snippet){
                    case 'discovery':
                        if (!empty($hidden))                
                           foreach($hidden as $field=>$value){
                               $value['type']='hidden';
                               $hidden_fields .= elgg_format_element('input', $value);}
                        // provides the envelope for the discovery
                        $discovery_edit = elgg_view('forms/experiences/edit',['action'=>$action,'section'=>'issue_discovery','snippet'=>'marker','perspective' => $perspective,'presentation'=>$presentation, 'presence'=>$presence,'display_state'=>$display_state, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'guid'=>$guid]);
                        $discovery_vars = ['task'=>'discovery','title'=>$title,'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid,'cid'=>$cid, 'carton_id'=>$carton_id,'info_boqx'=>$discovery_edit,'visible'=>$visible,'has_collapser'=>'no', 'hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'presence'=>$presence,'display_state'=>$display_state];
                        if(elgg_entity_exists($guid))
                            $discovery_vars['show_title'] = $entity->title;
                        $discovery      = elgg_view('page/elements/envelope',$discovery_vars);
                        break;
            		case 'marker':
            			//$hidden["jot[$cid][aspect]"] = 'discovery';
                        $date_picker   = elgg_format_element('input',['type'=>'date','name'=>"jot[$cid][moment]", 'value'=>$jot->moment ?: $now->format('Y-m-d')]);
            		    $participants_list_id = quebx_new_id('c');
                        $users                = elgg_get_entities(['type'=>'user', 'limit'=>0]);
                        foreach ($users as $item) {
                        	$item_icon        = elgg_view_entity_icon($item, "tiny");
                        	$item_url         = elgg_view('output/url',['text'=>$item->getDisplayName(), 'href'=>$item->getURL(), 'class'=>'recipient']);
                        	$list_items[$item_icon.$item_url] = $item->getGUID();
                        }
                        foreach($users as $user){
                        	$user_cid         = quebx_new_id('c');
                        	$user_guid        = $user->getGUID();
                        	$user_names       = explode(' ',$user->name);
                        	$user_initials    = strToUpper(str_split($user_names[0])[0]).strToUpper(str_split($user_names[1])[0]);
                        	$icon_src         = $user->getIconURL('tiny');
                        	if ($user->hasIcon('tiny'))
                        		$avatar       = elgg_format_element('span',['id'=>"icon_$user_cid",'class'=>"dropdown_icon", 'style'=>"background-image:url('$icon_src')", 'data-icon'=>$icon_src]);
                        	else
                        		$avatar       = elgg_format_element('span',['id'=>"icon_$user_cid",'class'=>"dropdown_initials"],$user_initials);
                        	$li[]             = 
                        		elgg_format_element('li',['id'=>$user_cid, 'class'=>["pickItem__m15wyqaE"], 'data-boqx'=>$participants_list_id, 'data-value'=>$user_guid,'data-index'=>"1"],
                        			elgg_format_element('a',['id'=>"select_$user_cid",'class'=>"item_$user_cid",'data-guid'=>$user_guid,'data-href'=>"profile/$user->username", 'href'=>"#"],
                        				$avatar.
                        				elgg_format_element('span',['id'=>"label_$user_cid",'class'=>"dropdown_label"],$user->name)));
                        }
                        $participant         = 
                        	elgg_format_element('div',['class'=>'dropdown', 'data-cid'=>$cid],
                        		 elgg_format_element('input',['type'=>"hidden",'name'=>"jot[$cid][participant_id]",'value'=>$owner->getGUID(),'data-type'=>"number"]).
                        		 elgg_format_element('a',['id'=>'person_'.$owner->getGUID(), 'class'=>["selection","item_$cid"],'tabindex'=>"-1"],
                        			 elgg_format_element('span',['tabindex'=>"-1"],
                        				 elgg_format_element('div',['class'=>['pickAvatar','pickAvatar__hasHoverCard'],'data-person-id'=>$owner->getGUID()],
                        					 elgg_format_element('span',['class'=>"pickAvatar__initials"],$owner_initials))).
                        			 elgg_format_element('span',[],
                        				 elgg_format_element('div',['class'=>['name','pickAvatarName']],$owner->name))).
                        		 elgg_format_element('a',['class'=>['arrow','target']]).
                        		 elgg_format_element('section',['class'=>['pick-boqx','closed']],
                        			 elgg_format_element('div',['class'=>["dropdown_menu","search"]],
                        				 elgg_format_element('div',['class'=>"search_item"],
                        					  elgg_format_element('input',['type'=>"text",'id'=>"filter_$cid",'class'=>"filter",'onkeyup'=>"filterList('$cid','$participants_list_id')"])).
                        				 elgg_format_element('ul',['id'=>$participants_list_id,'class'=>['pickList__NRi0PbnO',"selections_$cid"],'data-boqx'=>$cid], implode('',$li)))));
                        $info_box = 
                        	elgg_format_element('div',['class'=>['activity','info_box'],'style'=>'display:block'],
                        		elgg_format_element('div',['class'=>'info'],
                        			elgg_format_element('div',['class'=>'date row'],
                        			  "<em>Date</em>".
                        			  elgg_format_element('div',['class'=>['dropdown','story_date']],$date_picker)).
                        			elgg_format_element('div',['class'=>'participant row'],
                        			  "<em>Participant</em>".
                        				  $participant)));
            			$issue_discovery_marker = 
                            	elgg_format_element('div',['class'=>['edit','details'],'data-cid'=>$cid,'data-qid'=>$qid],
                            	   elgg_format_element('section',['class'=>'edit','data-aid'=>'StoryDetailsEdit','tabindex'=>'-1'],
                            		  elgg_format_element('section',['class'=>'model_details'],
                            			  elgg_format_element('section',['class'=>'story_or_epic_header'],
                            				elgg_format_element('a',['class'=>['autosaves','collapser','CollapseEnvelope__z7DilsLc'],'id'=>"story_collapser_$cid",'data-cid'=>$cid,'data-qid'=>$qid,'tabindex'=>'-1']).
                            				elgg_format_element('fieldset',['class'=>'name'],
                            					elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'display: flex;'],
                            						elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp','style'=>'flex-basis: 300px;'],
                            						   $marker_title)))).
                            			  elgg_format_element('aside',[],
                            				elgg_format_element('div',['class'=>'wrapper'],
                            				  $nav_controls.
                            				  $info_box.
                            			  elgg_format_element('div',['class'=>'mini attachments'])))).
                            			  elgg_format_element('section',['class'=>['description','full']],
    										elgg_format_element('div',['class'=>'Description___3oUx83yQ','data-aid'=>'Description'],
    											elgg_format_element('h4',[],'When I did ...').
    											elgg_format_element('div',['class'=>['DescriptionShow___3-QsNMNj','tracker_markup','DescriptionShow__placeholder___1NuiicbF'],'data-focus-id'=>"DescriptionShow--$cid",'data-aid'=>'renderedDescription','tabindex'=>'0'],$jot->action?:'Action').
    											elgg_format_element('div',['class'=>'DescriptionEdit___1FO6wKeX','style'=>'display:none'],
    												elgg_format_element('div',['class'=>'textContainer___2EcYJKlD','data-aid'=>'editor'],
    													elgg_format_element('div',[],
    														elgg_format_element('div',['class'=>'DescriptionEdit__write___207LwO1n'],
    															elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
    																elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
    																	elgg_format_element('textarea',['class'=>['AutosizeTextarea__textarea___1LL2IPEy','editor___1qKjhI5c','tracker_markup'], 'name'=>"jot[$cid][action]",'aria-labelledby'=>"description{$cid}",'data-aid'=>'textarea','data-focus-id'=>"DescriptionEdit--$cid",'placeholder'=>'Action','disabled'=>$disabled], $jot->action))))))))).
    									
    									elgg_format_element('section',['class'=>['description','full']],
    										elgg_format_element('div',['class'=>'Description___3oUx83yQ','data-aid'=>'Description'],
    											elgg_format_element('h4',[],'This happened ...').
    											elgg_format_element('div',['class'=>['DescriptionShow___3-QsNMNj','tracker_markup','ObservationShow__placeholder___1NuiicbF'],'data-focus-id'=>"ObservationShow--$cid",'data-aid'=>'renderedDescription','tabindex'=>'0'],$jot->observation?:'Observations').
    											elgg_format_element('div',['class'=>'DescriptionEdit___1FO6wKeX','style'=>'display:none'],
    												elgg_format_element('div',['class'=>'textContainer___2EcYJKlD','data-aid'=>'editor'],
    													elgg_format_element('div',[],
    														elgg_format_element('div',['class'=>'DescriptionEdit__write___207LwO1n'],
    															elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
    																elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
    																	elgg_format_element('textarea',['class'=>['AutosizeTextarea__textarea___1LL2IPEy','editor___1qKjhI5c','tracker_markup'], 'name'=>"jot[$cid][observation]",'aria-labelledby'=>"observation{$cid}",'data-aid'=>'textarea','data-focus-id'=>"ObservationEdit--$cid",'placeholder'=>'Observations','disabled'=>$disabled],$jot->observation))))))))).
    									elgg_format_element('section',['class'=>'description full'],
    										elgg_format_element('div',['class'=>'Description___3oUx83yQ','data-aid'=>'Description'],
    											elgg_format_element('h4',[],'And I learned ...').
    											elgg_format_element('div',['class'=>['DescriptionShow___3-QsNMNj','tracker_markup','DiscoveryShow__placeholder___1NuiicbF'],'data-focus-id'=>"DiscoveryShow--$cid",'data-aid'=>'renderedDescription','tabindex'=>'0'],$jot->discovery?:'Conclusions').
    											elgg_format_element('div',['class'=>'DescriptionEdit___1FO6wKeX','style'=>'display:none'],
    												elgg_format_element('div',['class'=>'textContainer___2EcYJKlD','data-aid'=>'editor'],
    													elgg_format_element('div',[],
    														elgg_format_element('div',['class'=>'DescriptionEdit__write___207LwO1n'],
    															elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
    																elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
    																	elgg_format_element('textarea',['class'=>['AutosizeTextarea__textarea___1LL2IPEy','editor___1qKjhI5c','tracker_markup'], 'name'=>"jot[$cid][discovery]",'aria-labelledby'=>"discovery{$cid}",'data-aid'=>'textarea','data-focus-id'=>"DiscoveryEdit--$cid",'placeholder'=>'Conclusions','disabled'=>$disabled], $jot->discovery))))))))).
    									elgg_format_element('section',['class'=>['media','full']], $media_input).
/*    										elgg_format_element('div',['class'=>'Activity___2ZLT4Ekd'],
    										    elgg_format_element('h4',[],'Media').
    											$media_input
    											)).
*/                            		elgg_format_element('section',['class'=>'activity full'],
                            			elgg_format_element('div',['class'=>'Activity___2ZLT4Ekd'],
                            				elgg_view('input/description',['cid'=>$cid,'input_type'=>'comment','heading'=>'Activity','metadata_name'=>'comment'])))));
            			break;
            		default:
                        if (!empty($hidden))                
                           foreach($hidden as $field=>$value){
                               $value['type']='hidden';
                               $hidden_fields .= elgg_format_element('input', $value);}
		                $add_story_button = elgg_view('output/url', array(
		                                        'title'    => 'Add discovery',
		                		                'data-element' => 'new_discovery',
		                		                'data-qid'     => $qid,
		                    				    //'text'     => '+',
		                    					//'href'     => '#',
		                    					//'class'    => 'add-progress-marker addButton___3-z3g3BH',
		                    					'class'    => 'trigger-element add_marker',
		                                        'tabindex' => '-1' 
		                    					));
		            	$story_span     = elgg_view('output/span',   array('content'=>'dig?','class'=>'story_name'));
		            	$panel_header   = elgg_view('output/header', ['class'=>'tn-PanelHeader___c0XQCVI7 tn-PanelHeader--single___2ns28dRL pin',
	            	                                                  'options' => ['style'   =>'background-color: #dddddd; color: #000000',
	            	                                                                'data-bb'  =>'PanelHeader',
	            	                                                                'data-aid' =>'PanelHeader'],
	            	                                                  'content' => elgg_view('output/div',['class'=>'tn-PanelHeader__inner___3Nt0t86w tn-PanelHeader__inner--single___3Nq8VXGB',
	            	                                                  		                               'options'=>['data-aid'=>'highlight',
	            	                                                  		                                           'style'=>'border-top-color: #c0c0c0'],
	            	                                                   		                               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__actionsArea___EHMT4f1g undraggable',
	            	                                                   		                               									   'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__action___3zvuQp6Z',
	            	                                                   		                                		                            		                           'content'=>$add_story_button])])
	            	                                                   		                                         . elgg_view('output/div',['class'=>'tn-PanelHeader__titleArea___1DRH-oDF',
	            	                                                   		                                            		               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__name___2UfJ8ho9',
	            	                                                   		                                            		                  		                           'options'=>['data-aid'=>'PanelHeader__name'],
	            	                                                   		                                            		                  		                           'content'=>'Discoveries'])])
	            	                                                   		                                         . elgg_view('output/div',['class'=>'tn-PanelHeader__actionsArea___EHMT4f1g undraggable',
	            	                                                   		                                            		               'content'=> "<a class='' data-aid='DropdownButton'>
																	                                                                            				<div title='Panel actions' class='tn-DropdownButton___nNklb3UY'></div>
																	                                                                            			</a>"])])]);
		            	$preview        = elgg_view('output/span',   array('content'=>$story_span,'class'=>'name tracker_markup'));
		            	$view_summary   = elgg_view('output/header', array('content'=>$expander.$preview.$delete, 'class'=>'preview collapsed'));
		/*                $preview = "
		        			        <div class='rTableRow progress_marker pin' style='cursor:move'>
		                                <div class='rTableCell' style='width:0%'>$expander</div>
		                                <div class='rTableCell' style='width:100%'>dig?</div>
		            	                $hidden_fields
		            	            </div>";*/
		            	$quick_input = elgg_view('input/text', array('name'=>'jot[observation][discovery][$cid][title]', 'data-aid'=>'name', 'data-focus-id'=>'NameEdit--c1037'));
		            	$edit_details = elgg_view('output/section', array('content'=>$expander, 'class'=>'edit details', 'options'=>['style'=>'display:none']));
		            	$view_id      = 'view274';
		            	$data_id      = '147996415';
		            	$edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
		            	                                        'options'=>['data-parent-cid'=>$parent_cid,'data-cid'=>$cid,'data-id'=>$data_id],
	                    			    		                'content'=>$view_summary
	                    			    		                         . elgg_view('forms/experiences/edit',['section'=>'project_in_process', 'snippet'=>'marker','parent_cid'=>$parent_cid, 'cid'=>$cid, 'data-qid'=>$qid])]);
		            	$story_model .= elgg_view('output/div', ['class'  =>'rTableRow story',
		                                                        'content'=>elgg_view('output/div', ['class'  =>'rTableCell',
		                                                                                            'options'=>['style'=>'width:100%; padding: 0px 0px;vertical-align:top'],
		                                                                                            'content'=>elgg_view('output/div',  ['class'  =>'story model item pin',
		                                                                                                                                 'content'=>/*$view_summary.*/$edit_details])])]);
		            	$new_line_items = elgg_view('output/div', ['class'=>'new_progress_marker']);
		            	$items_container = elgg_view('output/div', ['class'=>'items panel_content',
		                                                            'options'=>['id'=>$view_id],
		                                                            'content'=>elgg_view('output/div', ['class'   =>'tn-panel__loom',
		                                                                                                'options' =>['data-reactroot'=>''],
		                                                                                                'content' =>$new_line_items])]);
                }
                break;
                /****************************************
*add********** $section = 'issue_remedies'              *****************************************************************
             * Pass-through - Assumes single remedy.  Used to provide compatibility with action = 'edit'.
             ****************************************/
            case 'issue_remedies':
                    if(empty($carton_id))
                        $carton_id            = quebx_new_id('c');
                    $remedies_vars            = $vars;
                    $remedies_vars['section'] = 'issue_remedy';
                    $issue_remedies[]         = elgg_view('forms/experiences/edit',$remedies_vars);
                    $form_body                = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>'remedies','pieces'=>$issue_remedies,'title'=>'Remedies'] );
                    break;
                
            /****************************************
*add********** $section = 'issue_remedy'              *****************************************************************
             * Do not send a $cid value.  Receives $cid as parent_cid, generates new $cid for this envelope
             ****************************************/
            case 'issue_remedy':
				if ($presentation == 'qbox'){
            		$form_body .= '$presentation: '.$presentation;
//            		break;
            	}
                unset($form_body, $disabled, $id_value, $hidden, $hidden_fields);
    		    $hidden[] =['name'=>"jot[$cid][boqx]"      , 'value' => $parent_cid];
                $hidden[] =['name'=>"jot[$cid][cid]"       , 'value' => $cid];
                if($guid && elgg_entity_exists($guid))
                    $hidden[] =['name'=>"jot[$cid][guid]"  , 'value' => $guid];
                $hidden[] =['name'=>"jot[$cid][proximity]" , 'value' => 'in'];
                $hidden[] =['name'=>"jot[$cid][aspect]"    , 'value' => 'remedy'   , 'data-focus-id' => "Aspect--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][fill_level]", 'value' => '0'        , 'data-focus-id' => "FillLevel--{$cid}"];
                $hidden[] =['name'=>"jot[$cid][sort_order]", 'value' => '1'        , 'data-focus-id' => "SortOrder--{$cid}"];
                $title = elgg_extract('title', $vars, false);
                $disabled = 'disabled';
                $parts_carton_id = quebx_new_id('c');
                $parts_envelope_id = quebx_new_id('c');
                $labor_carton_id = quebx_new_id('c');
                $labor_envelope_id = quebx_new_id('c');

                switch($snippet){
            /****************************************
*add********** $section = 'issue_remedy' $snippet = 'remedy'   *****************************************************************
             ****************************************/
                    case 'remedy':
                         if (!empty($hidden))                
                            foreach($hidden as $field=>$value){
                                $value['type']='hidden';
                                $hidden_fields .= elgg_format_element('input', $value);}
                        // provides the envelope for the remedy
                        $remedy_edit = elgg_view('forms/experiences/edit',['action'=>$action,'section'=>'issue_remedy','snippet'=>'marker','perspective' => $perspective,'presentation'=>$presentation, 'presence'=>$presence, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id,'cid'=>$cid, 'qid'=>$qid]);   
                        $remedy      = elgg_view('page/elements/envelope',['task'=>'remedy','title'=>$title,'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid,'carton_id'=>$carton_id, 'cid'=>$cid,'info_boqx'=>$remedy_edit,'visible'=>$visible,'has_collapser'=>'no', 'hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'presence'=>$presence]);
                        break;
            /****************************************
*add********** $section = 'issue_remedy' $snippet = 'marker'   *****************************************************************
             ****************************************/
            		case 'marker':
                        if($guid && $guid!=0)
                            $action = 'edit';
                        $date_picker   = elgg_format_element('input',['type'=>'date','name'=>"jot[$cid][moment]", 'value'=>$jot->moment ?: $now->format('Y-m-d')]);
            		    $set_properties_button = elgg_view('output/url', array(
                                   'id'    => 'properties_set',
                                   'text'  => elgg_view_icon('settings-alt'), 
                                   'title' => 'set properties',
                                   'style' => 'cursor:pointer;',
                        ));
                    	$delete_button = "<label class=remove-progress-marker>". 
                                    	    elgg_view('output/url', array(
                                    	        'title'=>'remove progress marker',
                                    	        'class'=>'remove-progress-marker',
                                    	        'text' => elgg_view_icon('delete-alt'),
                                    	    	'data-qid' => $qid,
                                    	    )).
                                    	 "</label>";
                    	$drop_media = elgg_view('input/dropzone', [
                    		'name' => "jot[$cid][files]",
                    		'default-message'=> '<strong>Drop images videos or documents here</strong><br /><span>or click to select from your computer</span>',
                    		'max' => 25,
                    		'accept'=> 'image/*, video/*, application/vnd.*, application/pdf, text/plain',
                    		'multiple' => true,
                    		'style' => 'padding:0;',
                    		'container_guid' => $owner_guid,
                            ]);
                    	$delete = elgg_view("output/span", array("class"=>"remove-progress-marker", "content"=>$delete_button));
                    	$url = elgg_get_site_url().'jot';
                    	$marker_title         = elgg_format_element('input',['type'=>'text','class'=>['AutosizeTextarea__textarea___1LL2IPEy2','NameEdit___2W_xAa_R'],'data-aid'=>'name','tabindex'=>'0','data-focus-id'=>"NameEdit--$cid",'style'=>'padding-top: 0;margin: 8px;','name'=>"jot[$cid][title]",'value'=>$jot->title,'placeholder'=>'Give this effort a name']);
                    	$marker_type_selector = elgg_view_field(['#type'    =>'select',
                    	                                         'name'    =>"jot[$cid][type]",
            	                                                 'value'   => $jot->type,
                    			                                 'options_values' =>['quip'=>'Quip', 'milestone'=>'Milestone', 'check_in'=>'Check in']
                    	                                         ]);
                    	$nav_controls = elgg_view('navigation/controls',['form_method'=>'stuff','aspect'=>$aspect, 'parent_cid'=>$parent_cid,'cid'=>$cid,'guid'=>$guid,'action'=>$perspective,'presentation'=>$presentation,'presence'=>$presence, 'buttons'=>['copy_link'=>false,'copy_id'=>false,'show_guid'=>false,'import'=>false,'clone'=>false,'history'=>false,'delete'=>true,'delete_action'=>'replace','maximize'=>false,'cancel'=>false,'cancel_class'=>'cancelReplace__mw0ODp0p','action'=>true]]);
                    	$cancel_effort = elgg_format_element('button',['type'=>'reset' ,'id'=>"pallet_submit_cancel_{$cid}",'class'=>['autosaves',$cancel_class,'clear'],'data-cid'=>$cid,'tabindex'=>'-1', 'data-boqx'=>$parent_cid, 'data-presence'=>$presentation, 'title'=>'Cancel changes'],'Cancel');
                        $add_effort    = elgg_format_element('button',['type'=>'submit','class'=>['StuffEnvelope_6MIxIKaV','autosaves','button','std','egg'],'data-aid'=>'addEffortButton','tabindex'=>'-1','data-parent-cid'=>$parent_cid,'data-cid'=>$cid],'Add');
                        $save_effort   = elgg_format_element('button',['type'=>'submit','class'=>['StuffEnvelope_6MIxIKaV','autosaves','button','std','egg'],'data-aid'=>'editEffortButton','tabindex'=>'-1','data-parent-cid'=>$parent_cid,'data-cid'=>$cid,'data-guid'=>$guid],'Save');
                        $action_button = $action == 'add' ? $add_effort : $save_effort;
                        $do            = elgg_format_element('div',['class'=>['persistence','use_click_to_copy'],'style'=>'order:1'],$cancel_effort.$action_button);
//@TODO Show existing media                        
                        $media_info_boqx = 
        					elgg_format_element('ol',['class'=>['comments','all_activity'],'data-aid'=>'comments']).
        					elgg_format_element('div',['class'=>['GLOBAL__activity','comment','CommentEdit___3nWNXIac','CommentEdit--new___3PcQfnGf'],'tabindex'=>'-1','data-aid'=>'comment-new'],
        						elgg_format_element('div',['class'=>'CommentEdit__commentBox___21QXi4py'],
        							elgg_format_element('div',['class'=>'CommentEdit__textContainer___2V0EKFmS'],
        								elgg_format_element('div',['data-aid'=>'CommentGutter','class'=>'CommentGutter___1wlvO_PP'],
        									elgg_format_element('div',['data-aid'=>'Avatar','class'=>'_2mOpl__Avatar'],'SJ')).
        								 elgg_format_element('div',['class'=>'CommentEdit__textEditor___3L0zZts-','data-aid'=>'CommentV2TextEditor'],
        									 elgg_format_element('div',['class'=>'MentionableTextArea___1zoYeUDA'],
        										 elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
        											 elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
        												 $drop_media)))))));
        				$media_input = elgg_view('page/elements/envelope',['task'=>'media', 'action'=>$action, 'guid'=>$guid,'parent_cid'=>$cid,'info_boqx'=>$media_info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>'add', 'title'=>'Media','has_collapser'=>'yes']);
        				$contents_perspective = elgg_entity_exists($guid) ? 'edit' : 'add';
        				$actions = $jot->actions;
        				if(!is_array($actions)) $actions = (array) $actions;
        				foreach($actions as $remedy_action)
        				    $remedy_actions .= elgg_view('input/description',['cid'=>$cid,'input_type'=>'actions','metadata_name'=>'description','html_tag'=>'li','number'=>'series', $value=>$remedy_action]);
        				// add a blank one
        				$remedy_actions .= elgg_view('input/description',['cid'=>$cid,'input_type'=>'actions','metadata_name'=>'description','html_tag'=>'li','number'=>'series']);
// @TODO 2020-01-12 - SAJ - Causes catastrophic failure.  Suspected to be related to sent $container_guid
                        $parts           =  elgg_get_entities(['type'=>'object','container_guid'=>$guid,'subtype'=>'part']);
                        $parts_visibility = count($parts)>0 ? 'edit' : 'add';
        				$parts_contents  = elgg_view('forms/transfers/edit',['perspective' => $action,'section'=>'boqx_contents_parts'  ,'presentation'=> $presentation,'aspect'=> 'part'  ,'display_state'=>'edit','guid'=> $guid,'parent_cid'=> $cid]);
        				$labor_contents  = elgg_view('forms/transfers/edit',['perspective' => $action,'section'=>'boqx_contents_efforts','presentation'=> $presentation,'aspect'=> 'effort','display_state'=>'edit','guid'=> $guid,'parent_cid'=> $cid]);
                        $info_box = 
                        	elgg_format_element('div',['class'=>['activity','info_box'],'style'=>'display:block'],
                        		elgg_format_element('div',['class'=>'info'],
                        			elgg_format_element('div',['class'=>'date row'],
                        			  "<em>Date</em>".
                        			  elgg_format_element('div',['class'=>['dropdown','story_date']],$date_picker)).
                        			elgg_format_element('div',['class'=>'participant row'],
                        			  "<em>Participant</em>".
                        				  $participant)));            			
            			$issue_remedy_marker = 
                            	elgg_format_element('div',['class'=>['edit','details'],'data-cid'=>$cid,'data-qid'=>$qid],
                            	   elgg_format_element('section',['class'=>'edit','data-aid'=>'StoryDetailsEdit','tabindex'=>'-1'],
                            		  elgg_format_element('section',['class'=>'model_details'],
                            			  elgg_format_element('section',['class'=>'story_or_epic_header'],
                            				elgg_format_element('a',['class'=>['autosaves','collapser','CollapseEnvelope__z7DilsLc'],'id'=>"story_collapser_$cid",'data-cid'=>$cid,'data-qid'=>$qid,'tabindex'=>'-1']).
                            				elgg_format_element('fieldset',['class'=>'name'],
                            					elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt62','style'=>'display: flex;'],
                            						elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp','style'=>'flex-basis: 300px;'],
                            						   $marker_title)))).
                            			  elgg_format_element('aside',[],
                            				elgg_format_element('div',['class'=>'wrapper'],
                            				  $nav_controls.
                            				  $info_box.
                            			  elgg_format_element('div',['class'=>'mini attachments'])))).
                            			  elgg_format_element('section',['class'=>['description']],
    										elgg_format_element('div',['class'=>'Activity__header___2pU2Tw9L'],
                            			        elgg_format_element('h4',[],'Actions')).
    										elgg_format_element('ol',['class'=>'Description___3oUx83yQ','data-aid'=>'Description'],
    											$remedy_actions)).
    									elgg_format_element('section',['class'=>['contents',]],
    									    elgg_format_element('h4',[],'Parts').
    										elgg_format_element('div',['class'=>'Description___3oUx83yQ','data-aid'=>'Parts'],
    										    elgg_format_element('div',['id'=>$parts_carton_id, 'class'=>'boqx-carton', 'data-boqx'=>$cid, 'data-aspect'=>'parts'],
    										      elgg_view('page/elements/envelope',['task'=>'parts', 'action'=>$action, 'guid'=>$guid,'parent_cid'=>$cid,'cid'=>$parts_envelope_id,'carton_id'=>$parts_carton_id,'info_boqx'=>$parts_contents,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>$parts_visibility, 'has_collapser'=>'yes'])))).
    									elgg_format_element('section',['class'=>['contents']],
    										elgg_format_element('div',['class'=>'Activity___2ZLT4Ekd'],
        										elgg_format_element('h4',[],'Activity').
        										elgg_format_element('div',['class'=>'Description___3oUx83yQ','data-aid'=>'Activity'],
        										    elgg_format_element('div',['id'=>$labor_carton_id, 'class'=>'boqx-carton', 'data-boqx'=>$cid, 'data-aspect'=>'time'],
        											  elgg_view('page/elements/envelope',['task'=>'efforts', 'action'=>$action, 'guid'=>$guid,'parent_cid'=>$cid,'cid'=>$labor_envelope_id,'carton_id'=>$labor_carton_id,'info_boqx'=>$labor_contents,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>'add','has_collapser'=>'yes']))))).
    									elgg_format_element('section',['class'=>'contents'],
    										elgg_format_element('h4',[],'Results ...').
    										elgg_format_element('div',['class'=>'Description___3oUx83yQ','data-aid'=>'Description'],
    											elgg_format_element('div',['class'=>['DescriptionShow___3-QsNMNj','tracker_markup','DiscoveryShow__placeholder___1NuiicbF'],'data-focus-id'=>"DiscoveryShow--$cid",'data-aid'=>'renderedDescription','tabindex'=>'0'],'Conclusions').
    											elgg_format_element('div',['class'=>'DescriptionEdit___1FO6wKeX','style'=>'display:none'],
    												elgg_format_element('div',['class'=>'textContainer___2EcYJKlD','data-aid'=>'editor'],
    													elgg_format_element('div',[],
    														elgg_format_element('div',['class'=>'DescriptionEdit__write___207LwO1n'],
    															elgg_format_element('div',['class'=>'AutosizeTextarea___2iWScFt6'],
    																elgg_format_element('div',['class'=>'AutosizeTextarea__container___31scfkZp'],
    																	elgg_format_element('textarea',['class'=>['AutosizeTextarea__textarea___1LL2IPEy','editor___1qKjhI5c','tracker_markup'], 'name'=>"jot[$cid][remedy]",'value'=>$jot->remedy, 'aria-labelledby'=>"remedy{$cid}",'data-aid'=>'textarea','data-focus-id'=>"DiscoveryEdit--$cid",'placeholder'=>'Conclusion']))))))))).
    									elgg_format_element('section',['class'=>['media','full']],
    										elgg_format_element('div',['class'=>'Activity___2ZLT4Ekd'],
    										    $media_input))));
            			break;
                }
                break;
            /****************************************
*add********** $section = 'issue_resolve'                 *****************************************************************
             ****************************************/
            case 'issue_resolve':                                                             $display .= '588 issue_resolve>$cid: '.$cid.'<br>588 issue_resolve>$service_cid: '.$service_cid.'<br>';
               unset($form_body, $disabled, $hidden);
               $disabled = 'disabled';
               $service_panel  = elgg_view('forms/experiences/edit', array(
                           'action'         => $action,
                           'section'        => 'issue_effort_service',
                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
                    
            	$delete_button = "<label class=remove-card>". 
                            	    elgg_view('output/url', array(
                            	        'title'    =>'remove effort card',
                            	        'class'    =>'remove-card',
                            	        'text'     => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$delete = elgg_view("output/span", ["class"=>"remove-card", "content"=>$delete_button]);
            	
            	$add_effort = "<button data-aid='addEffortButton' class='EffortEdit__submit___CfUzEM7s autosaves button std egg' type='button' tabindex='-1' style='margin: 3px 3px 0 15px;' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid'>Add</button>";
           	
            	$url = elgg_get_site_url().'jot';
            	$marker_title         = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' style='margin: 8px;' name='jot[observation][effort][$cid][title]' placeholder='Give this effort a name'></textarea>";
            	$marker_date_picker   = elgg_view('input/date', ['name'  => "jot[observation][effort][$cid][moment]", 'placeholder' => $now->format('Y-m-d'), 'value' => $now->format('Y-m-d'), 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[observation][effort][$cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$marker_point_selector = elgg_view_field(['#type'=>'select',
            			                                  'name'=>"jot[observation][effort][$cid][points]",
            			                                   'options_values'=>[0=>'Unestimated',1=>'1 point',2=>'2 points',3=>'3 points']
            	                                        ]);
            	$marker_state_selector = elgg_view_field(['#type'   =>'select',
            			                                  'name'   =>"jot[observation][effort][$cid][state]",
            			                                  'options_values' =>['started'=>'Started','finished'=>'Finished','delivered'=>'Delivered','rejected'=>'Rejected','accepted'=>'Accepted']
            			                                 ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[observation][effort][$cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',]);
            	
            	break;
               /****************************************
*add********** $section = 'issue_effort'                *****************************************************************
               ****************************************/
             case 'issue_effort':
				$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
							'data-cid'=> $cid,
                            'tabindex'=> '-1',]);
				$view_id = '<<view_id>>';
            	$url = elgg_get_site_url().'jot';
            	$issue_effort = elgg_view('partials/form_elements',['element'           =>'new_effort',
		            											    'show_view_summary' => false,
		            			                                    'guid'              => $guid,
            														'parent_cid'        => $parent_cid,
		            			                                    'cid'               => $cid,
            			                                            'service_cid'       => $service_cid,
		            			                                    'qid'               => $qid,]);
            	$marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[observation][effort][$parent_cid][$cid][title]' placeholder='Give this effort a name'></textarea>";
            	$marker_date_picker = elgg_view('input/date', ['name'  => "jot[observation][effort][$parent_cid][$cid][moment]", 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[observation][effort][$parent_cid][$cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[observation][effort][$parent_cid][$cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$add_story_button = elgg_view('output/url', array(
	                                        'title'    => 'Add service effort',
	                		                'data-element' => 'new_service_effort',
            								'data-qid'     => $qid,
            								'data-cid'     => $cid,
	                    				    //'text'     => '+',
	                    					//'href'     => '#',
	                    					//'class'    => 'add-progress-marker addButton___3-z3g3BH',
	                    					'class'    => 'trigger-element add_marker',
	                                        'tabindex' => '-1' 
	                    					));
				Switch ($snippet){
					case 'marker':                                                                                             $display .= '3917 issue_effort>marker $service_cid: '.$service_cid.'<br>';
            			unset($hidden, $hidden_fields);
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
		                
/*		            	$issue_effort = elgg_view('forms/experiences/edit',['action'            => $action,
		            			                                            'section'           =>'issue_effort',
		            														'snippet'           =>'new_effort',
				            											    'show_view_summary' => false,
				            			                                    'guid'              => $guid,
		            														'parent_cid'        => $parent_cid,
				            			                                    'cid'               => $cid,
		            			                                            'service_cid'       => $service_cid,
				            			                                    'qid'               => $qid,]);
*/						$service_item_header_selector = elgg_view('output/span', ['content'=>
					    								elgg_view('output/url', [
									    				    'text'          => '+',
									    					'href'          => '#',
															'class'         => 'elgg-button-submit-element new-item',
															'data-element'  => 'new_service_item',
									    					'data-qid'      => $qid,
									    					'data-guid'     => $guid,
									    					'data-cid'      => $cid,
									    					'data-rows'     => 0,
									    					'data-last-row' => 0,
									    					]), 
					    								'class'=>'effort-input']);
					    $service_item_header_qty   = 'Qty';
					    $service_item_header_item  = 'Item';
					    $service_item_header_tax   = 'tax';
					    $service_item_header_cost  = 'Cost';
					    $service_item_header_total = 'Total';
					    $delete_button = elgg_view('output/url', array(
		                            	        'title'=>'remove remedy effort',
		                            	        'text' => elgg_view_icon('delete-alt'),
		                            	    ));
		            	$delete = elgg_view("output/span", ["content"=>$delete_button]);
		            	$marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[observation][effort][$parent_cid][$cid][title]' placeholder='Service name'></textarea>";
		            	$marker_description = "<textarea name='jot[observation][effort][$parent_cid][$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
		                $line_items_header= "          
						    <div class='rTable line_items service-line-items'>
								<div class='rTableBody'>
									<div id='sortable'>
										<div class='rTableRow pin'>
							                <div class='rTableCell'>$service_item_header_selector</div>
							    			<div class='rTableHead'>$service_item_header_qty</div>
							    			<div class='rTableHead'>$service_item_header_item</div>
							    			<div class='rTableHead'>$service_item_header_tax</div>
							    			<div class='rTableHead'>$service_item_header_cost</div>
							    			<div class='rTableHead'>$service_item_header_total</div>
							    		</div>
							    		<div id={$cid}_new_line_items class='new_line_items'></div>
							    	</div>
						    	</div>
						    </div>
				    		<div id={$cid}_line_item_property_cards></div>";
	            	    $issue_effort_add ="
								<div tabindex='0' class='AddSubresourceButton___S1LFUcMd' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
									 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
									 <span class='AddSubresourceButton__message___2vsNCBXi'>Add an effort</span>
								</div>";
						$issue_effort_show = "
							<div class='EffortShow_haqOwGZY TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid=$cid style='display:none' draggable='true'>
								<input type='checkbox' title='mark task complete' data-aid='toggle-complete' data-focus-id='TaskShow__checkbox--$cid' class='TaskShow__checkbox___2BQ9bNAA'>
								<div class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription' tabindex='0'>
									<span class='TaskShow__title___O4DM7q tracker_markup section-add-issue_effort-marker'></span>
									<span class='TaskShow__description___qpuz67f tracker_markup'></span>
									<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
								</div>
								<nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
									<button class='IconButton___4wjSqnXU IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
										$delete
									</button>
								</nav>
							</div>";
						$issue_effort_edit = "
	                        <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-parent-cid='$parent_cid' data-cid=$cid>
					    		$issue_effort
							</div>";
						break;
					case 'service_item':                                                                      $display.= '3990 $cid: '.$cid.'<br>';
            			unset($hidden, $hidden_fields);
		                $hidden["jot[observation][effort][$parent_cid][$cid][$n][aspect]"] = 'service item';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						$element = 'service_item';
						$delete = elgg_view('output/url', ['title'=>'remove service item',
													       'class'=>'remove-service-item',
													       'data-element'=>$element,
														   'data-qid' => $qid_n,
													       'style'=> 'cursor:pointer',
													       'text' => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$set_properties_button = elgg_view('output/url', ['data-qboqx-dropdown'    => '#'.$qid_n,
														                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
							            	    $form_body .= $hidden_fields;
						$form_body .="
								<div class='rTableRow $element ui-sortable-handle TaskEdit___1Xmiy6lz' data-qid=$qid_n data-guid=$guid>
									<div class='rTableCell'>$delete</div>
									<div class='rTableCell'>".elgg_view('input/hidden', ['name' => "jot[observation][effort][$qid][$n][new_line]"]).
									                          elgg_view('input/number', ['name' => "jot[observation][effort][$qid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1'])."
                                    </div>
									<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', [
																'name'      => "jot[observation][effort][$qid][$n][title]",
											                    'class'     => 'rTableform90',
									                            'id'        => 'line_item_input',
											                    'data-name' => 'title',
															])."
						            </div>
									<div class='rTableCell'>".elgg_view('input/checkbox', [
																'name'      => "jot[observation][effort][$qid][$n][taxable]",
																'value'     => 1,
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'taxable',
					        			                        'default'   => false,
															])."
                                    </div>
									<div class='rTableCell'>".elgg_view('input/text', [
																'name'      => "jot[observation][effort][$qid][$n][cost]",
										 						'class'     => 'last_line_item',
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'cost',
				    											'class'     => 'nString',
															])."
                                    </div>
									<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total line_total_raw'></span></div>
				                </div>";
						break;
					case 'new_effort':
						$show_view_summary = elgg_extract('show_view_summary', $vars, true);
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
						                                        'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
						                    			    	'content'=>$view_summary
						                    			    	. elgg_view($form,['section'=>'issue_resolve', 'action'=>'add', 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid])]);
						$story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
                                    						      'options'=> ['parent_cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
						                                          'content'=>$hidden_fields.$edit_details]);
							            		            		            	
						$form_body = str_replace('<<cid>>', $cid, $story_model);
						break;
					default:
						$issue_efforts = elgg_view('forms/experiences/edit',[
						                                        'section'     => 'issue_effort', 
			                                                    'action'      => $action, 
			                                                    'snippet'     => 'marker', 
			                                                    'parent_cid'  => $parent_cid, 
			                                                    'n'           => $n, 
			                                                    'cid'         => $cid, 
			                                                    'service_cid' => $service_cid, 
			                                                    'qid'         => $qid, 
			                                                    'guid'        => $guid]);
	                break;
				}
				
				/****************************************
*add********** $section = 'issue_effort_service'         *****************************************************************
				 ****************************************/
             case 'issue_effort_service':
             	switch ($snippet){
             		case 'marker1':
             			unset($hidden, $hidden_fields);
             			$n = 1;
		                $hidden["jot[observation][effort][$parent_cid][$cid][aspect]"] = 'service task';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						$service_item_header_selector = elgg_view('output/span', ['content'=>
					    								elgg_view('output/url', [
									    				    'text'          => '+',
									    					'href'          => '#',
															'class'         => 'elgg-button-submit-element new-item',
															'data-element'  => 'new_service_item',
									    					'data-qid'      => $qid,
									    					'data-cid'      => $cid,
					    									'data-parent-cid'=>$parent_cid,
									    					'data-rows'     => 0,
									    					'data-last-row' => 0,
									    					]), 
					    								'class'=>'effort-input']);
					    $service_item_header_qty   = 'Qty';
					    $service_item_header_item  = 'Item';
					    $service_item_header_tax   = 'tax';
					    $service_item_header_cost  = 'Cost';
					    $service_item_header_total = 'Total';
					    $service_item_footer_shipping = elgg_view('input/text', [
																'name'      => "jot[observation][effort][$cid][shipping]",
											                    'data-qid'  => $cid,
											                    'data-name' => 'shipping_cost'
															]);
					    $service_item_footer_sales_tax = elgg_view('input/text', [
																'name'      => "jot[observation][effort][$cid][sales_tax]",
											                    'data-qid'  => $cid,
											                    'data-name' => 'sales_tax'
															]);
					    $delete_button = elgg_view('output/url', array(
		                            	        'title'=>'remove progress marker',
		                            	        'text' => elgg_view_icon('delete-alt'),
		                            	    ));
		            	$delete = elgg_view("output/span", ["content"=>$delete_button]);
		            	$service_marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[observation][effort][$parent_cid][$cid][title]' placeholder='Service name'></textarea>";
		            	$service_marker_description = "<textarea name='jot[observation][effort][$parent_cid][$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
		                $line_items_footer = "<div class='rTableRow pin'><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div>
                				<div class='rTableCell'>Subtotal</div>
                				<div class='rTableCell'><span id={$cid}_subtotal></span><span class='{$cid}_subtotal subtotal_raw'></span></div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div>
                				<div class='rTableCell'>Shipping</div>
                				<div class='rTableCell'>$service_item_footer_shipping</div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div>
                				<div class='rTableCell'>Sales Tax <span class='{$cid}_sales_tax_rate'></span></div>
                				<div class='rTableCell'>$service_item_footer_sales_tax</div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div>
                				<div class='rTableCell'>Total</div>
                				<div class='rTableCell'>
                                      
                                        <span id={$cid}_total></span><span class='{$cid}_total total_raw'></span></div>
                			</div>";
		                /*blank line*/
                		$line_items = elgg_view('forms/experiences/edit',[ 
														  'action'    => $action,
														  'section'   =>'issue_effort_service', 
														  'snippet'   =>'service_item',
														  'parent_cid'=> $parent_cid, 
														  'n'         => $n, 
														  'cid'       => $cid, 
														  'qid'       => $qid,
			                                          	  'qid_n'     => $qid_n,]);
		            	$line_items_properties .= "
		            				<div id={$cid}_{$n} class='jq-dropdown jq-dropdown-tip qboqx-dropdown-relative_xxx'>
					                      <div class='qboqx-dropdown-panel'>".
			                              elgg_view('forms/market/properties', [
				    	                                   'element_type'   => 'service_item',
                			                               'container_type' => 'experience',
                			                               'action'         => $action,
			                              		           'qid'            => $qid,
			                                          	   'qid_n'          => $qid_n,
			                                          	   'cid'            => $cid,
			                                          	   'parent_cid'     => $parent_cid,
			                                          	   'n'              => $n,])."
			                              </div>
			                        </div>";
		                $line_items_pallet = "          
						    <div class='rTable line_items service-line-items'>
								<div class='rTableBody'>
									<div id='sortable'>
										<div class='rTableRow pin'>
							                <div class='rTableCell'>$service_item_header_selector</div>
							    			<div class='rTableHead'>$service_item_header_qty</div>
							    			<div class='rTableHead'>$service_item_header_item</div>
							    			<div class='rTableHead'>$service_item_header_tax</div>
							    			<div class='rTableHead'>$service_item_header_cost</div>
							    			<div class='rTableHead'>$service_item_header_total</div>
							    		</div>
                                        $line_items
							    		<div id={$cid}_new_line_items class='new_line_items'></div>
                                        $line_items_footer
							    	</div>
						    	</div>
						    </div>
                    		<div id={$cid}_line_item_property_cards>
                                $line_items_properties 
                            </div>";
		                $issue_effort_service_add = "<div tabindex='0' class='AddSubresourceButton___2PetQjcb' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-qid='$qid' data-guid='$guid'>
								 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
								 <span class='AddSubresourceButton__message___2vsNCBXi'>Add a task</span>
							</div>";
		                $issue_effort_service_show = "<div class='TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid='$cid' data-qid='$qid' style='display:none' draggable='true'>
								<input type='checkbox' title='mark task complete' data-aid='toggle-complete' data-focus-id='TaskShow__checkbox--$cid' class='TaskShow__checkbox___2BQ9bNAA'>
								<div class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription' tabindex='0'>
									<span class='TaskShow__title___O4DM7q tracker_markup section-add-issue_effort_service-marker1'></span>
									<span class='TaskShow__description___qpuz67f tracker_markup'></span>
									<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
								</div>
								<nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
									<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
										$delete
									</button>
								</nav>
							</div>";
		                $issue_effort_service_edit = "
	                        <div class='TaskEdit___1Xmiy6lz' id='$cid' style='display:none' data-aid='TaskEdit' data-cid='$cid' data-qid='$qid'>
					    		<a class='autosaves collapser-service-item' id='story_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
									<section class='TaskEdit__descriptionContainer___3NOvIiZo'>
			                            <fieldset class='name'>
			                            	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
												<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 300px;'>
	                            					<div class='AutosizeTextarea__container___31scfkZp'>
	                            					    $service_marker_title
	                            					</div>
	                            				</div>
												<button data-aid='addTaskButton'  type='submit'  class='TaskEdit__submit___3m10BkLZ std egg' style='margin: 3px 3px 0 15px;order: 2;' data-cid='$cid' data-parent_cid='$parent_cid'>Add
												</button>
	                            			</div>
	                            		</fieldset>
		                                <section class='description full'>
		                                     <div data-reactroot='' data-aid='Description' class='Description___3oUx83yQ issue_effort_service-marker1'><h5>Service Description</h5>
		                                        	<div data-focus-id='ServiceShow--$cid' data-aid='renderedDescription' tabindex='0' class='ServiceShow___3-QsNMNj tracker_markup ServiceShow__placeholder___1NuiicbF'>Describe the service
		                                        	</div>
		                                        	<div class='ServiceEdit___1FO6wKeX' style='display:none'>
		                                        	    <div data-aid='editor' class='textContainer___2EcYJKlD'>
		                                        	        <div>
		                                        	        	<div class='ServiceEdit__write___207LwO1n'>
		                                        	        		<div class='AutosizeTextarea___2iWScFt6'>
		                                        	        			<div class='AutosizeTextarea__container___31scfkZp'>
		                                        	        				$service_marker_description
		                                        	        			</div>
			                                        	        		<div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt editor___1qKjhI5c tracker_markup'>
				                                        	        		<span></span>
				                                        	        		<span>w</span>
				                                        	        	</div>
				                                        	        </div>
				                                        	    </div>
				                                        	</div>
				                                      </div>
				                                  </div>
				                              </div>
			                             </section>
			                             <section class='service-items'>
			                             	<div>
			                             		<h5 style='padding:10px 0 0 10px;'>Service Items</h5>
			                             		$line_items_pallet
			                             	</div>
			                             </section>
									</section>
							</div>";
             			break;
             		case 'service_item':
             			unset($hidden, $hidden_fields);
		                $hidden["jot[observation][effort][$parent_cid][$cid][$n][aspect]"] = 'service item';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						$element = 'service_item';
						$delete = elgg_view('output/url', ['title'=>'remove service item',
													       'class'=>'remove-service-item',
													       'data-element'=>$element,
														   'data-qid' => $qid_n,
													       'style'=> 'cursor:pointer',
													       'text' => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$set_properties_button = elgg_view('output/url', ['data-qboqx-dropdown'    => '#'.$cid.'_'.$n,
														                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
						$service_item_row ="
								<div class='rTableRow $element ui-sortable-handle TaskEdit___1Xmiy6lz' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
									<div class='rTableCell'>$delete</div>
									<div class='rTableCell'>".elgg_view('input/hidden', ['name' => "jot[observation][effort][$parent_cid][$cid][$n][new_line]"]).
									                          elgg_view('input/number', ['name' => "jot[observation][effort][$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', array(
																'name'      => "jot[observation][effort][$parent_cid][$cid][$n][title]",
											                    'class'     => 'rTableform90',
									                            'id'        => 'line_item_input',
											                    'data-name' => 'title',
															))."
						            </div>
									<div class='rTableCell'>".elgg_view('input/checkbox', array(
																'name'      => "jot[observation][effort][$parent_cid][$cid][$n][taxable]",
																'value'     => 1,
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'taxable',
					        			                        'default'   => false,
															))."</div>
									<div class='rTableCell'>".elgg_view('input/text', array(
																'name'      => "jot[observation][effort][$parent_cid][$cid][$n][cost]",
										 						'class'     => 'last_line_item',
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'cost',
				    											'class'     => 'nString',
															))."</div>
									<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total line_total_raw'></span></div>
				                </div>";
             			break;
             	}
             	break;
        }
     break;        
/****************************************
 * $action = 'edit'                      *****************************************************************************
 ****************************************/
    case 'edit':
        Switch ($section){
            case 'main':
                unset($form_body, $hidden, $buttons);
                $title              = elgg_extract('title', $vars, 'Experiences');
                $title_field        = elgg_view("input/text"    , ["name" => "jot[title]"         , 'value'=>$jot->title      , 'placeholder' => 'Give your experience a name', 'style' => 'width:100%', 'id'=>'title', 'data-parsley-required'=>'true']);
                $description_field  = elgg_view("input/longtext", ["name" => "jot[description]"   , 'value'=>$jot->description, 'placeholder' => 'Describe the experience ...', 'style' => 'word-wrap: break-word; /*width:520px; */height: 52px; margin-left: 0px; margin-right: 0px;', 'id' => $qid.'_description']);
                $moment_field       = elgg_view('input/date'    , ['name' => 'jot[moment]'        , 'value'=>$moment          , 'placeholder' => $now->format('Y-m-d')        , 'style' => 'width:150px']);
            	$buttons            = "<button class='autosaves button std do_xxx' data-qid='$qid' type='submit' tabindex='-1' data-perspective='save' data-action='$action'>Save</button>";
                // loading styles inline to raise their priority
                $form_body .= elgg_view('css/quebx/user_agent', ['element'=>'experience']);
            	$id_value                      = $guid;
                $hidden['jot[guid]']           = $guid;
                $hidden['jot[owner_guid]']     = $owner_guid;
            	$hidden['jot[container_guid]'] = $container_guid;
            	$hidden['jot[subtype]']        = $subtype;
            	$hidden['jot[aspect]']         = $aspect;
            break;
            /****************************************
*edit********** $section = 'experiences'                   *****************************************************************
             * Receives the item guid and breaks out each individual experience
             ****************************************/
            case 'experiences':
                $carton_id = quebx_new_id('c');
                $title = $presentation == 'open_boqx' ? '' :'Experiences';
                if($guid){
                    $experiences = elgg_get_entities_from_relationship(['relationship'=>'experience','relationship_guid'=>$guid,'inverse_relationship'=>true,'types'=>'object','subtypes'=>'experience','limit'=>0]);
                    $pieces      = count($experiences);
                }
                unset($experience_vars);
                $experience_vars = array_merge($vars,['guid'=>0,'action'=>'add','section'=>'single_experience','parent_cid'=>$cid,'carton_id'=>$carton_id,'title'=>$presentation == 'open_boqx' ? '' :'experiences']);
                unset($experience_vars['cid'],$experience_vars['entity']);
                $item_experiences[]             = elgg_view('forms/experiences/edit',$experience_vars);
                if($experiences){
                    foreach($experiences as $experience){
                        unset($experience_vars);
                        $experience_vars = array_merge($vars,['guid'=>$experience->guid,'action'=>'add','section'=>'single_experience','parent_cid'=>$cid,'carton_id'=>$carton_id,'visible'=>'show']);
                        unset($experience_vars['cid']);
                        $item_experiences[]              = elgg_view('forms/experiences/edit',$experience_vars);
                    }
                }
                switch($presentation){
                    case 'carton':
                        // inner carton
                        $info_boqx  = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$item_experiences, 'envelope_id'=>$envelope_id]);
                        // new cid for outer carton
                        $carton_id   = quebx_new_id('c');
                        $window_title= 'Experiences';
                        if($pieces==1) 
                            $window_title = 'Experience';
                        $show_title = elgg_format_element('span',['class'=>'TaskShow__qty_7lVp5tl4'], $pieces).
                                      elgg_format_element('span',['class'=>'TaskShow__title___O4DM7q'], $window_title);
                        if($pieces>0 || $display_state == 'view')
                            $visible = 'show';
                        $carton_contents = elgg_view('page/elements/envelope',['task'=>'experiences', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$cid, 'cid'=>$envelope_id, 'carton_id'=>$carton_id, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>$presentation,'presence'=>$presence,'display_state'=>$display_state,'visible'=>$visible,'title'=>$title,'show_title'=>$show_title,'has_collapser'=>'yes','allow_delete'=>false,'tally'=>$pieces,'origin'=>'forms/experiences/edit>'.$action.'>'.$section]);
                        $form_body       = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$carton_contents,'title'=>$title] );
                        break;
                    default:
                        $form_body       = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$item_experiences,'title'=>$title, 'tally'=>quebx_count_pieces($guid, 'experiences')] );
                }
                break;
                        
            /****************************************
*edit********** $section = 'things_xxx'                 *****************************************************************
             ****************************************/
            case 'things_xxx':
/*                unset($form_body, $things, $hidden, $current_asset_guid);
                if (elgg_instanceof($container, 'object', 'market') || elgg_instanceof($container, 'object', 'item')){
                    $current_asset_guid = $container_guid; 
                }
                $item_add_button = "<a title='add another thing - not in Quebx' class='elgg-button-submit-element add-other-item' style='cursor:pointer;height:14px;width:30px'>+</a> <span id='other_button_label'>Things not kept in Quebx</span>";
                $remove_button = "<a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>";
*/                $assets = elgg_get_entities_from_relationship([
						'type'                 => 'object',
						'subtypes'             => ['market'],
						'relationship'         => 'experience',
						'relationship_guid'    => $guid,
						'inverse_relationship' => false,
						'limit'                => 0,
						]);
                if($assets){
					foreach($assets as $asset){
						$quebx_assets[] = $asset->guid;
					}
				}
				
/*                if($presentation == 'qbox_experience' ||
                   $presentation == 'popup'){
                      $div_id = elgg_extract('qid', $vars);
                      $style  = elgg_extract('style', $vars, $style);}
                else                       {$div_id = 'Things_panel';}
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $things .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $things .= "<div panel='Things' aspect='attachments' guid=$guid id=$div_id class='elgg-head experience-panel' style='$style;'>
								Things associated with this experience".
								elgg_view('input/assetpicker', [
									'name'          =>'jot[assets]', 
									'values'        =>$quebx_assets, 
									'container_guid'=>$guid, 
									'other_name'    => 'jot[ghost_assets][title]', 
									'snapshot'      =>true, 
									'placeholder'   =>'Start typing the name of an item']).
								"$item_add_button
							<div class='rTable' style='width:100%'>
								<div class='rTableBody'>
									<div class='new_other_item'></div>
								</div>
							</div>
							<div style='visibility:hidden'>
								<div class='other_item'>
									<div class='rTableRow'>
										<div class='rTableCell' style='width:100%'>".
										  elgg_view('input/text', [
											'name'        => 'jot[ghost_assets][title][]',
											'class'       => 'last_other_item',
											'placeholder' => 'New Item Name',]).
										"</div>
										<div class='rTableCell' style='text-align:right;padding:0px'>$remove_button</div>
									</div>
								</div>
							</div>
						</div>";
*/                                                                                                $display .= '1590 $qid: '.$qid.'<br>';
            $forms_action       = 'forms/experiences/edit';
                $body_vars = ['container_guid'=> $container_guid,
                              'assets'        => $quebx_assets,
						      'qid'           => $qid,
						      'cid'           => $cid,
						      'parent_cid'    => $parent_cid,
						      'section'       => $section,
						      'visibility'    => 'display:none',
						      'presentation'  => $presentation,
                              'presence'      => $presence,
						      'action'        => 'add',
                              'perspective'   => $perspective,
                ];
                $things = elgg_view($forms_action, $body_vars);                                           //register_error($display);
            break;
            
            /****************************************
*edit********** $section = 'things'                   *****************************************************************
             * Receives the item guid and breaks out each individual thing
             ****************************************/
                case 'things':
                $things_used_vars            = ['action'=>'add', 'section'=>'things_used', 'perspective'=>$perspective, 'presentation'=>$presentation, 'presence'=>$presence, 'data-qid'=>$div_id, 'cid' => $cid, 'qid'=> $qid];
                $things_used_vars['snippet'] = 'things_used_add';
                $things_used_add             = elgg_view('forms/experiences/edit', $things_used_vars);
                $things_used_vars['snippet'] = 'dropboqx';
                $things_used_dropboqx        = elgg_view('forms/experiences/edit', $things_used_vars);
                $things_used_content         = elgg_format_element('div', ['class'=>'things_add_pallet'],$things_used_add.$things_used_dropboqx);

                $carton_id = quebx_new_id('c');
                $title = $presentation == 'open_boqx' ? '' :'things';
                if($guid)
                    $things_used    = elgg_get_entities_from_relationship(['relationship_guid'=>$guid,'inverse_relationship'=>false,'types'=>'object','subtypes'=>['market','item'],'limit'=>0]);
                unset($thing_vars);
                $thing_vars = $vars;
                unset($thing_vars['cid'],$thing_vars['entity'],$thing_vars['guid']);
                $item_things[]             = $things_used_dropboqx;
                if($things_used){
                    $pieces = count($things_used);
                    foreach($things_used as $thing){
                        unset($thing_vars);
                        $thing_vars                 = $vars;
                        unset($thing_vars['cid']);
                        $thing_vars['guid']         = $thing->guid;
                        $thing_vars['action']       = 'add';
                        $thing_vars['section']      = 'thing';
                        $thing_vars['parent_cid']   = $cid;
                        $thing_vars['carton_id']    = $carton_id;
                        $thing_vars['presentation'] = $presentation;
                        $thing_vars['presence']     = $presence;
                        $thing_vars['visible']      = 'show';
                        $thing_vars['form_wrapper'] = $form_wrapper;
                        $item_things[]              = elgg_view('forms/experiences/edit',$thing_vars);
                    }
                }
     
                switch($presentation){
                    case 'carton':
                        if($pieces==1) $label = "Item";
                        if($pieces>1)  $label = "Items";
                        if($pieces>0)  $visible="show";
                        $carton_contents = elgg_view('page/elements/envelope',['task'=>'things', 'action'=>$action, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>implode('',$item_things),'presentation'=>'contents', 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title, 'jot'=>['label'=>$label,'qty'=>$pieces],'show_title'=>false, 'has_collapser'=>'yes','allow_delete'=>false]);
                        $things = elgg_view_layout('carton',['cid'=>$parent_cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$carton_contents,'title'=>$title,'tally'=>$pieces] );
                        break;
                    default:
                        $things = implode('',$item_things);
                        break;
                 }
                    break;
            
            /****************************************
*edit********** $section = 'thing'                   *****************************************************************
             ****************************************/
                case 'thing':
                    unset($input_tabs, $form_body, $hidden);
                    $qid             = elgg_extract('qid', $vars);
                    $hidden["jot[aspect]"]              = "observation";
                    $hidden["jot[observation][aspect]"] = "thing";
                break;
            /****************************************
*edit********** $section = 'documents'                 *****************************************************************
             ****************************************/
            case 'documents':
                unset($form_body, $hidden);
                if ($presentation == 'qbox'){
                	$hidden['jot[container_guid]'] = $container_guid;
	            	$hidden['jot[guid]']           = $guid;
	            	$hidden['jot[aspect]']         = 'documents';
	                $documents = elgg_get_entities_from_relationship([
						'type' => 'object',
						'relationship' => 'document',
						'relationship_guid' => $guid,
						'inverse_relationship' => true,
						'limit' => 0,
						]);
	                foreach ($documents as $document){                               //$display .= '489 ['.$key.'] => '.$image_guid.'<br>';
	                	$item_document_guids[]=$document->getGUID();
	                }
                }
            break;
            /****************************************
*edit********** $section = 'gallery'                 *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
            if ($presentation == 'qbox'){
            	$hidden['jot[container_guid]'] = $container_guid;
            	$hidden['jot[guid]']           = $guid;
            	$hidden['jot[aspect]']         = 'pictures';
                $item_image_guids = $entity->images;  
                if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
                foreach ($item_image_guids as $key=>$image_guid){                               //$display .= '489 ['.$key.'] => '.$image_guid.'<br>';
                	if ($image_guid == ''){                     
                		unset($item_image_guids[$key]);
                		continue;
                	}
                }
            }
            break;
            /****************************************
*edit********** $section = 'media'                   *****************************************************************
             * Receives the item guid and breaks out each individual media item
             ****************************************/
                case 'media':
                    $carton_id = quebx_new_id('c');
                    $title = $title ?: $presentation == 'open_boqx' ? '' :'Media';
                    $item_image_guids = $entity->images;
                    if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
                    if($item_image_guids)
                        foreach($item_image_guids as $key=>$image_guid)
                            $images[] = get_entity($image_guid);
                
                    $experience_vars                = array_merge($vars,['guid'=>0,'action'=>'add','section'=>'media','parent_cid'=>$cid, 'carton_id'=>$carton_id,'presentation'=>$presentation,'presence'=>$presence,'perspective'=>$perspective,'form_wrapper'=>$form_wrapper,'title'=>$presentation == 'open_boqx' ? '' :'media']);
                    unset($experience_vars['cid'],$experience_vars['entity'],$experience_vars['guid']);
                    $item_experiences[]             = elgg_view('forms/experiences/edit',$experience_vars);
                    if($item_image_guids){
                        foreach($item_image_guids as $key=>$image_guid){
                            $experience_vars        = array_merge($vars,['guid'=>$guid,'action'=>'add','section'=>'media','parent_cid'=>$cid,'carton_id'=>$carton_id,'presentation'=>$presentation,'presence'=>$presence,'visible'=>'show','form_wrapper'=>$form_wrapper]);
                            unset($experience_vars['cid']);
                            $item_experiences[]     = elgg_view('forms/experiences/edit',$experience_vars);
                        }
                    }
                    $form_body                 = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$item_experiences,'title'=>$title, 'tally'=>quebx_count_pieces($guid, 'experiences')] );
                    break;
            /****************************************
*edit********** $section = 'expand'                  *****************************************************************
             ****************************************/
            case 'expand':
                unset($form_body, $hidden);
                Switch ($presentation){
                	case 'qbox_experience':
                	case 'qbox':
                	case 'popup':
                		
                	break;
                	default:
		                $nothing_panel     = NULL;
		                $instruction_panel = elgg_view('forms/experiences/edit', array(
		                                               'action'         => $action,
		                                               'section'        => 'instruction',
		                                               'guid'           => $guid,));
		                $observation_panel = elgg_view('forms/experiences/edit', array(
		                                               'action'         => $action,
		                                               'section'        => 'observation',
		                                               'guid'           => $guid,));
		                $event_panel = elgg_view('forms/experiences/edit', array(
		                                               'action'         => $action,
		                                               'section'        => 'event',
		                                               'guid'           => $guid,));
		                $project_panel = elgg_view('forms/experiences/edit', array(
		                                               'action'         => $action,
		                                               'section'        => 'project',
		                                               'guid'           => $guid,
                    		                           'presentation'   => $presentation,));
		                $issue_panel = elgg_view('forms/experiences/edit', [
		                                               'action'         => $action,
		                                               'section'        => 'issue',
		                                               'guid'           => $guid]);
	                break;
                }
                break;
            /****************************************
*edit********** $section = 'instruction'             *****************************************************************
             ****************************************/
                case 'instruction':
                    unset($input_tabs, $form_body, $hidden);
                    $material_panel .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => $action,
                                                 'section'        => 'instruction_material',
                                                 'guid'           => $guid,));
                    $tools_panel    .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => $action,
                                                 'section'        => 'instruction_tools',
                                                 'guid'           => $guid,));
                    $steps_panel    .= elgg_view('forms/experiences/edit', array(
                                                 'action'         => $action,
                                                 'section'        => 'instruction_steps',
                                                 'guid'           => $guid,));
                break;
            /****************************************
*edit********** $section = 'event'                 *****************************************************************
             ****************************************/
                case 'event':
                    unset($stage_selector, $stage_params, $form_body, $hidden, $input_tabs);
                    $stage_params = array("name"    => "jot[event][stage]",
                                          "align"   => "horizontal",
                    					  "value"   => $jot->state ?: 1,
                                          "guid"    => $guid,
                    					  "options2" => array(
                    					                   array("label" =>"Planning",
                    					                         "option" => 1,
                    					                         "title" => "Initial planning stage"),
                    					                   array("label" => "In Process",
                    					                         "option" => 2,
                    					                         "title" => "Event is active and under way"),
                    					                   array("label" => "Postponed",
                    					                         "option" => 3,
                    					                         "title" => "Event will be rescheduled"),
                    					                   array("label" => "Cancelled",
                    					                         "option" => 4,
                    					                         "title" => "Event is cancelled and will not continue"),
                    					                   array("label" => "Complete",
                    					                         "option" => 5,
                    					                         "title" => "Event is complete.")
                    					                   ),
                    				      "default" => 1,
                    					 );
                    $stage_selector = elgg_view('input/quebx_radio', $stage_params);
                    
                    $input_tabs[] = array('title'=>'Planning'  , 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'event_planning'  , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'In Process', 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_in_process', 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Postponed' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_postponed' , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Cancelled' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_cancelled' , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs[] = array('title'=>'Complete'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'event_complete'  , 'guid'=>$guid, 'aspect'=>'event_input');
                    $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'event_input', 'tabs'=>$input_tabs));
                    
                    $planning_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_planning',
                                           'guid'           => $guid,));
                    $in_process_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_in_process',
                                           'guid'           => $guid,
                    ));
                    $cancelled_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_cancelled',
                                           'guid'           => $guid,));
                    $postponed_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_postponed',
                                           'guid'           => $guid,));
                    $complete_panel .= elgg_view('forms/experiences/edit', array(
                                           'action'         => $action,
                                           'section'        => 'event_complete',
                                           'guid'           => $guid,));
                break;
            /****************************************
*edit********** $section = 'project'                 *****************************************************************
             ****************************************/
                case 'project':
                    unset($stage_selector, $stage_params, $form_body, $hidden);
                    $stage_params = array("name"    => "jot[project][stage]",
                                          'align'   => 'horizontal',
                    					  "value"   => 1,
                                          "guid"    => $guid,
                    					  "options2" => array(
                    					                   array("label" =>"Planning",
                    					                         "option" => 1,
                    					                         "title" => "Initial project setup"),
                    					                   array("label" => "In Process",
                    					                         "option" => 2,
                    					                         "title" => "Project is active and under way"),
                    					                   array("label" => "Blocked",
                    					                         "option" => 3,
                    					                         "title" => "Project is impeded"),
                    					                   array("label" => "Cancelled",
                    					                         "option" => 4,
                    					                         "title" => "Project is incomplete and will not continue"),
                    					                   array("label" => "Complete",
                    					                         "option" => 5,
                    					                         "title" => "Project is complete.")
                    					                   ),
                    				      'default' => 1,
                    					 );
                    $stage_selector = elgg_view('input/quebx_radio', $stage_params);
                    
                    $input_tabs[] = array('title'=>'Planning'  , 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'project_planning'  , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'In Process', 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_in_process', 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Blocked'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_blocked'   , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Cancelled' , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_cancelled' , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs[] = array('title'=>'Complete'  , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'project_complete'  , 'guid'=>$guid, 'aspect'=>'project_input');
                    $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'project_input', 'tabs'=>$input_tabs));
                    
                    $milestones_panel = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_milestones',
                                                  'guid'           => $guid,));
                    $in_process_panel = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_in_process',
                                                  'guid'           => $guid,
                    		                      'presentation'   => $presentation,));
                    $blocked_panel    = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_blocked',
                                                  'guid'           => $guid,));
                    $cancelled_panel  = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_cancelled',
                                                  'guid'           => $guid,));
                    $complete_panel   = elgg_view('forms/experiences/edit', array(
                                                  'action'         => $action,
                                                  'section'        => 'project_complete',
                                                  'guid'           => $guid,));
                break;
            /****************************************
*edit********** $section = 'observation'                 *****************************************************************
             ****************************************/
                case 'observation':                                                                 $display .= '229 $action: '.$action.'<br>229 $aspect: '.$aspect.'<br>229 $section: '.$section.'<br>';
                    unset($state_params, $state_selector, $form_body, $hidden);
                    $input_tabs[] = array('title'=>'Discovery', 'selected' => $state == 1, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_discoveries', 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs[] = array('title'=>'Resolve'  , 'selected' => $state == 2, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_efforts'    , 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs[] = array('title'=>'Request'  , 'selected' => $state == 3, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_request'     , 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs[] = array('title'=>'Accept'   , 'selected' => $state == 4, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_accept'     , 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs[] = array('title'=>'Complete' , 'selected' => $state == 5, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_complete'   , 'guid'=>$guid, 'aspect'=>'observation_input');
	                $input_tabs   = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'observation_input', 'tabs'=>$input_tabs));
	                    
                    $discoveries_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_discoveries',
                                                   'guid'           => $guid,));
                    $resolve_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_resolve',
                                                   'guid'           => $guid,
                    							   'action'         => $action,));
                    $assign_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_request',
                                                   'guid'           => $guid,));
                    $accept_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_accept',
                                                   'guid'           => $guid,));
                    $complete_panel = elgg_view('forms/experiences/edit', array(
                                                   'action'         => $action,
                                                   'section'        => 'observation_complete',
                                                   'guid'           => $guid,));
                break;
            /****************************************
*edit********** $section = 'observation_discoveries'                 *****************************************************************
             ****************************************/
                case 'observation_discoveries':
                    unset($form_body, $hidden, $last_class);
                break;
            /****************************************
*edit********** $section = 'observation_resolve'                 *****************************************************************
             ****************************************/
                case 'observation_resolve':
                    unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'observation_request'                 *****************************************************************
             ****************************************/
                case 'observation_request':
                    unset($form_body, $hidden, $form_items);
                    $next_request_number = 'RITM#####';
                    $request_selector    = 'placeholder';
                    $form_items .= "
                            <div class='rTableRow'>
                        		<div class='rTableCell' style='width:30%;padding:0px'>Request Number   		</div>
                        		<div class='rTableCell' style='width:30%;padding:0px'>$next_request_number".
                        	      elgg_view('input/hidden', array(
                        			'name'        => 'jot[observation][request][number][]',
                        	        'value'       => $next_request_number,))."                               </div>
                        		<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    		</div>";
                    $form_items .= "
                    	   <div class='rTableRow'>
                        		<div class='rTableCell' style='width:30%;padding:0px'>Request Type           </div>
                        		<div class='rTableCell' style='width:30%;padding:0px'>$request_selector      </div>
                        		<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'></div>
                    	   </div>";
                break;
            /****************************************
*edit********** $section = 'observation_accept'                    *****************************************************************
             ****************************************/
            case 'observation_accept':
                unset($form_body, $hidden, $form_items);
                $form_items .= "
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                    	    <div class='rTableRow'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                    		      elgg_view('input/longtext', array(
                    				'name'        => 'jot[observation][accept][description][]',
                    	      	    'placeholder' => 'Acceptance notes',
                    			))."
                    			</div>
                   			</div>
                		</div>
                    </div>";
                break;
            /****************************************
*edit********** $section = 'observation_complete'                 *****************************************************************
             ****************************************/
            case 'observation_complete':
                unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'instruction_material'     *****************************************************************
             ****************************************/
            case 'instruction_material':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                break;
            /****************************************
*edit********** $section = 'instruction_tools'     *****************************************************************
             ****************************************/
            case 'instruction_tools':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                $tool    = elgg_view('input/text', array('name'=> 'jot[instruction][tool][title][]', 'class'=> 'last_tool_item','placeholder' => 'Tool Name',));
                break;
            /****************************************
*edit********** $section = 'instruction_steps'        *****************************************************************
             ****************************************/
            case 'instruction_steps':
                unset($form_body, $hidden);
                $step_add_button = "<a title='add step' class='elgg-button-submit-element add-step-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                break;
            /****************************************
*edit********** $section = 'project_milestones'       *****************************************************************
             ****************************************/
            case 'project_milestones':
                unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'project_in_process'              *****************************************************************
             ****************************************/
            case 'project_in_process':
				if ($presentation == 'qbox'){
            		$form_body .= '$presentation: '.$presentation;
//            		break;
            	}
                unset($form_body, $hidden);
                $set_properties_button = elgg_view('output/url', array(
                           'id'    => 'properties_set',
                           'text'  => elgg_view_icon('settings-alt'), 
                           'title' => 'set properties',
                           'style' => 'cursor:pointer;',
                ));
            	$delete_button = "<label class=remove-progress-marker>". 
                            	    elgg_view('output/url', array(
                            	        'title'=>'remove progress marker',
                            	        'class'=>'remove-progress-marker',
                            	        'text' => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$delete = elgg_view("output/span", array("class"=>"remove-progress-marker", "content"=>$delete_button));
                $expander = elgg_view("output/url", array(
                            'text'    => '',
//                            'href'    => '#',
                            'class'   => 'expander undraggable',
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',
                          ));
                $collapser = elgg_view("output/url", array(
                            'text'    => '',
//                            'href'    => '#',
                            'class'   => 'collapser autosaves',
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',
                          ));
                break;
            /****************************************
*edit********** $section = 'project_blocked'              *****************************************************************
             ****************************************/
            case 'project_blocked':
                unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'project_cancelled'              *****************************************************************
             ****************************************/
            case 'project_cancelled':
                unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'project_complete'              *****************************************************************
             ****************************************/
            case 'project_complete':
                unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'event_planning'                 *****************************************************************
             ****************************************/
            case 'event_planning':
                unset($form_body, $hidden, $start_date, $end_date, $activity);
                $interval_add_button = "<a title='add interval' class='elgg-button-submit-element add-schedule-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $interval_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-schedule-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                $start_date  = elgg_view('input/date', array('name'=> 'jot[event][schedule][start_date][]', 'placeholder'=>'start date'));
                $end_date    = elgg_view('input/date', array('name'=> 'jot[event][schedule][end_date][]', 'placeholder'=>'end date'));
                $activity    = elgg_view('input/text', array('name'=> 'jot[event][schedule][title][]', 'class'=> 'last_schedule_item','placeholder' => 'Activity Name',));
                break;
            /****************************************
*edit********** $section = 'event_in_process'              *****************************************************************
             ****************************************/
            case 'event_in_process':
                unset($form_body, $hidden); 
                break;
            /****************************************
*edit********** $section = 'event_postponed'         *****************************************************************
             ****************************************/
            case 'event_postponed':
                unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'event_cancelled'              *****************************************************************
             ****************************************/
            case 'event_cancelled':
                unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'event_complete'              *****************************************************************
             ****************************************/
            case 'event_complete':
                unset($form_body, $hidden);
                break;
            /****************************************
*edit********** $section = 'transfer'                 *****************************************************************
             ****************************************/
            case 'transfer':
                unset($form_body, $hidden);
                if ($presentation == 'qbox'){
                	$hidden['jot[container_guid]'] = $container_guid;
	            	$hidden['jot[guid]']           = $guid;
	            	$hidden['jot[aspect]']         = 'documents';
	                $documents = elgg_get_entities_from_relationship(array(
						'type' => 'object',
						'relationship' => 'document',
						'relationship_guid' => $guid,
						'inverse_relationship' => true,
						'limit' => false,
						));
	                foreach ($documents as $document){                               //$display .= '489 ['.$key.'] => '.$image_guid.'<br>';
	                	$item_document_guids[]=$document->getGUID();
	                }
                }
                	
            break;
            
            /****************************************
*edit********** $section = 'issues'                   *****************************************************************
             * Receives the item guid
             * Creates a blank issue form
             * Breaks out each individual issue related to the item
             ****************************************/
                case 'issues':
echo "<!-- form_method: $form_method -->";
//                    $issues = elgg_get_entities(['type'=>'object','subtype'=>'issue','container_guid'=>$guid]);
                    $carton_id   = quebx_new_id('c');
                    $envelope_id = quebx_new_id('c');
                    $form_id     = quebx_new_id('f');
                    $title       = $presentation == 'open_boqx' ? '' :'Issues';
                    if($guid){
                       $issues  = elgg_get_entities_from_relationship(['relationship'=>'on','relationship_guid'=>$guid,'inverse_relationship'=>true,'types'=>'object','subtypes'=>'issue','limit'=>0]);
                       $pieces  = quebx_count_pieces($guid, 'issues');
                    }
                    // Create a blank issue form
                    unset($issue_vars);
                    $issue_vars             = array_merge($vars,['guid' => 0,'action'=>'add','section'=>'issue','parent_cid'=> $parent_cid,'carton_id'=> $carton_id,'title'=> $title,'display_state'=>'add','form_method'=>$form_method]);
                    unset($issue_vars['cid'],$issue_vars['entity'],$issue_vars['form_id']);
                    $form_wrapper['form_vars']['id'] = $form_id;
                    $issue_vars['form_wrapper'] = $form_wrapper;
                    if($display_state != 'view')
                        $item_issues[]         = elgg_view('forms/experiences/edit',$issue_vars);
                    if($issues){
                    // Break out each individual issue related to the item
                        foreach($issues as $issue){
                            unset($issue_vars);
                            $form_id = quebx_new_id('f');
                            $issue_vars     = array_merge($vars,['guid' => $issue->guid,'action'=>'add','section'=>'issue','parent_cid'=> $parent_cid,'carton_id'=> $carton_id,'visible'=>'show','form_method'=>$form_method]);
                            unset($issue_vars['cid'],$issue_vars['form_id']);
                            $form_wrapper['form_vars']['id'] = $form_id;
                            $issue_vars['form_wrapper'] = $form_wrapper;
                            $item_issues[]              = elgg_view('forms/experiences/edit',$issue_vars);
                        }
                    }
                    switch($presentation){
                        case 'carton':
                            // inner carton
                            $info_boqx  = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$item_issues, 'envelope_id'=>$envelope_id]);
                            // new cid for outer carton
                            $carton_id   = quebx_new_id('c');
                            $window_title= 'Issues';
                            if($pieces==1) 
                                $window_title = 'Issue';
                            $show_title = elgg_format_element('span',['class'=>'TaskShow__qty_7lVp5tl4'], $pieces).
                                          elgg_format_element('span',['class'=>'TaskShow__title___O4DM7q'], $window_title);
                            if($pieces>0)  $visible="show";
                            if($display_state == 'view')
                                           $visible = 'show';
//                            $carton_contents = elgg_view('page/elements/envelope',['task'=>'issues', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$cid, 'carton_id'=>$carton_id, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>'contents','presence'=>$presence,'display_state'=>$display_state,'visible'=>$visible,'title'=>$title,'show_title'=>$show_title,'has_collapser'=>'yes','allow_delete'=>false,'tally'=>$pieces]);
                            $carton_contents = elgg_view('page/elements/envelope',['task'=>'issues', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$cid, 'cid'=>$envelope_id, 'carton_id'=>$carton_id, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>$presentation,'presence'=>$presence,'display_state'=>$display_state,'visible'=>$visible,'title'=>$title,'show_title'=>$show_title,'has_collapser'=>'yes','allow_delete'=>false,'tally'=>$pieces,'origin'=>'forms/experiences/edit>'.$action.'>'.$section]);
//                            $carton_contents = elgg_view('page/elements/envelope',['task'=>'issues', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'carton_id'=>$carton_id, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>'contents','presence'=>$presence,'display_state'=>$display_state,'visible'=>$visible,'title'=>$title,'show_title'=>$show_title,'has_collapser'=>'yes','allow_delete'=>false,'tally'=>$pieces]);
//                            $carton_contents = elgg_view('page/elements/envelope',['task'=>'issue', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'carton_id'=>$carton_id, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>'contents','presence'=>$presence,'display_state'=>$display_state,'visible'=>$visible,'title'=>$title,'show_title'=>$show_title,'has_collapser'=>'yes','allow_delete'=>false,'tally'=>$pieces]);
                            $form_body       = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$carton_contents,'title'=>$title] );
                            break;
                        default:
                            $form_body       = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>$aspect,'pieces'=>$item_issues,'title'=>$title, 'tally'=>quebx_count_pieces($guid, 'issues')] );
                            break;
                    }
                    break;
            
            /****************************************
*edit********** $section = 'issue'                   *****************************************************************
             ****************************************/
                case 'issue':                                                                  $display .= '530 issue>$service_cid: '.$service_cid.'<br>';
                    unset($input_tabs, $form_body, $hidden);
                    $qid             = elgg_extract('qid', $vars);
                    $hidden["jot[aspect]"]              = "observation";
                    $hidden["jot[observation][aspect]"] = "issue";
                break;
            /****************************************
*edit********** $section = 'issue_discoveries'        *****************************************************************
             * Receives the issue guid and breaks out each individual discovery 
             ****************************************/
                case 'issue_discoveries':
                if(empty($carton_id))
                    $carton_id = quebx_new_id('c');
                //$discoveries =  elgg_get_entities(['type'=>'object','container_guid'=>$guid,'subtype'=>'discovery']);
                
                $jot_options = [
                	'type' => 'object',
                    'subtype'=>'discovery', 
                	'container_guid'=>$guid, 
                	'order_by_metadata' => ['name'=>'moment',
                			                'direction'=>ASC,
                                            'as'=>date],
                	'order_by'=>'time_updated',
                	'limit' => 0,];
                $discoveries = elgg_get_entities_from_metadata($jot_options);
                                
                if($discoveries){
                    foreach($discoveries as $discovery){
                        unset($discovery_vars);
                        $discovery_vars = $vars;
                        $discovery_vars['action']         = 'add';
                        $discovery_vars['perspective']    = 'save';
                        $discovery_vars['section']        = 'issue_discovery';
                        $discovery_vars['guid']           = $discovery->guid;
                        $discovery_vars['container_guid'] = $guid;
                        $discovery_vars['visible']        = 'show';
                        $issue_discoveries[]              =  elgg_view('forms/experiences/edit',$discovery_vars);
                    }
                }
                // Add a blank discovery card
                unset($discovery_vars);
                $discovery_vars                   = $vars;
                $discovery_vars['action']         = 'add';
                $discovery_vars['perspective']    = 'add';
                $discovery_vars['section']        = 'issue_discovery';
                $discovery_vars['guid']           = 0;
                $discovery_vars['container_guid'] = $guid;
                $issue_discoveries[]              = elgg_view('forms/experiences/edit',$discovery_vars); 
                $form_body                        = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>'discoveries','pieces'=>$issue_discoveries,'title'=>'Discoveries'] );
                
                break;
                
            /****************************************
*edit********** $section = 'issue_discovery'              *****************************************************************
             ****************************************/
            case 'issue_discovery':
                $discovery_vars = array_merge($vars,['action'=>'add']);
                $form_body      =  elgg_view('forms/experiences/edit',$discovery_vars);
            
                break;
            /****************************************
*edit********** $section = 'issue_remedies'           *****************************************************************
             * Receives the issue guid and breaks out each individual remedy 
             ****************************************/
                case 'issue_remedies':
                if(empty($carton_id))
                    $carton_id = quebx_new_id('c');
                $remedies =  elgg_get_entities(['type'=>'object','container_guid'=>$guid,'subtype'=>'remedy']);
                $remedy_count = 0;
                if($remedies){
                    foreach($remedies as $remedy){
                        unset($remedy_vars);
                        ++$remedy_count;
                        $remedy_vars            = $vars;
                        $remedy_vars['action']  = 'add';
                        $remedy_vars['perspective'] = 'save';
                        $remedy_vars['section'] = 'issue_remedy';
                        $remedy_vars['guid']    = $remedy->guid;
                        $remedy_vars['container_guid'] = $guid;
                        $remedy_vars['visible'] = 'show';
                        $issue_remedies[]       =  elgg_view('forms/experiences/edit',$remedy_vars);
                    }
                }
                // Add a blank remedy card
                unset($remedy_vars);
                $remedy_vars            = $vars;
                $remedy_vars['action']  = 'add';
                $remedy_vars['perspective'] = 'add';
                $remedy_vars['section'] = 'issue_remedy';
                $remedy_vars['guid']    = 0;
                $remedy_vars['container_guid'] = $guid;
                unset($remedy_vars['title']);
                if($remedy_count == 0)
                    $remedy_vars['visible'] = 'edit';
                $issue_remedies[]       =  elgg_view('forms/experiences/edit',$remedy_vars);
                $form_body              = elgg_view_layout('carton',['cid'=>$cid,'carton_id'=>$carton_id,'aspect'=>'remedies','pieces'=>$issue_remedies,'title'=>'Remedies'] );
                break;
                
            /****************************************
*edit********** $section = 'issue_remedy'              *****************************************************************
             ****************************************/
            case 'issue_remedy':
                $remedy_vars            = $vars;
                $remedy_vars['action']  = 'add';
                $form_body             .=  elgg_view('forms/experiences/edit',$remedy_vars);
            
                break;
            /****************************************
*edit********** $section = 'issue_resolve'                 *****************************************************************
             ****************************************/
            case 'issue_resolve':                                                             $display .= '2410 issue_resolve>$cid: '.$cid.'<br>2410 issue_resolve>$service_cid: '.$service_cid.'<br>';
               unset($form_body, $disabled, $hidden);
               $efforts  = elgg_extract('efforts', $vars);                                    //$display .= '2412 $efforts: '.print_r($efforts, true).'<br>';
               $effort   = elgg_extract('effort' , $vars);                                    $display .= '2413 $effort->guid: '.$effort->guid.'<br>';
               $disabled = 'disabled';
               $service_panel  = elgg_view('forms/experiences/edit', [
                           'action'         => $action,
                           'section'        => 'issue_effort_service',
                           'effort'         => $effort,
                           'guid'           => $guid,
                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid]);
                    
            	$delete_button = "<label class=remove-card>". 
                            	    elgg_view('output/url', array(
                            	        'title'    =>'remove effort card',
                            	        'class'    =>'remove-card',
                            	        'text'     => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$delete = elgg_view("output/span", ["class"=>"remove-card", "content"=>$delete_button]);
            	
            	$add_effort = "<button data-aid='editEffortButton' class='EffortEdit__submit___CfUzEM7s autosaves button std egg' type='button' tabindex='-1' style='margin: 3px 3px 0 15px;' data-parent-cid='$parent_cid' data-cid='$cid' data-qid='$qid'>Edit</button>";
           	
            	$url = elgg_get_site_url().'jot';
            	$marker_title         = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' style='margin: 8px;' name='jot[observation][effort][$cid][title]' placeholder='Give this effort a name'>$effort->title</textarea>";
            	$marker_date_picker   = elgg_view('input/date', ['name'  => "jot[observation][effort][$cid][moment]", 'placeholder' => $now->format('Y-m-d'), 'value' => $now->format('Y-m-d'), 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[observation][effort][$cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$marker_point_selector = elgg_view_field(['#type'=>'select',
            			                                  'name'=>"jot[observation][effort][$cid][points]",
            			                                   'options_values'=>[0=>'Unestimated',1=>'1 point',2=>'2 points',3=>'3 points']
            	                                        ]);
            	$marker_state_selector = elgg_view_field(['#type'   =>'select',
            			                                  'name'   =>"jot[observation][effort][$cid][state]",
            			                                  'options_values' =>['started'=>'Started','finished'=>'Finished','delivered'=>'Delivered','rejected'=>'Rejected','accepted'=>'Accepted']
            			                                 ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[observation][effort][$cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',]);
            	
            	break;
               /****************************************
*edit********** $section = 'issue_effort'                *****************************************************************
               ****************************************/
             case 'issue_effort':
/*//@EDIT 2019-01-02 - SAJ - Replaced with view>issue_effort
				$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
							'data-cid'=> $cid,
                            'tabindex'=> '-1',]);
				$view_id = '<<view_id>>';
            	$url = elgg_get_site_url().'jot';
            	$issue_effort = elgg_view('partials/form_elements',['element'           =>'new_effort',
		            											    'show_view_summary' => false,
		            			                                    'guid'              => $guid,
            														'parent_cid'        => $parent_cid,
		            			                                    'cid'               => $cid,
            			                                            'service_cid'       => $service_cid,
		            			                                    'qid'               => $qid,]);
            	$marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[observation][effort][$parent_cid][$cid][title]' placeholder='Give this effort a name'></textarea>";
            	$marker_date_picker = elgg_view('input/date', ['name'  => "jot[observation][effort][$parent_cid][$cid][moment]", 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[observation][effort][$parent_cid][$cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[observation][effort][$parent_cid][$cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$add_story_button = elgg_view('output/url', array(
	                                        'title'    => 'Add service effort',
	                		                'data-element' => 'new_service_effort',
            								'data-qid'     => $qid,
            								'data-cid'     => $cid,
	                    				    //'text'     => '+',
	                    					//'href'     => '#',
	                    					//'class'    => 'add-progress-marker addButton___3-z3g3BH',
	                    					'class'    => 'trigger-element add_marker',
	                                        'tabindex' => '-1' 
	                    					));
				Switch ($snippet){
					case 'marker':                                                                                             $display .= '3917 issue_effort>marker $service_cid: '.$service_cid.'<br>';
            			unset($hidden, $hidden_fields);
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
		                
// 		            	$issue_effort = elgg_view('forms/experiences/edit',['action'            => $action,
// 		            			                                            'section'           =>'issue_effort',
// 		            														'snippet'           =>'new_effort',
// 				            											    'show_view_summary' => false,
// 				            			                                    'guid'              => $guid,
// 		            														'parent_cid'        => $parent_cid,
// 				            			                                    'cid'               => $cid,
// 		            			                                            'service_cid'       => $service_cid,
// 				            			                                    'qid'               => $qid,]);
						$service_item_header_selector = elgg_view('output/span', ['content'=>
					    								elgg_view('output/url', [
									    				    'text'          => '+',
									    					'href'          => '#',
															'class'         => 'elgg-button-submit-element new-item',
															'data-element'  => 'new_service_item',
									    					'data-qid'      => $qid,
									    					'data-guid'     => $guid,
									    					'data-cid'      => $cid,
									    					'data-rows'     => 0,
									    					'data-last-row' => 0,
									    					]), 
					    								'class'=>'effort-input']);
					    $service_item_header_qty   = 'Qty';
					    $service_item_header_item  = 'Item';
					    $service_item_header_tax   = 'tax';
					    $service_item_header_cost  = 'Cost';
					    $service_item_header_total = 'Total';
					    $delete_button = elgg_view('output/url', array(
		                            	        'title'=>'remove remedy effort',
		                            	        'text' => elgg_view_icon('delete-alt'),
		                            	    ));
		            	$delete = elgg_view("output/span", ["content"=>$delete_button]);
		            	$marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[observation][effort][$parent_cid][$cid][title]' placeholder='Service name'></textarea>";
		            	$marker_description = "<textarea name='jot[observation][effort][$parent_cid][$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
		                $line_items_header= "          
						    <div class='rTable line_items service-line-items'>
								<div class='rTableBody'>
									<div id='sortable'>
										<div class='rTableRow pin'>
							                <div class='rTableCell'>$service_item_header_selector</div>
							    			<div class='rTableHead'>$service_item_header_qty</div>
							    			<div class='rTableHead'>$service_item_header_item</div>
							    			<div class='rTableHead'>$service_item_header_tax</div>
							    			<div class='rTableHead'>$service_item_header_cost</div>
							    			<div class='rTableHead'>$service_item_header_total</div>
							    		</div>
							    		<div id={$cid}_new_line_items class='new_line_items'></div>
							    	</div>
						    	</div>
						    </div>
				    		<div id={$cid}_line_item_property_cards></div>";
	            	    $issue_effort_add ="
								<div tabindex='0' class='AddSubresourceButton___S1LFUcMd' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
									 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
									 <span class='AddSubresourceButton__message___2vsNCBXi'>Add an effort</span>
								</div>";
						$issue_effort_show = "
							<div class='EffortShow_haqOwGZY TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid=$cid style='display:none' draggable='true'>
								<input type='checkbox' title='mark task complete' data-aid='toggle-complete' data-focus-id='TaskShow__checkbox--$cid' class='TaskShow__checkbox___2BQ9bNAA'>
								<div class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription' tabindex='0'>
									<span class='TaskShow__title___O4DM7q tracker_markup'></span>
									<span class='TaskShow__description___qpuz67f tracker_markup'></span>
									<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
								</div>
								<nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
									<button class='IconButton___4wjSqnXU IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
										$delete
									</button>
								</nav>
							</div>";
						$issue_effort_edit = "
	                        <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-cid=$cid>
					    		$issue_effort
							</div>";
						break;
					case 'service_item':                                                                      $display.= '3990 $cid: '.$cid.'<br>';
            			unset($hidden, $hidden_fields);
		                $hidden["jot[observation][effort][$parent_cid][$cid][$n][aspect]"] = 'service item';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						$element = 'service_item';
						$delete = elgg_view('output/url', ['title'=>'remove service item',
													       'class'=>'remove-service-item',
													       'data-element'=>$element,
														   'data-qid' => $qid_n,
													       'style'=> 'cursor:pointer',
													       'text' => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$set_properties_button = elgg_view('output/url', ['data-qboqx-dropdown'    => '#'.$qid_n,
														                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
						$form_body .= $hidden_fields;
						$form_body .="
								<div class='rTableRow $element ui-sortable-handle TaskEdit___1Xmiy6lz' data-qid=$qid_n data-guid=$guid>
									<div class='rTableCell'>$delete</div>
									<div class='rTableCell'>".elgg_view('input/hidden', ['name' => "jot[observation][effort][$qid_n][new_line]"]).
									                          elgg_view('input/number', ['name' => "jot[observation][effort][$qid_n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1'])."
                                    </div>
									<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', [
																'name'      => "jot[observation][effort][$qid_n][title]",
											                    'class'     => 'rTableform90',
									                            'id'        => 'line_item_input',
											                    'data-name' => 'title',
															])."
						            </div>
									<div class='rTableCell'>".elgg_view('input/checkbox', [
																'name'      => "jot[observation][effort][$qid_n][taxable]",
																'value'     => 1,
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'taxable',
					        			                        'default'   => false,
															])."
                                    </div>
									<div class='rTableCell'>".elgg_view('input/text', [
																'name'      => "jot[observation][effort][$qid_n][cost]",
										 						'class'     => 'last_line_item',
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'cost',
				    											'class'     => 'nString',
															])."
                                    </div>
									<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total line_total_raw'></span></div>
				                </div>";
						break;
					case 'new_effort':
						$show_view_summary = elgg_extract('show_view_summary', $vars, true);
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
							            		            		            	
						$form_body = str_replace('<<cid>>', $cid, $story_model);
						break;
					default:
						$efforts = elgg_extract('efforts', $vars);                                           $display .= '2672 $efforts # '.count($efforts).'<br>';
						if (is_array($efforts)){
							foreach($efforts as $this_effort){
							$issue_efforts .= elgg_view('forms/experiences/edit',[
														'section'    => 'issue_effort', 
														'action'     => $action, 
														'snippet'    => 'marker', 
														'parent_cid' => $parent_cid, 
														'n'          => $n, 
														'cid'        => $cid, 
														'service_cid'=> $service_cid, 
														'qid'        => $qid,
														'effort'     => $this_effort,
														'guid'       => $this_effort->guid]);
							}
						}
	                break;
				}
				//register_error($display);
*/
            	//@EDIT - copied from view>issue_edit
            	unset($issue_effort_add);
            	Switch ($snippet){
            	    case 'marker':                                                                                             $display .= '3249 issue_effort>marker $service_cid: '.$service_cid.'<br>';
            	    $effort = elgg_extract('effort', $vars);                                                               $display .= '3250 $effort->guid = '.$effort->guid.'<br>';
            	    $issue_effort = elgg_view('forms/experiences/edit',[
            	        'effort'            => $effort,
            	        'guid'              => $guid,
            	        'parent_cid'        => $parent_cid,
            	        'cid'               => $cid,
            	        'qid'               => $qid,
            	        'service_cid'       => $service_cid,
            	        'action'            => $action,
            	        'section'           =>'issue_effort',
            	        'snippet'           =>'list_efforts',]);
            	    $issue_effort_show = "
                	    <div class='EffortShow_haqOwGZY TaskShow___2LNLUMGe' data-aid='TaskShow' data-parent-cid='$parent_cid' data-cid='$cid' draggable='true'>
                    	    <div class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription' tabindex='0'>
                        	    <span class='TaskShow__title___O4DM7q tracker_markup section-edit-issue_effort-marker'>$effort->title</span>
                        	    <span class='TaskShow__description___qpuz67f tracker_markup'></span>
                        	    <span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
                    	    </div>
                	    </div>";
            	    $issue_effort_edit = "
            	       <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-parent-cid='$parent_cid' data-cid=$cid>
                	       $issue_effort
                	    </div>";
            	    break;
            	    case 'list_efforts':
            	        $effort = elgg_extract('effort', $vars);                                                             $display .= '2043 $effort->guid = '.$effort->guid.'<br>';
            	        //break;
            	    case 'view_effort':
            	        $expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'=> 'toggle_marker', 'data-cid'=>$cid, 'data-qid'=>$qid, 'tabindex'=> '-1',]);
            	        $form         = 'forms/experiences/edit';
            	        $edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
                                                	            'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                                	            'content'=> elgg_view($form,['action'=>$action, 
                                                	                                         'section'=>'issue_resolve', 
                                                	                                         'snippet'=>'marker', 
                                                	                                         'cid'=>$cid,
                                                	                                         'parent_cid'=>$parent_cid,
                                                	                                         'service_cid'=>$service_cid, 
                                                	                                         'qid'=>$qid, 
                                                	                                         'guid'=>$guid, 
                                                	                                         'effort'=>$effort])]);
            	        $story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
                                                    	          'options'=> ['parent_cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                                	              'content'=>$hidden_fields.$edit_details]);
            	        break;
            	    default:
            	         $efforts = elgg_extract('efforts', $vars);                                           $display .= '3341 $efforts # '.count($efforts).'<br>';
            	         if (is_array($efforts)){
            	             foreach($efforts as $this_effort){
            	                 $issue_efforts .= elgg_view('forms/experiences/edit',[
            	                     'section'    => 'issue_effort',
            	                     'action'     => $action,
            	                     'snippet'    => 'marker',
            	                     'parent_cid' => $parent_cid,
            	                     'n'          => $n,
            	                     'cid'        => quebx_new_cid (),//$cid,
            	                     'service_cid'=> $service_cid,
            	                     'qid'        => $qid,
            	                     'effort'     => $this_effort,
            	                     'guid'       => $this_effort->guid]);
            	             }
            	         }
            	         $issue_efforts .= elgg_view('forms/experiences/edit',[
            	             'section'    => 'issue_effort',
            	             'action'     => 'add',
            	             'snippet'    => 'marker',
            	             'parent_cid' => $parent_cid,]);
            	         break;
            	}
            	break;
				
               /****************************************
*edit********** $section = 'issue_effort_service'       *****************************************************************
               ****************************************/
             case 'issue_effort_service':
             	switch ($snippet){
             		case 'marker1':
             			$task = elgg_extract('task', $vars);
                		$service_items = elgg_get_entities(['type'=>'object',
                				                            'subtype'=>'item',
                				                            'container_guid'=>$task->guid,
                				                            'limit'=>0]);
            			unset($hidden, $hidden_fields);
		                $hidden["jot[observation][effort][$parent_cid][$cid][aspect]"] = 'service task';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						$service_item_header_selector = elgg_view('output/span', ['content'=>
					    								elgg_view('output/url', [
									    				    'text'          => '+',
									    					'href'          => '#',
															'class'         => 'elgg-button-submit-element new-item',
															'data-element'  => 'new_service_item',
									    					'data-qid'      => $qid,
									    					'data-cid'      => $cid,
					    									'data-parent-cid'=>$parent_cid,
									    					'data-rows'     => 0,
									    					'data-last-row' => 0,
									    					]), 
					    								'class'=>'effort-input']);
					    $service_item_header_qty   = 'Qty';
					    $service_item_header_item  = 'Item';
					    $service_item_header_tax   = 'tax';
					    $service_item_header_cost  = 'Cost';
					    $service_item_header_total = 'Total';
					    $delete_button = elgg_view('output/url', array(
		                            	        'title'=>'remove progress marker',
		                            	        'text' => elgg_view_icon('delete-alt'),
		                            	    ));
		            	$delete = elgg_view("output/span", ["content"=>$delete_button]);
		            	$service_marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[observation][effort][$parent_cid][$cid][title]' placeholder='Service name'>$task->title</textarea>";
		            	$service_marker_description = "<textarea name='jot[observation][effort][$parent_cid][$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'>$task->description</textarea>";
		                if($service_items){
		            		foreach($service_items as $service_item){                                                         $display .= '2806 $service_item->guid='.$service_item->guid.'<br>';
		            			$n           = $service_item->sort_order;
		            			$line_items .= elgg_view('forms/experiences/edit',[ 
														  'action'    => $action,
														  'section'   =>'issue_effort_service', 
														  'snippet'   =>'service_item',
														  'item'      => $service_item,      
														  'parent_cid'=> $parent_cid, 
														  'n'         => $n, 
														  'cid'       => $cid, 
														  'qid'       => $qid,]);
		            			$line_items_properties .= "
		            				<div id={$cid}_{$n} class='jq-dropdown jq-dropdown-tip qboqx-dropdown-relative_xxx'>
					                      <div class='qboqx-dropdown-panel'>".
			                              elgg_view('forms/market/properties', [
				    	                                   'element_type'   => 'service_item',
                			                               'container_type' => 'experience',
                			                               'action'         => $action,
			                              		           'item'           => $service_item,
					                                       'qid'            => $qid,
			                                          	   'qid_n'          => $qid_n,
			                                          	   'cid'            => $cid,
			                                          	   'parent_cid'     => $parent_cid,
			                                          	   'n'              => $n,])."
			                              </div>
			                        </div>";
		            		}
		            	}
		            	else {
		            	    $line_items .= elgg_view('forms/experiences/edit',[ 
														  'action'    => $action,
														  'section'   =>'issue_effort_service', 
														  'snippet'   =>'service_item',
														  'parent_cid'=> $parent_cid, 
														  'n'         => $n, 
														  'cid'       => $cid, 
														  'qid'       => $qid,]);
		            			$line_items_properties .= "
		            				<div id={$cid}_{$n} class='jq-dropdown jq-dropdown-tip qboqx-dropdown-relative_xxx'>
					                      <div class='qboqx-dropdown-panel'>".
			                              elgg_view('forms/market/properties', [
				    	                                   'element_type'   => 'service_item',
                			                               'container_type' => 'experience',
                			                               'action'         => $action,
			                              		           'qid'            => $qid,
			                                          	   'qid_n'          => $qid_n,
			                                          	   'cid'            => $cid,
			                                          	   'parent_cid'     => $parent_cid,
			                                          	   'n'              => $n,])."
			                              </div>
			                        </div>";
		            	}
		                $line_items_footer = "<div class='rTableRow pin'>
                				<div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div>
                				<div class='rTableCell'>Subtotal</div>
                				<div class='rTableCell'><span id={$cid}_subtotal>$subtotal</span><span id='{$cid}_subtotal_raw' class='subtotal_raw'>$transfer->subtotal</span></div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div>
                				<div class='rTableCell'>Shipping</div>
                				<div class='rTableCell'>$shipping_cost</div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div>
                				<div class='rTableCell'>Sales Tax <span class='{$cid}_sales_tax_rate'></span></div>
                				<div class='rTableCell'>$sales_tax</div>
                			</div>
                			<div class='rTableRow pin'>
                				<div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div><div class='rTableCell'></div>
                				<div class='rTableCell'>Total</div>
                				<div class='rTableCell'><span id={$cid}_total>$total</span><span id='{$cid}_total_raw' class='total_raw'></span></div>
                			</div>";
		                $line_items= "          
						    <div class='rTable line_items service-line-items'>
								<div class='rTableBody'>
									<div id='sortable'>
										<div class='rTableRow pin'>
							                <div class='rTableCell'>$service_item_header_selector</div>
							    			<div class='rTableHead'>$service_item_header_qty</div>
							    			<div class='rTableHead'>$service_item_header_item</div>
							    			<div class='rTableHead'>$service_item_header_tax</div>
							    			<div class='rTableHead'>$service_item_header_cost</div>
							    			<div class='rTableHead'>$service_item_header_total</div>
							    		</div>
										$line_items
							    		<div id={$cid}_new_line_items class='new_line_items'></div>
							    	</div>
                                    $line_items_footer
						    	</div>
						    </div>
				    		<div id={$cid}_line_item_property_cards></div>
                             $line_items_properties";
										
                        $issue_effort_service_add = "<div tabindex='0' class='AddSubresourceButton___2PetQjcb' style='display:none' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
								 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
								 <span class='AddSubresourceButton__message___2vsNCBXi'>Add a task</span>
							</div>";
		                $issue_effort_service_show = "<div class='TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid='$cid' draggable='true'>
								<input type='checkbox' title='mark task complete' data-aid='toggle-complete' data-focus-id='TaskShow__checkbox--$cid' class='TaskShow__checkbox___2BQ9bNAA'>
								<div class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription' tabindex='0'>
									<span class='TaskShow__title___O4DM7q tracker_markup section-edit-issue_effort_service-marker1'>$task->title</span>
									<span class='TaskShow__description___qpuz67f tracker_markup'>$task->description</span>
									<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
								</div>
								<nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
									<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
										$delete
									</button>
								</nav>
							</div>";
		                $issue_effort_service_edit = "
	                        <div class='TaskEdit___1Xmiy6lz' style='display:none' data-aid='TaskEdit' data-cid='$cid'>
					    		<a class='autosaves collapser-service-item' id='story_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
									<section class='TaskEdit__descriptionContainer___3NOvIiZo'>
			                            <fieldset class='name'>
			                            	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
												<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='flex-basis: 300px;'>
	                            					<div class='AutosizeTextarea__container___31scfkZp'>
	                            					    $service_marker_title
	                            					</div>
	                            				</div>
												<button data-aid='addTaskButton'  type='submit'  class='TaskEdit__submit___3m10BkLZ std egg' style='margin: 3px 3px 0 15px;order: 2;' data-cid='$cid' data-parent_cid='$parent_cid'>Add
												</button>
	                            			</div>
	                            		</fieldset>
		                                <section class='description full'>
		                                     <div data-reactroot='' data-aid='Description' class='Description___3oUx83yQ issue_effort_service-marker1'><h5>Service Description</h5>
		                                        	<div data-focus-id='ServiceShow--$cid' data-aid='renderedDescription' tabindex='0' class='ServiceShow___3-QsNMNj tracker_markup ServiceShow__placeholder___1NuiicbF'>Describe the service
		                                        	</div>
		                                        	<div class='ServiceEdit___1FO6wKeX' style='display:none'>
		                                        	    <div data-aid='editor' class='textContainer___2EcYJKlD'>
		                                        	        <div>
		                                        	        	<div class='ServiceEdit__write___207LwO1n'>
		                                        	        		<div class='AutosizeTextarea___2iWScFt6'>
		                                        	        			<div class='AutosizeTextarea__container___31scfkZp'>
		                                        	        				$service_marker_description
		                                        	        			</div>
			                                        	        		<div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt editor___1qKjhI5c tracker_markup'>
				                                        	        		<span></span>
				                                        	        		<span>w</span>
				                                        	        	</div>
				                                        	        </div>
				                                        	    </div>
				                                        	</div>
				                                      </div>
				                                  </div>
				                              </div>
			                             </section>
			                             <section class='service-items'>
			                             	<div>
			                             		<h5 style='padding:10px 0 0 10px;'>Service Items</h5>
			                             		$line_items
			                             	</div>
			                             </section>
									</section>
							</div>";
             			break;
             		case 'service_item':
             			unset($hidden, $hidden_fields);
             			$item = elgg_extract('item', $vars);
		                $hidden["jot[observation][effort][$parent_cid][$cid][$n][aspect]"] = 'service item';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						$element = 'service_item';
						$delete = elgg_view('output/url', ['title'=>'remove service item',
													       'class'=>'remove-service-item',
													       'data-element'=>$element,
														   'data-qid' => $qid_n,
													       'style'=> 'cursor:pointer',
													       'text' => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$set_properties_button = elgg_view('output/url', ['data-qboqx-dropdown' => '#'.$cid.'_'.$n,
														                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
						$service_item_row ="
								<div class='rTableRow $element ui-sortable-handle TaskEdit___1Xmiy6lz' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
									<div class='rTableCell'>$delete</div>
									<div class='rTableCell'>".elgg_view('input/hidden', ['name' => "jot[observation][effort][$parent_cid][$cid][$n][new_line]"]).
									                          elgg_view('input/number', ['name' => "jot[observation][effort][$parent_cid][$cid][$n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1', 'max'=>'0'])."</div>
									<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', [
																'name'      => "jot[observation][effort][$parent_cid][$cid][$n][title]",
									                            'value'     => $item->title,
											                    'class'     => 'rTableform90',
									                            'id'        => "line_item_input_$cid",
											                    'data-name' => 'title',
									                            'data-qboqx-dropdown' => '#'.$cid.'_'.$n,
															])."
						            </div>
									<div class='rTableCell'>".elgg_view('input/checkbox', array(
																'name'      => "jot[observation][effort][$parent_cid][$cid][$n][taxable]",
																'value'     => 1,
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'taxable',
					        			                        'default'   => false,
															))."</div>
									<div class='rTableCell'>".elgg_view('input/text', array(
																'name'      => "jot[observation][effort][$parent_cid][$cid][$n][cost]",
										 						'class'     => 'last_line_item',
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'cost',
				    											'class'     => 'nString',
															))."</div>
									<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total line_total_raw'></span></div>
				                </div>";
															break;
             		default:
             		    $effort = elgg_extract('effort', $vars);                                 //$display .= '2128 $effort->guid = '.$effort->guid.'<br>';
             		    $service_tasks = elgg_get_entities(['type'          => 'object',
                                                 		    'subtype'       => 'task',
                                                 		    'container_guid'=> $effort->guid,
                                                 		    'limit'         => 0]);
             		    if($service_tasks){
             		        foreach($service_tasks as $service_task){                        //$display .= '2134 $service_effort->guid = '.$service_effort->guid.'<br>';
             		            $add_task .= elgg_view('forms/experiences/edit',[
                             		            'action'    => $action,
                             		            'section'   =>'issue_effort_service',
                             		            'snippet'   =>'marker1',
                             		            'task'      => $service_task,
                             		            'parent_cid'=> $parent_cid,
                             		            'n'         => $n,
                             		            'cid'       => $service_cid,
                             		            'qid'       => $qid,]);
             		        }
             		    }
             		    
             		    
             		    break;
             	}
             	break;
        }
     break;
    case 'view':
        Switch ($section){
            /****************************************
*view******* $section = 'main'                      *****************************************************************
             ****************************************/
            case 'main':
            unset($form_body, $hidden, $buttons, $cid_array);
            $buttons            = "<button class='std do' data-guid='$guid' data-qid='$qid' type='submit' tabindex='-1' data-perspective='edit' data-element='popup' data-space='experience' data-presence='$presence' data-presentation='$presentation'>Edit</button>";
            
                $n                  = 0;
                $id_value           = $guid;
                $title_field        = "<span>$entity->title</span>";
                $description_field  = $entity->description;
                $moment_field       = $entity->moment;
                $expansion_objects  = elgg_get_entities(['type'=>'object', 'container_guid'=>$guid]);
                $expansion          = $expansion_objects[0]; // There must be only one expansion of an experience
//            	$buttons            = elgg_view('input/button',	['value' => elgg_echo('edit'), "class" => 'elgg-button-submit-element']);
            	$buttons            = "<button class='std do' data-guid='$guid' data-qid='$qid' type='submit' tabindex='-1' data-perspective='edit' data-element='popup' data-space='experience' data-presence='$presence' data-presentation='$presentation'>Edit</button>";
/*            	$forms_action       = 'forms/experiences/edit';
            	$view_sections[]    = ['section' => 'things'   , 'visibility' => 'display:block', 'subtypes'=>['market']                  , 'title'=>'Things'];
            	$view_sections[]    = ['section' => 'documents', 'visibility' => 'display:none' , 'subtypes'=>['file']                    , 'title'=>'Documents'];
            	$view_sections[]    = ['section' => 'gallery'  , 'visibility' => 'display:none' , 'subtypes'=>['image', 'hjalbumimage']   , 'title'=>'Gallery'];
            	$selected           = $view_sections[0]['section'];
            	foreach ($view_sections as $i=>$attachment){
            	    unset($attachments, $attachment_count);
            	    $attachments = elgg_get_entities_from_relationship(
            	       ['type'                 => 'object',
            	        'subtypes'             => $attachment['subtypes'],
            	        'relationship'         => 'experience',
            	        'relationship_guid'    => $guid,
            	        'inverse_relationship' => false,
            	        'limit'                => 0,]);
            	    $attachment_count = count($attachments);
            	    if ($attachment_count == 0) unset($view_sections[$i]);
            	    else             	       $view_sections[$i]['count'] = $attachment_count;
            	}
            	if (is_object($expansion)) {
            	    foreach ($view_sections as $i=>$attachment){
            	        $view_sections[$i]['visibility'] = 'display:none';
            	    }
            	   $view_sections[] = ['section' => $expansion->aspect, 'visibility' => 'display:block', 'subtypes'=>[$expansion->subtype], 'title'=>$expansion->aspect];
            	   $selected        = $expansion->aspect;     //replaces $selected from above
            	}                                                                                 $display .= '2939 $selected: '.$selected.'<br>';
            	$class      = "qbox-$guid";
            	$li_class   = 'qbox-q';
            	$link_class = 'qbox-q qbox-menu';
            	$ul_style   = 'border-bottom: 0px solid #cccccc';
            	$ul_aspect  = 'attachments';
            	foreach ($view_sections as $i=>$view_section) {
                    unset($qid_n, $child_cid);
                    $qid_n      = $qid.'_'.$i; 
                    $child_cid  = 'c'.mt_rand(1,999);
                    $cid_array[$i] = $child_cid;
                    $view_sections[$i]['cid'] = $child_cid;
                    $body_vars = ['container_guid'=> $guid,
                                  'cid'           => $child_cid,
                                  'parent_cid'    => $cid,
                                  'section'       => $view_section['section'],
                                  'style'         => $view_section['visibility'],
                                  'presentation'  => $presentation,
                                  'expansion'     => $expansion,
                                  'action'        => $action];
                    $preloaded_panels            .= elgg_view($forms_action, $body_vars);
                    
                    $this_tab = ['title'         => $view_section['title'],
                                'selected'       => $view_section['section'] == $selected,
                                'guid'           => $this_guid,
                                'id'             => $id,
                                'qid'            => $qid,
                                'cid'            => $child_cid,
                                'style'          => $li_style,
                                'class'          => $li_class,
                                'link_class'     => $link_class,
                                'count'          => $view_section['count'],
                                'panel'          => $panel,
                                'aspect'         => $subtype,
                                'action'         => $action,
                                'section'        => $view_section['section'],
                                'expand_tabs'    => $expand_tabs,];
                    $attachment_tabs[] = $this_tab;
            	}
            	$nav_vars['tabs']   = $attachment_tabs;
            	$nav_vars['style']  = $ul_style;
            	$nav_vars['aspect'] = $ul_aspect;
            	$nav_vars['class']  = $class;
            	$nav_vars['cid']    = $cid;
                $tabs               = elgg_view('navigation/tabs_slide', $nav_vars);
                $vars['preloaded_panels'] = $preloaded_panels;
                unset ($preloaded_panels, $expansion);
*/                // loading styles inline to raise their priority
            
                $form_body .= elgg_view('css/quebx/user_agent', ['element'=>'experience']);
            break;
            /****************************************
*view******* $section = 'things'                    *****************************************************************
             ****************************************/
            case 'things':
                unset($form_body, $hidden);
                
                $quebx_assets = elgg_get_entities_from_relationship_count(['type'                 => 'object',
	        		                                                       'subtypes'             => ['market'],
																		   'relationship'         => 'experience',
																		   'relationship_guid'    => $guid,
																		   'inverse_relationship' => false,
																		   'limit'                => 0,]);                
                if($presentation == 'qbox_experience' ||
                   $presentation == 'popup'){
                      $div_id = elgg_extract('qid', $vars);
                      $style  = elgg_extract('style', $vars, $style);}
                else                       {$div_id = 'Things_panel';}
                if ($quebx_assets > 0 ){
                	$these_things = elgg_list_entities_from_relationship(['type'                 => 'object',
        		                                                     'subtypes'             => ['market'],
																	 'relationship'         => 'experience',
																	 'relationship_guid'    => $guid,
																	 'inverse_relationship' => false,
																	 'limit'                => 0,]);
                }
                $things = elgg_view('output/div',['class'   => 'elgg-head experience-panel',
                		                          'content' => $these_things,
                		                          'options' =>['panel'      => 'Things',
                		                          		       'aspect'     => 'attachments',
                		                          		       'guid'       => $guid,
                        		                               'id'         => $div_id,
                		                                       'parent-cid' => $parent_cid,
                        		                               'data-qid'   => $div_id,
                		                                       'data-cid'   => $cid,
                		                          		       'style'      => $style]]);
                break;
            /****************************************
*view******* $section = 'documents'                 *****************************************************************
             ****************************************/
            case 'documents':
                unset($form_body, $hidden);
                if ($presentation == 'qbox'){
                	$hidden['jot[container_guid]'] = $container_guid;
	            	$hidden['jot[guid]']           = $guid;
	            	$hidden['jot[aspect]']         = 'documents';
	                $documents = elgg_get_entities_from_relationship(array(
						'type' => 'object',
						'relationship' => 'document',
						'relationship_guid' => $guid,
						'inverse_relationship' => true,
						'limit' => false,
						));
	                foreach ($documents as $document){                               //$display .= '489 ['.$key.'] => '.$image_guid.'<br>';
	                	$item_document_guids[]=$document->getGUID();
	                }
                }
            break;
            /****************************************
*view******* $section = 'gallery'                 *****************************************************************
             ****************************************/
            case 'gallery':
                unset($form_body, $hidden);
            	$hidden['jot[container_guid]'] = $container_guid;
            	$hidden['jot[guid]']           = $guid;
            	$hidden['jot[aspect]']         = 'pictures';
                $item_image_guids = $entity->images;  
                if (!is_array($item_image_guids)){$item_image_guids = array($item_image_guids);}
                foreach ($item_image_guids as $key=>$image_guid){                               //$display .= '1188 ['.$key.'] => '.$image_guid.'<br>';
                	if ($image_guid == ''){                     
                		unset($item_image_guids[$key]);
                		continue;
                	}
                	$images[] = elgg_view('market/thumbnail',['marketguid'=>$image_guid]);
                }
                $form_body = implode('',$images);
            break;
            /****************************************
*view******* $section = 'issue'                      *****************************************************************
             ****************************************/
                case 'issue':
                    unset($input_tabs, $form_body);
                    $qid             = elgg_extract('qid', $vars);
//                     for ($i = $n++; $i <= $n+4; $i++) {
//                         unset($panel);
//                         $cid = 'c'.mt_rand(1,200);
//                         switch ($i){
//                             case 1 : $panel = 'things'   ; break;
//                             case 2 : $panel = 'documents'; break;
//                             case 3 : $panel = 'gallery'  ; break;
//                             case 4 : $panel = 'issue'    ; break;
//                             default: continue;
//                         }
//                         $preload_panels [$i]= [
//                             'qid'           => $qid.'_'.$i,
//                             'cid'           => $cid,
//                             'panel'         => $panel];
//                         $cid_array[$i]      =  $cid;
//                     }
//                     $input_tabs[]    = array('title'=>'Discoveries', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'issue_discoveries', 'aspect'=>'issue_input');
// 	                $input_tabs[]    = array('title'=>'Remedies'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'issue_resolve'    , 'aspect'=>'issue_input');
// 	                $input_tabs[]    = array('title'=>'Assign'     , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'issue_assign'     , 'aspect'=>'issue_input');
// 	                $input_tabs[]    = array('title'=>'Complete'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'issue_complete'   , 'aspect'=>'issue_input');
// 	                $input_tabs      = elgg_view('navigation/tabs_slide', ['type'=>'vertical', 'aspect'=>'issue_input', 'tabs'=>$input_tabs, 'qid'=>$qid]);
// 	                $expansion       = elgg_extract('expansion', $vars);  //Received from 'main' when preloading panels
//                     $discoveries_panel = elgg_view('forms/experiences/edit', array(
//                                                    'action'         => $action,
//                                                    'section'        => 'issue_discovery',
//                                                    'guid'           => $guid,
//                     							   'qid'            => $qid,
//                     		                       'cid'            => $cid,));
//                     $resolve_panel     = elgg_view('forms/experiences/edit', array(
//                     							   'action'         => $action,
//                                                    'section'        => 'issue_resolve',
//                                                    'guid'           => $guid,
//                     							   'qid'            => $qid,
//                     							   'parent_cid'     => $parent_cid,
//                     		                       'cid'            => $cid,
//                     		                       'service_cid'    => $service_cid,
//                     		                       'efforts'        => elgg_get_entities([
// 												                           'type'           => 'object',
// 								                    		               'subtype'        => 'effort',
// 												                           'container_guid' => $expansion->guid,
// 												                           'limit'          => 0,
// 												                        ]),));
//                     $assign_panel      = elgg_view('forms/experiences/edit', array(
//                                                    'action'         => $action,
//                                                    'section'        => 'issue_request',
//                                                    'guid'           => $guid,));
//                     $complete_panel    = elgg_view('forms/experiences/edit', array(
//                                                    'action'         => $action,
//                                                    'section'        => 'issue_complete',
//                                                    'guid'           => $guid,));
                break;
                
            /****************************************
*view********** $section = 'issue_discovery'              *****************************************************************
             ****************************************/
            case 'issue_discovery':
				if ($presentation == 'qbox'){
            		$form_body .= '$presentation: '.$presentation;
//            		break;
            	}
                unset($form_body, $hidden, $hidden_fields, $disabled, $id_value);
                $set_properties_button = elgg_view('output/url', array(
                           'id'    => 'properties_set',
                           'text'  => elgg_view_icon('settings-alt'), 
                           'title' => 'set properties',
                           'style' => 'cursor:pointer;',
                ));
            	$delete_button = "<label class=remove-progress-marker>". 
                            	    elgg_view('output/url', array(
                            	        'title'=>'remove progress marker',
                            	        'class'=>'remove-progress-marker',
                            	        'text' => elgg_view_icon('delete-alt'),
                            	    	'data-qid' => $qid,
                            	    )).
                            	 "</label>";
            	$delete = elgg_view("output/span", array("class"=>"remove-progress-marker", "content"=>$delete_button));
            	$url = elgg_get_site_url().'jot';
            	$marker_title         = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' style='padding-top: 0;margin: 8px;' name='jot[observation][discovery][$cid][title]' placeholder='Give this discovery a name'></textarea>";
            	$marker_date_picker   = elgg_view('input/date', ['name'  => "jot[observation][discovery][$cid][moment]", 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[observation][discovery][$cid][type]",  
            			                                 'options_values' =>['quip'=>'Quip', 'milestone'=>'Milestone', 'check_in'=>'Check in']
            	                                         ]);
            	$owner                = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	Switch($snippet){
            		case 'marker':
            			break;
            		default:
            			$panel_header   = elgg_view('output/header', ['class'=>'tn-PanelHeader___c0XQCVI7 tn-PanelHeader--single___2ns28dRL pin',
	            	                                                  'options' => ['style'   =>'background-color: #dddddd; color: #000000',
	            	                                                                'data-bb'  =>'PanelHeader',
	            	                                                                'data-aid' =>'PanelHeader'],
	            	                                                  'content' => elgg_view('output/div',['class'=>'tn-PanelHeader__inner___3Nt0t86w tn-PanelHeader__inner--single___3Nq8VXGB',
	            	                                                  		                               'options'=>['data-aid'=>'highlight',
	            	                                                  		                                           'style'=>'border-top-color: #c0c0c0'],
	            	                                                   		                               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__titleArea___1DRH-oDF',
	            	                                                   		                                            		               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__name___2UfJ8ho9',
	            	                                                   		                                            		                  		                           'options'=>['data-aid'=>'PanelHeader__name'],
	            	                                                   		                                            		                  		                           'content'=>'Discoveries'])])])]);
		            	break;
            	}
                break;
            /****************************************
*view********** $section = 'issue_resolve'                 *****************************************************************
             ****************************************/
            case 'issue_resolve':                                                             $display .= '1949 issue_resolve>$cid: '.$cid.'<br>1949 issue_resolve>$service_cid: '.$service_cid.'<br>';
               unset($form_body, $disabled, $hidden,$delete);
               $efforts  = elgg_extract('efforts', $vars);                                    //$display .= '1951 $efforts: '.print_r($efforts, true).'<br>';
               $effort   = elgg_extract('effort' , $vars);                                    $display .= '1952 $effort->guid: '.$effort->guid.'<br>';
               $service_panel  = elgg_view('forms/experiences/edit', array(
                           'action'         => $action,
                           'section'        => 'issue_effort_service',
               		       'effort'         => $effort,
                           'guid'           => $guid,
                    	   'qid'            => $qid,
               		       'parent_cid'     => $cid,
               		       'n'              => $n,
                    	   'service_cid'    => $service_cid));
                $url = elgg_get_site_url().'jot';
            	$marker_title         = "<span class='AutosizeTextarea__textarea___1LL2IPEy2 NameView___rHG8dOIP' style='margin: 8px;'>$effort->title</span>";
            	$marker_date_picker   = $effort->moment;
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[observation][effort][$cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$marker_point_selector = elgg_view_field(['#type'=>'select',
            			                                  'name'=>"jot[observation][effort][$cid][points]",
            			                                   'options_values'=>[0=>'Unestimated',1=>'1 point',2=>'2 points',3=>'3 points']
            	                                        ]);
            	$marker_state_selector = elgg_view_field(['#type'   =>'select',
            			                                  'name'   =>"jot[observation][effort][$cid][state]",
            			                                  'options_values' =>['started'=>'Started','finished'=>'Finished','delivered'=>'Delivered','rejected'=>'Rejected','accepted'=>'Accepted']
            			                                 ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[observation][effort][$cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
                            'tabindex'=> '-1',]);
            	
/*               Switch($snippet){
            		case 'marker':
            			$id_value = $effort->guid;
            			$disabled_view = 'disabled';
            			break;
            		default:
            			$panel_header   = elgg_view('output/header', ['class'=>'tn-PanelHeader___c0XQCVI7 tn-PanelHeader--single___2ns28dRL pin',
	            	                                                  'options' => ['style'   =>'background-color: #dddddd; color: #000000',
	            	                                                                'data-bb'  =>'PanelHeader',
	            	                                                                'data-aid' =>'PanelHeader'],
	            	                                                  'content' => elgg_view('output/div',['class'=>'tn-PanelHeader__inner___3Nt0t86w tn-PanelHeader__inner--single___3Nq8VXGB',
	            	                                                  		                               'options'=>['data-aid'=>'highlight',
	            	                                                  		                                           'style'=>'border-top-color: #c0c0c0'],
	            	                                                   		                               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__titleArea___1DRH-oDF',
	            	                                                   		                                            		               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__name___2UfJ8ho9',
	            	                                                   		                                            		                  		                           'options'=>['data-aid'=>'PanelHeader__name'],
	            	                                                   		                                            		                  		                           'content'=>'Remedies'])])])]);
		            	break;
            	}                                                                            //register_error($display);
*/                break;
                
               /****************************************
*view********** $section = 'issue_effort'                *****************************************************************
               ****************************************/
             case 'issue_effort':
                 unset($issue_effort_add);
                Switch ($snippet){
					case 'marker':                                                                                             $display .= '3249 issue_effort>marker $service_cid: '.$service_cid.'<br>';
						$effort = elgg_extract('effort', $vars);                                                               $display .= '3250 $effort->guid = '.$effort->guid.'<br>';
            			$issue_effort = elgg_view('forms/experiences/edit',['effort'            => $effort,
            					                                            'guid'              => $guid,
		            														'parent_cid'        => $parent_cid,
				            			                                    'cid'               => $cid,
		            			                                            'qid'               => $qid,
            																'service_cid'       => $service_cid,
				            											    'action'            => $action,
				            			                                    'section'           =>'issue_effort',
            					                                            'snippet'           =>'list_efforts',]);
            			$issue_effort_show = "
							<div class='EffortShow_haqOwGZY TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid=$cid draggable='true'>
								<div class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription' tabindex='0'>
									<span class='TaskShow__title___O4DM7q tracker_markup section-view-issue_effort-marker'>$effort->title</span>
									<span class='TaskShow__description___qpuz67f tracker_markup'></span>
									<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
								</div>
							</div>";
						$issue_effort_edit = "
	                        <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-parent-cid='$parent_cid' data-cid=$cid>
					    		$issue_effort
					    	</div>";                                                                                         //register_error($display);
						break;
					case 'list_efforts':
						$effort = elgg_extract('effort', $vars);                                                             $display .= '2043 $effort->guid = '.$effort->guid.'<br>';
            			
					case 'view_effort':
					    $expander     = elgg_view("output/url",  ['text'   => '','class'   => 'expander undraggable','id'=> 'toggle_marker', 'data-parent-cid'=>$parent_cid, 'data-cid'=>$cid, 'data-qid'=>$qid, 'tabindex'=> '-1',]);
						$form         = 'forms/experiences/edit';
						$edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
                                    						    'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
                                    						    'content'=> elgg_view($form,['action'=>$action, 'section'=>'issue_resolve', 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid, 'service_cid'=>$service_cid, 'qid'=>$qid, 'guid'=>$guid, 'effort'=>$effort])]);
						$story_model .= elgg_view('output/div',  ['class' =>'story model item pin',
                                        						  'options'=> ['data-parent-cid'=>$parent_cid,'data-cid'=>$cid, 'data-qid'=>$qid],
						                                          'content'=>$hidden_fields.$edit_details]);                 //register_error($display);
						break;                                                                              
										
/*					case 'service_item':                                                                      $display.= '3990 $cid: '.$cid.'<br>';
            			$element = 'service_item';
						$delete = elgg_view('output/url', ['title'=>'remove service item',
													       'class'=>'remove-service-item',
													       'data-element'=>$element,
														   'data-qid' => $qid_n,
													       'style'=> 'cursor:pointer',
													       'text' => elgg_view_icon('delete-alt'),]);
						$action_button = elgg_view('input/button', ['class'      => 'IconButton___2y4Scyq6 IconButton--small___3D375vVd',
								                                    'data-aid'   => 'delete',
								                                    'aria-label' => 'Delete',
								                                    'data-cid'   => $cid]);
						$set_properties_button = elgg_view('output/url', ['data-qboqx-dropdown'    => '#'.$qid_n,
														                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'set properties',
						]);
						$form_body .="
								<div class='rTableRow $element ui-sortable-handle TaskEdit___1Xmiy6lz view-issue_effort-service_item' data-qid=$qid_n data-guid=$guid>
									<div class='rTableCell'>$delete</div>
									<div class='rTableCell'>".elgg_view('input/hidden', ['name' => "jot[observation][effort][$qid_n][new_line]"]).
									                          elgg_view('input/number', ['name' => "jot[observation][effort][$qid_n][qty]",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>'1'])."</div>
									<div class='rTableCell'>$set_properties_button ".elgg_view('input/text', array(
																'name'      => "jot[observation][effort][$qid_n][title]",
											                    'class'     => 'rTableform90',
									                            'id'        => 'line_item_input',
											                    'data-name' => 'title',
															))."
						            </div>
									<div class='rTableCell'>".elgg_view('input/checkbox', array(
																'name'      => "jot[observation][effort][$qid_n][taxable]",
																'value'     => 1,
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'taxable',
					        			                        'default'   => false,
															))."</div>
									<div class='rTableCell'>".elgg_view('input/text', array(
																'name'      => "jot[observation][effort][$qid_n][cost]",
										 						'class'     => 'last_line_item',
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'cost',
				    											'class'     => 'nString',
															))."</div>
									<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total line_total_raw'></span></div>
				                </div>";
						break;
*/					default:
						$efforts = elgg_extract('efforts', $vars);                                           $display .= '3341 $efforts # '.count($efforts).'<br>';
						if (is_array($efforts)){
							foreach($efforts as $this_effort){
							$issue_efforts .= elgg_view('forms/experiences/edit',['section'    => 'issue_effort', 
							                                                      'action'     => $action, 
							                                                      'snippet'    => 'marker', 
							                                                      'parent_cid' => $parent_cid, 
							                                                      'n'          => $n, 
							                                                      'cid'        => $cid, 
							                                                      'service_cid'=> $service_cid, 
							                                                      'qid'        => $qid,
																				  'effort'     => $this_effort,
							                                                      'guid'       => $this_effort->guid]);
							}
						}
	                break;
				}
				//register_error($display);
                break;
                
               /****************************************
*view********** $section = 'issue_effort_service'       *****************************************************************
               ****************************************/
             case 'issue_effort_service':
                switch ($snippet){
                	case 'marker1':
                		$task = elgg_extract('task', $vars);
                		$service_items = elgg_get_entities(['type'=>'object',
                				                            'subtype'=>'item',
                				                            'container_guid'=>$task->guid,
                				                            'limit'=>0]);
            			unset($hidden, $hidden_fields);
		                $service_item_header_qty   = 'Qty';
					    $service_item_header_item  = 'Item';
					    $service_item_header_tax   = 'tax';
					    $service_item_header_cost  = 'Cost';
					    $service_item_header_total = 'Total';
					    $service_marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2' style='padding-top: 0px;margin: 8px;' name='title' placeholder='Service name' disabled>$task->title</textarea>";
		            	$service_marker_description = "<textarea name='description' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' placeholder='(No service description)' disabled>$task->description</textarea>";
		            	if($service_items){
		            		foreach($service_items as $service_item){                                                         $display .= '2325 $service_item->guid='.$service_item->guid.'<br>';
		            			$n           = $service_item->sort_order;
		            			$line_items .= elgg_view('forms/experiences/edit',[ 
														  'action'    => $action,
														  'section'   =>'issue_effort_service', 
														  'snippet'   =>'service_item',
														  'item'      => $service_item,      
														  'parent_cid'=> $parent_cid, 
														  'n'         => $n, 
														  'cid'       => $cid, 
														  'qid'       => $qid,]);
		            			$line_items_properties .= "
		            				<div id={$cid}_{$n} class='qboqx-dropdown qboqx-dropdown-tip qboqx-dropdown-relative'>
					                      <div class='qboqx-dropdown-panel'>".
			                              elgg_view('forms/market/properties', [
				    	                                   'element_type'   =>'service_item',
				    	                                   'container_type' => 'experience',
			                                               'action'         => $action,
			                              		           'item'           => $service_item,
					                                       'qid'            => $qid,
			                                          	   'qid_n'          => $qid_n,
			                                          	   'cid'            => $cid,
			                                          	   'parent_cid'     => $parent_cid,
			                                          	   'n'              => $n,])."
			                              </div>
			                        </div>";
		            		}
		            	}
		                $line_items_header= "          
						    <div class='rTable line_items service-line-items'>
								<div class='rTableBody'>
									<div class='rTableRow pin'>
						                <div class='rTableCell'></div>
						    			<div class='rTableHead'>$service_item_header_qty</div>
						    			<div class='rTableHead'>$service_item_header_item</div>
						    			<div class='rTableHead'>$service_item_header_tax</div>
						    			<div class='rTableHead'>$service_item_header_cost</div>
						    			<div class='rTableHead'>$service_item_header_total</div>
						    		</div>
						    		$line_items
						    	</div>
						    </div>
				    		<div id={$cid}_line_item_property_cards></div>
                             $line_items_properties";
		                $issue_effort_service_show = "<div class='TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid='$cid'>
								<div class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription' tabindex='0'>
									<span class='TaskShow__title___O4DM7q tracker_markup section-view-issue_effort_service-marker1'>$task->title</span>
									<span class='TaskShow__description___qpuz67f tracker_markup'>$task->description</span>
									<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
								</div>
							</div>";
		                $issue_effort_service_edit = "
	                        <div class='TaskEdit___1Xmiy6lz' style='display:none' data-aid='TaskEdit' data-cid='$cid'>
					    		<a class='autosaves collapser-service-item' id='story_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
									<section class='TaskEdit__descriptionContainer___3NOvIiZo'>
			                            <fieldset class='name'>
			                            	<div class='AutosizeTextarea___2iWScFt6' style='display: flex;'>
												<div data-reactroot='' class='AutosizeTextarea___maAHRClx'>
	                            					<div class='AutosizeTextarea__container___70lNwu5L'>
	                            					    $service_marker_title
	                            					</div>
	                            				</div>
	                            			</div>
	                            		</fieldset>
		                                <section class='description full'>
		                                     <div data-reactroot='' data-aid='Description' class='issue_effort_service-marker1'><h5>Service Description</h5>
		                                        	<div class='ServiceEdit___1FO6wKeX'>
		                                        	    <div data-aid='editor' class='textContainer___2EcYJKlD'>
	                                        	        	<div class='ServiceEdit__write___207LwO1n'>
	                                        	        		<div class='AutosizeTextarea___2iWScFt6'>
	                                        	        			<div class='AutosizeTextarea__container___70lNwu5L'>
	                                        	        				$service_marker_description
	                                        	        			</div>
			                                        	        </div>
			                                        	    </div>
			                                        	</div>
				                                  </div>
				                              </div>
			                             </section>
			                             <section class='service-items'>
			                             	<div>
			                             		<h5 style='padding:10px 0 0 10px;'>Service Items</h5>
			                             		$line_items_header
			                             	</div>
			                             </section>
									</section>
							</div>";                                                                                               //register_error($display);
                		break;
             		case 'service_item':
             			unset($hidden, $hidden_fields);
             			$item    = elgg_extract('item', $vars);
		                $element = 'service_item';
						$show_properties_button = elgg_view('output/url', ['data-qboqx-dropdown' => '#'.$cid.'_'.$n,
														                  'data-qid'            => $qid_n,
														                  'data-horizontal-offset'=>"-15",
												                          'text'                => elgg_view_icon('settings-alt'), 
												                          'title'               => 'properties',
						]);
						$taxable_checkbox_options = ['name'      => "taxable",
													'value'     => 1,
								                    'data-qid'  => $qid_n,
								                    'data-name' => 'taxable',
						                            'disabled'  => 'disabled',
		        			                        'default'   => false,];
						if ($item->taxable == 1){
							$taxable_checkbox_options['checked']='checked';
						}
						$service_item_row ="
								<div class='rTableRow $element TaskEdit___1Xmiy6lz view-issue_effort_service-service_item' data-qid='$qid_n' data-cid='$cid' data-parent-cid='$parent_cid' data-row-id='$n'>
									<div class='rTableCell'></div>
									<div class='rTableCell'>".elgg_view('input/number', ['name' => "qty",'data-qid'=>$qid_n, 'data-name'=>'qty', 'value'=>$item->qty, 'disabled'=>''])."</div>
									<div class='rTableCell'>$show_properties_button ".elgg_view('input/text', array(
																'name'      => "title",
											                    'class'     => 'rTableform90',
									                            'id'        => 'line_item_input',
											                    'data-name' => 'title',
											                    'value'     => $item->title,
											                    'disabled'  => '',
															))."
						            </div>
									<div class='rTableCell'>".elgg_view('input/checkbox', $taxable_checkbox_options)."</div>
									<div class='rTableCell'>".elgg_view('input/text', array(
																'name'      => "cost",
										 						'class'     => 'last_line_item',
											                    'data-qid'  => $qid_n,
											                    'data-name' => 'cost',
				    											'class'     => 'nString',
											                    'value'     => $item->cost,
											                    'disabled'  => ''
															))."</div>
									<div class='rTableCell'><span id='{$qid_n}_line_total'></span><span class='{$qid_n}_line_total line_total_raw'></span></div>
				                </div>";
             			break;
                	default: 
		                $effort = elgg_extract('effort', $vars);                                 //$display .= '2128 $effort->guid = '.$effort->guid.'<br>';
				        $service_tasks = elgg_get_entities(['type'          => 'object',
						                                    'subtype'       => 'task',
						                                    'container_guid'=> $effort->guid,
						                                    'limit'         => 0]);
						if($service_tasks){
							foreach($service_tasks as $service_task){                        //$display .= '2134 $service_effort->guid = '.$service_effort->guid.'<br>';
								$add_task .= elgg_view('forms/experiences/edit',[ 
											  'action'    => $action,
											  'section'   =>'issue_effort_service', 
											  'snippet'   =>'marker1',
											  'task'      => $service_task,      
											  'parent_cid'=> $parent_cid, 
											  'n'         => $n, 
											  'cid'       => $service_cid, 
											  'qid'       => $qid,]);
							}
						}
										
		                
                		break;
                }
                break;
    break;
        }
}

/**************************************
 * Generalized Sections
 */
generalize:

Switch ($section){
            case 'main_xxx':
                unset($content, $view_sections);                                                                                    $display .= '3679 $section = '.$section.'<br>';
//                $form_body = $css.$buttons;
                Switch($presentation){
                    case 'full':    $style = 'width:100%';  break;
                    case 'compact': $style = 'width:550px'; break;
                    case 'qbox':                            break;
                    case 'inline':  $style = 'width:97%';   break;
                    default:        $style = 'width:550px'; break;
                }
                $url = elgg_get_site_url().'jot';
//                $expansion_objects  = elgg_get_entities(['type'=>'object', 'container_guid'=>$guid]);
            	$forms_action       = 'forms/experiences/edit';
            	$view_sections[]    = ['section' => 'things'   , 'visibility' => 'display:block', 'subtypes'=>['market']                  , 'title'=>'Things'];
            	$view_sections[]    = ['section' => 'documents', 'visibility' => 'display:none' , 'subtypes'=>['file']                    , 'title'=>'Documents'];
            	$view_sections[]    = ['section' => 'gallery'  , 'visibility' => 'display:none' , 'subtypes'=>['image', 'hjalbumimage']   , 'title'=>'Gallery'];
            	$selected           = $view_sections[0]['section'];
            	foreach ($view_sections as $i=>$attachment){
            	    unset($attachments, $attachment_count);
            	    $attachments = elgg_get_entities_from_relationship(
            	       ['type'                 => 'object',
            	        'subtypes'             => $attachment['subtypes'],
            	        'relationship'         => 'experience',
            	        'relationship_guid'    => $guid,
            	        'inverse_relationship' => false,
            	        'limit'                => 0,]);
            	    $attachment_count = count($attachments);
            	    if ($attachment_count == 0 && $action == 'view') unset($view_sections[$i]);
            	    else             	       $view_sections[$i]['count'] = $attachment_count;
            	}
            	if (is_object($expansion_objects[0])) {
            	    foreach ($view_sections as $i=>$attachment){
            	        $view_sections[$i]['visibility'] = 'display:none';
            	    }
            	   $expansion       = $expansion_objects[0]; // There must be only one expansion of an experience
            	   $view_sections[] = ['section' => $expansion->aspect, 'visibility' => 'display:block', 'subtypes'=>[$expansion->subtype], 'title'=>$expansion->aspect];
            	   $selected        = $expansion->aspect;     //replaces $selected from above
            	}
            	else {//Retain $expansion as defined in the action section above
            	    $view_sections[] = $expansion; 
            	}                                                                                 $display .= '3725 $selected: '.$selected.'<br>3725 count($view_sections): '.count($view_sections).'<br>';
            	$class      = "qbox-$guid";
            	$li_class   = 'qbox-q';
            	$link_class = 'qbox-q qbox-menu';
            	$ul_style   = 'border-bottom: 0px solid #cccccc';
            	$ul_aspect  = 'attachments';
            	foreach ($view_sections as $i=>$view_section) {
                    unset($qid_n, $child_cid);
                    $qid_n      = $qid.'_'.$i; 
                    $child_cid  = quebx_new_id ('c');
                    $cid_array[$i] = $child_cid;
                    $view_sections[$i]['cid'] = $child_cid;
                    $body_vars = ['container_guid'=> $guid,
                                  'cid'           => $child_cid,
                                  'parent_cid'    => $cid,
                                  'qid'           => $qid,
                                  'section'       => $view_section['section'],
                                  'visibility'    => $view_section['visibility'],
                                  'presentation'  => $presentation,
                                  'presence'      => $presence,
                                  'expansion'     => $expansion,
                                  'action'        => $action,
                                  'perspective'   => $perspective,
                    ];
                    $preloaded_panels            .= elgg_view($forms_action, $body_vars);
                    
                    $this_tab = ['title'         => $view_section['title'],
                                'selected'       => $view_section['section'] == $selected,
                                'guid'           => $this_guid,
                                'id'             => $id,
                                'qid'            => $qid,
                                'cid'            => $child_cid,
                                'style'          => $li_style,
                                'class'          => $li_class,
                                'link_class'     => $link_class,
                                'count'          => $view_section['count'],
                                'panel'          => $panel,
                                'aspect'         => $subtype,
                                'action'         => $action,
                                'section'        => $view_section['section'],
                                'expand_tabs'    => $expand_tabs,];
                    $attachment_tabs[] = $this_tab;
            	}
            	$nav_vars['tabs']   = $attachment_tabs;
            	$nav_vars['style']  = $ul_style;
            	$nav_vars['aspect'] = $ul_aspect;
            	$nav_vars['class']  = $class;
            	$nav_vars['cid']    = $cid;
                $tabs               = elgg_view('navigation/tabs_slide', $nav_vars);
                unset ($expansion);
               	$preload_panels   = elgg_extract('preload_panels', $vars);
                	if ($preload_panels){
				    	$forms_action =  'forms/experiences/edit';
                		foreach($preload_panels as $key=>$panel){                                         $display .= '3782 $preloading panel '.$panel['panel'].'<br>';
                		    $cid_array[] = $panel->cid;
                			$body_vars = ['container_guid'=> $guid,
	                				      'expansion'     => $expansion,
									      'qid'           => $panel->qid,
                			              'cid'           => $panel->cid,
					                      'section'       => $panel->panel,
									      'selected'      => true,
									      'style'         => 'display:none',
									      'presentation'  => $presentation,
                			              'presence'      => $presence,
									      'action'        => $action,
                			              'perspective'   => $perspective,
                			]; 
                			if ($action == 'edit' || $action == 'view'){$body_vars['container_guid']=$guid;}
                			if ($action == 'add')                      {$body_vars['cid'] = $cid;}
	                		$preloaded_panels             .= elgg_view($forms_action, $body_vars);
                		}
                	}
                	
                	$tab_vars  =   ['subtype'        => 'experience',
                            	    'this_section'   => 'issue',
                            	    'state'          => 'selected',
                            	    'action'         => $action,
                            	    'guid'           => $guid,
                            	    'qid'            => $qid_n,
                	                'cid_array'      => $cid_array,
                            	    'presentation'   => 'qbox',
                            	    'class'          => "qbox-$guid",
                            	    'ul_style'       => 'border-bottom: 0px solid #cccccc',
                            	    'ul_aspect'      => 'attachments',
                            	    'link_class'     => 'qbox-q qbox-menu',
                            	    'attachments'    => ['things'=>1]];
                	if (!$tabs){  // tabs have not been created in the specific section above
                	   $tabs      =  elgg_view('quebx/menu', $tab_vars);
                	}
            	if ($disabled      == 'disabled'){$disabled_label      = ' (disabled)';}
            	if ($disabled_view == 'disabled'){$disabled_view_label = ' (disabled)';}
                	$qbox_content = "<div class='qbox-details' data-qid='$qid' data-cid='$cid'>$preloaded_panels</div>";
                $content = "
				<div class='model_xxx'>
                	<section class='edit' style='position:relative;' data-aid='StoryDetailsEdit' tabindex='-1' action = '$action'>
						<div class='rTable'>
		        			<div class='rTableBody'>
		            			<div class='rTableRow'>
		        					<div class='rTableCell AutosizeTextarea__container___31scfkZp'>$description_field</div>
		        				</div>
		        				<div class='rTableRow'>
		            				<div class='rTableCell'>{$tabs}{$expand_tabs}{$qbox_content}</div>
		            			</div>
		            		</div>
		        		</div>
		        	</section>
		        </div>";
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $content .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body = $content;
                break;
/****************************************
 $section = 'main'                     *****************************************************************
****************************************/
            case 'main':
            	unset($content);                                                                                    $display .= '3679 $section = '.$section.'<br>';
                $form_body .= $buttons;
                Switch($presentation){
                    case 'full':    $style = 'width:100%';  break;
                    case 'compact': $style = 'width:550px'; break;
                    case 'qbox':                            break;
                    case 'inline':  $style = 'width:97%';   break;
                    default:        $style = 'width:550px'; break;
                }
                $url = elgg_get_site_url().'jot';
/***/				
// 				$n                  = 0;
//                 $id_value           = $guid;
//                 $title_field        = "<span>$entity->title</span>";
//                 $description_field  = $entity->description;
//                 $moment_field       = $entity->moment;
                 $expansion_objects  = elgg_get_entities(['type'=>'object', 'container_guid'=>$guid]);
                 //            	$buttons            = elgg_view('input/button',	['value' => elgg_echo('edit'), "class" => 'elgg-button-submit-element']);
            	$forms_action       = 'forms/experiences/edit';
            	$view_sections[]    = ['section' => 'things'   , 'visibility' => 'display:block', 'subtypes'=>['market']                  , 'title'=>'Things'];
            	$view_sections[]    = ['section' => 'documents', 'visibility' => 'display:none' , 'subtypes'=>['file']                    , 'title'=>'Documents'];
            	$view_sections[]    = ['section' => 'gallery'  , 'visibility' => 'display:none' , 'subtypes'=>['image', 'hjalbumimage']   , 'title'=>'Gallery'];
            	$selected           = $view_sections[0]['section'];
            	foreach ($view_sections as $i=>$attachment){
            	    unset($attachments, $attachment_count);
            	    $attachments = elgg_get_entities_from_relationship(
            	       ['type'                 => 'object',
            	        'subtypes'             => $attachment['subtypes'],
            	        'relationship'         => 'experience',
            	        'relationship_guid'    => $guid,
            	        'inverse_relationship' => false,
            	        'limit'                => 0,]);
            	    $attachment_count = count($attachments);
            	    if ($attachment_count == 0 && $action == 'view') unset($view_sections[$i]);
            	    else             	       $view_sections[$i]['count'] = $attachment_count;
            	}
            	if (is_object($expansion_objects[0])) {
            	    foreach ($view_sections as $i=>$attachment){
            	        $view_sections[$i]['visibility'] = 'display:none';
            	    }
            	   $expansion       = $expansion_objects[0]; // There must be only one expansion of an experience
            	   $view_sections[] = ['section' => $expansion->aspect, 'visibility' => 'display:block', 'subtypes'=>[$expansion->subtype], 'title'=>$expansion->aspect];
            	   $selected        = $expansion->aspect;     //replaces $selected from above
            	}
            	else {//Retain $expansion as defined in the action section above
            	    $view_sections[] = $expansion; 
            	}                                                                                 $display .= '3725 $selected: '.$selected.'<br>3725 count($view_sections): '.count($view_sections).'<br>';
            	$class      = "qbox-$guid";
            	$li_class   = 'qbox-q';
            	$link_class = 'qbox-q qbox-menu';
            	$ul_style   = 'border-bottom: 0px solid #cccccc';
            	$ul_aspect  = 'attachments';
            	foreach ($view_sections as $i=>$view_section) {
                    unset($qid_n, $child_cid);
                    $qid_n      = $qid.'_'.$i; 
                    $child_cid  = quebx_new_cid ();//c'.mt_rand(1,999);
                    $cid_array[$i] = $child_cid;
                    $view_sections[$i]['cid'] = $child_cid;
                    $body_vars = ['container_guid'=> $guid,
                                  'cid'           => $child_cid,
                                  'parent_cid'    => $cid,
                                  'qid'           => $qid,
                                  'section'       => $view_section['section'],
                                  'visibility'    => $view_section['visibility'],
//                                  'presentation'  => $presentation,
                                  'presence'      => $presence,
                                  'expansion'     => $expansion,
                                  'action'        => $action,
                                  'perspective'   => $perspective,
                    ];
                    $preloaded_panels            .= elgg_view($forms_action, $body_vars);
                    
                    $this_tab = ['title'         => $view_section['title'],
                                'selected'       => $view_section['section'] == $selected,
                                'guid'           => $this_guid,
                                'id'             => $id,
                                'qid'            => $qid,
                                'cid'            => $child_cid,
                                'style'          => $li_style,
                                'class'          => $li_class,
                                'link_class'     => $link_class,
                                'count'          => $view_section['count'],
                                'panel'          => $panel,
                                'aspect'         => $subtype,
                                'action'         => $action,
                                'section'        => $view_section['section'],
                                'expand_tabs'    => $expand_tabs,];
                    $attachment_tabs[] = $this_tab;
            	}
            	$nav_vars['tabs']   = $attachment_tabs;
            	$nav_vars['style']  = $ul_style;
            	$nav_vars['aspect'] = $ul_aspect;
            	$nav_vars['class']  = $class;
            	$nav_vars['cid']    = $cid;
                $tabs               = elgg_view('navigation/tabs_slide', $nav_vars);
//                $vars['preloaded_panels'] = $preloaded_panels;
                unset ($expansion);
/***/
            	//if ($presentation == 'qbox' || $presentation == 'inline' || $presentation == 'lightbox'){
//                $preloaded_panels = elgg_extract('preloaded_panels', $vars);
               	$preload_panels   = elgg_extract('preload_panels', $vars);
//                	$preload_panels   = $preload_panels ?: elgg_extract('preload_panels', $vars);
/*                	if ($expansion) {
	                	$preload_panels[] = ['panel'=>$expansion->aspect,
	                	                     'qid'  => $qid.'_4',
	                	                     'cid' => 'c'.mt_rand(1,999)];
	                }
*/                	if ($preload_panels){
				    	$forms_action =  'forms/experiences/edit';
                		foreach($preload_panels as $key=>$panel){                                         $display .= '3782 $preloading panel '.$panel['panel'].'<br>';
                		    $cid_array[] = $panel->cid;
                			$body_vars = ['container_guid'=> $guid,
	                				      'expansion'     => $expansion,
									      'qid'           => $panel->qid,
                			              'cid'           => $panel->cid,
					                      'section'       => $panel->panel,
									      'selected'      => true,
									      'style'         => 'display:none',
									      'presentation'  => $presentation,
                			              'presence'      => $presence,
									      'action'        => $action,
                			              'perspective'   => $perspective,
                			]; 
                			if ($action == 'edit' || $action == 'view'){$body_vars['container_guid']=$guid;}
                			if ($action == 'add')                      {$body_vars['cid'] = $cid;}
	                		$preloaded_panels             .= elgg_view($forms_action, $body_vars);
                		}
                	}
                	
                	$tab_vars  =   ['subtype'        => 'experience',
                            	    'this_section'   => 'issue',
                            	    'state'          => 'selected',
                            	    'action'         => $action,
                            	    'guid'           => $guid,
                            	    'qid'            => $qid_n,
                	                'cid_array'      => $cid_array,
                            	    'presentation'   => 'qbox',
                            	    'class'          => "qbox-$guid",
                            	    'ul_style'       => 'border-bottom: 0px solid #cccccc',
                            	    'ul_aspect'      => 'attachments',
                            	    'link_class'     => 'qbox-q qbox-menu',
                            	    'attachments'    => ['things'=>1]];
                	if (!$tabs){  // tabs have not been created in the specific section above
                	   $tabs      =  elgg_view('quebx/menu', $tab_vars);
                	}
                	/*
                	for ($i = $n++; $i <= $n+4; $i++) {
                	    switch ($i){
                	        case 1 : $section = 'things'   ; break;
                	        case 2 : $section = 'documents'; break;
                	        case 3 : $section = 'gallery'  ; break;
                	        case 4 : $section = 'issue'    ; break;
                	        default: continue;
                	    }
                	    $preloaded_panels .= elgg_view($forms_action, [
                	        'guid'          => $guid,
                	        'container_guid'=> $guid,
                	        'qid'           => $qid.'_'.$i,
                	        'section'       => $section,
                	        'style'         => 'display:none;',
                	        'presentation'  => $presentation,
                	        'action'        => $perspective]);
                	}*/
            	if ($disabled      == 'disabled'){$disabled_label      = ' (disabled)';}
            	if ($disabled_view == 'disabled'){$disabled_view_label = ' (disabled)';}
                	$qbox_content = "<div class='qbox-details' data-qid='$qid' data-cid='$cid'>$preloaded_panels</div>";
                $content .= "<b>$title</b>
				<div class='model_xxx'>
                	<section class='edit' data-aid='StoryDetailsEdit' tabindex='-1' action = '$action'>
						<section class='model_details'>
							<section class='story_or_epic_header'>
								<fieldset class='name'>
									<div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='display: flex;'>
										<div class='AutosizeTextarea__container___31scfkZp' style='flex-basis: 500px;'>
										   <span class='AutosizeTextarea__textarea___1LL2IPEy2 NameView___rHG8dOIP' style='margin: 8px;'>
										   		$title_field
										   </span>
										</div>
										<div class='persistence use_click_to_copy' style='margin: 3px 3px 0 15px;order: 2;'>
										  $buttons
										</div>
									</div>
								 </fieldset>
							  </section>
							  <aside>
								<div class='wrapper'>
									<nav class='edit'>
									  <section class='controls' style='margin: 10px 0;'>
										  <div class='actions'>
											  <div class='bubble'></div>
											  <button type='button' id='story_copy_link_$cid' title='Copy the link for this experience to your clipboard".$disabled_label."' data-clipboard-text='$url/view/$id_value' class='autosaves clipboard_button hoverable link left_endcap' tabindex='-1' $disabled></button>
											  <div class='button_with_field'>
												  <button type='button' id ='story_copy_id_$cid' title='Copy the ID of this experience to your clipboard".$disabled_label."' data-clipboard-text='$id_value' class='autosaves clipboard_button hoverable id use_click_to_copy' tabindex='-1' $disabled></button>
												  <input type='text' id='story_copy_id_value_$cid' readonly='' class='autosaves id text_value' value='$id_value' tabindex='-1'>
											</div>
											<button type='button' id='receipt_import_button_$cid' title='Import receipt (disabled)' class='autosaves import_receipt hoverable left_endcap' tabindex='-1' disabled></button>
											<button type='button' id='story_clone_button_$cid' title='Clone this experience".$disabled_view_label."' class='autosaves clone_story hoverable left_endcap' tabindex='-1' $disabled $disabled_view></button>
											<button type='button' id='story_history_button_$cid' title='View the history of this experience".$disabled_view_label."' class='autosaves history hoverable capped' tabindex='-1' $disabled $disabled_view></button>
											<button type='button' id='story_delete_button_$cid' title='Delete this experience".$disabled_view_label."' class='autosaves delete hoverable right_endcap remove-card' data-qid=$qid tabindex='-1'$disabled $disabled_view></button>
										  </div>
										</section>
								  </nav>
		                            <div class='story info_box' style='display:block'><!-- hidden -->
									<div class='info'>
										<div class='date row'>
										  <em>Date</em>
										  <div class='dropdown story_date'>
											<input aria-hidden='true' type='text' id='story_date_dropdown_cxxx_honeypot' tabindex='-1' class='honeypot'>
											$moment_field
										  </div>
										</div>
								  </div>
							  </div>
							 </div>
							</aside>
						</section>
		        		<div class='rTable'>
		        			<div class='rTableBody'>
		            			<div class='rTableRow'>
		        					<div class='rTableCell AutosizeTextarea__container___31scfkZp'>$description_field</div>
		        				</div>
		        				<div class='rTableRow'>
		            				<div class='rTableCell'>{$tabs}{$expand_tabs}{$qbox_content}</div>
		            			</div>
		            		</div>
		        		</div>
		        	</section>
		        </div>";
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $content .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
/*Presentation should happen in the calling view
                Switch ($presentation){
                	case 'qbox':
	                	$vars['content']=$content;
						unset($vars['title'], $vars['tabs']);
					    $form_body = elgg_view('jot/display/qbox',$vars);
						break;
                	case 'inline_xxx':
                		$vars['content'] = $content;
                		unset($vars['tabs']);
                		$form_body = elgg_view_layout('inline', $vars);
                		break;
                	default:
                		$form_body = $content;
                		break;
                }                                                                                             
                */              $form_body = $content;                                                        //register_error($display);
                        break;
                        
                
/****************************************
$section = 'single_experience'        *****************************************************************
****************************************/
            case 'experience':
            case 'single_experience':
                
                switch($presentation){
/****************************************
$section = 'single_experience' $presentation = 'pallet' *****************************************************************
****************************************/
                    case 'pallet':
            	        $form_version           = 'jot/edit_pallet';
            	        $form_vars = ['body'    => $hidden_fields.$info_boqx, 
        						      'enctype' => 'multipart/form-data',];
                    	$boqx_contents_edit = elgg_view_form($form_version,$form_vars);
                        $classes = ['ItemEditValue','Item__nhjb4ONn','boqx-experience'];
                        $classes[] = $presence == 'empty boqx' ? 'collapsed' : 'open';
            			$form_body = elgg_format_element('div',['id'=>$cid, 'class'=>$classes, 'data-boqx'=>$parent_cid, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'data-presentation'=>$presentation, 'data-presence'=>$presence, 'boqx-fill-level'=>$fill_level], $boqx_contents_edit);
                        break;
                    
/****************************************
$section = 'single_experience' $presentation = 'carton' *****************************************************************
****************************************/
                    case 'carton':
            	        $form_version = 'jot/edit_pallet';
                        if ($action == 'edit'){
                            $classes    = ['ItemEditValue','Item__nhjb4ONn','boqx-experience','collapsed'];
                            $edit_boqx  = elgg_format_element('div',['id'=>$cid, 'class'=>$classes, 'data-boqx'=>$preview_id, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'data-presentation'=>$presentation, 'data-presence'=>$presence, 'boqx-fill-level'=>$fill_level], $hidden_fields.$info_boqx);
                            $form_vars = ['id'      => $form_id,
            	                          'body'    => $edit_boqx, 
            						      'enctype' => 'multipart/form-data',];
                        	$boqx_contents_edit = $display_state == 'view' ? $form_vars['body'] : elgg_view_form($form_version,$form_vars);
                            
                            $form_body  = elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'experience', 'boqx_id'=>$parent_cid, 'cid'=>$preview_id, 'edit_boqx'=>$boqx_contents_edit,'state'=>'show','presentation'=>$presentation,'form_wrapper'=>$form_wrapper]);
                        }
                        else 
                            $form_body = elgg_view('page/elements/envelope',['task'=>'experience', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title,'has_collapser'=>'yes','form_wrapper'=>$form_wrapper]);
                        break;
                    
/****************************************
$section = 'single_experience' $presentation = 'open_boqx' *****************************************************************
****************************************/
                    case 'open_boqx':
                        if ($action == 'edit'){
                            if ($form_wrapper){
                                $form_wrapper['form_vars']['body']= $hidden_fields.$info_boqx;
                                $edit = elgg_view_form($form_wrapper['action'],$form_wrapper['form_vars'],$form_wrapper['body_vars']); 
                            }
                            else
                                $edit = $hidden_fields.$info_boqx;
                            $classes    = ['ItemEditValue','Item__nhjb4ONn','boqx-experience','collapsed'];
                            $edit_boqx = elgg_format_element('div',['id'=>$cid, 'class'=>$classes, 'data-boqx'=>$preview_id, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'data-presentation'=>$presentation, 'data-presence'=>$presence, 'boqx-fill-level'=>$fill_level], $edit);
                            $form_body = elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'experience', 'boqx_id'=>$parent_cid, 'cid'=>$preview_id, 'edit_boqx'=>$edit_boqx,'state'=>'show','hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'form_wrapper'=>$form_wrapper]);
                        }
                        else 
                            $form_body = elgg_view('page/elements/envelope',['task'=>'experience', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title,'has_collapser'=>'yes','form_wrapper'=>$form_wrapper]);
                        break;
                }
          break;
/****************************************
$section = 'things'                   *****************************************************************
****************************************/
            case 'things':
                $form_body = $things;                                                                       //register_error($display);
            	break;
/****************************************
 $section = 'things_used'                *****************************************************************
 * Provides a floating 'Add something' section to the Things panel
 * DRAFT
****************************************/
            case 'things_used':
        	    Switch ($snippet){
        	        case 'marker':                                                                             $display .= '3452 things_new>marker $service_cid: '.$service_cid.'<br>';
        	        $form_body .= $hidden_fields;
        	        
        	        $form_body .= "
        	        <div class='Effort__CPiu2C5N things_used-marker' data-qid='$qid' data-parent-cid='$parent_cid' data-cid='$cid' data-guid='$guid' data-aid='$action'>
        	        {$things_used_add}{$things_used_show}{$things_used_edit}
        	        </div>";
        	           break;
        	        case 'new_thing':                                                                      $display.= '3461 $cid: '.$cid.'<br>';
        	           $form_body .= $new_thing;
        	           break;
        	        case 'add':
        	        case 'view':
        	        case 'edit':
        	           // do nothing...
        	           break;
        	        case 'list_things':
        	        case 'view_things':
        	            $form_body .= str_replace('__cid__', $cid, $story_model);
        	            break;
        	        case 'things_used_add':
        	        case 'things_used_edit':
        	        case 'things_used_view':
        	        case 'things_used_dropboqx':
					case 'dropboqx':
        	            $form_body .= $things_used;
        	            break;
        	        default:
        	            $form_body .= $hidden_fields;
        	            $form_body .= "<div data-aid='Things' class='things_new-default' action ='$action'>
        	            <span class='efforts-eggs' data-aid='thingsCount' data-qid='$qid'><h4>Things used ...</h4>
        	            </span>
        	            $things_used
        	            </div>";                                                                          $display .= '4491 issue_effort-default ... <br>4050 $cid: '.$cid.'<br>4050 $service_cid: '.$service_cid;
        	            break;
            	}                                                                                         //register_error($display);
            	
            	break;
/****************************************
$section = 'thing'                      *****************************************************************
****************************************/
            case 'thing':
echo '<!-- $section = '.$section.' $presentation = '.$presentation.'-->';
            	unset($hidden_fields);

                if (!empty($hidden))                
                   foreach($hidden as $field=>$value){
                       $value['type']='hidden';
                       $hidden_fields .= elgg_format_element('input', $value);}
                switch($presentation){
/****************************************
$section = 'thing' $presentation = 'pallet' *****************************************************************
****************************************/
                    case 'pallet':
            	        $form_version           = 'jot/edit_pallet';
            	        $form_vars = ['body'    => $hidden_fields.$info_boqx, 
        						      'enctype' => 'multipart/form-data',];
                    	$boqx_contents_edit = elgg_view_form($form_version,$form_vars);
                            
            			$form_body = elgg_format_element('div',['id'=>$cid, 'class'=>['ItemEditValue','Item__nhjb4ONn','boqx-thing'], 'data-boqx'=>$parent_cid, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'boqx-fill-level'=>$fill_level], $boqx_contents_edit);
                        break;
                    
/****************************************
$section = 'thing' $presentation = 'carton' *****************************************************************
****************************************/
                    case 'carton':
                        if ($action == 'edit'){
                            $preview_id = quebx_new_id('c');
                            $edit_boqx = elgg_format_element('div',['id'=>$cid, 'class'=>['ItemEditValue','Item__nhjb4ONn','boqx-thing','collapsed'], 'data-boqx'=>$preview_id, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'boqx-fill-level'=>$fill_level], $hidden_fields.$info_boqx);
                            $form_body = elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'thing', 'boqx_id'=>$parent_cid, 'cid'=>$preview_id, 'edit_boqx'=>$edit_boqx,'state'=>'show','hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'presence'=>$presence,'form_wrapper'=>$form_wrapper,'mobility'=>'stationary']);
                        }
                        else 
                            $form_body = elgg_view('page/elements/envelope',['task'=>'thing', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title,'has_collapser'=>'no','form_wrapper'=>$form_wrapper]);
                        break;
                    
/****************************************
$section = 'thing' $presentation = 'open_boqx' *****************************************************************
****************************************/
                    case 'open_boqx':
                        if ($action == 'edit'){
                            $preview_id = quebx_new_id('c');
                            if ($form_wrapper){
                                $form_wrapper['form_vars']['body']= $hidden_fields.$info_boqx;
                                $edit = elgg_view_form($form_wrapper['action'],$form_wrapper['form_vars'],$form_wrapper['body_vars']); 
                            }
                            else
                                $edit = $hidden_fields.$info_boqx;
                            $edit_boqx = elgg_format_element('div',['id'=>$cid, 'class'=>['ItemEditValue','Item__nhjb4ONn','boqx-thing','collapsed'], 'data-boqx'=>$preview_id, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'boqx-fill-level'=>$fill_level], $edit);
                            $form_body = elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'thing', 'boqx_id'=>$parent_cid, 'cid'=>$preview_id, 'edit_boqx'=>$edit_boqx,'state'=>'show','hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'form_wrapper'=>$form_wrapper]);
                        }
                        else 
                            $form_body = elgg_view('page/elements/envelope',['task'=>'thing', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title,'has_collapser'=>'yes','form_wrapper'=>$form_wrapper]);
                        break;
/****************************************
$section = 'thing' $presentation = 'nested' *****************************************************************
****************************************/
                        case 'nested':
						  $form_body = elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'thing', 'boqx_id'=>$parent_cid, 'cid'=>$preview_id, 'edit_boqx'=>$info_boqx,'state'=>'show','hidden_fields'=>$hidden_fields,'presentation'=>$presentation,'form_wrapper'=>$form_wrapper,'mobility'=>'stationary']);
						break;
                    default:
                        $form_body = $info_boqx;
                }
                break;
/****************************************
$section = 'documents'                *****************************************************************
****************************************/
            case 'documents':
            	// Retrieve contents from shelf
            	$file        = new ElggFile;
				$file->owner_guid = elgg_get_logged_in_user_guid();
				$file->setFilename("shelf.json");
				if ($file->exists()) {
					$file->open('read');
					$json = $file->grabFile();
					$file->close();
				}				
				$data = json_decode($json, true);
				$thumbnails ="<div class='scrollimage' style='max-width:600px;margin:0 auto;'>";
				$file_count=0;
				if ($data){
					foreach($data as $key=>$contents){
			            unset($document_guid, $qty, $document, $input_checkbox, $thumbnail);
			            foreach($contents as $position=>$value){
			                while (list($position, $value) = each($contents)){
			                    if ($position == 'guid'){
			                        $document = get_entity($value);
			                    }
			                    if ($position == 'quantity'){
			                        $qty = $value;
			                    }
			                }
			            }
					    if ($document && $document->getSubtype() != 'file'){
					    	continue;
					    }
					    if($document){
    					    ++$file_count; 
    					    $document_guids[] = $document->getGUID();
    					    $document_guid    = $document->getGUID();
    					    $documents[]      = $document;
    						$thumbnail = elgg_view('market/thumbnail', 
    										array('marketguid' => $document_guid,
    										      'size'       => 'small',
    										));
    						
    						$input_checkbox_options = array('id'      =>$document_guid,
    			                                            'name'    => 'jot[documents][]', 
    								 					    'value'   => $document_guid,
    								                        'default' => false,
    								                        'presentation'=>$presentation,
    													);
    						$options = array(
    							'text' => $thumbnail, 
    							'class' => 'elgg-lightbox',
    						    'data-colorbox-opts' => json_encode(['width'=>500, 'height'=>525]),
    				            'href' => "market/viewimage/$document_guid");
    						
    						if($item_document_guids){
    							if (in_array($document_guid, $item_document_guids)){
    								$options['style'] = 'background-color: rgb(238, 204, 204);';
    							}
    							else {$input_checkbox   = elgg_view('input/checkbox', $input_checkbox_options);
    							}
    						}
    									
    					   	$thumbnail = "<span title = '$entity->title'>".elgg_view('output/url', $options)."</span>";					
    			
    					   	$thumbnails .= elgg_view('output/div',['content'=>$input_checkbox.$thumbnail]);;
    					}
					}
				}
				$thumbnails = $thumbnails."</div>";
				
//				$input_shelf = $thumbnails;
				if (!empty($document_guids)){
                	$options['wheres'][] = "e.guid in (".implode(',', $document_guids).")";
            	}
            	else {
            	    $options['wheres'][] = "e.guid is NULL";
            	}
            	if ($presentation == 'qbox'             || 
            	    $presentation == 'qbox_experience'  ||
            	    $presentation == 'popup')           {
            		$options['item_guid']    = $guid;
            		$options['presentation'] = $presentation;
            	}
            		
            	$input_shelf = elgg_list_entities($options);
            	$drop_media = elgg_view('input/dropzone', array(
                                    'name' => 'jot[documents]',
                                    'default-message'=> '<strong>Drop document files here</strong><br /><span>or click to select them from your computer</span>',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'file',));

                if ($file_count>0){
                    $documents_list = elgg_list_entities(array(
                            'guids'            =>$documents,
                            'input_name'       => 'jot[documents][]',
                            'input_value_field'=> 'guid'
                    ));
                }
                $input =
                "<div>
                    <div>$drop_media</div>
	                <div>Shelf</div>";
                if ($file_count>0){
                	$input.="
                        <div>Select documents from the shelf</div>
	                    <div aspect='media_input' style='display:block'>$input_shelf</div>";
                }
                else {
	                $input.="
		                 <div><ul style='list-style-type: disc; list-style-position: inside;'>
                                <li>Shelf is empty.  Pick documents from folders to set them on the shelf.</li>
                              </ul>
                         </div>";
                }
                if($presentation == 'qbox_experience' ||
                   $presentation == 'popup')         {
                      $div_id = elgg_extract('qid', $vars);}
                else {$div_id = 'Documents_panel';}
                
                $input.="
                 </div>";
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $content .= elgg_view('output/div',['content'=>$hidden_fields.$input,
                		                            'class'  => 'elgg-head experience-panel',
                    'options'=>['panel'=>'Documents','guid'=>$guid,'aspect'=>'attachments','id'=>$div_id, 'parent-cid'=>$parent_cid,'data-qid'=>$div_id,'data-cid'=>$cid, 'style'=>$vars['visibility']]
                ]);
                Switch ($presentation){
                	case 'qbox':
	                	$vars['content']=$content;
						$form_body = elgg_view('jot/display/qbox',$vars);
						break;
                	case 'inline':
                		$vars['content'] = $content;
                		$form_body = elgg_view_layout('inline', $vars);
                		break;
                	default:
                		$form_body = $content;
                		break;
                }
                break;
/****************************************
$section = 'gallery'                  *****************************************************************
****************************************/
            case 'gallery':
            	// Retrieve contents from shelf
            	$file        = new ElggFile;
				$file->owner_guid = elgg_get_logged_in_user_guid();
				$file->setFilename("shelf.json");
				if ($file->exists()) {
					$file->open('read');
					$json = $file->grabFile();
					$file->close();
				}				
				$data = json_decode($json, true);
				$file_count=0;
				$thumbnails ="<div class='scrollimage' style='max-width:600px;margin:0 auto;'>";
				if ($data){
					foreach($data as $key=>$contents){
			            unset($image_guid, $qty, $entity, $input_checkbox, $thumbnail);
			            foreach($contents as $position=>$value){
			                while (list($position, $value) = each($contents)){
			                    if ($position == 'guid'){
			                        $entity = get_entity($value);
			                    }
			                    if ($position == 'quantity'){
			                        $qty = $value;
			                    }
			                }
			            }
					    if ($entity && $entity->getSubtype() != 'hjalbumimage'){
					    	continue;
					    }
					    if ($entity){
    					    ++$file_count;
    					    $image_guid = $entity->getGUID();
    						$thumbnail = elgg_view('market/thumbnail', 
    										array('marketguid' => $image_guid,
    										      'size'       => 'small',
    										));
    						
    						$input_checkbox_options = array('id'      =>$image_guid,
    			                                            'name'    => 'jot[images][]', 
    								 					    'value'   => $image_guid,
    								                        'default' => false, 
    													);
    						$options = array(
    							'text' => $thumbnail, 
    							'class' => 'elgg-lightbox',
    						    'data-colorbox-opts' => json_encode(['width'=>500, 'height'=>525]),
    				            'href' => "market/viewimage/$image_guid");
    						
    						if ($item_image_guids){
    							if (in_array($image_guid, $item_image_guids)){
    								$options['style'] = 'background-color: rgb(238, 204, 204);';
    							}
    							else {$input_checkbox   = elgg_view('input/checkbox', $input_checkbox_options);
    							}
    						}
    									
    					   	$thumbnail = "<span title = '$entity->title'>".elgg_view('output/url', $options)."</span>";					
    			
    					   	$thumbnails .= elgg_view('output/div',['content'=>$input_checkbox.$thumbnail]);;
    					}
					}
				}
				$thumbnails = $thumbnails."</div>";
				
				$input_shelf = $thumbnails;
                // overwrite the default
				$drop_media   = elgg_view('input/dropzone', array(
						'name' => 'jot[images]',
						'default-message'=> '<strong>Drop images or videos here</strong><br /><span>or click to select from your computer</span>',
						'max' => 25,
						'accept'=> 'image/*, video/*',
						'multiple' => true,
						'style' => 'padding:0;',
						'container_guid' => $owner_guid,
				));
                $input =
                "<div>
                    <div>$drop_media</div>
	                <div>Shelf</div>";
                if ($file_count>0){
                	$input.="
                        <div>Select pictures from the shelf</div>
	                    <div aspect='media_input' style='display:block'>$input_shelf</div>
	                 </div>";
                }
                else {
	                $input.="
		                 <div><ul style='list-style-type: disc; list-style-position: inside;'>
                                <li>Shelf is empty.  Pick pictures from albums to set them on the shelf.</li>
                              </ul>
                         </div>
		                 </div>";
                }

                if($presentation == 'qbox_experience' ||
                    $presentation == 'popup')         {
                      $div_id = elgg_extract('qid', $vars);}
                else                       {$div_id = 'Gallery_panel';}
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $content .= elgg_view('output/div',['content'=>$hidden_fields.$input,
                		                            'class'  => 'elgg-head experience-panel',
                    'options'=>['panel'=>'Gallery','guid'=>$guid,'aspect'=>'attachments', 'data-qid'=>$div_id,'id'=>$div_id,'parent-cid'=>$parent_cid,'data-cid'=>$cid, 'style'=>$vars['visibility']]
                ]);
                Switch ($presentation){
                	case 'qbox':
	                	$vars['content']=$content;
						$form_body = elgg_view('jot/display/qbox',$vars);
						break;
                	case 'inline':
                		$vars['content'] = $content;
                		$form_body = elgg_view_layout('inline', $vars);
                		break;
                	default:
                		$form_body = $content;
                		break;
                }                
                break;
/****************************************
$section = 'expand'                     *****************************************************************
****************************************/
            case 'expand':
            	        $options  = array(array('id'    =>'expand_nothing',
		                                        'label' =>'Nothing',
		                                        'option'=>'nothing',
		                                        'title' =>'Nothing else'),
		                                  array('id'    =>'expand_instruction',
		                                        'label' =>'Instructions',
		                                        'option'=>'instruction',
		                                        'title' =>'Convert this experience into an instruction'),
		                                  array('id'    =>'expand_observation',
		                                        'label' =>'Observation',
		                                        'option'=>'observation',
		                                        'title' =>'Convert this experience into an observation'),
		                                  array('id'    =>'expand_event',
		                                        'label' =>'Event',
		                                        'option'=>'event',
		                                        'title' =>'Convert this experience into an event'),
		                                  array('id'    =>'expand_project',
		                                        'label' =>'Project',
		                                        'option'=>'project',
		                                        'title' =>'Convert this experience into a project'),
		                                  array('id'    =>'expand_issue',
		                                        'label' =>'Issue',
		                                        'option'=>'issue',
		                                        'title' =>'Convert this experience into an issue'),
		                                 );
		                $selector = elgg_view('input/quebx_radio', array(
		                        'id'      => 'expand',
		                        'name'    => 'jot[aspect]',
		                        'value'   => 'nothing',
		                        'options2'=> $options,
		                        'align'   => 'horizontal',
		                ));
		                $selector = 'Expand this experience ...'.$selector;
		                $nothing_panel_display     = 'display: block;';
		                $instruction_panel_display = 'display: none;';
		                $observation_panel_display = 'display: none;';
		                $event_panel_display       = 'display: none;';
		                $project_panel_display     = 'display: none;';
		                $effort_panel_display     = 'display: none;';
		                if ($action = 'edit'){
		                    if ($aspect != 'experience'){
		                        $style                 = 'display: block;';
		                        $nothing_panel_display = 'display: none;';
		                        unset($selector);}
		                    Switch ($aspect){
		                        case 'instruction': $instruction_panel_display = 'display: block;'; break;
		                        case 'observation': $observation_panel_display = 'display: block;'; break;
		                        case 'event'      : $event_panel_display       = 'display: block;'; break;
		                        case 'project'    : $project_panel_display     = 'display: block;'; break;
		                        case 'effort'     : $effort_panel_display      = 'display: block;'; break;
		                    }
		                }
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
		                        if($presentation == 'qbox_experience' ||
		                           $presentation == 'popup'){
		                              $div_id = elgg_extract('qid', $vars);}
		                else                       {$div_id = 'Expand_panel';}
		                $form_body .= "
		                <div panel='Expand' guid=$guid id=$div_id aspect='attachments' class='elgg-head' style='$style;'>";
		                $form_body .= $selector;
		                $form_body .= "
		                    <div id ='expand_nothing_panel'     style='$nothing_panel_display'>
		                        $nothing_panel
		                	</div>
		                    <div id ='expand_instruction_panel' style='$instruction_panel_display'>
		                        $instruction_panel
		                	</div>
		                    <div id ='expand_observation_panel' style='$observation_panel_display'>
		                        $observation_panel
		                	</div>
		                    <div id ='expand_event_panel'       style='$event_panel_display'>
		                        $event_panel
		                	</div>
		                    <div id ='expand_project_panel'     style='$project_panel_display'>
		                        $project_panel
		                	</div>
		                    <div id ='expand_effort_panel'     style='$effort_panel_display'>
		                        $effort_panel
		                	</div>";
		                $form_body .= "
						</div>";
                break;
/****************************************
$section = 'instruction'                *****************************************************************
****************************************/
            case 'instruction':
                unset($input_tabs);
                $input_tabs[]    = array('title'=>'Material', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'instruction_material'  , 'guid'=>$guid, 'aspect'=>'instruction_input');
                $input_tabs[]    = array('title'=>'Tools'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'instruction_tools'     , 'guid'=>$guid, 'aspect'=>'instruction_input');
                $input_tabs[]    = array('title'=>'Steps'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'instruction_steps'     , 'guid'=>$guid, 'aspect'=>'instruction_input');
                $input_tabs      = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'instruction_input', 'tabs'=>$input_tabs));
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "
                <div id='instruction_panel' class='elgg-head'  style='display: block;'>";
                $content_title .= "<h3>Instructions</h3>";
                $content_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:5px;'>
                                		<div panel='instruction_material'   guid=$guid aspect='instruction_input' id='instruction_material_panel'   class='elgg-head' style='display:block'>
                                		    $material_panel</div>
                                		<div panel='instruction_tools'   guid=$guid aspect='instruction_input' id='instruction_tools_panel'   class='elgg-head' style='display:none'>
                                		    $tools_panel</div>
                                		<div panel='instruction_steps' guid=$guid aspect='instruction_input' id='instruction_steps_panel' class='elgg-head' style='display:none'>
                                		    $steps_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                
                $form_body .= "
                </div>
                <hr>";
                
                if ($presentation == 'qbox'){
                	$form_body = elgg_view('output/div',['content'=>$content_body,
                			                             'class'=>'elgg-head qbox-content-expand',
                			                             'options'=>['id'      => $qid,
                			                                         'data-cid'=> $cid]]);                	
                }
                else {$form_body = elgg_view('output/div',['content'=>$content_title.$content_body.'<hr>',
                		                                   'class'  => 'elgg-head',
                		                                   'options'=>['style'=>'display:block;',
                                                                       'id'   =>'instructional_panel']
                ]);}                
                
                break;
/****************************************
$section = 'event'                      *****************************************************************
****************************************/
            case 'event':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<div id='event_schedule_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Event</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%'>Stage
                                    </div>
                					<div class='rTableCell' style='width:85%'>$stage_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:5px;'>
                                		<div panel='event_planning'   guid=$guid aspect='event_input' id='event_planning_panel'   class='elgg-head' style='display:block'>
                                		    $planning_panel</div>
                                		<div panel='event_in_process' guid=$guid aspect='event_input' id='event_in_process_panel' class='elgg-head' style='display:none'>
                                		    $in_process_panel</div>
                                        <div panel='event_postponed'  guid=$guid aspect='event_input' id='event_postponed_panel'  class='elgg-head' style='display:none;'>
                                            $postponed_panel</div>
                                        <div panel='event_cancelled'  guid=$guid aspect='event_input' id='event_cancelled_panel'  class='elgg-head' style='display:none;'>
                                            $cancelled_panel</div>
                                        <div panel='event_complete'   guid=$guid aspect='event_input' id='event_complete_panel'   class='elgg-head' style='display:none;'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                
                $form_body .= "
                </div>
                <hr>";
                break;
/****************************************
$section = 'project'                     *****************************************************************
****************************************/
            case 'project':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $content_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $content_body .= "<div id='project_milestones_panel' class='elgg-head'  style='display: block;'>";
                $content_body .= "<h3>Project</h3>";
                $content_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%'>Stage
                                    </div>
                					<div class='rTableCell' style='width:85%'>$stage_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:5px;'>
                                		<div panel='project_planning'   guid=$guid aspect='project_input' id='project_planning_panel'   class='elgg-head' style='display:block;'>
                                		    $milestones_panel</div>
                                		<div panel='project_in_process' guid=$guid aspect='project_input' id='project_in_process_panel' class='elgg-head panel items_draggable clearfix' style='display:none'>
                                		    $in_process_panel</div>
                                        <div panel='project_blocked'    guid=$guid aspect='project_input' id='project_blocked_panel'    class='elgg-head' style='display:none;'>
                                            $blocked_panel</div>
                                        <div panel='project_cancelled'  guid=$guid aspect='project_input' id='project_cancelled_panel'  class='elgg-head' style='display:none;'>
                                            $cancelled_panel</div>
                                        <div panel='project_complete'   guid=$guid aspect='project_input' id='project_complete_panel'   class='elgg-head' style='display:none;'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                $content_body .= "
                </div>";
                if ($presentation == 'qbox'){
	                if (!empty($hidden)){                
	                    foreach($hidden as $field=>$value){
	                        $troublehooting_content_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
	                $troublehooting_content_body .= "<div id='project_milestones_panel' class='elgg-head'  style='display: block;'>";
	                $troublehooting_content_body .= "<h3>Project</h3>";
	                $troublehooting_content_body .="
	                    <div class='rTable' style='width:100%'>
	                		<div class='rTableBody'>
	                				<div class='rTableRow'>
	                					<div class='rTableCell' style='width:15%'>Stage
	                                    </div>
	                					<div class='rTableCell' style='width:85%'>$stage_selector
	                                    </div>
	                                </div>
	                				<div class='rTableRow'>
	                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
	                                    </div>
	                					<div class='rTableCell' style='width:85%;padding:5px;'>
	                                		<div panel='project_planning'   guid=$guid aspect='project_input' id='project_planning_panel'   class='elgg-head' style='display:block;'>
	                                		    $milestones_panel</div>
	                                		<div panel='project_in_process' guid=$guid aspect='project_input' id='project_in_process_panel' class='elgg-head panel items_draggable clearfix' style='display:none'>
	                                		    $in_process_panel</div>

	                                    </div>
	                                </div>
	                        </div>
	                    </div>";
	                $troublehooting_content_body .= "
	                </div>";
                	$form_body = elgg_view('output/div',['content'=>$troublehooting_content_body,
                			                             'class'=>'elgg-head qbox-content-expand',
                			                             'options'=>['id'=>$qid]]);                	
                }
                else {$form_body = elgg_view('output/div',['content'=>$content_title.$content_body.'<hr>',
                		                                   'class'  => 'elgg-head',
                		                                   'options'=>['style'=>'display:block;',
                                                                       'id'   =>'project_panel']
                ]);}
                break;
/****************************************
$section = 'observation'              *****************************************************************
****************************************/
            case 'observation':
                $state                  = $entity->state ?: 1;
                $selected_panel[1]      = 'display:none';
                $selected_panel[2]      = 'display:none';
                $selected_panel[3]      = 'display:none';
                $selected_panel[4]      = 'display:none';
                $selected_panel[5]      = 'display:none';
                $selected_panel[$state] = 'display:block' ;
                  
                $state_params = array("name"    => "jot[observation][state]",
                                      'align'   => 'horizontal',
                					  "value"   => $state,
                                      "guid"    => $guid,
                					  "options2" => array(
                					                   array("label" =>"Discovery",
                					                         "option" => 1,
                					                         "title" => "Initial observation"),
                					                   array("label" => "Resolve",
                					                         "option" => 2,
                					                         "title" => "Early attempts to resolve"),
                					                   array("label" => "Request",
                					                         "option" => 3,
                					                         "title" => "Request the help of a specialist"),
                					                   array("label" => "Accept",
                					                         "option" => 4,
                					                         "title" => "Accept the situation and move on"),
                					                   array("label" => "Complete",
                					                         "option" => 5,
                					                         "title" => "Assigned tasks are complete.")
                					                   ),
                				      'default' => 1,
                					 );
                $state_selector = elgg_view('input/quebx_radio', $state_params);
                                
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<h3>Observation</h3>";
                $form_body .="Details of the experience that may help to resolve issues.
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:10%'>Stage:
                                    </div>
                					<div class='rTableCell' style='width:90%'>$state_selector
                                    </div>
                                </div>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:10%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:90%;padding:5px;'>
                                		<div panel='observation_discoveries' guid=$guid aspect='observation_input' id='observation_discoveries_panel' class='elgg-head' style='$selected_panel[1]'>
                                		    $discoveries_panel</div>
                                		<div panel='observation_efforts'     guid=$guid aspect='observation_input' id='observation_efforts_panel'     class='elgg-head' style='$selected_panel[2]'>
                                		    $resolve_panel</div>
                                        <div panel='observation_request'      guid=$guid aspect='observation_input' id='observation_request_panel'      class='elgg-head' style='$selected_panel[3]'>
                                            $assign_panel</div>
                                        <div panel='observation_accept'      guid=$guid aspect='observation_input' id='observation_accept_panel'      class='elgg-head' style='$selected_panel[4]'>
                                            $accept_panel</div>
                                        <div panel='observation_complete'    guid=$guid aspect='observation_input' id='observation_complete_panel'    class='elgg-head' style='$selected_panel[5]'>
                                            $complete_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
            	$form_body .= "
                    <div id='line_store' style='display:none;'>
                    	<div class='discovery'>
                    		<div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>".quebx_view_icon('move')."</div>
                    			<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".elgg_view('input/date', array(
                    											'name' => 'jot[observation][discovery][moment][]',
                    									))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][action][]',
                    							        		'class'=> 'rTableform',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][observation][]',
                    							        		'class'=> 'rTableform',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".elgg_view('input/longtext', array(
                    							        		'name' => 'jot[observation][discovery][discovery][]',
                    							        		'class'=> 'rTableform last_discovery',
                    			                                'style'=> 'height:17px',
                    							        ))."</div>
                    			<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                    	
                    	<div class='resolve_effort'>
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
                    		      elgg_view('input/text', array(
                    				'name'        => 'jot[observation][effort][title][]',
                    				'class'       => 'last_effort',
                    	      	    'placeholder' => 'Effort to Resolve',
//                    		        'style'       => 'height:17px',
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                    </div>
                <hr>";
                break;
/****************************************
$section = 'observation_discoveries'              *****************************************************************
****************************************/
            case 'observation_discoveries':
                $qid         = 'q'.$guid;
                $options     = array('type'              => 'object',
                                     'subtype'           => 'observation',
                                     'metadata_name_value_pairs' => array('name'=>'aspect', 'value'=>'discovery'),
                                     'container_guid'    => $guid,
                                     'order_by_metadata' => array('name' => 'sort_order', 'direction' => 'ASC', 'as' => 'integer'),
                               );
                $discoveries = elgg_get_entities_from_metadata($options);
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body  .= "
            	     <b>Discoveries</b><br>
            	        Interesting things that I learned during this experience:
            		<div class='rTable' style='width:100%'>
            			<div id='sortable_discovery' class='rTableBody'>
            				<div class='rTableRow pin'>
            					<div class='rTableCell' style='width:0'>".
            								elgg_view('output/url', [
            								    'text'        => '+',
            									'href'        => '#',
            									'data-element'=> 'new_discovery_item',
            									'data-qid'    => $qid,
            									'data-rows'   => '1',
									    		'data-last-row' => 1,
            									'class'       => 'elgg-button-submit-element clone-discovery-action new-item'])."
            		            </div>
            					<div class='rTableHead' style='width:10%; padding: 0px 0px'><span title='When this happened'>Date</span></div>
            					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I did'>Action</span></div>
            					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I observed'>Observation</span></div>
            					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I learned'>Discovery</span></div>
            					<div class='rTableHead' style='width:0; padding: 0px 0px'></div>
            				</div>
            				";
            	if (!empty($discoveries)){
            	    foreach ($discoveries as $line=>$discovery) {
            	        $form_body  .= 
                		" 		<div class='rTableRow' style='cursor:move'>
                					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                					<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".
                					                           elgg_view('input/date', array(
                													'name'  => "jot[observation][discovery][$qid][moment]",
                					                                'value' => $discovery->date,
                											))."</div>
                					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".
                					                           elgg_view('input/longtext', array(
                									        		'name' => "jot[observation][discovery][$qid][action]",
                					                                'value'=> $discovery->action,
                									        		'class'=> 'rTableform',
                				                                    'style'=> 'height:17px',
                									        ))."</div>
                					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What happened'>".
                					                          elgg_view('input/longtext', array(
                									        		'name' => "jot[observation][discovery][$qid][observation]",
                					                                'value'=> $discovery->observation,
                									        		'class'=> 'rTableform',
                				                                    'style'=> 'height:17px',
                									        ))."</div>
                					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".
                					                          elgg_view('input/longtext', array(
                									        		'name' => "jot[observation][discovery][$qid][discovery]",
                					                                'value'=> $discovery->discovery,
                									        		'class'=> 'rTableform'.$last_class,
                				                                    'style'=> 'height:17px',
                									        ))."</div>
                					<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>".
                					                          elgg_view('input/hidden', array(
                					                                'name' => "jot[observation][discovery][$qid][guid]",
                					                                'value'=> $discovery->guid,
                					                        ))."</div>
                				</div>";
                	}
            	}
            	// Populate blank lines
            	for ($i = $n+1; $i <= $n+1; $i++) {
            	    if ($i == 3){
            	        $last_class = ' last_discovery';
            	    }
            		$form_body  .= 
            		" 		<div class='rTableRow' style='cursor:move'>
            					<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>".quebx_view_icon('move')." </div>
            					<div class='rTableCell' style='width:10%; padding: 0px 0px;vertical-align:top' title='When this happened'>".elgg_view('input/date', array(
            													'name' => "jot[observation][discovery][$qid][moment]",
            											))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I did'>".elgg_view('input/longtext', array(
            									        		'name' => "jot[observation][discovery][$qid][action]",
            									        		'class'=> 'rTableform',
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I observed'>".elgg_view('input/longtext', array(
            									        		'name' => "jot[observation][discovery][$qid][observation]",
            									        		'class'=> 'rTableform',
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:30%; padding: 0px 0px;vertical-align:top' title='What I learned'>".elgg_view('input/longtext', array(
            									        		'name' => "jot[observation][discovery][$qid][discovery]",
            									        		'class'=> 'rTableform'.$last_class,
            				                                    'style'=> 'height:17px',
            									        ))."</div>
            					<div class='rTableCell' style='width:0; padding: 0px 0px' title='remove'><a href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
            				</div>";
            	}
            	$form_body  .="
            	        <div id='{$qid}_new_line_items' class='new_discovery'></div>
                	</div>
                </div>";                            
                break;
/****************************************
$section = 'observation_resolve'                    *****************************************************************
****************************************/
            case 'observation_resolve':
                $effort_add_button = "<a title='add effort' class='elgg-button-submit-element add-effort' style='cursor:pointer;height:14px;width:30px'>+</a>";
                unset($form_body, $hidden, $last_class, $list_items);
                $efforts     = elgg_get_entities_from_metadata(array(
                                    'type'              => 'object',
                                    'subtype'           => 'observation',
                                    'metadata_name_value_pairs' => array(
                                            'name'      => 'aspect', 
                                            'value'     => 'effort'),
                                    'container_guid'    => $guid,
                                    'order_by_metadata' => array(
                                            'name'      => 'sort_order', 
                                            'direction' => 'ASC', 
                                            'as'        => 'integer'),
                                    'limit'             => 0,
                        ));
                if (!empty($efforts)){
                    foreach ($efforts as $effort){
                        $list_items .= "
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
/*                    		      elgg_view('input/longtext', array(
                    				'name'        => 'jot[observation][effort][description][]',
                    		        'value'       => $effort->description,
                    	      	    'placeholder' => 'Effort to resolve',
                    		        'style'       => 'height:17px;list-style:decimal'
                    			))
*/                    			   elgg_view('output/url', array(
                                        'text'  => $effort->title,
                                        'href'  => 'jot/edit/'.$effort->guid,
                                ))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>".
            					                          elgg_view('input/hidden', array(
            					                                'name' => 'jot[observation][effort][guid][]',
            					                                'value'=> $effort->guid,
            					                        ))."
            					</div>
                		</div>";
                    }
                }

                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<b>Efforts to Resolve</b><br>
                    Attempts made to resolve the issue.<br>
                    $effort_add_button <span id='effort_button_label'>add effort</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_observation_resolve', style='list-style:decimal;padding-left:20px'>
                            $list_items";
                
                // Populate blank lines
                $n=0;
            	for ($i = $n+1; $i <= $n+0; $i++) {
            		$form_body .= "
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                                <li>".
                    		      elgg_view('input/text', array(
                    				'name'        => "jot[observation][effort][$qid][title]",
                    				'class'       => 'last_effort',
                    	      	    'placeholder' => 'Effort to Resolve',
                    		        'style'       => 'height:17px',
                    			))."
                    		    </li>
                    			</div>
                    			<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a>
                    			</div>
                		</div>";
            	}
                $form_body .= "<div class='new_effort'></div>";
                $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                
                break;
/****************************************
$section = 'observation_request'                    *****************************************************************
****************************************/
            case 'observation_request':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "
                	<p><b>Request the help of a specialist</b></p>
                    <p>Requesting assistance converts the Observation into a Request and routes it to the appropriate service provider.  Quebx routes the request based on the profile of the attached Things and on selections made below.</p><hr>
            		<div class='rTable' style='width:100%'>
                		<div class='rTableBody'>";
                $form_body .= $form_items;
                $form_body .= "
                	    </div>
                    </div>";
                break;
/****************************************
$section = 'observation_accept'                    *****************************************************************
****************************************/
            case 'observation_accept':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "
                    <b>Accept</b><br>
                    Accept the situation and move on.<br>
                	$form_items";
                break;
/****************************************
$section = 'observation_complete'                 *****************************************************************
****************************************/
            case 'observation_complete':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= "<p><b>Complete</b></p>
                	<p>Task is complete and can be closed</p>
                	$form_items";
                break;
/****************************************
$section = 'observation_effort_material'     *****************************************************************
****************************************/
            case 'observation_effort_material':
                $material_add_button = "<a title='add material item' class='elgg-button-submit-element add-material-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $material_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-material-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                $qty         = elgg_view('input/number', array('name'=> 'jot[effort][material][qty][]', 'value'=> 1, 'max'  => 0,     ));
                $units       = elgg_view('input/text', array('name'=> 'jot[effort][material][units][]',                               ));
                $material    = elgg_view('input/text', array('name'=> 'jot[effort][material][title][]', 'class'=> 'last_material_item'));
                
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body  .= "Materials<br>
                <div class='rTable' style='width:100%'>
            		<div id='sortable_instruction_material' class='rTableBody'>
            			<div class='rTableRow pin'>
		                    <div class='rTableCell' style='width:0'>$material_add_button</div>
            				<div class='rTableCell' style='width:15%'>Qty</div>
            				<div class='rTableCell' style='width:15%'>Units</div>
            				<div class='rTableCell' style='width:65%'>Material</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>
                        $line_items
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>";
                $form_body  .= "<div class='new_material_item'></div>";
                $form_body  .= "
                </div>
                    </div>";
                $form_body  .= "
                <div style='visibility:hidden'>
                	<div class='material_item'>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
/****************************************
$section = 'observation_effort_tools'          *****************************************************************
****************************************/
            case 'observation_effort_tools':
                $tool_add_button = "<a title='add tool' class='elgg-button-submit-element add-tool-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $tool_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-tool-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                
                $form_body .= "Tools<br>
                    $tool_add_button <span id='tool_button_label'>add tool</span>
                    <div class='rTable' style='width:100%'>
                		<div id='sortable_instruction_tool' class='rTableBody'>
                            $line_items
                    	    <div class='rTableRow' style='cursor:move'>
                    	        <div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[effort][tool][title][]',
                					'class'       => 'last_tool_item',
                    			    'style'       => 'height:17px',
                				))."
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove tool' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
                $form_body .= "<div class='new_tool_item'></div>";
                $form_body .= "
                	    </div>
                    </div>";
                $form_body .= "
                    <div id ='store' style='visibility:hidden'>
                    	<div class='tool_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                    	        <div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[effort][tool][title][]',
                					'class'       => 'last_tool_item',
                    			    'style'       => 'height:17px',
                				))."
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove tool' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
                break;
/****************************************
$section = 'observation_effort_steps'          *****************************************************************
****************************************/
            case 'observation_effort_steps':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                
                $form_body .= "Steps<br>
                    $step_add_button <span id='step_button_label'>add step</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_instruction_step' style='list-style:decimal;padding-left:20px'>
                            $steps
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                	            <li>".
                			      elgg_view('input/longtext', array(
                					'name'        => 'jot[effort][step][description][]',
                					'class'       => 'last_step_item',
                    			    'style'       => 'height:17px',
                				))."
            				    </li>
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
                $form_body .= "<div class='new_step_item'></div>";
                $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                $form_body .= "
                    <div id ='store' style='visibility:hidden'>
                    	<div class='step_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                	            <li>".
                			      elgg_view('input/longtext', array(
                					'name'        => 'jot[effort][step][description][]',
                					'class'       => 'last_step_item',
                    			    'style'       => 'height:17px',
                				))."
            				    </li>
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
                break;
/****************************************
$section = 'instruction_material'     *****************************************************************
****************************************/
            case 'instruction_material':
                $material_add_button = "<a title='add material item' class='elgg-button-submit-element add-material-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $material_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-material-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                $qty         = elgg_view('input/number', array('name'=> 'jot[instruction][material][qty][]', 'value'=> 1, 'max'  => 0,     ));
                $units       = elgg_view('input/text', array('name'=> 'jot[instruction][material][units][]',                               ));
                $material    = elgg_view('input/text', array('name'=> 'jot[instruction][material][title][]', 'class'=> 'last_material_item'));
                
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body  .= "Material<br>
                <div class='rTable' style='width:100%'>
            		<div id='sortable_instruction_material' class='rTableBody'>
            			<div class='rTableRow pin'>
		                    <div class='rTableCell' style='width:0'>$material_add_button</div>
            				<div class='rTableCell' style='width:15%'>Qty</div>
            				<div class='rTableCell' style='width:15%'>Units</div>
            				<div class='rTableCell' style='width:65%'>Material</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>
                        $line_items
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>";
                $form_body  .= "<div class='new_material_item'></div>";
                $form_body  .= "
                </div>
                    </div>";
                $form_body  .= "
                <div style='visibility:hidden'>
                	<div class='material_item'>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$qty</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$units</div>
                			<div class='rTableCell' style='width:65%;padding:0px'>$material</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove material item' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
/****************************************
$section = 'instruction_tools'          *****************************************************************
****************************************/
            case 'instruction_tools':
                $tool_add_button = "<a title='add tool' class='elgg-button-submit-element add-tool-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                $tool_add_button = elgg_view('output/url', array(
								    'text' => '+',
									'class' => 'elgg-button-submit-element add-tool-item',
                                    'style' => 'cursor:pointer;height:14px;width:30px'
									));
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                
                $form_body .= "Tools<br>
                    $tool_add_button <span id='tool_button_label'>add tool</span>
                    <div class='rTable' style='width:100%'>
                		<div id='sortable_instruction_tool' class='rTableBody'>
                            $line_items
                    	    <div class='rTableRow' style='cursor:move'>
                    	        <div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[instruction][tool][title][]',
                					'class'       => 'last_tool_item',
                    			    'style'       => 'height:17px',
                				))."
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove tool' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
                $form_body .= "<div class='new_tool_item'></div>";
                $form_body .= "
                	    </div>
                    </div>";
                $form_body .= "
                    <div id ='store' style='visibility:hidden'>
                    	<div class='tool_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                    	        <div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                    			<div class='rTableCell' style='width:100%;padding:0px'>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[instruction][tool][title][]',
                					'class'       => 'last_tool_item',
                    			    'style'       => 'height:17px',
                				))."
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove tool' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
                break;
/****************************************
$section = 'instruction_steps'          *****************************************************************
****************************************/
            case 'instruction_steps':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                
                $form_body .= "Steps<br>
                    $step_add_button <span id='step_button_label'>add step</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_instruction_step' style='list-style:decimal;padding-left:20px'>
                            $steps
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                	            <li>".
                			      elgg_view('input/longtext', array(
                					'name'        => 'jot[instruction][step][description][]',
                					'class'       => 'last_step_item',
                    			    'style'       => 'height:17px',
                				))."
            				    </li>
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>";
                $form_body .= "<div class='new_step_item'></div>";
                $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                $form_body .= "
                    <div id ='store' style='visibility:hidden'>
                    	<div class='step_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:100%;padding:0px'>
                	            <li>".
                			      elgg_view('input/longtext', array(
                					'name'        => 'jot[instruction][step][description][]',
                					'class'       => 'last_step_item',
                    			    'style'       => 'height:17px',
                				))."
            				    </li>
                				</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
                break;
/****************************************
$section = 'event_planning'                 *****************************************************************
****************************************/
            case 'event_planning':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                    }
                }
                $form_body  .= "<p></p>
                <div class='rTable' style='width:100%'>
            		<div id='sortable_interval' class='rTableBody'>
            			<div class='rTableRow pin'>
		                    <div class='rTableCell' style='width:0'>$interval_add_button</div>
            				<div class='rTableCell' style='width:15%'>Start</div>
            				<div class='rTableCell' style='width:15%'>End</div>
            				<div class='rTableCell' style='width:65%'>Activity</div>
            				<div class='rTableCell' style='width:5%'></div>
            			</div>";
                
                if ($action == 'edit'){
                    if (!empty($schedule)){
                        unset($last_item_class, $n);
                        foreach ($schedule as $schedule_item){
                            unset($set_start_date, $set_end_date, $set_activity, $set_hidden);
                            if (++$n == $schedule_item_count){
                                $last_item_class = 'last_schedule_item';}
                            $set_start_date  = elgg_view('input/date', array('name'=> 'jot[schedule][start_date][]', 'value'=>$schedule_item->start_date, 'placeholder'=>'start date'));
                            $set_end_date    = elgg_view('input/date', array('name'=> 'jot[schedule][end_date][]'  , 'value'=>$schedule_item->end_date  , 'placeholder'=>'end date'));
                            $set_activity    = elgg_view('input/text', array('name'=> 'jot[schedule][title][]'     , 'value'=>$schedule_item->title     , 'placeholder' => 'Activity Name', 'class'=>$last_item_class));
                            $set_delete = elgg_view("output/url",array(
                    			    	'href' => "action/jot/delete?guid=".$schedule_item->getGUID(),
                    			    	'text' => elgg_view_icon('delete-alt'),
                    			    	'confirm' => sprintf(elgg_echo('jot:delete:confirm'), $element_type),
                    			    	'encode_text' => false,
                    			    ));
                            $set_hidden['jot[schedule][guid][]']         = $schedule_item->guid;
                            foreach($set_hidden as $field=>$value){
                                $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));
                            }
                            $form_body .= "<div class='rTableRow' style='cursor:move'>
                				<div class='rTableCell' style='width:15%;padding:0px'>$set_start_date</div>
                				<div class='rTableCell' style='width:15%;padding:0px'>$set_end_date</div>
                				<div class='rTableCell' style='width:65%;padding:0px'>$set_activity</div>
                				<div class='rTableCell' style='width:5%;padding:0px 10px'>$set_delete</div>
                    		</div>";
                        }
                    }
                }
                
                $form_body .="
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
            				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
            				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove interval' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>";
                $form_body  .= "<div class='new_schedule_item'></div>";
                $form_body  .= "
                </div>
                    </div>";
                $form_body  .= "
                <div style='visibility:hidden'>
                	<div class='schedule_item'>
                	    <div class='rTableRow' style='cursor:move'>
            				<div class='rTableCell' style='width:0; padding: 0px 0px;text-align:center' title='move'>&#x225c</div>
                			<div class='rTableCell' style='width:15%;padding:0px'>$start_date</div>
            				<div class='rTableCell' style='width:15%;padding:0px'>$end_date</div>
            				<div class='rTableCell' style='width:65%;padding:0px'>$activity</div>
            				<div class='rTableCell' style='width:5%;padding:0px 10px'><a title='remove interval' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                		</div>
                	</div>
            	</div>";
                break;
/****************************************
$section = 'event_in_process'              *****************************************************************
****************************************/
            case 'event_in_process':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Event in process'; 
                break;
/****************************************
$section = 'event_postponed'         *****************************************************************
****************************************/
            case 'event_postponed':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Event postponed';
                break;
/****************************************
$section = 'event_cancelled'              *****************************************************************
****************************************/
            case 'event_cancelled':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Event cancelled';
                break;
/****************************************
$section = 'event_complete'              *****************************************************************
****************************************/
            case 'event_complete':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Event complete';
                break;
/****************************************
$section = 'project_milestones'       *****************************************************************
****************************************/
            case 'project_milestones':
                $target_date     = elgg_view('input/date', array('name'=> 'jot[project][milestone][target_date][]', 'placeholder'=>'target date'));
                $milestone_add_button = "<a title='add milestone' class='elgg-button-submit-element add-milestone-item' style='cursor:pointer;height:14px;width:30px'>+</a>";
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Project milestones<br>';
                $form_body .= "$milestone_add_button <span id='milestone_button_label'>add milestone</span>
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                        <ol id='sortable_project_milestones' style='list-style:decimal;padding-left:20px'>
                        	<div class='milestone_item'>";
                if (!empty($milestones)){
                    foreach ($milestones as $milestone){
                        $form_body .= "<div class='rTableRow' style='cursor:move'>
                        			<div class='rTableCell' style='width:70%;padding:0px'>
                    	            <li>".
                    			      elgg_view('input/text', array(
                    					'name'        => 'jot[project][milestone][title][]',
                    			        'value'       => $milestone->title,
                        			    'style'       => 'height:17px',
                    				))."
                				    </li>
                    				</div>
                    				<div class='rTableCell' style='width:200px;text-align:right;padding:0px 10px'>".
                    				    elgg_view('input/date', array(
                    				            'name'=> 'jot[project][milestone][target_date][]',
                    				            'value'=>$milestone->target_date,
                    				            'placeholder'=>'target date'))."
                    		        </div>
                    				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                        		</div>
                    		</div>";
                    }
                }
                $form_body .= "<div class='rTableRow' style='cursor:move'>
                        			<div class='rTableCell' style='width:70%;padding:0px'>
                    	            <li>".
                    			      elgg_view('input/text', array(
                    					'name'        => 'jot[project][milestone][title][]',
                    					'class'       => 'last_milestone_item',
                        			    'style'       => 'height:17px',
                    				))."
                				    </li>
                    				</div>
                    				<div class='rTableCell' style='width:200px;text-align:right;padding:0px 10px'>$target_date</div>
                    				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                        		</div>
                    		</div>";
                $form_body .= "<div class='new_milestone_item'></div>";
                $form_body .= "
                	    </ol>
                	    </div>
                    </div>";
                $form_body .= "
                    <div id ='store' style='visibility:hidden'>
                    	<div class='milestone_item'>
                    	    <div class='rTableRow' style='cursor:move'>
                    			<div class='rTableCell' style='width:70%;padding:0px'>
                	            <li>".
                			      elgg_view('input/text', array(
                					'name'        => 'jot[project][milestone][title][]',
                					'class'       => 'last_milestone_item',
                    			    'style'       => 'height:17px',
                				))."
            				    </li>
                				</div>
                				<div class='rTableCell' style='width:200px;text-align:right;padding:0px 10px'>$target_date</div>
                				<div class='rTableCell' style='width:35px;text-align:right;padding:0px 10px'><a title='remove step' href='#' class='remove-node'>".elgg_view_icon('delete-alt')."</a></div>
                    		</div>
                    	</div>
                	</div>";
                break;
/****************************************
$section = 'project_in_process'              *****************************************************************
****************************************/
            case 'project_in_process':
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>'jot[project][marker][type][]',  
            			                                 'options_values' =>['quip'=>'Quip', 'milestone'=>'Milestone', 'check_in'=>'Check in']
            	                                         ]);
            	$marker_point_selector = elgg_view_field(['#type'=>'select',
            			                                  'name'=>'jot[project][marker][points][]',
            			                                   'options_values'=>[0=>'Unestimated',1=>'1 point',2=>'2 points',3=>'3 points']
            	                                        ]);
            	$marker_state_selector = elgg_view_field(['#type'   =>'select',
            			                                  'name'   =>'jot[project][marker][state][]',
            			                                  'options_values' =>['started'=>'Started','finished'=>'Finished','delivered'=>'Delivered','rejected'=>'Rejected','accepted'=>'Accepted']
            			                                 ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "market/owned/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	
            	switch($snippet){
            		case 'marker':
            			$form_body .= elgg_view('output/div',['content'=>"<div class='edit details' data-cid=$cid>
					    					                   <section class='edit' data-aid='StoryDetailsEdit' tabindex='-1'>
					                                              <section class='model_details'>
					                                                  <section class='story_or_epic_header'>
					                                                    <a class='autosaves collapser' id='story_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
					                            					    <fieldset class='name'>
					                            					        <div data-reactroot='' class='AutosizeTextarea___2iWScFt62'>
					                            					            <div class='AutosizeTextarea__container___31scfkZp'>
					                            					               <textarea data-aid='name' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[project][marker][title][]'></textarea>
					                            					            </div>
					                            					        </div>
					                            					     </fieldset>
					                            					  </section>
					                            					  <aside>
					                                                    <div class='wrapper'>
					                                                      <nav class='edit'>
					                                                          <section class='controls'>
					                                                              <div class='persistence use_click_to_copy'>
					                                                                <button class='autosaves button std close' type='button' tabindex='-1'>Add</button>
					                                                              </div>
					                                                              <div class='actions'>
					                                                                  <div class='bubble'></div>
					                                                                  <button type='button' id='story_copy_link_$cid' title='Copy this story&#39;s link to your clipboard' data-clipboard-text='https://www.pivotaltracker.com/story/show/147996415' class='autosaves clipboard_button hoverable link left_endcap' tabindex='-1'></button>
					                                                                  <div class='button_with_field'>
						                                                                  <button type='button' id ='story_copy_id_$cid' title='Copy this story&#39;s ID to your clipboard' data-clipboard-text='147996415' class='autosaves clipboard_button hoverable id use_click_to_copy' tabindex='-1'></button>
						                                                                  <input type='text' id='story_copy_id_value_$cid' readonly='' class='autosaves id text_value' value='#147996415' tabindex='-1'>
					                                                                </div>
					                                                                <button type='button' id='story_clone_button_$cid' title='Clone this story' class='autosaves clone_story hoverable left_endcap' data-cid='$cid' tabindex='-1'></button>
					                                                                <button type='button' id='story_history_button_$cid' title='View the history of this story' class='autosaves history hoverable capped' data-cid='$cid' tabindex='-1'></button>
					                                                                <button type='button' id='story_delete_button_$cid' title='Delete this story' class='autosaves delete hoverable right_endcap remove-progress-marker' data-cid='$cid' data-qid='$qid' tabindex='-1'></button>
					                                                              </div>
					                                                            </section>
					                                                      </nav>
					                                                      <div class='story info_box' style='display:block'><!-- hidden -->
					                                                        <div class='info'>
					                                                            <div class='type row'>
					                                                              <em>Story Type</em>
<!-----------------------------------------------------------------------------------------------------------------------------
					                                                              <div class='dropdown story_type'>
					                                                                  <input aria-hidden='true' type='hidden' name='jot[project][marker][type][]' value='feature'>
					                                                                  <input aria-hidden='true' type='text' id='story_type_dropdown_$cid_honeypot' tabindex='0' class='honeypot'>
					                                                                  <a id='story_type_dropdown_$cid' class='selection item_feature' tabindex='-1'><span>feature</span></a>
					                                                                  <a id='story_type_dropdown_$cid_arrow' class='arrow target' tabindex='-1'></a>
						                                                              <section>
						                                                                <div class='dropdown_menu search'>
						                                                                      <div class='search_item'><input aria-label='search' type='text' id='story_type_dropdown_$cid_search' class='search'>
						                                                                      </div>
						                                                                  <ul>
						                                                                      <li class='no_search_results hidden'>No results match.</li>
						                                                                      <li data-value='feature' data-index='1' class='dropdown_item selected'>
						                                                                          <a class='item_feature ' id='feature_story_type_dropdown_$cid' href='#'>
						                                                                              <span>
						                                                                                  <span class='dropdown_label'>feature</span></span></a></li>
						                                                                      <li data-value='bug' data-index='2' class='dropdown_item'><a class='item_bug ' id='bug_story_type_dropdown_$cid' href='#'><span><span class='dropdown_label'>bug</span></span></a></li>
						                                                                      <li data-value='chore' data-index='3' class='dropdown_item'><a class='item_chore ' id='chore_story_type_dropdown_$cid' href='#'><span><span class='dropdown_label'>chore</span></span></a></li>
						                                                                      <li data-value='release' data-index='4' class='dropdown_item'><a class='item_release ' id='release_story_type_dropdown_$cid' href='#'><span><span class='dropdown_label'>release</span></span></a></li>
						                                                                  </ul>
						                                                                </div>
						                                                              </section>
						                                                            </div>
----------------------------------------------------------------------------------------------------------------------------->
																					$marker_type_selector
						                                                        </div>
					                                                        <div class='estimate row'>
					                                                          <em>Points</em>
					                                                            <div class='dropdown story_estimate'>
					                                                            <input aria-hidden='true' type='hidden' name='jot[project][marker][estimate][]' value='-1' data-type='number'>
					                                                            <input aria-hidden='true' type='text' id='story_estimate_dropdown_$cid_honeypot' tabindex='0' class='honeypot'>
<!-----------------------------------------------------------------------------------------------------------------------------
					                                                            <a id='story_estimate_dropdown_$cid' class='selection item_-1' tabindex='-1'><span>unestimated</span></a>
					                                                            <a id='story_estimate_dropdown_$cid_arrow' class='arrow target' tabindex='-1'></a>
					                                                          <section>
					                                                            <div class='dropdown_menu search'>
					                                                                <div class='search_item'><input aria-label='search' type='text' id='story_estimate_dropdown_$cid_search' class='search'></div>
					                                                                <ul>
					                                                                  <li class='no_search_results hidden'>No results match.</li>
					                                                                  <li data-value='-1' data-index='1' class='dropdown_item selected'><a class='item_-1 ' id='-1_story_estimate_dropdown_$cid' href='#'><span><span class='dropdown_label'>unestimated</span></span></a></li>
					                                                                  <li data-value='0' data-index='2' class='dropdown_item'><a class='item_0 ' id='0_story_estimate_dropdown_$cid' href='#'><span><span class='dropdown_label'>0 points</span></span></a></li>
					                                                                  <li data-value='1' data-index='3' class='dropdown_item'><a class='item_1 ' id='1_story_estimate_dropdown_$cid' href='#'><span><span class='dropdown_label'>1 point</span></span></a></li>
					                                                                  <li data-value='2' data-index='4' class='dropdown_item'><a class='item_2 ' id='2_story_estimate_dropdown_$cid' href='#'><span><span class='dropdown_label'>2 points</span></span></a></li>
					                                                                  <li data-value='3' data-index='5' class='dropdown_item'><a class='item_3 ' id='3_story_estimate_dropdown_$cid' href='#'><span><span class='dropdown_label'>3 points</span></span></a></li>
					                                                              </ul>
					                                                            </div>
					                                                          </section>
----------------------------------------------------------------------------------------------------------------------------->
                                                                               $marker_point_selector
					                                                        </div>
					                                                        </div>
					                                                        <div class='date row'>
					                                                          <em>Date</em>
					                                                          <div class='dropdown story_current_state disabled'>
					                                                            <input aria-hidden='true' type='hidden' name='jot[project][marker][current_state][]' value='unstarted' disabled='disabled'>
					                                                            <input aria-hidden='true' type='text' id='story_current_state_dropdown_$cid_honeypot' tabindex='-1' class='honeypot'>
					                                                            $pick_date
					                                                          </div>
					                                                     </div>
					                                                        <div class='state row'>
					                                                          <em>State</em>
					                                                          <div class='dropdown story_current_state disabled'>
					                                                            <input aria-hidden='true' type='hidden' name='jot[project][marker][current_state][]' value='started' disabled='disabled'>
					                                                            <input aria-hidden='true' type='text' id='story_current_state_dropdown_$cid_honeypot' tabindex='-1' class='honeypot'>
<!---------------------------------------------------------------------------------------------------------------------------
					                                                            <a id='story_current_state_dropdown_$cid' class='selection item_unstarted' tabindex='-1'><span>unscheduled</span></a>
					                                                            <a id='story_current_state_dropdown_$cid_arrow' class='arrow target' tabindex='-1'></a>
					                                                          <section>
					                                                            <div class='dropdown_menu search'>
					                                                                  <div class='search_item'>
					                                                                  	<input aria-label='search' type='text' id='story_current_state_dropdown_$cid_search' class='search'>
					                                                                  </div>
					                                                              <ul>
					                                                                  <li class='no_search_results hidden'>No results match.</li>
					                                                                  <li data-value='unstarted' data-index='1' class='dropdown_item'><a class='item_unstarted ' id='unstarted_story_current_state_dropdown_$cid' href='#'><span><span class='dropdown_label'>unstarted</span></span></a></li>
					                                                                  <li data-value='started' data-index='2' class='dropdown_item'><a class='item_started ' id='started_story_current_state_dropdown_$cid' href='#'><span><span class='dropdown_label'>started</span></span></a></li>
					                                                                  <li data-value='finished' data-index='3' class='dropdown_item'><a class='item_finished ' id='finished_story_current_state_dropdown_$cid' href='#'><span><span class='dropdown_label'>finished</span></span></a></li>
					                                                                  <li data-value='delivered' data-index='4' class='dropdown_item'><a class='item_delivered ' id='delivered_story_current_state_dropdown_$cid' href='#'><span><span class='dropdown_label'>delivered</span></span></a></li>
					                                                                  <li data-value='rejected' data-index='5' class='dropdown_item'><a class='item_rejected ' id='rejected_story_current_state_dropdown_$cid' href='#'><span><span class='dropdown_label'>rejected</span></span></a></li>
					                                                                  <li data-value='accepted' data-index='6' class='dropdown_item'><a class='item_accepted ' id='accepted_story_current_state_dropdown_$cid' href='#'><span><span class='dropdown_label'>accepted</span></span></a></li>
					                                                              </ul>
					                                                            </div>
					                                                          </section>
----------------------------------------------------------------------------------------------------------------------------->
            				                                                   $marker_state_selector
					                                                        </div>
					                                                     </div>
					                                                        <div class='requester row'>
					                                                          <em>Scribe</em>
					                                                          <div class='dropdown story_scribe_id'>
					                                                            <input aria-hidden='true' type='hidden' name='jot[project][marker][scribe_id][]' value='2936271' data-type='number'>
					                                                            <input aria-hidden='true' type='text' id='story_scribe_id_dropdown_$cid_honeypot' tabindex='0' class='honeypot'>
					                                                            <a id='story_scribe_id_dropdown_$cid' class='selection item_2936271' tabindex='-1'><span><div class='name'>Scott Jenkins</div>
					                                                            <div class='initials'>SJ</div></span></a>
					                                                            <a id='story_scribe_id_dropdown_$cid_arrow' class='arrow target' tabindex='-1'></a>
					                                                          <section>
					                                                            <div class='dropdown_menu search'>
					                                                                  <div class='search_item'><input aria-label='search' type='text' id='story_scribe_id_dropdown_$cid_search' class='search'></div>
					                                                              <ul>
					                                                                  <li class='no_search_results hidden'>No results match.</li>
					                                                                  <li data-value='2936271' data-index='1' class='dropdown_item selected'><a class='item_2936271 ' id='2936271_story_scribe_id_dropdown_$cid' href='#'><span><span class='dropdown_label'>Scott Jenkins</span><span class='dropdown_description'> SJ</span></span></a></li>
					                                                              </ul>
					                                                            </div>
					                                                          </section>
					                                                        </div>
					                                                      </div>
					                                                        <div class='participant row'>
					                                                          <em>Participants</em>
					                                                          <div class='story_participants'>
						                                                          <input aria-hidden='true' type='text' id='story_participant_ids_$cid_honeypot' tabindex='0' class='honeypot'>
						                                                          <a id='add_participant_$cid' class='add_participant selected' tabindex='-1'></a>
						                                                          $marker_participant_link
						                                                      </div>
						                                                    </div>
					                                                    </div>
					                                                    <div class='integration_wrapper'>
					                                                    </div>
					                                                     <div class='followers_wrapper'>
					                                                         <div class='following row'>
					                                                            <em>Follow this story</em>
					                                                            <input type='hidden' name='jot[project][marker][following][]' value='0'>
					                                                            <input type='checkbox' id='$cid_following' checked='checked' disabled='true' class='autosaves std value' name='jot[project][marker][following][]' value='on'>
					                                                            <span class='count not_read_only' data-cid='$cid'>1 follower</span>
					                                                        </div>
					                                                      </div>
					                                                      <div class='row timestamp_wrapper'>
					                                                        <div class='timestamp'>
					                                                          <div class='timestamps clickable'>
					                                                            <div class='saving timestamp_row'><span>Saving�</span>
					                                                            </div>
					                                                          <div class='updated_at timestamp_row'>Updated: <span data-millis='1500719603000'>22 Jul 2017, 5:33am</span></div>
					                                                          <div class='requested_at timestamp_row'>Requested: <span data-millis='1498652212000'>28 Jun 2017, 7:16am</span></div>
					                                                        </div>
					                                                      </div>
					                                                    </div>
					                                                  </div>
					                                                  <div class='mini attachments'></div>
					                                                </div>
					                                              </aside>
					                                        	</section>
					                                        	<section class='description full'>
					                                        	    <div data-reactroot='' data-aid='Description' class='Description___3oUx83yQ'><h4>Description </h4>
					                                        	        <div data-focus-id='DescriptionShow--$cid' data-aid='renderedDescription' tabindex='0' class='DescriptionShow___3-QsNMNj tracker_markup DescriptionShow__placeholder___1NuiicbF'>Add a description
					                                        	        </div>
					                                        	        <div class='DescriptionEdit___1FO6wKeX' style='display:none'>
					                                        	            <div data-aid='editor' class='textContainer___2EcYJKlD'>
					                                        	                <div class='AutosizeTextarea___2iWScFt6'>
					                                        	                    <div class='AutosizeTextarea__container___31scfkZp'>
					                                        	                        <textarea aria-label='Description' data-aid='textarea' data-focus-id='DescriptionEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' placeholder='Add a description'>
					                                        	                        </textarea>
					                                        	                    </div>
					                                        	                    <div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt editor___1qKjhI5c tracker_markup'>
					                                        	                        <span><!-- react-text: 11 --><!-- /react-text --></span>
					                                        	                        <span>w</span>
					                                        	                    </div>
					                                        	                </div>
					                                        	                <div class='controls___2K44hJCR'>
					                                        	                    <div class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe'>
					                                        	                        <span data-aid='AddEmoji' title='Add emoji to description' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/2b4b3f66-emoji-light.svg&quot;) center center no-repeat;'>
					                                        	                        </span>
					                                        	                    </div>
					                                        	                    <div style='display:none'><!--hidden-->
					                                        	                        <button class='SMkCk__Button QbMBD__Button--primary _3olWk__Button--small button__save___2XVnhUJI' data-aid='save' type='button'>Add Description</button>
					                                        	                        <button class='SMkCk__Button ibMWB__Button--open _3olWk__Button--small' data-aid='cancel' type='button'>Cancel</button>
					                                        	                    </div>
					                                        	                </div>
					                                        	           </div>
					                                        	           <div class='markdownHelpContainer___32_mTqNL'>
					                                        	               <a class='markdownHelp___2yFSQCip' href='/help/markdown' target='_blank' tabindex='-1'> Formatting help </a>
					                                        	           </div>
					                                        	       </div>
					                                        	    </div>
					                                        	</section>
					                                        	<section class='media full'>
					                                        	    <div data-reactroot='' class='Activity___2ZLT4Ekd'>
					                                        	        <h4 class='tn-comments__activity'>Media</h4>
					                                        	        <ol class='comments all_activity' data-aid='comments'></ol>
					                                        	        <div class='GLOBAL__activity comment CommentEdit___3nWNXIac CommentEdit--new___3PcQfnGf' tabindex='-1' data-aid='comment-new'>
					                                        	            <div class='CommentEdit__commentBox___21QXi4py'>
					                                        	                <div class='CommentEdit__textContainer___2V0EKFmS'>
					                                        	                    <div data-aid='CommentGutter' class='CommentGutter___1wlvO_PP'>
					                                        	                        <div data-aid='Avatar' class='_2mOpl__Avatar'>SJ</div>
					                                        	                     </div>
					                                        	                     <div class='CommentEdit__textEditor___3L0zZts-' data-aid='CommentV2TextEditor'>
					                                        	                         <div class='MentionableTextArea___1zoYeUDA'>
					                                        	                             <div class='AutosizeTextarea___2iWScFt6'>
					                                        	                                 <div class='AutosizeTextarea__container___31scfkZp'>
					                                        	                                     $drop_media
					                                        	                                 </div>
					                                        	                                 <div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt tracker_markup MentionableTextArea__textarea___2WDXl0X6 GLOBAL__no_save_on_enter CommentEdit__textarea___2Rzdgkej'>
					                                        	                                     <span><!-- react-text: 16 --><!-- /react-text --></span>
					                                        	                                     <span>w</span>
					                                        	                                 </div>
					                                        	                             </div>
					                                        	                         </div>
					                                        	                     </div>
					                                        	                 </div>
					                                        	             </div>
					                                        	         </div>
					                                        	     </div>
					                                        	 </section>
					<!--hidden in css-->
					                                        	<section class='activity full'>
					                                        	    <div data-reactroot='' class='Activity___2ZLT4Ekd'>
					                                        	        <h4 class='tn-comments__activity'>Activity</h4>
					                                        	        <ol class='comments all_activity' data-aid='comments'></ol>
					                                        	        <div class='GLOBAL__activity comment CommentEdit___3nWNXIac CommentEdit--new___3PcQfnGf' tabindex='-1' data-aid='comment-new'>
					                                        	            <div class='CommentEdit__commentBox___21QXi4py'>
					                                        	                <div class='CommentEdit__textContainer___2V0EKFmS'>
					                                        	                    <div data-aid='CommentGutter' class='CommentGutter___1wlvO_PP'>
					                                        	                        <div data-aid='Avatar' class='_2mOpl__Avatar'>SJ</div>
					                                        	                     </div>
					                                        	                     <div class='CommentEdit__textEditor___3L0zZts-' data-aid='CommentV2TextEditor'>
					                                        	                         <div class='MentionableTextArea___1zoYeUDA'>
					                                        	                             <div class='AutosizeTextarea___2iWScFt6'>
					                                        	                                 <div class='AutosizeTextarea__container___31scfkZp'>
					                                        	                                     <textarea id='comment-edit-c818' data-aid='Comment__textarea' data-focus-id='CommentEdit__textarea--c818' class='AutosizeTextarea__textarea___1LL2IPEy tracker_markup MentionableTextArea__textarea___2WDXl0X6 GLOBAL__no_save_on_enter CommentEdit__textarea___2Rzdgkej' placeholder='Add a comment or paste an image'></textarea>
					                                        	                                 </div>
					                                        	                                 <div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt tracker_markup MentionableTextArea__textarea___2WDXl0X6 GLOBAL__no_save_on_enter CommentEdit__textarea___2Rzdgkej'>
					                                        	                                     <span><!-- react-text: 16 --><!-- /react-text --></span>
					                                        	                                     <span>w</span>
					                                        	                                 </div>
					                                        	                             </div>
					                                        	                         </div>
					                                        	                     </div>
					                                        	                 </div>
					                                        	                 <div class='CommentEdit__action-bar___3dyLnEWb'>
																					<div class='CommentEdit__button-group___2ytpiQPa'>
																						<button class='SMkCk__Button QbMBD__Button--primary _3olWk__Button--small' data-aid='comment-submit' type='button'>Post comment</button>
																					</div>
																					<div class=''>
																						<span class='CommentEditToolbar__container___3LKaxfw8' data-aid='CommentEditToolbar__container'>
																							<div class='CommentEditToolbar__action___3t8pcxD7'>
																								<button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='add-mention' aria-label='Mention person in comment'>
																									<span title='Mention person in comment' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/8846f168-mention.svg&quot;) center center no-repeat;'>
																									</span>
																								</button>
																							</div>
																							<div class='CommentEditToolbar__action___3t8pcxD7'>
																								<a class=''>
																									<div data-aid='attachmentDropdownButton' tabindex='0' title='Add attachment to comment' class='DropdownButton__icon___1qwu3upG CommentEditToolbar__attachmentIcon___48kfJPfH' aria-label='Add attachment'>
																									</div>
																								</a>
																								<input type='file' data-aid='CommentEditToolbar__fileInput' title='Attach file from your computer' name='file' multiple='' tabindex='-1' style='display: none;'>
																							</div>
																							<div class='CommentEditToolbar__action___3t8pcxD7'>
																								<button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='add-emoji' aria-label='Add emoji to comment'>
																									<span title='Add emoji to comment' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/2b4b3f66-emoji-light.svg&quot;) center center no-repeat;'>
																									</span>
																								</button>
																							</div>
																						</span>
																					</div>
																				 </div>
					                                        	             </div>
					<!--hidden in css-->                       	             <a class='CommentEdit__markdown_help___lvuA4kSr' href='/help/markdown' target='_blank' tabindex='-1' title='Markdown help' data-focus-id='FormattingHelp__link--c818'>
					                                                         Formatting help</a>
					                                        	         </div>
					                                        	     </div>
					                                        	 </section>
					    					                 </section>
					    					               </div>"]);
            			break;
            		default:
	                if (!empty($hidden)){                
	                    foreach($hidden as $field=>$value){
	                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
	                // loading styles inline to raise their priority
	                $form_body .= elgg_view('css/quebx/user_agent', ['element'=>'experience']);
	                $add_story_button = elgg_view('output/url', array(
	                                        'title'    => 'Add story',
	                		                'data-element' => 'new_story',
	                    				    //'text'     => '+',
	                    					//'href'     => '#',
	                    					//'class'    => 'add-progress-marker addButton___3-z3g3BH',
	                    					'class'    => 'trigger-element add_marker',
	                                        'tabindex' => '-1' 
	                    					));
	            	$story_span     = elgg_view('output/span',   array('content'=>'dig?','class'=>'story_name'));
	            	$panel_header   = elgg_view('output/header', ['class'=>'tn-PanelHeader___c0XQCVI7 tn-PanelHeader--single___2ns28dRL pin',
            	                                                  'options' => ['style'   =>'background-color: #dddddd; color: #000000',
            	                                                                'data-bb'  =>'PanelHeader',
            	                                                                'data-aid' =>'PanelHeader'],
            	                                                  'content' => elgg_view('output/div',['class'=>'tn-PanelHeader__inner___3Nt0t86w tn-PanelHeader__inner--single___3Nq8VXGB',
            	                                                  		                               'options'=>['data-aid'=>'highlight',
            	                                                  		                                           'style'=>'border-top-color: #c0c0c0'],
            	                                                   		                               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__actionsArea___EHMT4f1g undraggable',
            	                                                   		                               									   'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__action___3zvuQp6Z',
            	                                                   		                                		                            		                           'content'=>$add_story_button])])
            	                                                   		                                         . elgg_view('output/div',['class'=>'tn-PanelHeader__titleArea___1DRH-oDF',
            	                                                   		                                            		               'content'=> elgg_view('output/div',['class'=>'tn-PanelHeader__name___2UfJ8ho9',
            	                                                   		                                            		                  		                           'options'=>['data-aid'=>'PanelHeader__name'],
            	                                                   		                                            		                  		                           'content'=>'Stories'])])
            	                                                   		                                         . elgg_view('output/div',['class'=>'tn-PanelHeader__actionsArea___EHMT4f1g undraggable',
            	                                                   		                                            		               'content'=> "<a class='' data-aid='DropdownButton'>
																                                                                            				<div title='Panel actions' class='tn-DropdownButton___nNklb3UY'></div>
																                                                                            			</a>"])])]);
	            	$preview        = elgg_view('output/span',   array('content'=>$story_span,'class'=>'name tracker_markup'));
	            	$view_summary   = elgg_view('output/header', array('content'=>$expander.$preview.$delete, 'class'=>'preview collapsed'));
	/*                $preview = "
	        			        <div class='rTableRow progress_marker pin' style='cursor:move'>
	                                <div class='rTableCell' style='width:0%'>$expander</div>
	                                <div class='rTableCell' style='width:100%'>dig?</div>
	            	                $hidden_fields
	            	            </div>";*/
	            	$quick_input = elgg_view('input/text', array('name'=>'jot[project][marker][title][]', 'data-aid'=>'name', 'data-focus-id'=>'NameEdit--c1037'));
	            	$edit_details = elgg_view('output/section', array('content'=>$expander, 'class'=>'edit details', 'options'=>['style'=>'display:none']));
	            	$view_id      = 'view274';
	            	$data_id      = '147996415';
	            	$edit_details = elgg_view('output/div',['class'=>'story model item draggable feature unscheduled point_scale_linear estimate_-1 is_estimatable',
	            	                                        'options'=>['data-parent-cid'=>$parent_cid,'data-cid'=>$cid,'data-id'=>$data_id],
                    			    		                'content'=>$view_summary
                    			    		                         . elgg_view('forms/experiences/edit',['section'=>'project_in_process', 'snippet'=>'marker', 'parent_cid'=>$parent_cid, 'cid'=>$cid])]);
	            	$story_model .= elgg_view('output/div', ['class'  =>'rTableRow story',
	                                                        'content'=>elgg_view('output/div', ['class'  =>'rTableCell',
	                                                                                            'options'=>['style'=>'width:100%; padding: 0px 0px;vertical-align:top'],
	                                                                                            'content'=>elgg_view('output/div',  ['class'  =>'story model item pin',
	                                                                                                                                 'content'=>/*$view_summary.*/$edit_details])])]);
	            	$new_line_items = elgg_view('output/div', ['class'=>'new_progress_marker']);
	            	$items_container = elgg_view('output/div', ['class'=>'items panel_content',
	                                                            'options'=>['id'=>$view_id],
	                                                            'content'=>elgg_view('output/div', ['class'   =>'tn-panel__loom',
	                                                                                                'options' =>['data-reactroot'=>''],
	                                                                                                'content' =>$new_line_items])]);
	            	$form_body .= elgg_view('output/div', ['class'=>"panel icebox_2068141 icebox items_draggable visible", 
	        	                                           'options' => ['id'       =>'panel_icebox_2068141',
	        	                                                         'data-aid' =>'Panel',
	        	                                                         'data-type'=>'icebox',],
	        	                                           'content' => elgg_view('output/div', ['class'   => 'container droppable sortable tn-panelWrapper___fTILOVmk',
	                                             	                                             'options' => ['data-reactroot'=>''],
	                                            	                                             'content' => $panel_header.
	                                            	                                                          elgg_view('output/div', ['class'  => 'rTable',
	                                            	                                                                                   'options'=> ['style'=>'width:100%'],
	                                            	                                                                                   'content'=> elgg_view('output/div',['class'=>'rTableBody ui-sortable',
	                                            	                                                                                                                       'options'=>['id'=>'sortable_item'],
	                                            	                                                                                                                       'content'=>elgg_view('output/div', ['class'=>'new_progress_marker'])])])])]);
	            	/*
	            	$form_body .= elgg_view('output/div', ['options'=>['id'   =>'line_store',
	            	                                                   'style'=>'display:none;'],
	            	                                       'content'=> elgg_view('output/div',['class'  =>'progress_marker_line_items',
	            	                                                                           'content'=> $story_model])]);
	            	*/
	                break;
				}
				end_of_section:
				break;
/****************************************
$section = 'project_blocked'              *****************************************************************
****************************************/
            case 'project_blocked':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Project Blocked';
                break;
/****************************************
$section = 'project_cancelled'              *****************************************************************
****************************************/
            case 'project_cancelled':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Project Cancelled';
                break;
/****************************************
$section = 'project_complete'              *****************************************************************
****************************************/
            case 'project_complete':
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $form_body .= 'Project Complete';
                break;
/****************************************
$section = 'observation_effort'                     *****************************************************************
****************************************/
            case 'observation_effort':
                unset($input_tabs);
                $input_tabs[]    = array('title'=>'Materials', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'observation_effort_material'  , 'guid'=>$guid, 'aspect'=>'observation_effort_input');
                $input_tabs[]    = array('title'=>'Tools'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_effort_tools'     , 'guid'=>$guid, 'aspect'=>'observation_effort_input');
                $input_tabs[]    = array('title'=>'Steps'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'observation_effort_steps'     , 'guid'=>$guid, 'aspect'=>'observation_effort_input');
                $input_tabs      = elgg_view('navigation/tabs_slide', array('type'=>'vertical', 'aspect'=>'observation_effort_input', 'tabs'=>$input_tabs));
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $form_body .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
//                $form_body .= $effort_header;
                $form_body .= "
                <div id='observation_effort_panel' class='elgg-head'  style='display: block;'>";
                $form_body .= "<h3>Effort</h3>";
                $form_body .="
                    <div class='rTable' style='width:100%'>
                		<div class='rTableBody'>
                				<div class='rTableRow'>
                					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                    </div>
                					<div class='rTableCell' style='width:85%;padding:5px;'>
                                		<div panel='observation_effort_material'   guid=$guid aspect='observation_effort_input' id='observation_effort_material_panel'   class='elgg-head' style='display:block'>
                                		    $material_panel</div>
                                		<div panel='observation_effort_tools'   guid=$guid aspect='observation_effort_input' id='observation_effort_tools_panel'   class='elgg-head' style='display:none'>
                                		    $tools_panel</div>
                                		<div panel='observation_effort_steps' guid=$guid aspect='observation_effort_input' id='observation_effort_steps_panel' class='elgg-head' style='display:none'>
                                		    $steps_panel</div>
                                    </div>
                                </div>
                        </div>
                    </div>";
                
                $form_body .= "
                </div>
                <hr>";
                break;
/****************************************
$section = 'transfer'                   *****************************************************************
****************************************/
            case 'transfer':
            	// Retrieve contents from shelf
/*            	$file        = new ElggFile;
				$file->owner_guid = elgg_get_logged_in_user_guid();
				$file->setFilename("shelf.json");
				if ($file->exists()) {
					$file->open('read');
					$json = $file->grabFile();
					$file->close();
				}				
				$data = json_decode($json, true);
				$thumbnails ="<div class='scrollimage' style='max-width:600px;margin:0 auto;'>";
				$file_count=0;
				foreach($data as $key=>$contents){
		            unset($document_guid, $qty, $document, $input_checkbox, $thumbnail);
		            foreach($contents as $position=>$value){
		                while (list($position, $value) = each($contents)){
		                    if ($position == 'guid'){
		                        $document = get_entity($value);
		                    }
		                    if ($position == 'quantity'){
		                        $qty = $value;
		                    }
		                }
		            }
				    if ($document->getSubtype() != 'file'){
				    	continue;
				    }
				    ++$file_count; 
				    $document_guids[] = $document->getGUID();
				    $document_guid    = $document->getGUID();
				    $documents[]      = $document;
					$thumbnail = elgg_view('market/thumbnail', 
									array('marketguid' => $document_guid,
									      'size'       => 'small',
									));
					
					$input_checkbox_options = array('id'      =>$document_guid,
		                                            'name'    => 'jot[documents][]', 
							 					    'value'   => $document_guid,
							                        'default' => false,
							                        'presentation'=>$presentation,
												);
					$options = array(
						'text' => $thumbnail, 
						'class' => 'elgg-lightbox',
					    'data-colorbox-opts' => json_encode(['width'=>500, 'height'=>525]),
			            'href' => "market/viewimage/$document_guid");
					
					if($item_document_guids){
						if (in_array($document_guid, $item_document_guids)){
							$options['style'] = 'background-color: rgb(238, 204, 204);';
						}
						else {$input_checkbox   = elgg_view('input/checkbox', $input_checkbox_options);
						}
					}
								
				   	$thumbnail = "<span title = '$entity->title'>".elgg_view('output/url', $options)."</span>";					
		
				   	$thumbnails .= elgg_view('output/div',['content'=>$input_checkbox.$thumbnail]);;
				}
				$thumbnails = $thumbnails."</div>";
				
//				$input_shelf = $thumbnails;
				if (!empty($document_guids)){
                	$options['wheres'][] = "e.guid in (".implode(',', $document_guids).")";
            	}
            	else {
            	    $options['wheres'][] = "e.guid is NULL";
            	}
            	if ($presentation == 'qbox' || $presentation == 'qbox_experience'){
            		$options['item_guid']    = $guid;
            		$options['presentation'] = $presentation;
            	}
            		
            	$input_shelf = elgg_list_entities($options);
            	$drop_media = elgg_view('input/dropzone', array(
                                    'name' => 'jot[documents]',
                                    'default-message'=> '<strong>Drop document files here</strong><br /><span>or click to select them from your computer</span>',
									'max' => 25,
									'multiple' => true,
		                            'style' => 'padding:0;',
									'container_guid' => $container_guid,
									'subtype' => 'file',));

                if ($file_count>0){
                    $documents_list = elgg_list_entities(array(
                            'guids'            =>$documents,
                            'input_name'       => 'jot[documents][]',
                            'input_value_field'=> 'guid'
                    ));
                }
                $input =
                "<div>
                    <div>$drop_media</div>";
                if ($file_count>0){
                	$input.="
                	<div>Select documents from the shelf</div>
                    <div aspect='media_input' style='display:block'>$input_shelf</div>";
                }
                $input.="
                 </div>";
*/
                
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}

                        if ($presentation == 'qbox_experience' ||
                            $presentation == 'popup'){
                              $div_id = elgg_extract('qid', $vars);}
                else                                   {$div_id = 'Documents_panel';}
                
			    $body_vars = array('element_type'   => 'item',
			                       'aspect'         => 'receipt',
			                       'presentation'   => 'box',
			                       'entity'         => $entity,
			                       'owner_guid'     => elgg_get_logged_in_user_guid(),
			                       'container_guid' => elgg_get_logged_in_user_guid(),
			                       'action'         => $action,
			                       'panel'          => 'receipts',
			                       'title'          => 'Add a receipt',
			                       'do'             => 'add_receipt');
			    $view      = "forms/jot/elements";
				$module_add_receipt = elgg_view('output/div',['class'=>'elgg-head',
				                                   'options'=>['aspect'=>'panel',
				                                               'panel' =>'receipt'],
				                                   'content'=>elgg_view($view, $body_vars)]);
				                
                
               $content .= $hidden_fields.$module_add_receipt;
               if ($presentation == 'qbox'){
                	$vars['content']=$content;
					$form_body = elgg_view('jot/display/qbox',$vars);
                }
                else {
                	$form_body = $content;	
                }
                	
                break;
                
/****************************************
$section = 'issue'                      *****************************************************************
****************************************/
            case 'issue':
                unset($hidden_fields);
                $hidden_fields = quebx_format_elements('hidden', $hidden);

//                 if (!empty($hidden))                
//                    foreach($hidden as $field=>$value){
//                        $value['type']='hidden';
//                        $hidden_fields .= elgg_format_element('input', $value);}
                
                if ($display_state == 'view')
                    unset($hidden_fields);
                
                $content_title .= "<h3>Something is wrong</h3>Let's fix it";
                
                switch($presentation){
/****************************************
$section = 'issue' $presentation = 'pallet' *****************************************************************
****************************************/
                    case 'pallet':
            	        $form_version           = 'jot/edit_pallet';
            	        $form_vars = ['id'      => $form_id,
            	                      'body'    => $hidden_fields.$info_boqx, 
        						      'enctype' => 'multipart/form-data',];
                    	$boqx_contents_edit = $display_state == 'view' ? $form_vars['body'] : elgg_view_form($form_version,$form_vars);
                            
            			$form_body = elgg_format_element('div',['id'=>$cid, 'class'=>['ItemEditValue','Item__nhjb4ONn','boqx-issue'], 'data-boqx'=>$parent_cid, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'boqx-fill-level'=>$fill_level], $boqx_contents_edit);
                        break;
                    
/****************************************
$section = 'issue' $presentation = 'carton' *****************************************************************
****************************************/
                    case 'carton':
            	        $form_version           = 'jot/edit_pallet';
                        if ($action == 'edit'){
                            $edit_boqx = elgg_format_element('div',['id'=>$cid, 'class'=>['ItemEditValue','Item__nhjb4ONn','boqx-issue','collapsed'], 'data-boqx'=>$preview_id, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'boqx-fill-level'=>$fill_level], $hidden_fields.$info_boqx);
                	        $form_vars = ['id'      => $form_id,
            	                          'body'    => $edit_boqx, 
            						      'enctype' => 'multipart/form-data',];
                        	$boqx_contents_edit = $display_state == 'view' ? $form_vars['body'] : elgg_view_form($form_version,$form_vars);
                            $form_body = elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'issue','boqx_id'=>$parent_cid,'cid'=>$preview_id,'edit_boqx'=>$boqx_contents_edit,'state'=>'show','presentation'=>$presentation,'presence'=>$presence,'mobility'=>'stationary','form_wrapper'=>$form_wrapper]);
                        }
                        else 
                            $form_body = elgg_view('page/elements/envelope',['task'=>'issue', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title,'has_collapser'=>'no','form_wrapper'=>$form_wrapper]);
                        break;
                    
/****************************************
$section = 'issue' $presentation = 'open_boqx' *****************************************************************
****************************************/
                    case 'open_boqx':
                        if ($action == 'edit'){
                            $preview_id = quebx_new_id('c');
                            if ($form_wrapper){
                                $form_wrapper['form_vars']['body']= $hidden_fields.$info_boqx;
                                $edit = elgg_view_form($form_wrapper['action'],$form_wrapper['form_vars'],$form_wrapper['body_vars']); 
                            }
                            else
                                $edit = $hidden_fields.$info_boqx;
                            $edit_boqx = elgg_format_element('div',['id'=>$cid, 'class'=>['ItemEditValue','Item__nhjb4ONn','boqx-issue','collapsed'], 'data-boqx'=>$preview_id, 'data-aspect'=>$aspect, 'data-guid'=>$guid, 'boqx-fill-level'=>$fill_level], $edit);
                            $form_body = elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'issue', 'boqx_id'=>$parent_cid, 'cid'=>$preview_id, 'edit_boqx'=>$edit_boqx,'state'=>'show','hidden_fields'=>$hidden_fields,'presentation'=>$presentation, 'form_wrapper'=>$form_wrapper]);
                        }
                        else 
                            $form_body = elgg_view('page/elements/envelope',['task'=>'issue', 'action'=>$action, 'guid'=>$guid, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'qid'=>$qid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx,'info_boqx'=>$info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title,'has_collapser'=>'yes','form_wrapper'=>$form_wrapper]);
                        break;
/****************************************
$section = 'issue' $presentation = 'qbox' *****************************************************************
** Note: Uses default processing
****************************************/
                        case 'qbox':
/****************************************
$section = 'issue' $presentation = default *****************************************************************
****************************************/
                    default:
                        $expansion      = elgg_extract('expansion', $vars);  //Received from 'main' when preloading panels
        				$guid = $expansion->guid;
                        $n = 0;
                        for ($i = $n++; $i <= $n+2; $i++) {
                            unset($child_cid, $panel, $title, $subtype, $this_compartment, $visibility);
                            $child_cid = quebx_new_cid ();//'c'.mt_rand(1,999);
                            $visibility = 'display:none';
                            switch ($i){
                                case 0 : $compartment = 'discovery'; $title = 'Discoveries'; $subtype = 'discovery'; $visibility = 'display:block'; break;
                                case 1 : $compartment = 'resolve'  ; $title = 'Remedies';    $subtype = 'effort';   break;
                                case 2 : $compartment = 'assign'   ; $title = 'Assign';      $subtype = 'task';     break;
                                case 3 : $compartment = 'complete' ; $title = 'Complete';    break;
                                default: continue;
                            }                                                                                   $display .= '5838 $subtype: '.$subtype.'<br>5838 $guid: '.$guid.'<br>';
                            $input_tabs[$i]      = ['title'=>$title, 
                                                    'class'=>'pallet-compartment', 
                                                    'selected' => $compartment == 'discovery', 
                                                    'compartment'=>'issue_'.$compartment , 
                                                    'action'     => $action,
                                                    'presentation'=> 'popup',
                                                    'parent_cid' => $cid, 
                                                    'cid'=>$child_cid];
                            $cid_array[$i]       =  $child_cid;
                            $pieces            = elgg_get_entities([
                                'type'           => 'object',
                                'subtype'        => $subtype,
                                'container_guid' => $guid,
                                'limit'          => 0,]);                                                       $display .= '5852 $pieces count: '.count($pieces).'<br>';
                            $this_compartment    = elgg_view('forms/experiences/edit', [
                                'action'         => $action,
                                'section'        => 'issue_'.$compartment,
                                'parent_cid'     => $cid,
                                'cid'            => $child_cid,
                                "{$subtype}s"    => $pieces,]);
                            $compartments       .= elgg_view('output/div', ['content'=> $this_compartment,
                                                                            'class'  => 'pallet-compartment',
                                                                            'options'=> ['compartment'     => 'issue_'.$compartment ,
                                                                                         'data-qid'        => $qid,
                                                                                         'data-parent-cid' => $cid,
                                                                                         'data-cid'        => $child_cid,
                                                                                         'aspect'          => 'issue_input',
                                                                                         'style'           => $visibility]
                                                                           ]);
                        }
        //                 $input_tabs[]    = array('title'=>'Discoveries', 'selected' => true , 'link_class' => '', 'link_id' => '', 'panel'=>'issue_discoveries', 'aspect'=>'issue_input');
        //                 $input_tabs[]    = array('title'=>'Remedies'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'issue_resolve'    , 'aspect'=>'issue_input');
        //                 $input_tabs[]    = array('title'=>'Assign'     , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'issue_assign'     , 'aspect'=>'issue_input');
        //                 $input_tabs[]    = array('title'=>'Complete'   , 'selected' => false, 'link_class' => '', 'link_id' => '', 'panel'=>'issue_complete'   , 'aspect'=>'issue_input');
                        $input_tabs      = elgg_view('navigation/tabs_slide', ['type'=>'vertical', 'aspect'=>'issue_input', 'tabs'=>$input_tabs, 'data-qid'=>$qid, 'data-cid'=>$cid]);
        /*                $discoveries_panel = elgg_view('forms/experiences/edit', [
                        'action'         => $action,
                        'section'        => 'issue_discovery',
                        'guid'           => $guid,
                        'qid'            => $qid,
                        'cid'            => $cid,]);
                        $resolve_panel     = elgg_view('forms/experiences/edit', array(
                            'action'         => $action,
                            'section'        => 'issue_resolve',
                            'guid'           => $guid,
                            'qid'            => $qid,
                            'parent_cid'     => $parent_cid,
                            'cid'            => $cid,
                            'service_cid'    => $service_cid,
                            'efforts'        => elgg_get_entities([
                                'type'           => 'object',
                                'subtype'        => 'effort',
                                'container_guid' => $expansion->guid,
                                'limit'          => 0,
                            ]),));
                        $assign_panel      = elgg_view('forms/experiences/edit', array(
                            'action'         => $action,
                            'section'        => 'issue_request',
                            'guid'           => $guid,));
                        $complete_panel    = elgg_view('forms/experiences/edit', array(
                            'action'         => $action,
                            'section'        => 'issue_complete',
                            'guid'           => $guid,));
        *//*                $content_body .="
                            <div class='rTable' style='width:100%'>
                        		<div class='rTableBody'>
                        				<div class='rTableRow'>
                        					<div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs
                                            </div>
                        					<div class='rTableCell' style='width:85%;padding:5px;'>
                                        		<div panel='issue_discoveries' data-qid='$qid' data-cid='$cid' aspect='issue_input' id='issue_discoveries_panel'   class='elgg-head' style='display:block'>
                                        		    $discoveries_panel</div>
                                        		<div panel='issue_resolve'    data-qid='$qid' aspect='issue_input' id='issue_resolve_panel'   class='elgg-head' style='display:none' action='$action'>
                                        		    $resolve_panel</div>
                                        		<div panel='issue_assign'     data-qid='$qid' aspect='issue_input' id='issue_assign_panel' class='elgg-head' style='display:none'>
                                        		    $assign_panel</div>
                                        		<div panel='issue_complete'   data-qid='$qid' aspect='issue_input' id='issue_complete_panel' class='elgg-head' style='display:none'>
                                        		    $complete_panel</div>
                                            </div>
                                        </div>
                                </div>
                            </div>";*/
                        $content_body .="
                        <div class='rTable' style='width:100%'>
                            <div class='rTableBody'>
                                <div class='rTableRow'>
                                    <div class='rTableCell' style='width:15%;padding:0px;vertical-align:top'>$input_tabs</div>
                                    <div class='rTableCell' style='width:85%;padding:5px;'>$compartments</div>
                                </div>
                            </div>
                        </div>";
                        
                        if ($presentation == 'qbox'){
                        	$form_body = elgg_view('output/div',['content'=>$content_body.$hidden_fields,
                        			                             'class'=>'elgg-head qbox-content-expand',
                        			                             'options'=>['data-qid'=>$qid]]);                	
                        }
                        else {$form_body = elgg_view('output/div',['content'=>$content_title.$content_body.$hidden_fields,
                        		                                   'class'  => 'elgg-head experience-panel',
                                                                   'options'=>['panel'      => 'Issue',
                                                                               'guid'       => $guid,
                                                                               'aspect'     => 'attachments',
                                                                               'data-qid'   => $qid,
                                                                               'parent-cid' => $parent_cid,
                                    		                                   'data-cid'   => $cid,
                                                                               'action'     => $action,
                                                                               'style'      => $vars['visibility']]]);}
                }
                //register_error($display);
                break;
/****************************************
$section = 'issue_discovery'              *****************************************************************
****************************************/
            case 'issue_discovery':
                $expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
							'data-cid'=> $cid,
                            'tabindex'=> '-1',]);
                $collapser = elgg_view("output/url", array(
                            'text'    => '',
//                            'href'    => '#',
                            'class'   => 'collapser autosaves',
                            'id'      => 'toggle_marker',
							'data-cid'=> $cid,
                            'tabindex'=> '-1',
                          ));
                switch($snippet){
                    case 'discovery':
                        $form_body = $discovery;
                        break;
                    case 'marker':
		                $form_body = $issue_discovery_marker;
            			break;
            		default:
	            	$form_body .= $hidden_fields;
	            	$form_body .= elgg_format_element('div', ['class'=>["panel","icebox_2068141","icebox","items_draggable","visible"],'id'=>'panel_icebox_2068141','data-aid' =>'Panel','data-type'=>'icebox','data-cid' => $cid,'data-qid' => $qid],
	        	                      elgg_format_element('div', ['class'=>['container','droppable','sortable','tn-panelWrapper___fTILOVmk','data-reactroot'=>'']],
	                                      $panel_header.
	                                      elgg_format_element('div', ['class'=>'rTable','style'=>'width:100%'],
	                                          elgg_format_element('div',['class'=>['rTableBody','ui-sortable','id'=>'sortable_item']],
	                                               elgg_format_element('div', ['class'=>'new_progress_marker'])))));
	            	/*
	            	$form_body .= elgg_view('output/div', ['options'=>['id'   =>'line_store',
	            	                                                   'style'=>'display:none;'],
	            	                                       'content'=> elgg_view('output/div',['class'  =>'progress_marker_line_items',
	            	                                                                           'content'=> $story_model])]);
	            	*/
	                break;
				}
				break;
/****************************************
$section = 'issue_remedy'              *****************************************************************
****************************************/
            case 'issue_remedy':
                $expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
							'data-cid'=> $cid,
                            'tabindex'=> '-1',]);
                $collapser = elgg_view("output/url", array(
                            'text'    => '',
//                            'href'    => '#',
                            'class'   => 'collapser autosaves',
                            'id'      => 'toggle_marker',
							'data-cid'=> $cid,
                            'tabindex'=> '-1',
                          ));
                switch($snippet){
                    case 'remedy':
                        $form_body = $remedy;
                        break;
                    case 'marker':
		                $form_body = $issue_remedy_marker;
            			break;
            		default:
	            	$form_body .= $hidden_fields;
	            	$form_body .= elgg_view('output/div', ['class'=>"panel icebox_2068141 icebox items_draggable visible", 
	        	                                           'options' => ['id'       =>'panel_icebox_2068141',
	        	                                                         'data-aid' =>'Panel',
	        	                                                         'data-type'=>'icebox',
	        	                                                         'data-cid' => $cid,
	        	                                                         'data-qid' => $qid],
	        	                                           'content' => elgg_view('output/div', ['class'   => 'container droppable sortable tn-panelWrapper___fTILOVmk',
	                                             	                                             'options' => ['data-reactroot'=>''],
	                                            	                                             'content' => $panel_header.
	                                            	                                                          elgg_view('output/div', ['class'  => 'rTable',
	                                            	                                                                                   'options'=> ['style'=>'width:100%'],
	                                            	                                                                                   'content'=> elgg_view('output/div',['class'=>'rTableBody ui-sortable',
	                                            	                                                                                                                       'options'=>['id'=>'sortable_item'],
	                                            	                                                                                                                       'content'=>elgg_view('output/div', ['class'=>'new_progress_marker'])])])])]);
	            	/*
	            	$form_body .= elgg_view('output/div', ['options'=>['id'   =>'line_store',
	            	                                                   'style'=>'display:none;'],
	            	                                       'content'=> elgg_view('output/div',['class'  =>'progress_marker_line_items',
	            	                                                                           'content'=> $story_model])]);
	            	*/
	                break;
				}
				break;
/****************************************
$section = 'issue_resolve'              *****************************************************************
****************************************/
            case 'issue_resolve':
			    switch($snippet){
            		case 'marker':
            			if ($disabled      == 'disabled'){$disabled_label      = ' (disabled)';}
            			if ($disabled_view == 'disabled'){$disabled_view_label = ' (disabled)';}
            			$form_body .= "	   	<div class='edit details issue_resolve-marker expanded' data-cid='$cid' data-guid='$guid' data-qid='$qid' action='$action'>
		    					                   <section class='edit' data-aid='StoryDetailsEdit' tabindex='-1'>
		                                              <section class='model_details'>
		                                                  <section class='story_or_epic_header'>
		                                                    <a class='autosaves collapser-effort' id='effort_collapser_$cid' data-cid='$cid' tabindex='-1'></a>
		                            					    <fieldset class='name'>
		                            					        <div data-reactroot='' class='AutosizeTextarea___2iWScFt62' style='display:flex'>
		                            					            <div class='AutosizeTextarea__container___31scfkZp'>
		                            					               $marker_title
		                            					            </div>
		                                                            <div class='persistence use_click_to_copy' style='order:1'>
		                                                               $add_effort
		                                                            </div>
		                            					        </div>
		                            					     </fieldset>
		                            					  </section>
		                            					  <aside>
		                                                    <div class='wrapper'>
		                                                      <nav class='edit'>
		                                                          <section class='controls'>
		                                                              <div class='actions'>
		                                                                  <div class='bubble'></div>
		                                                                  <button type='button' id='story_copy_link_$cid' title='Copy this effort&#39;s link to your clipboard".$disabled_label."' data-clipboard-text='$url/view/$id_value' class='autosaves clipboard_button hoverable link left_endcap' tabindex='-1' $disabled></button>
		                                                                  <div class='button_with_field'>
			                                                                  <button type='button' id ='story_copy_id_$cid' title='Copy this effort&#39;s ID to your clipboard".$disabled_label."' data-clipboard-text='$id_value' class='autosaves clipboard_button hoverable id use_click_to_copy' tabindex='-1' $disabled></button>
			                                                                  <input type='text' id='story_copy_id_value_$cid' readonly='' class='autosaves id text_value' value='$id_value' tabindex='-1'>
		                                                                </div>
		                                                                <button type='button' id='receipt_import_button_$cid' title='Import receipt (disabled)' class='autosaves import_receipt hoverable left_endcap' tabindex='-1' disabled></button>
		                                                                <button type='button' id='story_clone_button_$cid' title='Clone this effort".$disabled_view_label."' class='autosaves clone_story hoverable left_endcap' tabindex='-1' $disabled $disabled_view></button>
		                                                                <button type='button' id='story_history_button_$cid' title='View the history of this effort".$disabled_view_label."' class='autosaves history hoverable capped' tabindex='-1' $disabled $disabled_view></button>
		                                                                <button type='button' id='story_delete_button_$cid' title='Delete this effort".$disabled_view_label."' class='autosaves delete hoverable right_endcap remove-card' data-qid=$qid tabindex='-1'$disabled $disabled_view></button>
		                                                              </div>
		                                                            </section>
		                                                      </nav>
		                                                      <div class='story info_box' style='display:block'><!-- hidden -->
		                                                        <div class='info'>
		                                                            <div class='type row'>
		                                                              <em>Effort Type</em>
		                                                              <div class='dropdown story_type'>
		                                                                  <input aria-hidden='true' type='text' id='story_date_dropdown_{$cid}_honeypot' tabindex='0' class='honeypot'>
		                                                                  $marker_type_selector
			                                                            </div>
			                                                        </div>
			                                                        <div class='date row'>
			                                                          <em>Date</em>
			                                                          <div class='dropdown story_date'>
			                                                            <input aria-hidden='true' type='text' id='story_date_dropdown_{$cid}_honeypot' tabindex='-1' class='honeypot'>
			                                                            $marker_date_picker
			                                                          </div>
			                                                        </div>
			                                                       <div class='state row'>
			                                                          <em>State</em>
			                                                          <div class='dropdown story_current_state disabled'>
			                                                            <input aria-hidden='true' type='text' id='story_current_state_dropdown_{$cid}_honeypot' tabindex='-1' class='honeypot'>
			                                                            $marker_state_selector
			                                                        </div>
			                                                    </div>
		                                                        <div class='requester row'>
		                                                          <em>Organization</em>
		                                                          <div class='dropdown story_org_id'>
		                                                            <input aria-hidden='true' type='text' id='story_scribe_id_dropdown_{$cid}_honeypot' tabindex='0' class='honeypot'>
		                                                            <a id='effort_org_id_dropdown_$cid' class='selection item_2936271' tabindex='-1'><span><div class='name'>??</div></span></a>
		                                                          <section>
		                                                            <div class='dropdown_menu search'>
		                                                                  <div class='search_item'><input aria-label='search' type='text' id='effort_org_id_dropdown_{$cid}_search' class='search'></div>
		                                                              <ul>
		                                                                  <li class='no_search_results hidden'>No results match.</li>
		                                                                  <li data-value='2936271' data-index='1' class='dropdown_item selected'><a class='item_2936271 ' id='2936271_effort_org_id_dropdown_$cid' href='#'><span><span class='dropdown_label'>??</span><span class='dropdown_description'>??</span></span></a></li>
		                                                              </ul>
		                                                            </div>
		                                                          </section>
		                                                        </div>
		                                                      </div>
		                                                        <div class='participant row'>
		                                                          <em>Participants</em>
		                                                          <div class='story_participants'>
		                                                          <input aria-hidden='true' type='text' id='story_participant_ids_{$cid}_honeypot' tabindex='0' class='honeypot'>
		                                                          <a id='add_participant_$cid' class='add_participant selected' tabindex='-1'></a>
		                                                          $marker_participant_link
		                                                        </div>
		                                                  </div>
		                                                  <div class='mini attachments'></div>
		                                                </div>
		                                              </aside>
		                                        	</section>
		                                        	<section class='description full'>
		                                        	    <div data-reactroot='' data-aid='Description' class='Description___Dljkfzd5 issue_resolve-marker'>
		                                        	        $service_panel
		                                        	    </div>
		                                        	</section>
		                                        	<section class='media full'>
		                                        	    <div data-reactroot='' class='Activity___2ZLT4Ekd'>
		                                        	        <div class='GLOBAL__activity comment CommentEdit___3nWNXIac CommentEdit--new___3PcQfnGf' tabindex='-1' data-aid='comment-new'>
		                                        	        </div>
		                                        	     </div>
		                                        	 </section>
		    					                 </section>
		    					               </div>";
            			break;
            		case 'new_effort':
            			$content   = elgg_view('partials/form_elements',
							    		 ['element' => 'new_effort',
							    		  'guid'    => $guid,
							    		  'cid'     => $cid,
							    		  'parent_cid'=>$parent_cid,
							    		  'service_cid'=> $service_cid,
							    		  'qid'     => $qid,]);
            			$form_body .="<div class='TaskEdit___1Xmiy6lz issue_resolve-default' data-qid='$qid' data-cid='$cid' data-guid='$guid'>
							<div tabindex='0' class='AddSubresourceButton___2PetQjcb' data-aid='TaskAdd' data-focus-id='TaskAdd' data-cid='$cid' data-guid='$guid'>
								 <span class='AddSubresourceButton__icon___h1-Z9ENT'></span>
								 <span class='AddSubresourceButton__message___2vsNCBXi'>Add a remedy</span>
							</div>
							<div class='TaskShow___2LNLUMGe' data-aid='TaskShow' data-cid=$cid style='display:none' draggable='true'>
								<input type='checkbox' title='mark task complete' data-aid='toggle-complete' data-focus-id='TaskShow__checkbox--$cid' class='TaskShow__checkbox___2BQ9bNAA'>
								<div class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription' tabindex='0'>
									<span class='TaskShow__title___O4DM7q tracker_markup section-issue_resolve'></span>
									<span class='TaskShow__description___qpuz67f tracker_markup'></span>
									<span class='TaskShow__service_items___2wMiVig tracker_markup'></span>
								</div>
								<nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'>
									<button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete' data-cid='$cid'>
										$delete
									</button>
								</nav>
							</div>
	                        <div class='EffortEdit_fZJyC62e' style='display:none' data-aid='TaskEdit' data-parent-cid='$parent_cid' data-cid=$cid>
	                        	$content
							</div>
						</div>";
            			break;
            		default:
            			unset($hidden, $hidden_fields);                                                          $display .= '6138 $efforts # '.count($efforts).'<br>';
            			//$hidden['jot[observation][effort][boqx]'] = 'effort';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
		                    $new_effort     = elgg_view('forms/experiences/edit',
    		                    ['action'      => $action,
    		                     'section'     => 'issue_effort',
    		                     //'snippet'=>'new_effort',
    		                     'qid'         => $qid,
    		                     'parent_cid'  => $cid,
    		                     'service_cid' => $service_cid,
    		                     'guid'        => $guid,
    		                     'efforts'     => $efforts,
    		                    ]);
		            	$form_body     .= $new_effort;
		            	
		                break;
				}                                                                                                   //register_error($display);
				break;
/****************************************
$section = 'issue_effort'                *****************************************************************
 * Provides a floating 'Add effort' section to the Issue>Remedies panel
****************************************/
            case 'issue_effort':
				Switch ($snippet){
					case 'marker':                                                                             $display .= '4471 issue_effort>marker $service_cid: '.$service_cid.'<br>';
	            	    $form_body .= $hidden_fields;
						
						$form_body .= "
							<div class='Effort__CPiu2C5N section-issue_effort-marker' data-qid='$qid' data-parent-cid='$parent_cid' data-cid='$cid' data-guid='$guid' data-aid='$action'>
							{$issue_effort_add}{$issue_effort_show}{$issue_effort_edit}
							</div>";
						break;
					case 'service_item':                                                                      $display.= '4479 $cid: '.$cid.'<br>';

						break;
					case 'list_efforts':
					case 'view_effort':
						$form_body .= str_replace('__cid__', $cid, $story_model);
						break;
					default:
						$form_body .= $hidden_fields;
						$form_body .= "<div data-aid='Efforts' class='issue_effort-default' action ='$action'>
							<span class='efforts-eggs' data-aid='effortCounts' data-cid='$parent_cid' data-qid='$qid'><h4>Efforts to fix this issue ...</h4>
							</span>
							$issue_efforts
						</div>";                                                                          $display .= '4491 issue_effort-default ... <br>4050 $cid: '.$cid.'<br>4050 $service_cid: '.$service_cid;
	                break;
				}                                                                                         //register_error($display);
	                                            	                                                          
				break;
/****************************************
$section = 'issue_effort_service'        *****************************************************************
****************************************/
            case 'issue_effort_service':                                                                 $display .= '4500 issue_effort_service>$service_cid: '.$service_cid.'<br>';
				$expander = elgg_view("output/url", [
                            'text'    => '',
                            'class'   => 'expander undraggable',							
                            'id'      => 'toggle_marker',
							'data-cid'=> $service_cid,
                            'tabindex'=> '-1',]);
            	$url = elgg_get_site_url().'jot';                                                        $display .= '4512 $effort->guid: '.$effort->guid.'<br>';
            	$marker_title = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$service_cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___2W_xAa_R' name='jot[observation][effort][$parent_cid][$service_cid][title]' placeholder='Give this effort a name'></textarea>";
            	$marker_date_picker = elgg_view('input/date', ['name'  => "jot[observation][effort][$parent_cid][$service_cid][moment]", 'style'=>'width:75px; height: 20px;']);
            	$marker_type_selector = elgg_view_field(['#type'    =>'select',
            	                                         'name'    =>"jot[observation][effort][$parent_cid][$service_cid][type]",  
            			                                 'options_values' =>['investigation'=>'Investigation', 'repair'=>'Repair', 'test'=>'Test']
            	                                         ]);
            	$owner                 = get_entity($owner_guid ?: elgg_get_logged_in_user_guid());
            	$marker_work_order_no  = elgg_view_field(['#type'   => 'text',
            			                                  'name'    => "jot[observation][effort][$parent_cid][$service_cid][wo_no]"]);
            	$marker_participant_link     = elgg_view('output/div',['class'=>'dropdown', 
            			                                         'content' =>elgg_view('output/url',['class'=>'selection',
						            			                                 'href' => "profile/{$owner->username}",
						            			                                 'text' => elgg_view('output/span',['content'=>elgg_view('output/div',['class'=>'name', 'options'=>['style'=>'float:right'], 'content'=>$owner->name,]).
						            			                                 		                                       elgg_view('output/div',['class'=>'initials', 'options'=>['style'=>'float:right'], 'content'=>'SJ'])])])]);
            	$add_story_button = elgg_view('output/url', array(
	                                        'title'    => 'Add service effort',
	                		                'data-element' => 'new_service_effort',
            								'data-qid'     => $qid,
            								'data-cid'     => $service_cid,
	                    				    //'text'     => '+',
	                    					//'href'     => '#',
	                    					//'class'    => 'add-progress-marker addButton___3-z3g3BH',
	                    					'class'    => 'trigger-element add_marker',
	                                        'tabindex' => '-1' 
	                    					));
				Switch ($snippet){
					case 'marker1':
/*            			unset($hidden, $hidden_fields);
		                $hidden["jot[observation][effort][$parent_cid][$cid][aspect]"] = 'service task';
		                if (!empty($hidden)){                
		                    foreach($hidden as $field=>$value){
		                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
						$service_item_header_selector = elgg_view('output/span', ['content'=>
					    								elgg_view('output/url', [
									    				    'text'          => '+',
									    					'href'          => '#',
															'class'         => 'elgg-button-submit-element new-item',
															'data-element'  => 'new_service_item',
									    					'data-qid'      => $qid,
									    					'data-cid'      => $cid,
					    									'data-parent-cid'=>$parent_cid,
									    					'data-rows'     => 0,
									    					'data-last-row' => 0,
									    					]), 
					    								'class'=>'effort-input']);
					    $service_item_header_qty   = 'Qty';
					    $service_item_header_item  = 'Item';
					    $service_item_header_tax   = 'tax';
					    $service_item_header_cost  = 'Cost';
					    $service_item_header_total = 'Total';
					    $delete_button = elgg_view('output/url', array(
		                            	        'title'=>'remove progress marker',
		                            	        'text' => elgg_view_icon('delete-alt'),
		                            	    ));
		            	$delete = elgg_view("output/span", ["content"=>$delete_button]);
		            	$service_marker_title       = "<textarea data-aid='name' tabindex='0' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy2 NameEdit___Mak{$cid}_{$n}' style='padding-top: 0px;margin: 8px;' name='jot[observation][effort][$parent_cid][$cid][title]' placeholder='Service name'></textarea>";
		            	$service_marker_description = "<textarea name='jot[observation][effort][$parent_cid][$cid][description]' aria-labelledby='description$cid' data-aid='textarea' data-focus-id='ServiceEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy editor___1qKjhI5c tracker_markup' style='margin: 0px 0px 3px;display: block;' placeholder='Describe the service'></textarea>";
		                $line_items_header= "          
						    <div class='rTable line_items service-line-items'>
								<div class='rTableBody'>
									<div id='sortable'>
										<div class='rTableRow pin'>
							                <div class='rTableCell'>$service_item_header_selector</div>
							    			<div class='rTableHead'>$service_item_header_qty</div>
							    			<div class='rTableHead'>$service_item_header_item</div>
							    			<div class='rTableHead'>$service_item_header_tax</div>
							    			<div class='rTableHead'>$service_item_header_cost</div>
							    			<div class='rTableHead'>$service_item_header_total</div>
							    		</div>
							    		<div id={$cid}_new_line_items class='new_line_items'></div>
							    	</div>
						    	</div>
						    </div>
				    		<div id={$cid}_line_item_property_cards></div>";
*/	            	    $form_body .= $hidden_fields;
						$form_body .="<div class='ServiceEffort__26XCaBQk issue_effort_service-marker1' data-qid='$qid' data-parent-cid='$parent_cid' data-cid='$cid' data-guid='$guid'>
							$issue_effort_service_add
							$issue_effort_service_show
							$issue_effort_service_edit
						</div>";
			            break;
					case 'service_item':                                                                      $display.= '4648 $cid: '.$cid.'<br>';
	            	    $form_body .= $hidden_fields;
						$form_body .= $service_item_row;
						break;
					case 'service_item_properties':
//						$form_body .="<div id={$cid}_{$n} class='qboqx-dropdown qboqx-dropdown-tip qboqx-dropdown-relative'>
					    $form_body .="<div id={$cid}_{$n} class='qboqx-dropdown qboqx-dropdown-tip'>
					                      <div class='qboqx-dropdown-panel'>".
				                              elgg_view('forms/market/properties', [
					    	                                   'element_type'   =>'service_item',
					    	                                   'container_type' => 'experience',
						                                       'qid'            => $qid,
				                                          	   'qid_n'          => $qid_n,
				                                          	   'cid'            => $cid,
				                                          	   'parent_cid'     => $parent_cid,
				                                          	   'n'              => $n,])."
			                               </div>
			                           </div>";
						break;
					default:
						if ($action == 'add' || $action == 'edit'){
							$add_task .= elgg_view('forms/experiences/edit',[ 
													  'action'    => 'add',
													  'section'    =>'issue_effort_service', 
													  'snippet'   =>'marker1',
													  'effort'    => $effort,      
													  'parent_cid'=> $parent_cid, 
													  'n'         => $n, 
													  'cid'       => $service_cid, 
													  'qid'       => $qid,]);
						}
	            	    $form_body .= $hidden_fields;
						$form_body .= "<div data-aid='Tasks' class='issue_effort_service-default'>
							<span class='tasks-count' data-aid='taskCounts' data-cid='$parent_cid'><h4>What I did ...</h4>
							</span>
							$add_task
						</div>";                                              						$display .= '4739 issue_effort_service-default ... <br>4739 $cid: '.$cid.'<br>4739 $service_cid: '.$service_cid.'<br>4739 $effort->guid: '.$effort->guid;
						                                                                            //register_error($display);
						break;
				}               
	                                            	                                                          
				break;
/****************************************
$section = default                        *****************************************************************
****************************************/
            default:
				    if($presentation == 'qbox_experience' ||
				        $presentation == 'popup'){
				           $div_id = elgg_extract('qid', $vars);}
                else                       {$div_id = 'Gallery_panel';}
                if (!empty($hidden)){                
                    foreach($hidden as $field=>$value){
                        $hidden_fields .= elgg_view('input/hidden', array('name'=>$field, 'value'=>$value));}}
                $content .= elgg_view('output/div',['content'=>$hidden_fields.$section,
                		                            'class'  => 'elgg-head',
                		                            'options'=>['panel'=>$section,'guid'=>$guid,'aspect'=>'attachments','id'=>$div_id, 'style'=>$style]
                ]);
                if ($presentation == 'qbox'){
                	$vars['content']=$content;
					$form_body = elgg_view('jot/display/qbox',$vars);
                }
        }
echo $form_body;
//register_error($display);