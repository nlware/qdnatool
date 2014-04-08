<?php
App::uses('AppModel', 'Model');
/**
 * QuestionAnswer Model
 *
 * @property Question $Question
 */
class QuestionAnswer extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => 'create',
				'message' => 'This field cannot be left blank'
			)
		)
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'question_id'
		)
	);

	public static function printIndex($index) {
		return chr(65 + $index);
	}
}