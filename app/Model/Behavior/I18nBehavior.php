<?php
App::uses('I18n', 'I18n');
/* SVN FILE: $Id: $ */
/**
 * Requires:
 * CakePHP 1.2.1.8004
 *
 * I18n behavior for database content internationalization using locale dependent table field names.
 *
 * I18n behavior integration steps:
 * 1. Identify which languages you are going to use
 *	(e.g. English and Russian)
 * 2. Identify your default language
 *	(e.g. English);
 * 3. Identify fields of your models to be internationalized (
 *	(e.g. model Country field 'name' should be I18n compatible);
 * 4. Update your database tables for each model field to be I18n compatible
 *	(e.g. rename 'name' field to <name>.'_'.DEFAULT_LANGUAGE - default, and create field 'name_rus' that will be russian content);
 * 5. Add to your model this behavior;
 *	(e.g. $artAs = array('I18n' => array('fields' => array('name'), 'display' => 'name');)
 * 6. Add to all models that are associated with I18n compatible models this behavior;
 *	(e.g. $actAs = array('I18n'); //you can simply add this to each model )
 *	Its necessary because beforeFind and afterFind invoked for the behavior of the model that calls find method.
 *	During beforeFind and afterFind the behavior will look for any I18n behaviors, see localizeScheme and unlocalizeResults.
 * 7. In your model you can set $displayField as usual. The I18n behavior will unlocalize result field names in afterFind. Default $displayField is 'name'.
 * 8. In your model you can set $order as usual. The I18n behavior will localize your order field name in beforeFind.
 * 9. In your relations you can set order attribute for one field and it will be localized.
 * 10. To save multiple locales pass data with database field names.
 *	(e.g. 'name_rus', 'name_eng');
 * 11. To save data in to current locale pass data without locale profex.
 *	(e.g. 'name' will be saved to 'name_eng' if current locale is 'eng');
 * 12. To load values for all locales detach the I18n behavior before calling model read.
 * (e.g. $this->MyModel->Behaviors->detach('I18n'); $this->MyModel->read();)
 * 13. I18n can be used with Containable behaviour, but becuase it relies on recursion while searching for localizable
 * fields througth relations, check you have enougth recursion level (default recursion=1);
 *
 * PHP versions 4 and 5
 *
 * Copyright 2008, Palivoda IT Solutions, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright       Copyright 2008, Palivoda IT Solutions, Inc.
 * @link            http://www.palivoda.eu
 * @package         app
 * @subpackage      app.models.behaviors
 * @since           CakePHP(tm) v 1.2
 * @version         $Revision:	$
 * @modifiedby      $LastChangedBy:	$
 * @lastmodified    $Date: $
 * @license         http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class I18nBehavior extends ModelBehavior {

	//for each model stores lozalizable field names and their aliases to current locale
	public $fields = array();

/**
 * Reads configuration of behavior.
 * Allowed values:
 * fields - array of I18n compatible field names;
 */
	public function setup(Model $model, $config = array()) {
		if (!defined('DEFAULT_LANGUAGE')) {
			trigger_error("Add to bootstrap.php line: define('DEFAULT_LANGUAGE', 'eng');");
		}
		if (!empty($config['fields'])) {
			$this->fields[$model->alias] = array_fill_keys($config['fields'], null);
		}
	}

	public function cleanup(Model $model) {
		$this->__refreshSchema($model);
		//debug('I18n behaviour detached from '.$model->alias.' model.');
	}

	public function beforeFind(Model $model, $query) {
		$locale = $this->_getLocale($model);
		//debug('I18n-'.$model->alias.'-beforeFind-'.$locale);
		//debug($query);

		//reset shema if model locale set and was changed since last query
		if (isset($model->locale) && $locale != $model->locale) $this->__refreshSchema($model);

		$recursive = empty($query['recursive']) ?
			(empty($model->recursive) ? 0 : $model->recursive)
				: $query['recursive']; //during 'delete' there are queries with empty recursive

		$this->localizeScheme($model, $locale, $recursive);
		$this->localizeQuery($model, $query, $recursive, true);

		//debug($query);
		return $query;
	}

