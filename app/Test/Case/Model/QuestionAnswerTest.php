<?php
App::uses('QuestionAnswer', 'Model');

/**
 * QuestionAnswer Test Case
 *
 */
class QuestionAnswerTest extends CakeTestCase {

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
		$this->QuestionAnswer = ClassRegistry::init('QuestionAnswer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->QuestionAnswer);

		parent::tearDown();
	}

/**
 * testPrintIndex method
 *
 * @return void
 */
	public function testPrintIndex() {
		$this->assertEquals('A', $this->QuestionAnswer->printIndex(0));
		$this->assertEquals('B', $this->QuestionAnswer->printIndex(1));
		$this->assertEquals('C', $this->QuestionAnswer->printIndex(2));
		$this->assertEquals('D', $this->QuestionAnswer->printIndex(3));
		$this->assertEquals('E', $this->QuestionAnswer->printIndex(4));
		$this->assertEquals('F', $this->QuestionAnswer->printIndex(5));
		$this->assertEquals('G', $this->QuestionAnswer->printIndex(6));
		$this->assertEquals('H', $this->QuestionAnswer->printIndex(7));
	}

}
