<?php
App::uses('BootstrapFormHelper', 'TwitterBootstrap.View/Helper');
class CustomBootstrapFormHelper extends BootstrapFormHelper {

	public function create($model = null, $options = array()) {
		if (!isset($options['novalidate'])) {
			$options['novalidate'] = true;
		}
		return parent::create($model, $options);
	}

	protected function _restructureLabel($out, $options = array()) {
		$out = explode("\n", $out);
		foreach ($out as $key => &$_out) {
			$input = strip_tags($_out, '<input><img><span>');
			if ($input) {
				$_out = $this->Html->tag('label', $input, $options);
			}
		}
		return implode("\n", $out);
	}

	public function checkbox($fieldName, $options = array()) {
		$label = $this->_extractOption('label', $options);
		if (!is_array($label)) {
			$options['label'] = array('text' => $label);
		}
		$options['label']['class'] = 'inline';
		$this->_Opts[$fieldName] = $options;
		$isHorizontal = $this->_isHorizontal;
		$this->_isHorizontal = false;
		$output = parent::checkbox($fieldName, $options);
		$this->_isHorizontal = $isHorizontal;
		return $output;
	}
}