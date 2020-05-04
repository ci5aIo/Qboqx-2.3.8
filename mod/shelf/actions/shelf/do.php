<?php
$action = get_input('action');
$guid   = get_input('guid');

switch($action){
    case 'close':
        $message = shelf_close_boqx($guid);
        echo json_encode(['message'=>$message]);                
        break;
    case 'remove':
        $count = shelf_remove_boqx($guid);
        echo json_encode(['count'=>$count]);
        break;
}

        