<?php
$wwwroot     = elgg_get_config('wwwroot');
$widget      = elgg_extract('entity', $vars);
$owner       = get_user($widget->owner_guid);
$class       = elgg_extract('class', $vars);
$context     = elgg_get_context();
$module_type = elgg_extract('module_type', $vars);
$num         = (int) $widget->num_display;                                          
if (!isset($num)) {
	$num = 4;
}
$dbprefix = elgg_get_config('dbprefix');
$options = ['type'=>'object',
            'subtype'=>'file', 
            'owner_guid' => $owner->guid,
            'limit'=>$num];

// show only featured files
if ($widget->featured_only == 'yes') {
	$options['metadata_name_value_pairs'] = ['name' => 'show_in_widget','value' => '0','operand' => '>',];
}
$files = elgg_get_entities($options);                                              $display .= '$files = '.count($files).'<br>';
echo '<!-- context: '.elgg_get_context().'-->';
Switch ($module_type){
    case 'warehouse':
        if (is_array($files) && sizeof($files) > 0) {
       		foreach($files as $file) {
       		    $issues = elgg_get_entities_from_relationship(['relationship'=>'on','relationship_guid'=>$file->getGUID(),'inverse_relationship'=>true,'types'=>'object','subtypes'=>'issue']);
       		    $attachments = elgg_get_entities_from_relationship(['relationship'=>'on','relationship_guid'=>$file->getGUID(),'inverse_relationship'=>true,'types'=>'object','subtypes'=>'file']);
                $icon_guid = $file->icon ?: $file->guid;
                $icon = elgg_view('market/thumbnail', array('marketguid' => $file->guid, 'size' => 'tiny', 'class'=>'itemPreviewImage_ARIZlwto'));                
                echo elgg_view('page/components/pallet_boqx', ['entity'=>$file,'aspect'=>'file','boqx_id'=>$vars['boqx_id'],'has_issues'=>count($issues)>0,'icon'=>$icon,'has_description'=>isset($file->description),'has_attachments'=>count($attachments)>0]);
       		   }
          }
        break;
    default:
        // how to display the files
        if ($widget->gallery_list == 2) {
        	$files = elgg_get_entities_from_metadata($options);
        	if (empty($files)) {
        		echo elgg_echo('file:none');
        		return;
        	}
        	
        	$list = '<ul class="elgg-gallery">';
        	
        	foreach ($files as $file) {
        		$list .= '<li class="elgg-item">';
        		$list .= elgg_view('output/url', [
        			'text' => elgg_view_entity_icon($file, 'small'),
        			'href' => $file->getURL(),
        			'title' => $file->title,
        		]);
        		$list .= '</li>';
        	}
        	$list .= '</ul>';
        	
        	echo $list;
        	
        	$more_link = '';
        	$owner = $widget->getOwnerEntity();
        	if ($owner instanceof ElggUser) {
        		$more_link = "file/owner/{$owner->username}";
        	} elseif ($owner instanceof ElggGroup) {
        		$more_link = "file/group/{$owner->getGUID()}/all";
        	}
        	
        	if (empty($more_link)) {
        		return;
        	}
        	
        	echo elgg_format_element('div', ['class' => 'elgg-widget-more'], elgg_view('output/url', [
        		'text' => elgg_echo('file:more'),
        		'href' => $more_link,
        		'is_trusted' => true,
        	]));
        	
        	return;
        }
        
        $list = elgg_list_entities_from_metadata($options);
        if (empty($list)) {
        	echo elgg_echo('file:none');
        	return;
        }
        
        echo $list;
        
        $more_link = '';
        $owner = $widget->getOwnerEntity();
        if ($owner instanceof ElggUser) {
        	$more_link = "file/owner/{$owner->username}";
        } elseif ($owner instanceof ElggGroup) {
        	$more_link = "file/group/{$owner->getGUID()}/all";
        }
        
        if (empty($more_link)) {
        	return;
        }
        
        echo elgg_format_element('div', ['class' => 'elgg-widget-more'], elgg_view('output/url', [
        	'text' => elgg_echo('file:more'),
        	'href' => $more_link,
        	'is_trusted' => true,
        ]));

}
//register_error($display);