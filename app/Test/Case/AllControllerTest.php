<?php
/**
 * AllControllerTest class
 *
 */
class AllControllerTest extends CakeTestSuite {

/**
 * Get the suite object.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All controller tests');

		$path = TESTS . 'Case' . DS . 'Controller';

		$suite->addTestDirectory($path);

		return $suite;
	}

}
