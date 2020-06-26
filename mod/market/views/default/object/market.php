<!-- View: market\views\default\object\market.php<br>-->
<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$full       = elgg_extract('full_view', $vars, null);                     $display .= '13 $full: '.$full.'<br>';
$compartment= elgg_extract('compartment', $vars, 'Summary');
$item       = elgg_extract('entity', $vars, FALSE);
$view_type = elgg_extract('view_type', $vars, elgg_extract('perspective', $vars));
if ($full){$view_type  = 'full_view';}
$marketpost = $vars['entity'];
$guid = $item->getGUID();
                                                                             $display .= '18 $view_type: '.$view_type.'<br>';
if (!empty($marketpost->icon)){$icon_guid = $marketpost->icon;}              
else {$icon_guid = $marketpost->guid;}                                       $display .= '20 $icon_guid: '.$icon_guid.'<br>';

if (!$marketpost) {return TRUE;}

$currency = elgg_get_plugin_setting('market_currency', 'market');

$owner = $marketpost->getOwnerEntity();
$tu = $marketpost->time_updated;
$container = $marketpost->getContainerEntity();
$category_link = elgg_view('output/url', array(
        'href' => "queb?y=$marketpost->category",
        'text' => get_entity($marketpost->category)->title,
));
/*$category_link = elgg_view('output/url', array(
	'href' => "market/category/{$marketpost->marketcategory}",
	'text' => elgg_echo("market:category:{$marketpost->marketcategory}"),
));*/
$category = "<b>" . elgg_echo('market:category') . ":</b> " . $category_link;
//$category = "<b>" . elgg_echo('market:category') . ":</b> " . elgg_echo("market:category:{$marketpost->marketcategory}");

$excerpt = elgg_get_excerpt($marketpost->description);

