<?php
App::uses('Exam', 'Model');

/**
 * TestExam
 *
 */
class TestExam extends Exam {

/**
 * Set alias for sql.
 *
 * @var string
 */
	public $alias = 'Exam';

/**
 * Use table.
 *
 * @var mixed False or table name
 */
	public $useTable = 'exams';

/**
 * Public test double of `parent::_getIndexOfVersionFromTeleformHeader`.
 *
 */
	public function getIndexOfVersionFromTeleformHeader($header, $version) {
		return parent::_getIndexOfVersionFromTeleformHeader($header, $version);
	}

/**
 * Public test double of `parent::_executeAnalysis`.
 *
 */
	public function executeAnalysis($questionCount, $studentCount, $maxAnswerOptionCount, $exam, $givenAnswers, $answerOptionCount) {
		return parent::_executeAnalysis($questionCount, $studentCount, $maxAnswerOptionCount, $exam, $givenAnswers, $answerOptionCount);
	}

/**
 * Public test double of `parent::_duplicate`.
 *
 */
	public function duplicate($postData) {
		return parent::_duplicate($postData);
	}

}

/**
 * Exam Test Case
 *
 */
class ExamTest extends CakeTestCase {

/**
 * Auto fixtures.
 *
 * @var bool
 */
	public $autoFixtures = false;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.answer_option', 'app.exam', 'app.given_answer', 'app.item', 'app.subject');

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
 * testExecuteAnalysis method
 *
 * @return void
 */
	public function testExecuteAnalysis() {
		$questionCount = 3;
		$studentCount = 6;
		$maxAnswerOptionCount = 3;
		$exam = array(
			'Item' => array(
				array(
					'AnswerOption' => array(
						array('is_correct' => true)
					)
				),
				array(
					'AnswerOption' => array(
						array('is_correct' => true)
					)
				),
				array(
					'AnswerOption' => array(
						array('is_correct' => true)
					)
				)
			)
		);
		$givenAnswers = array(
			array(2, 1, 1),
			array(3, 1, 2),
			array(1, 1, 3),
			array(2, 1, 1),
			array(2, 3, 2),
			array(1, 1, 3),
		);
		$answerOptionCount = array(3, 3, 3);
		$result = $this->Exam->executeAnalysis($questionCount, $studentCount, $maxAnswerOptionCount, $exam, $givenAnswers, $answerOptionCount);
		$this->assertTrue((bool)$result);
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
		$this->assertSame($expected, $result);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = false;
		$this->assertSame($expected, $result);

		//
		// Header does contain requested versions
		//

		$header = array(
			'Versie.1',
			'Versie.2'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = 0;
		$this->assertSame($expected, $result);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = 1;
		$this->assertSame($expected, $result);

		$header = array(
			'Versie.2',
			'Versie.1'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = 1;
		$this->assertSame($expected, $result);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = 0;
		$this->assertSame($expected, $result);

		//
		// case insensitive
		//

		$header = array(
			'veRSie.1',
			'vErsIe.2'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = 0;
		$this->assertSame($expected, $result);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = 1;
		$this->assertSame($expected, $result);

		//
		// Seperator whitespace instead of dot and case insensitive
		//

		$header = array(
			'veRSie 1',
			'vErsIe 2'
		);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 1);
		$expected = 0;
		$this->assertSame($expected, $result);
		$result = $this->Exam->getIndexOfVersionFromTeleformHeader($header, 2);
		$expected = 1;
		$this->assertSame($expected, $result);
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

/**
 * testDuplicate method
 *
 * @return void
 */
	public function testDuplicate() {
		$this->_testDuplicateExamWithSubjectsWithNonUniqueIdentifiers();

		$this->_testDuplicateExamWithMissingGivenAnswers();
	}

	protected function _testDuplicateExamWithSubjectsWithNonUniqueIdentifiers() {
		$this->loadFixtures('AnswerOption', 'Exam', 'GivenAnswer', 'Item', 'Subject');

		$examId = 2;
		$postData = $this->_createPostDataForDuplicate($examId);
		$result = $this->Exam->duplicate($postData);
		$this->assertTrue((bool)$result);

		$contain = array('Subject' => 'GivenAnswer');

		$conditions = array('Exam.id' => $examId);
		$originalExam = $this->Exam->find('first', compact('conditions', 'contain'));
		$originalExam = $this->_stripIds($originalExam);

		$conditions = array('Exam.id' => $result);
		$duplicateExam = $this->Exam->find('first', compact('conditions', 'contain'));

		$this->assertNotNull($duplicateExam['Exam']['duplicated']);
		$duplicateExam['Exam']['duplicated'] = null;

		$duplicateExam = $this->_stripIds($duplicateExam);

		$this->assertEquals($originalExam, $duplicateExam);
	}

	protected function _testDuplicateExamWithMissingGivenAnswers() {
		$this->loadFixtures('AnswerOption', 'Exam', 'GivenAnswer', 'Item', 'Subject');

		$expected = 748;

		$examId = 747;
		$postData = $this->_createPostDataForDuplicate($examId);

		$result = $this->Exam->duplicate($postData);
		$this->assertEquals($expected, $result);
	}

	protected function _createPostDataForDuplicate($examId) {
		$conditions = array('Exam.id' => $examId);
		$contain = array('Item' => 'AnswerOption');
		$postData = $this->Exam->find('first', compact('conditions', 'contain'));

		$postData['Exam']['parent_id'] = $postData['Exam']['id'];
		unset($postData['Exam']['id']);
		$postData['Exam']['name'] = 'test';

		foreach ($postData['Item'] as $i => $item) {
			$postData['Item'][$i]['include'] = '1';
		}
		return $postData;
	}

	protected function _stripIds($data) {
		foreach ($data as $key => $value) {
			if (in_array($key, array('id', 'created', 'modified'), true) || (strpos($key, '_id') === (strlen($key) - 3))) {
				$data[$key] = null;
			} elseif (is_array($value)) {
				$data[$key] = $this->_stripIds($value);
			}
		}
		return $data;
	}

}
