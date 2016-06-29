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

/**
 * Public test double of `parent::_extractTeleformMappingfile`.
 *
 */
	public function extractTeleformMappingfile($filename) {
		return parent::_extractTeleformMappingfile($filename);
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
	public $fixtures = array('app.answer_option', 'app.category', 'app.exam', 'app.given_answer', 'app.item', 'app.subject');

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
		$this->skipIf(!file_exists(APP . 'Config' . DS . 'rserve.php'), 'Rserve configuration file is NOT present.');

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
		$this->loadFixtures('AnswerOption', 'Exam', 'GivenAnswer', 'Item', 'Subject');

		$id = 1;
		$result = $this->Exam->analyse($id);
		$this->assertTrue((bool)$result);
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
		$this->loadFixtures('AnswerOption', 'Category', 'Exam', 'GivenAnswer', 'Item', 'Subject');

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
		$this->loadFixtures('AnswerOption', 'Category', 'Exam', 'GivenAnswer', 'Item', 'Subject');

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

/**
 * testExtractTeleformMappingfile method
 *
 * @return void
 */
	public function testExtractTeleformMappingfile() {
		$files = array(
			'Teleform-mappingfile.csv',
			'Teleform-mappingfile-with-spaces-in-version-column-headers.csv',
			'Teleform-mappingfile-with-upper-and-lower-case-in-version-column-headers.csv'
		);

		$expectedVersionMapping = array(
			2 => array(
				1 => 2,
				2 => 1
			)
		);
		$expectedAnswerOptionCount = array(
			1 => 3,
			2 => 3
		);

		foreach ($files as $file) {
			$filename = APP . DS . 'Test' . DS . 'File' . DS . 'Exam' . DS . $file;
			$result = $this->Exam->extractTeleformMappingfile($filename);
			$this->assertTrue((bool)$result);
			list($versionMapping, $answerOptionCount, $categories) = $result;
			$this->assertEquals($expectedVersionMapping, $versionMapping);
			$this->assertEquals($expectedAnswerOptionCount, $answerOptionCount);
			$this->assertEmpty($categories);
		}
	}

/**
 * testExtractTeleformMappingfileWithCategory method
 *
 * @return void
 */
	public function testExtractTeleformMappingfileWithCategory() {
		$expectedVersionMapping = array(
			2 => array(
				1 => 2,
				2 => 1
			)
		);
		$expectedAnswerOptionCount = array(
			1 => 3,
			2 => 3
		);

		$expectedCategories = array(
			1 => 'M',
			2 => 'G'
		);

		$filename = APP . DS . 'Test' . DS . 'File' . DS . 'Exam' . DS . 'Teleform-mappingfile-with-category.csv';
		$result = $this->Exam->extractTeleformMappingfile($filename);
		$this->assertTrue((bool)$result);
		list($versionMapping, $answerOptionCount, $categories) = $result;
		$this->assertEquals($expectedVersionMapping, $versionMapping);
		$this->assertEquals($expectedAnswerOptionCount, $answerOptionCount);
		$this->assertEquals($expectedCategories, $categories);
	}

/**
 * testExtractTeleformMappingfileWithCategoryWithoutAnswerOptionCount method
 *
 * @return void
 */
	public function testExtractTeleformMappingfileWithCategoryWithoutAnswerOptionCount() {
		$expectedVersionMapping = array(
			2 => array(
				1 => 2,
				2 => 1
			)
		);

		$expectedCategories = array(
			1 => 'M',
			2 => 'G'
		);

		$filename = APP . DS . 'Test' . DS . 'File' . DS . 'Exam' . DS . 'Teleform-mappingfile-with-categories-without-answer-option-count.csv';
		$result = $this->Exam->extractTeleformMappingfile($filename);
		$this->assertTrue((bool)$result);
		list($versionMapping, $answerOptionCount, $categories) = $result;
		$this->assertEquals($expectedVersionMapping, $versionMapping);
		$this->assertEmpty($answerOptionCount);
		$this->assertEquals($expectedCategories, $categories);
	}

}
