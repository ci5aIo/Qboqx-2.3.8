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
$entity        = $vars['entity'];
$take_snapshot = $vars['snapshot'];
if (elgg_instanceof($entity, 'object', 'market') || elgg_instanceof($entity, 'object', 'item')){
    $variable_name  = $vars['input_name'];
    $variable_value = $entity->getGUID();
    $icon        = elgg_view_entity_icon($entity, 'tiny');
    $name        = $entity->title;
    $entity_guid = $entity->getGUID();
    $icon_guid   = $entity->icon ?: $entity_guid;
    $name_link   = elgg_view('output/url', array('text' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                                             'href' => $entity->getURL(),
        ));
    $icon_link   = elgg_view('output/url', array(
        			'text' => $icon, 
        		    'class' => 'elgg-lightbox',
        	        'href' => "mod/market/viewimage.php?marketguid=$icon_guid"));
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
}
?>
<li data-guid='<?php echo $entity_guid ?>'>
	<div class='elgg-image-block'>
		<div class='elgg-image'><?php echo $icon_link ?></div>
		<div class='elgg-image-alt'><?php echo elgg_view_icon("unlink", "elgg-asset-picker-remove"); ?></div>
		<div class='elgg-body'><?php echo $name_link; ?></div>
	</div>
	<input type="hidden" name="<?php echo htmlspecialchars($variable_name, ENT_QUOTES, 'UTF-8'); ?>[]" value="<?php echo $variable_value ?>" />
	<?php echo $snapshot_input?>
</li>