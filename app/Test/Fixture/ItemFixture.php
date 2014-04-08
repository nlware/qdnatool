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
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'exam_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'second_version_order' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'answer_option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'correct_answer_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'correct_answer_percentage' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,1'),
		'correct_answer_irc' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,3'),
		'missing_answer_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'missing_answer_percentage' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,1'),
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
			'id' => '1',
			'exam_id' => '2',
			'order' => '1',
			'second_version_order' => null,
			'value' => '1B 05 individualisering 002',
			'answer_option_count' => '3',
			'correct_answer_count' => null,
			'correct_answer_percentage' => null,
			'correct_answer_irc' => null,
			'missing_answer_count' => null,
			'missing_answer_percentage' => null
		),
	);

}
