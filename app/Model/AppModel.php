<?php
/**
 * Application model for Cake.
 *
 * @copyright     Copyright (c) NLWare B.V. (http://www.nlware.com)
 * @link          http://docs.qdnatool.org qDNAtool(tm) Project
 * @package       app.Model
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
 * @var int
 */
	public $recursive = -1;

/**
 * Deletes multiple model records based on a set of conditions.
 * A workaround for CakePHP lack of support for recursive
 *
 * @param mixed $conditions Conditions to match
 * @param bool $cascade Set to true to delete records that depend on this record
 * @param bool $callbacks Run callbacks
 * @param int $recursive (Optional) Overrides the default recursive level
 * @return bool True on success, false on failure
 */
	public function deleteAll($conditions, $cascade = true, $callbacks = false, $recursive = null) {
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

		$result = parent::deleteAll($conditions, $cascade, $callbacks);

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
 * @param int $recursive (Optional) Overrides the default recursive level
 * @return bool True on success, false on failure
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

/**
 * removeFieldFromSchema method
 *
 * @param string $fieldname Fieldname
 * @return void
 */
	public function removeFieldFromSchema($fieldname) {
		unset($this->_schema[$fieldname]);
	}

/**
 * Create foreign key constraints for all belongsTo relations.
 *
 * @param DboSource[optional] $db An instance of the database object
 * @return void
 */
	public function addForeignKeyConstraints($db = null) {
		if ($db === null) {
			$db = $this->getDataSource();
		}

		if (!empty($this->belongsTo)) {
			$query = '';

			foreach ($this->belongsTo as $name => $belongsTo) {
				$query .= sprintf(
					'ALTER TABLE %s ADD FOREIGN KEY (%s) REFERENCES %s (%s);',
					$db->name($this->useTable),
					$db->name($belongsTo['foreignKey']),
					$db->name($this->{$name}->useTable),
					$db->name($this->primaryKey)
				);
			}

			$db->execute($query);
		}
	}

/**
 * Drop all foreign key constraints that target the table of the current model.
 *
 * @param DboSource[optional] $db An instance of the database object
 * @return void
 */
	public function dropForeignKeyConstraints($db = null) {
		if ($db === null) {
			$db = $this->getDataSource();
		}

		$constraints = $this->_getIncomingForeignKeyConstraints($db);

		if (!empty($constraints)) {
			$query = '';

			foreach ($constraints as $constraint) {
				$query .= sprintf(
					'ALTER TABLE %s DROP FOREIGN KEY %s;',
					$db->name($constraint['source_table']),
					$db->name($constraint['name'])
				);
			}

			$db->execute($query);
		}
	}

/**
 * Fetch all foreign key constraints that target the table of the current model.
 *
 * @param DboSource $db An instance of the database object
 * @return array A list with name and source table of the constraints
 */
	protected function _getIncomingForeignKeyConstraints($db) {
		$schema = $db->getSchemaName();

		$query = '' .
			'SELECT ' .
			'  `CONSTRAINT_NAME` as `name`, ' .
			'  `TABLE_NAME` as `source_table` ' .
			'FROM ' .
			'  `information_schema`.`KEY_COLUMN_USAGE` ' .
			'WHERE ' .
			'  `CONSTRAINT_SCHEMA` = ? AND ' .
			'  `TABLE_SCHEMA` = ? AND ' .
			'  `REFERENCED_TABLE_SCHEMA`= ? AND ' .
			'  `REFERENCED_TABLE_NAME` = ? ;';

		$constraints = $db->fetchAll($query, array($schema, $schema, $schema, $this->useTable));

		$constraints = array_map(function($constraint) {
			return [
				'name' => $constraint['KEY_COLUMN_USAGE']['name'],
				'source_table' => $constraint['KEY_COLUMN_USAGE']['source_table']
			];
		}, $constraints);

		return $constraints;
	}

}
