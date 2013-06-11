<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	public $actsAs = array('Containable');

	public $recursive = -1;

/**
 * A workaround for CakePHP lack of support for recursive
 */
	public function deleteAll($fields, $conditions = true, $recursive = null) {
		if (!isset($recursive)) $recursive = $this->recursive;

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

	public function updateAll($fields, $conditions = true, $recursive = null) {
		if (!isset($recursive)) $recursive = $this->recursive;

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
