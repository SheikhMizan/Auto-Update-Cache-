=== Auto Update Cache ===
Tags: auto update cache, clear cache, update cache, browser caching, browser cache, update css, update js, assets, frontend, development
Requires at least: 4.0
Tested up to: 4.9.6
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Update the version of all CSS and JS files. Show the latest changes on the site without asking the client to clear browse

== Description ==
Made CSS/JS file changes but not showing on website? this plugin will help.

Auto Update Cache allows you to update the version of all CSS and JS files automatically or manually in one click. Show latest changes to the users/viewers.

= How it works? =

By default, WordPress loads assets using query param \"ver\" in the URL (e.g., style.css?ver=4.9.6). It allows browsers to cache this files until the WordPress core will not be upgraded to a newer version.

To prevent caching of CSS and JS files, this plugin adds query param \"time\" with beautiful easy to use dashboard panel (e.g., style.css?ver=4.9.6&time=1526905286) to all links, loaded using wp_enqueue_style and wp_enqueue_script functions.


== Installation ==
= From WordPress dashboard =

1. Visit \"Plugins > Add New\".
2. Search for \"Auto Update Cache\".
3. Install and activate Auto Update Cache plugin.

= From WordPress.org site =

1. Download Auto Update Cache plugin.
2. Upload the \"auto-update-cache\" directory to your \"/wp-content/plugins/\" directory.
3. Activate Auto Update Cache on your Plugins page.

== Changelog ==
1.0 - Initial Release