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
$header_class = '';
if($show_site_menu)
    $expanded_header = 'expanded_header';

$file        = new ElggFile;
$file->owner_guid = elgg_get_logged_in_user_guid();
$file->setFilename("shelf.json");
if ($file->exists()) {
	$file->open('read');
	$json = $file->grabFile();
	$file->close();
}

$data = json_decode($json, true);
foreach($data as $key=>$contents){
    if (array_key_exists('open_state',$contents) && $contents['open_state'] != 'closed'){
       $root_class='boqx-open';
       $header_class = 'compressed';
       $boqx_state = $contents['open_state'];
       $boqx_open  = $contents['guid'];
       continue;
    }
}
if ($boqx_open)
    $open_boqx = elgg_view_resource('shelf/open_boqx',['guid'=>$boqx_open,'open_state'=>$boqx_state]);
if (elgg_is_logged_in()) {
	$topbar = elgg_format_element('div',['class'=>"elgg-page-topbar"],
	              elgg_format_element('div',['class'=>"elgg-inner"],
			          elgg_view('page/elements/topbar', $vars)));
$body_header = elgg_format_element('header',['class'=>"page_header_container"],
            	   elgg_format_element('div',['class'=>['closed-boqx',$header_class]],
            		   elgg_format_element('div',[],$header)).
                    $open_boqx).
               elgg_format_element('section',['class'=>["main","space","project"]],$content).
               elgg_format_element('div',['class'=>"elgg-page-footer",'style'=>"display:none;"],
    		       elgg_format_element('div',['class'=>"elgg-inner"],$footer));
$root = elgg_format_element('div',['id'=>'root','class'=>["elgg-page","elgg-page-default","$root_class"],'open-boqx'=>$boqx_open,'open-state'=>$boqx_state],
            elgg_format_element('div',['id'=>"view01",'class'=>["normal","layouts","show","current_header_version-ia", $expanded_header]],
    	         elgg_format_element('div',['class'=>"elgg-page-messages"],$messages).
                 $topbar.
                 $body_header));
}
$body = <<<__BODY
$root
__BODY;

/****@EDIT 2020-01-31 - SAJ - Replaced below with above^ ****/
$body_xxx = <<<__BODY
<div id="root" class="elgg-page elgg-page-default $root_class" $boqx_state1 $boqx_open1>
    <div id="view01" class="normal layouts show current_header_version-ia $expanded_header">
    	<div class="elgg-page-messages">
    		$messages
    	</div>
__BODY;

if (elgg_is_logged_in()) {
	$topbar = elgg_view('page/elements/topbar', $vars);

	$body_xxx .= <<<__BODY
	<div class="elgg-page-topbar">
		<div class="elgg-inner">
			$topbar
		</div>
	</div>
__BODY;
}
$body_xxx .= <<<__BODY
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
/**********/

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
