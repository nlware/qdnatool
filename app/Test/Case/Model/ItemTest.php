<?php
App::uses('Item', 'Model');

/**
 * TestItem
 *
 */
class TestItem extends Item {

/**
 * Set alias for sql.
 *
 * @var string
 */
	public $alias = 'Item';

/**
 * Use table.
 *
 * @var mixed False or table name
 */
	public $useTable = 'Items';

/**
 * Public test double of `parent::_getMostGivenIncorrectAnswerOption`.
 *
 */
	public function getMostGivenIncorrectAnswerOption($item) {
		return parent::_getMostGivenIncorrectAnswerOption($item);
	}

}

/**
 * Item Test Case
 */
class ItemTest extends CakeTestCase {

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
	public $fixtures = array('app.item');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Item = ClassRegistry::init('TestItem');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Item);

		parent::tearDown();
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
		$this->markTestIncomplete('testAdd not implemented.');
	}

/**
 * testStevie method
 *
 * @return void
 */
	public function testStevie() {
		$this->markTestIncomplete('testStevie not implemented.');
	}

/**
 * testGetIds method
 *
 * @return void
 */
	public function testGetIds() {
		$this->loadFixtures('Item');

		$expected = array();
		$examId = 0;
		$result = $this->Item->getIds($examId);
		$this->assertEquals($expected, $result);

		$expected = array(1);
		$examId = 2;
		$result = $this->Item->getIds($examId);
		$this->assertEquals($expected, $result);

		$expected = array(21773);
		$examId = 747;
		$result = $this->Item->getIds($examId);
		$this->assertEquals($expected, $result);

		$expected = array(1000000, 1000001, 1000002, 1000003);
		$examId = 1;
		$result = $this->Item->getIds($examId);
		$this->assertEquals($expected, $result);

		$expected = array(1000000, 1000001, 1000003);
		$examId = 1;
		$categoryId = 1;
		$result = $this->Item->getIds($examId, $categoryId);
		$this->assertEquals($expected, $result);

		$expected = array(1000002);
		$examId = 1;
		$categoryId = 2;
		$result = $this->Item->getIds($examId, $categoryId);
		$this->assertEquals($expected, $result);
	}

/**
 * testDuplicate method
 *
 * @return void
 */
	public function testDuplicate() {
		$this->loadFixtures('Item');

		$expected = array();
		$examIds = array();
		$categoryIds = array();
		$result = $this->Item->duplicate($examIds, $categoryIds);
		$this->assertSame($expected, $result);

		$expected = array(1 => 1000004);
		$examIds = array(2 => 987);
		$categoryIds = array();
		$result = $this->Item->duplicate($examIds, $categoryIds);
		$this->assertEquals($expected, $result);

		$expected = array(
			1000000 => 1000005,
			1000001 => 1000006,
			1000002 => 1000007,
			1000003 => 1000008
		);
		$examIds = array(1 => 1001);
		$categoryIds = array(1 => 1001, 2 => 1002, 3 => 1003);
		$result = $this->Item->duplicate($examIds, $categoryIds);
		$this->assertEquals($expected, $result);

		$expected = array(1000001 => 1000009);
		$examIds = array(1 => 1001);
		$categoryIds = array(1 => 1001, 2 => 1002, 3 => 1003);
		$filteredIds = array(1000001);
		$result = $this->Item->duplicate($examIds, $categoryIds, $filteredIds);
		$this->assertEquals($expected, $result);
	}

/**
 * testGetMostGivenIncorrectAnswerOption method
 *
 * @return void
 */
	public function testGetMostGivenIncorrectAnswerOption() {
		$this->markTestIncomplete('testGetMostGivenIncorrectAnswerOption not implemented.');
	}

}
