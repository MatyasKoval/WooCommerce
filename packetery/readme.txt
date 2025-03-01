=== Packeta ===
Contributors: packeta
Tags: WooCommerce, shipping
Requires at least: WordPress 5.3, WooCommerce 4.5
Tested up to: WordPress 5.9.2, WooCommerce 6.3.1
Stable tag: 1.2.0
Requires PHP: 7.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

With the help of our official plugin, You can choose pickup points of Packeta and its external carriers in all of Europe, or utilize address delivery to 25 countries in the European Union, straight from the cart in Your e-shop. You can also submit all your orders to Packeta with just one click.

== Description ==

= Plugin functions: =

* the ability to choose a pickup place in Your cart using our widget v6
* the ability to change the destination pickup point of an existing order
* the option to allow checkout address validation using our widget HD
* delivery to Packeta pickup places (Czech Republic, Slovakian Republic, Hungary, and Romania)
* delivery to pickup places of carriers all around Europe
* the ability to add/modify the packet weight and dimensions before submitting the packet to Packeta
* automatic sending of orders to Packeta with one click
* each delivery sent to Packeta will automatically show the tracking number with a link to a website with the shipment tracking
* the printing of labels, including direct carrier labels
* printing of shipment lists (AWB)
* 18+ age verification setting for your products

= You can look forward to: =

* automatically updated information on the current packet status
* the ability to automatically change the order status according to the packet status
* the filling out of customs declarations and shipping of packets to countries outside of the EU
* the creation of claim assistant packets
* the option to choose a pickup place during the manual creation of the packet in the administration

== Installation ==

* You can install the plugin either in Your WordPress administration: Plugins->Plugin installation->Upload plugin or upload the "packetery" file into the /wp-content/plugins/ 
* Activate the plugin in the WordPress menu "Plugins"
* Set up the plugin according to our user documentation

== Frequently Asked Questions ==

= Is the plugin free? =

Yes. All functions of our plugin are completely free. No need to purchase any premium extensions.

= What are the minimum required versions of WordPress and PHP? =

In order to be able to use modern development procedures and continue to expand the functions of the plugin, it is necessary to run the plugin on WordPress 5.3+ and PHP 7.2 - 7.4. The plugin does not currently support PHP 8

= I'm missing a feature I would like to see, what should I do? =

We are constantly working on adding new features. You can find a list of features we are currently working on in the "You can look forward to" chapter. If there is a feature You would like to see added, that is missing in our list, then please contact us at technicka.podpora@zasilkovna.cz

= I have found a mistake in the plugin or need help with the installation or set up of the plugin. =

Please contact us at technicka.podpora@zasilkovna.cz .

== Changelog ==
= 1.2.0 =
* Added: Primary key for carrier table
* Updated: Packeta order meta data moved from posts to custom table
* Updated: Logger uses custom database table
* Updated: Only 3 decimal places are accepted for order weight
* Updated: JavaScript and CSS files are now loading conditionally
* Fixed: Packeta checkout validators now trigger only if Packeta shipping is selected
* Fixed: Label print page now shows the correct number of labels that will be printed
* Fixed: Widget button now shows even if no country was selected on checkout page load
* Fixed: Javascript dependencies added where missing
* Fixed: Non-Packeta order submission to Packeta API no longer creates PHP error
* Fixed: Label printing now accepts trashed orders
* Fixed: Packeta order modal now dynamically calculates weight if no weight is provided
* Fixed: Packeta logger now supports emote characters
* Fixed: Deactivating WooCommerce plugin while having Packeta plugin activated no longer crashes the entire site

= 1.1.1 =
* Fixed: Overweight orders now never have shipping for free
* Fixed: Packet API submissions now always include order currency

= 1.1.0 =
* Added: Information about the count of printed labels and "back" link added to label offset setting form
* Added: Possibility to print the same labels again in a single session in the label print page
* Added: Possibility to print single label from order list
* Added: Possibility to submit single order to Packeta from order list
* Added: Admin pickup point picker in order detail
* Added: Packaging weight in plugin settings
* Added: Checkout address validation
* Added: Possibility to edit order weight in order list
* Added: List of active carriers added to carrier settings section
* Added: Shipment lists printing
* Added: Sender verification in plugin settings
* Added: SK translation
* Updated: Carrier settings interface
* Updated: Packetery buttons in order list
* Updated: Flash messages are always first in message stack

= 1.0.7 =
* Fixed: The label print page displays a message to the user if no suitable orders are selected
* Fixed: Some environments caused error due PHPDocs being removed by OPCache
* Fixed: Order list filters can now be combined

= 1.0.6 =
* Added: Default cash-on-delivery surcharge
* Added: Cash-on-delivery surcharge was separated from shipping cost and is shown in order fees during checkout
* Updated: Carrier settings page errors highlighted
* Fixed: Only available payment methods are available for selection in plugin settings

= 1.0.5 =
* Updated: Sender description
* Fixed: Cash-on-delivery payment method is always available for selection in Packeta plugin settings
* Fixed: Checkout refresh on payment method change happens only if the value really changes

= 1.0.4 =
* Added: If the creation of the carrier table fails, the user is informed and error is logged
* Updated: Settings export expanded to be even more helpful
* Fixed: Inputs in the cart implemented so as not to affect the appearance
* Fixed: Packeta logo CSS in cart made simple and compatible
* Removed: Dependency on intl library

= 1.0.3 =
* Fixed: Use of pickup point method in cart with billing only setting enabled

= 1.0.2 =
* Fixed: Broken relative URLs in multiple places

= 1.0.1 =
* Added: Settings export to help solve various issues
* Updated: Logger no longer deletes older records
* Fixed: User no longer sees messages from other sessions
* Fixed: Logger handles double quotes in error messages
* Fixed: Corrected the count of orders in filtering links
* Fixed: Save carrier's maximum weight correctly as a float
* Fixed: Carrier name input width css rule to cover all carriers
* Fixed: Pickup point id will be stored as a string, because external carriers may require it
* Fixed: Exception handling during CreatePacket API call - faultstring used when no detail property is returned
* Fixed: CLI error - plugin now does not bootstrap in CLI environment
* Fixed: Exception when HTTP response headers are already sent - Plugin now does not bootstrap in such case

= 1.0.0 =
* Initial version

