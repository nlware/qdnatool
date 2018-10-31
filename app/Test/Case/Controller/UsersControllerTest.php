<?php
App::uses('CakeSession', 'Model/Datasource');
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

		$this->Users = $this->generate('Users');

		CakeSession::write('Auth.User.id', 1);

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
		$this->markTestIncomplete('testView not implemented.');
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
		$this->markTestIncomplete('testAdminAdd not implemented.');
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->markTestIncomplete('testAdminEdit not implemented.');
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->markTestIncomplete('testAdminDelete not implemented.');
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
 * testAccount method
 *
 * @return void
 */
	public function testAccount() {
		$this->markTestIncomplete('testAccount not implemented.');
	}

/**
 * testHome method
 *
 * @return void
 */
	public function testHome() {
		$this->markTestIncomplete('testHome not implemented.');
	}

/**
 * testClassicLogin method
 *
 * @return void
 */
	public function testClassicLogin() {
		$this->markTestIncomplete('testClassicLogin not implemented.');
	}

/**
 * testLogin method
 *
 * @return void
 */
	public function testLogin() {
		$this->markTestIncomplete('testLogin not implemented.');
	}

/**
 * testSamlLogin method
 *
 * @return void
 */
	public function testSamlLogin() {
		$this->markTestIncomplete('testSamlLogin not implemented.');
	}

/**
 * testLogout method
 *
 * @return void
 */
	public function testLogout() {
		$this->markTestIncomplete('testLogout not implemented.');
	}

/**
 * testChangePassword method
 *
 * @return void
 */
	public function testChangePassword() {
		$this->markTestIncomplete('testChangePassword not implemented.');
	}

}
