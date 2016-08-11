<?php
define('EXAM_UPLOADS', TMP . 'uploads' . DS);
define('EXAM_REPORTS', ROOT . DS . 'data' . DS . 'reports' . DS);
App::uses('AuthComponent', 'Controller/Component');
App::uses('Rserve', 'Lib');
App::uses('CakeText', 'Utility');
App::uses('ClassRegistry', 'Utility');
App::uses('ExamFormat', 'Model');
App::uses('ExamState', 'Model');
App::uses('AppModel', 'Model');

/**
 * Exam Model
 *
 * @property User $User
 * @property Item $Item
 * @property Subject $Subject
 * @property ExamFormat $ExamFormat
 * @property ExamState $ExamState
 * @property Exam $Parent
 * @property Exam $Child
 */
class Exam extends AppModel {

/**
 * actsAs behaviors
 *
 * @var array
 */
	public $actsAs = array('I18n');

/**
 * Path to the (temporary) uploads directory.
 *
 * @var string
 */
	const UPLOADS = EXAM_UPLOADS;

/**
 * Path to the reports directory.
 *
 * @var string
 */
	const REPORTS = EXAM_REPORTS;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'This field cannot be left blank',
				'required' => 'create'
			),
		),
		'exam_format_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'This field cannot be left blank',
				'required' => 'create',
				'last' => true
			),
			'inList' => array(
				'rule' => array('inList', array(ExamFormat::TELEFORM, ExamFormat::BLACKBOARD, ExamFormat::QMP), false),
				'message' => 'Please supply a valid exam format'
			)
		),
		'data_file' => array(
			'extension' => array(
				'rule' => array(
					'extension', array('csv', 'txt')
				),
				'required' => 'create',
				'message' => 'Please supply a csv or txt file.',
				'last' => true
			),
			'fileSize' => array(
				'rule' => array('filesize', '<=', '8MB'),
				'message' => 'File must be less than 8 MB',
				'last' => true
			),
			'uploadError' => array(
				'rule' => 'uploadError',
				'message' => 'Something went wrong with the upload.'
			)
		),
		'mapping_file' => array(
			'extension' => array(
				'rule' => array(
					'extension', array('csv'),
				),
				'allowEmpty' => true,
				'message' => 'Please supply a csv file.',
				'last' => true
			),
			'fileSize' => array(
				'rule' => array('filesize', '<=', '1MB'),
				'allowEmpty' => true,
				'message' => 'File must be less than 1 MB',
				'last' => true
			),
			'uploadError' => array(
				'rule' => 'uploadError',
				'message' => 'Something went wrong with the upload.'
			)
		),
		'answer_option_count' => array(
			'naturalNumber' => array(
				'rule' => 'naturalNumber',
				'required' => 'create',
				'message' => 'Please supply the default number of answer options.',
				'last' => true
			),
			'comparison' => array(
				'rule' => array('comparison', '<=', 8),
				'message' => 'Allowed maximum is 8.'
			)
		),
		'user_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'This field cannot be left blank',
				'required' => 'create'
			),
			'numeric' => array(
				'rule' => 'numeric'
			)
		),
		'Item' => array(
			'multiple' => array(
				'rule' => array(
					'multiple', array(
						'min' => 1
					)
				),
				'message' => 'Include at least one item.'
			)
		)
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ExamFormat' => array(
			'className' => 'ExamFormat',
			'foreignKey' => 'exam_format_id'
		),
		'ExamState' => array(
			'className' => 'ExamState',
			'foreignKey' => 'exam_state_id'
		),
		'Parent' => array(
			'className' => 'Exam',
			'foreignKey' => 'parent_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Child' => array(
			'className' => 'Exam',
			'foreignKey' => 'parent_id',
			'dependent' => false
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'exam_id',
			'dependent' => true
		),
		'Subject' => array(
			'className' => 'Subject',
			'foreignKey' => 'exam_id',
			'dependent' => true
		)
	);

/**
 * beforeValidate method
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @see Model::beforeValidate()
 */
	public function beforeValidate($options = array()) {
		if (!$this->exists() && empty($this->data[$this->alias]['user_id']) && $userId = AuthComponent::user('id')) {
			$this->data[$this->alias]['user_id'] = $userId;
		}
		return true;
	}

/**
 * Returns (default) pagination settings for a given user.
 *
 * @param int $userId A user id
 * @var array Hash with pagination settings (conditions, contain and order)
 */
	public function getPaginationSettings($userId) {
		$conditions = array(
			'Exam.user_id' => $userId,
			'Exam.parent_id' => null,
			'Exam.deleted' => null
		);
		$contain = array(
			'Child' => array(
				'conditions' => array('Child.deleted' => null),
				'ExamState'
			),
			'ExamState'
		);
		$order = array('Exam.modified' => 'DESC');
		return compact('conditions', 'contain', 'order');
	}

