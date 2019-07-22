<?php

/*$space_menu = "
<div id='space_menu' class='jq-dropdown jq-dropdown-tip'>
    <div class='jq-dropdown-panel'>*/
$space_menu = "
<div id='space_menu' class='qboqx-dropdown'>
    <div>
        <div data-aid='scrim' class='tc_scrim'></div>
        <div class='tc_projects_menu_arrow'></div>
        <div class='tc_menu tc_projects_menu'>
            <div class='tc_projects_menu_list_group'>
                <header class='tc_menu_header tc_menu_header_projects'>Projects</header>
                <ul class='tc_projects_menu_list'>
                    <li class='tc_projects_menu_item tc_projects_menu_callout tc_create_project'>
                        <a data-aid='CreateProject'>Create Project</a>
                    </li>
                    <li class='tc_projects_menu_item'>
                        <a href='https://www.pivotaltracker.com/n/projects/2068141' class='tc_projects_menu_item_link'>
                            <span class='raw_project_name'>Quebx</span>
                        </a>
                    </li>
                    <li class='tc_projects_menu_item'>
                        <a href='/projects'>
                            <span class='tc_projects_menu_show_all'>Show All Projects</span>
                        </a>
                    </li>
                </ul>
                <noscript></noscript>
            </div>
            <div class='tc_projects_menu_list_group'>
                <header class='tc_menu_header tc_menu_header_workspaces'>Workspaces</header>
                <ul class='tc_workspaces_menu_list'>
                    <li class='tc_projects_menu_item tc_projects_menu_callout tc_create_workspace'>
                        <a>Create Workspace</a>
                    </li>
                    <li class='tc_projects_menu_item'>
                        <a href='https://www.pivotaltracker.com/n/workspaces/754301' class='tc_projects_menu_item_link'>
                            <span class='raw_workspace_name'>Wholistic View</span>
                        </a>
                    </li>
                    <li class='tc_projects_menu_item'>
                        <a href='/projects' class='tc_projects_menu_show_all'>
                            <span class='tc_projects_menu_show_all'>Show All Workspaces</span>
                        </a>
                    </li>
                </ul>
            </div>
            <a href='/dashboard' class='tc_projects_menu_footer'>
                <span class='tc_projects_menu_dashboard'>Dashboard</span>
            </a>
        </div>
    </div>
</div>";

echo $space_menu;