<?php
define('EXAM_UPLOAD_DIRECTORY', TMP . 'uploads' . DS);
define('EXAM_REPORT_DIRECTORY', ROOT . DS . 'data' . DS . 'reports' . DS);
App::uses('Rserve', 'Lib');
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
 *
 */
class Exam extends AppModel {

	public $actsAs = array('I18n');

	const UPLOAD_DIRECTORY = EXAM_UPLOAD_DIRECTORY;
	const REPORT_DIRECTORY = EXAM_REPORT_DIRECTORY;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'exam_format_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank',
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
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
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

	//The Associations below have been created with all possible keys, those that are not needed can be removed

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

	public function beforeValidate($options = array()) {
		if (!$this->exists() && empty($this->data[$this->alias]['user_id']) && $userId = AuthComponent::user('id')) {
			$this->data[$this->alias]['user_id'] = $userId;
		}
		return true;
	}

	public function add($data) {
		$result = false;

		if (!empty($data['Exam']['data_file']['error']) && $data['Exam']['data_file']['error'] == UPLOAD_ERR_NO_FILE) unset($data['Exam']['data_file']);
		if (!empty($data['Exam']['mapping_file']['error']) && $data['Exam']['mapping_file']['error'] == UPLOAD_ERR_NO_FILE) unset($data['Exam']['mapping_file']);

		$this->set($data);
		if ($this->validates()) {
			if (!empty($data['Exam']['data_file']['tmp_name'])) {
				$data['Exam']['data_filename'] = String::uuid();

				//TODO: check for copy failures
				rename($data['Exam']['data_file']['tmp_name'], Exam::UPLOAD_DIRECTORY . $data['Exam']['data_filename']);
				$data['Exam']['data_file']['tmp_name'] = Exam::UPLOAD_DIRECTORY . $data['Exam']['data_filename'];
			}
			if (!empty($data['Exam']['mapping_file']['tmp_name'])) {
				$data['Exam']['mapping_filename'] = String::uuid();

				//TODO: check for copy failures
				rename($data['Exam']['mapping_file']['tmp_name'], Exam::UPLOAD_DIRECTORY . $data['Exam']['mapping_filename']);
				$data['Exam']['mapping_file']['tmp_name'] = Exam::UPLOAD_DIRECTORY . $data['Exam']['mapping_filename'];
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
				if (!empty($data['Exam']['data_filename']) && file_exists(Exam::UPLOAD_DIRECTORY . $data['Exam']['data_filename'])) {
					unlink(Exam::UPLOAD_DIRECTORY . $data['Exam']['data_filename']);
				}
				if (!empty($data['Exam']['mapping_filename']) && file_exists(Exam::UPLOAD_DIRECTORY . $data['Exam']['mapping_filename'])) {
					unlink(Exam::UPLOAD_DIRECTORY . $data['Exam']['mapping_filename']);
				}
			}
		}

		return $result;
	}

	public function remove($id) {
		$result = false;
		$exam = $this->find(
			'first', array(
				'conditions' => array(
					'Exam.id' => $id,
					'Exam.user_id' => AuthComponent::user('id')
				),
				'contain' => array(
					'Child' => array(
						'conditions' => array('Child.deleted' => null)
					)
				)
			)
		);
		if (!empty($exam) && empty($exam['Child'])) {
			$result = $this->saveField('deleted', date('Y-m-d H:i:s'));
		}
		return $result;
	}

	private function __decodeLine($line, $firstLine = false) {
		if ($firstLine) {
			$this->__encoding = null;
			if (substr($line,0,3) == pack("CCC",0xef,0xbb,0xbf)) {
				$this->__encoding = 'UTF-8';
				$line = substr($line,3);
			} elseif (substr($line,0,2) == pack("CC",0xfe,0xff)) {
				$this->__encoding = 'UTF-16';
				$line = substr($line,2);
			} elseif (substr($line,0,2) == pack("CC",0xff,0xfe)) {
				$this->__encoding = 'UTF-16LE';
				$line = substr($line,2);
			}
		}

		$line = ltrim($line);

		if (!empty($this->__encoding)) {
			$line = mb_convert_encoding($line , 'UTF-8' , $this->__encoding);
		}

		return $line;
	}

