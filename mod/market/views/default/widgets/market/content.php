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

$wwwroot = elgg_get_config('wwwroot');
//the page owner
$owner = get_user($vars['entity']->owner_guid);
$class = elgg_extract('class', $vars);
$context = elgg_get_context();

//the number of files to display
$num = (int) $vars['entity']->num_display;
if (!$num) {
	$num = 4;
}		
$dbprefix = elgg_get_config('dbprefix');
$options = array('type'=>'object',
                  'subtype'=>'market', 
                  'owner_guid' => $owner->guid,
                  'wheres'    => array("NOT EXISTS (SELECT *
	                                  FROM {$dbprefix}metadata md
            	                      JOIN {$dbprefix}metastrings ms1 ON ms1.id = md.name_id
            	                      JOIN {$dbprefix}metastrings ms2 ON ms2.id = md.value_id
            	                      WHERE ms1.string = 'visibility_choices'
            	                        AND ms2.string = 'hide_in_catalog'
            	                        AND e.guid = md.entity_guid)"), 
        'limit'=>$num);
    
$posts = elgg_get_entities($options);
echo '<!-- context: '.elgg_get_context().'-->';
Switch ($context){
    case 'warehouse':
        if (is_array($posts) && sizeof($posts) > 0) {
       		foreach($posts as $post) {
       		    $cid = quebx_new_id('c');
       		    $guid = $post->getGUID();
       		    $title = $post->title;
       		    $owner = $post->getOwnerEntity();
       		    $owner_name = $owner->name;
       		    $owner_initials = quebx_initials($owner_name);
                $items .= "<div class='story item has_tasks draggable story_147996385 $cid feature unscheduled point_scale_linear estimate_0 is_estimatable not_collapsed has_blockers_or_blocking' 
                              data-aid='StoryPreviewItem' 
                              data-cid='$cid' 
                              data-id='147996385' 
                              aria-describedby='reorder-help' 
                              aria-label='$title'>
                              <header tabindex='0' 
                                      data-aid='StoryPreviewItem__preview' 
                                      class='preview'>
                                      <button class='expander undraggable' 
                                              data-aid='StoryPreviewItem__expander'
                                              data-guid='$guid'
                                              data-cid='$cid'
                                              aria-expanded='false' 
                                              tabindex='-1' 
                                              aria-label='expander'></button>
                                      <a class='selector undraggable' 
                                         title='Select this story for bulk actions' 
                                         tabindex='-1'></a>
                                      <span class='meta'>
                                          <span>0</span>
                                      </span>
                                      <a class='reveal story button' 
                                         data-id='$cid' 
                                         data-guid='$guid' 
                                         data-type='item'
                                         tabindex='-1'>
                                         <span class='locator' 
                                               title='Reveal this story'></span>
                                      </a>
                                      <span class='state'>
                                           <label data-aid='StateButton' 
                                                  data-destination-state='start' 
                                                  class='state button start' 
                                                  tabindex='-1'>Start</label>
                                      </span>
                                      <span class='name normal'>
                                           <span class='story_name'>
                                                <span class='tracker_markup' 
                                                      data-aid='StoryPreviewItem__title'>$title</span>
                                                <span class='parens'>
                                                     <a class='owner' 
                                                        tabindex='-1' 
                                                        title='$owner_name'>$owner_initials</a>
                                                </span>
                                           </span>
                                           <span class='labels post'>
                                                <a class='std label' tabindex='-1'>juxtaposition</a>
                                                <a class='std label' tabindex='-1'>mechanical</a>
                                                <a class='std label' tabindex='-1'>sophisticated</a>
                                                <a class='std label' tabindex='-1'>wonder</a>
                                           </span>
                                           <span class='StoryPreviewItemReviewList___2PqmkeBu'></span>
                                      </span>
                                      <div class='blocker' 
                                           data-aid='StoryPreviewBlocker'></div>
                              </header>
                         </div>";
    			$ignore_me .= "<li class=\"pvs\">";
    			$category = "<b>" . elgg_echo('market:category') . ":</b> " . elgg_echo($post->marketcategory);
    			$comments_count = $post->countComments();
    			$text = elgg_echo("comments") . " ($comments_count)";
    			$comments_link = elgg_view('output/url', array(
    						'href' => $post->getURL() . '#market-comments',
    						'text' => $text,
    						));
    			$market_img = elgg_view('output/url', array(
    						'href' => "market/view/{$post->guid}/",
    						'text' => elgg_view('market/thumbnail', array('marketguid' => $post->guid, 'size' => 'small', 'width'=> '40', 'height' =>'40')),
    						));
    
    			$subtitle = "{$category}<br><b>" . elgg_echo('market:price') . ":</b> {$post->price}";
    			$subtitle .= "<br>{$author_text} {$date} {$comments_link}";
    			$params = array(
    				'entity' => $post,
    				'metadata' => $metadata,
    				'subtitle' => $subtitle,
    				'tags' => $tags,
    				'content' => $excerpt,
    			);
    			$params = $params + $vars;
    			$list_body = elgg_view('object/elements/summary', $params);
    			$ignore_me .= elgg_view_image_block($market_img, $list_body,['class'=>'preview']);
    			$ignore_me .= "</li>";
       		   }
          }
          echo $items;
        break;
    default:
        echo '<ul class="elgg-list">';		
        // display the posts, if there are any
        if (is_array($posts) && sizeof($posts) > 0) {
        
        	if (!$size || $size == 1){
        		foreach($posts as $post) {
        			echo "<li class=\"pvs\">";
        			$category = "<b>" . elgg_echo('market:category') . ":</b> " . elgg_echo($post->marketcategory);
        			$comments_count = $post->countComments();
        			$text = elgg_echo("comments") . " ($comments_count)";
        			$comments_link = elgg_view('output/url', array(
        						'href' => $post->getURL() . '#market-comments',
        						'text' => $text,
        						));
        			$market_img = elgg_view('output/url', array(
        						'href' => "market/view/{$post->guid}/",
        						'text' => elgg_view('market/thumbnail', array('marketguid' => $post->guid, 'size' => 'small', 'width'=> '40', 'height' =>'40')),
        						));
        
        			$subtitle = "{$category}<br><b>" . elgg_echo('market:price') . ":</b> {$post->price}";
        			$subtitle .= "<br>{$author_text} {$date} {$comments_link}";
        			$params = array(
        				'entity' => $post,
        				'metadata' => $metadata,
        				'subtitle' => $subtitle,
        				'tags' => $tags,
        				'content' => $excerpt,
        			);
        			$params = $params + $vars;
        			$list_body = elgg_view('object/elements/summary', $params);
        			echo elgg_view_image_block($market_img, $list_body);
        			echo "</li>";
        		}
        			
        	}
        	echo "</ul>";
        	echo "<div class=\"contentWrapper\"><a href=\"" . $wwwroot . "market/owned/" . $owner->username . "\">" . elgg_echo("market:widget:viewall") . "</a></div>";
        
        }

}