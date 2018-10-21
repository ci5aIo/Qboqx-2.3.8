<?php

/**
 * This function serialises an object recursively into an XML representation.
 *
 * The function attempts to call $data->export() which expects a \stdClass in return,
 * otherwise it will attempt to get the object variables using get_object_vars (which
 * will only return public variables!)
 *
 * @param mixed  $data The object to serialise.
 * @param string $name The name?
 * @param int    $n    Level, only used for recursion.
 *
 * @return string The serialised XML output.
 */
function data_views_object_to_xml($data, $name = "", $n = 0) {
	$classname = ($name == "" ? get_class($data) : $name);

	$vars = method_exists($data, "export") ? get_object_vars($data->export()) : get_object_vars($data);

	$output = "";

	if (($n == 0) || ( is_object($data) && !($data instanceof \stdClass))) {
		$output = "<$classname>";
	}

	foreach ($vars as $key => $value) {
		$output .= "<$key type=\"" . gettype($value) . "\">";

		if (is_object($value)) {
			$output .= data_views_object_to_xml($value, $key, $n + 1);
		} else if (is_array($value)) {
			$output .= data_views_array_to_xml($value, $n + 1);
		} else if (gettype($value) == "boolean") {
			$output .= $value ? "true" : "false";
		} else {
			$output .= htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
		}

		$output .= "</$key>\n";
	}

	if (($n == 0) || (is_object($data) && !($data instanceof \stdClass))) {
		$output .= "</$classname>\n";
	}

	return $output;
}

/**
 * Serialise an array.
 *
 * @param array $data The data to serialize
 * @param int   $n    Used for recursion
 *
 * @return string
 */
function data_views_array_to_xml(array $data, $n = 0) {
	$output = "";

	if ($n == 0) {
		$output = "<array>\n";
	}

	foreach ($data as $key => $value) {
		$item = "array_item";

		if (is_numeric($key)) {
			$output .= "<$item name=\"$key\" type=\"" . gettype($value) . "\">";
		} else {
			$item = $key;
			$output .= "<$item type=\"" . gettype($value) . "\">";
		}

		if (is_object($value)) {
			$output .= data_views_object_to_xml($value, "", $n + 1);
		} else if (is_array($value)) {
			$output .= data_views_array_to_xml($value, $n + 1);
		} else if (gettype($value) == "boolean") {
			$output .= $value ? "true" : "false";
		} else {
			$output .= htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
		}

		$output .= "</$item>\n";
	}

	if ($n == 0) {
		$output .= "</array>\n";
	}

	return $output;
}
