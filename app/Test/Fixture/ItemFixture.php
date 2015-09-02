<?php
/**
 * ItemFixture
 *
 */
class ItemFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'),
		'exam_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'index'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'second_version_order' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'answer_option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'correct_answer_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'correct_answer_percentage' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,1', 'unsigned' => false),
		'correct_answer_irc' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,3', 'unsigned' => false),
		'missing_answer_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'missing_answer_percentage' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,1', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'exam_id' => array('column' => 'exam_id', 'unique' => 0)
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
	);

}
