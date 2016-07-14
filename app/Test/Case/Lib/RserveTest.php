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
		$script[] = 'key = matrix(c(1,0,0,0,1,0,0,0,1), nrow = 3, ncol = nvragen, byrow = FALSE);';
		$script[] = 'input_answers = matrix(c(2,1,1,3,1,2,1,1,3,2,1,1,2,3,2,1,1,3), nrow = ndeel, ncol = nvragen, byrow = TRUE);';
		$script[] = 'number_answeroptions = c(3,3,3);';
		$script[] = 'Analyse(key, input_answers, number_answeroptions);';
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
		$script[] = 'key = matrix(c(1,0,0,1,0,0,1,0,0), nrow = 3, ncol = nvragen, byrow = FALSE);';
		$script[] = 'input_answers = matrix(c(1,2,3,1,2,3), nrow = ndeel, ncol = nvragen, byrow = TRUE);';
		$script[] = 'number_answeroptions = c(3,3,3);';
		$script[] = 'Analyse(key, input_answers, number_answeroptions);';
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
		$script[] = 'key = matrix(c(1,0,0,0,1,0,0,0,1), nrow = 3, ncol = nvragen, byrow = FALSE);';
		$script[] = 'input_answers = matrix(c(2,1,1,3,1,2), nrow = ndeel, ncol = nvragen, byrow = TRUE);';
		$script[] = 'number_answeroptions = c(3,3,3);';
		$script[] = 'Analyse(key, input_answers, number_answeroptions);';
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
 * testExecuteRscriptsAnalyseToLittleItems method
 *
 * @return void
 */
	public function testExecuteRscriptsAnalyseToLittleItems() {
		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
		$script[] = 'nvragen = 2;';
		$script[] = 'ndeel = 2;';
		$script[] = 'key = matrix(c(1,0,0,0,1,0), nrow = 3, ncol = nvragen, byrow = FALSE);';
		$script[] = 'input_answers = matrix(c(2,1,1,3), nrow = ndeel, ncol = nvragen, byrow = TRUE);';
		$script[] = 'number_answeroptions = c(3,3);';
		$script[] = 'Analyse(key, input_answers, number_answeroptions);';
		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertFalse($result);
	}

/**
 * testExecuteRscriptsAnalyseToLittleItems method
 *
 * @return void
 */
	public function testExecuteRscriptsAnalyseToLittleStudents() {
		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
		$script[] = 'nvragen = 3;';
		$script[] = 'ndeel = 1;';
		$script[] = 'key = matrix(c(1,0,0,0,1,0,0,0,1), nrow = 3, ncol = nvragen, byrow = FALSE);';
		$script[] = 'input_answers = matrix(c(2,1,1), nrow = ndeel, ncol = nvragen, byrow = TRUE);';
		$script[] = 'number_answeroptions = c(3,3,3);';
		$script[] = 'Analyse(key, input_answers, number_answeroptions);';
		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertFalse($result);
	}

	public function testExecuteRscriptsReport() {
		$filename = $this->__tmpPath . DS . 'testExecuteRscriptsReport.pdf';

		$this->assertFileNotExists($filename);

		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'report.R');
		$script[] = sprintf('file.name = "%s";', $filename);
		$script[] = 'number.students = 2;';
		$script[] = 'number.answeroptions = 3;';
		$script[] = 'number.questions = 3;';
		$script[] = 'cronbach = 0.5;';
		$script[] = 'frequency.answer.options = matrix(c(1,0,0,1,0,0,1,0,0,1,0,0), nrow = 4, ncol = number.questions, byrow = FALSE);';
		$script[] = 'percentage.answer.options = matrix(c(1,0,0,1,0,0,1,0,0,1,0,0), nrow = 4, ncol = number.questions, byrow = FALSE);';
		$script[] = 'input.correct = matrix(c(0,0,0,0,0,0), nrow = number.students, ncol = number.questions, byrow = TRUE);';
		$script[] = 'key = matrix(c(1,0,0,1,0,0,1,0,0), nrow = 3, ncol = number.questions, byrow = FALSE);';
		$script[] = 'correct.frequency = c(0,0,0);';
		$script[] = 'correct.percentage = c(0,0,0);';
		$script[] = 'corrected.item.tot.cor = 3;';
		$script[] = 'corrected.item.tot.cor.answ.option = 3;';
		$script[] = 'title = "Test Title";';
		$script[] = 'item.names = c("Item 1","Item 2","Item 3");';


		$script[] = 'GenerateReport(file.name, number.students, number.answeroptions, number.questions, cronbach,
			frequency.answer.options, percentage.answer.options,
			input.correct, key, correct.frequency,
			correct.percentage, corrected.item.tot.cor,
			corrected.item.tot.cor.answ.option, title,
			item.names);';

		$script = implode("\n", $script);
		$result = $this->Rserve->execute($script);
		$this->assertTrue((bool)$result);

		$this->assertFileExists($filename);
		unlink($filename);
	}

}
