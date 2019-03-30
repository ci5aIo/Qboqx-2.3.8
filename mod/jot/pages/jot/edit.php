
<?php
/**
 * Elgg jot Plugin
 * version 02_scratch
 * @package jot
 */
/*
$input_1 = get_input('guid');
$section = get_input('section');

if (elgg_entity_exists($input_1)){
    $guid = $input_1;
    $solo      = true;
    $item      = get_entity($guid);
}
else {
    $command = $input_1;
}*/

if (isset($page[1])) {
    $input = $page[1];
    if (elgg_entity_exists($input)){
        $guid        = (int) $input;
        $solo = true;
        $item = get_entity($guid);
    }
    else {                              //assume command
        $command = $input;
    }    
}

if (isset($page[2])) {
    $section = $page[2];
}
else {
	$section = $item->aspect ?: 'receipt';
}
if (elgg_instanceof($item, 'object', 'market') || $input == 0){
    $action = 'add';
    $asset  = $item;
}
else {
    $jot = $item;
    $action = 'edit';
}
$qid = $guid.'_01';                  // Experience is option 01 in the q menu

$origin = get_input('origin', $vars);

Switch ($section){
	case 'receive':
	case 'receipt':
    	$subtype = 'transfer';
    	Switch ($action){
    	    case 'add':
    	        $title = "New Receipt";
    	        break;
    	    case 'edit':
    	        $title = ($jot->aspect == 'receipt' ? 'Edit Receipt' : ($jot->aspect == 'receive' ? 'Edit Shipment Receipt': 'Edit'));
    	        break;
    	}
    	
    	$form_version = "transfers/elements/receipt";
        $form_vars    = array('name' => 'jotForm', 
                              'enctype' => 'multipart/form-data',
                              'action'  => 'action/jot/edit_v4',
    						);
        $referrer            = '';
        $description         = null;
        $body_vars           = jot_prepare_form_vars($subtype, $jot, $guid, $referrer, $description, $section);
        $body_vars['action'] = $action;
        $body_vars['asset']  = $asset;
        $body_vars['origin']=  $origin;                                                                                    $display .= '74 $body_vars: '.print_r($body_vars, true).'<br>';
    	$content             = elgg_view('output/div',['content'=> elgg_view_form($form_version, $form_vars, $body_vars),
    			                                       'class'  => 'qbox',
    			                                       'options'=> ['id' => 'q'.$guid,
    			                                                    'style' => 'padding:5px']]);
    	$params = array(
    			'content' => $content,
    			'title' => $title,
    //	        'header' => $header,
    			);
    	
    	$body = elgg_view_layout('one_sidebar_no_footer', $params);
    	break;
    case 'return':
    	$subtype = 'transfer';
    	$title   = "Return Receipt";
    	$form_version = "transfers/elements/return";
        $form_vars    = array('name' => 'jotForm', 
                              'enctype' => 'multipart/form-data',
                              'action'  => 'action/jot/edit',
    						);
        $referrer    = '';
        $description = null;
        $body_vars    = jot_prepare_form_vars($subtype, $jot, $guid, $referrer, $description, $section);	  
    	$content      = elgg_view_form($form_version, $form_vars, $body_vars);
    	$params = array(
    			'content' => $content,
    			'title' => $title,
    			);
    	
    	$body = elgg_view_layout('one_sidebar_no_footer', $params);
    	break;
    default:
        $subtype = $item->getSubtype();                                     $display .= '106 $subtype: '.$subtype.'<br>';
		$title     = 'Experience';
		$expansion_objects  = elgg_get_entities(['type'=>'object', 'container_guid'=>$guid]);
		$expansion          = $expansion_objects[0]; // There must be only one expansion of an experience
		if ($expansion) {
			$selected = $expansion->aspect;                                  $display .= '111 $selected: '.$selected.'<br>';
		}
		$tab_vars  = ['menu'         => 'q_expand',
					  'guid'         => $guid,
					  'class'        => "qbox-$guid",
					  'qid_parent'   => $qid,
					  'selected'     => $selected,];
		$expand_tabs =  elgg_view('jot/menu', $tab_vars);
		unset($tab_vars);
		$tab_vars  = ['subtype'      => 'experience',
					  'this_section' => $selected,
					  'state'        => 'selected',
					  'action'       => $action,
					  'guid'         => $guid,
					  'presentation' => 'qbox',
					  'class'        => "qbox-$guid",
					  'ul_style'     => 'border-bottom: 0px solid #cccccc',
					  'ul_aspect'    => 'attachments',
					  'link_class'   => 'qbox-q qbox-menu',
					  'attachments'  => ['things'=>1]];
		$tabs      =  elgg_view('quebx/menu', $tab_vars);
		$asset_title= get_entity($guid)->title;
		$form_vars = ['name'    => 'jotForm', 
                      'enctype' => 'multipart/form-data',
                      'action'  => 'action/jot/edit_scratch',];
		$body_vars = ['title'   => $title,
				      'guid'    => $guid,
				      'container_guid'=> $jot->container_guid,
				      'qid'     => $qid,
                      'section' => 'main',
				      'selected'=> true,
				      'presentation'=>$presentation,
				      'action'  => 'edit', 
				      'tabs'    => $tabs,
				      'expand_tabs'  => $expand_tabs,
					  'preload_panels'=>[['panel'=>'things', 'qid'=>'q'.$qid.'_1',
					  		              'panel'=>$selected, 'qid'=>'q'.$qid.'_4']],       //builds the panels in the form
		];
		$content   = elgg_view_form('experiences/edit', $form_vars, $body_vars);
    	$params    = ['content' => $content,'title' => $title,];
    	$body      = elgg_view_layout('one_sidebar_no_footer', $params);
    	break;
}
                                                                                                           register_error($display);
echo elgg_view_page($title, $body);
//register_error($display);