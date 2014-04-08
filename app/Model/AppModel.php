<?php
/**
 * @copyright     Copyright (c) NLWare B.V. (http://www.nlware.com)
 * @link          http://docs.qdnatool.org qDNAtool(tm) Project
 * @license       http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB CC BY-NC-SA 3.0 License
 */

App::uses('Model', 'Model');

/**
 * Application model for qDNAtool.
 *
 * @package       app.Model
 */
class AppModel extends Model {

/**
 * actsAs behaviors
 *
 * @var array
 */
	public $actsAs = array('Containable');

/**
 * recursive
 *
 * @var integer
 */
	public $recursive = -1;

/**
 * Deletes multiple model records based on a set of conditions.
 * A workaround for CakePHP lack of support for recursive
 *
 * @param mixed $conditions Conditions to match
 * @param boolean $cascade Set to true to delete records that depend on this record
 * @param boolean $callbacks Run callbacks
 * @return boolean True on success, false on failure
 */
	public function deleteAll($fields, $conditions = true, $recursive = null) {
		if (!isset($recursive)) {
			$recursive = $this->recursive;
		}

		if ($recursive == -1) {
			$belongsTo = $this->belongsTo;
			$hasOne = $this->hasOne;
			$this->unbindModel(array(
				'belongsTo' => array_keys($belongsTo),
				'hasOne' => array_keys($hasOne)
			), true);
		}

		$result = parent::deleteAll($fields, $conditions);

		if ($recursive == -1) {
			$this->bindModel(array(
				'belongsTo' => $belongsTo,
				'hasOne' => $hasOne
			), true);
		}

		return $result;
	}

/**
 * Updates multiple model records based on a set of conditions.
 * A workaround for CakePHP lack of support for recursive
 *
 * @param array $fields Set of fields and values, indexed by fields.
 *    Fields are treated as SQL snippets, to insert literal values manually escape your data.
 * @param mixed $conditions Conditions to match, true for all records
 * @return boolean True on success, false on failure
 */
	public function updateAll($fields, $conditions = true, $recursive = null) {
		if (!isset($recursive)) {
			$recursive = $this->recursive;
		}

		if ($recursive == -1) {
			$belongsTo = $this->belongsTo;
			$hasOne = $this->hasOne;
			$this->unbindModel(array(
				'belongsTo' => array_keys($belongsTo),
				'hasOne' => array_keys($hasOne)
			), true);
		}

		$result = parent::updateAll($fields, $conditions);

		if ($recursive == -1) {
			$this->bindModel(array(
				'belongsTo' => $belongsTo,
				'hasOne' => $hasOne
			), true);
		}

		return $result;
	}

	public function removeFieldFromSchema($fieldname) {
		unset($this->_schema[$fieldname]);
	}
}
