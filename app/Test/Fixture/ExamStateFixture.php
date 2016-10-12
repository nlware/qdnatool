<?php
App::uses('ExamState', 'Model');

/**
 * ExamState Fixture
 */
class ExamStateFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'name_eng' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name_nld' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => ExamState::UPLOADED,
			'name_eng' => 'Uploaded',
			'name_nld' => 'Geupload'
		),
		array(
			'id' => ExamState::IMPORTED,
			'name_eng' => 'Imported',
			'name_nld' => 'Geïmporteerd'
		),
		array(
			'id' => ExamState::ANALYSED,
			'name_eng' => 'Analysed',
			'name_nld' => 'Geanalyseerd'
		),
		array(
			'id' => ExamState::UPLOAD_FAILED,
			'name_eng' => 'Upload failed',
			'name_nld' => 'Uploaden mislukt'
		),
		array(
			'id' => ExamState::IMPORT_FAILED,
			'name_eng' => 'Import failed',
			'name_nld' => 'Importeren mislukt'
		),
		array(
			'id' => ExamState::ANALYSE_FAILED,
			'name_eng' => 'Analyse failed',
			'name_nld' => 'Analyseren mislukt'
		),
		array(
			'id' => ExamState::IMPORTING,
			'name_eng' => 'Importing',
			'name_nld' => 'Bezig met importeren'
		),
		array(
			'id' => ExamState::WAITING_TO_ANALYSE,
			'name_eng' => 'Waiting to analyse',
			'name_nld' => 'Wachten om te analyseren'
		),
		array(
			'id' => ExamState::REPORT_GENERATED,
			'name_eng' => 'Report generated',
			'name_nld' => 'Rapport gegenereerd'
		),
		array(
			'id' => ExamState::GENERATING_REPORT,
			'name_eng' => 'Generating report',
			'name_nld' => 'Bezig met genereren rapport'
		),
		array(
			'id' => ExamState::WAITING_TO_GENERATE_REPORT,
			'name_eng' => 'Waiting to generate report',
			'name_nld' => 'Wachten op het genereren van rapport'
		),
		array(
			'id' => ExamState::REPORT_FAILED,
			'name_eng' => 'Report failed',
			'name_nld' => 'Rapport mislukt'
		),
		array(
			'id' => ExamState::WAITING_TO_IMPORT,
			'name_eng' => 'Waiting to import',
			'name_nld' => 'Wachten op importeren'
		),
		array(
			'id' => ExamState::DUPLICATED,
			'name_eng' => 'Duplicated',
			'name_nld' => 'Gedupliceerd'
		),
		array(
			'id' => ExamState::WAITING_TO_REANALYSE,
			'name_eng' => 'Waiting to reanalyse',
			'name_nld' => 'Wachten op heranalyseren'
		),
		array(
			'id' => ExamState::REANALYSED,
			'name_eng' => 'Reanalysed',
			'name_nld' => 'Heranalyseerd'
		),
		array(
			'id' => ExamState::REANALYSE_FAILED,
			'name_eng' => 'Reanalyse failed',
			'name_nld' => 'Heranalyse mislukt'
		),
	);

}
