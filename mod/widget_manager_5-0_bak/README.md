Widget Manager
==============

[![Build Status](https://scrutinizer-ci.com/g/ColdTrick/widget_manager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ColdTrick/widget_manager/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ColdTrick/widget_manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ColdTrick/widget_manager/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/coldtrick/widget_manager/v/stable.svg)](https://packagist.org/packages/coldtrick/widget_manager)
[![License](https://poser.pugx.org/coldtrick/widget_manager/license.svg)](https://packagist.org/packages/coldtrick/widget_manager)

A plugin for Elgg that adds more widget features.

Features
--------

 - Adding widgets from a lightbox 
 - Index widgets (with a few different layouts)
 - Group widgets (instead of group profile modules)
 - Provides a few new widgets (see below for the widget list)
 - Forcing of widget (incl positioning) on profile/dashboard (also later!)
 - Option to globally hide a specific widgettype (will even hide widget already placed on profile/dashboard)
 - Create group default widgets
 - Create extra pages with a widget layout
	
Widgets
-------	

### General widgets

 - Twitter widget (paste your widget code)
 - Content (blog, file, pages, bookmarks) by tag
 - rss
 - free html
 - messages
 
### Index widgets

 - login box
 - members
 - online members
 - activity
 - image slider
 - community stats
 
### Admin widgets

 - user search (also searches disabled/blocked/unvalidated users and by email)
 
Info about the fix mechanisme for Default Widgets
-------------------------------------------------
 
Fixed widgets always

 - Appear on top of other widgets
 - Can not be removed or edited or dragged by a user
 - Maintain a relation to their default widget, so if the default widgets changes, all user widgets also change!
 - New fixed widgets will also be added to existing users
 
You can fix widgets on a dashboard or profile page. Fixing widgets can be done by clicking on the pin in the widget header. Only default widgets added when widget manager is enabled will have the option to fix. This is because a special attribute will be set on the default widget to keep a relation to the widget when cloning it to the user profile or dashboard. If you do not see the pin, remove the widget and add it again. 

A user can always add his or hers own widgets. They will always be added below the last fixed widget in the destination column.
