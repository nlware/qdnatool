<?php
App::uses('AppModel', 'Model');
/**
 * ExamFormat Model
 *
 * @property Exam $Exam
 */
class ExamFormat extends AppModel {

	const TELEFORM = 1;

	const BLACKBOARD = 2;

	const QMP = 3;

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	public $order = array('ExamFormat.name' => 'ASC');

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Exam' => array(
			'className' => 'Exam',
			'foreignKey' => 'exam_format_id',
			'dependent' => false
		)
	);
}