/**
 * add method
 *
 * @param array $data Data
 * @return bool
 */
	public function add($data) {
		$result = false;

		if (!empty($data['Exam']['data_file']['error']) && $data['Exam']['data_file']['error'] == UPLOAD_ERR_NO_FILE) {
			unset($data['Exam']['data_file']);
		}
		if (!empty($data['Exam']['mapping_file']['error']) && $data['Exam']['mapping_file']['error'] == UPLOAD_ERR_NO_FILE) {
			unset($data['Exam']['mapping_file']);
		}

		$this->set($data);
		if ($this->validates()) {
			if (!empty($data['Exam']['data_file']['tmp_name'])) {
				$data['Exam']['data_filename'] = CakeText::uuid();

				//TODO: check for copy failures
				rename($data['Exam']['data_file']['tmp_name'], Exam::UPLOADS . $data['Exam']['data_filename']);
				$data['Exam']['data_file']['tmp_name'] = Exam::UPLOADS . $data['Exam']['data_filename'];
			}
			if (!empty($data['Exam']['mapping_file']['tmp_name'])) {
				$data['Exam']['mapping_filename'] = CakeText::uuid();

				//TODO: check for copy failures
				rename($data['Exam']['mapping_file']['tmp_name'], Exam::UPLOADS . $data['Exam']['mapping_filename']);
				$data['Exam']['mapping_file']['tmp_name'] = Exam::UPLOADS . $data['Exam']['mapping_filename'];
			}

			$data['Exam']['exam_state_id'] = ExamState::UPLOADED;
			$data['Exam']['uploaded'] = date('Y-m-d H:i:s');

			$this->create();
			if ($result = $this->save($data)) {
				$jobData = array('Exam' => array('id' => $this->id));
				$QueuedTaskModel = ClassRegistry::init('QueuedTask');
				if ($result = $QueuedTaskModel->createJob('ImportExam', $jobData)) {
					$this->id = $this->getLastInsertID();
					$this->saveField('exam_state_id', ExamState::WAITING_TO_IMPORT);
				}
			} else {
				if (!empty($data['Exam']['data_filename']) && file_exists(Exam::UPLOADS . $data['Exam']['data_filename'])) {
					unlink(Exam::UPLOADS . $data['Exam']['data_filename']);
				}
				if (!empty($data['Exam']['mapping_filename']) && file_exists(Exam::UPLOADS . $data['Exam']['mapping_filename'])) {
					unlink(Exam::UPLOADS . $data['Exam']['mapping_filename']);
				}
			}
		}

		return $result;
	}

/**
 * remove method
 *
 * @param int $id An exam id
 * @return bool
 */
	public function remove($id) {
		$result = false;

		$conditions = array(
			'Exam.id' => $id,
			'Exam.user_id' => AuthComponent::user('id')
		);
		$contain = array(
			'Child' => array(
				'conditions' => array('Child.deleted' => null)
			)
		);
		$exam = $this->find('first', compact('conditions', 'contain'));
		if (!empty($exam) && empty($exam['Child'])) {
			$result = $this->saveField('deleted', date('Y-m-d H:i:s'));
		}
		return $result;
	}

/**
 * Decode a line
 *
 * @param string $line Line
 * @param bool $firstLine Whether this is the first line
 * @return string
 */
	protected function _decodeLine($line, $firstLine = false) {
		if ($firstLine) {
			$this->__encoding = null;
			if (substr($line, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
				$this->__encoding = 'UTF-8';
				$line = substr($line, 3);
			} elseif (substr($line, 0, 2) == pack("CC", 0xfe, 0xff)) {
				$this->__encoding = 'UTF-16';
				$line = substr($line, 2);
			} elseif (substr($line, 0, 2) == pack("CC", 0xff, 0xfe)) {
				$this->__encoding = 'UTF-16LE';
				$line = substr($line, 2);
			}
		}

		$line = ltrim($line);

		if (!empty($this->__encoding)) {
			$line = mb_convert_encoding($line, 'UTF-8', $this->__encoding);
		}

		return $line;
	}

/**
 * scheduleAnalyse
 *
 * @param int $id An exam id
 * @return bool
 */
	public function scheduleAnalyse($id) {
		$this->id = $id;
		if ($result = $this->saveField('exam_state_id', ExamState::WAITING_TO_ANALYSE)) {
			$data = array('Exam' => array('id' => $this->id));
			$QueuedTaskModel = ClassRegistry::init('QueuedTask');
			$result = $QueuedTaskModel->createJob('AnalyseExam', $data);
		}

		return $result;
	}

/**
 * analyse
 *
 * @param int $id An exam id
 * @return bool
 */
	public function analyse($id) {
		$conditions = array('Exam.id' => $id);
		$exam = $this->find('first', compact('conditions'));
		if (!empty($exam)) {
			return $this->_analyse($exam);
		}
		return false;
	}

/**
 * _analyse
 *
 * @param array $exam Exam data
 * @return bool
 */
	protected function _analyse($exam) {
		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::ANALYSING);

		$conditions = array('Exam.id' => $exam['Exam']['id']);
		$contain = array(
			'Item' => 'AnswerOption',
			'Subject' => 'GivenAnswer'
		);
		$exam = $this->find('first', compact('conditions', 'contain'));
		$fields = array('MAX(Item.answer_option_count) as answer_option_count');
		$conditions = array('Item.exam_id' => $exam['Exam']['id']);
		$maxAnswerOptionCount = $this->Item->find('first', compact('fields', 'conditions'));
		$maxAnswerOptionCount = $maxAnswerOptionCount[0]['answer_option_count'];

		$questionCount = count($exam['Item']);
		$studentCount = count($exam['Subject']);

		$answerOptionCount = array();
		for ($i = 0; $i < $questionCount; $i++) {
			$answerOptionCount[$i] = $exam['Item'][$i]['answer_option_count'];
		}

		$givenAnswers = array();
		for ($i = 0; $i < $studentCount; $i++) {
			$givenAnswerCount = count($exam['Subject'][$i]['GivenAnswer']);
			for ($j = 0; $j < $givenAnswerCount; $j++) {
				$givenAnswers[$i][$j] = $exam['Subject'][$i]['GivenAnswer'][$j]['value'];
			}
		}

		$result = $this->_executeAnalysis($questionCount, $studentCount, $maxAnswerOptionCount, $exam, $givenAnswers, $answerOptionCount);

		if ($result) {
			$cronbachsAlpha = $result[0];
			$maxAnswerOptionCount = $result[1];
			$correctAnswerCount = $result[2];
			$correctAnswerPercentage = $result[3];
			$correctAnswerIrc = $result[4];
			$givenAnswerOptionCount = $result[5];
			$givenAnswerOptionPercentage = $result[6];
			$givenAnswerOptionIrc = $result[7];

			$data = array(
				'Exam' => array(
					'id' => $exam['Exam']['id'],
					'exam_state_id' => ExamState::ANALYSED,
					'cronbachs_alpha' => $cronbachsAlpha,
					'max_answer_option_count' => $maxAnswerOptionCount,
					'analysed' => date('Y-m-d H:i:s')
				)
			);

			foreach ($exam['Item'] as $i => $item) {
				$data['Item'][$i] = array(
					'id' => $item['id'],
					'correct_answer_count' => $correctAnswerCount[$i],
					'correct_answer_percentage' => $correctAnswerPercentage[$i],
					'correct_answer_irc' => $correctAnswerIrc[$i]
				);
				$data['Item'][$i]['missing_answer_count'] = $givenAnswerOptionCount[$i * ($maxAnswerOptionCount + 1)];
				$data['Item'][$i]['missing_answer_percentage'] = $givenAnswerOptionPercentage[$i * ($maxAnswerOptionCount + 1)];

				for ($j = 0; !empty($item['answer_option_count']) && $j < $item['answer_option_count']; $j++) {
					if (empty($item['AnswerOption'][$j]['id'])) {
						$data['Item'][$i]['AnswerOption'][$j]['order'] = ($j + 1);
					} else {
						$data['Item'][$i]['AnswerOption'][$j]['id'] = $item['AnswerOption'][$j]['id'];
					}
					$data['Item'][$i]['AnswerOption'][$j]['given_answer_count'] = $givenAnswerOptionCount[$i * ($maxAnswerOptionCount + 1) + $j + 1];
					$data['Item'][$i]['AnswerOption'][$j]['given_answer_percentage'] = $givenAnswerOptionPercentage[$i * ($maxAnswerOptionCount + 1) + $j + 1];
					$data['Item'][$i]['AnswerOption'][$j]['given_answer_irc'] = (is_nan($givenAnswerOptionIrc[$i * ($maxAnswerOptionCount + 1) + $j + 1])?null:$givenAnswerOptionIrc[$i * ($maxAnswerOptionCount + 1) + $j + 1]);
				}
			}
			$this->id = $exam['Exam']['id'];
			$result = $this->saveAll($data, array('deep' => true));
		}

		if ($result) {
			$this->scheduleReport($exam['Exam']['id']);
		} else {
			$this->id = $exam['Exam']['id'];
			$this->saveField('exam_state_id', ExamState::ANALYSE_FAILED);
		}

		return $result;
	}

