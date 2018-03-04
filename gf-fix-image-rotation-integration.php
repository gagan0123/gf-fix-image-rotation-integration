<?php
/**
 * Plugin Name: GF Fix Image Rotation Integration
 * Plugin URI: https://wordpress.org/plugins/fix-image-rotation/
 * Description: Integrates Fix Image Rotation Plugin with Gravity Forms
 * Author: Gagan Deep Singh
 * Version: 1.0.0
 * Author URI: http://gagan0123.com
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package GF_Fix_Image_Rotation_Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'class-gf-fix-image-rotation-integration.php';

/**
 * Lets Initialize our plugin
 */
GF_Fix_Image_Rotation_Integration::get_instance();
