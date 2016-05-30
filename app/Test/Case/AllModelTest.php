<?php
/**
 * AllModelTest class
 *
 */
class AllModelTest extends CakeTestSuite {

/**
 * Get the suite object.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All model tests');

		$path = TESTS . 'Case' . DS . 'Model';

		$suite->addTestDirectory($path);

		return $suite;
	}

}
