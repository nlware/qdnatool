<?php
App::import('Vendor', 'htmLawed', array('file' => 'htmLawed' . DS . 'htmLawed.php'));
class HtmLawedHelper extends AppHelper {

	private $__config = array('keep_bad' => 1, 'safe' => 1, 'elements' => 'em, img, p, strong, u, strike, sub, sup, i');

	public function display($html, $options = array()) {
		$options = array_merge($this->__config, $options);
		return htmLawed($html, $options);
	}
}
