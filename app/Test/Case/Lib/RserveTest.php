<?php
App::uses('Rserve', 'Lib');

/**
 * Rserve Test Case
 *
 */
class RserveTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		$this->skipIf(!file_exists(APP . 'Config' . DS . 'rserve.php'), 'Rserve configuration file is NOT present.');

		parent::setUp();
		$this->Rserve = new Rserve();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Rserve);
	}

/**
 * testGetConfig method
 *
 * @return void
 */
	public function testGetConfig() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
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

}
