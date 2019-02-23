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
		$expected = '-';
		$value = '';
		$result = $this->Output->date($value);
		$this->assertEquals($expected, $result);

		$expected = '-';
		$value = null;
		$result = $this->Output->date($value);
		$this->assertEquals($expected, $result);

		$expected = '31-01-2016';
		$value = '2016-01-31';
		$result = $this->Output->date($value);
		$this->assertEquals($expected, $result);
	}

/**
 * testDecimal method
 *
 * @return void
 */
	public function testDecimal() {
		$expected = '123.456,8';
		$value = '123456.789';
		$result = $this->Output->decimal($value);
		$this->assertEquals($expected, $result);

		$expected = '123.456,789';
		$value = '123456.789';
		$places = 3;
		$result = $this->Output->decimal($value, $places);
		$this->assertEquals($expected, $result);
	}

/**
 * testBoolean method
 *
 * @return void
 */
	public function testBoolean() {
		$expected = 'Ja';
		$value = true;
		$result = $this->Output->boolean($value);
		$this->assertEquals($expected, $result);

		$expected = 'Nee';
		$value = false;
		$result = $this->Output->boolean($value);
		$this->assertEquals($expected, $result);

		$expected = '-';
		$value = 1;
		$result = $this->Output->boolean($value);
		$this->assertEquals($expected, $result);

		$expected = '-';
		$value = 0;
		$result = $this->Output->boolean($value);
		$this->assertEquals($expected, $result);

		$expected = '-';
		$value = '';
		$result = $this->Output->boolean($value);
		$this->assertEquals($expected, $result);

		$expected = '-';
		$value = null;
		$result = $this->Output->boolean($value);
		$this->assertEquals($expected, $result);
	}

/**
 * testHtml method
 *
 * @return void
 */
	public function testHtml() {
		$expected = '<a target="_blank" href="http://www.example.org">link</a>';
		$value = '<a href="http://www.example.org">link</a>';
		$result = $this->Output->html($value);
		$this->assertEquals($expected, $result);

		$expected = '<a target="_blank" href="http://www.example.org">link</a>';
		$value = '<a href="http://www.example.org" target="_self">link</a>';
		$result = $this->Output->html($value);
		$this->assertEquals($expected, $result);
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
