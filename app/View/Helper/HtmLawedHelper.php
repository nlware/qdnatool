<?php
App::import('Vendor', 'htmLawed', array('file' => 'htmlawed' . DS . 'htmlawed' . DS . 'htmLawed.php'));
class HtmLawedHelper extends AppHelper {

	private $__config = array('keep_bad' => 1, 'safe' => 1, 'elements' => 'em, img, p, strong, u, strike, sub, sup, i');

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
		return htmLawed($html, $options);
	}

}
