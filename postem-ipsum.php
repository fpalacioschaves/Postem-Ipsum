<?php
/**
 * Plugin Name:       Postem Ipsum
 * Plugin URI:
 * Description:       Plugin to create some random posts from scratch
 * Version:           1.0
 * Author:            Fco Palacios
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       postem-ipsum
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'POSTEM_IPSUM_TEXT_DOMAIN', 'postem-ipsum' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'core/postem-ipsum-core.php';
$PluginName = new PostemIpsum_Core ();

/**
 * Begins execution of the plugin.
 *
 */
$PluginName->run();
