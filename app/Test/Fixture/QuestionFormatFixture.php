<?php
App::uses('AppFixture', 'Test/Fixture');
App::uses('QuestionFormat', 'Model');

/**
 * QuestionFormat Fixture
 *
 */
class QuestionFormatFixture extends AppFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'question_info_diverge_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'question_info_converge_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
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
			'id' => 1,
			'name' => QuestionFormat::TRUE_FALSE,
			'question_info_diverge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/dive-t-f.htm',
			'question_info_converge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/conv-t-f.htm'
		),
		array(
			'id' => 2,
			'name' => QuestionFormat::MULTIPLE_CHOICE,
			'question_info_diverge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/dive-mcq.htm',
			'question_info_converge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/conv-mcq.htm'
		),
		array(
			'id' => 3,
			'name' => QuestionFormat::MULTIPLE_RESPONSE,
			'question_info_diverge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/dive-mrq.htm',
			'question_info_converge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/conv-mrq.htm'
		),
		array(
			'id' => 4,
			'name' => QuestionFormat::OPEN_ANSWER,
			'question_info_diverge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/pro-con-general.htm',
			'question_info_converge_url' => 'http://testdevelopment.nl/qdst/qdst-nl/pro-con-q-formats/pro-con-general.htm'
		),
	);

}
