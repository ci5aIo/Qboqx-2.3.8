<?php
setlocale(LC_MONETARY, 'en_US');
$wwwroot = elgg_get_config('wwwroot');
//the page owner
$owner = get_user($vars['entity']->owner_guid);
$class = elgg_extract('class', $vars);
$context = elgg_get_context();
$module_type = elgg_extract('module_type', $vars);

//the number of issues to display
$num = (int) $vars['entity']->num_display;
if (!isset($num)) {
    $num = 0;
}		
elgg_load_library('elgg:market');
$dbprefix = elgg_get_config('dbprefix');
$jot_options = [
	'type' => 'object',
    'subtype'=>'issue', 
	'owner_guid' => $owner->guid, 
	'order_by'=>'time_updated desc',
	'limit' => $num,];
$jots = elgg_get_entities_from_metadata($jot_options);
Switch ($module_type){
    case 'warehouse':
        $action        = elgg_extract('action', $vars);
        $parent_cid    = elgg_extract('boqx_id', $vars, quebx_new_id('c'));
        $carton_id     = elgg_extract('carton_id', $vars);
        $hidden_fields = elgg_extract('hidden_fields', $vars);
        $presentation  = elgg_extract('presentation', $vars);
        $presence      = elgg_extract('presence', $vars, 'pallet');
        $visible       = elgg_extract('visible', $vars);
        $title         = elgg_extract('title', $vars);
        $has_collapser = elgg_extract('has_collapser', $vars,'yes');
        
	    $params  = $vars;
	    unset($params['cid']);
	    $params['fill_level']   = 'full';
	    $view                   = 'experiences'; 
        $params['action']       = 'add';
        $params['section']      = 'issue';
        $params['presentation'] = 'pallet';
        $params['presence']     = 'issue boqx';
        $params['visible']      = 'show';
                
        if (is_array($jots) && sizeof($jots) > 0) {
       		foreach($jots as $jot) {
       		    unset($info_box, $cid, $show_boqx);
				$cid = quebx_new_id('c');
        	    $params['guid']       = $jot->getGUID();
        	    $params['parent_cid']   = $cid;
                $show_boqx  = elgg_view('page/components/pallet_boqx', ['entity'=>$jot, 'aspect'=>'issue', 'boqx_id'=>$vars['boqx_id'],'presence'=>$presence]);
                $info_boqx  = elgg_view("forms/{$view}/edit",$params);
                //$form_body .= elgg_view('page/elements/envelope',['task'=>'issue', 'action'=>$action, 'guid'=>$jot->guid, 'parent_cid'=>$parent_cid, 'carton_id'=>$carton_id, 'cid'=>$cid, 'hidden_fields'=>$hidden_fields,'show_boqx'=>$show_boqx, 'info_boqx'=>$info_boqx,'presentation'=>$presentation, 'presence'=>$presence, 'visible'=>$visible, 'title'=>$title,'has_collapser'=>'yes','fill_level'=>'full']);
                //echo $pallet_boqx;
                $form_body .= $show_boqx;
       		   }
        }
        break;
}
echo $form_body;
                