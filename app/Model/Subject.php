<?php
App::uses('AppModel', 'Model');
/**
 * Subject Model
 *
 * @property Exam $Exam
 * @property GivenAnswer $GivenAnswer
 */
class Subject extends AppModel {

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
		'Exam' => array(
			'className' => 'Exam',
			'foreignKey' => 'exam_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'GivenAnswer' => array(
			'className' => 'GivenAnswer',
			'foreignKey' => 'subject_id',
			'dependent' => false
		)
	);
}