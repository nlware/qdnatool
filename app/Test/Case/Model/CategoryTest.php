<?php
App::uses('Category', 'Model');

/**
 * Category Test Case
 */
class CategoryTest extends CakeTestCase {

/**
 * Auto fixtures.
 *
 * @var bool
 */
	public $autoFixtures = false;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.answer_option', 'app.category', 'app.exam', 'app.item', 'app.subject');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Category = ClassRegistry::init('Category');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Category);

		parent::tearDown();
	}

/**
 * testCreateCategories method
 *
 * @return void
 */
	public function testCreateCategories() {
		$this->loadFixtures('Category');

		$expected = array();
		$examId = 2;
		$names = array();
		$result = $this->Category->createCategories($examId, $names);
		$this->assertSame($expected, $result);

		$expected = array(3);
		$examId = 2;
		$names = array('test');
		$result = $this->Category->createCategories($examId, $names);
		$this->assertEquals($expected, $result);

		$expected = array(4, 4);
		$examId = 3;
		$names = array('test', 'test');
		$result = $this->Category->createCategories($examId, $names);
		$this->assertEquals($expected, $result);
	}

/**
 * testAnalyse method
 *
 * @return void
 */
	public function testAnalyse() {
		$this->loadFixtures('AnswerOption', 'Category', 'Exam', 'GivenAnswer', 'Item', 'Subject');

		$id = 1;
		$examId = 1;
		$result = $this->Category->analyse($id, $examId);
		$this->assertTrue($result);
	}

/**
 * testDuplicate method
 *
 * @return void
 */
	public function testDuplicate() {
		$this->loadFixtures('Category');

		$expected = array();
		$examIds = array();
		$result = $this->Category->duplicate($examIds);
		$this->assertSame($expected, $result);

		$expected = array(
			1 => 3,
			2 => 4
		);
		$examIds = array(1 => 987);
		$result = $this->Category->duplicate($examIds);
		$this->assertEquals($expected, $result);

		$examIds = array(1 => 1);
		$result = $this->Category->duplicate($examIds);
		$this->assertFalse($result);
	}

}
