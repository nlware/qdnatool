<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('OutputHelper', 'View/Helper');

/**
 * OutputHelper Test Case
 */
class OutputHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Output = new OutputHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Output);

		parent::tearDown();
	}

/**
 * testDate method
 *
 * @return void
 */
	public function testDate() {
		$this->markTestIncomplete('testDate not implemented.');
	}

/**
 * testDecimal method
 *
 * @return void
 */
	public function testDecimal() {
		$this->markTestIncomplete('testDecimal not implemented.');
	}

/**
 * testBoolean method
 *
 * @return void
 */
	public function testBoolean() {
		$this->markTestIncomplete('testBoolean not implemented.');
	}

/**
 * testHtml method
 *
 * @return void
 */
	public function testHtml() {
		$this->markTestIncomplete('testHtml not implemented.');
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->assertEquals('A', $this->Output->index(0));
		$this->assertEquals('B', $this->Output->index(1));
		$this->assertEquals('C', $this->Output->index(2));
		$this->assertEquals('D', $this->Output->index(3));
		$this->assertEquals('E', $this->Output->index(4));
		$this->assertEquals('F', $this->Output->index(5));
		$this->assertEquals('G', $this->Output->index(6));
		$this->assertEquals('H', $this->Output->index(7));
	}

/**
 * testValue method
 *
 * @return void
 */
	public function testValue() {
		$this->assertEquals('A', $this->Output->value(1));
		$this->assertEquals('B', $this->Output->value(2));
		$this->assertEquals('C', $this->Output->value(3));
		$this->assertEquals('D', $this->Output->value(4));
		$this->assertEquals('E', $this->Output->value(5));
		$this->assertEquals('F', $this->Output->value(6));
		$this->assertEquals('G', $this->Output->value(7));
		$this->assertEquals('H', $this->Output->value(8));
	}

}
