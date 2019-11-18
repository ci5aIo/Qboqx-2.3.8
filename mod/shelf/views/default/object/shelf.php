<?php
$perspective = elgg_extract('perspective', $vars, 'page');
$parent_cid  = elgg_extract('parent_cid', $vars, false);

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
    if (isset($subtype) && elgg_entity_exists($guid)){
       if ($entity->getSubtype() != $subtype)
    	continue;
    }
    else {
        if (elgg_entity_exists($guid)){
            $subtype = $entity->getSubtype();
        }
    }
    switch ($subtype){
        case 'market':
        case 'item':
            ++$n_items;
            $content_item .= elgg_view('shelf/arrange', ['quantity'=>$qty, 'entity'=>$entity, 'perspective'=>$perspective, 'parent_cid'=>$parent_cid]);
            break;
        case 'receipt':
            ++$n_receipts;
            $content_receipt .= elgg_view('shelf/arrange', ['quantity'=>$qty, 'entity'=>$entity, 'perspective'=>$perspective, 'parent_cid'=>$parent_cid]);
            break;
        default:
            $content_default .= elgg_view('shelf/arrange', ['quantity'=>$qty, 'entity'=>$entity, 'perspective'=>$perspective, 'parent_cid'=>$parent_cid]);
    }
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
	case 'header':
	    $content = $content_item.$content_receipt;
	    break;
}
			
echo $content;