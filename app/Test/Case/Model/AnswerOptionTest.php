<?php
App::uses('AnswerOption', 'Model');

/**
 * AnswerOption Test Case
 *
 */
class AnswerOptionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AnswerOption = ClassRegistry::init('AnswerOption');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AnswerOption);

		parent::tearDown();
	}

/**
 * testPrintIndex method
 *
 * @return void
 */
	public function testPrintIndex() {
		$expected = 'A';
		$value = 0;
		$result = $this->AnswerOption->printIndex($value);
		$this->assertEquals($expected, $result);

		$expected = 'B';
		$value = 1;
		$result = $this->AnswerOption->printIndex($value);
		$this->assertEquals($expected, $result);
	}

/**
 * testPrintValue method
 *
 * @return void
 */
	public function testPrintValue() {
		$expected = 'A';
		$value = 1;
		$result = $this->AnswerOption->printValue($value);
		$this->assertEquals($expected, $result);

		$expected = 'B';
		$value = 2;
		$result = $this->AnswerOption->printValue($value);
		$this->assertEquals($expected, $result);
	}

}