/**
 * scheduleReport
 *
 * @param int $id An exam id
 * @return bool
 */
	public function scheduleReport($id) {
		$this->id = $id;
		if ($result = $this->saveField('exam_state_id', ExamState::WAITING_TO_GENERATE_REPORT)) {
			$data = array('Exam' => array('id' => $this->id));
			$QueuedTaskModel = ClassRegistry::init('QueuedTask');
			$result = $QueuedTaskModel->createJob('AnalysisToReport', $data);
		}
		return $result;
	}

/**
 * report
 *
 * @param int $id An exam id
 * @return bool
 */
	public function report($id) {
		$conditions = array('Exam.id' => $id);
		$exam = $this->find('first', compact('conditions'));
		if (!empty($exam)) {
			return $this->_report($exam);
		}
		return false;
	}

/**
 * _report
 *
 * @param array $exam Exam data
 * @return bool
 */
	protected function _report($exam) {
		$result = true;

		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::GENERATING_REPORT);

		$conditions = array('Exam.id' => $exam['Exam']['id']);
		$contain = array(
			'Item' => 'AnswerOption',
			'Subject' => 'GivenAnswer'
		);
		$exam = $this->find('first', compact('conditions', 'contain'));

		// create temp file
		umask(0);
		if ($tempFile = tempnam(sys_get_temp_dir(), "report")) {
			chmod($tempFile, 0777);

			$answerOptionCount = Hash::extract($exam, 'Item.{n}.answer_option_count');
			$correctAnswerCount = Hash::extract($exam, 'Item.{n}.correct_answer_count');
			$correctAnswerPercentage = Hash::extract($exam, 'Item.{n}.correct_answer_percentage');
			$correctAnswerIRC = Hash::extract($exam, 'Item.{n}.correct_answer_irc');

			$script = array();
			$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'report.R');
			$script[] = sprintf('number_students = %d;', count($exam['Subject']));
			$script[] = sprintf('number_answeroptions = c(%s);', implode(',', $answerOptionCount));
			$script[] = sprintf('max_number_answeroptions = %s;', $exam['Exam']['max_answer_option_count']);
			$script[] = sprintf('number_questions = %d;', count($exam['Item']));
			$script[] = sprintf('Cronbach = %s;', $exam['Exam']['cronbachs_alpha']);
			$script[] = sprintf('correct_frequency = c(%s);', implode(',', $correctAnswerCount));
			$script[] = sprintf('correct_percentage = c(%s);', implode(',', $correctAnswerPercentage));
			$script[] = sprintf('corrected_item_tot_cor = c(%s);', implode(',', $correctAnswerIRC));

			$frequencyAnswerOptionsMatrix = array();
			$percentageAnswerOptionsMatrix = array();
			$correctedItemTotCorAnswOptionMatrix = array();
			$itemNamesVector = array();

			foreach ($exam['Item'] as $i => $item) {
				$frequencyAnswerOptionsMatrix[] = $item['missing_answer_count'];
				$percentageAnswerOptionsMatrix[] = $item['missing_answer_percentage'];
				$correctedItemTotCorAnswOptionMatrix[] = 0;
				$itemNamesVector[] = $item['order'];

				foreach ($item['AnswerOption'] as $j => $answerOption) {
					$frequencyAnswerOptionsMatrix[] = $answerOption['given_answer_count'];
					$percentageAnswerOptionsMatrix[] = $answerOption['given_answer_percentage'];
					if (empty($answerOption['given_answer_irc'])) {
						$correctedItemTotCorAnswOptionMatrix[] = '0';
					} else {
						$correctedItemTotCorAnswOptionMatrix[] = $answerOption['given_answer_irc'];
					}
				}
			}

			// Create the frequency_answer_options matrix (with given dimensions)
			// by filling it with a vector (by column)
			$script[] = sprintf(
				'frequency_answer_options = matrix( c( %s ), max_number_answeroptions + 1, number_questions, byrow = FALSE );',
				implode(',', $frequencyAnswerOptionsMatrix)
			);
			// Create the percentage_answer_options matrix (with given dimensions)
			// by filling it with a vector (by column)
			$script[] = sprintf(
				'percentage_answer_options = matrix( c( %s ), max_number_answeroptions + 1, number_questions, byrow = FALSE );',
				implode(',', $percentageAnswerOptionsMatrix)
			);
			// Create the corrected_item_tot_cor_answ_option matrix (with given dimensions)
			// by filling it with a vector (by column)
			$script[] = sprintf(
				'corrected_item_tot_cor_answ_option = matrix( c( %s ), max_number_answeroptions + 1, number_questions, byrow = FALSE );',
				implode(',', $correctedItemTotCorAnswOptionMatrix)
			);
			$script[] = sprintf('item_names = c( %s );', implode(',', $itemNamesVector));

			$inputCorrectMatrix = array();

			foreach ($exam['Subject'] as $i => $subject) {
				foreach ($subject['GivenAnswer'] as $j => $givenAnswer) {
					if (empty($givenAnswer['score'])) {
						$inputCorrectMatrix[] = '0';
					} else {
						$inputCorrectMatrix[] = $givenAnswer['score'];
					}
				}
			}

			// Create the input_correct matrix (with given dimensions) by filling it with a vector (by row)
			$script[] = sprintf(
				'input_correct = matrix( c( %s ), number_students, number_questions, byrow = TRUE );',
				implode(',', $inputCorrectMatrix)
			);

			$keyMatrix = array();

			foreach ($exam['Item'] as $i => $item) {
				foreach ($item['AnswerOption'] as $j => $answerOption) {
					if ($answerOption['is_correct']) {
						$keyMatrix[] = 1;
					} else {
						$keyMatrix[] = 0;
					}
				}
			}

			// Create the key matrix (with given dimensions) by filling it with a vector (by column)
			$script[] = sprintf(
				'key = matrix( c( %s ), max_number_answeroptions, number_questions, byrow = FALSE );',
				implode(',', $keyMatrix)
			);

			$script[] = sprintf(
				'report( ' .
				'"%s", number_students, number_answeroptions, number_questions, Cronbach, frequency_answer_options, ' .
				'percentage_answer_options, input_correct, key, correct_frequency, correct_percentage, ' .
				'corrected_item_tot_cor, corrected_item_tot_cor_answ_option, "%s", item_names' .
				' );',
				$tempFile, $exam['Exam']['name']
			);

			$script = implode("\n", $script);

			$result = Rserve::execute($script);

			if ($result && file_exists($tempFile)) {
				rename($tempFile, Exam::REPORTS . $exam['Exam']['id'] . '.pdf');
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}

		if ($result) {
			$data = array(
				'Exam' => array(
					'id' => $exam['Exam']['id'],
					'exam_state_id' => ExamState::REPORT_GENERATED,
					'report_generated' => date('Y-m-d H:i:s')
				)
			);

			$this->id = $exam['Exam']['id'];
			$result = $this->save($data);
		} else {
			$this->id = $exam['Exam']['id'];
			$this->saveField('exam_state_id', ExamState::REPORT_FAILED);
		}
		return $result;
	}

