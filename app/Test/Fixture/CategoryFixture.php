<?php
/**
 * Category Fixture
 */
class CategoryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'exam_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'cronbachs_alpha' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '4,3', 'unsigned' => false),
		'max_answer_option_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
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
			'exam_id' => 1,
			'name' => 'A',
			'cronbachs_alpha' => null,
			'max_answer_option_count' => null
		),
		array(
			'id' => 2,
			'exam_id' => 1,
			'name' => 'B',
			'cronbachs_alpha' => null,
			'max_answer_option_count' => null
		),
	);

}
