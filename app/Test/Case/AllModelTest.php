<?php
/**
 * AllModelTest class
 *
 * This test group will run Model related tests.
 */
class AllModelTest extends CakeTestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Model tests');
		$suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
		return $suite;
	}

}
