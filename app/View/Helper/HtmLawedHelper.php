<?php
App::uses('AppHelper', 'View/Helper');

/**
 * HtmLawed Helper.
 */
class HtmLawedHelper extends AppHelper {

	private $__config = array('keep_bad' => 1, 'safe' => 1, 'elements' => 'em, img, p, strong, u, strike, sub, sup, i');

/**
 * Wrapper for htmLawed to make testing easier.
 *
 * @param string $text The input text to be processed
 * @param array[optional] $config A list with configuration that instructs htmLawed on how to tackle certain tasks
 * @param array[optional] $spec The $spec argument of htmLawed can be used to disallow an otherwise legal attribute for an element, or to restrict the attribute's values.
 * @return string
 */
	protected function _htmLawed($text, $config = array(), $spec = array()) {
		return htmLawed($text, $config, $spec);
	}

/**
 * Returns a formatted HTML string given a HTML string
 *
 * Escapes all HTML elements except em, img, p, strong, u, strike, sub, sup, i
 *
 * @param string $html A HTML string
 * @param array $options Cofiguration options
 * @return string
 */
	public function display($html, $options = array()) {
		$options = array_merge($this->__config, $options);

		return $this->_htmLawed($html, $options);
	}

}
