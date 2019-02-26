<?php
App::uses('CakeSession', 'Model/Datasource');
App::uses('ImagesController', 'Controller');

/**
 * ImagesController Test Case
 *
 */
class ImagesControllerTest extends ControllerTestCase {

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
	public $fixtures = array('app.development_phase', 'app.question_format', 'app.role', 'app.user', 'app.question', 'app.image');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Images = $this->generate('Images');

		CakeSession::write('Auth.User.id', 1);

		$this->loadFixtures('DevelopmentPhase', 'QuestionFormat', 'Role', 'User', 'Question', 'Image');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Images);
	}

/**
 * testCapture method
 *
 * @return void
 */
	public function testCapture() {
		$this->markTestIncomplete('testCapture not implemented.');
	}

/**
 * testGet method
 *
 * @return void
 */
	public function testGet() {
		$this->markTestIncomplete('testGet not implemented.');
	}

/**
 * testUpload method
 *
 * @return void
 */
	public function testUpload() {
		$this->markTestIncomplete('testUpload not implemented.');
	}

/**
 * testBrowse method
 *
 * @return void
 */
	public function testBrowse() {
		$this->markTestIncomplete('testBrowse not implemented.');
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
		$this->markTestIncomplete('testDelete not implemented.');
	}

}
