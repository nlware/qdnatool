<?php
App::uses('QuestionFormat', 'Model');

/**
 * QuestionFormat Fixture
 */
class QuestionFormatFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'question_info_diverge_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'question_info_converge_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'id' => QuestionFormat::TRUE_FALSE,
			'name' => 'T/F',
			'question_info_diverge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/dive-t-f.htm',
			'question_info_converge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/conv-t-f.htm'
		),
		array(
			'id' => QuestionFormat::MULTIPLE_CHOICE,
			'name' => 'mc',
			'question_info_diverge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/dive-mcq.htm',
			'question_info_converge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/conv-mcq.htm'
		),
		array(
			'id' => QuestionFormat::MULTIPLE_RESPONSE,
			'name' => 'mr',
			'question_info_diverge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/dive-mrq.htm',
			'question_info_converge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/conv-mrq.htm'
		),
		array(
			'id' => QuestionFormat::OPEN_ANSWER,
			'name' => 'open',
			'question_info_diverge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/pro-con-general.htm',
			'question_info_converge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/pro-con-general.htm'
		),
	);

}
