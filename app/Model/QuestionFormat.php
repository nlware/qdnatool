<?php
App::uses('AppModel', 'Model');
/**
 * QuestionFormat Model
 *
 * @property Question $Question
 */
class QuestionFormat extends AppModel {

	const TRUE_FALSE = 1;

	const MULTIPLE_CHOICE = 2;

	const MULTIPLE_RESPONSE = 3;

	const OPEN_ANSWER = 4;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'last' => true,
				'message' => 'This field cannot be left blank'
			),
			'required' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'on' => 'create'
			)
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'question_format_id',
			'dependent' => false
		)
	);

/**
 * Returns the minimal number of required answer options for given QuestionFormat
 *
 * @param integer $questionFormatId A QuestionFormat ID
 * @return integer
 */
	public static function getMinimalQuestionAnswers($questionFormatId) {
		$minimalQuestionAnswers = 0;
		switch ($questionFormatId) {
			case QuestionFormat::TRUE_FALSE:
				$minimalQuestionAnswers = 2;
				break;
			case QuestionFormat::MULTIPLE_CHOICE:
			case QuestionFormat::MULTIPLE_RESPONSE:
				$minimalQuestionAnswers = 3;
				break;
			case QuestionFormat::OPEN_ANSWER:
				$minimalQuestionAnswers = 0;
				break;
			default:
				$minimalQuestionAnswers = 0;
		}
		return $minimalQuestionAnswers;
	}

}