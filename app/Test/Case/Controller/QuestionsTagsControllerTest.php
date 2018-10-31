<?php
App::uses('CakeSession', 'Model/Datasource');
App::uses('QuestionsTagsController', 'Controller');

/**
 * QuestionsTagsController Test Case
 *
 */
class QuestionsTagsControllerTest extends ControllerTestCase {

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
	public $fixtures = array('app.questions_tag');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->QuestionsTags = $this->generate('QuestionsTags');

		CakeSession::write('Auth.User.id', 1);

		$this->loadFixtures('QuestionsTag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		unset($this->QuestionsTags);
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
 * testMoveDown method
 *
 * @return void
 */
	public function testMoveDown() {
		$this->markTestIncomplete('testMoveDown not implemented.');
	}

/**
 * testMoveUp method
 *
 * @return void
 */
	public function testMoveUp() {
		$this->markTestIncomplete('testMoveUp not implemented.');
	}

}