/**
 * import method
 *
 * @param int $id An exam id
 * @return bool
 */
	public function import($id) {
		$success = false;
		$conditions = array('Exam.id' => $id);
		$exam = $this->find('first', compact('conditions'));
		if (!empty($exam['Exam']['exam_format_id'])) {
			switch ($exam['Exam']['exam_format_id']) {
				case ExamFormat::BLACKBOARD:
					$success = $this->importBlackboard($exam);
					break;
				case ExamFormat::TELEFORM:
					$success = $this->importTeleform($exam);
					break;
				case ExamFormat::QMP:
					$success = $this->importQMP($exam);
					break;
				default:
					break;
			}
		}

		if ($success) {
			$this->scheduleAnalyse($id);
		}
		return $success;
	}

/**
 * _parseCsvFile
 *
 * @param string $filename Filename
 * @param string[optional] $delimiter Set the field delimiter (one character only).
 * @param string[optional] $enclosure Set the field enclosure character (one character only).
 * @param string[optional] $escape Set the escape character (one character only).
 * @return array
 */
	protected function _parseCsvFile($filename, $delimiter = ',', $enclosure = '"', $escape = '\\') {
		$result = array();
		ini_set('auto_detect_line_endings', true);
		if (($handle = fopen($filename, "r")) !== false) {
			for ($i = 0; !feof($handle); $i++) {
				$line = fgets($handle);
				$line = $this->_decodeLine($line, $i == 0);
				if (!empty($line)) {
					$row = str_getcsv($line, $delimiter, $enclosure, $escape);
					if (!empty($row)) {
						$result[$i] = $row;
					}
				}
			}
			fclose($handle);
		}
		return $result;
	}

