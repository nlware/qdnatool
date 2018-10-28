<?php
/**
 * AllHelperTest class
 *
 */
class AllHelperTest extends PHPUnit_Framework_TestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Helper tests');

		$suite->addTestDirectory(TESTS . 'Case' . DS . 'View' . DS . 'Helper');
		return $suite;
	}
}
