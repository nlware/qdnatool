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
 * testOptionIndex method
 *
 * @return void
 */
	public function testOptionIndex() {
		$this->assertEquals('A', $this->Output->optionIndex(0));
		$this->assertEquals('B', $this->Output->optionIndex(1));
		$this->assertEquals('C', $this->Output->optionIndex(2));
		$this->assertEquals('D', $this->Output->optionIndex(3));
		$this->assertEquals('E', $this->Output->optionIndex(4));
		$this->assertEquals('F', $this->Output->optionIndex(5));
		$this->assertEquals('G', $this->Output->optionIndex(6));
		$this->assertEquals('H', $this->Output->optionIndex(7));
	}

/**
 * testOptionValue method
 *
 * @return void
 */
	public function testOptionValue() {
		$this->assertEquals('A', $this->Output->optionValue(1));
		$this->assertEquals('B', $this->Output->optionValue(2));
		$this->assertEquals('C', $this->Output->optionValue(3));
		$this->assertEquals('D', $this->Output->optionValue(4));
		$this->assertEquals('E', $this->Output->optionValue(5));
		$this->assertEquals('F', $this->Output->optionValue(6));
		$this->assertEquals('G', $this->Output->optionValue(7));
		$this->assertEquals('H', $this->Output->optionValue(8));
	}

}
