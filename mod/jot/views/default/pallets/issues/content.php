<?php
$guid         = elgg_extract('guid'       , $vars);
$name         = elgg_extract('name'       , $vars);
$boqx_id      = elgg_extract('cid'        , $vars);
$slot         = elgg_extract('slot'       , $vars);
$aspect       = elgg_extract('aspect'     , $vars);
$open_class   = elgg_extract('open_class' , $vars,'');
$perspective = elgg_extract('perspective' , $vars);
$cid         = elgg_extract('cid'         , $vars, quebx_new_id ('c'));
$action      = '';
$form_vars = ['name' => 'marketForm', 
			  'enctype' => 'multipart/form-data', 
			  'action' => 'action/jot/edit_pallet'];
$form_body_vars = [];

$issue_body_vars = $vars;
$issue_body_vars['cid'] = $boqx_id; 
$issue_body_vars['action'] = 'edit';
$issue_body_vars['section']='issues';
$issue_body_vars['presentation']='open_boqx';
$issue_body_vars['presence']='item boqx';
$issue_body_vars['form_wrapper'] = ['action'=>$action,'form_vars'=>$form_vars,'body_vars'=>$form_body_vars];

$content = elgg_view('forms/experiences/edit',$issue_body_vars);

echo elgg_view_layout('pallet',['presentation'=>'open_boqx','id'=>$boqx_id,'slot'=>$slot,'aspect'=>$aspect,'name'=>$name,'pieces'=>quebx_count_pieces($guid, $aspect),'content'=>$content, 'open_class'=>$open_class]);