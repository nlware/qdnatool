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

	//The Associations below have been created with all possible keys, those that are not needed can be removed

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

	public static function printIndex($index) {
		return chr(65 + $index);
	}

	public static function printValue($value) {
		return chr(65 + $value - 1);
	}
}