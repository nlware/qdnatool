<?php
App::uses('UsersController', 'Controller');

/**
 * UsersController Test Case
 *
 */
class UsersControllerTest extends ControllerTestCase {

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
	public $fixtures = array('app.user');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Users = $this->generate('Users', array(
			'components' => array(
				'Auth',
			)
		));

		$this->Users->Auth->staticExpects($this->any())
			->method('user')
			->with('id')
			->will($this->returnValue(1));

		$this->loadFixtures('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Users);
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
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
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

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
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
 * testAccount method
 *
 * @return void
 */
	public function testAccount() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testHome method
 *
 * @return void
 */
	public function testHome() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testClassicLogin method
 *
 * @return void
 */
	public function testClassicLogin() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testLogin method
 *
 * @return void
 */
	public function testLogin() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testSamlLogin method
 *
 * @return void
 */
	public function testSamlLogin() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testLogout method
 *
 * @return void
 */
	public function testLogout() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testChangePassword method
 *
 * @return void
 */
	public function testChangePassword() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

}
