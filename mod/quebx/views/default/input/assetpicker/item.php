<?php
/**
 * Asset view in Asset Picker
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['entity'] Asset entity
 * @uses $vars['input_name'] Name of the returned data array
 */

/* @var ElggEntity $entity */
$entity         = elgg_extract('entity', $vars);
$take_snapshot  = elgg_extract('snapshot', $vars);
$container_guid = elgg_extract('container_guid', $vars, false);
$container_type = elgg_extract('container_type', $vars, 'container');
$aspect         = elgg_extract('aspect', $vars);
$section        = elgg_extract('section', $vars);
$qid            = elgg_extract('qid', $vars);

$guid           = $entity->getGUID();
if (elgg_instanceof($entity, 'object', 'market') || elgg_instanceof($entity, 'object', 'item')){
    $variable_name  = elgg_extract('input_name', $vars);
    $variable_value = $entity->getGUID();
    $icon        = elgg_view_entity_icon($entity, 'tiny');
    $name        = $entity->title;
    $element_type = $entity->getSubtype();
    $entity_guid = $entity->getGUID();
    $icon_guid   = $entity->icon ?: $entity_guid;
    $name_link   = elgg_view('output/url', array('text' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                                             'href' => $entity->getURL(),
        ));
    $icon_link   = elgg_view('output/url', array(
        			'text' => $icon, 
        		    'class' => 'elgg-lightbox',
        	        'href' => "mod/market/viewimage.php?marketguid=$icon_guid"));
    $detach_link = elgg_view("output/url",
			       ['href' => "action/jot/detach?element_type='$element_type'&guid='$guid'&container_guid='$container_guid'",
			    	'text' => elgg_view_icon('link').elgg_view_icon('unlink'),
			    	'confirm' => sprintf(elgg_echo('jot:detach:confirm'), $name),
			        'class'=>'hover-change',                                      // icon change controlled by css
			    	'encode_text' => false,
			       ]
				);
    $detach_button = "<nav class='ThingShow__actions___oosero4fs undefined ThingShow__actions--unfocused___234slkj65'>
						<button class='IconButton___23o4ips IconButton--small___w40sDoiq3' data-aid='delete' aria-label='Delete' data-item-guid='$guid' data-qid='$qid' data-aspect='$aspect' data-section='$section'>
							<span><a title='remove from this $container_type'><span class='elgg-icon fa elgg-icon-delete-alt fa-times-circle'></span></a></span>
						</button>
					  </nav>";
}
else {
    $variable_name  = $vars['input_name'];
    $variable_value = $entity;
    $name        = $entity;
    $name_link   = $name;
    $icon        = elgg_get_site_url() . "mod/market/graphics/noimagetiny.png";
    $icon_link   = "<img src=\"{$icon}\">";
}

if ($take_snapshot){
    $snapshot_variable_name  = substr_replace($variable_name, '[snapshot]', strpos($variable_name, '['), 0);
    $snapshot_input          = "<input type='hidden' name='".htmlspecialchars($snapshot_variable_name, ENT_QUOTES, 'UTF-8')."[]' value='$variable_value' />"; 
}/*
$item = "<li data-guid= $entity_guid>
	<div class='elgg-image-block'>
		<div class='elgg-image'>$icon_link</div>
		<div class='elgg-image-alt'>$detach_link</div>
		<div class='elgg-head'>$name_link</div>
	</div>
	<input type='hidden' name='".htmlspecialchars($variable_name, ENT_QUOTES, 'UTF-8')."[]' value='$variable_value' />
	$snapshot_input
	</div>
</li>";*/
	
$item = "<li data-guid= '$entity_guid'>
        	<div class='SpotShow__elw1jufs'>
                <div class='ThingShow__alskdjlse'>
                	<div class='elgg-image-block'>
                    	<div class='elgg-image'>$icon_link</div>
                    	<div class='elgg-head'>$name_link</div>
                	</div>
                </div>
            	$detach_button
            	<input type='hidden' name='".htmlspecialchars($variable_name, ENT_QUOTES, 'UTF-8')."[]' value='$variable_value' />
            	$snapshot_input
        	</div>
         </li>";
	
echo $item;