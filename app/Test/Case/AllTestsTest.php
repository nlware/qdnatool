<?php
/**
 * AllTests file
 *
 * This test group will run all application tests.
 *
 * @copyright     Copyright (c) NLWare B.V. (http://www.nlware.com)
 * @link          http://docs.qdnatool.org qDNAtool(tm) Project
 * @package       app.Test.Case
 * @license       http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */

class AllTestsTest extends CakeTestSuite {

/**
 * Get the suite object.
 *
 * @return CakeTestSuite Suite class instance.
 */
	public static function suite() {
		$suite = new CakeTestSuite('All application tests');
		$suite->addTestDirectoryRecursive(TESTS . 'Case');
		return $suite;
	}

}
