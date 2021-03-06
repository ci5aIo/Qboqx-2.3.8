<?php
/**
 * Page edit form body
 *
 * @package ElggPages
 */

$variables = elgg_get_config('tasks');

// 10/31/2014 - Added by scottj
	$container_guid = get_input('container_guid');
	$element_type = get_input('element_type');
	echo elgg_view('input/hidden', array('name' => 'container', 'value' => $container_guid));
	echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
	//
echo 'path: mod\tasks\pages\tasks\new.php<br>';
echo 'path: mod/market/views/default/forms/tasks/edit.php<br>';
foreach ($variables as $name => $type) {
?>
<div>
	<label><?php echo elgg_echo("tasks:$name") ?></label>
	<?php	
		if ($type != 'longtext') {
			echo '<br />';
		}
	?>
	<?php echo elgg_view("input/$type", array(
			'name' => $name,
			'value' => $vars[$name],
		));
	?>
</div>
<?php
}

$cats = elgg_view('input/categories', $vars);
if (!empty($cats)) {
	echo $cats;
}


echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'task_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if ($vars['parent_guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}
echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';