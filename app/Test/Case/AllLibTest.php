<?php
/**
 * AllLibTest class
 *
 */
class AllLibTest extends PHPUnit_Framework_TestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All model tests');

		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Lib');
		return $suite;
	}
}
