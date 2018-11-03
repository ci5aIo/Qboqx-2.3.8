<!-- page: mod\jot\pages\jot\view.php -->
<!-- view: mod\jot\views\default\object\experience.php -->
<?php
$full          = elgg_extract('full_view', $vars, FALSE);                             $display .= 'object\experience<br>4 full_view '.$full.'<br>';
$section       = elgg_extract('this_section', $vars, 'Summary');
$experience    = elgg_extract('entity', $vars, FALSE);
$guid          = $experience->guid;
$container_guid= $experience->container_guid;
$item_guid     = elgg_extract('asset', $vars, $experience->asset);
$selected      = elgg_extract('selected', $vars);
$list_type     = elgg_extract('list_type', $vars, 'page');                            $display .= '11 $list_type = '.$list_type.'<br>';
$space         = elgg_extract('space', $vars, 'market');
$context       = elgg_extract('context', $vars, 'market');
$perspective   = elgg_extract('perspective', $vars, 'view');
    if (empty($item_guid)){$item_guid = $experience->assets;}
$item          = get_entity($item_guid);
$view          = get_subtype_from_id($experience->guid);
$owner         = $experience->getOwnerEntity();
$tu            = $experience->time_updated;
$container     = $experience->getContainerEntity();
$jot           = elgg_get_excerpt($experience->description);
$subtype       = $experience->getSubtype();
$aspect        = $experience->aspect;
$owner_icon    = elgg_view_entity_icon($owner, 'tiny', array('use_hover'=>false));
$metadata      = elgg_view_menu('entity', array(
                	'entity' => $vars['entity'],
                	'handler' => 'jot',
                	'sort_by' => 'priority',
                	'class' => 'elgg-menu-hz',
                ));
$options     = ['type'                 => 'object',
				'relationship_guid'    => $container_guid,
				'inverse_relationship' => true,
				'limit'                => false];