	public function scheduleAnalyse($id) {
		$this->id = $id;
		if ($result = $this->saveField('exam_state_id', ExamState::WAITING_TO_ANALYSE)) {
			$data = array('Exam' => array('id' => $this->id));
			$QueuedTaskModel = ClassRegistry::init('QueuedTask');
			$result = $QueuedTaskModel->createJob('AnalyseExam', $data);
		}

		return $result;
	}

	public function analyse($id) {
		$exam = $this->find('first', array('conditions' => array('Exam.id' => $id)));
		if (!empty($exam)) {
			return $this->__analyse($exam);
		}
		return false;
	}

	private function __analyse($exam) {
		$result = true;

		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::ANALYSING);

		$exam = $this->find(
			'first', array(
				'conditions' => array(
					'Exam.id' => $exam['Exam']['id']
				),
				'contain' => array(
					'Item' => 'AnswerOption',
					'Subject' => 'GivenAnswer'
				)
			)
		);
		$maxAnswerOptionCount = $this->Item->find(
			'first', array(
				'fields' => array(
					'MAX(Item.answer_option_count) as answer_option_count'
				),
				'conditions' => array(
					'Item.exam_id' => $exam['Exam']['id']
				)
			)
		);
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

		$script = array();
		$script[] = sprintf('source("%s");', APP . DS . 'Lib' . DS . 'Rscripts' . DS . 'analyse.R');
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
			'key = matrix(c(%s), %d, %d, byrow = FALSE);',
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
			'input_answers = matrix(c(%s), ndeel, nvragen, byrow = TRUE);',
			implode(',', $inputAnswersMaxtrix)
		);

		$numberAnsweroptionsVector = array();
		foreach ($answerOptionCount as $i => $count) {
			if (empty($count)) {
				$count = 0;
			}
			$numberAnsweroptionsVector[] = $count;
		}

		$script[] = sprintf('number_answeroptions = c(%s);', implode(',', $numberAnsweroptionsVector));

		$script[] = 'analyse(key, input_answers, number_answeroptions);';

		$script = implode("\n", $script);

		$result = Rserve::execute($script);

		if ($result) {
			$cronbachsAlpha = $result[0];
			$maxAnswerOptionCount = $result[1];
			$averageScore = null;
			$standardDeviation = null;
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
					'average_score' => $averageScore,
					'standard_deviation' => $standardDeviation,
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

	public function scheduleReport($id) {
		$this->id = $id;
		if ($result = $this->saveField('exam_state_id', ExamState::WAITING_TO_GENERATE_REPORT)) {
			$data = array('Exam' => array('id' => $this->id));
			$QueuedTaskModel = ClassRegistry::init('QueuedTask');
			$result = $QueuedTaskModel->createJob('AnalysisToReport', $data);
		}
		return $result;
	}

	public function report($id) {
		$exam = $this->find('first', array('conditions' => array('Exam.id' => $id)));
		if (!empty($exam)) {
			return $this->__report($exam);
		}
		return false;
	}

	private function __report($exam) {
		$result = true;

		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::GENERATING_REPORT);

		$exam = $this->find(
			'first', array(
				'conditions' => array(
					'Exam.id' => $exam['Exam']['id']
				),
				'contain' => array(
					'Item' => 'AnswerOption',
					'Subject' => 'GivenAnswer'
				)
			)
		);

		// create temp file
		umask(0);
		if ($tempFile = tempnam(sys_get_temp_dir(), "report")) {
			chmod($tempFile, 0777);

			$answerOptionCount = Set::extract('/Item/answer_option_count', $exam);
			$correctAnswerCount = Set::extract('/Item/correct_answer_count', $exam);
			$correctAnswerPercentage = Set::extract('/Item/correct_answer_percentage', $exam);
			$correctAnswerIRC = Set::extract('/Item/correct_answer_irc', $exam);

			$script = array();
			$script[] = sprintf('source("%s");', APP . DS . 'Lib' . DS . 'Rscripts' . DS . 'report.R');
			$script[] = sprintf('number_students = %d;', count($exam['Subject']));
			$script[] = sprintf('number_answeroptions = c(%s);', implode(',', $answerOptionCount));
			$script[] = sprintf('max_number_answeroptions = %s;', $exam['Exam']['max_answer_option_count']);
			$script[] = sprintf('number_questions = %d;', count($exam['Item']));
			$script[] = sprintf('Cronbach = %s;', $exam['Exam']['cronbachs_alpha']);
			$script[] = sprintf('correct_frequency = c(%s);', implode(',', $correctAnswerCount));
			$script[] = sprintf('correct_percentage = c(%s);', implode(',', $correctAnswerPercentage));
			$script[] = sprintf('corrected_item_tot_cor = c(%s);', implode(',', $correctAnswerIRC));

			$script[] = 'frequency_answer_options = matrix(, max_number_answeroptions + 1, number_questions);';
			$script[] = 'percentage_answer_options = matrix(, max_number_answeroptions + 1, number_questions);';
			$script[] = 'corrected_item_tot_cor_answ_option = matrix(, max_number_answeroptions + 1, number_questions);';
			$script[] = sprintf('item_names = rep(NA, %d);', count($exam['Item']));

			foreach ($exam['Item'] as $i => $item) {
				$script[] = sprintf('frequency_answer_options[1, %d] = %s;', ($i + 1), $item['missing_answer_count']);
				$script[] = sprintf('percentage_answer_options[1, %d] = %s;', ($i + 1), $item['missing_answer_percentage']);
				$script[] = sprintf('corrected_item_tot_cor_answ_option[1, %d] = 0;', ($i + 1));
				$script[] = sprintf('item_names[%d] = %s;', ($i + 1), $item['value']);

				foreach ($item['AnswerOption'] as $j => $answerOption) {
					$script[] = sprintf(
						'frequency_answer_options[%d, %d] = %s;',
						($j + 2), ($i + 1), $answerOption['given_answer_count']
					);
					$script[] = sprintf(
						'percentage_answer_options[%d, %d] = %s;',
						($j + 2), ($i + 1), $answerOption['given_answer_percentage']
					);
					$script[] = sprintf(
						'corrected_item_tot_cor_answ_option[%d, %d] = %s;',
						($j + 2), ($i + 1), (empty($answerOption['given_answer_irc']) ? '0' : $answerOption['given_answer_irc'])
					);
				}
			}

			$script[] = 'input_correct = matrix(0, number_students, number_questions);';
			foreach ($exam['Subject'] as $i => $subject) {
				foreach ($subject['GivenAnswer'] as $j => $givenAnswer) {
					$script[] = sprintf(
						'input_correct[%d, %d] = %s;',
						($i + 1), ($j + 1), (empty($givenAnswer['score']) ? '0' : $givenAnswer['score'])
					);
				}
			}

			$script[] = 'key = matrix(0, max_number_answeroptions, number_questions);';
			foreach ($exam['Item'] as $i => $item) {
				foreach ($item['AnswerOption'] as $j => $answerOption) {
					if ($answerOption['is_correct']) {
						$script[] = sprintf('key[%d, %d] = 1;', ($j + 1), ($i + 1));
					}
				}
			}

			$script[] = sprintf(
				'make_pdf(' .
				'"%s", number_students, number_answeroptions, number_questions, Cronbach, frequency_answer_options, ' .
				'percentage_answer_options, input_correct, key, correct_frequency, correct_percentage, ' .
				'corrected_item_tot_cor, corrected_item_tot_cor_answ_option, "%s", item_names' .
				');',
				$tempFile, $exam['Exam']['name']
			);

			$script = implode("\n", $script);

			$result = Rserve::execute($script);

			if ($result && file_exists($tempFile)) {
				rename($tempFile, ROOT . DS . 'data' . DS . 'reports' . DS . $exam['Exam']['id'] . '.pdf');
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

	public function import($id) {
		$success = false;
		$exam = $this->find('first', array('conditions' => array('Exam.id' => $id)));
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

		if ($success) $this->scheduleAnalyse($id);
		return $success;
	}

	public function importBlackboard($exam) {
		$result = true;
		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::IMPORTING);

		$filename = Exam::UPLOAD_DIRECTORY . $exam['Exam']['data_filename'];

		ini_set('auto_detect_line_endings', true);
		if (($handle = fopen($filename, "r")) !== false) {
			for ($i = 0; !feof($handle); $i++) {
				$skipLine = false;
				$line = fgets($handle);
				$line = $this->__decodeLine($line, $i == 0);

				if ($i == 0) {
					// first line contains column headings
					$headings = str_getcsv($line,',','"','"');

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
						$exam = $this->find(
							'first', array(
								'conditions' => array(
									'Exam.id' => $exam['Exam']['id']
								),
								'contain' => array(
									'Item' => 'AnswerOption'
								)
							)
						);
					} else {
						break;
					}
					$data = array();
				} else {
					$values = str_getcsv($line,',','"','"');

					// check for empty last line
					if (empty($values[0])) break;

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
										$itemIds = Set::extract('/Item[value=' . $questionId . ']/id', $exam);
										if (!empty($itemIds[0])) {
											$value = null;
											$givenAnswer = $this->Item->GivenAnswer->find(
												'first', array(
													'conditions' => array(
														'GivenAnswer.item_id' => $itemIds[0],
														'GivenAnswer.content' => $values[$j + 1]
													)
												)
											);
											if (empty($givenAnswer)) {
												$answerOptionCount = $this->Item->GivenAnswer->find(
													'count', array(
														'conditions' => array(
															'GivenAnswer.item_id' => $itemIds[0]
														)
													)
												);
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
			fclose($handle);
		} else {
			$result = false;
		}

		if ($result) {
			//TODO: clean up database: remove content from givenAnswers

			$this->Item->GivenAnswer->updateAll(
				array('GivenAnswer.content' => null),
				array('GivenAnswer.item_id' => Set::extract('/Item/id', $exam))
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

			if (!empty($filename) && file_exists($filename)) unlink($filename);
			if (!empty($versionMappingFilename) && file_exists($versionMappingFilename)) unlink($versionMappingFilename);
		} else {
			$this->id = $exam['Exam']['id'];
			$this->saveField('exam_state_id', ($result?ExamState::IMPORTED:ExamState::IMPORT_FAILED));
		}

		return $result;
	}

	public function importQMP($exam) {
		$result = true;
		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::IMPORTING);

		$filename = Exam::UPLOAD_DIRECTORY . $exam['Exam']['data_filename'];

		ini_set('auto_detect_line_endings', true);
		if (($handle = fopen($filename, "r")) !== false) {
			for ($i = 0; !feof($handle); $i++) {
				$skipLine = false;
				$line = fgets($handle);
				$line = $this->__decodeLine($line, $i == 0);

				if ($i == 0) {
					// first line contains column headings
					$headings = str_getcsv($line,';','"','"');

					$data = array();
				} else {
					$values = str_getcsv($line,';','"','"');

					// check for empty last line
					if (empty($values[0])) break;

					if (($index = array_search('Deelnemersnaam', $headings)) !== false) {
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
							$questionIndices = array_keys($headings, 'Vraagbeschrijving');
							foreach ($questionIndices as $questionIndex) {
								$itemId = null;
								$question = null;
								$givenAnswer = null;
								$score = 0;
								$maximumScore = 0;
								if (!empty($values[$questionIndex])) $question = $values[$questionIndex];
								if (!empty($values[$questionIndex + 1]) || (isset($values[$questionIndex + 1]) && is_numeric($values[$questionIndex + 1]))) {
									$givenAnswer = $values[$questionIndex + 1];
									if (strlen($givenAnswer) > 1) $givenAnswer = substr($givenAnswer, 0, 1);
									$givenAnswer++;
								}
								if (!empty($values[$questionIndex + 2]) || (isset($values[$questionIndex + 2]) && is_numeric($values[$questionIndex + 2]))) {
									$score = $values[$questionIndex + 2];
								}
								if (!empty($values[$questionIndex + 3]) || (isset($values[$questionIndex + 3]) && is_numeric($values[$questionIndex + 3]))) {
									$maximumScore = $values[$questionIndex + 3];
								}
								if (!empty($question) && $givenAnswer != null) {
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
			fclose($handle);
		} else {
			$result = false;
		}

		if ($result) {
			//TODO: clean up database: remove content from givenAnswers

			$this->Item->GivenAnswer->updateAll(
				array('GivenAnswer.content' => null),
				array('GivenAnswer.item_id' => Set::extract('/Item/id', $exam))
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

			if (!empty($filename) && file_exists($filename)) unlink($filename);
			if (!empty($versionMappingFilename) && file_exists($versionMappingFilename)) unlink($versionMappingFilename);
		} else {
			$this->id = $exam['Exam']['id'];
			$this->saveField('exam_state_id', ($result?ExamState::IMPORTED:ExamState::IMPORT_FAILED));
		}

		return $result;
	}

	public function importTeleform($exam) {
		$versionMapping = null;
		$versionMappingFilename = null;
		$answerOptionCount = array();

		$this->id = $exam['Exam']['id'];
		$this->saveField('exam_state_id', ExamState::IMPORTING);

		$filename = Exam::UPLOAD_DIRECTORY . $exam['Exam']['data_filename'];
		if (!empty($exam['Exam']['mapping_filename'])) $versionMappingFilename = Exam::UPLOAD_DIRECTORY . $exam['Exam']['mapping_filename'];

		if (!empty($versionMappingFilename)) {
			ini_set('auto_detect_line_endings', true);
			if (($handle = fopen($versionMappingFilename, "r")) !== false) {
				for ($i = 0; !feof($handle); $i++) {
					$line = fgets($handle);
					$line = $this->__decodeLine($line, $i == 0);

					if ($i == 0) {
						$header = str_getcsv($line,';','"','"');

						$version1Index = array_search('Versie.1', $header);
						$version2Index = array_search('Versie.2', $header);
						$answerOptionCountIndex = array_search('Answer Option Count', $header);
					} else {
						$values = str_getcsv($line,';','"','"');
						if (count($values) <= 1) continue;
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
				$line = $this->__decodeLine($line, $i == 0);

				if ($i == 0) {
					// first line contains correct answers for first version
					$header = str_getcsv($line,',','"','"');

					$data = array();
					$count = count($header);
					for ($j = 2; $j < $count; $j++) {
						if ($header[$j] == 9) break;

						$secondVersionOrder = null;
						if (!empty($versionMapping[2][$j - 1])) $secondVersionOrder = $versionMapping[2][$j - 1];

						$item = array(
							'exam_id' => $exam['Exam']['id'],
							'order' => $j - 1,
							'second_version_order' => $secondVersionOrder,
							'value' => $j - 1
						);
						if (empty($answerOptionCount[$j - 1])) $item['answer_option_count'] = $exam['Exam']['answer_option_count'];
						else $item['answer_option_count'] = $answerOptionCount[$j - 1];

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
						$exam = $this->find(
							'first', array(
								'conditions' => array(
									'Exam.id' => $exam['Exam']['id']
								),
								'contain' => array(
									'Item' => 'AnswerOption'
								)
							)
						);
					} else {
						break;
					}
					$data = array();
				} elseif ($i == 1) {
					// second line contains correct answers for second version
				} else {
					$values = str_getcsv($line,',','"','"');

					// only add versions 1 and 2
					if (!empty($values[1]) && in_array($values[1], array(1,2))) {
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
							if ($value === 0 || $value == 9) $value = null;

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

			if (!empty($filename) && file_exists($filename)) unlink($filename);
			if (!empty($versionMappingFilename) && file_exists($versionMappingFilename)) unlink($versionMappingFilename);
		} else {
			$this->id = $exam['Exam']['id'];
			$this->saveField('exam_state_id', ($result?ExamState::IMPORTED:ExamState::IMPORT_FAILED));
		}

		return $result;
	}

	public function stevie($id, $offset) {
		$exam = $this->find(
			'first', array(
				'conditions' => array(
					'Exam.id' => $id,
					'Exam.user_id' => AuthComponent::user('id')
				),
				'contain' => array(
					'Item' => 'AnswerOption'
				)
			)
		);

		if (!empty($exam['Item'])) {
			foreach ($exam['Item'] as $i => $item) {
				//if ($offset == ($i + 1))
				{
					$exam['Item'][$i] = $this->Item->stevie($item, $exam['Exam']['answer_option_count']);
				}
			}
		}

		return $exam;
	}

	public function scheduleReanalyse($data) {
		$result = true;
		$this->validator()->remove('data_file');
		$this->validator()->remove('answer_option_count');
		if (!$this->saveAll($data, array('validate' => 'only'))) $result = false;

		if ($result) {
			$exam = $this->find(
				'first', array(
					'conditions' => array(
						'Exam.id' => $data['Exam']['parent_id'],
						'Exam.user_id' => AuthComponent::user('id')
					)
				)
			);
			if (empty($exam)) $result = false;
		}

		if ($result) {
			$QueuedTaskModel = ClassRegistry::init('QueuedTask');
			$result = $QueuedTaskModel->createJob('ReanalyseExam', $data);
		}
		return $result;
	}

	public function reanalyse($data) {
		$result = true;
		if ($id = $this->__duplicate($data)) {
			$result = $this->scheduleAnalyse($id);
		} else {
			$result = false;
		}
		return $result;
	}

	private function __duplicate($postData) {
		$examId = false;
		$parentExam = $this->find(
			'first', array(
				'conditions' => array(
					'Exam.id' => $postData['Exam']['parent_id']
				),
				'contain' => array(
					'Item' => array(
						'conditions' => array(
							'Item.id' => Set::extract('/Item[include=1]/id', $postData)
						),
						'AnswerOption',
						'GivenAnswer' => 'Subject'
					),
					'Subject'
				)
			)
		);
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

			if (!empty($parentExam['Item'])) {
				foreach ($parentExam['Item'] as $i => $item) {
					$data['Item'][$i] = array(
						'exam_id' => $examId,
						'order' => $item['order'],
						'second_version_order' => $item['second_version_order'],
						'value' => $item['value'],
						'answer_option_count' => $item['answer_option_count']
					);

					if (!empty($item['AnswerOption'])) {
						$answerOptions = Set::extract('/Item[id=' . $item['id'] . ']/AnswerOption', $postData);
						foreach ($item['AnswerOption'] as $j => $answerOption) {
							$data['Item'][$i]['AnswerOption'][] = array(
								'order' => $answerOption['order'],
								'value' => $answerOption['value'],
								'is_correct' => $answerOptions[$j]['AnswerOption']['is_correct']
							);
						}
					}
				}
			}

			if (!empty($parentExam['Subject'])) {
				foreach ($parentExam['Subject'] as $i => $subject) {
					$data['Subject'][$i] = array(
						'value' => $subject['value'],
						'is_second_version' => $subject['is_second_version']
					);
				}
			}

			$this->validator()->remove('data_file', 'extension');
			$this->create();
			if ($this->saveAll($data, array('deep' => true))) {
				$examId = $this->id;
			}
			if ($examId) {
				$childExam = $this->find(
					'first', array(
						'conditions' => array(
							'Exam.id' => $examId
						),
						'contain' => 'Item'
					)
				);
				if (!empty($parentExam['Item'])) {
					$data = array();
					foreach ($parentExam['Item'] as $i => $item) {
						if (!empty($item['GivenAnswer'])) {
							foreach ($item['GivenAnswer'] as $givenAnswer) {
								$subject = $this->Subject->find(
									'first', array(
										'conditions' => array(
											'Subject.exam_id' => $examId,
											'Subject.value' => $givenAnswer['Subject']['value']
										)
									)
								);
								$data[] = array(
									'item_id' => $childExam['Item'][$i]['id'],
									'value' => $givenAnswer['value'],
									'score' => $givenAnswer['score'],
									'subject_id' => $subject['Subject']['id']
								);
							}
						}
					}
					if (!$this->Item->GivenAnswer->saveAll($data)) $examId = false;
				}
			}
		}
		return $examId;
	}

	public function scores($id) {
		$scoring = false;
		$exam = $this->find(
			'first', array(
				'conditions' => array(
					'Exam.id' => $id,
					'Exam.user_id' => AuthComponent::user('id')
				),
				'contain' => array(
					'Item'
				)
			)
		);
		if (!empty($exam)) {
			$scoring = $this->Item->GivenAnswer->find(
				'all', array(
					'conditions' => array(
						'GivenAnswer.item_id' => Set::extract('/Item/id', $exam)
					),
					'contain' => 'Subject',
					'fields' => array(
						'GivenAnswer.subject_id',
						'SUM(GivenAnswer.score) as score_total',
						'Subject.value'
					),
					'group' => 'GivenAnswer.subject_id',
				)
			);
		}

		return $scoring;
	}

	public function missings($id) {
		$missings = false;
		$exam = $this->find(
			'first', array(
				'conditions' => array(
					'Exam.id' => $id,
					'Exam.user_id' => AuthComponent::user('id')
				),
				'contain' => array(
					'Item'
				)
			)
		);
		if (!empty($exam)) {
			$missings = $this->Item->GivenAnswer->find(
				'all', array(
					'conditions' => array(
						'GivenAnswer.item_id' => Set::extract('/Item/id', $exam),
						'GivenAnswer.value' => null
					),
					'contain' => array(
						'Item',
						'Subject'
					)
				)
			);

		}
		return $missings;
	}
}
