<!-- jot/views/default/navigation/boqx_aspect_picker.php -->
<?php 
$aspect = elgg_extract('aspect', $vars);

switch($aspect){
    case 'collections':
        
        break;
    default:
        $boqx_aspects[] = ['name'=>'things', 'value'=>'things', 'has_children'=>false];
    	$boqx_aspects[] = ['name'=>'receipts', 'value'=>'receipts', 'has_children'=>false];
    	$boqx_aspects[] = ['name'=>'collections', 'value'=>'collections', 'has_children'=>true];
    	$boqx_aspects[] = ['name'=>'experience', 'value'=>'experience', 'has_children'=>false];
    	$boqx_aspects[] = ['name'=>'project', 'value'=>'project', 'has_children'=>false];
    	$boqx_aspects[] = ['name'=>'issue', 'value'=>'issue', 'has_children'=>false];
}
echo $boqx_aspects;