$attachments = elgg_get_entities_from_metadata($options);
$standard_attachments=['things', 'documents', 'gallery'];
if ($attachments){
	foreach($attachments as $attachment){
		if($attachment->getSubtype() == 'experience'){
		$extended_experience = $attachment;
		continue;
		}
	}
}                                                                                    //$display .= print_r($extended_experience, true);
Switch ($list_type){
    case 'brief':
        if ($aspect  == 'instruction'){
            $selected = 'Instructions';
        }
        else {
            $selected = ucfirst($aspect);
        }

            $i = $experience;
//  			$list_body = elgg_view('output/url', array('text' => $i->title,'href' =>  "jot/view/{$i->guid}"));
 			elgg_load_library('elgg:market');
 			$content =  market_render_section(array(
                                 'section'    => 'experience',
                                 'selected'   => $selected,
                                 'action'     => 'view',
                                 'entity'     => $experience,));
 			$params = array(
        		'header'   => $header,
        		'metadata' => $metadata,
        		'subtitle' => $subtitle,
        		'tags'     => 'none',
        	    'content'  => $content,
        	);
        
        	$params = array_merge($params, $vars);
        	$list_body = elgg_view('object/elements/summary', $params);
			$body      = elgg_view_image_block($owner_icon, $list_body);
			$body     .= 'here';
        break;
    case 'page':
        $expansion_objects  = elgg_get_entities(['type'=>'object', 'container_guid'=>$guid]);
        $expansion          = $expansion_objects[0]; // There must be only one expansion of an experience
        if ($expansion) {
            $selected       = $expansion->aspect;
        }
        else {
        	$selected       = 'things';
        }
/*    	$tab_vars  = ['menu'         => 'q_expand',
                      'guid'         => $guid,
        		      'class'        => "qbox-$guid",
					  'qid_parent'   => $qid,
					  'selected'     => $selected,];
        $expand_tabs =  elgg_view('jot/menu', $tab_vars);*/
    	$forms_action =  'forms/experiences/edit';
		$body_vars = ['guid'          => $guid,
				      'container_guid'=> $guid,
				      'qid'           => $qid.'_1',
                      'section'       => 'things',
				      'selected'      => true,
//				      'style'           => 'display:none;',
				      'presentation'  =>'qbox_experience',
				      'action'        => 'view'];
        $thing_panel = elgg_view($forms_action, $body_vars);
        $thing_count = elgg_get_entities_from_relationship_count(['type'                 => 'object',
        		                                                  'subtypes'             => ['market'],
																  'relationship'         => 'experience',
																  'relationship_guid'    => $guid,
																  'inverse_relationship' => false,
																  'limit'                => 0,]);
		$tab_vars  = ['subtype'      => 'experience',
		              'this_section' => $selected,
		              'state'        => 'selected',
		              'action'       => 'view',
		              'guid'         => $guid,
		        	  'presentation' => 'qbox',
					  'class'        => "qbox-$guid",
		              'ul_style'     => 'border-bottom: 0px solid #cccccc',
		              'ul_aspect'    => 'attachments',
					  'link_class'   => 'qbox-q qbox-menu',
					  'attachments'  => ['things'=>$thing_count]];
		$tabs      =  elgg_view('quebx/menu', $tab_vars);
		
		$content = elgg_view('forms/experiences/edit',[
        				'guid'          => $guid,
				        'qid'           => 'q'.$guid.'_01',
        				'container_guid'=> $container_guid,
						'section'       => 'main',
						'tabs'          => $tabs,
						'expand_tabs'   => $expand_tabs,
						'selected'      => true,
						'presentation'  =>'qbox_experience',
                                         'action'     => 'view', 
//						'preloaded_panels'=>$thing_panel,
						'preload_panels'=>['things'],]);
		$content = "<div class = 'inline-content-expand'>
				<div class='inline inline-visible' role='display' tabindex='-1' data-space='$space' data-perspective='$perspective' data-context = '$context'>
						<div id='inlineLoadedContent'>
							$title
							<div class='elgg-body inline-body'>
								<div class='elgg-layout elgg-layout-default clearfix'>
                                    $content
                                </div>
                            </div>
						</div>
				</div>
			</div>";
    	$params = array(
        	'metadata'      => $metadata,
    	    'tags'          => 'none',
    	    'content'       => $content,
				    'body_class'    => 'inline-body',
    	    'show_comments' => false,
    	);
    
    	$params = array_merge($params, $vars);
    	$list_body = elgg_view('object/elements/list', $params);
        	
		    	$body = "<div class='jot elgg-content'>".
		    	         elgg_view_image_block($owner_icon, $list_body, $params).
		    	         "</div>";
        break;
    case 'list':
    default:
    	$tab_vars  = ['menu'         => 'q_expand',
                      'guid'         => $guid,
        		      'class'        => "qbox-$guid",
					  'qid_parent'   => $qid,];
        $expand_tabs =  elgg_view('jot/menu', $tab_vars);
    	$forms_action =  'forms/experiences/edit';
		$body_vars = ['guid'    => $guid,
				      'container_guid'=> $guid,
				      'qid'     => $qid.'_1',
                      'section' => 'things',
				      'selected'=> true,
				      'style'   => 'display:none;',
				      'presentation'=>'qbox_experience',
				      'action'  => 'view'];
        $thing_panel = elgg_view($forms_action, $body_vars);
		$tab_vars  = ['subtype'      => 'experience',
		              'this_section' => 'Expand',
		              'state'        => 'selected',
		              'action'       => 'add',
		              'guid'         => $guid,
		        	  'presentation' => 'qbox',
					  'class'        => "qbox-$guid",
		              'ul_style'     => 'border-bottom: 0px solid #cccccc',
		              'ul_aspect'    => 'attachments',
					  'link_class'   => 'qbox-q qbox-menu',
					  'attachments'  => ['things'=>1]];
		$tabs      =  elgg_view('quebx/menu', $tab_vars);
		
		$content = elgg_view('forms/experiences/edit',[
        				'guid'          => $guid,
        				'container_guid'=> $container_guid,
						'section'       => 'main',
						'tabs'          => $tabs,
						'expand_tabs'   => $expand_tabs,
						'selected'      => true,
						'presentation'  =>'qbox_experience',
                                             'action'     => 'view', 
						'preloaded_panels'=>$thing_panel,]);
		$content = "<div class = 'inline-content-expand'>
				<div class='inline inline-visible' role='display' tabindex='-1' data-space='$space' data-perspective='$perspective' data-context = '$context'>
						<div id='inlineLoadedContent'>
							$title
							<div class='elgg-body inline-body'>
								<div class='elgg-layout elgg-layout-default clearfix'>
                                    $content
                                </div>
                            </div>
						</div>
				</div>
			</div>";
        	$params = array(
        		'metadata' => $metadata,
		    	    'tags'          => 'none',
        	    'content'  => $content,
		    	    'show_comments' => false,
        	);
        
        	$params = array_merge($params, $vars);
		    	$list_body = elgg_view('object/elements/list', $params);
        	
		    	$body = "<div class='jot elgg-content'>".
		    	         elgg_view_image_block($owner_icon, $list_body).
		    	         "</div>";
}
	
echo $body;
register_error($display);