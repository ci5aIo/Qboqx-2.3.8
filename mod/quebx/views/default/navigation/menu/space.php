<?php

$space_menu = elgg_format_element('div',['id'=>'space_menu','class'=>'jq-dropdown'],
                   elgg_format_element('div',[],
                       elgg_format_element('div',['data-aid'=>'scrim','class'=>'tc_scrim_xxx','note'=>'tc_scrim creates a curtain over the page']).
                       elgg_format_element('div',['class'=>'tc_projects_menu_arrow']).
                       elgg_format_element('div',['class'=>'tc_menu tc_projects_menu'],
                           elgg_format_element('div',['class'=>'tc_projects_menu_list_group'],
                               elgg_format_element('header',['class'=>['tc_menu_header','tc_menu_header_projects']],'Projects').
                               elgg_format_element('ul',['class'=>'tc_projects_menu_list'],
                                   elgg_format_element('li',['class'=>['tc_projects_menu_item','tc_projects_menu_callout','tc_create_project']],
                                       elgg_format_element('a',['data-aid'=>'CreateProject'],'Create Project')).
                                   elgg_format_element('li',['class'=>'tc_projects_menu_item'],
                                       elgg_format_element('a',['href'=>'https://www.pivotaltracker.com/n/projects/2068141','class'=>'tc_projects_menu_item_link'],
                                           elgg_format_element('span',['class'=>'raw_project_name'],'Quebx'))).
                                   elgg_format_element('li',['class'=>'tc_projects_menu_item'],
                                       elgg_format_element('a',['href'=>'/projects'],
                                           elgg_format_element('span',['class'=>'tc_projects_menu_show_all'],'Show All Projects')))).
                               elgg_format_element('noscript',[])).
                           elgg_format_element('div',['class'=>'tc_projects_menu_list_group'],
                               elgg_format_element('header',['class'=>['tc_menu_header','tc_menu_header_workspaces']],'Workspaces').
                               elgg_format_element('ul',['class'=>'tc_workspaces_menu_list'],
                                   elgg_format_element('li',['class'=>['tc_projects_menu_item','tc_projects_menu_callout','tc_create_workspace']],
                                       elgg_format_element('a',[],'Create Workspace')).
                                   elgg_format_element('li',['class'=>'tc_projects_menu_item'],
                                       elgg_format_element('a',['href'=>'https://www.pivotaltracker.com/n/workspaces/754301','class'=>'tc_projects_menu_item_link'],
                                           elgg_format_element('span',['class'=>'raw_workspace_name'],'Wholistic View'))).
                                   elgg_format_element('li',['class'=>'tc_projects_menu_item'],
                                       elgg_format_element('a',['href'=>'/projects','class'=>'tc_projects_menu_show_all'],
                                           elgg_format_element('span',['class'=>'tc_projects_menu_show_all'],'Show All Workspaces'))))).
                           elgg_format_element('a',['href'=>'/dashboard','class'=>'tc_projects_menu_footer'],
                               elgg_format_element('span',['class'=>'tc_projects_menu_dashboard'],'Dashboard')))));

echo $space_menu;