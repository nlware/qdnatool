<?php
App::uses('QuestionFormat', 'Model');

/**
 * QuestionFormatFixture
 *
 */
class QuestionFormatFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'name' => QuestionFormat::TRUE_FALSE
		),
		array(
			'id' => '2',
			'name' => QuestionFormat::MULTIPLE_CHOICE
		),
		array(
			'id' => '3',
			'name' => QuestionFormat::MULTIPLE_RESPONSE
		),
		array(
			'id' => '4',
			'name' => QuestionFormat::OPEN_ANSWER
		),
	);

}
