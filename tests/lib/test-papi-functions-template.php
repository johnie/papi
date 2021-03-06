<?php

/**
 * Unit tests covering template functionality.
 *
 * @package Papi
 */

class WP_Papi_Functions_Template extends WP_UnitTestCase {

	/**
	 * Setup the test.
	 *
	 * @since 1.0.0
	 */

	public function setUp() {
		parent::setUp();

		$this->post_id = $this->factory->post->create();
	}

	/**
	 * Test papi_property template.
	 *
	 * @since 1.0.0
	 */

	public function test_papi_property_template() {
		$actual = papi_property( dirname( __FILE__ ) . '/../data/properties/simple.php' );

		$this->assertEquals( 'Name', $actual->title );
		$this->assertEquals( 'string', $actual->type );
	}

	/**
	 * Test papi_template.
	 *
	 * @since 1.0.0
	 */

	public function test_papi_template() {
		$actual = papi_template( dirname( __FILE__ ) . '/../data/properties/simple.php' );

		$this->assertEquals( 'Name', $actual['title'] );
		$this->assertEquals( 'string', $actual['type'] );

		$this->assertEmpty( papi_template( null ) );
		$this->assertEmpty( papi_template( true ) );
		$this->assertEmpty( papi_template( false ) );
		$this->assertEmpty( papi_template( 1 ) );
		$this->assertEmpty( papi_template( array() ) );
		$this->assertEmpty( papi_template( new stdClass() ) );
	}

}
