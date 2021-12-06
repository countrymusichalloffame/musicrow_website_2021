<?php

/*
Plugin Name: Advanced Custom Fields: Advanced Image
Plugin URI: https://www.lifeblue.com
Description: ACF Advanced Image
Version: 1.0.0
Author: Lifeblue
Author URI: https://www.lifeblue.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists('acf_plugin_advanced_image') ) :

class acf_plugin_advanced_image {

	var $settings;

	function __construct() {

		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);

		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
		add_action('acf/register_fields', 		array($this, 'include_field')); // v4
	}

	function include_field( $version = false ) {
		include_once('fields/class-acf-field-advanced-image.php');
	}

}

new acf_plugin_advanced_image();

endif;