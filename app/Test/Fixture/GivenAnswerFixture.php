<?php
App::uses('AppFixture', 'Test/Fixture');

/**
 * GivenAnswer Fixture
 *
 */
class GivenAnswerFixture extends AppFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'subject_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'value' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'score' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '10,8', 'unsigned' => false),
		'content' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'item_id' => array('column' => 'item_id', 'unique' => 0),
			'subject_id' => array('column' => 'subject_id', 'unique' => 0)
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
			'id' => 1,
			'item_id' => 1,
			'subject_id' => 1,
			'score' => 0
		),
		array(
			'id' => 2,
			'item_id' => 1,
			'subject_id' => 2,
			'score' => 0
		),
		array(
			'id' => 2881298,
			'item_id' => 21773,
			'subject_id' => 100843,
			'value' => null,
			'score' => 0.00000000,
			'content' => null
		),
	);

}
