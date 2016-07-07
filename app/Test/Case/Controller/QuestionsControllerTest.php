<?php
App::uses('QuestionsController', 'Controller');

/**
 * QuestionsController Test Case
 *
 */
class QuestionsControllerTest extends ControllerTestCase {

/**
 * autoFixtures property
 *
 * @var bool
 */
	public $autoFixtures = false;

/**
 * fixtures property
 *
 * @var array
 */
	public $fixtures = array('app.question');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Questions = $this->generate('Questions', array(
			'components' => array(
				'Auth',
			)
		));

		$this->Questions->Auth->staticExpects($this->any())
			->method('user')
			->with('id')
			->will($this->returnValue(1));

		$this->loadFixtures('Question');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Questions);
	}

/**
 * testAnalyse method
 *
 * @return void
 */
	public function testAnalyse() {
		$this->markTestIncomplete('testAnalyse not implemented.');
	}

/**
 * testInstruction method
 *
 * @return void
 */
	public function testInstruction() {
		$this->markTestIncomplete('testInstruction not implemented.');
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$this->markTestIncomplete('testView not implemented.');
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
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
		$this->markTestIncomplete('testEdit not implemented.');
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
		$this->markTestIncomplete('testDelete not implemented.');
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->markTestIncomplete('testIndex not implemented.');
	}

/**
 * testDownload method
 *
 * @return void
 */
	public function testDownload() {
		$this->markTestIncomplete('testDownload not implemented.');
	}

/**
 * testExportQmp method
 *
 * @return void
 */
	public function testExportQmp() {
		$this->markTestIncomplete('testExportQmp not implemented.');
	}

/**
 * testExportRespondus method
 *
 * @return void
 */
	public function testExportRespondus() {
		$this->markTestIncomplete('testExportRespondus not implemented.');
	}

}
