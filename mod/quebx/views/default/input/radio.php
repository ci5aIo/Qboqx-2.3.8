<?php
/**
 * Elgg radio input
 * Displays a radio input field
 *
 * @warning Passing integers as labels does not currently work due to a
 * deprecated hack that will be removed in Elgg 1.9. To use integer labels,
 * the labels must be character codes: 1 would be &#0049;
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']    The current value, if any
 * @uses $vars['name']     The name of the input field
 * @uses $vars['options']  An array of strings representing the options for the
 *                         radio field as "label" => option
 * @uses $vars['class']    Additional class of the list. Optional.
 * @uses $vars['align']    'horizontal' or 'vertical' Default: 'vertical'
 * 
 * @uses $vars['label_class'] Additional class of the label.  Optional.
 * @uses $vars['radio_class'] Additional class of the radio control.  Optional.
 */

$defaults = array(
	'align' => 'vertical',
	'value' => array(),
	'disabled' => false,
	'options' => array(),
	'name' => '',
	'type' => 'radio'
);

$vars = array_merge($defaults, $vars);

$options = elgg_extract('options', $vars);
unset($vars['options']);

if (empty($options)) {
	return;
}

$id = elgg_extract('id', $vars, '');
unset($vars['id']);

$list_class = elgg_extract_class($vars, ['elgg-input-radios', "elgg-{$vars['align']}"]);

unset($vars['class']);
unset($vars['align']);

$vars['class'] = 'elgg-input-radio';
if (isset($vars['label_class'])){
	$label_class = " class = '{$vars['label_class']}'";
}
if (isset($vars['radio_class'])){
	$radio_class = $vars['radio_class'];
}

if (is_array($vars['value'])) {
	$vars['value'] = array_map('elgg_strtolower', $vars['value']);
} else {
	$vars['value'] = array(elgg_strtolower($vars['value']));
}

$value = $vars['value'];
unset($vars['value']);

$radios = '';
foreach ($options as $label => $option) {

	$vars['checked'] = in_array(elgg_strtolower($option), $value);
	$vars['value'] = $option;
	$vars['class'] = $radio_class;

	$radio = elgg_format_element('input', $vars);
	$radios .= "<li><label{$label_class}>{$radio}{$label}</label></li>";
}

echo elgg_format_element('ul', ['class' => $list_class, 'id' => $id], $radios);
