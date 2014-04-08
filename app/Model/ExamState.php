<?php
App::uses('AppModel', 'Model');
/**
 * ExamState Model
 *
 * @property Exam $Exam
 */
class ExamState extends AppModel {

	public $actsAs = array('I18n' => array('fields' => array('name'), 'display' => 'name'));

	const UPLOADED = 1;

	const IMPORTED = 2;

	const ANALYSED = 3;

	const UPLOAD_FAILED = 4;

	const IMPORT_FAILED = 5;

	const ANALYSE_FAILED = 6;

	const IMPORTING = 7;

	const ANALYSING = 8;

	const WAITING_TO_ANALYSE = 9;

	const REPORT_GENERATED = 10;

	const GENERATING_REPORT = 11;

	const WAITING_TO_GENERATE_REPORT = 12;

	const REPORT_FAILED = 13;

	const WAITING_TO_IMPORT = 14;

	const DUPLICATED = 15;

	const WAITING_TO_REANALYSE = 16;

	const REANALYSED = 17;

	const REANALYSE_FAILED = 18;

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Exam' => array(
			'className' => 'Exam',
			'foreignKey' => 'exam_state_id',
			'dependent' => false
		)
	);
}