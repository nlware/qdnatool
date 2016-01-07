<?php
App::uses('Domain', 'Model');

/**
 * Domain Test Case
 */
class DomainTest extends CakeTestCase {

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
	public $fixtures = array('app.answer_option', 'app.domain', 'app.exam', 'app.item', 'app.subject');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Domain = ClassRegistry::init('Domain');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Domain);

		parent::tearDown();
	}

/**
 * testCreateDomains method
 *
 * @return void
 */
	public function testCreateDomains() {
		$this->loadFixtures('Domain');

		$expected = array();
		$examId = 2;
		$names = array();
		$result = $this->Domain->createDomains($examId, $names);
		$this->assertSame($expected, $result);

		$expected = array(3);
		$examId = 2;
		$names = array('test');
		$result = $this->Domain->createDomains($examId, $names);
		$this->assertEquals($expected, $result);

		$expected = array(4, 4);
		$examId = 3;
		$names = array('test', 'test');
		$result = $this->Domain->createDomains($examId, $names);
		$this->assertEquals($expected, $result);
	}

/**
 * testAnalyse method
 *
 * @return void
 */
	public function testAnalyse() {
		$this->loadFixtures('AnswerOption', 'Domain', 'Exam', 'Item', 'Subject');

		$id = 1;
		$examId = 1;
		$result = $this->Domain->analyse($id, $examId);
		$this->assertTrue($result);
	}

/**
 * testDuplicate method
 *
 * @return void
 */
	public function testDuplicate() {
		$this->loadFixtures('Domain');

		$expected = array();
		$examIds = array();
		$result = $this->Domain->duplicate($examIds);
		$this->assertSame($expected, $result);

		$expected = array(
			1 => 3,
			2 => 4
		);
		$examIds = array(1 => 987);
		$result = $this->Domain->duplicate($examIds);
		$this->assertEquals($expected, $result);

		$examIds = array(1 => 1);
		$result = $this->Domain->duplicate($examIds);
		$this->assertFalse($result);
	}

}
