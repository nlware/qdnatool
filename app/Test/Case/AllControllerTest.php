<?php
/**
 * AllControllerTest class
 *
 */
class AllControllerTest extends PHPUnit_Framework_TestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All controller tests');

		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		return $suite;
	}
}
