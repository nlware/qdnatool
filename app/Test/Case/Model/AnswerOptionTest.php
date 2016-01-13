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
	public $fixtures = array('app.answer_option');

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
		$this->assertEquals('A', $this->AnswerOption->printIndex(0));
		$this->assertEquals('B', $this->AnswerOption->printIndex(1));
		$this->assertEquals('C', $this->AnswerOption->printIndex(2));
		$this->assertEquals('D', $this->AnswerOption->printIndex(3));
		$this->assertEquals('E', $this->AnswerOption->printIndex(4));
		$this->assertEquals('F', $this->AnswerOption->printIndex(5));
		$this->assertEquals('G', $this->AnswerOption->printIndex(6));
		$this->assertEquals('H', $this->AnswerOption->printIndex(7));
	}

/**
 * testPrintValue method
 *
 * @return void
 */
	public function testPrintValue() {
		$this->assertEquals('A', $this->AnswerOption->printValue(1));
		$this->assertEquals('B', $this->AnswerOption->printValue(2));
		$this->assertEquals('C', $this->AnswerOption->printValue(3));
		$this->assertEquals('D', $this->AnswerOption->printValue(4));
		$this->assertEquals('E', $this->AnswerOption->printValue(5));
		$this->assertEquals('F', $this->AnswerOption->printValue(6));
		$this->assertEquals('G', $this->AnswerOption->printValue(7));
		$this->assertEquals('H', $this->AnswerOption->printValue(8));
	}

/**
 * testDuplicate method
 *
 * @return void
 */
	public function testDuplicate() {
		$itemIds = array(21773 => 100000);

		$conditions = array('AnswerOption.item_id' => array_keys($itemIds));
		$srcRecords = $this->AnswerOption->find('all', compact('conditions'));

		$result = $this->AnswerOption->duplicate($itemIds);

		$conditions = array('AnswerOption.item_id' => array_keys($itemIds));
		$srcRecordsAfter = $this->AnswerOption->find('all', compact('conditions'));

		$this->assertEquals($srcRecords, $srcRecordsAfter);

		$srcRecords = Hash::remove($srcRecords, '{n}.AnswerOption.id');
		$srcRecords = Hash::insert($srcRecords, '{n}.AnswerOption.item_id', 100000);
		$conditions = array('AnswerOption.item_id' => array_values($itemIds));
		$dstRecords = $this->AnswerOption->find('all', compact('conditions'));
		$dstRecords = Hash::remove($dstRecords, '{n}.AnswerOption.id');

		$this->assertEquals($srcRecords, $dstRecords);
	}

}
