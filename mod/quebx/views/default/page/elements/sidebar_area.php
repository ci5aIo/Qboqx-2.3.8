<?php
$sidebar = "<aside class='sidebar expanded Sidebar__expanded___1DIqeICS'>
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
echo $sidebar;