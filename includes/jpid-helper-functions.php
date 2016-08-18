<?php
/**
 * Collection of helper functions for this plugin.
 *
 * All functions declared here are available in the global scope.
 *
 * NOTE:
 * If number of functions listed here are getting bigger, consider
 * moving and grouping them into separate files.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Get default terms of jpid_product_type taxonomy.
 *
 * @since    1.0.0
 */
function jpid_default_product_types() {
	return array(
			'Snack' => 'snack',
			'Drink' => 'drink'
	);
}
