<?php
App::uses('Exam', 'Model');

/**
 * TestExam
 *
 */
class TestExam extends Exam {

/**
 * Table name
 *
 * @var string
 */
	public $useTable = 'exams';

/**
 * Convenience method for testing protected method
 *
 * @param array $header Column headers of Teleform mapping file
 * @param integer $version Requested veersion
 * @return mixed Integer with the column index, or false on failure or requested version not found
 */
	public function getIndexOfVersionFromTeleformHeader($header, $version) {
		return self::_getIndexOfVersionFromTeleformHeader($header, $version);
	}

}

/**
 * Exam Test Case
 *
 */
class ExamTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.exam');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Exam = ClassRegistry::init('TestExam');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Exam);

		parent::tearDown();
	}

/**
 * testGetIndexOfVersionFromTeleformHeader method
 *
 * @return void
 */
	public function testGetIndexOfVersionFromTeleformHeader() {
		//
		// Header doesn't contain requested versions
		//

		$header = array(
			'Invalid Header 1',
			'Invalid Header 2'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = false;
		$this->assertIdentical($result, $expected);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = false;
		$this->assertIdentical($result, $expected);

		//
		// Header does contain requested versions
		//

		$header = array(
			'Versie.1',
			'Versie.2'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = 0;
		$this->assertIdentical($result, $expected);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = 1;
		$this->assertIdentical($result, $expected);

		$header = array(
			'Versie.2',
			'Versie.1'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = 1;
		$this->assertIdentical($result, $expected);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = 0;
		$this->assertIdentical($result, $expected);

		//
		// case insensitive
		//

		$header = array(
			'veRSie.1',
			'vErsIe.2'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = 0;
		$this->assertIdentical($result, $expected);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = 1;
		$this->assertIdentical($result, $expected);

		//
		// Seperator whitespace instead of dot and case insensitive
		//

		$header = array(
			'veRSie 1',
			'vErsIe 2'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = 0;
		$this->assertIdentical($result, $expected);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = 1;
		$this->assertIdentical($result, $expected);
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testRemove method
 *
 * @return void
 */
	public function testRemove() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testScheduleAnalyse method
 *
 * @return void
 */
	public function testScheduleAnalyse() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testAnalyse method
 *
 * @return void
 */
	public function testAnalyse() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testScheduleReport method
 *
 * @return void
 */
	public function testScheduleReport() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testReport method
 *
 * @return void
 */
	public function testReport() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testImport method
 *
 * @return void
 */
	public function testImport() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testImportBlackboard method
 *
 * @return void
 */
	public function testImportBlackboard() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testImportQMP method
 *
 * @return void
 */
	public function testImportQMP() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testImportTeleform method
 *
 * @return void
 */
	public function testImportTeleform() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testStevie method
 *
 * @return void
 */
	public function testStevie() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testScheduleReanalyse method
 *
 * @return void
 */
	public function testScheduleReanalyse() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testReanalyse method
 *
 * @return void
 */
	public function testReanalyse() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testScores method
 *
 * @return void
 */
	public function testScores() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

/**
 * testMissings method
 *
 * @return void
 */
	public function testMissings() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

}
