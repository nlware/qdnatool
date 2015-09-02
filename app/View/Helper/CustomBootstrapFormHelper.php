<?php
App::uses('BootstrapFormHelper', 'TwitterBootstrap.View/Helper');
class CustomBootstrapFormHelper extends BootstrapFormHelper {

/**
 * Returns an HTML FORM element.
 *
 * @param mixed $model The model name for which the form is being defined.
 * @param array $options An array of html attributes and options.
 * @return string An formatted opening FORM tag.
 * @see BootstrapFormHelper::create()
 */
	public function create($model = null, $options = array()) {
		if (!isset($options['novalidate'])) {
			$options['novalidate'] = true;
		}
		return parent::create($model, $options);
	}

/**
 * _restructureLabel method
 *
 * @param string $out HTML to restructure
 * @param array $options Configuration options
 * @return string
 * @see BootstrapFormHelper::_restructureLabel()
 */
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

/**
 * Creates a checkbox input widget.
 *
 * @param string $fieldName Name of a field, like this "Modelname.fieldname"
 * @param array $options Array of HTML attributes.
 * @return string An HTML text input element.
 * @see BootstrapFormHelper::checkbox
 */
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