<?php
App::uses('AppModel', 'Model');
/**
 * AnswerOption Model
 *
 * @property Item $Item
 */
class AnswerOption extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'value';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id'
		)
	);

/**
 * printIndex method
 *
 * @param integer $index Index
 * @return string
 */
	public static function printIndex($index) {
		return chr(65 + $index);
	}

/**
 * printValue method
 *
 * @param integer $value Value
 * @return string
 */
	public static function printValue($value) {
		return chr(65 + $value - 1);
	}
}