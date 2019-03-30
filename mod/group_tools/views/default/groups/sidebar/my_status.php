<?php
/**
 * Group status for logged in user
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 */

$group = elgg_extract('entity', $vars);
$user = elgg_get_logged_in_user_entity();
$subscribed = elgg_extract('subscribed', $vars);

if (empty($user)) {
	return true;
}

if (!($group instanceof ElggGroup)) {
	return;
}

// membership status
$is_member = $group->isMember($user);
$is_owner = ($group->getOwnerGUID() === $user->getGUID());

if ($is_owner) {
	elgg_register_menu_item('groups:my_status', [
		'name' => 'membership_status',
		'text' => elgg_echo('groups:my_status:group_owner'),
		'href' => '#',
	]);
} elseif ($is_member) {
	elgg_register_menu_item('groups:my_status', [
		'name' => 'membership_status',
		'text' => elgg_echo('groups:my_status:group_member'),
		'href' => '#',
	]);
} else {
	elgg_register_menu_item('groups:my_status', [
		'name' => 'membership_status',
		'text' => elgg_echo('groups:join'),
		'href' => "action/groups/join?group_guid={$group->getGUID()}",
		'is_action' => true,
	]);
}

// notification info
if (elgg_is_active_plugin('notifications') && $is_member) {
	if ($subscribed) {
		elgg_register_menu_item('groups:my_status', [
			'name' => 'subscription_status',
			'text' => elgg_echo('groups:subscribed'),
			'href' => "notifications/group/{$user->username}",
		]);
	} else {
		elgg_register_menu_item('groups:my_status', [
			'name' => 'subscription_status',
			'text' => elgg_echo('groups:unsubscribed'),
			'href' => "notifications/group/{$user->username}",
		]);
	}
}

$body = elgg_view_menu('groups:my_status', [
	'entity' => $group,
	'subscribed' => $subscribed,
]);

if (!empty($body)) {
	echo elgg_view_module('aside', elgg_echo('groups:my_status'), $body);
}
