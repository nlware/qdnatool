<?php
App::uses('ImagesController', 'Controller');

/**
 * ImagesController Test Case
 *
 */
class ImagesControllerTest extends ControllerTestCase {

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
	public $fixtures = array('app.image');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Images = $this->generate('Images', array(
			'components' => array(
				'Auth',
			)
		));

		$this->Images->Auth->staticExpects($this->any())
			->method('user')
			->with('id')
			->will($this->returnValue(1));

		$this->loadFixtures('Image');
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
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testGet method
 *
 * @return void
 */
	public function testGet() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testUpload method
 *
 * @return void
 */
	public function testUpload() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testBrowse method
 *
 * @return void
 */
	public function testBrowse() {
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

}
