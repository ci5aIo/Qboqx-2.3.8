<?php
/**
 * Market sidebar
 * v2
 */

echo elgg_view('page/components/tagcloud_block', array(
//@EDIT 2020-05-06 - SAJ subtype 'market' replaced by 'q_item'
    'subtypes' => ['market', 'item','q_item'],
	'owner_guid' => elgg_get_page_owner_guid(),
));

