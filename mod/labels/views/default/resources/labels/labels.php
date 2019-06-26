<?php

/*namespace Quebx\Labels;*/

gatekeeper();

$vars['user'] = elgg_get_logged_in_user_entity();

$body = elgg_view_form('labels/add', array('class' => 'labels'), $vars);

echo elgg_view_module('info', elgg_echo('labels:form:header'), $body);
