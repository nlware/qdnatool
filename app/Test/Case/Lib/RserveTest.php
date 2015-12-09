<?php
App::uses('Rserve', 'Lib');
class RserveTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Rserve = new Rserve();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Rserve);
	}

	public function testGetConfig() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	public function testExecute() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	public function testConnect() {
		$result = $this->Rserve->connect();
		$this->assertTrue((bool)$result);
	}

}