$owner_link = elgg_view('output/url', array(
	'href' => "market/owned/{$owner->username}",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$image = elgg_view('market/thumbnail', array('marketguid' => $icon_guid, 'size' => 'medium', 'tu' => $tu));
$market_img = elgg_view('output/url', array(
	'href' => "market/view/$owner->username",
	'text' => $image,
));

$tags = elgg_view('output/tags', array('tags' => $marketpost->tags));
$date = elgg_view_friendly_time($marketpost->time_created);

if(isset($marketpost->custom) && elgg_get_plugin_setting('market_custom', 'market') == 'yes'){
	$custom = "<br><b>" . elgg_echo('market:custom:text') . ": </b>" . elgg_echo($marketpost->custom);
}

$comments_count = $marketpost->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $marketpost->getURL() . '#market-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

// Create the general view menu.  Some items, such as cars, have their own specific view menu.
$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'market',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

//if ($full && !elgg_in_context('gallery')) {
Switch ($view_type){
	case 'full_view':
			$extra = elgg_view('market/display/item',array('entity'=>$item, 'this_section'=>$compartment));
			$subtitle = "{$category}{$custom}{$supplemental}{$pick}<br>{$author_text} {$date} {$comments_link}";
			$params = array(
				'entity' => $item,
				'metadata' => $metadata,
				'subtitle' => $subtitle,
				"class"      => "item-tools-item",
			);
			if ($vars['presentation'] == 'inline'){unset($params['metadata']);}
			$params = $params + $vars;
			$summary = elgg_view('object/elements/summary', $params);
		
			$text = elgg_view('output/longtext', array('value' => $item->description, 'style'=>'max-height:100px;overflow:auto;'));
			if (in_array($compartment, array('Summary','Details'))){
			    $body = "$text $extra";}
			else {$body = $extra;}
		
			$item_icon = elgg_view('output/url', array(
					'text' => elgg_view('market/thumbnail', ['marketguid' => $icon_guid, 'size' => 'medium', 'tu' => $tu]), 
				    'class' => 'elgg-lightbox',
				    'data-colorbox-opts' => json_encode(['width'=>500, 'height'=>525]),
		            'href' => "market/viewimage/$icon_guid",
			));					
					
			$list_body = elgg_view('object/elements/full', array(
				'entity' => $item,
				'summary' => $summary,
				'body' => $body,
				'header_params'=>['body_class'=>'quebx-item-body'], // Allows Que menu to overflow header
			));
			
		$item_icon = '<!--path: \mod\market\views\default\object\market.php-->'.$item_icon;
			// @EDIT - 2018-01-16 - SAJ - hack to add class allowing text wrapping in image_block view.
			if ($vars['presentation'] == 'inline'){
				$body_vars = ['class'=>$vars['class'], 
						      'id'=>$vars['qid_n'], 
						      'data-perspective'=>$vars['perspective']
				];}
			$body_vars['body_class'] = 'quebx-item-body';
			$this_view = elgg_view_image_block($item_icon, $list_body, $body_vars);
			// @EDIT - 2018-01-16 - SAJ - hack to add class to provide a destination for the qbox.
			$this_view = str_replace("elgg-listing-summary-title","elgg-listing-summary-title quebx-menu-q", $this_view);
			$view2 .= $this_view;
			//$view .= elgg_view_image_block($item_icon, $list_body);
	break;
	case 'gallery':
		$item_icon = elgg_view('output/url', array(
				'text' => elgg_view('market/thumbnail', array('marketguid' => $icon_guid, 'size' => 'medium', 'tu' => $tu)), 
			    'class' => 'elgg-lightbox',
			    'data-colorbox-opts' => '{"width":500, "height":525}',
		        'href' => "market/viewimage.php?marketguid=$icon_guid"));
		
		$view2 .= '<div class="market-gallery-item">'.
	             $item_icon.'<br>'.
	    	     elgg_view('output/url', array(
	        		'href' => "market/view/{$item->guid}/",
	        		'text' => "{$item->title}",
	        	)).
		     '</div>';
	break;
	case 'list':
// 11/02/2013 - Experimental
		// list view
		$market_img = elgg_view('output/url', array(
				'href'    => "market/view/$guid",
				'text'    => elgg_view('market/thumbnail', array('marketguid' => $guid, 'size' => 'tiny', 'tu' => $tu)),
//				'text'    => elgg_view('market/thumbnail', array('marketguid' => $icon_guid, 'size' => 'tiny', 'tu' => $tu)),
				));
		$contents = elgg_get_entities(array(
		    'type' => 'object',
//@EDIT 2020-05-06 - SAJ subtype 'market' replaced by 'qim'
		    'subtypes' => ['market', 'item', 'qim', 'contents'],
		    'joins'    => array('JOIN elgg_objects_entity e2 on e.guid = e2.guid'),
		    'wheres' => array(
		        "e.container_guid = $guid",
		        "NOT EXISTS (SELECT *
	                                 from elgg_entity_relationships s1
	                                 WHERE s1.relationship = 'component'
	                                   AND s1.guid_two = e.container_guid)"
		    ),
		    'order_by' => 'e2.title',
		    'limit' => false,
		));
		$components = elgg_get_entities_from_relationship(array(
		    'type' => 'object',
		    'relationship' => 'component',
		    'relationship_guid' => $guid,
		    'inverse_relationship' => true,
		    'limit' => false,
		));
		$accessories = elgg_get_entities_from_relationship(array(
		    'type' => 'object',
		    'relationship' => 'accessory',
		    'relationship_guid' => $guid,
		    'inverse_relationship' => true,
		    'limit' => false,
		));
		
	    $experiences = elgg_get_entities(array(
	        'type'          => 'object',
	        'subtype'       => 'experience',
	        'container_guid'=> $guid,
	    ));
	    
	    $experiences = array_merge($experiences,
	        elgg_get_entities_from_relationship(['type'                 => 'object',
	            'relationship'         => 'experience',
	            'relationship_guid'    => $guid,
	            'inverse_relationship' => true,
	            'limit'                => $limit,]));
        if($experiences){
            unset($n, $experiences_boqx);
            $n= count($experiences);
            foreach($experiences as $experience){
                unset($link);
                $guids[] = $experience->guid;
                $link  = elgg_view('output/div', ['content'=>elgg_view('output/url', ['text'=>$experience->title, 'class'=>'do', 'data-perspective'=>'view', 'data-presence'=>'pallet', 'data-presentation'=> 'popup', 'data-guid'=>$experience->getGUID(), 'data-element'=>'popup', 'data-space'=>'experience', 'data-aspect'=>$experience->aspect, 'data-context'=>'market', 'data-jq-dropdown'=>'#q'.$experience->getGUID(),'data-qid'=>'q'.$experience->getGUID()]),'class'  =>'drop-down']);
                $content_item .= "<li class='quebx-list-boqx-item'>$link</li>";
            }
            
            $experiences_boqx .= "<div class='quebx-list-boqx'>
            	            <div class='quebx-list-boqx-viewarea'>
                	            <ul class='quebx-list-boqx-compartment'>
                	               $content_item
                	            </ul>
            	            </div>
        	               <span title='Open boqx' class='boqx-items-collapsed'>
        	                   <div class='boqx-label'>Experiences (<span class='boqx-item-count' data-count='$n'>$n</span>)</div>
        	               </span>
        	            </div>";
        }
        if($contents){
            //		$activity .= '<ul><b>Experiences</b>';
            unset($n, $contents_boqx);
            $n= count($contents);
            foreach($contents as $q){
                unset($link);
                $guids[] = $q->guid;
                $link  = elgg_view('output/div', ['content'=>elgg_view('output/url', ['text'=>$q->title, 'class'=>'do', 'data-perspective'=>'view', 'data-presence'=>'pallet', 'data-presentation'=> 'popup', 'data-guid'=>$q->getGUID(), 'data-element'=>'popup', 'data-space'=>'market', 'data-aspect'=>$q->aspect, 'data-context'=>'market', 'data-jq-dropdown'=>'#q'.$q->getGUID(),'data-qid'=>'q'.$q->getGUID(), 'data-view_type'=>'list']),'class'  =>'drop-down']);
                $content_item .= "<li class='quebx-list-boqx-item'>$link</li>";
            }
            
            $contents_boqx .= "<div class='quebx-list-boqx'>
                            <div class='quebx-list-boqx-viewarea'>
                                <ul class='quebx-list-boqx-compartment'>
                                $content_item
                                </ul>
                            </div>
                            <span title='Open boqx' class='boqx-items-collapsed'>
                                <div class='boqx-label'>Contents (<span class='boqx-item-count' data-count='$n'>$n</span>)</div>
                            </span>
                        </div>";
        }
        if($components){
            //		$activity .= '<ul><b>Experiences</b>';
            unset($n, $components_boqx);
            $n= count($components);
            foreach($components as $q){
                unset($link);
                $guids[] = $q->guid;
//                $link  = elgg_view('output/div', ['content'=>elgg_view('output/url', ['text'=>$q->title, 'class'=>'do', 'data-perspective'=>'view', 'data-guid'=>$q->getGUID(), 'data-element'=>'market', 'data-space'=>'market', 'data-aspect'=>$q->aspect, 'data-context'=>'market', 'data-jq-dropdown'=>'#q'.$q->getGUID(),'data-qid'=>'q'.$q->getGUID(), 'data-view_type'=>'list', 'data-presentation'=>'popup']),'class'  =>'drop-down']);
                $link  = elgg_view('output/div', ['content'=>elgg_view('output/url', ['text'=>$q->title, 'class'=>'do', 'data-perspective'=>'view', 'data-presence'=>'pallet', 'data-presentation'=> 'popup', 'data-guid'=>$q->getGUID(), 'data-element'=>'popup', 'data-space'=>'market', 'data-aspect'=>$q->aspect, 'data-context'=>'market', 'data-jq-dropdown'=>'#q'.$q->getGUID(),'data-qid'=>'q'.$q->getGUID(), 'data-view_type'=>'list']),'class'  =>'drop-down']);
                $content_item .= "<li class='quebx-list-boqx-item'>$link</li>";
            }
            
            $components_boqx .= "<div class='quebx-list-boqx'>
            <div class='quebx-list-boqx-viewarea'>
            <ul class='quebx-list-boqx-compartment'>
            $content_item
            </ul>
            </div>
            <span title='Open boqx' class='boqx-items-collapsed'>
            <div class='boqx-label'>Components (<span class='boqx-item-count' data-count='$n'>$n</span>)</div>
            </span>
            </div>";
        }
        if($accessories){
            //		$activity .= '<ul><b>Experiences</b>';
            unset($n, $accessories_boqx);
            $n= count($accessories);
            foreach($accessories as $q){
                unset($link);
                $guids[] = $q->guid;
                $link  = elgg_view('output/div', ['content'=>elgg_view('output/url', ['text'=>$q->title, 'class'=>'do', 'data-perspective'=>'view', 'data-presence'=>'pallet', 'data-presentation'=> 'popup', 'data-guid'=>$q->getGUID(), 'data-element'=>'popup', 'data-space'=>'market', 'data-aspect'=>$q->aspect, 'data-context'=>'market', 'data-jq-dropdown'=>'#q'.$q->getGUID(),'data-qid'=>'q'.$q->getGUID(), 'data-view_type'=>'list']),'class'  =>'drop-down']);
                $content_item .= "<li class='quebx-list-boqx-item'>$link</li>";
            }
            
            $accessories_boqx .= "<div class='quebx-list-boqx'>
            <div class='quebx-list-boqx-viewarea'>
            <ul class='quebx-list-boqx-compartment'>
            $content_item
            </ul>
            </div>
            <span title='Open boqx' class='boqx-items-collapsed'>
            <div class='boqx-label'>Accessories (<span class='boqx-item-count' data-count='$n'>$n</span>)</div>
            </span>
            </div>";
        }
        
		$params = array(
			'entity'     => $item,
			'metadata'   => $metadata,
			// omit 'title' to revert to the default getURL() link
			'title'      => elgg_view('output/url', ['data-guid'        => $guid,
					                                 'data-qid'         => "q{$guid}",
					                                 'data-qid_n'       => "q{$guid}_0",
					                                 'data-element'     => 'market',
					                                 'data-space'       => $item->getSubtype(),
					                                 'data-perspective' => 'view',
					                                 'data-presentation'=> 'inline',
					                                 'data-context'     => elgg_get_context(),
					                                 'text'             => $item->title,
					                                 'class'            => 'do']),
			'subtitle'       => $subtitle,
			'tags'           => $tags,
	//		'content'        => $excerpt,
	        'show_activity'  => true,
	        'show_contents'  => false,
		    'show_comments'  => false,
		    'include_dropbox'=> false,
		);
		$vars['body_class']='quebx-menu-q';
	
		$params = array_merge($vars, $params);
		$list_body = elgg_view('page/elements/list', $params);
		
	    if (elgg_get_plugin_setting('market_comments', 'market') == 'yes') {
	        $params2 = array('inline'=>false, 'show_heading'=>false);
	        $params2 = array_merge($vars, $params2);
	        $add_comment = true;
	        $count_comments = $item->countComments();
	    	$comments = elgg_view_comments($item, $add_comment, $params2);
	    }
		$list_body = str_replace("elgg-body", "elgg-body elgg-menu-q", $list_body);
		$content .= elgg_view_image_block($market_img, $list_body, $vars);
		if ($experiences_boqx || $contents_boqx || $components_boqx || $accessories_boqx){
    		$content .= "<div class='quebx-list-pallet' data-qid='q{$guid}'>
    		               <div>
    		                  $experiences_boqx
    		                  $contents_boqx
    		                  $components_boqx
    		                  $accessories_boqx
    		               </div>
    		             </div>";
		}
		$content .= elgg_view('output/url', array('guid'=>$guid, 'text'=>$count_comments.' comments', 'do'=>'show_comments', 'style'=>array('cursor:pointer')));
	    if ($comments){
	    	$content .= "<div panel='comments' guid='".$marketpost->getGUID()."' class='elgg-comments' style='display: none'>$comments</div>";    
	    }
/*	    $drop_box = "<div id='qbox-pack-$guid' class='jq-dropdown jq-dropdown-tip'>
	    				 <div class='qbox-pallet'>
		    				 <ul class='jq-dropdown-menu'>
		                     	<li class='qbox-drop'>
			    					<div class='qbox-drop-header' data-aspect='content'>contents</div>
			    					<div class='qbox-drop-body' data-aspect='content'></div>
			    				</li>
		                        <li class='qbox-drop'>
			    					<div class='qbox-drop-header' data-aspect='attachment'>attachments</div>
			    					<div class='qbox-drop-body' data-aspect='attachment'></div>
			    				</li>
		                        <li class='qbox-drop'>
			    					<div class='qbox-drop-header' data-aspect='component'>components</div>
			    					<div class='qbox-drop-body' data-aspect='component'></div>
			    				</li>
	                          </ul>
                          </div>
                     </div>";*/
/*	    $drop_box = "<div id='qbox-pack-$guid' class='jq-dropdown jq-dropdown-tip jq-dropdown-relative jq-dropdown-anchor-top'>
	    				 <div class='jq-dropdown-panel qbox-pallet' data-guid=$guid >
		    				 <div class='qbox-drop' data-aspect='content'></div>
			    			 <div class='qbox-drop' data-aspect='attachment'></div>
			    			 <div class='qbox-drop' data-aspect='component'></div>
			    		  </div>
                     </div>";*/
	    $drop_box = "<div id='qbox-pack-$guid' class='dropbox dropbox-tip_xxx dropbox-relative_xxx dropbox-anchor-top'>
	    				<div class='dropbox-panel'>
	    				<span class='qbox-pallet-label'>Dropbox</span>
		    				 <div class='qbox-pallet' data-boqx-guid=$guid >
			    				 <div class='qbox-drop' data-aspect='content'></div>
				    			 <div class='qbox-drop' data-aspect='accessory'></div>
				    			 <div class='qbox-drop' data-aspect='component'></div>
				    		  </div>
                        </div>
                     </div>";
	    /*
	    $content .= $drop_box;
	    $view2 .= "<div class='qbox-open' data-guid=$guid data-jq-dropdown='#qbox-pack-$guid' data-vertical-fixed=30 data-horizontal-fixed=0>".$content."</div>";*/
/*	    $view2 .= "$drop_box
                   <div class='qbox-open' data-guid=$guid data-jq-dropdown='#qbox-pack-$guid' data-vertical-offset = '20' data-horizontal-offset = '10'>
	               	$content
                   </div>";*/
	    $view2 .= "<div class='qbox-open' data-guid=$guid data-dropbox='#qbox-pack-$guid' data-vertical-offset = '20' data-horizontal-offset = '10'>
	               	$content
                   </div>
                   $drop_box
                   ";

	break;
	case 'livesearch':
	    $img = elgg_view('output/url', array(
				'href'    => "market/view/$marketpost->guid",
				'text'    => elgg_view('market/thumbnail', array('marketguid' => $icon_guid, 'size' => 'tiny', 'tu' => $tu)),
				));
		$params = $vars;
		$params['image'] = $img;
		$content = elgg_view('object/elements/livesearch', $params);
		$view2 .= $content;
	break;
	case 'list_experience':
		$market_img = elgg_view('output/url', array(
				'href'    => "market/view/$marketpost->guid",
				'text'    => elgg_view('market/thumbnail', array('marketguid' => $icon_guid, 'size' => 'tiny', 'tu' => $tu)),
				));
		$params = array(
			'entity'     => $marketpost,
	//		'metadata'   => $metadata,
	//		'subtitle'   => $subtitle,
	//		'tags'       => $tags,
	//		'content'    => $excerpt,
	        'show_activity' => false,
		    'show_comments' => false,
		);
	
		$params = array_merge($vars, $params);
		$list_body = elgg_view('object/elements/list', $params);
	
		$view2 .= elgg_view_image_block($market_img, $list_body, $vars);
		break;
	case 'show':
		elgg_load_library('elgg:quebx:output');
		$action = elgg_extract('action', $vars);
		$aspect = elgg_extract('aspect', $vars);
		$options = ['guid'         => $guid,
				    'attribute'    => $aspect,
				    'cascade'      => false,
                    'cascade_depth'=> 0];
		$attributes = quebx_list_attributes($options);
		if (!empty($attributes)){
			foreach($attributes as $attribute){
				$view2 .= $attribute->title;
			}
		}
		else {  $view2 .= 'whazzup!';}
		break;
	case 'inline':
        $selected_compartment  = elgg_extract('compartment', $vars);
		$compartments = elgg_extract('compartments', $vars);
		if (is_array($compartments)){
			foreach ($compartments as $key=>$compartment){
				unset($section_vars);
				$section_vars = $vars;
				$section_vars['view_type']='full_view';
				$section_vars['compartment']=$compartment;
				$section_vars['qid_n'] = $vars['qid'].'_'.$key;
				$section_vars['class'] = $compartment == $selected_compartment ? 'inline-compartment inline-compartment-visible' : 'inline-compartment';
				$content .= elgg_view_entity($item, $section_vars); // swings back around to call 'full_view' above
			}
		}
		else {$vars['view_type'] = 'full_view';
		      $content = elgg_view_entity($item, $vars);} // swings back around to call 'full_view' above
		
		$view2 = elgg_view('output/div',['content'=>$content,
		                                 'class'=>'inline-container',
				                         'options'=>['data-qid'=>$vars['qid']]]);
	
//		$view2 = elgg_view_image_block($item_icon, $list_body);
		break;
	case 'maximized':
		$view2 = elgg_view_entity($item, ['view_type'=>'full_view']);
		break;
	default:
	// brief view
		$market_img = elgg_view('output/url', array(
				'href' => "market/view/$marketpost->guid",
				'text' => elgg_view('market/thumbnail', array('marketguid' => $icon_guid, 'size' => 'small', 'tu' => $tu)),
				));
	
		$subtitle = "{$category}{$value}{$custom}{$supplemental}";
		$subtitle .= "<br>{$author_text} {$date} {$comments_link}";
	
	
		$params = array(
			'entity' => $marketpost,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'tags' => $tags,
			'content' => $excerpt,
		);
		$params = $params + $vars;
	//	$list_body = elgg_view('mod/market/views/default/object/elements/summary', $params);
		$list_body = elgg_view('object/elements/summary', $params);
	
		$market_img = '<!--path: \mod\market\views\default\object\market.php-->'.$market_img;
	
		$view2 .= elgg_view_image_block($market_img, $list_body, $vars);
		break;
}

echo $view2;
//$view .= '<br>'.$display;