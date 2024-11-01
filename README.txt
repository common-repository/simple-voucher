=== Simple Voucher ===
Contributors: gladiaron
Donate link: www.danielsusanu.com
Tags: simple-voucher, simple voucher, courier voucher generator, tesae, pegasus
Requires at least: 5.6.4
Tested up to: 6.4.2
Stable tag: 1.2.9
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

We have built this plugin so you can simple generate a voucher for your order by clicking a button. 

This will minimize all the potential errors someone might make when manualy copying order details from one system 
to the other and it will significaly decrease the time it takes you or your emplye to create vouchers for your orders.

This plugin was built with user experience and ease of use in mind.

Notice: This plugin only works with courier companies that have the pegasus e-courier system from TESAEgroup and the courier company must sing up for our service. 
If you are a courier company get in touch for more details

== Installation ==

Installing this plugin 


1. If you download the zip file, upload `simple-voucher.php` to the `/wp-content/plugins/` directory, or import it using the plugin importer.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to settings page under woocommerce and add your couriers domain and login information
1. PDF document to help with installation and setup: https://drive.google.com/file/d/1C1jfPP6s0zY8dTlCjqsaoyAQ04BrjEs2/view?usp=sharing

== Screenshots ==

1. settings.jpg
2. button-location.jpg
3. popup.jpg

== Frequently Asked Questions == 

= What value should I enter in the domain field =

The domain value should be the login link from your courier provider. This is explaned in details in the pdf that we shared in the installation section.

== Changelog ==
= 1.2.9 =
* Added screen_id for wc orders
= 1.2.8 =
* Enable debug mode.
= 1.2.7 =
* Bumped version.
= 1.2.6 =
* Removed condition checks that are not required anymore.
= 1.2.5 =
* Fixed permission_callback access error
= 1.2.4 =
* Added permission_callback to register rest route
= 1.2.3 =
* Fixed issue with rendering simple voucher button on order page
= 1.2.2 =
* Made Imporvements
= 1.2.1 =
* Removed logging
= 1.2.0 =
* Added default service area code if none available
= 1.1.9 =
* Added condition to load hooks only when admin pages
= 1.1.8 =
* fixed minor bug with additional charges
= 1.1.7 =
* fixed minor bug
= 1.1.6 =
* added functionality to submit voucher additional charges
= 1.1.5 =
* overall improvements
= 1.1.4 =
* Added more options in the settings page
= 1.1.3 =
* Added functinality to complete order after voucher is created.
= 1.1.2 =
* Version number update.
= 1.1.1 =
* Changed how the session id is handled to prevent some issues with expired sessions.
= 1.1.0 =
* Wordpress not showing previous commit.
= 1.0.9 =
* Fixed bug that was throwing an error when searching for an order.
= 1.0.8 =
* Added list of service areas. 
= 1.0.7 =
* Added the Route Code and weight to the voucher.
= 1.0.6 =
* Improvements and bug fixes.
= 1.0 =
* Initial release.


== Upgrade Notice ==

No previous version no need for upgrade.

== Contact == 

If you are a courier and you want to sign up for this service send us an email for a quote.

email: info@danielsusanu.com
