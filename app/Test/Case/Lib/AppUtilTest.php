<?php
App::uses('AppUtil', 'Lib');

/**
 * AppUtil Test Case
 *
 */
class AppUtilTest extends CakeTestCase {

/**
 * testOptionIndex method
 *
 * @return void
 */
	public function testOptionIndex() {
		$this->assertEquals('A', AppUtil::optionIndex(0));
		$this->assertEquals('B', AppUtil::optionIndex(1));
		$this->assertEquals('C', AppUtil::optionIndex(2));
		$this->assertEquals('D', AppUtil::optionIndex(3));
		$this->assertEquals('E', AppUtil::optionIndex(4));
		$this->assertEquals('F', AppUtil::optionIndex(5));
		$this->assertEquals('G', AppUtil::optionIndex(6));
		$this->assertEquals('H', AppUtil::optionIndex(7));
	}

/**
 * testOptionValue method
 *
 * @return void
 */
	public function testOptionValue() {
		$this->assertEquals('A', AppUtil::optionValue(1));
		$this->assertEquals('B', AppUtil::optionValue(2));
		$this->assertEquals('C', AppUtil::optionValue(3));
		$this->assertEquals('D', AppUtil::optionValue(4));
		$this->assertEquals('E', AppUtil::optionValue(5));
		$this->assertEquals('F', AppUtil::optionValue(6));
		$this->assertEquals('G', AppUtil::optionValue(7));
		$this->assertEquals('H', AppUtil::optionValue(8));
	}

}
