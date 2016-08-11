<?php
App::uses('Rserve', 'Lib');
App::uses('Folder', 'Utility');

/**
 * Rserve Test Case
 *
 */
class RserveTest extends CakeTestCase {

	private $__tmpPath = null;

	public function __construct() {
		parent::__construct();

		$this->__tmpPath = TMP . 'tests' . DS . 'Lib' . DS . 'Rserve';
	}

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		$this->skipIf(!file_exists(APP . 'Config' . DS . 'rserve.php'), 'Rserve configuration file is NOT present.');

		parent::setUp();

		$this->Rserve = new Rserve();

		$folder = new Folder();
		$folder->create($this->__tmpPath);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		unset($this->Rserve);

		$folder = new Folder();
		$folder->delete($this->__tmpPath);
	}

/**
 * testGetConfig method
 *
 * @return void
 */
	public function testGetConfig() {
		$this->markTestIncomplete('testGetConfig not implemented.');
	}

/**
 * testExecute method
 *
 * @return void
 */
	public function testExecute() {
		$expected = 'Hello world!';
		$script = 'x ="Hello world!"; x';
		$result = $this->Rserve->execute($script);
		$this->assertEquals($expected, $result);
	}

/**
 * testConnect method
 *
 * @return void
 */
	public function testConnect() {
		$result = $this->Rserve->connect();
		$this->assertTrue((bool)$result);
	}

/**
 * testExecuteRscriptsAnalyse1 method
 *
 * @return void
 */
	public function testExecuteRscriptsAnalyse1() {
		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
		$script[] = 'nvragen = 3;';
		$script[] = 'ndeel = 6;';
		$script[] = 'key = matrix( c( 1, 0, 0, 0, 1, 0, 0, 0, 1 ), nrow = 3, ncol = nvragen, byrow = FALSE );';
		$script[] = 'input_answers = matrix( c( 2, 1, 1, 3, 1, 2, 1, 1, 3, 2, 1, 1, 2, 3, 2, 1, 1, 3 ), nrow = ndeel, ncol = nvragen, byrow = TRUE );';
		$script[] = 'number_answeroptions = c( 3, 3, 3 );';
		$script[] = 'analyse( key, input_answers, number_answeroptions );';
		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertTrue((bool)$result);
		$this->assertInternalType('array', $result);
		$this->assertCount(8, $result);

		list($cronbach) = $result;

		$this->assertInternalType('float', $cronbach);
		$this->assertNotEquals('NAN', $cronbach);
	}

/**
 * testExecuteRscriptsAnalyse2 method
 *
 * @return void
 */
	public function testExecuteRscriptsAnalyse2() {
		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
		$script[] = 'nvragen = 3;';
		$script[] = 'ndeel = 2;';
		$script[] = 'key = matrix( c( 1, 0, 0, 1, 0, 0, 1, 0, 0 ), nrow = 3, ncol = nvragen, byrow = FALSE );';
		$script[] = 'input_answers = matrix( c( 1, 2, 3, 1, 2, 3 ), nrow = ndeel, ncol = nvragen, byrow = TRUE );';
		$script[] = 'number_answeroptions = c( 3, 3, 3 );';
		$script[] = 'analyse( key, input_answers, number_answeroptions );';
		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertTrue((bool)$result);
		$this->assertInternalType('array', $result);
		$this->assertCount(8, $result);

		list($cronbach) = $result;

		$this->assertInternalType('float', $cronbach);
		$this->assertNotEquals('NAN', $cronbach);
	}

/**
 * testExecuteRscriptsAnalyse3 method
 *
 * @return void
 */
	public function testExecuteRscriptsAnalyse3() {
		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
		$script[] = 'nvragen = 3;';
		$script[] = 'ndeel = 2;';
		$script[] = 'key = matrix( c( 1, 0, 0, 0, 1, 0, 0, 0, 1), nrow = 3, ncol = nvragen, byrow = FALSE );';
		$script[] = 'input_answers = matrix( c( 2, 1, 1, 3, 1, 2 ), nrow = ndeel, ncol = nvragen, byrow = TRUE );';
		$script[] = 'number_answeroptions = c( 3, 3, 3 );';
		$script[] = 'analyse( key, input_answers, number_answeroptions );';
		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertTrue((bool)$result);
		$this->assertInternalType('array', $result);
		$this->assertCount(8, $result);

		list($cronbach) = $result;

		$this->assertInternalType('float', $cronbach);
		$this->assertNotEquals('NAN', $cronbach);
	}