/**
 * Recursively replaces $localField values to $localAlias in $section array (or string)
 */
	private function __localizeArrayInQuery($model, &$section, $localField, $localAlias, $isPrimary, &$level) {
		if ($level <= 0) return; //rectrict recursion level

		//multiple filed as array
		if (is_array($section)) {

			//localize array values
			foreach ($section as $queryAlias => &$queryField) {
				if (is_array($queryField)) {
					//for containable [model] => array('fields'=>array(...)), all sub calls will localize by short name too
					if ($queryAlias == $model->alias) $isPrimary = true;
					//localize array values in sub section (like contain, order)
					$this->__localizeArrayInQuery($model, $queryField, $localField, $localAlias, $isPrimary, $level);
				} else {
					//full name
					if (preg_match('/(^|,| )(' . $model->alias . '.' . $localField . ')(,| |$)/i', $queryField))
						$queryField = preg_replace('/(^|,| )(' . $model->alias . '.' . $localField . ')(,| |$)/i',
							'$1' . $model->alias . '.' . $localAlias . '$3', $queryField);
					//short name
					else if ($isPrimary && preg_match('/(^|,| )(' . $localField . ')(,| |$)/i', $queryField))
						$queryField = preg_replace('/(^|,| )(' . $localField . ')(,| |$)/i',
							'$1' . $localAlias . '$3', $queryField);
				}
			}

			//localize array keys
			$oldKeys = array();
			foreach ($section as $queryAlias => &$queryField) {
				if (preg_match('/(^|,| )(' . $model->alias . '.' . $localField . ')(,| |$)/i', $queryAlias)) { //full name
					$newKey = preg_replace('/(^|,| )(' . $model->alias . '.' . $localField . ')(,| |$)/i',
							'$1' . $model->alias . '.' . $localAlias . '$3', $queryAlias);
					$section[$newKey] = $queryField;
					$oldKeys[] = $queryAlias;
					debug($queryAlias . '' . $newKey);
				} else if ($isPrimary && preg_match('/(^|,| )(' . $localField . ')(,| |$)/i', $queryAlias)) { //short name
					$newKey = preg_replace('/(^|,| )(' . $localField . ')(,| |$)/i',
						'$1' . $localAlias . '$3', $queryAlias);
					$section[$newKey] = $queryField;
					$oldKeys[] = $queryAlias;
					debug($queryAlias . '' . $newKey);
				}
			}
			foreach ($oldKeys as $removeKey) {
				unset($section[$removeKey]);
			}

			unset($queryAlias);
			unset($queryField);
			unset($section);
		} else { //multiple fileds in one string, comma separated
			//full name
			if (strstr($section, $model->alias . '.' . $localField) != false)
				$section = str_replace($model->alias . '.' . $localField, $model->alias . '.' . $localAlias, $section);
			//short name
			else if ($isPrimary && strstr($section, $localField) != false)
				$section = str_replace($localField, $localAlias, $section);
		}
	}

/**
 * Modifies query fielelds to load localized content for current locale.
 * isPrimary should be true only when localizing model that has afterFind event
 */
	public function localizeQuery($model, &$query, $recursive, $isPrimary) {
		if ($model->Behaviors->attached('I18n') && isset($model->Behaviors->I18n->fields[$model->alias])) {
			foreach ($model->Behaviors->I18n->fields[$model->alias] as $localField => $localAlias) { //$localAlias set by localizeScheme

				//localize field names in query sections:
				//1. fields - localize full and short array values
				//2. contain - localize full array values
				//3. conditions - localize array keys, localize array values
				//4. order - localize array values as comma separated string
				foreach (array('fields', 'contain', 'conditions', 'order') as $section) {
					if (isset($query[$section])) {
						$level = 3; //recursion level for __localizeArrayInQuery only
						$this->__localizeArrayInQuery($model, $query[$section], $localField, $localAlias, $isPrimary, $level);
					}
				}

				//on primary model append default display name to query if not exists
				if ($isPrimary &&
					is_array($query['fields']) &&
					$model->displayField == $localField &&
					!in_array($model->alias . '.' . $localAlias, $query['fields']) &&
					!in_array($localAlias,	$query['fields']) ) {
						//keep only one Id column in query
						$query['fields'] = array_values(array_unique($query['fields']));
						$query['fields'][] = $model->alias . '.' . $localAlias;
						//set displayFieled fof list type of query
						$query['list']['valuePath'] = '{n}.' . $model->alias . '.' . $localField;

				}
			}
		}

		//if no recursive set then localize fields of related models
		if (empty($recursive)) $recursive = 0;

		if ($recursive < 0) return;

		//go throught related models and if thay has I18n behaviour then localize theme
		//Note: models A-B-C, if B is not I18n then C will not be localized, even if it has I18n behaviour

		foreach (array('belongsTo','hasOne','hasMany','hasAndBelongsToMany') as $relationGroup) {
			if (isset($model->$relationGroup)) {
				foreach ($model->$relationGroup as $name => &$relation) {
					if ($model->Behaviors->attached('I18n')) {
						$model->Behaviors->I18n->localizeQuery($model->$name, $query, $recursive - 1, false);
					}
				}
			}
		}
	}

