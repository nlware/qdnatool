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
