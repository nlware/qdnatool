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
 * @var boolean
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
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testInstruction method
 *
 * @return void
 */
	public function testInstruction() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testDownload method
 *
 * @return void
 */
	public function testDownload() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testExportQmp method
 *
 * @return void
 */
	public function testExportQmp() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testExportRespondus method
 *
 * @return void
 */
	public function testExportRespondus() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

}
