<?php
App::uses('AppUtil', 'Lib');

/**
 * AppUtil Test Case
 *
 */
class AppUtilTest extends CakeTestCase {

/**
 * testPrintIndex method
 *
 * @return void
 */
	public function testPrintIndex() {
		$this->assertEquals('A', AppUtil::printIndex(0));
		$this->assertEquals('B', AppUtil::printIndex(1));
		$this->assertEquals('C', AppUtil::printIndex(2));
		$this->assertEquals('D', AppUtil::printIndex(3));
		$this->assertEquals('E', AppUtil::printIndex(4));
		$this->assertEquals('F', AppUtil::printIndex(5));
		$this->assertEquals('G', AppUtil::printIndex(6));
		$this->assertEquals('H', AppUtil::printIndex(7));
	}

/**
 * testPrintValue method
 *
 * @return void
 */
	public function testPrintValue() {
		$this->assertEquals('A', AppUtil::printValue(1));
		$this->assertEquals('B', AppUtil::printValue(2));
		$this->assertEquals('C', AppUtil::printValue(3));
		$this->assertEquals('D', AppUtil::printValue(4));
		$this->assertEquals('E', AppUtil::printValue(5));
		$this->assertEquals('F', AppUtil::printValue(6));
		$this->assertEquals('G', AppUtil::printValue(7));
		$this->assertEquals('H', AppUtil::printValue(8));
	}

}
