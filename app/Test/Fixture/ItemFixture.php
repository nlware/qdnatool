<?php
App::uses('AppFixture', 'Test/Fixture');

/**
 * Item Fixture
 *
 */
class ItemFixture extends AppFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'exam_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true),
		'second_version_order' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'answer_option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'correct_answer_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'correct_answer_percentage' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '4,1', 'unsigned' => true),
		'correct_answer_irc' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '4,3', 'unsigned' => false),
		'missing_answer_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'missing_answer_percentage' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '4,1', 'unsigned' => true),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'exam_id' => array('column' => 'exam_id', 'unique' => 0)
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
			'exam_id' => 2,
			'order' => 1,
			'second_version_order' => null,
			'value' => '1B 05 individualisering 002',
			'answer_option_count' => 3,
			'correct_answer_count' => null,
			'correct_answer_percentage' => null,
			'correct_answer_irc' => null,
			'missing_answer_count' => null,
			'missing_answer_percentage' => null
		),
		array(
			'id' => 21773,
			'exam_id' => 747,
			'order' => 35,
			'second_version_order' => null,
			'value' => '35',
			'answer_option_count' => 4,
			'correct_answer_count' => 16,
			'correct_answer_percentage' => 18.4,
			'correct_answer_irc' => -0.187,
			'missing_answer_count' => 1,
			'missing_answer_percentage' => 1.1
		),
		array(
			'id' => 100000,
			'exam_id' => 747,
			'order' => 1,
			'second_version_order' => null,
			'value' => '35 (dup)',
			'answer_option_count' => 4,
			'correct_answer_count' => 16,
			'correct_answer_percentage' => 18.4,
			'correct_answer_irc' => -0.187,
			'missing_answer_count' => 1,
			'missing_answer_percentage' => 1.1
		),
	);

}
