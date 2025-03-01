<?php
/**
 * Plugin implementing Packeta shipping methods.
 *
 * @package   Packeta
 *
 * @wordpress-plugin
 *
 * Plugin Name: Packeta
 * Description: With the help of our official plugin, You can choose pickup points of Packeta and it's external carriers in all of Europe, or utilize address delivery for 25 countries in the European Union, straight from the cart in Your eshop. You can submit all of Your orders to Packeta with one click.
 * Version: 1.0.0
 * Author: Zásilkovna s.r.o.
 * Author URI: https://www.zasilkovna.cz/
 * Text Domain: packetery
 * Domain Path: /languages
 *
 * WC requires at least: 4.5
 * WC tested up to: 5.7.1
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly.
use Packetery\Module\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
 */
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	exit;
}

$container = require __DIR__ . '/bootstrap.php';
$container->getByType( Plugin::class )->run();
