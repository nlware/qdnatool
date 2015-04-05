<?php
/**
 * AnswerOptionFixture
 *
 */
class AnswerOptionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'index'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'value' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'is_correct' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'given_answer_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'given_answer_irc' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,3', 'unsigned' => false),
		'given_answer_percentage' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '4,1', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'item_id' => array('column' => 'item_id', 'unique' => 0)
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
			'id' => 99456,
			'item_id' => 21773,
			'order' => 1,
			'value' => null,
			'is_correct' => false,
			'given_answer_count' => 23,
			'given_answer_irc' => -0.161,
			'given_answer_percentage' => 26.4
		),
		array(
			'id' => 99457,
			'item_id' => 21773,
			'order' => 2,
			'value' => null,
			'is_correct' => true,
			'given_answer_count' => 16,
			'given_answer_irc' => -0.187,
			'given_answer_percentage' => 18.4
		),
		array(
			'id' => 99458,
			'item_id' => 21773,
			'order' => 3,
			'value' => null,
			'is_correct' => false,
			'given_answer_count' => 21,
			'given_answer_irc' => 0.262,
			'given_answer_percentage' => 24.1
		),
		array(
			'id' => 99459,
			'item_id' => 21773,
			'order' => 4,
			'value' => null,
			'is_correct' => false,
			'given_answer_count' => 26,
			'given_answer_irc' => 0.045,
			'given_answer_percentage' => 29.9
		),
	);

}
