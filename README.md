# Drupal Module - Social Custom Links

Provides local tasks for Home page and custom subgroups view

Requirements:
 - Drupal 8
 - Group Module
 
Enables custom local task (tabs) on the Group pages for the Group Module for specific users

Features Currently implemented:
 - Provides a local task that adds home tab to Community Group pages
 - Provides a local task that add subgroups tab to Community Group pages
 - Prevents subgroups local task from displaying if not member of the Community Group
 - Prevents subgroups local task from displaying on subgroups
 - Disables all local tasks if not admin or member of Community Group
 - Alter page title for Topics, Events & About Group pages to include Group name
 - Redirect group pages to assigned domain, if accessed via direct url on incorrect domain
 - After joined group, redirect to group stream page