/**
 * importBlackboard
 *
 * @param array $exam Exam data
 * @return bool
 */
	public function importBlackboard($exam) {
		$result = true;
		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::IMPORTING);

		$filename = Exam::UPLOADS . $exam['Exam']['data_filename'];

		$csv = $this->_parseCsvFile($filename);
		//TODO: validate csv
		if (!empty($csv)) {
			foreach ($csv as $i => $row) {
				if ($i == 0) {
					// first line contains column headings
					$headings = $row;

					$data = array();
					$answerIndex = 0;
					foreach ($headings as $heading) {
						if ((strlen($heading) > strlen('Question ID ')) && (substr($heading, 0, strlen('Question ID ')) == 'Question ID ')) {
							$item = array(
								'exam_id' => $exam['Exam']['id'],
								'order' => $answerIndex + 1,
								'value' => substr($heading, strlen('Question ID ')),
								'answer_option_count' => $exam['Exam']['answer_option_count']
							);

							for ($j = 0; $j < $item['answer_option_count']; $j++) {
								$item['AnswerOption'][] = array('order' => $j + 1);
							}

							$data[] = $item;
							$answerIndex++;
						}
					}

					$this->id = $exam['Exam']['id'];
					if ($result &= $this->Item->saveAll($data, array('deep' => true))) {
						$conditions = array('Exam.id' => $exam['Exam']['id']);
						$contain = array('Item' => 'AnswerOption');
						$exam = $this->find('first', compact('conditions', 'contain'));
					} else {
						break;
					}
					$data = array();
				} else {
					$values = $row;

					// check for empty last line
					if (empty($values[0])) {
						break;
					}

					if (($index = array_search('Username', $headings)) !== false) {
						if (empty($values[$index])) {
							$result = false;
						} else {
							$data = array(
								'Subject' => array(
									'exam_id' => $exam['Exam']['id'],
									'value' => $values[$index]
								)
							);
						}

						if ($result) {
							foreach ($values as $j => $value) {
								if ((strlen($value) > strlen('Question ID ')) && (substr($value, 0, strlen('Question ID ')) == 'Question ID ')) {
									$questionId = substr($value, strlen('Question ID '));
									if (empty($values[$j + 1])) {
										$result = false;
									} else {
										$itemIds = Hash::extract($exam, 'Item.{n}[value=' . $questionId . '].id');
										if (!empty($itemIds[0])) {
											$value = null;
											$conditions = array(
												'GivenAnswer.item_id' => $itemIds[0],
												'GivenAnswer.content' => $values[$j + 1]
											);
											$givenAnswer = $this->Item->GivenAnswer->find('first', compact('conditions'));
											if (empty($givenAnswer)) {
												$conditions = array('GivenAnswer.item_id' => $itemIds[0]);
												$answerOptionCount = $this->Item->GivenAnswer->find('count', compact('conditions'));
												$value = $answerOptionCount + 1;
											} else {
												$value = $givenAnswer['GivenAnswer']['value'];
											}

											// calculate score
											$score = null;
											if (($possiblePointsIndex = array_search('Possible Points ' . $questionId, $headings)) !== false) {
												if (($manualScoreIndex = array_search('Manual Score ' . $questionId, $headings)) !== false) {
													if (is_numeric($values[$manualScoreIndex]) && is_numeric($values[$possiblePointsIndex])) {
														$score = $values[$manualScoreIndex] / $values[$possiblePointsIndex];
													}
												}

												if (empty($score)) {
													if (($autoScoreIndex = array_search('Auto Score ' . $questionId, $headings)) !== false) {
														if (is_numeric($values[$autoScoreIndex]) && is_numeric($values[$possiblePointsIndex])) {
															$score = $values[$autoScoreIndex] / $values[$possiblePointsIndex];
														}
													}
												}
											}

											if (!empty($score) && $score >= 1) {
												foreach ($exam['Item'] as $k => $item) {
													if ($item['id'] == $itemIds[0]) {
														if (!empty($item['AnswerOption'][$value - 1]) && !$item['AnswerOption'][$value - 1]['is_correct']) {
															$this->Item->AnswerOption->id = $item['AnswerOption'][$value - 1]['id'];
															if ($this->Item->AnswerOption->saveField('is_correct', true)) {
																$exam['Item'][$k]['AnswerOption'][$value - 1]['is_correct'] = true;
															}
														}
													}
												}
											}

											$data['GivenAnswer'][] = array(
												'item_id' => $itemIds[0],
												'value' => $value,
												'content' => $values[$j + 1],
												'score' => $score
											);
										} else {
											$result = false;
										}
									}
								}
							}
						}
					} else {
						$result = false;
					}

					if ($result) {
						$this->Subject->create();
						$result &= $this->Subject->saveAll($data);
					}
				}
			}
		} else {
			$result = false;
		}

		if ($result) {
			//TODO: clean up database: remove content from givenAnswers

			$this->Item->GivenAnswer->updateAll(
				array('GivenAnswer.content' => null),
				array('GivenAnswer.item_id' => Hash::extract($exam, 'Item.{n}.id'))
			);

			$data = array(
				'Exam' => array(
					'id' => $exam['Exam']['id'],
					'exam_state_id' => ExamState::IMPORTED,
					'imported' => date('Y-m-d H:i:s')
				)
			);
			$this->id = $exam['Exam']['id'];
			$this->save($data);

			if (!empty($filename) && file_exists($filename)) {
				unlink($filename);
			}
			if (!empty($versionMappingFilename) && file_exists($versionMappingFilename)) {
				unlink($versionMappingFilename);
			}
		} else {
			$this->id = $exam['Exam']['id'];
			$this->saveField('exam_state_id', ($result?ExamState::IMPORTED:ExamState::IMPORT_FAILED));
		}

		return $result;
	}

/**
 * _validateQMPData
 *
 * @param array $data QMP data
 * @return array
 */
	protected function _validateQMPData($data) {
		//TODO: validate QMP data
		return $data;
	}