/**
 * Modifies theme to load localized content only for default and current locale.
 */
	public function localizeScheme($model, $locale, $recursive, &$relation = null) {
		$model->locale = $locale;

		if ($model->Behaviors->attached('I18n') && isset($model->Behaviors->I18n->fields[$model->alias])) {
			foreach ($model->Behaviors->I18n->fields[$model->alias] as $configName => &$configAlias) {

				//ammend schema and store in config localized field name <name>_<locale> or <name>_def
				$foundSpecific = false;
				$schema = $model->schema();
				foreach ($schema as $shemaName => $v) {
					if (strpos('_' . $shemaName, $configName) == 1) { //is one of I18n fields
						if ($configName . '_' . DEFAULT_LANGUAGE != $shemaName) { //not for default locale
							if ($configName . '_' . $locale != $shemaName) { //not for current locale
								$model->removeFieldFromSchema($shemaName);
								//unset($model->_schema[$shemaName]);
							} else {
								$foundSpecific = true;
								$configAlias = $configName . '_' . $locale;
							}
						}
					}
				}
				unset($shemaName);
				unset($v);
				if ($foundSpecific) { //found locale specific content, no need in default content
					$model->removeFieldFromSchema($configName . '_' . DEFAULT_LANGUAGE);
					//unset($model->_schema[$configName.'_'.DEFAULT_LANGUAGE]);
				} else {
					$configAlias = $configName . '_' . DEFAULT_LANGUAGE;
				}

				//set defailt display field to I18n name or title
				if (empty($model->displayField) || $model->displayField == 'id') {
					if (isset($this->fields[$model->alias]['name'])) {
						$model->displayField = 'name';
					}
					if (isset($this->fields[$model->alias]['title'])) {
						$model->displayField = 'title';
					}
				}

				//localize relations
				if (isset($relation)) {

					// localize other relation attributes: 'conditions', 'fields', 'order', //TODO: 'finderQuery', 'deleteQuery', 'insertQuery'.
					$sections = array(&$relation['fields'], &$relation['order'], &$relation['conditions']);
					foreach ($sections as &$section) {
						//do not localize more than once
						if (isset($section)) {
							if (is_array($section)) {
								foreach ($section as &$subSection) {
									if (substr_count($subSection, $configAlias) == 0)
										$subSection = str_replace($configName, $configAlias, $subSection);
								}
							} else {
								if (strlen($section) > 0 && substr_count($section, $configAlias) == 0)
									$section = str_replace($configName, $configAlias, $section);
							}
						}
					}
				}
			}
		}

		//if no recursive set then update schema of related models
		if (empty($recursive)) $recursive = 0;

		if ($recursive < 0) return;

		//go throught related models and if thay has I18n behaviour then localize theme
		//Note: models A-B-C, if B is not I18n then C will not be localized, even if it has I18n behaviour

		foreach (array('belongsTo','hasOne','hasMany','hasAndBelongsToMany') as $relationGroup) {
			if (isset($model->$relationGroup)) {
				foreach ($model->$relationGroup as $name => &$relation) {
					if ($model->Behaviors->attached('I18n')) {
						$model->Behaviors->I18n->localizeScheme($model->$name, $locale, $recursive - 1, $relation);
					}
				}
			}
		}
	}

	public function afterFind(Model $model, $results, $primary) {
		//debug('I18n-'.$model->alias.'-afterFind');
		if (is_array($results)) {
			foreach ($results as &$result) {
				$this->unlocalizeResults($model, $result, $this->_getLocale($model));
			}
		}
		return $results;
	}

