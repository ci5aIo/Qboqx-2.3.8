<?php
//Copied from start.php.  Do not work as copied.  Comment out until higher priority. 

function cars_user_picker_callback($query, $options = array()) {
	
	// this is the guid of the market entity
	// we don't actually need it for this
	$id = sanitize_int(get_input('id'));
	
	// replace mysql vars with escaped strings
    $q = str_replace(array('_', '%'), array('\_', '\%'), sanitize_string($query));
	
	$dbprefix = elgg_get_config('dbprefix');
	return elgg_get_entities(array(
		'type' => 'user',
		'joins' => array(
			"JOIN {$dbprefix}users_entity ue ON ue.guid = e.guid",
		),
		'wheres' => array(
			"ue.username LIKE '%{$q}%' OR ue.name LIKE '%{$q}%'",
		),
		'order_by' => 'ue.name ASC'
	));
}



function cars_shoe_picker_callback($query, $options = array()) {
	
	// this is the guid of the market entity
	// we don't actually need it for this
	$id = sanitize_int(get_input('id'));
	
	// replace mysql vars with escaped strings
    $q = str_replace(array('_', '%'), array('\_', '\%'), sanitize_string($query));
	
	$shoes_id = elgg_get_metastring_id('shoes');
	$marketcat_id = elgg_get_metastring_id('marketcategory');
	
	$dbprefix = elgg_get_config('dbprefix');
	return elgg_get_entities(array(
		'type' => 'object',
//@EDIT 2020-05-06 - SAJ subtype 'market' replaced by 'qim'
	    'subtypes' => ['market','qim'],
		'joins' => array(
			"JOIN {$dbprefix}objects_entity oe ON oe.guid = e.guid",
			"JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid AND md.name_id = {$marketcat_id} AND md.value_id = {$shoes_id}"
		),
		'wheres' => array(
			"oe.title LIKE '%{$q}%' OR oe.description LIKE '%{$q}%'",
		),
		'order_by' => 'oe.title ASC'
	));
}

/**
 * Adapted from engine\lib\access.php\get_user_access_collections
 * Returns an array of database row labels owned by $owner_guid.
 *
 * @param int $owner_guid The entity guid
 * @param int $site_guid  The GUID of the site (default: current site).
 *
 * @return array|false
 */
function get_family_characteristics_collections($owner_guid, $site_guid = 0) {
	global $CONFIG;
	$owner_guid = (int) $owner_guid;
	$site_guid  = (int) $site_guid;
	$dbprefix   = elgg_get_config('dbprefix');
	
	if (($site_guid == 0) && (isset($CONFIG->site_guid))) {
		$site_guid = $CONFIG->site_guid;
	}
	$wheres[] = "s1.string   = 'characteristic_names'";
	$wheres[] = "s4.type     = 'object'";
	$wheres[] = "s4.owner_guid = $owner_guid";
//@EDIT 2020-05-06 - SAJ subtype 'market' replaced by 'qim'
	$wheres[] = "s5.subtype  in ('market', 'qim)";
//	$wheres[] = "s5.subtype  = 'market'";
	if ($category)	$wheres[] = "s7.string   = '$category'";
	$wheres[] = "s2.value_id = t1.id)";

	$query = "SELECT *
	        FROM {$dbprefix}metastrings msv
			where msv.string is not null
              and msv.string <> ''
              and exists (Select *
			              from {$dbprefix}entities e
			              join {$dbprefix}metadata md 
			              on md.entity_guid = e.guid
			              JOIN {$dbprefix}metastrings msn 
			              on md.name_id = msn.id
					      where md.owner_guid = {$owner_guid}
						    AND e.site_guid = {$site_guid}
							and msv.id = md.value_id
							and msn.string IN ('tags'))
			 order by msv.string";

	$collections = get_data($query);

	return $collections;
}