<?php
App::uses('Question', 'Model');

/**
 * TestQuestion
 *
 */
class TestQuestion extends Question {

/**
 * Set alias for sql.
 *
 * @var string
 */
	public $alias = 'Question';

/**
 * Use table.
 *
 * @var mixed False or table name
 */
	public $useTable = 'Questions';

/**
 * Public test double of `parent::_getTagIds`.
 *
 */
	public function getTagIds($id) {
		return parent::_getTagIds($id);
	}

/**
 * Public test double of `parent::_itemResprocessingToRespondus`.
 *
 */
	public function itemResprocessingToRespondus($question, $dom) {
		return parent::_itemResprocessingToRespondus($question, $dom);
	}

/**
 * Public test double of `parent::_itemFeedbackToRespondus`.
 *
 */
	public function itemFeedbackToRespondus($question, $dom) {
		return parent::_itemFeedbackToRespondus($question, $dom);
	}

/**
 * Public test double of `parent::_responseToQMP`.
 *
 */
	public function responseToQMP($question, $dom) {
		return parent::_responseToQMP($question, $dom);
	}

/**
 * Public test double of `parent::_responseToRespondus`.
 *
 */
	public function responseToRespondus($question, $dom) {
		return parent::_responseToRespondus($question, $dom);
	}

/**
 * Public test double of `parent::_materialToRespondus`.
 *
 */
	public function materialToRespondus($stimulus, $dom) {
		return parent::_materialToRespondus($stimulus, $dom);
	}

/**
 * Public test double of `parent::_wordCount`.
 *
 */
	public function wordCount($value) {
		return parent::_wordCount($value);
	}

/**
 * Public test double of `parent::_contains`.
 *
 */
	public function contains($check, $keywords) {
		return parent::_contains($check, $keywords);
	}

/**
 * Public test double of `parent::_average`.
 *
 */
	public function average($values) {
		return parent::_average($values);
	}

}

/**
 * Question Test Case
 */
class QuestionTest extends CakeTestCase {

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
		$this->Question = ClassRegistry::init('TestQuestion');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Question);

		parent::tearDown();
	}

/**
 * Tests `Question::notContains`.
 *
 * @return void
 */
	public function testNotContains() {
		$this->markTestIncomplete('testNotContains not implemented.');
	}

/**
 * Tests `Question::getFindOptionsForTagIds`.
 *
 * @return void
 */
	public function testGetFindOptionsForTagIds() {
		$this->markTestIncomplete('testGetFindOptionsForTagIds not implemented.');
	}

/**
 * Tests `Question::analyse`.
 *
 * @return void
 */
	public function testAnalyse() {
		$this->markTestIncomplete('testAnalyse not implemented.');
	}

/**
 * Tests `Question::view`.
 *
 * @return void
 */
	public function testView() {
		$this->markTestIncomplete('testView not implemented.');
	}

/**
 * Tests `Question::add`.
 *
 * @return void
 */
	public function testAdd() {
		$this->markTestIncomplete('testAdd not implemented.');
	}

/**
 * Tests `Question::edit`.
 *
 * @return void
 */
	public function testEdit() {
		$this->markTestIncomplete('testEdit not implemented.');
	}

/**
 * Tests `Question::update`.
 *
 * @return void
 */
	public function testUpdate() {
		$this->markTestIncomplete('testUpdate not implemented.');
	}

/**
 * Tests `Question::getList`.
 *
 * @return void
 */
	public function testGetList() {
		$this->markTestIncomplete('testGetList not implemented.');
	}

/**
 * Tests `Question::getMineIds`.
 *
 * @return void
 */
	public function testGetMineIds() {
		$this->markTestIncomplete('testGetMineIds not implemented.');
	}

/**
 * Tests `Question::getStartSentences`.
 *
 * @return void
 */
	public function testGetStartSentences() {
		$this->markTestIncomplete('testGetStartSentences not implemented.');
	}

/**
 * Tests `Question::toQMP`.
 *
 * @return void
 */
	public function testToQMP() {
		$this->markTestIncomplete('testToQMP not implemented.');
	}

/**
 * Tests `Question::toRespondus`.
 *
 * @return void
 */
	public function testToRespondus() {
		$this->markTestIncomplete('testToRespondus not implemented.');
	}

/**
 * Tests `Question::_getTagIds`.
 *
 * @return void
 */
	public function testGetTagIds() {
		$this->markTestIncomplete('testGetTagIds not implemented.');
	}

/**
 * Tests `Question::_itemResprocessingToRespondus`.
 *
 * @return void
 */
	public function testItemResprocessingToRespondus() {
		$this->markTestIncomplete('testItemResprocessingToRespondus not implemented.');
	}

/**
 * Tests `Question::_itemFeedbackToRespondus`.
 *
 * @return void
 */
	public function testItemFeedbackToRespondus() {
		$this->markTestIncomplete('testItemFeedbackToRespondus not implemented.');
	}

/**
 * Tests `Question::_responseToQMP`.
 *
 * @return void
 */
	public function testResponseToQMP() {
		$this->markTestIncomplete('testResponseToQMP not implemented.');
	}

/**
 * Tests `Question::_responseToRespondus`.
 *
 * @return void
 */
	public function testResponseToRespondus() {
		$this->markTestIncomplete('testResponseToRespondus not implemented.');
	}

/**
 * Tests `Question::_materialToRespondus`.
 *
 * @return void
 */
	public function testMaterialToRespondus() {
		$this->markTestIncomplete('testMaterialToRespondus not implemented.');
	}

/**
 * Tests `Question::_wordCount`.
 *
 * @return void
 */
	public function testWordCount() {
		$expected = 5;
		$value = 'Lorem ipsum dolor sit amet';
		$result = $this->Question->wordCount($value);
		$this->assertEquals($expected, $result);
	}

/**
 * Tests `Question::_contains`.
 *
 * @return void
 */
	public function testContains() {
		$check = 'Lorem ipsum dolor sit amet';
		$keywords = array('ipsum');
		$result = $this->Question->contains($check, $keywords);
		$this->assertTrue($result);

		$check = 'Lorem ipsum dolor sit amet';
		$keywords = array('not');
		$result = $this->Question->contains($check, $keywords);
		$this->assertFalse($result);
	}

/**
 * Tests `Question::_average`.
 *
 * @return void
 */
	public function testAverage() {
		$expected = 5;
		$values = array(1, 4, 10);
		$result = $this->Question->average($values);
		$this->assertEquals($expected, $result);
	}

}
