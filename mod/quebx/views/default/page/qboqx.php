<!-- Path: mod/quebx/views/default/page/qboqx.php -->
<?php
/**
 * Forked from default Elgg pageshell
 * The standard HTML page shell that everything else fits into
 * 
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body_attrs']  Attributes of the <body> tag
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

$show_site_menu = elgg_extract('show_site_menu', $vars, true);

// backward compatability support for plugins that are not using the new approach
// of routing through admin. See reportedcontent plugin for a simple example.
if (elgg_get_context() == 'admin') {
	if (get_input('handler') != 'admin') {
		elgg_deprecated_notice("admin plugins should route through 'admin'.", 1.8);
	}
	_elgg_admin_add_plugin_settings_menu();
	elgg_unregister_css('elgg');
	echo elgg_view('page/admin', $vars);
	return true;
}   
// render content before head so that JavaScript and CSS can be loaded. See #4032

$aspect        = 'agile';
$vars['aspect']= $aspect;
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$header  = elgg_view('page/elements/qboqx_header', $vars);
$content = elgg_view('page/elements/body', $vars);
$footer  = elgg_view('page/elements/footer', $vars);
if($show_site_menu)
    $expanded_header = 'expanded_header';

$body = <<<__BODY
<div id="root" class="elgg-page elgg-page-default">
    <div id="view01" class="normal layouts show current_header_version-ia $expanded_header">
    	<div class="elgg-page-messages">
    		$messages
    	</div>
__BODY;

if (elgg_is_logged_in()) {
	$topbar = elgg_view('page/elements/topbar', $vars);

	$body .= <<<__BODY
	<div class="elgg-page-topbar">
		<div class="elgg-inner">
			$topbar
		</div>
	</div>
__BODY;
}
$body .= <<<__BODY
        <header class="page_header_container">
        	<div>
        		<div>
        			$header
        		</div>
        	</div>
        </header>
        <section class="main space project">
            $content
        </section>
    	<div class="elgg-page-footer" style="display:none;">
    		<div class="elgg-inner">
    			$footer
    		</div>
    	</div>
    </div>
</div>
__BODY;

$body .= elgg_view('page/elements/foot');
//$body .= elgg_view('css/quebx/user_agent', ['element'=>'experience']);

$head = elgg_view('page/elements/head', $vars['head']);

$params = array(
	'head' => $head,
	'body' => $body,
);

if (isset($vars['body_attrs'])) {
	$params['body_attrs'] = $vars['body_attrs'];
}

echo elgg_view("page/elements/html", $params);
