<?php
$guid        = (int) get_input('guid');
$intention   = get_input('intention', 'remove');

$entity = get_entity($guid);

if($entity->getSubtype() != 'widget'){
    system_message('Not a pallet.  Skipping');
    return false;
}
if($intention == 'delete')
    $entity->delete();
else
    set_private_setting($guid, 'column', 0);

return true;