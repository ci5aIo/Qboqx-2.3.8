<?php

/**
 * Most viewed images of the current year
 *
 */

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:mostviewedthisyear'));

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$start = mktime(0, 0, 0, 1, 1, date("Y"));
$end = time();

$result = elgg_list_entities_from_annotation_calculation([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'annotation_name' => 'tp_view',
	'calculation' => 'count',
	'annotation_created_time_lower' => $start,
	'annotation_created_time_upper' => $end,
	'order_by' => 'annotation_calculation desc',
	'full_view' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'tidypics-gallery',
]);

$title = elgg_echo('tidypics:mostviewedthisyear');

$logged_in_user = elgg_get_logged_in_user_entity();
if (tidypics_can_add_new_photos(null, $logged_in_user)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $logged_in_user->getGUID(),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

// only show slideshow link if slideshow is enabled in plugin settings and there are images
if (elgg_get_plugin_setting('slideshow', 'tidypics') && !empty($result)) {
	elgg_require_js('tidypics/slideshow');
	elgg_register_menu_item('title', [
		'name' => 'slideshow',
		'id' => 'slideshow',
		'data-slideshowurl' => elgg_get_site_url() . "photos/mostviewedthisyear",
		'data-limit' => $limit,
		'data-offset' => $offset,
		'href' => 'ajax/view/photos/galleria',
		'text' => "<img src=\"" . elgg_get_simplecache_url("tidypics/slideshow.png") . "\" alt=\"".elgg_echo('album:slideshow')."\">",
		'title' => elgg_echo('album:slideshow'),
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
	]);
}

if (!empty($result)) {
	$content = $result;
} else {
	$content = elgg_echo('tidypics:mostviewedthisyear:nosuccess');
}
$body = elgg_view_layout('content', [
	'filter_override' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'all']),
]);

// Draw it
echo elgg_view_page(elgg_echo('tidypics:mostviewedthisyear'), $body);
