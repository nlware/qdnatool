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
