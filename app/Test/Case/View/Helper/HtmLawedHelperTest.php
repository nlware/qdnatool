<?php
App::uses('View', 'View');
App::uses('HtmLawedHelper', 'View/Helper');

/**
 * HtmLawedHelper Test Case
 */
class HtmLawedHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$View = new View();
		$this->HtmLawed = new HtmLawedHelper($View);
	}

/**
* tearDown method
*
* @return void
*/
	public function tearDown() {
		unset($this->HtmLawed);

		parent::tearDown();
	}

/**
 * test that display adds the correct default options.
 *
 * @return void
 */
	public function testDisplayWithoutOptions() {
		$expected = 'return';
		$html = '<html></html';

		$View = new View();
		$HtmLawed = $this->getMock('HtmLawedHelper', array('_htmLawed'), array($View));
		$HtmLawed->expects($this->once())
			->method('_htmLawed')
			->with($html, array('keep_bad' => 1, 'safe' => 1, 'elements' => 'em, img, p, strong, u, strike, sub, sup, i'))
			->will($this->returnValue($expected));

		$result = $HtmLawed->display($html);
		$this->assertEquals($expected, $result);
	}

/**
 * test that display adds the correct default options.
 *
 * @return void
 */
	public function testDisplayWithOptions() {
		$expected = 'return';
		$html = '<html></html';

		$View = new View();
		$HtmLawed = $this->getMock('HtmLawedHelper', array('_htmLawed'), array($View));
		$HtmLawed->expects($this->once())
			->method('_htmLawed')
			->with($html, array('test' => 'test', 'keep_bad' => 1, 'safe' => 1, 'elements' => 'em, img, p, strong, u, strike, sub, sup, i'))
			->will($this->returnValue($expected));

		$result = $HtmLawed->display($html, array('test' => 'test'));
		$this->assertEquals($expected, $result);
	}

/**
 * Test that display doesn't escape allowed HTML tags.
 *
 * @return void
 */
	public function testDisplayWithAllowedHtml() {
		$expected = '<p></p>';
		$html = '<p></p>';
		$result = $this->HtmLawed->display($html);
		$this->assertEquals($expected, $result);
	}

/**
 * Test that display does escape not allowed HTML tags.
 *
 * @return void
 */
public function testDisplayWithNotAllowedHtml() {
		$expected = '&lt;html&gt;&lt;/html';
		$html = '<html></html';
		$result = $this->HtmLawed->display($html);
		$this->assertEquals($expected, $result);
	}

}