/**
 * importQMP method
 *
 * @param array $exam Exam data
 * @return bool
 */
	public function importQMP($exam) {
		$result = true;
		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::IMPORTING);

		$filename = Exam::UPLOADS . $exam['Exam']['data_filename'];

		$values = $this->_parseCsvFile($filename, ';', '"', '"');
		if ($values) {
			$values = $this->_validateQMPData($values);
		}
		if ($values) {
			$headings = $values[0];
			$data = array();

			foreach ($values as $i => $row) {
				if ($i == 0) {
					// first line contains column headings
					continue;
				} else {
					if (($index = array_search('Deelnemersnaam', $headings)) !== false) {
						if (empty($row[$index])) {
							$result = false;
						} else {
							$data = array(
								'Subject' => array(
									'exam_id' => $exam['Exam']['id'],
									'value' => $row[$index]
								)
							);
						}

						if ($result) {
							$questionIndices = array_keys($headings, 'Vraagbeschrijving');
							foreach ($questionIndices as $questionIndex) {
								$itemId = null;
								$question = null;
								$givenAnswer = null;
								$score = 0;
								$maximumScore = 0;
								if (!empty($row[$questionIndex])) {
									$question = $row[$questionIndex];
								}
								if (!empty($row[$questionIndex + 1]) || (isset($row[$questionIndex + 1]) && is_numeric($row[$questionIndex + 1]))) {
									$givenAnswer = $row[$questionIndex + 1];
									if (strlen($givenAnswer) > 1) {
										$givenAnswer = substr($givenAnswer, 0, 1);
									}
									if (is_numeric($givenAnswer)) {
										$givenAnswer++;
									} else {
										$givenAnswer = null;
									}
								}
								if (!empty($row[$questionIndex + 2]) || (isset($row[$questionIndex + 2]) && is_numeric($row[$questionIndex + 2]))) {
									$score = $row[$questionIndex + 2];
								}
								if (!empty($row[$questionIndex + 3]) || (isset($row[$questionIndex + 3]) && is_numeric($row[$questionIndex + 3]))) {
									$maximumScore = $row[$questionIndex + 3];
								}
								if (!empty($question)) {
									$itemId = $this->Item->add($exam['Exam']['id'], $exam['Exam']['answer_option_count'], $question, $givenAnswer, $score, $maximumScore);
								}

								if (!empty($itemId)) {
									$normalizedScore = null;
									if (is_numeric($score) && is_numeric($maximumScore) && $maximumScore > 0) {
										$normalizedScore = $score / $maximumScore;
									}

									$data['GivenAnswer'][] = array(
										'item_id' => $itemId,
										'value' => $givenAnswer,
										'content' => null,
										'score' => $normalizedScore
									);
								}
							}
						}
					} else {
						$result = false;
					}

					if ($result) {
						$this->Subject->create();
						$result &= $this->Subject->saveAll($data);
					}
				}
			}
		} else {
			$result = false;
		}

		if ($result) {
			//TODO: clean up database: remove content from givenAnswers

			$this->Item->GivenAnswer->updateAll(
				array('GivenAnswer.content' => null),
				array('GivenAnswer.item_id' => Hash::extract($exam, 'Item.{n}.id'))
			);

			$data = array(
				'Exam' => array(
					'id' => $exam['Exam']['id'],
					'exam_state_id' => ExamState::IMPORTED,
					'imported' => date('Y-m-d H:i:s')
				)
			);
			$this->id = $exam['Exam']['id'];
			$this->save($data);

			if (!empty($filename) && file_exists($filename)) {
				unlink($filename);
			}
			if (!empty($versionMappingFilename) && file_exists($versionMappingFilename)) {
				unlink($versionMappingFilename);
			}
		} else {
			$this->id = $exam['Exam']['id'];
			$this->saveField('exam_state_id', ($result?ExamState::IMPORTED:ExamState::IMPORT_FAILED));
		}

		return $result;
	}

/**
 * Get column index of requested version in given header
 *
 * @param array $header Column headers of Teleform mapping file
 * @param int $version Requested veersion
 * @return mixed Integer with the column index, or false on failure or requested version not found
 */
	protected function _getIndexOfVersionFromTeleformHeader($header, $version) {
		$result = false;
		foreach ($header as $key => $value) {
			if (in_array(strtolower($value), array('versie.' . $version, 'versie ' . $version))) {
				$result = $key;
				break;
			}
		}
		return $result;
	}