/**
 * testExecuteRscriptsAnalyseTooLittleItems method
 *
 * @return void
 */
	public function testExecuteRscriptsAnalyseTooLittleItems() {
		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
		$script[] = 'nvragen = 2;';
		$script[] = 'ndeel = 2;';
		$script[] = 'key = matrix( c( 1, 0, 0, 0, 1, 0 ), nrow = 3, ncol = nvragen, byrow = FALSE );';
		$script[] = 'input_answers = matrix( c( 2, 1, 1, 3 ), nrow = ndeel, ncol = nvragen, byrow = TRUE );';
		$script[] = 'number_answeroptions = c( 3, 3 );';
		$script[] = 'analyse( key, input_answers, number_answeroptions );';
		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertFalse($result);
	}

/**
 * testExecuteRscriptsAnalyseTooLittleItems method
 *
 * @return void
 */
	public function testExecuteRscriptsAnalyseTooLittleStudents() {
		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
		$script[] = 'nvragen = 3;';
		$script[] = 'ndeel = 1;';
		$script[] = 'key = matrix( c( 1, 0, 0, 0, 1, 0, 0, 0, 1 ), nrow = 3, ncol = nvragen, byrow = FALSE );';
		$script[] = 'input_answers = matrix( c( 2, 1, 1 ), nrow = ndeel, ncol = nvragen, byrow = TRUE );';
		$script[] = 'number_answeroptions = c( 3, 3, 3 );';
		$script[] = 'analyse( key, input_answers, number_answeroptions );';
		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertFalse($result);
	}

/**
 * testExecuteRscriptsReport method
 *
 * @return void
 */
	public function testExecuteRscriptsReport() {
		$filename = $this->__tmpPath . DS . 'testExecuteRscriptsReport.pdf';

		$this->assertFileNotExists($filename);

		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'report.R');
		$script[] = sprintf('file_name = "%s";', $filename);
		$script[] = 'number_students = 2;';
		$script[] = 'number_answeroptions = c( 3, 3, 3 );';
		$script[] = 'number_questions = 3;';
		$script[] = 'cronbach = 0.5;';
		$script[] = 'frequency_answer_options = matrix( c( 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0 ), nrow = 4, ncol = number_questions, byrow = FALSE );';
		$script[] = 'percentage_answer_options = matrix( c( 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0 ), nrow = 4, ncol = number_questions, byrow = FALSE );';
		$script[] = 'input_correct = matrix( c( 0, 0, 0, 0, 0, 0 ), nrow = number_students, ncol = number_questions, byrow = TRUE );';
		$script[] = 'key = matrix( c( 1, 0, 0, 1, 0, 0, 1, 0, 0 ), nrow = 3, ncol = number_questions, byrow = FALSE );';
		$script[] = 'correct_frequency = c( 0, 0, 0 );';
		$script[] = 'correct_percentage = c( 0, 0, 0 );';
		$script[] = 'corrected_item_tot_cor = 3;';
		$script[] = 'corrected_item_tot_cor_answ_option = matrix( c( 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0 ), nrow = 4, ncol = number_questions, byrow = FALSE );';
		$script[] = 'title = "Test Title";';
		$script[] = 'item_names = c( "Item 1", "Item 2", "Item 3" );';
		$script[] = 'report( file_name, number_students, number_answeroptions, number_questions, cronbach,
			frequency_answer_options, percentage_answer_options,
			input_correct, key, correct_frequency,
			correct_percentage, corrected_item_tot_cor,
			corrected_item_tot_cor_answ_option, title,
			item_names);';

		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertTrue((bool)$result);

		$this->assertFileExists($filename);
	}

}
