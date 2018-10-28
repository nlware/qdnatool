<?php
/**
 * AllTests class
 *
 */
class AllTests extends PHPUnit_Framework_TestSuite {

/**
 * Get the suite object.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All tests');

		$path = TESTS . 'Case' . DS;

		$suite->addTestFile($path . 'AllControllerTest.php');
		$suite->addTestFile($path . 'AllHelperTest.php');
		$suite->addTestFile($path . 'AllLibTest.php');
		$suite->addTestFile($path . 'AllModelTest.php');
		return $suite;
	}
}
