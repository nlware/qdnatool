<?php
class CkSourceHelper extends FormHelper {

	private $__included = false;

	public $helpers = array('Form', 'Html', 'Js');

/**
 * Sets field defaults for ckeditor: id, config and language.
 *
 * Options
 *
 * - string 'id': To access events, same as $field
 * - array 'config': If not indicate
 * - string 'language': Set the language from cake
 *
 * @param string $field Name of the field to initialize options for.
 * @param array $options Array of options to append options into.
 * @return array Array of options for the input.
 * @access protected
 */
	protected function _initInputField($field, $options = array()) {
		$this->setEntity($field);
		$htmlAttributes = $this->domId($options);

		// It needs id for replace function
		if (!array_key_exists('id', $options)) {
			$options['id'] = $htmlAttributes['id'];
		}

		// CKeditor only works in textarea inputs
		if (!array_key_exists('type', $options)) {
			$options['type'] = 'textarea';
		}

		// Defaults options
		if (!array_key_exists('config', $options)) {
			$options['config']['toolbar'] = array(array('Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', '-', 'Image', '-', 'fmath_formula', '-', 'Source'));

			$options['config']['filebrowserUploadUrl'] = $this->Html->url(array('controller' => 'images', 'action' => 'upload'));

			$options['config']['extraPlugins'] = 'fmath_formula,autogrow';

			$options['config']['resize_enabled'] = false;
			$options['config']['toolbarCanCollapse'] = false;
			$options['config']['removePlugins'] = 'elementspath';
			//$options['config']['height'] = '100px';
		}

		if (!$this->__included) {
			$this->__included = true;

			$script = '';

			// Config language
			$lang = Configure::read('Config.language');
			if (isset($lang)) {
				if ($lang == 'nld') {
					$lang = 'nl';
				}
				// Load language
				$script .= 'CKEDITOR.config.language = \'' . $lang . '\';';
			}

			$script .=
				"
		CKEDITOR.plugins.addExternal('fmath_formula', 'plugins/fmath_formula/', 'plugin.js');

		CKEDITOR.on( 'dialogDefinition', function( ev )
		{
			// Take the dialog name and its definition from the event data.
			var dialogName = ev.data.name;
			var dialogDefinition = ev.data.definition;

			if( dialogName == 'image' )
			{
				// FCKConfig.ImageDlgHideAdvanced = true
				dialogDefinition.removeContents( 'advanced' );
				// FCKConfig.ImageDlgHideLink = true
				dialogDefinition.removeContents( 'Link' );
			}
		});

		CKEDITOR.on( 'instanceReady', function( ev )
    {
      // Clean up HTML on paste in CKEditor
      ev.editor.on('paste', function(evt) {
          evt.data['html'] = '<!--class=\"Mso\"-->'+evt.data['html'];
      }, null, null, 9);

      // Ends self closing tags the HTML4 way, like <br>.
      ev.editor.dataProcessor.writer.setRules('p',
      {
        indent : false,
        breakBeforeOpen : true,
        breakAfterOpen : false,
        breakBeforeClose : false,
        breakAfterClose : true
      });
    });";

			$script = '$(document).ready(function () {' . $script . '});';

			$this->Html->scriptBlock($script, array('inline' => false));
		}
		return $options;
	}

/**
 * Creates a textarea widget with CKeditor.
 *
 * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
 * @param array $options Array of HTML attributes, and special options.
 * @return string A generated HTML text input element
 * @see FormHelper::input()
 */
	public function ckeditor($fieldName, $options = array()) {
		$options = $this->_initInputField($fieldName, $options);
		$value = null;
		$config = null;
		$events = null;

		if (array_key_exists('value', $options)) {
			$value = $options['value'];
			if (!array_key_exists('escape', $options) || $options['escape'] !== false) {
				$value = h($value);
			}
			unset($options['value']);
		}
		if (array_key_exists('config', $options)) {
			$config = $options['config'];
			unset($options['config']);
		}
		if (array_key_exists('events', $options)) {
			$events = $options['events'];
			unset($options['events']);
		}

		require_once WWW_ROOT . DS . 'js' . DS . 'ckeditor' . DS . 'ckeditor.php';
		$CKEditor = new CKEditor();
		$CKEditor->basePath = $this->webroot . 'js/ckeditor/';

		//unset($options['name']);
		//		debug($options);
		echo $this->Form->input($fieldName, $options);
		return $CKEditor->replace($options['id'], $config, $events);
	}
}
