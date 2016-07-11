<?php
App::uses('TagsController', 'Controller');

/**
 * TagsController Test Case
 *
 */
class TagsControllerTest extends ControllerTestCase {

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
	public $fixtures = array('app.tag');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Tags = $this->generate('Tags', array(
			'components' => array(
				'Auth',
			)
		));

		$this->Tags->Auth->staticExpects($this->any())
			->method('user')
			->with('id')
			->will($this->returnValue(1));

		$this->loadFixtures('Tag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Tags);
	}

/**
 * testAutocomplete method
 *
 * @return void
 */
	public function testAutocomplete() {
		$this->markTestIncomplete('testAutocomplete not implemented.');
	}

}
