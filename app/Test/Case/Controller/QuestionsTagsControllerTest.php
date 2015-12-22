<?php
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

		$this->QuestionsTags = $this->generate('QuestionsTags', array(
			'components' => array(
				'Auth',
			)
		));

		$this->QuestionsTags->Auth->staticExpects($this->any())
			->method('user')
			->with('id')
			->will($this->returnValue(1));

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
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testMoveDown method
 *
 * @return void
 */
	public function testMoveDown() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testMoveUp method
 *
 * @return void
 */
	public function testMoveUp() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

}
