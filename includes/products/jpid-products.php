<?php

/**
 * Collection of functoins to manage product object.
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
 * @since     1.0.0
 * @return    array    An array of name-slug pairs.
 */
function jpid_default_product_types() {
	return array(
			'Snack' => 'snack',
			'Drink' => 'drink'
	);
}

/**
 * Get product type term objects.
 *
 * @since     1.0.0
 * @return    array    Array of jpid_product_type WP_Term objects.
 */
function jpid_get_product_type_terms() {
	return get_terms( array(
			'taxonomy'   => 'jpid_product_type',
			'hide_empty' => false,
			'orderby'    => 'id',
			'order'      => 'ASC'
	) );
}

/**
 * Get product type term objects.
 *
 * @since     1.0.0
 * @param     int      $product_type_id    ID of jpid_product_type meta value in jpid_product_category term object.
 * @return    array                        Array of jpid_product_category WP_Term objects.
 */
function jpid_get_product_category_terms( $product_type_id = '' ) {
	$args = array(
		'taxonomy'   => 'jpid_product_category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC'
	);

	if ( is_numeric( $product_type_id ) ) {
		$args['meta_key']   = 'jpid_product_type';
		$args['meta_value'] = $product_type_id;
	}

	return get_terms( $args );
}

/**
 * Get an instance of JPID_Product object.
 *
 * @since    1.0.0
 * @param    int|JPID_Product|WP_Post    $product    Post ID, product object, or post object.
 * @return   JPID_Product                            Return global product object if no parameter passed.
 *                                                   Otherwise return specific product object.
 */
function jpid_get_product( $product = null ) {
	global $theproduct;

	$return_product = null;

	if ( empty( $product ) ) {
		$return_product = jpid_get_theproduct();
	} else {
		if ( jpid_is_product( $theproduct ) ) {
			$id_is_equal      = is_numeric( $product ) && ( $product == $theproduct->get_id() );
			$product_is_equal = ( $product instanceof JPID_Product ) && $product->get_id() == $theproduct->get_id();
			$post_is_equal    = ( $product instanceof WP_Post ) && $product->ID == $theproduct->get_id();

			if ( $id_is_equal || $product_is_equal || $post_is_equal ) {
				$return_product = $theproduct;
			}
		}

		if ( is_null( $return_product ) ) {
			$theproduct     = new JPID_Product( $product );
			$return_product = $theproduct;
		}
	}

	return $return_product;
}

/**
 * Get the instance of global JPID_Product object.
 *
 * @since    1.0.0
 * @return   JPID_Product    Return global product object;
 */
function jpid_get_theproduct() {
	global $theproduct, $post;

	if ( ! jpid_is_product( $theproduct ) ) {
		$theproduct = new JPID_Product( $post->ID );
	}

	return $theproduct;
}

/**
 * Check if object is type of JPID_Product
 *
 * @since     1.0.0
 * @param     object    $product    The object to be checked.
 * @return    boolean               Return true if object is type of JPID_Product. Otherwiser return false.
 */
function jpid_is_product( $product ) {
	return is_object( $product ) && ( $product instanceof JPID_Product );
}

function jpid_get_products() {
	// TODO: get all products.
}

function jpid_get_available_products() {
	// TODO: get all available products. To check product availability call 'is_available' method on product instance.
}
