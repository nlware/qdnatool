<?php
App::uses('BootstrapHtmlHelper', 'TwitterBootstrap.View/Helper');
class CustomBootstrapHtmlHelper extends BootstrapHtmlHelper {

	public function tag($name, $text = null, $options = array()) {
		if (is_string($options)) {
			$options = array('class' => $options);
		}
		return parent::tag($name, $text, $options);
	}

}