<?php
App::uses('QuestionFormatsController', 'Controller');

/**
 * QuestionFormatsController Test Case
 *
 */
class QuestionFormatsControllerTest extends ControllerTestCase {

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
	public $fixtures = array('app.question_format');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->QuestionFormats = $this->generate('QuestionFormats', array(
			'components' => array(
				'Auth',
			)
		));

		$this->QuestionFormats->Auth->staticExpects($this->any())
			->method('user')
			->with('id')
			->will($this->returnValue(1));

		$this->loadFixtures('QuestionFormat');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		unset($this->QuestionFormats);
	}

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

}