/**
 * Narrows fields of loaded data to locale independant names, e.g. fields <name>_def and <name>_eng will became just <name>.
 * It recurse as far as resulsts are exists. If you made find with recursive 2 then it will recurse till second level of results.
 * TODO: The reverse process should be made before model saved.
 */
	public function unlocalizeResults($model, &$result, $locale) {
		if ($model->Behaviors->attached('I18n') && isset($model->Behaviors->I18n->fields[$model->alias])) {

			//collection of models
			if (!empty($result[$model->alias])) {
				$data = &$result[$model->alias];
			} else { //single model
				$data = &$result;
			}

			foreach ($model->Behaviors->I18n->fields[$model->alias] as $name => $alias) { //alias set in localizeScheme
				//unlocalize field name
				if (is_array($data) && array_key_exists($alias, $data)) {
					$data[$name] = $data[$alias];
					unset($data[$alias]);
				}
			}

			unset($data);
		}

		if (isset($model->belongsTo)) {
			foreach ($model->belongsTo as $name => $relation) {
				// $behaviors = $model->$name->Behaviors;
				if (isset($result[$name]) && $model->Behaviors->attached('I18n')) {
					$model->Behaviors->I18n->unlocalizeResults($model->$name, $result[$name], $locale);
				}
			}
		}

		if (isset($model->hasOne)) {
			foreach ($model->hasOne as $name => $relation) {
				// $behaviors = $model->$name->Behaviors;
				if (isset($result[$name]) && $model->Behaviors->attached('I18n')) {
					$model->Behaviors->I18n->unlocalizeResults($model->$name, $result[$name], $locale);
				}
			}
		}

		if (isset($model->hasMany)) {
			foreach ($model->hasMany as $name => $relation) {
				// $behaviors = $model->$name->Behaviors;
				if (isset($result[$name]) && $model->Behaviors->attached('I18n')) {
					foreach ($result[$name] as &$record) {
						$model->Behaviors->I18n->unlocalizeResults($model->$name, $record, $locale);
					}
				}
			}
		}

		if (isset($model->hasAndBelongsToMany)) {
			foreach ($model->hasAndBelongsToMany as $name => $relation) {
				// $behaviors = $model->$name->Behaviors;
				if (isset($result[$name]) && $model->Behaviors->attached('I18n')) {
					foreach ($result[$name] as &$record) {
						$model->Behaviors->I18n->unlocalizeResults($model->$name, $record, $locale);
					}
				}
			}
		}
	}

	public function beforeSave(Model $model) {
		//get current locale
		$locale = $this->_getLocale($model);

		//if user is saving unlocalized values then reset shema and do not localize any value
		foreach ($this->fields as $modelAlias => $modelFields) {
			foreach ($modelFields as $fieldName => $fieldAlias) {
				if (isset($model->data[$modelAlias][$fieldAlias])) {
					$this->__refreshSchema($model);
					return true; //exit
				}
			}
		}

		//save localized value to alias database field
		foreach ($this->fields as $modelAlias => $modelFields) {
			foreach ($modelFields as $fieldName => $fieldAlias) {
				if (!empty($model->data[$modelAlias][$fieldName])) {
					$model->data[$modelAlias][$fieldAlias] = $model->data[$modelAlias][$fieldName];
					unset($model->data[$modelAlias][$fieldName]);
				}
			}
		}
		//debug($model->data);

		return true;
	}

	private static $__i18n = null;

	protected function _getLocale($model) {
		//instanciate current locale storage class
		if (self::$__i18n == null) {
			//	if (!class_exists('I18n')) {
			//		uses('I18n');
			//	}
			self::$__i18n =& I18n::getInstance();
		}

		//retreive current locale
		$locale = self::$__i18n->l10n->locale;
		//debug($model->alias.' get locale '.$locale);

		return $locale;
	}

	private function __refreshSchema($model) {
		$model->schema(true);
		//debug($model->alias.' schema renewed');
	}

}