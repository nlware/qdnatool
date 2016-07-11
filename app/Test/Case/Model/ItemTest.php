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
	public $fixtures = array();

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
 * testDuplicate method
 *
 * @return void
 */
	public function testDuplicate() {
		$this->markTestIncomplete('testDuplicate not implemented.');
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
