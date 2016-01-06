<?php
App::uses('Item', 'Model');

/**
 * Item Test Case
 */
class ItemTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.item',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Item = ClassRegistry::init('Item');
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

		$expected = array(1000000, 1000001);
		$examId = 1;
		$domainId = 1;
		$result = $this->Item->getIds($examId, $domainId);
		$this->assertEquals($expected, $result);

		$expected = array(1000002);
		$examId = 1;
		$domainId = 2;
		$result = $this->Item->getIds($examId, $domainId);
		$this->assertEquals($expected, $result);
	}

}
