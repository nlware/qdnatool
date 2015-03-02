<?php
/**
 * AllTest file
 *
 * This test group will run all tests.
 */

class AllTest extends CakeTestSuite {

/**
 * Get the suite object.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All tests');
		$suite->addTestDirectoryRecursive(TESTS . 'Case');
		return $suite;
	}

}
