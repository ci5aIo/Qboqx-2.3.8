<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$img_guid           = elgg_extract('marketguid', $vars, false);
if(!$img_guid) goto eof;
$size               = elgg_strtolower(elgg_extract('size',$vars, 'master'));
$class              = elgg_extract('class', $vars);
$tu                 = elgg_extract('tu'   , $vars);

$entity             = get_entity($img_guid);

$options['guid']    = $img_guid; 
$options['size']    = $size;
$options['class'][] = "elgg-photo";
$options['tu']      = $tu;
if($class)
     $options['class'][] = $class;

//Confirm that the image exists
if      ($entity->mimetype == 'image/png')  $filename = "icons/" . $entity->guid . $size . ".png";
else if ($entity->mimetype == 'image/gif') 	$filename = "icons/" . $entity->guid . $size . ".gif";
else                                        $filename = "icons/" . $entity->guid . $size . ".jpg";

$filehandler             = new ElggFile();
$filehandler->owner_guid = $entity->owner_guid;
$filehandler->setFilename($filename);
$filehandler->open('read');
$etag                    = md5($filehandler->icontime . $size);
$contents                = $filehandler->grabFile();
$filehandler->close();

// The image exists ...
if ($contents){
//@EDIT 21020-05-13 - SAJ - make the image it's own icon
    $entity->icon = $img_guid;
    echo elgg_view('output/image',$options);
}
else 
    return false;

eof: