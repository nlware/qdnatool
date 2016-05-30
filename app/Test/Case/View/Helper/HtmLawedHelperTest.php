<?php
App::uses('View', 'View');
App::uses('HtmLawedHelper', 'View/Helper');

/**
 * HtmLawedHelper Test Case
 */
class HtmLawedHelperTest extends CakeTestCase {

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

}
