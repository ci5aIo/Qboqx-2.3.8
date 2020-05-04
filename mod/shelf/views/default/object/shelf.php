<?php
$perspective = elgg_extract('perspective', $vars, 'page');
$parent_cid  = elgg_extract('parent_cid', $vars, false);
$shelf_id    = elgg_extract('shelf_id', $vars, quebx_new_id('c'));

$file        = new ElggFile;
$file->owner_guid = elgg_get_logged_in_user_guid();
$file->setFilename("shelf.json");
if ($file->exists()) {
	$file->open('read');
	$json = $file->grabFile();
	$file->close();
}

$data = json_decode($json, true);
unset($n_items, $n_receipts);

foreach($data as $key=>$contents){                        
    $qty = NULL; $entity = NULL;
    foreach($contents as $position=>$value){ 
        while (list($position, $value) = each($contents)){
            if ($position == 'guid'){
                $guid   = $value;
                $entity = get_entity($guid);
            }
            if ($position == 'quantity'){                 
                $qty = $value;                            
            }
        }
    }
/*    if (isset($subtype) && elgg_entity_exists($guid)){
       if ($entity->getSubtype() != $subtype)
    	continue;
    }
    else {
        if (elgg_entity_exists($guid)){
            $subtype = $entity->getSubtype();
        }
    }*/
    if ($entity && elgg_entity_exists($guid))
        $subtype = $entity->getSubtype();
    switch ($subtype){
        case 'market':
        case 'item':
            ++$n_items;
            $content_item .= elgg_view('shelf/arrange', ['state'=>'selected','quantity'=>$qty, 'entity'=>$entity, 'guid'=>$guid, 'perspective'=>$perspective, 'parent_cid'=>$parent_cid]);
            break;
        case 'receipt':
        case 'transfer':
            ++$n_receipts;
            $content_receipt .= elgg_view('shelf/arrange', ['state'=>'selected','quantity'=>$qty, 'entity'=>$entity, 'guid'=>$guid, 'perspective'=>$perspective, 'parent_cid'=>$parent_cid]);
            break;
        default:
            $content_default .= elgg_view('shelf/arrange', ['state'=>'selected','quantity'=>$qty, 'entity'=>$entity, 'guid'=>$guid, 'perspective'=>$perspective, 'parent_cid'=>$parent_cid]);
    }
    // all items on the shelf
    $shelf_items .= elgg_view('shelf/arrange', ['state'=>'selected','quantity'=>$qty, 'entity'=>$entity, 'guid'=>$guid, 'perspective'=>$perspective, 'parent_cid'=>$parent_cid]);
}
Switch ($perspective){
	case 'page':
		$content = "<div id='shelf_list_items'>
		     <div class='rTable' style='width:100%'>
				<div class='rTableBody'>
				    <div class='rTableRow'>
						<div class='rTableCell' style='width:0'>
		<!-- stolen from 'file_tools\views\default\file_tools\js\site.php'
		                    <a id='shelf_select_all' class='float-alt' href='javascript:void(0);'>
		                    <span>" .  
		                        elgg_echo("file_tools:list:select_all") ."
		                    </span>
		                    <span class='hidden'>" . 
		                        elgg_echo("file_tools:list:deselect_all") . "
		                    </span></a>
		-->
		                </div>
						<div class='rTableCell' style='width:5%'>qty</div>
						<div class='rTableCell' style='width:95%'></div>
					</div>".
					$content.
		        "</div>
		    </div>
		</div>";
		break;
	case 'sidebar':
	    unset ($content);
	    if ($content_item){
	        $content .="<div class='quebx-shelf-items'>
                            <div class='quebx-list-boqx-viewarea' style='display: block;'>
                        	    <ul class='shelf-items-compartment'>
                        	    $content_item
                        	    </ul>
                            </div>
                            <span title='Close boqx' class='boqx-items-expanded'>
                                <div class='boqx-label'>Things (<span class='shelf-item-count' data-count='$n_items'>$n_items</span>)</div>
                            </span>
                	    </div>"; 
	    }
	    if ($content_receipt){
	        $content .="<div class='quebx-shelf-receipts'>
                            <div class='quebx-list-boqx-viewarea' style='display: block;'>
                        	    <ul class='shelf-items-compartment'>
                        	    $content_receipt
                        	    </ul>
                            </div>
                            <div class='boqx-label'>Receipts ($n_receipts)</div>
                	    </div>"; 
	    }
	    if ($content_default){
	        $content .="<div>
                	        <ul>
                	        $content_default
                	        </ul>
            	        </div>";
	    }
	    
	    $content = "<div id='shelf_list_items' class='shelf-list-items'>
    	    <div>".
    	        elgg_view('output/url', ['href'=>'shelf', 'text'=>'Shelf','class'=>'shelf-menu-page', 'title'=>'Jump to the Shelf'])."
        	    $content
    	    </div>
	    </div>";
		break;
	case 'space_sidebar':
	    unset ($content);
        $body_class     = elgg_extract('body_class'    , $vars, 'full-pallet__stack');
        $parent_cid     = elgg_extract('parent_cid'    , $vars, quebx_new_id('c'));
        $show_access    = elgg_extract('show_access'   , $vars, true);
        $contents_count = elgg_extract('contents_count', $vars, shelf_count_items());
        $perspective    = elgg_extract('perspective'   , $vars);
        $module_type    = elgg_extract('module_type'   , $vars, $perspective);
        $shelf          = elgg_extract('entity'        , $vars, false);
        if (!$shelf){
        	$shelf           = new ElggObject();
        	$shelf->can_edit = true;
	        $shelf->show_visible = false;
        	$shelf->title    = 'Shelf';
        	$shelf->handler  = 'shelf';
        	$shelf->context  = 'dashboard';
        	$shelf->column   = 0;
        	$shelf->guid     = 0;
        	$shelf->classes  = ['elgg-module','elgg-module-widget','elgg-state-draggable','boqx','container','droppable','tn-panelWrapper___fTILOVmk','q-module','q-module-widget'];}
//        $shelf          = (object) $shelf;
        $this_slot      = elgg_extract('this_slot'     , $vars, $shelf->column);                               $display = print_r($shelf->classes, true);
        $can_edit       = $shelf->can_edit;
        
        $pallet_header = '';
        $controls = elgg_view('object/pallet/elements/controls', [
        		'pallet'         => $shelf,
        		'show_edit'      => $can_edit,
        		'show_add'       => false,
        	    'module_type'    => $perspective,
                'cid'            => $shelf_id,
        	    'target_boqx'    => $empty_boqx_id,
                'contents_count' => $contents_count
        	]);
        $handler = $shelf->handler;
        $title = $shelf->title;
        
        $pallet_header = elgg_format_element('div',['class'=>['elgg-widget-handle','clearfix','tn-PanelHeader__inner___3Nt0t86w','tn-PanelHeader__inner--single___3Nq8VXGB']],
        					   elgg_format_element('h3',['class'=>['elgg-widget-title','tn-PanelHeader__name___2UfJ8ho9']],$title).
        					   $controls);
        $pallet_body_vars = [
        	'id'        => $cid,
            'data-boqx' => $shelf_id,
        	'class'     => ['elgg-widget-content', $body_class],
            'data-guid' => $shelf->guid,];
        
        $content_vars                  = $vars;
        $content_vars['boqx_id']       = $parent_cid;
        $content_vars['visible']       = 'show';
        $content_vars['has_collapser'] ='yes';
        $content_vars['action']        = 'show';
        $content_vars['presentation']  = $module_type;
        unset($content_vars ['title']);
        $pallet_body        = elgg_format_element('div', $pallet_body_vars, $shelf_items);
        $module_vars = [
        	'class'     => $shelf->classes,
        	'id'        => $shelf_id,
        	'data-boqx' => $parent_cid,
        	'header'    => $pallet_header];
        $pallet_class = ['elgg-widgets','q-widgets','pallet','items_draggable','ui-sortable'];
        if ($shelf->show_visible)
            $pallet_class[] = 'visible';
        echo "<!-- module_type: $module_type -->";
        //echo elgg_view_module('widget', '', $pallet_body, $widget_module_vars);
        Switch ($module_type){
            case 'warehouse':
        // @EDIT - 2020-01-25 - SAJ - Make the pallet un-draggable
//                 $rem_key = array_search('elgg-module-widget', $module_vars['class']);
//                 unset($module_vars['class'][$rem_key]);
                $pallet_body                = elgg_format_element('div', ['class'=>'tn-pallet__stack'], $pallet_body);
                $module_vars['title']       = '';
                $module_vars['body']        = $pallet_body;
                $module_vars['module_type'] = $module_type;
                $module_vars['handler']     = $handler;
                $content = elgg_format_element('div',['id'=>$parent_cid,'class'=>$pallet_class,'data-contents'=>$handler, 'data-slot'=>$this_slot],
                              elgg_view('page/components/module_warehouse', $module_vars));
                break;
        }
		break;
	case 'header':
	    $content = $content_item.$content_receipt;
	    break;
}
			
echo $content;
//register_error($display);