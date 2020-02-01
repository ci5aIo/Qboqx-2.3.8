<!-- Path: mod/quebx/views/default/page/elements/space_navigator.php -->
<?php
$aspect = elgg_extract('aspect', $vars, 'agile');
$panel_items = elgg_extract('panel_items', $vars);
$aid = elgg_extract('aid', $vars);

Switch($aspect){
    case 'qboqx':
        $shelf_count = 0;
        $shelf_label = ' items';
        $count_class = 'count';
        foreach ($panel_items as $panel_item){
            unset($counter);                       $display .= print_r($panel_item, true); 
            if ($panel_item['attributes']['count'] > 0)       $counter     = elgg_format_element('div',['class'=>'counter', 'aria-label'=>'count'], $panel_item['attributes']['count']);
            if ($panel_item['attributes']['name'] == 'shelf') $shelf_count = $panel_item['attributes']['count']; 
            $items .= elgg_format_element('li',
                                         $panel_item['attributes'],
                                         $panel_item['text'].$counter);
        }
        if ($shelf_count == 1){
            $shelf_label = ' item';
            $count_class .= ' single';
        }
/*        $action       = 'quebx/add';
        $body         = elgg_format_element('div', ['class'=>'panels'],
                             elgg_format_element('ul', ['class'=>'items'],$items));
    	$form_vars    = ['data-action'=>$action,'body'=>$body];
		$form_version = $action;
	    $panels       = elgg_view_form($form_version, $form_vars);
*/	    $panels       = elgg_format_element('div', ['class'=>'panels'],
                             elgg_format_element('ul', ['class'=>'items'],$items));
        $shelf_id    = quebx_new_id('c');
        $shelf_items = elgg_view('object/shelf', ['perspective'=>'space_sidebar', 'parent_cid'=>$shelf_id, 'this_slot'=>0,'module_type'=>'warehouse']);
                
        $sidebar = "
                <div id='$aid' class='commandArea'>
                    <aside class='sidebar expanded Sidebar__expanded___1DIqeICS' data-aspect='$aspect'>
                       <div class='sidebar_wrapper'>
                          <div class='Sidebar__toggleContainer___34L56aTg'>
                              <button class='Sidebar__toggle___3X5Ypi6e toggle_sidebar' title='Toggle Sidebar'></button>
                              <button class='Shelf__toggle___pGbKiuvT toggle_shelf' title='Show items on the shelf' data-target='$shelf_id'>
                                   <span class='$count_class'>$shelf_count</span>
                              </button>
                          </div>
                          <section class='sidebar_content scrollable'>
                               $shelf_items
                               <section class='project'>
                                   <ul class='item settings_area'>
                                       <li class='ProjectSidebar__header___30T22TC6'>
                                           <div class='ProjectSidebar__teamStatus___17YjbEgP'>
                                                <div data-aid='sidebar-velocity' title='velocity' class='ProjectSidebar__velocity_wrapper___PHy8iglH'>
                                                     <button class='Velocity_Indicator___2XilNZd- undraggable' title='Velocity' data-aid='VelocityIndicator' data-project-id='2068141'>
                                                           <div class='Velocity_Indicator--icon___1l1IPRy8'></div>
                                                           <span>10</span>
                                                     </button>
                                                </div>
                                                <a href='/projects/2068141/memberships' class='members ProjectSidebar__teamMembers___1ZtT31rT' data-aid='ProjectSidebar__MembershipLink' title='1 project members'>
                                                   <span>1</span>
                                                </a>
                                           </div>
                                           <button class='DensityControl___jzMNmfpK' aria-label='Story Density Mode' data-aid='DensityControl'></button>
                                       </li>
                                   </ul>
                                   $panels
                                </section>
                            </section>
                            <footer class='sidebar_footer'>
                                <div class='disabled'>
                                    <button class='width_mode' title='Panel Width: Auto'>auto</button>
                                    <a class='fit_all disabled' title='Fit Panels to Browser (or double click on panel headers)'>
                                        <span>fit all</span>
                                    </a>
                                    <form id='form_layout' class='SidebarFooter__sliderContainer___1YVq_U7c'>
                                        <a class='narrow' title='Decrease Panel Width'>
                                            <span>decrease panel width</span>
                                        </a>
                                        <input name='layout[panel_width]' type='range' aria-label='Set panel width' class='SidebarFooter__slider___3e6IcZxR' min='375' max='1000' data-type='number' value='375' disabled=''>
                                            <a class='wide' title='Increase Panel Width'>
                                                <span>increase panel width</span>
                                            </a>
                                    </form>
                                </div>
                            </footer>
                        </div>
                    </aside>
                </div>";
        break;
    case 'agile':
        /* Agile is the original design.  Copy this design to create other aspects. Leave 'agile' intact. */
        $sidebar = "<aside class='sidebar expanded Sidebar__expanded___1DIqeICS' data-aspect='$aspect'>
                       <div class='sidebar_wrapper'>
                          <div class='Sidebar__toggleContainer___34L56aTg'>
                              <button class='Sidebar__toggle___3X5Ypi6e toggle_sidebar' title='Toggle Sidebar'></button>
                          </div>
                          <section class='sidebar_content scrollable'>
                               <section class='project'>
                                   <ul class='item settings_area'>
                                       <li class='ProjectSidebar__header___30T22TC6'>
                                           <div class='ProjectSidebar__teamStatus___17YjbEgP'>
                                                <div data-aid='sidebar-velocity' title='velocity' class='ProjectSidebar__velocity_wrapper___PHy8iglH'>
                                                     <button class='Velocity_Indicator___2XilNZd- undraggable' title='Velocity' data-aid='VelocityIndicator' data-project-id='2068141'>
                                                           <div class='Velocity_Indicator--icon___1l1IPRy8'></div>
                                                           <span>10</span>
                                                     </button>
                                                </div>
                                                <a href='/projects/2068141/memberships' class='members ProjectSidebar__teamMembers___1ZtT31rT' data-aid='ProjectSidebar__MembershipLink' title='1 project members'>
                                                   <span>1</span>
                                                </a>
                                           </div>
                                           <button class='DensityControl___jzMNmfpK' aria-label='Story Density Mode' data-aid='DensityControl'></button>
                                       </li>
                                   </ul>
                                   <div class='panels'>
                                        <ul class='items'>
                                            <li title='My work' class='item my_work visible'>
                                                <button class='panel_toggle' aria-label='hide panel' data-panel-visible='true' data-panel-id='my_work_2068141'>
                                                     <span class='panel_name'>my work</span>
                                                </button>
                                                <div class='counter' aria-label='count'>1</div>
                                            </li>
                                            <li title='Current/backlog' class='item backlog visible'>
                                                 <button class='panel_toggle' aria-label='hide panel' data-panel-visible='true' data-panel-id='backlog_2068141'>
                                                    <span class='panel_name'>current/backlog</span>
                                                </button>
                                            </li>
                                            <li title='Icebox' class='item icebox visible'>
                                                <button class='panel_toggle' aria-label='hide panel' data-panel-visible='true' data-panel-id='icebox_2068141'>
                                                    <span class='panel_name'>icebox</span>
                                                </button>
                                            </li>
                                            <li title='Done' class='item done'>
                                                <button class='panel_toggle' aria-label='show panel' data-panel-visible='false' data-panel-id='done_2068141'>
                                                    <span class='panel_name'>done</span>
                                                </button>
                                            </li>
                                            <li title='Blocked' class='item blockers'>
                                                <button class='panel_toggle' aria-label='show panel' data-panel-visible='false' data-panel-id='blockers_2068141'>   
                                                    <span class='panel_name'>blocked</span>
                                                </button>
                                                <div class='counter' aria-label='count'>1</div>
                                            </li>
                                            <li title='Epics' class='item epics'>
                                                <button class='panel_toggle' aria-label='show panel' data-panel-visible='false' data-panel-id='epics_2068141'>
                                                    <span class='panel_name'>epics</span>
                                                </button>
                                            </li>
                                            <li title='Labels' class='item labels visible'>
                                                <button class='panel_toggle' aria-label='hide panel' data-panel-visible='true' data-panel-id='labels_2068141'>
                                                    <span class='panel_name'>labels</span>
                                                </button>
                                            </li>
                                            <li title='Project history' class='item project_history'>
                                                <button class='panel_toggle' aria-label='show panel' data-panel-visible='false' data-panel-id='project_history_2068141'>
                                                    <span class='panel_name'>project history</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </section>
                            </section>
                            <footer class='sidebar_footer'>
                                <div class='disabled'>
                                    <button class='width_mode' title='Panel Width: Auto'>auto</button>
                                    <a class='fit_all disabled' title='Fit Panels to Browser (or double click on panel headers)'>
                                        <span>fit all</span>
                                    </a>
                                    <form id='form_layout' class='SidebarFooter__sliderContainer___1YVq_U7c'>
                                        <a class='narrow' title='Decrease Panel Width'>
                                            <span>decrease panel width</span>
                                        </a>
                                        <input name='layout[panel_width]' type='range' aria-label='Set panel width' class='SidebarFooter__slider___3e6IcZxR' min='375' max='1000' data-type='number' value='375' disabled=''>
                                            <a class='wide' title='Increase Panel Width'>
                                                <span>increase panel width</span>
                                            </a>
                                    </form>
                                </div>
                            </footer>
                        </div>
                    </aside>";
        break;
    default:
        break;
}
echo $sidebar;
//register_error($display);