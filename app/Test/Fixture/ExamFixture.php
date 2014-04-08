<?php
App::uses('ExamFormat', 'Model');
App::uses('ExamState', 'Model');

/**
 * ExamFixture
 *
 */
class ExamFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'exam_state_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'exam_format_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'data_filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'mapping_filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'answer_option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'max_answer_option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'average_score' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '10,3'),
		'standard_deviation' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '10,8'),
		'cronbachs_alpha' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,3'),
		'report_filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'uploaded' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'imported' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'duplicated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'analysed' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'report_generated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'exam_format_id' => array('column' => 'exam_format_id', 'unique' => 0),
			'exam_state_id' => array('column' => 'exam_state_id', 'unique' => 0),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0)
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
			'id' => '1',
			'parent_id' => null,
			'name' => 'AANGEPAST',
			'exam_state_id' => ExamState::IMPORTED,
			'user_id' => '1',
			'exam_format_id' => ExamFormat::QMP,
			'data_filename' => '51e7c424-b7a8-47fb-801f-72f17f000101',
			'mapping_filename' => null,
			'answer_option_count' => '3',
			'max_answer_option_count' => null,
			'average_score' => null,
			'standard_deviation' => null,
			'cronbachs_alpha' => null,
			'report_filename' => null,
			'uploaded' => '2000-01-01 00:00:00',
			'imported' => '2000-01-01 00:01:00',
			'duplicated' => null,
			'analysed' => null,
			'report_generated' => null,
			'deleted' => null,
			'created' => '2000-01-01 00:00:00',
			'modified' => '2000-01-01 00:00:00'
		),
	);

}