/**
 * importTeleform method
 *
 * @param array $exam Teleform data
 * @return bool
 */
	public function importTeleform($exam) {
		$versionMapping = null;
		$versionMappingFilename = null;
		$answerOptionCount = array();

		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::IMPORTING);

		$filename = Exam::UPLOADS . $exam['Exam']['data_filename'];
		if (!empty($exam['Exam']['mapping_filename'])) {
			$versionMappingFilename = Exam::UPLOADS . $exam['Exam']['mapping_filename'];
		}

		if (!empty($versionMappingFilename)) {
			ini_set('auto_detect_line_endings', true);
			if (($handle = fopen($versionMappingFilename, "r")) !== false) {
				$version1Index = false;
				$version2Index = false;
				$answerOptionCountIndex = false;
				for ($i = 0; !feof($handle); $i++) {
					$line = fgets($handle);
					$line = $this->_decodeLine($line, $i == 0);

					if ($i == 0) {
						$header = str_getcsv($line, ';', '"', '"');

						$version1Index = $this->_getIndexOfVersionFromTeleformHeader($header, 1);
						$version2Index = $this->_getIndexOfVersionFromTeleformHeader($header, 2);
						$answerOptionCountIndex = array_search('Answer Option Count', $header);
					} else {
						$values = str_getcsv($line, ';', '"', '"');
						if (count($values) <= 1) {
							continue;
						}
						if ($version1Index !== false && $version2Index !== false) {
							$versionMapping[2][$values[$version1Index]] = intval($values[$version2Index]);
						}

						if ($version1Index !== false && $answerOptionCountIndex !== false) {
							$answerOptionCount[$values[$version1Index]] = intval($values[$answerOptionCountIndex]);
						}
					}
				}

				fclose($handle);
			}
		}

		$result = true;
		ini_set('auto_detect_line_endings', true);
		if ($result && ($handle = fopen($filename, "r")) !== false) {
			for ($i = 0; !feof($handle); $i++) {
				$skipLine = false;
				$line = fgets($handle);
				$line = $this->_decodeLine($line, $i == 0);

				if ($i == 0) {
					// first line contains correct answers for first version
					$header = str_getcsv($line, ',', '"', '"');

					$data = array();
					$count = count($header);
					for ($j = 2; $j < $count; $j++) {
						if ($header[$j] == 9) {
							break;
						}

						$secondVersionOrder = null;
						if (!empty($versionMapping[2][$j - 1])) {
							$secondVersionOrder = $versionMapping[2][$j - 1];
						}

						$item = array(
							'exam_id' => $exam['Exam']['id'],
							'order' => $j - 1,
							'second_version_order' => $secondVersionOrder,
							'value' => $j - 1
						);
						if (empty($answerOptionCount[$j - 1])) {
							$item['answer_option_count'] = $exam['Exam']['answer_option_count'];
						} else {
							$item['answer_option_count'] = $answerOptionCount[$j - 1];
						}

						for ($k = 0; $k < $item['answer_option_count']; $k++) {
							$item['AnswerOption'][] = array(
								'order' => $k + 1,
								'is_correct' => ($header[$j] == $k + 1)
							);
						}

						$data[] = $item;
					}

					$this->id = $exam['Exam']['id'];
					if ($result &= $this->Item->saveAll($data, array('deep' => true))) {
						$conditions = array('Exam.id' => $exam['Exam']['id']);
						$contain = array('Item' => 'AnswerOption');
						$exam = $this->find('first', compact('conditions', 'contain'));
					} else {
						break;
					}
					$data = array();
				} elseif ($i == 1) {
					// second line contains correct answers for second version
				} else {
					$values = str_getcsv($line, ',', '"', '"');

					// only add versions 1 and 2
					if (!empty($values[1]) && in_array($values[1], array(1, 2))) {
						$data = array(
							'Subject' => array(
								'exam_id' => $exam['Exam']['id'],
								'value' => $values[0],
								'is_second_version' => (($values[1] == 2)?1:0)
							)
						);
						$itemCount = count($exam['Item']);
						for ($j = 0; $j < $itemCount; $j++) {
							$index = $j + 1;
							if ($values[1] == 2) {
								if (empty($versionMapping[$values[1]][$index])) {
									$skipLine = true;
									break;
								} else {
									$index = $versionMapping[$values[1]][$index];
								}
							}
							$index++;

							$value = $values[$index];
							// missing value is 9
							if ($value === 0 || $value == 9) {
								$value = null;
							}

							$score = (!empty($value) && !empty($exam['Item'][$j]['AnswerOption'][$value - 1]['is_correct']) && $exam['Item'][$j]['AnswerOption'][$value - 1]['is_correct']);

							// convert bool to int
							$score = $score?1:0;

							$data['GivenAnswer'][] = array(
								'item_id' => $exam['Item'][$j]['id'],
								'value' => $value,
								'score' => $score
							);
						}
						if ($skipLine) {
							continue;
						}

						$this->Subject->create();
						$result &= $this->Subject->saveAll($data);
					}
				}
			}
			fclose($handle);
		} else {
			$result = false;
		}

		if ($result) {
			$data = array(
				'Exam' => array(
					'id' => $exam['Exam']['id'],
					'exam_state_id' => ExamState::IMPORTED,
					'imported' => date('Y-m-d H:i:s')
				)
			);
			$this->id = $exam['Exam']['id'];
			$this->save($data);

			if (!empty($filename) && file_exists($filename)) {
				unlink($filename);
			}
			if (!empty($versionMappingFilename) && file_exists($versionMappingFilename)) {
				unlink($versionMappingFilename);
			}
		} else {
			$this->id = $exam['Exam']['id'];
			$this->saveField('exam_state_id', ($result?ExamState::IMPORTED:ExamState::IMPORT_FAILED));
		}

		return $result;
	}

/**
 * stevie method
 *
 * @param int $id An exam id
 * @return array
 */
	public function stevie($id) {
		$conditions = array(
			'Exam.id' => $id,
			'Exam.user_id' => AuthComponent::user('id')
		);
		$contain = array('Item' => 'AnswerOption');
		$exam = $this->find('first', compact('conditions', 'contain'));

		if (!empty($exam['Item'])) {
			foreach ($exam['Item'] as $i => $item) {
				$exam['Item'][$i] = $this->Item->stevie($item, $exam['Exam']['answer_option_count']);
			}
		}

		return $exam;
	}

/**
 * scheduleReanalyse method
 *
 * @param array $data Exam data
 * @return bool
 */
	public function scheduleReanalyse($data) {
		$result = true;
		$this->validator()->remove('exam_format_id');
		$this->validator()->remove('data_file');
		$this->validator()->remove('answer_option_count');
		$this->validator()->remove('exam_format_id');
		if (!$this->saveAll($data, array('validate' => 'only'))) {
			$result = false;
		}

		if ($result) {
			$conditions = array(
				'Exam.id' => $data['Exam']['parent_id'],
				'Exam.user_id' => AuthComponent::user('id')
			);
			$exam = $this->find('first', compact('conditions'));
			if (empty($exam)) {
				$result = false;
			}
		}

		if ($result) {
			$QueuedTaskModel = ClassRegistry::init('QueuedTask');
			$result = $QueuedTaskModel->createJob('ReanalyseExam', $data);
		}
		return $result;
	}

/**
 * reanalyse method
 *
 * @param array $data Exam data
 * @return bool
 */
	public function reanalyse($data) {
		$result = true;
		if ($id = $this->_duplicate($data)) {
			$result = $this->scheduleAnalyse($id);
		} else {
			$result = false;
		}
		return $result;
	}

/**
 * Extract correct answer options from post data
 *
 * @param array $postData Post data
 * @return array Hash with item ids as key and indices of related correct answer options as value
 */
	protected function _extractCorrectAnswerOptions($postData) {
		$result = array();
		foreach ($postData['Item'] as $item) {
			$result[$item['id']] = array();
			foreach ($item['AnswerOption'] as $i => $answerOption) {
				if ($answerOption['is_correct']) {
					$result[$item['id']][] = $i;
				}
			}
		}
		return $result;
	}

