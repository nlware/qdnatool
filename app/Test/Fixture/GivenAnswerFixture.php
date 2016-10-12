<?php
/**
 * GivenAnswer Fixture
 */
class GivenAnswerFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'subject_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'value' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'score' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '10,8', 'unsigned' => false),
		'content' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'item_id' => array('column' => 'item_id', 'unique' => 0),
			'subject_id' => array('column' => 'subject_id', 'unique' => 0)
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
		array(
			'id' => 1000000,
			'item_id' => 1000000,
			'subject_id' => 1000000,
			'value' => 3,
			'score' => 0,
		),
		array(
			'id' => 1000001,
			'item_id' => 1000001,
			'subject_id' => 1000000,
			'value' => 3,
			'score' => 0,
		),
		array(
			'id' => 1000002,
			'item_id' => 1000002,
			'subject_id' => 1000000,
			'value' => 3,
			'score' => 0,
		),
		array(
			'id' => 1000003,
			'item_id' => 1000003,
			'subject_id' => 1000000,
			'value' => 3,
			'score' => 0,
		),
		array(
			'id' => 1000004,
			'item_id' => 1000000,
			'subject_id' => 1000001,
			'value' => 3,
			'score' => 0,
		),
		array(
			'id' => 1000005,
			'item_id' => 1000001,
			'subject_id' => 1000001,
			'value' => 3,
			'score' => 0,
		),
		array(
			'id' => 1000006,
			'item_id' => 1000002,
			'subject_id' => 1000001,
			'value' => 3,
			'score' => 0,
		),
		array(
			'id' => 1000007,
			'item_id' => 1000003,
			'subject_id' => 1000001,
			'value' => 3,
			'score' => 0,
		),
	);

}
