<?php
App::uses('AppFixture', 'Test/Fixture');
App::uses('ExamFormat', 'Model');

/**
 * ExamFormat Fixture
 *
 */
class ExamFormatFixture extends AppFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => ExamFormat::TELEFORM,
			'name' => 'Teleform'
		),
		array(
			'id' => ExamFormat::BLACKBOARD,
			'name' => 'Blackboard'
		),
		array(
			'id' => ExamFormat::QMP,
			'name' => 'QMP'
		),
	);

}
