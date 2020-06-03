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

$wwwroot      = elgg_get_config('wwwroot');
$dbprefix     = elgg_get_config('dbprefix');

$entity       = elgg_extract('entity', $vars);
$class        = elgg_extract('class', $vars);
$module_type  = elgg_extract('module_type', $vars);
$presentation = elgg_extract('presentation', $vars);
$presence     = elgg_extract('presence', $vars);

$owner        = get_user($entity->owner_guid);

$context      = elgg_get_context();

$num          = (int) $entity->num_display;                                          
if (!isset($num)) 
	$num = 4;
		                                                                          $display .= '$num = '.$num.'<br>';
$options = array('type'=>'object',
//@EDIT 2020-05-06 - SAJ subtype 'market' replaced by 'q_item'
                  'subtypes'   =>['q_item'], 
//                  'subtypes'   =>['market'], 
                  'owner_guid' => $owner->guid,
                  'wheres'     => ["NOT EXISTS (SELECT *
        	                                    FROM {$dbprefix}metadata md
                    	                        JOIN {$dbprefix}metastrings ms1 ON ms1.id = md.name_id
                    	                        JOIN {$dbprefix}metastrings ms2 ON ms2.id = md.value_id
                    	                        WHERE ms1.string = 'visibility_choices'
                    	                          AND ms2.string = 'hide_in_catalog'
                    	                          AND e.guid = md.entity_guid)"], 
        'limit'=>$num);
    
$posts = elgg_get_entities($options);                                              $display .= '$posts = '.count($posts).'<br>';
echo '<!-- context: '.elgg_get_context().'-->';
Switch ($module_type){
    case 'warehouse':
        if (is_array($posts) && sizeof($posts) > 0) {
       		foreach($posts as $post) {
       		    $issues = elgg_get_entities_from_relationship(['relationship'=>'on','relationship_guid'=>$post->getGUID(),'inverse_relationship'=>true,'types'=>'object','subtypes'=>'issue']);
       		    $attachments = elgg_get_entities_from_relationship(['relationship'=>'on','relationship_guid'=>$post->getGUID(),'inverse_relationship'=>true,'types'=>'object','subtypes'=>'file']);
                $icon_guid = $post->icon ?: $post->guid;
                $icon = elgg_view('market/thumbnail', array('marketguid' => $icon_guid, 'size' => 'tiny', 'class'=>'itemPreviewImage_ARIZlwto'));
                echo elgg_view('page/components/pallet_boqx', ['entity'=>$post,'aspect'=>'thing','boqx_id'=>$vars['boqx_id'],'issues'=>count($issues),'has_issues'=>count($issues)>0,'icon'=>$icon,'has_description'=>isset($post->description),'has_attachments'=>count($attachments)>0, 'handler'=>$handler,'presence'=>'pallet']);
       		   }
          }
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
//register_error($display);