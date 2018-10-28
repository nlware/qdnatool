<?php
App::uses('CakeSession', 'Model/Datasource');
App::uses('QuestionFormatsController', 'Controller');

/**
 * QuestionFormatsController Test Case
 *
 */
class QuestionFormatsControllerTest extends ControllerTestCase {

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

		CakeSession::write('Auth.User.id', 1);

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
		$this->markTestIncomplete('testAdminIndex not implemented.');
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->markTestIncomplete('testAdminView not implemented.');
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->markTestIncomplete('testAdminEdit not implemented.');
	}

}
