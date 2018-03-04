<?php
/**
 * Class to integrate Fix Image Rotation with Gravity Forms plugin
 *
 * @package GF_Fix_Image_Rotation_Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'GF_Fix_Image_Rotation_Integration' ) ) {

	/**
	 * Integrates Fix Image Rotation with Gravity Forms plugin
	 */
	class GF_Fix_Image_Rotation_Integration {

		/**
		 * The instance of the class GF_Fix_Image_Rotation_Integration
		 *
		 * @since 1.0
		 *
		 * @access protected
		 *
		 * @var GF_Fix_Image_Rotation_Integration
		 */
		protected static $instance = null;

		/**
		 * Constructs the plugin object and adds required actions
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'conditionally_add_fix_image_action' ) );
		}

		/**
		 * Conditionally hooks fix image action
		 *
		 * @since 1.0
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function conditionally_add_fix_image_action() {
			if ( class_exists( 'GFFormsModel' ) && class_exists( 'Fix_Image_Rotation' ) ) {
				add_action( 'gform_after_submission', array( $this, 'fix_image_rotation_on_file_uploads' ), 100, 2 );
			}
		}

		/**
		 * Returns the current instance of the class, in case some other
		 * plugin needs to use its public methods.
		 *
		 * @since 1.0
		 *
		 * @access public
		 *
		 * @return GF_Fix_Image_Rotation_Integration Returns the current instance of the class
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Fixes the orientation of images upon form submissions
		 *
		 * @since 1.0
		 *
		 * @access public
		 *
		 * @action gform_after_submission
		 *
		 * @param Array $entry The entry that was just created.
		 *
		 * @param Array $form The current form.
		 */
		public function fix_image_rotation_on_file_uploads( $entry, $form ) {
			if ( ! isset( $form['fields'] ) || ! is_array( $form['fields'] ) ) {
				return;
			}

			$upload_path = $this->get_gravity_forms_upload_path( $form['id'] );

			foreach ( $form['fields'] as $field ) {
				if ( ! is_a( $field, 'GF_Field_FileUpload' ) || ! isset( $entry[ $field->id ] ) ) {
					continue;
				}

				$filename  = basename( $entry[ $field->id ] );
				$file_path = trailingslashit( $upload_path ) . $filename;

				$suffix = strtolower( substr( $filename, strrpos( $filename, '.', -1 ) + 1 ) );
				if ( in_array( $suffix, array( 'jpg', 'jpeg', 'tiff' ), true ) ) {
					$fir = Fix_Image_Rotation::get_instance();
					$fir->fix_image_orientation( $file_path );
				}
			}
		}

		/**
		 * Returns the upload path for a particular form
		 *
		 * Code for this is directly copied from Gravity Forms plugin.
		 *
		 * @since 1.0
		 *
		 * @access public
		 *
		 * @param int $form_id Form ID.
		 *
		 * @return string Upload Path for the given form.
		 */
		public function get_gravity_forms_upload_path( $form_id ) {
			$time        = current_time( 'mysql' );
			$y           = substr( $time, 0, 4 );
			$m           = substr( $time, 5, 2 );
			$target_root = GFFormsModel::get_upload_path( $form_id ) . "/$y/$m/";
			return $target_root;
		}

	}
}
