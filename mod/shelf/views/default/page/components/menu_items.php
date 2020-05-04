<?php 

$guid = elgg_extract('guid', $vars);
$entity = get_entity($guid);
switch($entity->getSubtype()){
    case 'market':
    case 'item':
        
        break;
    case 'experience':
        
        break;
    case 'issue':
        
         break;
    case 'receipt':
        
        break;
    default:
        break;    
}



<ul class='items'>
                                        <li class='item details visible' name='details' count='70' id='details_{$guid}' cid='c93139' visible='visible' data-boqx='a42921'>
                                             <button class='pallet_toggle' data-pallet-visible='data-pallet-visible' data-boqx='details' data-cid='c93139'>
                                                  <span class='pallet_name'>details</span></button>
                                             <div class='counter' aria-label='count'>70</div></li>
                                        <li class='item contents visible' name='contents' id='contents_{$guid}' cid='c39253' data-boqx='a42921'>
                                             <button class='pallet_toggle' data-boqx='river_widget' data-cid='c39253'>
                                                  <span class='pallet_name'>contents</span></button></li>
                                        <li class='item accessories visible' name='accessories' count='15' id='accessories_{$guid}' cid='c98543' visible='visible' data-boqx='a42921'>
                                             <button class='pallet_toggle' data-pallet-visible='data-pallet-visible' data-boqx='accessories' data-cid='c98543'>
                                                  <span class='pallet_name'>accessories</span></button>
                                                  <div class='counter' aria-label='count'>15</div></li>
                                        <li class='item components visible' name='components' id='components_{$guid}' cid='c66402' data-boqx='a42921'><button class='pallet_toggle' data-boqx='components' data-cid='c66402'><span class='pallet_name'>components</span></button></li>
                                        <li class='item issues visible' name='issues' count='21' id='issues_{$guid}' cid='c49120' visible='visible' data-boqx='a42921'><button class='pallet_toggle' data-pallet-visible='data-pallet-visible' data-boqx='issue' data-cid='c49120'><span class='pallet_name'>issues</span></button><div class='counter' aria-label='count'>21</div></li>
                                        <li class='item experiences visible' name='experiences' id='experiences_{$guid}' cid='c55465' data-boqx='a42921'><button class='pallet_toggle' data-boqx='experiences' data-cid='c55465'><span class='pallet_name'>experiences</span></button></li>
                                        <li class='item gallery visible' name='history' id='gallery_{$guid}' cid='c20169' data-boqx='a42921'><button class='pallet_toggle' data-boqx='gallery' data-cid='c20169'><span class='pallet_name'>gallery</span></button></li>
                                        <li class='item documents visible' name='documents' id='documents_{$guid}' cid='c24393' data-boqx='a42921'><button class='pallet_toggle' data-boqx='documents' data-cid='c24393'><span class='pallet_name'>documents</span></button></li>
                                   </ul>";