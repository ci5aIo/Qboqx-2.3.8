<!-- Path: mod/quebx/pages/q.php -->
<?php

if (!elgg_is_logged_in()){
    forward(_elgg_walled_garden_init());
}
$title              = 'Testing';
$body               = elgg_extract('body', $vars);
$page_shell         = 'boqx';
$vars['body_attrs'] = ['id'=>'tracker', 'class'=>['js-focus-visible']];

if ($body)
    echo elgg_view_page($title, $body, $page_shell, $vars);
else 
    echo elgg_view_resource('space', $vars);
