<?php
App::uses('AppFixture', 'Test/Fixture');
App::uses('ExamFormat', 'Model');
App::uses('ExamState', 'Model');

/**
 * Exam Fixture
 *
 */
class ExamFixture extends AppFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'exam_state_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'exam_format_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'data_filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'mapping_filename' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'answer_option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'max_answer_option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'cronbachs_alpha' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '4,3', 'unsigned' => false),
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
			'id' => 1,
			'parent_id' => null,
			'name' => 'AANGEPAST',
			'exam_state_id' => ExamState::IMPORTED,
			'user_id' => 1,
			'exam_format_id' => ExamFormat::QMP,
			'data_filename' => '51e7c424-b7a8-47fb-801f-72f17f000101',
			'mapping_filename' => null,
			'answer_option_count' => 3,
			'max_answer_option_count' => null,
			'cronbachs_alpha' => null,
			'uploaded' => '2000-01-01 00:00:00',
			'imported' => '2000-01-01 00:01:00',
			'duplicated' => null,
			'analysed' => null,
			'report_generated' => null,
			'deleted' => null,
			'created' => '2000-01-01 00:00:00',
			'modified' => '2000-01-01 00:00:00'
		),
		array(
			'id' => 2,
			'name' => 'test',
			'user_id' => 1,
			'exam_format_id' => ExamFormat::TELEFORM,
			'answer_option_count' => 4,
			'created' => '2000-01-01 00:00:00',
			'modified' => '2000-01-01 00:00:00'
		),
		array(
			'id' => 747,
			'parent_id' => null,
			'name' => 'Algera v2',
			'exam_state_id' => 10,
			'user_id' => 1,
			'exam_format_id' => 1,
			'data_filename' => '551e602f-9a88-4589-bca2-15f994fb7d0c',
			'mapping_filename' => null,
			'answer_option_count' => 4,
			'max_answer_option_count' => 4,
			'cronbachs_alpha' => 0.737,
			'uploaded' => '2015-04-03 09:41:03',
			'imported' => '2015-04-03 09:41:18',
			'duplicated' => null,
			'analysed' => '2015-04-03 09:41:18',
			'report_generated' => '2015-04-03 09:41:23',
			'deleted' => null,
			'created' => '2015-04-03 09:41:03',
			'modified' => '2015-04-03 09:41:23'
		),
	);

}
