<?php
App::uses('QuestionsTag', 'Model');

/**
 * TestQuestionsTag
 *
 */
class TestQuestionsTag extends QuestionsTag {

/**
 * Set alias for sql.
 *
 * @var string
 */
	public $alias = 'QuestionsTag';

/**
 * Use table.
 *
 * @var mixed False or table name
 */
	public $useTable = 'QuestionsTags';

/**
 * Public test double of `parent::_allowed`.
 *
 */
	public function allowed($id) {
		return parent::_allowed($id);
	}

}

/**
 * QuestionsTag Test Case
 */
class QuestionsTagTest extends CakeTestCase {

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
		$this->QuestionsTag = ClassRegistry::init('TestQuestionsTag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->QuestionsTag);

		parent::tearDown();
	}

/**
 * testRemove method
 *
 * @return void
 */
	public function testRemove() {
		$this->markTestIncomplete('testRemove not implemented.');
	}

/**
 * testAllowed method
 *
 * @return void
 */
	public function testAllowed() {
		$this->markTestIncomplete('testAllowed not implemented.');
	}

}
