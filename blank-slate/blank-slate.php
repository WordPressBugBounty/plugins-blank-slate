<?php
/**
 * Blank Slate
 *
 * @package           BlankSlate
 * @author            Aaron Reimann & Micah Wood
 * @copyright         Copyright 2019-2026 by Aaron Reimann & Micah Wood - All rights reserved.
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Blank Slate
 * Plugin URI:        https://wpblankslate.com
 * Description:       Provides a blank page template for use with WordPress page builders.
 * Version:           1.3.0
 * Requires PHP:      7.2
 * Requires at least: 5.8
 * Author:            Aaron Reimann & Micah Wood
 * Author URI:        https://aaronreimann.com
 * Text Domain:       blank-slate
 * Domain Path:       /languages
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . '/functions.php';

// Run on init (not plugins_loaded) so translated labels are not loaded too early (WordPress 6.7+).
add_action( 'init', 'blank_slate_bootstrap' );
