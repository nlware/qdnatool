<?php
/**
 * AllModelTest class
 *
 */
class AllModelTest extends PHPUnit_Framework_TestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All model tests');

		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');

		return $suite;
	}
}
