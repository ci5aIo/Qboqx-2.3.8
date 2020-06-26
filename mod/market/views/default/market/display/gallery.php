<?php

$section     = elgg_extract('section'    , $vars);
$entity      = elgg_extract('entity'     , $vars);
$boqx        = elgg_extract('boqx'       , $vars);
$cid         = elgg_extract('cid'        , $vars);
$carton_id   = elgg_extract('carton_id'  , $vars);
$image_guid  = elgg_extract('image_guid' , $vars);                                                              
$image_guids = elgg_extract('image_guids', $vars, $entity->images);
$origin      = elgg_extract('origin'     , $vars, false);
$output_as   = elgg_extract('output_as'  , $vars, 'string'); //options: array, string
$size        = elgg_extract('size'       , $vars, 'medium');
$presence    = elgg_extract('presence'   , $vars, false);

if (!is_array($image_guids))
     $image_guids = (array)$image_guids;                                                                    

echo "<!--origin=$origin, section=$section, image_guid=$image_guid-->";
switch($section){
    case 'thumbnails':                                                                                        $display .= 'image_guids: '.print_r($image_guids, true).'<br>boqx: '.$boqx.'<br>carton_id:'.$carton_id.'<br>size: '.$size.'<br>';
        foreach ($image_guids as $key=>$image_guid){
            unset($class, $thumbnail);
            $thumbnail_id          = quebx_new_id('c');
            $class        = ['media-item'];
            if ($image_guid == $entity->icon)
               $class[]   = 'selected';
            if(!elgg_entity_exists($image_guid))
                continue;
            if(get_entity($image_guid)->getSubtype() != 'hjalbumimage')
                continue;
            $thumbnail    = elgg_view('market/display/gallery',['section'=>'thumbnail','boqx'=>$boqx,'cid'=>$thumbnail_id,'image_guid'=>$image_guid,'entity'=>$entity,'size'=>$size,'origin'=>'market/display/gallery>'.$section]);

            if (!empty($thumbnail)){
                if($presence && $presence == 'empty boqx'){
                    $hidden[]      = ['name'=>"jot[$cid][images]",'value'=>$image_guid];
                    $hidden_fields = quebx_format_elements('hidden',$hidden);}
                $thumbnails[]      = elgg_format_element('div',['id'=>$thumbnail_id,'class'=>$class,'data-guid'=>$image_guid,'data-boqx'=>$boqx,'data-cid'=>$cid,'data-carton'=>$carton_id], 
                                         $hidden_fields.$thumbnail);
            }
        }
        if ($output_as == 'string' && is_array($thumbnails))
             $form_data = implode('',$thumbnails);
        else $form_data = $thumbnails;
        break;
    case 'thumbnail_show':                          
        foreach ($image_guids as $key=>$image_guid){                                                      $display .= 'image_guid: '.$image_guid.'<br>';
            $thumbnail     = elgg_view('market/thumbnail', ['marketguid' => $image_guid, 'size'=>$size]);
             $thumbnails[] = elgg_format_element('div',['id'=>$thumbnail_id,'class'=>$class,'data-guid'=>$image_guid,'data-boqx'=>$boqx,'data-cid'=>$cid,'data-carton'=>$carton_id], 
                                 $thumbnail);
        }
        if ($output_as == 'string' && is_array($thumbnails))
             $form_data = implode('',$thumbnails);
        else $form_data = $thumbnails;                                                                    $display .= $form_data;
        break;
    case 'thumbnail':                                                                                     $display .= 'image_guid: '.$image_guid.'<br>';
        $image = get_entity($image_guid);                                                                 $display .= 'image subtype: '.$image->getSubtype().'<br>';
        if($image->getSubtype() != 'hjalbumimage')
            break;
//        $thumbnail = elgg_view_entity($image,['size'=>'medium']);
        $thumbnail = elgg_view('market/thumbnail', ['marketguid' => $image_guid, 'size'=>$size]);
        if($thumbnail && !empty($thumbnail)){
            $selector_class = ['action-item', 'selector'];
            $selector_title    = "Set as default icon";
            if ($image_guid == $entity->icon){
               $selector_title = 'Default icon';
            }
            /*******************************************
             //Get the size of the master image for colorbox
                //Confirm that the image exists
                $size = 'master';
                if      ($image->mimetype == 'image/png')  $filename = "icons/" . $image_guid . $size . ".png";
                else if ($image->mimetype == 'image/gif')  $filename = "icons/" . $image_guid . $size . ".gif";
                else                                       $filename = "icons/" . $image_guid . $size . ".jpg";
                        
                $filehandler             = new ElggFile();
                $filehandler->guid       = $image_guid;
                $filehandler->setFilename($filename);
                $filehandler->open('read');
                $etag                    = md5($filehandler->icontime . $size);
                $input_name              = $filehandler->grabFile();
                $filehandler->close();
            	$imgsizearray = getimagesize($input_name->getFilenameOnFilestore());
            	$width  = elgg_extract('0',$imgsizearray,'500');
            	$height = elgg_extract('1',$imgsizearray,'525');
            /*******************************************/
            $width = 500;
            $height = 525;
            	
            $overlay = elgg_format_element('div',['class'=>"action-menu"],
                           elgg_format_element('ul',[],
                        	   elgg_format_element('li',['class'=>"action-menu-item"],
                        			elgg_format_element('a',['class'=>$selector_class,'data-cid'=>$cid,'data-boqx'=>$boqx,'data-aid'=>"set_default",'title'=>$selector_title]))).
                           elgg_format_element('ul',[],
                        	   elgg_format_element('li',['class'=>["hidden","action-menu-item"]],
                        			elgg_format_element('a',['class'=>["elgg-lightbox"],'data-boqx'=>$boqx,'data-cid'=>$cid,'data-aid'=>"settings",'title'=>"Set image properties",'href'=>"http://qboqx.smarternetwork.com/ajax/gallery/image/settings?guid={$image_guid}",'data-color-box-opts'=>json_encode(['width'=>750, 'height'=>500, 'trapFocus'=>false])],
                        			  elgg_format_element('span',['class'=>["elgg-icon","fa","elgg-icon-settings-alt","fa-cog"]]))).
                        	   elgg_format_element('li',['class'=>["action-menu-item"]],
                        			elgg_format_element('a',['class'=>["action-item"],'data-boqx'=>$boqx,'data-cid'=>$cid,'data-aid'=>"detach",'title'=>"Detach"],
                        			  elgg_format_element('span',['class'=>["elgg-icon","fa","elgg-icon-delete-alt","fa-times-circle"]])))));
            $options      = ['text' => $thumbnail, 
                             'class' => 'elgg-lightbox',
            	     	     'data-colorbox-opts' => json_encode(['width'=>$width, 'height'=>$height]),
                             'href' => "market/viewimage/$image_guid"];
            $form_data    = $overlay.
                            elgg_format_element('div',['class'=>$image_class], elgg_view('output/url', $options));  
        }
        break;
    default:
        $image_guids[] = $entity->icon;                                                                         $display .= '$entity->icon = '.$entity->icon.'<br>';
        $image_guids   = array_unique(array_filter($image_guids));
        $thumbnails    = elgg_view('market/display/gallery',['section'=>'thumbnails','boqx'=>$boqx,'carton_id'=>$carton_id,'cid'=>$cid, 'entity'=>$entity,'image_guids'=>$image_guids,'size'=>$size,'presence'=>$presence,'origin'=>'market/display/gallery>'.$section]);
        $form_data     = elgg_format_element('div',['class'=>['scrollimage','mediaBoqx_fnBMgIOE'],'data-carton'=>$carton_id], $thumbnails);
        break;
}

echo $form_data;
//register_error($display);