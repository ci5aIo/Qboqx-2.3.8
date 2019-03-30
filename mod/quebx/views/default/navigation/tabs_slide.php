<?php
/**
 * Tab navigation
 *
 * @uses string $vars['type'] horizontal || vertical - Defaults to horizontal
 * @uses string $vars['class'] Additional class to add to 'ul'
 * @uses string $vars['aspect'] Additional class to add to all 'li'
 * @uses array $vars['tabs'] A multi-dimensional array of tab entries in the format array(
 * 	'text' => string, // The string between the <a></a> tags
 * 	'href' => string, // URL for the link
 * 	'class' => string  // Class of the li element
 * 	'id' => string, // ID of the li element
 * 	'selected' => bool // if this tab is currently selected (applied to li element)
 * 	'link_class' => string, // Class to pass to the link
 * 	'link_id' => string, // ID to pass to the link
 * )
 */                                                                                         $display .= 'quebx/views/default/navigation/tabs_slide<br>';
$options = _elgg_clean_vars($vars);

$type         = elgg_extract('type', $vars, 'horizontal');
$presentation = elgg_extract('presentation', $vars,'full');
$element      = elgg_extract('element', $vars, 'qbox');
$space        = elgg_extract('space', $vars);
$qid          = elgg_extract('qid', $vars, false);

if ($type == 'horizontal') {
	$options['class'] = "elgg-tabs elgg-htabs";
} else {
	$options['class'] = "elgg-tabs elgg-vtabs";
}
if (isset($vars['class'])) {
	$options['class'] = "{$options['class']} {$vars['class']}";
}
if (isset($vars['style'])) {
	$options['style'] = "{$vars['style']}";
}

unset($options['tabs']);
unset($options['type']);

$attributes = elgg_format_attributes($options);

if (isset($vars['tabs']) && is_array($vars['tabs']) && !empty($vars['tabs'])) {
	?>
	<ul <?php echo $attributes; ?>>
		<?php
		foreach ($vars['tabs'] as $key=>$info) {
		    unset($count_label, $parent_cid);                                               $display .= '48 title = '.$info['title'].'<br>';
		    $class    = $info['class'];                                                     //$display .= '49 $class = '.$class.'<br>';
			$style    = elgg_extract('style'   , $info, '');
			$id       = elgg_extract('id'      , $info, '');
//			$parent_cid = elgg_extract('parent_cid' , $info, $options['data-cid']); //derive $parent_cid from the DOM
			$cid      = elgg_extract('cid'     , $info, false);
			$guid     = elgg_extract('guid'    , $info, false);
			$panel    = elgg_extract('panel'   , $info, false);
			$count    = elgg_extract('count'   , $info, false);                              //$display .= '55 $count: '.$count.'<br>';
			$selected = elgg_extract('selected', $info, false);                              $display .= '57 $selected: '.$selected.'<br>';
			$expand_tabs = elgg_extract('expand_tabs', $info, false);
			$section  = elgg_extract('section' , $info);
			$aspect   = elgg_extract('aspect'  , $info, false);
			$action   = elgg_extract('action'  , $info, false);
			$note     = elgg_extract('note'    , $info, false);
			$cursor   = 'cursor:pointer';
			$style    = ($style) ? $style.';'.$cursor : $cursor;
			$n        = $key+1;
			if ($selected) {
				Switch ($space){
					case 'transfer':
						$class  = 'elgg-state-selected';
						break;
/*					case 'market':
						$class .= ' qbox-state-selected';
						break;*/
					default:
						$class .= ' elgg-state-selected';// Selected plus the received class
						break;
				}
			}

			$class_str  = ($class)  ? " class=\"$class\""   : '';                             $display .= '79 $class_str = '.$class_str.'<br>';
			$style_str  = ($style)  ? " style=\"$style\""   : '';
			$id_str     = ($id)     ? " id=\"$id\""         : '';
			$qid_str    = ($qid)    ? " data-qid=\"$qid\""  : '';
			$cid_str    = ($cid)    ? " data-cid=\"$cid\""  : '';
			$guid_str   = ($guid)   ? " guid=\"$guid\""     : '';
			$panel_str  = ($panel)  ? " panel=\"$panel\""   : '';
			$aspect_str = ($aspect) ? " aspect=\"$aspect\"" : '';
			$action_str = ($action) ? " action=\"$action\"" : '';
			
			$options = $info;
			unset(//$options['class'], 
			      $options['id'],
			      $options['qid'],
			      $options['cid'], 
			      $options['selected'],
				  $options['section']);
/*
			if (!isset($info['href']) && isset($info['url'])) {
				$options['href'] = $info['url'];
				unset($options['url']);
			}
*/			
//			if ($presentation == 'qbox' || $presentation == 'popup'){
				$options['data-section'] = elgg_strtolower($section);
				$options['data-element'] = $element;
//				$options['class']        = 'qbox-q';
// 				if (isset($qid))           {$options['data-qid']="{$qid}_{$n}";}
//				else                       {$options['data-qid']="q{$guid}_01_{$n}";}
				
				if (isset($cid))           {$options['data-cid']=$cid;}
//				if ($space == 'transfer')  {$options['class']='qbox-q2';}
// 				if ($space == 'transfer' && !$selected){$options['class']='qbox-q2';}
// 				if ($space == 'transfer' &&  $selected){$options['class']='elgg-state-selected';}
//			}
			
			if ($count && $count>0) $count_label = "<span class='$aspect-".strtolower($section)."-count' data-cid='{$options['data-cid']}' data-count=$count> ($count)</span>";
			else                    $count_label = "<span class='$aspect-".strtolower($section)."-count' data-cid='{$options['data-cid']}' data-count=0></span>";
			
            if (!isset($info['text']) && isset($info['title'])) {
                $options['text'] = $options['title'].$count_label;
				unset($options['title']);
            }
            if (isset($info['count'])) {
                $options['data-count'] = $count;
                unset($options['count']);
                
            }
			if (isset($info['link_class'])) {
				$options['class'] = $options['link_class'];
				unset($options['link_class']);
			}
			if (isset($info['link_style'])) {
				$options['style'] = $options['link_style'];
				unset($options['link_style']);
			}

			if (isset($info['link_id'])) {
				$options['id'] = $options['link_id'];
				unset($options['link_id']);
			}
			$link = elgg_view('output/url', $options);
			if ($panel == 'Expand'){$text = $link.$expand_tabs;}
			else                   {$text = $link;}
			if ($note)             {$text = elgg_view('output/span',['content'=>$text, 'options'=>['title'=>$note]]);}

			echo "<li $id_str
        			  $class_str
        			  $aspect_str
			          $action_str
			          $guid_str
			          $qid_str
			          $cid_str
			          $panel_str
			          $style_str>$text</li>";
		}
		?>
	</ul>
	<?php
}
//register_error($display);