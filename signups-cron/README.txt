=== Signups Cron ===
Contributors: scottmotion
Tags: users, members, signups, cron, buddypress
Requires at least: 6.2.0
Tested up to: 6.7.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage WordPress user signups via WP-Cron.

== Description ==

Enable a cron event that will remove active and/or pending user signups from the database.

Features:
* Displays information about the signups table such as size and number of signups.
* Enable or disable removal of active and pending user signups independently.
* Set separate thresholds for how old the signups should be before removal.
* Choose to email a report to the Site Admin each time the event runs.
* Set how often the cron event will run and see when the next event is scheduled for.

Compatibility:

This plugin is designed to work with single-site installations running BuddyPress (2.0 or later) or BuddyBoss.

== Installation ==

1. Install.
2. Activate.
3. Go to Users > Signups Cron to change settings.

== Frequently Asked Questions ==

= What does this plugin do? =

This plugin creates a WP-Cron event that will trigger removal of rows from the signups table in your site's database.

= Will it work on my site? =

This plugin is designed to work with single-site installations running BuddyPress (2.0 or later) or BuddyBoss.

= I have a multisite installation. Will this plugin work on my network? =

While multsite installations do use the signups table, this plugin is not yet designed to work with networks, regardless of whether or not BuddyPress or BuddyBoss is installed. Check have been made to prevent the plugin from being used on such sites. Please do not install this plugin on multisite networks.

= What time will the scheduled cron event run? =

The event will run once immediately and be scheduled to recur at the time the submit button is clicked.

= Can I choose the time when the cron event will run? =

Currently you cannot choose the event time. If you change the schedule (i.e. from Daily to Weekly) the event will be rescheduled to the time the submit button is clicked. You can reset the time of the event by disabling both Active and Pending cron, clicking submit, then enable either cron and click submit again at the time you wish the event to run.

Other plugin that modify WP-Cron events may allow you to change the event time.

= How do I disable the cron event from running? =

On the plugin's settings page uncheck both 'Enable Active signups cron' and 'Enable Pending signups cron', then click the submit button. The scheduled cron event will be removed while all other setting will be saved.

Alternatively, disabling the plugin will automatically uncheck both settings and remove the scheduled cron event. All other settings will be kept.

== Screenshots ==

1. The Signups Cron settings page.

== Changelog ==

= 1.0.0 =
* Initial public release.
