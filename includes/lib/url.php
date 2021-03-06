<?php

/**
 * Papi url functions.
 *
 * @package Papi
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the url to 'post-new.php' with query string of the page type to load.
 *
 * @param string $page_type
 * @param bool $append_admin_url Default true
 *
 * @since 1.0.0
 *
 * @return string
 */

function papi_get_page_new_url( $page_type, $append_admin_url = true, $post_type = null, $exclude = array() ) {
	$admin_url = $append_admin_url ? get_admin_url() : '';

	$admin_url = $admin_url . 'post-new.php?page_type=' . $page_type . papi_get_page_query_strings( '&', $exclude );

	if ( ! is_null( $post_type ) && in_array( 'post_type', $exclude ) ) {
		$admin_url .= '&post_type=' . $post_type;
	}

	return papi_append_post_type_query( $admin_url, $post_type );
}

/**
 * Get page query strings.
 *
 * @param string $first_char
 * @param array $exclude
 *
 * @since 1.0.0
 *
 * @return string
 */

function papi_get_page_query_strings( $first_char = '&', $exclude = array() ) {
	$request_uri = $_SERVER['REQUEST_URI'];
	$parsed_url  = parse_url( $request_uri );

	if ( !isset( $parsed_url['query'] ) || empty ( $parsed_url['query'] ) ) {
		return '';
	}

	$query = $parsed_url['query'];
	$query = preg_replace( '/page\=[a-z-,]+/', '', $query );
	$query = str_replace( '?', '', $query );
	$query = explode( '&', $query );

	$query = array_filter( $query, function ( $q ) use ( $exclude ) {
		$q = explode( '=', $q );

		if ( empty( $q ) ) {
			return false;
		}

		$q = $q[0];

		$allowed = array( 'post_type', 'page_type', 'post_new', 'post_parent', 'papi_bypass', 'npparent' );
		$allowed = array_diff( $allowed, $exclude );

		return in_array( $q, $allowed );
	} );

	$query = implode( '&', $query );

	if ( substr( $query, 0, 1 ) === '&' || substr( $query, 0, 1 ) === '?' ) {
		$query[0] = $first_char;
	} else {
		$query = $first_char . $query;
	}

	// Remove last char if it's a & or ?
	if ( substr( $query, - 1, 1 ) === '&' || substr( $query, - 1, 1 ) === '?' ) {
		$query = substr( $query, 0, - 1 );
	}

	if ( in_array( 'post_type', $exclude ) ) {
		return $query;
	}

	return papi_append_post_type_query( $query );
}

/**
 * Append post type query string.
 *
 * @param string $url
 * @param string $post_type_arg
 *
 * @since 1.0.0
 *
 * @return string
 */

function papi_append_post_type_query( $url, $post_type_arg = null ) {
	if ( strpos( $url, 'post_type=' ) !== false ) {
		return $url;
	}

	$post_id = papi_get_post_id();

	if ( ! is_null( $post_id ) ) {
		$post_type = get_post_type( $post_id );
	} else {
		$post_type = papi_get_or_post( 'post_type' );
	}

	if ( ! empty( $post_type_arg ) && empty( $post_type ) ) {
		$post_type = $post_type_arg;
	}

	if ( empty ( $post_type ) ) {
		$post_type = papi_get_wp_post_type();
	}

	if ( ! empty( $post_type ) ) {
		if ( substr( $url, - 1, 1 ) !== '&' ) {
			$url .= '&';
		}

		$url .= 'post_type=' . $post_type;
	}

	return $url;
}
