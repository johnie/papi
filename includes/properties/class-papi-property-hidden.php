<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Papi Property Hidden.
 *
 * @package Papi
 * @since 1.0.0
 */

class Papi_Property_Hidden extends Papi_Property_String {

	/**
	 * The input type to use.
	 *
	 * @var string
	 * @since 1.0.0
	 */

	public $input_type = 'hidden';

}
