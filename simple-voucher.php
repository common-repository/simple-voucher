<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.danielsusanu.com
 * @since             1.0.0
 * @package           Simple_Voucher
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Voucher
 * Plugin URI:        www.simple-voucher.gr
 * Description:       Με το simple voucher plugin μπορείτε να δημιουργήσετε και να φωτοτυπήσετε το voucher μιας παραγγελίας με ένα click.
 * Version:           1.2.9
 * Author:            Daniel Susanu
 * Author URI:        www.danielsusanu.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-voucher
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SIMPLE_VOUCHER_VERSION', '1.1.9' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-voucher-activator.php
 */
function activate_simple_voucher() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-voucher-activator.php';
	Simple_Voucher_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-voucher-deactivator.php
 */
function deactivate_simple_voucher() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-voucher-deactivator.php';
	Simple_Voucher_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_voucher' );
register_deactivation_hook( __FILE__, 'deactivate_simple_voucher' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-voucher.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simple_voucher() {

	$plugin = new Simple_Voucher();
	$plugin->run();

}
run_simple_voucher();
