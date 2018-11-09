<?php
$element      = elgg_extract('element', $vars);
$guid         = elgg_extract('guid', $vars);


Switch ($element){
    case 'delete':
        // Get current shelf data
        $owner_guid = elgg_get_logged_in_user_guid();                  //$display .= '$guid: '.$guid.PHP_EOL;
        $file = new ElggFile;
        $file->owner_guid = $owner_guid;
        $file->setFilename("shelf.json");
        if ($file->exists()) {
            $file->open('read');
            $json = $file->grabFile();                                 //$display .= '15 $json:'."$json<br>";
            $file->close();
        }
        
        $data = json_decode($json, true);
            foreach($data as $data_key =>$line){                       //$display .= '$data_key: '."$data_key = $line<br>";
                foreach($line as $key1=>$value1){                      //$display .= '$key1: '."$key1 = $value1<br>";
                    if ($key1 == 'guid' && $value1 == $guid){          //$display .= '22 $data_key: '."$data_key; ".'$guid: '.$guid.'; $qty: '.$qty.'<br>';
                    unset($data[$data_key]);
                    }
                }
            }
        // Write shelf data
        $file = new ElggFile;
        $file->owner_guid = $owner_guid;
        $file->setFilename("shelf.json");
        $file->open('write');
        $file->write(json_encode($data));
        $file->close();
        break;
    case 'load':
        $params = new stdClass();
        
        $input_keys = array_keys((array) elgg_get_config('input'));
        $request_keys = array_keys((array) $_REQUEST);
        $keys = array_unique(array_merge($input_keys, $request_keys));
        foreach ($keys as $key) {
            if ($key) {
                $params->$key = get_input($key);
            }
        }
        
        $params->quantity = (int) $params->quantity;
        
        if ($params->quantity < 0) {
            register_error(elgg_echo('cart:add_to_cart:error:negative_quantity'));
            forward(REFERRER);
        }
        if ($params->quantity == 0) { // Makes no sense to add zero items to the shelf.  Allows for quick load from entity menu.
            $params->quantity = 1;
        }
        
        $owner_guid = elgg_get_logged_in_user_guid();                  //$display .= '$guid: '.$guid.PHP_EOL;
        $file = new ElggFile;
        $file->owner_guid = $owner_guid;
        $file->setFilename("shelf.json");
        if ($file->exists()) {
            $file->open('read');
            $json = $file->grabFile();                                 //$display .= '15 $json:'."$json<br>";
            $file->close();
        }
        $data = json_decode($json, true);                         //$display .= '$data: '.$data.PHP_EOL;
        foreach($data as $key=>$value){                           $display .= '39 $data->$key: '.$key.'=>'.$value.PHP_EOL;
            foreach($value as $key1=>$value1){                    //$display .= '40 $value->$key1: '.$key1.'=>'.$value1.PHP_EOL;
                if ($key1 == 'guid'){
                    $guids[] = $value1;
                }
            }
        }
        if (in_array($guid, $guids)){
            $skip = true;
        }
        if (!$skip){
            // from CartStorage.php>put($guid, $data)
            $owner_guid = elgg_get_logged_in_user_guid();
            $data[] = $params;
            $file = new ElggFile;
            $file->owner_guid = $owner_guid;
            $file->setFilename("shelf.json");
            $file->open('write');
            $file->write(json_encode($data));
            $file->close();
            system_message(elgg_echo('shelf:add_to_shelf:success'));
            
            $entity = get_entity($guid);
            $perspective = 'sidebar';
            $qty = 1;
            $content = elgg_view('shelf/arrange', ['quantity'=>$qty, 'entity'=>$entity, 'perspective'=>$perspective]);
        }
        else {
            system_message(elgg_echo('Item is already on the shelf.  Skipping.'));
            $content = false;
        }
        break;
    default:
        
        
    
}
echo $content;