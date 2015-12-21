<?php
App::uses('Rserve', 'Lib');

/**
 * Rserve Test Case
 *
 */
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

/**
 * testGetConfig method
 *
 * @return void
 */
	public function testGetConfig() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testExecute method
 *
 * @return void
 */
	public function testExecute() {
		$expected = 'Hello world!';
		$script = 'x ="Hello world!"; x';
		$result = $this->Rserve->execute($script);
		$this->assertEquals($expected, $result);
	}

/**
 * testConnect method
 *
 * @return void
 */
	public function testConnect() {
		$result = $this->Rserve->connect();
		$this->assertTrue((bool)$result);
	}

}
