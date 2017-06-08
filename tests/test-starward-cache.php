<?php
/**
 * starward-cache-manager Starward Cache Tests.
 *
 * @since   0.0.0
 * @package Starward_cache-manager
 */
class SCM_Starward_Cache_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  0.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'SCM_Starward_Cache') );
	}

	/**
	 * Test that we can access our class through our helper function.
	 *
	 * @since  0.0.0
	 */
	function test_class_access() {
		$this->assertInstanceOf( 'SCM_Starward_Cache', starward_cache_manager()->starward-cache );
	}

	/**
	 * Replace this with some actual testing code.
	 *
	 * @since  0.0.0
	 */
	function test_sample() {
		$this->assertTrue( true );
	}
}
