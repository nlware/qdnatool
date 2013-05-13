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

	public function input($fieldName, $options = array()) {
		$options = array_merge(
				array('format' => array('before', 'label', 'between', 'input', 'error', 'after')),
				$this->_inputDefaults,
				$options
		);
		$this->_Opts[$fieldName] = $options;

		$type = $this->_extractOption('type', $options);
		$options = $this->_getType($fieldName, $options);

		$hidden = null;
		if ('hidden' === $options['type']) {
			$options['div'] = false;
			$options['label'] = false;
		} else {
			$options = $this->uneditable($fieldName, $options, true);
			$options = $this->addon($fieldName, $options, true);
			$options = $this->_setOptions($fieldName, $options);
			$options = $this->_controlGroupStates($fieldName, $options);
			$options = $this->_buildAfter($options);

			$hidden = $this->_hidden($fieldName, $options);
			if ($hidden) {
				$options['hiddenField'] = false;
			}
		}

		if (is_null($type) && empty($this->_Opts[$fieldName]['type'])) {
			unset($options['type']);
		}

		$disabled = $this->_extractOption('disabled', $options, false);
		if ($disabled) {
			$options = $this->addClass($options, 'disabled');
		}

		$div = $this->_extractOption('div', $options);
		$options['div'] = false;

		$before = $this->_extractOption('before', $options);
		$options['before'] = null;

		$label = $this->_extractOption('label', $options);
		if (false !== $label) {
			if (!is_array($label)) {
				$label = array('text' => $label);
			}
			if (false !== $div) {
				$class = $this->_extractOption('class', $label, 'control-label');
				$label = $this->addClass($label, $class);
			}
			$text = $label['text'];
			unset($label['text']);
			$label = $this->label($fieldName, $text, $label);
		}
		$options['label'] = false;

		$between = $this->_extractOption('between', $options);
		$options['between'] = null;

		$input = parent::input($fieldName, $options);
		$divControls = $this->_extractOption('divControls', $options, self::CLASS_INPUTS);
		$input = $hidden . ((false === $div) ? $input : $this->Html->div($divControls, $input));

		$out = $before . $label . $between . $input;
		$attr = $div;
		//if(!empty($attr['class'])) unset($attr['class']);
		return (false === $div) ? $out : $this->Html->div(empty($div['class'])?null:$div['class'], $out, $attr);
	}

}