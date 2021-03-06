<!-- Layout: quebx\views\default\page\layouts\inline.php -->
<?php
$guid      = elgg_extract('guid', $vars);
$qid       = elgg_extract('qid', $vars);
$cid       = elgg_extract('cid', $vars);
$space     = elgg_extract('space',$vars);
$aspect    = elgg_extract('aspect',$vars);
$perspective=elgg_extract('perspective', $vars);
$context   = elgg_extract('context', $vars);
$title     = elgg_extract('title', $vars, $vars['entity']->title);
$content   = elgg_extract('content', $vars);
$show_title= elgg_extract('show_title', $vars, false);
$show_save = elgg_extract('show_save', $vars, true);
$show_full = elgg_extract('show_full_view', $vars, true);
$disable_save = elgg_extract('disable_save', $vars, false);
$position  = elgg_extract('position', $vars, false);
$element   = elgg_extract('element', $vars, 'qbox');
$message   = elgg_extract('message', $vars, false);
$close_icon= elgg_view_icon('window-close',['title'=>'Close']);
$minimize_icon = elgg_view_icon('window-minimize',['title'=>'Minimize']);
$tabs      = elgg_extract('tabs', $vars, false);
//$close_icon= elgg_view_icon('delete',['title'=>'Close']);
$let_edit  = elgg_extract('let_edit', $vars, false);
$let_view  = elgg_extract('let_view', $vars, false);
$action    = elgg_extract('action', $vars);
$form_vars = elgg_extract('form_vars', $vars);
$body_vars = elgg_extract('body_vars', $vars);
if(elgg_extract('show_title', $vars, true)==true){
	$qbox_title = "<div id='qboxTitle'>$title</div>";
}
if ($perspective == 'add' || $perspective == 'edit'){ //assume form
//	$content = elgg_view_form($action, $form_vars, $body_vars);
}
if ($context == 'market'){$domain = $context;} // Used in the $full_view_link
else                     {$domain = 'jot';}
switch($aspect){
	case 'donate':
		$save_icon_disabled_title = 'Please select a recipient and an action to save';
		break;
	case 'receipt':
		unset($save_icon_disabled_title);
}
if ($disable_save){
	$disabled = 'disabled style=\'cursor:unset;\'';
	$save_icon = elgg_view_icon('save',['title'=>$save_icon_disabled_title]);
}
else {
	$save_icon = elgg_view_icon('save',['title'=>'Save', 'name'=>'apply']);
}
/*$save_button = $show_save ? "<a class='do' id='qboxSave' data-qid=$qid value='Save' $disabled data-perspective='save'>
							     $save_icon
						     </a>":
                           null;*/
if (isset($cid)){$data_cid = "data-cid='$cid'";}
if (isset($qid)){$data_qid = "data-qid='$qid'";}
if (isset($qid)){$data_id  = "id='$qid'";}
$save_button = $show_save ? "<button  class='do inlineSave inline-controls-button' type='submit' id='qboxSave' $data_qid $data_cid value='Save' $disabled data-perspective='save'>
							     $save_icon
						     </button>":
                           null;
$close_button = "<button class='inlineClose inline-controls-button' type='button' $data_qid $data_cid data-perspective='$perspective'>
					$close_icon
				</button>";
$minimize_button = "<button class='inlineMinimize inline-controls-button' type='button' $data_qid $data_cid data-perspective='$perspective'>
					$minimize_icon
				</button>";
/*$full_view_link = elgg_view('output/url',['text'=>elgg_view_icon('external-link',['title'=>'full view']),
		                                  'href'=>"$domain/$perspective/$guid"]);
*/$full_options = ['text'=>elgg_view_icon('external-link',['title'=>'maximize view']),
                   'class'=>'do',
                   'data-jq-dropdown'=>'#max-'.$qid,
		           'data-vertical-offset'=>"-60",
		           'data-horizontal-offset'=>"-515",
                   'data-element'=>'qbox-maximized',
                   'data-space'=>$space,
                   'data-aspect'=>$aspect,
		           'data-context'=>'maximized',
                   'data-guid'=>$guid,
                   'data-qid'=>'max-'.$qid,
                   'data-perspective'=>$perspective];
if ($position){$pos_style = "style='position:$position'";}
if ($let_edit){
	$edit_button = elgg_view('output/div',['content'=>elgg_view('output/url',['text'=>elgg_view_icon('edit',['title'=>'Edit','class'=>'far']), 
								                                  		      'class'            => 'do',
			                                                                  'data-element'     => $element,
			                                                                  'data-space'       => $space,
			                                                                  'data-aspect'      => $aspect,
			                                                                  'data-context'     => $context,
			                                                                  'data-guid'        => $guid,
			                                                                  'data-qid'         => $qid,
	                                                                          'data-cid'         => $cid,
			                                                                  'data-perspective' => 'edit',
			                                                                  'data-presentation'=> 'inline',
	                                                                          'data-presence'    => 'inline',
	]),
	                                       'class'  => 'inlineEdit inline-controls-button',
//			                               'options'=>['id'=>'inlineEdit']
	]);
}
if ($let_view){
	$edit_button = elgg_view('output/div',['content'=>elgg_view('output/url',['text'=>elgg_view_icon('file',['title'=>'View','class'=>'far']), 
								                                  		      'class'            => 'do',
			                                                                  'data-element'     => $element,
			                                                                  'data-space'       => $space,
			                                                                  'data-aspect'      => $aspect,
			                                                                  'data-context'     => $context,
			                                                                  'data-guid'        => $guid,
                                                                        	  'data-qid'         => $qid,
                                                                        	  'data-cid'         => $cid,
			                                                                  'data-perspective' => 'view',
			                                                                  'data-presentation'=> 'inline']),
                                    	    'class'=>'inlineView inline-controls-button',
//                                    	    'options'=>['id'=>'inlineView']
	]);
}
$full_view_link = elgg_view('output/url', $full_options);
if ($show_full){
	$full_button = elgg_view('output/div', ['content'=>$full_view_link,
	                                        'class' => 'inlineFullView inline-controls-button',
//			                                'options'=>['id'=>'inlineFullView']	    
	]);}
	if ($title && $show_title) $title = "<div class='inline-controls-title'>$title</div>";
if (!$show_title){unset($title);}
if ($message){
    $msg = elgg_view('output/div', ['content'=>$message,
        'class'  =>'message-stamp']);
}
$form_body = "<div class='inline-content-expand' $data_qid $data_cid>
				<div class='inline inline-visible' $data_id $pos_style role='data entry' tabindex='-1' data-space='$space' data-perspective='$perspective' data-context = '$context'>
						<div class='inlineLoadedContent'>
                            <div class='inline-controls'>
                                $title
                                $msg
    							$full_button
    							$save_button
    	                        $edit_button
    	                        $close_button
							</div>
							$tabs
							<div class='elgg-body inline-body'>
								<div class='elgg-layout elgg-layout-default clearfix'>
                                    $content
                                </div>
                            </div>
						</div>
				</div>
			</div>";
echo $form_body ;