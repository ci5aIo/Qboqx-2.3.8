<?php
elgg_register_event_handler('init', 'system', 'hypegallery_socialshare_addon');
 
    function hypegallery_socialshare_addon()
    {

    elgg_extend_view('object/hjalbumimage/meta', 'hypegallery_socialshare/extend', 1);
    elgg_extend_view('css/elgg', 'css/hypegallery_socialshare/cssextend');
}
?>