/**
 * _duplicate method
 *
 * @param data $postData Exam data
 * @return int|bool The created exam id, false on failure
 */
	protected function _duplicate($postData) {
		$examId = false;

		$parentId = $postData['Exam']['parent_id'];
		$filteredItemIds = Hash::extract($postData, 'Item.{n}[include=1].id');
		$correctAnswerOptions = $this->_extractCorrectAnswerOptions($postData);

		$conditions = array('Exam.id' => $parentId);
		$parentExam = $this->find('first', compact('conditions'));

		if (!empty($parentExam)) {
			$data = array(
				'Exam' => array(
					'parent_id' => $parentExam['Exam']['id'],
					'name' => $postData['Exam']['name'],
					'exam_state_id' => ExamState::DUPLICATED,
					'user_id' => $parentExam['Exam']['user_id'],
					'exam_format_id' => $parentExam['Exam']['exam_format_id'],
					'answer_option_count' => $parentExam['Exam']['answer_option_count'],
					'duplicated' => date('Y-m-d H:i:s')
				)
			);

			$success = true;

			// Start transaction
			$this->begin();

			$this->validator()->remove('data_file', 'extension');
			$this->create();
			$success &= $this->save($data);

			$examId = $this->id;

			if ($success) {
				$itemMapping = $this->Item->duplicate(array($parentId => $examId), $filteredItemIds);
				if ($itemMapping === false) {
					$success = false;
				}
			}

			if ($success) {
				$answerOptionMapping = $this->Item->AnswerOption->duplicate($itemMapping, $correctAnswerOptions);
				if ($answerOptionMapping === false) {
					$success = false;
				}
			}

			if ($success) {
				$subjectMapping = $this->Subject->duplicate(array($parentId => $examId));
				if ($subjectMapping === false) {
					$success = false;
				}
			}

			if ($success) {
				$givenAnswerMapping = $this->Subject->GivenAnswer->duplicate($itemMapping, $subjectMapping, $correctAnswerOptions);
				if ($givenAnswerMapping === false) {
					$success = false;
				}
			}

			if ($success) {
				$this->commit();
			} else {
				$this->rollback();
				$examId = false;
			}
		}

		return $examId;
	}

/**
 * scores method
 *
 * @param int $id An exam id
 * @return void
 */
	public function scores($id) {
		$scoring = false;
		$conditions = array(
			'Exam.id' => $id,
			'Exam.user_id' => AuthComponent::user('id')
		);
		$contain = array('Item');
		$exam = $this->find('first', compact('conditions', 'contain'));
		if (!empty($exam)) {
			$fields = array(
				'GivenAnswer.subject_id',
				'SUM(GivenAnswer.score) as score_total',
				'Subject.value'
			);
			$conditions = array('GivenAnswer.item_id' => Hash::extract($exam, 'Item.{n}.id'));
			$contain = array('Subject');
			$group = array('GivenAnswer.subject_id');
			$scoring = $this->Item->GivenAnswer->find('all', compact('fields', 'conditions', 'contain', 'group'));
		}

		return $scoring;
	}

/**
 * missings method
 *
 * @param int $id An exam id
 * @return void
 */
	public function missings($id) {
		$missings = false;
		$conditions = array(
			'Exam.id' => $id,
			'Exam.user_id' => AuthComponent::user('id')
		);
		$contain = array('Item');
		$exam = $this->find('first', compact('conditions', 'contain'));
		if (!empty($exam)) {
			$conditions = array(
				'GivenAnswer.item_id' => Hash::extract($exam, 'Item.{n}.id'),
				'GivenAnswer.value' => null
			);
			$contain = array('Item', 'Subject');
			$missings = $this->Item->GivenAnswer->find('all', compact('conditions', 'contain'));

		}
		return $missings;
	}

/**
 * _executeAnalysis
 *
 * @param int $questionCount Number of questions
 * @param int $studentCount Number of students
 * @param int $maxAnswerOptionCount Maximum number of answer options
 * @param array $exam
 * @param array $givenAnswers
 * @param array $answerOptionCount Array of number of answer options per question
 * @return array
 */
	protected function _executeAnalysis($questionCount, $studentCount, $maxAnswerOptionCount, $exam, $givenAnswers, $answerOptionCount) {
		$script = array();
		$script[] = file_get_contents(APP . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
		$script[] = sprintf('nvragen = %d;', $questionCount);
		$script[] = sprintf('ndeel = %d;', $studentCount);

		$keyMatrix = array();
		foreach ($exam['Item'] as $i => $item) {
			foreach ($item['AnswerOption'] as $j => $answerOption) {
				if ($answerOption['is_correct']) {
					$keyMatrix[] = 1;
				} else {
					$keyMatrix[] = 0;
				}
			}
		}

		// Create the key matrix (with given dimensions) by filling it with a vector (by column)

		// > matrix(1:4, 2, 2, byrow = FALSE)
		//      [,1] [,2]
		// [1,]    1    3
		// [2,]    2    4

		$script[] = sprintf(
			'key = matrix( c( %s ), %d, %d, byrow = FALSE );',
			implode(',', $keyMatrix), $maxAnswerOptionCount, count($exam['Item'])
		);

		$inputAnswersMaxtrix = array();
		foreach ($givenAnswers as $i => $givenAnswersByStudent) {
			foreach ($givenAnswersByStudent as $j => $givenAnswer) {
				if (empty($givenAnswer)) {
					$givenAnswer = 0;
				}
				$inputAnswersMaxtrix[] = $givenAnswer;
			}
		}

		// Create the input_answers matrix (with given dimensions) by filling it with a vector (by row)

		// > matrix(1:4, 2, 2, byrow = TRUE)
		//      [,1] [,2]
		// [1,]    1    2
		// [2,]    3    4

		$script[] = sprintf(
			'input_answers = matrix( c( %s ), ndeel, nvragen, byrow = TRUE );',
			implode(',', $inputAnswersMaxtrix)
		);

		$numberAnsweroptionsVector = array();
		foreach ($answerOptionCount as $i => $count) {
			if (empty($count)) {
				$count = 0;
			}
			$numberAnsweroptionsVector[] = $count;
		}

		$script[] = sprintf('number_answeroptions = c( %s );', implode(',', $numberAnsweroptionsVector));

		$script[] = 'analyse( key, input_answers, number_answeroptions );';

		$script = implode("\n", $script);

		return Rserve::execute($script);
	}

}
