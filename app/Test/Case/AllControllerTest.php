<?php
/**
 * AllControllerTest class
 *
 * This test group will run Controller related tests.
 */
class AllControllerTest extends CakeTestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Controller tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
		return $suite;
	}
}
